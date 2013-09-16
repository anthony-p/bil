<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }

include_once ('includes/class_formchecker.php');
include_once ('includes/class_custom_field.php');
include_once ('includes/class_item.php');
include_once ('includes/functions_item.php');

$template->set('header_browse_auctions', $header_browse_auctions); 

$limit = 10;
if (!isset($start) || !$start)
    $start = 1;
/**
 * Generate Alphabetical navigation bar
 */
$alphabetically = array();
$alphabet = array("#","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
for($i = 0, $maxi = sizeof($alphabet); $i < $maxi; $i++){
    if($_REQUEST['alphabetically'] == $alphabet[$i])
        $alphabetically[$alphabet[$i]] = "";
    else
        $alphabetically[$alphabet[$i]] = page_alphabetically($page_url.'.php', 'a.name', $start, $limit, $additional_vars, $alphabet[$i]);
}
/* ===== End Generate Alphabetical navigation bar ====*/

// Add request at additional variables.
$additional_vars .= '&alphabetically='.$_REQUEST['alphabetically'];

$order_link = '&order_field=' . $order_field . '&order_type=' . $order_type . '&alphabetically='.$_REQUEST['alphabetically'];
$limit_link = '&start=' . $start . '&limit=' . $limit;

$template->set('page_order_itemname', page_order($page_url . '.php', 'a.name', $start, $limit, $additional_vars, MSG_ITEM_TITLE));
$template->set('page_order_start_price', page_order($page_url . '.php', 'a.start_price', $start, $limit, $additional_vars, MSG_START_BID));
$template->set('page_order_max_bid', page_order($page_url . '.php', 'a.max_bid', $start, $limit, $additional_vars, MSG_MAX_BID));
$template->set('page_order_nb_bids', page_order($page_url . '.php', 'a.nb_bids', $start, $limit, $additional_vars, MSG_NR_BIDS));
$template->set('page_order_end_time', page_order($page_url . '.php', 'a.end_time', $start, $limit, $additional_vars, MSG_ENDS));
$template->set('page_order_current_price', page_order($page_url . '.php', 'current_price', $start, $limit, $additional_vars, MSG_CURRENT_PRICE));
$template->set('alphabetically',$alphabetically);

$nb_items = $db->get_sql_number("SELECT a.advert_id FROM " . DB_PREFIX . "partners a " . $where_query . " GROUP BY a.advert_id");

$template->set('nb_items', $nb_items);

$template->set('redirect', $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);

if ($nb_items)
{
#	$pagination = paginate($start, $limit, $nb_items, $page_url . '.php', $additional_vars . $order_link); //g
$pagination = paginate($start, $limit, $nb_items, $page_url . '.php', $order_link); //g
	$template->set('pagination', $pagination);

	if (empty($force_index))
	{
		$force_index = item::force_index($order_field);
	}
	
	$order_field = ($order_field == 'current_price') ? 'IF(a.max_bid > a.start_price, a.max_bid, IF(a.auction_type=\'first_bidder\', a.fb_current_bid, a.start_price))' : $order_field;	

	//LEFT JOIN " . DB_PREFIX . "auction_media am ON am.advert_id=a.advert_id AND am.media_type=1 AND am.upload_in_progress=0 
	$sql_select_auctions = $db->query("SELECT a.advert_id, a.name, a.advert_code, a.advert_url, a.advert_pct, a.nb_bids, a.currency, 
		a.end_time, a.closed, a.bold, a.hl, a.buyout_price, a.is_offer, a.reserve_price, a.owner_id, a.postage_amount, 
		a.fb_current_bid, a.auction_type FROM 
		" . DB_PREFIX . "partners a " . $force_index . " 
		" . $where_query . "
		ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit); //g

	(string) $browse_auctions_content = null; //g
	while ($partnersitem_details = $db->fetch_array($sql_select_auctions))
	{
		if (defined(IS_SHOP) && IS_SHOP == 1)
		{
			$background = ($counter++%2) ? 'c2_shop' : 'c3_shop';
		}
		else 
		{
			$background = ($counter++%2) ? 'c1' : 'c2';
		}
	
		$background .= ($partnersitem_details['bold']) ? ' bold_item' : '';
		$background .= ($partnersitem_details['hl']) ? ' hl_item' : '';
		
		if ($page_url == 'global_partners')
		{
			$auction_link =  $partnersitem_details['advert_url'];
		}
		else 
		{
			$auction_link = process_link('auction_details', array('name' => $partnersitem_details['name'], 'advert_id' => $partnersitem_details['advert_id']));
		}
		
		$media_url = $db->get_sql_field("SELECT media_url FROM " . DB_PREFIX . "auction_media WHERE auction_id=" . $partnersitem_details['advert_id'] . " AND 
			media_type=1 AND upload_in_progress=0 ORDER BY media_id ASC", 'media_url');
		$auction_image = (!empty($media_url)) ? $media_url : 'themes/' . $setts['default_theme'] . '/img/system/noimg.gif';
		
		$current_price = ($partnersitem_details['auction_type'] == 'first_bidder') ? $partnersitem_details['fb_current_bid'] : max($partnersitem_details['start_price'], $partnersitem_details['max_bid']);
				$browse_auctions_content .= '<tr class="$background"> '.
	    	//'	<td align="center"></td> '.
	    	'	<td class="logos" align="center">' . display_globalad($partnersitem_details['advert_code'])  . '</td> '.
			
			//'	<td><a href="' . $auction_link . '">' . $partnersitem_details['name'] . '</a> ' . '</td> '.
			'	<td class="link"><a href="' . $auction_link . '">' . $partnersitem_details['name'] . '</a> ' . '</td> '.
			'	<td class="percent">'.($partnersitem_details['advert_pct']/2).'</td> '.
            '	<td class="center golink"> <a href="'.$auction_link.'">'.MSG_PARTNER_BTN_GO.'</a></td> '.
			//	<td>' . display_globalad($partnersitem_details['advert_url']) .  '</td> '.
	    	//'	<td align="center">' . $fees->display_amount($partnersitem_details['start_price'], $partnersitem_details['currency']) . '</td> '.
	    	//'	<td align="center">' . $fees->display_amount($partnersitem_details['max_bid'], $partnersitem_details['currency']) . '</td> '.
	    	//'	<td align="center">' . $partnersitem_details['nb_bids'] . '</td> '.
	    	//'	<td align="center">' . $fees->display_amount($current_price, $partnersitem_details['currency']) . '</td> '.
	    	//'	<td align="center">' . time_left($partnersitem_details['end_time']) . '</td> '.
	  		'</tr> '
			
			
			;
	}
}
else 
{
	if ($page_url == 'auction_search')
	{
		header_redirect('search.php?search_empty=1');	
	}
	else 
	{
		$browse_auctions_content = '<tr><td colspan="6" align="center">' . GMSG_NO_ITEMS_MSG . '</td></tr>';
	}
}
$template->set('browse_auctions_content', $browse_auctions_content);

$template_output .= $template->process('browse_partners.tpl.php');

?>
