<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

define ('IN_SITE', 1);

include_once('includes/npglobal.php');

$username = $db->rem_special_chars($_GET['username']); // get the username

$count_username = $db->count_nprows('users', "WHERE username='" . $username . "'");

if (!empty($username))
{
	echo ($count_username > 0) ? MSG_USERNAME_UNAVAILABLE : MSG_USERNAME_AVAILABLE;
}
else 
{
	echo MSG_ENTER_USERNAME;
}
	
?>