<?php

// get POST data


$textContent = $_POST['textData'];
$file = '../localmemory/insanity.txt';
file_put_contents($file,$textContent);

?>
