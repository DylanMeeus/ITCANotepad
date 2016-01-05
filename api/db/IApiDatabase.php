<?php
/**
 * Created by PhpStorm.
 * User: Dylan
 * Date: 27/12/2015
 * Time: 0:23
 */

/*
 * Interface for the api database.
 */
interface IApiDatabase {
    public function createNote($title,$text,$userid);
    public function authenticateKey($key);
    public function getUserNotes($userid);
}