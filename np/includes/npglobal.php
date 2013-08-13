<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

$fileExtension = (file_exists('includes/npglobal.php')) ? '' : '../';
include_once ('includes/npconfig.php');




 
/* Database and Session prefixes */
if (!defined('DB_PREFIX'))
    define('DB_PREFIX', 'probid_'); ## Do not edit !
if (!defined('NPDB_PREFIX'))
    define('NPDB_PREFIX', 'np_');
if (!defined('SESSION_PREFIX'))
    define('SESSION_PREFIX', 'np_');



define('INCLUDED', 1);

define('DEFAULT_DB_LANGUAGE', 'english');

function getmicrotime()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}

if(!function_exists('memory_get_usage'))
{
	function memory_get_usage()
	{
		if ( substr(PHP_OS,0,3) == 'WIN')
		{
			$output = array();
			exec( 'tasklist /FI "PID eq ' . getmypid() . '" /FO LIST', $output );

			return preg_replace( '/[\D]/', '', $output[5] ) * 1024;
		}
		else
		{
			//We now assume the OS is UNIX
			$pid = getmypid();
			exec("ps -eo%mem,rss,pid | grep $pid", $output);
			$output = explode("  ", $output[0]);
			//rss is given in 1024 byte units
			return $output[1] * 1024;
		}
	}
}

$time_start = getmicrotime();
##$memory_start = memory_get_usage();

include_once ($fileExtension.'language/'.DEFAULT_DB_LANGUAGE.'/db.lang.php');

include_once ($fileExtension.'includes/npclass_database.php');

$db = new npdatabase;

$db->connect($db_host, $db_username, $db_password);
$db->select_db($db_name);
include_once ($fileExtension.'includes/npclass_session.php'); ## global
## create the session class, will contain all session variables.
$session = new session;
include_once ($fileExtension.'includes/npinit.php'); ## global
define('RSS_FEED', 'http://themoneyconverter.com/' . $setts['currency'] . '/rss.xml');
## 6.06 mod -> override $setts['site_lang'] with $session->value('site_lang')
if ($session->is_set('site_lang'))
{
	$setts['site_lang'] = $session->value('site_lang');
}

$current_version = '6.07';

include_once ($fileExtension.'includes/functions_security.php');

cleanData();

/**
 * sanitize order_type and order_field variables
 */

$_REQUEST['order_type'] = (isset($_REQUEST['order_type']) && in_array($_REQUEST['order_type'], array('ASC', 'DESC'))) ? $_REQUEST['order_type'] : '';


if (!empty($_REQUEST['order_field']))
{
	$order_field_explode = explode(' ', $_REQUEST['order_field']);
	
	if (count($order_field_explode) > 1)
	{
		die('Fatal Error: Query Prohibited');
	}	
}

include_once ($fileExtension.'includes/functions_smtp.php'); ## global

include_once ($fileExtension.'includes/npfunctions.php'); ## global



