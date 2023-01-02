<?php

/**
 * @author Hakeem
 */
class Notes {

    /**
     * This will hold the end user's noted text.
     * @var String The saved noted text.
     */
    private String $noteText;

    /**
     * This will hold the end user's subject.
     * @var String The subject.
     */
    private String $noteSubject;

    /**
     * Creates a new object that takes two parameters.
     * @param $noteText The noted text.
     * @param $noteSubject The noted subject.
     */
    public function __construct($noteText, $noteSubject) {
        $this->setNoteText($noteText);
        $this->setNoteSubject($noteSubject);
    }

    /**
     * Gets the end user's noted text.
     * @return string The noted text.
     */
    public function getNoteText(): string {
        return $this->noteText;
    }

    /**
     * Gets the end user's noted subject.
     * @return string The noted subject.
     */
    public function getNoteSubject(): string {
        return $this->noteSubject;
    }

    /**
     * This method will handle the allocation of the noted text.
     * @param $noteText The noted text.
     * @return void Sets the noteText field to whatever has been inputted into the parameter.
     */
    public function setNoteText($noteText) {
        $this->noteText = $noteText;
    }

    /**
     * This method will handle the allocation of the noted subject.
     * @param $noteSubject The subject text.
     * @return void Sets the noteSubject field to whatever has been inputted into the parameter.
     */
    public function setNoteSubject($noteSubject) {
        $this->noteSubject = $noteSubject;
    }

}