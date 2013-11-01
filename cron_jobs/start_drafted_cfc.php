<?php 

session_start();
include_once ('../includes/global.php');

$today     = strtotime('today');
$campaigns = $db->query("SELECT * FROM np_user WHERE active = 0 AND cfc = 1 AND start_date = $today");

while ($query_result = mysql_fetch_assoc($campaigns)) {

	$starting_campaigns[] = $query_result;	

}
foreach ($starting_campaigns as $starting_campaign) {

	$db->query("UPDATE np_users SET active = 1 WHERE user_id = $starting_campaign['user_id']");
	
}