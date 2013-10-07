<?
## Email File -> retrieve username
## called only from the retrieve_password.php page

if ( !defined('INCLUDED') ) { die("Access Denied"); }

//$row_details = $db->get_sql_row("SELECT u.name, u.username, u.email FROM " . DB_PREFIX . "users u WHERE u.username='" . $mail_input_id . "'");
$row_details = $db->get_sql_row("SELECT u.first_name, u.last_name, u.email FROM bl2_users u WHERE u.email='" . $mail_input_id . "'");

$send = true; // always sent;

## text message - editable
$text_message = 'Dear %1$s,

Your password to %2$s has been successfully reset.

Your login details are:

	- email: %3$s
	- password: %4$s

Best regards,
The %2$s staff';

var_dump($text_message); exit;

## html message - editable
$html_message = 'Dear %1$s, <br>
<br>
Your password to <b>%2$s</b> has been successfully reset. <br>
<br>
Your login details are:<br>
<ul>
	<li>Username: <b>%3$s</b></li>
	<li>Password: <b>%4$s</b></li>
</ul>
Best regards, <br>
The %2$s staff';

$name = $row_details['first_name'] . ' ' . $row_details['last_name'];

$text_message = sprintf($text_message, $name, $setts['sitename'], $row_details['email'], $new_password);
$html_message = sprintf($html_message, $name, $setts['sitename'], $row_details['email'], $new_password);

send_mail($row_details['email'], $setts['sitename'] . ' - Login Details Recovery', $text_message, 
	$setts['admin_email'], $html_message, null, $send);
?>
