<?php
/**
 * Created by JetBrains PhpStorm.
 * User: radu
 * Date: 9/10/13
 * Time: 11:33 PM
 */
if ( !defined('INCLUDED') ) { die("Access Denied"); }

$subject = "Your campaign received a vote!";
$html_message_owner = 'Your campaign, ' . $campaign_title . ', received a vote!';

$headers = 'From: Bring It Local <support@bringitlocal.com>' . PHP_EOL .
    'X-Mailer: PHP-' . phpversion() . PHP_EOL .
    'Content-type: text/html; charset=iso-8859-1' . PHP_EOL;

$uid = md5(uniqid(time()));
$header = "From: Bring It Local <support@bringitlocal.com> \r\n";
$header .= "MIME-Version: 1.0\r\n";
$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
$header .= "This is a multi-part message in MIME format.\r\n";
$header .= "--".$uid."\r\n";
$header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$header .= "--".$uid."--";

mail($campaign_owner_email, $subject, $html_message_owner, $header) ;
