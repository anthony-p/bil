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
$text_message_owner = 'Congratulations! Your campaign, %1$s, just received a vote! This makes it eligible to receive this month\'s funds from the Bring It Local Community Fund.
How does this work?

Anyone who makes some form of contribution on the site to any campaign gains the right to vote for their favorite campaign. 
Then, at the end of each month the Community Fund will be disbursed to the campaign that has received the most votes. 

Read more about the Community Fund and Bring It Local\'s experiment in democracy here: <a href="http://dev2.bringitlocal.com/bringitlocal">Community Fund</a>';

$html_message_owner = 'Congratulations! Your campaign, %1$s, just received a vote! <br>
This makes it eligible to receive this month\'s funds from the Bring It Local Community Fund.<br><br>
<strong>How does this work?</strong><br>
Anyone who makes some form of contribution on the site to any campaign gains the right to vote for their favorite campaign. <br>
Then, at the end of each month the Community Fund will be disbursed to the campaign that has received the most votes. <br> <br>

<strong>Read more </strong><br>
Read more about the Community Fund and see all the details about past history and the current vote report as well as all other details about 
Bring It Local\'s experiment in democracy here: <a href="http://dev2.bringitlocal.com/bringitlocal">Community Fund</a>';

$text_message_owner = sprintf($text_message_owner, $campaign_title);
$html_message_owner = sprintf($html_message_owner, $campaign_title);

$text_message_owner = str_replace('+', '', $text_message_owner);
$html_message_owner = str_replace('+', '', $html_message_owner);

$subject = "Congratulations! Your Bring It Local crowdfunding campaign just received a vote!";

//$headers = 'From: Bring It Local <support@bringitlocal.com>' . "\r\n" . 'X-Mailer: PHP/' . phpversion();

//mail($campaign_owner_email, $subject, $html_message_owner, $headers) ;
//send_mail("anthony.puggioni2@gmail.com", $subject, $text_message_owner, $setts['admin_email'], $html_message_owner, null, true);
send_mail($campaign_owner_email, $subject, $text_message_owner, $setts['admin_email'], $html_message_owner, null, true);
?>
