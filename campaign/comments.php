<?php
/**
 * Created by Lilian Codreanu.
 * User: Lilian Codreanu
 * Date: 5/25/13
 * Time: 1:28 PM
 */

if (count($_POST) > 0) {
    include_once ('../includes/global.php');
    include_once ('../includes/class_database.php');
    $user_id = 0;
    if (!isset($_POST["keep_private"]) && isset($_SESSION["probid_user_id"])) {
        $user_id = $_SESSION["probid_user_id"];
    }

    $comment = mysql_real_escape_string($_POST["comment_text"]);
    $compaignID = (int)1*mysql_real_escape_string($_POST['compaign']);

    $link = mysql_connect($db_host, $db_username, $db_password);
    mysql_select_db($db_name, $link);
    $sql = "INSERT INTO  `project_comment` (`id`, `user_id`, `project_id`, `parrent_id`, `comment`, `create_at` ) VALUES (NULL, '$user_id', '$compaignID', '', '$comment', '".time()."')";
    $result_user = mysql_query($sql, $link);


    // send update to owner

    include_once($fileExtension . 'language/' . DEFAULT_DB_LANGUAGE . '/site.lang.php');

    $sql = "SELECT * FROM np_users  WHERE user_id = '".intval($compaignID)."';";
    $sql_res = mysql_query($sql, $link);
    $row = mysql_fetch_array($sql_res);

    $sql = "SELECT * FROM bl2_users WHERE id = '".intval($row['probid_user_id'])."';";
    $sql_res = mysql_query($sql, $link);
    $userinfo = mysql_fetch_array($sql_res);

    $camp_url = $_SERVER["HTTP_REFERER"];

    include('../language/' . $setts['site_lang'] . '/mails/campaign_comment_owner_notification.php');


//    echo "<br><pre>"; print_r($row); echo "</pre><br>";
//    echo "<br><pre>";
//    print_r(MSG_REWARD_EMAIL);
//    echo "</pre><br>";



    header("Location: ".$_SERVER["HTTP_REFERER"]);

} else
    header ("Location: /");