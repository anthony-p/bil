<?php
error_reporting(E_ALL);
date_default_timezone_set("America/Los_Angeles");

include_once ('../includes/config.php');

//$yesterdey = date("Y-m-j", time() - (60 * 60 * 24)*103);
$yesterdey = date("Y-m-d", time() - 60 * 60 * 24);
$start_date = $yesterdey;//"2012-09-1";//$yesterdey;//"2012-06-30";
$end_date = date("Y-m-d", time());//"2012-10-14";//$yesterdey;//"012-09-16";

//Reports dir
$reports_dir = dirname(__FILE__).'/report/gan/';

$start_date = explode("-", $start_date);
$end_date = explode("-", $end_date);

$user_arr= array();

$dir = '/tmp/';
$csvFile = $dir.implode("", $start_date)."-".implode("", $end_date).".csv";

$csvFile = $reports_dir."savedreport_GANyestarday_".implode("", $start_date)."_".implode("", $end_date).".csv";
//$csvReportFile = $reports_dir."savedreport_GANyestarday_20130409_20130410.csv";
$csvFileContent = file($csvReportFile);

echo "Parse CSV file ...<br>";
$csvFileContent = parseCsvConvertToArray($csvFileContent);

## Database Username
$username =  $db_username;
## Database Login Password
$password = $db_password;
$database=$db_name;
##  Opens a connection to a MySQL server
$connection = mysql_connect("127.0.0.1", $username, $password);
if (!$connection) {
    die("Not connected : " . mysql_error());
}
## Set the active MySQL database
$db_selected = mysql_select_db($database, $connection);

if (!$db_selected)
{
    die("Can\'t use db : " . mysql_error());
}

echo "Import to Database CSV report ...<br>";
importReportToDb($csvFileContent);

//$content = file_get_contents("report.xml");
//$xml = simplexml_load_string($content);
//foreach($xml->children() as $child)
//{
//
//    if ("TagDetails"==$child->getName())
//    {
//        getOneTag($child);
//    }
//}

if(count($user_arr)){
    echo "Sanding email to users ... <br>";
    sendNotificationEmail($user_arr);
}

echo "Done import to Database XML report ...<br>";

if(!is_array($start_date) && !is_array($end_date)){
    $csvFileNewName =  "/tmp/".str_replace("-","",substr($start_date,0,10))."-".str_replace("-","",substr($end_date,0,10)).".csv";
    if($csvFileNewName != $csvFile)
        rename($csvFile,$csvFileNewName);
}
// Send Report to mail at support
sendReportToSupport($start_date, $end_date, $csvFile);

