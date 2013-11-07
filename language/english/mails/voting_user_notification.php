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

$text_message_user = 'Thank you for voting for %1$s campaign!';
$html_message_user = 'Thank you for voting for %1$s campaign!';

$text_message_user = sprintf($text_message_user, $campaign_title);
$html_message_user = sprintf($html_message_user, $campaign_title);

$text_message_user = str_replace('+', '', $html_message_user);
$html_message_user = str_replace('+', '', $html_message_user);

$subject = "Thanks for voting";

//$headers = 'From: Bring It Local <support@bringitlocal.com>' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
//mail($user_voted_email, $subject, $html_message_user, $headers) ;
send_mail($user_voted_email, $subject, $text_message_user, $setts['admin_email'], $html_message_user, null, true);
?>