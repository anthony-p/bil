<?

session_start();

define ('IN_ADMIN', 1);

include_once ('../includes/global.php');
include_once ('../includes/class_formchecker.php');

if ($session->value('adminarea')!='Active')
{
	header_redirect('login.php');
}
else
{
	if (!isset($_GET["user_id"]) || !$_GET["user_id"] || !is_numeric($_GET["user_id"])) {
        include_once ('header.php');

        (string) $management_box = NULL;

        $msg_changes_saved = '<p align="center" class="contentfont">' . AMSG_CHANGES_SAVED . '</p>';

        $sql_select_users = $db->query("SELECT id, first_name, last_name, organization FROM bl2_users");

        $users = array();
        while ($user = $db->fetch_array($sql_select_users)){
            $users[] = $user;
        }
        $template->set('users', $users);

        $np_users_content = '';
        $template->set('np_users_content', $np_users_content);


        $template->set('header_section', AMSG_DISABLE_CAMPAIGNS);
        $template->set('subpage_title', 'Campaigns');

        $template_output .= $template->process('campaigns.tpl.php');

        include_once ('footer.php');

        echo $template_output;
    } else {
        if (!isset($_GET["campaign_id"]) || !$_GET["campaign_id"] || !is_numeric($_GET["campaign_id"])) {
            $user_id = $_GET["user_id"];
            $campaign_type_query = '';
            $all_selected = $draft_selected = $live_selected = $closed_selected = '';

            if (isset($_GET["campaign_type"]) && $_GET["campaign_type"]) {
                switch ($_GET["campaign_type"]) {
                    case "draft" : $active = 0;
                        $campaign_type_query = " AND active=0 ";
                        $draft_selected = 'selected';
                        break;
                    case "live" : $active = 1;
                        $campaign_type_query = " AND active=1 ";
                        $live_selected = 'selected';
                        break;
                    case "closed" : $active = 2;
                        $campaign_type_query = " AND active=2 ";
                        $closed_selected = 'selected';
                        break;
                    default:
                        $all_selected = 'selected';
                }
            }

            $campaigns_select_query = $db->query(
                "SELECT SQL_CALC_FOUND_ROWS user_id, username, email, reg_date, end_date, project_title, active, logo, banner, disabled " .
                    " FROM np_users WHERE probid_user_id=" . $user_id . $campaign_type_query
            );
//        $total_number_of_rows = $db->get_sql_row("SELECT FOUND_ROWS()");

            $counter = 0;
            $np_users_content = '<tr><td></td><td></td><td></td><td></td><td><select name="campaign_type" id="campaign_type">' .
                '<option value="all" ' . $all_selected . '>all</option>' .
                '<option value="draft" ' . $draft_selected . '>draft</option>' .
                '<option value="live" ' . $live_selected . '>live</option>' .
                '<option value="closed" ' . $closed_selected . '>closed</option>' .
                '</select></td><td></td></tr>';
            while ($campaigns_query_result =  mysql_fetch_array($campaigns_select_query)) {
//            $campaigns[] = $campaigns_query_result;
                $background = ($counter++%2) ? 'c1' : 'c2';
                $disable = $campaigns_query_result['disabled'] ? 'Enable' : 'Disable';
                switch ($campaigns_query_result['active']) {
                    case 0: $status = 'draft';
                        break;
                    case 1: $status = 'live';
                        break;
                    case 2: $status = 'closed';
                        break;
                    default: $status = '';
                }

//                if ($campaigns_query_result["end_date"] < time()) {
//                    $status = 'closed';
//                }

//            var_dump($campaigns_query_result['user_id']);

                $np_users_content .= '<tr class="' . $background . '"> '.
                    '	<td>' . $campaigns_query_result['user_id'] . '</td> '.
                    '	<td>' . $campaigns_query_result['project_title'] . '</td> '.
                    '	<td>' . format_date($campaigns_query_result['reg_date']) . '</td> '.
                    '	<td>' . format_date($campaigns_query_result['end_date']) . '</td> '.
                    '	<td>' . $status . '</td> '.
                    '	<td><a id="' . $campaigns_query_result['user_id'] .
                    '" class="disable_campaign" href="javascript:void(0)">' .
                    $disable . '</a></td> '.
                    '	<input type="hidden" id="campaign_' . $campaigns_query_result['user_id'] .
                    '" value="' . $campaigns_query_result['disabled'] . '"/> '.
                    '</tr> ';
            }

//        var_dump($np_users_content); exit;

            echo json_encode(array("np_users_content" => $np_users_content));
        } else {
            $campaign_id = $_GET["campaign_id"];

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

                $np_user_content = $db->get_sql_row(
                    "SELECT user_id, username, email, reg_date, project_title, logo, banner, disabled " .
                        " FROM np_users WHERE user_id=" . $campaign_id
                );

                $data = array(
                    "np_user_content" => $np_user_content,
                );
                echo json_encode($data);
            }
        }
    }
}

function format_date($timestamp)
{
    return date("Y-m-d H:i:s", $timestamp);
}

?>
