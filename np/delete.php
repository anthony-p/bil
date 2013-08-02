<?php
/**
 * Created by JetBrains PhpStorm.
 * User: radu
 * Date: 7/24/13
 * Time: 12:31 AM
 */

include_once ('includes/npglobal.php');
include_once ('includes/npclass_user.php');

$campaign_id = (isset($_GET['np_userid'])) ? $_GET['np_userid'] : 0;

$user = new npuser();
$user->delete_campaign($campaign_id);

echo $campaign_id;
//header('location: /index.php');