<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

session_start();

define ('IN_SITE', 1);

include_once ('includes/npglobal.php');
include_once ('includes/npclass_np_news.php');

if (!$session->value('user_id'))
{
	header_redirect('nplogin.php');
}
else
{
;
	$template->set('session', $session);
	require ('npglobal_header.php');
	
	$template->set('members_area_header', header7(MSG_MEMBERS_AREA_TITLE));


	$site_content = new news();

	(string) $news_handle = null;
	(string) $management_box = NULL;
	(string) $user_id = $session->value('user_id');

	$languages = list_languages('admin');
	$template->set('languages', $languages);
	//$template->set('db', $db);

	$news_handle = (isset($_REQUEST['news'])) ? $_REQUEST['news'] : '';

	$template->set('news_handle', $news_handle);
	$template->set('custom_section_pages_ordering', $custom_section_pages_ordering);

	if (!in_array($news_handle, $custom_section_pages))
	{
		$template_output .= '<p align="center" class="contentfont">' . AMSG_CUSTOM_PAGE_SEL_ERROR . '</p>';
	}
	else
	{
		$msg_changes_saved = '<p align="center" class="contentfont">' . AMSG_CHANGES_SAVED . '</p>';

		if (isset($_POST['form_save_settings']) && in_array($news_handle, $custom_section_pages_ordering))
		{
			foreach ($_POST['news_id'] as $key => $value)
			{
				$update_ordering = $db->query("UPDATE " . NPDB_PREFIX . "news SET
					news_order='" . $_POST['news_order'][$key] . "' WHERE news_id='" . $value . "' AND news_handle='" . $news_handle . "'");
			}
		}

		if ($_REQUEST['do'] == 'add_news')
		{
			if ($_REQUEST['operation'] == 'submit')
			{
				$template->set('msg_changes_saved', $msg_changes_saved);

				$post_details = $db->rem_special_chars_array($_POST);
			
				//$news_id = md5(uniqid(rand(2,99999999))); // generated the unique id for the new page

				foreach ($languages as $value)
				{					
					$site_content->insert_news($post_details, $value, $user_id, $news_handle);
				}
			}
			else
			{
				$template->set('disabled_button', 'disabled');
				$template->set('do', $_REQUEST['do']);
				$template->set('manage_box_title', AMSG_ADD_TOPIC);

				$management_box = $template->process('npnp_news_add.tpl.php');
			}
		}
		else if ($_REQUEST['do'] == 'edit_news')
		{

			if ($_REQUEST['operation'] == 'submit')
			{
				$template->set('msg_changes_saved', $msg_changes_saved);

				$post_details = $db->rem_special_chars_array($_POST);

				foreach ($languages as $value)
				{
					$site_content->edit_news($post_details, $value, $_POST['news_id'], $news_handle);
				}
			}
			else
			{
				$template->set('disabled_button', 'disabled');
				$template->set('do', $_REQUEST['do']);
				$template->set('manage_box_title', AMSG_EDIT_TOPIC);
				$template->set('news_id', $_REQUEST['news_id']);

				$management_box = $template->process('npnp_news_add.tpl.php');
			}
		}
		else if ($_REQUEST['do'] == 'delete_news')
		{
			$template->set('msg_changes_saved', $msg_changes_saved);

			$site_content->delete_news($_REQUEST['news_id'], $news_handle);
		}

		$template->set('management_box', $management_box);

		$sql_select_topics = $db->query("SELECT * FROM " . NPDB_PREFIX . "news WHERE
	   	MATCH (news_lang) AGAINST ('" . $setts['site_lang'] . "*' IN BOOLEAN MODE) AND user_id=" . $user_id ." ORDER BY news_order ASC, reg_date DESC");

	   while ($topic_details = $db->fetch_array($sql_select_topics))
		{
			$background = ($counter++%2) ? 'c1' : 'c2';

			$content_pages_content .= '<input type="hidden" name="news_id[]" value="' . $topic_details['news_id'] . '">'.
				'<tr class="' . $background . '"> '.
	      	'	<td>' . $topic_details['news_name'] . '</td> ';

	      if (in_array($news_handle, $custom_section_pages_ordering))
	      {
	      	$content_pages_content .=	'	<td align="center"><input name="topic_order[]" type="text" value="' . $topic_details['news_order'] . '" size="8"></td> ';

	      }

	      $content_pages_content .= '	<td align="center">' . show_date($topic_details['reg_date']) . '</td> '.
	      	'	<td align="center"> '.
				'		[ <a href="npmembers_news.php?do=edit_news&news_id=' . $topic_details['news_id'] . '&news=' . $news_handle . '">' . AMSG_EDIT . '</a> ] &nbsp;'.
				'		[ <a href=npmembers_news.php?do=delete_news&news_id=' . $topic_details['news_id'] . '&news=' . $news_handle . ' onclick="return confirm(\'' . AMSG_DELETE_CONFIRM . '\');">' . AMSG_DELETE . '</a> ]</td> '.
				'</tr> ';

		}

		$template->set('content_pages_content', $content_pages_content);

		$subpage_title = $site_content->subpage_title($news_handle);

		$template->set('header_section', AMSG_SITE_CONTENT);
		$template->set('subpage_title', $subpage_title);

		$template_output .= $template->process('npnp_news.tpl.php');
	}

	include_once ('npglobal_footer.php');

	echo $template_output;
}

?>
