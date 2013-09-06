<?
session_start();

include_once ('includes/global.php');
include_once ('includes/class_project_rewards.php');
include_once ('includes/npfunctions_login.php');

if($_POST['claim_project_reward'] == true){
	$reward_id = isset($_POST["rewards_id"]) ? $_POST["rewards_id"] : '';
	$projectRewards   = new projectRewards();
	$result = $projectRewards->getClaimRewardForm($reward_id, $session->value('user_id'));
	echo json_encode(array("response" => $result));
}