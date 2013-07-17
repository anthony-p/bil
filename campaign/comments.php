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

    header("Location: ".$_SERVER["HTTP_REFERER"]);

} else
    header ("Location: /");