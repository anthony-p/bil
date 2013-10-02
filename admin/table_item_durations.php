<?
#################################################################
## PHP Pro Bid v6.00															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

session_start();

define ('IN_ADMIN', 1);

include_once ('../includes/global.php');

if ($session->value('adminarea')!='Active')
{
	header_redirect('login.php');
}
else
{
	include_once ('header.php');

	$msg_changes_saved = '<p align="center" class="contentfont">' . AMSG_CHANGES_SAVED . '</p>';

	if (isset($_POST['form_save_settings']))
	{

		$template->set('msg_changes_saved', $msg_changes_saved);

		if (count($_POST['duration_id']))
		{
			foreach ($_POST['duration_id'] as $key => $value)
			{
				$sql_update_durations = $db->query("UPDATE " . DB_PREFIX . "auction_durations SET
					days='" . intval($_POST['days'][$key]) . "',
					description='" . $db->rem_special_chars($_POST['description'][$key]) . "' WHERE
					id=" . $value);
			}
		}

		if (!empty($_POST['new_days']))
		{
			$sql_insert_durations = $db->query("INSERT INTO " . DB_PREFIX . "auction_durations (days, description) VALUES
				('" . intval($_POST['new_days']) . "', '" . $db->rem_special_chars($_POST['new_description']) . "')");
		}

		if (count($_POST['delete'])>0)
		{
			$delete_array = $db->implode_array($_POST['delete']);

			$sql_delete_durations = $db->query("DELETE FROM " . DB_PREFIX . "auction_durations WHERE
				id IN (" . $delete_array . ")");
		}
	}

	(string) $item_durations_page_content = NULL;

	$sql_select_durations = $db->query("SELECT id, days, description FROM
		" . DB_PREFIX . "auction_durations ORDER BY days ASC");

	while ($duration_details = $db->fetch_array($sql_select_durations))
	{
		$background = ($counter++%2) ? 'c1' : 'c2';

		$item_durations_page_content .= '<input type="hidden" name="duration_id[]" value="' . $duration_details['id'] . '"> '.
			'<tr class="' . $background . '"> '.
			'	<td></td> '.
      	'	<td><input name="days[]" type="text" value="' . $duration_details['days'] . '" size="8"></td> '.
      	'	<td><input name="description[]" type="text" value="' . $duration_details['description'] . '" size="50"></td> '.
			'	<td align="center"><input type="checkbox" name="delete[]" value="' . $duration_details['id'] . '"></td> '.
			'</tr> ';
	}

	$template->set('header_section', AMSG_TABLES_MANAGEMENT);
	$template->set('subpage_title', AMSG_EDIT_ITEM_DURATIONS);

	$template->set('item_durations_page_content', $item_durations_page_content);

	$template_output .= $template->process('table_item_durations.tpl.php');

	include_once ('footer.php');

	echo $template_output;
}
?>
