<?php
/**
 * Created by Lilian Codreanu.
 * User: Lilian Codreanu
 * Date: 1/10/13
 * Time: 2:50 AM
 */
error_reporting(0);
define ('IN_SITE', 1);

include_once ('includes/global.php');

if(!isset($_GET["wkey"]))
    die;

$blocks = 8;

if(isset($_GET["blocks"]))
    $blocks = $_GET["blocks"];
$user_details = $db->get_sql_row("SELECT user_id, username,  email,
    				enable_aboutme_page, aboutme_page_content, shop_account_id, shop_active, tax_company_name, wkey FROM
    				" . NPDB_PREFIX . "users WHERE wkey='". $_GET["wkey"]."'");

if($user_details == null){
    header("Location: /");
    die;
}

$tax_company_name = $user_details['tax_company_name'];
$username = $user_details['username'];

setcookie("np_userid","",time()-3600,"/",".bringitlocal.com",0);
setcookie("np_userid",$user_details["user_id"]);

$amazon_item = $db->get_sql_row("SELECT advert_id, name, description, advert_code, big_banner_code, currency, end_time
                                    FROM probid_partners WHERE name LIKE 'Amazon' LIMIT 1");

## featured ads
$select_condition = "WHERE
            hpfeat=1 AND active=1 AND approved=1 AND closed=0 AND deleted=0 AND
            list_in!='store' AND advert_id!= {$amazon_item["advert_id"]} AND big_banner_code != ''";


$global_item_details = $db->random_rows('partners', 'advert_id, name, description, advert_code, big_banner_code, currency, end_time', $select_condition, $blocks-1);

array_unshift($global_item_details,$amazon_item);
//$global_item_details[0] = $amazon_item;
//$template->set('global_item_details', $global_item_details);
$template->set('vendor_banner_code', html_entity_decode($global_item_details[0]["advert_code"]));

$template->set('vendors', $global_item_details);
$template->set('companyName',$tax_company_name);
$template->set('username',$username);
$template_output .= $template->process('widget.tpl.php');

echo $template_output;

