<?php
/**
 * Created by Lilian Codreanu.
 * User: Lilian Codreanu
 * Date: 2/4/13
 * Time: 10:23 PM
 */

$GLOBALS['body_id'] = "global_partners";
include_once ('includes/global.php');

if(!isset($_GET['search'])) die();

$amazon_item = $db->get_sql_row("SELECT advert_id, name, description, advert_code, big_banner_code, currency, end_time
                                    FROM probid_partners WHERE name LIKE 'Amazon' LIMIT 1");

$search_keyword = $_GET['search'];
$sql = "SELECT a.advert_id, a.name, a.advert_code, a.advert_url, a.advert_pct, a.nb_bids, a.currency, a.end_time, a.closed, a.bold, a.hl, a.buyout_price, a.is_offer, a.reserve_price, a.owner_id, a.postage_amount, a.fb_current_bid, a.auction_type FROM probid_partners a FORCE INDEX(auctions_end_time) WHERE a.active=1 AND a.approved=1 AND a.deleted=0 AND a.list_in!='store' AND a.creation_in_progress=0 AND MATCH (a.name, a.description) AGAINST ('+$search_keyword*' IN BOOLEAN MODE) ORDER BY a.end_time ASC LIMIT 0, 10";

$rows = $db->query($sql);
$result = array();
$result[]= html_entity_decode($amazon_item['advert_code']);
while ($row = $db->fetch_array($rows))
{
    if($row['name'] == $amazon_item['name'])
        continue;
    $result[]=html_entity_decode($row['advert_code']);
}
echo(json_encode($result));