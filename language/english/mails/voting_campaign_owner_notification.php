<?php
/**
 * Created by JetBrains PhpStorm.
 * User: radu
 * Date: 9/10/13
 * Time: 11:33 PM
 */
if ( !defined('INCLUDED') ) { die("Access Denied"); }

$subject = "Your campaign received a vote!";
$html_message_owner = 'Your campaign, '. $campaign_title . ',received a vote! <br>Active site users are able to vote for their favorite campaigns. The Community Fund will be disbursed each
month to the campaign that receives the most votes. You can read more about the Community Fund and Bring It Local\'s experiment in democracy here: <a href="http://dev2.bringitlocal.com/bringitlocal">Community Fund</a>';
$html_message_owner = str_replace('+', '', $html_message_owner);

$headers = 'From: Bring It Local <support@bringitlocal.com>' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($campaign_owner_email, $subject, $html_message_owner, $headers) ;
