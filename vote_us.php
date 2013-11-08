<?php
/**
 * Created by JetBrains PhpStorm.
 * User: radu
 * Date: 9/7/13
 * Time: 11:14 PM
 */
session_start();
include_once ('includes/global.php');
include_once ('includes/functions.php');

$vote_us = array("success" => false, "vote_us" => '', );

$campaign_id = isset($_GET["campaign_id"]) ? $_GET["campaign_id"] : 0;
$campaign_title = isset($_GET["campaign_title"]) ? $_GET["campaign_title"] : '';

if ($session -> value('user_id') && $campaign_id) {
	require_once (dirname(__FILE__) . '/includes/class_project_votes.php');
	$projectVotes = new projectVotes($session -> value('user_id'), $campaign_id);
	//$vote_us = $projectVotes -> vote();
	//if (isset($vote_us["success"]) && $vote_us["success"]) {
		//$user_voted_email = $projectVotes -> getUserEmail();
		//$campaign_owner_email = $projectVotes -> getCampaignOwnerEmail();
		$user_voted_email = "anthony.puggioni2@gmail.com";
		$campaign_owner_email = "anthony.puggioni2@gmail.com";
		
		if (!defined('INCLUDED')) {
			define('INCLUDED', 1);
		}
		include ('language/' . $setts['site_lang'] . '/mails/voting_campaign_owner_notification.php');
		include ('language/' . $setts['site_lang'] . '/mails/voting_user_notification.php');
	//}
}

echo json_encode($vote_us);
