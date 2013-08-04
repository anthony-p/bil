<?php
if ( !defined('INCLUDED') ) { die("Access Denied"); }


//$comments =  $db->get_sql_row("SELECT * FROM  `project_comment` WHERE project_id =$compaignId");
$comments_query =  $db->query("SELECT project_comment.*, bl2_users.id, bl2_users.first_name, bl2_users.last_name FROM `project_comment` LEFT JOIN bl2_users ON project_comment.`user_id` = bl2_users.id WHERE project_id = $compaignId");
$comments = array();

while ($result =  mysql_fetch_array($comments_query)) {
    $comments[] = $result;
}

?>