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
    //**********************************************************************

//$adult_categories = array();
//
//$sql_select_adult_cats = $db->query("SELECT * FROM " . DB_PREFIX . "categories WHERE minimum_age>0 AND parent_id=0");
//while ($adult_cats = $db->fetch_array($sql_select_adult_cats))
//{
//    reset($categories_array);
//    foreach ($categories_array as $key => $value)
//    {
//        if ($adult_cats['category_id'] == $key)
//        {
//            list($category_name, $tmp_user_id) = $value;
//        }
//    }
//    reset($categories_array);
//    while (list($cat_array_id, $cat_array_details) = each($categories_array))
//    {
//        list($cat_array_name, $cat_user_id) = $cat_array_details;
//        $categories_match = strpos($cat_array_name, $category_name);
//        if (trim($categories_match) == "0")
//        {
//            $adult_categories[] = intval($cat_array_id);
//        }
//    }
//}
//
//$adult_cats_query = null;
//if (count($adult_categories) > 0)
//{
//    $adult_cats_list = $db->implode_array($adult_categories, ', ', false);
//    $adult_cats_query = ' AND (category_id NOT IN (' . $adult_cats_list . ') AND addl_category_id NOT IN (' . $adult_cats_list . '))';
//}
//
//$layout['hpfeat_nb'] = 2;
//$layout['hpfeat_max'] =6;
//
////Featured auctions by user
//$probid_user = $db->get_sql_row('SELECT npu.probid_user_id, u.username FROM np_users npu LEFT JOIN ' . DB_PREFIX . 'users u ON u.user_id = npu.probid_user_id WHERE npu.user_id = '.$np_userid.'
//
//                                            AND user_link_active = 1');
//
//if($probid_user['probid_user_id']){
//
//    $probid_user_box = true;
//
//    $template->set('probid_user_box', $probid_user_box);
//
//
//
//    $displayed_acuctions_user_items = 14;
//
//    $template->set('displayed_acuctions_user_items', $displayed_acuctions_user_items);
//
//
//
//    $featured_auctions_user_header = header1('<span class="main-title">Featured Auctions from '.$probid_user['username'].'</span> <span class="viewAll"><a href="' .
//
//        process_link('auctions_show', array('option' => 'localuser', 'user' => $probid_user['probid_user_id'])) . '">' . MSG_VIEW_ALL . '</a></span>');
//
//    $template->set('featured_auctions_user_header', $featured_auctions_user_header);
//
//
//
//    $select_auctions_user = "SELECT auction_id, name, start_price, max_bid, currency, end_time FROM " . DB_PREFIX . "auctions WHERE npuser_id  =$np_userid AND
//
//            owner_id = " .$probid_user['probid_user_id']. " AND active=1 AND approved=1 AND closed=0 AND creation_in_progress=0 AND deleted=0 AND
//
//            list_in!='store'" . $adult_cats_query. " ORDER BY RAND()";
//
//    $result = $db->query($select_auctions_user);
//
//    while ($item_details = $db->fetch_array($result)){
//
//        $items_user_details[] = $item_details;
//
//    }
//
//
//
//    $template->set('items_user_details', $items_user_details);
//
//
//
//}





#Coupons (magento) recent deals



//$coupons_recent_deals_header = header1('Local merchants' . ' <span class="viewAll"><a href="'.$coupon_url.'/index.php/active-deals.html?id='.$np_userid.'">' . MSG_VIEW_ALL . '</a></span>');



//$magento_items_nr = 6;
//$template->set('coupons_recent_deals_header', $coupons_recent_deals_header);
//$template->set('magento_items_nr', $magento_items_nr);

/*
    $woptions['soap_version'] = SOAP_1_2;
    $woptions['login'] = $coupon_http_username;
    $woptions['password'] = $coupon_http_password;
    $proxy = new SoapClient($coupon_url."/index.php/api/soap/?wsdl",$woptions);
    $sessionId = $proxy->login($coupon_soap_username, $coupon_soap_password);

    $filters = array(
        'limit' => $magento_items_nr,
        'np_user_id' => $np_userid
    );
    $magento_items = $proxy->call($sessionId, 'bcustomer_authentication.getRecentDeals', array($filters));
*/
//$magento_items = '';


/*

    $filters = array(

        'featured' => 1,

        'status'   => 1,

        'np_user_id' => $np_userid

    );



    $products = $proxy->call($sessionId, 'product.list', array($filters));



    $magento_items = array();



    foreach($products as $product)

    {

        $item['name'] = $product['name'];

        $start = microtime_float();

        $product_images = $proxy->call($sessionId, 'catalog_product_attribute_media.list', $product['product_id']);

        $end = microtime_float();

        foreach($product_images as $product_image)

        {

            if(count($product_image['types']))

                $item['image_url'] = $product_image['url'];

        }

        $product_info = $proxy->call($sessionId, 'product.info', $product['product_id']);

        $item['url'] = $product_info['url_path'];

        $magento_items[] = $item;

    }

*/

$funders_result = $db->query( "SELECT * FROM funders LEFT JOIN bl2_users ON (funders.user_id=bl2_users.id) WHERE funders.campaign_id=" . $np_userid . " ORDER BY funders.created_at DESC");

$funders = array();


while ($result = $db->fetch_array($funders_result)) {

    $funders[] = $result;

}

$np_row =  $db->get_sql_row("SELECT logo, banner  FROM np_users WHERE user_id ='" . $np_userid . "'");

$np_logo = $np_row['logo'];


$template->set('np_logo', $np_logo);

$template->set('np_banner', $np_row['banner']);

$compaignData =  $db->get_sql_row("SELECT * FROM np_users WHERE user_id = " . $campaign_id);


if (isset($compaignData["probid_user_id"]) && $compaignData["probid_user_id"] == $user_id) {
    // == == == == == == == == == == == == == == == == == == == == == == == ==

    /* Comment write/read from base*/

    $compaignId = $compaignData["user_id"];

    $project_update_query_result = $db->query("SELECT * FROM project_updates LEFT JOIN bl2_users ON project_updates.user_id =  bl2_users.id WHERE project_id=" . $compaignId . " ORDER BY project_updates.id DESC");

    $project_updates = array();

    while ($query_result =  mysql_fetch_array($project_update_query_result)) {

        $project_updates[] = $query_result;

    }

    if (file_exists(__DIR__ . '/includes/class_project_rewards.php')) {
        require_once (__DIR__ . '/includes/class_project_rewards.php');
    } elseif (file_exists(__DIR__ . '/../includes/class_project_rewards.php')) {
        require_once (__DIR__ . '/../includes/class_project_rewards.php');
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

    /* == == == == == == == == == == == == == == == == == == == == == == ==*/

    $template->set("cComments",$menuTemplate->process("comments.tpl.php"));

    $template->set("cFunders",$menuTemplate->process("funders.tpl.php"));

    $template->set("cRewards",$menuTemplate->process("rewards.tpl.php"));

    $template->set("cSupport",$menuTemplate->process("support.tpl.php"));

    $template->set("cUpdates",$menuTemplate->process("updates.tpl.php"));

// == == == == == == == == == == == == == == == == == == == == == == == ==



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
    //**********************************************************************
}
else
{
    header_redirect('/');
}