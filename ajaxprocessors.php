<?php
session_start();

define ('IN_SITE', 1);
$GLOBALS['body_id'] = "ajaxprocessors";
(string)$page_handle = 'ajaxprocessors';
include_once('includes/global.php');




if (isset($_POST['do']) && $_POST['do']!==""){


    ob_start();
        print_r($_POST);
                $p_debug = ob_get_contents();
    ob_end_clean();

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


        case 'stateList':

            $tax = new tax();
            $result['data'] = $tax->states_box('state', '', intval($_POST['country_id']));

            break;

        case 'checkCampaignName':

            if (trim($_POST['name']) == '' ){
                $result = array(
                    'msg' => 'Campaign name exist',
                    'data' => '',
                    'status' => 1
                );
            } else {
                $sql_query = $db->query("SELECT * FROM np_users WHERE username='" . $db->rem_special_chars($_POST['name']) . "';");
                $row = $db->fetch_array($sql_query);

                if ($row === false) {
                    // campaign name is not exist
                    $result = array(
                        'msg' => 'OK',
                        'data' => '',
                        'status' => 0
                    );
                } else {
                    // campaign exist
                    $result = array(
                        'msg' => 'Campaign name exist',
                        'data' => '',
                        'status' => 1
                    );
                }
            }


            break;

    }



    $result['debug'] = $p_debug;
    // return result
    echo json_encode($result);
    exit();




}

exit();