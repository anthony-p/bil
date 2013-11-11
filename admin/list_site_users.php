<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
## PHP Pro Bid & PHP Pro Ads Integration v1.00						##
#################################################################

session_start();

define ('IN_ADMIN', 1);
define ('IN_SITE', 1);

include_once ('../includes/global.php');
include_once ('../includes/class_formchecker.php');
include_once ('../includes/class_custom_field.php');
include_once ('../includes/class_item.php');
include_once ('../includes/class_user.php');
include_once ('../includes/functions_login.php');


if ($session->value('adminarea')!='Active')
{
	header_redirect('login.php');
}
else
{
	include_once ('header.php');

	$value = isset($_REQUEST['value']) ? intval($_REQUEST['value']) : 0;
	$user_id = isset($_REQUEST['user_id']) ? intval($_REQUEST['user_id']) : 0;
	
	(string) $management_box = NULL;
	(string) $page_handle = 'register';

	$msg_changes_saved = '<p align="center" class="contentfont">' . AMSG_CHANGES_SAVED . '</p>';

	$form_submitted = false;

	$user = new user();
	$user->setts = &$setts;

	$tax = new tax();
	$tax->setts = &$setts;

	$balance_v2 = 1; ## balance limit (debit balance option)

	if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'user_details')
	{
		$row_user = $db->get_sql_row("SELECT u.user_id, u.username, u.email, u.active, u.approved, u.reg_date,
		u.payment_mode, u.tax_account_type, u.tax_reg_number, u.tax_apply_exempt, u.tax_exempted,
		u.name, u.address, u.city, u.zip_code, u.phone, u.birthdate, u.birthdate_year,
		u.tax_company_name, c.name AS country_name, s.name AS state_name, u.state FROM
		" . DB_PREFIX ."users u
		LEFT JOIN " . DB_PREFIX . "countries s ON u.state=s.id
		LEFT JOIN " . DB_PREFIX . "countries c ON u.country=c.id WHERE u.user_id=" . $user_id);

		$user_details_print_link = ' &nbsp; [ <a href="javascript:popUp(\'popup_user_details.php?user_id=' . $row_user['user_id'] . '\');">' . AMSG_PRINT_VIEW . '</a> ]';
		$template->set('user_details_print_link', $user_details_print_link);
		
		$user->save_edit_vars($user_id, $page_handle);

		$template->set('user_details', $row_user);
		$template->set('user_full_address', $user->full_address($row_user));
		$template->set('user_birthdate', $user->show_birthdate($row_user));

		$template->set('tax_account_type', field_display($row_user['tax_account_type'], GMSG_INDIVIDUAL, GMSG_BUSINESS));

		$custom_sections_table = $user->display_sections($row_user, $page_handle, true, $user_id);

		$template->set('custom_sections_table', $custom_sections_table);
		
		(string) $ip_address_history_content = null;
		
		$sql_select_iphistory = $db->query("SELECT time1, time2, ip FROM " . DB_PREFIX . "iphistory WHERE 
			memberid='" . $user_id . "' ORDER by time1 DESC");
		
		if ($db->num_rows($sql_select_iphistory) > 0) 
		{
			while ($iphistory_details = $db->fetch_array($sql_select_iphistory)) 
			{
				if ($iphistory_details['time2'] < 1) 
				{
					$iphistory_details['time2'] = $iphistory_details['time1'];
				}
				
				$background = ($counter++%2) ? 'c1' : 'c2';
			
				$ip_address_history_content .= '<tr class="' . $background . '"> '.
					'	<td align="center">' . $iphistory_details['ip'] . '</td> '.
					'	<td align="center">' . show_date($iphistory_details['time1']) . '</td> '.
					'	<td align="center">' . show_date($iphistory_details['time2']) . '</td> '.
	            '</tr> ';
			}
		} 
		else 
		{
			$ip_address_history_content .= '<tr class="' . $background . '"> '.
				'	<td align="center" colspan="3">' . AMSG_USER_HASNT_LOGGED_IPS . '</td> '.
				'</tr> ';
		}
		
		$template->set('ip_address_history_content', $ip_address_history_content);

		$management_box = $template->process('list_site_users_user_details.tpl.php');
	}
	else if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'add_user')
	{
		$template->set('do', $_REQUEST['do']);

		$user->save_vars($_POST);

		if ($_REQUEST['operation'] == 'submit')
		{
			define ('FRMCHK_USER', 1);
			(bool) $frmchk_user_edit = 0;
			$frmchk_details = $_POST;


			include ('../includes/admin_procedure_frmchk_user.php'); /* Formchecker for user creation/edit */

			if ($fv->is_error())
			{
				$template->set('display_formcheck_errors', $fv->display_errors());
			}
			else
			{
				$form_submitted = true;

				$template->set('msg_changes_saved', $msg_changes_saved);

//                var_dump($_POST);

//				$insert_user_id = $user->insert($_POST);

				/* For PPA & PPB Integration */
//				pps_insert_user($insert_user_id, 'ppb');
				
				/**
				 * since admin creates the user, the user will be automatically activated no matter the site settings.
				 */
//				$sql_update_user = $db->query("UPDATE " . DB_PREFIX . "users SET
//				active=1, approved=1, payment_status='confirmed', mail_activated=1 WHERE user_id=" . $insert_user_id);
			}
		}

		if (!$form_submitted)
		{
			$template->set('user_details', $_POST);
			$template->set('proceed_button', GMSG_REGISTER_BTN);
			$template->set('do', $_REQUEST['do']);

			$header_registration_message = '<table width="100%" border="0" cellpadding="3" cellspacing="3" class="border"> ' .
      		'<tr><td class="c3"><b>' . AMSG_ADD_SITE_USER . '</b></td></tr></table>';

			$template->set('header_registration_message', $header_registration_message);

			$template->set('register_post_url', 'list_site_users.php');
			$template->set('proceed_button', GMSG_REGISTER_BTN);

			$post_country = ($_POST['country']) ? $_POST['country'] : $db->get_sql_field("SELECT c.id FROM " . DB_PREFIX . "countries c WHERE
				c.parent_id=0 ORDER BY c.country_order ASC, c.name ASC LIMIT 1", 'id');

			$template->set('country_dropdown', $tax->countries_dropdown('country', $post_country, 'registration_form'));
			$template->set('state_box', $tax->states_box('state', $_POST['state'], $post_country));

			$template->set('birthdate_box', $user->birthdate_box($_POST));

			$custom_sections_table = $user->display_sections($_POST, $page_handle);

			$template->set('custom_sections_table', $custom_sections_table);
			$template->set('path_relative', '../');

			$template->set('display_direct_payment_methods', $user->direct_payment_methods_edit($_POST));			

			$template->change_path('../templates/');
			$management_box = $template->process('register.tpl.php');
			$template->change_path('templates/');
		}
	}
	else if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'edit_user')
	{
		$row_user = $db->get_sql_row("SELECT * FROM
			bl2_users WHERE id=" . $user_id);

		$username = $row_user['first_name'] . ' ' . $row_user['last_name']; /* the readonly field will not be altered */

		if (isset($_POST['edit_refresh']) && $_POST['edit_refresh'] == 1)
		{
			$row_user = $_POST;

            if (!isset($row_user['first_name'])) {
                $row_user['first_name'] = isset($user_details['fname']) ? $user_details['fname'] : '';
            }

            if (!isset($row_user['last_name'])) {
                $row_user['lname'] = isset($user_details['lname']) ? $user_details['lname'] : '';
            }

			$row_user['last_name'] = $username;
		}

		if (isset($_REQUEST['operation']) && $_REQUEST['operation'] == 'submit')
		{
			$user->save_vars($_POST);
			define ('FRMCHK_USER', 1);
			(bool) $frmchk_user_edit = 1;
			$frmchk_details = $_POST;

			$row_user = $_POST;
			$row_user['username'] = $username; /* the readonly field will not be altered */

			include ('../includes/admin_procedure_frmchk_user.php'); /* Formchecker for user creation/edit */

			if ($fv->is_error())
			{
				$template->set('display_formcheck_errors', $fv->display_errors());
			}
			else
			{
				$form_submitted = true;

//                var_dump(1); exit;

				$template->set('msg_changes_saved', $msg_changes_saved);

				$new_password = ($_POST['password'] == $_POST['password2'] && !empty($_POST['password'])) ? $_POST['password'] : null;
//				$user->update($_POST['user_id'], $_POST, $new_password, $page_handle, true);
				$user->update($_POST['user_id'], $_POST, $new_password, $page_handle);
			}
		}

		if (!$form_submitted)
		{
			if (isset($_REQUEST['operation']) && $_REQUEST['operation'] != 'submit')
			{
				$user->save_edit_vars($user_id, $page_handle);
			}

			$template->set('edit_user', 1);
			$template->set('edit_disabled', 'disabled'); /* some fields in the registration will be disabled for editing */

			$email_check_value = (isset($_POST['email_check']) && $_POST['email_check']) ?
                $_POST['email_check'] : $row_user['email'];
			$template->set('email_check_value', $email_check_value);

			if (isset($_POST['tax_account_type']))
			{
				$row_user['tax_account_type'] = $_POST['tax_account_type'];
			}

			$template->set('user_details', $row_user);
			$template->set('do', $_REQUEST['do']);

			$header_registration_message = '<table width="100%" border="0" cellpadding="3" cellspacing="3" class="border"> ' .
      		'<tr><td class="c3"><b>' . AMSG_EDIT_SITE_USER . '</b></td></tr></table>';

			$template->set('header_registration_message', $header_registration_message);

			$template->set('register_post_url', 'list_site_users.php');
			$template->set('proceed_button', GMSG_UPDATE_BTN);

			$template->set('country_dropdown', $tax->countries_dropdown('country', $row_user['country'], 'registration_form'));
			$template->set('state_box', $tax->states_box('state', $row_user['state'], $row_user['country']));

			$custom_sections_table = $user->display_sections($row_user, $page_handle);

			$template->set('custom_sections_table', $custom_sections_table);
			$template->set('path_relative', '../');

			$template->set('display_direct_payment_methods', $user->direct_payment_methods_edit($row_user));			
			
			$template->change_path('templates/');
			$management_box = $template->process('edit_user.tpl.php');
			$template->change_path('templates/');
		}
	}
	else if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'delete_user')
	{
		$template->set('msg_changes_saved', $msg_changes_saved);

		$user = new user();
		$user->setts = &$setts;

		$user->delete($user_id);
		
		/* mark the user row as deleted */
		$db->query("UPDATE pps_users SET ppb_deleted=1 WHERE ppb_user_id=" . intval($user_id));
	}
	else if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'activate_user')
	{
		$template->set('msg_changes_saved', $msg_changes_saved);

		$user_approved = $db->get_sql_field("SELECT approved FROM " . DB_PREFIX . "users WHERE user_id=" . $user_id, 'approved');
		$approved = ($user_approved) ? 1 : $value;

		if ($approved)
		{## PHP Pro Bid v6.00 users counter - activate/inactivate all the user's auctions## PHP Pro Bid v6.00 but only if his account is approved - also activate/suspend the user's auctions
			user_account_management(intval($user_id), $value);	
		}
		
		if (!$user_approved && $approved)
		{
			$mail_input_id = intval($user_id);
			include('../language/' . $setts['site_lang'] . '/mails/register_success_no_fee_user_notification.php');
		}
		$db->query("UPDATE " . DB_PREFIX . "users SET
			active='" . $value . "', approved='" . $approved . "' WHERE
			user_id=" . $user_id);
	}
	else if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'tax_exempt')
	{
		$template->set('msg_changes_saved', $msg_changes_saved);

		$db->query("UPDATE " . DB_PREFIX . "users SET
			tax_exempted='" . $value . "' WHERE
			user_id=" . $user_id);
	}
	else if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'can_sell')
	{
		$template->set('msg_changes_saved', $msg_changes_saved);

		$db->query("UPDATE " . DB_PREFIX . "users SET
			is_seller='" . $value . "' WHERE
			user_id=" . $user_id);
	}
	else if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'verify_seller')
	{
		$template->set('msg_changes_saved', $msg_changes_saved);
		

		$db->query("UPDATE " . DB_PREFIX . "users SET
			seller_verified='" . $value . "', seller_verif_next_payment=0 , seller_verified_pending=0 WHERE
			user_id=" . $user_id);

	}
	else if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'preferred_seller')
	{
		$template->set('msg_changes_saved', $msg_changes_saved);

		$expiration_date = ($setts['preferred_days'] > 0 && $value) ? (CURRENT_TIME + $setts['preferred_days'] * 24 * 60 * 60) : 0;
		
		$db->query("UPDATE " . DB_PREFIX . "users SET
			preferred_seller='" . $value . "', preferred_seller_exp_date='" . intval($expiration_date) . "' WHERE
			user_id=" . $user_id);
	}
	else if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'is_seller')
	{
		$template->set('msg_changes_saved', $msg_changes_saved);

		$db->query("UPDATE " . DB_PREFIX . "users SET
			is_seller='" . $value . "' WHERE
			user_id=" . $user_id);
	}
	else if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'auction_approval')
	{
		$template->set('msg_changes_saved', $msg_changes_saved);

		$db->query("UPDATE " . DB_PREFIX . "users SET
			auction_approval='" . $value . "' WHERE
			user_id=" . $user_id);
	}
	else if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'payment_mode')
	{
		$template->set('msg_changes_saved', $msg_changes_saved);

		$db->query("UPDATE " . DB_PREFIX . "users SET
			payment_mode='" . $value . "' WHERE
			user_id=" . $user_id);
	}
	else if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'store_default_account')
	{
		$template->set('msg_changes_saved', $msg_changes_saved);

		$db->query("UPDATE " . DB_PREFIX . "users SET
			shop_active='1', shop_account_id='0', shop_next_payment='0' WHERE
			user_id=" . $user_id);
	}
	else if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'payment_reminder')
	{
		$template->set('msg_changes_saved', '<p align="center">' . AMSG_INVOICE_SENT_SUCCESS . '</p>');

		$mail_input_id = $user_id;
		include('../language/' . $setts['site_lang'] . '/mails/user_payment_reminder.php');
	}
	else if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'payment_reminder_v2')
	{
		$template->set('msg_changes_saved', '<p align="center">' . AMSG_INVOICES_SENT_SUCCESS . '</p>');

		$sql_src_filter = null;
		
		if ($setts['account_mode'] == 2 || $setts['account_mode_personal'] == 1)
		{
			$sql_src_filter .= (($sql_src_filter) ? ' AND' : ' WHERE') . " u.balance>=" . $balance_v2;	
			
			if ($setts['account_mode_personal'] == 1)	
			{
				$sql_src_filter .= (($sql_src_filter) ? ' AND' : ' WHERE') . " u.payment_mode=2";				
			}		
		}
		else 
		{
			$sql_src_filter .= (($sql_src_filter) ? ' AND' : ' WHERE') . " u.user_id=0";	
		}

		$sql_select_debit_users = $db->query("SELECT u.user_id FROM " . DB_PREFIX . "users u " . $sql_src_filter);
		
		while($balance_user = $db->fetch_array($sql_select_debit_users))
		{
			$mail_input_id = $balance_user['user_id'];
			include('../language/' . $setts['site_lang'] . '/mails/user_payment_reminder.php');			
		}
	}
	else if (isset($_REQUEST['do']) &&  $_REQUEST['do'] == 'mail_activated')
	{
		$template->set('msg_changes_saved', $msg_changes_saved);

		$db->query("UPDATE " . DB_PREFIX . "users SET
			mail_activated='1' WHERE
			user_id=" . $user_id);
	}
	
	$template->set('management_box', $management_box);

	$limit = 20;

	$order_field = (isset($_REQUEST['order_field']) && $_REQUEST['order_field']) ?
        $_REQUEST['order_field'] : 'u.first_name';
	$order_type = (isset($_REQUEST['order_type']) && $_REQUEST['order_type']) ?
        $_REQUEST['order_type'] : 'ASC';

    $additional_vars = '';
    if (isset($_REQUEST['keywords_name'])) {
        $additional_vars .= '&keywords_name=' . $_REQUEST['keywords_name'];
    }
    if (isset($_REQUEST['keywords_email'])) {
        $additional_vars .= '&keywords_email=' . $_REQUEST['keywords_email'];
    }
    $order_link = '';
    if (isset($order_field)) {
        $order_link .= '&order_field=' . $order_field;
    }
    if (isset($order_type)) {
        $order_link .= '&order_type=' . $order_type;
    }
    $limit_link = '';
    if (isset($start)) {
        $limit_link .= '&start=' . $start;
    }
    if (isset($limit)) {
        $limit_link .= '&limit=' . $limit;
    }
    $show_link = '';
    if (isset($_REQUEST['show'])) {
        $show_link .= '&show=' . $_REQUEST['show'];
    }

	(string) $search_filter = null;

	if (isset($_REQUEST['keywords_first_name']) && $_REQUEST['keywords_first_name'])
	{
		//$search_filter .= (($search_filter) ? ' AND' : ' WHERE') . " MATCH(u.username) AGAINST ('".$_REQUEST['keywords_name']."' WITH QUERY EXPANSION)";
		$search_filter .= (($search_filter) ? ' AND' : ' WHERE') . " u.first_name LIKE '%".$_REQUEST['keywords_first_name']."%'";
		$template->set('keywords_first_name', $_REQUEST['keywords_first_name']);
	}
	if (isset($_REQUEST['keywords_last_name']) && $_REQUEST['keywords_last_name'])
	{
		//$search_filter .= (($search_filter) ? ' AND' : ' WHERE') . " MATCH(u.username) AGAINST ('".$_REQUEST['keywords_name']."' WITH QUERY EXPANSION)";
		$search_filter .= (($search_filter) ? ' AND' : ' WHERE') . " u.last_name LIKE '%".$_REQUEST['keywords_last_name']."%'";
		$template->set('keywords_last_name', $_REQUEST['keywords_last_name']);
	}
	if (isset($_REQUEST['keywords_email']) && $_REQUEST['keywords_email'])
	{
//		$search_filter .= (($search_filter) ? ' AND' : ' WHERE') . " MATCH(u.email) AGAINST ('".$_REQUEST['keywords_email']."*' IN BOOLEAN MODE)";
		$search_filter .= (($search_filter) ? ' AND' : ' WHERE') . " u.email LIKE '%".$_REQUEST['keywords_email']."%'"; /* slow query - will need a workaround */
		$template->set('keywords_email', $_REQUEST['keywords_email']);
	}



//	if (isset($_REQUEST['show']) && $_REQUEST['show'] == 'active')
//	{
//		$search_filter .= (($search_filter) ? ' AND' : ' WHERE') . " u.active=1 AND u.approved=1";
//	}
//	else if (isset($_REQUEST['show']) && $_REQUEST['show'] == 'suspended')
//	{
//		$search_filter .= (($search_filter) ? ' AND' : ' WHERE') . " (u.active=0 OR u.approved=0)";
//	}
//	else if (isset($_REQUEST['show']) && $_REQUEST['show'] == 'individual')
//	{
//		$search_filter .= (($search_filter) ? ' AND' : ' WHERE') . " u.tax_account_type=0";
//	}
//	else if (isset($_REQUEST['show']) && $_REQUEST['show'] == 'business')
//	{
//		$search_filter .= (($search_filter) ? ' AND' : ' WHERE') . " u.tax_account_type=1";
//	}
//	else if (isset($_REQUEST['show']) && $_REQUEST['show'] == 'tax_apply_exempt')
//	{
//		$search_filter .= (($search_filter) ? ' AND' : ' WHERE') . " u.tax_apply_exempt=1 AND u.tax_exempted=0";
//	}
//	else if (isset($_REQUEST['show']) && $_REQUEST['show'] == 'tax_exempted')
//	{
//		$search_filter .= (($search_filter) ? ' AND' : ' WHERE') . " u.tax_exempted=1";
//	}
//	else if (isset($_REQUEST['show']) && $_REQUEST['show'] == 'accounting_overdue')
//	{
//		if ($setts['account_mode'] == 2 || $setts['account_mode_personal'] == 1)
//		{
//			$search_filter .= (($search_filter) ? ' AND' : ' WHERE') . " u.balance>0";
//
//			if ($setts['account_mode_personal'] == 1)
//			{
//				$search_filter .= (($search_filter) ? ' AND' : ' WHERE') . " u.payment_mode=2";
//			}
//		}
//		else
//		{
//			$search_filter .= (($search_filter) ? ' AND' : ' WHERE') . " u.user_id=0";
//		}
//	}
//	else if (isset($_REQUEST['show']) && $_REQUEST['show'] == 'accounting_overdue_v2')
//	{
//		if ($setts['account_mode'] == 2 || $setts['account_mode_personal'] == 1)
//		{
//			$search_filter .= (($search_filter) ? ' AND' : ' WHERE') . " u.balance>=" . $balance_v2;
//
//			if ($setts['account_mode_personal'] == 1)
//			{
//				$search_filter .= (($search_filter) ? ' AND' : ' WHERE') . " u.payment_mode=2";
//			}
//		}
//		else
//		{
//			$search_filter .= (($search_filter) ? ' AND' : ' WHERE') . " u.user_id=0";
//		}
//	}
//	else if (isset($_REQUEST['show']) && $_REQUEST['show'] == 'mail_activated')
//	{
//		$search_filter .= (($search_filter) ? ' AND' : ' WHERE') . " u.mail_activated=0";
//	}
//	else if (isset($_REQUEST['show']) && $_REQUEST['show'] == 'verify_pending')
//	{
//		$search_filter .= (($search_filter) ? ' AND' : ' WHERE') . " u.seller_verified_pending=1";
//	}




//	$nb_users = $db->count_rows('users u', $search_filter);
//    var_dump('SELECT COUNT(u.id) FROM bl2_users u ' . $search_filter);
	$nb_users = $db->get_sql_row('SELECT COUNT(u.id) FROM bl2_users u ' . $search_filter);
    $nb_users = $nb_users['COUNT(u.id)'];

    if (!isset($start)) {
        $start = 0;
    }
	$template->set('query_results_message', display_pagination_results($start, $limit, $nb_users));

    $old_query = "SELECT u.user_id, u.name, u.username, u.email, u.active, u.approved, u.reg_date,
		u.payment_mode, u.balance, u.tax_account_type, u.tax_reg_number, u.tax_apply_exempt, u.tax_exempted,
		u.is_seller, u.preferred_seller, u.auction_approval, u.seller_verified, u.seller_verified_pending, u.preferred_seller_exp_date,
		u.tax_company_name, c.name AS country_name, s.name AS state_name, u.mail_activated FROM
		" . DB_PREFIX ."users u
		LEFT JOIN " . DB_PREFIX . "countries c ON u.country=c.id
		LEFT JOIN " . DB_PREFIX . "countries s ON u.state=s.id
		" . $search_filter . "
		ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit;

    $new_select_users_query = "SELECT u.id, u.first_name, u.last_name, u.email, u.active,
		u.tax_account_type, u.tax_reg_number,
		u.tax_company_name, c.name AS country_name, s.name AS state_name FROM
		bl2_users u
		LEFT JOIN " . DB_PREFIX . "countries c ON u.country=c.id
		LEFT JOIN " . DB_PREFIX . "countries s ON u.state=s.id
		" . $search_filter . "
		ORDER BY " . $order_field . " " . $order_type . " LIMIT " . $start . ", " . $limit;

//    var_dump($search_filter);
//    var_dump($new_select_users_query);

	$sql_select_users = $db->query($new_select_users_query);

    $counter = 0;
    $site_users_content = '';
	while ($user_details = $db->fetch_array($sql_select_users))
	{
		$background = ($counter++%2) ? 'c1' : 'c2';

		$user_country = $tax->show_country($user_details['country_name'], $user_details['state_name']);
//		$is_seller = $user->can_sell($user_details['is_seller']);
//		$is_seller_pending = $user_details['seller_verified_pending'];

        $edit_option = '[<a href="list_site_users.php?do=edit_user&user_id=' .
            $user_details['id'] .
            '&order_field=u.first_name&order_type=ASC&limit=20">edit</a>]';

		$site_users_content .= '<tr class="' . $background . '"> '.
      	'	<td valign="top">' . $user_details['first_name'] . ' ' . $user_details['last_name'] . '</td>' .
            '<td valign="top">' . $user_details['email'] . '</td>' .
            '<td valign="top">' . $user_details['country_name'] . '</td>' .
            '<td valign="top">' . $user_details['state_name'] . '</td>' .
            '<td valign="top">' . $edit_option . '</td>' .
            '</tr>';

//      if ($is_seller)
//      {
//      	$site_users_content .= '<br>[ <a href="list_auctions.php?status=open&owner_id=' . $user_details['user_id'] . '">' . AMSG_VIEW_OPEN_AUCTIONS . '</a> ]';
//		}

//		$site_users_content .= '</td> '.
//			'	<td valign="top">' . AMSG_COUNTRY . ': ' . $user_country . '<br>' .
//      	'		' . AMSG_EMAIL_ADDR  . ': ' . $user_details['email'] . '<br>' .
////      	'		' . AMSG_REG_DATE . ': ' . show_date($user_details['reg_date']) .
////      	(($setts['enable_pref_sellers'] && $user_details['preferred_seller_exp_date'] > 0) ? '<br><br>' . GMSG_PREFERRED_SELLER_EXP_DATE . ':<br> ' . show_date($user_details['preferred_seller_exp_date']) : '') .
//      	'	</td> ' .
//			'	<td valign="top">' . GMSG_ACCOUNT_STATUS . ': <b>' . $user->account_status($user_details['active'], true) . '</b>';
////			'	<td valign="top">' . GMSG_ACCOUNT_STATUS . ': <b>' . $user->account_status($user_details['active'], $user_details['approved']) . '</b>';
//
//      // get user payment mode.
////     	$user_payment_mode_display = $user->payment_mode_desc($user_details['payment_mode']);
//     	$user_payment_mode = $tax->user_payment_mode($user_details['id']);
//
//      if ($setts['account_mode_personal'] == 1)
//      {
//      	$live_payment_mode = ($user_payment_mode == 1) ? 1 : 0;
//
////			$payment_mode_live_link = $user_payment_mode_display . ' [ <a href="list_site_users.php?do=payment_mode&user_id=' . $user_details['user_id'] . $additional_vars . $order_link . $limit_link . $show_link . '&value=2">' . GMSG_CHANGE_TO_ACCOUNT . '</a> ]';
////			$payment_mode_account_link = $user_payment_mode_display . ' [ <a href="list_site_users.php?do=payment_mode&user_id=' . $user_details['user_id'] . $additional_vars . $order_link . $limit_link . $show_link . '&value=1">' . GMSG_CHANGE_TO_LIVE . '</a> ]';
//
////      	$payment_mode_link = field_display($live_payment_mode, $payment_mode_account_link, $payment_mode_live_link);
//      }
//      else
//      {
//      	$payment_mode_link = $user_payment_mode_display;
//      }
//
////		$site_users_content .= '<br><br>' . AMSG_PAYMENT_MODE . ': <b>' . $payment_mode_link . '</b>';
//
//		if ($user_payment_mode == 2)
//		{
//			$site_users_content .= '<br>' . GMSG_BALANCE . ': <b>' . $user->show_balance($user_details['balance'], $setts['currency']) . '</b><br>'.
//				'[ <a href="accounting.php?user_id=' . $user_details['user_id'] . '">' . AMSG_VIEW_ACCOUNT_HISTORY . '</a> ]';
//
//			if ($user_details['balance'] > 0)
//			{
//				$site_users_content .= '<br>[ <a href="list_site_users.php?do=payment_reminder&user_id=' . $user_details['user_id'] . $additional_vars . $order_link . $limit_link . $show_link . '">' . AMSG_SEND_PAYMENT_REMINDER . '</a> ]';
//			}
//		}
//
//		$site_users_content .= '<br>';
//
//		if ($setts['enable_private_site'])
//		{
//			$can_sell_enable_link = GMSG_NO . ' [ <a href="list_site_users.php?do=can_sell&user_id=' . $user_details['user_id'] . $additional_vars . $order_link . $limit_link . $show_link . '&value=1">' . GMSG_ENABLE . '</a> ]';
//			$can_sell_disable_link = GMSG_YES . ' [ <a href="list_site_users.php?do=can_sell&user_id=' . $user_details['user_id'] . $additional_vars . $order_link . $limit_link . $show_link . '&value=0">' . GMSG_DISABLE . '</a> ]';
//			$site_users_content .= '<br>' . AMSG_SELLING_CAPABILITIES .': <b>' . field_display($user_details['is_seller'], $can_sell_enable_link, $can_sell_disable_link) . '</b>';
//		}

//		if ($user_details['seller_verified_pending'])
//		{
//			$verified_seller_enable_link =  GMSG_PENDING . ' [ <a href="list_site_users.php?do=verify_seller&user_id=' . $user_details['user_id'] . $additional_vars . $order_link . $limit_link . $show_link . '&value=1">' . GMSG_VERIFY . '</a> ]';
//		}
//		else
//		{
//			$verified_seller_enable_link = GMSG_NO . ' [ <a href="list_site_users.php?do=verify_seller&user_id=' . $user_details['user_id'] . $additional_vars . $order_link . $limit_link . $show_link . '&value=1">' . GMSG_VERIFY . '</a> ]';
//		}

//		$verified_seller_disable_link = GMSG_YES . ' [ <a href="list_site_users.php?do=verify_seller&user_id=' . $user_details['id'] . $additional_vars . $order_link . $limit_link . $show_link . '&value=0">' . GMSG_UNVERIFY . '</a> ]';
//		$site_users_content .= '<br>' . AMSG_VERIFIED_SELLER .': <b>' . field_display($user_details['seller_verified'], $verified_seller_enable_link, $verified_seller_disable_link) . '</b>';


//		if ($setts['enable_pref_sellers'])
//		{
//			$pref_seller_enable_link = GMSG_NO . ' [ <a href="list_site_users.php?do=preferred_seller&user_id=' . $user_details['id'] . $additional_vars . $order_link . $limit_link . $show_link . '&value=1">' . GMSG_ENABLE . '</a> ]';
//			$pref_seller_disable_link = GMSG_YES . ' [ <a href="list_site_users.php?do=preferred_seller&user_id=' . $user_details['id'] . $additional_vars . $order_link . $limit_link . $show_link . '&value=0">' . GMSG_DISABLE . '</a> ]';
//			$site_users_content .= '<br>' . AMSG_PREF_SELLER .': <b>' . field_display($user_details['preferred_seller'], $pref_seller_enable_link, $pref_seller_disable_link) . '</b>';
//		}
//
//		if (!$setts['enable_auctions_approval'])
//		{
//			$auct_approval_enable_link = GMSG_NO . ' [ <a href="list_site_users.php?do=auction_approval&user_id=' . $user_details['user_id'] . $additional_vars . $order_link . $limit_link . $show_link . '&value=1">' . GMSG_ENABLE . '</a> ]';
//			$auct_approval_disable_link = GMSG_YES . ' [ <a href="list_site_users.php?do=auction_approval&user_id=' . $user_details['user_id'] . $additional_vars . $order_link . $limit_link . $show_link . '&value=0">' . GMSG_DISABLE . '</a> ]';
//			$site_users_content .= '<br>' . AMSG_REQUIRE_AUCT_APPROVAL .': <b>' . field_display($user_details['auction_approval'], $auct_approval_enable_link, $auct_approval_disable_link) . '</b>';
//		}
//
//		if ($setts['enable_stores'])
//		{
//			$site_users_content .= '<br>' . AMSG_ASSIGN_DEFAULT_STORE_ACCOUNT .': <b>[ <a href="list_site_users.php?do=store_default_account&user_id=' . $user_details['user_id'] . $additional_vars . $order_link . $limit_link . $show_link . '">' . GMSG_PROCEED . '</a> ]</b>';
//		}

		$site_users_content .= '</td> ';

//		if ($setts['enable_tax'])
//		{
//			$tax_exempt_enable_link = GMSG_NO . ' [ <a href="list_site_users.php?do=tax_exempt&user_id=' . $user_details['id'] . $additional_vars . $order_link . $limit_link . $show_link . '&value=1">' . GMSG_ENABLE . '</a> ]';
//			$tax_exempt_disable_link = GMSG_YES . ' [ <a href="list_site_users.php?do=tax_exempt&user_id=' . $user_details['id'] . $additional_vars . $order_link . $limit_link . $show_link . '&value=0">' . GMSG_DISABLE . '</a> ]';
//
//			$site_users_content .= '<td valign="top">' . AMSG_REGISTERED_AS . ': <b>' . field_display($user_details['tax_account_type'], GMSG_INDIVIDUAL, GMSG_BUSINESS) . '</b><br>' .
//      		AMSG_APPLIED_FOR_TAX_EXEMPT . ': <b>' . field_display($user_details['tax_apply_exempt'], GMSG_NO, GMSG_YES) . '</b><br>' .
//      		AMSG_COMPANY_NAME . ': ' . field_display($user_details['tax_company_name']) . '<br>' .
//				AMSG_TAX_REG_NUMBER . ': <b>' . field_display($user_details['tax_reg_number']) . '</b><br>' .
//				AMSG_TAX_EXEMPTED .': <b>' . field_display($user_details['tax_exempted'], $tax_exempt_enable_link, $tax_exempt_disable_link) . '</b></td> ';
//		}

		(string) $site_user_options = null;

//		if ($user_details['approved'])
//		{
//			if ($user_details['active'])
//			{
//				$site_user_options .= '[ <a href="list_site_users.php?do=activate_user&user_id=' . $user_details['user_id'] . $additional_vars . $order_link . $limit_link . $show_link . '&value=0">' . AMSG_SUSPEND . '</a> ]';
//			}
//			else
//			{
//				$site_user_options .= '[ <a href="list_site_users.php?do=activate_user&user_id=' . $user_details['user_id'] . $additional_vars . $order_link . $limit_link . $show_link . '&value=1">' . AMSG_ACTIVATE . '</a> ]';
//			}
//		}
//		else
//		{
//			$site_user_options .= '[ <a href="list_site_users.php?do=activate_user&user_id=' . $user_details['user_id'] . $additional_vars . $order_link . $limit_link . $show_link . '&value=1">' . AMSG_APPROVE . '</a> ]';
//		}
//		$site_user_options .= '<br>[ <a href="list_site_users.php?do=edit_user&user_id=' . $user_details['id'] . $additional_vars . $order_link . $limit_link . $show_link . '">' . AMSG_EDIT . '</a> ] '.
//			'[ <a href="list_site_users.php?do=delete_user&user_id=' . $user_details['id'] . $additional_vars . $order_link . $limit_link . $show_link . '" onclick="return confirm(\'' . AMSG_DELETE_CONFIRM . '\');">' . AMSG_DELETE . '</a> ]';

//		if (!$user_details['mail_activated'])
//		{
//			$site_user_options .= '<br>[ <a href="list_site_users.php?do=mail_activated&user_id=' . $user_details['user_id'] . $additional_vars . $order_link . $limit_link . $show_link . '">' . AMSG_VERIFY_EMAIL_ADDRESS . '</a> ] ';
//		}

//		$site_users_content .= '<td align="center">' . $site_user_options . '	</td>'.
//			'</tr> ';
	}

	$template->set('site_users_content', $site_users_content);

    if (!isset($_REQUEST['show'])) {
        $_REQUEST['show'] = '';
    }
	$template->set('show', $_REQUEST['show']);
	
	(string) $filter_users_content = null;

	$filter_users_content .= display_link('list_site_users.php', GMSG_ALL, ((!$_REQUEST['show']) ? false : true)) . ' | ';
	$filter_users_content .= display_link('list_site_users.php?show=active', GMSG_ACTIVE, (($_REQUEST['show'] == 'active') ? false : true)) . ' | ';
	$filter_users_content .= display_link('list_site_users.php?show=suspended', GMSG_SUSPENDED, (($_REQUEST['show'] == 'suspended') ? false : true)) . ' | ';
	$filter_users_content .= display_link('list_site_users.php?show=accounting_overdue', AMSG_ACCOUNTING_OVERDUE, (($_REQUEST['show'] == 'accounting_overdue') ? false : true)) . ' | ';	
	$filter_users_content .= display_link('list_site_users.php?show=accounting_overdue_v2', AMSG_DEBIT_BALANCE_LIMIT, (($_REQUEST['show'] == 'accounting_overdue_v2') ? false : true)) . ' | ';	
	$filter_users_content .= display_link('list_site_users.php?show=mail_activated', AMSG_MAIL_UNVERIFIED, (($_REQUEST['show'] == 'mail_activated') ? false : true));	

	if ($setts['enable_tax'])
	{
		$filter_users_content .= '<br>';
		$filter_users_content .= display_link('list_site_users.php?show=individual', GMSG_INDIVIDUAL, (($_REQUEST['show'] == 'individual') ? false : true)) . ' | ';
		$filter_users_content .= display_link('list_site_users.php?show=business', GMSG_BUSINESS, (($_REQUEST['show'] == 'business') ? false : true)) . ' | ';
		$filter_users_content .= display_link('list_site_users.php?show=tax_apply_exempt', AMSG_APPLIED_FOR_TAX_EXEMPT, (($_REQUEST['show'] == 'tax_apply_exempt') ? false : true)) . ' | ';
		$filter_users_content .= display_link('list_site_users.php?show=tax_exempted', AMSG_TAX_EXEMPTED, (($_REQUEST['show'] == 'tax_exempted') ? false : true));
	}
	
	$filter_users_content .= ' | ' .  display_link('list_site_users.php?show=verify_pending', AMSG_SELLER_PENDING, (($_REQUEST['show'] == 'seller_pending') ? false : true));	

	$template->set('filter_users_content', $filter_users_content);

	$pagination = paginate($start, $limit, $nb_users, 'list_site_users.php', $additional_vars . $order_link . $show_link);

	$template->set('pagination', $pagination);

	$template->set('header_section', AMSG_USERS_MANAGEMENT);
	$template->set('subpage_title', AMSG_USERS_MANAGEMENT);

	$template->set('page_order_username', page_order('list_site_users.php', 'u.username', $start, $limit, $additional_vars . $show_link, AMSG_USERNAME));
	$template->set('page_order_reg_date', page_order('list_site_users.php', 'u.reg_date', $start, $limit, $additional_vars . $show_link, AMSG_REG_DATE));

	$template_output .= $template->process('list_site_users.tpl.php');

	include_once ('footer.php');

	echo $template_output;
}
?>
