<?php
//session_set_cookie_params(0, '/', 'bringitlocal.com');
session_start();

define ('IN_SITE', 1);

include_once ('includes/global.php');

if (!$session->value('user_id'))
{
	print_r('Not login!');
}else
{
    //print_r($_SESSION);
}
//print_r($_SESSION);
$name = session_name();
//echo "SNAME:".$name."<br>";
$a = session_id();
//if(empty($a)) session_start();
//echo "SID: ".SID.": ".session_id()."<br>COOKIE: ".$_COOKIE["PHPSESSID"];
print_r($_COOKIE);
//echo phpinfo();
if(isset($_COOKIE['referrer_url']))
    echo $_COOKIE['referrer_url'];
