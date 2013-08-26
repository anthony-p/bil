<?php
/**
 * Created by Lilian Codreanu.
 * User: Lilian Codreanu
 * Date: 6/7/13
 * Time: 3:51 PM
 * To change this template use File | Settings | File Templates.
 */

if(session_id() == '') {
    session_start();
}

if (! defined('IN_SITE'))
    define ('IN_SITE', 1);
$GLOBALS['body_id'] = "members_area";

include_once ('includes/global.php');
include_once ('includes/class_formchecker.php');
include_once ('includes/class_custom_field.php');
include_once ('includes/class_user.php');
include_once ('includes/class_fees.php');
include_once ('includes/class_shop.php');
include_once ('includes/class_item.php');
include_once ('includes/functions_item.php');
include_once ('includes/functions_login.php');
include_once ('includes/class_messaging.php');
include_once ('includes/class_reputation.php');

if (!$session->value('user_id'))
{
	header_redirect('login.php');
    die;
}

$tail_query_part = '';
if (isset($name_keyword) && $name_keyword) {
    $tail_query_part .= " AND np_users.name LIKE '%" . $name_keyword . "%'";
}

if (isset($order) && $order) {
    $tail_query_part .= "  ORDER BY reg_date " . $order;
}

if ($section == 'drafts'){
   $title="draft";
//    var_dump("SELECT * FROM bl2_users Join np_users
//        on id = probid_user_id
//        WHERE np_users.active='0'
//        AND np_users.probid_user_id=" . $session->value('user_id') . $tail_query_part); exit;
   $sql_query = $db->query(
        "SELECT * FROM bl2_users Join np_users
        on id = probid_user_id
        WHERE np_users.active='0'
        AND np_users.probid_user_id=" . $session->value('user_id') . $tail_query_part
    );
    $rows = array();
    while ($row = mysql_fetch_array($sql_query)) {
        if($row["end_date"]>time() || $row["end_date"] == $row["reg_date"] || !$row["end_date"]) {
        $rows[] = $row;
        }
    }
   $template->set('campaigns_list', $rows);
   $template->set('campaign_title', $title);
//    $template_output .= $template->process('members_area_campaigns.tpl.php');
//    echo $template_output;
} elseif ($section == 'live') {
    $title="live";
    $sql_query = $db->query(
        "SELECT * FROM bl2_users Join np_users
        on id = probid_user_id
        WHERE np_users.active='1'
        AND np_users.probid_user_id=" . $session->value('user_id') . $tail_query_part
    );
    $rows = array();
    while ($row = mysql_fetch_array($sql_query)) {
        $rows[] = $row;
    }
   $template->set('campaigns_list', $rows);
   $template->set('campaign_title', $title);

//    $template_output .= $template->process('members_area_campaigns.tpl.php');
//    echo $template_output;
} elseif ($section == 'closed') {
    $title="closed";
    $sql_query = $db->query(
        "SELECT * FROM bl2_users Join np_users
        on id = probid_user_id
        AND np_users.probid_user_id=" . $session->value('user_id') . $tail_query_part
    );
    $rows = array();
    while ($row = mysql_fetch_array($sql_query)) {
        if($row["end_date"]<=time()) {
            $rows[] = $row;}


    }
   $template->set('campaigns_list', $rows);
   $template->set('campaign_title', $title);

//    $template_output .= $template->process('members_area_campaigns.tpl.php');
//    echo $template_output;
}

?>





