<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

session_start();

define ('IN_ADMIN', 1);
define ('IN_SITE', 1);

include_once ('../includes/global.php');
include_once ('../includes/class_formchecker.php');
include_once ('../includes/class_custom_field.php');
include_once ('../includes/class_fees.php');
include_once ('../includes/class_item.php');

if ($session->value('adminarea')!='Active')
{
	header_redirect('login.php');
}
else
{
	include_once ('header.php');

    $item = new item();
    $item->setts = &$setts;
    $item->layout = &$layout;

    $item_details = $db->get_sql_row("SELECT * FROM
		" . DB_PREFIX . "auctions WHERE auction_id='" . intval($_REQUEST['auction_id']) . "'");

    if ($_REQUEST['action'] == 'bid_confirm')
    {
        $max_bid = numeric_format($_REQUEST['max_bid']);
        $max_bid = ($max_bid>0 && is_numeric($max_bid)) ? $max_bid : 0;



        $quantity = $_REQUEST['quantity'];

        $proxy_details = $db->get_sql_row("SELECT * FROM " . DB_PREFIX . "proxybid WHERE
				auction_id=" . $item_details['auction_id']);

        $bid_result = $item->bid($max_bid, $quantity, intval($_REQUEST['users']), $item_details, $proxy_details);

        if($bid_result['result']){
            $bid_sucess_msg = ($bid_result['display']) ? $bid_result['display'] : MSG_BID_SUCCESSFUL;
        }
    }elseif($_REQUEST['action'] == 'delete_bid'){
        $bid_id = intval($_REQUEST['bid_id']);
        $bid_details = $db->get_sql_row("SELECT * FROM " . DB_PREFIX . "bids WHERE
			bid_id='" . $bid_id . "'");
        $bid_result = $item->hide_bid($bid_details['bid_id'], $bid_details['bidder_id']);

        if($bid_result['result']){
            $bid_sucess_msg = ($bid_result['display']) ? $bid_result['display'] : MSG_BID_SUCCESSFUL;
        }
    }

	$msg_changes_saved = '<p align="center" class="contentfont">' . AMSG_CHANGES_SAVED . '</p>';

	$form_submitted = false;

    $auction_details = $db->get_sql_row("SELECT auction_id, name, currency FROM
						" . DB_PREFIX . "auctions WHERE auction_id='" . intval($_REQUEST['auction_id']) . "'");

    $sql_select_bids = $db->query("SELECT b.bid_id, b.bid_amount, b.bid_date, b.quantity, b.bid_out, u.username, u.user_id FROM " . DB_PREFIX . "bids b
					LEFT JOIN " . DB_PREFIX . "users u ON b.bidder_id=u.user_id WHERE
					b.auction_id='" . intval($_REQUEST['auction_id']) . "' AND b.deleted=0");

	while ($bid_details = $db->fetch_array($sql_select_bids))
	{
		$background = ($counter++%2) ? 'c1' : 'c2';

		$bid_history_content .= '<tr class="' . $background . '"> '.
    		'	<td> ' . $bid_details['username'] . '</td>'.
			'	<td align="center">' . $fees->display_amount($bid_details['bid_amount'], $bid_details['currency']) . '</td>'.
    		'	<td align="center">' . show_date($bid_details['bid_date']) . '</td>'.
    		'	<td align="center" class="contentfont">' . $bid_details['quantity'] . '</td> '.
    		'	<td align="center" class="contentfont">' . field_display($bid_details['bid_out'], GMSG_ACTIVE, GMSG_INACTIVE) . '</td> ';

		if ($bid_details['closed']==0 && $bid_details['deleted']==0)
		{
			$bid_history_content .= '<td align="center" class="contentfont"> '.
				'	[ <a href="list_auctions_bids.php?action=delete_bid&bid_id=' . $bid_details['bid_id'] . '&auction_id='.$auction_details['auction_id'].'" onclick="return confirm(\'Are you sure you want to delete this bid?\');">Delete bid</a> ]</td>';
		}
		else
		{
			$bid_history_content .= '<td align=center>' . MSG_REMOVAL_IMPOSSIBLE . '</td>';
		}
		$bid_history_content .= '</tr> ';
	}

    $sql_select_user = $db->query("SELECT user_id, username FROM " . DB_PREFIX . "users ORDER BY username ASC");

    $users = '<select name="users">';
    while ($user = $db->fetch_array($sql_select_user)){
        $users .= '<option value="'.$user["user_id"].'" ';
        $users .= ($row_user['probid_user_id'] == $user["user_id"]) ? 'selected' : '';
        $users .= ' >'.$user["username"].'</option>';
    }
    $users .= '</select>';



	$template->set('bid_history_content', $bid_history_content);
    $template->set('bid_sucess_msg', $bid_sucess_msg);
    $template->set('item_details', $item_details);
    $template->set('item', $item);

	$template->set('header_section', AMSG_AUCTIONS_MANAGEMENT);
	$template->set('subpage_title', AMSG_VIEW_BIDS);
    $template->set('auction_details', $auction_details);
    $template->set('users', $users);

	$template_output .= $template->process('list_auctions_bids.tpl.php');

	include_once ('footer.php');

	echo $template_output;
}
?>
