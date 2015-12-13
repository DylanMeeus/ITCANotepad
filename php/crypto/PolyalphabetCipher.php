<?php
/**
 * Created by PhpStorm.
 * User: Dylan
 * Date: 13/12/2015
 * Time: 17:10
 */

class PolyalphabetCipher implements  ICipher
{

    private $key = "anajulianovia";
    private $alph = "abcdefghijklmnopqrstuvwxyz";
    public function cipher($message)
    {
        // We get the lexicographical value of each letter.
        $keyArray = Array();
        // we loop over every letter in the message
        $ciphered = "";
        for($i = 0; $i<strlen($message);$i++)
        {
            $ciphered += $this->getNewChar($message[i],$this->key[i%strlen(key)]);
        }
        echo $ciphered;
    }

    /**
     * Return a new value based on the key value;
     * @param $character
     * @param $keyValueR
     */
    private function getNewChar($character,$keyValue)
    {
        $originalPos = strpos($this->alph,$character); // position of this character
        return $this->alph[($originalPos+$keyValue)%strlen($this->alph)];
    }

    public function decipher($cipheredmessage)
    {
        // TODO: Implement decipher() method.
    }
}