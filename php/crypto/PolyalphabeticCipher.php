<?php
/**
 * Created by PhpStorm.
 * User: Dylan
 * Date: 13/12/2015
 * Time: 17:10
 */

class PolyalphabeticCipher implements  ICipher
{

    private $key = "anajulianovia";
    private $alph = "abcdefghijklmnopqrstuvwxyz";
    public function cipher($message)
    {
        // We get the lexicographical value of each letter.
        $keyArray = Array();
        // we loop over every letter in the message
        $ciphered = "";
        for($i = 0; $i < strlen($message);$i++)
        {
            $ciphered .= $this->getNewChar($message[$i],$this->key[$i%strlen($this->key)]);
        }
        echo "ciphered message: " . $ciphered;
    }

    /**
     * Return a new value based on the key value;
     * Key value is the character in the $key.
     * @param $character
     * @param $keyValueR
     */
    private function getNewChar($character,$keyValue)
    {
        if(preg_match('/^[a-zA-Z]/',$character))
        {
            if(!ctype_upper($character))
            {

                 $originalPos = strpos($this->alph,$character); // position of this character
                 $keyPos = strpos($this->key,$keyValue);
                 return $this->alph[($originalPos+$keyPos)%strlen($this->alph)];
            }
            else
            {
                $originalPos = strpos(strtoupper($this->alph),$character); // position of this character
                $keyPos = strpos($this->key,$keyValue);
                return strtoupper($this->alph[($originalPos+$keyPos)%strlen($this->alph)]);
            }
        }
        else
        {
            echo "old char";
            return $character;
        }
    }

    public function decipher($cipheredmessage)
    {
        // we loop over every letter in the message
        $message = "";
        for($i = 0; $i < strlen($cipheredmessage);$i++)
        {
            $message .= $this->getOldChar($message[$i],$this->key[$i%strlen($this->key)]);
        }
        echo "deciphered message: " . $message;
    }

    /**
     * Gets the original value of the character.
     * @param $cipherchar
     * @param $keyValue
     */
    private function getOldChar($cipherchar, $keyValue)
    {

    }
}