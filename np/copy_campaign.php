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

//$campaign_id = (isset($_SESSION['campaign_id']) && $_SESSION['campaign_id']) ?
//    $_SESSION['campaign_id'] : 0;

$campaign_id = (isset($_GET['campaign_id']) && $_GET['campaign_id']) ?
    $_GET['campaign_id'] : 0;
//var_dump($campaign_id); exit;
$copied_campaign_id = 0;
if (isset($_GET['action']) && $_GET['action'] == 'clone') {
    $copied_campaign_id = $user->clone_campaign($campaign_id);
} else {
    $copied_campaign_id = $user->copy_campaign($campaign_id);
}

if (!$copied_campaign_id) {
    header_redirect('login.php');
}

//header("location: /view_campaign.php?campaign_id=" . $copied_campaign_id);
//header("location: /campaigns,page,edit,section," . $copied_campaign_id . ",campaign_id,members_area");
if (!headers_sent())
    header('Location: /campaigns,page,drafts,section,members_area');
else {
    echo '<script type="text/javascript">';
    echo 'window.location.href="/campaigns,page,drafts,section,members_area";';
    echo '</script>';
    echo '<noscript>';
    echo '<meta http-equiv="refresh" content="0;url=/campaigns,page,drafts,section,members_area" />';
    echo '</noscript>';
}