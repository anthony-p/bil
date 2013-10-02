<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

session_start();

define ('IN_SITE', 1);
$GLOBALS['body_id'] = "npmembers_area";

include_once ('includes/npglobal.php');

include_once ('includes/npclass_formchecker.php');

include_once ('includes/npclass_custom_field.php');

include_once ('includes/npclass_user.php');

#include_once ('includes/npclass_fees.php');
#include_once ('includes/npclass_shop.php');
#include_once ('includes/npclass_item.php');
#include_once ('includes/npfunctions_item.php');
include_once ('includes/npfunctions_login.php');
#include_once ('includes/npclass_messaging.php');
#include_once ('includes/npclass_reputation.php');

if (!$session->value('user_id'))
{
	header_redirect('nplogin.php');
}
else
{
;
	$template->set('session', $session);

	(array) $summary_page_content = null;

	$default_landing_page = 'account';
	$default_landing_section = 'editinfo';

	$page = (!empty($_REQUEST['page'])) ? $_REQUEST['page'] : $default_landing_page;
	$section = (!empty($_REQUEST['section'])) ? $_REQUEST['section'] : $default_landing_section;

	$section = ($page == 'wanted_ads' && !$setts['enable_wanted_ads']) ? $default_landing_section : $section;
	$page = ($page == 'wanted_ads' && !$setts['enable_wanted_ads']) ? $default_landing_page : $page;

	$section = ($page == 'store' && !$setts['enable_stores']) ? $default_landing_section : $section;
	$page = ($page == 'store' && !$setts['enable_stores']) ? $default_landing_page : $page;

	$section = ($page == 'bulk' && !$setts['enable_bulk_lister']) ? $default_landing_section : $section;
	$page = ($page == 'bulk' && !$setts['enable_bulk_lister']) ? $default_landing_page : $page;

	$section = ($page == 'reverse' && !$setts['enable_reverse_auctions']) ? $default_landing_section : $section;
	$page = ($page == 'reverse' && !$setts['enable_reverse_auctions']) ? $default_landing_page : $page;

	/* if account is suspended, only account related pages are active */
	if ($session->value('membersarea') == 'Active')
	{

		if (!$session->value('is_seller') && in_array($page, array('selling', 'bulk', 'store')))
		{
			$page = 'bidding';
			$section = 'current_bids';

			$template->set('msg_seller_error', '<p align="center">' . MSG_NO_SELLING_CAPABILITIES . '</p>');
		}
	}
	else
	{
		$page = 'account';
		$section = (in_array($section, array('editinfo', 'management', 'invoices', 'mailprefs'))) ? $section : 'management';
	}
	require ('npglobal_header.php');

	$msg_changes_saved = '<p align="center" class="contentfont">' . MSG_CHANGES_SAVED . '</p>';

	$limit = 20;












	$order_type = ($_REQUEST['order_type']) ? $_REQUEST['order_type'] : 'DESC';

	$additional_vars = '&page=' . $page . '&section=' . $section;
	$order_link = '&order_field=' . $order_field . '&order_type=' . $order_type;
	$limit_link = '&start=' . $start . '&limit=' . $limit;

	$template->set('page', $page);
	$template->set('section', $section);

#	$item = new item();
	$item->setts = &$setts;
	$item->layout = &$layout;



	

	

	
	

	/* members tips code snippet */
	if ($session->value('is_seller'))
	{
		$show_tips = $db->count_nprows('users', "WHERE user_id='" . $session->value('user_id') . "' AND notif_a=0");

		if ($show_tips)
		{
			$msg_member_tips = '<p class="errormessage">' . MSG_MEMBER_TIPS_A . '<br>' . MSG_MEMBER_TIPS_B . '</p>';
			$db->query("UPDATE " . NPDB_PREFIX . "users SET notif_a=1 WHERE user_id='" . $session->value('user_id') . "'");
		}
		$template->set('msg_member_tips', $msg_member_tips);
	}

	if (isset($_REQUEST['form_download_proceed']))
	{
		$download_result = download_redirect($_REQUEST['winner_id'], $session->value('user_id'));

		if ($download_result['redirect'])
		{
			header('Location: ' . $download_result['url']);
		}

		$template->set('msg_changes_saved', '<p align="center">' . $download_result['display'] . '</p>');
		$page = 'bidding';
		$section = 'won_items';
	}

	

	

	$src_transactions_query = null;
	$src_auctions_query = null;
	
	

	
	

	if ($page == 'account' || $page == 'summary') /* BEGIN -> MY ACCOUNT SECTION */
	{
		if ($section == 'editinfo') /* BEGIN -> PERSONAL INFORMATION PAGE */
		{
			$page_handle = 'register'; /* this page is related to users, so the page handle for custom fields is "register" */

			$row_user = $db->get_sql_row("SELECT * FROM
				" . NPDB_PREFIX . "users WHERE user_id=" . $session->value('user_id'));

			$username = $row_user['username']; /* the readonly field will not be altered */

			if ($_POST['edit_refresh'] == 1)
			{
				$row_user = $_POST;
				$row_user['username'] = $username;
			}

			$user = new npuser();
			$user->setts = &$setts;

			$tax = new nptax();
			$tax->setts = &$setts;

			if ($_REQUEST['operation'] == 'submit')
			{
				$user->save_vars($_POST);
				define ('FRMCHK_USER', 1);
				(bool) $frmchk_user_edit = 1;
				$frmchk_details = $_POST;

				$row_user = $_POST;
				$row_user['username'] = $username; /* the readonly field will not be altered */

				include ('includes/npprocedure_frmchk_user.php'); /* Formchecker for user creation/edit */

				if ($fv->is_error())
				{
					$template->set('display_formcheck_errors', $fv->display_errors());
				}
				else
				{
					$form_submitted = true;
#include 'includes/npgeocode_user.php';
					$template->set('msg_changes_saved', $msg_changes_saved);

					$new_password = ($_POST['password'] == $_POST['password2'] && !empty($_POST['password'])) ? $_POST['password'] : null;

					$user->update($session->value('user_id'), $_POST, $new_password);
				}
			}

			if (!$form_submitted)
			{
				if ($_REQUEST['operation'] != 'submit')
				{
					$user->save_edit_vars($session->value('user_id'), $page_handle);
				}

				$template->set('edit_user', 1);
				$template->set('edit_disabled', 'disabled'); /* some fields in the registration will be disabled for editing */

				$email_check_value = ($_POST['email_check']) ? $_POST['email_check'] : $row_user['email'];
				$template->set('email_check_value', $email_check_value);

				if (isset($_POST['tax_account_type']))
				{
					$row_user['tax_account_type'] = $_POST['tax_account_type'];
				}

				$template->set('user_details', $row_user);
				$template->set('do', $_REQUEST['do']);

	      	//$header_registration_message = headercat('<b>' . MSG_MM_MY_ACCOUNT . ' - ' . MSG_MM_PERSONAL_INFO . '</b>');
				//$template->set('header_registration_message', $header_registration_message);

				$template->set('proceed_button', GMSG_UPDATE_BTN);

				$template->set('country_dropdown', $tax->countries_dropdown('country', $row_user['country'], 'registration_form'));
				$template->set('state_box', $tax->states_box('state', $row_user['state'], $row_user['country']));

				$custom_sections_table = $user->display_sections($row_user, $page_handle);
				$template->set('custom_sections_table', $custom_sections_table);

#				$template->set('display_direct_payment_methods', $user->direct_payment_methods_edit($row_user));

				$members_area_page_content = $template->process('npregister.tpl.php');
				$template->set('members_area_page_content', $members_area_page_content);
			}
		} /* END -> PERSONAL INFORMATION PAGE */
		else if ($section == 'management' || $page == 'summary') /* BEGIN -> MANAGE ACCOUNT PAGE */
		{
			$user = new user();
			$user->setts = &$setts;
			$tax = new tax();

			if ($_REQUEST['operation'] == 'submit')
			{
				$form_submitted = false;

				$template->set('msg_changes_saved', $msg_changes_saved);

				/**
				 * all fee payments will be redirected to the new fee_payment.php file, which will
				 * have different options on how payments will be handled
				 */

				$sql_update_pg_details = $db->query("UPDATE " . NPDB_PREFIX . "users SET
					default_bank_details='" . $db->rem_special_chars($_POST['default_bank_details']) . "',
					pg_paypal_email = '" . $db->rem_special_chars($_POST['pg_paypal_email']) . "',
					pg_worldpay_id = '" . $db->rem_special_chars($_POST['pg_worldpay_id']) . "',
					pg_checkout_id = '" . $db->rem_special_chars($_POST['pg_checkout_id']) . "',
					pg_nochex_email = '" . $db->rem_special_chars($_POST['pg_nochex_email']) . "',
					pg_ikobo_username = '" . $db->rem_special_chars($_POST['pg_ikobo_username']) . "',
					pg_ikobo_password = '" . $db->rem_special_chars($_POST['pg_ikobo_password']) . "',
					pg_protx_username = '" . $db->rem_special_chars($_POST['pg_protx_username']) . "',
					pg_protx_password = '" . $db->rem_special_chars($_POST['pg_protx_password']) . "',
					pg_authnet_username = '" . $db->rem_special_chars($_POST['pg_authnet_username']) . "',
					pg_authnet_password = '" . $db->rem_special_chars($_POST['pg_authnet_password']) . "',
					pg_mb_email = '" . $db->rem_special_chars($_POST['pg_mb_email']) . "',
					paypal_address_override = '" . intval($_POST['paypal_address_override']) . "',
					paypal_first_name = '" . $db->rem_special_chars($_POST['paypal_first_name']) . "',
					paypal_last_name = '" . $db->rem_special_chars($_POST['paypal_last_name']) . "',
					paypal_address1 = '" . $db->rem_special_chars($_POST['paypal_address1']) . "',
					paypal_address2 = '" . $db->rem_special_chars($_POST['paypal_address2']) . "',
					paypal_city = '" . $db->rem_special_chars($_POST['paypal_city']) . "',
					paypal_state = '" . $db->rem_special_chars($_POST['paypal_state']) . "',
					paypal_zip = '" . $db->rem_special_chars($_POST['paypal_zip']) . "',
					paypal_country = '" . $db->rem_special_chars($_POST['paypal_country']) . "',
					paypal_night_phone_a = '" . $db->rem_special_chars($_POST['paypal_night_phone_a']) . "',
					paypal_night_phone_b = '" . $db->rem_special_chars($_POST['paypal_night_phone_b']) . "',
					paypal_night_phone_c = '" . $db->rem_special_chars($_POST['paypal_night_phone_c']) . "',
					pg_paymate_merchant_id = '" . $db->rem_special_chars($_POST['pg_paymate_merchant_id']) . "',
					pg_gc_merchant_id = '" . $db->rem_special_chars($_POST['pg_gc_merchant_id']) . "',
					pg_gc_merchant_key = '" . $db->rem_special_chars($_POST['pg_gc_merchant_key']) . "',
					pg_amazon_access_key = '" . $db->rem_special_chars($_POST['pg_amazon_access_key']) . "',
					pg_amazon_secret_key = '" . $db->rem_special_chars($_POST['pg_amazon_secret_key']) . "',
					pg_alertpay_id = '" . $db->rem_special_chars($_POST['pg_alertpay_id']) . "',
					pg_alertpay_securitycode = '" . $db->rem_special_chars($_POST['pg_alertpay_securitycode']) . "'
					WHERE	user_id=" . $session->value('user_id'));
			}

			if (!$form_submitted)
			{
				$row_user = $db->get_sql_row("SELECT * FROM
					" . NPDB_PREFIX . "users WHERE user_id=" . $session->value('user_id'));

				if ($_POST['refresh'] == 1)
				{
					foreach ($_POST as $key => $value)
					{
						$row_user[$key] = $value;
					}
				}

				$template->set('user_details', $row_user);
				$template->set('do', $_REQUEST['do']);

				$template->set('countries_drop_down', paypal_countries_drop_down($row_user['paypal_country']));

				$header_registration_message = headercat('<b>' . MSG_MM_MY_ACCOUNT . ' - ' . MSG_MM_MANAGE_ACCOUNT . '</b>');

				$template->set('header_registration_message', $header_registration_message);

				$template->set('display_account_status', $user->account_status($row_user['active'], $row_user['approved']));

				$user_payment_mode = $fees->user_payment_mode($session->value('user_id'));
				$template->set('user_payment_mode', $user_payment_mode);

				$template->set('display_payment_mode', $user->payment_mode_desc($user_payment_mode));

				(string) $display_balance_details = null;
				$display_balance_details = $user->show_balance($row_user['balance'], $setts['currency']);

				if ($user_payment_mode == 2 && $row_user['balance']>=$setts['min_invoice_value'])
				{
					$display_balance_details .= ' [ <a href="fee_payment.php?do=clear_balance">' . MSG_CLEAR_ACC_BALANCE . '</a> ]';
				}

				$template->set('display_balance_details', $display_balance_details);

#				$template->set('display_direct_payment_methods', $user->direct_payment_methods_edit($row_user));
				$template->set('proceed_button', GMSG_UPDATE_BTN);

				$members_area_page_content = $template->process('npmembers_area_manage_account.tpl.php');

				if ($page == 'summary')
				{
					$summary_page_content['manage_account'] = $members_area_page_content;
				}
				else
				{
					$template->set('members_area_page_content', $members_area_page_content);
				}
			}
		} /* END -> MANAGE ACCOUNT PAGE */
		else if ($section == 'history')
		{
			$user = new user();
			$user->setts = &$setts;

			$row_user = $db->get_sql_row("SELECT * FROM
				" . NPDB_PREFIX . "users WHERE user_id=" . $session->value('user_id'));

			$template->set('user_details', $row_user);

			$template->set('display_account_status', $user->account_status($row_user['active'], $row_user['approved']));

			$user_payment_mode = $fees->user_payment_mode($session->value('user_id'));
			$template->set('user_payment_mode', $user_payment_mode);

			$template->set('display_payment_mode', $user->payment_mode_desc($user_payment_mode));

			(string) $display_balance_details = null;
			$display_balance_details = $user->show_balance($row_user['balance'], $setts['currency']);

			if ($user_payment_mode == 2 && $row_user['balance']>=$setts['min_invoice_value'])
			{
				$display_balance_details .= ' [ <a href="fee_payment.php?do=clear_balance">' . MSG_CLEAR_ACC_BALANCE . '</a> ]';
			}

			$template->set('display_balance_details', $display_balance_details);

			$show_history_table = false;

			if (isset($_POST['form_display_history']) || $_REQUEST['do'] == 'view_history')
			{
				$show_history_table = true;

				$additional_vars .= '&do=view_history&date1_month=' . $_REQUEST['date1_month'] .
					'&date1_year=' . $_REQUEST['date1_year'] . '&date1_day=' . $_REQUEST['date1_day'] .
					'&date2_month=' . $_REQUEST['date2_month'] . '&date2_year=' . $_REQUEST['date2_year'] .
					'&date2_day=' . $_REQUEST['date2_day'];

				$history_details['start_time'] = get_box_timestamp($_REQUEST, 1);
				$history_details['start_time'] = ($history_details['start_time'] > 0) ? $history_details['start_time'] : 0;

				$history_details['end_time'] = get_box_timestamp($_REQUEST, 2);
				$history_details['end_time'] = ($history_details['end_time'] > 0 && $history_details['end_time'] <= CURRENT_TIME) ? $history_details['end_time'] : CURRENT_TIME;

				$date_query = "AND invoice_date>=" . $history_details['start_time'] . " AND invoice_date<='" . $history_details['end_time'] . "'";## PHP Pro Bid v6.00 we will generate the history table here.## PHP Pro Bid v6.00 first we select all auction invoices (account mode)## PHP Pro Bid v6.00 then we select all live fees (auction fees, store fees, signup fees)## PHP Pro Bid v6.00 we will only generate invoices for fees, not for payments, so only if invoice_amount>0 => invoice
				$invoices_query = "SELECT *, sum(amount) AS invoice_amount FROM " . DB_PREFIX . "invoices WHERE
					live_fee=0 AND item_id>0 AND user_id='" . $session->value('user_id') . "' " . $date_query . "
					GROUP BY item_id
					UNION
					SELECT *, sum(amount) AS invoice_amount FROM " . DB_PREFIX . "invoices WHERE
					live_fee=0 AND wanted_ad_id>0 AND user_id='" . $session->value('user_id') . "' " . $date_query . "
					GROUP BY wanted_ad_id
					UNION
					SELECT *, sum(amount) AS invoice_amount FROM " . DB_PREFIX . "invoices WHERE
					live_fee=0 AND reverse_id>0 AND user_id='" . $session->value('user_id') . "' " . $date_query . "
					GROUP BY reverse_id
					UNION
					SELECT *, amount AS invoice_amount FROM " . DB_PREFIX . "invoices WHERE
					live_fee=1 AND user_id='" . $session->value('user_id') . "' " . $date_query;

				$nb_invoices = $db->get_sql_number($invoices_query);
				$template->set('nb_invoices', $nb_invoices);

				$sql_select_invoices = $db->query($invoices_query . " ORDER BY invoice_id DESC LIMIT " . $start . ", " . $limit);

				(string) $history_table_content = null;

				while ($invoice_details = $db->fetch_array($sql_select_invoices))
				{
					$background = ($counter++%2) ? 'c1' : 'c2';

					$history_row = $item->history_row($invoice_details);

					$history_table_content .= '<tr class="' . $background . ' contentfont"> '.
	      			'	<td align="center">' . $history_row['item_id'] . '</td> '.
	      			'	<td>' . $history_row['invoice_name'] . '</td> '.
	      			'	<td align="center">' . $history_row['payment_type'] . '</td> '.
	      			'	<td align="center">' . $history_row['date'] . '</td> '.
	      			'	<td align="center">' . $history_row['amount'] . '</td> '.
	      			//'	<td align="center">' . $history_row['balance'] . '</td> '.
	   				'</tr>';
				}

				$template->set('history_table_content', $history_table_content);
			}

			$template->set('show_history_table', $show_history_table);

			$start_date_box = date_form_field($history_details['start_time'], 1, 'account_history_form', false);
			$template->set('start_date_box', $start_date_box);

			$end_date_box = date_form_field($history_details['end_time'], 2, 'account_history_form', false);
			$template->set('end_date_box', $end_date_box);

			$pagination = paginate($start, $limit, $nb_invoices, 'npmembers_area.php', $additional_vars);
			$template->set('pagination', $pagination);

			$members_area_page_content = $template->process('npmembers_area_account_history.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);
		}
		else if ($section == 'mailprefs')
		{
			if (isset($_POST['form_save_settings']))
			{
				$template->set('msg_changes_saved', $msg_changes_saved);

				$item->update_mailprefs($_POST, $session->value('user_id'));
			}

			$mail_prefs = $db->get_sql_row("SELECT * FROM " . NPDB_PREFIX . "users WHERE
				user_id='" . $session->value('user_id') . "'");

			$template->set('mail_prefs', $mail_prefs);

			$members_area_page_content = $template->process('npmembers_area_account_mailprefs.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);

		}
		else if ($section == 'abuse_report')
		{
			$form_submitted = false;

			if (isset($_POST['form_add_abuse_report']))
			{
				$post_details = $db->rem_special_chars_array($_POST);

				$is_user = $db->count_nprows('users', "WHERE username='" . $post_details['abuser_username'] . "'");

				if ($is_user)
				{
					$form_submitted = true;

					$db->query("INSERT INTO " . DB_PREFIX . "abuses
						(user_id, abuser_username, comment, reg_date, auction_id) VALUES
						('" . $session->value('user_id') . "', '" . $post_details['abuser_username'] . "',
						'" . $post_details['comment'] . "', '" . CURRENT_TIME . "', '" . intval($post_details['auction_id']) . "')");

					/**
					 * email a notification to the admin regarding the abuse report
					 */
					$mail_input_id = $db->insert_id();
					include_once('language/' . $setts['site_lang'] . '/mails/npabuse_report_notification.php');


					$template->set('msg_changes_saved', '<p align="center">' . MSG_ABUSE_REPORT_ADDED . '</p>');
				}
				else
				{
					$display_formcheck_errors = '<tr> '.
					'	<td align="center">' . MSG_ERROR_USER_DOESNT_EXIST . '</td> '.
					'</tr> ';
					$template->set('display_formcheck_errors', $display_formcheck_errors);
				}
			}

			if (!$form_submitted)
			{
				$auction_id = intval($_REQUEST['auction_id']);
				$template->set('auction_id', $auction_id);

				if ($auction_id)
				{
					$item_details = $db->get_sql_row("SELECT a.name, u.username FROM " . DB_PREFIX . "auctions a,
						" . NPDB_PREFIX . "users u WHERE u.user_id=a.owner_id AND a.auction_id='" . $auction_id . "'");
					$template->set('item_details', $item_details);
				}

				$template->set('post_details', $post_details);

				$members_area_page_content = $template->process('npmembers_area_account_abuse_report.tpl.php');
				$template->set('members_area_page_content', $members_area_page_content);
			}
		}
		else if ($section == 'refund_requests')
		{
			$user = new user();
			$user->setts = &$setts;

			$row_user = $db->get_sql_row("SELECT * FROM
				" . NPDB_PREFIX . "users WHERE user_id=" . $session->value('user_id'));

			$invoices_query = "SELECT * FROM " . DB_PREFIX . "invoices WHERE
				refund_request!=0 AND user_id='" . $session->value('user_id') . "'";

			$nb_invoices = $db->get_sql_number($invoices_query);
			$template->set('nb_invoices', $nb_invoices);

			$sql_select_invoices = $db->query($invoices_query . " ORDER BY refund_request_date DESC LIMIT " . $start . ", " . $limit);

			(string) $history_table_content = null;

			while ($invoice_details = $db->fetch_array($sql_select_invoices))
			{
				$background = ($counter++%2) ? 'c1' : 'c2';

				$refunds_table_content .= '<tr class="' . $background . ' contentfont"> '.
					'	<td align="center">' . $invoice_details['item_id'] . '</td> '.
					'	<td>' . $invoice_details['name'] . '</td> '.
					'	<td align="center">' . $fees->display_amount($invoice_details['amount']) . '</td> '.
					'	<td align="center">' . show_date($invoice_details['refund_request_date']) . '</td> '.
					'	<td align="center">' . $item->refund_status($invoice_details['refund_request']) . '</td> '.
					'</tr>';
			}

			$template->set('refunds_table_content', $refunds_table_content);

			$pagination = paginate($start, $limit, $nb_invoices, 'npmembers_area.php', $additional_vars);
			$template->set('pagination', $pagination);

			$members_area_page_content = $template->process('npmembers_area_account_refund_requests.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);
		}
	} /* END -> MY ACCOUNT SECTION */

	
	

	
	

	
	

	
	

	
	

	if ($page == 'about_me') /* BEGIN -> ABOUT ME PAGE(S) */
	{
		if ($section == 'view')
		{
			$shop = new shop();

			if (isset($_POST['form_aboutme_save']))
			{
				$shop->save_aboutme($_POST, $session->value('user_id'));
				$template->set('msg_changes_saved', $msg_changes_saved);
			}

			$user_details = $db->get_sql_row("SELECT user_id, username,  email,
				enable_aboutme_page, aboutme_page_content, shop_account_id, shop_active FROM
				" . NPDB_PREFIX . "users WHERE user_id=" . $session->value('user_id'));

			$template->set('user_details', $user_details);

			$shop_status = $shop->shop_status($user_details);
			$template->set('shop_status', $shop_status);

			$members_area_page_content = $template->process('npmembers_area_aboutme_view.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);
		}
		if ($section == 'profile' && $setts['enable_profile_page'])
		{
			if (isset($_POST['form_profile_save']))
			{
				$post_details = $db->rem_special_chars_array($_POST);
				$db->query("UPDATE " . NPDB_PREFIX . "users SET
					enable_profile_page='" . $post_details['enable_profile_page'] . "',
					profile_www='" . $post_details['profile_www'] . "', profile_msn='" . $post_details['profile_msn'] . "',
					profile_icq='" . $post_details['profile_icq'] . "', profile_aim='" . $post_details['profile_aim'] . "',
					profile_yim='" . $post_details['profile_yim'] . "', profile_skype='" . $post_details['profile_skype'] . "',
					profile_show_birthdate='" . $post_details['profile_show_birthdate'] . "' WHERE
					user_id='" . $session->value('user_id') . "'");

				$template->set('msg_changes_saved', $msg_changes_saved);
			}

			$user_details = $db->get_sql_row("SELECT * FROM
				" . NPDB_PREFIX . "users WHERE user_id=" . $session->value('user_id'));

			$template->set('user_details', $user_details);

			$members_area_page_content = $template->process('npmembers_area_aboutme_profile.tpl.php');
			$template->set('members_area_page_content', $members_area_page_content);
		}
	} /* END -> ABOUT ME PAGE(S) */

	
	

	
	

	
	

	if ($page == 'summary') /* BEGIN -> SUMMARY PAGE */
	{
		if ($section == 'summary_main')
		{
			$summary_page_content['content'] = header6(MSG_MM_SUMMARY) .
				$summary_page_content['manage_account'] .
				$summary_page_content['messaging_received'] . '<br>' .
				'<table cellpadding="0" cellspacing="0" width="100%" border="0"> '.
				'	<tr> '.
				'		<td valign="top">' . $summary_page_content['stats_bidding'] . '</td> '.
				'		<td align="right" valign="top">' . $summary_page_content['stats_selling'] . '</td> '.
				'	</tr> '.
				'</table>' .
				$summary_page_content['bidding_current_bids'] .
				$summary_page_content['selling_open'];

			$template->set('members_area_page_content', $summary_page_content['content']);

		}
	} /* END -> SUMMARY PAGE */

	$template->set('members_area_header', header7(MSG_MEMBERS_AREA_TITLE));

	if ($session->value('category_language') == 1)
	{
		$msg_store_cats_modified = '<div class="errormessage contentfont" align="center">' . MSG_STORE_CATS_MODIFIED . '</div>';
		$template->set('msg_store_cats_modified', $msg_store_cats_modified);
	}

	## begin - header members area
	## preferred seller and check for credit limit
	$user_details = $db->get_sql_row("SELECT preferred_seller, balance, max_credit FROM " . NPDB_PREFIX . "users WHERE user_id='" . $session->value('user_id') . "'");
	$user_payment_mode = $fees->user_payment_mode($session->value('user_id'));

	$template->set('pref_seller_reduction', ($user_details['preferred_seller'] && $setts['enable_pref_sellers']) ? 1 : 0);
	$credit_limit_warning = ($user_payment_mode == 2 && ($user_details['max_credit'] <= ($user_details['balance']+2))) ? 1 : 0;
	$template->set('credit_limit_warning', $credit_limit_warning);


	$nb_cells = 1;

	if ($session->value('membersarea') == 'Active')
	{
		$nb_cells+=4;
	}

	if ($session->value('is_seller'))
	{
		$nb_cells++;
		if ($setts['enable_bulk_lister'])
		{
			$nb_cells++;
		}
		if ($setts['enable_stores'])
		{
			$nb_cells++;
		}
	}


	if ($setts['enable_wanted_ads'])
	{
		$nb_cells++;
	}

	if ($setts['enable_reverse_auctions'])
	{
		$nb_cells++;
	}

	$cell_width = round(100/$nb_cells) . '%';

	$template->set('cell_width', $cell_width);

	if ($page != 'summary')
	{
		$template->change_path('themes/' . $setts['default_theme'] . '/templates/');
		$members_area_header_menu = $template->process('npmembers_area_header_menu.tpl.php');
		$template->change_path('templates/');

		$template->set('members_area_header_menu', $members_area_header_menu);## PHP Pro Bid v6.00 end - header members area
	}

	$template_output .= $template->process('npmembers_area.tpl.php');

	include_once ('global_footer.php');

	echo $template_output;
}
?>
