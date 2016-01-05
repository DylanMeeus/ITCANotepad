<?php
/**
 * Created by PhpStorm.
 * User: Dylan
 * Date: 13/12/2015
 * Time: 17:09
 */
interface ICipher
{
    public function cipher($message);
    public function decipher($cipheredmessage);
}