<?php
/**
 * Created by PhpStorm.
 * User: Dylan
 * Date: 10/09/2015
 * Time: 4:43
 */


// A facade for what this API is able to do.
// Kept seperate from the rest of the facade.

// I promise myself not to cry with this code
require_once "db/ApiDatabase.php";
class ApiFacade
{

    private $apidatabase;

    public function __construct()
    {
        $this->apidatabase = new ApiDatabase();
    }

    public function createNote($title,$text,$userid){
        $this->apidatabase->createNote($title,$text,$userid);
    }

    public function authenticateKey($key){
        return $this->apidatabase->authenticateKey($key);
    }

}