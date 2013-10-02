<?
#################################################################
## PHP Pro Bid & PHP Pro Ads Integration v1.00						##
##-------------------------------------------------------------##
## Copyright ©2008 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

session_start();

define ('IN_SITE', 1);
define ('INDEX_UNIFIED_PAGE', 1); ## for integration

if (!file_exists('includes/config.php')) echo "<script>document.location.href='install/install.php'</script>";

include_once ('includes/global.php');

include_once ('includes/functions_login.php');
include_once ('includes/functions_item.php');

if (eregi('logout', $_GET['option']))
{
	logout();
}

$setts['default_theme'] = $integration['default_skin'];

$ppa_setts = $db->get_sql_row("SELECT * FROM " . $integration['ppa_db_prefix'] . "gen_setts LIMIT 0,1");
$ppa_layout = $db->get_sql_row("SELECT * FROM " . $integration['ppa_db_prefix'] . "layout_setts LIMIT 0,1");

$template->set('setts', $setts);
$template->set('integration', $integration);

## BEGIN -> header code
$time_start = getmicrotime();
$currentTime = time();

include ('integration_themes/'.$setts['default_theme'].'/title.php');
$template->change_path('integration_themes/' . $setts['default_theme'] . '/templates/');


$page_meta_tags = $meta_tags_details['meta_tags'];

$template->set('page_meta_tags', $page_meta_tags);

$current_date = date(DATE_FORMAT, time() + (TIME_OFFSET * 3600));
$template->set('current_date', $current_date);

$current_time_display = date("F d, Y H:i:s", time() + (TIME_OFFSET * 3600));
$template->set('current_time_display', $current_time_display);

if ($setts['user_lang'])
{
	$template->set('languages_list', list_languages('site', false, null, true));
}

$menu_box_header = header7(MSG_MEMBERS_AREA_TITLE);
$template->set('menu_box_header', $menu_box_header);

(string) $menu_box_content = NULL;

