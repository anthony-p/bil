<?php
include_once ('dbconfig.php');

$username =  $db_username;
$password =  $db_password;
$hostname = $db_host;	
$database = $db_name;

mysql_connect($hostname, $username, $password) or die(mysql_error());
mysql_select_db($database) or die(mysql_error()); 

?>
