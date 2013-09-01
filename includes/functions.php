<?
#################################################################
## PHP Pro Bid v6.10															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
## PHP Pro Bid & PHP Pro Ads Integration v1.00						##
## (Mods-Store) -> Shopping Cart											##
#################################################################

define('GMSG_MAIL_FROM_ADMIN', 'Bring It Local');
define('ADDL_PIN_CODE', 'ENTER_CODE');
function SetCookieLive($name, $value='', $expire=0, $path = '', $domain='')
    {
        $_COOKIE[$name] = $value;
        return setcookie($name, $value, $expire, $path, $domain);
    }
function header_redirect ($redirect_url, $force_redirect = false)
{
	global $setts;
	
	if (!$force_redirect)
   {
		$redirect_url = ((eregi('http://', $redirect_url) || eregi('https://', $redirect_url) || eregi('ftp://', $redirect_url)) && 
			!eregi($setts['site_path_ssl'], $redirect_url) && !eregi($setts['site_path'], $redirect_url)) ? 'index.php' : $redirect_url;
	}
	
	echo "<script>document.location.href='" . $redirect_url . "'</script>";
}

function check_pin ($pin_generated, $pin_submitted)
{
	return (substr(md5($pin_generated . ADDL_PIN_CODE),15,8) == $pin_submitted) ? TRUE : FALSE;
}

function generate_pin ($pin_submitted)
{
	return substr(md5($pin_submitted . ADDL_PIN_CODE),15,8);
}

function show_pin_image ($full_pin, $generated_pin, $image_url = '')
{
  	## create an image not a text for the pin
	$font  = 6;
	$width  = ImageFontWidth($font) * strlen($generated_pin);
	$height = ImageFontHeight($font);

	$im = @imagecreate ($width,$height);
	$background_color = imagecolorallocate ($im, 219, 239, 249); //cell background
	$text_color = imagecolorallocate ($im, 0, 0,0);//text color
	imagestring ($im, $font, 0, 0,  $generated_pin, $text_color);
	touch($image_url . 'uplimg/site_pin_' . $full_pin . '.jpg');
	imagejpeg($im, $image_url . 'uplimg/site_pin_' . $full_pin . '.jpg');

	$image_output = '<img src="' . $image_url . 'uplimg/site_pin_' . $full_pin . '.jpg">';

	imagedestroy($im);

	return $image_output;
}

function unlink_pin()
{
	global $session;

	$path = (defined("IN_ADMIN") && IN_ADMIN == 1) ? '../' : '';

	if ($session->is_set('pin_value'))
	{
		@unlink($path.'uplimg/site_pin_'.$session->value('pin_value').'.jpg');
		$session->unregister('pin_value');
	}

	if ($session->is_set('admin_pin_value'))
	{
		@unlink($path.'uplimg/site_pin_'.$session->value('admin_pin_value').'.jpg');
		$session->unregister('admin_pin_value');
	}
}

function sanitize_var($value)
{
	if (!is_numeric($value))
	{
		$value = ereg_replace("[^A-Za-z0-9_ ]", "", $value);

		$value = str_replace('amp','and',$value);
		$value = str_replace('quot','',$value);
		$value = str_replace('039','',$value);
		$value = str_replace(' ','-',$value);
	}

	return $value;
}

function process_link($base_url, $var_array = NULL, $overwrite_amp = false)
{
	global $setts;
	
	$ssl_url_simple = array('login', 'register');
	$ssl_url_enhanced = array('login', 'register', 'members_area', 'fee_payment');
	
	$amp = ($overwrite_amp) ? '_AND_' : '&';
	
	$ssl_url_array = ($setts['enable_enhanced_ssl']) ? $ssl_url_enhanced : $ssl_url_simple;

	(string) $output = NULL;

	$path = ($setts['is_ssl']==1 && (in_array($base_url, $ssl_url_array))) ? $setts['site_path_ssl'] : $setts['site_path'];

	if ($setts['is_mod_rewrite'] && $var_array)
	{
		if ($var_array)
		{
			while(list($key, $value) = each($var_array))
			{
				$sanitized_value = sanitize_var($value);
				$output .= $sanitized_value.','.$key.',';
			}
		}
		$base_url_array = @explode('/', $base_url);
		$base_url_cnt = count($base_url_array);
		
		if ($base_url_cnt > 1)
		{
			$base_url_suffix = $base_url_array[$base_url_cnt - 1];
			
			$base_url_prefix = '';
			for ($i=0; $i<($base_url_cnt - 1); $i++)
			{
				$base_url_prefix .= $base_url_array[$i] . '/';
			}
			$output = $base_url_prefix . $output . $base_url_suffix;
		}
		else 
		{
			$output .= $base_url;
		}
	}
	else
	{
		$output = $base_url.'.php';
		if ($var_array)
		{
			$output .= '?';
			while(list($key, $value) = each($var_array))
			{
				$sanitized_value = sanitize_var($value);
				$output .= $key . '=' . $sanitized_value . $amp;
			}
			$output = substr($output,0,((-1) * strlen($amp)));
		}
	}

	return $path . $output;
}

