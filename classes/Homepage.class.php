<?php

/**
 * @author Hakeem
 */
class Homepage {

    /**
     * The login rejection message that will show to the end user if they were to login with incorrect credentials.
     *
     * @var This will return the rejection message.
     */
    private $loginRejectionMessage;

    /**
     * This creates the connection object to send and receive information to the database.
     *
     * @var The connection object.
     */
    private $connection;

    /**
     * Creates a new constructor for when the class has a new object created.
     *
     * @param $connection The connection object.
     */
    public function __construct($connection) {
        $this->connection = $connection;
    }

    /**
     * This will check the incoming request and will divert to the correct method.
     *
     * @return void This method starts a session, checks conditions and closes the connection object.
     */
    public function checkRequest() {
        session_start();
        if (isset($_POST['submit'])) {
            $this->checkLoginValidation();
        } else if (isset($_POST['loadNotes'])) {
            $this->loadNotes();
        } else if (isset($_POST['saveButton'])) {
            $this->saveButton();
        }
        $this->closeConnection();
    }

    /**
     * This method will check the login credentials the end user has inputted and validate to what is in the database.
     *
     * @return void It will check the database to see if the user has an account with  us, if not a rejection message will be allocated.
     */
    private function checkLoginValidation() {
        $enterEmailAddress = $_POST['email'];
        $enteredPassword = $_POST['pw'];

        $selectUsers = $this->connection->prepare("SELECT * FROM users WHERE email_address=? AND password=?");
        $selectUsers->bind_param("ss", $enterEmailAddress, $enteredPassword);
        $selectUsers->execute();
        $result = $selectUsers->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['note_index'] = "text1";
            $_SESSION['subject_index'] = "subject1";
            $_SESSION['logged_in'] = $enterEmailAddress;

            $selectNotes = $this->connection->prepare("SELECT * FROM notes WHERE email_address=?");
            $selectNotes->bind_param("s", $enterEmailAddress);
            $selectNotes->execute();
            $results = $selectNotes->get_result();

            while($row = $results->fetch_assoc()) {
                $_SESSION['note_text'] = $row['text1'];
                $_SESSION['note_subject'] = $row['subject1'];
            }
        } else {
            $this->setLoginRejectionMessage("<center><br><b>Invalid username or password.</b></center>");
        }
    }

    /**
     * This method will load the notes that the end user has stored historically.
     *
     * @return void Grabs the information from the database and returns the note text and subject.
     */
    private function loadNotes() {
        $sessionEmailAddress = $_SESSION['logged_in'];
        $selectedNote = $_POST['notes'];

        $sqlStatement = $this->connection->prepare("SELECT * FROM notes WHERE email_address=?");
        $sqlStatement->bind_param("s", $sessionEmailAddress);
        $sqlStatement->execute();
        $result = $sqlStatement->get_result();

        $text = "text$selectedNote";
        $sub = "subject$selectedNote";

        $_SESSION['note_index'] = $text;
        $_SESSION['subject_index'] = $sub;

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $_SESSION['note_text'] = $row[$text];
                $_SESSION['note_subject'] = $row[$sub];
            }
        }
    }

    /**
     * This method will save the new content that the end user has inputted into the subject and notes field.
     *
     * @return void This method will update the information in the database.
     */
    private function saveButton() {
        $noteText = $_POST['textArea'];
        $emailAddress = $_SESSION['logged_in'];
        $noteSubject = $_POST['subject'];

        $noteIndex = $_SESSION['note_index'];
        $subjectIndex= $_SESSION['subject_index'];

        $stmt = $this->connection->prepare("UPDATE notes SET $noteIndex=?, $subjectIndex=? WHERE email_address=?");
        $stmt->bind_param("sss", $noteText, $noteSubject, $emailAddress);
        $stmt->execute();

        $_SESSION['note_text'] = $noteText;
        $_SESSION['note_subject'] = $noteSubject;
    }

    /**
     * This method will close the connection object.
     *
     * @return void This will close the connection object.
     */
    private function closeConnection() {
        $this->connection->close();
    }

    /**
     * This will grab the login rejection message and output this to the main page.
     *
     * @return String The allocated login rejection message or could return as null as login was successful.
     */
    public function getLoginRejectionMessage() {
        return $this->loginRejectionMessage;
    }

    /**
     * This will set the login rejection message if the end user was to place incorrect credentials during the login process.
     *
     * @param $loginRejectionMessage The allocated rejection message.
     *
     * @return void Sets the allocated rejection message.
     */
    public function setLoginRejectionMessage($loginRejectionMessage) {
        $this->loginRejectionMessage = $loginRejectionMessage;
    }

}