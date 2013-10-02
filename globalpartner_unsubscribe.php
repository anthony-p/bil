<?
## File Version -> v6.04
## Email File -> registration confirmation message
## called only from the register.php page

if ( !defined('INCLUDED') ) { die("Access Denied"); }
/*
$input =  $session->value('sender');
$key = "bringitlocal firmhashkey"; // you can change it
$encrypted_data1 = md5($input . $key);
$encrypted_data = $session->value('key');


if ($encrypted_data1==$encrypted_data)
{
	global $db;
    $row_details = $db->get_sql_row("update " . DB_PREFIX . "users  set globalparner_email=0 WHERE user_id='". $input . "'"
		u.user_id='" . $mail_input_id . "'");
      echo "You have been unsubscribed successfully.";
}
  */ 
 
echo "You have been unsubscribed successfully.";

?>
