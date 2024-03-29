<?php

// ORM
class Note {

    private $id;
    private $title;
    private $text;
    private $colour;
    private $userID;


    public function __construct()
    {
        $this->sharedusers = array();
        $this->opened = false;
    }

    public function getID(){return $this->id;}
    public function getTitle(){return $this->title;}
    public function getText(){return $this->text;}
    public function getColour(){return $this->colour;}
    public function getUserID(){return $this->userID;}


    public function setID($noteid){$this->id = $noteid;}
    public function setTitle($t){$this->title = $t;}
    public function setText($t){$this->text=$t;}
    public function setColour($c){$this->colour = $c;}
    public function setUserID($id){$this->userID = $id;}

}