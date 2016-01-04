<?php
/**
 * Created by PhpStorm.
 * User: Dylan
 * Date: 13/12/2015
 * Time: 21:01
 */
require_once "php/crypto/PolyalphabeticCipher.php";

class CipherFactory
{

    public function getPolyalphabeticCipher()
    {
        return new PolyalphabeticCipher();
    }
}