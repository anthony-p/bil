<?php



if (!defined('INCLUDED')) {
    die("Access Denied");
}

$send = true; // always sent;

$body = MSG_EMAIL_CAMPAIGN_COMMENT_UPDATE_BODY . "<br>\r\n";
$body .= "<a href='" . $camp_url . "'>" . $camp_url . "</a>";

send_mail($userinfo['email'],
    MSG_EMAIL_CAMPAIGN_COMMENT_UPDATE_SUBJECT,
    strip_tags($body),
    $setts['admin_email'],
    $body,
    null,
    $send);


?>