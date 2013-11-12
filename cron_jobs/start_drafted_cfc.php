<?php 

session_start();
include_once ('../includes/global.php');

$today     = strtotime('today');
$campaigns = $db->query("SELECT * FROM np_users WHERE active = 0 AND cfc = 1");

while ($query_result = mysql_fetch_assoc($campaigns)) {

	$starting_campaigns[] = $query_result;	

}
foreach ($starting_campaigns as $starting_campaign) {
	if (date('j M Y', $today) == date('j M Y', $starting_campaign['start_date'])) 		
		$db->query("UPDATE np_users SET active = 1 WHERE user_id = " . $starting_campaign['user_id']);
	
}