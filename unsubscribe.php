<?
#################################################################
## PHP Pro Bid v6.01															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

session_start();

define ('IN_SITE', 1);


if(!isset($_REQUEST["wkey"]) && !isset($_REQUEST["sbb"])){
    header('Location: /');
    die;
}

include_once ('includes/global.php');
include_once ('includes/class_formchecker.php');
include_once ('includes/class_custom_field.php');

include_once ('global_header.php');

if (isset($_REQUEST["wkey"])) {
    $wkey = $db->rem_special_chars($_REQUEST["wkey"]);
    $result = $db->get_sql_row("SELECT * FROM `np_users` WHERE wkey='$wkey' AND wkey <> ''");

    if ($result) {
        @$db->get_sql_row("UPDATE `np_users` SET `report_daily`=0, `report_weekly`=0, `report_monthly`=0 WHERE wkey='$wkey' AND wkey <> '' ");
    }

} elseif(isset($_REQUEST["sbb"])) {

    $wkey = $db->rem_special_chars($_REQUEST["sbb"]);
    $result = $db->get_sql_row("SELECT * FROM `probid_users` WHERE MD5(user_id) ='$wkey'");
    if ($result) {
        @$db->get_sql_row("UPDATE `probid_users` SET  `clickreport` =  '0' WHERE  MD5(`probid_users`.`user_id`) ='$wkey'");
    }

}

$template_output .= $template->process('unsubscribe.tpl.php');
include_once ('global_footer.php');
echo $template_output;

?>
