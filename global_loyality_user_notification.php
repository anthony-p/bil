<?php
define ('IN_ADMIN', 1);
if (!defined('IN_SITE'))
    define ('IN_SITE', 1);

include_once ('includes/global.php');

global $db;
global $email_user_id;
global $activity_id;

if(isset($_POST['user_id'])) $email_user_id   = $_POST['user_id'];
if(isset($_POST['activity_id'])) $activity_id   = $_POST['activity_id'];


$activity = $db->get_sql_row("SELECT activity_name, points_awarded FROM probid_user_activities WHERE activity_id=$activity_id");
$user_details= $db->get_sql_row("SELECT name, username, email FROM " . DB_PREFIX . "users WHERE user_id='" . $email_user_id . "'");

$sum_points_awarded = $db->get_sql_field("SELECT SUM(points_awarded) as sum_points_awarded FROM probid_user_points WHERE user_id = $email_user_id AND active = 1 ", sum_points_awarded);
$points_to_win = $db->get_sql_field("SELECT points_awarded FROM probid_user_activities WHERE activity_id=100", points_awarded);

if($activity)
{
    //send mail to user with activity and point awarded
    $send = true; // always sent;


    $text_message = 'Thank you for using Bring It Local to support your community. Your most recent act of consumer disobedience has earned you %1$s points for %2$s.
        <br>
        You now have a total of %3$s points. When you reach %4$s points we\'ll give you a voucher for a local merchant coupon.';

    $text_message = sprintf($text_message, $activity['points_awarded'],$activity['activity_name'], $sum_points_awarded, $points_to_win);
    send_mail($user_details['email'], 'User loyalty program', $text_message,
        'support@bringitlocal.com', null, null, $send);

    //send mail to administration with user activity and point awarded
    $send = true; // always sent;

    ## text message - editable
    $text_message = '
        User %1$s earned %2$s points for %3$s.
        <br>
        <br>
        The Bring It Local mail notification';

    $text_message = sprintf($text_message, $user_details['username'], $activity['points_awarded'],$activity['activity_name']);

    send_mail($user_details['email'], 'User loyalty program', $text_message, 'support@bringitlocal.com', null, null, $send);

    /* Send summarry to support*/
    $summarry = "Points Awarded: {$activity['points_awarded']} \n";
    $summarry .= "Activity name: {$activity['activity_name']} \n";
    $summarry .= "Sum Points Awarded: $sum_points_awarded \n";
    $summarry .= "Points to win: $points_to_win \n";
    $support_mail = "support@bringitlocal.com";

    send_mail($support_mail,'Notification mail, User loyalty program', $summarry,
                                'support@bringitlocal.com', nl2br($summarry), null, $send);
}



if($sum_points_awarded > $points_to_win)
{
    $send = true; // always sent;

    ## text message - editable
    $text_message = '
    User %1$s have %2$s points.
    <br/>
    <br/>
    The Bring It Local mail notification';

    $text_message = sprintf($text_message, $user_details['username'], $sum_points_awarded);

   # send_mail($user_details['email'], 'Users loyalty program', $text_message,'support@bringitlocal.com', null, null, $send);

	}
?>