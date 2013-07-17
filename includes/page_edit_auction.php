<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('EDIT_AUCTION') ) { die("Access Denied"); }

(string) $edit_auction_content = null;

## BEGIN pages upload
## 1.- details section
$item_description_editor = "<script> \n" .
	" 	var oEdit1 = new InnovaEditor(\"oEdit1\"); \n" .
	" 	oEdit1.width=\"100%\";//You can also use %, for example: oEdit1.width=\"100%\" \n" .
	"	oEdit1.height=300; \n" .
	"	oEdit1.REPLACE(\"description_main\");//Specify the id of the textarea here \n" .
	"</script>";

$template->set('item_description_editor', $item_description_editor);

$template->set('setup_voucher_box', voucher_form('setup', $item_details['voucher_value'], false));

$template->set('main_category_display', category_navigator($item_details['category_id'], false));
$template->set('addl_category_display', category_navigator($item_details['addl_category_id'], false, true, null, null, GMSG_NONE_CAT));

if (!empty($item_details['voucher_value']))
{
	$voucher_details = $item->check_voucher($item_details['voucher_value'], 'setup');

	$template->set('check_voucher_message', $voucher_details['display']);
}

$edit_auction_content .= $template->process('sell_item_details.tpl.php');

## 2.- settings section
$selected_currency = ($item_details['currency']) ? $item_details['currency'] : $setts['currency'];
$template->set('currency_drop_down', $item->currency_drop_down('currency', $selected_currency, 'ad_create_form'));

$tax = new tax();

$can_add_tax = $tax->can_add_tax($item_details['owner_id'], $setts['enable_tax']);
$template->set('can_add_tax', $can_add_tax['can_add_tax']);

$template->set('duration_drop_down', $item->durations_drop_down('duration', $item_details['duration']));

if (IN_ADMIN != 1)
{
	$buyout_fee = $setup_fee->display_fee('buyout_fee', $user_details, $item_details['category_id'], $item_details['list_in'], $voucher_details);

	if ($buyout_fee['amount'])
	{
		$buyout_fee_expl_message = '(<b>+' . $fees->display_amount($buyout_fee['amount'], $setts['currency'], true) . '</b>)';
		$template->set('buyout_fee_expl_message', $buyout_fee_expl_message);
	}

	$makeoffer_fee = $setup_fee->display_fee('makeoffer_fee', $user_details, $item_details['category_id'], $item_details['list_in'], $voucher_details);

	if ($makeoffer_fee['amount'])
	{
		$makeoffer_fee_expl_message = '(<b>+' . $fees->display_amount($makeoffer_fee['amount'], $setts['currency'], true) . '</b>)';
		$template->set('makeoffer_fee_expl_message', $makeoffer_fee_expl_message);
	}

	$rp_fee = $setup_fee->display_fee('rp_fee', $user_details, $item_details['category_id'], $item_details['list_in'], $voucher_details);

	if ($rp_fee['amount'])
	{
		$rp_fee_expl_message = '(<b>+' . $fees->display_amount($rp_fee['amount'], $setts['currency'], true) . '</b>)';
		$template->set('rp_fee_expl_message', $rp_fee_expl_message);
	}

	$hpfeat_fee = $setup_fee->display_fee('hpfeat_fee', $user_details, $item_details['category_id'], $item_details['list_in'], $voucher_details);

	if ($hpfeat_fee['amount'])
	{
		$hpfeat_fee_expl_message = '(<b>+' . $fees->display_amount($hpfeat_fee['amount'], $setts['currency'], true) . '</b>)';
		$template->set('hpfeat_fee_expl_message', $hpfeat_fee_expl_message);
	}

	$catfeat_fee = $setup_fee->display_fee('catfeat_fee', $user_details, $item_details['category_id'], $item_details['list_in'], $voucher_details);

	if ($catfeat_fee['amount'])
	{
		$catfeat_fee_expl_message = '(<b>+' . $fees->display_amount($catfeat_fee['amount'], $setts['currency'], true) . '</b>)';
		$template->set('catfeat_fee_expl_message', $catfeat_fee_expl_message);
	}

	$hl_fee = $setup_fee->display_fee('hlitem_fee', $user_details, $item_details['category_id'], $item_details['list_in'], $voucher_details);

	if ($hl_fee['amount'])
	{
		$hl_fee_expl_message = '(<b>+' . $fees->display_amount($hl_fee['amount'], $setts['currency'], true) . '</b>)';
		$template->set('hl_fee_expl_message', $hl_fee_expl_message);
	}

	$bold_fee = $setup_fee->display_fee('bolditem_fee', $user_details, $item_details['category_id'], $item_details['list_in'], $voucher_details);

	if ($bold_fee['amount'])
	{
		$bold_fee_expl_message = '(<b>+' . $fees->display_amount($bold_fee['amount'], $setts['currency'], true) . '</b>)';
		$template->set('bold_fee_expl_message', $bold_fee_expl_message);
	}

	$custom_start_fee = $setup_fee->display_fee('custom_start_fee', $user_details, $item_details['category_id'], $item_details['list_in'], $voucher_details);

	if ($custom_start_fee['amount'])
	{
		$custom_start_fee_expl_message = '(<b>+' . $fees->display_amount($custom_start_fee['amount'], $setts['currency'], true) . '</b>)';
		$template->set('custom_start_fee_expl_message', $custom_start_fee_expl_message);
	}

	$picture_fee = $setup_fee->display_fee('picture_fee', $user_details, $item_details['category_id'], $item_details['list_in'], $voucher_details);

	if ($picture_fee['amount'])
	{
		$picture_fee_expl_message = '(<b>+' . $fees->display_amount($picture_fee['amount'], $setts['currency'], true) . '</b>)';
		$template->set('picture_fee_expl_message', $picture_fee_expl_message);
	}

	$video_fee = $setup_fee->display_fee('video_fee', $user_details, $item_details['category_id'], $item_details['list_in'], $voucher_details);

	if ($video_fee['amount'])
	{
		$video_fee_expl_message = '(<b>+' . $fees->display_amount($video_fee['amount'], $setts['currency'], true) . '</b>)';
		$template->set('video_fee_expl_message', $video_fee_expl_message);
	}
	
	$dd_fee = $setup_fee->display_fee('dd_fee', $user_details, $item_details['category_id'], $item_details['list_in'], $voucher_details);

	if ($dd_fee['amount'])
	{
		$dd_fee_expl_message = '(<b>+' . $fees->display_amount($dd_fee['amount'], $setts['currency'], true) . '</b>)';
		$template->set('dd_fee_expl_message', $dd_fee_expl_message);
	}
}

