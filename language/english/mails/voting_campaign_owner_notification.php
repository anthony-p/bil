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

$header = "From: Bring It Local <support@bringitlocal.com>";

mail($campaign_owner_email, $subject, $html_message_owner) ;
//mail($campaign_owner_email, $subject, $html_message_owner, $header) ;
