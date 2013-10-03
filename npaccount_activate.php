<?
#################################################################
## PHP Pro Bid v6.10															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

session_start();

define ('IN_SITE', 1);

include_once ('includes/npglobal.php');
include_once ('includes/npclass_fees.php');
echo "user id $user_id<br>";
$user_id = intval($_REQUEST['user_id']);
echo "user id $user_id<br>";

$username = $db->rem_special_chars($_REQUEST['username']);
echo "user id $username<br>";

$signup_fee = new npfees();

#echo "signup_fee $signup_fee<br>";

$signup_fee->setts = &$setts;
#echo "signup_fee $signup_fee<br>";
$signup_result = $signup_fee->signup($user_id);

echo "signup_result $signup_result<br>";
$is_user = $db->count_nprows('users', "WHERE user_id=" . $user_id . " AND username='" . $username . "' AND mail_activated=0");

echo "is_user $is_user<br>";
echo "user_id $user_id<br>";
echo "username $username<br>";
echo "mail_activated $mail_activated<br>";


$generate_page = false;

if ($is_user == 1)
{
	$db->query("UPDATE " . NPDB_PREFIX . "users SET active=1, approved=1, mail_activated=1, payment_status='confirmed' WHERE 
		user_id=" . $user_id);

	$template->set('message_content', '<div align="center" class="errormessage">' . MSG_ACC_ACTIVATE_SUCCESS . '</div>');

#send email with landing page url
$mail_input_id = $user_id;
include('language/' . $setts['site_lang'] . '/mails/npregister_landingpage_url_message.php');	



	
	$generate_page = true;
}
else if ($is_user && !$signup_result['amount'] && $setts['signup_settings'] == 2)
{
	$db->query("UPDATE " . NPDB_PREFIX . "users SET active=1, mail_activated=1, payment_status='confirmed' WHERE 
		user_id=" . $user_id);

	$template->set('message_content', '<div align="center" class="errormessage">' . MSG_ACC_VERIFY_EMAIL_SUCCESS . '</div>');
	
	$generate_page = true;
} 
else if ($session->value('user_id'))
{
	header_redirect('npmembers_area.php');
}
else 
{
	$template->set('message_content', '<div align="center" class="errormessage">' . MSG_ACC_ACTIVATE_FAILURE . '</div>');
	
	$generate_page = true;
}

if ($generate_page)
{
	include_once ('npglobal_header.php');	
	$template->set('message_header', header5(MSG_USER_ACCOUNT_CONFIRMATION));
	
	$template_output .= $template->process('npsingle_message.tpl.php');
	
	include_once ('global_footer.php');
	
	echo $template_output;
}
?>
