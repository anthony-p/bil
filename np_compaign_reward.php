<?
/**
 *
 * author: Zakaria DOUGHI
 *
 **/
session_start();

include_once ('includes/global.php');
include_once ('includes/class_project_rewards.php');
include_once ('includes/npfunctions_login.php');

$projectRewards   = new projectRewards();
if($_POST['claim_project_reward'] == true){
	$reward_id = isset($_POST["rewards_id"]) ? $_POST["rewards_id"] : '';
	$result = $projectRewards->getClaimRewardForm($reward_id, $session->value('user_id'));
	echo json_encode(array("response" => $result));
} else if($_POST['make_donation_for_reward'] == true){
	$reward_id = $_POST["reward_id"];
	$_SESSION['reward_claiming'] = array(
				'reward_id' => $_POST['reward_id'],
				'contribution' => $_POST['contribution'],
				'email' => $_POST['email'],
				'name' => $_POST['name'],
				'country' => $_POST['country'],
				'address1' => $_POST['address1'],
				'address2' => $_POST['address2'],
				'city' => $_POST['city'],
				'postal_code' => $_POST['postal_code']
			);
	$campaign_id = $projectRewards->getRewardCampaignId($reward_id);
	$result = '	<form id="contribution_form" method="post" action="chained.php">
					<img src="img/redirecting.gif" alt="redirecting ..." class="redirecting_indicator" />
					<input type="hidden" name="amount" id="amount" value="'.$_POST['contribution'].'" />
					<input type="hidden" name="community_amount" id="community_amount" value="'.$_POST['contribution_to_community_fund'].'" />
					<input type="hidden" name="np_user_id" id="np_user_id" value="'.$campaign_id.'" />
				</form>
				<script>
					window.scrollTo(0, 0);
					$("#contribution_form").submit();
				</script>';
	echo json_encode(array("response" => $result));
}