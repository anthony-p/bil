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

$template->change_path('templates/');
$template_output .= $template->process('donate.tpl.php');


include_once ('global_footer.php');

echo $template_output;