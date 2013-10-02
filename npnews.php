<?
#################################################################
## PHP Pro Bid v6.07										   ##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.  ##
##-------------------------------------------------------------##
#################################################################

session_start();

define ('IN_SITE', 1);
define('NPDB_PREFIX', 'np_');

include_once ('includes/global.php');
include_once ('includes/class_formchecker.php');

include_once ('includes/functions_login.php');
include_once ('includes/functions_item.php');

include_once ('global_header.php');

(string) $message_content = null;

## all default content pages
$pages_array_src = array('help', 'news', 'faq', 'about_us',	'contact_us', 'terms', 'privacy', 'announcements');

$pages_array = array('help' => MSG_HELP_TOPICS, 'news' => MSG_SITE_NEWS, 'faq' => MSG_FAQ_TITLE, 'about_us' => MSG_ABOUT_US,
	'contact_us' => MSG_CONTACT_US, 'terms' => MSG_TERMS, 'privacy' => MSG_PRIVACY, 'announcements' => MSG_ANNOUNCEMENTS_TITLE);

## content pages that support multiple rows
$content_pages_array = array('help', 'news', 'faq', 'announcements');

if (is_numeric($_REQUEST['news_id']))
	$addl_query = ($_REQUEST['news_id']) ? " AND news_id='" . $_REQUEST['news_id'] . "'" : '';
else
	$addl_query = "";

if (isset($_COOKIE["np_userid"]))
	$mynp_userid2=$_COOKIE["np_userid"];
else
	$mynp_userid2 = np_userid;

if (is_numeric($mynp_userid2))
	$addl_query = $addl_query . " AND user_id = " . $mynp_userid2;


/*$sql_select_pages = $db->query("SELECT news_id, news_name, news_content FROM " . NPDB_PREFIX . "news WHERE
	MATCH (news_lang) AGAINST	('" . $session->value('news_lang') . "*' IN BOOLEAN MODE) AND
	news_handle='" . $_REQUEST['news'] . "' " . $addl_query . " ORDER BY news_order ASC, reg_date DESC");*/

$sql_select_pages = $db->query("SELECT news_id, news_name, news_content FROM np_news where (1=1) " . $addl_query . " ORDER BY news_order ASC, reg_date DESC");

if (in_array($_REQUEST['news'], $pages_array_src))
{
	$message_header = $pages_array[$_REQUEST['news']];
}


$message_content = '<br>'.
'<script language="javascript">
	var ie4 = false;
	if(document.all) { ie4 = true; }

	function getObject(id) { if (ie4) { return document.all[id]; } else { return document.getElementById(id); } }
	function toggle(link, divId) {
		var d = getObject(divId);
		if (d.style.display == \'\') { d.style.display = \'none\'; }
		else { d.style.display = \'\'; }
	}
</script>';

$counter = 0;
while ($content_page = $db->fetch_array($sql_select_pages))
{
	if (in_array($_REQUEST['news'], array('custom_page')))
	{
		$message_header = $content_page['news_name'];
	}

	if (in_array($_REQUEST['news'], $content_pages_array))
	{
		$message_content .= '<div class="topic_id"> '.
    		//'<a href="' . process_link('content_pages', array('page' => $_REQUEST['page'], 'topic_id' => $content_page['topic_id'])) . '">' . $content_page['topic_name'] . '</a> '.
    		'<a href="javascript:void(0)" onclick="toggle(this, \'topic_content_' . $counter . '\');">' . $content_page['news_name'] . '</a> '.
  			'</div>';
	}

	$style_display = (in_array($_REQUEST['news'], $content_pages_array) && $_REQUEST['news'] != 'news') ? 'none' : '';
	$message_content .= '<div class="topic_content" id="topic_content_' . $counter . '" style="display: ' . $style_display . ';"> ' . $db->add_special_chars($content_page['news_content']) . '</div>';
	
	$counter++;
}

$topic_id = intval($_REQUEST['news_id']);


$template->set('message_header', header5($message_header));
$template->set('message_content', $message_content);

$template_output .= $template->process('single_message.tpl.php');

include_once ('global_footer.php');

echo $template_output;

?>
