<?php
/**
 * Created by Lilian Codreanu.
 * User: Lilian Codreanu
 * Date: 2/9/13
 * Time: 9:16 PM
 */

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

	$sql_select_words = $db->query("SELECT user_id, tax_company_name, wkey FROM np_users ORDER BY tax_company_name ASC");
    $npUser_select_option="";
    $default_user = "";
    while ($_npuser = $db->fetch_array($sql_select_words)){
        if(trim($_npuser["wkey"]) == "")
            continue;
        if($default_user == "")
            $default_user = $_npuser["wkey"];
        $npUser_select_option .="<option value='{$_npuser["wkey"]}'>{$_npuser["tax_company_name"]}</option>";
    }

    $template->set('npUsers_list', $npUser_select_option);
    $template->set('defaultUser', $default_user);
	$template_output .= $template->process('widget.tpl.php');

	include_once ('footer.php');

	echo $template_output;
}