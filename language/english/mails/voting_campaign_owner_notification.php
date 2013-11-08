<?php
/**
 * Created by JetBrains PhpStorm.
 * User: radu
 * Date: 9/10/13
 * Time: 11:33 PM
 */
if (!defined('INCLUDED')) { die("Access Denied");
}

$send = true;
// always sent;

## text message - editable
$text_message_owner = 'Your campaign, %1$s ,received a vote! 
Active site users are able to vote for their favorite campaigns. 
The Community Fund will be disbursed each month to the campaign that receives the most votes. 
You can read more about the Community Fund and 
Bring It Local\'s experiment in democracy here: <a href="http://dev2.bringitlocal.com/bringitlocal">Community Fund</a>';

$html_message_owner = 'Your campaign, %1$s ,received a vote! <br>
Active site users are able to vote for their favorite campaigns. <br>
The Community Fund will be disbursed each month to the campaign that receives the most votes. <br>  
You can read more about the Community Fund and <br>
Bring It Local\'s experiment in democracy here: <a href="http://dev2.bringitlocal.com/bringitlocal">Community Fund</a>';

$text_message_owner = sprintf($text_message_owner, $campaign_title);
$html_message_owner = sprintf($html_message_owner, $campaign_title);

$text_message_owner = str_replace('+', '', $text_message_owner);
$html_message_owner = str_replace('+', '', $html_message_owner);

$subject = "Your campaign received a vote!";

//$headers = 'From: Bring It Local <support@bringitlocal.com>' . "\r\n" . 'X-Mailer: PHP/' . phpversion();

//mail($campaign_owner_email, $subject, $html_message_owner, $headers) ;
send_mail("anthony.puggioni2@gmail.com", $subject, $text_message_owner, $setts['admin_email'], $html_message_owner, null, true);
//mail("anthony.puggioni2@gmail.com", "subject4", "message4") ;
?>