$start_date_box = date_form_field($item_details['start_time'], $start_time_id, 'ad_create_form');
$template->set('start_date_box', $start_date_box);

$end_date_box = date_form_field($item_details['end_time'], $end_time_id, 'ad_create_form');
$template->set('end_date_box', $end_date_box);

$custom_fld->new_table = false;
$custom_fld->field_colspan = 2;
$custom_sections_table = $custom_fld->display_sections($item_details, $page_handle, false, 0, $db->main_category($item_details['category_id']));

$template->set('custom_sections_table', $custom_sections_table);

/* start digital media code */
$dd_upload_manager = $item->upload_manager($item_details, 3, 'ad_create_form', true, false, true, $dd_fee_expl_message);
$template->set('dd_upload_manager', $dd_upload_manager);
/* end digital media code */

$item->show_free_images = true;

$image_upload_manager = $item->upload_manager($item_details, 1, 'ad_create_form', true, false, true, $picture_fee_expl_message);
$template->set('image_upload_manager', $image_upload_manager);

$video_upload_manager = $item->upload_manager($item_details, 2, 'ad_create_form', true, false, true, $video_fee_expl_message);
$template->set('video_upload_manager', $video_upload_manager);

$template->set('country_dropdown', $tax->countries_dropdown('country', $item_details['country'], 'ad_create_form', 'setup'));
$template->set('state_box', $tax->states_box('state', $item_details['state'], $item_details['country']));

$edit_auction_content .= $template->process('sell_item_settings.tpl.php');

## 3.- shipping section
$template->set('shipping_methods_drop_down', $item->shipping_methods_drop_down('type_service', $item_details['type_service']));

$direct_payments = $item->select_direct_payment($item_details['direct_payment'], $item_details['owner_id']);

$direct_payment_table = $template->generate_table($direct_payments, 4, 1, 1, '75%');
$template->set('direct_payment_table', $direct_payment_table);

$offline_payments = $item->select_offline_payment($item_details['payment_methods']);

$offline_payment_table = $template->generate_table($offline_payments, 4, 1, 1, '75%');
$template->set('offline_payment_table', $offline_payment_table);

$edit_auction_content .= $template->process('sell_item_shipping.tpl.php');

$template->set('edit_auction_content', $edit_auction_content);
## END pages upload
?>
