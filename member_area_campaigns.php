<?php
/**
 * Created by Lilian Codreanu.
 * User: Lilian Codreanu
 * Date: 6/7/13
 * Time: 3:51 PM
 * To change this template use File | Settings | File Templates.
 */

session_start();

define ('IN_SITE', 1);
$GLOBALS['body_id'] = "members_area";

include_once ('includes/global.php');
include_once ('includes/class_formchecker.php');
include_once ('includes/class_custom_field.php');
include_once ('includes/class_user.php');
include_once ('includes/class_fees.php');
include_once ('includes/class_shop.php');
include_once ('includes/class_item.php');
include_once ('includes/functions_item.php');
include_once ('includes/functions_login.php');
include_once ('includes/class_messaging.php');
include_once ('includes/class_reputation.php');

if (!$session->value('user_id'))
{
	header_redirect('login.php');
    die;
}


$template_output="";
$template_output .= $template->process('members_area_campaigns.tpl.php');;

	include_once ('global_footer.php');

	echo $template_output;