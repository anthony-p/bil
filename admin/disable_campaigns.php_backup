<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

session_start();

define ('IN_ADMIN', 1);
define ('IN_SITE', 1);

include_once ('../includes/global.php');
include_once ('../includes/class_formchecker.php');
include_once ('../includes/class_custom_field.php');
include_once ('../includes/class_user.php');
include_once ('../includes/class_fees.php');
include_once ('../includes/class_item.php');
include_once ('../includes/functions_item.php');
include_once ('../includes/functions_login.php');

if ($session->value('adminarea')!='Active')
{
	header_redirect('login.php');
}
else
{
	include_once ('header.php');

    $users = $campaigns = array();
    $campaign_types = array("draft", "live", "closed");

    $user_id = isset($_GET["user_id"]) ? $_GET["user_id"] : '';
    $campaign_type = isset($_GET["campaign_type"]) ? $_GET["campaign_type"] : '';
    $campaign_id = isset($_GET["campaign_id"]) ? $_GET["campaign_id"] : '';

    if (!$user_id) {
        $users_select_query = $db->query("SELECT id, first_name, last_name, organization, email FROM bl2_users");
        while ($users_query_result =  mysql_fetch_array($users_select_query)) {
            $users[] = $users_query_result;
        }

        $template->set('users', $users);
        $template->set('campaigns', $campaigns);

        $template_output .= $template->process('disable_campaigns.tpl.php');

        include_once ('footer.php');

        echo $template_output;
//        echo '<pre>';
//        var_dump(count($users));
//        var_dump($users);
//        echo '</pre>';
//        exit;
    } else {
        if (!$campaign_type) {
            $data = array(
                "user_id" => $user_id,
                "campaign_types" => $campaign_types,
            );
            echo json_encode($data);
//        var_dump($user_id); exit;
        } else {
            if (!$campaign_id) {
                switch ($campaign_type) {
                    case "draft" : $active = 0;
                        break;
                    case "live" : $active = 1;
                        break;
                    case "closed" : $active = 2;
                        break;
                    default : $active = 1;
                }

                $page = isset($_GET["page"]) ? $_GET["page"] : 1;
                $limit = " LIMIT " . ($page - 1) . ", 10";

                $campaigns_select_query = $db->query(
                    "SELECT SQL_CALC_FOUND_ROWS user_id, username, email, reg_date, project_title, logo, banner, disabled " .
                        " FROM np_users WHERE probid_user_id=" . $user_id . " AND active=" . $active . $limit
                );
                $total_number_of_rows = $db->get_sql_row("SELECT FOUND_ROWS()");

                while ($campaigns_query_result =  mysql_fetch_array($campaigns_select_query)) {
                    $campaigns[] = $campaigns_query_result;
                }

                $pages = ($total_number_of_rows["FOUND_ROWS()"] % 10) ?
                    (int)(($total_number_of_rows["FOUND_ROWS()"] / 10) + 1) :
                    (int)($total_number_of_rows["FOUND_ROWS()"] / 10);

                $data = array(
                    "user_id" => $user_id,
                    "campaign_type" => $campaign_type,
                    "campaign_types" => $campaign_types,
                    "campaigns" => $campaigns,
                    "pages" => $pages,
                    "current_page" => $page,
                );
                echo json_encode($data);
            } else {
                if (isset($_GET["disabled"])) {
                    if ($_GET["disabled"]) {
                        $disabled = 0;
                    } else {
                        $disabled = 1;
                    }

                    $db->query(
                        "UPDATE np_users SET disabled=" . $disabled .
                            " WHERE user_id=" . $campaign_id
                    );

                    $campaign = $db->get_sql_row(
                        "SELECT user_id, username, email, reg_date, project_title, logo, banner, disabled " .
                            " FROM np_users WHERE user_id=" . $campaign_id
                    );

                    $data = array(
                        "user_id" => $user_id,
                        "campaign_type" => $campaign_type,
                        "campaign_id" => $campaign_id,
                        "campaign_types" => $campaign_types,
                        "campaign" => $campaign,
                    );
                    echo json_encode($data);
                }
            }
        }
    }


//    $template->set('users', $users);
//    $template->set('campaigns', $campaigns);
//
//    $template_output .= $template->process('disable_campaigns.tpl.php');
//
//	include_once ('footer.php');
//
//	echo $template_output;
}
?>
