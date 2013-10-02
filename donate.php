<?php
session_start();

define ('IN_SITE', 1);
define ('DONATE', 1);
$GLOBALS['body_id'] = "donate";

include_once ('includes/global.php');
include_once ('includes/class_formchecker.php');
include_once ('includes/class_custom_field.php');
include_once ('includes/class_user.php');
include_once ('includes/class_fees.php');
include_once ('includes/class_item.php');
include_once ('includes/functions_item.php');
include_once ('includes/class_messaging.php');
include_once ('includes/class_reputation.php');

require ('global_header_interior.php');
//require ('global_header_interior.php');

$status = "contribute";

if (isset($_POST["ammount"]) && !empty($_POST["ammount"])) {
    $status = "pay";
}

if (isset($_GET["np_userid"]) && !empty($_GET["np_userid"])) {
    $np_user_id = $_GET["np_userid"];
}


if (!isset($np_user_id) || !$np_user_id) {
    $np_user_id = $_COOKIE["np_userid"];
}

if (!$np_user_id)
{
    header_redirect('index.php');
}

$user_id = isset($_SESSION["probid_user_id"]) ? $_SESSION["probid_user_id"] : 0;
if (!$user_id) {
    $user_id = isset($_COOKIE["user_id"]) ? $_COOKIE["user_id"] : 0;
}

$user = $db->get_sql_row(
    "SELECT id, cfc_donated  FROM bl2_users WHERE bl2_users.id=" . $user_id
);

$campaign = $db->get_sql_row(
    "SELECT end_date, active, logo, banner, description, pg_paypal_email, name, project_title, cfc
    FROM np_users WHERE np_users.user_id=" . $np_user_id
);

if (!$campaign || $campaign['active'] == 2 || ($campaign['end_date']-time()) <= 0 )
    header("Location: /",TRUE,301);

$template->set('user', $user);
$template->set('campaign', $campaign);
$template->set('np_user_id', $np_user_id);
$template->change_path('templates/');
$template_output .= $template->process('donate.tpl.php');


include_once ('global_footer.php');

echo $template_output;
