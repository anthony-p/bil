<?
#################################################################
## PHP Pro Bid v6.04															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
## PHP Pro Bid & PHP Pro Ads Integration v1.00						##
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

	$valid_url = false;

	$ppa_url = (!empty($_POST['ppa_url'])) ? $_POST['ppa_url'] : $integration['ppa_url'];
	
	$check_config = true;
	@include('../' . $ppa_url . 'integration.php');

	if (PPA_EXISTS == 1)
	{
		$valid_url = true;
	}

	$msg_changes_saved = '<p align="center" class="contentfont">' . AMSG_CHANGES_SAVED . '</p>';
	
	if (isset($_POST['form_save_settings']))
	{
		$template->set('msg_changes_saved', $msg_changes_saved);
		
		$sql_update_integration = $db->query("UPDATE pps_integration SET 
			ppa_url='" . $ppa_url . "', ppb_path='" . SITE_PATH . "', 
			ppa_db_prefix='" . $ppa_db_prefix . "', ppa_session_prefix='" . $ppa_session_prefix . "', 
			ppb_db_prefix='" . DB_PREFIX . "', ppb_session_prefix='" . SESSION_PREFIX . "', 
			enable_integration='" . (($valid_url) ? intval($_POST['enable_integration']) : 0) . "', 
			main_page_unified='" . intval($_POST['main_page_unified']) . "', 
			default_skin='" . $_POST['default_theme'] . "'");
	}

	$template->set('header_section', AMSG_GENERAL_SETTINGS);
	$template->set('subpage_title', AMSG_PPB_PPA_INTEGRATION);

	$integration = $db->get_sql_row("SELECT * FROM pps_integration LIMIT 0,1");

	$template->set('integration', $integration);
	$template->set('valid_url', $valid_url);

	$template->set('integration_skins_dropdown', list_integration_skins('admin', true, $integration['default_skin']));
	
	$template_output .= $template->process('pps_integration.tpl.php');

	include_once ('footer.php');

	echo $template_output;
}
?>
