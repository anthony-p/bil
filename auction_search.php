<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

session_start();

define ('IN_SITE', 1);

include_once ('includes/global.php');

include_once ('global_header.php');

$header_browse_auctions = header5(MSG_AUCTION_SEARCH);
/**
 * below we have the variables that need to be declared in each separate browse page
 */
$page_url = 'auction_search';## PHP Pro Bid v6.00 we will now build the addl_query variable depending on the search type requested
(array) $query = null;
(string) $where_query = null;


if ($_REQUEST['option'] == 'basic_search')## PHP Pro Bid v6.00 quick search - header form
{
	$query[] = "a.closed=0";

	if (!empty($_REQUEST['basic_search'])) 
	{
		$basic_search = $db->rem_special_chars($_REQUEST['basic_search']);
		$basic_search = optimize_search_string($basic_search);

		//$query[] = "(MATCH (a.name, a.description) AGAINST ('+" . $basic_search . "' IN BOOLEAN MODE))";
		$query[] = "(MATCH (a.name) AGAINST ('+" . $basic_search . "' IN BOOLEAN MODE))";
		/**
		 * or the old and SLOW search using LIKE - disabled by default, just added the line in case 
		 * anyone might want to use this instead
		 */## PHP Pro Bid v6.00 $query[] = "(a.name LIKE '%" . $basic_search . "%' OR a.description LIKE '%" . $basic_search . "%')";
	}
}
else if ($_REQUEST['option'] == 'auction_search')## PHP Pro Bid v6.00 auction search - advanced form
{
	if (!empty($_REQUEST['src_auction_id']))
	{
		$auction_id = intval($_REQUEST['src_auction_id']);
		$query[] = "a.auction_id=" . $auction_id;		
	}
	if (!empty($_REQUEST['keywords_search']))
	{
		$keywords_search = $db->rem_special_chars($_REQUEST['keywords_search']);
		$keywords_search = optimize_search_string($keywords_search);

		if ($_REQUEST['search_description'] == 1)
		{
			$query[] = "MATCH (a.name, a.description) AGAINST ('+" . $keywords_search . "' IN BOOLEAN MODE)";		
		}
		else 
		{
			$query[] = "MATCH (a.name) AGAINST ('+" . $keywords_search . "' IN BOOLEAN MODE)";					
		}
		/**
		 * or the old and SLOW search using LIKE - disabled by default, just added the line in case 
		 * anyone might want to use this instead
		 */## PHP Pro Bid v6.00 $query[] = "(a.name LIKE '%" . $keywords_search . "%' OR a.description LIKE '%" . $keywords_search . "%')";
	}
	if ($_REQUEST['buyout_price'] == 1)
	{
		$query[] = "a.buyout_price>0";
	}
	if ($_REQUEST['reserve_price'] == 1)
	{
		$query[] = "a.reserve_price>0";
	}
	if ($_REQUEST['quantity'] == 1)
	{
		$query[] = "a.quantity>1";
	}
	if ($_REQUEST['enable_swap'] == 1)
	{
		$query[] = "a.enable_swap=1";
	}
	if (!empty($_REQUEST['list_in']))
	{
		$list_in = $db->rem_special_chars($_REQUEST['list_in']);
		$query[] = "a.list_in='" . $list_in . "'";
	}
	if (!empty($_REQUEST['country']))
	{
		$query[] = "a.country='" . intval($_REQUEST['country']) . "'";
	}
	if (!empty($_REQUEST['zip_code']))
	{
		$zip_code = $db->rem_special_chars($_REQUEST['zip_code']);
		$query[] = "MATCH (a.zip_code) AGAINST ('" . $zip_code . "*' IN BOOLEAN MODE)";
		/**
		 * or the old and SLOW search using LIKE - disabled by default, just added the line in case 
		 * anyone might want to use this instead
		 */## PHP Pro Bid v6.00 $query[] = "(a.zip_code LIKE '%" . $zip_code . "%')";
	}## PHP Pro Bid v6.00 now add the custom fields search feature
	$sql_select_custom_boxes = $db->query("SELECT b.*, t.box_type AS box_type_name FROM " . DB_PREFIX . "custom_fields_boxes b, 
	" . DB_PREFIX . "custom_fields f, " . DB_PREFIX . "custom_fields_types t WHERE 
		f.active=1 AND f.page_handle='auction' AND f.field_id=b.field_id AND b.box_searchable=1 AND b.box_type=t.type_id");
	
	$is_searchable_boxes = $db->num_rows($sql_select_custom_boxes);
	if ($is_searchable_boxes)
	{
		(string) $custom_addl_vars = null;
		while ($custom_box = $db->fetch_array($sql_select_custom_boxes))
		{			
			if (!empty($_REQUEST['custom_box_' . $custom_box['box_id']]))
			{
				$search_box = true;
				$box_id = $custom_box['box_id'];
				$where_query .= "LEFT JOIN " . DB_PREFIX . "custom_fields_data cfd_" . $box_id . " ON cfd_" . $box_id . ".owner_id=a.auction_id AND cfd_" . $box_id . ".page_handle='auction' ";
				$custom_box_value = $db->rem_special_chars($_REQUEST['custom_box_' . $custom_box['box_id']]);
				$custom_addl_vars .= '&custom_box_' . $custom_box['box_id'] . '=' . $custom_box_value;
								
				if (in_array($custom_box['box_type_name'], array('list', 'radio')))
				{
					$query[] = "cfd_" . $box_id . ".box_value = '" . $custom_box_value . "'";					
				}
				else if (in_array($custom_box['box_type_name'], array('checkbox')))
				{
					(array) $checkbox_query = null;
					foreach ($_REQUEST['custom_box_' . $custom_box['box_id']] as $value)
					{
						if (!empty($value))
						{
							//$checkbox_query[] = "MATCH (cfd_" . $box_id . ".box_value) AGAINST ('" . $value . "*' IN BOOLEAN MODE)";
							$checkbox_query[] = "(cfd_" . $box_id . ".box_value LIKE '%" . $value . "%')";
						}						
					}

					if (count($checkbox_query) > 0) 
					{
						$query[] = "(" . $db->implode_array($checkbox_query, ' OR ') . ")";						
					}
					else 
					{
						$search_box = false;
					}
				}
				else 
				{
					//$query[] = "MATCH (cfd_" . $box_id . ".box_value) AGAINST ('" . $custom_box_value . "*' IN BOOLEAN MODE)";
					
					/**
					 * or the old and SLOW search using LIKE - disabled by default, just added the line in case 
					 * anyone might want to use this instead
					 */
					$query[] = "(cfd_" . $box_id . ".box_value LIKE '%" . $custom_box_value . "%')";
				}

				if ($search_box)
				{
					$query[] = "cfd_" . $box_id . ".box_id='" . $box_id . "'";
				}
			}
		}
	}
}
else if ($_REQUEST['option'] == 'seller_search')## PHP Pro Bid v6.00 search auctions posted by the seller requested
{
	if (!empty($_REQUEST['username']))
	{
		$username = $db->rem_special_chars($_REQUEST['username']);
		$where_query = "LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=a.owner_id ";
		$query[] = "MATCH u.username AGAINST ('" . $username . "*' IN BOOLEAN MODE) AND u.active=1";
		/**
		 * or the old and SLOW search using LIKE - disabled by default, just added the line in case 
		 * anyone might want to use this instead
		 */## PHP Pro Bid v6.00 $query[] = "(u.username LIKE '%" . $username . "%')";
	}
}
else if ($_REQUEST['option'] == 'buyer_search')## PHP Pro Bid v6.00 search auctions on which the buyer requested has placed bids
{
	if (!empty($_REQUEST['username']))
	{
		$username = $db->rem_special_chars($_REQUEST['username']);
		$where_query = "LEFT JOIN " . DB_PREFIX . "bids b ON b.auction_id=a.auction_id
			LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=b.bidder_id ";
		$query[] = "MATCH u.username AGAINST ('" . $username . "*' IN BOOLEAN MODE) AND u.active=1";
		/**
		 * or the old and SLOW search using LIKE - disabled by default, just added the line in case 
		 * anyone might want to use this instead
		 */## PHP Pro Bid v6.00 $query[] = "(u.username LIKE '%" . $username . "%')";
	}	
}

if ($_REQUEST['option'] != 'basic_search') 
{
	if (!empty($_REQUEST['results_view']))
	{
		switch ($_REQUEST['results_view'])## PHP Pro Bid v6.00 all value means we add no variables to the query
		{
			case 'open':
				$query[] = "a.closed=0";
				break;
			case 'closed':
				$query[] = "a.closed=1";
				break;
		}
	}
}	

if (count($query))
{
	$addl_query = " AND " . $db->implode_array($query, ' AND ');
}
	
$where_query .= "WHERE a.active=1 AND a.approved=1 AND a.deleted=0 AND a.creation_in_progress=0 " . $addl_query;

//echo $where_query;
//die();
$order_field = (in_array($_REQUEST['order_field'], $auction_ordering)) ? $_REQUEST['order_field'] : 'a.end_time'; 
$order_type = (in_array($_REQUEST['order_type'], $order_types)) ? $_REQUEST['order_type'] : 'ASC';

## if we are on the page for the first time, we will override the ordering variables
if (!empty($_REQUEST['ordering']))
{
	switch ($_REQUEST['ordering'])
	{
		case 'end_time_asc':
			$order_field = 'a.end_time';
			$order_type = 'ASC';
			break;
		case 'end_time_desc':
			$order_field = 'a.end_time';
			$order_type = 'DESC';
			break;
		case 'start_price_asc':
			$order_field = 'a.start_price';
			$order_type = 'ASC';
			break;
	}
}

$additional_vars = '&option=' . $_REQUEST['option'] . '&src_auction_id=' . $_REQUEST['src_auction_id'] . '&keywords_search=' . $_REQUEST['keywords_search'] .
	'&buyout_price=' . $_REQUEST['buyout_price'] . '&reserve_price=' . $_REQUEST['reserve_price'] . 
	'&quantity=' . $_REQUEST['quantity'] . '&enable_swap=' . $_REQUEST['enable_swap'] . 
	'&list_in=' . $_REQUEST['list_in'] . '&results_view=' . $_REQUEST['results_view'] . 
	'&country=' . $_REQUEST['country'] . '&zip_code=' . $_REQUEST['zip_code'] . '&username=' . $_REQUEST['username'] . 
	'&basic_search=' . $_REQUEST['basic_search'] . $custom_addl_vars;

include_once('includes/page_browse_auctions.php');

include_once ('global_footer.php');

echo $template_output;

?>
