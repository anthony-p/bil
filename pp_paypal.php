<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

session_start();

define ('IN_SITE', 1);

include_once ('includes/global.php');
include_once ('includes/class_fees.php');

(string) $active_pg = 'PayPal';
(string) $error_output = null;

$pg_enabled = $db->get_sql_field("SELECT checked FROM " . DB_PREFIX . "payment_gateways WHERE
	name='" . $active_pg . "' LIMIT 0,1", "checked");

if (!$pg_enabled) { die(GMSG_NOT_AUTHORIZED); }

$req = 'cmd=_notify-validate';



foreach ($_POST as $key => $value)
{
	$value = urlencode(stripslashes($value));
	$req .= '&' . $key . '=' . $value;
}


//$url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
$url = "https://www.paypal.com/cgi-bin/webscr";
file_put_contents('pp_paypal_out.txt', "url:".$url."  eq:".$req);
//$url = "https://www.paypal.com/cgi-bin/webscr";
$ch = curl_init();    // Starts the curl handler
curl_setopt($ch, CURLOPT_URL,$url); // Sets the paypal address for curl
curl_setopt($ch, CURLOPT_FAILONERROR, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // Returns result to a variable instead of echoing
curl_setopt($ch, CURLOPT_TIMEOUT, 3); // Sets a time limit for curl in seconds (do not set too low)
curl_setopt($ch, CURLOPT_POST, 1); // Set curl to send data using post
curl_setopt($ch, CURLOPT_POSTFIELDS, $req); // Add the request parameters to the post
$result = curl_exec($ch); // run the curl process (and return the result to $result
curl_close($ch);

$payment_status = $_POST['payment_status'];
$payment_gross = $_POST['mc_gross'];
$payment_currency = $_POST['mc_currency'];
$txn_id = $_POST['txn_id'];

list($custom, $fee_table) = explode('TBL',$_POST['custom']);

file_put_contents('pp_paypal_ver.txt', $result."  ".$custom."  ".$fee_table."  ".$active_pg."  ".$payment_gross."  ".$txn_id."  ".$payment_currency);
if (strcmp ($result, "VERIFIED") == 0) // It may seem strange but this function returns 0 if the result matches the string So you MUST check it is 0 and not just do strcmp ($result, "VERIFIED") (the if will fail as it will equate the result as false)
{
    $process_fee = new fees();
    $process_fee->setts = &$setts;
    $process_fee->callback_process($custom, $fee_table, $active_pg, $payment_gross, $txn_id, $payment_currency);
}
?>
