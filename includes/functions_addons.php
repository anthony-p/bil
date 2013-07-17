<?
#################################################################
## PHP Pro Bid v6.00															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
## (Mods-Store) -> Shopping Cart											##
#################################################################

$sc_id = intval($_REQUEST['sc_id']);

if (!eregi('shopping_cart.php', $_SERVER['PHP_SELF']))
{
	$session->unregister('cart_purchase_id');
}

function is_shopping_cart($item_id)
{
	global $db, $setts, $layout;	
	$output = false;
	
	$item_details = $db->get_sql_row("SELECT a.list_in, a.buyout_price, u.shop_active FROM " . DB_PREFIX . "auctions a, 
		" . DB_PREFIX . "users u WHERE a.owner_id=u.user_id AND a.auction_id=" . $item_id);
	
	$buyout_enabled = ($layout['enable_buyout'] && $setts['buyout_process'] == 1 && $item_details['buyout_price'] > 0) ? true : false;
	
	if ($setts['enable_stores'] && $item_details['list_in'] != 'auction' && $item_details['shop_active'] && $buyout_enabled)
	{
		$output = true;
	}
	
	return $output;	
}
?>
