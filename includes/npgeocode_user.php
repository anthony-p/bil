<?php
#this script geocodes a single address which is hardcoded for testing purposes below
#use this to take posted values from the registration form and add to the db insert

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
 $address = $_POST['address'] .",". $_POST['city'] .",". $_POST['zip_code'];

#  $address = '200 main street, manchester,03102';



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
?>