function category_navigator ($parent_id, $show_links = true, $show_category = true, $page_link = null, $additional_vars = null, $none_msg = null, $reverse_categories = false)
{
	global $reverse_categoy_lang, $category_lang, $db;

	(string) $display_output = NULL;
	(int) $counter = 0;

	$none_msg = ($none_msg) ? $none_msg : GMSG_ALL_CATEGORIES;
	
	$page_link = ($page_link) ? $page_link : $_SERVER['PHP_SELF'];
	if($parent_id > 0)
	{
		$root_id = $parent_id;
		while ($root_id > 0)
		{
			$row_category = $db->get_sql_row("SELECT category_id, name, parent_id FROM 
				" . DB_PREFIX . (($reverse_categories) ? 'reverse_categories' : 'categories') . " WHERE 
				category_id=" . $root_id . " LIMIT 0,1");

			if($counter == 0)
			{
				$display_output = $category_lang[$row_category['category_id']];
				$display_output = (!empty($display_output)) ? $display_output : $row_category['name'];
			}
			else if($parent_id != $root_id)
			{
				$category_name = ($reverse_categories) ? $reverse_category_lang[$row_category['category_id']] : $category_lang[$row_category['category_id']];
				$category_name = (!empty($category_name)) ? $category_name : $row_category['name'];
				
				$display_output = (($show_links) ? '<a href="' . $page_link . '?parent_id=' . $row_category['category_id'] . '&name=' . $category_name . ((!empty($additional_vars)) ? ('&' . $additional_vars) : '') . '">' : '') . $category_name . (($show_links) ? '</a>' : '') . ' > ' . $display_output;
			}
			$counter++;
			$root_id = $row_category['parent_id'];
		}
		$display_output = (($show_links && $show_category) ? '<a href="' . $page_link . '?' . $additional_vars . '"><b> ' . GMSG_CATEGORY . ':</b></a> ' : '') . $display_output;
	}

	$display_output = (empty($display_output)) ? $none_msg : $display_output;

	return $display_output;
}

function http_post($server, $port, $url, $vars)
{
	// get urlencoded vesion of $vars array
	(string) $urlencoded = null;

	foreach ($vars as $index => $value)
	{
		$urlencoded .= urlencode($index ) . '=' . urlencode($value) . '&';
	}

	$urlencoded = substr($urlencoded,0,-1);

	$headers = "POST " . $url . " HTTP/1.0\r\n" .
		"Content-Type: application/x-www-form-urlencoded\r\n" .
		"Content-Length: " . strlen($urlencoded) . "\r\n\r\n";

	$fp = fsockopen($server, $port, $errno, $errstr, 10);
	if ($log)
	{
		if (!$fp) fputs($fh,"ERROR: fsockopen failed.\r\nError no: " . $errno . " - " . $errstr . "\n");
		else fputs($fh,"Fsockopen success.\n");
	}

	fputs($fp, $headers);
	fputs($fp, $urlencoded);

	$ret = "";
	while (!feof($fp)) $ret .= fgets($fp, 1024);

	fclose($fp);
	return $ret;
}

function paginate($start,$limit,$total,$file_path,$other_params)
{
	(string) $display_output = null;

	$all_pages = ceil($total / $limit);

	$current_page = floor($start / $limit) + 1;

	if ($all_pages > 10)
	{
		$max_pages = ($all_pages > 9) ? 9 : $all_pages;

		if ($all_pages > 9)
		{
			if ($current_page >= 1 && $current_page <= $all_pages)
			{
				$display_output .= ($current_page > 4) ? ' ... ' : ' ';

				$min_pages = ($current_page > 4) ? $current_page : 5;
				$max_pages = ($current_page < $all_pages - 4) ? $current_page : $all_pages - 4;

				for($i=$min_pages - 4; $i<$max_pages + 5; $i++)
				{
					$display_output .= display_link($file_path . '?start=' . (($i - 1) * $limit) . $other_params, $i, (($i == $current_page) ? false : true));
				}
				$display_output .= ($current_page < $all_pages - 4) ? ' ... ' : ' ';
			}
			else
			{
				$display_output .= ' ... ';
			}
		}
	}
	else
	{
		for($i=1; $i<$all_pages + 1; $i++)
		{
			$display_output .= display_link($file_path . '?start=' . (($i - 1) * $limit) . $other_params, $i, (($i == $current_page) ? false : true));
		}
	}

	if ($current_page > 1)
	{
		$display_output = '[<a href="' . $file_path . '?start=0' . $other_params . '">&lt;&lt;</a>] '.
			'[<a href="' . $file_path . '?start=' . (($current_page - 2) * $limit) . $other_params . '">&lt;</a>] ' . $display_output;
	}

	if ($current_page < $all_pages)
	{
		$display_output .= ' [<a href="' . $file_path . '?start=' . ($current_page * $limit) . $other_params . '">&gt;</a>] '.
			'[<a href="' . $file_path . '?start=' . (($all_pages - 1) * $limit) . $other_params . '">&gt;&gt;</a>]';
	}

	return $display_output;
}

function page_order($file_path, $order_field, $start, $limit, $other_params, $field_name = null)
{
	(string) $display_output = null;

	$file_extension = (defined('IN_ADMIN') && IN_ADMIN == 1) ? '../' : '';

	$display_output = '<a href="' . $file_path . '?start=' . $start . '&limit=' . $limit . $other_params . '
		&order_field=' . $order_field . '&order_type=ASC">'.
		'<img src="' . $file_extension . 'images/s_asc.png" align="absmiddle" border="0" alt="' . $field_name . ' ' . GMSG_ASCENDING . '"></a>'.
		'<a href="' . $file_path . '?start=' . $start . '&limit=' . $limit . $other_params . '
		&order_field=' . $order_field . '&order_type=DESC">'.
		'<img src="' . $file_extension . 'images/s_desc.png" align="absmiddle" border="0" alt="' . $field_name . ' ' . GMSG_DESCENDING . '"></a>';

	return $display_output;
}

function page_alphabetically($file_path, $order_field, $start, $limit, $other_params, $field_name = null)
{
	(string) $display_output = null;

	$file_extension = (IN_ADMIN == 1) ? '../' : '';

    if($field_name == "#")
	    $display_output = '<a href="' . $file_path . '?start=' . $start . '&limit=' . $limit . $other_params . '
		    &order_field=' . $order_field . '&alphabetically=[0-9]">'.$field_name.'</a>';
    else
        $display_output = '<a href="' . $file_path . '?start=' . $start . '&limit=' . $limit . $other_params . '
		    &order_field=' . $order_field . '&alphabetically='.$field_name.'">'.$field_name.'</a>';

	return $display_output;
}

function field_display($field_value, $output_false = '-', $output_true = null)
{
	(string) $display_output = null;

	$display_output = ($field_value) ? (($output_true) ? $output_true : $field_value) : $output_false;

	return $display_output;
}

function display_pagination_results ($start, $limit, $total)
{
	(string) $display_output = null;

	$end = ($start + $limit > $total) ? $total : ($start + $limit);

	if ($total)
	{
		$start++;
	}

	$display_output = GMSG_DISPLAYING_RESULTS . ' <b>' . $start . ' - ' . $end . '</b> ' . GMSG_FROM_LOW. ' <b>' . $total . '</b>';

	return $display_output;
}

function display_link ($link_url, $link_message, $active = true)
{
	(string) $display_output = null;

	$display_output = ($active) ? '<a href="' . $link_url . '">' : '[ ';
	$display_output .= $link_message;
	$display_output .= ($active) ? '</a> ' : ' ] ';

	return $display_output;
}

function remove_spaces($input_variable)
{
	$output_variable = str_replace(' ', '', $input_variable);

	return $output_variable;
}

/**
 * PHP Pro Bid functions start here!
 */

function list_skins($location = 'site', $drop_down = false, $selected_skin = null, $display_none = false, $dd_multiple = false)
{
	(array) $output = null;
	(string) $display_output = null;

	$relative_path = ($location == 'site') ? '' : '../';

	$handle = opendir($relative_path . 'themes');

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

function list_languages($location = 'site', $drop_down = false, $selected_language = null, $show_flags = false)
{
	global $db, $setts;
	(array) $output = null;
	(array) $language_flags = null;
	(string) $display_output = null;


	$relative_path = ($location == 'site') ? '' : '../';

    $relative_path = file_exists($relative_path . 'language')?$relative_path:"../";

	$handle = opendir($relative_path . 'language');

	while ($file = readdir($handle))
	{
		if (!ereg('[.]', $file))
		{
			$output[] = $file;
		}
	}

	closedir($handle);

	/**
	 * this is an enhancement of the function, to create a drop down menu to select the language
	 * in the admin area
	 */

	if ($drop_down)
	{
		$display_output = '<select name="language"> ';

		foreach ($output as $value)
		{
			$display_output .= '<option value="' . $value . '" ' . (($value == $selected_language) ? 'selected' : '') . '>' . $value . '</option> ';
		}

		$display_output .= '</select>';
	}
	else if ($show_flags)
	{
		if ($setts['user_lang'])
		{
			foreach ($output as $value)
			{
				$language_flags[] = '<a href="' . process_link('index', array('change_language' => $value)) . '"><img src="themes/' . $setts['default_theme'] . '/img/' . $value . '.gif" border="0" alt="' . $value . '" align="middle"></a>';
			}

			$display_output = $db->implode_array($language_flags, ' &nbsp; ');
		}
	}
	return ($drop_down || $show_flags) ? $display_output : $output;
}

function timezones_drop_down($selected_value = null)
{
	global $db, $setts;

	(string) $display_output = null;

	$selected_value = (!empty($selected_value)) ? $selected_value : $setts['time_offset'];

	$display_output = '<select name="time_zone"> ';

	$sql_select_timezones = $db->query("SELECT value, caption FROM
		" . DB_PREFIX . "timesettings");

	while ($time_zone = $db->fetch_array($sql_select_timezones))
	{
		$display_output .= '<option value="' . $time_zone['value'] . '" ' . (($time_zone['value'] == $selected_value) ? 'selected' : '') . '>' . $time_zone['caption'] . '</option> ';
	}

	$display_output .= '</select>';

	return $display_output;
}

## this function will be used to save email and language files in admin
function save_file($file_name, $file_content)
{
	global $db;

	(string) $display_output = null;

	$file_content = $db->add_special_chars($file_content);

	if (is_writable($file_name))
	{
		$fp = fopen($file_name, 'w');

		if (!$fp)
		{
			$display_output = GMSG_CANNOT_OPEN_FILE . ' [ ' . $file_name . ' ]';
		}
		else if (!fwrite($fp, $file_content))
		{
			$display_output = GMSG_FILE_NOT_EDITABLE . ' [ ' . $file_name . ' ]';
		}
		else
		{
			$display_output = GMSG_FILE_UPDATED . ' [ ' . $file_name . ' ]';
		}

		fclose($fp);
	}
	else
	{
		$display_output = GMSG_FILE_NOT_WRITABLE . ' [ ' . $file_name . ' ]';
	}

	return $display_output;
}


function categories_list ($selected_category_id, $category_id = 0, $custom_fees = true, $show_reverse = false, $show_list = true)
{
	global $db;

	(string) $display_output = null;

	$addl_query = ($custom_fees) ? " AND custom_fees=1" : '';

	$sql_select_categories = $db->query("SELECT category_id, name FROM " . DB_PREFIX . "categories WHERE
		parent_id=0 AND user_id=0" . $addl_query);

	$nb_categories = $db->num_rows($sql_select_categories);

	$display_output = '<select name="category_id"> '.
		'<option value="0" selected>' . (($custom_fees) ? GMSG_DEFAULT : GMSG_ALL_CATEGORIES) . '</option> ';

	$display_output .= ($nb_categories) ? '<option value="0">' . GMSG_LIST_SEPARATOR . '</option>' : '';

	$cnt = 0;
	while ($category_details = $db->fetch_array($sql_select_categories))
	{
		$display_output .= '<option value="' . $category_details['category_id'] . '" ' . (($category_details['category_id'] == $selected_category_id) ? 'selected' : '') . '>' . $category_details['name'] . '</option>';
		$cnt ++;
	}

	if ($show_reverse)
	{
		$sql_select_categories = $db->query("SELECT category_id, name FROM " . DB_PREFIX . "reverse_categories WHERE
			parent_id=0" . $addl_query);
	
		$nb_categories = $db->num_rows($sql_select_categories);
	
		$display_output .= ($nb_categories) ? '<option value="0">' . GMSG_LIST_SEPARATOR . '</option>' : '';
			
		while ($category_details = $db->fetch_array($sql_select_categories))
		{
			$display_output .= '<option value="reverse|' . $category_details['category_id'] . '" ' . (($category_details['category_id'] == (-1) * $selected_category_id) ? 'selected' : '') . '>' . $category_details['name'] . '</option>';
			$cnt ++;
		}
		
	}

	$display_output .= '</select>';

	return ($show_list) ? $display_output : $cnt;
}

function voucher_form ($voucher_type, $voucher_value = null, $new_table = true)
{
	global $db;
	(string) $display_output = null;

	$is_voucher = $db->count_rows('vouchers', "WHERE voucher_type='" . $voucher_type . "' AND
		(exp_date=0 OR exp_date>" . CURRENT_TIME . ") AND (uses_left>0 OR nb_uses=0)");

	if ($is_voucher)
	{
		$display_output = ($new_table) ? '<br><table width="100%" border="0" cellpadding="3" cellspacing="2" class="border">' : '';
		$display_output .=	'	<tr> '.
         '		<td colspan="2" class="c3">' . GMSG_VOUCHER_SETTINGS . '</td> '.
      	'	</tr> '.
      	'	<tr class="c5"> '.
         '		<td><img src="themes/' . $db->setts['default_theme'] . '/img/pixel.gif" width="1" height="1" /></td> '.
         '		<td><img src="themes/' . $db->setts['default_theme'] . '/img/pixel.gif" width="1" height="1" /></td> '.
      	'	</tr> '.
      	'	<tr class="c1"> '.
         '		<td width="150" align="right" class="contentfont">' . GMSG_VOUCHER_CODE . '</td> '.
         '		<td><input name="voucher_value" type="text" class="contentfont" id="voucher_value" value="' . $voucher_value . '" size="40" /></td> '.
      	'	</tr> '.
      	'	<tr class="reguser"> '.
         '		<td align="right" class="contentfont">&nbsp;</td> '.
         '		<td>' . GMSG_VOUCHER_CODE_EXPL . '</td> '.
      	'	</tr> ';
   	$display_output .= ($new_table) ? '</table>' : '';
	}

	return $display_output;
}

function terms_box ($terms_type, $selected_value)
{
	global $db;
	(string) $display_output = null;

	if ($terms_type == 'registration')
	{
		$new_table = true;
		$colspan = 2;
		$terms = array('enabled' => $db->layout['enable_reg_terms'], 'content' => $db->layout['reg_terms_content']);
		$agreement_msg = '<input type="checkbox" name="agree_terms" value="1" ' . (($selected_value) ? 'checked' : '') . '>' . GMSG_CLICK_TO_AGREE_TO_TERMS;
	}
	else if ($terms_type == 'auction_setup')
	{
		$new_table = false;
		$colspan = 3;
		$terms = array('enabled' => $db->layout['enable_auct_terms'], 'content' => $db->layout['auct_terms_content']);
		$agreement_msg = GMSG_AUCT_TERMS_AGREEMENT_EXPL;
	}

	if ($terms['enabled'])
	{
		if ($new_table)
		{
			$display_output = '<br><table width="100%" border="0" cellpadding="3" cellspacing="2" class="border"> ';
		}

      $display_output .= '	<tr> '.
         '		<td colspan="' . $colspan . '" class="c3">' . GMSG_TERMS_AND_CONDITIONS . '</td> '.
      	'	</tr> '.
      	'	<tr class="c5"> '.
         '		<td><img src="themes/' . $db->setts['default_theme'] . '/img/pixel.gif" width="1" height="1" /></td> '.
         '		<td colspan="' . ($colspan-1) . '"><img src="themes/' . $db->setts['default_theme'] . '/img/pixel.gif" width="1" height="1" /></td> '.
      	'	</tr> '.
      	'	<tr class="c1"> '.
         '		<td width="150" align="right" class="contentfont"></td> '.
         '		<td colspan="' . ($colspan-1) . '"><textarea name="terms_content" cols="50" rows="8" readonly class="smallfont" style="width: 100%; height: 200px;" />' . eregi_replace('<br>', "\n", $terms['content']) . '</textarea></td> '.
      	'	</tr> '.
      	'	<tr class="reguser"> '.
         '		<td align="right" class="contentfont">&nbsp;</td> '.
         '		<td colspan="' . ($colspan-1) . '">' . $agreement_msg . '</td> '.
      	'	</tr> ';
      if ($new_table)
      {
      	$display_output .='</table>';
      }
	}

	return $display_output;
}


function display_globalad($text, $max_length = 2215, $fulltext = true)
{
	global $db;
	(string) $display_output = null;

	$text = $db->add_special_chars($text);

	if ($fulltext)
	{
		$output = (strlen($text) > $max_length) ? substr($text, 0, $max_length - 3) . '... '  : $text;
	}
	else 
	{
	   $text_words = explode(' ', $text);
   
	   $nb_words = count($text_words);
   
	   for ($i=0; $i<$nb_words; $i++)
	   {
		   $display_output[] = (strlen($text_words[$i]) > $max_length) ? substr($text_words[$i], 0, $max_length-3) . '... ' : $text_words[$i];
	   }

		$output = $db->implode_array($display_output, ' ', true, '');
	}

	return $output;
}











function title_resize($text, $max_length = 15, $fulltext = false)
{
	global $db;
	(string) $display_output = null;

	$text = $db->add_special_chars($text);

	if ($fulltext)
	{
		$output = (strlen($text) > $max_length) ? substr($text, 0, $max_length - 3) . '... '  : $text;
	}
	else 
	{
	   $text_words = explode(' ', $text);
   
	   $nb_words = count($text_words);
   
	   for ($i=0; $i<$nb_words; $i++)
	   {
		   $display_output[] = (strlen($text_words[$i]) > $max_length) ? substr($text_words[$i], 0, $max_length-3) . '... ' : $text_words[$i];
	   }

		$output = $db->implode_array($display_output, ' ', true, '');
	}

	return $output;
}

function online_users($in_admin = false)
{
	$data_file = (($in_admin) ? '../' : '') . 'online_users.txt';

	$session_time = 60; //time in **minutes** to consider someone online before removing them

	if(!file_exists($data_file))
	{
		$fp = fopen($data_file, 'w+');
		fclose($fp);
	}

	$ip = $_SERVER['REMOTE_ADDR'];
	$users = array();
	$online_users = array();

	//get users part
	$fp = fopen($data_file, 'r');
	flock($fp, LOCK_SH);

	while(!feof($fp))
	{
		$users[] = rtrim(fgets($fp, 32));
	}

	flock($fp, LOCK_UN);
	fclose($fp);


	//cleanup part
	$x = 0;
	$already_in = false;

	foreach($users as $key => $data)
	{
		list( , $last_visit) = explode('|', $data);

		if(CURRENT_TIME - $last_visit >= $session_time * 60)
		{
			$users[$x] = '';
		}
		else
		{
			if(strpos($data, $ip) !== false)
			{
				$already_in = true;
				$users[$x] = $ip . '|' . time(); //update record
			}
		}
		$x++;
	}

	if($already_in == false)
	{
		$users[] = $ip . '|' . time();
	}

	//write file
	$fp = fopen($data_file, 'w+');
	flock($fp, LOCK_EX);

	$nb_users = 0;
	foreach($users as $user)
	{
		if(!empty($user))
		{
			fwrite($fp, $user . "\r\n");
			$nb_users++;
		}
	}
	flock($fp, LOCK_UN);
	fclose($fp);

	if ($in_admin)
	{
		$nb_users--;
		$nb_users = ($nb_users < 0) ? 0 : $nb_users;
	}
	
	return $nb_users;
}

function blocked_user ($user_id, $owner_id, $block_type = 'bid')
{
	global $db;

	$addl_query = null;
	switch ($block_type)
	{
		case 'message':
			$addl_query = ' AND block_message=1';
			break;
		case 'reputation':
			$addl_query = ' AND block_reputation=1';
			break;
		default:
			$addl_query = ' AND block_bid=1';
			break;
			
	}
	$is_blocked = $db->count_rows('blocked_users', "WHERE
		user_id='" . intval($user_id) . "' AND owner_id='" . intval($owner_id) . "' " . $addl_query);

	$output = ($is_blocked) ? true : false;

	return $output;
}

function block_reason ($user_id, $owner_id, $block_type = 'bid')
{
	global $db;

	$block_msg = null;
	switch ($block_type)
	{
		case 'message':
			$block_msg = MSG_BLOCKED_USER_MESSAGE_MSG;
			break;
		case 'reputation':
			$block_msg = MSG_BLOCKED_USER_REPUTATION_MSG;
			break;
		default:
			$block_msg = MSG_BLOCKED_USER_MSG;
			break;
			
	}
	
	$block_details = $db->get_sql_row("SELECT b.*, u.username FROM " . DB_PREFIX . "blocked_users b
		LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=b.user_id WHERE
		b.user_id='" . $user_id . "' AND b.owner_id='" . $owner_id . "'");

	$block_message = '<p class="errormessage">' . $block_msg .
		(($block_details['show_reason']) ? '<br><b>' . MSG_REASON .'</b>: ' . $block_details['block_reason'] : '') . '</p>';

	return $block_message;
}

function block_type ($block_details)
{
	if ($block_details['block_bid'])
	{
		$output[] = MSG_BIDDING;
	}
	if ($block_details['block_message'])
	{
		$output[] = MSG_MESSAGING;
	}
	if ($block_details['block_reputation'])
	{
		$output[] = MSG_REPUTATION;
	}
	
	return $output;
}

function check_banned ($banned_address, $address_type)
{
	global $db;
	$output = array('result' => false, 'display' => null);

	$is_banned = $db->count_rows('banned', "WHERE banned_address='" . $banned_address . "' AND address_type='" . $address_type . "'");

	if ($is_banned)
	{
		$output['result'] = true;

		$output['display'] = '<p class="errormessage" align="center">' . MSG_BANNED_EXPL_A . ' <b>'.
			(($address_type == 1) ? MSG_IP_ADDRESS : MSG_EMAIL_ADDRESS) . '</b> ' . MSG_BANNED_EXPL_B . '</p>';
	}

	return $output;
}

function meta_tags ($base_url, $parent_id, $auction_id, $wanted_ad_id, $shop_id)
{
	global $db, $category_lang, $setts, $reverse_category_lang;
	(array) $output = null;
	(array) $subcats_array = null;

	if (eregi('auction_details.php', $base_url))
	{
		$item_details = $db->get_sql_row("SELECT auction_id, name, end_time, category_id FROM " . DB_PREFIX . "auctions WHERE
			auction_id='" . $auction_id . "'");

		$parent_id = $item_details['category_id'];
	}
	else if (eregi('wanted_details.php', $base_url))
	{
		$item_details = $db->get_sql_row("SELECT wanted_ad_id, name, end_time, category_id FROM " . DB_PREFIX . "wanted_ads WHERE
			wanted_ad_id='" . $wanted_ad_id . "'");

		$parent_id = $item_details['category_id'];
	}

	if($parent_id > 0)
	{
		$root_id = $parent_id;
		while ($root_id > 0)
		{
			$row_category = $db->get_sql_row("SELECT category_id, parent_id 
				FROM " . DB_PREFIX . ((eregi('reverse_auctions.php', $base_url)) ? 'reverse_categories' : 'categories') . " 
				WHERE category_id=" . $root_id . " LIMIT 0,1");
			
			if (eregi('reverse_auctions.php', $base_url))
			{
				$subcats_array[] = $reverse_category_lang[$row_category['category_id']];
			}
			else 
			{
			   $subcats_array[] = $category_lang[$row_category['category_id']];
			}

			$root_id = $row_category['parent_id'];
		}

		$subcats_array = array_reverse($subcats_array);
	}

	/* now generate the title and meta tags */
	if (eregi('auction_details.php', $base_url))
	{
		$output['title'] = $db->add_special_chars($item_details['name']) . ' (' . MSG_AUCTION_ID . ': ' . $item_details['auction_id'] . ', ' .
			GMSG_END_TIME . ': ' . show_date($item_details['end_time']) . ') - ' . $setts['sitename'];

		$output['meta_tags'] = '<meta name="description" content="' . MSG_MTT_FIND . ' ' . $db->add_special_chars($item_details['name']) . ' ' .
			MSG_MTT_IN_THE . ' ' . $db->add_special_chars($db->implode_array($subcats_array, ' - ')) . ' ' . MSG_MTT_CATEGORY_ON . ' ' . $setts['sitename'] . '"> '.
			'<meta name="keywords" content="' . $db->add_special_chars($item_details['name']) . ', ' . $db->add_special_chars($db->implode_array($subcats_array, ', ')) . ', ' .
			$setts['sitename'] . '"> ';
	}
	else if (eregi('wanted_details.php', $base_url))
	{
		$output['title'] = $db->add_special_chars($item_details['name']) . ' (' . MSG_WANTED_AD_ID . ': ' . $item_details['wanted_ad_id'] . ', ' .
			GMSG_END_TIME . ': ' . show_date($item_details['end_time']) . ') - ' . $setts['sitename'];

		$output['meta_tags'] = '<meta name="description" content="' . MSG_MTT_FIND . ' ' . $db->add_special_chars($item_details['name']) . ' ' .
			MSG_MTT_IN_THE . ' ' . $db->add_special_chars($db->implode_array($subcats_array, ' - ')) . ' ' . MSG_MTT_CATEGORY_ON . ' ' . $setts['sitename'] . '"> '.
			'<meta name="keywords" content="' . $db->add_special_chars($item_details['name']) . ', ' . $db->add_special_chars($db->implode_array($subcats_array, ', ')) . ', ' .
			$setts['sitename'] . '"> ';
	}
	else if (eregi('categories.php', $base_url))
	{
		$output['title'] = ((is_array($subcats_array)) ? $db->add_special_chars($db->implode_array($subcats_array, ' - ')) . ' - ' : '') . $setts['sitename'];

		$main_category_id = $db->main_category($parent_id);
		$category_details = $db->get_sql_row("SELECT meta_description, meta_keywords FROM " . DB_PREFIX . "categories WHERE
			category_id='" . $main_category_id . "'");

		if (!empty($category_details['meta_description']) && !empty($category_details['meta_keywords']))
		{
			$output['meta_tags'] = '<meta name="description" content="' . $db->add_special_chars($category_details['meta_description']) . '"> '.
				'<meta name="keywords" content="' . $db->add_special_chars($category_details['meta_keywords']) . '"> ';
		}
		else
		{
			$output['meta_tags'] = $db->add_special_chars($setts['metatags']);
		}
	}
	else if (eregi('reverse_auctions.php', $base_url))
	{
		$output['title'] = ((is_array($subcats_array)) ? $db->add_special_chars($db->implode_array($subcats_array, ' - ')) . ' - ' : '') . GMSG_REVERSE_AUCTIONS .  ' - ' . $setts['sitename'];

		$main_category_id = $db->main_category($parent_id);
		$category_details = $db->get_sql_row("SELECT meta_description, meta_keywords FROM " . DB_PREFIX . "reverse_categories WHERE
			category_id='" . $main_category_id . "'");

		if (!empty($category_details['meta_description']) && !empty($category_details['meta_keywords']))
		{
			$output['meta_tags'] = '<meta name="description" content="' . $db->add_special_chars($category_details['meta_description']) . '"> '.
				'<meta name="keywords" content="' . $db->add_special_chars($category_details['meta_keywords']) . '"> ';
		}
		else
		{
			$output['meta_tags'] = $db->add_special_chars($setts['metatags']);
		}
	}
	else
	{
		if (eregi('shop.php', $base_url))
		{
			$user_details = $db->get_sql_row("SELECT shop_name, shop_metatags FROM " . DB_PREFIX . "users WHERE 
				user_id='" . intval($shop_id) . "'");
			$output['title'] = $user_details['shop_name'] . ' - ' . $setts['sitename'];			
			
			if (!empty($user_details['shop_metatags']))
			{
				$output['meta_tags'] = '<meta name="keywords" content="' . $db->add_special_chars($user_details['shop_metatags']) . '"> ';				
			}
		}
	   else
	   {
		   $output['title'] = $setts['sitename'];
		}

		if (empty($output['meta_tags']))
		{
		   $output['meta_tags'] = $db->add_special_chars($setts['metatags']);
	   }
	}

	return $output;
}

function remove_cache_img()
{
	global $fileExtension;
	
	$cache_directory = $fileExtension . 'cache/';
	$time_limit = 60*60*24; ## one day
	
	$cache_dir = opendir($cache_directory);
	
	while ($file = readdir($cache_dir)) 
	{
		if($file != '..' && $file !='.' && $file !='' && $file !='index.htm') 
		{
			$filestats = array();
			$filestats = stat($cache_directory . $file);
			
			if (($filestats[10] + $time_limit) < CURRENT_TIME)
			{
				@unlink($cache_directory . $file);
			}
		}
	}
	
	closedir($cache_dir);
	clearstatcache(); 	
}

function user_pics ($user_id, $reputation_only = false, $reverse = false)
{
	global $db, $setts, $fileExtension;	
	(string) $display_output = null;

	$user_details = $db->get_sql_row("SELECT enable_aboutme_page, shop_active, seller_verified, enable_profile_page FROM " . DB_PREFIX . "users WHERE user_id='" . $user_id . "'");

	$positive_reputation = $db->count_rows('reputation', "WHERE user_id='" . $user_id . "' AND 
		reputation_rate IN (4,5) AND submitted=1 " . (($reverse) ? 'AND reverse_id>0' : ''));
	$negative_reputation = $db->count_rows('reputation', "WHERE user_id='" . $user_id . "' AND 
		reputation_rate IN (1,2) AND submitted=1 " . (($reverse) ? 'AND reverse_id>0' : ''));
	
	$reputation_rating = $positive_reputation - $negative_reputation;	
	$link_url = ($reverse) ? 'reverse_profile.php' : 'user_reputation.php';
	$reputation_rating_link = '<span class="contentfont"><a href="' . $fileExtension . $link_url . '?user_id=' . $user_id . '">' . $reputation_rating . '</a></span>';
	
	if ($reputation_rating < 1)
	{
		$display_output = ' (' . $reputation_rating_link . ') ';
	}
	else if ($reputation_rating >= 1 && $reputation_rating < 10) 
	{
		$display_output = ' (' . $reputation_rating_link . ') <img align=absmiddle src="' . $fileExtension . 'themes/' . $setts['default_theme'] . '/img/system/yellow_star.gif" border="0">';
	}
	else if ($reputation_rating >= 10 && $reputation_rating < 50) 
	{
		$display_output = ' (' . $reputation_rating_link . ') <img align=absmiddle src="' . $fileExtension . 'themes/' . $setts['default_theme'] . '/img/system/green_star.gif" border="0">';
	}
	else if ($reputation_rating >= 50 && $reputation_rating < 100) 
	{
		$display_output = ' (' . $reputation_rating_link . ') <img align=absmiddle src="' . $fileExtension . 'themes/' . $setts['default_theme'] . '/img/system/blue_star.gif" border="0">';
	}
	else if ($reputation_rating >= 100 && $reputation_rating < 200) 
	{
		$display_output = ' (' . $reputation_rating_link . ') <img align=absmiddle src="' . $fileExtension . 'themes/' . $setts['default_theme'] . '/img/system/red_star.gif" border="0">';
	}
	else if ($reputation_rating >= 200) 
	{
		$display_output = ' (' . $reputation_rating_link . ') <img align=absmiddle src="' . $fileExtension . 'themes/' . $setts['default_theme'] . '/img/system/gold_star.gif" border="0">';
	}

	if (!$reputation_only && !$reverse)
	{
		if ($user_details['seller_verified'])
		{
			$display_output .= ' <img align=absmiddle src="' . $fileExtension . 'themes/' . $setts['default_theme'] . '/img/system/verified.gif" border="0" alt="' . GMSG_VERIFIED_SELLER . '">';		
		}
		
		if ($user_details['enable_aboutme_page'])
		{
			$display_output .= ' <a href="' . $fileExtension . 'about_me.php?user_id=' . $user_id . '"><img src="' . $fileExtension . 'themes/' . $setts['default_theme'] . '/img/system/about_me.gif" border="0" align="absmiddle"></a>';
		}
		
		if ($user_details['shop_active'])
		{
			$display_output .= ' <a href="' . $fileExtension . 'shop.php?user_id=' . $user_id . '"><img src="' . $fileExtension . 'themes/' . $setts['default_theme'] . '/img/system/25store.gif" border="0" align=absmiddle></a>';
		}
		
		if ($user_details['enable_profile_page'] && $setts['enable_profile_page'])
		{
			$display_output .= ' <a href="' . $fileExtension . 'profile.php?user_id=' . $user_id . '"><img src="' . $fileExtension . 'themes/' . $setts['default_theme'] . '/img/system/profile.gif" border="0" align=absmiddle alt="' . MSG_VIEW_MEMBER_PROFILE . '"></a>';
		}
	}
	
	return $display_output;
}

/**
 * below are all the category counters related functions 
 */

 function ads_counter ($category_id, $operation = 'add', $advert_id = 0)
{
	global $db;

#	$can_add = ($category_id) ? true : false;
	
		
#	if ($can_add)
#	{
		$root_id = $category_id;
		
		while ($root_id > 0) 
		{
			$db->query("UPDATE " . DB_PREFIX . "categories SET 
				ads_counter=ads_counter" . (($operation == 'add') ? '+' : '-') . "1 WHERE category_id='" . $root_id . "'");
			
			$root_id = $db->get_sql_field("SELECT parent_id FROM " . DB_PREFIX . "categories WHERE category_id=" . $root_id, 'parent_id');
		}		
#	}
}

 
 
 function auction_counter ($category_id, $operation = 'add', $auction_id = 0)
{
	global $db;

	$can_add = ($category_id) ? true : false;
	
	if ($auction_id)
	{
		$list_in = $db->get_sql_field("SELECT list_in FROM " . DB_PREFIX . "auctions WHERE auction_id='" . $auction_id . "'", 'list_in');
		$can_add = ($list_in == 'store') ? false : $can_add;
	}
	
	if ($can_add)
	{
		$root_id = $category_id;
		
		while ($root_id > 0) 
		{
			$db->query("UPDATE " . DB_PREFIX . "categories SET 
				items_counter=items_counter" . (($operation == 'add') ? '+' : '-') . "1 WHERE category_id='" . $root_id . "'");
			
			$root_id = $db->get_sql_field("SELECT parent_id FROM " . DB_PREFIX . "categories WHERE category_id=" . $root_id, 'parent_id');
		}		
	}
}

function wanted_counter ($category_id, $operation = 'add')
{
	global $db;
	
	$can_add = ($category_id) ? true : false;
	
	if ($can_add)
	{
		$root_id = $category_id;
		
		while ($root_id > 0) 
		{
			$db->query("UPDATE " . DB_PREFIX . "categories SET 
				wanted_counter=wanted_counter" . (($operation == 'add') ? '+' : '-') . "1 WHERE category_id='" . $root_id . "'");
			
			$root_id = $db->get_sql_field("SELECT parent_id FROM " . DB_PREFIX . "categories WHERE category_id=" . $root_id, 'parent_id');
		}		
	}
}

function reverse_counter ($category_id, $operation = 'add')
{
	global $db;

	$can_add = ($category_id) ? true : false;
	
	if ($can_add)
	{
		$root_id = $category_id;
		
		while ($root_id > 0) 
		{
			$db->query("UPDATE " . DB_PREFIX . "reverse_categories SET 
				items_counter=items_counter" . (($operation == 'add') ? '+' : '-') . "1 WHERE category_id='" . $root_id . "'");
			
			$root_id = $db->get_sql_field("SELECT parent_id FROM " . DB_PREFIX . "categories WHERE category_id=" . $root_id, 'parent_id');
		}		
	}
}

function user_counter ($user_id, $operation = 'add')
{
	global $db;
	
	$cnt_active = ($operation == 'add') ? 0 : 1;
	
	$sql_select_auctions = $db->query("SELECT auction_id, category_id, addl_category_id FROM " . DB_PREFIX . "auctions WHERE 
		owner_id='" . $user_id . "' AND active=" . $cnt_active . " AND approved=1 AND closed=0 AND deleted!=1 AND list_in!='store'");
	
	while ($item_details = $db->fetch_array($sql_select_auctions)) 
	{
		auction_counter($item_details['category_id'], $operation, $item_details['auction_id']);
		auction_counter($item_details['addl_category_id'], $operation, $item_details['auction_id']);	
	}

	$sql_select_wa = $db->query("SELECT category_id, addl_category_id FROM " . DB_PREFIX . "wanted_ads WHERE 
		owner_id='" . $user_id . "' AND active=" . $cnt_active . " AND closed=0 AND deleted!=1");
	
	while ($item_details = $db->fetch_array($sql_select_wa)) 
	{
		wanted_counter($item_details['category_id'], $operation);
		wanted_counter($item_details['addl_category_id'], $operation);	
	}
	
	$sql_select_reverse = $db->query("SELECT category_id, addl_category_id FROM " . DB_PREFIX . "reverse_auctions WHERE 
		owner_id='" . $user_id . "' AND active=" . $cnt_active . " AND closed=0 AND deleted!=1");
	
	while ($item_details = $db->fetch_array($sql_select_reverse)) 
	{
		reverse_counter($item_details['category_id'], $operation);
		reverse_counter($item_details['addl_category_id'], $operation);	
	}	
}

function user_account_management($user_id, $active)
{
	global $db;
	
	$operation = ($active == 1) ? 'add' : 'remove';
	
	## if the activation is done through the admin, the payment_status flag will always be set to confirmed so the account_payment 
	## issue doesnt come into play anymore
	$db->query("UPDATE " . DB_PREFIX . "users SET active=" . $active . ", payment_status='confirmed' WHERE user_id='" . $user_id . "'");
	
	user_counter($user_id, $operation);	
	$db->query("UPDATE " . DB_PREFIX . "auctions SET active=" . $active . " WHERE owner_id='" . $user_id . "'");
	$db->query("UPDATE " . DB_PREFIX . "wanted_ads SET active=" . $active . " WHERE owner_id='" . $user_id . "'");
}

function send_mail($to, $subject, $text_message, $from_email, $html_message = null, $from_name = null, $send = true, $reply_to = null) 
{
	global $setts, $current_version;

	if ($send)
	{
		## set date
		$tz = date('Z');
		$tzs = ($tz < 0) ? '-' : '+';
	   $tz = abs($tz);
		$tz = ($tz / 3600) * 100 + ($tz % 3600) / 60;
		$mail_date = sprintf('%s %s%04d', date('D, j M Y H:i:s'), $tzs, $tz);
		
		$uniq_id = md5(uniqid(time()));
	
		## create the message body
		$html_message = ($html_message) ? $html_message : $text_message;
		
		$html_msg = "<!--\n" . $text_message . "\n-->\n".
			"<html><body><img src=\"" . SITE_PATH . "images/bringitlocalogo.gif\"><p>" . EMAIL_FONT . $html_message . "</body></html>";
	
		$from_name = (!empty($from_name)) ? $from_name : GMSG_MAIL_FROM_ADMIN;
		switch ($setts['mailer'])
		{
			case 'sendmail': ## send through the UNIX Sendmail function
				## create header
				$header = "Date: " . $mail_date . "\n".
					"Return-Path: " . $from_email . "\n".
					"To: " . $to . "\n".
					"From: " . $from_name . " <" . $from_email . ">\n".
					(($setts['enable_bcc']) ? "Bcc: " . $setts['admin_email'] . "\n" : "").
					"Reply-to: " . ((!empty($reply_to)) ? $reply_to : $from_email) . "\n".
					"Subject: " . $subject . "\n".
					sprintf("Message-ID: <%s@%s>%s", $uniq_id, $_SERVER['SERVER_NAME'], "\n").
					"X-Priority: 3\n".
					"X-Mailer: PHP Pro Bid/Sendmail [version " . $current_version . "]\n".
					"MIME-Version: 1.0\n".
					"Content-Transfer-Encoding: 7bit\n".
					sprintf("Content-Type: %s; charset=\"%s\"","text/html","iso-8859-1").
					"\n\n";
		
				if ($from_email)
				{
					$output = sprintf("%s -oi -f %s -t", $setts['sendmail_path'], $from_email);
				}
				else
				{
					$output = sprintf("%s -oi -t", $setts['sendmail_path']);
				}
		
				if(!@$mail = popen($output, "w")) 
				{
					echo GMSG_COULDNT_EXECUTE . ': ' . $setts['sendmail_path'];
				}
				
				fputs($mail, $header);
				fputs($mail, $html_msg);
		        
				$result = pclose($mail) >> 8 & 0xFF;
				
				if($result != 0) 
				{
					echo GMSG_COULDNT_EXECUTE . ': ' . $setts['sendmail_path'];
				}
				break;
				
			case 'mail':
				## send through the PHP mail() function
				## create header
				$boundary[1] = "b1_" . $uniq_id;
				$boundary[2] = "b2_" . $uniq_id;
			
				$header = "Date: ".$mail_date."\n".
					"Return-Path: " . $from_email . "\n".
					"From: " . $from_name . " <" . $from_email . ">\n".
					(($setts['enable_bcc']) ? "Bcc: " . $setts['admin_email'] . "\n" : "").
					"Reply-to: " . ((!empty($reply_to)) ? $reply_to : $from_email) . "\n".
					sprintf("Message-ID: <%s@%s>%s", $uniq_id, $_SERVER['SERVER_NAME'], "\n").
					"X-Priority: 3\n".
					"X-Mailer: PHP Pro Bid/Sendmail [version " . $current_version . "]\n".
					"MIME-Version: 1.0\n".
					"Content-Transfer-Encoding: 7bit\n".
					sprintf("Content-Type: %s; charset=\"%s\"","text/html","iso-8859-1").
		
				$params = sprintf("-oi -f %s", $from_email);
				
				if (strlen(ini_get('safe_mode'))<1) 
				{
					$old_from = ini_get('sendmail_from');
					ini_set("sendmail_from", $from_email);
					$result = @mail($to, $subject, $html_msg, $header, $params);
				} 
				else 
				{
					$result = @mail($to, $subject, $html_msg, $header);
				}
				
				if (isset($old_from)) 
				{
					ini_set("sendmail_from",$old_from);
				}
				
				if (!$result) 
				{
					echo GMSG_MAIL_SENDING_FAILED;
				}
				
				break;
			case 'smtp':
				## send through the smtp method
				smtp_mailer($from_email, $from_name, $to, $subject, $html_msg, $reply_to);
				break;
		}
	}
}

function suspend_debit_users()
{
	global $db, $fees, $setts, $session, $parent_dir;
	(array) $addl_query = null;
	$remove_session = false;
	
	$addl_query[] = "balance>max_credit"; // suspend if max_credit is exceeded
		
	if ($setts['account_mode_personal'] == 1)
	{
		$addl_query[] = "payment_mode=2"; // personal mode, only suspend users that have account mode enabled
	}

	if (!$setts['suspend_over_bal_users'])
	{
		$addl_query[] = "exceeded_balance_email=0";
	}

	$query = $db->implode_array($addl_query, ' AND ');
		
	if ($setts['account_mode'] == 2 || $setts['account_mode_personal'] == 1)
	{
		$sql_select_users = $db->query("SELECT user_id FROM " . DB_PREFIX . "users WHERE 
			active=1 AND " . $query);
			
		while ($user_details = $db->fetch_array($sql_select_users))
		{
			$mail_input_id = $user_details['user_id'];

			if ($setts['suspend_over_bal_users'])
			{
				user_account_management($user_details['user_id'], 0);
				
				include ($parent_dir . 'language/' . $setts['site_lang'] . '/mails/exceeded_balance_user_notification.php');
				if ($session->value('user_id') == $user_details['user_id'])
				{
					$remove_session = true;
				}
			}
			else
			{
				include ($parent_dir . 'language/' . $setts['site_lang'] . '/mails/exceeded_balance_user_notification_no_suspension.php');
			}
		}
	}
	
	return $remove_session;
}

function last_char($value, $char = ',')
{
	$value = trim($value);
	$last_char = substr($value, -1);
			
	$value = ($last_char == $char) ? substr($value, 0, -1) : $value;

	return $value;
}

function paypal_countries_list()
{
	$output = array(
		'UNITED KINGDOM' => 'GB', 
		'UNITED STATES' => 'US', 
		'CANADA' => 'CA', 
		'AUSTRALIA' => 'AU', 
		'AFGHANISTAN' => 'AF',
		'ALAND ISLANDS' => 'AX', 
		'ALBANIA' => 'AL', 
		'ALGERIA' => 'DZ', 
		'AMERICAN SAMOA' => 'AS', 
		'ANDORRA' => 'AD', 
		'ANGOLA' => 'AO', 
		'ANGUILLA' => 'AI', 
		'ANTARCTICA' => 'AQ', 
		'ANTIGUA AND BARBUDA' => 'AG', 
		'ARGENTINA' => 'AR', 
		'ARMENIA' => 'AM', 
		'ARUBA' => 'AW', 
		'AUSTRIA' => 'AT', 
		'AZERBAIJAN' => 'AZ', 
		'BAHAMAS' => 'BS', 
		'BAHRAIN' => 'BH', 
		'BANGLADESH' => 'BD', 
		'BARBADOS' => 'BB', 
		'BELARUS' => 'BY', 
		'BELGIUM' => 'BE', 
		'BELIZE' => 'BZ', 
		'BENIN' => 'BJ', 
		'BERMUDA' => 'BM', 
		'BHUTAN' => 'BT', 
		'BOLIVIA' => 'BO', 
		'BOSNIA AND HERZEGOVINA' => 'BA', 
		'BOTSWANA' => 'BW', 
		'BOUVET ISLAND' => 'BV', 
		'BRAZIL' => 'BR', 
		'BRITISH INDIAN OCEAN TERRITORY' => 'IO', 
		'BRUNEI DARUSSALAM' => 'BN', 
		'BULGARIA' => 'BG', 
		'BURKINA FASO' => 'BF', 
		'BURUNDI' => 'BI', 
		'CAMBODIA' => 'KH', 
		'CAMEROON' => 'CM', 
		'CAPE VERDE' => 'CV', 
		'CAYMAN ISLANDS' => 'KY', 
		'CENTRAL AFRICAN REPUBLIC' => 'CF', 
		'CHAD' => 'TD', 
		'CHILE' => 'CL', 
		'CHINA' => 'CN', 
		'CHRISTMAS ISLAND' => 'CX', 
		'COCOS (KEELING) ISLANDS' => 'CC', 
		'COLOMBIA' => 'CO', 
		'COMOROS' => 'KM', 
		'CONGO' => 'CG', 
		'CONGO, THE DEMOCRATIC REPUBLIC OF THE' => 'CD', 
		'COOK ISLANDS' => 'CK', 
		'COSTA RICA' => 'CR', 
		'C�TE D\'IVOIRE' => 'CI', 
		'CROATIA' => 'HR', 
		'CUBA' => 'CU', 
		'CYPRUS' => 'CY', 
		'CZECH REPUBLIC' => 'CZ', 
		'DENMARK' => 'DK', 
		'DJIBOUTI' => 'DJ', 
		'DOMINICA' => 'DM', 
		'DOMINICAN REPUBLIC' => 'DO', 
		'ECUADOR' => 'EC', 
		'EGYPT' => 'EG', 
		'EL SALVADOR' => 'SV', 
		'EQUATORIAL GUINEA' => 'GQ', 
		'ERITREA' => 'ER', 
		'ESTONIA' => 'EE', 
		'ETHIOPIA' => 'ET', 
		'FALKLAND ISLANDS (MALVINAS)' => 'FK', 
		'FAROE ISLANDS' => 'FO', 
		'FIJI' => 'FJ', 
		'FINLAND' => 'FI', 
		'FRANCE' => 'FR', 
		'FRENCH GUIANA' => 'GF', 
		'FRENCH POLYNESIA' => 'PF', 
		'FRENCH SOUTHERN TERRITORIES' => 'TF', 
		'GABON' => 'GA', 
		'GAMBIA' => 'GM', 
		'GEORGIA' => 'GE', 
		'GERMANY' => 'DE', 
		'GHANA' => 'GH', 
		'GIBRALTAR' => 'GI', 
		'GREECE' => 'GR', 
		'GREENLAND' => 'GL', 
		'GRENADA' => 'GD', 
		'GUADELOUPE' => 'GP', 
		'GUAM' => 'GU', 
		'GUATEMALA' => 'GT', 
		'GUERNSEY' => 'GG', 
		'GUINEA' => 'GN', 
		'GUINEA-BISSAU' => 'GW', 
		'GUYANA' => 'GY', 
		'HAITI' => 'HT', 
		'HEARD ISLAND AND MCDONALD ISLANDS' => 'HM', 
		'HOLY SEE (VATICAN CITY STATE)' => 'VA', 
		'HONDURAS' => 'HN', 
		'HONG KONG' => 'HK', 
		'HUNGARY' => 'HU', 
		'ICELAND' => 'IS', 
		'INDIA' => 'IN', 
		'INDONESIA' => 'ID', 
		'IRAN, ISLAMIC REPUBLIC OF' => 'IR', 
		'IRAQ' => 'IQ', 
		'IRELAND' => 'IE', 
		'ISLE OF MAN' => 'IM', 
		'ISRAEL' => 'IL', 
		'ITALY' => 'IT', 
		'JAMAICA' => 'JM', 
		'JAPAN' => 'JP', 
		'JERSEY' => 'JE', 
		'JORDAN' => 'JO', 
		'KAZAKHSTAN' => 'KZ', 
		'KENYA' => 'KE', 
		'KIRIBATI' => 'KI', 
		'KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF' => 'KP', 
		'KOREA, REPUBLIC OF' => 'KR', 
		'KUWAIT' => 'KW', 
		'KYRGYZSTAN' => 'KG', 
		'LAO PEOPLE\'S DEMOCRATIC REPUBLIC' => 'LA', 
		'LATVIA' => 'LV', 
		'LEBANON' => 'LB', 
		'LESOTHO' => 'LS', 
		'LIBERIA' => 'LR', 
		'LIBYAN ARAB JAMAHIRIYA' => 'LY', 
		'LIECHTENSTEIN' => 'LI', 
		'LITHUANIA' => 'LT', 
		'LUXEMBOURG' => 'LU', 
		'MACAO' => 'MO', 
		'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF' => 'MK', 
		'MADAGASCAR' => 'MG', 
		'MALAWI' => 'MW', 
		'MALAYSIA' => 'MY', 
		'MALDIVES' => 'MV', 
		'MALI' => 'ML', 
		'MALTA' => 'MT', 
		'MARSHALL ISLANDS' => 'MH', 
		'MARTINIQUE' => 'MQ', 
		'MAURITANIA' => 'MR', 
		'MAURITIUS' => 'MU', 
		'MAYOTTE' => 'YT', 
		'MEXICO' => 'MX', 
		'MICRONESIA, FEDERATED STATES OF' => 'FM', 
		'MOLDOVA, REPUBLIC OF' => 'MD', 
		'MONACO' => 'MC', 
		'MONGOLIA' => 'MN', 
		'MONTENEGRO' => 'ME', 
		'MONTSERRAT' => 'MS', 
		'MOROCCO' => 'MA', 
		'MOZAMBIQUE' => 'MZ', 
		'MYANMAR' => 'MM', 
		'NAMIBIA' => 'NA', 
		'NAURU' => 'NR', 
		'NEPAL' => 'NP', 
		'NETHERLANDS' => 'NL', 
		'NETHERLANDS ANTILLES' => 'AN', 
		'NEW CALEDONIA' => 'NC', 
		'NEW ZEALAND' => 'NZ', 
		'NICARAGUA' => 'NI', 
		'NIGER' => 'NE', 
		'NIGERIA' => 'NG', 
		'NIUE' => 'NU', 
		'NORFOLK ISLAND' => 'NF', 
		'NORTHERN MARIANA ISLANDS' => 'MP', 
		'NORWAY' => 'NO', 
		'OMAN' => 'OM', 
		'PAKISTAN' => 'PK', 
		'PALAU' => 'PW', 
		'PALESTINIAN TERRITORY, OCCUPIED' => 'PS', 
		'PANAMA' => 'PA', 
		'PAPUA NEW GUINEA' => 'PG', 
		'PARAGUAY' => 'PY', 
		'PERU' => 'PE', 
		'PHILIPPINES' => 'PH', 
		'PITCAIRN' => 'PN', 
		'POLAND' => 'PL', 
		'PORTUGAL' => 'PT', 
		'PUERTO RICO' => 'PR', 
		'QATAR' => 'QA', 
		'R�UNION' => 'RE', 
		'ROMANIA' => 'RO', 
		'RUSSIAN FEDERATION' => 'RU', 
		'RWANDA' => 'RW', 
		'SAINT BARTH�LEMY' => 'BL', 
		'SAINT HELENA' => 'SH', 
		'SAINT KITTS AND NEVIS' => 'KN', 
		'SAINT LUCIA' => 'LC', 
		'SAINT MARTIN' => 'MF', 
		'SAINT PIERRE AND MIQUELON' => 'PM', 
		'SAINT VINCENT AND THE GRENADINES' => 'VC', 
		'SAMOA' => 'WS', 
		'SAN MARINO' => 'SM', 
		'SAO TOME AND PRINCIPE' => 'ST', 
		'SAUDI ARABIA' => 'SA', 
		'SENEGAL' => 'SN', 
		'SERBIA' => 'RS', 
		'SEYCHELLES' => 'SC', 
		'SIERRA LEONE' => 'SL', 
		'SINGAPORE' => 'SG', 
		'SLOVAKIA' => 'SK', 
		'SLOVENIA' => 'SI', 
		'SOLOMON ISLANDS' => 'SB', 
		'SOMALIA' => 'SO', 
		'SOUTH AFRICA' => 'ZA', 
		'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS' => 'GS', 
		'SPAIN' => 'ES', 
		'SRI LANKA' => 'LK', 
		'SUDAN' => 'SD', 
		'SURINAME' => 'SR', 
		'SVALBARD AND JAN MAYEN' => 'SJ', 
		'SWAZILAND' => 'SZ', 
		'SWEDEN' => 'SE', 
		'SWITZERLAND' => 'CH', 
		'SYRIAN ARAB REPUBLIC' => 'SY', 
		'TAIWAN, PROVINCE OF CHINA' => 'TW', 
		'TAJIKISTAN' => 'TJ', 
		'TANZANIA, UNITED REPUBLIC OF' => 'TZ', 
		'THAILAND' => 'TH', 
		'TIMOR-LESTE' => 'TL', 
		'TOGO' => 'TG', 
		'TOKELAU' => 'TK', 
		'TONGA' => 'TO', 
		'TRINIDAD AND TOBAGO' => 'TT', 
		'TUNISIA' => 'TN', 
		'TURKEY' => 'TR', 
		'TURKMENISTAN' => 'TM', 
		'TURKS AND CAICOS ISLANDS' => 'TC', 
		'TUVALU' => 'TV', 
		'UGANDA' => 'UG', 
		'UKRAINE' => 'UA', 
		'UNITED ARAB EMIRATES' => 'AE', 
		'UNITED STATES MINOR OUTLYING ISLANDS' => 'UM', 
		'URUGUAY' => 'UY', 
		'UZBEKISTAN' => 'UZ', 
		'VANUATU' => 'VU', 
		'VENEZUELA' => 'VE', 
		'VIET NAM' => 'VN', 
		'VIRGIN ISLANDS, BRITISH' => 'VG', 
		'VIRGIN ISLANDS, U.S.' => 'VI', 
		'WALLIS AND FUTUNA' => 'WF', 
		'WESTERN SAHARA' => 'EH', 
		'YEMEN' => 'YE', 
		'ZAMBIA' => 'ZM', 
		'ZIMBABWE' => 'ZW'
	);
	
	return $output;
}

function paypal_countries_drop_down($selected_country, $form_name = 'manage_account_form')
{
	(string) $display_output = null;
	
	$countries_array = paypal_countries_list();
	
	$display_output = '<select name="paypal_country" onchange="submit_form(' . $form_name . ')"> ';
	foreach ($countries_array as $key => $value)
	{
		$display_output .= '<option value="' . $value . '" ' . (($value == $selected_country) ? 'selected' : '') . '>' . $key . '</option> ';
	}
	$display_output .= '</select>';
	
	return $display_output;
}

function optimize_search_string($keywords)
{
	global $setts;
	
	if ($setts['fulltext_search_method'] == 1)
	{
		$output = str_replace(' ', '* +', $keywords) . '*';
	}
	else 
	{
		$output = str_replace(' ', ' +', $keywords);		
	}
	
	return $output;
}

function get_server_load() 
{
	$output = GMSG_NA;
	$os = strtolower(PHP_OS);
	
	if(strpos($os, "win") === false) 
	{
		if(@file_exists("/proc/loadavg")) {
			$load = @file_get_contents("/proc/loadavg");
			$load = @explode(' ', $load);
			
			$output = $load[0];
		}
		else if(@function_exists("shell_exec")) 
		{
			$load = @explode(' ', `uptime`);
			$output = $load[count($load)-1];
		}
	}	
	
	return ($output > 0) ? $output : GMSG_NA;
}

function dd_expires($start_time)
{
	global $setts;
	
	$output = array('display' => GMSG_NA, 'result' => 1);
	
	if ($setts['dd_expiration'])
	{
		$expiration = $setts['dd_expiration'] * 24 * 60 * 60;
		$end_date = $start_time + $expiration;
		
		$output['display'] = time_left($end_date);
		$output['result'] = $end_date - CURRENT_TIME;
	}
	
	return $output;
}

/* currently it will only support one media file attached to an auction */
function download_redirect($winner_id, $buyer_id)
{
	global $db, $setts;
	$output = array('url' => null, 'redirect' => false, 'display' => null);
	
	$winner_id = intval($winner_id);
	$buyer_id = intval($buyer_id);
	
	$media_details = $db->get_sql_row("SELECT am.*, w.is_dd, w.dd_active, w.dd_active_date FROM 
		" . DB_PREFIX . "auction_media am, " . DB_PREFIX . "winners w WHERE 
		am.auction_id=w.auction_id AND w.winner_id=" . $winner_id . " AND w.buyer_id=" . $buyer_id . " AND am.media_type=3");
	
	if ($media_details['is_dd'])
	{
		if ($media_details['dd_active'])
		{
			$expiration = (($setts['dd_expiration'] * 24 * 60 * 60) + $media_details['dd_active_date']) - CURRENT_TIME;
			
			if ($expiration > 0 || !$setts['dd_expiration'])
			{
				$db->query("UPDATE " . DB_PREFIX . "winners SET dd_nb_downloads=dd_nb_downloads+1 WHERE winner_id=" . $winner_id);
				
				$output['redirect'] = true;
				$output['display'] = MSG_DD_DOWNLOAD_SUCCESS;			
			}
			else 
			{
				$output['display'] = MSG_DD_DOWNLOAD_LINK_EXPIRED;
			}
		}
		else 
		{
			$output['display'] = MSG_DD_DOWNLOAD_LINK_INACTIVE;			
		}
	}
	else 
	{
		$output['display'] = MSG_DD_NO_DD_OR_NO_AUCT_WINNER;					
	}
	
	if ($output['redirect'])
	{
		if (eregi('http://', $media_details['media_url']) || eregi('https://', $media_details['media_url']))
		{ /* means we have an external link */
			$output['url'] = $media_details['media_url'];
		}
		else 
		{ /* the file is on site */
			//$output['url'] = $setts['dd_folder'] . $media_details['media_url'];
			$output['url'] = $media_details['media_url'];
		}
	}
	
	return $output;
}

function activate_dd($winner_id, $seller_id, $value)
{
	global $db, $setts;
	
	$winner_id = intval($winner_id);
	$seller_id = intval($seller_id);
	
	$db->query("UPDATE " . DB_PREFIX . "winners SET 
		dd_active=IF(is_dd=1, " . $value . ", 0), 
		dd_active_date=IF(" . $value . "=1 AND is_dd=1, " . CURRENT_TIME . ", 0) WHERE 
		winner_id=" . $winner_id . " AND seller_id=" . $seller_id);	
}

/**
 * this function will create the secred digital media folder. 
 * It will however not remove the old folder (at this time).
 */
function secret_folder($folder_name, $in_admin = true)
{
	global $db, $setts;
		
	$folder = (($in_admin) ? '../' : '') . $folder_name;
	$result = @mkdir($folder, 0777);
	
	if ($result)
	{
		/* now create an index.htm page */
		$fp = @fopen($folder . '/index.htm', 'w');
		$content = ' '; 
		@fputs($fp,$content); 
		@fclose($fp); 
	}	
	
	return $result;
}

function numeric_format ($input)
{
	global $setts;

	if (substr_count($input, '.') <= 1 && substr_count($input, ',') == 0)
	{
		$output = $input;		
	}
	else 
	{
		if ($setts['amount_format'] == 1)
		{
			$output = str_replace(',', '', $input);
		}
		else if ($setts['amount_format'] == 2)
		{
			if (substr_count($input, ',') > 0)
			{
				$output = str_replace(',', '.', $input);
			}
			else 
			{
				$output = str_replace('.', '', $input);
			}
			
			$temp = explode('.', $output);
			$cnt_temp = count($temp);
			if ($cnt_temp > 1)
			{
				$output = null;
				for ($i=0; $i<($cnt_temp-1); $i++)
				{
					$output .= $temp[$i];
				}
				$output .= '.' . $temp[$cnt_temp-1];
			}
		}
	}
		    		
	return $output;
}

/**
 * this function will take two input variables, one array and the other string, and format it in an array string delimited by a selected delimiter
 * all values in the new array string will be integers
 *
 * @param array $array_input
 * @param string $string_input
 * @return string
 */
function format_response_integer($array_input, $string_input, $glue_input = ',', $glue_output = ',')
{
	global $db;
	
	$string_array = null;
	if (is_array($array_input))
	{
		$string_array = $db->implode_array($array_input, $glue_input);
	}
	else if (!empty($string_input))
	{
		$string_array = $string_input;
	}

	$tmp_array = null;
	if (!empty($string_array))
	{
		$explode_string = @explode($glue_input, $string_array);

		foreach ($explode_string as $value)
		{
			$tmp_array[] = intval($value);
		}
	}

	$output = $db->implode_array($tmp_array, $glue_output);
	
	return $output;
}

/**
 * this function will return 1 if force payment is enabled on an auction, and 0 otherwise
 *
 * @param int $user_id
 * @param array $item_details - we need "buyout_price" and "direct_payment" from this array 
 */
function force_payment_enabled($user_id, $item_details)
{
	global $db, $setts, $layout;
	
	$user_details = $db->get_sql_row("SELECT enable_force_payment FROM " . DB_PREFIX . "users WHERE 
		user_id='" . intval($user_id) . "'");
	
	$result = false;
	
	if (
			$setts['enable_force_payment'] && $layout['enable_buyout'] && $setts['buyout_process'] == 1 && 
			$user_details['enable_force_payment'] && $item_details['buyout_price'] > 0 && 
			!empty($item_details['direct_payment'])
	)
	{
		$result = true;
	}
	
	return $result;
}

function revert_sale($winner_details)
{
	global $db, $fees;
	
	// first we add quantity back for dutch auctions, remove reputation, remove end of auction fee, then remove the winner row
	if ($winner_details['quantity_requested'] == $winner_details['quantity_offered'])
	{
		$quantity_query = ($winner_details['auction_type'] == 'standard') ? '1' : 'quantity+' . $winner_details['quantity_offered'];
		
		$db->query("UPDATE " . DB_PREFIX . "auctions SET quantity=" . $quantity_query . " WHERE auction_id='" . $winner_details['auction_id'] . "'");		
	}
	
	$db->query("DELETE FROM " . DB_PREFIX . "reputation WHERE winner_id='" . $winner_details['winner_id'] . "'");
	$db->query("DELETE FROM " . DB_PREFIX . "invoices WHERE invoice_id='" . $winner_details['sale_fee_invoice_id'] . "'");
	
	if ($winner_details['sale_fee_payer_id'])
	{
		$payment_mode = $fees->user_payment_mode($winner_details['sale_fee_payer_id']);
		
		if ($payment_mode == 2)
		{
			$db->query("UPDATE " . DB_PREFIX . "users SET balance=balance-" . $winner_details['sale_fee_amount'] . " WHERE user_id='" . $winner_details['sale_fee_payer_id'] . "'");
		}
	}

	$one_day = CURRENT_TIME + 24 * 60 * 60;
	$end_time = ($winner_details['closed']) ? max(array($one_day, $winner_details['end_time_cron'])) : $winner_details['end_time'];
	
	$db->query("UPDATE " . DB_PREFIX . "auctions SET deleted=0, closed=0, end_time='" . $end_time . "' WHERE auction_id='" . $winner_details['auction_id'] . "'");
	
	$db->query("DELETE FROM " . DB_PREFIX . "winners WHERE winner_id='" . $winner_details['winner_id'] . "'");	
}

/**
 * NTS -> number to string (99999999999 => above)
 * STN -> string to number (above => 99999999999)
 */
function convert_amount ($input_array, $direction = 'NTS')
{
	(array) $output = null;

	foreach ($input_array as $key => $value)
	{
		if (!is_array($value)) 
		{
			if ($direction == 'NTS')
			{
				$output[$key] = ($value >= 99999999999 && doubleval($value)) ? 'above' : $value;
			}
			else if ($direction == 'STN')
			{
				$output[$key] = (eregi('above', $value)) ? 99999999999 : $value;
			}
		}
		else 
		{
			$output[$key] = convert_amount($value, $direction);
		}
	}

	return $output;
}

/**
 * this function will calculate the postage based on the array of winner_id values and the postage type the seller has selected.
 * if the invoice has been created and is weight or amount based, the postage amount will only be taken from one of the rows resulted 
 * because the total amount will be saved in each row
 *
 * if the invoice is already created, take the invoice values of the postage and insurance
 * 
 * @param array $winner_ids
 * @param int $seller_id
 * @param int $auction_id 	-- optional, used only for the shipping calculator feature
 * @param int $buyer_id 	-- optional, used only for the shipping calculator feature
 * 
 * @return array $totals
 */
function calculate_postage($winner_ids, $seller_id, $auction_id = null, $buyer_id = null, $buyer_country = null, $buyer_state = null, $sc_quantity = 0, $shopping_cart = false)

{
	global $db;
	$totals = array('postage' => null, 'insurance' => null, 'invoice' => null, 'weight' => null, 
		'total_postage' => null, 'valid_location' => false, 'nb_items' => null);

	$winner_ids = (is_array($winner_ids)) ? $db->implode_array($winner_ids) : intval($winner_ids);

	$user_details = $db->get_sql_row("SELECT * FROM " . DB_PREFIX . "users WHERE user_id='" . $seller_id . "'");

	if ($auction_id)
	{
		$sql_select_winners = $db->query("SELECT a.postage_amount AS item_postage,
			a.insurance_amount AS item_insurance, a.item_weight FROM " . DB_PREFIX . "auctions a WHERE 
			a.auction_id='" . intval($auction_id) . "'");		

	}
	else if ($shopping_cart) /* calculate postage, insurance and invoice total for a shopping cart */
	{
		$sql_select_winners = $db->query("SELECT sc.*, sci.sc_item_id, sci.quantity AS quantity_offered, a.postage_amount AS item_postage,
			a.insurance_amount AS item_insurance, a.item_weight, a.currency 
			FROM " . DB_PREFIX . "shopping_carts_items sci 
			LEFT JOIN " . DB_PREFIX . "auctions a ON a.auction_id=sci.item_id 
			LEFT JOIN " . DB_PREFIX . "shopping_carts sc ON sc.sc_id=sci.sc_id 
			WHERE sci.sc_item_id IN (" . $winner_ids . ")");
		
	}

	else 
	{
		$sql_select_winners = $db->query("SELECT w.*, a.postage_amount AS item_postage,
			a.insurance_amount AS item_insurance, a.item_weight 
			FROM " . DB_PREFIX . "winners w 
			LEFT JOIN " . DB_PREFIX . "auctions a ON a.auction_id=w.auction_id 
			WHERE w.winner_id IN (" . $winner_ids . ")");
	}
	
	$invoice_sent = false;
	$can_calculate = true;

	while ($winner_details = $db->fetch_array($sql_select_winners))
	{
		$winner_details['quantity_offered'] = ($sc_quantity) ? $sc_quantity : $winner_details['quantity_offered'];
		$buyer_id = (!$buyer_id) ? $winner_details['buyer_id'] : $buyer_id;
		$totals['currency'] = $winner_details['currency'];
		
		if (!empty($winner_details['shipping_method']) && !$sc_carrier)
		{
			$sc_carrier = $winner_details['shipping_method']; //override the carrier with the one saved in the winner/sc row
		}
		

		if ($winner_details['invoice_sent'])
		{
			$invoice_sent = true;
			if ($user_details['pc_postage_type'] == 'item')
			{
				$totals['postage'] += ($winner_details['postage_included']) ? ($winner_details['postage_amount'] * $winner_details['quantity_offered']) : 0;
			}
			else
			{
				$totals['postage'] = $winner_details['postage_amount'];
			}

			$totals['insurance'] += ($winner_details['insurance_included']) ? ($winner_details['insurance_amount'] * $winner_details['quantity_offered']) : 0;

		}
		else
		{
			$totals['postage'] += $winner_details['item_postage'] * $winner_details['quantity_offered'];
			$totals['insurance'] += $winner_details['item_insurance'] * $winner_details['quantity_offered'];
		}

		$totals['invoice'] += $winner_details['bid_amount'] * $winner_details['quantity_offered'];
		$totals['weight'] += $winner_details['item_weight'] * $winner_details['quantity_offered'];
		
		$totals['nb_items'] += $winner_details['quantity_offered'];
	}

	if ($buyer_id) /* only if there is a sale we get these variables from the db, for the shipping calculator, these values are passed */
	{
		$buyer_details = $db->get_sql_row("SELECT * FROM " . DB_PREFIX . "users WHERE user_id='" . $buyer_id . "'");
		$buyer_country = $buyer_details['country'];
		$buyer_state = $buyer_details['state'];
	}

	if ($user_details['pc_shipping_locations'] == 'global')
	{
		$totals['valid_location'] = true;
	}
	else 
	{
		$addl_costs = user_location($seller_id, $buyer_country, $buyer_state);
				
		$totals['valid_location'] = $addl_costs['valid'];
	}
	
	if ($invoice_sent)
	{
		$totals['total_postage'] = $totals['postage'] + $totals['insurance'];
	}
	else
	{
		if ($user_details['pc_free_postage'] && $user_details['pc_free_postage_amount'] <= $totals['invoice'])
		{
			$totals['postage'] = 0;
			$totals['total_postage'] = $totals['insurance'];
		}
		else
		{
			switch ($user_details['pc_postage_type'])
			{
				case 'item':
					/* do nothing */
					$postage_amount = $totals['postage'];
					break;
				case 'weight':
					$postage_amount = $db->get_sql_field("SELECT postage_amount FROM " . DB_PREFIX . "postage_calc_tiers WHERE
						tier_type='weight' AND tier_from<='" . $totals['weight'] . "' AND tier_to>'" . $totals['weight'] . "' 
						AND user_id=" . (($user_details['pc_postage_calc_type'] == 'default') ? '0' : $user_details['user_id']), 'postage_amount');					
					break;
				case 'amount':
					$postage_amount = $db->get_sql_field("SELECT postage_amount FROM " . DB_PREFIX . "postage_calc_tiers WHERE
						tier_type='amount' AND tier_from<='" . $totals['invoice'] . "' AND tier_to>'" . $totals['invoice'] . "' 
						AND user_id=" . (($user_details['pc_postage_calc_type'] == 'default') ? '0' : $user_details['user_id']), 'postage_amount');
					break;
				case 'flat':
					$nb_items = $totals['nb_items'] - 1;
					
					$postage_amount = $user_details['pc_flat_first'] + ($nb_items * $user_details['pc_flat_additional']);
					break;
			}
			
			if ($can_calculate)
			{
			   $totals['postage'] = ($totals['nb_items']) ? $postage_amount : 0;
			   
			   if ($totals['valid_location'])
			   {
				   $totals['postage'] = $totals['postage'] + (($addl_costs['amount_type'] == 'flat') ? $addl_costs['amount'] : ($totals['postage'] * $addl_costs['amount'] / 100));
			   }
			   $totals['total_postage'] = $totals['postage'] + $totals['insurance'];
		   }		
			else 
			{
				$totals['error'] = MSG_PLEASE_SELECT_SHIPPING_METHOD;
				$totals['postage'] = null;
			}
		}		
	}

	return $totals;
}

function shipping_locations($user_id)
{
	global $db;

	$pc_shipping_locations = $db->get_sql_field("SELECT pc_shipping_locations FROM " . DB_PREFIX . "users WHERE 
		user_id='" . $user_id . "'", 'pc_shipping_locations');
		
	$all_locations = null;
	if ($pc_shipping_locations == 'local')
	{
		$sql_select_locations = $db->query("SELECT * FROM " . DB_PREFIX . "shipping_locations WHERE 
			user_id='" . $user_id . "'");
			
		while($loc_details = $db->fetch_array($sql_select_locations))
		{
			$all_locations[] = $loc_details['locations_id'];
		}
		
		if (is_array($all_locations))
		{
			$all_locations = $db->implode_array($all_locations);
		}	
	}
	
	$all_locations = (empty($all_locations)) ? 0 : $all_locations;
	
	return $all_locations;
}

function user_location($seller_id, $buyer_country, $buyer_state)
{
	global $db;	
	$result = array('valid' => false, 'amount' => null, 'amount_type' => 'flat');
	
	$location_details = $db->get_sql_row("SELECT id, amount, amount_type FROM " . DB_PREFIX . "shipping_locations WHERE
		(
			LOCATE('," . intval($buyer_country) . ",', CONCAT(',',locations_id,','))>0 OR
			LOCATE('," . intval($buyer_state) . ",', CONCAT(',',locations_id,','))>0
		) AND user_id='" . $seller_id . "'");

	if (intval($location_details['id']) > 0)
	{
		$result = $location_details;
		$result['valid'] = true;
	}
	
	return $result;
}

function getProjectCategoryListToHTML()
{
    global $db;
    $result = "<select name=\"project_category\" id=\"project_category\">";
    $countries = array();
    $sql_select_locations = $db->query("SELECT * FROM  `np_orgtype` ");
    while($country = $db->fetch_array($sql_select_locations))
    {
        $countries[$country['id']] = $country['name'];
        $result .="<option value='{$country['id']}'> {$country['name']} </option>";
    }
    $result .= "</select>";
    return $result;
}


function generateRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, strlen($characters) - 1)];
	}
	return $randomString;
}

function newRewardForm($reward = array()){
	$reward_id = isset($reward['id']) ? $reward['id'] : generateRandomString();
	if($reward['estimated_delivery_date'] && !empty($reward['estimated_delivery_date'])){
		$reward['estimated_delivery_date'] = date("m/d/Y", strtotime($reward['estimated_delivery_date']));
	}
	ob_start();
	?><div class="reward_block" id="reward_block_<?= $reward_id; ?>">
		<div class="reward_title">
			<div class="reward_title_label"><?=MSG_REWARD;?></div>
			<div class="rewards-actions">
				<button onclick="<?=isset($reward['id']) ? 'update' : 'save';?>ProjectReward('<?= $reward_id; ?>'); return false;" class="validate-reward"></button>
				<button onclick="deleteProjectReward('<?= $reward_id; ?>'); return false;" class="delete-reward"></button>
			</div>
		</div>
		<div class="reward_content">
			<input type="hidden" id="is_new_<?= $reward_id; ?>" value="<?= isset($reward['id']) ? '0' : '1'; ?>">
			<div class="account-row">
				<label> <?=MSG_REWARD_AMOUNT;?> *</label>
				<input type="text" id="reward_amount_<?= $reward_id; ?>" value="<?= @$reward['amount']?>" size="40" />
			</div>
			<div class="account-row">
				<label> <?=MSG_REWARD_NAME;?> *</label>
				<input type="text" id="reward_name_<?= $reward_id; ?>" value="<?= @$reward['name']?>" size="40" />
			</div>
			<div class="account-row">
				<label> <?=MSG_REWARD_DESCRIPTION;?> *</label>
				<textarea id="reward_description_<?= $reward_id; ?>"><?= @$reward['description']?></textarea>
			</div>
			<div class="account-row">
				<label> <?=MSG_REWARD_AVAILABLE_NUMBER;?></label>
				<input type="text" value="<?= @$reward['available_number']?>" id="reward_available_number_<?= $reward_id; ?>"></input>
			</div>
			<div class="account-row">
				<label> <?=MSG_REWARD_ESTIMATED_DELIVERY;?></label>
				<input type="text" id="reward_estimated_delivery_date_<?= $reward_id; ?>" value="<?= isset($reward['estimated_delivery_date']) ? $reward['estimated_delivery_date'] : ''?>"></input>
			</div>
			<div class="account-row" style="margin-top: 20px; margin-left: 135px;">
				<input type="checkbox" <?php if(@$reward['shipping_address_required'] == 1){echo 'checked';} ?> id="reward_shipping_address_required_<?= $reward_id; ?>" class="reward_shipping_address_required" value="1"></input>
				<?=MSG_REWARD_SHIPPING_ADDRESS_REQUIRED;?>
			</div>
		</div>
		<script>
			$( "#reward_estimated_delivery_date_<?= $reward_id; ?>" ).datepicker({ 
				dateFormat: "mm/dd/yy", 
				changeMonth: true,
				changeYear: true, 
				minDate: new Date(),
				defaultDate: "<?= isset($reward['estimated_delivery_date']) ? $reward['estimated_delivery_date'] : ''?>"
			});
		</script>
	</div>
<?php
	$form = ob_get_contents();
	ob_end_clean();
	
	return $form;
}

?>
