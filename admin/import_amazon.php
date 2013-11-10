<?php
error_reporting(E_ALL);
date_default_timezone_set("America/Los_Angeles");

include_once ('../includes/config.php');

$user = "thesite@bringitlocal.com";
$password = "21primus4412";
$url = "https://affiliate-program.amazon.com";
$cookieName = "default";
//$yesterdey = date("Y-m-j", time() - (60 * 60 * 24)*103);
$yesterdey = date("Y-m-j", time() - 60 * 60 * 24);
$start_date = $yesterdey;//"2012-09-1";//$yesterdey;//"2012-06-30";
$end_date = $yesterdey;//"2012-10-14";//$yesterdey;//"012-09-16";
//Cookies dir
$dir = '/tmp/bil/amazon/';
//$dir = dirname(__FILE__)."/";

//Reports dir
$reports_dir = 'report/';

$start_date = explode("-", $start_date);
$end_date = explode("-", $end_date);

$csvFile = $dir.implode("", $start_date)."-".implode("", $end_date).".csv";
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
        CURLOPT_USERAGENT => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:19.0) Gecko/20100101 Firefox/19.0",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FAILONERROR => true,
        CURLOPT_COOKIEJAR => $cookies,
        CURLOPT_COOKIEFILE => $cookies,
        CURLOPT_FAILONERROR => true,
        CURLOPT_HTTPAUTH => CURLAUTH_ANY,
        CURLOPT_AUTOREFERER => true,
        CURLOPT_SSL_VERIFYPEER => false,
    );

    $header = array();
    $header[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
    $header[] = "Accept-Language: en-US,en;q=0.5";
    $header[] = "Connection: keep-alive";

    //Init curl to get session id for login
    $ch = curl_init();
    $options[CURLOPT_URL] = $url;
    $options[CURLOPT_POST] = false;
    $options[CURLOPT_FOLLOWLOCATION] = true;
    curl_setopt_array($ch, $options);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    //Close curl session
    $mainPage = curl_exec($ch);
    if($mainPage == false)
        die(curl_error($ch));
    $widgetToken = substr($mainPage,strpos($mainPage,"widgetToken"));
    $widgetToken = substr($widgetToken,strpos($widgetToken,'value="')+7);
    $widgetToken = substr($widgetToken,0,strpos($widgetToken,'"'));

//    curl_close($ch);

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
//    $ch = curl_init();
    $options[CURLOPT_URL] = $url."/gp/flex/sign-in/select.html?";
    $options[CURLOPT_FOLLOWLOCATION] = true;
    $options[CURLOPT_POST] = true;

    //set params for login
    $params['sessionId'] = $cookies['session-id'];
    $params['widgetToken'] = $widgetToken;
    $params['query'] = "/gp/associates/join/landing/main.html";
    $params['path'] = "/gp/associates/login/login.html";
    $params['action'] = "sign-in";
    $params['mode'] = "1";
    $params['email'] = $user;
    $params['password'] = $password;
    $params['metadata1'] = "ymcpwKTJRakGCxtfteIQhx9RqJB5Ejp33PMaQGzVZb4sAxgDzEDrHEmxCksY4kJ4rhNGoUNHBtcXIKgJKyHQnKQ08e/3UgtlV2jiVoKmaz3VTiAymCkaJMptGpS4lyVNzy2zlcGGemGii3jQzuwhQDiYKP06P4xzpIGlJ8W9xDoYWlqtVchkmMZNcQUOPod7XLFa/eQL77mfPkIPAEkTJhsUfcRqDI2bAl1SLL7UTobZfmOYZyUckMH0i4TyQzlBMpSjEFP/yCvjMGOU0GhayBI9duZlG31SswiGoM9XVeTsQB9ma22zLu5a1W+z2tqZh20JR03LdeC0S+aWgU2S/dauY0FdZ4p8HSEiqjvJgdcZEoqPRXbfPA5PLR/GDhsuCrJLoTl4B7n7Z+8LLUZ+gUs9l5XgJfu7mdr/o8au8ZxjmVrTD09tVfLXp/+dwLeiUe99nM5vwJXmfzE2H6dq5iSB/Nte/WthMbIJH5O8wiNx0ZgJNNzFEo4JnqJac3lQiDizppghlLjmaCmAj6zIL/Nkd6Fkd+P6xMgo7rK64bU6bU1NALmI1yVNrrew8Rm0/HfQ38CjvRlUs/4wUiDITtXHvW78qj1NdJLBEBdCGYk3DK/hKpsvBGUIkMTIGY2T6g/95hk5o5282bS7fgeuyARI/aZ0CPiEI42EQZOMGa2+85Cpff3f6LmBL7RJiPiCKH40CGc9qPSwpyMATFeoe70usCVIdAWv8/fPjnnoetbBKaQMKCdh4xGAW/VyxKMYj7iHRbueXVFsmo2Lcz8tnVDUiTBrRi0dG8Uf2SvOLK6zlXAPnsNfJj/Cj1jip4U8sDAO4cREA7uTpyhiVEN2xeL1zmiQGN+nFGl2gCv9CLDr0uqt88nYdl+FqFaZ/IRxl3gc/YBPEgOIAL2OKxWmSUguGaVvXdCrCPt4xUqvhBejYmWc0QElmbbqoHAPJu0l/ElhZSSxqMyEfiLRg7P3Ox1/cANU9SbY4+gT3UqRSTBJVTuk4N9162+0fBEsImSj8Fal0AJFhKp8/jeFsBfJ3kdho/xQwY9L4xBBSa3t+PIgIMa+g67QO57M24bSw/uh0RElPuiMaupNn1LLtOsM+dDxyaCMm+X73DwGIDKZbcDtw0rE+VG5ZGC7Wkc=";

    $arg = getPostFields($params);
    $options[CURLOPT_POSTFIELDS] = $arg;
    curl_setopt_array($ch, $options);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_exec($ch);
    //Close curl session
//    curl_close($ch);

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
    $params['submit.download_CSV'] = 'Download report (CSV)';
//    $params['submit.download_XML'] = 'Download report (XML)';
    $arg = getPostFields($params);

    //https://affiliate-program.amazon.com/gp/associates/network/reports/report.html?tag=&reportType=tagsReport&periodType=preSelected&preSelectedPeriod=yesterday&startMonth=1&startDay=15&startYear=2013&endMonth=1&endDay=15&endYear=2013&submit.download_CSV.x=57&submit.download_CSV.y=10&submit.download_CSV=Download+report+%28CSV%29

    //-------------------------------------------------------------------------
    //------------------- Amazon Last Update Time -----------------------------

    // $options[CURLOPT_URL] = $url."/gp/associates/network/reports/report.html?".$arg;
    // $options[CURLOPT_FOLLOWLOCATION] = true;
    // $options[CURLOPT_RETURNTRANSFER] = true;
    // curl_setopt_array($ch, $options);
    // $result = curl_exec($ch);    
    // $err = curl_errno($ch);
    // $errmsg = curl_error($ch);
    // $info = curl_getinfo($ch);
    // $content = curl_multi_getcontent($ch);

	$content = file_get_contents($dir.'report.txt');
//    $content = file_get_contents("report.txt");
    $periodTime = $content;
    $periodTime = trim(substr($periodTime,0,strpos($periodTime,"\n")));
    $periodTime = trim(substr($periodTime,strpos($periodTime,"mainsailstore-20")+16));
    $periodTime = explode(" to ",$periodTime);
    $periodTime = $periodTime[1];
    $periodTime = str_replace(",","",$periodTime);
    $periodTime = strtotime($periodTime);
    //-------------------------------------------------------------------------
    // var_dump($content);die;

    //file_put_contents($reports_dir."report_".implode("", $start_date)."-".implode("", $end_date).".xml", $content);
    $csv_header = "unique id,tracking link,site user,user name,click date,vendor,np-id,np-name,Sales,Commission,pct,pct giveback,np-share,bil-share\n";
    file_put_contents($csvFile, $csv_header);

    // Import Amazon Report to Database table
    $tag_exist  = false;

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


    //-------------------------------------------------------------------------
    //------------------- Amazon Last Update Time -----------------------------
    $sql = "SELECT * FROM amazon_report_time WHERE time = '$periodTime'";
    $sql_select = mysql_query($sql);
    $count = mysql_num_rows($sql_select);
    if(mysql_num_rows($sql_select) > 0){
		unlink($csvFile);
        die(date("F j, Y",$periodTime)." was updated ");
	}
    $sql = "INSERT INTO `amazon_report_time` (`time`) VALUES ($periodTime)";
    if(!mysql_query($sql))
        die("Can\'t add to DB last update!");
    if (isset($params['submit.download_CSV']))
        unset($params['submit.download_CSV']);
    //-------------------------------------------------------------------------

    // $params['submit.download_XML'] = 'Download report (XML)';
    // $arg = getPostFields($params);
    // $options[CURLOPT_URL] = $url."/gp/associates/network/reports/report.html?".$arg;
    // $options[CURLOPT_FOLLOWLOCATION] = true;
    // $options[CURLOPT_RETURNTRANSFER] = true;
    // curl_setopt_array($ch, $options);
    // $result = curl_exec($ch);
    // $err = curl_errno($ch);
    // $errmsg = curl_error($ch);
    // $info = curl_getinfo($ch);
    // $content = curl_multi_getcontent($ch);

    // Close curl session
    // curl_close($ch);
	
	$content = file_get_contents($dir.'report.xml');
    //-------------------------------------------------------------------------

    $start_date = date('Y-m-d 00:00:00', strtotime(implode('-', $start_date)));
//        $end_date = date('Y-m-d 08:59:59', strtotime(implode('-', $end_date))+28800);
    $end_date = date('Y-m-d 08:59:59', time());

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
//$content = file_get_contents("report.xml");
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

// Send Report to mail at support
sendReportToSupport();

// if(!is_array($start_date) && !is_array($end_date)){
    // $csvFileNewName =  $dir.str_replace("-","",substr($start_date,0,10))."-".str_replace("-","",substr($end_date,0,10)).".csv";
    // if($csvFileNewName != $csvFile)
        // rename($csvFile,$csvFileNewName);
// }
unlink($csvFile);

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

    global $csvFile;

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
            $pct_giveback = 0;
            $sales = (string)$child->attributes()->ShippedRevenue;
            $commision = (string)$child->attributes()->ShippedEarnings;

            $orderedUnit = (int)$child->attributes()->OrderedUnits;
            $shippedUnits = (int)$child->attributes()->ShippedUnits;

            $np_share = 0;
            $bil_share = 0;
            $pct = 0;
            if($commision > 0 && $sales > 0){
				$sql_query = "SELECT rate_of_pay FROM `payment_option_details` WHERE id=1";
				$sql_select= mysql_query($sql_query);
				while ($row = mysql_fetch_assoc($sql_select)) {
					$rate_of_pay = (int)$row["rate_of_pay"];
					$rate_of_pay2 = 100 - $rate_of_pay;
				}
                $np_share = round($commision*$rate_of_pay2/100,2);
                $bil_share = round($commision*$rate_of_pay/100,2);
                $pct = (round(($commision/$sales)*100,2))."%";
                $pct_giveback = round($pct*$rate_of_pay/100,2)."%";
            } else {
				continue;
			}
            
            // Check if TrackID is Free or bussy
            $atl_sql = "SELECT * FROM  `amazon_tracking_links` WHERE (name = '$tracking_id' AND isfree=0) OR (name = '$tracking_id' AND timestamp+86400 >".time()." )";
            $sql_select= mysql_query($atl_sql);            
            $track_timestamp = 0;            
            while ($row = mysql_fetch_assoc($sql_select))
            {
                $track_timestamp = (int)$row["timestamp"]-28800;
            }
            if($track_timestamp !=0)
                $start_date = date('Y-m-d 0:0:0',$track_timestamp);
//                $start_date = date('Y-m-d H:i:s',$track_timestamp);

            
            ##get any tracks that happen before consolidated date and  within a week.
            $s1 = "select id, click_date from shop_tracking_links where (click_date >= '".$start_date."' and click_date < '".$end_date."') and target_url like '%amazon%' and tracking_id='".$tracking_id."' order by click_date desc  limit 0,1";
            unset($sql_select);
            unset($row);
            $sql_select= mysql_query($s1);
            while ($row = mysql_fetch_assoc($sql_select))
            {
                $id = $row["id"];
            }
			
            if($id){
                $s2 = "SELECT shop_tracking_links. * , bl2_users.first_name, bl2_users.last_name, np_users.tax_company_name AS npuser_name 
						FROM shop_tracking_links 
						LEFT JOIN bl2_users ON shop_tracking_links.user_id = bl2_users.id 
						LEFT JOIN np_users ON shop_tracking_links.np_userid = np_users.user_id 
						WHERE shop_tracking_links.id=". $id;
                $sql_select= mysql_query($s2);

                while ($row = mysql_fetch_assoc($sql_select))
                {
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

                    if($row["first_name"] != null && $row["last_name"] != null)
                        $user_name = $row["first_name"].' '.$row["last_name"];
                    elseif($user_id == "widget")
                        $user_name = "widget";
                    else
                        $user_name = "guest shopper";
                    $npuser_name = $row["npuser_name"];
                }
				
                $tag_exist = TagExist($tracking_id, $user_id, $click_date);
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

                    $sql = "INSERT INTO giveback_amazon_invoices (tracking_id, user_id, shop_url_id, date_time, destination, np_user_id, sales, commision)
                    VALUES ('$tracking_id', '$user_id', '$shop_url_id', '$click_date', '$target_url', '$npuser_id', '$sales', '$commision')";
                    //@TODO UnComment
                    mysql_query($sql);

					$sql = "update np_users set payment=payment+".$np_share." where user_id='".$npuser_id."'";
					mysql_query($sql);
					
					$sql = "insert into funders(user_id, campaign_id, amount, source, created_at) values('".$user_id."', '".$npuser_id."', '".$np_share."', 'click through', '".time()."')";
					mysql_query($sql);
					
                    $activity_sql="SELECT points_awarded FROM probid_user_activities WHERE activity_id = 8";
                    $activity_result = mysql_query($activity_sql);
                    $activity_row = mysql_fetch_array($activity_result);
                    $points_awarded = $activity_row['points_awarded'];

                    //@TODO Uncomment
                    mysql_query("INSERT INTO probid_user_points (user_id, activity_id, points_awarded, awarded_date)
                                                  VALUES (sd'$user_id', '8', '$points_awarded', date(now()))");

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

                    if($orderedUnit == 0 || $orderedUnit == $shippedUnits)               
                        markFree($tracking_id);
                    
                } 
				// elseif($track_timestamp != 0){

                    // $fields = array();
                    // $sales = (float)$sales+(float)$tag_exist["sales"];				
					// if($commision > 0 && $sales > 0){
						// $sql_query = "SELECT rate_of_pay FROM `payment_option_details` WHERE id=1";
						// $sql_select= mysql_query($sql_query);
						// while ($row = mysql_fetch_assoc($sql_select)) {
							// $rate_of_pay = (int)$row["rate_of_pay"];
							// $rate_of_pay2 = 100 - $rate_of_pay;
						// }
						// $np_share = round($commision*$rate_of_pay2/100,2);
						// $bil_share = round($commision*$rate_of_pay/100,2);
						// $pct = (round(($commision/$sales)*100,2))."%";
						// $pct_giveback = round($pct*$rate_of_pay/100,2)."%";
					// }
			
                    // $fields[]="$id";//unique id
                    // $fields[]="$tracking_id";//tracking link
                    // $fields[]="$user_id";//site user
                    // $fields[]="$user_name";//user name
                    // $fields[]="$click_date";//click date
                    // $fields[]="$target_url";//vendor
                    // $fields[]="$npuser_id";//np-id
                    // $fields[]="$npuser_name";//np-name
                    // $fields[]="$sales";//Sales
                    // $fields[]="$commision";//Commission
                    // $fields[]=$pct;//pct
                    // $fields[]=$pct_giveback;//pct giveback
                    // $fields[]=$np_share;//np-share
                    // $fields[]=$bil_share;//bil-share				

                    // $fp = fopen($csvFile, 'a+');
                    // fputcsv($fp,$fields);

                    // $date = substr($click_date, 0, 10);
//                   $sql = 'SELECT `unique id` as ID FROM  `vendor_click_reports` WHERE `tracking link`= "'.$tracking_id.'" AND  `click date`  = "'.$date.'"  ORDER BY  `vendor_click_reports`.`unique id` DESC LIMIT 1';
                    // $sql = 'SELECT `unique id` as ID FROM  `vendor_click_reports` WHERE `tracking link`= "'.$tracking_id.'"  ORDER BY  `vendor_click_reports`.`unique id` DESC LIMIT 1';

                    // $sql_select= mysql_query($sql);
                    // $id = null;
                    // if($sql_select){
                        // while ($row = mysql_fetch_assoc($sql_select))
                        // {
                            // $id = $row["ID"];
                        // }
                    // }
//                   $sql = "UPDATE  `vendor_click_reports` SET  `user name` =  'guest shoppers', `Sales` =  '$sales' , `Commission` =  '$commision' ,  `pct` =  '$pct' ,  `pct_giveback` =  '$pct_giveback' , `np-share` =  '$np_share' , `bil-share` =  '$bil_share' WHERE  `unique id` =  '$id' LIMIT 1";
                    // $today = date("Y-m-j", time() - 86400);
                    // $sql = "UPDATE  `vendor_click_reports` SET  `Sales` =  '$sales' , `Commission` =  '$commision' ,  `pct` =  '$pct' ,  `pct_giveback` =  '$pct_giveback' , `np-share` =  '$np_share' , `bil-share` =  '$bil_share' , `last_update` = '$today' WHERE  `unique id` =  '$id' LIMIT 1";
                    // mysql_query($sql);
                    // if($orderedUnit == 0 || $orderedUnit == $shippedUnits)               
                        // markFree($tracking_id);
                // }
            }
        }
    }
    return false;
}

