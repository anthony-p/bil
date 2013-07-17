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

		
		
			$sql_insert_update_invoices = $db->query("INSERT INTO giveback_invoices 
			(np_userid,np_name,points,invoice_date,user_id,username,invoice_id,transtype,tracking_id, Sales, Commission) 
			VALUES('$_POST[np_userid]','$_POST[np_name]', '$_POST[points]','$_POST[invoice_date]',
			'$_POST[user_id]','$_POST[username]','$_POST[invoice_id]','$_POST[transtype]','$_POST[tracking_id]','$_POST[Sales]','$_POST[Commission]')  ");
	}

	(string) $update_invoices_page_content = NULL;


    $sql_select_partner_options = $db->query("SELECT * FROM " . DB_PREFIX . "partners");
	while ($partner= $db->fetch_array($sql_select_partner_options))
	{
		$partner_options .= "<option value='". $partner['name'] ."'>";
		$partner_options .= $partner['name'];
        $partner_options .= "</option>";
	}
    
    $template->set('partner_options', $partner_options);
    

	$template->set('header_section', AMSG_TABLES_MANAGEMENT);
	$template->set('subpage_title', AMSG_EDIT_SHIPPING_OPTIONS);

	$template->set('update_invoices_page_content', $update_invoices_page_content);

	$template_output .= $template->process('giveback_invoices.tpl.php');
    
	include_once ('footer.php');

	echo $template_output;
}
?>
