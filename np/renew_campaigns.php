<?php
/**
 * Created by JetBrains PhpStorm.
 * User: radu
 * Date: 8/29/13
 * Time: 9:14 AM
 */

include_once ('includes/npglobal.php');
include_once ('includes/npclass_user.php');

$user = new npuser();
$current_time = time();

$closed_campaigns = $user->get_closed_campaigns();

echo '<pre>';
var_dump($closed_campaigns);
echo '</pre>';

$renew_campaign_data = array();
$clone_campaign = array();

$i = 0;
$z = 0;
foreach ($closed_campaigns as $closed_campaign) {
    if ($closed_campaign['cron_company'] == 0) {
        $time_to_add = 0;
        var_dump($closed_campaign["keep_alive_days"]);
        if (isset($closed_campaign["keep_alive_days"])) {
            $time_to_add = $closed_campaign["keep_alive_days"] * 86400;
            var_dump($closed_campaign["keep_alive_days"]);
            var_dump($time_to_add);
        }
        $renew_campaign_data[$i]["user_id"] = $closed_campaign["user_id"];
        $renew_campaign_data[$i]["end_date"] = $current_time + $time_to_add;
        $i++;
    } //elseif ($closed_campaign['cron_company'] == 1) {

        //$clone_campaign[$z] = $closed_campaign["user_id"];
   // }

}

//echo '<br>==========================================================================</br>';
//echo '<pre>';
//var_dump($renew_campaign_data);
//echo '</pre>';

//$user->clone_campaign($clone_campaign);

$user->renew_campaigns($renew_campaign_data);

header("location: /");
