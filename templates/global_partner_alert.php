<?
#################################################################
## PHP Pro Bid v6.02															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

session_start();

define ('IN_SITE', 1);

include_once ('includes/global.php');

include_once ('includes/class_messaging.php');

$npid = $_GET["npid"];
$URL= $_GET["shop_url"];


$partner_details = $db->get_sql_row("SELECT name FROM " . DB_PREFIX . "partners WHERE advert_url='" . $URL . "'");
$vendor = $partner_details['name'];
if ($vendor=="")
{
    $URL= str_replace('&', '',$URL);
    $URL= str_replace('\%26', '',$URL);
    $URL= str_replace('/', '',$URL);    
    $URL= str_replace(':', '',$URL);
    $URL= str_replace('.', '',$URL);
    $URL= str_replace('amp;', '',$URL);
    
    $partner_details = $db->get_sql_row("SELECT replace(replace(replace(replace(replace(advert_url, '%26', ''), '&', ''), '/', ''), ':',''),'.','') as x, name FROM " . DB_PREFIX . "partners WHERE replace(replace(replace(replace(replace(advert_url, '%26', ''), '&', ''), '/', ''), ':',''),'.','')='" . $URL . "'");
    $vendor = $partner_details['name'];
}

$np_details = $db->get_sql_row("SELECT tax_company_name FROM " . NPDB_PREFIX . "users WHERE user_id='" . $npid . "'");
$nonprofit = $np_details['tax_company_name'];

$activation_link="unsubcribe";
$sql_select_news = $db->query("SELECT n.news_id, n.news_name FROM " . NPDB_PREFIX ."news n WHERE n.user_id=" . $npid);

$news='';
while ($news_detail= $db->fetch_array($sql_select_news))
{
        if ($news!="")
        {
            $news .= "<br> <br>";
        }
        $news .= "<b><a href=\"http://" . $_SERVER["SERVER_NAME"] . "/npnews.php?news_id=" . $news_detail["news_id"]  . "\" target=\"_blank\">" . $news_detail["news_name"] . "</a></b>";
}

if ($news!="")
{
    $news .= "<br> <br>";
}

if ($news!='')
{
    $news = " And by the way, here is their latest news item: <br><br>" . $news;
}

$template_output = $template->process('global_partner_alert.tpl.php');

echo $template_output;

?>
