<?
# this file takes the registering users choice for non profit and stores it in form input variables
# so it stays available when the form is submitted or reloaded and can then be
#inserted in the datasbe when the user does finally submit their registration form


#/* Database Host Name */ 
#$host = 'localhost'; 
 
/* Database Username */ 
$username = 'devbring_userbid'; 
 
/* Database Login Password */ 
$password = '^Xqh#^sqT%xC'; 

$database="devbring_auction";
// Opens a connection to a MySQL server
$connection = mysql_connect("localhost", $username, $password);
if (!$connection) {
  die("Not connected : " . mysql_error());
}

// Set the active MySQL database
$db_selected = mysql_select_db($database, $connection);
if (!$db_selected) {
  die("Can\'t use db : " . mysql_error());
}

$q=$_GET["q"];






$sql="SELECT * FROM np_users WHERE user_id = '".$q."'";

$result = mysql_query($sql);
echo "<b>You've selected:</b><br>";

$row = mysql_fetch_array( $result );


$statename=fetchstate($row['state']);



// Print out the contents of each row into a table 


echo  "<br><strong>" . $row['tax_company_name'] . "</strong>";
echo  "<br>" . $row['address'];
echo  "<br>" . $row['city'];
echo  "<br>" . $statename;
echo  "<br>" . $row['zip_code'];



$_POST['npname']=$row['tax_company_name'];
$_POST['npaddress']=$row['address'];
$_POST['npcity']=$row['city'];
$_POST['npstate']= $statename;
$_POST['npzipcode']=$row['zip_code'];









echo "<input type='hidden' name='npname' value = '";
echo $_POST['npname'];
echo "'><br>";


echo "<input type='hidden' name='npaddress' value = '";
echo $_POST['npaddress'];
echo "'>";



echo "<input type='hidden' name='npcity' value = '";
echo $_POST['npcity'];
echo "'>";

echo "<input type='hidden' name='npstate' value = '";
echo $_POST['npstate'];
echo "'>";

echo "<input type='hidden' name='npzipcode' value = '";
echo $_POST['npzipcode'];
echo "'>";




#convert statecode to full statename for non profit selection
function fetchstate($statecode){
$sql="SELECT name FROM probid_countries WHERE id = '".$statecode."'";
$result = mysql_query($sql);
$row = mysql_fetch_array( $result );
$statename=$row['name'];
return $statename;
}

mysql_close($connection);

?> 
