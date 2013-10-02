<?
#################################################################
## PHP Pro Bid v6.00															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

session_start();

define ('IN_SITE', 1);

include_once ('includes/global.php');
include_once ('includes/class_formchecker.php');
include_once ('includes/class_custom_field.php');
include_once ('includes/class_item.php');
include_once ('includes/class_shop.php');

include_once ('global_header.php');

if (!empty($_GET['user_id'])) {
    $userId = $_GET['user_id'];
} else {
    $userId = null;
}

if (empty($userId)) {
    header_redirect('login.php');
}

$user_details = $db->get_sql_row("SELECT * FROM bl2_users WHERE id=" . $userId);

if (empty($user_details)) {
    header_redirect('login.php');
}

$template->set('user_details', $user_details);

$members_area_page_content = $template->process('members_area_aboutme_view.tpl.php');
$template->set('members_area_page_content', $members_area_page_content);

$template->set('message_header', header5($message_header));
$template->set('message_content', $message_content);

$template_output .= $template->process('about_me.tpl.php');

include_once ('global_footer.php');

echo $template_output;

?>
