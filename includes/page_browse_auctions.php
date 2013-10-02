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

$limit = 20; 

$order_link = '&order_field=' . $order_field . '&order_type=' . $order_type; 
$limit_link = '&start=' . $start . '&limit=' . $limit; 

$template->set('page_order_itemname', page_order($page_url . '.php', 'a.name', $start, $limit, $additional_vars, MSG_ITEM_TITLE));
$template->set('page_order_start_price', page_order($page_url . '.php', 'a.start_price', $start, $limit, $additional_vars, MSG_START_BID));
$template->set('page_order_max_bid', page_order($page_url . '.php', 'a.max_bid', $start, $limit, $additional_vars, MSG_MAX_BID));
$template->set('page_order_nb_bids', page_order($page_url . '.php', 'a.nb_bids', $start, $limit, $additional_vars, MSG_NR_BIDS));
$template->set('page_order_end_time', page_order($page_url . '.php', 'a.end_time', $start, $limit, $additional_vars, MSG_ENDS));
$template->set('page_order_current_price', page_order($page_url . '.php', 'current_price', $start, $limit, $additional_vars, MSG_CURRENT_PRICE));

$nb_items = $db->get_sql_number("SELECT a.auction_id FROM " . DB_PREFIX . "auctions a " . $where_query . " GROUP BY a.auction_id");

$template->set('nb_items', $nb_items);

$template->set('redirect', $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);

if ($nb_items)
{
	$pagination = paginate($start, $limit, $nb_items, $page_url . '.php', $additional_vars . $order_link); //g
	$template->set('pagination', $pagination); 

	if (empty($force_index))
	{
		$force_index = item::force_index($order_field);
	}
	
	$order_field = ($order_field == 'current_price') ? 'IF(a.max_bid > a.start_price, a.max_bid, IF(a.auction_type=\'first_bidder\', a.fb_current_bid, a.start_price))' : $order_field;	

	//LEFT JOIN " . DB_PREFIX . "auction_media am ON am.auction_id=a.auction_id AND am.media_type=1 AND am.upload_in_progress=0 
	$sql_select_auctions = $db->query("SELECT a.auction_id, a.name, a.start_price, a.max_bid, a.nb_bids, a.currency, 
		a.end_time, a.closed, a.bold, a.hl, a.buyout_price, a.is_offer, a.reserve_price, a.owner_id, a.postage_amount, 
		a.fb_current_bid, a.auction_type FROM 
		" . DB_PREFIX . "auctions a " . $force_index . " 
		" . $where_query . "
		ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit); //g

	(string) $browse_auctions_content = null; //g
	while ($item_details = $db->fetch_array($sql_select_auctions))
	{
		if (IS_SHOP == 1)
		{
			$background = ($counter++%2) ? 'c2_shop' : 'c3_shop';
		}
		else 
		{
			$background = ($counter++%2) ? 'c1' : 'c2';
		}
	
		$background .= ($item_details['bold']) ? ' bold_item' : '';
		$background .= ($item_details['hl']) ? ' hl_item' : '';
		
		if ($page_url == 'auction_search')
		{
			$auction_link = 'auction_details.php?auction_search=1&auction_id=' . $item_details['auction_id'] . $additional_vars;
		}
		else 
		{
			$auction_link = process_link('auction_details', array('name' => $item_details['name'], 'auction_id' => $item_details['auction_id']));
		}
		
		$media_url = $db->get_sql_field("SELECT media_url FROM " . DB_PREFIX . "auction_media WHERE auction_id=" . $item_details['auction_id'] . " AND 
			media_type=1 AND upload_in_progress=0 ORDER BY media_id ASC", 'media_url');
		$auction_image = (!empty($media_url)) ? $media_url : 'themes/' . $setts['default_theme'] . '/img/system/noimg.gif';
		
		$current_price = ($item_details['auction_type'] == 'first_bidder') ? $item_details['fb_current_bid'] : max($item_details['start_price'], $item_details['max_bid']);
		
		$browse_auctions_content .= '<tr> '.
	    	'	<td align="center"><input type="checkbox" name="auction_id[]" value="' . $item_details['auction_id'] . '"></td> '.
	    	'	<td align="center"><a href="' . $auction_link . '"><img src="thumbnail.php?pic=' . $auction_image . '&w=50&sq=Y&b=Y" border="0" alt="' . $item_details['name'] . '"></a></td> '.
	    	'	<td class="itemTitle"><a href="' . $auction_link . '">' . $item_details['name'] . '</a> ' . item_pics($item_details) . '</td> '.
	    	//'	<td align="center">' . $fees->display_amount($item_details['start_price'], $item_details['currency']) . '</td> '.
	    	//'	<td align="center">' . $fees->display_amount($item_details['max_bid'], $item_details['currency']) . '</td> '.
	    	'	<td align="center">' . $item_details['nb_bids'] . '</td> '.
	    	'	<td align="center">' . $fees->display_amount($current_price, $item_details['currency']) . '</td> '.
	    	'	<td align="center">' . time_left($item_details['end_time']) . '</td> '.
	  		'</tr> ';
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

$template_output .= $template->process('browse_auctions.tpl.php');

?>
