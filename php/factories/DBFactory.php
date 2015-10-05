<?php

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