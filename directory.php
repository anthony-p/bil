<?php
session_start();

define ('IN_SITE', 1);

include_once ('includes/global.php');

global $db;
    $state = 'New York';
$user_info = $db->get_sql_row("SELECT id FROM
                        			proads_countries WHERE name='New York'", true);


print_r($user_info);
