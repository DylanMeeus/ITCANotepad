<?php



interface IDatabase
{
    public function login($username,$password);
    public function getNotes($userID);
    public function getNoteDetails($noteID);
    public function addNote($userID, $title); // returns an instance of the new note!
    public function getNoteText($userID, $title);
    public function getUserFromUsername($username);
    public function addSharedNote($userID, $users, $title, $rightIDList);
    public function addSharedUsers($noteID, $users, $rightIDList);
    public function deleteSharedUser($userID, $noteID);
    public function deleteSharedNote($noteID);
    public function isUniqueNoteTitle($userID, $title);
    public function makeShared($noteID, $userID);
    public function getSharedNotes($userID);
    public function getSharedNoteDetails($noteID);
    public function getUserDetails($userID);
    public function deleteNote($noteID);
    public function updateNote($noteID, $noteTitle, $noteText, $colour);
    public function register($username,$password, $mail);
    public function getLinks($noteID);
    public function saveLink($noteID, $linkurl, $linkname);
    public function deleteLink($linkID);
    public function changePassword($userID, $newpassword);
    public function verifyPassword($userID, $password);
    public function resetPassword($password,$recoveryString); // We will only reset it if the recovery string was present in the database. The ID we can filter out of the recoveryString.
    public function addAPIKey($userID, $key);
    public function getUsersAPIKey($userID);
    public function isUniqueUsername($username);
    public function createPasswordRecovery($mail, $recoveryString); // Generates the unique ID used to recover somebodies password.
}
?>