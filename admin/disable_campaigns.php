<?

session_start();

define ('IN_ADMIN', 1);

include_once ('../includes/global.php');
include_once ('../includes/class_formchecker.php');

if ($session->value('adminarea')!='Active')
{
	header_redirect('login.php');
}
else
{
	include_once ('header.php');

	(string) $management_box = NULL;

	$msg_changes_saved = '<p align="center" class="contentfont">' . AMSG_CHANGES_SAVED . '</p>';

    $form_submitted = false;

    if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'edit_user')
    {
        $row_user = $db->get_sql_row("SELECT npu.user_id, npu.username as npu_username, npu.active, npu.probid_user_id, u.username, npu.user_link_active,
                                    npu.report_email, npu.report_daily, npu.report_weekly, npu.report_monthly FROM np_users npu
	                                LEFT JOIN " . DB_PREFIX . "users u ON u.user_id = npu.probid_user_id WHERE npu.user_id=" . $_REQUEST['np_user_id']);

        if ($_POST['operation'] == 'submit')
        {
            $form_submitted = true;

            $template->set('msg_changes_saved', $msg_changes_saved);
            $query_update_user = "UPDATE np_users SET probid_user_id='" . $_POST['users'] . "',
                                    report_email='{$_POST["report_email"]}', report_daily='{$_POST["report_daily"]}', report_weekly='{$_POST["report_weekly"]}', report_monthly='{$_POST["report_monthly"]}',
                                    user_link_active='" . $_POST['user_link_active'] . "' WHERE user_id=" . $_POST['np_user_id'];

            $sql_update_user = $db->query($query_update_user);
        }

        if (!$form_submitted)
        {
            $sql_select_user = $db->query("SELECT user_id, username FROM " . DB_PREFIX . "users ORDER BY username ASC");

            $np_user_edit_content = '<select name="users">';
            while ($user = $db->fetch_array($sql_select_user)){
                $np_user_edit_content .= '<option value="'.$user["user_id"].'" ';
                $np_user_edit_content .= ($row_user['probid_user_id'] == $user["user_id"]) ? 'selected' : '';
                $np_user_edit_content .= ' >'.$user["username"].'</option>';
            }
            $np_user_edit_content .= '</select>';
            $template->set('users', $np_user_edit_content);
            $template->set('user_details', $row_user);
            $template->set('do', $_REQUEST['do']);

            $management_box = $template->process('list_np_users_edit_np_user.tpl.php');
        }
    }

	$template->set('management_box', $management_box);

	$sql_select_users = $db->query("SELECT npu.user_id, npu.username as npu_username, npu.name, npu.active,
	    	                                npu.email, npu.tax_company_name, npu.report_daily, npu.report_weekly, npu.report_monthly FROM np_users npu
	    	                                LEFT JOIN " . DB_PREFIX . "users u ON u.user_id = npu.probid_user_id");

    $counter = 0;
    $np_users_content = '';
	while ($user_details = $db->fetch_array($sql_select_users))
	{
//        var_dump($user_details);die;

        /*
         *           <th class="header" >np-Username</th>
                   <th width="150" align="center">UserId</th>
                   <th width="150" align="center">E-mail address</th>
                   <th width="90" align="center">Contact Name</th>
                   <th filter="false" width="150" align="center">Report selected</th>
         *
         * */
		$background = ($counter++ % 2) ? 'c1' : 'c2';

		$np_users_content .= '<tr class="' . $background . '"> '.
      	'	<td>' . $user_details['user_id'] . '</td> '.
        '	<td>' . $user_details['npu_username'] . '</td> '.
      	'	<td>' . $user_details['email'] . '</td> '.
      	'	<td>' . $user_details['name'] . '</td> '.
      	'	<td align="center">' . getReportsPeriod($user_details) . '</td> '.
      	'	<td align="center"> '.
			'		[ <a href="list_np_users.php?do=edit_user&np_user_id=' . $user_details['user_id'] . '">' . AMSG_EDIT . '</a> ] &nbsp;'.
			'</tr> ';
	}

	$template->set('np_users_content', $np_users_content);


	$template->set('header_section', AMSG_USERS_MANAGEMENT);
	$template->set('subpage_title', 'np-Users');

	$template_output .= $template->process('disable_campaigns.tpl.php');

	include_once ('footer.php');

	echo $template_output;
}

/**
 * @param $user
 * @return string
 */
function getReportsPeriod($user)
{
    $result = array();

    if ($user["report_daily"] != 0)
        $result[] = "daily";
    if ($user["report_weekly"] != 0)
        $result[] = "weekly";
    if ($user["report_monthly"] != 0)
        $result[] = "monthly";

    $result = implode(" | ",$result);

    if (empty($result))
        $result = "none";
    return $result;
}

?>
