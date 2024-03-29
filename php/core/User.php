<?php


// ORM to MySQL
class User
{
    // Like a POJO, But I guess it's a POPHPO;
    private $id;
    private $username;
    private $apikey;
    private $email;
    public function __construct()
    {
        $this->apikey = "0xDEAD"; // not all people will have an API key, so this value indicates that someone doesn't have it
    }

    public function setEmail($mail)
    {
        $this->email = $mail;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setID($userid)
    {
        $this->id = $userid;
    }

    public function getID(){return $this->id;}

    public function setUsername($name)
    {
        $this->username = $name;
    }

    public function getUsername(){return $this->username;}


    public function getAPIKey()
    {
        return $this->apikey;
    }

    public function setAPIKey($key)
    {
        $this->apikey = $key;
    }

}