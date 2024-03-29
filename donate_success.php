<?php
session_start();
define ('IN_SITE', 1);
define ('DONATE_SUCCESS', 1);
$GLOBALS['body_id'] = "donate_success";

include_once ('includes/global.php');
include_once ('includes/class_formchecker.php');
include_once ('includes/class_custom_field.php');
include_once ('includes/class_user.php');
include_once ('includes/class_fees.php');
include_once ('includes/class_item.php');
include_once ('includes/functions_item.php');
include_once ('includes/class_messaging.php');
include_once ('includes/class_reputation.php');
require_once ('includes/class_project_rewards.php');

require ('global_header_interior.php');

$cfc_id = isset($_SESSION["cfc_id"]) ? $_SESSION["cfc_id"] :0;
$cfc_amount = isset($_SESSION["cfc_amount"]) ? $_SESSION["cfc_amount"] :0;
$np_user_id = isset($_SESSION["np_userid"]) ? $_SESSION["np_userid"] :0;
if (!$np_user_id) {
    $np_user_id = isset($_COOKIE["np_userid"]) ? $_COOKIE["np_userid"] : 0;
}

$user_id = isset($_SESSION["probid_user_id"]) ? $_SESSION["probid_user_id"] : 0;
 if (!$user_id) {
     $user_id = isset($_COOKIE["user_id"]) ? $_COOKIE["user_id"] : 0;
 }

$transferred_amount = $_SESSION["transferred_amount"];

if(isset($_SESSION['reward_claiming'])){
	$projectRewards = new projectRewards();
	$projectRewards->finalizeRewardClaiming($transferred_amount);
}

//if(isset($_SESSION['cfc_donated']) && $_SESSION['cfc_donated'] && $user_id){
//    $db->query("UPDATE bl2_users SET cfc_donated=1  WHERE id=" . $user_id);
//}

//$select_query = "SELECT payment FROM np_users WHERE
//				user_id=" . $np_user_id;
//$update_query = "UPDATE np_users SET payment=" . $total_amount . "  WHERE
//				user_id=" . $np_user_id;

$user_data = $db->get_sql_row("SELECT payment, username FROM np_users WHERE
				user_id=" . $np_user_id);

//$existing_amount = $db->get_sql_field("SELECT payment FROM np_users WHERE
//				user_id=" . $np_user_id, payment);

$total_amount = $transferred_amount + $user_data['payment'];

$db->query("UPDATE np_users SET payment=" . $total_amount . "  WHERE
				user_id=" . $np_user_id);
if ($user_id) {
    $db->query(
        "INSERT INTO funders (user_id, campaign_id, amount, created_at) VALUES (" .
            $user_id . ", " . $np_user_id . ", " . $transferred_amount . ", " . time() . ")"
    );

    if ($cfc_id && $cfc_amount) {
        $db->query(
            "INSERT INTO funders (user_id, campaign_id, amount, created_at) VALUES (" .
                $user_id . ", " . $cfc_id . ", " . $cfc_amount . ", " . time() . ")"
        );
    }
}

header("location: /" . $user_data['username']);

$template->set('total_amount', $total_amount);

$template->change_path('templates/');
$template_output .= $template->process('donate_success.tpl.php');


include_once ('global_footer.php');

echo $template_output;