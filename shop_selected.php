<?
/**
 * page acts like a multiplexer, to redirect logged user to a tracked amazon link;
 *
 * page expects a 'shop_url' GET parameter containing the (urlencoded) destination Amazon URL.
 *
 * eg,  calling
 *           http://www.bringitlocal.com/shop_selected.php?shop_url=http%3a%2f%2fwww.amazon.com%2fKindle-Wireless-Reader-Wifi-Graphite%2fdp%2fB002Y27P3M%2fref%3das_li_wdgt_js_ex%3f%26camp%3d212361%26linkCode%3dwsw%26tag%3dbringitstore-20%26creative%3d391881
 *
 *      will redirect logged user to (for instance)
 *
 *           http://www.amazon.com/Kindle-Wireless-Reader-Wifi-Graphite/dp/B002Y27P3M/ref=as_li_wdgt_js_ex?&camp=212361&linkCode=wsw&tag=bringitstore15-20&creative=391881
 *
 *	and send anonymous user to
 *
 *		http://www.amazon.com/Kindle-Wireless-Reader-Wifi-Graphite/dp/B002Y27P3M/ref=as_li_wdgt_js_ex?&camp=212361&linkCode=wsw&tag=bringlocal-20&creative=391881
 *
 */
session_start();

define ('IN_SITE', 1);
$GLOBALS['body_id'] = "shop_selected";


try{
    include_once ('includes/global.php');
    include_once ('includes/class_formchecker.php');
    include_once ('includes/class_custom_field.php');
    include_once ('includes/class_user.php');
    require_once ('includes/class.shop_link.inc');

    function email_globalpartner_to_user($userid, $url)
    {
        $mail_input_id = $userid;
        include_once("globalpartner_confirm_user_notification.php");
        return false;
    }

    function popup_globalpartner_to_user($userid, $url)
    {
        //$mail_input_id = $userid;
        //include_once("globalpartner_popup_notification.php");
//    echo "<script>$(document).ready(function(){alert('hello');});</script>";
        return false;
    }

    if($session->value('user_id') || isset($_REQUEST["username"])){
        $amazon_sql="SELECT * FROM probid_user_points WHERE activity_id = 3 AND awarded_date = date(now())";
        $amazon_result = mysql_query($amazon_sql);
        $amazon_count = mysql_num_rows($amazon_result);
        if(!$amazon_count)
        {
            if($session->value('user_id')){
                $user_id = $session->value('user_id');

                $activity_sql="SELECT points_awarded FROM probid_user_activities WHERE activity_id = 3";
                $activity_result = mysql_query($activity_sql);
                $activity_row = mysql_fetch_array($activity_result);
                $points_awarded = $activity_row['points_awarded'];
                mysql_query("INSERT INTO probid_user_points (user_id, activity_id, points_awarded, awarded_date)
                                                                      VALUES ('$user_id', '3', '$points_awarded', date(now()))");

                $email_user_id = $user_id;
                $activity_id = 3;
                include('global_loyality_user_notification.php');
            }
            else if(isset($_REQUEST["username"]) && trim($_REQUEST["username"])!=''){
                $activity_sql="SELECT user_id FROM probid_users WHERE username = '{$_REQUEST["username"]}'";
                $activity_result = mysql_query($activity_sql);
                $activity_row = mysql_fetch_array($activity_result);
                $user_id = $activity_row['user_id'];

                if($user_id && $user_id!=""){
                    mysql_query("INSERT INTO probid_user_points (user_id, activity_id, points_awarded, awarded_date)
                                                                          VALUES ('$user_id', '3', '$points_awarded', date(now()))");
                    $email_user_id = $user_id;
                    $session->set('user_id',$user_id);
                    $activity_id = 3;
                    include('global_loyality_user_notification.php');
                }
            }
        }
    }


##$_URL = !empty($_GET['shop_url']) ? html_entity_decode(urldecode($_GET['shop_url'])): "";
    $_URL = !empty($_GET['shop_url']) ? html_entity_decode($_GET['shop_url']) : "";

    $pUrl = parse_url($_URL);

    if(is_array($pUrl) && isset($pUrl["host"]) && $pUrl["host"] == "gan.doubleclick.net"){
        $main_tag = "gan_".mt_rand(1,100).mt_rand(100,999).mt_rand(100,999).time();
        $_URL.="&mid=$main_tag";
    } elseif (is_array($pUrl) && isset($pUrl["host"]) && $pUrl["host"] == "www.amazon.com"){
        $main_tag="bringlocal-20";
    } else {
        $main_tag = "cj_".mt_rand(1,100).mt_rand(100,999).mt_rand(100,999).time();
        $_URL.="&SID=$main_tag";
    }


    if($session->value('user_id') AND (!isset($_REQUEST['SKIP_NP_SELECTION']) || $_REQUEST['SKIP_NP_SELECTION'] !=TRUE) )
    {
        email_globalpartner_to_user($session->value('user_id'), $_URL);
    }
    else
    {
        popup_globalpartner_to_user(null, $_URL);
    }

    if($_REQUEST['SKIP_NP_SELECTION'] == true) {
        // user already declined
        $_rewritten = AmazonTrackingURL::parseUrl($_URL,$main_tag);
        header("Location: {$_rewritten}");
    }
    else if($_REQUEST['np_selection'] == true) {
        // ask user if she/he wants to choose a quick NP...
        (string) $page_handle = 'register';

        include_once ('includes/global.php');

        include_once ('includes/class_user.php');
        include_once ('includes/class_fees.php');
        include_once ('includes/functions_login.php');

        include_once ('global_header.php');

        $template->set('refuse_url', str_replace('np_selection','SKIP_NP_SELECTION',$_SERVER['REQUEST_URI']));

        $template_output .= $template->process('searchnp.tpl.php');

        include_once ('global_footer.php');

        echo $template_output;
    }
    else if($session->value('user_id')) {
        if($_GET['report'] == true) {
            echo AmazonTrackingURL::printReport($_GET['report_id']);
        }
        else if($_GET['encode'] == true) {
            echo AmazonTrackingURL::generateSwitchingUrl('http://www.amazon.com/Kindle-Wireless-Reader-Wifi-Graphite/dp/B002Y27P3M/ref=as_li_wdgt_js_ex?&camp=212361&linkCode=wsw&tag=bringitstore15-20&creative=391881');
        }
        else {
            if(!empty($_URL)) {
                $_rewritten = AmazonTrackingURL::parseUrl($_URL,$main_tag);
                header("Location: {$_rewritten}");
            }
            else {
                header("Location: {$_URL}");
            }
        }
    }elseif(isset($_GET["fromwidget"])){        
        $_rewritten = AmazonTrackingURL::parseUrl($_URL,$main_tag);
        header("Location: {$_rewritten}");
    }
    else {

        if(!empty($_COOKIE['np_userid'])) {
            // we've got a quick NP selected...
            $_rewritten = AmazonTrackingURL::parseUrl($_URL,$main_tag);
            header("Location: {$_rewritten}");

        }
        else if(empty($_COOKIE['np_userid']) && empty($_COOKIE['SKIP_NP_SELECTION'])) {
            // anonymous user has neither selected a quick NP nor declined selection, let's go to ASKING PAGE...
            $_URL = $_SERVER['REQUEST_URI']."&np_selection=true";
            header("Location: {$_URL}");
        }
        else {
            // not selected and declined, let's go to plain URL...
            header("Location: {$_URL}");
        }
    }

}catch(Exception $e){
    print_r($e->getMessage());
}


?>
