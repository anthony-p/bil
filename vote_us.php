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

        //send mail to user
        $text_message = 'Thank you for voting for ' . $campaign_title . ' campaign!';
        send_mail($user_voted_email, 'Campaign voting', $text_message,
            'support@bringitlocal.com', null, null, true);

        //send mail to campaign owner
        $text_message = 'Your campaign, ' . $campaign_title . ', received a vote!';
        send_mail($campaign_owner_email, 'Campaign voting', $text_message,
            'support@bringitlocal.com', null, null, true);

//        $mailChimp = new Mailchimp($mailChimpConfig['apiKey']);
//        var_dump($user_voted_email);
//        var_dump($campaign_owner_email);

//        try {
//            $mailChimp->lists->subscribe($mailChimpConfig['listId'],
//                array(
//                    'email'     => $user_voted_email
//                ),
//                array(
//                    'EMAIL'     => $user_voted_email,
//                    'FNAME'     => '',
//                    'LNAME'     => ''
//                )
//            );
//            $mailChimp->lists->subscribe($mailChimpConfig['listId'],
//                array(
//                    'email'     => $campaign_owner_email
//                ),
//                array(
//                    'EMAIL'     => $campaign_owner_email,
//                    'FNAME'     => '',
//                    'LNAME'     => ''
//                )
//            );
//        } catch (Mailchimp_Error $e){
//
//            // TODO: MailChimp error processing
//
//            if ($e->getMessage()) {
//                //echo '<br>' . $e->getMessage() . '<br>';
//            } else {
//                // unrecognized error
//            }
//
//        }
    }
}

echo json_encode($vote_us);