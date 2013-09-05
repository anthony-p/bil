<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
## (Mods-Store) -> Shopping Cart											##
#################################################################

session_start();

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
}
else
{
	$template->set('session', $session);
	
	(array) $summary_page_content = null;
	
//	$default_landing_page = 'summary';
//	$default_landing_section = 'summary_main';
	$default_landing_page = 'campaigns';
	$default_landing_section = 'main';


	$page = (!empty($_REQUEST['page'])) ? $_REQUEST['page'] : $default_landing_page;
	$section = (!empty($_REQUEST['section'])) ? $_REQUEST['section'] : $default_landing_section;
	$order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'DESC';
	$name_keyword = (!empty($_REQUEST['keyword'])) ? $_REQUEST['keyword'] : '';

	$section = ($page == 'wanted_ads' && !$setts['enable_wanted_ads']) ? $default_landing_section : $section;
	$page = ($page == 'wanted_ads' && !$setts['enable_wanted_ads']) ? $default_landing_page : $page;
	
	$section = ($page == 'store' && !$setts['enable_stores']) ? $default_landing_section : $section;
	$page = ($page == 'store' && !$setts['enable_stores']) ? $default_landing_page : $page;
	
	$section = ($page == 'bulk' && !$setts['enable_bulk_lister']) ? $default_landing_section : $section;
	$page = ($page == 'bulk' && !$setts['enable_bulk_lister']) ? $default_landing_page : $page;

	$section = ($page == 'reverse' && !$setts['enable_reverse_auctions']) ? $default_landing_section : $section;
	$page = ($page == 'reverse' && !$setts['enable_reverse_auctions']) ? $default_landing_page : $page;

	/* if account is suspended, only account related pages are active */
	if ($session->value('membersarea') == 'Active')
	{

		if (!$session->value('is_seller') && in_array($page, array('selling', 'bulk', 'store')))
		{
			$page = 'bidding';
			$section = 'current_bids';

			$template->set('msg_seller_error', '<p align="center">' . MSG_NO_SELLING_CAPABILITIES . '</p>');
		}
	}
	else
	{
		$page = 'account';
		$section = (in_array($section, array('editinfo', 'management', 'invoices', 'mailprefs'))) ? $section : 'management';
	}
	require ('global_header_interior.php');

	$msg_changes_saved = '<p align="center" class="contentfont">' . MSG_CHANGES_SAVED . '</p>';

	$limit = 20;

	if ($page == 'messaging' || $page == 'summary')
	{
		$order_field = (isset($_REQUEST['order_field']) && $_REQUEST['order_field']) ? $_REQUEST['order_field'] : 'm.reg_date';
	}
	else if ($page == 'reputation')
	{
		$order_field = (isset($_REQUEST['order_field']) && $_REQUEST['order_field']) ? $_REQUEST['order_field'] : 'r.reg_date';
	}
	else if ($section == 'current_bids')
	{
		$order_field = (isset($_REQUEST['order_field']) && $_REQUEST['order_field']) ? $_REQUEST['order_field'] : 'b.auction_id';
	}
	else if ($section == 'item_watch')
	{
		$order_field = (isset($_REQUEST['order_field']) && $_REQUEST['order_field']) ? $_REQUEST['order_field'] : 'aw.id';
	}
	else if ($section == 'favorite_stores')
	{
		$order_field = (isset($_REQUEST['order_field']) && $_REQUEST['order_field']) ? $_REQUEST['order_field'] : 's.id';
	}
	else if ($section == 'keywords_watch')
	{
		$order_field = (isset($_REQUEST['order_field']) && $_REQUEST['order_field']) ? $_REQUEST['order_field'] : 'keyword_id';
	}
	else if ($section == 'block_users')
	{
		$order_field = (isset($_REQUEST['order_field']) && $_REQUEST['order_field']) ? $_REQUEST['order_field'] : 'b.reg_date';
	}
	else if ($page == 'wanted_ads')
	{
		$order_field = (isset($_REQUEST['order_field']) && $_REQUEST['order_field']) ? $_REQUEST['order_field'] : 'w.wanted_ad_id';
	}
	else if ($section == 'won_items' || $section == 'sold')
	{
		$order_field = (isset($_REQUEST['order_field']) && $_REQUEST['order_field']) ? $_REQUEST['order_field'] : 'w.auction_id';
	}
	else if ($section == 'sold_carts' || $section == 'purchased_carts')
	{
		$order_field = (isset($_REQUEST['order_field']) && $_REQUEST['order_field']) ? $_REQUEST['order_field'] : 'sc.sc_date';
	}

	else if ($page == 'reverse')
	{
		$order_field = (isset($_REQUEST['order_field']) && $_REQUEST['order_field']) ? $_REQUEST['order_field'] : 'r.reverse_id';
	}
	else
	{
		$order_field = (isset($_REQUEST['order_field']) && $_REQUEST['order_field']) ? $_REQUEST['order_field'] : 'a.auction_id';
	}

	$order_type = ($_REQUEST['order_type']) ? $_REQUEST['order_type'] : 'DESC';

	$additional_vars = '&page=' . $page . '&section=' . $section;
	$order_link = '&order_field=' . $order_field . '&order_type=' . $order_type;
    if (!isset($start))
        $start = 0;
	$limit_link = '&start=' . $start . '&limit=' . $limit;

	$template->set('page', $page);
	$template->set('section', $section);

	$item = new item();
	$item->setts = &$setts;
	$item->layout = &$layout;

	/**
	 * pending Google Checkout fees
	 */
	$is_pending_gc = $db->count_rows('gc_transactions', "WHERE buyer_id='" . $session->value('user_id') . "'");
	
	$template->set('is_pending_gc', $is_pending_gc);
	if ($is_pending_gc)
	{
		if ($section == 'management')
		{
			$sql_pending_gc_transactions = $db->query("SELECT gc.*, u.username FROM " . DB_PREFIX . "gc_transactions gc  
				LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=gc.seller_id 
				WHERE gc.buyer_id='" . $session->value('user_id') . "' ORDER BY gc.reg_date ASC");
			
			while ($payment_details = $db->fetch_array($sql_pending_gc_transactions)) 
			{
				$pending_gc_transactions_content .=	'<tr class="contentfont c1"> '.
					'	<td>' . $payment_details['gc_payment_description'] . '</td> '.
					'	<td nowrap align="center">' . field_display($payment_details['seller_id'], '<b>' . MSG_ADMIN . '</b>', $payment_details['username']) . '</td> '.
					'	<td nowrap align="center">' . $fees->display_amount($payment_details['gc_price'], $payment_details['gc_currency']) . '</td> '.
					'	<td nowrap align="center">' . show_date($payment_details['reg_date']) . '</td> '.
					'	<td nowrap align="center">' . field_display($payment_details['seller_id'], MSG_SITE_PAYMENT, MSG_DIRECT_PAYMENT) . '</td> '.
					'</tr> ';
			} 
			
			$template->set('pending_gc_transactions_content', $pending_gc_transactions_content);
		}
		else 
		{
			$page_link = process_link('members_area', array('page' => 'account', 'section' => 'management'));
			$msg_pending_gc_transactions = '<p align="center" class="errormessage contentfont">' . MSG_PENDING_GC_PAYMENTS_A . 
				' [ <a href="' . $page_link . '">' . MSG_HERE . '</a> ] ' . MSG_PENDING_GC_PAYMENTS_B . '.</p>';	
		}
		$template->set('msg_pending_gc_transactions', $msg_pending_gc_transactions);
	} else
        $template->set('msg_pending_gc_transactions', '');
	
	/**
	 * unpaid end of auction fees message - applies if the account is in live payment mode
	 */
	$user_payment_mode = $fees->user_payment_mode($session->value('user_id'));
		
	if($user_payment_mode == 1)
	{
		$eoa_fee = new fees();
		$eoa_fee->setts = &$setts;
		
		$eoa_fee->set_fees($session->value('user_id'));

        $msg_unpaid_endauction_fees = "";

		if (eregi('b', $eoa_fee->fee['endauction_fee_applies']))
		{
			$unpaid_fees = $db->count_rows('winners', "WHERE buyer_id='" . $session->value('user_id') . "' AND active!=1 AND payment_status!='confirmed'");

			if ($unpaid_fees)
			{
				$page_link = process_link('members_area', array('page' => 'bidding', 'section' => 'won_items'));
				$msg_unpaid_endauction_fees = '<p align="center" class="errormessage contentfont">' . MSG_UNPAID_EOAFEES_A . 
					' [ <a href="' . $page_link . '">' . MSG_WON_ITEMS_PAGE . '</a> ] ' . MSG_UNPAID_EOAFEES_B . '.</p>';
			}
		}
		else if (eregi('s', $eoa_fee->fee['endauction_fee_applies']))
		{
			$unpaid_fees = $db->count_rows('winners', "WHERE seller_id='" . $session->value('user_id') . "' AND active!=1 AND payment_status!='confirmed'");			

			if ($unpaid_fees)
			{
				$page_link = process_link('members_area', array('page' => 'selling', 'section' => 'sold'));
				$msg_unpaid_endauction_fees = '<p align="center" class="errormessage contentfont">' . MSG_UNPAID_EOAFEES_A . 
					' [ <a href="' . $page_link . '">' . MSG_SOLD_ITEMS_PAGE . '</a> ] ' . MSG_UNPAID_EOAFEES_B . '.</p>';
			}
		}
		
		$template->set('msg_unpaid_endauction_fees', $msg_unpaid_endauction_fees);
	}
	
	/* members tips code snippet */
	if ($session->value('is_seller'))
	{
        $msg_member_tips= '';
        $show_tips = $db->count_rows('users', "WHERE user_id='" . $session->value('user_id') . "' AND notif_a=0");
		
		if ($show_tips)
		{
			$msg_member_tips = '<p class="errormessage">' . MSG_MEMBER_TIPS_A . '<br>' . MSG_MEMBER_TIPS_B . '</p>';
			$db->query("UPDATE " . DB_PREFIX . "users SET notif_a=1 WHERE user_id='" . $session->value('user_id') . "'");
		}
		$template->set('msg_member_tips', $msg_member_tips);
	}
		
	if (isset($_REQUEST['form_download_proceed']))
	{
		$download_result = download_redirect($_REQUEST['winner_id'], $session->value('user_id'));
		
		if ($download_result['redirect'])
		{
			header('Location: ' . $download_result['url']);
		}
		
		$template->set('msg_changes_saved', '<p align="center">' . $download_result['display'] . '</p>');
		$page = 'bidding';
		$section = 'won_items';
	}
	
	if ( isset($_REQUEST['do']) && $_REQUEST['do'] == 'delete_invoice')
	{
		$item->delete_invoice($_REQUEST['invoice_id'], $_REQUEST['option'], $session->value('user_id'));		
	}
	
	if ( isset($_REQUEST['do']) && $_REQUEST['do'] == 'delete_winner')
	{
		$item->delete_winner($_REQUEST['winner_id'], $_REQUEST['option'], $session->value('user_id'));
	}
	if ( isset($_REQUEST['do']) && $_REQUEST['do'] == 'delete_cart')
	{
		$item->delete_cart(intval($_REQUEST['sc_id']), $_REQUEST['option'], $session->value('user_id'));
	}	

	
	if ( isset($_REQUEST['do']) && $_REQUEST['do'] == 'request_refund')
	{
		$winner_details = $db->get_sql_row("SELECT w.*, i.user_id AS payer_id, i.refund_request FROM " . DB_PREFIX . "winners w 
			LEFT JOIN " . DB_PREFIX . "invoices i ON i.invoice_id=w.refund_invoice_id
			WHERE	w.refund_invoice_id='" . intval($_REQUEST['refund_invoice_id']) . "'");
		
		if ($winner_details['payer_id'] == $session->value('user_id') && $item->request_refund($winner_details['refund_invoice_id'], $winner_details['purchase_date'], $winner_details['flag_paid'], $winner_details['refund_request']))
		{
			$output = $item->process_refund_request($winner_details['refund_invoice_id']);
			
			$template->set('msg_changes_saved', '<p align="center">' . $output['display'] . '</p>');
		}
		
	}
	
	$src_transactions_query = null;
	$src_auctions_query = null;
	if ($page != 'reverse' && ($section == 'sold' || $section == 'won_items' || ($page == 'selling' && $section == 'open')))	
	{
		$src_box_type = ($page == 'selling' && $section == 'open') ? 1 : 0;
		$template->set('src_box_type', $src_box_type);
		
		$src_auction_id = intval((isset($_REQUEST['src_auction_id']))?$_REQUEST['src_auction_id']:0);
		$template->set('src_auction_id', $src_auction_id);

		if ($src_box_type == 1)
		{
			$keywords_search = $db->rem_special_chars((isset($_REQUEST['src_item_title']))?$_REQUEST['src_item_title']:"");
			$keywords_search = optimize_search_string($keywords_search);
		}
		else 
		{			
			$src_username = (isset($_REQUEST['src_username']))?$db->rem_special_chars($_REQUEST['src_username']):'';
			$template->set('src_username', $src_username);
			
            $src_start_time = isset($_REQUEST['src_start_time'])?$_REQUEST['src_start_time']:0;
            $src_start_time = (isset($_REQUEST['form_search_transactions'])) ? get_box_timestamp($_REQUEST, 1) : intval($src_start_time);
			$src_start_time = ($src_start_time > 0) ? $src_start_time : 0;
		
            $src_end_time = isset($_REQUEST['src_end_time'])?$_REQUEST['src_end_time']:0;
            $src_end_time = (isset($_REQUEST['form_search_transactions'])) ? get_box_timestamp($_REQUEST, 2) : intval($src_end_time);
			$src_end_time = ($src_end_time > 0 && $src_end_time <= CURRENT_TIME) ? $src_end_time + (24 * 60 * 60 - 1) : CURRENT_TIME;
			
			$start_date_box = date_form_field($src_start_time, 1, 'search_transactions_form', false);
			$template->set('start_date_box', $start_date_box);
		
			$end_date_box = date_form_field($src_end_time, 2, 'search_transactions_form', false);
			$template->set('end_date_box', $end_date_box);
		}
		
		$show = (isset($_REQUEST['show']))?$_REQUEST['show']:'';
		$template->set('show', $show);
		
		$search_transactions_box = $template->process('search_transactions_box.tpl.php');
		$template->set('search_transactions_box', $search_transactions_box);

		// build search query
		if ($src_box_type == 1)
		{
			if ($src_auction_id)
			{
				$src_auctions_query .= " AND a.auction_id='" . $src_auction_id . "'";
			}
			if (!empty($_REQUEST['src_item_title']))
			{
				$src_auctions_query .= " AND MATCH (a.name) AGAINST ('+" . $keywords_search . "' IN BOOLEAN MODE)";	
			}
		}
		else 
		{
			if ($src_auction_id)
			{
				$src_transactions_query .= " AND w.auction_id='" . $src_auction_id . "'";
			}
			if ($src_username)
			{
				$src_user_id = $db->get_sql_field("SELECT user_id FROM " . DB_PREFIX . "users WHERE username='" . $src_username . "'", 'user_id');
				$src_transactions_query .= " AND " . (($section == 'sold') ? 'w.buyer_id' : 'w.seller_id') . "='" . $src_user_id . "'";
			}
			if ($src_start_time)
			{
				$src_transactions_query .= " AND w.purchase_date>='" . $src_start_time . "'";
			}
			if ($src_end_time)
			{
				$src_transactions_query .= " AND w.purchase_date<='" . $src_end_time . "'";
			}
		}
        $src_auction_id = isset($_REQUEST['src_auction_id'])?$_REQUEST['src_auction_id']:0;
        $src_item_title = isset($_REQUEST['src_item_title'])?$_REQUEST['src_item_title']:'';
        if (!isset($src_username))
            $src_username = '';
        if (!isset($src_start_time))
            $src_start_time= 0;
        if (!isset($src_end_time))
            $src_end_time = 0;
		$additional_vars .= '&src_auction_id=' . $src_auction_id . '&src_username=' . $src_username .
			'&src_start_time=' . $src_start_time . '&src_end_time=' . $src_end_time . '&src_item_title=' . $src_item_title;
	}
	
	if ($page == 'bidding' || $page == 'selling') /* allow bidders to create product invoices as well */
	{
		if (isset($_REQUEST['form_send_invoice']) || (isset($_REQUEST['send_invoice']) && $_REQUEST['send_invoice'] == 1 ) )
		{
			$item->send_invoice($_POST, intval($_REQUEST['seller_id']), doubleval($_REQUEST['total_postage']), $session->value('user_id'));

			$template->set('msg_changes_saved', '<p align="center">' . MSG_INVOICE_SENT_SUCCESSFULLY_B . '</p>');
			
			$section = ($page == 'bidding') ? 'invoices_received' : 'invoices_sent';
			$additional_vars = '&page=' . $page . '&section=' . $section;
		}
		
		if ($section == 'product_invoice')
		{
			if ($setts['enable_buyer_create_invoice'])
			{
				$seller_id = ($_REQUEST['buyer_id'] && $page == 'selling') ? $session->value('user_id') : intval($_REQUEST['seller_id']);
				$buyer_id = ($_REQUEST['seller_id'] && $page == 'bidding') ? $session->value('user_id') : intval($_REQUEST['buyer_id']);
			}
			else 
			{
				$seller_id = $session->value('user_id');
				$buyer_id = intval($_REQUEST['buyer_id']);
			}
			
			$edit_invoice = false;
			if ($_REQUEST['option'] == 'edit_invoice') // only the seller can edit the invoice
			{
				$edit_invoice = true;
				$sql_select_products = $db->query("SELECT w.*, a.name, a.apply_tax, a.currency FROM " . DB_PREFIX . "winners w
					LEFT JOIN " . DB_PREFIX . "auctions a ON a.auction_id=w.auction_id WHERE w.invoice_id='" . intval($_REQUEST['invoice_id']) . "' AND
					w.seller_id='" . $session->value('user_id') . "'");
				
				$buyer_id = $db->get_sql_field("SELECT buyer_id FROM " . DB_PREFIX . "winners WHERE 
					invoice_id='" . intval($_REQUEST['invoice_id']) . "' AND seller_id='" . $session->value('user_id') . "'", 'buyer_id');				
				$seller_id = $session->value('user_id');
			}
			else 
			{
				/**
				 * only items which have the same currency as the auction selected to be invoiced can be added in the 
				 * same invoice
				 */
				$accepted_currency = $db->get_sql_field("SELECT currency FROM " . DB_PREFIX . "auctions WHERE auction_id='" . intval($_REQUEST['auction_id']) . "'", 'currency');
				
				$sql_select_products = $db->query("SELECT a.*, w.winner_id, w.bid_amount, w.quantity_offered FROM 
					" . DB_PREFIX . "auctions a, " . DB_PREFIX . "winners w WHERE
					a.currency='" . $accepted_currency . "' AND a.auction_id=w.auction_id AND w.seller_id='" .  $seller_id . "' AND 
					w.buyer_id='" . $buyer_id . "' AND w.invoice_id=0");
			}
			$template->set('edit_invoice', $edit_invoice);
			
			$seller_details = $db->get_sql_row("SELECT * FROM " . DB_PREFIX . "users WHERE 
				user_id='" . $seller_id . "'");
			$template->set('seller_details', $seller_details);
			
			$user_details = $db->get_sql_row("SELECT user_id, username FROM " . DB_PREFIX . "users WHERE 
				user_id='" . $buyer_id . "'");
			$template->set('user_details', $user_details);
			$template->set('auction_id', intval($_REQUEST['auction_id']));
			
			$single_settings = false;
			$calculate_postage = true;
			$winner_ids = null;
			$disabled_button = 'disabled';

			$can_edit = ($seller_id == $session->value('user_id') || $edit_invoice) ? true : false;

            $counter= 0 ;
			while ($item_details = $db->fetch_array($sql_select_products))
			{
				if (!$single_settings)
				{
					$currency = $item_details['currency'];
					
					if ($_REQUEST['option'] == 'edit_invoice')
					{
						$total_postage['postage'] = $item_details['postage_amount'];
						$calculate_postage = false;
						$disabled_button = '';
					}
					
					$template->set('invoice_comments', $item_details['invoice_comments']);
					$template->set('invoice_final', $item_details['invoice_final']);
				}
				
				$background = ($counter++%2) ? 'c1' : 'c2';

				$winner_array = (!empty($_REQUEST['winner_id'])) ? $_REQUEST['winner_id'] : array();
				if (in_array($item_details['winner_id'], $winner_array))
				{
					$disabled_button = '';
					$checked = 'checked';
					$winner_ids[] = $item_details['winner_id'];
				}
				else 
				{
					$checked = '';
				}
				
				$field = null;
				if ($can_edit)
				{
					$field['winning_bid'] = $item_details['currency'] . ' <input name="bid_amount[' . $item_details['winner_id'] . ']" type="text" value="' . $item_details['bid_amount'] . '" size="6">';
					$field['postage_item'] = $item_details['currency'] . ' <input name="postage_amount[' . $item_details['winner_id'] . ']" type="text" value="' . $item_details['postage_amount'] . '" size="6">';
					$field['insurance'] = $item_details['currency'] . ' <input name="insurance_amount[' . $item_details['winner_id'] . ']" type="text" value="' . $item_details['insurance_amount'] . '" size="6">';
				}
				else 
				{
					$field['winning_bid'] = $fees->display_amount($item_details['bid_amount'], $item_details['currency']);
					$field['postage_item'] = $fees->display_amount($item_details['postage_amount'], $item_details['currency'], true);
					$field['insurance'] = $fees->display_amount($item_details['insurance_amount'], $item_details['currency'], true);					
				}
				
				$product_invoice_content .= '<tr class="' . $background . '"> '.
					'	<input type="hidden" name="auction_ids[' . $item_details['winner_id'] . ']" value="' . $item_details['auction_id'] . '"> '.
					(($_REQUEST['option'] == 'edit_invoice') ? 
					'	<td align="center"><input name="winner_tmp[]" type="checkbox" value="' . $item_details['winner_id'] . '" checked disabled>'.
					'		<input name="winner_id[]" type="hidden" value="' . $item_details['winner_id'] . '"></td>' : 
					'	<td align="center"><input name="winner_id[]" id="winner_id" type="checkbox" value="' . $item_details['winner_id'] . '" ' . $checked . ' onclick="submit_form(product_invoice_form);"></td>').				
					'	<td class="contentfont"><a href="' . process_link('auction_details', array('auction_id' => $item_details['auction_id'])) . '" target="_blank"># ' . $item_details['auction_id'] . '</a></td> '.
					'	<td class="contentfont"><a href="' . process_link('auction_details', array('auction_id' => $item_details['auction_id'])) . '" target="_blank">' . $item_details['name'] . '</a></td>'.
					'	<td align="center">' . $field['winning_bid'] . '</td> '.
					'	<td align="center">' . $item_details['quantity_offered'] . '</td>'.
					(($seller_details['pc_postage_type'] == 'item') ? '<td align="center"><input name="postage_included[' . $item_details['winner_id'] . ']" type="checkbox" value="1" ' . (($_REQUEST['option'] == 'edit_invoice') ? (($item_details['postage_included']) ? 'checked' : '') : 'checked') . ' ' . (($can_edit) ? '' : 'disabled') . '> '.
						$field['postage_item'] . '</td>' : '').
					'	<td align="center"><input name="insurance_included[' . $item_details['winner_id'] . ']" type="checkbox" value="1" ' . (($_REQUEST['option'] == 'edit_invoice') ? (($item_details['insurance_included']) ? 'checked' : '') : 'checked') . '> '.
						$field['insurance'] . '</td>'.
					'</tr>';
			}
			$template->set('currency', $currency);
			$template->set('disabled_button', $disabled_button);
			
			if ($calculate_postage)
			{
				$total_postage = calculate_postage($winner_ids, $seller_details['user_id']);
			}
			else if ($seller_details['pc_postage_type'] != 'item')
			{
				$total_postage = $total_postage;
			}
			
			if ($can_edit)
			{
				$total_postage_box = $currency . ' <input type="text" name="total_postage" size="8" value="' . $total_postage['postage'] . '">';
			}
			else 
			{
				$total_postage_box = $fees->display_amount($total_postage['postage'], $currency);
			}
			$template->set('total_postage_box', $total_postage_box);
			
			$template->set('total_postage', $total_postage['postage']);

			$template->set('product_invoice_content', $product_invoice_content);

			$members_area_page_content = $template->process('members_area_selling_product_invoice.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);			
		}
	}
			
	if ($page == 'account' || $page == 'summary') /* BEGIN -> MY ACCOUNT SECTION */
	{
		if ($section == 'editinfo') /* BEGIN -> PERSONAL INFORMATION PAGE */
		{
			$page_handle = 'full_register'; /* this page is related to users, so the page handle for custom fields is "register" */

            $session->set('pin_value', md5(rand(2, 99999999)));
            $generated_pin = generate_pin($session->value('pin_value'));
            $pin_image_output = show_pin_image($session->value('pin_value'), $generated_pin);
            $template->set('pin_image_output', $pin_image_output);
            $template->set('generated_pin', $generated_pin);

//			$row_user = $db->get_sql_row("SELECT * FROM
//				" . DB_PREFIX . "users WHERE user_id=" . $session->value('user_id'));
            $row_user = $db->get_sql_row("SELECT *
            FROM  `bl2_users` where id=" . $session->value('user_id'));

            $user_in_database = $row_user;

            if (isset($row_user['username']))
			    $username = $row_user['username']; /* the readonly field will not be altered */
            else
                $username  = '';

#which np is this. get the npuser_id and put it in a variable
			if (isset ($row_user['npuser_id']))
                $mynp_userid = $row_user['npuser_id'];
            else
                $mynp_userid = 0;
			if ( isset($_POST['edit_refresh']) && $_POST['edit_refresh'] == 1)
			{
				$row_user = $_POST;
				$row_user['username'] = $username;
			}

			$user = new user();
			$user->setts = &$setts;

			$tax = new tax();
			$tax->setts = &$setts;

			if ( isset($_REQUEST['operation']) && $_REQUEST['operation'] == 'submit')
			{
				$user->save_vars($_POST);
				define ('FRMCHK_USER', 1);
				(bool) $frmchk_user_edit = 1;
				$frmchk_details = $_POST;

				$row_user = $_POST;
				$row_user['username'] = $username; /* the readonly field will not be altered */


				include ('includes/procedure_frmchk_user.php'); /* Formchecker for user creation/edit */
//                var_dump($confirmed_paypal_email);exit;
                $row_user["confirmed_paypal_email"] = $_POST["confirmed_paypal_email"] = $confirmed_paypal_email;
				if ($fv->is_error())
				{
                    if (isset($user_in_database) && $user_in_database && is_array($user_in_database)) {
                        $row_user = $user_in_database;
                    }
					$template->set('display_formcheck_errors', $fv->display_errors());
				}
				else
				{
                    $_POST['phone'] = "(" . $_POST['phone_a'] . ")" . $_POST['phone_b'];
					$form_submitted = true;

                    // ---- MailChimp Subscription ------------------------------------
                    // check if user subscribed & add to MailChimp list
                    if (isset($_POST['newsletter']) && intval($_POST['newsletter'])) {
                        $mailChimp = new Mailchimp($mailChimpConfig['apiKey']);

                        try {
                            $mailChimp->lists->subscribe($mailChimpConfig['listId'],
                                array(
                                    'email' => $_POST['email']
                                ),
                                array(
                                    'EMAIL' => $_POST['email'],
                                    'FNAME' => $_POST['fname'],
                                    'LNAME' => $_POST['lname']
                                )
                            );
                        } catch (Mailchimp_Error $e) {

                            // TODO: MailChimp error processing

                            if ($e->getMessage()) {
//                                echo '<br>' . $e->getMessage() . '<br>';
                            } else {
                                // unrecognized error
                            }
                        }
                    }
                    // ---- end MailChimp subscription ---------------------------------


					$template->set('msg_changes_saved', $msg_changes_saved);

					$new_password =
                        ($_POST['password'] == $_POST['password2'] && !empty($_POST['password'])) ?
                            $_POST['password'] : null;

					$user->update($session->value('user_id'), $_POST, $new_password);
				}
			}

			if (!isset($form_submitted))
			{
				if ( isset($_REQUEST['operation']) && $_REQUEST['operation'] != 'submit')
				{
					$user->save_edit_vars($session->value('user_id'), $page_handle);
				}

//				$template->set('edit_user', 1);
				$template->set('edit_disabled', 'disabled'); /* some fields in the registration will be disabled for editing */

                if (isset($row_user['email']))
                    $rEmail = $row_user['email'];
                else
                    $rEmail =  "";
				$email_check_value = ( isset($_POST['email_check']) &&  $_POST['email_check']) ? $_POST['email_check'] : $rEmail;
				$template->set('email_check_value', $email_check_value);

				if (isset($_POST['tax_account_type']))
				{
					$row_user['tax_account_type'] = $_POST['tax_account_type'];
				}

                $phone_a = $phone_b = '';
                if (isset($row_user["phone"])) {
                    $phone_array = explode(")", $row_user["phone"]);
                    $phone_a = trim(str_replace("(", "", $phone_array[0]));
                    if (isset($phone_array[1])) {
                        $phone_b = trim($phone_array[1]);
                    }
                }
                $row_user["phone_a"] = $phone_a;
                $row_user["phone_b"] = $phone_b;

                if (!isset($row_user['first_name'])) {
                    $row_user['first_name'] = isset($_POST['fname']) ? $_POST['fname'] : '';
                }
                if (!isset($row_user['last_name'])) {
                    $row_user['last_name'] = isset($_POST['lname']) ? $_POST['lname'] : '';
                }

//                echo '<pre>';
//                var_dump($row_user);
//                echo '</pre>';

				$template->set('user_details', $row_user);
                if (isset($_REQUEST['do']))
                    $do = $_REQUEST['do'];
                else
                    $do = "";
				$template->set('do', $do);

	      	//$header_registration_message = headercat('<b>' . MSG_MM_MY_ACCOUNT . ' - ' . MSG_MM_PERSONAL_INFO . '</b>');
				//$template->set('header_registration_message', $header_registration_message);

				$template->set('proceed_button', GMSG_UPDATE_BTN);

                if (isset($row_user['country']))
                    $rCountry = $row_user['country'];
                else
                    $rCountry = '';
				$template->set('country_dropdown', $tax->countries_dropdown('country', $rCountry, 'registration_form'));
                if (isset($row_user['state']))
                    $rState = $row_user['state'];
                else
                    $rState = '';
				$template->set('state_box', $tax->states_box('state', $rState, $rCountry));

				$custom_sections_table = $user->display_sections($row_user, $page_handle);
				$template->set('custom_sections_table', $custom_sections_table);

				$template->set('display_direct_payment_methods', $user->direct_payment_methods_edit($row_user));
				
				$members_area_page_content = $template->process('full_registration.tpl.php');
				$template->set('members_area_page_content', $members_area_page_content);
			}
		} /* END -> PERSONAL INFORMATION PAGE */
		else if ($section == 'management' || $page == 'summary') /* BEGIN -> MANAGE ACCOUNT PAGE */
		{
			$user = new user();
			$user->setts = &$setts;
			$tax = new tax();

			if ($_REQUEST['operation'] == 'submit')
			{
				$form_submitted = false;

				$template->set('msg_changes_saved', $msg_changes_saved);

				/**
				 * all fee payments will be redirected to the new fee_payment.php file, which will
				 * have different options on how payments will be handled
				 */

				$sql_update_pg_details = $db->query("UPDATE " . DB_PREFIX . "users SET
					default_bank_details='" . $db->rem_special_chars($_POST['default_bank_details']) . "', 
					pg_paypal_email = '" . $db->rem_special_chars($_POST['pg_paypal_email']) . "',
					pg_worldpay_id = '" . $db->rem_special_chars($_POST['pg_worldpay_id']) . "',
					pg_checkout_id = '" . $db->rem_special_chars($_POST['pg_checkout_id']) . "',
					pg_nochex_email = '" . $db->rem_special_chars($_POST['pg_nochex_email']) . "',
					pg_ikobo_username = '" . $db->rem_special_chars($_POST['pg_ikobo_username']) . "',
					pg_ikobo_password = '" . $db->rem_special_chars($_POST['pg_ikobo_password']) . "',
					pg_protx_username = '" . $db->rem_special_chars($_POST['pg_protx_username']) . "',
					pg_protx_password = '" . $db->rem_special_chars($_POST['pg_protx_password']) . "',
					pg_authnet_username = '" . $db->rem_special_chars($_POST['pg_authnet_username']) . "',
					pg_authnet_password = '" . $db->rem_special_chars($_POST['pg_authnet_password']) . "', 
					pg_mb_email = '" . $db->rem_special_chars($_POST['pg_mb_email']) . "',
					paypal_address_override = '" . intval($_POST['paypal_address_override']) . "',
					paypal_first_name = '" . $db->rem_special_chars($_POST['paypal_first_name']) . "',
					paypal_last_name = '" . $db->rem_special_chars($_POST['paypal_last_name']) . "',
					paypal_address1 = '" . $db->rem_special_chars($_POST['paypal_address1']) . "',
					paypal_address2 = '" . $db->rem_special_chars($_POST['paypal_address2']) . "',
					paypal_city = '" . $db->rem_special_chars($_POST['paypal_city']) . "',
					paypal_state = '" . $db->rem_special_chars($_POST['paypal_state']) . "',
					paypal_zip = '" . $db->rem_special_chars($_POST['paypal_zip']) . "',
					paypal_country = '" . $db->rem_special_chars($_POST['paypal_country']) . "',
					paypal_night_phone_a = '" . $db->rem_special_chars($_POST['paypal_night_phone_a']) . "',
					paypal_night_phone_b = '" . $db->rem_special_chars($_POST['paypal_night_phone_b']) . "',
					paypal_night_phone_c = '" . $db->rem_special_chars($_POST['paypal_night_phone_c']) . "', 
					pg_paymate_merchant_id = '" . $db->rem_special_chars($_POST['pg_paymate_merchant_id']) . "',
					pg_gc_merchant_id = '" . $db->rem_special_chars($_POST['pg_gc_merchant_id']) . "',
					pg_gc_merchant_key = '" . $db->rem_special_chars($_POST['pg_gc_merchant_key']) . "', 
					pg_amazon_access_key = '" . $db->rem_special_chars($_POST['pg_amazon_access_key']) . "',
					pg_amazon_secret_key = '" . $db->rem_special_chars($_POST['pg_amazon_secret_key']) . "', 
					pg_alertpay_id = '" . $db->rem_special_chars($_POST['pg_alertpay_id']) . "', 
					pg_alertpay_securitycode = '" . $db->rem_special_chars($_POST['pg_alertpay_securitycode']) . "' 
					WHERE	user_id=" . $session->value('user_id'));
			}

			if (!$form_submitted)
			{
				$row_user = $db->get_sql_row("SELECT * FROM
					" . DB_PREFIX . "users WHERE user_id=" . $session->value('user_id'));

				if ($_POST['refresh'] == 1)
				{
					foreach ($_POST as $key => $value)
					{
						$row_user[$key] = $value;
					}
				}

				$template->set('user_details', $row_user);
				$template->set('do', $_REQUEST['do']);

				$template->set('countries_drop_down', paypal_countries_drop_down($row_user['paypal_country']));
				
				$header_registration_message = headercat('<b>' . MSG_MM_MY_ACCOUNT . ' - ' . MSG_MM_MANAGE_ACCOUNT . '</b>');

				$template->set('header_registration_message', $header_registration_message);

				$template->set('display_account_status', $user->account_status($row_user['active'], $row_user['approved']));

				$user_payment_mode = $fees->user_payment_mode($session->value('user_id'));
				$template->set('user_payment_mode', $user_payment_mode);

				$template->set('display_payment_mode', $user->payment_mode_desc($user_payment_mode));

				(string) $display_balance_details = null;
				$display_balance_details = $user->show_balance($row_user['balance'], $setts['currency']);

				if ($user_payment_mode == 2 && $row_user['balance']>=$setts['min_invoice_value'])
				{
					$display_balance_details .= ' [ <a href="fee_payment.php?do=clear_balance">' . MSG_CLEAR_ACC_BALANCE . '</a> ]';
				}

				$template->set('display_balance_details', $display_balance_details);

				$template->set('display_direct_payment_methods', $user->direct_payment_methods_edit($row_user));
				$template->set('proceed_button', GMSG_UPDATE_BTN);

				$members_area_page_content = $template->process('members_area_manage_account.tpl.php');
				
				if ($page == 'summary')
				{
					$summary_page_content['manage_account'] = $members_area_page_content;
				}
				else 
				{
					$template->set('members_area_page_content', $members_area_page_content);
				}
			}
		} /* END -> MANAGE ACCOUNT PAGE */
		else if ($section == 'history')
		{
			$user = new user();
			$user->setts = &$setts;

			$row_user = $db->get_sql_row("SELECT * FROM
				" . DB_PREFIX . "users WHERE user_id=" . $session->value('user_id'));

			$template->set('user_details', $row_user);

			$template->set('display_account_status', $user->account_status($row_user['active'], $row_user['approved']));

			$user_payment_mode = $fees->user_payment_mode($session->value('user_id'));
			$template->set('user_payment_mode', $user_payment_mode);

			$template->set('display_payment_mode', $user->payment_mode_desc($user_payment_mode));

			(string) $display_balance_details = null;
			$display_balance_details = $user->show_balance($row_user['balance'], $setts['currency']);

			if ($user_payment_mode == 2 && $row_user['balance']>=$setts['min_invoice_value'])
			{
				$display_balance_details .= ' [ <a href="fee_payment.php?do=clear_balance">' . MSG_CLEAR_ACC_BALANCE . '</a> ]';
			}

			$template->set('display_balance_details', $display_balance_details);

			$show_history_table = false;

			if (isset($_POST['form_display_history']) || $_REQUEST['do'] == 'view_history')
			{
				$show_history_table = true;
				
				$additional_vars .= '&do=view_history&date1_month=' . $_REQUEST['date1_month'] . 
					'&date1_year=' . $_REQUEST['date1_year'] . '&date1_day=' . $_REQUEST['date1_day'] . 
					'&date2_month=' . $_REQUEST['date2_month'] . '&date2_year=' . $_REQUEST['date2_year'] . 
					'&date2_day=' . $_REQUEST['date2_day'];

				$history_details['start_time'] = get_box_timestamp($_REQUEST, 1);
				$history_details['start_time'] = ($history_details['start_time'] > 0) ? $history_details['start_time'] : 0;

				$history_details['end_time'] = get_box_timestamp($_REQUEST, 2);
				$history_details['end_time'] = ($history_details['end_time'] > 0 && $history_details['end_time'] <= CURRENT_TIME) ? $history_details['end_time'] : CURRENT_TIME;

				$date_query = "AND invoice_date>=" . $history_details['start_time'] . " AND invoice_date<='" . $history_details['end_time'] . "'";## PHP Pro Bid v6.00 we will generate the history table here.## PHP Pro Bid v6.00 first we select all auction invoices (account mode)## PHP Pro Bid v6.00 then we select all live fees (auction fees, store fees, signup fees)## PHP Pro Bid v6.00 we will only generate invoices for fees, not for payments, so only if invoice_amount>0 => invoice
				$invoices_query = "SELECT *, sum(amount) AS invoice_amount FROM " . DB_PREFIX . "invoices WHERE
					live_fee=0 AND item_id>0 AND user_id='" . $session->value('user_id') . "' " . $date_query . "
					GROUP BY item_id
					UNION
					SELECT *, sum(amount) AS invoice_amount FROM " . DB_PREFIX . "invoices WHERE
					live_fee=0 AND wanted_ad_id>0 AND user_id='" . $session->value('user_id') . "' " . $date_query . "
					GROUP BY wanted_ad_id
					UNION
					SELECT *, sum(amount) AS invoice_amount FROM " . DB_PREFIX . "invoices WHERE
					live_fee=0 AND reverse_id>0 AND user_id='" . $session->value('user_id') . "' " . $date_query . "
					GROUP BY reverse_id
					UNION
					SELECT *, amount AS invoice_amount FROM " . DB_PREFIX . "invoices WHERE
					live_fee=1 AND user_id='" . $session->value('user_id') . "' " . $date_query;
				
				$nb_invoices = $db->get_sql_number($invoices_query);
				$template->set('nb_invoices', $nb_invoices);
				
				$sql_select_invoices = $db->query($invoices_query . " ORDER BY invoice_id DESC LIMIT " . $start . ", " . $limit);

				(string) $history_table_content = null;
                $counter= 0 ;

				while ($invoice_details = $db->fetch_array($sql_select_invoices))
				{
					$background = ($counter++%2) ? 'c1' : 'c2';

					$history_row = $item->history_row($invoice_details);

					$history_table_content .= '<tr class="' . $background . ' contentfont"> '.
	      			'	<td align="center">' . $history_row['item_id'] . '</td> '.
	      			'	<td>' . $history_row['invoice_name'] . '</td> '.
	      			'	<td align="center">' . $history_row['payment_type'] . '</td> '.
	      			'	<td align="center">' . $history_row['date'] . '</td> '.
	      			'	<td align="center">' . $history_row['amount'] . '</td> '.
	      			//'	<td align="center">' . $history_row['balance'] . '</td> '.
	   				'</tr>';
				}

				$template->set('history_table_content', $history_table_content);
			}

			$template->set('show_history_table', $show_history_table);

			$start_date_box = date_form_field($history_details['start_time'], 1, 'account_history_form', false);
			$template->set('start_date_box', $start_date_box);

			$end_date_box = date_form_field($history_details['end_time'], 2, 'account_history_form', false);
			$template->set('end_date_box', $end_date_box);

			$pagination = paginate($start, $limit, $nb_invoices, 'members_area.php', $additional_vars);
			$template->set('pagination', $pagination);
			
			$members_area_page_content = $template->process('members_area_account_history.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);
		}
		else if ($section == 'mailprefs')
		{
			if (isset($_POST['form_save_settings']))
			{
				$template->set('msg_changes_saved', $msg_changes_saved);

				$item->update_mailprefs($_POST, $session->value('user_id'));
			}

			$mail_prefs = $db->get_sql_row("SELECT * FROM " . DB_PREFIX . "users WHERE
				user_id='" . $session->value('user_id') . "'");

			$template->set('mail_prefs', $mail_prefs);

			$members_area_page_content = $template->process('members_area_account_mailprefs.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);

		}		
		else if ($section == 'abuse_report')
		{
			$form_submitted = false;

			if (isset($_POST['form_add_abuse_report']))
			{
				$post_details = $db->rem_special_chars_array($_POST);

				$is_user = $db->count_rows('users', "WHERE username='" . $post_details['abuser_username'] . "'");

				if ($is_user)
				{
					$form_submitted = true;
										
					$db->query("INSERT INTO " . DB_PREFIX . "abuses
						(user_id, abuser_username, comment, reg_date, auction_id) VALUES
						('" . $session->value('user_id') . "', '" . $post_details['abuser_username'] . "',
						'" . $post_details['comment'] . "', '" . CURRENT_TIME . "', '" . intval($post_details['auction_id']) . "')");
					
					/**
					 * email a notification to the admin regarding the abuse report
					 */
					$mail_input_id = $db->insert_id();
					include_once('language/' . $setts['site_lang'] . '/mails/abuse_report_notification.php');


					$template->set('msg_changes_saved', '<p align="center">' . MSG_ABUSE_REPORT_ADDED . '</p>');
				}
				else
				{
					$display_formcheck_errors = '<tr> '.
					'	<td align="center">' . MSG_ERROR_USER_DOESNT_EXIST . '</td> '.
					'</tr> ';
					$template->set('display_formcheck_errors', $display_formcheck_errors);
				}
			}

			if (!$form_submitted)
			{
				$auction_id = intval($_REQUEST['auction_id']);
				$template->set('auction_id', $auction_id);
				
				if ($auction_id)
				{
					$item_details = $db->get_sql_row("SELECT a.name, u.username FROM " . DB_PREFIX . "auctions a, 
						" . DB_PREFIX . "users u WHERE u.user_id=a.owner_id AND a.auction_id='" . $auction_id . "'");
					$template->set('item_details', $item_details);
				}
				
				$template->set('post_details', $post_details);

				$members_area_page_content = $template->process('members_area_account_abuse_report.tpl.php');
				$template->set('members_area_page_content', $members_area_page_content);
			}
		}
		else if ($section == 'refund_requests')
		{
			$user = new user();
			$user->setts = &$setts;

			$row_user = $db->get_sql_row("SELECT * FROM
				" . DB_PREFIX . "users WHERE user_id=" . $session->value('user_id'));

			$invoices_query = "SELECT * FROM " . DB_PREFIX . "invoices WHERE
				refund_request!=0 AND user_id='" . $session->value('user_id') . "'";

			$nb_invoices = $db->get_sql_number($invoices_query);
			$template->set('nb_invoices', $nb_invoices);

			$sql_select_invoices = $db->query($invoices_query . " ORDER BY refund_request_date DESC LIMIT " . $start . ", " . $limit);

			(string) $history_table_content = null;
            $counter= 0 ;

			while ($invoice_details = $db->fetch_array($sql_select_invoices))
			{
				$background = ($counter++%2) ? 'c1' : 'c2';

				$refunds_table_content .= '<tr class="' . $background . ' contentfont"> '.
					'	<td align="center">' . $invoice_details['item_id'] . '</td> '.
					'	<td>' . $invoice_details['name'] . '</td> '.
					'	<td align="center">' . $fees->display_amount($invoice_details['amount']) . '</td> '.
					'	<td align="center">' . show_date($invoice_details['refund_request_date']) . '</td> '.
					'	<td align="center">' . $item->refund_status($invoice_details['refund_request']) . '</td> '.
					'</tr>';
			}

			$template->set('refunds_table_content', $refunds_table_content);

			$pagination = paginate($start, $limit, $nb_invoices, 'members_area.php', $additional_vars);
			$template->set('pagination', $pagination);

			$members_area_page_content = $template->process('members_area_account_refund_requests.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);
		} elseif( $section == 'main') {

            $members_area_page_content = $template->process('members_area_account_main.tpl.php');
            $template->set('members_area_page_content', $members_area_page_content);
        }
	} /* END -> MY ACCOUNT SECTION */
	
	if ($page == 'messaging' || $page == 'summary') /* BEGIN -> MESSAGING PAGES */
	{
		$msg = new messaging();
		$msg->setts = &$setts;

		if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'delete_message')
		{
			$is_messages = count($_REQUEST['delete']);
			
			if ($is_messages)
			{
				foreach ($_REQUEST['delete'] as $value)				
				{
					$msg->delete_message($value, $session->value('user_id'), $_REQUEST['type']);
				}
				$template->set('msg_changes_saved', '<p align="center">' . MSG_MSG_DELETED_SUCCESS . '</p>');
			}
		}

		if ($section == 'received' || $page == 'summary')
		{
			$nb_messages = $db->count_rows('messaging', "WHERE receiver_id='" . $session->value('user_id') . "' AND
				receiver_deleted=0" . (($page == 'summary') ? " AND is_read=0" : ''));

			$template->set('nb_messages', $nb_messages);

			$template->set('page_order_reg_date', page_order('members_area.php', 'm.reg_date', $start, $limit, $additional_vars, MSG_MESSAGE_DATE));
			$template->set('page_order_sender_username', page_order('members_area.php', 'u.username', $start, $limit, $additional_vars, MSG_SENDER_USERNAME));

			if ($nb_messages)
			{
				$nb_unread_messages = $db->count_rows('messaging', "WHERE receiver_id='" . $session->value('user_id') . "' AND
					receiver_deleted=0 AND is_read=0");

				$template->set('nb_unread_messages', $nb_unread_messages);

				$sql_select_messages = $db->query("SELECT m.admin_message, a.name, u.username AS sender_username, 
					w.name AS wanted_name, r.name AS reverse_name, 
					m.* FROM " . DB_PREFIX . "messaging m
					LEFT JOIN " . DB_PREFIX . "auctions a ON a.auction_id=m.auction_id 
					LEFT JOIN " . DB_PREFIX . "wanted_ads w ON w.wanted_ad_id=m.wanted_ad_id 
					LEFT JOIN " . DB_PREFIX . "reverse_auctions r ON r.reverse_id=m.reverse_id 
					LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=m.sender_id
					WHERE m.receiver_id='" . $session->value('user_id') . "' AND m.receiver_deleted=0 
					" . (($page == 'summary') ? " AND m.is_read=0" : '') . "
					ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit);

                $counter= 0 ;
				while ($msg_details = $db->fetch_array($sql_select_messages))
				{
					$background = ($counter++%2) ? 'c1' : 'c2';
					$ico_read = (!$msg_details['is_read']) ? 'unread' : 'read';

					$content_options = '<a href="members_area.php?do=delete_message&type=receiver_deleted&message_id=' . $msg_details['message_id'] . $additional_vars . $limit_link . $order_link . '" onclick="return confirm(\'' . MSG_DELETE_CONFIRM . '\');">' . MSG_DELETE . '</a>';

					$received_messages_content .= '<tr class="' . $background . '"> '.
						'	<td class="contentfont" nowrap> '.
						'		<img src="themes/' . $setts['default_theme'] . '/img/system/' . $ico_read . '_mess.gif" border="0" align="absmiddle" hspace="5"><a href="' . $msg->msg_board_link($msg_details) . '">' . (($msg_details['admin_message']) ? GMSG_SITE_ADMIN : $msg_details['sender_username']) . '</a></td> '.
						'	<td class="contentfont">' . $msg->message_subject($msg_details) . '</td>'.
						'	<td align="center" nowrap>' . show_date($msg_details['reg_date']) . '</td> '.
						'	<td align="center" class="smallfont" nowrap><input name="delete[]" type="checkbox" id="delete[]" value="' . $msg_details['message_id'] . '" class="checkdelete"></td>'.
						'</tr>';
				}
			}
			else
			{
				$received_messages_content = '<tr><td colspan="8" align="center">' . GMSG_NO_MESSAGES_MSG . '</td></tr>';
			}

			$template->set('received_messages_content', $received_messages_content);

			if ($page != 'summary')
			{
				$pagination = paginate($start, $limit, $nb_messages, 'members_area.php', $additional_vars . $order_link);
				$template->set('pagination', $pagination);
			}

			$members_area_page_content = $template->process('members_area_messaging_received.tpl.php');			
			
			if ($page == 'summary') 
			{
				$summary_page_content['messaging_received'] = $members_area_page_content;
			}
			
			$template->set('members_area_page_content', $members_area_page_content);
		}

		if ($section == 'sent')
		{
			$nb_messages = $db->count_rows('messaging', "WHERE sender_id='" . $session->value('user_id') . "' AND
				sender_deleted=0");

			$template->set('nb_messages', $nb_messages);

			$template->set('page_order_reg_date', page_order('members_area.php', 'm.reg_date', $start, $limit, $additional_vars, MSG_MESSAGE_DATE));
			$template->set('page_order_receiver_username', page_order('members_area.php', 'u.username', $start, $limit, $additional_vars, MSG_SENDER_USERNAME));

			if ($nb_messages)
			{
				$sql_select_messages = $db->query("SELECT a.name, u.username AS receiver_username, 
					w.name AS wanted_name, r.name AS reverse_name, 
					m.* FROM " . DB_PREFIX . "messaging m
					LEFT JOIN " . DB_PREFIX . "auctions a ON a.auction_id=m.auction_id 
					LEFT JOIN " . DB_PREFIX . "wanted_ads w ON w.wanted_ad_id=m.wanted_ad_id 
					LEFT JOIN " . DB_PREFIX . "reverse_auctions r ON r.reverse_id=m.reverse_id 
					LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=m.receiver_id
					WHERE m.sender_id='" . $session->value('user_id') . "' AND m.sender_deleted=0
					ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit);

                $counter= 0 ;
				while ($msg_details = $db->fetch_array($sql_select_messages))
				{
					$background = ($counter++%2) ? 'c1' : 'c2';

					$content_options = '<a href="members_area.php?do=delete_message&type=sender_deleted&message_id=' . $msg_details['message_id'] . $additional_vars . $limit_link . $order_link . '" onclick="return confirm(\'' . MSG_DELETE_CONFIRM . '\');">' . MSG_DELETE . '</a>';

					$sent_messages_content .= '<tr class="' . $background . '"> '.
						'	<td class="contentfont" nowrap><a href="' . $msg->msg_board_link($msg_details) . '">' . $msg_details['receiver_username'] . '</a></td> '.
						'	<td class="contentfont">' . $msg->message_subject($msg_details) . '</td>'.
						'	<td align="center" nowrap>' . show_date($msg_details['reg_date']) . '</td> '.
						'	<td align="center" class="smallfont"><input name="delete[]" type="checkbox" id="delete[]" value="' . $msg_details['message_id'] . '" class="checkdelete"></td>'.
						'</tr>';
				}
			}
			else
			{
				$sent_messages_content = '<tr><td colspan="8" align="center">' . GMSG_NO_MESSAGES_MSG . '</td></tr>';
			}

			$template->set('sent_messages_content', $sent_messages_content);

			$pagination = paginate($start, $limit, $nb_messages, 'members_area.php', $additional_vars . $order_link);
			$template->set('pagination', $pagination);

			$members_area_page_content = $template->process('members_area_messaging_sent.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);
		}

	} /* END -> MESSAGING PAGES */
	
	if ($page == 'bidding' || $page == 'summary') /* BEGIN -> BIDDING PAGES */
	{
		if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'retract_bid')
		{

			$item_details_tmp = $db->get_sql_row("SELECT * FROM " . DB_PREFIX . "auctions WHERE auction_id='" . $_REQUEST['auction_id'] . "'");

			if (!$item->under_time($item_details_tmp))
			{
				$retract_output = $item->retract_bid($session->value('user_id'), $_REQUEST['auction_id']);
				$template->set('msg_changes_saved', '<p align="center">' . $retract_output['display'] . '</p>');
			}
		}

		if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'hide_bid')
		{
			$hide_output = $item->hide_bid($_REQUEST['bid_id'], $session->value('user_id'));
			$template->set('msg_changes_saved', '<p align="center">' . $hide_output . '</p>');
		}

		/*
		if ($_REQUEST['do'] == 'delete_item_watch')
		{
			$delete_output = $item->item_watch_delete($_REQUEST['auction_id'], $session->value('user_id'));
			$template->set('msg_changes_saved', '<p align="center">' . $delete_output . '</p>');
		}
		*/
		
		if (isset($_POST['form_watched_proceed']))
		{
			$nb_deletions = isset($_REQUEST['delete'])?$item->count_contents($_REQUEST['delete']):0;

			if ($nb_deletions > 0)
			{
				$delete_output = $item->item_watch_delete($db->implode_array($_REQUEST['delete']), $session->value('user_id'));
			} else
                $delete_output = '';
			$template->set('msg_changes_saved', '<p align="center">' . $delete_output . '</p>');
		}		

		if (isset($_POST['form_keywords_watch_proceed']))
		{
			$nb_deletions = $item->count_contents($_REQUEST['delete']);

			if ($nb_deletions > 0)
			{
				$delete_output = $item->keywords_watch_delete($db->implode_array($_REQUEST['delete']), $session->value('user_id'));
			}
			$template->set('msg_changes_saved', '<p align="center">' . $delete_output . '</p>');
		}		

		if (isset($_POST['form_keywords_watch_add_keyword']))
		{
			$keyword = $db->rem_special_chars($_REQUEST['keyword']);
			
			if (!empty($keyword))
			{
				$db->query("INSERT INTO " . DB_PREFIX . "keywords_watch 
					(keyword, user_id) VALUES ('" . $keyword . "', '" . $session->value('user_id') . "')");
				
				$template->set('msg_changes_saved', '<p align="center">' . MSG_KEYWORD_ADD_SUCCESS . '</p>');
			}
		}				
		
		if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'delete_fav_store')
		{
			$db->query("DELETE FROM " . DB_PREFIX . "favourite_stores WHERE id='" . intval($_REQUEST['id']) . "' AND 
				user_id='" . $session->value('user_id') . "'");
			$template->set('msg_changes_saved', '<p align="center">' . MSG_FAV_STORE_REMOVED . '</p>');
		}
		
		/* begin -> stats box */
		$nb_current_bids = $db->get_sql_field("SELECT count(*) AS nb_bids FROM " . DB_PREFIX . "bids b, " . DB_PREFIX . "auctions a WHERE
			b.bidder_id=" . $session->value('user_id') . " AND a.auction_id=b.auction_id AND a.active=1 AND a.closed=0 AND 
			a.deleted=0 AND b.deleted=0 AND b.bid_invalid=0", 'nb_bids');

		$nb_winning = $db->get_sql_field("SELECT count(*) AS nb_bids FROM " . DB_PREFIX . "bids b, " . DB_PREFIX . "auctions a WHERE

			b.bidder_id=" . $session->value('user_id') . " AND b.bid_out=0 AND b.bid_invalid=0 AND
			a.auction_id=b.auction_id AND a.active=1 AND a.closed=0 AND a.deleted=0 AND b.deleted=0", 'nb_bids');

		$nb_won_items = $db->count_rows('winners w', "WHERE w.buyer_id='" . $session->value('user_id') . "' AND
			w.b_deleted=0 AND w.sc_id=0" . $src_transactions_query);

		if ($setts['enable_stores'])
		{
			$nb_purchased_carts = $db->count_rows('shopping_carts', "WHERE buyer_id='" . $session->value('user_id') . "' AND
				sc_purchased=1 AND b_deleted=0");			
		}


		$template->set('nb_current_bids', $nb_current_bids);
		$template->set('nb_winning', $nb_winning);
		$template->set('nb_won_items', $nb_won_items);
		$template->set('nb_purchased_carts', isset($nb_purchased_carts)?$nb_purchased_carts:'');

		$members_area_stats = $template->process('members_area_stats_bidding.tpl.php');
		
		if ($page == 'summary')
		{
			$summary_page_content['stats_bidding'] = $members_area_stats;	
		}
		else 
		{
			$template->set('members_area_stats', $members_area_stats);
		}
		/* end -> stats box */

		if ($section == 'current_bids' || $page == 'summary')
		{
			$header_bidding_page = headercat('<b>' . MSG_MM_BIDDING . ' - ' . MSG_MM_CURRENT_BIDS . '</b>');

			$nb_bids = $nb_current_bids;

			$template->set('header_bidding_page', $header_bidding_page);
			$template->set('nb_bids', $nb_bids);

			if ($page == 'summary')
			{
				$order_field = 'b.bid_id';
				$order_type = 'DESC';
				
				$start = 0;
				$limit = 5;
			}
			else 
			{
				$template->set('page_order_auction_id', page_order('members_area.php', 'a.auction_id', $start, $limit, $additional_vars, MSG_AUCTION_ID));
				$template->set('page_order_itemname', page_order('members_area.php', 'a.name', $start, $limit, $additional_vars, MSG_ITEM_TITLE));
				$template->set('page_order_bid_amount', page_order('members_area.php', 'b.bid_amount', $start, $limit, $additional_vars, MSG_BID_AMOUNT));
				$template->set('page_order_bid_proxy', page_order('members_area.php', 'b.bid_proxy', $start, $limit, $additional_vars, MSG_PROXY_BID));
				$template->set('page_order_bid_date', page_order('members_area.php', 'b.bid_date', $start, $limit, $additional_vars, GMSG_DATE));
			}
			
			
			if ($nb_bids)
			{
				$sql_select_bids = $db->query("SELECT b.*, a.* FROM " . DB_PREFIX . "bids b, " . DB_PREFIX . "auctions a 
					WHERE b.bidder_id=" . $session->value('user_id') . " AND a.auction_id=b.auction_id AND a.active=1 AND
					a.closed=0 AND a.deleted=0 AND b.deleted=0 AND b.bid_invalid=0 
					ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit);

                $counter= 0 ;
				while ($bid_details = $db->fetch_array($sql_select_bids))
				{
					$background = ($counter++%2) ? 'c1' : 'c2';

					$content_options = array();

					if ($setts['enable_bid_retraction'])
					{
						if (!$item->under_time($bid_details))
						{
							$content_options[] = '<a href="members_area.php?do=retract_bid&auction_id=' . $bid_details['auction_id'] . $additional_vars . $limit_link . $order_link . '" onclick="return confirm(\'' . MSG_RETRACT_CONFIRM . '\');">' . MSG_RETRACT_BID . '</a>';
						}

						if ($bid_details['bid_out'])
						{
							$content_options[] = '<a href="members_area.php?do=hide_bid&bid_id=' . $bid_details['bid_id'] . $additional_vars . $limit_link . $order_link . '" onclick="return confirm(\'' . MSG_DELETE_CONFIRM . '\');">' . MSG_DELETE . '</a>';
						}
					}
					$options_output = $db->implode_array($content_options, '<br>');

					$media_url = $db->get_sql_field("SELECT media_url FROM " . DB_PREFIX . "auction_media WHERE auction_id=" . $bid_details['auction_id'] . " AND 
						media_type=1 AND upload_in_progress=0 ORDER BY media_id ASC", 'media_url');
					$auction_image = (!empty($media_url)) ? $media_url : 'themes/' . $setts['default_theme'] . '/img/system/noimg.gif';
					
					$auction_link = process_link('auction_details', array('auction_id' => $bid_details['auction_id']));
					
					$current_bids_content .= '<tr class="' . $background . '"> '.
						'	<td align="center"><a href="' . $auction_link . '"><img src="thumbnail.php?pic=' . $auction_image . '&w=50&sq=Y&b=Y" border="0" alt="' . $bid_details['name'] . '"></a></td> '.
						'	<td class="contentfont"><a href="' . $auction_link . '"># ' . $bid_details['auction_id'] . '</a></td> '.
						'	<td class="contentfont"><a href="' . $auction_link . '">' . $bid_details['name'] . '</a></td>'.
						'	<td align="center">' . $fees->display_amount($bid_details['bid_amount'], $bid_details['currency']) . '</td> '.
						'	<td align="center">' . $fees->display_amount($bid_details['bid_proxy'], $bid_details['currency']) . '</td>'.
						'	<td align="center">' . show_date($bid_details['bid_date']) . '</td>'.
						'	<td align="center">' . field_display($bid_details['bid_out'], GMSG_ACTIVE, GMSG_INACTIVE) . '</td>'.
						'	<td align="center" class="smallfont">' . $options_output . '</td>'.
						'</tr>';
				}
			}
			else
			{
				$current_bids_content = '<tr><td colspan="8" align="center">' . GMSG_NO_BIDS_MSG . '</td></tr>';
			}

			$template->set('current_bids_content', $current_bids_content);

			if ($page != 'summary') 
			{
				$pagination = paginate($start, $limit, $nb_bids, 'members_area.php', $additional_vars . $order_link);
				$template->set('pagination', $pagination);
			}

			$members_area_page_content = $template->process('members_area_bidding_current_bids.tpl.php');
			
			if ($page == 'summary')
			{
				$summary_page_content['bidding_current_bids'] = $members_area_page_content;
			}
			
			$template->set('members_area_page_content', $members_area_page_content);

		}

		if ($section == 'won_items')
		{
			$show_link = (isset($_REQUEST['show']))?'&show=' . $_REQUEST['show']:'';
			
			(string) $search_filter = null;
		
			if (isset($_REQUEST['show'])) {
                if (isset($_REQUEST['show']) && $_REQUEST['show'] == 'dd')
                {
                    $search_filter .= " AND w.is_dd=1";
                    $nb_won_items = $db->count_rows('winners w', "WHERE w.buyer_id='" . $session->value('user_id') . "' AND
                        w.b_deleted=0" . $search_filter . $src_transactions_query);
                }
                else if ($_REQUEST['show'] == 'no_dd')
                {
                    $search_filter .= " AND w.is_dd=0";
                    $nb_won_items = $db->count_rows('winners w', "WHERE w.buyer_id='" . $session->value('user_id') . "' AND
                        w.b_deleted=0" . $search_filter . $src_transactions_query);
                }
            }
			
			(string) $filter_items_content = null;

			$filter_items_content .= display_link('members_area.php?page=bidding&section=won_items', GMSG_ALL, ((!isset($_REQUEST['show'])) ? false : true)) . ' | ';
			$filter_items_content .= display_link('members_area.php?page=bidding&section=won_items&show=dd', MSG_DIGITAL_MEDIA_ATTACHED, ((isset($_REQUEST['show']) && $_REQUEST['show'] == 'dd') ? false : true)) . ' | ';
			$filter_items_content .= display_link('members_area.php?page=bidding&section=won_items&show=no_dd', MSG_NO_DIGITAL_MEDIA, ((isset($_REQUEST['show']) && $_REQUEST['show'] == 'no_dd') ? false : true));

			$template->set('filter_items_content', $filter_items_content);
			
			$nb_items = $nb_won_items;

			$template->set('nb_items', $nb_items);

			$template->set('page_order_auction_id', page_order('members_area.php', 'w.auction_id', $start, $limit, $additional_vars . $show_link, MSG_AUCTION_ID));
			$template->set('page_order_itemname', page_order('members_area.php', 'a.name', $start, $limit, $additional_vars . $show_link, MSG_ITEM_TITLE));
			$template->set('page_order_bid_amount', page_order('members_area.php', 'w.bid_amount', $start, $limit, $additional_vars . $show_link, MSG_WINNING_BID));
			$template->set('page_order_quantity', page_order('members_area.php', 'w.quantity_offered', $start, $limit, $additional_vars . $show_link, MSG_QUANTITY_OFFERED));
			$template->set('page_order_purchase_date', page_order('members_area.php', 'w.purchase_date', $start, $limit, $additional_vars . $show_link, MSG_PURCHASE_DATE));

			if ($nb_items)
			{
				$sql_select_won = $db->query("SELECT w.*, a.name AS auction_name, a.currency, a.category_id,
					a.bank_details, a.direct_payment, u.username, u.name, r.submitted, r.reputation_id, 
					i.refund_request, i.user_id AS payer_id 
					FROM " . DB_PREFIX . "winners w
					LEFT JOIN " . DB_PREFIX . "auctions a ON a.auction_id=w.auction_id
					LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=w.seller_id
					LEFT JOIN " . DB_PREFIX . "reputation r ON r.from_id=w.buyer_id AND r.winner_id=w.winner_id
					LEFT JOIN " . DB_PREFIX . "invoices i ON i.invoice_id=w.refund_invoice_id 
					WHERE w.buyer_id='" . $session->value('user_id') . "' AND w.b_deleted=0 
					" . $search_filter . $src_transactions_query . " 
					
					GROUP BY w.winner_id 
					ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit);
				
				$sale_fee = new fees();
				$sale_fee->setts = &$setts;

                $counter= 0 ;
				while ($item_details = $db->fetch_array($sql_select_won))
				{
					$background = ($counter++%2) ? 'c1' : 'c2';

					$sale_fee->set_fees($item_details['buyer_id'], $item_details['category_id']);## PHP Pro Bid v6.00 by default the seller will pay

					$item_paid = ($item_details['active'] == 1 && $item_details['payment_status'] == 'confirmed') ? 1 : 0;
					if ($item_paid)
					{
						$content_options = '&#8226; <a href="' . process_link('message_board', array('message_handle' => '3', 'winner_id' => $item_details['winner_id'])) . '"><b class="greenfont">' . MSG_MESSAGE_BOARD . '</b></a><br>';

						if (!$item_details['submitted'])
						{
							$content_options .= '&#8226; <a href="members_area.php?page=reputation&section=post&reputation_ids=' . $item_details['reputation_id'] . '">' . MSG_LEAVE_COMMENTS . '</a><br>';
						}

						$content_options .= (!empty($item_details['bank_details'])) ? '&#8226; <a href="javascript:void(0)" onClick="openPopup(\'popup_bank_details.php?auction_id=' . $item_details['auction_id'] . '\')">' . MSG_VIEW_BANK_DETAILS . '</a><br>' : '';
						
						if (!$item_details['invoice_sent'])
						{
							$content_options .= '&#8226; <a href="members_area.php?do=delete_winner&option=buyer&winner_id=' . $item_details['winner_id'] . $additional_vars . $order_link . $limit_link . $show_link . '" onclick="return confirm(\'' . MSG_DELETE_CONFIRM . '\');"><b class="redfont">' . MSG_DELETE . '</b></a>';
						}
					}
					else
					{
						if (eregi('b', $sale_fee->fee['endauction_fee_applies']))
						{
							$content_options = '&#8226; <a href="fee_payment.php?do=sale_fee_payment&winner_id=' . $item_details['winner_id'] . '">' . MSG_PAY_ENDAUCTION_FEE . '</a>';
						}
						else
						{
							$content_options = '&#8226; ' . MSG_ENDAUCTION_FEE_NOT_PAID;
						}
					}

					if ($item_details['invoice_id'])
					{
						$direct_payment_link = '[ <a href="fee_payment.php?do=invoice_direct_payment&invoice_id=' . $item_details['invoice_id'] . '">' . MSG_PAY_WITH_DIRECT_PAYMENT . '</a> ]';							
					}
					else 
					{
						$direct_payment_link = '[ <a href="' . process_link('auction_details', array('auction_id' => $item_details['auction_id'])) . '">' . field_display(MSG_PAY_WITH_DIRECT_PAYMENT, '') . '</a> ]';
					}

					$won_auctions_content .= '<tr class="' . $background . '"> '.
						'	<td class="contentfont"><a href="' . process_link('auction_details', array('auction_id' => $item_details['auction_id'])) . '"># ' . $item_details['auction_id'] . '</a> - '.
						'		<a href="' . process_link('auction_details', array('auction_id' => $item_details['auction_id'])) . '">' . field_display($item_details['auction_name'], MSG_AUCTION_DELETED) . '</a>'.
						(($item_paid && !empty($item_details['direct_payment']) && !$item_details['direct_payment_paid'] && !$item_details['flag_paid'] && $item_details['bid_amount'] > 0) ? '<br><br>' . $direct_payment_link : '').
						'	</td>'.
						'	<td align="center">' . $fees->display_amount($item_details['bid_amount'], $item_details['currency']) . '</td>'.
						'	<td align="center">' . MSG_REQUESTED . ': ' . $item_details['quantity_requested'] . '<br> '.
						'		' . MSG_OFFERED . ': ' . $item_details['quantity_offered'] . '</td> '.
						'	<td align="center"> ';

					if ($item_paid)
					{
						$won_auctions_content .= '<table border="0" cellpadding="0" cellspacing="0" class="wonAuctions border"> '.
	            		'	<tr valign="top" bgcolor="#FFFFF"> '.
	               	'		<td class="smallfont" nowrap><b>' . MSG_USERNAME . '</b></td> '.
	               	'		<td class="smallfont" width="100%">' . field_display($item_details['username'], GMSG_NA) . '</td> '.
	            		'	</tr> '.
	            		'	<tr valign="top" bgcolor="#FFFFF"> '.
	               	'		<td class="smallfont" nowrap><b>' . MSG_FULL_NAME . '</b></td> '.
	               	'		<td class="smallfont">' . field_display($item_details['name'], GMSG_NA) . '</td> '.
	            		'	</tr> '.
	         			'</table> ';
					}

         		$won_auctions_content .= '	</td>'.
						'	<td align="center">';
					if ($item_paid)
					{
						$won_auctions_content .= '<table border="0" cellpadding="0" cellspacing="0" class="wonAuctions border smallfont"> '.
               		'	<tr bgcolor="#FFFFF"> '.
                  	'		<td align="center">' . show_date($item_details['purchase_date']) . '</td> '.
               		'	</tr> '.
               		'	<tr bgcolor="#FFFFF"> '.
                  	'		<td align="center">' . $item->flag_paid($item_details['flag_paid'], $item_details['direct_payment_paid']) . '</td> '.
               		'	</tr> '.
               		'	<tr bgcolor="#FFFFF"> '.
                  	'		<td align="center">' . $item->flag_status($item_details['flag_status']) . '</td> '.
               		'	</tr> '.
         				'</table>';
					}
					$won_auctions_content .= '	</td>'.
						'	<td class="smallfont">' . $content_options . '</td>'.
						'</tr>';

					if ($item_paid)					
					{
						if ($item_details['invoice_sent'])
					   {
						   $won_auctions_content .= '<tr> '.
							   '	<td colspan="6" class="contentfont">'.
							   '		&#8226; <b>' . MSG_INVOICE_RECEIVED . '</b> [ ' . MSG_ID . ': ' . $item_details['invoice_id'] . ' ] <a href="' . process_link('invoice_print', array('invoice_type' => 'product_invoice', 'invoice_id' => $item_details['invoice_id'])) . '" target="_blank">' . MSG_VIEW_PRODUCT_INVOICE . '</a>'.
							   '	</td> '.
							   '</tr> ';
					   }
					   else if ($setts['enable_buyer_create_invoice'])
					   {
							$won_auctions_content .= '<tr> '.
							   '	<td colspan="6" class="contentfont">'.
							   '		&#8226; <a href="members_area.php?page=bidding&section=product_invoice&seller_id=' . $item_details['seller_id'] . '&auction_id=' . $item_details['auction_id'] . '">'  . MSG_COMBINE_PURCHASES . '</a>'.
								'	</td> '.
							   '</tr> ';
					   }
						
						if ($item_details['payer_id'] == $session->value('user_id') && $item->request_refund($item_details['refund_invoice_id'], $item_details['purchase_date'], $item_details['flag_paid'], $item_details['refund_request']))
						{
							$won_auctions_content .= '<tr> '.
							   '	<td colspan="6" class="contentfont">'.
							   '		&#8226; <a href="members_area.php?do=request_refund&refund_invoice_id=' . $item_details['refund_invoice_id'] . $additional_vars . $order_link . $limit_link  . $show_link. '" onclick="return confirm(\'' . MSG_REQUEST_REFUND_CONFIRM . '\');">' . MSG_REQUEST_EOA_REFUND . '</a>';
								'	</td> '.
							   '</tr> ';
						}

						if ($item_details['is_dd'])
						{
							$dd_expires = dd_expires($item_details['dd_active_date']);
							
							$won_auctions_content .= '<tr class="c7"> '.
								'	<td colspan="2"><b>' . MSG_DIGITAL_MEDIA_ATTACHED . '</b></td> '.
								'	<td align="center">' . MSG_DOWNLOADED . ' ' . $item_details['dd_nb_downloads'] . ' ' . MSG_TIMES . '</td>'.
								(($item_details['dd_active']) ? '<form action="" method="post"><input type="hidden" name="winner_id" value="' . $item_details['winner_id'] . '">' : '') . 
								'	<td align="center"><input name="form_download_proceed" type="submit" id="form_download_proceed" value="' . MSG_DOWNLOAD_MEDIA . '" ' . (($item_details['dd_active'] && $dd_expires['result']>0) ? '' : 'disabled') . '/></td>'.
								(($item_details['dd_active']) ? '</form>' : '') . 
								'	<td align="center" colspan="2">' . MSG_LINK_EXPIRES . ': ' . (($item_details['dd_active']) ? $dd_expires['display'] : GMSG_NA) . '</td>'.
								'</tr>';
						}							
					}
					
					if ($item_details['temp_purchase'])
					{
						$won_auctions_content .= '<tr> '.
							'	<td colspan="6" class="c3"><b>' . MSG_BUYOUT_FORCE_PAYMENT_ALERT . '</b></td> '.
							'</tr>';						
					}
					
					$won_auctions_content .= '<tr> '.
						'	<td colspan="6" class="c4"></td> '.
						'</tr>';
				}

			}
			else
			{
				$won_auctions_content = '<tr><td colspan="6" align="center">' . GMSG_NO_ITEMS_MSG . '</td></tr>';
			}

			$template->set('won_auctions_content', $won_auctions_content);

			$pagination = paginate($start, $limit, $nb_items, 'members_area.php', $additional_vars . $order_link . $show_link);
			$template->set('pagination', $pagination);

			$members_area_page_content = $template->process('members_area_bidding_won_items.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);

		}
		if ($section == 'purchased_carts')
		{
			if (PC_DM == 1)
			{
				header_redirect('shopping_cart.php?filter_carts=unpaid');
			}
			else 
			{
				$limit = 3; // 3 carts per page
			
			   $cart = new shop();
			   $cart->setts = &$setts;
			   $cart->sc_field = 'buyer_id';
			   $cart->sc_value = $session->value('user_id');
   
			   $nb_items = $nb_purchased_carts;
   
			   $template->set('nb_items', $nb_items);
   
			   $template->set('page_order_sc_id', page_order('members_area.php', 'sc.sc_id', $start, $limit, $additional_vars, MSG_SC_ID));
			   $template->set('page_order_purchase_date', page_order('members_area.php', 'sc.sc_date', $start, $limit, $additional_vars, MSG_PURCHASE_DATE));
   
			   if ($nb_items)
			   {
				   $sql_select_purchased_carts = $db->query("SELECT sc.*, u.username, u.shop_direct_payment, u.shop_buyer_shipping, 
					   (SELECT count(*) FROM " . DB_PREFIX . "shopping_carts_items sci WHERE sci.sc_id=sc.sc_id) AS nb_cart_items, 
					   (SELECT count(*) FROM " . DB_PREFIX . "winners w WHERE w.sc_id=sc.sc_id AND w.active=1 AND w.payment_status='confirmed') AS nb_cart_items_paid, 
					   m.message_id FROM " . DB_PREFIX . "shopping_carts sc 
					   LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=sc.seller_id
					   LEFT JOIN " . DB_PREFIX . "messaging m ON m.sc_id=sc.sc_id AND m.is_read=0 AND m.sender_id!=sc.buyer_id 					
					   WHERE sc.buyer_id='" . $session->value('user_id') . "' AND sc.sc_purchased=1 AND sc.b_deleted=0  
					   ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit);

                   $counter= 0 ;
				   while ($sc_details = $db->fetch_array($sql_select_purchased_carts))
				   {
					   $background = ($counter++%2) ? 'c1' : 'c2';
   
					   $cart_paid = ($sc_details['nb_cart_items'] == $sc_details['nb_cart_items_paid']) ? 1 : 0;
					   if ($cart_paid)
					   {
						   $content_options = '&#8226; <a href="' . process_link('message_board', array('message_handle' => '6', 'sc_id' => $sc_details['sc_id'])) . '"><b class="greenfont">' . MSG_MESSAGE_BOARD . '</b></a><br>';
						   $content_options .= '&#8226; <a href="members_area.php?do=delete_cart&option=buyer&sc_id=' . $sc_details['sc_id'] . $additional_vars . $order_link . $limit_link . '" onclick="return confirm(\'' . MSG_DELETE_CONFIRM . '\');"><b class="redfont">' . MSG_DELETE . '</b></a>';
					   }
					   else
					   {
						   $sale_fee = new fees();
						   $sale_fee->setts = &$setts;
   
						   $sale_fee->set_fees($sc_details['buyer_id']);
   
						   if (eregi('b', $sale_fee->fee['endauction_fee_applies']))
						   {
							   $content_options = '&#8226; <a href="fee_payment.php?do=sc_sale_fee_payment&sc_id=' . $sc_details['sc_id'] . '">' . MSG_PAY_ENDAUCTION_FEE . '</a>';
						   }
						   else
						   {
							   $content_options = '&#8226; ' . MSG_ENDAUCTION_FEE_NOT_PAID;
						   }
					   }
   
					   $purchased_carts_content .= '<tr> '.
					   '	<td class="contentfont">' . $cart->cart_table_display($sc_details['sc_id']) . '</td>'.
					   '	<td align="center" class="' . $background . '"> ';
					   
					   if ($cart_paid)
					   {
						   $purchased_carts_content .= '<table border="0" cellpadding="0" cellspacing="0" class="purchasedCarts border"> '.
						   '	<tr valign="top" bgcolor="#FFFFF"> '.
						   '		<td class="smallfont" nowrap><b>' . MSG_USERNAME . '</b></td> '.
						   '		<td class="smallfont" width="100%">' . field_display($sc_details['username'], GMSG_NA) . '</td> '.
						   '	</tr> '.
						   '</table><br>';
   
						   $purchased_carts_content .=
						   '<table border="0" cellpadding="0" cellspacing="0" class="purchasedCarts border"> '.
						   '	<tr> '.
						   '		<td align="center">' . show_date($sc_details['sc_date']) . '</td> '.
						   '	</tr> '.
						   '	<tr bgcolor="#FFFFF"> '.
						   '			<td align="center">' . $item->flag_paid($sc_details['flag_paid'], $sc_details['direct_payment_paid']) . '</td> '.
						   '		</tr> '.
						   '		<tr bgcolor="#FFFFF"> '.
						   '			<td align="center">' . $item->flag_status($sc_details['flag_status']) . '</td> '.
						   '		</tr> '.
							'</table>';
					   }
					   $purchased_carts_content .= '	</td>'.
					   '	<td class="smallfont ' . $background . '">' . $content_options . '</td>'.
					   '</tr>';
   
					   if ($cart_paid)
					   {
						   $purchased_carts_content .= '<tr><td colspan="6" class="contentfont">';
						   if ($sc_details['shipping_calc_auto'])
						   {
							   $purchased_carts_content .= '&#8226; <b>' . MSG_INVOICE_SENT . '</b> [ ' . MSG_ID . ': ' . $sc_details['sc_id'] . ' ] <a href="' . process_link('invoice_print', array('invoice_type' => 'sc_invoice', 'invoice_id' => $sc_details['sc_id'])) . '" target="_blank">' . MSG_VIEW_CART_INVOICE . '</a>';
							   
							   if ($sc_details['shop_direct_payment'] && !$sc_details['flag_paid'] && !$sc_details['direct_payment_paid'])
							   {
								   $purchased_carts_content .= ' &#8226; <a href="' . process_link('fee_payment', array('do' => 'sc_direct_payment', 'sc_id' => $sc_details['sc_id'])) . '" target="_blank">' . MSG_PAY_WITH_DIRECT_PAYMENT . '</a>';								
							   }
						   }
						   $purchased_carts_content .= '</td></tr>';
					   }
					   $purchased_carts_content .= '<tr><td colspan="6" class="c4"></td></tr>';
				   }
			   }
			   else
			   {
				   $purchased_carts_content = '<tr><td colspan="8" align="center">' . GMSG_NO_CARTS_MSG . '</td></tr>';
			   }
   
			   $template->set('purchased_carts_content', $purchased_carts_content);
   
			   $pagination = paginate($start, $limit, $nb_items, 'members_area.php', $additional_vars . $order_link);
			   $template->set('pagination', $pagination);
   
			   $members_area_page_content = $template->process('members_area_bidding_purchased_carts.tpl.php');
			   $template->set('members_area_page_content', $members_area_page_content);
		   }
		}


		if ($section == 'invoices_received')
		{
			$nb_items = $db->get_sql_number("SELECT winner_id FROM " . DB_PREFIX . "winners WHERE 
				invoice_sent=1 AND buyer_id='" . $session->value('user_id') . "' GROUP BY invoice_id");
			$template->set('nb_items', $nb_items);

			(string) $invoices_received_content = null;
			
			if ($nb_items)
			{
				$sql_select_invoices = $db->query("SELECT w.*, u.username FROM " . DB_PREFIX . "winners w
					LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=w.seller_id
					WHERE w.buyer_id='" . $session->value('user_id') . "' AND w.b_deleted=0 AND w.invoice_sent=1
					GROUP BY w.invoice_id
					ORDER BY w.invoice_id DESC LIMIT " . $start . ", 5");
				
				while ($invoice_details = $db->fetch_array($sql_select_invoices)) 
				{
					$invoices_received_content .= '<tr> '.
						'	<td colspan="2">[ ' . MSG_INVOICE_ID . ': ' . $invoice_details['invoice_id'] . ' ] &nbsp; [ ' . MSG_SELLER_USERNAME . ': ' . $invoice_details['username'] . ' ]'.
						'	<td align="center" class="contentfont">[ <a href="members_area.php?do=delete_invoice&option=buyer&invoice_id=' . $invoice_details['invoice_id'] . $additional_vars . $order_link . $limit_link . '" onclick="return confirm(\'' . MSG_DELETE_CONFIRM . '\');"><b class="redfont">' . MSG_DELETE . '</b></a> ]'.
						'</tr> ';
					
					$sql_select_products = $db->query("SELECT w.*, a.name,  
						a.direct_payment, a.currency FROM " . DB_PREFIX . "winners w 
						LEFT JOIN " . DB_PREFIX . "auctions a ON a.auction_id=w.auction_id WHERE 
						w.invoice_id='" . $invoice_details['invoice_id'] . "'");
					
					$invoices_received_content .= '<tr align="center" class="membmenu"> '.
						'	<td align="left">' . GMSG_DESCRIPTION . '</td> '.
						'	<td>' . GMSG_QUANTITY . '</td> '.
						'	<td>' . GMSG_PRICE_ITEM . '</td> '.
						'</tr> '.
						'<tr class="c3"> '.
						'	<td width="100%"></td> '.
						'	<td><img src="themes/' . $setts['default_theme'] . '/img/pixel.gif" width="60" height="1"></td> '.
						'	<td><img src="themes/' . $setts['default_theme'] . '/img/pixel.gif" width="80" height="1"></td> '.
						'</tr> ';

					(array) $dp_array = null;
					(array) $items_array = null;
					
					$product_postage = null;
					$product_insurance = null;
					while ($item_details = $db->fetch_array($sql_select_products)) 
					{
						$background = 'c1';
						
						$currency = $item_details['currency'];
						
						$product_postage = ($item_details['postage_included']) ? (($item_details['pc_postage_type'] == 'item') ? ($item_details['postage_amount'] + $product_postage) : $item_details['postage_amount']) : 0;
						$product_insurance += ($item_details['insurance_included']) ? $item_details['insurance_amount'] : 0;
						$auction_link = process_link('auction_details', array('name' => $item_details['name'], 'auction_id' => $item_details['auction_id']));
						
						$invoices_received_content .= '<tr class="' . $background . '" align="center"> '.
							'	<td align="left" class="contentfont">[ ' . MSG_ID . ': <a href="' . $auction_link . '">' . $item_details['auction_id'] . '</a> ] <a href="' . $auction_link . '">' . $item_details['name'] . '</a></td> '.
							'	<td>' . $item_details['quantity_offered'] . '</td> '.
							'	<td>' . $fees->display_amount($item_details['bid_amount'], $item_details['currency']) . '</td> '.
							'</tr> ';
						
						$items_array[] = $item_details;
						$dp_array[] = ($item_details['direct_payment']) ? @explode(',', $item_details['direct_payment']) : null;
					}

					// new postage and insurance tab
					$invoices_received_content .= '<tr> '.
						'	<td colspan="5" class="c4"></td> '.
						'</tr> '.
						'<tr> '.
						'	<td></td>'.
						'	<td class="c1"  >' . MSG_POSTAGE . ':</td>'.
						'	<td class="c1" align="center">' . $fees->display_amount($product_postage, $currency) . '</td>'.
						'</tr> '.
						'<tr> '.
						'	<td></td>'.
						'	<td class="c1" >' . MSG_INSURANCE . ':</td>'.
						'	<td class="c1" align="center">' . $fees->display_amount($product_insurance, $currency) . '</td>'.
						'</tr> '.
						'<tr> '.
						'	<td colspan="5" class="c4"></td> '.
						'</tr> ';
					
					
					(string) $direct_payment_link = null;
					$is_direct_payment = $item->direct_payment_multiple($invoice_details['invoice_id'], $items_array, $dp_array, $session->value('user_id'));
					
					if ($is_direct_payment)
					{
						$direct_payment_link = '[ <a href="fee_payment.php?do=invoice_direct_payment&invoice_id=' . $invoice_details['invoice_id'] . '">' . MSG_PAY_WITH_DIRECT_PAYMENT . '</a> ]';	
					}
					
					$invoices_received_content .= '<tr> '.
						'	<td colspan="5" class="contentfont">[ <a href="' . process_link('invoice_print', array('invoice_type' => 'product_invoice', 'invoice_id' => $invoice_details['invoice_id'])) . '" target="_blank">' . MSG_VIEW_PRODUCT_INVOICE . '</a> ] ' . $direct_payment_link . '</td>'.
						'</tr> '.
						'<tr> '.
						'	<td colspan="5" class="c4"></td> '.
						'</tr> ';
				}
			}
			else 
			{
				$invoices_received_content = '<tr><td colspan="5" align="center">' . GMSG_NO_ITEMS_MSG . '</td></tr>';
			}
			
			$template->set('invoices_received_content', $invoices_received_content);
			
			$pagination = paginate($start, 5, $nb_items, 'members_area.php', $additional_vars . $order_link);
			$template->set('pagination', $pagination);
			
			$members_area_page_content = $template->process('members_area_bidding_invoices_received.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);
		}
		
		if ($section == 'item_watch')
		{
			$nb_items = $db->count_rows('auction_watch', "WHERE user_id='" . $session->value('user_id') . "'");

			$template->set('nb_items', $nb_items);

			$template->set('page_order_auction_id', page_order('members_area.php', 'a.auction_id', $start, $limit, $additional_vars, MSG_AUCTION_ID));
			$template->set('page_order_end_time', page_order('members_area.php', 'a.end_time', $start, $limit, $additional_vars, GMSG_END_TIME));
			$template->set('page_order_itemname', page_order('members_area.php', 'a.name', $start, $limit, $additional_vars, MSG_ITEM_TITLE));

			if ($nb_items)
			{
				$sql_select_items = $db->query("SELECT aw.*, a.name, a.end_time FROM " . DB_PREFIX . "auction_watch aw
					LEFT JOIN " . DB_PREFIX . "auctions a ON a.auction_id=aw.auction_id
					WHERE aw.user_id='" . $session->value('user_id') . "'
					ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit);

                $counter = 0;
				while ($item_details = $db->fetch_array($sql_select_items))
				{
					$background = ($counter++%2) ? 'c1' : 'c2';

					//$content_options = '<a href="members_area.php?do=delete_item_watch&auction_id=' . $item_details['auction_id'] . $additional_vars . $limit_link . $order_link . '" onclick="return confirm(\'' . MSG_DELETE_CONFIRM . '\');">' . MSG_DELETE . '</a>';
					$content_options = '<input name="delete[]" type="checkbox" id="delete[]" value="' . $item_details['auction_id'] . '" class="checkdelete">';

                    if (!isset($watched_items_content))
                        $watched_items_content='';
					$watched_items_content .= '<tr class="' . $background . '"> '.
						'	<td class="contentfont"><a href="' . process_link('auction_details', array('auction_id' => $item_details['auction_id'])) . '"># ' . $item_details['auction_id'] . '</a></td> '.
						'	<td class="contentfont"><a href="' . process_link('auction_details', array('auction_id' => $item_details['auction_id'])) . '">' . $item_details['name'] . '</a></td>'.
						'	<td align="center">' . time_left($item_details['end_time']) . '</td>'.
						'	<td align="center" class="smallfont">' . $content_options . '</td>'.
						'</tr>';
				}

			}
			else
			{
				$watched_items_content = '<tr><td colspan="8" align="center">' . GMSG_NO_ITEMS_MSG . '</td></tr>';
			}

			$template->set('watched_items_content', $watched_items_content);

			$pagination = paginate($start, $limit, $nb_items, 'members_area.php', $additional_vars . $order_link);
			$template->set('pagination', $pagination);

			$members_area_page_content = $template->process('members_area_bidding_item_watch.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);

		}

		if ($section == 'favorite_stores')
		{
			$nb_items = $db->count_rows('favourite_stores', "WHERE user_id='" . $session->value('user_id') . "'");

			$template->set('nb_items', $nb_items);

			if ($nb_items)
			{
				$sql_select_items = $db->query("SELECT s.*, u.shop_name, u.username, u.shop_nb_items FROM " . DB_PREFIX . "favourite_stores s
					LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=s.store_id
					WHERE s.user_id='" . $session->value('user_id') . "'
					ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit);

                $counter = 0;
				while ($item_details = $db->fetch_array($sql_select_items))
				{
					$background = ($counter++%2) ? 'c1' : 'c2';

					$content_options = '<a href="members_area.php?do=delete_fav_store&id=' . $item_details['id'] . $additional_vars . $limit_link . $order_link . '" onclick="return confirm(\'' . MSG_DELETE_CONFIRM . '\');">' . MSG_DELETE . '</a>';

					$fav_stores_content .= '<tr class="' . $background . '"> '.
						'	<td class="contentfont"><a href="' . process_link('shop', array('user_id' => $item_details['store_id'])) . '"># ' . $item_details['store_id'] . '</a></td> '.
						'	<td class="contentfont"><a href="' . process_link('shop', array('user_id' => $item_details['store_id'])) . '">' . $item_details['shop_name'] . '</a></td>'.
						'	<td class="contentfont" align="center"><a href="' . process_link('shop', array('user_id' => $item_details['store_id'])) . '">' . $item_details['username'] . '</a></td>'.
						'	<td class="contentfont" align="center">' . $item_details['shop_nb_items'] . '</td>'.						
						'	<td align="center" class="smallfont">' . $content_options . '</td>'.
						'</tr>';
				}

			}
			else
			{
				$fav_stores_content = '<tr><td colspan="8" align="center">' . GMSG_NO_ITEMS_MSG . '</td></tr>';
			}

			$template->set('fav_stores_content', $fav_stores_content);

			$pagination = paginate($start, $limit, $nb_items, 'members_area.php', $additional_vars . $order_link);
			$template->set('pagination', $pagination);

			$members_area_page_content = $template->process('members_area_bidding_favorite_stores.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);

		}
		
		if ($section == 'keywords_watch')
		{
			$nb_items = $db->count_rows('keywords_watch', "WHERE user_id='" . $session->value('user_id') . "'");

			$template->set('nb_items', $nb_items);
            $rOption = isset($_REQUEST['option'])?$_REQUEST['option']:'';
			$template->set('option', $rOption);

			$template->set('page_order_keyword', page_order('members_area.php', 'keyword', $start, $limit, $additional_vars, MSG_KEYWORD));

			if ($nb_items)
			{
				$sql_select_items = $db->query("SELECT * FROM " . DB_PREFIX . "keywords_watch 
					WHERE user_id='" . $session->value('user_id') . "'
					ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit);

                $counter = 0;
				while ($item_details = $db->fetch_array($sql_select_items))
				{
					$background = ($counter++%2) ? 'c1' : 'c2';

					$content_options = '<input name="delete[]" type="checkbox" id="delete[]" value="' . $item_details['keyword_id'] . '" class="checkdelete">';

					$keywords_watch_content .= '<tr class="' . $background . '"> '.
						'	<td class="contentfont" colspan="2">' . $item_details['keyword'] . '</td> '.
						'	<td align="center" class="smallfont">' . $content_options . '</td>'.
						'</tr>';
				}

			}
			else
			{
				$keywords_watch_content = '<tr><td colspan="8" align="center">' . GMSG_NO_ITEMS_MSG . '</td></tr>';
			}

			$template->set('keywords_watch_content', $keywords_watch_content);

			$pagination = paginate($start, $limit, $nb_items, 'members_area.php', $additional_vars . $order_link);
			$template->set('pagination', $pagination);

			$members_area_page_content = $template->process('members_area_bidding_keywords_watch.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);

		}
	} /* END -> BIDDING PAGES */
	
	if ($page == 'selling' || ($page == 'summary' && $session->value('is_seller'))) /* BEGIN -> SELLING PAGES */
	{
		if ($page == 'selling' && $session->value('is_seller') && $setts['enable_seller_verification'])
		{ 
			## seller verification status box
			$seller_details = $db->get_sql_row("SELECT seller_verified FROM
				" . DB_PREFIX . "users WHERE user_id=" . $session->value('user_id'));
			$template->set('seller_details', $seller_details);
			
			$seller_verified_status_box = $template->process('members_area_stats_verif_status_box.tpl.php');
			$template->set('seller_verified_status_box', $seller_verified_status_box);
		}
		
		if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'delete_auction')
		{
			$item->delete($_REQUEST['auction_id'], $session->value('user_id'));
		}

		if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'close_auction')
		{
			$auction_id = intval($_REQUEST['auction_id']);
			$close_item_details = $db->get_sql_row("SELECT * FROM " . DB_PREFIX . "auctions WHERE auction_id='" . $auction_id . "'");
			
			if ($item->can_close_manually($close_item_details, $session->value('user_id')))
			{
				$db->query("UPDATE " . DB_PREFIX . "auctions SET close_in_progress=1 WHERE
					auction_id='" . $auction_id . "'");

				$item->close($close_item_details, false, false);

				$db->query("UPDATE " . DB_PREFIX . "auctions SET close_in_progress=0 WHERE
					auction_id='" . $auction_id . "'");
			}
		}				
		
		if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'closed_proceed')
		{
			$nb_relists = isset($_REQUEST['relist'])?$item->count_contents($_REQUEST['relist']):0;
			$nb_deletions = isset($_REQUEST['delete'])?$item->count_contents($_REQUEST['delete']):0;

			if ($nb_relists > 0)
			{
				for ($i=0; $i<$nb_relists; $i++)
				{
					$relist_id = $_REQUEST['relist'][$i];					
					$relist_result = $item->relist($relist_id, $session->value('user_id'), $_REQUEST['duration'][$relist_id]);
					$relist_output[] = $relist_result['display'];
				}

				$template->set('msg_auction_relist', '<p align="center">' . $db->implode_array($relist_output, '<br>') . '</p>');
			}

			if ($nb_deletions > 0)
			{
				$item->delete($db->implode_array($_REQUEST['delete']), $session->value('user_id'));
			}
		}

		if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'delete_offer')
		{
			$msg_changes_saved = '<p align="center">' . MSG_OFFER_DELETED_SUCCESS . '</p>';
			$template->set('msg_changes_saved', $msg_changes_saved);

			if (in_array($_REQUEST['offer_type'], array('auction_offers', 'swaps')))
			{
				$item->delete_offer($_REQUEST['offer_type'], $_REQUEST['offer_id'], $session->value('user_id'));
			}
		}

		if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'accept_offer')
		{
			$msg_changes_saved = '<p align="center">' . MSG_OFFER_ACCEPTED_SUCCESS . '</p>';
			$template->set('msg_changes_saved', $msg_changes_saved);

			if (in_array($_REQUEST['offer_type'], array('auction_offers', 'swaps', 'bids')))
			{
				$item->accept_offer($_REQUEST['offer_type'], $_REQUEST['offer_id'], $session->value('user_id'));
			}
		}
		
		if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'resend_invoice')
		{
			$invoice_id = intval($_REQUEST['invoice_id']);
			
			$is_invoice = $db->count_rows('winners', "WHERE invoice_id='" . $invoice_id . "' AND 
				seller_id='" . $session->value('user_id') . "'");

			if ($is_invoice && $invoice_id > 0)
			{
				$item->resend_invoice($invoice_id);
				$template->set('msg_changes_saved', '<p align="center">' . MSG_INVOICE_SENT_SUCCESSFULLY . '</p>');
			}
		}

		if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'dd_active')
		{
			activate_dd($_REQUEST['winner_id'], $session->value('user_id'), $_REQUEST['value']);			
			
			$template->set('msg_changes_saved', '<p align="center">' . MSG_CHANGES_SAVED . '</p>');
		}
		if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'resend_cart_invoice')
		{
			$is_invoice = $db->count_rows('shopping_carts', "WHERE sc_id='" . $invoice_id . "' AND 
				seller_id='" . $session->value('user_id') . "' AND sc_purchased=1 AND shipping_calc_auto=1");

			if ($is_invoice && $invoice_id > 0)
			{
				$mail_input_id = $invoice_id;
				include('language/' . $setts['site_lang'] . '/mails/cart_invoice_buyer_notification.php');				
				
				$template->set('msg_changes_saved', '<p align="center">' . MSG_CART_INVOICE_RESENT_SUCCESSFULLY . '</p>');
			}
		}
		
		
		if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'add_suggested_category')
		{
			$category_desc = $db->rem_special_chars($_REQUEST['category_desc']);

			$db->query("INSERT INTO " . DB_PREFIX . "suggested_categories 
				(userid, content, regdate) VALUES 
				('" . $session->value('user_id') . "', '" . $category_desc . "', '" . CURRENT_TIME . "')");
			
			$template->set('msg_changes_saved', '<p align="center">' . MSG_CATEGORY_SUGGESTION_SUCCESS . '</p>');
		}
		
		if ($section == 'suggest_category')
		{
			$members_area_page_content = $template->process('members_area_selling_suggest_category.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);			
		}
		
		if ($section == 'view_offers')
		{
			$item_details = $db->get_sql_row("SELECT a.*, w.seller_id, w.purchase_date, w.flag_paid, w.buyer_id FROM " . DB_PREFIX . "auctions a 
				LEFT JOIN " . DB_PREFIX . "winners w ON w.auction_id=a.auction_id WHERE
				a.owner_id='" . $session->value('user_id') . "' AND a.auction_id='" . intval($_REQUEST['auction_id']) . "' 				
				GROUP BY a.auction_id");
			
			## add a can_make_offer function which checks if offers are available.
			$can_make_offer = $item->can_make_offer($item_details);

			if ($item->count_contents($item_details))
			{
				$template->set('item_details', $item_details);

				if (!empty($item_details['direct_payment']))
				{
					$dp_methods = $item->select_direct_payment($item_details['direct_payment'], 0, true, true);

					$template->set('direct_payment_methods_display', $db->implode_array($dp_methods, ', '));
				}

				if (!empty($item_details['payment_methods']))
				{
					$offline_payments = $item->select_offline_payment($item_details['payment_methods'], true, true);

					$template->set('offline_payment_methods_display', $db->implode_array($offline_payments, ', '));
				}

				(string) $winning_bids_content = null;
				(string) $make_offer_content = null;
				(string) $reserve_offer_content = null;
				(string) $second_chance_content = null;
				(string) $swap_offer_content = null;

				/**
				 * first we will show on this page if there are any winners on this auction with the possibility to 
				 * delete the winning bid rows
				 */
				if ($item_details['closed'] == 1)
				{
					$sql_select_winning_bids = $db->query("SELECT w.*,	u.username, a.currency FROM " . DB_PREFIX . "winners w
						LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=w.buyer_id
						LEFT JOIN " . DB_PREFIX . "auctions a ON a.auction_id=w.auction_id
						WHERE w.auction_id='" . $item_details['auction_id'] . "' AND w.seller_id='" . $session->value('user_id') . "' 
						ORDER BY w.winner_id DESC");

                    $counter = 0;
					while ($winning_bid_details = $db->fetch_array($sql_select_winning_bids))
					{
						$background = ($counter++%2) ? 'c1' : 'c2';
						
						$winning_bids_content .= '<tr class="' . $background . '"> '.
      					'	<td>' . $winning_bid_details['username'] . user_pics($winning_bid_details['buyer_id']) . '</td> '.
      					'	<td align="center">' . $winning_bid_details['quantity_offered'] . '</td> '.
      					'	<td align="center">' . $fees->display_amount($winning_bid_details['bid_amount'], $winning_bid_details['currency']) . '</td> '.
      					'	<td align="center">' . show_date($winning_bid_details['purchase_date']) . '</td> '.
      					'	<td align="center">' . $item->flag_paid($winning_bid_details['flag_paid'], $winning_bid_details['direct_payment_paid']) . '</td> '.
							'</tr> ';
					}	
					$template->set('winning_bids_content', $winning_bids_content);
				}
				
				if ($layout['enable_buyout'] && $setts['makeoffer_process'] == 1 && ($item_details['auction_type'] == 'standard' || empty($winning_bids_content)))## make offer is enabled (for dutch only if no winners)
				{
					$sql_select_make_offer = $db->query("SELECT ao.*, u.username FROM " . DB_PREFIX . "auction_offers ao,
						" . DB_PREFIX . "users u WHERE ao.auction_id='" . $item_details['auction_id'] . "' AND
						ao.seller_id='" . $session->value('user_id') . "' AND ao.buyer_id=u.user_id");

                    $counter = 0;
					while ($offer_details = $db->fetch_array($sql_select_make_offer))
					{
						$background = ($counter++%2) ? 'c1' : 'c2';

						$make_offer_content .= '<tr class="' . $background . '"> '.
      					'	<td>' . $offer_details['username'] . '</td> '.
      					'	<td align="center">' . $offer_details['quantity'] . '</td> '.
      					'	<td align="center">' . $offer_details['amount'] . '</td> '.
      					'	<td align="center">' . $item->offer_status($offer_details['accepted']) . '</td> '.
      					'	<td align="center">' . $item->offer_options($item_details['auction_id'], $offer_details['offer_id'], $offer_details['accepted'], $can_make_offer, 'auction_offers') . '</td> '.
							'</tr> ';
					}

					$template->set('make_offer_content', $make_offer_content);
				}

				if ($item_details['enable_swap'] && ($item_details['auction_type'] == 'standard' || empty($winning_bids_content))) ## swaps are enabled (for dutch only if no winners)
				{
					$sql_select_swaps = $db->query("SELECT s.*, u.username FROM " . DB_PREFIX . "swaps s,
						" . DB_PREFIX . "users u WHERE s.auction_id='" . $item_details['auction_id'] . "' AND
						s.seller_id='" . $session->value('user_id') . "' AND s.buyer_id=u.user_id");

					while ($offer_details = $db->fetch_array($sql_select_swaps))
					{
						$background = ($counter++%2) ? 'c1' : 'c2';

						$swap_offer_content .= '<tr class="' . $background . '"> '.
      					'	<td>' . $offer_details['username'] . '</td> '.
      					'	<td align="center">' . $offer_details['quantity'] . '</td> '.
      					'	<td>' . $offer_details['description'] . '</td> '.
      					'	<td align="center">' . $item->offer_status($offer_details['accepted']) . '</td> '.
      					'	<td align="center">' . $item->offer_options($item_details['auction_id'], $offer_details['swap_id'], $offer_details['accepted'], $can_make_offer, 'swaps') . '</td> '.
							'</tr> ';
					}

					$template->set('swap_offer_content', $swap_offer_content);
				}

				if ($item_details['closed'] == 1 && $item_details['nb_bids'] > 0 && $item_details['max_bid'] < $item_details['reserve_price'])## PHP Pro Bid v6.00 we have bids on the auction
				{
					$sql_select_bids = $db->query("SELECT b.*, u.username FROM " . DB_PREFIX . "bids b,
						" . DB_PREFIX . "users u WHERE b.auction_id='" . $item_details['auction_id'] . "' AND b.bidder_id=u.user_id");

					while ($offer_details = $db->fetch_array($sql_select_bids))
					{
                        $counter = 0;
						$background = ($counter++%2) ? 'c1' : 'c2';

						$reserve_offer_content .= '<tr class="' . $background . '"> '.
      					'	<td>' . $offer_details['username'] . user_pics($offer_details['bidder_id']) . '</td> '.
      					'	<td align="center">' . $offer_details['quantity'] . '</td> '.
      					'	<td align="center">' . $offer_details['bid_amount'] . '</td> '.
      					'	<td align="center">' . $item->offer_status($offer_details['accepted']) . '</td> '.
      					'	<td align="center">' . $item->offer_options($item_details['auction_id'], $offer_details['bid_id'], 0, $can_make_offer, 'bids') . '</td> '.
							'</tr> ';
					}

					$template->set('reserve_offer_content', $reserve_offer_content);
				}
				else if ($setts['enable_second_chance'] && $item_details['nb_bids'] > 0 && $item_details['closed'] == 1)
				{
					if ($item_details['closed'] == 1 && $item_details['nb_bids'] > 0 && $item->apply_second_chance($item_details, $session->value('user_id')))
					{
						$sql_select_bids = $db->query("SELECT b.*, u.username FROM " . DB_PREFIX . "bids b,
							" . DB_PREFIX . "users u WHERE b.auction_id='" . $item_details['auction_id'] . "' AND 
							b.bidder_id=u.user_id AND b.bid_invalid=0 AND b.bidder_id!='" . $item_details['buyer_id'] . "' ORDER BY b.bid_out ASC, b.bid_id DESC");

                        $counter = 0;
						while ($bid_details = $db->fetch_array($sql_select_bids))
						{
							$select_winner_link = '[ <a href="members_area.php?page=selling&section=view_offers&do=accept_offer&offer_id=' . $bid_details['bid_id'] . 
								'&offer_type=bids&auction_id=' . $item_details['auction_id'] . '" ' . 
								'onclick="return confirm(\'' . MSG_SECOND_CHANCE_PURCHASING_CONFIRM . '\');">' . MSG_SELECT_WINNER . '</a> ] ';
							
							$background = ($counter++%2) ? 'c1' : 'c2';
	
							$second_chance_content .= '<tr class="' . $background . '"> '.
	      					'	<td>' . $bid_details['username'] . user_pics($bid_details['bidder_id']) . '</td> '.
	      					'	<td align="center">' . $bid_details['quantity'] . '</td> '.
	      					'	<td align="center">' . $bid_details['bid_amount'] . '</td> '.
	      					'	<td align="center">' . $select_winner_link . '</td> '.
								'</tr> ';
						}
	
						$template->set('second_chance_content', $second_chance_content);
					}
				}

				$members_area_page_content = $template->process('members_area_selling_view_offers.tpl.php');
				$template->set('members_area_page_content', $members_area_page_content);
			}
			else
			{
				$section = 'selling';## PHP Pro Bid v6.00 we redirect
			}
		}

		if ($section == 'rollback')
		{
			$auction_id = intval($_REQUEST['auction_id']);
			$reverse_id = intval($_REQUEST['reverse_id']);
			
			$message_header = headercat('<b>' . (($reverse_id) ? MSG_MM_REVERSE_AUCTIONS : MSG_MM_SELLING) . ' - ' . MSG_ROLLBACK_TRANSACTION . '</b>');

			$template->set('message_header', $message_header);
			$template->set('message_content', '<p align="center">' . MSG_ROLLBACK_SUCCESS . '</p>');

			$item->rollback_transaction($auction_id, $session->value('user_id'), $reverse_id);

			$template_output .= $template->process('single_message.tpl.php');
		}

		/* begin -> stats box */
		
		$nb_open_items = $db->count_rows('auctions a', "WHERE a.owner_id='" . $session->value('user_id') . "' AND
			a.closed=0 AND a.deleted=0 AND a.creation_in_progress=0 AND a.is_draft=0" . $src_auctions_query);
		$nb_items_bids = $db->count_rows('auctions', "WHERE owner_id='" . $session->value('user_id') . "' AND
			closed=0 AND deleted=0 AND creation_in_progress=0 AND is_draft=0 AND (nb_bids>0 OR nb_offers>0)");
		$nb_scheduled_items = $db->count_rows('auctions', "WHERE closed=1 AND owner_id='" . $session->value('user_id') . "' AND
			deleted=0 AND creation_in_progress=0 AND is_draft=0 AND end_time>='" . CURRENT_TIME . "'");
		$nb_closed_items = $db->count_rows('auctions', "WHERE closed=1 AND owner_id='" . $session->value('user_id') . "' AND
			deleted=0 AND end_time<='" . CURRENT_TIME . "' AND creation_in_progress=0 AND is_draft=0");
		$nb_sold_items = $db->count_rows('winners w', "WHERE w.seller_id='" . $session->value('user_id') . "' AND
						w.s_deleted=0 AND w.sc_id=0" . $src_transactions_query);
		$nb_drafts = $db->count_rows('auctions', "WHERE owner_id='" . $session->value('user_id') . "' AND
			is_draft=1");
		if ($setts['enable_stores'])
		{
			$nb_sold_carts = $db->count_rows('shopping_carts', "WHERE seller_id='" . $session->value('user_id') . "' AND
				sc_purchased=1 AND s_deleted=0");
		}


        if (!isset($nb_sold_carts))
            $nb_sold_carts='';
		$template->set('nb_open_items', $nb_open_items);
		$template->set('nb_items_bids', $nb_items_bids);
		$template->set('nb_scheduled_items', $nb_scheduled_items);
		$template->set('nb_closed_items', $nb_closed_items);
		$template->set('nb_sold_items', $nb_sold_items);
		$template->set('nb_sold_carts', $nb_sold_carts);
		$template->set('nb_drafts', $nb_drafts);

		$members_area_stats = $template->process('members_area_stats_selling.tpl.php');
		
		if ($page == 'summary')
		{
			$summary_page_content['stats_selling'] = $members_area_stats;			
		}
		else 
		{
			$template->set('members_area_stats', $members_area_stats);
		}
		/* end -> stats box */

		if ($section == 'open' || $page == 'summary')
		{
			$header_selling_page = headercat('<b>' . MSG_MM_SELLING . ' - ' . MSG_MM_OPEN_AUCTIONS . '</b>');

			$nb_items = $nb_open_items;

			$template->set('header_selling_page', $header_selling_page);
			$template->set('nb_items', $nb_items);

			if ($page == 'summary')
			{
				$order_field = 'a.auction_id';
				$order_type = 'DESC';
				
				$start = 0;
				$limit = 5;
			}
			else 
			{
				$template->set('page_order_auction_id', page_order('members_area.php', 'a.auction_id', $start, $limit, $additional_vars, MSG_AUCTION_ID));
				$template->set('page_order_itemname', page_order('members_area.php', 'a.name', $start, $limit, $additional_vars, MSG_ITEM_TITLE));
				$template->set('page_order_start_time', page_order('members_area.php', 'a.start_time', $start, $limit, $additional_vars, GMSG_START_TIME));
				$template->set('page_order_end_time', page_order('members_area.php', 'a.end_time', $start, $limit, $additional_vars, GMSG_END_TIME));
				$template->set('page_order_nb_bids', page_order('members_area.php', 'a.nb_bids', $start, $limit, $additional_vars, MSG_NR_BIDS));
				$template->set('page_order_start_bid', page_order('members_area.php', 'a.start_price', $start, $limit, $additional_vars, MSG_START_BID));
				$template->set('page_order_max_bid', page_order('members_area.php', 'a.max_bid', $start, $limit, $additional_vars, MSG_MAX_BID));
			}
						
			if ($nb_items)
			{
				$force_index = $item->force_index($order_field, true);
				
				$sql_select_items = $db->query("SELECT a.*, ao.offer_id, s.swap_id, u.username,
					u.shop_account_id, u.shop_active, m.message_id FROM " . DB_PREFIX . "auctions a
					" . $force_index . "
					LEFT JOIN " . DB_PREFIX . "auction_offers ao ON ao.auction_id=a.auction_id 
					LEFT JOIN " . DB_PREFIX . "swaps s ON s.auction_id=a.auction_id 
					LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=a.owner_id 
					LEFT JOIN " . DB_PREFIX . "messaging m ON m.auction_id=a.auction_id AND m.is_read=0 AND m.sender_id!=a.owner_id 
					WHERE a.owner_id='" . $session->value('user_id') . "' AND a.closed=0 AND 
					a.deleted=0 AND a.creation_in_progress=0 AND a.is_draft=0 
					" . $src_auctions_query . "
					GROUP BY a.auction_id
					ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit);

                $counter = 0;
				while ($item_details = $db->fetch_array($sql_select_items))
				{
					$media_url = $db->get_sql_field("SELECT media_url FROM " . DB_PREFIX . "auction_media WHERE auction_id=" . $item_details['auction_id'] . " AND 
						media_type=1 AND upload_in_progress=0 ORDER BY media_id ASC", 'media_url');
					$auction_image = (!empty($media_url)) ? $media_url : 'themes/' . $setts['default_theme'] . '/img/system/noimg.gif';

					$background = ($counter++%2) ? 'c1' : 'c2';

					$content_options = '<a href="sell_item.php?option=sell_similar&auction_id=' . $item_details['auction_id'] . '">' . MSG_SELL_SIMILAR . '</a><br>';

					if ($item_details['payment_status']!='confirmed' && $item_details['active']==0)
					{
						$content_options .= '<a href="fee_payment.php?do=setup_fee_payment&auction_id=' . $item_details['auction_id'] . '">' . MSG_PAY_SETUP_FEE . '</a>';
					}
					else if ($item_details['nb_bids']==0 && $item_details['active']==1 && !$item_details['offer_id'] && !$item_details['swap_id'])
					{
						$content_options .= '<a href="' . process_link('edit_item', array('auction_id' => $item_details['auction_id'], 'edit_option' => 'new')) . '">' . MSG_EDIT_AUCTION . '</a><br> ';

						if (!$item->under_time($item_details))
						{
							$content_options .= '<a href="members_area.php?do=delete_auction&auction_id=' . $item_details['auction_id'] . $additional_vars . $limit_link . $order_link . '" onclick="return confirm(\'' . MSG_DELETE_CONFIRM . '\');">' . MSG_DELETE . '</a>';
						}
					}
					else if (($item_details['nb_bids']>0 || $item_details['offer_id'] || $item_details['swap_id']) && $item_details['active']==1)
					{
						$content_options .= '<a href="edit_description.php?auction_id=' . $item_details['auction_id'] . '">' . MSG_EDIT_DESCRIPTION . '</a><br> ';
					}

					if ($item->can_close_manually($item_details, $session->value('user_id')))
					{
						$content_options .= '<br><a href="members_area.php?do=close_auction&auction_id=' . $item_details['auction_id'] . $additional_vars . $limit_link . $order_link . '" onclick="return confirm(\'' . MSG_CLOSE_AUCTION_CONFIRM . '\');">' . MSG_CLOSE_AUCTION . '</a>';					
					}
					
					if ($item_details['approved']==0 && $item_details['payment_status'] == 'confirmed')
					{
						$content_options .= '<br><br>' . MSG_AUCTION_AWAITING_APPROVAL;
					}

					$auction_link = process_link('auction_details', array('auction_id' => $item_details['auction_id']));
					$open_auctions_content .= '<tr class="' . $background . '"> '.
						'	<td align="center"><a href="' . $auction_link . '"><img src="thumbnail.php?pic=' . $auction_image . '&w=50&sq=Y&b=Y" border="0" alt="' . $item_details['name'] . '"></a></td> '.
						'	<td class="contentfont"><a href="' . $auction_link . '"># ' . $item_details['auction_id'] . '</a></td> '.
						'	<td class="contentfont"><a href="' . $auction_link . '">' . $item_details['name'] . '</a> ' . 
						$item->relisted_tick($item_details['is_relisted_item']) . 
						$item->new_message_tick($item_details['message_id']) . $item->listed_in($item_details) .
						(($item_details['offer_id'] || $item_details['swap_id']) ? '<br>[ <a href="members_area.php?page=selling&section=view_offers&auction_id=' . $item_details['auction_id'] . '">' . MSG_VIEW_AUCTION_OFFERS . '</a> ]' : '') .
						'	</td>'.
						'	<td align="center">' . show_date($item_details['start_time'], false) . '</td> '.
						'	<td align="center">' . show_date($item_details['end_time'], false) . '</td>'.
						'	<td align="center">' . field_display($item_details['nb_bids'], '-', $item_details['nb_bids']) . '</td>'.
						'	<td align="center">' . field_display($item_details['auto_relist_nb'], GMSG_NO, GMSG_YES . ' (' . $item_details['auto_relist_nb'] . ')'). '</td>'.
						'	<td align="center">' . $fees->display_amount($item_details['start_price'], $item_details['currency']) . '</td>'.
						'	<td align="center">' . $fees->display_amount($item_details['max_bid'], $item_details['currency']) . '</td>'.
						'	<td align="center" class="smallfont">' . $content_options . '</td>'.
						'</tr>';
				}
			}
			else
			{
				$open_auctions_content = '<tr><td colspan="8" align="center">' . GMSG_NO_ITEMS_MSG . '</td></tr>';
			}

			$template->set('open_auctions_content', $open_auctions_content);

			if ($page != 'summary')
			{
				$pagination = paginate($start, $limit, $nb_items, 'members_area.php', $additional_vars . $order_link);
				$template->set('pagination', $pagination);
			}

			$members_area_page_content = $template->process('members_area_selling_open.tpl.php');

			if ($page == 'summary')
			{
				$summary_page_content['selling_open'] = $members_area_page_content;
			}

			$template->set('members_area_page_content', $members_area_page_content);
		}

		if ($section == 'bids_offers')
		{
			$header_selling_page = headercat('<b>' . MSG_MM_SELLING . ' - ' . MSG_MM_OPEN_AUCTIONS . '</b>');

			$nb_items = $nb_items_bids;

			$template->set('header_selling_page', $header_selling_page);
			$template->set('nb_items', $nb_items);

			$template->set('page_order_auction_id', page_order('members_area.php', 'a.auction_id', $start, $limit, $additional_vars, MSG_AUCTION_ID));
			$template->set('page_order_itemname', page_order('members_area.php', 'a.name', $start, $limit, $additional_vars, MSG_ITEM_TITLE));
			$template->set('page_order_start_time', page_order('members_area.php', 'a.start_time', $start, $limit, $additional_vars, GMSG_START_TIME));
			$template->set('page_order_end_time', page_order('members_area.php', 'a.end_time', $start, $limit, $additional_vars, GMSG_END_TIME));
			$template->set('page_order_nb_bids', page_order('members_area.php', 'a.nb_bids', $start, $limit, $additional_vars, MSG_NR_BIDS));
			$template->set('page_order_max_bid', page_order('members_area.php', 'a.max_bid', $start, $limit, $additional_vars, MSG_MAX_BID));

			if ($nb_items)
			{
				$force_index = $item->force_index($order_field, true);
				
				$sql_select_items = $db->query("SELECT a.*, ao.offer_id, s.swap_id, u.username,
					u.shop_account_id, u.shop_active FROM " . DB_PREFIX . "auctions a
					" . $force_index . "
					LEFT JOIN " . DB_PREFIX . "auction_offers ao ON ao.auction_id=a.auction_id 
					LEFT JOIN " . DB_PREFIX . "swaps s ON s.auction_id=a.auction_id 
					LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=a.owner_id 
					WHERE a.owner_id='" . $session->value('user_id') . "' AND a.closed=0 AND 
					a.deleted=0 AND a.creation_in_progress=0 AND a.is_draft=0 AND end_time>0 AND 
					(nb_bids>0 OR nb_offers>0) 
					GROUP BY a.auction_id
					ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit);

                $counter = 0;
				while ($item_details = $db->fetch_array($sql_select_items))
				{
					$media_url = $db->get_sql_field("SELECT media_url FROM " . DB_PREFIX . "auction_media WHERE auction_id=" . $item_details['auction_id'] . " AND 
						media_type=1 AND upload_in_progress=0 ORDER BY media_id ASC", 'media_url');
					$auction_image = (!empty($media_url)) ? $media_url : 'themes/' . $setts['default_theme'] . '/img/system/noimg.gif';

					$background = ($counter++%2) ? 'c1' : 'c2';

					$content_options = '<a href="sell_item.php?option=sell_similar&auction_id=' . $item_details['auction_id'] . '">' . MSG_SELL_SIMILAR . '</a><br>';

					if ($item_details['payment_status']!='confirmed' && $item_details['active']==0)
					{
						$content_options .= '<a href="fee_payment.php?do=setup_fee_payment&auction_id=' . $item_details['auction_id'] . '">' . MSG_PAY_SETUP_FEE . '</a>';
					}
					else if ($item_details['nb_bids']==0 && $item_details['active']==1)
					{
						$content_options .= '<a href="edit_item.php?auction_id=' . $item_details['auction_id'] . '&edit_option=new">' . MSG_EDIT_AUCTION . '</a><br> ';

						if (!$item->under_time($item_details))
						{
							$content_options .= '<a href="members_area.php?do=delete_auction&auction_id=' . $item_details['auction_id'] . $additional_vars . $limit_link . $order_link . '" onclick="return confirm(\'' . MSG_DELETE_CONFIRM . '\');">' . MSG_DELETE . '</a>';
						}
					}
					else if ($item_details['nb_bids']>0 && $item_details['active']==1)
					{
						$content_options .= '<a href="edit_description.php?auction_id=' . $item_details['auction_id'] . '">' . MSG_EDIT_DESCRIPTION . '</a><br> ';
					}

					if ($item_details['approved']==0 && $item_details['payment_status'] == 'confirmed')
					{
						$content_options .= '<br><br>' . MSG_AUCTION_AWAITING_APPROVAL;
					}

					$auction_link = process_link('auction_details', array('auction_id' => $item_details['auction_id']));
					$open_auctions_content .= '<tr class="' . $background . '"> '.
						'	<td align="center"><a href="' . $auction_link . '"><img src="thumbnail.php?pic=' . $auction_image . '&w=50&sq=Y&b=Y" border="0" alt="' . $item_details['name'] . '"></a></td> '.
						'	<td class="contentfont"><a href="' . process_link('auction_details', array('auction_id' => $item_details['auction_id'])) . '"># ' . $item_details['auction_id'] . '</a></td> '.
						'	<td class="contentfont"><a href="' . process_link('auction_details', array('auction_id' => $item_details['auction_id'])) . '">' . $item_details['name'] . '</a> ' . $item->relisted_tick($item_details['is_relisted_item']) . $item->listed_in($item_details) .
						(($item_details['offer_id'] || $item_details['swap_id']) ? '<br>[ <a href="members_area.php?page=selling&section=view_offers&auction_id=' . $item_details['auction_id'] . '">' . MSG_VIEW_AUCTION_OFFERS . '</a> ]' : '') .
						'	</td>'.
						'	<td align="center">' . show_date($item_details['start_time'], false) . '</td> '.
						'	<td align="center">' . show_date($item_details['end_time'], false) . '</td>'.
						'	<td align="center">' . field_display($item_details['nb_bids'], '-', $item_details['nb_bids']) . '</td>'.
						'	<td align="center">' . field_display($item_details['auto_relist_nb'], GMSG_NO, GMSG_YES . ' (' . $item_details['auto_relist_nb'] . ')'). '</td>'.
						'	<td align="center">' . $fees->display_amount($item_details['start_price'], $item_details['currency']) . '</td>'.
						'	<td align="center">' . $fees->display_amount($item_details['max_bid'], $item_details['currency']) . '</td>'.
						'	<td align="center" class="smallfont">' . $content_options . '</td>'.
						'</tr>';
				}
			}
			else
			{
				$open_auctions_content = '<tr><td colspan="8" align="center">' . GMSG_NO_ITEMS_MSG . '</td></tr>';
			}

			$template->set('open_auctions_content', $open_auctions_content);

			$pagination = paginate($start, $limit, $nb_items, 'members_area.php', $additional_vars . $order_link);
			$template->set('pagination', $pagination);

			$members_area_page_content = $template->process('members_area_selling_open.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);
		}
		
		if ($section == 'scheduled')
		{
			$header_selling_page = headercat('<b>' . MSG_MM_SELLING . ' - ' . MSG_MM_SCHEDULED_AUCTIONS . '</b>');

			$nb_items = $nb_scheduled_items;

			$template->set('header_selling_page', $header_selling_page);
			$template->set('nb_items', $nb_items);

			$template->set('page_order_auction_id', page_order('members_area.php', 'a.auction_id', $start, $limit, $additional_vars, MSG_AUCTION_ID));
			$template->set('page_order_itemname', page_order('members_area.php', 'a.name', $start, $limit, $additional_vars, MSG_ITEM_TITLE));
			$template->set('page_order_start_time', page_order('members_area.php', 'a.start_time', $start, $limit, $additional_vars, GMSG_START_TIME));
			$template->set('page_order_end_time', page_order('members_area.php', 'a.end_time', $start, $limit, $additional_vars, GMSG_END_TIME));
			$template->set('page_order_nb_bids', page_order('members_area.php', 'a.nb_bids', $start, $limit, $additional_vars, MSG_NR_BIDS));
			$template->set('page_order_max_bid', page_order('members_area.php', 'a.max_bid', $start, $limit, $additional_vars, MSG_MAX_BID));

			if ($nb_items)
			{
				$force_index = $item->force_index($order_field, true);

				$sql_select_items = $db->query("SELECT a.*, u.username,
					u.shop_account_id, u.shop_active FROM " . DB_PREFIX . "auctions a
					" . $force_index . "
					LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=a.owner_id
					WHERE a.owner_id='" . $session->value('user_id') . "' AND a.closed=1 AND a.deleted=0 AND
					a.end_time>'" . CURRENT_TIME . "' AND a.creation_in_progress=0 AND a.is_draft=0
					GROUP BY a.auction_id
					ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit); 

                $counter = 0;
				while ($item_details = $db->fetch_array($sql_select_items))
				{
					$background = ($counter++%2) ? 'c1' : 'c2';

					$content_options = '<a href="sell_item.php?option=sell_similar&auction_id=' . $item_details['auction_id'] . '">' . MSG_SELL_SIMILAR . '</a><br>';

					if ($item_details['payment_status']!='confirmed' && $item_details['active']==0)
					{
						$content_options .= '<a href="fee_payment.php?do=setup_fee_payment&auction_id=' . $item_details['auction_id'] . '">' . MSG_PAY_SETUP_FEE . '</a>';
					}
					else if ($item_details['nb_bids']==0 && $item_details['active']==1)
					{
						$content_options .= '<a href="edit_item.php?auction_id=' . $item_details['auction_id'] . '&edit_option=new">' . MSG_EDIT_AUCTION . '</a><br> ';

						if (!$item->under_time($item_details))
						{
							$content_options .= '<a href="members_area.php?do=delete_auction&auction_id=' . $item_details['auction_id'] . $additional_vars . $limit_link . $order_link . '" onclick="return confirm(\'' . MSG_DELETE_CONFIRM . '\');">' . MSG_DELETE . '</a>';
						}
					}
					else if ($item_details['nb_bids']>0 && $item_details['active']==1)
					{
						$content_options .= '<a href="edit_description.php?auction_id=' . $item_details['auction_id'] . '">' . MSG_EDIT_DESCRIPTION . '</a><br> ';
					}

					if ($item_details['approved']==0 && $item_details['payment_status'] == 'confirmed')
					{
						$content_options .= '<br><br>' . MSG_AUCTION_AWAITING_APPROVAL;
					}

					$scheduled_auctions_content .= '<tr class="' . $background . '"> '.
						'	<td class="contentfont"><a href="' . process_link('auction_details', array('auction_id' => $item_details['auction_id'])) . '"># ' . $item_details['auction_id'] . '</a></td> '.
						'	<td class="contentfont"><a href="' . process_link('auction_details', array('auction_id' => $item_details['auction_id'])) . '">' . $item_details['name'] . '</a> ' . $item->listed_in($item_details) . '</td>'.
						'	<td align="center">' . show_date($item_details['start_time']) . '</td> '.
						'	<td align="center">' . show_date($item_details['end_time']) . '</td>'.
						'	<td align="center">' . field_display($item_details['auto_relist_nb'], GMSG_NO, GMSG_YES . ' (' . $item_details['auto_relist_nb'] . ')'). '</td>'.
						'	<td align="center" class="smallfont">' . $content_options . '</td>'.
						'</tr>';
				}
			}
			else
			{
				$scheduled_auctions_content = '<tr><td colspan="8" align="center">' . GMSG_NO_ITEMS_MSG . '</td></tr>';
			}

			$template->set('scheduled_auctions_content', $scheduled_auctions_content);

			$pagination = paginate($start, $limit, $nb_items, 'members_area.php', $additional_vars . $order_link);
			$template->set('pagination', $pagination);

			$members_area_page_content = $template->process('members_area_selling_scheduled.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);

		}

		if ($section == 'closed')
		{
			$header_selling_page = headercat('<b>' . MSG_MM_SELLING . ' - ' . MSG_MM_CLOSED_AUCTIONS . '</b>');

			$nb_items = $nb_closed_items;

			$template->set('header_selling_page', $header_selling_page);
			$template->set('nb_items', $nb_items);

			$template->set('page_order_auction_id', page_order('members_area.php', 'a.auction_id', $start, $limit, $additional_vars, MSG_AUCTION_ID));
			$template->set('page_order_itemname', page_order('members_area.php', 'a.name', $start, $limit, $additional_vars, MSG_ITEM_TITLE));
			$template->set('page_order_start_time', page_order('members_area.php', 'a.start_time', $start, $limit, $additional_vars, GMSG_START_TIME));
			$template->set('page_order_end_time', page_order('members_area.php', 'a.end_time', $start, $limit, $additional_vars, GMSG_END_TIME));
			$template->set('page_order_nb_bids', page_order('members_area.php', 'a.nb_bids', $start, $limit, $additional_vars, MSG_NR_BIDS));
			$template->set('page_order_max_bid', page_order('members_area.php', 'a.max_bid', $start, $limit, $additional_vars, MSG_MAX_BID));

			
			if ($nb_items)
			{
				$force_index = $item->force_index($order_field, true);
				
				$sql_select_items = $db->query("SELECT a.*, ao.offer_id, s.swap_id, w.winner_id, b.bid_id, u.shop_active FROM " . DB_PREFIX . "auctions a					
					" . $force_index . "	
					LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=a.owner_id
					LEFT JOIN " . DB_PREFIX . "auction_offers ao ON ao.auction_id=a.auction_id
					LEFT JOIN " . DB_PREFIX . "swaps s ON s.auction_id=a.auction_id
					LEFT JOIN " . DB_PREFIX . "winners w ON w.auction_id=a.auction_id
					LEFT JOIN " . DB_PREFIX . "bids b ON b.auction_id=a.auction_id
					WHERE a.owner_id='" . $session->value('user_id') . "' AND a.closed=1 AND a.deleted=0
					AND a.end_time<='" . CURRENT_TIME . "'AND a.creation_in_progress=0 AND a.is_draft=0 
					GROUP BY a.auction_id
					ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit);

                $counter= 0;
				while ($item_details = $db->fetch_array($sql_select_items))
				{
					$background = ($counter++%2) ? 'c1' : 'c2';

                    if (!isset($closed_auctions_content))
                        $closed_auctions_content = '';
					$closed_auctions_content .= '<tr class="' . $background . '"> '.
						'	<td class="contentfont"><a href="' . process_link('auction_details', array('auction_id' => $item_details['auction_id'])) . '"># ' . $item_details['auction_id'] . '</a></td> '.
						'	<td class="contentfont"><a href="' . process_link('auction_details', array('auction_id' => $item_details['auction_id'])) . '">' . $item_details['name'] . '</a> ' . 
						(($item_details['winner_id']) ? '<img src="themes/' . $setts['default_theme'] . '/img/system/relisted_sold.gif" border="0" alt="' . MSG_ITEM_WAS_SOLD . '">' : '') . ' ' . $item->listed_in($item_details) .
						((!$item_details['winner_id'] && (($item_details['bid_id'] && $item_details['max_bid'] < $item_details['reserve_price']) || $item_details['offer_id'] || $item_details['swap_id'])) ? '<br>[ <a href="members_area.php?page=selling&section=view_offers&auction_id=' . $item_details['auction_id'] . '">' . MSG_SELECT_WINNER_MANUALLY . '</a> ]' : '') .
						'	</td>'.
						'	<td align="center">' . show_date($item_details['start_time'], false) . '</td> '.
						'	<td align="center">' . show_date($item_details['end_time'], false) . '</td>'.
						'	<td align="center">' . field_display($item_details['nb_bids'], '-', $item_details['nb_bids']) . '</td>'.
						'	<td align="center">' . $fees->display_amount($item_details['max_bid'], $item_details['currency']) . '</td>'.
						'	<td align="center"><input name="relist[]" type="checkbox" id="relist[]" value="' . $item_details['auction_id'] . '" class="checkrelist"> '.
						'		' . $item->durations_drop_down('duration[' . $item_details['auction_id'] . ']', $item_details['duration']) . '</td>'.
						'	<td align="center" class="smallfont"><input name="delete[]" type="checkbox" id="delete[]" value="' . $item_details['auction_id'] . '" class="checkdelete"></td>'.
						'</tr>';
				}

			}
			else
			{
				$closed_auctions_content = '<tr><td colspan="8" align="center">' . GMSG_NO_ITEMS_MSG . '</td></tr>';
			}

			$template->set('closed_auctions_content', $closed_auctions_content);

			$pagination = paginate($start, $limit, $nb_items, 'members_area.php', $additional_vars . $order_link);
			$template->set('pagination', $pagination);

			$members_area_page_content = $template->process('members_area_selling_closed.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);

		}

		if ($section == 'drafts')
		{
            $header_selling_page = headercat('<b>' . MSG_MM_SELLING . ' - ' . MSG_MM_DRAFTS . '</b>');

			$nb_items = $nb_drafts;

			$template->set('header_selling_page', $header_selling_page);
			$template->set('nb_items', $nb_items);

			$template->set('page_order_auction_id', page_order('members_area.php', 'a.auction_id', $start, $limit, $additional_vars, MSG_AUCTION_ID));
			$template->set('page_order_itemname', page_order('members_area.php', 'a.name', $start, $limit, $additional_vars, MSG_ITEM_TITLE));
			$template->set('page_order_start_bid', page_order('members_area.php', 'a.start_price', $start, $limit, $additional_vars, MSG_START_BID));

			if ($nb_items)
			{
				$sql_select_items = $db->query("SELECT a.*, u.username,
					u.shop_account_id, u.shzop_active FROM " . DB_PREFIX . "auctions a
					LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=a.owner_id
					WHERE a.owner_id='" . $session->value('user_id') . "' AND a.is_draft=1 AND a.deleted=0 
					GROUP BY a.auction_id
					ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit);## PHP Pro Bid v6.00 uses temporary/filesort

                $counter = 0;
				while ($item_details = $db->fetch_array($sql_select_items))
				{
					$background = ($counter++%2) ? 'c1' : 'c2';

					$content_options = '<a href="sell_item.php?option=sell_similar&auction_id=' . $item_details['auction_id'] . '">' . GMSG_LIST_NOW . '</a><br>';
					$content_options .= '<a href="edit_item.php?auction_id=' . $item_details['auction_id'] . '&edit_option=new&draft=1">' . MSG_EDIT_DRAFT . '</a><br> ';
					$content_options .= '<a href="members_area.php?do=delete_auction&auction_id=' . $item_details['auction_id'] . $additional_vars . $limit_link . $order_link . '" onclick="return confirm(\'' . MSG_DELETE_CONFIRM . '\');">' . MSG_DELETE . '</a>';

					$drafts_content .= '<tr class="' . $background . '"> '.
						'	<td class="contentfont"><a href="' . process_link('auction_details', array('auction_id' => $item_details['auction_id'])) . '"># ' . $item_details['auction_id'] . '</a></td> '.
						'	<td class="contentfont"><a href="' . process_link('auction_details', array('auction_id' => $item_details['auction_id'])) . '">' . $item_details['name'] . '</a></td>'.
						'	<td align="center">' . $fees->display_amount($item_details['start_price'], $item_details['currency']) . '</td>'.
						'	<td align="center" class="smallfont">' . $content_options . '</td>'.
						'</tr>';
				}
			}
			else
			{
				$drafts_content = '<tr><td colspan="8" align="center">' . GMSG_NO_ITEMS_MSG . '</td></tr>';
			}

			$template->set('drafts_content', $drafts_content);

			$pagination = paginate($start, $limit, $nb_items, 'members_area.php', $additional_vars . $order_link);
			$template->set('pagination', $pagination);

			$members_area_page_content = $template->process('members_area_selling_drafts.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);
		}

		if ($section == 'sold')
		{
			$show_link = (isset($_REQUEST['show']))? '&show=' . $_REQUEST['show']:'&show=';
			
			(string) $search_filter = null;
		
			if (isset($_REQUEST['show'])) {
                if ($_REQUEST['show'] == 'dd')
                {
                    $search_filter .= " AND w.is_dd=1";
                    $nb_sold_items = $db->count_rows('winners w', "WHERE w.seller_id='" . $session->value('user_id') . "' AND
                        w.s_deleted=0" . $search_filter . $src_transactions_query);
                }
                else if ($_REQUEST['show'] == 'no_dd')
                {
                    $search_filter .= " AND w.is_dd=0";
                    $nb_sold_items = $db->count_rows('winners w', "WHERE w.seller_id='" . $session->value('user_id') . "' AND
                        w.s_deleted=0" . $search_filter . $src_transactions_query);
                }
            }
			
			(string) $filter_items_content = null;

			$filter_items_content .= display_link('members_area.php?page=selling&section=sold', GMSG_ALL, ((!isset($_REQUEST['show'])) ? false : true)) . ' | ';
			$filter_items_content .= display_link('members_area.php?page=selling&section=sold&show=dd', MSG_DIGITAL_MEDIA_ATTACHED, ((isset($_REQUEST['show']) && $_REQUEST['show'] == 'dd') ? false : true)) . ' | ';
			$filter_items_content .= display_link('members_area.php?page=selling&section=sold&show=no_dd', MSG_NO_DIGITAL_MEDIA, ((isset($_REQUEST['show']) && $_REQUEST['show'] == 'no_dd') ? false : true));

			$template->set('filter_items_content', $filter_items_content);
			
			if (isset($_REQUEST['form_update_winner_status']))
			{
				$dd_active = (intval($_REQUEST['flag_paid']) == 1) ? 1 : 0;
				$current_time = ($dd_active) ? CURRENT_TIME : 0;
				$update_force_payment = (intval($_REQUEST['flag_paid']) == 1) ? ", temp_purchase=0" : '';
				
				$db->query("UPDATE " . DB_PREFIX . "winners SET flag_paid='" . $_REQUEST['flag_paid'] . "', 					
					flag_status='" . $_REQUEST['flag_status'] . "',dd_active=IF(is_dd=1, " . $dd_active . ", 0), 
					dd_active_date=IF(is_dd=1, " . $current_time . ", 0)
					" . $update_force_payment . " WHERE winner_id='" . intval($_REQUEST['winner_id']) . "' AND
					seller_id='" . $session->value('user_id') . "'");
			}

			$header_selling_page = headercat('<b>' . MSG_MM_SELLING . ' - ' . MSG_MM_SOLD_ITEMS . '</b>');

			$nb_items = $nb_sold_items;

			$template->set('header_selling_page', $header_selling_page);
			$template->set('nb_items', $nb_items);

			$template->set('page_order_auction_id', page_order('members_area.php', 'w.auction_id', $start, $limit, $additional_vars . $show_link, MSG_AUCTION_ID));
			$template->set('page_order_itemname', page_order('members_area.php', 'a.name', $start, $limit, $additional_vars . $show_link, MSG_ITEM_TITLE));
			$template->set('page_order_bid_amount', page_order('members_area.php', 'w.bid_amount', $start, $limit, $additional_vars . $show_link, MSG_WINNING_BID));
			$template->set('page_order_quantity', page_order('members_area.php', 'w.quantity_offered', $start, $limit, $additional_vars . $show_link, MSG_QUANTITY_OFFERED));
			$template->set('page_order_purchase_date', page_order('members_area.php', 'w.purchase_date', $start, $limit, $additional_vars . $show_link, MSG_PURCHASE_DATE));

			if ($nb_items)
			{
				$sql_select_sold = $db->query("SELECT w.*, a.name AS auction_name, a.currency, a.category_id, a.auction_type, 
					a.bank_details, u.username, u.name, r.submitted, r.reputation_id, m.message_id, 
					i.refund_request, i.user_id AS payer_id 
					FROM " . DB_PREFIX . "winners w
					LEFT JOIN " . DB_PREFIX . "auctions a ON a.auction_id=w.auction_id
					LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=w.buyer_id
					LEFT JOIN " . DB_PREFIX . "reputation r ON r.from_id=w.seller_id AND r.winner_id=w.winner_id
					LEFT JOIN " . DB_PREFIX . "messaging m ON m.auction_id=w.auction_id AND m.is_read=0 AND m.sender_id!=w.seller_id 					
					LEFT JOIN " . DB_PREFIX . "invoices i ON i.invoice_id=w.refund_invoice_id 
					WHERE w.seller_id='" . $session->value('user_id') . "' AND w.s_deleted=0 
					" . $search_filter . $src_transactions_query . " 
					GROUP BY w.winner_id 
					ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit);
					
				$sale_fee = new fees();
				$sale_fee->setts = &$setts;

                $counter = 0;
				while ($item_details = $db->fetch_array($sql_select_sold))
				{
					$background = ($counter++%2) ? 'c1' : 'c2';

					$sale_fee->set_fees($item_details['seller_id'], $item_details['category_id']);

					$item_paid = ($item_details['active'] == 1 && $item_details['payment_status'] == 'confirmed') ? 1 : 0;
					if ($item_paid)
					{
						$content_options = '&#8226; <a href="' . process_link('message_board', array('message_handle' => '3', 'winner_id' => $item_details['winner_id'])) . '"><b class="greenfont">' . MSG_MESSAGE_BOARD . '</b></a><br>';

						if (!$item_details['submitted'])
						{
							$content_options .= '&#8226; <a href="members_area.php?page=reputation&section=post&reputation_ids=' . $item_details['reputation_id'] . '">' . MSG_LEAVE_COMMENTS . '</a><br>';
						}

						$content_options .= '&#8226; <a href="javascript:void(0)" onClick="openPopup(\'popup_bank_details.php?auction_id=' . $item_details['auction_id'] . '\')">' . ((empty($item_details['bank_details'])) ? MSG_SEND_BANK_DETAILS : MSG_VIEW_BANK_DETAILS) . '</a><br>';
						
						if (!$item_details['invoice_sent'])
						{
							$content_options .= '&#8226; <a href="members_area.php?do=delete_winner&option=seller&winner_id=' . $item_details['winner_id'] . $additional_vars . $order_link . $limit_link . $show_link . '" onclick="return confirm(\'' . MSG_DELETE_CONFIRM . '\');"><b class="redfont">' . MSG_DELETE . '</b></a>';
						}
					}
					else
					{
						if (eregi('s', $sale_fee->fee['endauction_fee_applies']))
						{
							$content_options = '&#8226; <a href="fee_payment.php?do=sale_fee_payment&winner_id=' . $item_details['winner_id'] . '">' . MSG_PAY_ENDAUCTION_FEE . '</a>';
						}
						else
						{
							$content_options = '&#8226; ' . MSG_ENDAUCTION_FEE_NOT_PAID;
						}
					}

					$sold_auctions_content .= '<tr class="' . $background . '"> '.
						'	<td class="contentfont"><a href="' . process_link('auction_details', array('auction_id' => $item_details['auction_id'])) . '"># ' . $item_details['auction_id'] . '</a> - '.
						'		<a href="' . process_link('auction_details', array('auction_id' => $item_details['auction_id'])) . '">' . field_display($item_details['auction_name'], MSG_AUCTION_DELETED) . '</a> '. 
						$item->new_message_tick($item_details['message_id']) . '</td>'.
						'	<td align="center">' . $fees->display_amount($item_details['bid_amount'], $item_details['currency']) . '</td>'.
						'	<td align="center">' . MSG_REQUESTED . ': ' . $item_details['quantity_requested'] . '<br> '.
						'		' . MSG_OFFERED . ': ' . $item_details['quantity_offered'] . '</td> '.
						'	<td align="center"> ';

					if ($item_paid)
					{
						$sold_auctions_content .= '<table border="0" cellpadding="0" cellspacing="0" class="soldAuctionsContent border"> '.
	            		'	<tr valign="top" bgcolor="#FFFFF"> '.
	               	'		<td class="smallfont" nowrap><b>' . MSG_USERNAME . '</b></td> '.
	               	'		<td class="smallfont" width="100%">' . field_display($item_details['username'], GMSG_NA) . '</td> '.
	            		'	</tr> '.
	            		'	<tr valign="top" bgcolor="#FFFFF"> '.
	               	'		<td class="smallfont" nowrap><b>' . MSG_FULL_NAME . '</b></td> '.
	               	'		<td class="smallfont">' . field_display($item_details['name'], GMSG_NA) . '</td> '.
	            		'	</tr> '.
	         			'</table> ';
					}

         		$sold_auctions_content .= '	</td>'.
						'	<td align="center">';
					if ($item_paid)
					{
						$sold_auctions_content .= show_date($item_details['purchase_date']) . '<br> '.
							'<table border="0" cellpadding="0" cellspacing="0" class="soldAuctionContent border"> '.
            			'	<form action="members_area.php?start=' . $start . $additional_vars . '" method="post"> '.
               		'	<input type="hidden" name="winner_id" value="' . $item_details['winner_id'] . '"> '.
               		'		<tr bgcolor="#FFFFF"> '.
                  	'			<td align="center"><select name="flag_paid" style="font-size:10px; width: 100px;"> '.
                     '				<option value="0" ' . (($item_details['flag_paid'] == 0) ? 'selected' : '') . '>' . MSG_UNPAID . '</option> '.
							'				<option value="1" ' . (($item_details['flag_paid'] == 1) ? 'selected' : '') . '>' . MSG_PAID . '</option> '.
							'			</select></td> '.
               		'		</tr> '.
               		'		<tr bgcolor="#FFFFF"> '.
                  	'			<td align="center"><select name="flag_status" style="font-size:10px; width: 100px;"> '.
                     '				<option value="0" ' . (($item_details['flag_status'] == 0) ? 'selected' : '') . '>' . MSG_FLAG_STATUS_A . '</option> '.
							'				<option value="1" ' . (($item_details['flag_status'] == 1) ? 'selected' : '') . '>' . MSG_FLAG_STATUS_B . '</option> '.
							'				<option value="2" ' . (($item_details['flag_status'] == 2) ? 'selected' : '') . '>' . MSG_FLAG_STATUS_C . '</option> '.
							'				<option value="3" ' . (($item_details['flag_status'] == 3) ? 'selected' : '') . '>' . MSG_FLAG_STATUS_D . '</option> '.
							'			</select></td> '.
               		'		</tr> '.
               		'		<tr bgcolor="#FFFFF"> '.
                  	'			<td align="center"><input type="submit" name="form_update_winner_status" value="' . GMSG_GO . '" style="font-size:10px; width: 100px;"></td> '.
               		'		</tr> '.
            			'	</form> '.
         				'</table>';
					}
					$sold_auctions_content .= '	</td>'.
						'	<td class="smallfont">' . $content_options . '</td>'.
						'</tr>';
						
					if ($item_paid && !empty($item_details['auction_name']))
					{
						if ($item_details['is_dd'])
						{
							$link_active = MSG_LINK_ACTIVE . ' &middot; [ <a href="members_area.php?do=dd_active&value=0&winner_id=' . $item_details['winner_id'] . $additional_vars . $order_link . $limit_link . $show_link . '">' . MSG_INACTIVATE . '</a> ]';
							$link_inactive = MSG_LINK_INACTIVE . ' &middot; [ <a href="members_area.php?do=dd_active&value=1&winner_id=' . $item_details['winner_id'] . $additional_vars . $order_link . $limit_link . $show_link . '">' . MSG_ACTIVATE . '</a> ]';
							
							$dd_expires = dd_expires($item_details['dd_active_date']);
							
							$sold_auctions_content .= '<tr class="c7"> '.
								'	<td><b>' . MSG_DIGITAL_MEDIA_ATTACHED . '</b></td> '.
								'	<td align="center" colspan="2">' . MSG_DOWNLOADED . ' ' . $item_details['dd_nb_downloads'] . ' ' . MSG_TIMES . '</td>'.
								'	<td align="center" class="contentfont">' . (($item_details['dd_active'] && $dd_expires['result']>0) ? $link_active : $link_inactive) . '</td>'.
								'	<td align="center" colspan="2">' . MSG_LINK_EXPIRES . ': ' . (($item_details['dd_active']) ? $dd_expires['display'] : GMSG_NA) . '</td>'.
								'</tr>';
						}							

						$sold_auctions_content .= '<tr><td colspan="6" class="contentfont">';
						if ($item_details['invoice_sent'])
						{
							$sold_auctions_content .= '&#8226; <b>' . MSG_INVOICE_SENT . '</b> [ ' . MSG_ID . ': ' . $item_details['invoice_id'] . ' ] <a href="' . process_link('invoice_print', array('invoice_type' => 'product_invoice', 'invoice_id' => $item_details['invoice_id'])) . '" target="_blank">' . MSG_VIEW_PRODUCT_INVOICE . '</a> &middot; '.
						'		<a href="members_area.php?page=selling&section=product_invoice&option=edit_invoice&invoice_id=' . $item_details['invoice_id'] . '">' . MSG_EDIT_PRODUCT_INVOICE . '</a> &middot; ' . 
								'<a href="members_area.php?do=resend_invoice&invoice_id=' . $item_details['invoice_id'] . $additional_vars . $order_link . $limit_link  . $show_link. '">' . MSG_RESEND_PRODUCT_INVOICE . '</a>';
						}
						else
						{
							$sold_auctions_content .= '&#8226; <a href="members_area.php?page=selling&section=product_invoice&buyer_id=' . $item_details['buyer_id'] . '&auction_id=' . $item_details['auction_id'] . '">'  . MSG_SEND_PRODUCT_INVOICE . '</a>';
						}
						
						if ($item->apply_second_chance($item_details, $session->value('user_id')))
						{
							$sold_auctions_content .= ' &middot; <a href="members_area.php?page=selling&section=view_offers&auction_id=' . $item_details['auction_id'] . '">'  . MSG_SECOND_CHANCE_PURCHASING . '</a>';						
						}
						
						if ($item_details['payer_id'] == $session->value('user_id') && $item->request_refund($item_details['refund_invoice_id'], $item_details['purchase_date'], $item_details['flag_paid'], $item_details['refund_request']))
						{
							$sold_auctions_content .= ' &middot; <a href="members_area.php?do=request_refund&refund_invoice_id=' . $item_details['refund_invoice_id'] . $additional_vars . $order_link . $limit_link  . $show_link. '" onclick="return confirm(\'' . MSG_REQUEST_REFUND_CONFIRM . '\');">' . MSG_REQUEST_EOA_REFUND . '</a>';
						}

						$sold_auctions_content .= '</td></tr>';
					}
					
					if ($item_details['temp_purchase'])
					{
						$sold_auctions_content .= '<tr> '.
							'	<td colspan="6" class="c3"><b>' . MSG_BUYOUT_FORCE_PAYMENT_ALERT . '</b></td> '.
							'</tr>';						
					}
					
					$sold_auctions_content .= '<tr><td colspan="6" class="c4"></td></tr>';
				}

			}
			else
			{
				$sold_auctions_content = '<tr><td colspan="8" align="center">' . GMSG_NO_ITEMS_MSG . '</td></tr>';
			}

			$template->set('sold_auctions_content', $sold_auctions_content);

			$pagination = paginate($start, $limit, $nb_items, 'members_area.php', $additional_vars . $order_link . $show_link);
			$template->set('pagination', $pagination);

			$members_area_page_content = $template->process('members_area_selling_sold.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);

		}
		if ($section == 'sold_carts')
		{
			$limit = 3; // 3 carts per page

			$cart = new shop();
			$cart->setts = &$setts;
			$cart->sc_field = 'seller_id';
			$cart->sc_value = $session->value('user_id');

			if (isset($_REQUEST['form_update_sc_status']))
			{
				$db->query("UPDATE " . DB_PREFIX . "shopping_carts SET flag_paid='" . intval($_REQUEST['flag_paid']) . "',
					flag_status='" . intval($_REQUEST['flag_status']) . "' WHERE sc_id='" . $sc_id . "' AND
					seller_id='" . $session->value('user_id') . "'");
				$db->query("UPDATE " . DB_PREFIX . "winners SET flag_paid='" . intval($_REQUEST['flag_paid']) . "',
					flag_status='" . intval($_REQUEST['flag_status']) . "' WHERE sc_id='" . $sc_id . "' AND
					seller_id='" . $session->value('user_id') . "'");
			}

			if (isset($_REQUEST['form_update_sc_postage']))
			{
				$template->set('msg_changes_saved', $msg_changes_saved);

				$db->query("UPDATE " . DB_PREFIX . "shopping_carts SET
					sc_postage='" . doubleval($_REQUEST['sc_postage']) . "', shipping_calc_auto=1, invoice_final=1   
					WHERE sc_id='" . $sc_id . "' AND seller_id='" . $session->value('user_id') . "'");

				## send invoice notification
				$mail_input_id = $sc_id;
				include('language/' . $setts['site_lang'] . '/mails/cart_invoice_buyer_notification.php');
			}

			$shop_active = $db->get_sql_field("SELECT shop_active FROM
				" . DB_PREFIX . "users WHERE user_id=" . $session->value('user_id'), 'shop_active');

			$nb_items = $nb_sold_carts;

			$template->set('nb_items', $nb_items);

			$template->set('page_order_sc_id', page_order('members_area.php', 'sc.sc_id', $start, $limit, $additional_vars, MSG_SC_ID));
			$template->set('page_order_purchase_date', page_order('members_area.php', 'sc.sc_date', $start, $limit, $additional_vars, MSG_PURCHASE_DATE));

			if ($nb_items)
			{
				$sql_select_sold_carts = $db->query("SELECT sc.*, u.username,
					(SELECT count(*) FROM " . DB_PREFIX . "shopping_carts_items sci WHERE sci.sc_id=sc.sc_id) AS nb_cart_items, 
					(SELECT count(*) FROM " . DB_PREFIX . "winners w WHERE w.sc_id=sc.sc_id AND w.active=1 AND w.payment_status='confirmed') AS nb_cart_items_paid, 
					m.message_id FROM " . DB_PREFIX . "shopping_carts sc 
					LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=sc.buyer_id
					LEFT JOIN " . DB_PREFIX . "messaging m ON m.sc_id=sc.sc_id AND m.is_read=0 AND m.sender_id!=sc.seller_id 					
					WHERE sc.seller_id='" . $session->value('user_id') . "' AND sc.sc_purchased=1 AND sc.s_deleted=0   
					ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit);

                $counter= 0 ;
				while ($sc_details = $db->fetch_array($sql_select_sold_carts))
				{
					$background = ($counter++%2) ? 'c1' : 'c2';

					$cart_paid = ($sc_details['nb_cart_items'] == $sc_details['nb_cart_items_paid']) ? 1 : 0;
					if ($cart_paid)
					{
						$content_options = '&#8226; <a href="' . process_link('message_board', array('message_handle' => '6', 'sc_id' => $sc_details['sc_id'])) . '"><b class="greenfont">' . MSG_MESSAGE_BOARD . '</b></a><br>';
						$content_options .= '&#8226; <a href="members_area.php?do=delete_cart&option=seller&sc_id=' . $sc_details['sc_id'] . $additional_vars . $order_link . $limit_link . '" onclick="return confirm(\'' . MSG_DELETE_CONFIRM . '\');"><b class="redfont">' . MSG_DELETE . '</b></a>';
					}
					else
					{
						$sale_fee = new fees();
						$sale_fee->setts = &$setts;

						$sale_fee->set_fees($sc_details['seller_id']);

						if (eregi('s', $sale_fee->fee['endauction_fee_applies']))
						{
							$content_options = '&#8226; <a href="fee_payment.php?do=sc_sale_fee_payment&sc_id=' . $sc_details['sc_id'] . '">' . MSG_PAY_ENDAUCTION_FEE . '</a>';
						}
						else
						{
							$content_options = '&#8226; ' . MSG_ENDAUCTION_FEE_NOT_PAID;
						}
					}

					$addl_vars = $additional_vars . $order_link . $limit_link . $show_link;
					$sold_carts_content .= '<tr> '.
					'	<td class="contentfont">' . $cart->cart_table_display($sc_details['sc_id'], true, true, $addl_vars) . '</td>'.
					'	<td align="center" class="' . $background . '"> ';

					if ($cart_paid)
					{
						$sold_carts_content .= '<table border="0" cellpadding="0" cellspacing="0" class="soldCartsContent border"> '.
						'	<tr valign="top" bgcolor="#FFFFF"> '.
						'		<td class="smallfont" nowrap><b>' . MSG_USERNAME . '</b></td> '.
						'		<td class="smallfont" width="100%">' . field_display($sc_details['username'], GMSG_NA) . '</td> '.
						'	</tr> '.
						'</table><br>';

						$sold_carts_content .=
						'<table border="0" cellpadding="0" cellspacing="0" class="soldCartsContent border"> '.
						'	<tr> '.
						'		<td align="center">' . show_date($sc_details['sc_date']) . '</td> '.
						'	</tr> '.
						'	<form action="" method="post"> '.
						'	<input type="hidden" name="sc_id" value="' . $sc_details['sc_id'] . '"> '.
						'		<tr bgcolor="#FFFFF"> '.
						'			<td align="center"><select name="flag_paid" style="font-size:10px; width: 100px;"> '.
						'				<option value="0" ' . (($sc_details['flag_paid'] == 0) ? 'selected' : '') . '>' . MSG_UNPAID . '</option> '.
						'				<option value="1" ' . (($sc_details['flag_paid'] == 1) ? 'selected' : '') . '>' . MSG_PAID . '</option> '.
						'			</select></td> '.
						'		</tr> '.
						'		<tr bgcolor="#FFFFF"> '.
						'			<td align="center"><select name="flag_status" style="font-size:10px; width: 100px;"> '.
						'				<option value="0" ' . (($sc_details['flag_status'] == 0) ? 'selected' : '') . '>' . MSG_FLAG_STATUS_A . '</option> '.
						'				<option value="1" ' . (($sc_details['flag_status'] == 1) ? 'selected' : '') . '>' . MSG_FLAG_STATUS_B . '</option> '.
						'				<option value="2" ' . (($sc_details['flag_status'] == 2) ? 'selected' : '') . '>' . MSG_FLAG_STATUS_C . '</option> '.
						'				<option value="3" ' . (($sc_details['flag_status'] == 3) ? 'selected' : '') . '>' . MSG_FLAG_STATUS_D . '</option> '.
						'			</select></td> '.
						'		</tr> '.
						'		<tr bgcolor="#FFFFF"> '.
						'			<td align="center"><input type="submit" name="form_update_sc_status" value="' . GMSG_GO . '" style="font-size:10px; width: 100px;"></td> '.
						'		</tr> '.
						'	</form> '.
						'</table>';
					}
					$sold_carts_content .= '	</td>'.
					'	<td class="smallfont ' . $background . '">' . $content_options . '</td>'.
					'</tr>';

					if ($cart_paid)
					{
						$sold_carts_content .= '<tr><td colspan="6" class="contentfont">';
						if ($sc_details['shipping_calc_auto'])
						{
							$sold_carts_content .= '&#8226; <b>' . MSG_INVOICE_SENT . '</b> [ ' . MSG_ID . ': ' . $sc_details['sc_id'] . ' ] <a href="' . process_link('invoice_print', array('invoice_type' => 'sc_invoice', 'invoice_id' => $sc_details['sc_id'])) . '" target="_blank">' . MSG_VIEW_CART_INVOICE . '</a> &middot; '.
							'<a href="members_area.php?do=resend_cart_invoice&invoice_id=' . $sc_details['sc_id'] . $additional_vars . $order_link . $limit_link . '">' . MSG_RESEND_CART_INVOICE . '</a>';
						}
						$sold_carts_content .= '</td></tr>';
					}
					$sold_carts_content .= '<tr><td colspan="6" class="c4"></td></tr>';
				}
			}
			else
			{
				$sold_carts_content = '<tr><td colspan="8" align="center">' . GMSG_NO_CARTS_MSG . '</td></tr>';
			}

			$template->set('sold_carts_content', $sold_carts_content);

			$pagination = paginate($start, $limit, $nb_items, 'members_area.php', $additional_vars . $order_link);
			$template->set('pagination', $pagination);

			$members_area_page_content = $template->process('members_area_selling_sold_carts.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);
		}


		if ($section == 'invoices_sent')
		{
			$nb_items = $db->get_sql_number("SELECT winner_id FROM " . DB_PREFIX . "winners WHERE 
				invoice_sent=1 AND seller_id='" . $session->value('user_id') . "' GROUP BY invoice_id");
			$template->set('nb_items', $nb_items);

			(string) $invoices_sent_content = null;
			
			if ($nb_items)
			{
				$sql_select_invoices = $db->query("SELECT w.*, u.username FROM " . DB_PREFIX . "winners w
					LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=w.buyer_id
					WHERE w.seller_id='" . $session->value('user_id') . "' AND w.s_deleted=0 AND w.invoice_sent=1
					GROUP BY w.invoice_id
					ORDER BY w.invoice_id DESC LIMIT " . $start . ", 5");
				
				while ($invoice_details = $db->fetch_array($sql_select_invoices)) 
				{
					$invoices_sent_content .= '<tr> '.
						'	<td colspan="2">[ ' . MSG_INVOICE_ID . ': ' . $invoice_details['invoice_id'] . ' ] &nbsp; [ ' . MSG_BUYER_USERNAME . ': ' . $invoice_details['username'] . ' ]'.
						'	<td align="center" class="contentfont">[ <a href="members_area.php?do=delete_invoice&option=seller&invoice_id=' . $invoice_details['invoice_id'] . $additional_vars . $order_link . $limit_link . '" onclick="return confirm(\'' . MSG_DELETE_CONFIRM . '\');"><b class="redfont">' . MSG_DELETE . '</b></a> ]'.
						'</tr> ';
					
					$sql_select_products = $db->query("SELECT w.*, a.name,  
						a.direct_payment, a.currency FROM " . DB_PREFIX . "winners w 
						LEFT JOIN " . DB_PREFIX . "auctions a ON a.auction_id=w.auction_id WHERE 
						w.invoice_id='" . $invoice_details['invoice_id'] . "'");
					
					$invoices_sent_content .= '<tr align="center" class="membmenu"> '.
						'	<td align="left">' . GMSG_DESCRIPTION . '</td> '.
						'	<td>' . GMSG_QUANTITY . '</td> '.
						'	<td>' . GMSG_PRICE_ITEM . '</td> '.
						'</tr> '.
						'<tr class="c3"> '.
						'	<td width="100%"></td> '.
						'	<td><img src="themes/' . $setts['default_theme'] . '/img/pixel.gif" width="60" height="1"></td> '.
						'	<td><img src="themes/' . $setts['default_theme'] . '/img/pixel.gif" width="80" height="1"></td> '.
						'</tr> ';

					$product_postage = null;
					$product_insurance = null;
					while ($item_details = $db->fetch_array($sql_select_products)) 
					{
						$background = 'c1';
						
						$currency = $item_details['currency'];
						
						$product_postage = ($item_details['postage_included']) ? (($item_details['pc_postage_type'] == 'item') ? ($item_details['postage_amount'] + $product_postage) : $item_details['postage_amount']) : 0;
						$product_insurance += ($item_details['insurance_included']) ? $item_details['insurance_amount'] : 0;
						$auction_link = process_link('auction_details', array('name' => $item_details['name'], 'auction_id' => $item_details['auction_id']));
						
						$invoices_sent_content .= '<tr class="' . $background . '" align="center"> '.
							'	<td align="left" class="contentfont">[ ' . MSG_ID . ': <a href="' . $auction_link . '">' . $item_details['auction_id'] . '</a> ] <a href="' . $auction_link . '">' . $item_details['name'] . '</a></td> '.
							'	<td>' . $item_details['quantity_offered'] . '</td> '.
							'	<td>' . $fees->display_amount($item_details['bid_amount'], $item_details['currency']) . '</td> '.
							'</tr> ';
					}

					// new postage and insurance tab
					$invoices_sent_content .= '<tr> '.
						'	<td colspan="5" class="c4"></td> '.
						'</tr> '.
						'<tr> '.
						'	<td></td>'.
						'	<td class="c1"  >' . MSG_POSTAGE . ':</td>'.
						'	<td class="c1" align="center">' . $fees->display_amount($product_postage, $currency) . '</td>'.
						'</tr> '.
						'<tr> '.
						'	<td></td>'.
						'	<td class="c1"  >' . MSG_INSURANCE . ':</td>'.
						'	<td class="c1" align="center">' . $fees->display_amount($product_insurance, $currency) . '</td>'.
						'</tr> '.
						'<tr> '.
						'	<td colspan="5" class="c4"></td> '.
						'</tr> ';
					
					(string) $direct_payment_link = null;
					
					$invoices_sent_content .= '<tr> '.
						'	<td colspan="5" class="contentfont">[ <a href="' . process_link('invoice_print', array('invoice_type' => 'product_invoice', 'invoice_id' => $invoice_details['invoice_id'])) . '" target="_blank">' . MSG_VIEW_PRODUCT_INVOICE . '</a> ] &middot; ' . 
						((!$invoice_details['invoice_final']) ? '[ <a href="members_area.php?page=selling&section=product_invoice&option=edit_invoice&invoice_id=' . $invoice_details['invoice_id'] . '">' . MSG_EDIT_PRODUCT_INVOICE . '</a> ] &middot; ' : ''). 
						'		[ <a href="members_area.php?do=resend_invoice&invoice_id=' . $invoice_details['invoice_id'] . $additional_vars . $order_link . $limit_link . '">' . MSG_RESEND_PRODUCT_INVOICE . '</a>] ' . $direct_payment_link . '</td>'.
						'</tr> '.
						'<tr> '.
						'	<td colspan="5" class="c4"></td> '.
						'</tr> ';
				}
			}
			else 
			{
				$invoices_sent_content = '<tr><td colspan="5" align="center">' . GMSG_NO_ITEMS_MSG . '</td></tr>';
			}
			
			$template->set('invoices_sent_content', $invoices_sent_content);
			
			$pagination = paginate($start, 5, $nb_items, 'members_area.php', $additional_vars . $order_link);
			$template->set('pagination', $pagination);
			
			$members_area_page_content = $template->process('members_area_selling_invoices_sent.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);
		}
		
		if ($section == 'fees_calculator')
		{
			$item_details = $_POST;
			$item_details['currency'] = ($item_details['currency']) ? $item_details['currency'] : $setts['currency'];

			$item_details['ad_image'] = array();
			$item_details['ad_image'][0] = ($item_details['is_image']) ? 'image_placeholder' : '';

			$item_details['ad_video'] = array();
			$item_details['ad_video'][0] = ($item_details['is_video']) ? 'video_placeholder' : '';

			if (isset($_POST['form_save_settings']))
			{
				$setup_fee = new fees();
				$setup_fee->setts = &$setts;

				if ($item_details['start_price'] > 0)
				{
					$user_details = $db->get_sql_row("SELECT user_id, username, shop_account_id, shop_categories,
						shop_active, preferred_seller, reg_date, country, state, zip_code, balance,
						default_name, default_description, default_duration, default_hidden_bidding,
						default_enable_swap, default_shipping_method, default_shipping_int, default_postage_amount,
						default_insurance_amount, default_type_service, default_shipping_details, default_payment_methods FROM
						" . DB_PREFIX . "users WHERE user_id=" . $session->value('user_id'));

					$auction_fees = $setup_fee->auction_setup_fees($item_details, $user_details);
					$fees_calculator_result = $auction_fees['display'];
				}
				else
				{
					$fees_calculator_result = '<tr><td align="center" height="30" colspan="3">' . MSG_START_PRICE_ERROR . '</td></tr>';
				}

				$template->set('fees_calculator_result', $fees_calculator_result);
			}

			$currency_drop_down = $item->currency_drop_down('currency', $item_details['currency'], 'fees_calculator_form');
			$template->set('currency_drop_down', $currency_drop_down);

			$categories_list_menu = categories_list ($item_details['category_id']);
			$template->set('categories_list_menu', $categories_list_menu);

			$template->set('item_details', $item_details);
			$members_area_page_content = $template->process('members_area_selling_fees_calculator.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);
		}

		if ($section == 'prefilled_fields')
		{
			if (isset($_POST['form_save_settings']))
			{
				$template->set('msg_changes_saved', $msg_changes_saved);

				$item->update_prefilled($_POST, $session->value('user_id'));
			}

			$header_selling_page = headercat('<b>' . MSG_MM_SELLING . ' - ' . MSG_MM_PREFILLED_FIELDS . '</b>');

			$template->set('header_selling_page', $header_selling_page);

			$prefilled_fields = $db->get_sql_row("SELECT * FROM " . DB_PREFIX . "users WHERE
				user_id='" . $session->value('user_id') . "'");

			$template->set('prefilled_fields', $prefilled_fields);

			$item_description_editor = "<script> \n" .
//				" 	var oEdit1 = new InnovaEditor(\"oEdit1\"); \n" .
//				" 	oEdit1.width=\"100%\";//You can also use %, for example: oEdit1.width=\"100%\" \n" .
//				"	oEdit1.height=300; \n" .
//				"	oEdit1.REPLACE(\"description_main\");//Specify the id of the textarea here \n" .
				"</script>";

			$template->set('item_description_editor', $item_description_editor);
			
			$default_currency = (!empty($prefilled_fields['default_currency'])) ? $prefilled_fields['default_currency'] : $setts['currency'];			
			$template->set('currency_drop_down', $item->currency_drop_down('currency', $default_currency));
			
			$template->set('duration_drop_down', $item->durations_drop_down('duration', $prefilled_fields['default_duration']));
			$template->set('shipping_methods_drop_down', $item->shipping_methods_drop_down('default_type_service', $prefilled_fields['default_type_service']));

			$direct_payments = $item->select_direct_payment($prefilled_fields['default_direct_payment'], $session->value('user_id'));

			$direct_payment_table = $template->generate_table($direct_payments, 4, 1, 1, '75%');
			$template->set('direct_payment_table', $direct_payment_table);
			
			$offline_payments = $item->select_offline_payment($prefilled_fields['default_payment_methods']);

			$offline_payment_table = $template->generate_table($offline_payments, 4, 1, 1, '75%');
			$template->set('offline_payment_table', $offline_payment_table);
			
			$members_area_page_content = $template->process('members_area_selling_prefilled.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);

		}

		if ($section == 'block_users')
		{
			$template->set('do', $_REQUEST['do']);
			if ($_REQUEST['do'] == 'add_user')
			{
				$form_submitted = false;

				if (isset($_POST['form_add_blocked_user']))
				{
					$post_details = $db->rem_special_chars_array($_POST);

					$is_user = $db->count_rows('users', "WHERE username='" . $post_details['username'] . "'");

					if ($is_user)
					{
						$form_submitted = true;

						$blocked_user_id = $db->get_sql_field("SELECT user_id FROM " . DB_PREFIX . "users WHERE
							username='" . $post_details['username'] . "'", 'user_id');

						$db->query("INSERT INTO " . DB_PREFIX . "blocked_users
							(user_id, owner_id, reg_date, block_reason, show_reason, block_bid, block_message, block_reputation) VALUES
							('" . $blocked_user_id . "', '" . $session->value('user_id') . "', '" . CURRENT_TIME . "',
							'" . $post_details['block_reason'] . "', '" . $post_details['show_reason'] . "', 
							'" . intval($post_details['block_bid']) . "', '" . intval($post_details['block_message']) . "', 
							'" . intval($post_details['block_reputation']) . "')");

						$template->set('msg_changes_saved', '<p align="center">' . MSG_BLOCKED_USER_ADD_SUCCESS . '</p>');
					}
					else
					{
						$display_formcheck_errors = '<tr> '.
      					'	<td align="center">' . MSG_ERROR_USER_DOESNT_EXIST . '</td> '.
   						'</tr> ';
   					$template->set('display_formcheck_errors', $display_formcheck_errors);
					}
				}

				if (!$form_submitted)
				{
					$template->set('post_details', $post_details);

					$template->set('block_users_header_message', MSG_ADD_BLOCKED_USER);

					$block_add_user_content = $template->process('members_area_selling_block_users_add_user.tpl.php');
					$template->set('block_add_user_content', $block_add_user_content);
				}
			}
			else if ($_REQUEST['do'] == 'edit_user')
			{
				$form_submitted = false;

				if (isset($_POST['form_add_blocked_user']))
				{
					$post_details = $db->rem_special_chars_array($_POST);

					$form_submitted = true;

					$db->query("UPDATE " . DB_PREFIX . "blocked_users SET
						block_reason='" . $post_details['block_reason'] . "',	show_reason='" . $post_details['show_reason'] . "', 
						block_bid='" . intval($post_details['block_bid']) . "', block_message='" . intval($post_details['block_message']) . "', 
						block_reputation='" . intval($post_details['block_reputation']) . "' WHERE
						block_id='" . intval($_REQUEST['block_id']) . "' AND owner_id='" . $session->value('user_id') . "'");

					$template->set('msg_changes_saved', '<p align="center">' . MSG_BLOCKED_USER_EDIT_SUCCESS . '</p>');
				}
				else
				{
					$post_details = $db->get_sql_row("SELECT b.*, u.username FROM " . DB_PREFIX . "blocked_users b
						LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=b.user_id WHERE
						b.block_id='" . intval($_REQUEST['block_id']) . "' AND b.owner_id='" . $session->value('user_id') . "'");
				}

				if (!$form_submitted)
				{
					$template->set('post_details', $post_details);

					$template->set('block_users_header_message', MSG_EDIT_BLOCKED_USER);

					$block_add_user_content = $template->process('members_area_selling_block_users_add_user.tpl.php');
					$template->set('block_add_user_content', $block_add_user_content);
				}

			}
			else if ($_REQUEST['do'] == 'delete_user')
			{
				$db->query("DELETE FROM " . DB_PREFIX . "blocked_users WHERE block_id='" . intval($_REQUEST['block_id']) . "' AND
					owner_id='" . $session->value('user_id') . "'");

				$template->set('msg_changes_saved', '<p align="center">' . MSG_BLOCKED_USER_DELETE_SUCCESS . '</p>');
			}


			$nb_items = $db->count_rows('blocked_users', "WHERE owner_id='" . $session->value('user_id') . "'");
			$template->set('nb_items', $nb_items);

			if ($nb_items)
			{
				$sql_select_blocked = $db->query("SELECT b.*, u.username FROM " . DB_PREFIX . "blocked_users b
					LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=b.user_id
					WHERE b.owner_id='" . $session->value('user_id') . "'
					ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit);

                $counter= 0 ;
				while ($block_details = $db->fetch_array($sql_select_blocked))
				{
					$background = ($counter++%2) ? 'c1' : 'c2';

					$content_options = '[ <a href="members_area.php?page=selling&section=block_users&do=edit_user&block_id=' . $block_details['block_id'] . '">' . GMSG_EDIT . '</a> ] ';
					$content_options .= '[ <a href="members_area.php?do=delete_user&block_id=' . $block_details['block_id'] . $additional_vars . $order_link . $limit_link . '" onclick="return confirm(\'' . MSG_DELETE_CONFIRM . '\');">' . MSG_DELETE . '</a> ]';

					$blocked_users_content .= '<tr class="' . $background . '"> '.
						'	<td class="contentfont">' . $block_details['username'] . '</td>'.
						'	<td>' . $block_details['block_reason'] . '</td>'.
						'	<td align="center">' . field_display($block_details['show_reason'], '<span class="redfont">' . GMSG_NO . '</span>', '<span class="greenfont">' . GMSG_YES . '</span>') . '</td> '.
						'	<td align="center" class="smallfont">' . $db->implode_array(block_type($block_details), '; ', true, GMSG_NA) . '</td>'.
						'	<td align="center" class="smallfont">' . $content_options . '</td>'.
						'</tr>';
				}
			}
			else
			{
				$blocked_users_content = '<tr><td colspan="4" align="center">' . MSG_NO_BLOCKED_USERS . '</td></tr>';
			}

			$template->set('blocked_users_content', $blocked_users_content);

			$pagination = paginate($start, $limit, $nb_items, 'members_area.php', $additional_vars . $order_link);
			$template->set('pagination', $pagination);

			$members_area_page_content = $template->process('members_area_selling_block_users.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);

		}

		if ($section == 'postage_setup')
		{
			if ($_REQUEST['option'] == 'delete_location')
			{
				$db->query("DELETE FROM " . DB_PREFIX . "shipping_locations WHERE 
					id='" . intval($_REQUEST['id']) . "' AND user_id='" . $session->value('user_id') . "'");
				
				$template->set('msg_changes_saved', $msg_changes_saved);
			}
			
			if (isset($_POST['form_postage_save']))
			{
				$user = new user();
				$user->setts = &$setts;
				
				$template->set('msg_changes_saved', $msg_changes_saved);
				$user->postage_calc_save($_POST, $session->value('user_id'));
			}
			else 
			{
				$postage_details = $db->get_sql_row("SELECT * FROM
					" . DB_PREFIX . "users WHERE user_id=" . $session->value('user_id'));
			}

			if ($_POST['box_submit'] == 1)
			{
				$postage_details = $_POST;
			}
			
			$postage_details['pc_postage_type'] = (in_array($postage_details['pc_postage_type'], array('item', 'weight', 'amount', 'flat'))) ? $postage_details['pc_postage_type'] : 'item';
			
			$template->set('postage_details', $db->rem_special_chars_array($postage_details));

      	(string) $postage_tiers_table = null;
      	
      	$postage_tiers_table = '<table border="0" cellpadding="2" cellspacing="2" class="postageTiersTable border"> '.
				'<tr class="c3">'.
				'	<td width="20">&nbsp;</td> '.
				'	<td width="120" align="center">' . (($postage_details['pc_postage_type'] == 'weight') ? GMSG_WEIGHT_FROM : GMSG_AMOUNT_FROM) . '</td> '.
				'	<td width="120" align="center">' . (($postage_details['pc_postage_type'] == 'weight') ? GMSG_WEIGHT_TO : GMSG_AMOUNT_TO) . '</td> '.
				'	<td width="120" align="center">' . GMSG_AMOUNT . '</td> '.
				(($postage_details['pc_postage_calc_type'] == 'custom') ? '<td width="80" align="center">' . MSG_DELETE . '</td>' : '').
				'</tr> ';
				
			if ($postage_details['pc_postage_calc_type'] == 'custom') /* custom postage tiers used */
			{
				$sql_select_tiers = $db->query("SELECT * FROM " . DB_PREFIX . "postage_calc_tiers WHERE 
					user_id='" . $session->value('user_id') . "' AND tier_type='" . $postage_details['pc_postage_type'] . "' ORDER BY tier_from ASC");

                $counter= 0 ;
				while ($tier_details = $db->fetch_array($sql_select_tiers))
				{
					$background = ($counter++%2) ? 'c1' : 'c2';
			
					$tier_details = convert_amount($tier_details, 'NTS');
					
					$postage_tiers_table .= '<input type="hidden" name="tier_id[]" value="' . $tier_details['tier_id'] . '"> '.
						'<tr class="' . $background . '"> '.
						'	<td></td> '.
			      	'	<td><input name="tier_from[]" type="text" value="' . $tier_details['tier_from'] . '" size="12"></td> '.
			      	'	<td><input name="tier_to[]" type="text" value="' . $tier_details['tier_to'] . '" size="12"></td> '.
			      	'	<td><input name="postage_amount[]" type="text" value="' . $tier_details['postage_amount'] . '" size="12"></td> '.
						'	<td align="center"><input type="checkbox" name="delete[]" value="' . $tier_details['tier_id'] . '"></td> '.
						'</tr> ';
				}
				
				$postage_tiers_table .= '<tr class="c3"> '.
					'	<td style="padding: 3px;" colspan="5">' . MSG_ADD_TIER . '</td> '.
					'</tr> '.
					'<tr class="c1"> '.
					'	<td>&nbsp;</td> '.
					'	<td><input type="text" name="new_tier_from" size="12" /></td> '.
					'	<td width="120"><input type="text" name="new_tier_to" size="12" /></td> '.
					'	<td><input type="text" name="new_postage_amount" size="12" /></td> '.
					'	<td width="80" align="center">&nbsp;</td> '.
					'</tr> ';
			}
			else /* global postage tiers used */
			{
				$sql_select_tiers = $db->query("SELECT * FROM " . DB_PREFIX . "postage_calc_tiers WHERE 
					user_id='0' AND tier_type='" . $postage_details['pc_postage_type'] . "' ORDER BY tier_from ASC");

                $counter= 0 ;
				while ($tier_details = $db->fetch_array($sql_select_tiers))
				{
					$background = ($counter++%2) ? 'c1' : 'c2';
			
					$tier_details = convert_amount($tier_details, 'NTS');
					
					$postage_tiers_table .= '<tr class="' . $background . '"> '.
						'	<td></td> '.
			      	'	<td align="center">' . $tier_details['tier_from'] . '</td> '.
			      	'	<td align="center">' . $tier_details['tier_to'] . '</td> '.
			      	'	<td align="center">' . $tier_details['postage_amount'] . '</td> '.
						'</tr> ';
				}
				
			}
			
			$postage_tiers_table .= '</table>';
			
			$template->set('postage_tiers_table', $postage_tiers_table);

			if ($postage_details['pc_shipping_locations'] == 'local')
			{
				$sql_select_shipping_locations = $db->query("SELECT * FROM " . DB_PREFIX . "shipping_locations WHERE 
					user_id='" . $session->value('user_id') . "' ORDER BY amount ASC");
				
				$shipping_locations_table = null;
				
				$tax = new tax();
				
				while ($location_details = $db->fetch_array($sql_select_shipping_locations))
				{
					$shipping_locations_table .= '<tr class="c1">'.
						'	<td>' . title_resize($tax->display_countries($location_details['locations_id']), 200, true) . '</td>'.
						'	<td align="center">' . (($location_details['amount_type'] == 'flat') ? $fees->display_amount($location_details['amount']) : $location_details['amount'] . '%') . '</td>'.
						//'	<td align="center"><input type="radio" name="pc_default" value="' . $location_details['id'] . '" ' . (($location_details['pc_default']) ? 'checked' : '') . '></td>'.
						'	<td align="center" class="contentfont">[ <a href="javascript:;"  onClick="openPopup(\'shipping_locations_select.php?option=edit&id=' . $location_details['id'] . '&form_name=form_selling_postage_setup\')">' . GMSG_EDIT . '</a> ]<br>'.
						'		[ <a href="members_area.php?page=selling&section=postage_setup&option=delete_location&id=' . $location_details['id'] . '">' . GMSG_DELETE . '</a> ]</td>'.
						'</tr>';
				}
				
				if (empty($shipping_locations_table))
				{
					$shipping_locations_table = '<tr><td colspan="4">' . MSG_NO_SHIPPING_LOCATIONS_SET . '</td></tr>';
				}
				$template->set('shipping_locations_table', $shipping_locations_table);
			}
			$members_area_page_content = $template->process('members_area_selling_postage_setup.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);
		}
		
	} /* END -> SELLING PAGES */
	
	if ($page == 'reputation') /* BEGIN -> REPUTATION PAGES */
	{
		$reputation = new reputation();
		$reputation->setts = &$setts;

		if ($section == 'post' || isset($_POST['form_reputation_post']))
		{
			$custom_fld = new custom_field();

			$reputation_ids = format_response_integer($_POST['reputation_id'], $_REQUEST['reputation_ids']);
			$template->set('reputation_ids', $reputation_ids);
			
			$sql_select_reputation = $db->query("SELECT r.*, u.username, IF(a.auction_id,a.name,rp.name) AS auction_name FROM " . DB_PREFIX . "reputation r
					LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=r.user_id
					LEFT JOIN " . DB_PREFIX . "auctions a ON a.auction_id=r.auction_id
					LEFT JOIN " . DB_PREFIX . "reverse_auctions rp ON rp.reverse_id=r.reverse_id
					WHERE r.from_id='" . $session->value('user_id') . "' AND r.submitted=0 AND r.reputation_id IN (" . $reputation_ids . ")");

			$nb_reputation_rows = $db->num_rows($sql_select_reputation);
			if ($nb_reputation_rows > 0)
			{
				if ($nb_reputation_rows == 1)
				{
					$reputation_details = $db->fetch_array($sql_select_reputation);
				}
				else 
				{
					$reputation_details['username'] = MSG_MULTIPLE_USERS;
					$reputation_details['auction_name'] = MSG_MULTIPLE_AUCTIONS;
				}
				$template->set('reputation_details', $reputation_details);

				$form_submitted = false;
				$post_details = $_POST;

				$custom_fld->save_vars($_POST);

				$template->set('post_details', $post_details);

				if (isset($_POST['form_leave_comments'])) /* formchecker code snippet */
				{
					define ('FRMCHK_ITEM', 1);
					(int) $item_post = 1;

					$frmchk_details = $post_details;

					include('includes/procedure_frmchk_reputation.php');

					if ($fv->is_error())
					{
						$template->set('display_formcheck_errors', '<tr><td><br>' . $fv->display_errors() . '</td></tr>');
					}
					else
					{
						$form_submitted = true;

						$reputation->save($post_details, $session->value('user_id'));
						$template->set('message_content', '<p align="center">' . MSG_REPUTATION_SAVED . '</p>');
						$members_area_page_content = $template->process('single_message.tpl.php');
					}
				}

				if (!$form_submitted)
				{
					$custom_fld->new_table = false;
					$custom_fld->field_colspan = 1;
					$page_handle = $reputation->cf_page_handle($reputation_details);
					$custom_sections_table = $custom_fld->display_sections($post_details, $page_handle, false, $reputation_details['reputation_id']);
					$template->set('custom_sections_table', $custom_sections_table);

					$members_area_page_content = $template->process('members_area_reputation_post.tpl.php');
				}
			}
			else
			{
				$template->set('message_content', '<p align="center">' . MSG_POST_REP_FAILURE . '</p>');
				$members_area_page_content = $template->process('single_message.tpl.php');
			}

			$template->set('members_area_page_content', $members_area_page_content);
		}

		if ($section == 'received')
		{
			$nb_items = $db->count_rows('reputation', "WHERE user_id='" . $session->value('user_id') . "' AND
				submitted=1");

			$template->set('nb_items', $nb_items);

			if ($nb_items)
			{
				$sql_select_reputation = $db->query("SELECT r.*, u.username FROM " . DB_PREFIX . "reputation r
					LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=r.from_id
					WHERE r.user_id='" . $session->value('user_id') . "' AND r.submitted=1
					ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit);

                $counter= 0 ;
				while ($reputation_details = $db->fetch_array($sql_select_reputation))
				{
					$background = ($counter++%2) ? 'c1' : 'c2';

					$reputation_received_content .= '<tr class="' . $background . ' contentfont"> '.
						'	<td align="center">' . $reputation_details['username'] . '</td> '.
						'	<td align="center">' . $reputation->rep_rate($reputation_details['reputation_rate']) . '</td>'.
						'	<td align="center">' . show_date($reputation_details['reg_date'], false) . '</td> '.
						'	<td>' . $reputation_details['reputation_content'] . '</td> '.
						'	<td align="center">[ <a href="javascript://" onclick="popUp(\'reputation_details.php?reputation_id=' . $reputation_details['reputation_id'] . '\');">' . GMSG_VIEW . '</a> ]</td> '.
						'	<td align="center">' . $reputation->reputation_type($reputation_details) . '</td> '.
						'</tr>';
				}
			}
			else
			{
				$reputation_received_content = '<tr><td colspan="5" align="center">' . GMSG_NO_COMMENTS_MSG . '</td></tr>';
			}

			$template->set('reputation_received_content', $reputation_received_content);

			$pagination = paginate($start, $limit, $nb_items, 'members_area.php', $additional_vars . $order_link);
			$template->set('pagination', $pagination);

			$members_area_page_content = $template->process('members_area_reputation_received.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);
		}

		if ($section == 'sent')
		{
			$sql_nb_items = $db->query("SELECT r.reputation_id FROM " . DB_PREFIX . "reputation r
				LEFT JOIN " . DB_PREFIX . "auctions a ON a.auction_id=r.auction_id
				LEFT JOIN " . DB_PREFIX . "reverse_auctions rp ON rp.reverse_id=r.reverse_id
				WHERE r.from_id='" . $session->value('user_id') . "' AND r.submitted=0 AND (a.auction_id!=0 OR rp.reverse_id!=0)");
			$nb_items = $db->num_rows($sql_nb_items);

			$template->set('nb_items', intval($nb_items));

			$nb_auction_items = 0;
			if ($nb_items)
			{
				$sql_select_reputation = $db->query("SELECT r.*, u.username, IF(a.auction_id, a.name, rp.name) AS auction_name FROM " . DB_PREFIX . "reputation r
					LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=r.user_id
					LEFT JOIN " . DB_PREFIX . "auctions a ON a.auction_id=r.auction_id
					LEFT JOIN " . DB_PREFIX . "reverse_auctions rp ON rp.reverse_id=r.reverse_id
					WHERE r.from_id='" . $session->value('user_id') . "' AND r.submitted=0 AND (a.auction_id!=0 OR rp.reverse_id!=0)
					ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit);

                $counter= 0 ;
				while ($reputation_details = $db->fetch_array($sql_select_reputation))
				{
					if ($reputation_details['auction_id'])
					{
						$nb_auction_items++;
					}
					
					$background = ($counter++%2) ? 'c1' : 'c2';

					if ($reputation_details['reverse_id'])
					{
						$auction_link = process_link('reverse_details', array('reverse_id' => $reputation_details['reverse_id']));
						$auction_id = $reputation_details['reverse_id'];	
						$rep_link = '[ <a href="members_area.php?page=reputation&section=post&reputation_ids=' . $reputation_details['reputation_id'] . '">' . MSG_LEAVE_COMMENTS . '</a> ]';
					}
					else 
					{
						$auction_link = process_link('auction_details', array('auction_id' => $reputation_details['auction_id']));
						$auction_id = $reputation_details['auction_id'];	
						$rep_link = '<input name="reputation_id[]" type="checkbox" id="reputation_id[]" value="' . $reputation_details['reputation_id'] . '" class="checkreputation">';
					}
					$reputation_sent_content .= '<tr class="' . $background . ' contentfont"> '.
						'	<td align="center">' . $reputation_details['username'] . '</td> '.
						'	<td align="center"><a href="' . $auction_link . '">' . $auction_id . '</a></td> '.
						'	<td><a href="' . $auction_link . '">' . $reputation_details['auction_name'] . '</a></td>'.
						'	<td align="center">' . $reputation->reputation_type($reputation_details) . '</td> '.
						'	<td align="center" class="smallfont">' . $rep_link . '</td>'.
						'</tr>';
				}				
			}
			else
			{
				$reputation_sent_content = '<tr><td colspan="5" align="center">' . GMSG_NO_ITEMS_MSG . '</td></tr>';
			}
			$template->set('nb_auction_items', $nb_auction_items);

			$template->set('reputation_sent_content', $reputation_sent_content);

			$pagination = paginate($start, $limit, $nb_items, 'members_area.php', $additional_vars . $order_link);
			$template->set('pagination', $pagination);

			$members_area_page_content = $template->process('members_area_reputation_sent.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);
		}
	} /* END -> REPUTATION PAGES */
	
	if ($page == 'bulk') /* BEGIN -> BULK PAGE(S) */
	{
		if ($section == 'details')
		{
			$members_area_page_content = $template->process('members_area_bulk_details.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);
		}
	} /* END -> BULK PAGE(S) */
	
	if ($page == 'about_me') /* BEGIN -> ABOUT ME PAGE(S) */
	{
		if ($section == 'view')	{

			if (!empty($_REQUEST['user_id'])) {
		        $userId = $_REQUEST['user_id'];
			} else {
                $userId = $session->value('user_id');
            }

            if (empty($userId)) {
                header_redirect('login.php');
            }

			$user_details = $db->get_sql_row("SELECT * FROM bl2_users WHERE id=" . $userId);

            if (empty($user_details)) {
                header_redirect('login.php');
            }

			$template->set('user_details', $user_details);

			$members_area_page_content = $template->process('members_area_aboutme_view.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);
		}
		if ($section == 'edit')
		{
            include_once("includes/class_about_me.php");
		}
	} /* END -> ABOUT ME PAGE(S) */
	
	if ($page == 'store') /* BEGIN -> STORE SETUP PAGES */
	{
		$shop = new shop();
		$shop->setts = &$setts;
		$shop->user_id = $session->value('user_id');
		
		if ($section == 'subscription')
		{
			$show_page = true;
			if (isset($_POST['form_shop_save']))
			{
				define ('FRMCHK_STORE_SETUP', 1);
				$frmchk_details = $db->get_sql_row("SELECT * FROM
					" . DB_PREFIX . "users WHERE user_id=" . $session->value('user_id'));

				$frmchk_store_settings = true;

				include ('includes/procedure_frmchk_store_setup.php'); /* Formchecker for store setup pages */
	
				if ($fv->is_error())
				{
					$template->set('display_formcheck_errors', $fv->display_errors());
	
				}
				else 
				{
					$subscription_output = $shop->shop_save_subscription($_POST, $session->value('user_id'));
					
					$template->set('msg_changes_saved', $subscription_output['display']);
					
					$show_page = $subscription_output['show_page'];
				}
			}
			
			if ($show_page)
			{
				$user_details = $db->get_sql_row("SELECT * FROM
					" . DB_PREFIX . "users WHERE user_id=" . $session->value('user_id'));

				$template->set('user_details', $user_details);
	
				$shop_status = $shop->shop_status($user_details, true);
				$template->set('shop_status', $shop_status);
				
				$template->set('list_store_subscriptions', $shop->store_subscriptions_drop_down('shop_account_id', $user_details['shop_account_id']));
	
				$members_area_page_content = $template->process('members_area_store_subscription.tpl.php');
				$template->set('members_area_page_content', $members_area_page_content);
			}
		}
		
		if ($section == 'setup')
		{

			if (isset($_POST['form_shop_save']))
			{
				
				define ('FRMCHK_STORE_SETUP', 1);
				$frmchk_details = $_POST;
				$frmchk_store_settings = true;

				include ('includes/procedure_frmchk_store_setup.php'); /* Formchecker for store setup pages */
	
				if ($fv->is_error())
				{
					$template->set('display_formcheck_errors', $fv->display_errors());
	
				}
				else 
				{
					$template->set('msg_changes_saved', $msg_changes_saved);
					$shop->shop_save_settings($_POST, $session->value('user_id'));
				}
				
				$user_details = $_POST;
			}
			else 
			{
				$user_details = $db->get_sql_row("SELECT * FROM
					" . DB_PREFIX . "users WHERE user_id=" . $session->value('user_id'));
			}

			$item->setts['max_images'] = 1;

			$post_details = $_POST;
			
			if ($_POST['box_submit'] == 1)
			{
				$user_details = $post_details;
			}
			
			$post_details['auction_id'] = 'store_logo_' . $session->value('user_id');

			$post_details['ad_image'][0] = (!empty($_POST['ad_image'][0])) ? $_POST['ad_image'][0] : $user_details['shop_logo_path'];

			if (empty($_POST['file_upload_type']))
			{
				$template->set('media_upload_fields', $item->upload_manager($post_details));
			}
			else if (is_numeric($_POST['file_upload_id'])) /* means we remove a file / media url */
			{
				$media_upload = $item->media_removal($post_details, $post_details['file_upload_type'], $post_details['file_upload_id'], false);
				$media_upload_fields = $media_upload['display_output'];
		
				$post_details['ad_image'] = $media_upload['post_details']['ad_image'];

				$db->query("UPDATE " . DB_PREFIX . "users SET shop_logo_path='' WHERE user_id='" . $session->value('user_id') . "'");
				
				$template->set('media_upload_fields', $media_upload_fields);
			}
			else /* means we have a file upload */
			{
				$media_upload = $item->media_upload($post_details, $post_details['file_upload_type'], $_FILES, false);
				$media_upload_fields = $media_upload['display_output'];
						
				$post_details['ad_image'] = $media_upload['post_details']['ad_image'];
		
				$db->query("UPDATE " . DB_PREFIX . "users SET shop_logo_path='" . $post_details['ad_image'][0] . "' WHERE user_id='" . $session->value('user_id') . "'");
				
				$template->set('media_upload_fields', $media_upload_fields);
			}

			$template->set('user_details', $db->rem_special_chars_array($user_details));

			$image_upload_manager = $item->upload_manager($post_details, 1, 'form_store_setup', true, true, false);
			$template->set('image_upload_manager', $image_upload_manager);

			$template->set('store_templates_drop_down', $shop->store_templates_drop_down('shop_template_id', $user_details['shop_template_id']));

			$members_area_page_content = $template->process('members_area_store_setup.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);
		}
		if ($section == 'postage')
		{
			if (isset($_POST['form_postage_save']))
			{
				
				define ('FRMCHK_STORE_POSTAGE_SETUP', 1);
				$frmchk_details = $_POST;
				$frmchk_store_settings = true;

				include ('includes/procedure_frmchk_store_postage_setup.php'); /* Formchecker for store setup pages */
	
				if ($fv->is_error())
				{
					$template->set('display_formcheck_errors', $fv->display_errors());
	
				}
				else 
				{
					$template->set('msg_changes_saved', $msg_changes_saved);
					$shop->shop_postage_save($_POST, $session->value('user_id'));
				}				
			}
			else 
			{
				$postage_details = $db->get_sql_row("SELECT * FROM
					" . DB_PREFIX . "users WHERE user_id=" . $session->value('user_id'));
			}

			if ($_POST['box_submit'] == 1)
			{
				$postage_details = $_POST;
			}
			$postage_details['shop_direct_payment'] = (count($_POST['payment_gateway'])) ? $db->implode_array($_POST['payment_gateway']) : $postage_details['shop_direct_payment'];

			$tax = new tax();
			$can_add_tax = $tax->can_add_tax($session->value('user_id'), $setts['enable_tax']);
			$template->set('can_add_tax', $can_add_tax['can_add_tax']);

			$template->set('postage_details', $db->rem_special_chars_array($postage_details));

			$direct_payments = $item->select_direct_payment($postage_details['shop_direct_payment'], $session->value('user_id'));
	
			$direct_payment_table = $template->generate_table($direct_payments, 4, 1, 1, '75%');
			$template->set('direct_payment_table', $direct_payment_table);
			
			$members_area_page_content = $template->process('members_area_store_postage_setup.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);
		}

		
		if ($section == 'store_pages')
		{
			if (isset($_POST['form_shop_save']))
			{
				
				define ('FRMCHK_STORE_SETUP', 1);
				$frmchk_details = $_POST;
				$frmchk_store_pages = true;

				include ('includes/procedure_frmchk_store_setup.php'); /* Formchecker for store setup pages */
	
				if ($fv->is_error())
				{
					$template->set('display_formcheck_errors', $fv->display_errors());
	
				}
				else 
				{
					$template->set('msg_changes_saved', $msg_changes_saved);
					$shop->shop_save_pages($_POST, $session->value('user_id'));
				}
				
				$user_details = $db->rem_special_chars_array($_POST);
			}
			else 
			{
				$user_details = $db->get_sql_row("SELECT * FROM
					" . DB_PREFIX . "users WHERE user_id=" . $session->value('user_id'));
			}

			$template->set('user_details', $user_details);

			$members_area_page_content = $template->process('members_area_store_pages.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);
		}
		
		if ($section == 'categories')
		{
			$parent_id = intval($_REQUEST['parent_id']);
		
			if (isset($_POST['form_save_settings']))
			{
				$session->set('category_language', 1);
		
				$template->set('msg_changes_saved', $msg_changes_saved);

				if (count($_POST['category_id']) > 0)
				{
					foreach ($_POST['category_id'] as $key => $value)
					{
						$order_id = intval($_POST['order_id'][$key]);
						
						$order_id = ($order_id>=0 && $order_id<10000) ? $order_id : 10000;
			
						$sql_update_categories = $db->query("UPDATE " . DB_PREFIX . "categories SET
							name='" . $db->rem_special_chars($_POST['name'][$key]) . "', order_id=" . $order_id . " WHERE
							category_id=" . $value . " AND user_id='" . $session->value('user_id') . "'");
					}
				}
			
				if (count($_POST['delete'])>0)
				{
					$delete_array = $db->implode_array($_POST['delete']);
		
					$sql_delete_categories = $db->query("DELETE FROM " . DB_PREFIX . "categories WHERE category_id IN (" . $delete_array . ")");## PHP Pro Bid v6.00 all the subcategories need to be deleted as well.
				}
		
				foreach ($_POST['add_name'] as $value)
				{
					if (!empty($value))
					{
						$sql_insert_category = $db->query("INSERT INTO " . DB_PREFIX . "categories
							(name, parent_id, user_id) VALUES ('" . $db->rem_special_chars($value) . "', " . $parent_id . ", '" . $session->value('user_id') . "')");
					}
				}
			}

			if (isset($_POST['form_generate_subcategories']) || $_REQUEST['generate_subcategories'] == 1)
			{
				(array) $subcat_ids_array = NULL;
		
				$template->set('msg_changes_saved', '<p align="center">' . MSG_CATEGORIES_GENERATED . '</p>');
		
				$sql_reset_subcategories = $db->query("UPDATE " . DB_PREFIX . "categories SET is_subcat=''");
		
				$sql_select_subcategories = $db->query("SELECT parent_id FROM " . DB_PREFIX . "categories WHERE
					parent_id>0");
		
				while ($subcat_details = $db->fetch_array($sql_select_subcategories))
				{
					$subcat_ids_array[] = $subcat_details['parent_id'];
				}
		
				if (count($subcat_ids_array) > 0)
				{
					$subcat_ids = $db->implode_array($subcat_ids_array);
		
					$sql_update_subcategories = $db->query("UPDATE " . DB_PREFIX . "categories SET
						is_subcat='>' WHERE category_id IN (" . $subcat_ids . ")");
				}## PHP Pro Bid v6.00 here we delete all subcategories that have no parent anymore		
				$delete_subcats = 1;
				while ($delete_subcats) 
				{
					(array) $subcategory = null;
					
					$sql_select_obsolete_cats = $db->query_silent("SELECT c.category_id FROM " . DB_PREFIX . "categories c WHERE 
						(SELECT count(*) FROM " . DB_PREFIX . "categories cc WHERE cc.category_id=c.parent_id)=0 AND c.parent_id!=0");
					
					$delete_subcats = 0;
					
					if ($sql_select_obsolete_cats)
					{
						while ($subcat_details = $db->fetch_array($sql_select_obsolete_cats)) 
						{
							$delete_subcats = 1;
							$subcategory[] = $subcat_details['category_id'];
						}
						
						if ($delete_subcats)
						{
							$delete_array = $db->implode_array($subcategory);
							$db->query("DELETE FROM " . DB_PREFIX . "categories WHERE category_id IN (" . $delete_array . ")");
						}
					}
					else 
					{
						## delete obsolete cats using the old slower version 
						$sql_select_subcats = $db->query("SELECT * FROM " . DB_PREFIX . "categories WHERE parent_id!=0");
							
						while ($subcat_details = $db->fetch_array($sql_select_subcats)) 
						{
							$is_maincat = $db->count_rows('categories', "WHERE category_id='" . $subcat_details['parent_id'] . "'");
							
							if ($is_maincat == 0) 
							{
								$db->query("DELETE FROM " . DB_PREFIX . "categories WHERE parent_id='" . $subcat_details['parent_id'] . "'");
							}
						}
					}
				}				
			}
						
			if (isset($_POST['form_shop_save']))
			{
				$template->set('msg_changes_saved', $msg_changes_saved);
				$db->query("UPDATE " . DB_PREFIX . "users SET shop_categories = '" . $db->implode_array($_POST['categories_id']) . "' WHERE 
					user_id='" . $session->value('user_id') . "'");
				
				$user_details = $_POST;
			}
			else 
			{
				$user_details = $db->get_sql_row("SELECT * FROM
					" . DB_PREFIX . "users WHERE user_id=" . $session->value('user_id'));
			}
			
			$template->set('parent_id', $parent_id);
			
			$template->set('category_navigator', category_navigator($parent_id, true, true, 'members_area.php', 'page=store&section=categories'));
		
			(string) $categories_page_content = NULL;
			(string) $add_category_content = NULL;
		
			$sql_select_categories = $db->query("SELECT category_id, name, parent_id, order_id, hidden, custom_fees, user_id, is_subcat FROM
				" . DB_PREFIX . "categories WHERE parent_id=" . $parent_id . " AND user_id=" . $session->value('user_id') . " ORDER BY order_id ASC, name ASC");

            $counter= 0 ;
			while ($category_details = $db->fetch_array($sql_select_categories))
			{
				$background = ($counter++%2) ? 'c1' : 'c2';
				$background_border = (!empty($category_details['is_subcat'])) ? 'grey' : $background;
		
				$order_value = ($category_details['order_id']>0 && $category_details['order_id']<1000) ? $category_details['order_id'] : '';
		
				$categories_page_content .= '<tr class="' . $background . '"> '.
					'	<td class="' . $background_border . '"><a href="members_area.php?page=store&section=categories&parent_id=' . $category_details['category_id'] . '"> '.
					'		<img src="images/catplus.gif" alt="' . AMSG_VIEW_SUBCATEGORIES . '" width="20" height="20" border="0"></a></td> '.
					'	<td><input name="name[]" type="text" id="name[]" value="' . $category_details['name'] . '" style="width:65%"> ' ;
		
				$categories_page_content .= '<input type="hidden" name="category_id[]" value="' . $category_details['category_id'] . '"> ';
				
				$categories_page_content .= '</td> '.
					'<td align="center"> '.
					'	<input name="order_id[]" type="text" id="order_id[]" value="' . $order_value . '" size="8"></td> ';
		
				$categories_page_content .= '<td align="center"><input name="delete[]" type="checkbox" id="delete[]" value="' . $category_details['category_id'] . '"></td> '.
		         '</tr> ';
		
			}
		
			(int) $add_cats_counter = 1;
		
			$add_category_content = '<tr class="c1"> '.
				'	<td>&nbsp;</td> '.
				'	<td> ';
		
			for ($i=0; $i<$add_cats_counter; $i++)
			{
				$add_category_content .= '<input name="add_name[]" type="text" id="add_name[]"><br> ';
			}
		
			$add_category_content .='</td>' .
				'	<td align="center">&nbsp;</td> ';
				
			$add_category_content .= '<td align="center">&nbsp;</td> '.
				'</tr> ';
		
			$template->set('categories_page_content', $categories_page_content);
			$template->set('add_category_content', $add_category_content);
			
			(string) $all_categories_table = null;
			(string) $selected_categories_table = null;
	
			$selected_categories = (!empty($user_details['shop_categories'])) ? $user_details['shop_categories'] : 0;
			$selected_categories = (is_array($_POST['categories_id'])) ? $db->implode_array($_POST['categories_id']) : $selected_categories;

			$selected_categories = last_char($selected_categories);
			
			$sql_select_all_categories = $db->query("SELECT category_id, name FROM " . DB_PREFIX . "categories WHERE
				parent_id=0 AND user_id IN (0, " . $session->value('user_id') . ") AND category_id NOT IN (" . $selected_categories . ") ORDER BY order_id ASC, name ASC");
	
			$all_categories_table = '<select name="all_categories" size="15" multiple="multiple" id="all_categories" style="width: 100%;">';
	
			while ($all_categories_details = $db->fetch_array($sql_select_all_categories))
			{
				$all_categories_table .= '<option value="' . $all_categories_details['category_id'] . '">' . $all_categories_details['name'] . '</option>';
			}
	
			$all_categories_table .= '</select>';
	
			$sql_select_selected_categories = $db->query("SELECT category_id, name FROM " . DB_PREFIX . "categories WHERE
				parent_id=0 AND user_id IN (0, " . $session->value('user_id') . ") AND category_id IN (" . $selected_categories . ") ORDER BY order_id ASC, name ASC");
	
			$selected_categories_table ='<select name="categories_id[]" size="15" multiple="multiple" id="categories_id" style="width: 100%;"> ';
	
			while ($selected_categories_details = $db->fetch_array($sql_select_selected_categories))
			{
				$selected_categories_table .= '<option value="' . $selected_categories_details['category_id'] . '" selected>' . $selected_categories_details['name'] . '</option>';
			}
	
			$selected_categories_table .= '</select>';
	
			$template->set('all_categories_table', $all_categories_table);
			$template->set('selected_categories_table', $selected_categories_table);

			$template->set('user_details', $user_details);

			$members_area_page_content = $template->process('members_area_store_categories.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);
		}
	} /* END -> STORE SETUP PAGES */
	
	if ($page == 'wanted_ads') /* BEGIN -> WANTED ADS PAGE(S) */
	{
		if ($_REQUEST['do'] == 'closed_wa_proceed')
		{
			$nb_relists = $item->count_contents($_REQUEST['relist']);
			$nb_deletions = $item->count_contents($_REQUEST['delete']);

			if ($nb_relists > 0)
			{
				for ($i=0; $i<$nb_relists; $i++)
				{
					$relist_id = $_REQUEST['relist'][$i];
					$relist_result = $item->relist_wanted_ad($relist_id, $session->value('user_id'), $_REQUEST['duration'][$relist_id]);
					$relist_output[] = $relist_result['display'];
				}

				$template->set('msg_auction_relist', '<p align="center">' . $db->implode_array($relist_output, '<br>') . '</p>');
			}

			if ($nb_deletions > 0)
			{
				$item->delete_wanted_ad($db->implode_array($_REQUEST['delete']), $session->value('user_id'));
			}
		}

		if ($section == 'new')
		{
			header_redirect(SITE_PATH . 'wanted_manage.php');
		}

		if ($_REQUEST['do'] == 'delete_wanted_ad')
		{
			$item->delete_wanted_ad($_REQUEST['wanted_ad_id'], $session->value('user_id'));
		}

		if ($section == 'open')
		{
			$nb_items = $db->count_rows('wanted_ads', "WHERE owner_id='" . $session->value('user_id') . "' AND
				closed=0 AND deleted=0 AND creation_in_progress=0");

			$template->set('nb_items', $nb_items);

			$template->set('page_order_wanted_ad_id', page_order('members_area.php', 'w.wanted_ad_id', $start, $limit, $additional_vars, MSG_WANTED_AD_ID));
			$template->set('page_order_itemname', page_order('members_area.php', 'w.name', $start, $limit, $additional_vars, MSG_ITEM_TITLE));
			$template->set('page_order_start_time', page_order('members_area.php', 'w.start_time', $start, $limit, $additional_vars, GMSG_START_TIME));
			$template->set('page_order_end_time', page_order('members_area.php', 'w.end_time', $start, $limit, $additional_vars, GMSG_END_TIME));

			if ($nb_items)
			{
				$sql_select_items = $db->query("SELECT w.* FROM " . DB_PREFIX . "wanted_ads w
					WHERE w.owner_id='" . $session->value('user_id') . "' AND w.closed=0 AND w.deleted=0 AND w.creation_in_progress=0
					ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit);

                $counter= 0 ;
				while ($item_details = $db->fetch_array($sql_select_items))
				{
					$background = ($counter++%2) ? 'c1' : 'c2';

					(string) $content_options = null;
					if ($item_details['payment_status']!='confirmed' && $item_details['active']==0)
					{
						$content_options .= '<a href="fee_payment.php?do=wa_setup_fee_payment&wanted_ad_id=' . $item_details['wanted_ad_id'] . '">' . MSG_PAY_WA_SETUP_FEE . '</a>';
					}
					else
					{
						$content_options .= '<a href="' . process_link('wanted_manage', array('do' => 'edit', 'wanted_ad_id' => $item_details['wanted_ad_id'], 'edit_option' => 'new')) . '">' . MSG_EDIT_WANTED_AD . '</a><br> ';

						$content_options .= '<a href="members_area.php?do=delete_wanted_ad&wanted_ad_id=' . $item_details['wanted_ad_id'] . $additional_vars . $limit_link . $order_link . '" onclick="return confirm(\'' . MSG_DELETE_CONFIRM . '\');">' . MSG_DELETE . '</a>';
					}

					$open_wanted_ads_content .= '<tr class="' . $background . '"> '.
						'	<td class="contentfont"><a href="' . process_link('wanted_details', array('wanted_ad_id' => $item_details['wanted_ad_id'])) . '"># ' . $item_details['wanted_ad_id'] . '</a></td> '.
						'	<td class="contentfont"><a href="' . process_link('wanted_details', array('wanted_ad_id' => $item_details['wanted_ad_id'])) . '">' . $item_details['name'] . '</a></td>'.
						'	<td align="center">' . show_date($item_details['start_time'], false) . '</td> '.
						'	<td align="center">' . show_date($item_details['end_time'], false) . '</td>'.
						'	<td align="center" class="smallfont">' . $content_options . '</td>'.
						'</tr>';
				}
			}
			else
			{
				$open_wanted_ads_content = '<tr><td colspan="8" align="center">' . GMSG_NO_ITEMS_MSG . '</td></tr>';
			}

			$template->set('open_wanted_ads_content', $open_wanted_ads_content);

			$pagination = paginate($start, $limit, $nb_items, 'members_area.php', $additional_vars . $order_link);
			$template->set('pagination', $pagination);

			$members_area_page_content = $template->process('members_area_wanted_ads_open.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);
		}

		if ($section == 'closed')
		{
			$nb_items = $db->count_rows('wanted_ads', "WHERE owner_id='" . $session->value('user_id') . "' AND
				closed=1 AND deleted=0 AND end_time<='" . CURRENT_TIME . "' AND creation_in_progress=0");

			$template->set('nb_items', $nb_items);

			$template->set('page_order_wanted_ad_id', page_order('members_area.php', 'w.wanted_ad_id', $start, $limit, $additional_vars, MSG_WANTED_AD_ID));
			$template->set('page_order_itemname', page_order('members_area.php', 'w.name', $start, $limit, $additional_vars, MSG_ITEM_TITLE));
			$template->set('page_order_start_time', page_order('members_area.php', 'w.start_time', $start, $limit, $additional_vars, GMSG_START_TIME));
			$template->set('page_order_end_time', page_order('members_area.php', 'w.end_time', $start, $limit, $additional_vars, GMSG_END_TIME));
			$template->set('page_order_nb_bids', page_order('members_area.php', 'w.nb_bids', $start, $limit, $additional_vars, GMSG_OFFERS));

			if ($nb_items)
			{
				$sql_select_items = $db->query("SELECT w.* FROM " . DB_PREFIX . "wanted_ads w
					WHERE w.owner_id='" . $session->value('user_id') . "' AND w.closed=1 AND w.deleted=0
					AND w.end_time<='" . CURRENT_TIME . "'AND w.creation_in_progress=0
					ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit);

                $counter= 0 ;
				while ($item_details = $db->fetch_array($sql_select_items))
				{
					$background = ($counter++%2) ? 'c1' : 'c2';

					$closed_wanted_ads_content .= '<tr class="' . $background . '"> '.
						'	<td class="contentfont"><a href="' . process_link('wanted_details', array('wanted_ad_id' => $item_details['wanted_ad_id'])) . '"># ' . $item_details['wanted_ad_id'] . '</a></td> '.
						'	<td class="contentfont"><a href="' . process_link('wanted_details', array('wanted_ad_id' => $item_details['wanted_ad_id'])) . '">' . $item_details['name'] . '</a></td>'.
						'	<td align="center">' . show_date($item_details['start_time'], false) . '</td> '.
						'	<td align="center">' . show_date($item_details['end_time'], false) . '</td>'.
						'	<td align="center">' . field_display($item_details['nb_bids'], '-', $item_details['nb_bids']) . '</td>'.
						'	<td align="center"><input name="relist[]" type="checkbox" id="relist[]" value="' . $item_details['wanted_ad_id'] . '" class="checkrelist"> '.
						'		' . $item->durations_drop_down('duration[' . $item_details['wanted_ad_id'] . ']', $item_details['duration']) . '</td>'.
						'	<td align="center" class="smallfont"><input name="delete[]" type="checkbox" id="delete[]" value="' . $item_details['wanted_ad_id'] . '" class="checkdelete"></td>'.
						'</tr>';
				}
			}
			else
			{
				$closed_wanted_ads_content = '<tr><td colspan="8" align="center">' . GMSG_NO_ITEMS_MSG . '</td></tr>';
			}

			$template->set('closed_wanted_ads_content', $closed_wanted_ads_content);

			$pagination = paginate($start, $limit, $nb_items, 'members_area.php', $additional_vars . $order_link);
			$template->set('pagination', $pagination);

			$members_area_page_content = $template->process('members_area_wanted_ads_closed.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);
		}
	} /* END -> WANTED ADS PAGE(S) */
	
	if ($page == 'reverse') /* BEGIN -> REVERSE AUCTIONS PAGE(S) */
	{
		if ($_REQUEST['do'] == 'closed_reverse_proceed')
		{
			$nb_relists = $item->count_contents($_REQUEST['relist']);
			$nb_deletions = $item->count_contents($_REQUEST['delete']);

			if ($nb_relists > 0)
			{
				for ($i=0; $i<$nb_relists; $i++)
				{
					$relist_id = intval($_REQUEST['relist'][$i]);
					$relist_result = $item->relist_reverse($relist_id, $session->value('user_id'), intval($_REQUEST['duration'][$relist_id]));
					$relist_output[] = $relist_result['display'];
				}

				$template->set('msg_auction_relist', '<p align="center">' . $db->implode_array($relist_output, '<br>') . '</p>');
			}

			if ($nb_deletions > 0)
			{
				$item->delete_reverse($db->implode_array($_REQUEST['delete']), $session->value('user_id'));
			}
		}

		if ($section == 'new_auction')
		{
			header_redirect(SITE_PATH . 'reverse_manage.php');
		}

		if ($_REQUEST['do'] == 'delete_reverse')
		{
			$item->delete_reverse(intval($_REQUEST['reverse_id']), $session->value('user_id'));
		}

		if ($section == 'open')
		{
			$nb_items = $db->count_rows('reverse_auctions', "WHERE owner_id='" . $session->value('user_id') . "' AND
				closed=0 AND deleted=0 AND creation_in_progress=0");

			$template->set('nb_items', $nb_items);

			$template->set('page_order_reverse_id', page_order('members_area.php', 'r.reverse_id', $start, $limit, $additional_vars, MSG_AUCTION_ID));
			$template->set('page_order_itemname', page_order('members_area.php', 'r.name', $start, $limit, $additional_vars, MSG_ITEM_TITLE));
			$template->set('page_order_start_time', page_order('members_area.php', 'r.start_time', $start, $limit, $additional_vars, GMSG_START_TIME));
			$template->set('page_order_end_time', page_order('members_area.php', 'r.end_time', $start, $limit, $additional_vars, GMSG_END_TIME));

			if ($nb_items)
			{
				$sql_select_items = $db->query("SELECT r.* FROM " . DB_PREFIX . "reverse_auctions r
					WHERE r.owner_id='" . $session->value('user_id') . "' AND r.closed=0 AND r.deleted=0 AND r.creation_in_progress=0
					ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit);

                $counter= 0 ;
				while ($item_details = $db->fetch_array($sql_select_items))
				{
					$background = ($counter++%2) ? 'c1' : 'c2';

					(string) $content_options = null;
					if ($item_details['payment_status']!='confirmed' && $item_details['active']==0)
					{
						$content_options .= '<a href="fee_payment.php?do=reverse_setup_fee_payment&reverse_id=' . $item_details['reverse_id'] . '">' . MSG_PAY_REVERSE_SETUP_FEE . '</a>';
					}
					else
					{
						$content_options .= '<a href="' . process_link('reverse_manage', array('do' => 'edit', 'reverse_id' => $item_details['reverse_id'], 'edit_option' => 'new')) . '">' . MSG_EDIT_AUCTION . '</a><br> ';

						$content_options .= '<a href="members_area.php?do=delete_reverse&reverse_id=' . $item_details['reverse_id'] . $additional_vars . $limit_link . $order_link . '" onclick="return confirm(\'' . MSG_DELETE_CONFIRM . '\');">' . MSG_DELETE . '</a>';
					}

					$open_reverse_content .= '<tr class="' . $background . '"> '.
						'	<td class="contentfont"><a href="' . process_link('reverse_details', array('reverse_id' => $item_details['reverse_id'])) . '"># ' . $item_details['reverse_id'] . '</a></td> '.
						'	<td class="contentfont"><a href="' . process_link('reverse_details', array('reverse_id' => $item_details['reverse_id'])) . '">' . $item_details['name'] . '</a></td>'.
						'	<td align="center">' . field_display($item_details['nb_bids'], '-', $item_details['nb_bids']) . '</td>'.
						'	<td align="center">' . show_date($item_details['start_time'], false) . '</td> '.
						'	<td align="center">' . show_date($item_details['end_time'], false) . '</td>'.
						'	<td align="center" class="smallfont">' . $content_options . '</td>'.
						'</tr>';
				}
			}
			else
			{
				$open_reverse_content = '<tr><td colspan="8" align="center">' . GMSG_NO_ITEMS_MSG . '</td></tr>';
			}

			$template->set('open_reverse_content', $open_reverse_content);

			$pagination = paginate($start, $limit, $nb_items, 'members_area.php', $additional_vars . $order_link);
			$template->set('pagination', $pagination);

			$members_area_page_content = $template->process('members_area_reverse_open.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);
		}

		if ($section == 'closed')
		{
			$nb_items = $db->count_rows('reverse_auctions', "WHERE owner_id='" . $session->value('user_id') . "' AND
				closed=1 AND deleted=0 AND end_time<='" . CURRENT_TIME . "' AND creation_in_progress=0");

			$template->set('nb_items', $nb_items);

			$template->set('page_order_reverse_id', page_order('members_area.php', 'r.reverse_id', $start, $limit, $additional_vars, MSG_AUCTION_ID));
			$template->set('page_order_itemname', page_order('members_area.php', 'r.name', $start, $limit, $additional_vars, MSG_ITEM_TITLE));
			$template->set('page_order_start_time', page_order('members_area.php', 'r.start_time', $start, $limit, $additional_vars, GMSG_START_TIME));
			$template->set('page_order_end_time', page_order('members_area.php', 'r.end_time', $start, $limit, $additional_vars, GMSG_END_TIME));
			$template->set('page_order_nb_bids', page_order('members_area.php', 'r.nb_bids', $start, $limit, $additional_vars, GMSG_OFFERS));

			if ($nb_items)
			{
				$sql_select_items = $db->query("SELECT r.* FROM " . DB_PREFIX . "reverse_auctions r
					WHERE r.owner_id='" . $session->value('user_id') . "' AND r.closed=1 AND r.deleted=0
					AND r.end_time<='" . CURRENT_TIME . "'AND r.creation_in_progress=0
					ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit);

                $counter= 0 ;
				while ($item_details = $db->fetch_array($sql_select_items))
				{
					$background = ($counter++%2) ? 'c1' : 'c2';

					$closed_reverse_content .= '<tr class="' . $background . '"> '.
						'	<td class="contentfont"><a href="' . process_link('reverse_details', array('reverse_id' => $item_details['reverse_id'])) . '"># ' . $item_details['reverse_id'] . '</a></td> '.
						'	<td class="contentfont"><a href="' . process_link('reverse_details', array('reverse_id' => $item_details['reverse_id'])) . '">' . $item_details['name'] . '</a></td>'.
						'	<td align="center">' . show_date($item_details['start_time'], false) . '</td> '.
						'	<td align="center">' . show_date($item_details['end_time'], false) . '</td>'.
						'	<td align="center">' . field_display($item_details['nb_bids'], '-', $item_details['nb_bids']) . '</td>'.
						'	<td align="center"><input name="relist[]" type="checkbox" id="relist[]" value="' . $item_details['reverse_id'] . '" class="checkrelist"> '.
						'		' . $item->durations_drop_down('duration[' . $item_details['reverse_id'] . ']', $item_details['duration']) . '</td>'.
						'	<td align="center" class="smallfont"><input name="delete[]" type="checkbox" id="delete[]" value="' . $item_details['reverse_id'] . '" class="checkdelete"></td>'.
						'</tr>';
				}
			}
			else
			{
				$closed_reverse_content = '<tr><td colspan="8" align="center">' . GMSG_NO_ITEMS_MSG . '</td></tr>';
			}

			$template->set('closed_reverse_content', $closed_reverse_content);

			$pagination = paginate($start, $limit, $nb_items, 'members_area.php', $additional_vars . $order_link);
			$template->set('pagination', $pagination);

			$members_area_page_content = $template->process('members_area_reverse_closed.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);
		}
		
		if ($section == 'scheduled')
		{
			$nb_items = $db->count_rows('reverse_auctions', "WHERE closed=1 AND owner_id='" . $session->value('user_id') . "' AND
			deleted=0 AND creation_in_progress=0 AND end_time>='" . CURRENT_TIME . "'");

			$template->set('nb_items', $nb_items);

			$template->set('page_order_auction_id', page_order('members_area.php', 'r.reverse_id', $start, $limit, $additional_vars, MSG_AUCTION_ID));
			$template->set('page_order_itemname', page_order('members_area.php', 'r.name', $start, $limit, $additional_vars, MSG_ITEM_TITLE));
			$template->set('page_order_start_time', page_order('members_area.php', 'r.start_time', $start, $limit, $additional_vars, GMSG_START_TIME));
			$template->set('page_order_end_time', page_order('members_area.php', 'r.end_time', $start, $limit, $additional_vars, GMSG_END_TIME));

			if ($nb_items)
			{
				$force_index = $item->force_index($order_field, true);

				$sql_select_items = $db->query("SELECT r.*, u.username FROM " . DB_PREFIX . "reverse_auctions r
					" . $force_index . "
					LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=r.owner_id
					WHERE r.owner_id='" . $session->value('user_id') . "' AND r.closed=1 AND r.deleted=0 AND
					r.end_time>'" . CURRENT_TIME . "' AND r.creation_in_progress=0 
					GROUP BY r.reverse_id 
					ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit); 

                $counter= 0 ;
				while ($item_details = $db->fetch_array($sql_select_items))
				{
					$background = ($counter++%2) ? 'c1' : 'c2';

					(string) $content_options = null;
					if ($item_details['payment_status']!='confirmed' && $item_details['active']==0)
					{
						$content_options .= '<a href="fee_payment.php?do=reverse_setup_fee_payment&reverse_id=' . $item_details['reverse_id'] . '">' . MSG_PAY_REVERSE_SETUP_FEE . '</a>';
					}
					else
					{
						$content_options .= '<a href="' . process_link('reverse_manage', array('do' => 'edit', 'reverse_id' => $item_details['reverse_id'], 'edit_option' => 'new')) . '">' . MSG_EDIT_AUCTION . '</a><br> ';

						$content_options .= '<a href="members_area.php?do=delete_reverse&reverse_id=' . $item_details['reverse_id'] . $additional_vars . $limit_link . $order_link . '" onclick="return confirm(\'' . MSG_DELETE_CONFIRM . '\');">' . MSG_DELETE . '</a>';
					}
					
					$scheduled_reverse_content .= '<tr class="' . $background . '"> '.
						'	<td class="contentfont"><a href="' . process_link('reverse_details', array('reverse_id' => $item_details['reverse_id'])) . '"># ' . $item_details['reverse_id'] . '</a></td> '.
						'	<td class="contentfont"><a href="' . process_link('reverse_details', array('reverse_id' => $item_details['reverse_id'])) . '">' . $item_details['name'] . '</a></td>'.
						'	<td align="center">' . show_date($item_details['start_time']) . '</td> '.
						'	<td align="center">' . show_date($item_details['end_time']) . '</td>'.
						'	<td align="center" class="smallfont">' . $content_options . '</td>'.
						'</tr>';
				}
			}
			else
			{
				$scheduled_reverse_content = '<tr><td colspan="8" align="center">' . GMSG_NO_ITEMS_MSG . '</td></tr>';
			}

			$template->set('scheduled_reverse_content', $scheduled_reverse_content);

			$pagination = paginate($start, $limit, $nb_items, 'members_area.php', $additional_vars . $order_link);
			$template->set('pagination', $pagination);

			$members_area_page_content = $template->process('members_area_reverse_scheduled.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);

		}		

		if ($section == 'awarded')
		{
			$nb_items = $db->count_rows('reverse_winners w', "WHERE w.poster_id='" . $session->value('user_id') . "' AND
				w.b_deleted=0");

			$template->set('nb_items', $nb_items);

			$template->set('page_order_auction_id', page_order('members_area.php', 'r.reverse_id', $start, $limit, $additional_vars . $show_link, MSG_AUCTION_ID));
			$template->set('page_order_itemname', page_order('members_area.php', 'r.name', $start, $limit, $additional_vars . $show_link, MSG_ITEM_TITLE));
			$template->set('page_order_bid_amount', page_order('members_area.php', 'w.bid_amount', $start, $limit, $additional_vars . $show_link, MSG_WINNING_BID));
			$template->set('page_order_purchase_date', page_order('members_area.php', 'w.purchase_date', $start, $limit, $additional_vars . $show_link, MSG_PURCHASE_DATE));

			if ($nb_items)
			{
				$sql_select_awarded = $db->query("SELECT w.*, b.bid_id, r.name AS auction_name, r.currency, r.category_id,
					u.username, u.name, rp.submitted, rp.reputation_id 
					FROM " . DB_PREFIX . "reverse_winners w
					LEFT JOIN " . DB_PREFIX . "reverse_auctions r ON r.reverse_id=w.reverse_id
					LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=w.provider_id
					LEFT JOIN " . DB_PREFIX . "reverse_bids b ON b.winner_id=w.winner_id
					LEFT JOIN " . DB_PREFIX . "reputation rp ON rp.from_id=w.poster_id AND rp.reverse_winner_id=w.winner_id
					WHERE w.poster_id='" . $session->value('user_id') . "' AND w.b_deleted=0 
					" . $search_filter . " 					
					GROUP BY w.winner_id 
					ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit);
				
				$reverse_fee = new fees(true);
				$reverse_fee->setts = &$setts;

                $counter= 0 ;
				while ($item_details = $db->fetch_array($sql_select_awarded))
				{
					$background = ($counter++%2) ? 'c1' : 'c2';

					$reverse_fee->set_fees($item_details['poster_id'], $item_details['category_id']);## PHP Pro Bid v6.00 by default the seller will pay

					$item_paid = ($item_details['active'] == 1 && $item_details['payment_status'] == 'confirmed') ? 1 : 0;
					if ($item_paid)
					{
						$content_options = '&#8226; <a href="' . process_link('message_board', array('message_handle' => '15', 'bid_id' => $item_details['bid_id'])) . '"><b class="greenfont">' . MSG_PMB . '</b></a><br>';

						if (!$item_details['submitted'])
						{
							$content_options .= '&#8226; <a href="members_area.php?page=reputation&section=post&reputation_ids=' . $item_details['reputation_id'] . '">' . MSG_LEAVE_COMMENTS . '</a><br>';
						}
					}
					else
					{
						if (eregi('b', $sale_fee->fee['endauction_fee_applies']))
						{
							$content_options = '&#8226; <a href="fee_payment.php?do=reverse_sale_fee_payment&winner_id=' . $item_details['winner_id'] . '">' . MSG_PAY_ENDAUCTION_FEE . '</a>';
						}
						else
						{
							$content_options = '&#8226; ' . MSG_ENDAUCTION_FEE_NOT_PAID;
						}
					}

					$reverse_awarded_content .= '<tr class="' . $background . '"> '.
						'	<td class="contentfont"><a href="' . process_link('reverse_details', array('reverse_id' => $item_details['reverse_id'])) . '"># ' . $item_details['reverse_id'] . '</a> - '.
						'		<a href="' . process_link('reverse_details', array('reverse_id' => $item_details['reverse_id'])) . '">' . field_display($item_details['auction_name'], MSG_AUCTION_DELETED) . '</a>'.
						'	</td>'.
						'	<td align="center">' . $fees->display_amount($item_details['bid_amount'], $item_details['currency']) . '</td>'.
						'	<td align="center"> ';

					if ($item_paid)
					{
						$reverse_awarded_content .= '<table border="0" cellpadding="0" cellspacing="0" class="reverseAwardedContent border"> '.
	            		'	<tr valign="top" bgcolor="#FFFFF"> '.
	               	'		<td class="smallfont" nowrap><b>' . MSG_USERNAME . '</b></td> '.
	               	'		<td class="smallfont" width="100%">' . field_display($item_details['username'], GMSG_NA) . '</td> '.
	            		'	</tr> '.
	            		'	<tr valign="top" bgcolor="#FFFFF"> '.
	               	'		<td class="smallfont" nowrap><b>' . MSG_FULL_NAME . '</b></td> '.
	               	'		<td class="smallfont">' . field_display($item_details['name'], GMSG_NA) . '</td> '.
	            		'	</tr> '.
	         			'</table> ';
					}

         		$reverse_awarded_content .= '	</td>'.
						'	<td align="center">';
					if ($item_paid)
					{
						$reverse_awarded_content .= '<table width="100%" border="0" cellpadding="3" cellspacing="2" class="reverseAwardedContent border smallfont"> '.
               		'	<tr bgcolor="#FFFFF"> '.
                  	'		<td align="center">' . show_date($item_details['purchase_date']) . '</td> '.
               		'	</tr> '.
               		'	<tr bgcolor="#FFFFF"> '.
                  	'		<td align="center">' . $item->flag_paid($item_details['flag_paid'], $item_details['direct_payment_paid']) . '</td> '.
               		'	</tr> '.
         				'</table>';
					}
					$reverse_awarded_content .= '	</td>'.
						'	<td class="smallfont">' . $content_options . '</td>'.
						'</tr>';

					$reverse_awarded_content .= '<tr> '.
						'	<td colspan="6" class="c4"></td> '.
						'</tr>';
				}

			}
			else
			{
				$reverse_awarded_content = '<tr><td colspan="6" align="center">' . GMSG_NO_ITEMS_MSG . '</td></tr>';
			}

			$template->set('reverse_awarded_content', $reverse_awarded_content);

			$pagination = paginate($start, $limit, $nb_items, 'members_area.php', $additional_vars . $order_link . $show_link);
			$template->set('pagination', $pagination);

			$members_area_page_content = $template->process('members_area_reverse_awarded.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);

		}			

		if ($section == 'my_bids')
		{
			$header_bidding_page = headercat('<b>' . MSG_MM_REVERSE_AUCTIONS . ' - ' . MSG_MM_MY_BIDS . '</b>');

			$nb_bids = $db->get_sql_field("SELECT count(*) AS nb_bids FROM " . DB_PREFIX . "reverse_bids b, " . DB_PREFIX . "reverse_auctions r WHERE
			b.bidder_id=" . $session->value('user_id') . " AND r.reverse_id=b.reverse_id AND r.active=1 AND r.closed=0 AND 
			r.deleted=0", 'nb_bids');

			$template->set('nb_bids', $nb_bids);

			$template->set('page_order_auction_id', page_order('members_area.php', 'r.reverse_id', $start, $limit, $additional_vars, MSG_AUCTION_ID));
			$template->set('page_order_itemname', page_order('members_area.php', 'r.name', $start, $limit, $additional_vars, MSG_ITEM_TITLE));
			$template->set('page_order_bid_amount', page_order('members_area.php', 'b.bid_amount', $start, $limit, $additional_vars, MSG_BID_AMOUNT));
			$template->set('page_order_bid_date', page_order('members_area.php', 'b.bid_date', $start, $limit, $additional_vars, GMSG_DATE));
			
			if ($nb_bids)
			{
				$sql_select_bids = $db->query("SELECT b.*, b.active AS bid_active, b.payment_status AS bid_payment_status, r.* 
					FROM " . DB_PREFIX . "reverse_bids b, " . DB_PREFIX . "reverse_auctions r 
					WHERE b.bidder_id=" . $session->value('user_id') . " AND r.reverse_id=b.reverse_id AND r.active=1 AND
					r.closed=0 AND r.deleted=0  
					ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit);

                $counter= 0 ;
				while ($bid_details = $db->fetch_array($sql_select_bids))
				{
					$background = ($counter++%2) ? 'c1' : 'c2';

					$auction_link = process_link('reverse_details', array('reverse_id' => $bid_details['reverse_id']));

					$item_paid = ($bid_details['bid_active'] == 1 && $bid_details['bid_payment_status'] == 'confirmed') ? 1 : 0;
					
					$content_options = ($item_paid) ? $item->reverse_bid_status($bid_details['bid_status']) : '<a href="fee_payment.php?do=reverse_bid_fee_payment&bid_id=' . $bid_details['bid_id'] . '">' . MSG_PAY_ENDAUCTION_FEE . '</a>';

					$current_bids_content .= '<tr class="' . $background . '"> '.
						'	<td class="contentfont"><a href="' . $auction_link . '"># ' . $bid_details['reverse_id'] . '</a></td> '.
						'	<td class="contentfont"><a href="' . $auction_link . '">' . $bid_details['name'] . '</a></td>'.
						'	<td align="center">' . $fees->display_amount($bid_details['bid_amount'], $bid_details['currency']) . '</td> '.
						'	<td align="center">' . show_date($bid_details['bid_date']) . '</td>'.
						'	<td align="center" class="contentfont">' . $content_options . '</td>'.
						'</tr>';					
				}
			}
			else
			{
				$current_bids_content = '<tr><td colspan="8" align="center">' . GMSG_NO_BIDS_MSG . '</td></tr>';
			}

			$template->set('current_bids_content', $current_bids_content);

			$pagination = paginate($start, $limit, $nb_bids, 'members_area.php', $additional_vars . $order_link);
			$template->set('pagination', $pagination);

			$members_area_page_content = $template->process('members_area_reverse_my_bids.tpl.php');
			
			$template->set('members_area_page_content', $members_area_page_content);

		}
				
		if ($section == 'my_profile')
		{
			$custom_fld = new custom_field();
			$page_handle = 'provider_profile';

			$frmchk_error = false;
			$custom_fld->save_edit_vars($session->value('user_id'), 'provider_profile');
						
			$item->setts['max_images'] = $setts['max_portfolio_files'];
			
			if (isset($_POST['form_profile_save']) || $_POST['box_submit'] == 1)
			{
				$user_details = $_POST;
			}
			else 
			{
				$user_details = $db->get_sql_row("SELECT user_id, provider_profile FROM
					" . DB_PREFIX . "users WHERE user_id=" . $session->value('user_id'));
				$user_details['profile_id'] = $user_details['user_id'];
				
				## retrieve profile images
				$media_details = $item->get_media_values($user_details['user_id'], false, false, true);
				$user_details['ad_image'] = $media_details['ad_image'];
				$user_details['ad_video'] = $media_details['ad_video'];
				$user_details['ad_dd'] = $media_details['ad_dd'];							
			}
			
			if (isset($_POST['form_profile_save']))
			{				
				$custom_fld->save_vars($_POST);
				
				define ('FRMCHK_ITEM', 1);
				$frmchk_details = $_POST;
				
				include ('includes/procedure_frmchk_provider_profile.php'); /* Formchecker for reverse auction creation/edit */
		
				if ($fv->is_error())
				{
					$template->set('display_formcheck_errors', $fv->display_errors());
					$frmchk_error = true;
				}
				else
				{				
					$form_submitted = true;
					$db->query("UPDATE " . DB_PREFIX . "users SET 
						provider_profile='" . $db->rem_special_chars($_POST['provider_profile']) . "' WHERE 
						user_id='" . $session->value('user_id') . "'");
					$custom_fld->update_page_data($session->value('user_id'), $page_handle, $_POST);
					$template->set('msg_changes_saved', $msg_changes_saved);
				}				
			}

			$custom_fld->new_table = false;
			$custom_fld->field_colspan = 2;
			$custom_sections_table = $custom_fld->display_sections($user_details, $page_handle);
			$template->set('custom_sections_table', $custom_sections_table);
			
			if (empty($_POST['file_upload_type']))
			{
				$template->set('media_upload_fields', $item->upload_manager($user_details));
			}
			else if (is_numeric($_POST['file_upload_id'])) /* means we remove a file / media url */
			{
				$media_upload = $item->media_removal($user_details, $user_details['file_upload_type'], $user_details['file_upload_id']);
				$media_upload_fields = $media_upload['display_output'];
	
				$user_details['ad_image'] = $media_upload['post_details']['ad_image'];
				$user_details['ad_video'] = $media_upload['post_details']['ad_video'];
				$user_details['ad_dd'] = $media_upload['post_details']['ad_dd'];
	
				$template->set('media_upload_fields', $media_upload_fields);
			}
			else /* means we have a file upload */
			{
				$media_upload = $item->media_upload($user_details, $user_details['file_upload_type'], $_FILES);
				$media_upload_fields = $media_upload['display_output'];
	
				$user_details['ad_image'] = $media_upload['post_details']['ad_image'];
				$user_details['ad_video'] = $media_upload['post_details']['ad_video'];
				$user_details['ad_dd'] = $media_upload['post_details']['ad_dd'];
	
				$template->set('media_upload_fields', $media_upload_fields);
			}## <<END>> media upload sequence			
			
			$image_upload_manager = $item->upload_manager($user_details, 1, 'form_provider_profile', true, false, false);
			$template->set('image_upload_manager', $image_upload_manager);

			$template->set('user_details', $user_details);

			$members_area_page_content = $template->process('members_area_reverse_profile.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);
		}
		
		if ($section == 'won')
		{
			if (isset($_REQUEST['form_update_winner_status']))
			{
				$dd_active = (intval($_REQUEST['flag_paid']) == 1) ? 1 : 0;
				$current_time = ($dd_active) ? CURRENT_TIME : 0;
				$update_force_payment = (intval($_REQUEST['flag_paid']) == 1) ? ", temp_purchase=0" : '';
				
				$db->query("UPDATE " . DB_PREFIX . "reverse_winners SET flag_paid='" . intval($_REQUEST['flag_paid']) . "' 
					WHERE winner_id='" . intval($_REQUEST['winner_id']) . "' AND
					provider_id='" . $session->value('user_id') . "'");
			}

			$nb_items = $db->count_rows('reverse_winners w', "WHERE w.provider_id='" . $session->value('user_id') . "' AND
				w.b_deleted=0");

			$template->set('nb_items', $nb_items);

			$template->set('page_order_auction_id', page_order('members_area.php', 'w.reverse_id', $start, $limit, $additional_vars . $show_link, MSG_AUCTION_ID));
			$template->set('page_order_itemname', page_order('members_area.php', 'a.name', $start, $limit, $additional_vars . $show_link, MSG_ITEM_TITLE));
			$template->set('page_order_bid_amount', page_order('members_area.php', 'w.bid_amount', $start, $limit, $additional_vars . $show_link, MSG_WINNING_BID));
			$template->set('page_order_purchase_date', page_order('members_area.php', 'w.purchase_date', $start, $limit, $additional_vars . $show_link, MSG_PURCHASE_DATE));

			if ($nb_items)
			{
				$sql_select_won = $db->query("SELECT w.*, b.bid_id, r.name AS auction_name, r.currency, r.category_id, 
					u.username, u.name, rp.submitted, rp.reputation_id, m.message_id 
					FROM " . DB_PREFIX . "reverse_winners w
					LEFT JOIN " . DB_PREFIX . "reverse_auctions r ON r.reverse_id=w.reverse_id
					LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=w.poster_id
					LEFT JOIN " . DB_PREFIX . "reverse_bids b ON b.winner_id=w.winner_id
					LEFT JOIN " . DB_PREFIX . "reputation rp ON rp.from_id=w.provider_id AND rp.reverse_winner_id=w.winner_id 
					LEFT JOIN " . DB_PREFIX . "messaging m ON m.reverse_id=w.reverse_id AND m.is_read=0 AND m.sender_id!=w.provider_id 					
					WHERE w.provider_id='" . $session->value('user_id') . "' AND w.b_deleted=0 
					GROUP BY w.winner_id 
					ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit);
					
				$reverse_fee = new fees(true);
				$reverse_fee->setts = &$setts;

                $counter= 0 ;
				while ($item_details = $db->fetch_array($sql_select_won))
				{
					$background = ($counter++%2) ? 'c1' : 'c2';

					$reverse_fee->set_fees($item_details['provider_id'], $item_details['category_id']);

					$item_paid = ($item_details['active'] == 1 && $item_details['payment_status'] == 'confirmed') ? 1 : 0;
					if ($item_paid)
					{
						$content_options = '&#8226; <a href="' . process_link('message_board', array('message_handle' => '15', 'bid_id' => $item_details['bid_id'])) . '"><b class="greenfont">' . MSG_PMB . '</b></a><br>';

						if (!$item_details['submitted'])
						{
							$content_options .= '&#8226; <a href="members_area.php?page=reputation&section=post&reputation_ids=' . $item_details['reputation_id'] . '">' . MSG_LEAVE_COMMENTS . '</a><br>';
						}
					}
					else
					{
						if (eregi('b', $reverse_fee->fee['endauction_fee_applies']))
						{
							$content_options = '&#8226; <a href="fee_payment.php?do=reverse_sale_fee_payment&winner_id=' . $item_details['winner_id'] . '">' . MSG_PAY_ENDAUCTION_FEE . '</a>';
						}
						else
						{
							$content_options = '&#8226; ' . MSG_ENDAUCTION_FEE_NOT_PAID;
						}
					}

					$reverse_won_content .= '<tr class="' . $background . '"> '.
						'	<td class="contentfont"><a href="' . process_link('reverse_details', array('reverse_id' => $item_details['reverse_id'])) . '"># ' . $item_details['reverse_id'] . '</a> - '.
						'		<a href="' . process_link('reverse_details', array('reverse_id' => $item_details['reverse_id'])) . '">' . field_display($item_details['auction_name'], MSG_AUCTION_DELETED) . '</a> '. 
						$item->new_message_tick($item_details['message_id']) . '</td>'.
						'	<td align="center">' . $fees->display_amount($item_details['bid_amount'], $item_details['currency']) . '</td>'.
						'	<td align="center"> ';

					if ($item_paid)
					{
						$reverse_won_content .= '<table border="0" cellpadding="0" cellspacing="0" class="reverseWonContent border"> '.
	            		'	<tr valign="top" bgcolor="#FFFFF"> '.
	               	'		<td class="smallfont" nowrap><b>' . MSG_USERNAME . '</b></td> '.
	               	'		<td class="smallfont" width="100%">' . field_display($item_details['username'], GMSG_NA) . '</td> '.
	            		'	</tr> '.
	            		'	<tr valign="top" bgcolor="#FFFFF"> '.
	               	'		<td class="smallfont" nowrap><b>' . MSG_FULL_NAME . '</b></td> '.
	               	'		<td class="smallfont">' . field_display($item_details['name'], GMSG_NA) . '</td> '.
	            		'	</tr> '.
	         			'</table> ';
					}

         		$reverse_won_content .= '	</td>'.
						'	<td align="center">';
					if ($item_paid)
					{
						$reverse_won_content .= show_date($item_details['purchase_date']) . '<br> '.
							'<table border="0" cellpadding="0" cellspacing="0" class="reverseWonContent border"> '.
            			'	<form action="members_area.php?page=reverse&section=won" method="post"> '.
               		'	<input type="hidden" name="winner_id" value="' . $item_details['winner_id'] . '"> '.
               		'		<tr bgcolor="#FFFFF"> '.
                  	'			<td align="center"><select name="flag_paid" style="font-size:10px; width: 100px;"> '.
                     '				<option value="0" ' . (($item_details['flag_paid'] == 0) ? 'selected' : '') . '>' . MSG_UNPAID . '</option> '.
							'				<option value="1" ' . (($item_details['flag_paid'] == 1) ? 'selected' : '') . '>' . MSG_PAID . '</option> '.
							'			</select></td> '.
               		'		</tr> '.
               		'		<tr bgcolor="#FFFFF"> '.
                  	'			<td align="center"><input type="submit" name="form_update_winner_status" value="' . GMSG_GO . '" style="font-size:10px; width: 100px;"></td> '.
               		'		</tr> '.
            			'	</form> '.
         				'</table>';
					}
					$reverse_won_content .= '	</td>'.
						'	<td class="smallfont">' . $content_options . '</td>'.
						'</tr>';											
					
					$reverse_won_content .= '<tr><td colspan="6" class="c4"></td></tr>';
				}

			}
			else
			{
				$reverse_won_content = '<tr><td colspan="8" align="center">' . GMSG_NO_ITEMS_MSG . '</td></tr>';
			}

			$template->set('reverse_won_content', $reverse_won_content);

			$pagination = paginate($start, $limit, $nb_items, 'members_area.php', $additional_vars . $order_link . $show_link);
			$template->set('pagination', $pagination);

			$members_area_page_content = $template->process('members_area_reverse_my_projects.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);

		}		
	} /* END -> WANTED ADS PAGE(S) */
		
	if ($page == 'summary') /* BEGIN -> SUMMARY PAGE */
	{
		if ($section == 'summary_main')
		{
			$summary_page_content['content'] = header6(MSG_MM_SUMMARY) . 
				$summary_page_content['manage_account'] .  
				$summary_page_content['messaging_received'] . '<br>' . 
				'<table cellpadding="0" cellspacing="0" border="0" class="summaryPageContent"> '.
				'	<tr> '.
				'		<td valign="top">' . $summary_page_content['stats_bidding'] . '</td> '.
				'		<td valign="top">' . $summary_page_content['stats_selling'] . '</td> '.
				'	</tr> '.
				'</table>' . 
				$summary_page_content['bidding_current_bids'] . 
				$summary_page_content['selling_open'];
				
			$template->set('members_area_page_content', $summary_page_content['content']);

		}
	} /* END -> SUMMARY PAGE */

    if ($page == 'contributions') /* BEGIN -> CONTRIBUTIONS PAGE */
   	{
   		if ($section == 'main')
   		{
            $campaigns_result = $db->query("SELECT np_users.project_title FROM np_users INNER JOIN funders ON funders.user_id = np_users.probid_user_id WHERE np_users.probid_user_id =".$session->value('user_id'));
            $nrElement = mysql_num_rows($campaigns_result);

            $per_page = 10;
            $total_pages = ceil(($nrElement-1)/$per_page);

            if (isset($_GET['page_selected'])) {
                $page_nr = $_GET['page_selected'];
            } else {
                $page_nr = 1;
            }
            $start = ($page_nr - 1)*$per_page;

            $campaigns_query_result = $db->query("SELECT bl2_users.first_name, bl2_users.last_name, funders.amount, funders.created_at, funders.user_id, np_users.project_title
                FROM np_users INNER JOIN funders ON funders.user_id = np_users.probid_user_id
                LEFT JOIN bl2_users ON bl2_users.id = funders.user_id
                WHERE np_users.probid_user_id=" . $session->value('user_id')."
                ORDER BY 'np_users.project_title' ASC limit $start, $per_page");
            $userCampaigns = array();
            while ($query_result =  mysql_fetch_array($campaigns_query_result)) {
                $userCampaigns[] = $query_result;
            }

            $template->set("page_selected",$page_selected);
            $template->set("total_pages",$total_pages);
            $template->set("info_contribution_campaigns",$userCampaigns);
            $members_area_page_content = $template->process('members_area_contributions.tpl.php');
            $template->set('members_area_page_content', $members_area_page_content);

   		}
   	} /* END -> CONTRIBUTIONS PAGE */

    if ($page == 'earnings') /* BEGIN -> CONTRIBUTIONS PAGE */
    {
        if ($section == 'main')
        {
            $campaigns_result = $db->query("SELECT np_users.project_title FROM np_users INNER JOIN funders ON funders.campaign_id = np_users.user_id");
            $nrElement = mysql_num_rows($campaigns_result);

            $per_page = 10;
            $total_pages = ceil(($nrElement-1)/$per_page);

            if (isset($_GET['page_selected'])) {
                $page_nr = $_GET['page_selected'];
            } else {
                $page_nr = 1;
            }
            $start = ($page_nr - 1)*$per_page;

            $campaigns_query_result = $db->query("SELECT bl2_users.first_name, bl2_users.last_name, funders.amount, funders.created_at, funders.user_id, np_users.project_title
                FROM np_users INNER JOIN funders ON funders.campaign_id = np_users.user_id
                LEFT JOIN bl2_users ON bl2_users.id = funders.user_id
                WHERE np_users.probid_user_id=" . $session->value('user_id')."
                ORDER BY 'np_users.project_title' ASC limit $start, $per_page");
            $userCampaigns = array();
            while ($query_result =  mysql_fetch_array($campaigns_query_result)) {
                $userCampaigns[] = $query_result;
            }

            $template->set("page_selected",$page_selected);
            $template->set("total_pages",$total_pages);
            $template->set("info_earning_campaigns",$userCampaigns);
            $members_area_page_content = $template->process('members_area_earnings.tpl.php');
            $template->set('members_area_page_content', $members_area_page_content);

        }
    }
    /*end earning page*/

    if ($page == 'campaigns') /* BEGIN -> CAMPAIGNS PAGE */
    {
        include('templates/member_area_campaigns.php');


        if (!isset($rows))
            $rows = array();
        $template->set('campaigns_list', $rows);
        $template->set('section', $section);

        if ($section == 'main') {
                $members_area_page_content = $template->process('members_area_campaigns_main.tpl.php');
                $template->set('members_area_page_content', $members_area_page_content);
        }

        if ($section == 'live') {
            $members_area_page_content = $template->process('members_area_campaigns.tpl.php');
            $template->set('members_area_page_content', $members_area_page_content);
        }
        if ($section == 'closed') {
            $members_area_page_content = $template->process('members_area_campaigns.tpl.php');
            $template->set('members_area_page_content', $members_area_page_content);
        }

        if ($section == 'drafts') {

            $members_area_page_content = $template->process('members_area_campaigns.tpl.php');
            $template->set('members_area_page_content', $members_area_page_content);
        }

        if ($section == 'edit') {
            //include('includes/class_npuser.php');

            include_once ('includes/global.php');
            include_once ('np/includes/npclass_formchecker.php');
            include_once('includes/generate_image_thumbnail.php');
            global $db;

            $tax = new tax();

            if (isset($_REQUEST['operation']) && $_REQUEST['operation'] == 'submit') {
                $campaign_id = mysql_real_escape_string($_POST["user_id"]);
            } else {
                $campaign_id = mysql_real_escape_string($_GET["campaign_id"]);
            }

            if (empty($_POST['last_selected_tab'])) {
                $last_selected_tab = '';
            } else {
                $last_selected_tab = $_POST['last_selected_tab'];
            }


            $mysql_select_query = "SELECT * FROM np_users WHERE user_id=" . $campaign_id;
            $campaign = $db->get_sql_row($mysql_select_query);


            $categories_query_result = $db->query("SELECT * FROM np_orgtype");
            $categories = array();
            while ($query_result =  mysql_fetch_array($categories_query_result)) {
                $categories[] = $query_result;
            }

            $countries_query_result = $db->query("SELECT * FROM proads_countries");
            $countries = array();
            while ($query_result =  mysql_fetch_array($countries_query_result)) {
                $countries[] = $query_result;
            }

            $pitches_query_result = $db->query("SELECT * FROM project_pitch WHERE project_id=" . $campaign_id);
            $pitches = array();
            while ($query_result =  mysql_fetch_array($pitches_query_result)) {
                $pitches[] = $query_result;
            }

            $project_update_query_result = $db->query("SELECT * FROM project_updates WHERE project_id=" . $campaign_id . " ORDER BY id DESC");
            $project_updates = array();
            while ($query_result =  mysql_fetch_array($project_update_query_result)) {
                $project_updates[] = $query_result;
            }

            $project_reward_query_result = $db->query("SELECT * FROM project_rewards WHERE project_id=" . $campaign_id . " ORDER BY id");
            $project_rewards = array();
            while ($query_result =  mysql_fetch_array($project_reward_query_result)) {
                $project_rewards[] = $query_result;
            }

            $post_country = ($campaign['country']) ? $campaign['country'] : $db->get_sql_field("SELECT c.id FROM " . DB_PREFIX . "countries c WHERE
				c.parent_id=0 ORDER BY c.country_order ASC, c.name ASC LIMIT 1", 'id');

            $template->set('country_dropdown', $tax->countries_dropdown('country', $post_country, 'registration_form'));

            $template->set("project_country",getProjectCategoryListToHTML());
            $template->set('state_box', $tax->states_box('state', $campaign['state'], $post_country));


            $form_submitted = FALSE;

            if (isset($_REQUEST['operation']) && $_REQUEST['operation'] == 'submit') {

                $post_country = ($_POST['country']) ? $_POST['country'] : $db->get_sql_field("SELECT c.id FROM " . DB_PREFIX . "countries c WHERE
				c.parent_id=0 ORDER BY c.country_order ASC, c.name ASC LIMIT 1", 'id');

                $template->set('country_dropdown', $tax->countries_dropdown('country', $post_country, 'registration_form'));

                $template->set("project_country",getProjectCategoryListToHTML());

                $template->set('state_box', $tax->states_box('state', $_POST['state'], $post_country));

                $fv = new formchecker;

                $allowed_image_mime_types = array(
                    'image/gif',
                    'image/jpeg',
                    'image/pjpeg',
                    'image/png',
                    'image/tiff',
                    'image/svg+xml'
                );

                define ('FRMCHK_USER', 1);

                (bool) $frmchk_user_edit = 0;

                $frmchk_details = $_POST;

                if (isset ($_FILES["logo"]) && is_uploaded_file($_FILES["logo"]["tmp_name"])) {
                    $frmchk_details["logo"]  =array();
                    $frmchk_details["logo"]["type"] = $_FILES["logo"]["type"];
                    if (in_array($_FILES["logo"]["type"], $allowed_image_mime_types)) {
                        $logo_image_size = getimagesize($_FILES["logo"]["tmp_name"]);
                        $frmchk_details["logo"]["dimensions"] = array(
                            "width" => $logo_image_size[0],
                            "max_width" => 160,
                            "height" => $logo_image_size[1],
                            "max_height" => 160,
                            "error_message" => "The dimension of the logo must be 160x160px"
                        );
                    }
                }

                if (isset ($_FILES["banner"]) && is_uploaded_file($_FILES["banner"]["tmp_name"])) {
                    $frmchk_details["banner"]  =array();
                    $frmchk_details["banner"]["type"] = $_FILES["banner"]["type"];
                    if (in_array($_FILES["logo"]["type"], $allowed_image_mime_types)) {
                        $logo_image_size = getimagesize($_FILES["banner"]["tmp_name"]);
                        $frmchk_details["banner"]["dimensions"] = array(
                            "width" => $logo_image_size[0],
                            "max_width" => 600,
                            "height" => $logo_image_size[1],
                            "max_height" => 400,
                            "error_message" => "The dimension of the banner must be 600x400px"
                        );
                    }
                }


                include ('includes/npprocedure_frmchk_edit_campaign.php');
                $pEmail = isset($_POST['email'])?$_POST['email']:'';
                $banned_output = check_banned($pEmail, 2);
                if ($banned_output['result'])
                {
                    $template->set('banned_email_output', $banned_output['display']);
                }
                else if ($fv->is_error())
                {
                    $template->set('display_formcheck_errors', $fv->display_errors());
                    $template->set('campaign', $campaign);
                    $template->set('categories', $categories);
                    $template->set('last_selected_tab', $last_selected_tab);
                    $template->set('countries', $countries);
                    $template->set('pitches', $pitches);
                    $template->set('project_updates', $project_updates);
                    $template->set('project_rewards', $project_rewards);
                    $members_area_page_content = $template->process('members_area_campaigns_edit.tpl.php');
                    $template->set('members_area_page_content', $members_area_page_content);
                }
                else
                {
                    $form_submitted = TRUE;## PHP Pro Bid v6.00 atm we wont create any emails either until we decide how many ways of registration we have.
                    (string) $register_success_message = null;
                    $_POST["end_date"] = 0;
                    if (isset($_POST["deadline_type_value"]) && $_POST["deadline_type_value"]) {
                        if ($_POST["deadline_type_value"] == "time_period")
                            $_POST["end_date"] = time() + ($_POST["time_period"] * 86400);
                        elseif ($_POST["deadline_type_value"] == "certain_date")
                            $_POST["end_date"] = strtotime($_POST["certain_date"]);
                    }

                    $funding_type = (isset($_POST["funding_type"]))?$_POST["funding_type"]:'';
                    $certain_date = (isset($_POST["certain_date"]))?$_POST["certain_date"]:'';
                    $pitch_text = (isset($_POST["pitch_text"]))?$_POST["pitch_text"]:'';

                    $keep_alive = (isset($_POST["keep_alive"]) && $_POST["keep_alive"]) ? 1 : 0;
                    $keep_alive_days = (isset($_POST["keep_alive_days"]) && $_POST["keep_alive_days"]) ?
                        $_POST["keep_alive_days"] : 0;

                    $mysql_update_query = "UPDATE np_users SET
                    project_category='" . $_POST["project_category"] . "',
                    campaign_basic='" . $_POST["campaign_basic"] . "',
                    project_title='" . $_POST["project_title"] . "',
                    description='" . $_POST["project_short_description"] . "',
                    founddrasing_goal='" . $_POST["founddrasing_goal"] . "',
                    funding_type='" . $funding_type . "',
                    active='" . $_POST["active"] . "',
                    deadline_type_value='" . $_POST["deadline_type_value"] . "',
                    time_period='" . $_POST["time_period"] . "',
                    certain_date='" . $certain_date . "',
                    end_date='" . $_POST["end_date"] . "',
                    url='" . $_POST["url"] . "',
                    facebook_url='" . $_POST["facebook_url"] . "',
                    twitter_url='" . $_POST["twitter_url"] . "',
                    name='" . $_POST["name"] . "',
                    tax_company_name='" . $_POST["tax_company_name"] . "',
                    address='" . $_POST["address"] . "',
                    city='" . $_POST["city"] . "',
                    zip_code='" . $_POST["zip_code"] . "',
                    country='" . $_POST["country"] . "',
                    state='" . $_POST["state"] . "',
                    phone='" . $_POST["phone"] . "',
                    orgtype='" . $_POST["orgtype"] . "',
                    pg_paypal_email='" . $_POST["pg_paypal_email"] . "',
                    pg_paypal_first_name='" . $_POST["pg_paypal_first_name"] . "',
                    pg_paypal_last_name='" . $_POST["pg_paypal_last_name"] . "',
                    keep_alive='" . $_POST["keep_alive"] . "',
                    keep_alive_days='" . $_POST["keep_alive_days"] . "',
                    pitch_text='" . $pitch_text . "'";

                    if (isset($_POST["username"]) && $_POST["username"]) {
                        $mysql_update_query .= ", username='" . $_POST["username"] . "'";
                    }


/*
                    if (!file_exists(__DIR__ . '/uplimg/partner_logos/')) {
                        mkdir(__DIR__ . '/uplimg/partner_logos/', 0777, true);
                    }

                    if (!file_exists(__DIR__ . '/uplimg/partner_logos/temp/')) {
                        mkdir(__DIR__ . '/uplimg/partner_logos/temp/', 0777, true);
                    }
*/


                    if (isset ($_FILES["logo"]) && is_uploaded_file($_FILES["logo"]["tmp_name"])) {
                        $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
                        $logo_file_name = '/uplimg/partner_logos/' . md5($_POST["name"] . 'logo') . '.' . $ext;
                        array_map(
                            'unlink',
                            glob('uplimg/partner_logos/' . md5($_POST["name"] . 'logo') . '*')
                        );
                        $upload_logo = generate_image_thumbnail(
                            $_FILES["logo"]["tmp_name"], trim($logo_file_name, '/'), 160, 160
                        );
                        $mysql_update_query .= ", logo='" . $logo_file_name . "'";
                    }

                    if (isset ($_FILES["banner"]) && is_uploaded_file($_FILES["banner"]["tmp_name"])) {
                        $ext = pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION);
                        $banner_file_name = '/uplimg/partner_logos/' . md5($_POST["name"] . 'banner') . '.' . $ext;
                        array_map(
                            'unlink',
                            glob('uplimg/partner_logos/' . md5($_POST["name"] . 'banner') . '*')
                        );
                        $upload_logo = generate_image_thumbnail(
                            $_FILES["banner"]["tmp_name"], trim($banner_file_name, '/'), 600, 400
                        );
                        $mysql_update_query .= ", banner='" . $banner_file_name . "'";

                    } elseif (isset ($_POST["video_url"]) && !empty($_POST["video_url"])) {
                        $mysql_update_query .= ", banner='" . $_POST["video_url"] . "'";
                    }

                    $_POST["probid_user_id"] =
                        (isset($_SESSION["probid_user_id"]) && !empty($_SESSION["probid_user_id"])) ?
                            $_SESSION["probid_user_id"] : 0;

                    $mysql_update_query .= " WHERE user_id='" . $_POST["user_id"] . "'";
                    $result = $db->query($mysql_update_query);
                    if (isset($_POST["pitch_amoun"])) {

                        $pitch_data = array();

                        $pitches_to_update = array();

                        $pitches_to_insert = array();

                        $pitches_to_delete = array();

                        foreach ($_POST["pitch_amoun"] as $index => $value) {

                            if (isset($_POST["pitch_id"][$index]) && $_POST["pitch_id"][$index]) {

                                $pitches_to_update[$index]["id"] = $_POST["pitch_id"][$index];

                                $pitches_to_update[$index]["project_id"] = $_POST["user_id"];

                                $pitches_to_update[$index]["amoun"] = $value;

                                $pitches_to_update[$index]["name"] = $_POST["pitch_name"][$index];

                                $pitches_to_update[$index]["description"] = $_POST["pitch_description"][$index];

                                foreach ($pitches as $key => $pitch) {

                                    if ($pitch["id"] == $_POST["pitch_id"][$index]) {

                                        unset($pitches[$key]);

                                    }

                                }

                            } else {

                                $pitches_to_insert[$index]["project_id"] = $_POST["user_id"];

                                $pitches_to_insert[$index]["amoun"] = $value;

                                $pitches_to_insert[$index]["name"] = $_POST["pitch_name"][$index];

                                $pitches_to_insert[$index]["description"] = $_POST["pitch_description"][$index];

                            }

                        }

                        if (count($pitches_to_insert) > 0) {

                            $insert_query = "INSERT INTO project_pitch(project_id, amoun, name, description) VALUES";
                            $i = 0;

                            foreach ($pitches_to_insert as $_pitch_details) {

                                if ($i > 0)

                                    $insert_query .= ',';

                                $insert_query .= "(" .

                                    $_pitch_details["project_id"] . ", " .

                                    $_pitch_details["amoun"] . ", '" .

                                    $_pitch_details["name"] . "', '" .

                                    $_pitch_details["description"] .

                                    "')";

                                $i++;

                            }


                            $sql_insert_pitch = $db->query($insert_query);

                        }


                        foreach ($pitches_to_update as $pitch_to_update) {

                            $pitch_update_query = "UPDATE project_pitch SET

                            project_id='" . $pitch_to_update["project_id"] . "',

                            amoun='" . $pitch_to_update["amoun"] . "',

                            name='" . $pitch_to_update["name"] . "',

                            description='" . $pitch_to_update["description"] . "'

                            WHERE id=" . $pitch_to_update["id"];

                            $db->query($pitch_update_query);

                        }

                    }



                    if (count($pitches) > 0) {

                        $pitch_delete_query = "DELETE FROM project_pitch WHERE id IN (";

                        foreach ($pitches as $key => $_pitch) {

                            if ($pitch_delete_query == "DELETE FROM project_pitch WHERE id IN (") {

                                $pitch_delete_query .= "'" . $_pitch["id"] . "'";

                            } else {

                                $pitch_delete_query .= ", '" . $_pitch["id"] . "'";
                            }
                        }

                        $pitch_delete_query .= ")";

                        $db->query($pitch_delete_query);

                    }

                    $mysql_select_query = "SELECT * FROM np_users WHERE user_id=" . $campaign_id;

                    $campaign = $db->get_sql_row($mysql_select_query);



                    $categories_query_result = $db->query("SELECT * FROM np_orgtype");

                    $categories = array();

                    while ($query_result =  mysql_fetch_array($categories_query_result)) {

                        $categories[] = $query_result;

                    }



                    $countries_query_result = $db->query("SELECT * FROM proads_countries");

                    $countries = array();

                    while ($query_result =  mysql_fetch_array($countries_query_result)) {

                        $countries[] = $query_result;
                    }

                    $pitches_query_result = $db->query("SELECT * FROM project_pitch WHERE project_id=" . $campaign_id);

                    $pitches = array();

                    while ($query_result =  mysql_fetch_array($pitches_query_result)) {

                        $pitches[] = $query_result;

                    }



                    $project_update_query_result = $db->query("SELECT * FROM project_updates WHERE project_id=" . $campaign_id);

                    $project_updates = array();

                    while ($query_result =  mysql_fetch_array($project_update_query_result)) {

                        $project_updates[] = $query_result;

                    }

                    $project_reward_query_result = $db->query("SELECT * FROM project_rewards WHERE project_id=" . $campaign_id);

                    $project_rewards = array();

                    while ($query_result =  mysql_fetch_array($project_reward_query_result)) {
                        $project_rewards[] = $query_result;
                    }

                    $template->set('campaign', $campaign);
                    $template->set('categories', $categories);
                    $template->set('countries', $countries);
                    $template->set('pitches', $pitches);
                    $template->set('video_url', isset($video_url) ? $video_url : '');
                    $template->set('project_updates', $project_updates);
                    $template->set('project_rewards', $project_rewards);
                    $template->set('last_selected_tab', $last_selected_tab);
                    $members_area_page_content = $template->process('members_area_campaigns_edit.tpl.php');
                    $template->set('members_area_page_content', $members_area_page_content);


                }

            } else if (isset($_REQUEST['get_states']) && $_REQUEST['get_states'] == 'true') {

                $post_country = (isset($_POST['country'])) ? $_POST['country'] : $db->get_sql_field("SELECT c.id FROM " . DB_PREFIX . "countries c WHERE

				c.parent_id=0 ORDER BY c.country_order ASC, c.name ASC LIMIT 1", 'id');

                $template->set('country_dropdown', $tax->countries_dropdown('country', $post_country, 'registration_form'));
                $template->set("project_country",getProjectCategoryListToHTML());
                $template->set('state_box', $tax->states_box('state', $_POST['state'], $post_country));
                $template->set('campaign', $campaign);
                $template->set('categories', $categories);
                $template->set('countries', $countries);
                $template->set('pitches', $pitches);
                $template->set('project_updates', $project_updates);
                $template->set('project_rewards', $project_rewards);
                $template->set('last_selected_tab', $last_selected_tab);
                $members_area_page_content = $template->process('members_area_campaigns_edit.tpl.php');
                $template->set('members_area_page_content', $members_area_page_content);

            } else if (!$form_submitted)

            {

                $post_country = ($campaign['country']) ? $campaign['country'] : $db->get_sql_field("SELECT c.id FROM " . DB_PREFIX . "countries c WHERE
				c.parent_id=0 ORDER BY c.country_order ASC, c.name ASC LIMIT 1", 'id');
                $template->set('country_dropdown', $tax->countries_dropdown('country', $post_country, 'registration_form'));
                $template->set("project_country",getProjectCategoryListToHTML());
                $template->set('state_box', $tax->states_box('state', $campaign['state'], $post_country));
                $template->set('campaign', $campaign);
                $template->set('categories', $categories);
                $template->set('countries', $countries);
                $template->set('pitches', $pitches);
                $template->set('project_updates', $project_updates);
                $template->set('project_rewards', $project_rewards);
                $template->set('last_selected_tab', $last_selected_tab);
                $members_area_page_content = $template->process('members_area_campaigns_edit.tpl.php');
                $template->set('members_area_page_content', $members_area_page_content);



            }
        }

    } /* BEGIN -> CAMPAIGNS PAGE */

    if ($page == 'widget') /* BEGIN -> WIDGET PAGE */
      {

          if ($section == 'view') {
              include_once('np/npwidget.php');
              $template->set('campaign', $campaign);
//              $members_area_page_content = $template->process('members_area_campaigns.tpl.php');
              $template->set('members_area_page_content', $members_area_page_content);
          }

      } /* BEGIN -> WIDGET PAGE */


	$template->set('members_area_header', header7(MSG_MEMBERS_AREA_TITLE));

	if ($session->value('category_language') == 1)
	{
		$msg_store_cats_modified = '<div class="errormessage contentfont" align="center">' . MSG_STORE_CATS_MODIFIED . '</div>';
		$template->set('msg_store_cats_modified', $msg_store_cats_modified);
	}
	
	## begin - header members area
	## preferred seller and check for credit limit
	$user_details = $db->get_sql_row("SELECT preferred_seller, balance, max_credit FROM " . DB_PREFIX . "users WHERE user_id='" . $session->value('user_id') . "'");
	$user_payment_mode = $fees->user_payment_mode($session->value('user_id'));

	$template->set('pref_seller_reduction', ($user_details['preferred_seller'] && $setts['enable_pref_sellers']) ? 1 : 0);
	$credit_limit_warning = ($user_payment_mode == 2 && ($user_details['max_credit'] <= ($user_details['balance']+2))) ? 1 : 0;
	$template->set('credit_limit_warning', $credit_limit_warning);


	$nb_cells = 1;

	if ($session->value('membersarea') == 'Active')
	{
		$nb_cells+=4;
	}

	if ($session->value('is_seller'))
	{
		$nb_cells++;
		if ($setts['enable_bulk_lister'])
		{
			$nb_cells++;
		}
		if ($setts['enable_stores'])
		{
			$nb_cells++;
		}
	}


	if ($setts['enable_wanted_ads'])
	{
		$nb_cells++;
	}

	if ($setts['enable_reverse_auctions'])
	{
		$nb_cells++;	
	}	
	
	$cell_width = round(100/$nb_cells) . '%';

	$template->set('cell_width', $cell_width);
	
	//if ($page != 'summary')
	//{
		$template->change_path('themes/' . $setts['default_theme'] . '/templates/');
		$members_area_header_menu = $template->process('members_area_header_menu.tpl.php');
		$template->change_path('templates/');
		
		$template->set('members_area_header_menu', $members_area_header_menu);## PHP Pro Bid v6.00 end - header members area
	//}

	$template_output .= $template->process('members_area.tpl.php');

	include_once ('global_footer.php');

	echo $template_output;
}
?>
