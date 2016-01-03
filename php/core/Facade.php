<?php


require_once "php/crypto/ICipher.php";
require_once "php/crypto/PolyalphabeticCipher.php";
require_once "php/db/OnlineDB.php";
require_once "php/db/IDatabase.php";
require_once "php/factories/DBFactory.php";
require_once "php/factories/CipherFactory.php";
class Facade
{
    private $database;
    private $dbFactory;
    private $cipher;
    public function __construct()
    {
        $this->dbFactory = new DBFactory();
        $this->database = $this->dbFactory->getDatabase();
        $this->cipherFactory = new CipherFactory();
        $this->cipher = $this->cipherFactory->getPolyalphabeticCipher();
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
    public function getSharedNotes($userID)
    {
        return $this->database->getSharedNotes($userID);
    }
    public function deleteSharedUser($userID, $noteID){
        $this->database->deleteSharedUser($userID, $noteID);
    }

    public function getSharedNoteDetails($noteID)
    {
        return $this->database->getSharedNoteDetails($noteID);
    }

    public function getUserFromUsername($username){
        return $this->database->getUserFromUsername($username);
    }

    public function openSharedNote($noteID){
        $this->database->openSharedNote($noteID);
    }

    public function closeSharedNote($noteID){
        $this->database->closeSharedNote($noteID);
    }

    public function deleteSharedNote($noteID){
        $this->database->deleteSharedNote($noteID);
    }
    public function addSharedUsers($noteID, $users, $rightIDList){
        $this->database->addSharedUsers($noteID, $users, $rightIDList);
    }

    public function addSharedNote($userID, $users, $title, $rightIDList)
    {
        return $this->database->addSharedNote($userID, $users, $title, $rightIDList);
    }

    public function getUsers(){
        return $this->database->getUsers();
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

    public function isUniqueNotetitleForUser($userID, $title){

        return $this->database->isUniqueNoteTitle($userID, $title);
    }

    public function makeShared($noteID, $userID){
        return $this->database->makeShared($noteID, $userID);
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
        return $this->database->resetPassword($this->encrypt($password),$recoveryString);
    }

    public function cipher($message)
    {
        $this->cipher->cipher($message);
    }

    /* Leave private functions at the bottom */
    private function encrypt($inputtext)
    {
        return sha1($inputtext);
    }

    /**
     * Returns a random string based on the md5 hash of microtime and random.
     * @return string
     */
    private function generateRandomString()
    {
        return  md5(microtime().rand());
    }


}

?>