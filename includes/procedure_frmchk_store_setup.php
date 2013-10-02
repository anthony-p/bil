<?
#################################################################
## PHP Pro Bid v6.00															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('FRMCHK_STORE_SETUP') ) { die("Access Denied"); }

$fv = new formchecker;

if ($frmchk_store_settings) ## store - main settings
{
	$fv->check_box($frmchk_details['shop_name'], MSG_STORE_NAME, array('field_empty', 'field_html'));
	$fv->check_box($frmchk_details['shop_mainpage'], MSG_STORE_DESCRIPTION, array('field_empty', 'field_js', 'field_iframes', 'invalid_html'));
	$fv->check_box($frmchk_details['shop_metatags'], MSG_STORE_META_KEYWORDS, array('field_html'));	
}

if ($frmchk_store_pages) ## store - store pages
{
	$fv->check_box($frmchk_details['shop_about'], MSG_STORE_ABOUT_PAGE, array('field_js', 'field_iframes', 'invalid_html'));
	$fv->check_box($frmchk_details['shop_specials'], MSG_STORE_SPECIALS, array('field_js', 'field_iframes', 'invalid_html'));
	$fv->check_box($frmchk_details['shop_shipping_info'], MSG_STORE_SHIPPING_INFO, array('field_js', 'field_iframes', 'invalid_html'));
	$fv->check_box($frmchk_details['shop_company_policies'], MSG_STORE_COMPANY_POILICIES, array('field_js', 'field_iframes', 'invalid_html'));
}

?>
