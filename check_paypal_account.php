<?php
//session_start();
//
//define ('IN_SITE', 1);
//
////var_dump(32); exit;
//echo json_encode(
//    array(
//        "result" => checkPaypalAccount($_POST["pg_paypal_email"])
//    )
//);


/**
 * @param $email
 * @return mixed
 *
 *
 * function checks if an email is a valid paypal account
 * as an example can be:
 *
 *
 * $response = checkPaypalAccount('test_paypal_email');
 * if ($response == "Success") {
 *  // it means email is a valid paypal account
 * } else {
 *  // it means paypal account is not valid
 * }
 */


function checkPaypalAccount($email = '', $fname = '', $lname = '')
{
//    var_dump(1);exit;
    include_once ('paypalplatform.php');
//    var_dump(2); exit;
//    var_dump($Env);
$Env = "sandbox";
    if ($Env == "sandbox")
    {
        $url = trim('https://svcs.sandbox.paypal.com/AdaptiveAccounts/GetVerifiedStatus');
    }
    else
    {
        $url = "https://svcs.paypal.com/AdaptiveAccounts/GetVerifiedStatus";
    }

//    var_dump($Env); echo '<br />';

//turn php errors on
//ini_set('track_errors', true);

//set APAPI URL
//$url = trim('https://svcs.sandbox.paypal.com/AdaptiveAccounts/GetVerifiedStatus');
// API_UserName=support_api1.bringitlocal.com_&_API_Password=GH92ZGH3RWYLH725_&_API_Signature=AiPC9BjkCyDFQXbSkoZcgqH3hpacANnjmVMIEtNqJK4qh5vMWIe33mZj_&_API_AppID=APP-7YF493902L373612H

//PayPal API Credentials
// $API_UserName = "sbapi_1287090601_biz_api1.paypal.com"; //TODO
// $API_Password = "1287090610"; //TODO
// $API_Signature = "ANFgtzcGWolmjcm5vfrf07xVQ6B9AsoDvVryVxEQqezY85hChCfdBMvY"; //TODO
//$API_SANDBOX_EMAIL_ADDRESS = "rishaque@paypal.com"; //TODO
//$API_DEVICE_IPADDRESS = "127.0.0.1"; //TODO

$API_UserName = "support_api1.bringitlocal.com"; //TODO
$API_Password = "GH92ZGH3RWYLH725"; //TODO
$API_Signature = "AiPC9BjkCyDFQXbSkoZcgqH3hpacANnjmVMIEtNqJK4qh5vMWIe33mZj"; //TODO
$API_AppID = "APP-7YF493902L373612H";

//Default App ID for Sandbox
// $API_AppID = "APP-80W284485P519543T";

    $API_RequestFormat = "NV";//TODO
    $API_ResponseFormat = "NV";//TODO


//Create request body content
//    var_dump($_POST);

    $paypal_email = $email;
    $first_name = $fname;
    $last_name = $lname;

    if (isset($_POST['pg_paypal_email']) && $_POST['pg_paypal_email']) {
        $paypal_email = $_POST['pg_paypal_email'];
    }

    if (isset($_POST['pg_paypal_first_name']) && $_POST['pg_paypal_first_name']) {
        $first_name = $_POST['pg_paypal_first_name'];
    }

    if (isset($_POST['pg_paypal_last_name']) && $_POST['pg_paypal_last_name']) {
        $last_name = $_POST['pg_paypal_last_name'];
    }

    $body_data  =  array(
        "accountIdentifier.emailAddress" => $paypal_email,//TODO
        "requestEnvelope.errorLanguage" => "en_US",//TODO
        "matchCriteria" => "NAME",//TODO
        "firstName" => $first_name,//TODO
        "lastName" => $last_name//TODO

    );

//    var_dump($body_data); echo '<br />';



//URL encode the request body content array
    $body_data = urldecode(http_build_query($body_data, '', chr(38)));

    try
    {

        //create request and add headers
        $params = array('http' => array(
            'method' => "POST",
            'content' => $body_data,
            'header' =>  'X-PAYPAL-SECURITY-USERID: ' . $API_UserName ."\r\n" .
                'X-PAYPAL-SECURITY-PASSWORD: ' . $API_Password . "\r\n" .
                'X-PAYPAL-SECURITY-SIGNATURE: ' . $API_Signature . "\r\n" .
                'X-PAYPAL-REQUEST-DATA-FORMAT: ' . $API_RequestFormat . "\r\n" .
                'X-PAYPAL-RESPONSE-DATA-FORMAT: ' . $API_ResponseFormat . "\r\n" .
                'X-PAYPAL-APPLICATION-ID: ' . $API_AppID . "\r\n" //.
//            'X-PAYPAL-SANDBOX-EMAIL-ADDRESS: ' . $API_SANDBOX_EMAIL_ADDRESS . "\r\n" .
//            'X-PAYPAL-DEVICE-IPADDRESS: ' . $API_DEVICE_IPADDRESS . "\r\n"

        ));

//        var_dump($params);


        //create stream context
        $ctx = stream_context_create($params);

//        var_dump($url);

        //open the stream and send request
        $fp = @fopen($url, 'r', false, $ctx);

        //get response
        $response = stream_get_contents($fp);

//        echo '<pre>';
//        var_dump($response);
//        echo '</pre>';

        //check to see if stream is open
//        if ($response === false) {
//            throw new Exception("php error message = " . "$php_errormsg");
//        }
//        echo $response;
        //close the stream
        fclose($fp);

//        return $response;

        $keyArray = explode("&", $response);

        foreach ($keyArray as $rVal){
            list($qKey, $qVal) = explode ("=", $rVal);
            $kArray[$qKey] = $qVal;
        }
        
        return $kArray["accountStatus"];






    }
    catch(Exception $e)
    {
        echo 'Message: ||' .$e->getMessage().'||';
    }
}

?>