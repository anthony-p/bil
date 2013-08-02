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
    $user_id = $_GET["np_userid"];
}


if (!isset($user_id) || !$user_id) {
    $user_id = $_COOKIE["np_userid"];
}

if (!$user_id)
{
    header_redirect('index.php');
}

$campaign = $db->get_sql_row(
    "SELECT logo, banner, description, name  FROM np_users WHERE np_users.user_id=" . $user_id
);

$template->set('campaign', $campaign);
$template->change_path('templates/');
$template_output .= $template->process('donate.tpl.php');


include_once ('global_footer.php');

echo $template_output;