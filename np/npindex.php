<?
#################################################################
## PHP Pro Bid v6.05															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

session_start();

define ('IN_SITE', 1);
define ('INDEX_PAGE', 1); ## for integration

include_once ('includes/npglobal.php');

include_once ('includes/npfunctions_login.php');


if (eregi('logout', $_GET['option']))
{
session_destroy();
	header_redirect('/index.php');
}

include_once ('npglobal_header.php');

echo "hello you;re logged out?";

?>