function importReportToDb($data)
{
    global $tag_exist;
    global $start_date;
    global $end_date;

    global $user_arr;

    global $csvFile;
    foreach ($data as $line) {

        if ($line["Member ID"] == "") continue;

        $id = 0;
        $tracking_id = (string)$line["Member ID"];
        $user_id = 0;
        $shop_url_id = 0;
        $click_date = $line["Modified Day"];
        $target_url = "";
        $npuser_id = 0;
        $pct_giveback = 0;
        $sales = (float) str_replace("$","",$line["Publisher-commissionable sales"]);
        $commision = (float) str_replace("$","",$line["Publisher fees"]);

//        $orderedUnit = (int)$child->attributes()->OrderedUnits;
//        $shippedUnits = (int)$child->attributes()->ShippedUnits;

        $np_share = 0;
        $bil_share = 0;
        $pct = 0;
        if($commision > 0 && $sales > 0){
            $np_share = round($commision/2,2);
            $bil_share = round($commision/2,2);
            $pct = (round(($commision/$sales)*100,2))."%";
            $pct_giveback = round($pct/2,2)."%";
        }
        $s2 = "select shop_tracking_links.*, probid_users.name as user_name, np_users.tax_company_name as npuser_name from shop_tracking_links LEFT JOIN probid_users ON shop_tracking_links.user_id  = probid_users.user_id LEFT JOIN np_users ON shop_tracking_links.np_userid=np_users.user_id where shop_tracking_links.tracking_id='$tracking_id' ORDER BY id DESC LIMIT 1";
        $sql_select= mysql_query($s2);

        while ($row = mysql_fetch_assoc($sql_select))
        {
            $id = $row["id"];
            $user_id = $row["user_id"];
            $npuser_id = $row["np_userid"];
            $tracking_id = $row["tracking_id"];
            $shop_url_id = $row["shop_url_id"];
            $target_url = $row["target_url"];
            $click_date = $row["click_date"];
            // Check Click Data and merge time difference
            $tmpDate = trim(substr($click_date,0,strpos($click_date," ")));
            $nowDate = date("Y-m-j", time());
            if($tmpDate == $nowDate)
                $click_date = date("Y-m-j", time() - 60 * 60 * 24);

            if($row["user_name"] != null)
                $user_name = $row["user_name"];
            elseif($user_id == "widget")
                $user_name = "widget";
            else
                $user_name = "guest shopper";
            $npuser_name = $row["npuser_name"];
        }

        $tag_exist = TagExist($tracking_id);

        if(!$tag_exist){
            $fields = array();
            $fields[]="$id";//unique id
            $fields[]="$tracking_id";//tracking link
            $fields[]="$user_id";//site user
            $fields[]="$user_name";//user name
            $fields[]="$click_date";//click date
            $fields[]="$target_url";//vendor
            $fields[]="$npuser_id";//np-id
            $fields[]="$npuser_name";//np-name
            $fields[]="$sales";//Sales
            $fields[]="$commision";//Commission
            $fields[]=$pct;//pct
            $fields[]=$pct_giveback;//pct giveback
            $fields[]=$np_share;//np-share
            $fields[]=$bil_share;//bil-share

            $fp = fopen($csvFile, 'a+');
            fputcsv($fp,$fields);

            $sql = "
            INSERT INTO `vendor_click_reports` (`unique id`, `tracking link`, `site user`, `user name`, `click date`, `vendor`, `np-id`, `np-name`, `Sales`, `Commission`, `pct`, `pct_giveback`, `np-share`, `bil-share`, `last_update`) VALUES
            ('$id', '$tracking_id', '$user_id', '$user_name', '$click_date', '$target_url', '$npuser_id', '$npuser_name', $sales, $commision, '$pct', '$pct_giveback', $np_share, $bil_share, '$click_date')";
            mysql_query($sql);


            $activity_sql="SELECT points_awarded FROM probid_user_activities WHERE activity_id = 8";
            $activity_result = mysql_query($activity_sql);
            $activity_row = mysql_fetch_array($activity_result);
            $points_awarded = $activity_row['points_awarded'];

            mysql_query("INSERT INTO probid_user_points (user_id, activity_id, points_awarded, awarded_date)
                                          VALUES (sd'$user_id', '8', '$points_awarded', date(now()))");

            ##add user array to send emails.
            if (!array_key_exists($user_id, $user_arr))
            {
                $user_arr[$user_id]['sales'] = $sales;
                $user_arr[$user_id]['commision'] = $commision;
            }else{
                $user_arr[$user_id]['sales'] += $sales;
                $user_arr[$user_id]['commision'] += $commision;
            }


        }else{

            $fields = array();
            $sales = (float)$sales+(float)$tag_exist["Sales"];
            if($commision > 0 && $sales > 0){
                $np_share = round($commision/2,2);
                $bil_share = round($commision/2,2);
                $pct = (round(($commision/$sales)*100,2))."%";
                $pct_giveback = round($pct/2,2)."%";
            }
            $fields[]="$id";//unique id
            $fields[]="$tracking_id";//tracking link
            $fields[]="$user_id";//site user
            $fields[]="$user_name";//user name
            $fields[]="$click_date";//click date
            $fields[]="$target_url";//vendor
            $fields[]="$npuser_id";//np-id
            $fields[]="$npuser_name";//np-name
            $fields[]="$sales";//Sales
            $fields[]="$commision";//Commission
            $fields[]=$pct;//pct
            $fields[]=$pct_giveback;//pct giveback
            $fields[]=$np_share;//np-share
            $fields[]=$bil_share;//bil-share

            $fp = fopen($csvFile, 'a+');
            fputcsv($fp,$fields);

            $id = $tag_exist["unique id"];
            $today = date("Y-m-j", time() - 86400);
            $sql = "UPDATE  `vendor_click_reports` SET  `Sales` =  '$sales' , `Commission` =  '$commision' ,  `pct` =  '$pct' ,  `pct_giveback` =  '$pct_giveback' , `np-share` =  '$np_share' , `bil-share` =  '$bil_share' , `last_update` = '$today' WHERE  `unique id` =  '$id' LIMIT 1";
            mysql_query($sql);
        }

    }

}

function TagExist($tag)
{

    $s = "SELECT * FROM bringit_auction.vendor_click_reports where `tracking link` = '$tag' ORDER BY `unique id` DESC LIMIT 1" ;

    $sql_select= mysql_query($s);
    if($sql_select){
        while ($row = mysql_fetch_assoc($sql_select))
        {
            return $row;
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

function sendReportToSupport($start_date, $end_date, $csvFile=null){

    $start_date = implode("-",$start_date);
    $end_date = implode("-",$end_date);
    include("giveback_invoice_support_email.php");

}


function parseCsvConvertToArray(array $content)
{
    $keys = str_getcsv($content[2]);
    unset($content[0]);
    unset($content[1]);
    unset($content[2]);
    $result = array();
    if (!empty($content)) {
        foreach ($content as $line) {
            $line = str_getcsv($line);
            $result[] = array_combine($keys,$line);
        }

    }
    return $result;

}
