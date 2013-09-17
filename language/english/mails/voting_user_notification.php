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

$headers = 'From: Bring It Local <support@bringitlocal.com>' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($user_voted_email, $subject, $html_message_user, $headers) ;
