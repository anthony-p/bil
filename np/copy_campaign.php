<?php
/**
 * Created by JetBrains PhpStorm.
 * User: radu
 * Date: 8/29/13
 * Time: 9:14 AM
 */
session_start();

include_once ('includes/npglobal.php');
include_once ('includes/npclass_user.php');

$user = new npuser();

$campaign_id = (isset($_SESSION['campaign_id']) && $_SESSION['campaign_id']) ?
    $_SESSION['campaign_id'] : 0;

$copied_campaign_id = $user->copy_campaign($campaign_id);

var_dump($copied_campaign_id);

if (!$copied_campaign_id) {
    header_redirect('login.php');
}

header("location: /view_campaign.php?campaign_id=" . $copied_campaign_id);
