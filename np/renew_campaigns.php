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

$renewed_campaigns = $user->new_renew_campaigns();

print_r($renewed_campaigns['project_title']);

$closed_campaigns = $user->get_closed_campaigns();
$user->close_campaigns($closed_campaigns);

//exit;
// $renew_campaign_data = array();
// $clone_campaign = array();

// $i = 0;
// $z = 0;
// foreach ($closed_campaigns as $closed_campaign) {
//     if ($closed_campaign['clone_campaign'] == 3) {
//         $clone_campaign[$z] = $closed_campaign["user_id"];
//         $z++;
//     } elseif ($closed_campaign['clone_campaign'] == 2) {
//         $time_to_add = 0;
//         var_dump($closed_campaign["keep_alive_days"]);
//         if (isset($closed_campaign["keep_alive_days"])) {
//             $time_to_add = $closed_campaign["keep_alive_days"] * 86400;
//             var_dump($closed_campaign["keep_alive_days"]);
//             var_dump($time_to_add);
//         }
//         $renew_campaign_data[$i]["user_id"] = $closed_campaign["user_id"];
//         $renew_campaign_data[$i]["end_date"] = $current_time + $time_to_add;
//         $i++;
//     }
//    if ($closed_campaign['clone_campaign'] != 0) {
//        $time_to_add = 0;
//        var_dump($closed_campaign["keep_alive_days"]);
//        if (isset($closed_campaign["keep_alive_days"])) {
//            $time_to_add = $closed_campaign["keep_alive_days"] * 86400;
//            var_dump($closed_campaign["keep_alive_days"]);
//            var_dump($time_to_add);
//        }
//        $renew_campaign_data[$i]["user_id"] = $closed_campaign["user_id"];
//        $renew_campaign_data[$i]["end_date"] = $current_time + $time_to_add;
//        $i++;
//        /**
//         * at first we keep alive that campaign after that wwe are cloning
//         */
//        if ($closed_campaign['clone_campaign'] == 1) {
//            $clone_campaign[$z] = $closed_campaign["user_id"];
//            $z++;
//        }
//    }
// }

//echo '<br>==========================================================================</br>';
//echo '<pre>';
//var_dump($renew_campaign_data);
//echo '</pre>';
// $user->renew_campaigns($renew_campaign_data);

//$user->clone_campaign($clone_campaign);
// $user->renew_cloned_campaigns($clone_campaign);



//header("location: /");
