<?php
/**
 * Created by JetBrains PhpStorm.
 * User: radu
 * Date: 9/10/13
 * Time: 11:32 PM
 */
if ( !defined('INCLUDED') ) { die("Access Denied"); }

$subject = "Thanks for voting";
$html_message_user = 'Thank you for voting for ' . $campaign_title . ' campaign!';
$html_message_user = str_replace('+', '', $html_message_user);

$headers = 'From: Bring It Local <support@bringitlocal.com>' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

//mail($user_voted_email, $subject, $html_message_user, $headers) ;
send_mail($user_voted_email, $subject, $html_message_user, 
    $setts['admin_email'], $html_message_user, null, true);