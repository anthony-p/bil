<?
#################################################################
## PHP Pro Bid v6.00															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
## PHP Pro Bid & PHP Pro Ads Integration v1.00						##
#################################################################

$integration = $db->get_sql_row("SELECT * FROM pps_integration LIMIT 0,1");

## needed in both files
if (INDEX_PAGE == 1 && !$_SESSION['index_page_viewed'])
{
	if ($integration['enable_integration'] && $integration['main_page_unified'])
	{
		$_SESSION['index_page_viewed'] = 1;
		header_redirect($integration['ppb_path'] . 'index.php');
	}
}

function pps_insert_user($user_id, $user_type = 'ppb')
{
	global $db;
	$user_data = array();
	
	switch ($user_type) 
	{
		case 'ppb':
			$user_data = array(
				'ppb_user_id' => $user_id,
				'ppb_reg_incomplete' => 0,
				'ppa_user_id' => 0,
				'ppa_reg_incomplete' => 1
			);
			
			break;
			
		case 'ppa':
			$user_data = array(
				'ppb_user_id' => 0,
				'ppb_reg_incomplete' => 1,
				'ppa_user_id' => $user_id,
				'ppa_reg_incomplete' => 0
			);
			
			break;	
	}
	
	$db->query("INSERT INTO pps_users (ppb_user_id, ppb_reg_incomplete, ppa_user_id, ppa_reg_incomplete) VALUES 
		('" . $user_data['ppb_user_id'] . "', '" . $user_data['ppb_reg_incomplete'] . "', 
		'" . $user_data['ppa_user_id'] . "', '" . $user_data['ppa_reg_incomplete'] . "')");
}

/* if a user existed before the integration, on his first login a row will be created in pps_users */
if ($session->value('user_id') && $session->value('pps_checked') != 1)
{
	$session->set('pps_checked', 1);
	$is_pps_row = $db->get_sql_field("SELECT count(*) AS nb_rows FROM pps_users WHERE 
		ppb_user_id='" . $session->value('user_id') . "'", 'nb_rows');
	
	if (!$is_pps_row)
	{
		pps_insert_user($session->value('user_id'), 'ppb');
	}
}

function pps_convert_username($username)
{
	global $db;
	
	$valid = false;
	$counter = 0;
	$new_username = $username;
	
	while (!$valid) 
	{
		$counter++;
		$is_username = $db->count_rows('users', "WHERE username='" . $new_username . "'");
		
		if ($is_username)
		{
			$new_username = $username . $counter;
		}
		else 
		{
			$valid = true;
		}
	}
	
	return $new_username;
}

/**
 * below we will purge, on first access of PPB, all the users which have had their accounts deleted in PPB
 */

$is_deleted_pps = $db->get_sql_field("SELECT count(*) AS count_rows FROM pps_users WHERE ppa_deleted=1", 'count_rows');

if ($is_deleted_pps)
{
	$sql_select_deleted_pps = $db->query("SELECT * FROM pps_users WHERE ppa_deleted=1");
	
	include_once ($fileExtension.'includes/class_voucher.php');
	include_once ($fileExtension.'includes/class_fees_main.php');
	include_once ($fileExtension.'includes/class_tax.php');

	include_once ($fileExtension.'includes/class_formchecker.php');
	include_once ($fileExtension.'includes/class_custom_field.php');
	include_once ($fileExtension.'includes/class_item.php');
	include_once ($fileExtension.'includes/class_user.php');
	
	$pps_deleted_array = null;
	
	$pps_deleted_user = new user();
	$pps_deleted_user->setts = &$setts;

	while ($deleted_row = $db->fetch_array($sql_select_deleted_pps))
	{
		$pps_deleted_user->delete($deleted_row['ppb_user_id']);

		$pps_deleted_array[] = $deleted_row['pps_id'];	
	}
	
	$db->query("DELETE FROM pps_users WHERE pps_id IN (" . $db->implode_array($pps_deleted_array) . ")");
}

/**
 * if user is logged in to PPA, on his first access of the PPB site is automatically logged in to PPB as well
 * if the account doesnt exist, an account is created and needs to be completed at first
 * if the account exists, the user is logged in and redirect accordingly (if everything is in order no redirect is made)
 * 
 * when first registering, only the username, password and email are copied.
 */
if (
		IN_ADMIN != 1 &&		
		$_SESSION[$integration['ppa_session_prefix'] . 'user_id'] && 
		!$session->value('user_id') && 
		!eregi('registration_completion.php', $_SERVER['PHP_SELF']) && 
		!eregi('pp_', $_SERVER['PHP_SELF']) &&
		!eregi('payment_', $_SERVER['PHP_SELF']) && 
		!eregi('activate_account.php', $_SERVER['PHP_SELF']) && 
		!eregi('account_activate.php', $_SERVER['PHP_SELF'])
	)
{
	$pps_user_details = $db->get_sql_row("SELECT pps.*, ppb.username AS ppb_username, ppb.email AS ppb_email, 
		ppa.password AS ppa_password, ppa.salt AS ppa_salt, ppa.reg_date AS reg_date, 
		ppa.username AS ppa_username, ppa.email AS ppa_email, 
		ppa.name, ppa.address, ppa.city, ppa.zip_code, ppa.phone, ppa.state, ppa.country  		
		FROM pps_users pps
		LEFT JOIN " . $integration['ppb_db_prefix'] . "users ppb ON ppb.user_id=pps.ppb_user_id 
		LEFT JOIN " . $integration['ppa_db_prefix'] . "users ppa ON ppa.user_id=pps.ppa_user_id 
		WHERE pps.ppa_user_id='" . intval($_SESSION[$integration['ppa_session_prefix'] . 'user_id']) . "'");

	if ($pps_user_details['pps_id'] > 0)
	{
		$ppb_username = $pps_user_details['ppa_username'];
	
		if (!$pps_user_details['ppb_user_id'])
		{
			$ppb_username = pps_convert_username($ppb_username);
			
			$db->query("INSERT INTO " . $integration['ppb_db_prefix'] . "users 
				(username, email, password, salt, reg_date, name, address, city, zip_code, state, country, phone) VALUES  
				('" . $ppb_username . "', '" . $pps_user_details['ppa_email'] . "', 
				'" . $pps_user_details['ppa_password'] . "', '" . $pps_user_details['ppa_salt'] . "', 
				'" . $pps_user_details['reg_date'] . "', 
				'" . $pps_user_details['name'] . "', '" . $pps_user_details['address'] . "', 
				'" . $pps_user_details['city'] . "', '" . $pps_user_details['zip_code'] . "', 
				'" . $pps_user_details['state'] . "', '" . $pps_user_details['country'] . "', '" . $pps_user_details['phone'] . "')");
			$ppb_user_id = $db->insert_id();
			
			$db->query("UPDATE pps_users SET ppb_user_id='" . $ppb_user_id . "', ppb_reg_incomplete=1 WHERE pps_id=" . $pps_user_details['pps_id']);		
		}
		else 
		{
			$ppb_username = $pps_user_details['ppb_username'];
		}
		
		if ($pps_user_details['ppb_reg_incomplete'] == 1 && IN_ADMIN != 1) /* we redirect to the registration completion page */
		{
			header_redirect($fileExtension.'registration_completion.php');
		}
		else /* registration complete, now login user */
		{
			include_once ($fileExtension.'includes/class_voucher.php');
			include_once ($fileExtension.'includes/class_fees_main.php');
			include_once ($fileExtension.'includes/class_tax.php');
			
			include_once ($fileExtension.'includes/class_fees.php');
			include_once ($fileExtension.'includes/functions_login.php');
		
			$signup_fee = new fees();
			$signup_fee->setts = &$setts;
			
			$login_output = login_user($ppb_username, '', '', true);
		
			$session->set('membersarea', $login_output['active']);
			$session->set('username', $login_output['username']);
			$session->set('user_id', $login_output['user_id']);
			$session->set('is_seller', $login_output['is_seller']);
	
			$session->set('temp_user_id', $login_output['temp_user_id']); /* for use with activate_account.php only! */
	
			$redirect_url = $login_output['redirect_url'];
			$redirect_url = (eregi('account_activate', $redirect_url)) ? 'members_area.php' : $redirect_url;
	
			header_redirect($db->add_special_chars($redirect_url));
		}
	}
}

##################################################
## needed for the PHP Pro Bid integration only	##
##################################################
function list_integration_skins($location = 'site', $drop_down = false, $selected_skin = null, $display_none = false, $dd_multiple = false)
{
	(array) $output = null;
	(string) $display_output = null;

	$relative_path = ($location == 'site') ? '' : '../';

	$handle = opendir($relative_path . 'integration_themes');

	while ($file = readdir($handle))
	{
		if (!ereg('[.]', $file))
		{
			$output[] = $file;
		}
	}

	closedir($handle);

	/**
	 * this is an enhancement of the function, to create a drop down menu to select the skin
	 * in the admin area
	 */

	if ($drop_down)
	{
		$display_output = '<select name="default_theme' . (($dd_multiple) ? '[]' : '') . '"> ';

		if ($display_none)
		{
			$display_output .= '<option value="" selected>' . GMSG_DEFAULT . '</option> ';			
		}

		foreach ($output as $value)
		{
			$display_output .= '<option value="' . $value . '" ' . (($value == $selected_skin) ? 'selected' : '') . '>' . $value . '</option> ';
		}

		$display_output .= '</select>';
	}
	return ($drop_down) ? $display_output : $output;
}

function mp_setts_show($input, $sort = false)
{
	(array) $output = null;
	$array_one = @explode(';', $input);
		
	if (is_array($array_one))
	{
		foreach ($array_one as $value)
		{
			list($ad_type, $order) = explode(',', $value);
			
			$output[$ad_type] = intval($order);
		}
	}
	
	if ($sort)
	{
		asort($output);
	}

	return $output;
}

function ad_type_inttostr($ad_type)
{
	global $ppa_setts, $ppa_layout;
	
	$output = array('int' => 0, 'str' => null, 'desc' => null);
	
	$ad_type = intval($ad_type);
	
	if ($ad_type == 1 && $ppa_setts['enable_standard_ad']) 
	{ 
		$output = array('int' => 1, 'str' => 'standard', 'desc' => $ppa_layout['name_sa_p']);
	}
	if ($ad_type == 2 && $ppa_setts['enable_trade_ad']) 
	{
		$output = array('int' => 2, 'str' => 'trade', 'desc' => $ppa_layout['name_ta_p']);
	}
	if ($ad_type == 3 && $ppa_setts['enable_wanted_ad']) 
	{
		$output = array('int' => 3, 'str' => 'wanted', 'desc' => $ppa_layout['name_wa_p']);
	}
	
	return $output;
}

function pps_show_date ($timestamp, $show_time = true, $duration = 0)
{
	(string) $display_output = null;

	if ($timestamp)
	{
		if ($duration < 0)
		{
			$display_output = GMSG_NA . ' (' . GMSG_UNLIMITED . ')';
		}
		else 
		{
			$date_format = ($show_time) ? DATETIME_FORMAT : DATE_FORMAT;
	
			$offset_time = $timestamp + (TIME_OFFSET * 60 * 60);
	
			$display_output = date($date_format, $offset_time);
		}
	}
	else
	{
		$display_output = GMSG_NA;
	}
	return $display_output;
}

function show_start_time($item_details, $user_details)
{
	global $setts;

	$show_start_time = false;
	
	if (!$user_details['user_id'] || $user_details['setting_start_time'] || !$setts['setting_start_time'])
	{
		$show_start_time = true;
	}
	
	return $show_start_time;
}
?>
