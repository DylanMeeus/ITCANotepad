<?php

class Validator
{

    public function _construct()
    {

    }

    function sanitize($input)
    {
        if (is_array($input)) {
            foreach ($input as $key => $element) {
                $input[$key] = sanitize($element);
            }
        } else {
            $input = trim($input);
            $input = htmlentities($input);
        }
        return $input;
    }
}
?>