<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

$current_version = '6.04';

(array) $setts = NULL;

define ('CURRENT_TIME', time());

define ('CURRENT_TIME_MYSQL', date("Y-m-d H:i:s", time()));

## add the site settings in an array
$setts = $db->get_sql_row("SELECT * FROM " . DB_PREFIX . "gen_setts LIMIT 1");

define('DEFAULT_THEME', $setts['default_theme']);
define('SITE_PATH', $setts['site_path']);

## this will be the font used in emails - obsolete
define('EMAIL_FONT', '<font face="Verdana, Arial, Helvetica" size="2">');

## add the layout settings in an array
$layout = $db->get_sql_row("SELECT * FROM " . DB_PREFIX . "layout_setts LIMIT 1");
$layout['nb_want_ads'] = ($setts['enable_wanted_ads']) ? $layout['nb_want_ads'] : 0;

$db->setts = &$setts;
$db->layout = &$layout;

$currentTime = time();

/*
 * Allow Language for cookie
 * */
$allowLanguage = array(
    "en" => "english",
    "fr" => "french",
    "de" => "german"
);

if (isset($_COOKIE['language']) && isset($allowLanguage[$_COOKIE['language']]))
{
    $session->set('site_lang', $allowLanguage[$_COOKIE['language']]);
}else
if (!$session->is_set('site_lang'))
{
	$session->set('site_lang', $setts['site_lang']);
//	$session->set('site_lang', "french");
}

if (!$session->is_set('site_theme'))
{
	$session->set('site_theme', $setts['default_theme']);
}

## now override setts['default_theme']
$setts['default_theme'] = ($setts['enable_skin_change']) ? $session->value('site_theme') : $setts['default_theme'];

if (!$setts['user_lang'])
{
	$session->set('site_lang', $setts['site_lang']);	
}
## load site and admin language files
$path = $fileExtension . 'language/' . $session->value('site_lang');
include_once ($fileExtension . 'language/' . $session->value('site_lang') . '/global.lang.php');
include_once ($fileExtension . 'language/' . $session->value('site_lang') . '/category.lang.php');
@include_once ($fileExtension . 'language/' . $session->value('site_lang') . '/reverse_category.lang.php');

if (IN_SITE == 1)
{
	include_once ($fileExtension . 'language/' . $session->value('site_lang') . '/site.lang.php');
}

if ( defined("IN_ADMIN") &&  IN_ADMIN == 1)
{
	include_once ($fileExtension . 'language/' . $setts['admin_lang'] . '/admin.lang.php');
}

include_once ($fileExtension . 'language/' . $session->value('site_lang') . '/categories_array.php');
@include_once ($fileExtension . 'language/' . $session->value('site_lang') . '/reverse_categories_array.php');


## date format will be drawn from a table, it is only temporary atm.
$date_format_row = $db->get_sql_row("SELECT value FROM " . DB_PREFIX . "dateformat WHERE active='checked'");

$datetime_format = $date_format_row['value'];
define ('DATETIME_FORMAT', $datetime_format);

$date_format = substr($datetime_format, 0, -6);
define ('DATE_FORMAT', $date_format);

## again a temporary setting

define ('TIME_OFFSET', $setts['time_offset']);

## process link procedure
if ($setts['is_mod_rewrite'])
{
    $valsArray = array();
    if (isset($_REQUEST['rewrite_params']))
        $valsArray = explode(',', $_REQUEST['rewrite_params']);
	$valsCnt = 0;
	$count_valsArray = count($valsArray);
	while ($valsCnt < $count_valsArray)
	{
		$_REQUEST[$valsArray[$valsCnt+1]] = $valsArray[$valsCnt];
		$_GET[$valsArray[$valsCnt+1]] = $valsArray[$valsCnt];
		$_POST[$valsArray[$valsCnt+1]] = $valsArray[$valsCnt];
		$valsCnt+=2;
	}
}

if (!eregi('sell_item.php', $_SERVER['PHP_SELF']) ||
(eregi('sell_item.php', $_SERVER['PHP_SELF']) && $_REQUEST['option'] == 'new_item') ||
(eregi('sell_item.php', $_SERVER['PHP_SELF']) && $_REQUEST['option'] == 'sell_similar'))
{
	$session->unregister('auction_id');
	$session->unregister('refresh_id');
}

if (!eregi('wanted_manage.php', $_SERVER['PHP_SELF']) && !eregi('category_selector.php', $_SERVER['PHP_SELF']))
{
	$session->unregister('wanted_ad_id');
	$session->unregister('wa_refresh_id');
}

if (!eregi('reverse_manage.php', $_SERVER['PHP_SELF']) && !eregi('category_selector.php', $_SERVER['PHP_SELF']))
{
	$session->unregister('reverse_id');
	$session->unregister('reverse_refresh_id');
}

if (!eregi('edit_item.php', $_SERVER['PHP_SELF']))
{
	$session->unregister('edit_refresh_id');
}

if (!eregi('bid.php', $_SERVER['PHP_SELF']))
{
	$session->unregister('bid_id');
}

if (!eregi('reverse_bid.php', $_SERVER['PHP_SELF']))
{
	$session->unregister('reverse_bid_id');
}

if (!eregi('buy_out.php', $_SERVER['PHP_SELF']))
{
	$session->unregister('buyout_id');
}

if (!eregi('make_offer.php', $_SERVER['PHP_SELF']))
{
	$session->unregister('make_offer_id');
}

if (!eregi('swap_offer.php', $_SERVER['PHP_SELF']))
{
	$session->unregister('swap_offer_id');
}

## add any functions that we dont want the user to see the contents of
if (isset($_GET['start']))
    $start = abs(intval($_GET['start'])); ## start wont be initialized on each page that needs it anymore, but only here


?>
