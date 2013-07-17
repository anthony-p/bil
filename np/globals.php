<?php
#/* Database Host Name */ 
$host = 'localhost'; 
 
/* Database Username */ 
$username = 'bringit_userbids'; 
 
/* Database Login Password */ 
$password = '^Xqh#^sqT%xC'; 

$database="bringit_auction";
// Opens a connection to a MySQL server
$connection = mysql_connect("localhost", $username, $password);
if (!$connection) {
  die("Not connected : " . mysql_error());
}

// Set the active MySQL database
$db = mysql_select_db($database, $connection);
if (!$db) {
  die("Can\'t use db : " . mysql_error());
}
?>
