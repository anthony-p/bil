<?php
/**
 * Created by JetBrains PhpStorm.
 * User: radu
 * Date: 9/7/13
 * Time: 11:14 PM
 */
session_start();
include_once ('includes/global.php');
include_once ('includes/functions.php');

$vote_us = array(
    "success" => false,
    "vote_us" => '',
);

$campaign_id = isset($_GET["campaign_id"]) ? $_GET["campaign_id"] : 0;
$campaign_title = isset($_GET["campaign_title"]) ? $_GET["campaign_title"] : '';

if ($session->value('user_id') && $campaign_id)
{
    require_once (dirname(__FILE__) . '/includes/class_project_votes.php');
    $projectVotes = new projectVotes($session->value('user_id'), $campaign_id);
    $vote_us = $projectVotes->vote();
    if (isset($vote_us["success"]) && $vote_us["success"]) {
        $user_voted_email = $projectVotes->getUserEmail();
        $campaign_owner_email = $projectVotes->getCampaignOwnerEmail();

//        if (!defined('INCLUDED')) {
//            define('INCLUDED', 1);
//        }
//        include_once ('language/english/mails/voting_campaign_owner_notification.php');
//        include_once ('language/english/mails/voting_user_notification.php');

        $subject = "Campaign voting";
        $html_message_user = 'Thank you for voting for ' . $campaign_title . ' campaign!';
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

        mail($user_voted_email, $subject, $html_message_user, $header) ;
        mail($campaign_owner_email, $subject, $html_message_owner, $header) ;
    }
}

echo json_encode($vote_us);