<?php
// Create connection
$con=mysqli_connect("localhost","root","johilo9-4","devbrin");

// Check connection
if (mysqli_connect_errno($con))
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  echo "good";
  
   ini_set('display_errors','On');
 error_reporting(E_ALL);
?>
