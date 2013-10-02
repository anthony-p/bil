<?
#################################################################
## PHP Pro Bid v6.01															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

function nav_btns_position($submit = false, $save_draft = false)
{
	global $setts;
	(string) $display_output = null;

	$next_step_btn = ($submit) ? GMSG_LIST_NOW : GMSG_NEXT_STEP;

	$buttons = array(
		'previous' => '<input name="form_previous_step" type="submit" id="form_previous_step" value="' . GMSG_PREV_STEP . '" />',
		'next' => '<input name="form_next_step" type="submit" id="form_next_step" value="' . $next_step_btn . '" />', 
		'draft' => '<input name="form_save_draft" type="submit" id="form_save_draft" value="' . GMSG_SAVE_AS_DRAFT . '" />');

	if ($setts['sell_nav_position'] == 1)
	{
		$display_output = $buttons['previous'] . ' &nbsp; ' . $buttons['next'];
	}
	else if ($setts['sell_nav_position'] == 2)
	{
		$display_output = $buttons['next'] . ' &nbsp; ' . $buttons['previous'];
	}
	
	if ($save_draft)
	{
		$display_output .= ' &nbsp; ' . $buttons['draft'];
	}
	
	return $display_output;
}

function show_buyout($item_details) // max_bid, reserve_price, buyout_price, nb_bids
{
	global $layout, $setts;

	$output = false;

	if ($layout['enable_buyout'] && $setts['buyout_process'] == 1 && $item_details['buyout_price'] > 0 && $item_details['closed'] == 0)
	{
		if ($setts['always_show_buyout'])
		{
			$output = ($item_details['max_bid'] > $item_details['buyout_price']) ? false : true;
		}
		else
		{
			if ($item_details['nb_bids'] <= 0)
			{
				$output = true;
			}
			else
			{
				if ($item_details['reserve_price'])
				{
					$output = ($item_details['max_bid'] < $item_details['reserve_price']) ? true : false;
				}
				else
				{
					$output = false;
				}
			}
		}
	}

	return $output;
}

function show_makeoffer($item_details)
{
	global $layout, $setts;

	$output = false;

	if ($layout['enable_buyout'] && $setts['makeoffer_process'] == 1 && $item_details['closed'] == 0)
	{
		if ($setts['always_show_buyout'])
		{
			$output = ($item_details['max_bid'] > $item_details['offer_max']) ? false : true;
		}
		else
		{
			if ($item_details['nb_bids'] <= 0)
			{
				$output = true;
			}
			else
			{
				if ($item_details['reserve_price'])
				{
					$output = ($item_details['max_bid'] < $item_details['reserve_price']) ? true : false;
				}
				else
				{
					$output = false;
				}
			}
		}
	}

	return $output;
}

function item_pics($item_details)
{
	global $setts, $db, $session;
	(string) $display_output = null;

	$is_images = $db->count_rows('auction_media', "WHERE auction_id='" . $item_details['auction_id'] . "' AND
		media_type=1 AND upload_in_progress=0");

	if ($is_images)
	{
		$display_output .= '<img src="themes/' . $setts['default_theme'] . '/img/system/camera1.gif" border="0" align="absmiddle" alt="' . $item_details['name'] . '"> ';
	}
	
	$show_buyout = show_buyout($item_details);

	if ($setts['buyout_process'] == 1 && $show_buyout)
	{
		$image_link = '<img src="themes/' . $setts['default_theme'] . '/img/system/buyitnow25.gif" border="0" align="absmiddle" alt="' . GMSG_BUYOUT . '">';

		if ($session->value('user_id') != $item_details['owner_id'])
		{
			$display_output .= '<a href="' . process_link('buy_out', array('auction_id' => $item_details['auction_id'], 'name' => $item_details['name'])) . '">' . $image_link . '</a>';			
		}
		else 
		{
			$display_output .= $image_link;
		}
	}

	if ($setts['makeoffer_process'] == 1 && $item_details['is_offer'])
	{
		$image_link = '<img src="themes/' . $setts['default_theme'] . '/img/system/makeoffer25.gif" border="0" align="absmiddle" alt="' . GMSG_MAKE_OFFER . '">';
		
		if ($session->value('user_id') != $item_details['owner_id'])
		{
			$display_output .= '<a href="' . process_link('make_offer', array('auction_id' => $item_details['auction_id'], 'name' => $item_details['name'])) . '">' . $image_link . '</a>';
		}
		else 
		{
			$display_output .= $image_link;
		}
	}

	/*
	if ($item_details['postage_amount'] <= 0)
	{
		$display_output .= ' <img src="images/freeshipping.gif" border="0" align="absmiddle" alt="' . GMSG_FREE_SHIPPING . '"> ';		
	}
	*/
	
	return $display_output;
}
?>
