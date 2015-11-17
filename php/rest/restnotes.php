<?php

require_once "core/Facade.php";

class  NoteRestService{

    private $facade;



    public function __construct()
    {
        $this->facade = new Facade();
    }

    public function getNoteById($id){
        $noteData = $this->facade->getNoteDetails(($id));
        //array to parse all data in JSON
        $note = array("note_title" => $noteData->getTitle(), "note_text" => $noteData->getText(), "note_id" => $noteData->getID(),
            "note_userid" => $noteData->getUserID(), "note_colour" => $noteData->getColour());

        return json_encode($note);
    }

    public function getNotes($userId){
        $userNotes = $this->facade->getNotes($userId);
        $notes  = array();
        foreach($userNotes as $note){
           $notes[] = array("note_title" => $note->getTitle(), "note_text" => $note->getText(), "note_id" => $note->getID(),
               "note_userid" => $note->getUserID(), "note_colour" => $note->getColour());
        }
        return json_encode($notes);
    }

    public function addNote($note){
       // $newNote = new Note();

        $noteData = json_decode($note, true);
        /*$newNote->setText($noteData["note_text"]);
        $newNote->setID(($noteData["note_id"]));
        $newNote->setTitle($noteData["note_title"]);
        $newNote->setColour($noteData["note_colour"]);
        $newNote->setUserID($noteData["note_userid"]);*/

        return $this->facade->addNote($noteData["note_userid"], $noteData["note_title"]);
    }

    public function deleteNote($note){
        $noteData = json_decode($note, true);
        $this->facade->deleteNote($noteData["note_id"]);
    }

}
