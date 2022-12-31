<?php

class Homepage {

    private $noteText;
    private $noteSubject;
    private $loginRejectionMessage;
    private $connection;

    public function __construct($conn) {
        $this->connection = $conn;
    }

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

    public function checkLoginValidation() {
        $enterEmailAddress = $_POST['email'];
        $enteredPassword = $_POST['pw'];

        $stmt = $this->connection->prepare("SELECT * FROM users WHERE email=? AND password=?");
        $stmt->bind_param("ss", $enterEmailAddress, $enteredPassword);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['logged_in'] = $enterEmailAddress;
            while($row = $result->fetch_assoc()) {
                $this->noteText = $row['text1'];
                $this->noteSubject = $row['subject1'];
            }
        } else {
            $this->loginRejectionMessage = "<center><br><b>Invalid username or password.</b></center>";
        }
    }

    public function loadNotes() {
        $sessionEmailAddress = $_SESSION['logged_in'];
        $selectedNote = $_POST['notes'];

        $stmt = $this->connection->prepare("SELECT * FROM users WHERE email=?");
        $stmt->bind_param("s", $sessionEmailAddress);
        $stmt->execute();
        $result = $stmt->get_result();

        $text = "text$selectedNote";
        $sub = "subject$selectedNote";

        $_SESSION['note_index'] = $text;
        $_SESSION['subject_index'] = $sub;

        if ($result->num_rows > 0) {
            while($info = $result->fetch_assoc()) {
                $this->noteText = $info[$text];
                $this->noteSubject = $info[$sub];
            }
        }
    }

    public function saveButton() {
        $noteText = $_POST['textArea'];
        $emailAddress = $_SESSION['logged_in'];
        $noteSubject = $_POST['subject'];

        $nindex = $_SESSION['note_index'];
        $sindex= $_SESSION['subject_index'];

        $stmt = $this->connection->prepare("UPDATE users SET $nindex=?, $sindex=? WHERE email=?");
        $stmt->bind_param("sss", $noteText, $noteSubject, $emailAddress);
        $stmt->execute();

        $this->noteText = $noteText;
        $this->noteSubject = $noteSubject;
    }

    private function closeConnection() {
        $this->connection->close();
    }

    public function getLoginRejectionMessage() {
        return $this->loginRejectionMessage;
    }

    public function getNoteSubject() {
        return $this->noteSubject;
    }

    public function getNoteText() {
        return $this->noteText;
    }

}