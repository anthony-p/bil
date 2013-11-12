<?
#################################################################
## PHP Pro Bid v6.00										   ##
##-------------------------------------------------------------##
## Copyright 2007 PHP Pro Software LTD. All rights reserved.   ##
##-------------------------------------------------------------##
#################################################################

session_start();

define ('IN_SITE', 1);
$GLOBALS['body_id'] = "retrieve_password";

include_once ('includes/global.php');
include_once ('includes/class_fees.php');
include_once ('includes/class_formchecker.php');
include_once ('includes/class_custom_field.php');
include_once ('includes/class_user.php');
include_once ('includes/functions_login.php');


$ActiveTest=$session->value('membersarea');

//This is Unpredicted PHP Good example  when you compare 0=='Active  0-is number and 'active' is string
if ($ActiveTest==='Active')
{
    header_redirect('/members_area.php');//header_redirect('index.php');
}
else
{
	require ('global_header.php');

	$template->set('header_message', header5(MSG_RETRIEVE_YOUR_PASSWORD));

	$post_details = $db->rem_special_chars_array($_POST);
	if (isset($_REQUEST['operation']) && $_REQUEST['operation'] == 'retrieve_password')
	{
		$result = $db->query("SELECT id FROM bl2_users WHERE email='" . $post_details['email'] . "'");
		if (mysql_num_rows($result) <= 0)
		{
			$template->set('retrieve_password_msg', '<div align="center" class="errormessage_email">' . MSG_RETRIEVE_USER_ERROR . '</div>');
		}
		else 
		{
			$user = new user();
			$user->setts = &$setts;
			
			$new_password = substr(md5(rand(0, 100000)), 0, 8);
			$salt = $user->create_salt();
			$password_hashed = password_hash($new_password, $salt);

			$db->query("UPDATE bl2_users SET password='" . $password_hashed . "', salt='" . $salt . "' WHERE
				email='" . $post_details['email'] . "'");

            $template->set('submitted', 1);
			$template->set('retrieve_password_msg', '<div align="center" class="errormessage">' . MSG_NEW_PASSWORD_EMAILED . '</div>');
			
			$mail_input_id = $post_details['email'];
			include('language/' . $setts['site_lang'] . '/mails/retrieve_password.php');
		}
	}
	$template->set('post_details', $post_details);

	$template_output .= $template->process('retrieve_password.tpl.php');
	
	include_once ('global_footer.php');

	echo $template_output;
}
