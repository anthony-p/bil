<?php
/**
 * Created by JetBrains PhpStorm.
 * User: radu
 * Date: 9/7/13
 * Time: 11:14 PM
 */
session_start();
include_once ('includes/global.php');

$vote_us = array(
    "success" => false,
    "vote_us" => '',
);

if ($session->value('user_id') && $_GET["campaign_id"])
{
    require_once (dirname(__FILE__) . '/includes/class_project_votes.php');
    $projectVotes = new projectVotes($session->value('user_id'), $_GET["campaign_id"]);
    $vote_us = $projectVotes->vote();
}

echo json_encode($vote_us);