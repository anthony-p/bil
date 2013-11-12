<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

session_start();

define ('IN_SITE', 1);
$GLOBALS['body_id'] = "content_pages";

include_once ('includes/global.php');
include_once ('includes/class_formchecker.php');

include_once ('includes/functions_login.php');
include_once ('includes/functions_item.php');

include_once ('global_header.php');

$template_output .= $template->process('site_fees.tpl.php');

include_once ('global_footer.php');

echo $template_output;

?>
