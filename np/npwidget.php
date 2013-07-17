<?php
/**
 * Created by Lilian Codreanu.
 * User: Lilian Codreanu
 * Date: 1/9/13
 * Time: 11:46 PM
 */

session_start();

define ('IN_SITE', 1);

//include_once ('includes/npglobal.php');
//include_once ('includes/npclass_formchecker.php');
//include_once ('includes/npclass_custom_field.php');
//include_once ('includes/npclass_user.php');
//include_once ('includes/npfunctions_login.php');

if (!$session->value('user_id'))
{
	header_redirect('nplogin.php');
}
else
{
    $template->change_path("np/templates/");
	$template->set('session', $session);

	(array) $summary_page_content = null;

    $user_details = $db->get_sql_row("SELECT user_id, username,  email,
    				enable_aboutme_page, aboutme_page_content, shop_account_id, shop_active, wkey FROM
    				" . NPDB_PREFIX . "users WHERE user_id=" . $session->value('user_id'));
    if($user_details["wkey"] == ""){
        $db->query("UPDATE " . NPDB_PREFIX . "users SET wkey = MD5(CONCAT(MD5(user_id),salt)) WHERE
        					user_id=".$session->value('user_id') );

        $user_details = $db->get_sql_row("SELECT user_id, username,  email,
            				enable_aboutme_page, aboutme_page_content, shop_account_id, shop_active, wkey FROM
            				" . NPDB_PREFIX . "users WHERE user_id=" . $session->value('user_id'));
    }

    $template->set('wkey', $user_details["wkey"]);

//	require ('npglobal_header.php');

//	$msg_changes_saved = '<p align="center" class="contentfont">' . MSG_CHANGES_SAVED . '</p>';

//	$template->set('members_area_header', header7(MSG_MEMBERS_AREA_WIDGET_TITLE));

	if ($session->value('category_language') == 1)
	{
		$msg_store_cats_modified = '<div class="errormessage contentfont" align="center">' . MSG_STORE_CATS_MODIFIED . '</div>';
		$template->set('msg_store_cats_modified', $msg_store_cats_modified);
	}


//	if ($page != 'summary')
//	{
//		$template->change_path('themes/' . $setts['default_theme'] . '/templates/');
//		$members_area_header_menu = $template->process('npmembers_area_header_menu.tpl.php');
//		$template->change_path('templates/');
//
//		$template->set('members_area_header_menu', $members_area_header_menu);## PHP Pro Bid v6.00 end - header members area
//	}

//	$template_output .= $template->process('npwidget.tpl.php');
    $members_area_page_content = $template->process('npwidget.tpl.php');
	//include_once ('npglobal_footer.php');

//	echo $template_output;
}
?>