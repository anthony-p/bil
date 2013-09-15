<?php
/**
 * Created by Lilian Codreanu.
 * User: Lilian Codreanu
 * Date: 8/9/13
 * Time: 1:38 AM
 * To change this template use File | Settings | File Templates.
 */

$adult_categories = array();

$sql_select_adult_cats = $db->query("SELECT * FROM " . DB_PREFIX . "categories WHERE minimum_age>0 AND parent_id=0");
while ($adult_cats = $db->fetch_array($sql_select_adult_cats))
{
	reset($categories_array);
	foreach ($categories_array as $key => $value)
	{
		if ($adult_cats['category_id'] == $key)
		{
			list($category_name, $tmp_user_id) = $value;
		}
	}
	reset($categories_array);
	while (list($cat_array_id, $cat_array_details) = each($categories_array))
	{
		list($cat_array_name, $cat_user_id) = $cat_array_details;
		$categories_match = strpos($cat_array_name, $category_name);
		if (trim($categories_match) == "0")
		{
			$adult_categories[] = intval($cat_array_id);
		}
	}
}

$adult_cats_query = null;
if (count($adult_categories) > 0)
{
	$adult_cats_list = $db->implode_array($adult_categories, ', ', false);
	$adult_cats_query = ' AND (category_id NOT IN (' . $adult_cats_list . ') AND addl_category_id NOT IN (' . $adult_cats_list . '))';
}

$layout['hpfeat_nb'] = 2;
$layout['hpfeat_max'] =6;

