<?
#################################################################
## PHP Pro Bid & PHP Pro Ads Integration v1.00						##
##-------------------------------------------------------------##
## Copyright �2008 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

$imgarrow = "<img src='themes/".$setts['default_theme']."/img/arrow.gif' width='9' height='9' hspace='4'>";
$imgarrow2 = "<img src='themes/".$setts['default_theme']."/img/arrowb.gif' width='8' height='8' hspace='4'>1";
$imgarrowit = "<img src='themes/".$setts['default_theme']."/img/arr_it.gif' width='11' height='11' hspace='4' align='absmiddle'>";
$imgwarning = "<img src='themes/".$setts['default_theme']."/img/warning.gif' width='11' height='11' hspace='4'>";
$imgarrwhite = "<img src='themes/".$setts['default_theme']."/img/arrow1.gif' width='9' height='9' hspace='2' align='absmiddle'>";
$imgarritem = "";


function header1($head_title) {
	global $setts;
	return "<div class='barTitle'>$head_title</div>";
}

function header2($head_title) {
global $setts;
	return "<div class='barTitle'>$head_title</div>";
}

function header3($head_title) {
global $setts;
	return "<div class='barTitleNew'>$head_title</div>";
}

function header4($head_title) {
global $setts;
	return "<div class='barTitle'>$head_title</div>";
}

function header5($head_title) {
	global $setts;
	return "<div class='barTitleNew'>$head_title</div>";
}

function header6($head_title) {
	global $setts;
	return "<div class='barTitle barSiteNews'>$head_title</div>";
}

function header7($head_title) {
	global $setts;
	return "<div class='barTitle'>$head_title</div>";
}
function headercat($head_title) {
	global $setts;
	return "<div class='barTitle'>$head_title</div>";
}

function headerdetails($head_title) {
	global $setts;
	return "<div class='barTitleNew'>$head_title</div>";
}
$template->set('imgarrow', $imgarrow);

(string) $header_cell_width = null;
(int) $nb_header_buttons = 5;

## generate links
## -> index page
$index_link = process_link('index');
$template->set('index_link', $index_link);

$rss_link = process_link('rss_feed');
$template->set('rss_link', $rss_link);

$npregister_link = process_link('np/npregister');
$template->set('npregister_link', $npregister_link);

$nplogin_link = process_link('np/nplogin');
$template->set('nplogin_link', $nplogin_link);




if ($session->value('user_id'))
{
	$template->set('register_btn_msg', MSG_BTN_MEMBERS_AREA);
	$template->set('register_link', process_link('members_area'));

	$template->set('login_btn_msg', MSG_BTN_LOGOUT);
	$template->set('login_link', process_link('np/npindex', array('option' => 'logout')));
}
else
{
	$template->set('register_btn_msg', MSG_BTN_REGISTER);
	$template->set('register_link', process_link('register'));

	$template->set('login_btn_msg', MSG_BTN_LOGIN);
	$template->set('login_link', process_link('login'));
}


if (!$setts['enable_private_site'] || $session->value('is_seller'))
{
	$nb_header_buttons++;

	$template->set('place_ad_btn_msg', MSG_SELL_ITEM);

	if (!$session->value('user_id'))
	{
		$template->set('place_ad_link', process_link('login', array('redirect' => 'sell_item')));
	}
	else
	{
		$template->set('place_ad_link', process_link('sell_item', array('option' => 'new_item')));
	}
}

## display header banner
$site_banner = new npbanner();
$site_banner->setts = &$setts;

if (isset($_REQUEST['parent_id']))
    $parentId = $_REQUEST['parent_id'];
else
    $parentId = 0;

if (isset($_REQUEST['auction_id']))
    $actionId = $_REQUEST['auction_id'];
else
    $actionId = 0;

$template->set('banner_header_content', $site_banner->select_banner($_SERVER['PHP_SELF'], intval($parentId), intval($actionId)));

if ($setts['enable_stores'])
{
	$nb_header_buttons++;
}

if ($setts['enable_reverse_auctions'])
{
	$nb_header_buttons++;
}

if ($setts['enable_wanted_ads'])
{
	$nb_header_buttons++;
}

if (isset($integration['enable_integration']) && $integration['enable_integration'] == 1)
{
	$nb_header_buttons++;
}

$template->set('header_cell_width', round(100 / $nb_header_buttons) . '%');
?>
