<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################


session_start();

define ('IN_ADMIN', 1);

include_once ('../includes/global.php');

include_once ('../includes/class_formchecker.php');
include_once ('../includes/class_custom_field.php');
include_once ('../includes/class_item.php');

/**
 * @param $options_array
 * @return array
 */
function explodeAccessData($options_array)
{
    if (is_array($options_array) && isset($options_array["access_data"])) {
        $string_to_explode = $options_array["access_data"];
        $exploded_by_ampersand = explode('_&_', $string_to_explode);
        foreach ($exploded_by_ampersand as $item) {
            $exploded_item = explode('=', $item);
            $options_array[$exploded_item[0]] = $exploded_item[1];
        }
    }

    return $options_array;
}

if ($session->value('adminarea')!='Active')
{
    header_redirect('login.php');
}
else
{
    include_once ('header.php');

    $item = new item();
    $item->setts = &$setts;

    $paypal_options = array();

    $paypal_options["live"] = $db->get_sql_row("SELECT * FROM payment_option_details WHERE payment_method='paypal' AND payment_environment='live'");
    $paypal_options["live"] = explodeAccessData($paypal_options["live"]);
    $paypal_options["sandbox"] = $db->get_sql_row("SELECT * FROM payment_option_details WHERE payment_method='paypal' AND payment_environment='sandbox'");
    $paypal_options["sandbox"] = explodeAccessData($paypal_options["sandbox"]);
    $paypal_options["current"] = $db->get_sql_row("SELECT * FROM payment_option_details WHERE payment_method='paypal' AND active=1");
    $paypal_options["current"] = explodeAccessData($paypal_options["current"]);

    $msg_changes_saved = '<p align="center" class="contentfont">' . AMSG_CHANGES_SAVED . '</p>';

    if (isset($_POST['form_save_settings']))
    {
        $post_details = $db->rem_special_chars_array($_POST);


        switch ($_REQUEST['page'])
        {
            case 'paypal':
                $update_status_query = "UPDATE payment_option_details SET active=0 WHERE payment_method='paypal'";
                $db->query($update_status_query);

                $select_query = "SELECT * FROM payment_option_details WHERE payment_method='" .
                    $_POST["payment_method"] .
                    "' AND payment_environment='" . $_POST["payment_environment"] . "'";
                $select_result = $db->get_sql_row(
                    $select_query
                );

                $access_data = 'API_UserName=' . $_POST["API_UserName"] .
                    '_&_API_Password=' . $_POST["API_Password"] .
                    '_&_API_Signature=' . $_POST["API_Signature"] .
                    '_&_API_AppID=' . $_POST["API_AppID"];

                if (!$select_result) {
                    $insert_query = "INSERT INTO payment_option_details(payment_method, access_data,
                        payment_account, payment_environment, rate_of_pay, active)
                        VALUES('" .
                        $_POST["payment_method"] . "', '" .
                        $access_data . "', '" . $_POST["payment_account"] . "', '" .
                        $_POST["payment_environment"] . "', " .
                        (int)$_POST["rate_of_pay"] . ", 1)";

                    $insert_result = $db->query($insert_query);
                } else {
                    $update_query = "UPDATE payment_option_details SET active=1, access_data='" .
                        $access_data . "', payment_account='" . $_POST["payment_account"] .
                        "', payment_environment='" . $_POST["payment_environment"] .
                        "', rate_of_pay=" . $_POST["rate_of_pay"] .
                        " WHERE payment_method='paypal' AND payment_environment='" .
                        $_POST["payment_environment"] . "'";
                    $db->query($update_query);
                }
                header("Location: payment_option_details.php?page=paypal");
                break;
        }
        $template->set('msg_changes_saved', $msg_changes_saved);
    }

    (string) $header_section = null;
    (string) $subpage_title = null;

    $setts_tmp = $db->get_sql_row("SELECT * FROM " . DB_PREFIX . "gen_setts");
    $layout_tmp  = $db->get_sql_row("SELECT * FROM " . DB_PREFIX . "layout_setts");

    $template->set('paypal_options', $paypal_options);
    $template->set('paypal_options_json', json_encode($paypal_options));
    $template->set('page', $_REQUEST['page']);

    if ($_REQUEST['page'] == 'payment_option_details')
    {
        $header_section = AMSG_GENERAL_SETTINGS;
        $subpage_title = AMSG_PAYMENT_OPTION_DETAILS;
    }

    $template->set('header_section', $header_section);
    $template->set('subpage_title', $subpage_title);

    $template_output .= $template->process('payment_option_details.tpl.php');

    include_once ('footer.php');

    echo $template_output;
}
?>
