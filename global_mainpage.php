<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
## Retail Mod -> hide adult items on main page						##
#################################################################

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

#Coupons (magento) recent deals

    $coupons_recent_deals_header = header1('Recent Deals' . ' <span class="viewAll"><a href="'.$coupon_url.'/index.php/recent-deals.html">' . MSG_VIEW_ALL . '</a></span>');

    $template->set('coupons_recent_deals_header', $coupons_recent_deals_header);

    $woptions['soap_version'] = SOAP_1_2;
    $woptions['login'] = $coupon_http_username;
    $woptions['password'] = $coupon_http_password;

    $proxy = new SoapClient($coupon_url."/index.php/api/soap/?wsdl",$woptions);
    $sessionId = $proxy->login($coupon_soap_username, $coupon_soap_password);

    $filters = array(
        'featured' => 1,
        'status'   => 1
    );

    $products = $proxy->call($sessionId, 'product.list', array($filters));

    $magento_items = array();
    foreach($products as $product)
    {
        $item['name'] = $product['name'];
        $product_images = $proxy->call($sessionId, 'catalog_product_attribute_media.list', $product['product_id']);
        foreach($product_images as $product_image)
        {
            if(count($product_image['types']))
                $item['image_url'] = $product_image['url'];
        }
        $product_info = $proxy->call($sessionId, 'product.info', $product['product_id']);
        $item['url'] = $product_info['url_path'];
        $magento_items[] = $item;
    }

    $template->set('magento_items', $magento_items);
#Coupons (magento) recent deals end.


if ($layout['hpfeat_nb'])## PHP Pro Bid v6.00 home page featured auctions
{
	$featured_auctions_header = header1(MSG_BAR_FEATURED_AUCTIONS . ' <span class="viewAll"><a href="' . process_link('categories') . '">' . MSG_VIEW_ALL . '</a></span>');

	$template->set('featured_auctions_header', $featured_auctions_header);

	$select_condition = "WHERE
		hpfeat=1 AND active=1 AND approved=1 AND closed=0 AND creation_in_progress=0 AND deleted=0 AND
		list_in!='store'" . $adult_cats_query;

	$template->set('featured_columns', min((floor($db->count_rows('auctions', $select_condition)/$layout['hpfeat_nb']) + 1), ceil($layout['hpfeat_max']/$layout['hpfeat_nb'])));

	$template->set('feat_fees', $fees);
	$template->set('feat_db', $db);

	$item_details = $db->random_rows('auctions', 'auction_id, name, start_price, max_bid, currency, end_time', $select_condition, $layout['hpfeat_max']);
	$template->set('item_details', $item_details);
}



$globalads="1";
if ($globalads=="1")## featured ads
{
	$featured_auctions_header = header1(MSG_BAR_FEATURED_AUCTIONS . ' <span class="viewAll"><a href="' . process_link('categories') . '">' . MSG_VIEW_ALL . '</a></span>');
	$template->set('featured_auctions_header', $featured_auctions_header);

	$select_condition = "WHERE
		hpfeat=1 AND active=1 AND approved=1 AND closed=0 AND deleted=0 AND
		list_in!='store'" . $adult_cats_query;

	$template->set('featured_columns', min((floor($db->count_rows('partners', $select_condition)/$layout['hpfeat_nb']) + 1), ceil($layout['hpfeat_max']/$layout['hpfeat_nb'])));

	$template->set('feat_fees', $fees);
	$template->set('feat_db', $db);

	$global_item_details = $db->random_rows('partners', 'advert_id, name, description, advert_code, big_banner_code, currency, end_time', $select_condition, $layout['hpfeat_max']);
	$template->set('global_item_details', $global_item_details);
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


$template->change_path('themes/' . $setts['default_theme'] . '/templates/');

$template_output .= $template->process('mainpage.tpl.php');

$template->change_path('templates/');
?>