function TagExist($tag, $user_id, $click_date)
{
    
    /*
    $click_date = substr($click_date, 0, 10);    
    $sql = 'SELECT * FROM  `vendor_click_reports` WHERE `tracking link`= "'.$tag.'" AND  `click date`  = "'.$click_date.'"  ORDER BY  `vendor_click_reports`.`unique id` DESC LIMIT 1';    
    $sql_select= mysql_query($sql);
    if($sql_select){
        while ($row = mysql_fetch_assoc($sql_select))
        {
            return true;
        }
    }    
    return false;    */
    
    if ($user_id=='')
        $user_id=0;

    $s = "select * from giveback_amazon_invoices where date_time = '".$click_date."'
          and tracking_id = '".$tag."' and user_id='".$user_id."'" ;

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
        //$points = $values['commision'] / 2.0;
		$points = $values['commision'];

        if($values['sales'] > 0 AND $values['commision'] > 0){
            include ('giveback_invoice_email.php');
        }
    }
    return true;
}

function sendReportToSupport(){
    include("giveback_invoice_support_email.php");
}

function markFree($track_link){

    $_q = "UPDATE `amazon_tracking_links` SET  `isfree` =  '1', timestamp=".time()." WHERE  `amazon_tracking_links`.`name` = '$track_link'";
    mysql_query($_q);
}
