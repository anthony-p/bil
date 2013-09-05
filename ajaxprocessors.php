<?php
session_start();

define ('IN_SITE', 1);
$GLOBALS['body_id'] = "ajaxprocessors";
(string)$page_handle = 'ajaxprocessors';
include_once('includes/global.php');




if (isset($_POST['do']) && $_POST['do']!==""){


    $result = array(
        'msg'       => '',
        'data'      => '',
        'status'    => 0
    );


    switch($_POST['do']){

        case 'subscribe':

            $mailChimp = new Mailchimp($mailChimpConfig['apiKey']);

            try {

                $mailChimp->lists->subscribe($mailChimpConfig['listId'], array( 'email' => $_POST['email'] ) );

                $result = array(
                    'msg' => 'OK',
                    'data' => '',
                    'status' => 0
                );


            } catch (Mailchimp_Error $e) {


                if ($e->getMessage()) {
                    //echo '<br>' . $e->getMessage() . '<br>';
                    $result = array(
                        'msg' => $e->getMessage(),
                        'data' => '',
                        'status' => 1
                    );
                } else {
                    // unrecognized error
                    $result = array(
                        'msg' => 'unrecognized error',
                        'data' => '',
                        'status' => 1
                    );
                }
            }



            break;


    }


    // return result
    echo json_encode($result);
    exit();




}

exit();