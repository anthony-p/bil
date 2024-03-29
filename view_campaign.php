<?php
/**
 * Created by JetBrains PhpStorm.
 * User: radu
 * Date: 8/21/13
 * Time: 10:15 PM
 */
session_start();

define ('IN_SITE', 1);

include_once ('includes/global.php');
include_once ('np/includes/npclass_formchecker.php');
include_once('includes/generate_image_thumbnail.php');
global $db;

if ($session->value('user_id') && (isset($_GET["campaign_id"]) && $_GET["campaign_id"]))
{
    include_once ('global_header.php');
    $campaign_id = $_GET["campaign_id"];
    $user_id = $session->value('user_id');
    $np_userid = $user_id;

    $query = "SELECT * FROM funders LEFT JOIN bl2_users ON (funders.user_id=bl2_users.id) ";
    $query.= "WHERE funders.campaign_id=" . $np_userid . " ORDER BY funders.created_at DESC";
	
    $funders_result = $db->query($query);
	$nrElement = mysql_num_rows($funders_result);
	
 	$per_page = 2;
	$total_pages = ceil(($nrElement - 1) / $per_page);
	if (isset($_GET['page_selected'])) {
		$page_nr = $_GET['page_selected'];
    } else {
     	$page_nr = 1;
    }
    $start = ($page_nr - 1) * $per_page;
	
	$query = "SELECT * FROM funders LEFT JOIN bl2_users ON (funders.user_id=bl2_users.id) ";
    $query.= "WHERE funders.campaign_id=" . $np_userid . " ORDER BY funders.created_at DESC";
	$query .= " limit $start, $per_page";
	
	$funders_result = $db->query($query);
	$funders = array();
	while ($result = $db->fetch_array($funders_result)) {
		$funders[] = $result;
	}
	$template->set("page_selected", $page_nr);
    $template->set("total_pages", $total_pages);

$np_row =  $db->get_sql_row("SELECT logo, banner  FROM np_users WHERE user_id ='" . $np_userid . "'");

$np_logo = $np_row['logo'];


$template->set('np_logo', $np_logo);

$template->set('np_banner', $np_row['banner']);

$compaignData =  $db->get_sql_row("SELECT * FROM np_users WHERE user_id = " . $campaign_id);


if (isset($compaignData["probid_user_id"]) && $compaignData["probid_user_id"] == $user_id) {

    $compaignId = $compaignData["user_id"];

    $project_update_query_result = $db->query("SELECT * FROM project_updates LEFT JOIN bl2_users ON project_updates.user_id =  bl2_users.id WHERE project_id=" . $compaignId . " ORDER BY project_updates.id DESC");

    $project_updates = array();

    while ($query_result =  mysql_fetch_array($project_update_query_result)) {

        $project_updates[] = $query_result;

    }

    if (file_exists('./includes/class_project_rewards.php')) {
        require_once ('./includes/class_project_rewards.php');
    } elseif (file_exists('./../includes/class_project_rewards.php')) {
        require_once ('./../includes/class_project_rewards.php');
    }else{
        echo "File Not Found : ".'./includes/class_project_rewards.php';
    }

	$projectRewards   = new projectRewards();
	$project_rewards = $projectRewards->getAllRewards($compaignId, 'amount');

    $menuTemplate = new template('themes/' . $setts['default_theme'] . '/templates/campaign/');

    $menuTemplate->set('compaignData',$compaignData);

    $menuTemplate->set('projectUpdates',$project_updates);

    $menuTemplate->set('projectRewards',$project_rewards);

    $menuTemplate->set('funders', $funders );

    $template->set("cHome",$menuTemplate->process("home.tpl.php"));

    include_once("includes/campaign_comments.php");

    $menuTemplate->set('comments',$comments);

    $menuTemplate->set('compaignId',$compaignId);

    $template->set("cComments",$menuTemplate->process("comments.tpl.php"));

    $template->set("cFunders",$menuTemplate->process("funders.tpl.php"));

    $template->set("cRewards",$menuTemplate->process("rewards.tpl.php"));

    $template->set("cSupport",$menuTemplate->process("support.tpl.php"));

    $template->set("cUpdates",$menuTemplate->process("updates.tpl.php"));

    $template->change_path('themes/' . $setts['default_theme'] . '/templates/');

    $template->set('compaigns', $compaignData );



    if (!isset($_COOKIE['np_userid']))

        setcookie('np_userid', $compaignData['user_id']);



    $template_output .= $template->process('mainpage_landingpage.tpl.php');

    include_once ('global_footer.php');

    echo $template_output;
} else {
    header_redirect('/');
}
}
else
{
    header_redirect('/');
}