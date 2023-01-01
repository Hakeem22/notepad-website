<?php

class Homepage {

    private $noteText;
    private $noteSubject;
    private $loginRejectionMessage;
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
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

    private function checkLoginValidation() {
        $enterEmailAddress = $_POST['email'];
        $enteredPassword = $_POST['pw'];

        $stmt = $this->connection->prepare("SELECT * FROM users WHERE email=? AND password=?");
        $stmt->bind_param("ss", $enterEmailAddress, $enteredPassword);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['logged_in'] = $enterEmailAddress;
            while($row = $result->fetch_assoc()) {
                $this->setNoteText($row['text1']);
                $this->setNoteSubject($row['subject1']);
            }
        } else {
            $this->setLoginRejectionMessage("<center><br><b>Invalid username or password.</b></center>");
        }
    }

    private function loadNotes() {
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
                $this->setNoteText($info[$text]);
                $this->setNoteSubject($info[$sub]);
            }
        }
    }

    private function saveButton() {
        $noteText = $_POST['textArea'];
        $emailAddress = $_SESSION['logged_in'];
        $noteSubject = $_POST['subject'];

        $nindex = $_SESSION['note_index'];
        $sindex= $_SESSION['subject_index'];

        $stmt = $this->connection->prepare("UPDATE users SET $nindex=?, $sindex=? WHERE email=?");
        $stmt->bind_param("sss", $noteText, $noteSubject, $emailAddress);
        $stmt->execute();

        $this->setNoteText($noteText);
        $this->setNoteSubject($noteSubject);
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

    public function setNoteText($noteText) {
        $this->noteText = $noteText;
    }

    public function setNoteSubject($noteSubject) {
        $this->noteSubject = $noteSubject;
    }

    public function setLoginRejectionMessage($loginRejectionMessage) {
        $this->loginRejectionMessage = $loginRejectionMessage;
    }

}