<?php
/**
 * Created by PhpStorm.
 * User: Dylan
 * Date: 1/08/2015
 * Time: 21:58
 */

// get POST data


$textContent = $_POST['textData'];
$file = '../localmemory/insanity.txt';
file_put_contents($file,$textContent);

?>
