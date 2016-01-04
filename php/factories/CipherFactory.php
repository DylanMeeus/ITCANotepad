<?php
/**
 * Created by PhpStorm.
 * User: Dylan
 * Date: 13/12/2015
 * Time: 21:01
 */

class CipherFactory
{

    public function getPolyalphabeticCipher()
    {
        return new PolyalphabeticCipher();
    }
}