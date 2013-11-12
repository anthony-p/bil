<?php

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

    include_once ('paypalplatform.php');

    if ($Env == "sandbox")
    {
        $url = trim('https://svcs.sandbox.paypal.com/AdaptiveAccounts/GetVerifiedStatus');
    }
    else
    {
        $url = "https://svcs.paypal.com/AdaptiveAccounts/GetVerifiedStatus";
    }

    $API_RequestFormat = "NV";//TODO
    $API_ResponseFormat = "NV";//TODO

    //Create request body content
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

        //create stream context
        $ctx = stream_context_create($params);

        //open the stream and send request
        $fp = @fopen($url, 'r', false, $ctx);

        //get response
        $response = stream_get_contents($fp);

        //close the stream
        fclose($fp);

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