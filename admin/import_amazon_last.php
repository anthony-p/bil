<?php
error_reporting(E_ALL);

include_once ('../includes/config.php');

$user = "1pro.cli@gmail.com";
$password = "primus21";
$url = "https://affiliate-program.amazon.com";
$cookieName = "default";
$yesterdey = date("Y-m-j", time() - 60 * 60 * 24);
$start_date = $yesterdey;//"2012-09-1";//$yesterdey;//"2012-06-30";
$end_date = $yesterdey;//"2012-10-14";//$yesterdey;//"012-09-16";
//Cookies dir
$dir = '/tmp/';
//$dir = dirname(__FILE__)."/";

//Reports dir
$reports_dir = 'report/';

$start_date = explode("-", $start_date);
$end_date = explode("-", $end_date);

try{
    //delete cookies file
    if ($handle = opendir($dir)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                if ($cookieName == substr($file, 0, strpos($file, "_"))) {
                    unlink($dir.$file);
                    break;
                }
            }
        }
        closedir($handle);
    }

    //cookies file
    // Other way to make it
    $cookies = "cookies/".$cookieName.'_cookies.txt';
    touch($cookies);
    chmod($cookies, 0777);

    //Better way to make it.
    //$cookies = tempnam($dir, $cookieName.'_cookies');
    //curl options
    $options = array(
        CURLOPT_USERAGENT => "Mozilla/5.0 (X11; Linux i686; rv:7.0.1) Gecko/20100101 Firefox/7.0.1",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FAILONERROR => true,
        CURLOPT_COOKIEJAR => $cookies,
        CURLOPT_COOKIEFILE => $cookies,
        CURLOPT_FAILONERROR => true,
        CURLOPT_HTTPAUTH => CURLAUTH_ANY,
        CURLOPT_AUTOREFERER => true,
        CURLOPT_SSL_VERIFYPEER => false,
    );


    //Init curl to get session id for login
    $ch = curl_init();
    $options[CURLOPT_URL] = $url;
    $options[CURLOPT_POST] = true;
    $options[CURLOPT_FOLLOWLOCATION] = true;
    curl_setopt_array($ch, $options);
    //Close curl session
    curl_exec($ch);
    curl_close($ch);

    //get cookies from file
    $aCookies = array();
    $aLines = file($cookies);
    foreach ($aLines as $line) {
        if ('#' == $line {
        0})
            continue;
        $arr = explode("\t", $line);
        if (isset($arr[5]) && isset($arr[6]))
            $aCookies[$arr[5]] = str_replace("\n", "", $arr[6]);
    }

    $cookies = $aCookies;

    //Init curl for login
    $ch = curl_init();
    $options[CURLOPT_URL] = $url."/gp/flex/sign-in/select.html?";
    $options[CURLOPT_FOLLOWLOCATION] = true;
    $options[CURLOPT_POST] = true;

    //set params for login
    $params['sessionId'] = $cookies['session-id'];
    $params['query'] = "/gp/associates/join/landing/main.html";
    $params['path'] = "/gp/associates/login/login.html";
    $params['action'] = "sign-in";
    $params['mode'] = "1";
    $params['email'] = $user;
    $params['password'] = $password;

    $arg = getPostFields($params);
    $options[CURLOPT_POSTFIELDS] = $arg;
    curl_setopt_array($ch, $options);
    curl_exec($ch);
    //Close curl session
    curl_close($ch);

    //Init curl for downloading xml report
    echo "Download XML report ...<br>";
    $ch = curl_init();
    //set params for download
    //$params = array();
    $params['tag'] = '';
    $params['idbox_store_id'] = 'mainsailstore-20';
    $params['reportType'] = 'tagsReport';
    $params['preSelectedPeriod'] = 'yesterday';
    $params['periodType'] = 'exact';
    $params['startMonth'] = $start_date[1]-1;
    $params['startDay'] = $start_date[2];
    $params['startYear'] = $start_date[0];
    $params['endMonth'] = $end_date[1]-1;
    $params['endDay'] = $end_date[2];
    $params['endYear'] = $end_date[0];
    $params['submit.download_XML'] = 'Download report (XML)';
    $arg = getPostFields($params);

    $options[CURLOPT_URL] = $url."/gp/associates/network/reports/report.html?".$arg;
    $options[CURLOPT_FOLLOWLOCATION] = true;
    $options[CURLOPT_RETURNTRANSFER] = true;
    curl_setopt_array($ch, $options);
    $result = curl_exec($ch);
    $err = curl_errno($ch);
    $errmsg = curl_error($ch);
    $info = curl_getinfo($ch);
    $content = curl_multi_getcontent($ch);

    //Close curl session
    curl_close($ch);


    //file_put_contents($reports_dir."report_".implode("", $start_date)."-".implode("", $end_date).".xml", $content);

    // Import Amazon Report to Database table
    $tag_exist  = false;

    ## Database Username
    $username =  $db_username;
    ## Database Login Password
    $password = $db_password;
    $database=$db_name;
    ##  Opens a connection to a MySQL server
    $connection = mysql_connect("localhost", $username, $password);
    if (!$connection) {
        die("Not connected : " . mysql_error());
    }
    ## Set the active MySQL database
    $db_selected = mysql_select_db($database, $connection);

    if (!$db_selected)
    {
        die("Can\'t use db : " . mysql_error());
    }


    //one day dates
    //$use_one_day = true;
    //if($use_one_day){
        $start_date = date('Y-m-d 00-00-00', strtotime(implode('-', $start_date)));
        $end_date = date('Y-m-d 23-59-59', strtotime(implode('-', $end_date)));
    //}
}catch (Exception $e){
    echo $e->getMessage();
}

