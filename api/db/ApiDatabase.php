<?php

require_once "db/IApiDatabase.php";
class ApiDatabase implements IApiDatabase{

    private $servername = "it-ca.net";
    private $username = "itca_Insanity";
    private $password = 't&Musk$StEJ[';
    private $con;


    public function __construct()
    {

    }

    private function closeConnection()
    {
        $this->con = null;
    }

    private function openConnection()
    {
        try
        {
            $this->con = new PDO("mysql:host=" . $this->servername . ";dbname=itca_ghostnotes", $this->username, $this->password);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $ex)
        {
            echo "Connection failed " . $ex->getMessage();
        }
    }


    // returns either null, or the ID associated with the key
    public function authenticateKey($key){
        $this->openConnection();


        $sql = "select users.id from apikeys inner join users on apikeys.id = users.apikey where apikeys.apikey = ?";
        $statement = $this->con->prepare($sql);
        $statement->bindParam(1,$key);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $statement->fetchAll();
        $id = -1;
        foreach($result as $row){
            // normally only one.
            $id = $row["id"];
        }

        $this->closeConnection();
        return $id;
    }

    public function createNote($title,$text,$userID){

        $this->openConnection();

        $sql = "insert into notes(title,notetext,userID) values (?,?,?)";
        $statement = $this->con->prepare($sql);
        $statement->bindParam(1,$title);
        $statement->bindParam(2,$text);
        $statement->bindParam(3,$userID);
        $statement->execute();
        $this->closeConnection();
    }

    public  function getUserNotes($userID)
    {
        $this->openConnection();

        $sql = "select * from notes where userID = ?";
        $statement = $this->con->prepare($sql);
        $statement->bindParam(1, $userID);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $statement->fetchAll();

        $notes = array();
        foreach ($result as $row)
        {
            $note = new Note();
            $note->setID($row['noteID']);
            $note->setTitle($row['title']);
            $note->setText($row['notetext']);
            $note->setColour($row['colour']);
            array_push($notes, $note);
        }
        $this->closeConnection();
        return $notes;
    }
}




