//Featured auctions by user
$probid_user = $db->get_sql_row('SELECT npu.probid_user_id, u.username FROM np_users npu LEFT JOIN ' . DB_PREFIX . 'users u ON u.user_id = npu.probid_user_id WHERE npu.user_id = '.$np_userid.'

                                            AND user_link_active = 1');

if($probid_user['probid_user_id']){

    $probid_user_box = true;
    $template->set('probid_user_box', $probid_user_box);


    $displayed_acuctions_user_items = 14;
    $template->set('displayed_acuctions_user_items', $displayed_acuctions_user_items);

    $featured_auctions_user_header = header1('<span class="main-title">Featured Auctions from '.$probid_user['username'].'</span> <span class="viewAll"><a href="' .
        process_link('auctions_show', array('option' => 'localuser', 'user' => $probid_user['probid_user_id'])) . '">' . MSG_VIEW_ALL . '</a></span>');
    $template->set('featured_auctions_user_header', $featured_auctions_user_header);

    $select_auctions_user = "SELECT auction_id, name, start_price, max_bid, currency, end_time FROM " . DB_PREFIX . "auctions WHERE npuser_id  =$np_userid AND
            owner_id = " .$probid_user['probid_user_id']. " AND active=1 AND approved=1 AND closed=0 AND creation_in_progress=0 AND deleted=0 AND
            list_in!='store'" . $adult_cats_query. " ORDER BY RAND()";
    $result = $db->query($select_auctions_user);
    while ($item_details = $db->fetch_array($result)){
        $items_user_details[] = $item_details;
    }

    $template->set('items_user_details', $items_user_details);

}


#Coupons (magento) recent deals
    $coupons_recent_deals_header = header1('Local merchants' . ' <span class="viewAll"><a href="'.$coupon_url.'/index.php/active-deals.html?id='.$np_userid.'">' . MSG_VIEW_ALL . '</a></span>');

    $magento_items_nr = 6;
    $template->set('coupons_recent_deals_header', $coupons_recent_deals_header);
    $template->set('magento_items_nr', $magento_items_nr);

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
$magento_items = '';


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

    $template->set('magento_items', $magento_items);



#Coupons (magento) recent deals end.





$localauctions="1";

if ($localauctions=="1")## landing page featured local auctions

{



	$featured_localauctions_header = header1('<span class="main-title">' . MSG_FEATURED_LOCALAUCTIONS . '</span> <span class="viewAll"><a href="' . process_link('auctions_show', array('option' => 'local')) . '">' . MSG_VIEW_ALL . '</a></span>');

	$template->set('featured_localauctions_header', $featured_localauctions_header);



	$select_localcondition = "WHERE npuser_id=$np_userid AND

		hpfeat=1 AND active=1 AND approved=1 AND closed=0 AND creation_in_progress=0 AND deleted=0 AND

		list_in!='store'" . $adult_cats_query;



	$template->set('featured_columns', min((floor($db->count_rows('auctions', $select_localcondition)/$layout['hpfeat_nb']) + 1), ceil($layout['hpfeat_max']/$layout['hpfeat_nb'])));



	$template->set('feat_fees', $fees);

	$template->set('feat_db', $db);

	$item_localdetails = $db->random_rows('auctions', 'auction_id, name, start_price, max_bid, currency, end_time', $select_localcondition, $layout['hpfeat_max']);

	$template->set('item_localdetails', $item_localdetails);

}



$localshops="1";

if ($localshops=="1")## landing page featured local shops

{

include_once ('local_featured_stores.php');

}



$globalads="1";

if ($globalads=="1")## featured ads

{

    $featured_columns = 14;



	$featured_auctions_header = header1(MSG_BAR_FEATURED_AUCTIONS . ' <span class="viewAll"><a href="' . process_link('categories') . '">' . MSG_VIEW_ALL . '</a></span>');

	$template->set('featured_auctions_header', $featured_auctions_header);



	$select_condition = "WHERE active=1 AND approved=1 AND closed=0 AND deleted=0 AND

		list_in!='store'  AND advert_id = 34  " . $adult_cats_query;

    $global_item_amazon = array();

    $global_item_amazon = $db->random_rows('partners', 'advert_id, name, description, advert_code, currency, end_time', $select_condition, 1);



    $select_condition = "WHERE active=1 AND approved=1 AND closed=0 AND deleted=0 AND

		list_in!='store' AND advert_id != 34  " . $adult_cats_query;

	$template->set('featured_columns', $featured_columns);



	$template->set('feat_fees', $fees);

	$template->set('feat_db', $db);



	$global_item_details = $db->random_rows('partners', 'advert_id, name, description, advert_code, currency, end_time', $select_condition, $featured_columns - 1);

	$template->set('global_item_details', array_merge($global_item_amazon, $global_item_details));

}





$localwantads="1";

#local want ads

if ($localwantads=="1")## landing page recent local want ads

{

	$recent_localwa_header = header4(MSG_RECENTLY_LISTED_LOCALWANTED_ADS . ' <span class="viewAll"><a href="' . process_link('localwanted_ads') . '">' . MSG_VIEW_ALL . '</a></span>');

	$template->set('recent_localwa_header', $recent_localwa_header);



	$sql_select_recent_localwa = $db->query("SELECT wanted_ad_id, start_time, name FROM " . DB_PREFIX . "wanted_ads

		FORCE INDEX (wa_mainpage) WHERE npuser_id=$np_userid AND

		closed=0 AND active=1 AND deleted=0 AND creation_in_progress=0 " . $adult_cats_query . " ORDER BY

		start_time DESC LIMIT 0," . $layout['nb_want_ads']);



	$template->set('sql_select_recent_localwa', $sql_select_recent_localwa);

}





if ($layout['hpfeat_nb'])## PHP Pro Bid v6.00 home page featured auctions

{

	$featured_auctions_header = header1(MSG_BAR_FEATURED_AUCTIONS . ' <span class="viewAll"><a href="' . process_link('auctions_show', array('option' => 'featured')) . '">' . MSG_VIEW_ALL . '</a></span>');

	$template->set('featured_auctions_header', $featured_auctions_header);



	$select_condition = "WHERE

		hpfeat=1 AND active=1 AND approved=1 AND closed=0 AND creation_in_progress=0 AND deleted=0 AND

		list_in!='store'" . $adult_cats_query;



	$template->set('featured_columns', min((floor($db->count_rows('auctions', $select_condition)/$layout['hpfeat_nb']) + 1), ceil($layout[6]/$layout['hpfeat_nb'])));



	$template->set('feat_fees', $fees);

	$template->set('feat_db', $db);



	$item_details = $db->random_rows('auctions', 'auction_id, name, start_price, max_bid, currency, end_time', $select_condition, $layout['6']);

	$template->set('item_details', $item_details);

}



if ($layout['r_hpfeat_nb'] && $setts['enable_reverse_auctions'])

{

	$featured_reverse_auctions_header = header1(MSG_FEATURED_REVERSE_AUCTIONS);

	$template->set('featured_reverse_auctions_header', $featured_reverse_auctions_header);



	$select_condition = "WHERE

		hpfeat=1 AND active=1 AND closed=0 AND creation_in_progress=0 AND deleted=0";



	$template->set('featured_ra_columns', min((floor($db->count_rows('reverse_auctions', $select_condition)/$layout['r_hpfeat_nb']) + 1), ceil($layout['r_hpfeat_max']/$layout['r_hpfeat_nb'])));



	$template->set('feat_fees', $fees);

	$template->set('feat_db', $db);



	$ra_details = $db->random_rows('reverse_auctions', 'reverse_id, name, budget_id, nb_bids, currency, end_time', $select_condition, $layout['r_hpfeat_max']);

	$template->set('ra_details', $ra_details);

}



if ($layout['nb_recent_auct'])

{

	$recent_auctions_header = header2(MSG_RECENTLY_LISTED_AUCTIONS . ' <span class="viewAll"><a href="' . process_link('auctions_show', array('option' => 'recent')) . '">' . MSG_VIEW_ALL . '</a></span>');

	$template->set('recent_auctions_header', $recent_auctions_header);



	$sql_select_recent_items = $db->query("SELECT auction_id, start_time, start_price, currency, name,

		bold, hl, buyout_price, is_offer, reserve_price, max_bid, nb_bids, owner_id, postage_amount FROM " . DB_PREFIX . "auctions

		FORCE INDEX (auctions_start_time) WHERE

		closed=0 AND active=1 AND approved=1 AND deleted=0 AND creation_in_progress=0 AND

		list_in!='store' " . $adult_cats_query . " ORDER BY start_time DESC LIMIT 0," . $layout['nb_recent_auct']);



	$template->set('sql_select_recent_items', $sql_select_recent_items);

}



if ($layout['nb_popular_auct'])

{

	$popular_auctions_header = header3(MSG_POPULAR_AUCTIONS . ' <span class="viewAll"><a href="' . process_link('auctions_show', array('option' => 'popular')) . '">' . MSG_VIEW_ALL . '</a></span>');

	$template->set('popular_auctions_header', $popular_auctions_header);



	$sql_select_popular_items = $db->query("SELECT auction_id, max_bid, currency, name, bold, hl,

		buyout_price, is_offer, reserve_price, nb_bids, owner_id, postage_amount FROM " . DB_PREFIX . "auctions

		FORCE INDEX (auctions_max_bid) WHERE

		closed=0 AND active=1 AND approved=1 AND deleted=0 AND creation_in_progress=0 AND

		list_in!='store' AND nb_bids>0 " . $adult_cats_query . " ORDER BY max_bid DESC LIMIT 0," . $layout['nb_popular_auct']);



	$template->set('sql_select_popular_items', $sql_select_popular_items);



	$is_popular_items = $db->num_rows($sql_select_popular_items);

	$template->set('is_popular_items', $is_popular_items);

}



if ($layout['nb_ending_auct'])

{

	$ending_auctions_header = header4(MSG_ENDING_SOON_AUCTIONS . ' <span class="viewAll"><a href="' . process_link('auctions_show', array('option' => 'ending')) . '">' . MSG_VIEW_ALL . '</a></span>');

	$template->set('ending_auctions_header', $ending_auctions_header);



	 $sql_select_ending_items = $db->query("SELECT auction_id, start_price, max_bid, end_time, currency, name, bold,

		hl, buyout_price, is_offer, reserve_price, nb_bids, owner_id, postage_amount, auction_type FROM " . DB_PREFIX . "auctions

		FORCE INDEX (auctions_end_time) WHERE

		closed=0 AND active=1 AND approved=1 AND deleted=0 AND creation_in_progress=0 AND

		list_in!='store' AND auction_type!='first_bidder' " . $adult_cats_query . " ORDER BY end_time ASC LIMIT 0," . $layout['nb_ending_auct']);



	$template->set('sql_select_ending_items', $sql_select_ending_items);

}



if ($layout['nb_want_ads'])

{

	$recent_wa_header = header4(MSG_RECENTLY_LISTED_WANTED_ADS . ' <span class="viewAll"><a href="' . process_link('wanted_ads') . '">' . MSG_VIEW_ALL . '</a></span>');

	$template->set('recent_wa_header', $recent_wa_header);



	$sql_select_recent_wa = $db->query("SELECT wanted_ad_id, start_time, name FROM " . DB_PREFIX . "wanted_ads

		FORCE INDEX (wa_mainpage) WHERE

		closed=0 AND active=1 AND deleted=0 AND creation_in_progress=0 " . $adult_cats_query . " ORDER BY

		start_time DESC LIMIT 0," . $layout['nb_want_ads']);



	$template->set('sql_select_recent_wa', $sql_select_recent_wa);

}



if ($layout['r_recent_nb'] && $setts['enable_reverse_auctions'])

{

	$recent_reverse_header = header4(MSG_MM_REVERSE_AUCTIONS . ' <span class="viewAll"><a href="' . process_link('reverse_auctions') . '">' . MSG_VIEW_ALL . '</a></span>');

	$template->set('recent_reverse_header', $recent_reverse_header);



	$sql_select_recent_reverse = $db->query("SELECT reverse_id, name, budget_id, nb_bids, currency, start_time, end_time FROM " . DB_PREFIX . "reverse_auctions

		WHERE	closed=0 AND active=1 AND deleted=0 AND creation_in_progress=0 ORDER BY

		start_time DESC LIMIT 0," . $layout['r_recent_nb']);



	$template->set('sql_select_recent_reverse', $sql_select_recent_reverse);

}


$funders_result = $db->query( "SELECT * FROM funders LEFT JOIN bl2_users ON (funders.user_id=bl2_users.id) WHERE funders.campaign_id=" . $np_userid . " ORDER BY funders.created_at DESC");

$funders = array();


while ($result = $db->fetch_array($funders_result)) {

    $funders[] = $result;

}



$np_row =  $db->get_sql_row("SELECT logo, banner  FROM np_users WHERE user_id ='" . $np_userid . "'");

$np_logo = $np_row['logo'];


$template->set('np_logo', $np_logo);

$template->set('np_banner', $np_row['banner']);

$compaignData =  $db->get_sql_row("SELECT * FROM np_users WHERE username = '$npusername' ");


// == == == == == == == == == == == == == == == == == == == == == == == ==

/* Comment write/read from base*/

$compaignId = $compaignData["user_id"];

$project_update_query_result = $db->query("SELECT * FROM project_updates LEFT JOIN bl2_users ON project_updates.user_id =  bl2_users.id WHERE project_id=" . $compaignId . " ORDER BY project_updates.id DESC");

$project_updates = array();

while ($query_result =  mysql_fetch_array($project_update_query_result)) {

    $project_updates[] = $query_result;

}

if ($session->value('user_id') && $compaignId)
{
    require_once (dirname(__FILE__) . '/includes/class_project_votes.php');
    $projectVotes = new projectVotes($session->value('user_id'), $compaignId);
    $vote_us = $projectVotes->getVotesElement();
    $template->set('vote_us', $vote_us);
} else {
    $template->set('vote_us', '');
}

require_once (dirname(__FILE__) . '/includes/class_project_rewards.php');
$projectRewards   = new projectRewards();
$project_rewards = $projectRewards->getAllRewards($compaignId, 'amount');

$menuTemplate = new template('themes/' . $setts['default_theme'] . '/templates/campaign/');

$menuTemplate->set('compaignData',$compaignData);

$menuTemplate->set('projectUpdates',$project_updates);

$menuTemplate->set('projectRewards',$project_rewards);

if($compaignData['cfc'] == 1){
	require_once (dirname(__FILE__) . '/includes/class_project_votes.php');
    $projectVotes = new projectVotes();
	$currentMonthVoteReport = $projectVotes->getVotesReportData(date('n'), date('Y'));
	$menuTemplate->set('voteReportMonth', date('F, Y'));
	$menuTemplate->set('todaysDate', date('M d, Y'));
	$menuTemplate->set('communityTotalFund', $compaignData['payment']);
	$menuTemplate->set('voteReportData', $currentMonthVoteReport);
}

$menuTemplate->set('funders', $funders );

$template->set("cHome",$menuTemplate->process("home.tpl.php"));



include_once("includes/campaign_comments.php");

$menuTemplate->set('comments',$comments);

$menuTemplate->set('compaignId',$compaignId);

/* == == == == == == == == == == == == == == == == == == == == == == ==*/

$template->set("cComments",$menuTemplate->process("comments.tpl.php"));

$template->set("cFunders",$menuTemplate->process("funders.tpl.php"));

 $catfeat_max2 = '4';
 $catfeat_nb2 = '4';
 $layout['catfeat_nb'] = 4;
 $layout['catfeat_max'] = 16;
 $where_query = '';
 $addl_where_query = '';

 (array) $partnersitem_details = null;

 $select_condition = $where_query . " WHERE a.active=1 AND a.approved=1 AND a.deleted=0
 			AND a.list_in!='store' AND a.catfeat='1'" . $addl_where_query;

 $partnersitem_details = $db->random_rows('partners a', 'a.advert_id, a.name, a.advert_code, a.advert_url, a.advert_pct,a.currency,
 		a.end_time, a.big_banner_code', $select_condition, $layout['catfeat_max']);


/* Find Amazon Partner and add to response*/
$select_condition = $where_query . " WHERE a.active=1 AND a.approved=1 AND a.deleted=0
 			AND a.list_in!='store' AND a.catfeat='1' AND a.advert_id = 34 " . $addl_where_query;
$parnerAmazon = $db->random_rows('partners a', 'a.advert_id, a.name, a.advert_code, a.advert_url, a.advert_pct,a.currency,
 		a.end_time, a.big_banner_code', $select_condition, $layout['catfeat_max']);
$partnersitem_details[0] = $parnerAmazon[0];

$menuTemplate->set("partners",$partnersitem_details);

$template->set("cRewards",$menuTemplate->process("rewards.tpl.php"));

if($compaignData['cfc'] == 1){
	$template->set("cVoteReport",$menuTemplate->process("vote_report.tpl.php"));
}

$template->set("cSupport",$menuTemplate->process("support.tpl.php"));

$template->set("cUpdates",$menuTemplate->process("updates.tpl.php"));

// == == == == == == == == == == == == == == == == == == == == == == == ==



$template->change_path('themes/' . $setts['default_theme'] . '/templates/');

$template->set('compaigns', $compaignData );


if (isset($_COOKIE['np_userid']))
    setcookie('np_userid', '',0);
setcookie('np_userid', $compaignData['user_id']);

$template_output .= $template->process('mainpage_landingpage.tpl.php');





$template->change_path('templates/');
?>