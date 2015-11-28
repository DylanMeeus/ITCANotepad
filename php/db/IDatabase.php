<?php



interface IDatabase
{
    public function login($username,$password);
    public function getNotes($userID);
    public function getNoteDetails($noteID);
    public function addNote($userID, $title); // returns an instance of the new note!
    public function getNoteText($userID, $title);
    public function deleteNote($noteID);
    public function updateNote($noteID, $noteTitle, $noteText, $colour);
    public function register($username,$password);
    public function getLinks($noteID);
    public function saveLink($noteID, $linkurl, $linkname);
    public function deleteLink($linkID);
    public function changePassword($userID, $newpassword);
    public function verifyPassword($userID, $password);
    public function addAPIKey($userID, $key);
    public function getUsersAPIKey($userID);
    public function isUniqueUsername($username);
}
// CGI
?>