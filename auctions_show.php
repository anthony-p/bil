<?
#################################################################
## PHP Pro Bid v6.05															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

session_start();

define ('IN_SITE', 1);
$GLOBALS['body_id'] = "auctions_show";

include_once ('includes/global.php');

include_once ('global_header.php');

$option = (in_array($_REQUEST['option'], array('featured', 'local', 'recent', 'popular', 'ending', 'localuser'))) ? $_REQUEST['option'] : 'popular';

switch ($option)
{
    case 'localuser':
        $user = $_REQUEST['user'];
        $np_userid = $_COOKIE['np_userid'];

        $np_username = $db->get_sql_field("SELECT username  FROM " . DB_PREFIX . "users WHERE user_id ='" . $user . "'", 'username');

        $page_header_msg = 'Browse Auctions from '.$np_username;
        $where_query = "WHERE a.active=1 AND a.approved=1 AND a.closed=0 AND a.deleted=0 AND
			a.list_in!='store' AND a.close_in_progress=0 AND a.hpfeat=1 AND a.npuser_id='$np_userid' AND owner_id = '$user'";

        $order_field = ($_REQUEST['order_field']) ? $_REQUEST['order_field'] : 'a.end_time';
        $order_type = ($_REQUEST['order_type']) ? $_REQUEST['order_type'] : 'ASC';

        break;
	case 'local':
        $np_userid = $_COOKIE['np_userid'];

		$page_header_msg = MSG_FEATURED_LOCALAUCTIONS;
		$where_query = "WHERE a.active=1 AND a.approved=1 AND a.closed=0 AND a.deleted=0 AND 
			a.list_in!='store' AND a.close_in_progress=0 AND a.hpfeat=1 AND a.npuser_id='$np_userid'";
		
		$order_field = ($_REQUEST['order_field']) ? $_REQUEST['order_field'] : 'a.end_time'; 
		$order_type = ($_REQUEST['order_type']) ? $_REQUEST['order_type'] : 'ASC'; 
		
		break;
	case 'featured':
		$page_header_msg = MSG_FEATURED_AUCTIONS;
		$where_query = "WHERE a.active=1 AND a.approved=1 AND a.closed=0 AND a.deleted=0 AND 
			a.list_in!='store' AND a.close_in_progress=0 AND a.hpfeat=1";
		
		$order_field = ($_REQUEST['order_field']) ? $_REQUEST['order_field'] : 'a.end_time'; 
		$order_type = ($_REQUEST['order_type']) ? $_REQUEST['order_type'] : 'ASC'; 
		
		break;
	case 'recent':
		$page_header_msg = MSG_RECENTLY_LISTED_AUCTIONS;
		$where_query = "WHERE a.closed=0 AND a.active=1 AND a.approved=1 AND a.deleted=0 AND 
			a.creation_in_progress=0 AND a.list_in!='store'";
		
		$order_field = ($_REQUEST['order_field']) ? $_REQUEST['order_field'] : 'a.start_time'; 
		$order_type = ($_REQUEST['order_type']) ? $_REQUEST['order_type'] : 'DESC'; 

		break;
	case 'popular':
		$page_header_msg = MSG_POPULAR_AUCTIONS;
		$where_query = "WHERE a.active=1 AND a.approved=1 AND a.closed=0 AND a.deleted=0 AND 
			a.list_in!='store' AND a.creation_in_progress=0 AND a.nb_bids>0 ";
		//"WHERE a.closed=0 AND a.active=1 AND a.approved=1 AND a.deleted=0 AND a.crea"
		$order_field = ($_REQUEST['order_field']) ? $_REQUEST['order_field'] : 'a.max_bid'; 
		$order_type = ($_REQUEST['order_type']) ? $_REQUEST['order_type'] : 'DESC'; 
		break;
	case 'ending':
		$page_header_msg = MSG_ENDING_SOON_AUCTIONS;
		$where_query = "WHERE a.active=1 AND a.approved=1 AND a.closed=0 AND a.deleted=0 AND 
			a.list_in!='store' AND a.creation_in_progress=0";

		$order_field = ($_REQUEST['order_field']) ? $_REQUEST['order_field'] : 'a.end_time'; 
		$order_type = ($_REQUEST['order_type']) ? $_REQUEST['order_type'] : 'ASC'; 
		break;
}

$header_browse_auctions = header5($page_header_msg);
/**
 * below we have the variables that need to be declared in each separate browse page
 */
$page_url = 'auctions_show';

$order_field = (in_array($order_field, $auction_ordering)) ? $order_field : 'a.end_time'; 
$order_type = (in_array($order_type, $order_types)) ? $order_type : 'ASC';

$additional_vars = '&option=' . $_REQUEST['option'];

include_once('includes/page_browse_auctions.php');

include_once ('global_footer.php');

echo $template_output;

?>
