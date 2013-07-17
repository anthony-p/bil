<?
$qa=$_GET["address"];
$qb=$_GET["city"];
$q=$_GET["zipcode"];

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

echo  $q;
echo  $qb;
echo  $qc;
echo "hello";

// Set the active MySQL database
$db_selected = mysql_select_db($database, $connection);
if (!$db_selected) {
  die("Can\'t use db : " . mysql_error());
}

echo  $q;
echo "hello";

$myaddress=$qa;
$mycity=$qb;
$myzipcode=$q;
$distancefrom="25";
$limitresults="20";


echo "<br>My address is $myaddress, $mycity  $myzipcode <br>";



// run users address thru geocode function to get lat/longitude
// set the user's latitude and longitude as the one to search against
$mygeocode=geocode($myaddress,$mycity,$myzipcode);

#$mylonglat = implode(",", $mygeocode);
#echo "<br><br>my geocode after using the geocode function is: $mylonglat"; // latitude, longitude

$Latitude = $mygeocode['lat'];
echo "<br>My latititude :$Latitude";

$Longitude = $mygeocode['lng'];
echo "<br>My longitude :$Longitude";







echo "<br><br>choose your favorite local non profit: ";

//Here's the SQL statement that will find the closest 20 locations 
//that are within a radius of 25 miles to the users coordinates posted above. 
//It calculates the distance based on the latitude/longitude of that row
// and the target latitude/longitude, and then asks for only rows where 
//the distance value is less than $distancefrom, orders the whole query by distance, 
//and limits it to $limitresults results. To search by kilometers instead of miles, replace 3959 with 6371. 

$sqlnew = "SELECT user_id,username, tax_company_name,( 3959 * acos( cos( radians($Latitude) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($Longitude) ) + sin( radians($Latitude) ) * sin( radians( lat ) ) ) ) AS distance FROM np_users HAVING distance < $distancefrom ORDER BY distance LIMIT 0 , $limitresults";
$get_datanew = mysql_query($sqlnew);

?>
<form method="post"  action="<?php echo $PHP_SELF;?>">
<?

echo "<select tax_company_name=orgname value=''>Student Name</option>";

while($storedatanew = mysql_fetch_assoc($get_datanew)) {

#echo '<li>'.$storedatanew['user_id'].' '.$storedatanew['username'].' '.$storedatanew['distance'].', ';

echo "<option value=$storedatanew[user_id]>$storedatanew[tax_company_name] </option>";

}
#echo "</select>";// Closing of list box 
?><input name="submit" type="submit" id="form_register_proceed" value="pick your favorite non profit" /><?


#this function gets a users lat and longitude when we pass it an address
function geocode($myaddress,$mycity,$myzipcode){

#this is based on the file includes/npgeocode_user.php
#this script geocodes a single address
#use this to geocode the users adress when they register

define("MAPS_HOST", "maps.google.com");
define("KEY", "ABQIAAAANZo8i4uelpdrExUQ7l1QQhRQH_6T815PID1JgUCCFqXDzpTw0hT7DZFK6k2J4trOMqOctBABfU_1uA");


// Initialize delay in geocode speed
$delay = 0;
$base_url = "http://" . MAPS_HOST . "/maps/geo?output=xml" . "&key=" . KEY;

// Iterate through the rows, geocoding each address
# while ($row = @mysql_fetch_assoc($result)) {
#  $geocode_pending = true;

#comment out the line below and test I don;t think it serves any purpose
#$geoaddress=$_POST['geoaddress'];

#  while ($geocode_pending) {
#  $address = $user_details['address'] .",". $user_details['city'] .",". $user_details['zip_code'];
# $address = $_POST['address'] .",". $_POST['city'] .",". $_POST['zip_code'];
#  $address = '680 cascade drive, fairfax,94930';
 $address = $myaddress .",". $mycity .",". $myzipcode;


   $id = $row["user_id"];
    $request_url = $base_url . "&q=" . urlencode($address);
    $xml = simplexml_load_file($request_url) or die("url not loading");

    $status = $xml->Response->Status->code;
    if (strcmp($status, "200") == 0) {
      // Successful geocode
      $geocode_pending = false;
      $coordinates = $xml->Response->Placemark->Point->coordinates;
      $coordinatesSplit = split(",", $coordinates);
      // Format: Longitude, Latitude, Altitude
      $lat = $coordinatesSplit[1];
      $lng = $coordinatesSplit[0];

#     $query = sprintf("UPDATE np_users" .
#            " SET lat = '%s', lng = '%s' " .
#            " WHERE user_id = '%s' LIMIT 1;",
#            mysql_real_escape_string($lat),
#            mysql_real_escape_string($lng),
#            mysql_real_escape_string($id));
#     $update_result = mysql_query($query);
#     if (!$update_result) {
#       die("Invalid query: " . mysql_error());
#     }
    } else if (strcmp($status, "620") == 0) {
      // sent geocodes too fast
      $delay += 100000;
    } else {
      // failure to geocode
      $geocode_pending = false;
      echo "Address " . $address . " failed to geocoded. ";
      echo "Received status " . $status . "
\n";
    }
    usleep($delay);
  

$user_details['lat'] = $lat;
$user_details['lng'] = $lng;

return $user_details;

}



?>
