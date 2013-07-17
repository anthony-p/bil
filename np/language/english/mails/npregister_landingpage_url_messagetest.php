<?
## File Version -> v6.04
## Email File -> registration confirmation message
## called only from the register.php page
include_once ('includes/npglobal.php');

include_once ('includes/npclass_formchecker.php');
include_once ('includes/npclass_custom_field.php');
include_once ('includes/npclass_item.php');
include_once ('includes/npclass_user.php');
include_once ('includes/npclass_fees.php');
include_once ('includes/npfunctions_login.php');

#if ( !defined('INCLUDED') ) { die("Access Denied"); }

#if ($mail_input_id)
{
	$row_details = $db->get_sql_row("SELECT u.user_id, u.name, u.username, u.email FROM " . NPDB_PREFIX . "users u WHERE 
		u.user_id='" . $mail_input_id . "'");
}
## otherwise row_details is provided from the file calling this email

$send = true; // always sent;

## text message - editable
$text_message = 'Dear %1$s,

Help raise funds for %3$s by making your online purchases through %2$s.

Your landing page is:

	- username: %3$s
	- password: -hidden-

In order to activate your account, please click on the activation link below:

%4$s
	
Best regards,
The %2$s staff';

## html message - editable
$html_message = 'Dear %1$s, <br>
<br>
Help raise funds for <b>%3$s</b> by making online purchases through <b>%2$s</b>. <br>
<br>
Your special <b>%3$s</b> page on %2$s is here:<br>
<ul>
	<li>Username: <b>%3$s</b></li>
	<li>Password: -hidden-</li>
</ul>
Please [ <a href="%4$s">click here</a> ] in order to activate your account. <br>
<br>
Best regards, <br>
The %2$s staff';


$activation_link = SITE_PATH . $row_details['username'];

$text_message = sprintf($text_message, $row_details['name'], $setts['sitename'], $row_details['username'], $activation_link);
$html_message = sprintf($html_message, $row_details['name'], $setts['sitename'], $row_details['username'], $activation_link);

send_mail($row_details['email'], $setts['sitename'] . ' - Help us raise funds', $text_message, 
	$setts['admin_email'], $html_message, null, $send);
?>
