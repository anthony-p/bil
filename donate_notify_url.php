<?php
/**
 * Created by JetBrains PhpStorm.
 * User: radu
 * Date: 9/15/13
 * Time: 9:48 PM
 */
//var_dump($_POST); exit;
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

//$user_id = isset($_SESSION["probid_user_id"]) ? $_SESSION["probid_user_id"] : 0;
$user_id = 0;

if (!$user_id) {
    if (isset($_POST) && isset($_POST['payer_email']) && $_POST['payer_email']) {
        $payer_email = $_POST['payer_email'];

        $user_id = $db->get_sql_field("SELECT id FROM bl2_users WHERE
				pg_paypal_email='" . $payer_email . "'", 'id');

        if ($user_id) {
            $_SESSION["probid_user_id"] = $user_data['id'];
        }
    }
}