<?php
$server = "BEER-PC\SQLEXPRESS";$options = array(  "Database" => "notesTest");$conn = sqlsrv_connect($server, $options);if ($conn === false) die("<pre>".print_r(sqlsrv_errors(), true));echo "Successfully connected!";sqlsrv_close($conn);
/*require_once 'php/controller/Servlet.php';
$servlet = new Servlet();
$servlet->processRequest();*/
?>