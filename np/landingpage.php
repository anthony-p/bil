<?php
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
$username=$xyz;





define('NPDB_PREFIX', 'np_');



define ('IN_SITE', 1);

include_once ('includes/global.php');
include_once ('includes/class_database.php');
#include_once ('includes/npclass_shareddatabase.php');
#include_once ('includes/npshared_functions.php');
define('NPDB_PREFIX', 'np_');





$is_user = $db->count_nprows('users', "WHERE username='" . $xyz . "'");

#echo "isuser $is_user";
if (!empty($is_user))
{
	echo "<h1>Welcome $xyz </h1>";





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
	include_once ('global_mainpage.php');
}

include_once ('npglobal_footer.php');

echo $template_output;


























}
else 
{
	echo "<h1>page not found</h1>";
}
		
?>
