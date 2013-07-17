<?php
/**
 * Created by Lilian Codreanu.
 * User: Lilian Codreanu
 * Date: 12/10/12
 * Time: 11:48 PM
 */


include_once ('../includes/global.php');

for($i = 3; $i <= 200; $i++){
    $sql = "INSERT INTO  `amazon_tracking_links` (`id` ,`name` ,`isfree` ,`timestamp`)VALUES (NULL ,  'bringlocal$i-20',  'true',  '00000000')";
    mysql_query($sql);
}