<?php
# ob_start(); // Initiate the output buffer
function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}
$GLOBALS['body_id'] = "landingpage";
#puts the current url array into a variable
$current_url = str_replace("www.", "", curPageURL());

#use the parse url function to get the path
$arr = parse_url($current_url,PHP_URL_PATH);
#print_r($arr,true);
#print_r($arr);

/*
put the path in a variable and take out the forward slash
*/

$xyz.=basename($arr);
#echo "<br>the username is: $xyz";

#echo "<br><br>search for username $xyz in the db--actually it will be a new field but for now the username will do<br>";
$npusername=$xyz;


define ('IN_SITE', 1);

include_once ('includes/global.php');
include_once ('includes/class_database.php');
#include_once ('includes/npclass_shareddatabase.php');
#include_once ('includes/npshared_functions.php');
#define('NPDB_PREFIX', 'np_');
#define('givebackDB_PREFIX', 'giveback_');

$link = mysql_connect($db_host, $db_username, $db_password);
mysql_select_db($db_name, $link);

$result_user = mysql_query("SELECT * FROM np_users WHERE username = '$npusername'", $link);
$is_user = mysql_num_rows($result_user);

#echo "is user";
#echo $is_user;


if ($is_user == '1')
{


//who is the np
//start with username in the url which is unique and use that to get the organization name which might not be unique
#$np_name = $db->get_sql_field("SELECT user_id  FROM np_users WHERE username ='" . $xyz . "'", tax_company_name);
//we need the np id also which is unique so we can look up in the giveback invoices to see if there are any sales yet
$np_userid = $db->get_sql_field("SELECT user_id  FROM np_users WHERE username ='" . $xyz . "'", user_id);

#set cookie so we know if this np has already sales or not. 1 means they do aleady have sales
#SetCookieLive("np_userid", $np_userid,0, '/', 'bringitlocal.com');   
$inThreeMonths = 60 * 60 * 24 * 90 + time(); 
SetCookieLive("np_userid", $np_userid,$inThreeMonths, '/', 'bringitlocal.com');   
define('np_userid', $np_userid);
define('landingpage', 1);


//do they have sales. if not we dont want to show the chart
$result_sales = mysql_query("SELECT * FROM giveback_invoices WHERE np_userid = '$np_userid'", $link);
$is_sales = mysql_num_rows($result_sales);


if ($is_sales <> '0' )
define('sales', 1);
$salesno = sales;

#set a cookie and define a variable so we know the np when the rest of the homepage loads
#SetCookieLive("sales", $salesno, 0, '/', 'bringitlocal.com'); 
$inThreeMonths = 60 * 60 * 24 * 90 + time(); 
SetCookieLive("sales", '1', $inThreeMonths, '/', 'bringitlocal.com');


define ('INDEX_PAGE', 1); ## for integration

if (!file_exists('includes/config.php')) echo "<script>document.location.href='install/install.php'</script>";



if (eregi('logout', $_GET['option']))
{
	logout();
}

include_once ('global_header.php');

if (isset($_GET['change_language']))
{
	$all_languages = list_languages('site');

	if (in_array($_GET['change_language'], $all_languages))
	{
		$session->set('site_lang', $_GET['change_language']);
	}

	$refresh_link = 'index.php';

	$template_output .= '<br><p class="contentfont" align="center">' . MSG_SITE_LANG_CHANGED . '<br><br>
		Please click <a href="' . process_link('index') . '">' . MSG_HERE . '</a> ' . MSG_PAGE_DOESNT_REFRESH . '</p>';
	$template_output .= '<script>window.setTimeout(\'changeurl();\',300); function changeurl(){window.location=\'' . $refresh_link . '\'}</script>';
}
else if (isset($_GET['change_skin']))
{
	$all_skins = list_skins('site');

	if (in_array($_GET['default_theme'], $all_skins))
	{
		$session->set('site_theme', $_GET['default_theme']);
	}

	$refresh_link = 'index.php';

	$template_output .= '<br><p class="contentfont" align="center">' . MSG_SITE_SKIN_CHANGED . '<br><br>
		Please click <a href="' . process_link('index') . '">' . MSG_HERE . '</a> ' . MSG_PAGE_DOESNT_REFRESH . '</p>';
	$template_output .= '<script>window.setTimeout(\'changeurl();\',300); function changeurl(){window.location=\'' . $refresh_link . '\'}</script>';	
}
else
{

    var_dump("HA HA HA !");
	include_once ('global_mainpage_landingpage.php');
    var_dump("HMMMMMMMM!");
}

include_once ('global_footer.php');

echo $template_output;


}
else 
{

echo "<br><h3>Sorry - that page is not found<br><br>"; 
echo "<a href=\"index.php\">Click here to go back to the Bring It Local homepage</a></h2>";

#header ("location: ..\index.php");
	
}
#ob_end_flush(); // Flush the output from the buffer		
?>