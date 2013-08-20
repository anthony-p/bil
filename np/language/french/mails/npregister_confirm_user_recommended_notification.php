<?
## File Version -> v6.04
## Email File -> np recommended confirmation message - just notify the site admin that the form is submitted
## called only from the /public_html/np/templates/npregister_supporter.tpl.php/register.php page

if ( !defined('INCLUDED') ) { die("Access Denied"); }

if ($mail_input_id)
{
	$row_details = $db->get_sql_row("SELECT u.user_id, u.name, u.username, u.email FROM " . NPDB_PREFIX . "users u WHERE 
		u.user_id='" . $mail_input_id . "'");
}
## otherwise row_details is provided from the file calling this email

$send = true; // always sent;

## text message - editable
$text_message = 'Thankyou for your recommendation of a non-profit organization for Bring It Local. 
An account for this non-profit on %2$s has been successfully created.

Please note: You may begin supporting this organization immediately. You will find them listed in the Quick Select page or you can start at the community page.
However, please note, this submission is subject to a final review. We will send you a note when this account is confirmed.


The community page details are:

	- community page: <a href="http://www.bringitlocal.com/%3$s
	


%4$s
	
Best regards,
The %2$s staff';

## html message - editable
$html_message = 'Thankyou for your recommendation of a non-profit organization for Bring It Local. 
An account for this non-profit on %2$s has been successfully created.

<br>
The community page details are:<br>
<ul>
	<li>The address of the community page for this organization: 
	<a href="http://www.bringitlocal.com/%3$s">http://www.bringitlocal.com/%3$s</a>
	</li>
</ul>
Please note: You may begin supporting this organization immediately. 
You will find them listed in the Quick Select page after you enter your zipcode or you can simply start 
at the community page and then begin shopping.
<br><br>

Watch a 2 minute Youtube video with full details on how Bring It Local works <a href="http://youtu.be/oXQq10yjeTk">here</a>. 
<br><br>
However, please note, this submission is subject to a final review. We will send you a note when this account is confirmed.
<br><br>
We really appreciate your participation!!! Thank you for supporting Bring It Local and your community!
<br><br>
If you have any questions please feel free to contact us by simply replying to this email. 
<br><br>

Best regards, <br>
The %2$s staff';


$activation_link = SITE_PATH . 'np/npaccount_activate.php?user_id=' . $row_details['user_id'] . '&username=' . $row_details['username'];

$text_message = sprintf($text_message, $row_details['name'], $setts['sitename'], $row_details['username'], $activation_link);
$html_message = sprintf($html_message, $row_details['name'], $setts['sitename'], $row_details['username'], $activation_link);

send_mail($row_details['email'], $setts['sitename'] . ' - Your Recommendation has been received', $text_message, 
	$setts['admin_email'], $html_message, null, $send);
?>
