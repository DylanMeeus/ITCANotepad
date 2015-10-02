<?php
/**
 * Created by PhpStorm.
 * User: Dylan
 * Date: 10/09/2015
 * Time: 4:45
 */

class ApiDatabase {

    private $servername = "it-ca.net";
    private $username = "itca_global";
    private $password = "xgkzl9Af!pLv";
    private $con;


    public function __construct(){

    }

    private function closeConnection()
    {
        $this->con = null;
    }

    private function openConnection()
    {
        try
        {
            $this->con = new PDO("mysql:host=" . $this->servername . ";dbname=itca_notedb", $this->username, $this->password);
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
}




























