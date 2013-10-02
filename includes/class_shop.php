<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
## (Mods-Store) -> Shopping Cart											##
#################################################################

class shop extends database
{
	var $fees;
	var $user_id;
	var $tax;
	var $template;

	/* shopping cart specific variables */
	var $sc_field = null;	
	var $sc_value = null;
	
	function shop_status($user_details, $view_all = false)
	{
		$output = array('enabled' => false, 'display' => '<span class="redfont">' . GMSG_DISABLED . '</span>', 
			'account_id' => 0, 'account_type' => GMSG_NONE, 'shop_description' => null, 
			'total_items' => 0, 'remaining_items' => 0, 'remaining_items_display' => null	
		);
		
		if ($view_all)
		{
			$shop_details = $this->get_sql_row("SELECT * FROM " . DB_PREFIX . "fees_tiers WHERE 
				tier_id='" . $user_details['shop_account_id'] . "'");
		}

		if ($user_details['shop_account_id'])
		{
			$output['account_id'] = $user_details['shop_account_id'];
			
			if ($view_all)
			{					
				$output['account_type'] = $shop_details['store_name'];
				$output['shop_description'] = $this->shop_description($shop_details, false);
			}
		}
		
		if ($user_details['shop_active'])
		{
			$output['enabled'] = true;
			$output['display'] = '<span class="greenfont">' . GMSG_ENABLED . '</span>';

			if ($user_details['shop_account_id'])
			{
				if ($view_all)
				{					
					$output['total_items'] = $this->count_rows('auctions', "WHERE list_in!='auction' AND 
						active=1 AND approved=1 AND closed=0 AND deleted=0 AND owner_id='" . $user_details['user_id'] . "'");
					$output['remaining_items'] = $shop_details['store_nb_items'] - $output['total_items'];
					$output['remaining_items'] = ($output['remaining_items'] > 0) ? $output['remaining_items'] : 0;
				}
			}
			else 
			{
				## workaround so that there are an unlimited number of remaining items in case there are no 
				## subscriptions set up
				$output['remaining_items'] = 1; 
				$output['account_type'] = GMSG_DEFAULT;
				$output['shop_description'] = MSG_FREE_UNLIMITED_ITEMS;
			}
		}

		return $output;
	}

	function shop_description ($shop_details, $show_title = true)
	{
		(string) $display_output = null;
		
		$this->fees = new fees();
		$this->fees->setts = $this->setts;
		
		if ($show_title) 
		{
			$output[] = '<b>' . $shop_details['store_name'] . '</b>';
		}
		
		$shop_fee_amount = $shop_details['fee_amount'];
		## calculate the preferred seller and tax for the fee
		$preferred_seller = $this->get_sql_field("SELECT preferred_seller FROM " . DB_PREFIX . "users WHERE
			user_id='" . $this->user_id . "'", 'preferred_seller');
		
		if ($preferred_seller && $this->setts['enable_pref_sellers'])
		{
			$shop_fee_amount = $this->fees->round_number($shop_fee_amount - ($shop_fee_amount * ($this->setts['pref_sellers_reduction'] / 100)));
		}
		
		$tax_output = $this->fees->apply_tax($shop_fee_amount, $this->setts['currency'], $this->user_id, $this->setts['enable_tax']);
		$shop_fee_amount = $tax_output['amount'];
				
		$output[] = $shop_details['store_nb_items'] . ' ' . MSG_ITEMS;
		$output[] = MSG_PRICE . ': ' . $this->fees->display_amount($shop_fee_amount, $this->setts['currency']);
		$output[] = ($shop_details['store_recurring'] > 0) ? MSG_RECURRING_EVERY . ' ' . $shop_details['store_recurring'] . ' ' . GMSG_DAYS : MSG_FLAT_FEE;

		if ($shop_details['store_featured'])
		{
			$output[] = '[ <b>' . MSG_FEATURED_STORE . '</b> ]';
		}
		
		$display_output = $this->implode_array($output,', ');
		
		return $display_output;
	}
	function save_aboutme($form_details, $user_id)
	{
		$store_active = $this->get_sql_field("SELECT shop_active FROM
			" . DB_PREFIX . "users WHERE user_id=" . $user_id, 'shop_active');

		$store_active = ($form_details['enable_aboutme_page']) ? 0 : $store_active;

		$this->query("UPDATE " . DB_PREFIX . "users SET enable_aboutme_page='" . $form_details['enable_aboutme_page'] . "',
			aboutme_page_content='" . $this->rem_special_chars($form_details['aboutme_page_content']) . "' WHERE	user_id='" . $user_id . "'");
		
		##,shop_active='" . $store_active . "'
	}

	function favourite_store_link ($store_id, $user_id)
	{
		(string) $display_output = null;

		if ($user_id)
		{
			$is_favourite = $this->count_rows('favourite_stores', "WHERE store_id='" . $store_id . "' AND user_id='" . $user_id . "'");

			$fav_store = ($is_favourite) ? 'remove' : 'add';
			$fav_store_msg = ($is_favourite) ? MSG_ADD_TO_FAVOURITE_STORES : MSG_REMOVE_FROM_FAVOURITE_STORES;
			$display_output = ' [ <a href="' . process_link('shop', array('user_id' => $store_id, 'fav_store' => $fav_store)) . '">' . $fav_store_msg . '</a> ]';
		}

		return $display_output;
	}
	
	function store_templates_drop_down ($box_name = 'shop_template_id', $selected = null)
	{
		(string) $display_output = null;
		$store_template_names = array(MSG_STORE_TPL_DESIGN_0, MSG_STORE_TPL_DESIGN_1, MSG_STORE_TPL_DESIGN_2, 
			MSG_STORE_TPL_DESIGN_3, MSG_STORE_TPL_DESIGN_4, MSG_STORE_TPL_DESIGN_5);

		$nb_templates = 6; ## max template id = 5

		$display_output = '<select name="' . $box_name . '" id="' . $box_name . '" size="10"  onChange="previewPic(this)"> ';

		for ($i=0; $i<$nb_templates; $i++)
		{
			$display_output .= '<option value="' . $i . '" ' . (($i == $selected) ? 'selected' : '') . '>' . $store_template_names[$i] . '</option> ';
		}
		$display_output .= '</select> ';

		$display_output .= '&nbsp; <img src="store_templates/images/' . (($selected) ? $selected : '0') . '.jpg?' . rand(2,9999) . '" border="1" align="top" name="preview_pic"> ';
		
		return $display_output;		
	}
	
	function store_subscriptions_drop_down ($box_name = 'shop_account_id', $selected = null)
	{
		(string) $display_output = null;
		
		$sql_select_subscriptions = $this->query("SELECT * FROM " . DB_PREFIX . "fees_tiers WHERE fee_type='store' ORDER BY fee_amount ASC");
			
		$added_menu = false;
		$default_check = false;
		
		while ($subscr_details = $this->fetch_array($sql_select_subscriptions)) 
		{
			if (!$added_menu)
			{
				$added_menu = true;
				$display_output .= '<tr class="c1"> '.
					'	<td align="right">' . MSG_CHOOSE_SUBSCRIPTION . '</td> ';
			}
			else 
			{
				$display_output .= '<tr> '.
					'	<td></td> ';
					
			}
			$display_output .= '	<td class="c1"><input type="radio" name="shop_account_id" value="' . $subscr_details['tier_id'] . '" ' . (($subscr_details['tier_id'] == $selected || !$default_check) ? 'checked' : '') . '> ' . $this->shop_description($subscr_details) . '</td></tr>';				
			
			$default_check = true;
		}
			
		return $display_output;
	}
	
	function shop_save_settings($post_details, $user_id)
	{
		$shop_logo_path = $post_details['ad_image'][0];
		$post_details = $this->rem_special_chars_array($post_details);

		$this->query("UPDATE " . DB_PREFIX . "users SET 
			shop_name='" . $post_details['shop_name'] . "', shop_mainpage='" . $post_details['shop_mainpage'] . "', 
			shop_logo_path='" . $shop_logo_path . "', shop_template_id='" . $post_details['shop_template_id'] . "', 
			shop_metatags='" . $post_details['shop_metatags'] . "' WHERE 
			user_id='" . $user_id . "'");
	}
	
	function shop_save_pages($post_details, $user_id)
	{
		$post_details = $this->rem_special_chars_array($post_details);
		
		$feat_items_row = intval($post_details['shop_nb_feat_items_row']);
		$feat_items_row = ($feat_items_row<0 || $feat_items_row > $post_details['shop_nb_feat_items']) ? $post_details['shop_nb_feat_items'] : $feat_items_row;		
		
		$this->query("UPDATE " . DB_PREFIX . "users SET 
			shop_about='" . $post_details['shop_about'] . "', shop_specials='" . $post_details['shop_specials'] . "', 
			shop_shipping_info='" . $post_details['shop_shipping_info'] . "', shop_company_policies='" . $post_details['shop_company_policies'] . "', 
			shop_nb_feat_items_row='" . $feat_items_row . "', shop_nb_feat_items='" . $post_details['shop_nb_feat_items'] . "', 
			shop_nb_ending_items='" . $post_details['shop_nb_ending_items'] . "', 
			shop_nb_recent_items='" . $post_details['shop_nb_recent_items'] . "' WHERE user_id='" . $user_id . "'");		
	}
	
	function shop_save_subscription ($post_details, $user_id)
	{
		$output = array('display' => null, 'show_page' => true);
		(array) $query = null;
		
		$shop_details = $this->get_sql_row("SELECT enable_aboutme_page, shop_account_id, shop_active, shop_next_payment FROM
			" . DB_PREFIX . "users WHERE user_id=" . $user_id);
				
		$enable_aboutme_page = ($post_details['shop_active']) ? 0 : $shop_details['enable_aboutme_page'];
		$shop_active = $post_details['shop_active'];
		
		//$query[] = "enable_aboutme_page='" . $enable_aboutme_page . "'";
		
		if ($post_details['shop_account_id'])
		{
			$query[] = "shop_account_id='" . $post_details['shop_account_id'] . "'";
		}
		
		$charge_fees = false;
		/**
		 * the fee routine is called if the shop is active and we change the shop account or if
		 * the shop is inactive and we activate it
		 */
		if (($post_details['shop_account_id'] != $shop_details['shop_account_id'] && $shop_details['shop_active'] && $post_details['shop_account_id']) || ($post_details['shop_active'] && !$shop_details['shop_active'] && $shop_details['shop_next_payment'] < CURRENT_TIME))
		{
			## if we change the shop_account_id, shop_active = 0 until we go through the setup fee process
			$shop_active = 0;
			$charge_fees = true;
			$query[] = "shop_next_payment='" . CURRENT_TIME . "'";
		}
		else if ($post_details['shop_account_id'] != $shop_details['shop_account_id'] && !$shop_details['shop_active'] && !$post_details['shop_active'])
		{
			$shop_active = 0;
			$charge_fees = false;
			$query[] = "shop_next_payment='" . CURRENT_TIME . "'";			
		}
		
		$query[] = "shop_active='" . $shop_active . "'";
		
		$shop_update_query = $this->implode_array($query, ', ');
		$this->query("UPDATE " . DB_PREFIX . "users SET " . $shop_update_query . " WHERE user_id=" . $user_id);
		
		if ($charge_fees)
		{
			$this->fees = new fees();
			$this->fees->setts = $this->setts;
			
			$store_subscription_fee = $this->fees->store_subscription($post_details['shop_account_id'], $user_id);
			$output['display'] = $store_subscription_fee['display'];
			$output['show_page'] = false;
		}
		else 
		{
			$output['display'] = '<p align="center" class="contentfont">' . MSG_CHANGES_SAVED . '</p>';
		}
		
		return $output;	
	}
		
	function shop_postage_save($postage_details, $user_id)
	{
		$user_id = intval($user_id);
		
		$postage_details['shop_direct_payment'] = (count($postage_details['payment_gateway'])) ? $this->implode_array($postage_details['payment_gateway']) : '';

		$this->query("UPDATE " . DB_PREFIX . "users SET 
			shop_add_tax='" . intval($postage_details['shop_add_tax']) . "', 
			shop_direct_payment='" . $postage_details['shop_direct_payment'] . "' 
			WHERE user_id='" . $user_id . "'"); 		
	}
	
	
	/* shopping cart functions */
	function is_cart($item_details, $pending_cart = false)
	{		
		$is_cart = $this->count_rows('shopping_carts', "WHERE seller_id=" . $item_details['owner_id'] . " AND
			" . $this->sc_field . "='" . $this->sc_value . "' AND " . 
			((PC_DM == 1 && !$pending_cart) ? 'b_deleted=0' : 'sc_purchased=0'));
		
		return ($is_cart) ? true : false;
	}
	
	function create_cart($item_details)
	{
		$this->query("INSERT INTO " . DB_PREFIX . "shopping_carts (seller_id, " . $this->sc_field . ", sc_date) VALUES 
			(" . $item_details['owner_id'] . ", '" . $this->sc_value . "', " . CURRENT_TIME . ")");
			
		$cart_id = $this->insert_id();

		return $cart_id;
	}
	
	function select_cart($item_details, $select_seller = true, $filter_carts = 'all')
	{
		$addl_query = $this->show_carts($filter_carts);
		$addl_query = (empty($addl_query)) ? ' AND sc.sc_purchased=0 ' : $addl_query;

		$cart_id = $this->get_sql_field("SELECT sc_id FROM " . DB_PREFIX . "shopping_carts sc WHERE " . 
			(($select_seller) ? 'sc.seller_id=' . $item_details['owner_id'] . ' AND ' : '') .
			'sc.' . $this->sc_field . "='" . $this->sc_value . "' " . $addl_query . " ORDER BY sc.sc_id DESC", 'sc_id');			
		
		return $cart_id;
	}
	
	function item_in_cart($sc_id, $item_details)
	{
		$is_item = $this->count_rows('shopping_carts_items', "WHERE sc_id=" . $sc_id . " AND item_id='" . $item_details['auction_id'] . "'");
		
		return ($is_item) ? true : false;
	}
	
	function remove_item_from_cart ($sc_id, $item_details)
	{
		$this->query("DELETE FROM " . DB_PREFIX . "shopping_carts_items WHERE 
			sc_id=" . $sc_id . " AND item_id=" . $item_details['item_id']);
		
		$this->remove_cart($sc_id);
	}
	
	function remove_cart($sc_id)
	{
		$result = false;
		$is_items = $this->count_rows('shopping_carts_items', "WHERE sc_id=" . $sc_id);
		
		if (!$is_items)
		{
			$this->query("DELETE FROM " . DB_PREFIX . "shopping_carts WHERE sc_id=" . $sc_id);
			$result = true;
		}
		
		return $result;
	}
	
	function check_quantity($item_details, $quantity)
	{
		return ($item_details['quantity'] < $quantity) ? false : true;
	}
	
	function manage_cart_item($sc_id, $item_details, $quantity)
	{
		$output = array('success' => false, 'display' => null);
		
		if ($this->item_in_cart($sc_id, $item_details))
		{			
			if ($quantity<=0)
			{
				$this->remove_item_from_cart($sc_id, $item_details);
				$output = array('success' => true, 'display' => MSG_ITEM_ID . ' #' . $item_details['auction_id'] . ': ' . MSG_REMOVED_FROM_CART_SUCCESS);
			}
			else 
			{
				$check_quantity = $this->check_quantity($item_details, $quantity);
				$output = array('success' => true, 'display' => MSG_ITEM_ID . ' #' . $item_details['auction_id'] . ': ' . MSG_CART_QUANTITY_UPDATE_SUCCESS);
				
				if ($check_quantity)
				{
					$this->query("UPDATE " . DB_PREFIX . "shopping_carts_items SET quantity=" . $quantity . " WHERE 
						sc_id=" . $sc_id . " AND item_id=" . $item_details['auction_id']);				
				}
				else 
				{
					$output = array('success' => false, 'display' => MSG_ITEM_ID . ' #' . $item_details['auction_id'] . ': ' . MSG_CART_QUANTITY_FAIL);
				}
			}
		}
		else 
		{
			$check_quantity = $this->check_quantity($item_details, $quantity);

			if ($check_quantity)
			{				
				$this->query("INSERT INTO " . DB_PREFIX . "shopping_carts_items (sc_id, item_id, quantity) VALUES 
					(" . $sc_id . ", " . $item_details['auction_id'] . ", " . $quantity . ")");
				$output = array('success' => true, 'display' => MSG_ITEM_ID . ' #' . $item_details['auction_id'] . ': ' . MSG_CART_ITEM_ADD_SUCCESS);
			}
			else 
			{
				$output = array('success' => false, 'display' => MSG_ITEM_ID . ' #' . $item_details['auction_id'] . ': ' . MSG_CART_QUANTITY_FAIL);				
			}
		}
		
		return $output;
	}
	
	function cart_postage($sc_id)
	{
	}
	
	function manage_cart($item_details, $quantity = 1)
	{
		$is_cart = $this->is_cart($item_details, true);
		
		if (!$is_cart) /* create cart */
		{
			$sc_id = $this->create_cart($item_details);
		}
		else /* select an existing cart */
		{
			$sc_id = $this->select_cart($item_details);
		}
		
		/* now add the item to cart, update the item's quantity or remove the item from the cart */
		$cart_result = $this->manage_cart_item($sc_id, $item_details, $quantity);
	
		$result = array('success' => $cart_result['success'], 'display' => $cart_result['display'], 'sc_id' => $sc_id);
		
		return $result;
	}	
	
	function show_carts($filter_carts = 'all')
	{
		$output = null;
		switch ($filter_carts)
		{
			case 'pending':
				$output = ' AND sc.sc_purchased=0 ';
				break;
			case 'unpaid':
				$output = ' AND sc.flag_paid=0 AND sc.sc_purchased=1 ';
				break;
			case 'paid':
				$output = ' AND sc.flag_paid=1 AND sc.sc_purchased=1 ';
				break;
		}
		
		return $output;		
	}
	
	function list_carts($selected = null, $form_refresh = null, $filter_carts = 'all')
	{
		(string) $display_output = null;

		$addl_query = $this->show_carts($filter_carts);
		
		$sql_select_carts = $this->query("SELECT sc.*, u.username, u.shop_name FROM	" . DB_PREFIX . "shopping_carts sc 
			LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=sc.seller_id 
			WHERE sc." . $this->sc_field . "='" . $this->sc_value . "' 
			" . $addl_query . " 
			AND " . ((PC_DM) ? 'sc.b_deleted=0' : 'sc.sc_purchased=0') . 
			" ORDER BY sc.sc_id DESC");

		$is_cart = $this->num_rows($sql_select_carts);
		
		if ($is_cart)
		{
			$display_output = '<select name="sc_id" ' . (($form_refresh) ? 'onChange = "' . $form_refresh . '.submit();"' : '') . '> '.
				'<option value="" selected>' . MSG_NONE_CART . '</option>';
	
			while ($cart_details = $this->fetch_array($sql_select_carts))
			{
				//$store_name = $cart_details['sc_id'] . ' - ' . $cart_details['username'];				
				$store_name = $cart_details['shop_name'] . (($cart_details['sc_purchased'] == 1) ? ' - ' . MSG_INVOICE_ID . ': #' . $cart_details['sc_id'] : '');				
				$display_output .= '<option value="' . $cart_details['sc_id'] . '" ' . (($cart_details['sc_id'] == $selected) ? 'selected' : '') . '>' . $store_name . '</option> ';
			}
			$display_output .= '</select> ';
		}
		
		return $display_output;
	}
	
	function cart_table_display($sc_id, $edit_postage = false, $seller_view = false, $addl_vars = null) /* we will use $this->sc_field and $this->sc_value when displaying */
	{
		$output = false;
		(string) $shopping_cart_content = null;
	
		$this->fees = new fees();
		$this->fees->setts = $this->setts;
	
		$sql_select_cart = $this->query("SELECT sc.*, sci.sc_item_id, sci.item_id, sci.quantity AS quantity_ordered,
			a.auction_id, a.name, a.quantity, a.buyout_price, a.currency, a.owner_id, 
			a.postage_amount, a.insurance_amount, a.item_weight, 
			w.is_dd, w.dd_active_date, w.dd_nb_downloads, w.dd_active, w.winner_id 
			FROM " . DB_PREFIX . "shopping_carts sc 
			LEFT JOIN " . DB_PREFIX . "shopping_carts_items sci ON sci.sc_id=sc.sc_id 
			LEFT JOIN " . DB_PREFIX . "auctions a ON a.auction_id=sci.item_id 
			LEFT JOIN " . DB_PREFIX . "winners w ON w.auction_id=sci.item_id AND w.sc_id=sc.sc_id
			WHERE sc.sc_id=" . $sc_id . " AND sc.sc_purchased=1 AND sc." . $this->sc_field . "='" . $this->sc_value . "'");
	
		$is_cart = $this->num_rows($sql_select_cart);
	
		if ($is_cart)
		{
			/* show the respective cart */
			$owner_selected = false;
	
			$cart_subtotal = 0; // price * quantity
			$insurance_total = 0; // cart insurance
			$shipping_total = 0; // cart shipping cost
			$shipping_method = null;
	
			while ($cart_details = $this->fetch_array($sql_select_cart))
			{
				if (!$owner_selected)
				{
					$owner_selected = true;
					
					$seller_details = $this->get_sql_row("SELECT * FROM " . DB_PREFIX . "users WHERE
						user_id=" . $cart_details['seller_id']);				
					$buyer_details = $this->get_sql_row("SELECT * FROM " . DB_PREFIX . "users WHERE 
						user_id='" . $cart_details['buyer_id'] . "'");
					
					$insurance_total = $cart_details['sc_insurance'];
					$shipping_total = $cart_details['sc_postage'];
					$shipping_method = $cart_details['shipping_method'];
					
					$tax_rate = $cart_details['tax_rate'];
					
					$is_invoice = $cart_details['shipping_calc_auto'];
					
					if ($edit_postage)
					{
						$edit_postage = ($cart_details['invoice_final']) ? false : true;
					}
				}
	
				$background = ($counter++%2) ? 'c1' : 'c2';
	
				$shopping_cart_content .= '<tr class="' . $background . ' contentfont"> '.
					'	<td><a href="' . process_link('auction_details', array('auction_id' => $cart_details['auction_id'])) . '"># ' . $cart_details['auction_id'] . '</a> - '.
					'		<a href="' . process_link('auction_details', array('auction_id' => $cart_details['auction_id'])) . '">' . $cart_details['name'] . '</a></td> '.
					'	<td align="center">' . $this->fees->display_amount($cart_details['buyout_price']) . '</td> '.
					'	<td align="center">' . $cart_details['quantity_ordered'] . '</td> '.
					'</tr>';
					
					if ($cart_details['is_dd'])
					{
						if ($seller_view)
						{
							$link_active = MSG_LINK_ACTIVE . ' &middot; [ <a href="members_area.php?do=dd_active&value=0&winner_id=' . $cart_details['winner_id'] . $addl_vars . '">' . MSG_INACTIVATE . '</a> ]';
							$link_inactive = MSG_LINK_INACTIVE . ' &middot; [ <a href="members_area.php?do=dd_active&value=1&winner_id=' . $cart_details['winner_id'] . $addl_vars . '">' . MSG_ACTIVATE . '</a> ]';

							$dd_expires = dd_expires($cart_details['dd_active_date']);

							$shopping_cart_content .= '<tr class="c7"> '.
								'	<td><b>' . MSG_DIGITAL_MEDIA_ATTACHED . '</b><br>'.
								'		' . (($cart_details['dd_active'] && $dd_expires['result']>0) ? $link_active : $link_inactive) . '</td> '.
								'	<td align="center" colspan="2">' . MSG_DOWNLOADED . ' ' . $cart_details['dd_nb_downloads'] . ' ' . MSG_TIMES . '<br>'.
								'		' . MSG_LINK_EXPIRES . ': ' . (($cart_details['dd_active']) ? $dd_expires['display'] : GMSG_NA) . '</td>'.
								'</tr>';

						}
						else 
						{
							$dd_expires = dd_expires($cart_details['dd_active_date']);
	
							$shopping_cart_content .= '<tr class="c7"> '.
								'	<td><b>' . MSG_DIGITAL_MEDIA_ATTACHED . '</b></td> '.
								'	<td align="center">' . MSG_DOWNLOADED . ' ' . $cart_details['dd_nb_downloads'] . ' ' . MSG_TIMES . '<br>'.
								'		' . MSG_LINK_EXPIRES . ': ' . (($cart_details['dd_active']) ? $dd_expires['display'] : GMSG_NA) . '</td>'.
								(($cart_details['dd_active']) ? '<form action="" method="post"><input type="hidden" name="winner_id" value="' . $cart_details['winner_id'] . '">' : '') .
								'	<td align="center"><input name="form_download_proceed" type="submit" id="form_download_proceed" value="' . MSG_DOWNLOAD_MEDIA . '" ' . (($cart_details['dd_active'] && $dd_expires['result']>0) ? '' : 'disabled') . '/></td>'.
								(($cart_details['dd_active']) ? '</form>' : '') .
								'</tr>';
						}
					}

	
				$cart_subtotal += $cart_details['buyout_price'] * $cart_details['quantity_ordered'];
			}
			
			$this->tax = new tax();
			$this->tax->setts = $this->setts;
			
			$cart_tax = $this->tax->auction_tax($seller_details['user_id'], $this->setts['enable_tax'], $buyer_details['user_id']);
	
			$tax_rate = ($seller_details['shop_add_tax']) ? $tax_rate : 0;
			$tax_details = array(
				'apply' => (($tax_rate > 0) ? true : false),
				'tax_reg_number' => (($tax_rate > 0) ? $cart_tax['tax_reg_number'] : '-'),
				'tax_rate' => (($tax_rate > 0) ? $tax_rate . '%' : '-'),
			);
	
			$total_no_tax = $cart_subtotal + $insurance_total + $shipping_total;
			$total_tax = ($seller_details['shop_add_tax']) ? ($total_no_tax * $tax_rate / 100) : 0;
			$total_amount = $total_no_tax + $total_tax;
	
			$this->template = new template('templates/');
			
			$this->template->set('sc_id', $sc_id);
			$this->template->set('is_invoice', $is_invoice);
			$this->template->set('edit_postage', $edit_postage);
			$this->template->set('currency', $this->setts['currency']);
			
			$this->template->set('cart_subtotal', $this->fees->display_amount($cart_subtotal));
			$this->template->set('insurance_total', $this->fees->display_amount($insurance_total));
			$this->template->set('shipping_total_value', $shipping_total);
			$this->template->set('shipping_total', $this->fees->display_amount($shipping_total));
			$this->template->set('shipping_method', field_display($shipping_method, GMSG_NA));

			$this->template->set('total_tax', $this->fees->display_amount($total_tax));
			$this->template->set('total_amount', $this->fees->display_amount($total_amount));
	
			$this->template->set('tax_details', $tax_details);

			$this->template->set('shopping_cart_content', $shopping_cart_content);
			
			$output = $this->template->process('sc_details.tpl.php');					
		}
		
		return $output;
	}
	
	function sc_direct_payment_box($sc_id)
	{
		(string) $display_output = null;
		$fee_table = 110; ## direct payment - shopping carts;
				
		$this->fees = new fees();
		$this->fees->setts = $this->setts;
		
		$sql_select_cart = $this->query("SELECT sc.*, sci.sc_item_id, sci.item_id, sci.quantity AS quantity_ordered,
			a.auction_id, a.name, a.quantity, a.buyout_price, a.currency, a.owner_id, 
			a.postage_amount, a.insurance_amount, a.item_weight 
			FROM " . DB_PREFIX . "shopping_carts sc 
			LEFT JOIN " . DB_PREFIX . "shopping_carts_items sci ON sci.sc_id=sc.sc_id 
			LEFT JOIN " . DB_PREFIX . "auctions a ON a.auction_id=sci.item_id 
			WHERE sc.sc_id=" . $sc_id . " AND sc.sc_purchased=1 AND sc." . $this->sc_field . "='" . $this->sc_value . "'");
	
		$is_cart = $this->num_rows($sql_select_cart);
	
		if ($is_cart)
		{
			/* show the respective cart */
			$owner_selected = false;
	
			$cart_subtotal = 0; // price * quantity
			$insurance_total = 0; // cart insurance
			$shipping_total = 0; // cart shipping cost
	
			while ($cart_details = $this->fetch_array($sql_select_cart))
			{
				if (!$owner_selected)
				{
					$owner_selected = true;
					
					$seller_details = $this->get_sql_row("SELECT * FROM " . DB_PREFIX . "users WHERE
						user_id=" . $cart_details['seller_id']);				
					$buyer_details = $this->get_sql_row("SELECT * FROM " . DB_PREFIX . "users WHERE 
						user_id='" . $cart_details['buyer_id'] . "'");
					
					$insurance_total = $cart_details['sc_insurance'];
					$shipping_total = $cart_details['sc_postage'];
					$direct_payment_paid = $cart_details['direct_payment_paid'];
				}
				
				$tax_rate = $cart_details['tax_rate'];
	
				$cart_subtotal += $cart_details['buyout_price'] * $cart_details['quantity_ordered'];
			}
			
			if ($direct_payment_paid)
			{
				$display_output = '<p align="center">' . MSG_SC_DP_PAID_EXPL . ' (' . MSG_SC_ID . ': ' . $sc_id . ')</p> ';
			}
			else 
			{
				$this->tax = new tax();
				$this->tax->setts = $this->setts;
				
				$cart_tax = $this->tax->auction_tax($seller_details['user_id'], $this->setts['enable_tax'], $buyer_details['user_id']);
								
				$tax_details = array(
					'apply' => (($tax_rate > 0) ? true : false),
					'tax_reg_number' => (($tax_rate > 0) ? $cart_tax['tax_reg_number'] : '-'),
					'tax_rate' => (($tax_rate > 0) ? $tax_rate . '%' : '-'),
				);
		
				$total_no_tax = $cart_subtotal + $insurance_total + $shipping_total;
				$total_tax = ($seller_details['shop_add_tax']) ? ($total_no_tax * $tax_rate / 100) : 0;
				$total_amount = $total_no_tax + $total_tax;	
	
				$dp_gateways = $seller_details['shop_direct_payment'];
	
				$payment_description = MSG_DIRECT_PAYMENT . ' - ' . MSG_SC_ID . ': ' . $sc_id;
				
				$transaction_id = $sc_id . 'TBL' . $fee_table;
	
				$display_output = '<p align="center" class="errormessage"><b>' . MSG_DIRECT_PAYMENT . ' [ ' . MSG_SC_ID . ': ' . $sc_id . ' ]</b><br><br> '.
					MSG_PROCEED_TO_PG_DP_MSG . ' <b>' . $this->fees->display_amount($total_amount) . '</b>.</p><br>';
	
				$this->fees->user_id = $buyer_details['user_id'];
				$display_output .= $this->fees->show_gateways($transaction_id, $total_amount, $this->setts['currency'], $seller_details['user_id'], $payment_description, $dp_gateways);
			}
		}
		else 
		{
			$display_output .= '<p align="center">' . MSG_ERROR_CART_NOT_AVAILABLE . '</p>';
		}
		
		return $display_output;
	}
}

?>
