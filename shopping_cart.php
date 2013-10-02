<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright ©2008 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
## (Mods-Store) -> Shopping Cart											##
#################################################################

session_start();

define ('IN_SITE', 1);

include_once ('includes/global.php');
include_once ('includes/class_shop.php');
include_once ('includes/class_formchecker.php');
include_once ('includes/class_custom_field.php');
include_once ('includes/class_user.php');
include_once ('includes/class_fees.php');
include_once ('includes/class_item.php');
include_once ('includes/functions_item.php');

require ('global_header.php');

/* if the user accesses this page for the first time, create a session for the cart */

$item_id = intval($_REQUEST['item_id']);
$sc_id = intval($_REQUEST['sc_id']);

if (!$session->is_set('buyer_session_id') && !$session->is_set('user_id'))
{
	$buyer_session_id = md5(uniqid(rand(), true));
	$session->set('buyer_session_id', $buyer_session_id);
}

if ($session->is_set('user_id'))
{
	$sc_field = 'buyer_id';
	$sc_value = $session->value('user_id');
}
else
{
	$sc_field = 'buyer_session_id';
	$sc_value = $session->value('buyer_session_id');

}


$item = new item();
$item->setts = &$setts;
$item->layout = &$layout;
$item->shopping_cart = true;

$filter_carts = (in_array($_REQUEST['filter_carts'], array('all', 'pending', 'unpaid', 'paid'))) ? $_REQUEST['filter_carts'] : 'pending';
$template->set('filter_carts', $filter_carts);

/* create cart object */
$cart = new shop();
$cart->setts = &$setts;
$cart->sc_field = $sc_field;
$cart->sc_value = $sc_value;

$sc_carrier = $db->rem_special_chars($_REQUEST['sc_carrier']);
$invoice_comments = $db->rem_special_chars($_REQUEST['invoice_comments']);

// delete all empty shopping carts the user has created
$sql_select_empty_carts = $db->query("SELECT sc_id FROM " . DB_PREFIX . "shopping_carts WHERE " . $sc_field . "='" . $sc_value . "'");
while ($empty_cart = $db->fetch_array($sql_select_empty_carts))
{
	$cart->remove_cart($empty_cart['sc_id']);
}

$template->set('session', $session);
$template->set('item', $item);

$actions_array = array('view_cart', 'cart_error', 'checkout_confirm', 'checkout_success');
$action = (in_array($_REQUEST['action'], $actions_array)) ? $_REQUEST['action'] : 'view_cart';

$cart_result = null;
$checkout_disabled = false;
$cart_removed = false;

$action_checkout = false;

$tax = new tax();