echo "Import to Database XML report ...<br>";
/*
$content = '<?xml version="1.0"?>
<Data>
<TagDetails>
    <OneTag Clicks="1" GrowthTargetBonus="0.00" OrderedUnits="1" ShippedEarnings="1.26" ShippedRevenue="20.95" ShippedUnits="1" Tag="bringlocal35-20" TierBonus="0.00" TotalEarnings="1.26" Visitors="1"/>
    <OneTag Clicks="1" GrowthTargetBonus="0.00" OrderedUnits="1" ShippedEarnings="5.20" ShippedRevenue="79.95" ShippedUnits="1" Tag="bringlocal36-20" TierBonus="0.00" TotalEarnings="5.20" Visitors="1"/>
</TagDetails>
<Totals Clicks="2" GrowthTargetBonus="0.00" OrderedUnits="2" ShippedEarnings="6.46" ShippedRevenue="100.90" ShippedUnits="2" TierBonus="0.00" TotalEarnings="6.46" Visitors="2"/>
</Data>'; */

$user_arr= array();
$xml = simplexml_load_string($content);
foreach($xml->children() as $child)
{

    if ("TagDetails"==$child->getName())
    {
        getOneTag($child);
    }
}

if(count($user_arr)){
    echo "Sanding email to users ... <br>";
    sendNotificationEmail($user_arr);
}

echo "Done import to Database XML report ...<br>";

function getPostFields(array $data) {

    $return = array();

    foreach ($data as $key => $parameter) {
        $return[] = $key.'='.urlencode($parameter);
    }

    return implode('&', $return);
}

function getOneTag($xml)
{
    global $tag_exist;
    global $start_date;
    global $end_date;

    global $user_arr;

    foreach($xml->children() as $child)
    {
        if ("OneTag"==$child->getName())
        {
            $id = 0;
            $tracking_id = (string)$child->attributes()->Tag;
            $user_id = 0;
            $shop_url_id = 0;
            $click_date = "";
            $target_url = "";
            $npuser_id = 0;
            $sales = (string)$child->attributes()->ShippedRevenue;
            $commision = (string)$child->attributes()->ShippedEarnings;

            ##get any tracks that happen before consolidated date and  within a week.
            $s1 = "select id, click_date from shop_tracking_links
                    where (click_date > '".$start_date."' and click_date < '".$end_date."')
                    and target_url like '%amazon%'
                    and tracking_id='".$tracking_id."' order by click_date desc  limit 0,1";
            $sql_select= mysql_query($s1);

            while ($row = mysql_fetch_assoc($sql_select))
            {
                $id = $row["id"];
            }

            if($id){
                $s2 = "select * from shop_tracking_links where id=". $id;
                $sql_select= mysql_query($s2);

                while ($row = mysql_fetch_assoc($sql_select))
                {
                    $user_id = $row["user_id"];
                    $npuser_id = $row["np_userid"];
                    $tracking_id = $row["tracking_id"];
                    $shop_url_id = $row["shop_url_id"];
                    $target_url = $row["target_url"];
                    $click_date = $row["click_date"];
                }

                $tag_exist = TagExist($tracking_id, $user_id, $click_date);
                if(!$tag_exist){
                    $sql = "INSERT INTO giveback_amazon_invoices (tracking_id, user_id, shop_url_id, date_time, destination, np_user_id, sales, commision)
                    VALUES ('$tracking_id', '$user_id', '$shop_url_id', '$click_date', '$target_url', '$npuser_id', '$sales', '$commision')";
                    mysql_query($sql);

                    $activity_sql="SELECT points_awarded FROM probid_user_activities WHERE activity_id = 8";
                    $activity_result = mysql_query($activity_sql);
                    $activity_row = mysql_fetch_array($activity_result);
                    $points_awarded = $activity_row['points_awarded'];
                    mysql_query("INSERT INTO probid_user_points (user_id, activity_id, points_awarded, awarded_date)
                                                  VALUES ('$user_id', '8', '$points_awarded', date(now()))");

                    $email_user_id = $user_id;
                    $activity_id = 8;
#                   include('../global_loyality_user_notification.php');

                    ##add user array to send emails.
                    if (!array_key_exists($user_id, $user_arr))
                    {
                        $user_arr[$user_id]['sales'] = $sales;
                        $user_arr[$user_id]['commision'] = $commision;
                    }else{
                        $user_arr[$user_id]['sales'] += $sales;
                        $user_arr[$user_id]['commision'] += $commision;
                    }
                }
            }
        }
    }
    return false;
}

function TagExist($tag, $user_id, $click_date)
{
    if ($user_id=='')
        $user_id=0;

    $s = "select * from giveback_amazon_invoices where date_time = '".$click_date."'
          and tracking_id = '".$tag."' and user_id='".$user_id."'" ;

    $sql_select= mysql_query($s);
    if($sql_select){
        while ($row = mysql_fetch_assoc($sql_select))
        {
            return true;
        }
    }
    return false;
}

function sendNotificationEmail($user_arr)
{
    foreach($user_arr as $mail_input_id => $values)
    {
        $gross = $values['sales'];
        $points = $values['commision'] / 2.0;

        if($values['sales'] > 0 AND $values['commision'] > 0){
            include ('giveback_invoice_email.php');
        }
    }
    return true;
}
