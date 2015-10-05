<?php


class Link {

    private $url;
    private $name;
    private $linkID;

    public function __construct()
    {

    }

    public function setID($id)
    {
        $this->linkID = $id;
    }

    public function getID()
    {
        return $this->linkID;
    }

    public function setUrl($url){
        $this->url = $url;
    }

    public function setName($name){
        $this->name=$name;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getName()
    {
        return $this->name;
    }

}