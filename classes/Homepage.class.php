<?php
include("Notes.class.php");

/**
 * @author Hakeem
 */
class Homepage {

    /**
     * This will hold the Notes object
     * @var Notes The notes object.
     */
    private Notes $notes;

    /**
     * The login rejection message that will show to the end user if they were to login with incorrect credentials.
     * @var This will return the rejection message.
     */
    private $loginRejectionMessage;

    /**
     * This creates the connection object to send and receive information to the database.
     * @var The connection object.
     */
    private $connection;

    /**
     * Creates a new constructor for when the class has a new object created.
     * @param $connection The connection object.
     */
    public function __construct($connection) {
        $this->connection = $connection;
    }

    /**
     * This will check the incoming request and will divert to the correct method.
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
     * @return void It will check the database to see if the user has an account with  us, if not a rejection message will be allocated.
     */
    private function checkLoginValidation() {
        $enterEmailAddress = $_POST['email'];
        $enteredPassword = $_POST['pw'];

        $stmt = $this->connection->prepare("SELECT * FROM users WHERE email_address=? AND password=?");
        $stmt->bind_param("ss", $enterEmailAddress, $enteredPassword);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['note_index'] = "text1";
            $_SESSION['subject_index'] = "subject1";
            $_SESSION['logged_in'] = $enterEmailAddress;
            while($row = $result->fetch_assoc()) {
                $this->setNotes(new Notes($row['text1'], $row['subject1']));
            }
        } else {
            $this->setLoginRejectionMessage("<center><br><b>Invalid username or password.</b></center>");
        }
    }

    /**
     * This method will load the notes that the end user has stored historically.
     * @return void Grabs the information from the database and returns the note text and subject.
     */
    private function loadNotes() {
        $sessionEmailAddress = $_SESSION['logged_in'];
        $selectedNote = $_POST['notes'];

        $stmt = $this->connection->prepare("SELECT * FROM users WHERE email_address=?");
        $stmt->bind_param("s", $sessionEmailAddress);
        $stmt->execute();
        $result = $stmt->get_result();

        $text = "text$selectedNote";
        $sub = "subject$selectedNote";

        $_SESSION['note_index'] = $text;
        $_SESSION['subject_index'] = $sub;

        if ($result->num_rows > 0) {
            while($info = $result->fetch_assoc()) {
                $this->setNotes(new Notes($info[$text], $info[$sub]));
            }
        }
    }

    /**
     * This method will save the new content that the end user has inputted into the subject and notes field.
     * @return void This method will update the information in the database.
     */
    private function saveButton() {
        $noteText = $_POST['textArea'];
        $emailAddress = $_SESSION['logged_in'];
        $noteSubject = $_POST['subject'];

        $nindex = $_SESSION['note_index'];
        $sindex= $_SESSION['subject_index'];

        $stmt = $this->connection->prepare("UPDATE users SET $nindex=?, $sindex=? WHERE email_address=?");
        $stmt->bind_param("sss", $noteText, $noteSubject, $emailAddress);
        $stmt->execute();

        $this->setNotes(new Notes($noteText, $noteSubject));
    }

    /**
     * This method will close the connection object.
     * @return void This will close the connection object.
     */
    private function closeConnection() {
        $this->connection->close();
    }

    /**
     * This will grab the login rejection message and output this to the main page.
     * @return String The allocated login rejection message or could return as null as login was successful.
     */
    public function getLoginRejectionMessage() {
        return $this->loginRejectionMessage;
    }

    /**
     * This will set the login rejection message if the end user was to place incorrect credentials during the login process.
     * @param $loginRejectionMessage The allocated rejection message.
     * @return void Sets the allocated rejection message.
     */
    public function setLoginRejectionMessage($loginRejectionMessage) {
        $this->loginRejectionMessage = $loginRejectionMessage;
    }

    /**
     * This will grab the Notes object.
     * @return Notes The notes object or this could return as null if nothing has been allocated..
     */
    public function getNotes()  {
        return $this->notes;
    }

    /**
     * This will set a Notes object.
     * @param Notes $notes The notes object.
     * @return void Sets the note object.
     */
    public function setNotes(Notes $notes) {
        $this->notes = $notes;
    }

}