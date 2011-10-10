<?php
/* Database config */

$db_host     = 'localhost';
$db_user     = 'root';
$db_pass     = '';
$db_database = 'hsc'; 

/* End config */

$mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
?>
