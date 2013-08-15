<?php

//-------------------------------------------------
// When you integrate this code
// look for TODO as an indication
// that you may need to provide a value or take action
// before executing this code
//-------------------------------------------------

include_once ('includes/global.php');
session_start();

$user_details = $session->value('user_id') ? $session->value('user_id') : null;;


if (!isset($_POST["amount"]))
    header('location: http://dev2.bringitlocal.com/');

require_once ("paypalplatform.php");

$amount = 0;

if (isset($_POST["amount"]) && !empty($_POST["amount"])) {
    $amount = $_POST["amount"];
}

//$rate = (double)5 / 100;
$rate = (double)$result["rate_of_pay"] / 100;

$bring_it_local_amount = $rate * $amount;
$beneficiar_amount = $amount;
//$beneficiar_amount = $amount - $bring_it_local_amount;

$_SESSION["transferred_amount"] = $beneficiar_amount;;

//$bring_it_local_account = 'rlpc.test1@gmail.com';
$bring_it_local_account = $result["payment_account"];

$user_id = $_COOKIE["np_userid"];
$_SESSION["np_userid"] = $user_id;



if (isset($_POST["np_user_id"]) && !empty($_POST["np_user_id"])) {
    $user_id = $_POST["np_user_id"];
}


$email = '';

$email = $db->get_sql_field("SELECT np_users.pg_paypal_email FROM np_users, bl2_users WHERE np_users.user_id=" .
    $user_id . " AND np_users.probid_user_id=bl2_users.id", "pg_paypal_email");


if (!$email) {
    $email = $db->get_sql_field("SELECT bl2_users.pg_paypal_email FROM np_users, bl2_users WHERE np_users.user_id=" .
        $user_id . " AND np_users.probid_user_id=bl2_users.id", "pg_paypal_email");
}

$beneficiar_account = $email ? $email : 'rlpc.test2@gmail.com';

if ($beneficiar_amount >= $bring_it_local_amount) {
    $beneficiar_primary = true;
    $bring_it_local_primary = false;
} else {
    $beneficiar_primary = false;
    $bring_it_local_primary = true;
}


// ==================================
// PayPal Platform Chained Payment Module
// ==================================

// Request specific required fields
$actionType			= "PAY";
$cancelUrl			= "http://dev2.bringitlocal.com/donate_cancel.php";	// TODO - If you are not executing the Pay call for a preapproval,
//        then you must set a valid cancelUrl for the web approval flow
//        that immediately follows this Pay call
$returnUrl			= "http://dev2.bringitlocal.com/donate_success.php";	// TODO - If you are not executing the Pay call for a preapproval,
//        then you must set a valid returnUrl for the web approval flow
//        that immediately follows this Pay call
$currencyCode		= "USD";

// A chained payment can be made with 1 primary and between 1 and 5 secondary
// TODO - specify the receiver emails
//        remove or set to an empty string the array entries for receivers that you do not have
$receiverEmailArray	= array(
    $bring_it_local_account,
    $beneficiar_account
);

// TODO - specify the receiver amounts as the amount of money, for example, '5' or '5.55'
//        remove or set to an empty string the array entries for receivers that you do not have
$receiverAmountArray = array(
    $bring_it_local_amount,
    $beneficiar_amount
);

// TODO - Set ONLY 1 receiver in the array to 'true' as the primary receiver, and set the
//        other receivers corresponding to those indicated in receiverEmailArray to 'false'
//        make sure that you do NOT specify more values in this array than in the receiverEmailArray
$receiverPrimaryArray = array(
    $bring_it_local_primary,
    $beneficiar_primary
);

// TODO - Set invoiceId to uniquely identify the transaction associated with each receiver
//        set the array entries with value for receivers that you have
//		  each of the array values must be unique across all Pay calls made by the caller's API credentials
$receiverInvoiceIdArray = array(
    '',
    '',
    '',
    '',
    '',
    ''
);

// Request specific optional fields
//   Provide a value for each field that you want to include in the request, if left as an empty string the field will not be passed in the request
$senderEmail					= "";		// TODO - If you are executing the Pay call against a preapprovalKey, you should set senderEmail
//        It is not required if the web approval flow immediately follows this Pay call
$feesPayer						= "";
$ipnNotificationUrl				= "";
$memo							= "";		// maxlength is 1000 characters
$pin							= "";		// TODO - If you are executing the Pay call against an existing preapproval
//        the requires a pin, then you must set this
$preapprovalKey					= "";		// TODO - If you are executing the Pay call against an existing preapproval, set the preapprovalKey here
$reverseAllParallelPaymentsOnError	= "";	// TODO - Do not specify for chained payment
$trackingId						= generateTrackingID();	// generateTrackingID function is found in paypalplatform.php

//-------------------------------------------------
// Make the Pay API call
//
// The CallPay function is defined in the paypalplatform.php file,
// which is included at the top of this file.
//-------------------------------------------------
$resArray = CallPay ($actionType, $cancelUrl, $returnUrl, $currencyCode, $receiverEmailArray,
    $receiverAmountArray, $receiverPrimaryArray, $receiverInvoiceIdArray,
    $feesPayer, $ipnNotificationUrl, $memo, $pin, $preapprovalKey,
    $reverseAllParallelPaymentsOnError, $senderEmail, $trackingId
);

$ack = strtoupper($resArray["responseEnvelope.ack"]);
if($ack=="SUCCESS")
{
    if ("" == $preapprovalKey)
    {
        // redirect for web approval flow
        $cmd = "cmd=_ap-payment&paykey=" . urldecode($resArray["payKey"]);
        RedirectToPayPal ( $cmd );
    }
    else
    {
        // the Pay API call was made for an existing preapproval agreement so no approval flow follows
        // payKey is the key that you can use to identify the result from this Pay call
        $payKey = urldecode($resArray["payKey"]);
        // paymentExecStatus is the status of the payment
        $paymentExecStatus = urldecode($resArray["paymentExecStatus"]);
        // note that in order to get the exact status of the transactions resulting from
        // a Pay API call you should make the PaymentDetails API call for the payKey
    }
}
else
{
    //Display a user friendly Error on the page using any of the following error information returned by PayPal
    //TODO - There can be more than 1 error, so check for "error(1).errorId", then "error(2).errorId", and so on until you find no more errors.
    $ErrorCode = urldecode($resArray["error(0).errorId"]);
    $ErrorMsg = urldecode($resArray["error(0).message"]);
    $ErrorDomain = urldecode($resArray["error(0).domain"]);
    $ErrorSeverity = urldecode($resArray["error(0).severity"]);
    $ErrorCategory = urldecode($resArray["error(0).category"]);

    echo "Pay API call failed. ";
    echo "Detailed Error Message: " . $ErrorMsg;
    echo "Error Code: " . $ErrorCode;
    echo "Error Severity: " . $ErrorSeverity;
    echo "Error Domain: " . $ErrorDomain;
    echo "Error Category: " . $ErrorCategory;
}

?>