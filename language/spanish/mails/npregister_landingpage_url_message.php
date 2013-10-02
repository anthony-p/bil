<?
## File Version -> v6.04
## Email File -> registration confirmation message
## called only from the register.php page

if ( !defined('INCLUDED') ) { die("Access Denied"); }

if ($mail_input_id)
{
	$row_details = $db->get_sql_row("SELECT u.user_id, u.name, u.username, u.email FROM " . NPDB_PREFIX . "users u WHERE 
		u.user_id='" . $mail_input_id . "'");
}
## otherwise row_details is provided from the file calling this email

$send = true; // always sent;

## text message - editable
$text_message = 'Dear %1$s,

Thank you for signing up your organization with %2$s. Please forward this email to
your community so that they will know how they can help raise funds for %3$s by making their online purchases through %2$s. 
Feel free to edit this note or simply send it on as is.


Dear %3$s community:

Help raise funds for %3$s by making your online purchases through %2$s Its simple just do what you already are doing but start here at this landing page on %2$s This page is set up so you can track exactly how much funds have been raised by the %3$s community 
Your %3$s landing page is:

	- http://www.bringitlocal.com/%3$s
	- go to this page for all your online purchases
	- Click here: %4$s

	
Best regards,
The %2$s staff';


## html message - editable
$html_message = 'Dear %1$s, <br>
<br>
Thank you for signing up your organization with %2$s.<br>
<br>
Please forward this email to
your community so that they will know how they can help raise funds for %3$s by making their online purchases through %2$s. 
Feel free to edit this note or simply send it on as is.
<br><br>

Dear %3$s community:<br><br>

Help raise funds for %3$s by making your online purchases through %2$s 
<br><br>Its simple, just do what you already are doing - shop online- but
start here at this landing page on %2$s. <br>
This page is set up so you can track exactly how much funds have been raised by the %3$s community.
<br>Your %3$s landing page is:
<ul>
	- http://www.bringitlocal.com/%3$s<br>
	- go to this page for all your online purchases<br>
	-[ <a href="%4$s">click here</a> ]
</ul>

%4$s
<br><br>	
Best regards,<br>
The %2$s staff';


$activation_link = SITE_PATH . $row_details['username'];

$text_message = sprintf($text_message, $row_details['name'], $setts['sitename'], $row_details['username'], $activation_link);
$html_message = sprintf($html_message, $row_details['name'], $setts['sitename'], $row_details['username'], $activation_link);

send_mail($row_details['email'], $setts['sitename'] . ' - Help us raise funds', $text_message, 
	$setts['admin_email'], $html_message, null, $send);
?>