if (!$session->value('user_id') && $layout['d_login_box'] && $setts['is_ssl']!=1)
{
	$template->set('redirect', $db->rem_special_chars($_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']));

	$menu_box_content	= $template->process('header_login_box.tpl.php');
	$template->set('menu_box_content', $menu_box_content);
}
else if (!$session->value('user_id') && $layout['d_login_box'] && $setts['is_ssl']==1)
{
	$menu_box_content = '<p align="center" class="contentfont">[ <a href="'.process_link('login').'"><strong>'.MSG_LOGIN_SECURELY.'</strong></a> ]</p>';
	$template->set('menu_box_content', $menu_box_content);
}
if ($session->value('user_id'))
{
    $_user_data = $db->query("SELECT * FROM bl2_users WHERE id=" . $session->value('user_id'));
    $current_user_data = array();
    while ($result =  mysql_fetch_array($_user_data)) {
        $current_user_data[] = $result;
    }
    $current_user_identifier = (isset($current_user_data[0]["organization"]) && $current_user_data[0]["organization"]) ?
        $current_user_data[0]["organization"] : $current_user_data[0]["first_name"];
    $template->set('current_user_identifier', $current_user_identifier);
} else {
    $template->set('current_user_identifier', '');
}

$template_output .= $template->process('header.tpl.php');

## END -> header code

if (isset($_GET['change_language']))
{
	$all_languages = list_languages('site');

	if (in_array($_GET['change_language'], $all_languages))
	{
		$session->set('site_lang', $_GET['change_language']);
	}

	$refresh_link = 'index_unified.php';

	$template_output .= '<br><p class="contentfont" align="center">' . MSG_SITE_LANG_CHANGED . '<br><br>
		Please click <a href="' . process_link('index') . '">' . MSG_HERE . '</a> ' . MSG_PAGE_DOESNT_REFRESH . '</p>';
	$template_output .= '<script>window.setTimeout(\'changeurl();\',300); function changeurl(){window.location=\'' . $refresh_link . '\'}</script>';
}
else
{
	## BEGIN -> unified main page code
	if ($layout['hpfeat_nb'])## PHP Pro Bid v6.00 home page featured auctions
	{
		$featured_auctions_header = header1(MSG_FEATURED_AUCTIONS . ' [ <span class="sell"><a href="' . process_link('auctions_show', array('option' => 'featured')) . '">' . MSG_VIEW_ALL . '</a></span> ]');
		$template->set('featured_auctions_header', $featured_auctions_header);
	
		$select_condition = "WHERE
			hpfeat=1 AND active=1 AND approved=1 AND closed=0 AND creation_in_progress=0 AND deleted=0 AND
			list_in!='store'";## PHP Pro Bid v6.00 should use 'hpfeat' as index key
	
		$template->set('featured_columns', min((floor($db->count_rows('auctions', $select_condition)/$layout['hpfeat_nb']) + 1), ceil($layout['hpfeat_max']/$layout['hpfeat_nb'])));
	
		$template->set('feat_fees', $fees);
		$template->set('feat_db', $db);## PHP Pro Bid v6.00 the design is handled in the mainpage.tpl.php file to allow liberty on skins design
		//$table_rows = $db->count_rows('auctions', $select_condition);
		//$total_rows = ($table_rows > $layout['hpfeat_max']) ? $layout['hpfeat_max'] : $table_rows;
	
		$item_details = $db->random_rows('auctions', 'auction_id, name, start_price, max_bid, currency, end_time', $select_condition, $layout['hpfeat_max']);
		$template->set('item_details', $item_details);
	}
	
	if ($layout['nb_recent_auct'])
	{
		$recent_auctions_header = header2(MSG_RECENTLY_LISTED_AUCTIONS . ' [ <span class="sell"><a href="' . process_link('auctions_show', array('option' => 'recent')) . '">' . MSG_VIEW_ALL . '</a></span> ]');
		$template->set('recent_auctions_header', $recent_auctions_header);
	
		$sql_select_recent_items = $db->query("SELECT auction_id, start_time, start_price, currency, name,
			bold, hl, buyout_price, is_offer, reserve_price, max_bid, nb_bids, owner_id FROM " . DB_PREFIX . "auctions
			FORCE INDEX (auctions_start_time) WHERE
			closed=0 AND active=1 AND approved=1 AND deleted=0 AND creation_in_progress=0 AND
			list_in!='store' ORDER BY start_time DESC LIMIT 0," . $layout['nb_recent_auct']);
	
		$template->set('sql_select_recent_items', $sql_select_recent_items);
	}
	
	if ($layout['nb_popular_auct'])
	{
		$popular_auctions_header = header3(MSG_POPULAR_AUCTIONS . ' [ <span class="sell"><a href="' . process_link('auctions_show', array('option' => 'popular')) . '">' . MSG_VIEW_ALL . '</a></span> ]');
		$template->set('popular_auctions_header', $popular_auctions_header);
	
		$sql_select_popular_items = $db->query("SELECT auction_id, max_bid, currency, name, bold, hl,
			buyout_price, is_offer, reserve_price, nb_bids, owner_id FROM " . DB_PREFIX . "auctions
			FORCE INDEX (auctions_max_bid) WHERE
			closed=0 AND active=1 AND approved=1 AND deleted=0 AND creation_in_progress=0 AND
			list_in!='store' AND nb_bids>0 ORDER BY max_bid DESC LIMIT 0," . $layout['nb_popular_auct']);
	
		$template->set('sql_select_popular_items', $sql_select_popular_items);
		
		$is_popular_items = $db->num_rows($sql_select_popular_items);
		$template->set('is_popular_items', $is_popular_items);
	}
	
	if ($layout['nb_ending_auct'])
	{
		$ending_auctions_header = header4(MSG_ENDING_SOON_AUCTIONS . ' [ <span class="sell"><a href="' . process_link('auctions_show', array('option' => 'ending')) . '">' . MSG_VIEW_ALL . '</a></span> ]');
		$template->set('ending_auctions_header', $ending_auctions_header);
	
		 $sql_select_ending_items = $db->query("SELECT auction_id, max_bid, end_time, currency, name, bold,
			hl, buyout_price, is_offer, reserve_price, nb_bids, owner_id FROM " . DB_PREFIX . "auctions
			FORCE INDEX (auctions_end_time) WHERE
			closed=0 AND active=1 AND approved=1 AND deleted=0 AND creation_in_progress=0 AND
			list_in!='store' ORDER BY end_time ASC LIMIT 0," . $layout['nb_ending_auct']);
	
		$template->set('sql_select_ending_items', $sql_select_ending_items);
	}
	
	if ($layout['nb_want_ads'])
	{
		$recent_wa_header = header4(MSG_RECENTLY_LISTED_WANTED_ADS . ' [ <span class="sell"><a href="' . process_link('wanted_ads') . '">' . MSG_VIEW_ALL . '</a></span> ]');
		$template->set('recent_wa_header', $recent_wa_header);
	
		$sql_select_recent_wa = $db->query("SELECT wanted_ad_id, start_time, name FROM " . DB_PREFIX . "wanted_ads
			FORCE INDEX (wa_mainpage) WHERE
			closed=0 AND active=1 AND deleted=0 AND creation_in_progress=0 ORDER BY 
			start_time DESC LIMIT 0," . $layout['nb_want_ads']);
	
		$template->set('sql_select_recent_wa', $sql_select_recent_wa);
	}
	
	$mainpage_auctions_box .= $template->process('mainpage_auctions.tpl.php');
	$template->set('mainpage_auctions_box', $mainpage_auctions_box);
	
	$ppa_setts['default_theme'] = $integration['default_skin'];
	$template->set('setts', $ppa_setts);
	$template->set('layout', $ppa_layout);
	
	/* BEGIN: home page setup settings */
	$mp_hpfeat = mp_setts_show($ppa_setts['main_page_hpfeat'], true);
	$mp_popular = mp_setts_show($ppa_setts['main_page_popular'], true);
	$mp_recent = mp_setts_show($ppa_setts['main_page_recent'], true);
	
	$template->set('mp_hpfeat', $mp_hpfeat);
	$template->set('mp_popular', $mp_popular);
	$template->set('mp_recent', $mp_recent);
	/* END: home page setup settings */

	$db->db_prefix = $integration['ppa_db_prefix'];
	
	if ($ppa_layout['hpfeat_nb'])
	{
		(string) $mainpage_featured_box = null;
		
		foreach ($mp_hpfeat as $key => $value)
		{
			$ad_type_details = ad_type_inttostr($key);
			
			if ($ad_type_details['int'] == $key && $value)
			{
				$featured_ads_header = header1(MSG_FEATURED . ' ' . $ad_type_details['desc'] . ' [ <span class="sell"><a href="' . process_link($integration['ppa_url'] . 'ads_show', array('option' => 'featured', 'ad_type' => $ad_type_details['int'])) . '">' . MSG_VIEW_ALL . '</a></span> ]');
				$template->set('featured_ads_header', $featured_ads_header);
			
				$select_condition = "WHERE 
					ad_type='" . $ad_type_details['int'] . "' AND hpfeat=1 AND active=1 AND approved=1 AND closed=0 AND 
					creation_in_progress=0 AND deleted=0 AND list_in!=2";
			
				$template->set('featured_columns', min((floor($db->count_rows('items', $select_condition)/$layout['hpfeat_nb']) + 1), ceil($ppa_layout['hpfeat_max']/$ppa_layout['hpfeat_nb'])));
			
				$item_details = $db->random_rows('items', 'ad_type, item_id, name, price, currency, end_time', $select_condition, $ppa_layout['hpfeat_max']);
				$template->set('item_details', $item_details);
				
				$mainpage_featured_box .= $template->process('mainpage_featured.tpl.php');
				$template->set('mainpage_featured_box', $mainpage_featured_box);
			}
		}
	}
	
	if ($ppa_layout['nb_recent_ads'])
	{
		(string) $mainpage_recent_box = null;
		
		foreach ($mp_recent as $key => $value)
		{
			$ad_type_details = ad_type_inttostr($key);
			
			if ($ad_type_details['int'] == $key && $value)
			{
				$recent_ads_header = header2(MSG_RECENTLY_LISTED . ' ' . $ad_type_details['desc'] . ' [ <span class="sell"><a href="' . process_link($integration['ppa_url'] . 'ads_show', array('option' => 'recent', 'ad_type' => $ad_type_details['int'])) . '">' . MSG_VIEW_ALL . '</a></span> ]');
				$template->set('recent_ads_header', $recent_ads_header);
			
				$sql_select_recent_items = $db->query("SELECT i.item_id, i.start_time, i.price, i.currency, i.name,
					i.bold, i.hl, i.is_instant_purchase, i.is_offer, i.owner_id, i.list_in,  
					u.setting_start_time, u.user_id FROM " . $integration['ppa_db_prefix'] . "items i FORCE INDEX(main_page_recent)
					LEFT JOIN " . $integration['ppa_db_prefix'] . "users u ON u.user_id=i.owner_id WHERE
					i.ad_type='" . $ad_type_details['int'] . "' AND i.closed=0 AND i.active=1 AND i.approved=1 AND i.deleted=0 AND 
					i.creation_in_progress=0 AND i.list_in!=2 ORDER BY i.start_time DESC LIMIT 0," . $ppa_layout['nb_recent_ads']);
			
				$template->set('sql_select_recent_items', $sql_select_recent_items);
			
				$mainpage_recent_box .= $template->process('mainpage_recent.tpl.php');
				$template->set('mainpage_recent_box', $mainpage_recent_box);
			}
		}
	}
	
	if ($ppa_layout['nb_popular_ads'])
	{
		(string) $mainpage_popular_box = null;
		
		foreach ($mp_popular as $key => $value)
		{
			$ad_type_details = ad_type_inttostr($key);
			
			if ($ad_type_details['int'] == $key && $value)
			{
				$popular_ads_header = header3(MSG_POPULAR_ADS . ' ' . $ad_type_details['desc'] . ' [ <span class="sell"><a href="' . process_link($integration['ppa_url'] . 'ads_show', array('option' => 'popular', 'ad_type' => $ad_type_details['int'])) . '">' . MSG_VIEW_ALL . '</a></span> ]');
				$template->set('popular_ads_header', $popular_ads_header);
			
				$sql_select_popular_items = $db->query("SELECT item_id, price, nb_clicks, currency, name, 
					bold, hl, is_instant_purchase, is_offer, owner_id, list_in FROM " . $integration['ppa_db_prefix'] . "items FORCE INDEX (main_page_popular)
					WHERE	ad_type='" . $ad_type_details['int'] . "' AND closed=0 AND active=1 AND approved=1 AND deleted=0 AND 
					creation_in_progress=0 AND	list_in!=2 AND nb_clicks>0 ORDER BY nb_clicks DESC LIMIT 0," . $ppa_layout['nb_popular_ads']);
			
				$template->set('sql_select_popular_items', $sql_select_popular_items);
				
				$is_popular_items = $db->num_rows($sql_select_popular_items);
				$template->set('is_popular_items', $is_popular_items);
			
				$mainpage_popular_box .= $template->process('mainpage_popular.tpl.php');
				$template->set('mainpage_popular_box', $mainpage_popular_box);
			}
		}
	}
	
	$template_output .= $template->process('mainpage.tpl.php');
	
	## END -> unified main page code
}

$template->set('setts', $setts);
$template->set('layout', $layout);

## BEGIN -> footer code
$time_end = getmicrotime();

$time_passed = $time_end - $time_start;
$template->set('time_passed', number_format($time_passed, 6));

$template_output .= $template->process('footer.tpl.php');
## END -> footer code

$template->change_path('templates/');

echo $template_output;

?>
