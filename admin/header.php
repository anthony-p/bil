<?
#################################################################
## PHP Pro Bid v6.00															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }

$can_view = true;

if ($session->value('adminlevel') > 1)
{
	$can_view = ((eregi('list_site_users.php', $_SERVER['PHP_SELF'])) || eregi('list_auctions.php', $_SERVER['PHP_SELF']) ||
		eregi("email_user.php",$_SERVER['PHP_SELF']) || 
		eregi("list_user_bids.php",$_SERVER['PHP_SELF']) ||
		eregi("accounting.php",$_SERVER['PHP_SELF']) ||
		eregi("index.php",$_SERVER['PHP_SELF'])) ? true : false;

}

if ($session->value('category_language')==1)
{
	$updated_categories_message = '<table width="100%" bgcolor="red"><tr> '.
		'<td align="center"><font size="+2" color="#ffffff">' . AMSG_CAT_CHANGE_EXPL_MSG . '</font></td></tr></table> ';
	$template->set('updated_categories_message', $updated_categories_message);
}

if (!$can_view && !$msg_shown)
{
	header_redirect('index.php?viewmsg=1');
}

if (eregi('index.php', $_SERVER['PHP_SELF']))
{
	include_once ('status.php');
	$template->set('admin_left_menu', $status_template_output);
}
else
{
	$leftmenu_template_output = $template->process('leftmenu.tpl.php');
	$template->set('admin_left_menu', $leftmenu_template_output);
}

if ($session->value('user_id'))
{
    $_user_data = $db->query("SELECT * FROM bl2_users WHERE id=" . $session->value('user_id'));
    $current_user_data = array();
    while ($result =  mysql_fetch_array($_user_data)) {
        $current_user_data[] = $result;
    }
    $current_user_identifier = (isset($current_user_data[0]["organization"]) && $current_user_data[0]["organization"]) ?
        $current_user_data[0]["organization"] : $current_user_data[0]["first_name"];
    $template->set('current_user_identifier', $current_user_identifier);
} else {
    $template->set('current_user_identifier', '');
}

$template_output .= $template->process('header.tpl.php');
?>
