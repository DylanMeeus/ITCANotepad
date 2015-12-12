<?php

require_once "php/db/OnlineDB.php";
require_once "php/db/IDatabase.php";
require_once "php/factories/DBFactory.php";
class Facade
{
    private $database;
    private $dbFactory;
    public function __construct()
    {
        $this->dbFactory = new DBFactory();
        $this->database = $this->dbFactory->getDatabase();
    }

    public function login($username, $password)
    {
        $encryptedPass = $this->encrypt($password);
        return $this->database->login($username, $encryptedPass);
    }

    public function getNotes($userID)
    {
        return $this->database->getNotes($userID);
    }

    public function getNoteDetails($noteID)
    {
        return $this->database->getNoteDetails($noteID);
    }

    public function updateNote($noteID, $noteTitle, $noteText, $colour)
    {
        $this->database->updateNote($noteID, $noteTitle, $noteText, $colour);
    }

    public function deleteNote($noteID)
    {
        $this->database->deleteNote($noteID);
    }

    public function addNote($userID, $noteTitle)
    {
        return $this->database->addNote($userID, $noteTitle);
    }

    public function register($username, $password, $mail) // return instance of new user - perform some null checks in servlet though.
    {
        $encryptedPass = $this->encrypt($password);
        return $this->database->register($username, $encryptedPass, $mail);
    }

    public function savelink($noteID, $linkurl, $linkname)
    {
        // the linkURL might not contain "http://" so we should check for that.
        $this->database->saveLink($noteID, $linkurl, $linkname);
    }

    public function getLinks($noteID)
    {
        return $this->database->getLinks($noteID);
    }

    public function deleteLink($linkID)
    {
        $this->database->deleteLink($linkID);
    }

    public function changePassword($userID, $newpassword)
    {
        $hashedPW = $this->encrypt($newpassword);
        $this->database->changepassword($userID, $hashedPW);
    }

    public function verifyPassword($userID, $password) // verify that the password belongs to the currently active user. (Used to check the old password value under 'Account'
    {
        return $this->database->verifyPassword($userID, $this->encrypt($password));
    }

    public function getUsersAPIKey($userID)
    {
        return $this->database->getUsersAPIKey($userID);
    }

    public function generateAPIKey($userID)
    {
        $key = md5(microtime().rand()); // We will perform some collision checking, but collisions are EXTREMELY rare?
        while(!$this->database->addAPIKey($userID, $key))
        {
            // keep retrying till it works.
            $key = $this->generateRandomString();// We will perform some collision checking, but collisions are EXTREMELY rare?
        }
        // When we got here - we actually KNOW that the key got set correctly. (Otherwise, we'd be stuck in a while loop)
        // So we can safely return the key
        return $key; // This we do so we can update the session's stored user!
    }

    public function isUniqueUsername($username)
    {
        return $this->database->isUniqueUsername($username);
    }

    /*
     * Returns the recoveryString if the mail was found. False otherwise.
     */
    public function startPasswordRecovery($mail)
    {
        // TODO: check for a collision here.
        $recoveryString = $this->generateRandomString();
        return $this->database->createPasswordRecovery($mail, $recoveryString);
    }

    /*
     * Returns true if the password could successfully be recovered.
     */
    public function resetPassword($password,$recoveryString)
    {
        return $this->database->resetPassword($password,$recoveryString);
    }

    /* Leave private functions at the bottom */
    private function encrypt($inputtext)
    {
        return sha1($inputtext);
    }

    private function generateRandomString()
    {
        // we can just return another MD5 I think.
        return  md5(microtime().rand());
    }


}

?>