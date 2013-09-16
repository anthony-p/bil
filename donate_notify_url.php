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
        if ($_POST['payer_email'] == 'buyer@paypalsandbox.com') {
            $payer_email = 'rlpc.test@gmail.com';
            $db->query(
                "INSERT INTO funders (user_id, campaign_id, amount, created_at) VALUES (" .
                    1 . ", " . 1 . ", " . 1 . ", " . time() . ")"
            );
        }
        $user_data = $db->get_sql_row("SELECT payment, username FROM np_users WHERE
				pg_paypal_email=" . $_POST['payer_email']);

        if (is_array($user_data) && isset($user_data['id']) && $user_data['id']) {
            $_SESSION["probid_user_id"] = $user_data['id'];
            $db->query(
                "INSERT INTO funders (user_id, campaign_id, amount, created_at) VALUES (" .
                    $user_data['id'] . ", " . 1 . ", " . 1 . ", " . time() . ")"
            );
            $db->query(
                "INSERT INTO funders (user_id, campaign_id, amount, created_at) VALUES (" .
                    $user_data['id'] . ", " . 2 . ", " . 2 . ", " . time() . ")"
            );
            $db->query(
                "INSERT INTO funders (user_id, campaign_id, amount, created_at) VALUES (" .
                    $user_data['id'] . ", " . 3 . ", " . 3 . ", " . time() . ")"
            );
            $db->query(
                "INSERT INTO funders (user_id, campaign_id, amount, created_at) VALUES (" .
                    $user_data['id'] . ", " . 4 . ", " . 4 . ", " . time() . ")"
            );
        }
    }
}