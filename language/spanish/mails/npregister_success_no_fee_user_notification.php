<?
## File Version -> v6.04
## Email File -> registration success - signup fee disabled
## called only from the register.php page

if ( !defined('INCLUDED') ) { die("Access Denied"); }

$row_details = $db->get_sql_row("SELECT u.name, u.username, u.email FROM " . NPDB_PREFIX . "users u WHERE u.user_id='" . $mail_input_id . "'");

$send = true; // always sent;

## text message - editable
$text_message = 'Dear %1$s,

Your account on %2$s has been successfully activated.

Your login details are:

	- username: %3$s
	- password: -hidden-

Best regards,
The %2$s staff';

## html message - editable
$html_message = 'Dear %1$s, <br>
<br>
Your account on <b>%2$s</b> has been successfully activated. <br>
<br>
Your login details are:<br>
<ul>
	<li>Username: <b>%3$s</b></li>
	<li>Password: -hidden-</li>
</ul>
Best regards, <br>
The %2$s staff';


$text_message = sprintf($text_message, $row_details['name'], $setts['sitename'], $row_details['username']);
$html_message = sprintf($html_message, $row_details['name'], $setts['sitename'], $row_details['username']);

send_mail($row_details['email'], $setts['sitename'] . ' - Registration Successful', $text_message, 
	$setts['admin_email'], $html_message, null, $send);
?>
