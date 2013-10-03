<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

session_start();

define ('IN_SITE', 1);
define ('AUCTION_DETAILS', 1);
$GLOBALS['body_id'] = "auction_details";

include_once ('includes/global.php');
include_once ('includes/class_formchecker.php');
include_once ('includes/class_custom_field.php');
include_once ('includes/class_user.php');
include_once ('includes/class_fees.php');
include_once ('includes/class_item.php');
include_once ('includes/functions_item.php');
include_once ('includes/class_messaging.php');
include_once ('includes/class_reputation.php');

require ('global_header_interior.php');

(array) $user_details = null;

$start_time_id = 1;
$end_time_id = 2;

$item = new item();
$item->setts = &$setts;
$item->layout = &$layout;

$reputation = new reputation();
$reputation->setts = &$setts;

$page_handle = 'auction';

$addl_query = ($session->value('adminarea')!="Active") ? " AND active=1 AND approved=1" : '';

$item_details = $db->get_sql_row("SELECT * FROM " . DB_PREFIX . "auctions WHERE
	auction_id='" . intval($_REQUEST['auction_id']) . "'");

$main_category_id = $db->main_category($item_details['category_id']);
$category_details = $db->get_sql_row("SELECT minimum_age FROM " . DB_PREFIX . "categories WHERE category_id='" . $main_category_id . "'");

if (isset($_REQUEST['option']) && $_REQUEST['option'] == 'agree_adult')
{
	$session->set('adult_category', 1);	
}

$can_view = false;
$adult_cat = false;
if ($item_details['auction_id'])
{
	if (($session->value('adminarea')=="Active") || ($item_details['active'] ==1 && $item_details['approved'] == 1) || ($session->value('user_id') == $item_details['owner_id']))
	{
		$can_view = true;	
		$adult_cat = false;
	}
	if ($session->value('adminarea')!="Active" && $category_details['minimum_age'] > 0 && !$session->value('adult_category'))
	{
		$can_view = false;	
		$adult_cat = true;
	}
}

if ($can_view)
{
	$blocked_user = blocked_user($session->value('user_id'), $item_details['owner_id']);
	$template->set('blocked_user', $blocked_user);

	if ($blocked_user)
	{
		$template->set('block_reason_msg', block_reason($session->value('user_id'), $item_details['owner_id']));
	}

	$template->set('auction_id', intval($_REQUEST['auction_id']));## PHP Pro Bid v6.00 add click
	$sql_add_click = $db->query("UPDATE " . DB_PREFIX . "auctions SET nb_clicks=nb_clicks+1 WHERE auction_id=" . $item_details['auction_id']);

	$user_details = $db->get_sql_row("SELECT * FROM
		" . DB_PREFIX . "users WHERE user_id=" . $item_details['owner_id']);

	$custom_fld = new custom_field();

	$msg = new messaging();
	$msg->setts = &$setts;

	/**
	 * if we have a user logged in, we mark as read any questions/answers he has received
	 */
	if ($session->value('user_id'))
	{
		$msg->mark_read($session->value('user_id'), 0, $item_details['auction_id'], 1); //<-- needs mysql optimization!
	}

	$blocked_user = blocked_user($session->value('user_id'), $item_details['owner_id'], 'message');
		
	if ((isset($_REQUEST['option']) && in_array($_REQUEST['option'], array('post_question', 'post_answer'))) || (isset($_REQUEST['operation']) && in_array($_REQUEST['operation'], array('post_question', 'post_answer'))))
	{
		if ($blocked_user)
		{
			$msg_changes_saved = block_reason($session->value('user_id'), $item_details['owner_id'], 'message');	
		}
		else 
		{
			if ($_REQUEST['option'] == 'post_question')
			{
				$msg->new_topic($item_details['auction_id'], $session->value('user_id'), $item_details['owner_id'], 1, '', $_REQUEST['message_content'], $_REQUEST['message_handle']);
		
				header_redirect('auction_details.php?auction_id=' . $item_details['auction_id'] . '&operation=post_question');
			}
			else if ($_REQUEST['option'] == 'post_answer')
			{
				$msg->reply(intval($_REQUEST['question_id']), $session->value('user_id'), '', $_REQUEST['message_content']);
		
				header_redirect('auction_details.php?auction_id=' . $item_details['auction_id'] . '&operation=post_answer');
			}
		
			if ($_REQUEST['operation'] == 'post_question')
			{
				$msg_changes_saved = '<p align="center" class="contentfont">' . MSG_QUESTION_POSTED_SUCCESSFULLY . '</p>';
			}
			else if ($_REQUEST['operation'] == 'post_answer')
			{
				$msg_changes_saved = '<p align="center" class="contentfont">' . MSG_ANSWER_POSTED_SUCCESSFULLY . '</p>';
			}
		}
	}
	
	## PHP Pro Bid v6.00 item watch procedure
	if (isset($_REQUEST['option']) && $_REQUEST['option'] == 'item_watch')
	{
		if ($session->value('user_id'))
		{
			$item_watch = $item->item_watch_add($item_details['auction_id'], $session->value('user_id'), $item_details['owner_id']);
			$msg_changes_saved = '<p align="center" class="contentfont">' . $item_watch . '</p>';
		}
		else
		{
			$msg_changes_saved = '<p align="center" class="contentfont">' . MSG_LOGIN_FOR_ITEM_WATCH . '</p>';
		}
	}
	
	## PHP Pro Bid v6.00 send auction to a friend procedure
	if (isset($_REQUEST['option']) && $_REQUEST['option'] == 'auction_friend')
	{
		$form_submitted = 0;
		
		if (isset($_REQUEST['form_auction_friend']))
		{
			define ('FRMCHK_AUCTION_FRIEND', 1);
			(int) $item_post = 1;
	
			$af_details = $_REQUEST;
			$frmchk_details = $af_details;

			include('includes/procedure_frmchk_auction_friend.php');
			
			if ($fv->is_error())
			{
				$template->set('display_formcheck_errors', '<tr><td colspan="2">' . $fv->display_errors() . '</td></tr>');
			}
			else
			{
				$auction_friend_output = $item->auction_friend($item_details, $session->value('user_id'), $af_details['friend_name'], $af_details['friend_email'], $_REQUEST['comments'], $af_details['name'], $af_details['email']);
				$msg_changes_saved = '<p align="center" class="contentfont">' . $auction_friend_output . '</p>';
			}
		}
		
		if (!$form_submitted)
		{
			if (!$item_post && $session->value('user_id'))
			{
				$af_details = $db->get_sql_row("SELECT name, email FROM " . DB_PREFIX . "users WHERE user_id='" . $session->value('user_id') . "'");
			}
			
			$post_details = ($item_post) ? $_GET : $af_details;
			$template->set('post_details', $post_details);

			$session->set('pin_value', md5(rand(2,99999999)));
			$generated_pin = generate_pin($session->value('pin_value'));
	
			$pin_image_output = show_pin_image($session->value('pin_value'), $generated_pin);
	
			$template->set('pin_image_output', $pin_image_output);
			$template->set('generated_pin', $generated_pin);
			
			$auction_friend_form = $template->process('auction_friend.tpl.php');
			$template->set('auction_friend_form', $auction_friend_form);
		}
	}

	if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'delete_topic' && $session->value('adminarea') == 'Active') /* delete public question - admin area feature only */
	{
		$db->query("DELETE FROM " . DB_PREFIX . "messaging WHERE topic_id='" . intval($_REQUEST['topic_id']) . "'");
		$msg_changes_saved = '<p align="center">' . MSG_TOPIC_DELETED . '</p>';
	} else
        $msg_changes_saved = '';

	$template->set('msg_changes_saved', $msg_changes_saved);

	$item_details['quantity'] = $item->set_quantity($item_details['quantity']);

	$custom_fld->save_edit_vars($item_details['owner_id'], $page_handle);

	$media_details = isset($_REQUEST['auction_id'])?$item->get_media_values(intval($_REQUEST['auction_id'])):'';
	$item_details['ad_image'] = isset($media_details['ad_image'])?$media_details['ad_image']:'';
	$item_details['ad_video'] = isset($media_details['ad_video'])?$media_details['ad_video']:'';
	$item_details['ad_dd'] = isset($media_details['ad_dd'])?$media_details['ad_dd']:'';

	$template->set('item_details', $item_details);

	$template->set('buyout_only', $item->buyout_only($item_details));

	$template->set('user_details', $user_details);

	//$template->set('fees', $fees);
	$template->set('session', $session);
	$template->set('item', $item);

	$template->set('item_can_bid', $item->can_bid($session->value('user_id'), $item_details));

	$template->set('main_category_display', category_navigator($item_details['category_id'], true, false, 'categories.php'));
	$template->set('addl_category_display', category_navigator($item_details['addl_category_id'], true, false, 'categories.php'));

	$template->set('direct_payment_box', $item->direct_payment_box($item_details, $session->value('user_id')));
	$template->set('ad_display', 'live'); /* if ad_display = preview, then some table fields will be disabled */

	$template->set('show_buyout', show_buyout($item_details));

	$template->set('your_bid', $item->your_bid($item_details['auction_id'], $session->value('user_id')));

	$tax = new tax();
	$seller_country = $tax->display_countries($user_details['country']);
	$template->set('seller_country', $seller_country);

	$template->set('auction_location', $item->item_location($item_details));
	$template->set('auction_country', $tax->display_countries($item_details['country']));

	$swap_offer_link = ($item_details['enable_swap'] && $session->value('user_id') != $item_details['owner_id']) ? '[ <a href="swap_offer.php?auction_id=' . $item_details['auction_id'] . '">' . MSG_MAKE_SWAP_OFFER . '</a> ]' : '';
	$template->set('swap_offer_link', $swap_offer_link);

	$item->show_hidden_bid = ($item_details['owner_id'] == $session->value('user_id') || $session->value('adminarea') == 'Active') ? true : false;
	
	$template->set('high_bidders_content', $item->show_high_bid($item_details, 'high_bid'));
	$template->set('winners_content', $item->show_high_bid($item_details, 'winner'));

	$winners_message_board = $item->winners_message_board_link($item_details, $session->value('user_id'));
	$template->set('winners_message_board', $winners_message_board);

	$item_watch_text = $item->item_watch_text($item_details['auction_id']);
	$template->set('item_watch_text', $item_watch_text);
	
	$reputation_table_small = $reputation->rep_table_small($item_details['owner_id'], $item_details['auction_id']);
	$template->set('reputation_table_small', $reputation_table_small);

	$auction_tax = $tax->auction_tax($user_details['user_id'], $setts['enable_tax'], $session->value('user_id'));
	$template->set('auction_tax', $auction_tax);

	$custom_fld->new_table = ($setts['default_theme'] == 'ultra') ? true : false;
	$custom_fld->field_colspan = 1;
	
	$custom_sections_table = $custom_fld->display_sections($item_details, $page_handle, true, $item_details['auction_id'], $db->main_category($item_details['category_id']));
	$template->set('custom_sections_table', $custom_sections_table);

	$ad_image_thumbnails = $item->item_media_thumbnails($item_details, 1);
	$full_size_images_link = $item->full_size_images($item_details);
	$template->set('ad_image_thumbnails', $ad_image_thumbnails . '<br>' . $full_size_images_link);

	
	$ad_video_thumbnails = $item->item_media_thumbnails($item_details, 2);
	$template->set('ad_video_thumbnails', $ad_video_thumbnails);

	$ad_dd_thumbnails = $item->item_media_thumbnails($item_details, 3);
	$template->set('ad_dd_thumbnails', $ad_dd_thumbnails);

    $vpAdVideo = isset($item_details['ad_video'][0])?$item_details['ad_video'][0]:'';
	$video_play_file = (!empty($_REQUEST['video_name'])) ? $_REQUEST['video_name'] : $vpAdVideo;
	$ad_video_main_box = $item->video_box($video_play_file);
	$template->set('ad_video_main_box', $ad_video_main_box);## PHP Pro Bid v6.00 auction questions
	if ($setts['enable_asq'])
	{
		$public_messages = $msg->public_messages($item_details['auction_id']);

		(string) $public_questions_content = null;
		while ($msg_details = $db->fetch_array($public_messages))
		{
			$public_questions_content .= '<tr class="c2"> '.
				'	<td><table width="100%" class="publicQuestion"> '.
   			'			<tr> '.
   			'				<td><img src="themes/' . $setts['default_theme'] . '/img/system/q.gif" /></td> '.
   			'				<td width="100%" align="right"><strong>' . MSG_QUESTION . '</strong></td> '.
   			'			</tr> '.
   			'		</table></td> '.
   			'	<td>' . $msg_details['question_content'] . '</td>'.
   			'</tr> '.
   			'<tr class="c1"> '.
   			'	<td><table width="100%" class="publicAnswer"> '.
   			'			<tr> '.
   			'				<td><img src="themes/' . $setts['default_theme'] . '/img/system/a.gif" /></td> '.
   			'				<td width="100%" align="right"><strong>' . MSG_ANSWER . '</strong></td> '.
   			'			</tr> '.
   			'		</table></td> '.
   			'	<td>' . ((!empty($msg_details['answer_content'])) ? $msg_details['answer_content'] : '-') . '</td> '.
   			'</tr>';

   		if ($session->value('adminarea') == 'Active')
   		{
	   		$public_questions_content .= '<tr> '.
	   			'	<td></td> '.
	   			'	<td class="c1 contentfont"> '.
         		'		[ <a href="auction_details.php?do=delete_topic&topic_id=' . $msg_details['topic_id'] . '&auction_id=' . $item_details['auction_id'] . '" onclick="return confirm(\'' . MSG_DELETE_CONFIRM . '\');">' . MSG_DELETE_TOPIC . '</a> ]</td> '.
	   			'</tr>';
   		}
   		else if ($session->value('user_id') == $item_details['owner_id'])
   		{
	   		$public_questions_content .= '<tr> '.
	   			'	<td></td> '.
	   			'	<form method="get"> '.
	   			'	<td class="c1"> '.
         		'		<input type="button" value="'.MSG_SUBMIT_EDIT_ANSWER.'" onClick="openPopup(\'popup_edit_public_question.php?auction_id=' . $item_details['auction_id'] . '&question_id='.$msg_details['question_id'].'\')"></td> '.
      			'	</form> '.
	   			'</tr>';
   		}

   		$public_questions_content .= '<tr class="c4"> '.
   			'	<td></td> '.
   			'	<td></td> '.
   			'</tr>';
		}

		$template->set('public_questions_content', $public_questions_content);
	}


	if (!empty($item_details['direct_payment']))
	{
		$dp_methods = $item->select_direct_payment($item_details['direct_payment'], $user_details['user_id'], true);

		$direct_payment_methods_display = $template->generate_table($dp_methods, 2, 3, 3, null, '', '');
		$template->set('direct_payment_methods_display', $direct_payment_methods_display);
	}

	if (!empty($item_details['payment_methods']))
	{
		$offline_payments = $item->select_offline_payment($item_details['payment_methods'], true);

		$offline_payment_methods_display = $template->generate_table($offline_payments, 4, 3, 3, null, '', '');
		$template->set('offline_payment_methods_display', $offline_payment_methods_display);
	}
	
	/* BEGIN -> shipping calculator box code snippet */
	$sc_disabled = 'disabled';	

	$sc_quantity = isset($_REQUEST['sc_quantity'])?intval($_REQUEST['sc_quantity']):0;
	$sc_quantity = ($sc_quantity > $item_details['quantity']) ? $item_details['quantity'] : (($sc_quantity < 1) ? 1 : $sc_quantity);
	$template->set('sc_quantity', $sc_quantity);
	
	$tax->selected_cid = shipping_locations($item_details['owner_id']);
	
	$sc_country = isset($_REQUEST['sc_country'])?intval($_REQUEST['sc_country']):'';
	$sc_state = isset($_REQUEST['sc_state'])?intval($_REQUEST['sc_state']):'';
	$template->set('country_dropdown', $tax->countries_dropdown('sc_country', $sc_country, 'shipping_calculator_form', 'shipping_calculator', true, MSG_SELECT_COUNTRY));
	
	if ($tax->is_states($sc_country))
	{
		$template->set('state_dropdown', $tax->states_box('sc_state',$sc_state, $sc_country, 'shipping_calculator_form'));
		
		if ($sc_state)
		{
			$sc_disabled = '';
		}
	}
	else if ($sc_country)
	{
		$sc_disabled = '';
	}
	
	$sc_postage_value = null;
	if (isset($_REQUEST['form_calculate_postage']))
	{
		$sc_quantity = intval($_REQUEST['sc_quantity']);
		$sc_quantity = ($sc_quantity > 0) ? $sc_quantity : 1;
		
		$calc_postage = calculate_postage(null, $item_details['owner_id'], $item_details['auction_id'], null, $sc_country, $sc_state, $sc_quantity);
		$sc_postage_value = $calc_postage['postage'];
	}
	$template->set('sc_postage_value', $sc_postage_value);
	
	$template->set('sc_disabled', $sc_disabled);
	
	$shipping_calculator_box = $template->process('shipping_calculator_box.tpl.php');
	$template->set('shipping_calculator_box', $shipping_calculator_box);
	/* END -> shipping calculator box code snippet */

	if ($setts['enable_other_items_adp'])
	{
		$select_condition = "WHERE	a.active=1 AND a.closed=0 AND a.creation_in_progress=0 AND a.deleted=0 AND
			a.list_in!='store' AND a.owner_id=" . $item_details['owner_id'] . " AND a.auction_id!=" . $item_details['auction_id'];

		//$template->set('db', $db);## PHP Pro Bid v6.00 the design is handled in the mainpage.tpl.php file to allow liberty on skins design
		$other_items = $db->random_rows('auctions a', 'a.auction_id, a.name, a.start_price, a.max_bid, a.currency, a.end_time', $select_condition, $layout['hpfeat_nb']);
		$template->set('other_items', $other_items);
	}

	## add the search details back link if the auction was accessed through the search page.
	(string) $search_url = null;
	if (isset($_REQUEST['auction_search']) && $_REQUEST['auction_search'] == 1)
	{
		$additional_vars = '&option=' . $_REQUEST['option'] . '&src_auction_id=' . $_REQUEST['src_auction_id'] . '&keywords_search=' . $_REQUEST['keywords_search'] .
			'&buyout_price=' . $_REQUEST['buyout_price'] . '&reserve_price=' . $_REQUEST['reserve_price'] . 
			'&quantity=' . $_REQUEST['quantity'] . '&enable_swap=' . $_REQUEST['enable_swap'] . 
			'&list_in=' . $_REQUEST['list_in'] . '&results_view=' . $_REQUEST['results_view'] . 
			'&country=' . $_REQUEST['country'] . '&zip_code=' . $_REQUEST['zip_code'] . '&username=' . $_REQUEST['username'] . 
			'&basic_search=' . $_REQUEST['basic_search'];		
			
		$search_url = 'auction_search.php?start=0' . $additional_vars;
		$template->set('search_url', $search_url);	
	}
	
	$template->change_path('themes/' . $setts['default_theme'] . '/templates/');
	$template_output .= $template->process('auction_details.tpl.php');
	$template->change_path('templates/');
}
else if ($adult_cat)
{
	$template->set('categories_header_menu', $item_details['name']);
	$template->set('minimum_age', $category_details['minimum_age']);
	$template->set('auction_id', $item_details['auction_id']);
	
	$template_output .= $template->process('adult_category_warning.tpl.php');
}
else
{
	$template->set('message_header', header5(MSG_AUCTION_DETAILS_ERROR_TITLE));
	$template->set('message_content', '<p align="center">' . MSG_AUCTION_DETAILS_ERROR_CONTENT . '</p>');

	$template_output .= $template->process('single_message.tpl.php');
}

include_once ('global_footer.php');

echo $template_output;
?>
