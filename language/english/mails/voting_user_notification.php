<?php
/**
 * Created by JetBrains PhpStorm.
 * User: radu
 * Date: 9/10/13
 * Time: 11:32 PM
 */
if (!defined('INCLUDED')) { die("Access Denied");
}

$send = true;
// always sent;

$text_message_user = 'Thank you for voting for %1$s, a Bring It Local crowdfunding campaign This campaign is eligible 
to receive this month\'s funds from the Bring It Local Community Fund. 

How does this work?

Anyone who makes some form of contribution on the site to any campaign gains the right to vote for their favorite campaign. 
Then, at the end of each month the Community Fund will be disbursed to the campaign that has received the most votes. 

You will be able to vote again if you participate in the site next month. 

Read more about the Community Fund and Bring It Local\'s experiment in democracy here: <a href="http://dev2.bringitlocal.com/bringitlocal">Community Fund</a>';

$html_message_user = 'Thank you for voting for %1$s a Bring It Local crowdfunding campaign!
<br> <br>
<strong>How does this work?</strong><br>
Anyone who makes some form of contribution on the site to any campaign gains the right to vote for their favorite campaign. <br>
<br> <br>Then, at the end of each month the Community Fund will be disbursed to the campaign that has received the most votes. <br> <br>

You will be able to vote again if you participate in the site next month. 
<br> <br>
<strong>Read more </strong><br>
Read more about the Community Fund and see all the details about past history and the current vote report as well as all other details about 
Bring It Local\'s experiment in democracy here: <a href="http://dev2.bringitlocal.com/bringitlocal">Community Fund</a>';




$text_message_user = sprintf($text_message_user, $campaign_title);
$html_message_user = sprintf($html_message_user, $campaign_title);

$text_message_user = str_replace('+', '', $html_message_user);
$html_message_user = str_replace('+', '', $html_message_user);

$subject = "We got your vote! Thanks for participating in our experiment in democracy";

//$headers = 'From: Bring It Local <support@bringitlocal.com>' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
//mail($user_voted_email, $subject, $html_message_user, $headers) ;
send_mail($user_voted_email, $subject, $text_message_user, $setts['admin_email'], $html_message_user, null, true);
//send_mail("anthony.puggioni2@gmail.com", $subject, $text_message_user, $setts['admin_email'], $html_message_user, null, true);
?>
