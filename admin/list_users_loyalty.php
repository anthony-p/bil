<?
#################################################################
## PHP Pro Bid v6.00															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

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

	$template->set('management_box', $management_box);

	$sql_select_user_loyalty = $db->query("SELECT id, username, name, email, SUM(points_awarded) as sum_points_awarded FROM " . DB_PREFIX ."user_points
	                                        LEFT JOIN " . DB_PREFIX ."users ON " . DB_PREFIX ."user_points.user_id = " . DB_PREFIX ."users.user_id
	                                        GROUP BY " . DB_PREFIX ."user_points.user_id ORDER BY sum_points_awarded DESC ");

	while ($user_details = $db->fetch_array($sql_select_user_loyalty))
	{
		$background = ($counter++%2) ? 'c1' : 'c2';

		$admin_users_content .= '<tr class="' . $background . '"> '.
      	'	<td>' . $user_details['username'] . '</td> '.
        '	<td>' . $user_details['name'] . '</td> '.
        '	<td>' . $user_details['email'] . '</td> '.
      	'	<td align="center">' . $user_details['sum_points_awarded'] . '</td> ';
	}

	$template->set('admin_users_content', $admin_users_content);


	$template->set('header_section', 'Users Loyalty');
	$template->set('subpage_title', 'Users Loyalty');

	$template_output .= $template->process('list_users_loyalty.tpl.php');

	include_once ('footer.php');

	echo $template_output;
}
?>