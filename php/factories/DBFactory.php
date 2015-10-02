<?php
/**
 * Created by PhpStorm.
 * User: Dylan
 * Date: 13/08/2015
 * Time: 4:14
 */

class DBFactory
{

    public function __construct()
    {

    }

    public function getDatabase()
    {
        return new OnlineDB();
    }
}