## now do the theme alterations in case of categories and auction_details
$is_custom_skin = 0;
if (eregi('categories.php', $_SERVER['PHP_SELF']))
{
	$category_id = $db->main_category(intval($_GET['parent_id']));

	$is_custom_skin = 1;
}
else if (eregi('auction_details.php', $_SERVER['PHP_SELF']))
{
	$category_id = $db->get_sql_field("SELECT category_id FROM " . DB_PREFIX . "auctions WHERE 
		auction_id='" . intval($_GET['auction_id']) . "'", 'category_id');
	
	$category_id = $db->main_category($category_id);
	
	$is_custom_skin = 1;
}

if ($is_custom_skin)
{
	$custom_skin = $db->get_sql_field("SELECT custom_skin FROM " . DB_PREFIX . "categories WHERE 
		category_id='" . $category_id . "'", 'custom_skin');
	
	if (!empty($custom_skin))
	{
		$setts['default_theme'] = $custom_skin;
		define ('DEFAULT_THEME', $custom_skin);
	}
}

unlink_pin();

include_once ($fileExtension.'includes/npclass_template.php');

## initialize the template for the output that will be generated
$template = new template('templates/');

$setts['ga_code'] = $db->add_special_chars($setts['ga_code']);

$template->set('setts', $setts);
$template->set('layout', $layout);
$template->set('current_version', $current_version);
$template->set('is_seller', $session->value('is_seller'));

/* banner positions -> skin dependant */
$setts['banner_positions'] = array(
	0 => GMSG_BANNER_POSITION_0,
	1 => GMSG_BANNER_POSITION_1,
	2 => GMSG_BANNER_POSITION_2,
	3 => GMSG_BANNER_POSITION_3,
	4 => GMSG_BANNER_POSITION_4,
	5 => GMSG_BANNER_POSITION_5
);

$template->set('banner_positions', $setts['banner_positions']);

(string) $template_output = NULL;

if ($setts['maintenance_mode'] && $session->value('adminarea')!='Active' && IN_ADMIN != 1)
{
	$template_output = $template->process('maintenance_splash_page.tpl.php');

	echo $template_output;
	die();
}

include_once ($fileExtension.'includes/npclass_voucher.php');
include_once ($fileExtension.'includes/npclass_fees_main.php');
include_once ($fileExtension.'includes/npclass_tax.php');

$fees = new npfees_main();
$fees->setts = &$setts;
$template->set('fees', $fees);

$template->set('db', $db);

/* remember me option */
$s_username = $session->value('username');
$c_username = $session->cookie_value('username_cookie');

//if ($session->value('remember_username') == 1 && !empty($s_username) && empty($c_username))
if ($session->value('remember_username') == 1)
{
	$session->set_cookie('username_cookie', $session->value('username'));
}

$username_cookie = $session->cookie_value('username_cookie');
if (!empty($username_cookie) && !$session->value('user_id'))
{
	include_once ($fileExtension.'includes/npclass_fees.php');
	include_once ($fileExtension.'includes/npfunctions_login.php');
	
	$signup_fee = new fees();
	$signup_fee->setts = &$setts;
	
	$login_output = login_user($session->cookie_value('username_cookie'), '', '', true);

	$session->set('membersarea', $login_output['active']);
	$session->set('username', $login_output['username']);
	$session->set('user_id', $login_output['user_id']);
	$session->set('is_seller', $login_output['is_seller']);

	$session->set('temp_user_id', $login_output['temp_user_id']); /* for use with activate_account.php only! */	
}

include_once ($fileExtension.'includes/npclass_banner.php');

## classes used in most files will be initialized here.

include_once ($fileExtension.'includes/npfunctions_date.php');

## declare all the pages that can contain custom fields
$custom_fields_pages = array ('register', 'reputation_sale', 'reputation_purchase', 
	'auction', 'wanted_ad', 'reverse', 'provider_profile', 'reputation_poster', 'reputation_provider'
);

## custom section pages
$custom_section_pages = array ('help', 'news', 'faq', 'custom_page', 'announcements');
$custom_section_pages_ordering = array('help', 'faq', 'custom_page');

$custom_pages = array('about_us', 'contact_us', 'terms', 'privacy');

## declare all tables that are linkable to custom fields
$linkable_tables = array('countries');

## load the cron if it is run from the site.
if ($setts['cron_job_type'] == 2 && (!defined('IN_ADMIN') || IN_ADMIN != 1))
{
	$manual_cron = true;
	
}

$auction_ordering = array('a.name', 'a.start_price', 'a.max_bid', 'a.nb_bids', 'a.end_time', 'a.start_time', 'current_price');
$order_types = array('DESC', 'ASC');
## suspend user accounts that are over the debit limit. -> placeholder

### IP Logging addon, created by Kevin

if ($session->value('user_id') > 0) 

{
	$set = 0;
	
	/*
	$db->query("CREATE TABLE IF NOT EXISTS " . NPDB_PREFIX . "iphistory (
		`memberid` INT NOT NULL, 
		`time1` INT NOT NULL, 
		`time2` INT NOT NULL, 
		`ip` VARCHAR(20) NOT NULL
	)");
	*/
	
	$sql_select_iphistory = $db->query("SELECT time1, time2, ip FROM " . NPDB_PREFIX . "iphistory WHERE 
		memberid='" . $session->value('user_id') . "' ORDER by time1 DESC LIMIT 1");
	
	if ($db->num_rows($sql_select_iphistory) > 0) 
	{
		if ($ip_details = $db->fetch_array($sql_select_iphistory)) 
		{
			if ($ip_details['ip'] == $_SERVER['REMOTE_ADDR']) 
			{
				$db->query("UPDATE " . NPDB_PREFIX . "iphistory SET time2='" . CURRENT_TIME . "' WHERE 
					time1='" . $ip_details['time1'] . "' AND ip='" . $ip_details['ip'] . "'");

				$set = 1;
			}
		}
	}
	
	if (!$set) 
	{
		$db->query("INSERT INTO " . NPDB_PREFIX . "iphistory VALUES 
			('" . $session->value('user_id') . "', '" . CURRENT_TIME . "', '0', '" . $_SERVER['REMOTE_ADDR'] . "')");
	}
}

#include_once ($fileExtension.'includes/class_shop.php');
#include_once ($fileExtension.'includes/functions_addons.php');

$reverse_sect_get = array('open', 'closed', 'scheduled', 'awarded');
$reverse_sect_provide = array('my_profile', 'my_bids', 'won');
$template->set('reverse_sect_get', $reverse_sect_get);
$template->set('reverse_sect_provide', $reverse_sect_provide);
?>