if (isset($_POST['form_continue_shopping']))
{
	header_redirect('index.php');
}
else if (isset($_POST['form_update_cart']))
{
	// we already have the $sc_id variable available!
	(array) $cart_result = null;

	$session->set('apply_insurance', intval($_POST['apply_insurance']));
		
	if (count($_POST['sc_item_id']))
	{
		foreach ($_POST['sc_item_id'] as $key => $value)
		{
			$item_details = $db->get_sql_row("SELECT * FROM	" . DB_PREFIX . "auctions WHERE auction_id='" . intval($_POST['item_ids'][$key]) . "'");

			$cart_result[$value] = $cart->manage_cart_item($sc_id, $item_details, intval($_POST['quantity'][$key]));
			$checkout_disabled = ($cart_result[$value]['success']) ? $checkout_disabled : true;
		}
	}

	if (count($_POST['delete'])>0)
	{
		$delete_array = $db->implode_array($_POST['delete']);

		$sql_delete_items = $db->query_silent("DELETE sci FROM " . DB_PREFIX . "shopping_carts_items sci
			LEFT JOIN " . DB_PREFIX . "shopping_carts sc ON sc.sc_id=sci.sc_id WHERE
			sci.sc_item_id IN (" . $delete_array . ") AND sci.sc_id='" . $sc_id . "' AND sc.sc_purchased=0 AND 
			sc." . $sc_field . "='" . $sc_value . "'");

		$cart_removed = $cart->remove_cart($sc_id);
	}

	$shopping_cart_message = '<p align="center">' . MSG_SHOPPING_CART_UPDATED . '</p>';
}
else if (isset($_POST['form_checkout']))
{
	if ($session->value('membersarea')!='Active')
	{
		header_redirect('login.php?redirect=shopping_cart&sc_id=' . $sc_id);
	}
	else
	{
		$action_checkout = true;
	}
}

if ($session->is_set('cart_purchase_id'))
{
	if (PC_DM != 1)
	{
		$shopping_cart_message = '<p align="center" class="contentfont">' . MSG_DOUBLE_POST_ERROR . '</p>';
	}
	$action = 'cart_error';
}
else if ($item_id) /* the user is adding an item to his shopping cart */
{
	$item_details = $db->get_sql_row("SELECT * FROM	" . DB_PREFIX . "auctions WHERE auction_id='" . $item_id . "'");

	$blocked_user = blocked_user($session->value('user_id'), $item_details['owner_id']);

	if (show_buyout($item_details) && is_shopping_cart($item_id) && $session->value('user_id') != $item_details['owner_id'] && !$blocked_user)
	{
		/* add item to shopping cart */
		$cart_result = $cart->manage_cart($item_details); // the item is always added with quantity = 1

		$checkout_disabled = ($cart_result['success']) ? $checkout_disabled : true;
		$sc_id = $cart_result['sc_id'];
	}
	else
	{
		$action = 'cart_error';
	}
}
else if (!$sc_id || $cart_removed)
{
	$sc_id = $cart->select_cart(null, false, $filter_carts);
}

$template->set('apply_insurance', $session->value('apply_insurance'));

$select_cart_drop_down = $cart->list_carts($sc_id, 'list_carts', $filter_carts);
$template->set('select_cart_drop_down', $select_cart_drop_down);

if ($sc_id) /* this is also available for the checkout process */
{

	$seller_id = $db->get_sql_field("SELECT seller_id FROM " . DB_PREFIX . "shopping_carts WHERE sc_id=" . $sc_id, 'seller_id');

	if ($seller_id == $session->value('user_id'))
	{
		$shopping_cart_message = '<p align="center" class="errormessage">' . MSG_ERROR_SELLER_CART_OWNER . '</p>';
		$checkout_disabled = true;
	}

	if ($session->value('user_id'))
	{
		$blocked_user = blocked_user($session->value('user_id'), $seller_id);

		if ($blocked_user)
		{
			$shopping_cart_message = block_reason($session->value('user_id'), $seller_id);
			$checkout_disabled = true;
		}
		else
		{
			$buyer_details = $db->get_sql_row("SELECT * FROM " . DB_PREFIX . "users WHERE user_id='" . $session->value('user_id') . "'");
		}

	}

	$sql_select_cart = $db->query("SELECT sc.*, sci.sc_item_id, sci.item_id, sci.quantity AS quantity_ordered,
		a.auction_id, a.name, a.quantity, a.buyout_price AS price, a.currency, a.owner_id, 
		a.postage_amount, a.insurance_amount, a.item_weight, a.auto_relist_nb, a.auto_relist_bids, u.shop_direct_payment, m.message_id,   
		(SELECT count(*) FROM " . DB_PREFIX . "shopping_carts_items sci WHERE sci.sc_id=sc.sc_id) AS nb_cart_items, 
		(SELECT count(*) FROM " . DB_PREFIX . "winners w WHERE w.sc_id=sc.sc_id AND w.active=1 AND w.payment_status='confirmed') AS nb_cart_items_paid 
		FROM " . DB_PREFIX . "shopping_carts sc 
		LEFT JOIN " . DB_PREFIX . "shopping_carts_items sci ON sci.sc_id=sc.sc_id 
		LEFT JOIN " . DB_PREFIX . "auctions a ON a.auction_id=sci.item_id 
		LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=sc.seller_id
		LEFT JOIN " . DB_PREFIX . "messaging m ON m.sc_id=sc.sc_id AND m.is_read=0 AND m.sender_id!=sc.buyer_id 					
		WHERE sc.sc_id=" . $sc_id . " AND sc." . $sc_field . "='" . $sc_value . "' AND " . 
		((PC_DM == 1) ? 'b_deleted=0' : 'sc_purchased=0'));

	$is_cart = $db->num_rows($sql_select_cart);

	if ($is_cart)
	{
		/* show the respective cart */
		(string) $shopping_cart_content = null;
		(array) $cart_item_details = null;
		(array) $cart_ids = null;
		$owner_selected = false;

		$cart_subtotal = 0; // price * quantity
		$insurance_total = 0; // cart insurance
		$shipping_total = 0; // cart shipping cost
		$total_weight = 0;
		$checkout_cart = false;

		while ($cart_details = $db->fetch_array($sql_select_cart))
		{
			if ($cart_details['sc_purchased'])
			{
				$checkout_cart = true;			
				$sc_details = $cart_details;			
			}
			else 
			{
				
			   $cart_item_details[] = $cart_details;
				   $cart_ids[] = $cart_details['sc_item_id'];
				   
			   if (!$owner_selected)
			   {
				   $owner_selected = true;
				   $user_details = $db->get_sql_row("SELECT * FROM " . DB_PREFIX . "users WHERE
						   user_id=" . $cart_details['seller_id']);				
			   }
   
			   $background = ($counter++%2) ? 'c1' : 'c2';
   
			   $msg = (!empty($cart_result[$cart_details['sc_item_id']]['display'])) ? $cart_result[$cart_details['sc_item_id']]['display'] : false;
   
			   $shopping_cart_content .= '<input type="hidden" name="sc_item_id[]" value="' . $cart_details['sc_item_id'] . '"> '.
				   '<input type="hidden" name="item_ids[]" value="' . $cart_details['item_id'] . '"> '.
				   '<tr class="' . $background . ' contentfont"> '.
				   '	<td><a href="' . process_link('auction_details', array('auction_id' => $cart_details['item_id'])) . '"># ' . $cart_details['item_id'] . '</a> - '.
				   '		<a href="' . process_link('auction_details', array('auction_id' => $cart_details['item_id'])) . '">' . $cart_details['name'] . '</a></td> '.
				   '	<td align="center">' . $fees->display_amount($cart_details['price']) . '</td> '.
				   '	<td align="center">' . $cart_details['quantity'] . '</td> '.
				   '	<td align="center"><input type="text" size="5" name="quantity[]" value="' . $cart_details['quantity_ordered'] . '"></td> '.
				   '	<td align="center"><input name="delete[]" type="checkbox" id="delete[]" value="' . $cart_details['sc_item_id'] . '"></td> '.
				   '</tr>'.
				   (($msg) ? '<tr><td colspan="4">' . $msg . '</td></tr>' : '');
   
			   $cart_subtotal += $cart_details['price'] * $cart_details['quantity_ordered'];
			   $insurance_total += $cart_details['insurance_amount'];
				
				$total_weight += $cart_details['item_weight'] * $cart_details['quantity_ordered'];
			}
		}

		if (!$checkout_cart)
		{
		   if ($session->value('user_id'))
		   {
				$shipping_details = calculate_postage($cart_ids, $seller_id, null, null, null, null, 0, true);
				$shipping_total = $shipping_details['postage'];
	
				if (!$shipping_details['valid_location'])
				{
					$shipping_message = MSG_SHIPPING_LOCATION_UNSUPPORTED_WARNING_SC;
				}
			   $checkout_disabled = (!$shipping_details['valid_location']) ? true : $checkout_disabled;
			   $shipping_total = ($shipping_details['valid_location']) ? $shipping_total : 0;
			   $shipping_total_display = ($shipping_details['valid_location']) ? $fees->display_amount($shipping_total) : GMSG_NA;
			}

		   $template->set('cart_subtotal', $fees->display_amount($cart_subtotal));
		   $template->set('insurance_total', $fees->display_amount($insurance_total));
		   $template->set('shipping_total', $shipping_total_display);
			$template->set('shipping_message', $shipping_message);
	
			$template->set('invoice_comments', $invoice_comments);

		   $cart_tax = $tax->auction_tax($user_details['user_id'], $setts['enable_tax'], $session->value('user_id'));

		$user_details['shop_add_tax'] = ($setts['enable_tax'] && $cart_tax['amount'] > 0) ? $user_details['shop_add_tax'] : 0;

		$tax_details = array(
			'apply' => $user_details['shop_add_tax'],
			'tax_reg_number' => (($user_details['shop_add_tax']) ? $cart_tax['tax_reg_number'] : '-'),
			'tax_rate' => (($user_details['shop_add_tax']) ? $cart_tax['amount'] . '%' : '-'),
		);

		$insurance_total = ($session->value('apply_insurance')) ? $insurance_total : 0;

		$total_no_tax = $cart_subtotal + $insurance_total + $shipping_total;
		$total_tax = ($user_details['shop_add_tax']) ? ($total_no_tax * $cart_tax['amount'] / 100) : 0;
		$total_amount = $total_no_tax + $total_tax;

		$template->set('total_tax', $fees->display_amount($total_tax));
		$template->set('total_amount', $fees->display_amount($total_amount));

		$template->set('tax_details', $tax_details);

		$template->set('shopping_cart_content', $shopping_cart_content);

		/* if we have checkout_success, we will proceed to checkout if possible */		
		if ($action_checkout && !$checkout_disabled && $session->value('user_id') == $buyer_details['user_id'])
		{
			foreach ($cart_item_details as $key => $item_details)
			{
				$purchase_loop = 1;
				$purchase_time = time(); // we will wait for 5 seconds and then make the purchase
		
				$session->set('cart_purchase_id', $sc_id);
		
				while ($purchase_loop == 1)
				{
					$purchase_loop = $db->get_sql_field("SELECT bid_in_progress FROM " . DB_PREFIX . "auctions WHERE
						auction_id='" . $item_details['auction_id'] . "'",'bid_in_progress');
		
					$purchase_current_time = time();
		
					$purchase_loop = (($purchase_current_time - $purchase_time) > 5) ? 0 : $purchase_loop;
		
					if ($purchase_loop)
					{
						sleep(1);
						## we dont want to create a huge load on the database.
					}
				}
		
				$mark_in_progress = $db->query("UPDATE " . DB_PREFIX . "auctions SET
					bid_in_progress=1 WHERE auction_id='" . $item_details['auction_id'] . "'");
		
				## we will assign the winner, and then close the ad if the case.
				$item->sc_id = $sc_id;
				$item_details['buyout_price'] = $item_details['price'];
				$purchase_result = $item->assign_winner($item_details, 'buy_out', $session->value('user_id'), $item_details['quantity_ordered']);
		
				if ($purchase_result['auction_close'])
				{
					$item->close($item_details);
				}
		
				$unmark_in_progress = $db->query("UPDATE " . DB_PREFIX . "auctions SET
					bid_in_progress=0 WHERE auction_id='" . $item_details['auction_id'] . "'");
			   }
			   
			   /**
			    * STEPS:
			    * - email the seller and the buyer (special emails with the cart details and all)
			    * - set the shopping cart as purchased and set the postage and shipping if available (OK)
			    * - set the shipping_calc_auto flag accordingly (OK)
			    * - show direct payment dialogue if shipping_calc_auto = 1
			    */
			   $update_shopping_cart = $db->query("UPDATE " . DB_PREFIX . "shopping_carts SET 
				   sc_postage='" . (($shipping_details['valid_location']) ? $shipping_total : 0) . "', 
				   sc_insurance='" . $insurance_total . "', 
					sc_purchased='1', shipping_calc_auto='" . (($shipping_details['valid_location']) ? 1 : 0) . "',
					shipping_method='" . $sc_carrier . "', invoice_comments='" . $invoice_comments . "', 
					tax_amount='" . $total_tax . "', tax_rate='" . $cart_tax['amount'] . "', tax_calculated='1' 
					WHERE sc_id=" . $sc_id . " AND buyer_id=" . $session->value('user_id'));
			
			   $action = 'checkout_success';			
   
			   $mail_input_id = $sc_id;
			   include('language/' . $setts['site_lang'] . '/mails/cart_sale_seller_notification.php');
			   include('language/' . $setts['site_lang'] . '/mails/cart_sale_buyer_notification.php');				
			
				if ($shipping_details['valid_location'])
			   {
				   ## send invoice notification
				   $mail_input_id = $sc_id;
				   include('language/' . $setts['site_lang'] . '/mails/cart_invoice_buyer_notification.php');				
			   }
			
				if (PC_DM == 1)
				{
					header_redirect('shopping_cart.php?sc_id=' . $sc_id);					
				}
				else 
				{
			      $shopping_cart_success_message = '<p align="center" class="contentfont"><b>' . MSG_CART_PURCHASE_SUCCESS_EXPL . '</b></p>';
			      $template->set('shopping_cart_success_message', $shopping_cart_success_message);		
			      
			      $template->set('shopping_cart_success_view_cart', $cart->cart_table_display($sc_id));	
      
			      (string) $direct_payment_box = null;
			      
			      if ($shipping_details['valid_location'] && !empty($user_details['shop_direct_payment']))
			      {			
				      $direct_payment_box = $cart->sc_direct_payment_box($sc_id);
			      }
			      $template->set('direct_payment_box', $direct_payment_box);
				}
			}
		}
		else 
		{
			if (isset($_REQUEST['form_download_proceed']))
			{
				$download_result = download_redirect($_REQUEST['winner_id'], $session->value('user_id'));
				
				if ($download_result['redirect'])
				{
					header('Location: ' . $download_result['url']);
				}
			}			
			
			$action = 'checkout_success';
			
			$template->set('shopping_cart_success_view_cart', $cart->cart_table_display($sc_id));

			(string) $direct_payment_box = null;
			$template->set('sc_details', $sc_details);
			
			$cart_paid = ($sc_details['nb_cart_items'] == $sc_details['nb_cart_items_paid']) ? 1 : 0;
			$template->set('cart_paid', $cart_paid);
				
			if ($cart_paid)			
			{	
				if ($sc_details['shop_direct_payment'] && !$sc_details['flag_paid'] && !$sc_details['direct_payment_paid'])
				{
					$direct_payment_box = $cart->sc_direct_payment_box($sc_id);
				}
				$template->set('direct_payment_box', $direct_payment_box);
			}
		}
	}
	else
	{
		$template->set('shopping_cart_content', '<tr><td colspan="5" align="center">' . MSG_SHOPPING_CART_EMPTY . '</td></tr>');
		$checkout_disabled = true;
	}

	if (!$action_checkout && !$checkout_cart)
	{
		$action = 'view_cart';
	}
}
else
{
	$shopping_cart_message = '<p align="center" class="errormessage">' . MSG_NO_ACTIVE_SHOPPING_CARTS . '</p>';
	$action = 'cart_error';
}

$template->set('item_details', $item_details);

(string) $shopping_cart_page_content = null;

## now we display the page
if ($action == 'cart_error')
{
	$template->set('shopping_cart_header_message', header5(MSG_ERROR));
}
else if ($action == 'view_cart')
{
	$template->set('shopping_cart_header_message', header5(GMSG_SHOPPING_CART));

	$template->set('sc_id', $sc_id);
	$template->set('checkout_disabled', (($checkout_disabled) ? 'disabled' : ''));

	$shopping_cart_page_content = $template->process('shopping_cart_view.tpl.php');
}
else if ($action == 'checkout_success')
{
	$template->set('shopping_cart_header_message', header5(((PC_DM == 1) ? GMSG_SHOPPING_CART : MSG_PURCHASE_SUCCESS)));

	$shopping_cart_page_content = $template->process('shopping_cart_success.tpl.php');
}

$template->set('shopping_cart_message', $shopping_cart_message);
$template->set('shopping_cart_page_content', $shopping_cart_page_content);
$template_output .= $template->process('shopping_cart.tpl.php');

include_once ('global_footer.php');

echo $template_output;
?>
