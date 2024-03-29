<?php
/**
 * Created by JetBrains PhpStorm.
 * User: radu
 * Date: 6/20/13
 * Time: 10:24 PM
 */

include_once ('global.php');
include_once ('np/includes/npclass_formchecker.php');
include_once('generate_image_thumbnail.php');
global $db;

$tax = new tax();

if ($_REQUEST['operation'] == 'submit') {
    $campaign_id = mysql_real_escape_string($_POST["user_id"]);
} else {
    $campaign_id = mysql_real_escape_string($_GET["campaign_id"]);
}

$mysql_select_query = "SELECT * FROM np_users WHERE user_id=" . $campaign_id;
$campaign = $db->get_sql_row($mysql_select_query);


$categories_query_result = $db->query("SELECT * FROM np_orgtype");
$categories = array();
while ($query_result =  mysql_fetch_array($categories_query_result)) {
    $categories[] = $query_result;
}

$countries_query_result = $db->query("SELECT * FROM proads_countries");
$countries = array();
while ($query_result =  mysql_fetch_array($countries_query_result)) {
    $countries[] = $query_result;
}

$pitches_query_result = $db->query("SELECT * FROM project_pitch WHERE project_id=" . $campaign_id);
$pitches = array();
while ($query_result =  mysql_fetch_array($pitches_query_result)) {
    $pitches[] = $query_result;
}

$project_update_query_result = $db->query("SELECT * FROM project_updates WHERE project_id=" . $campaign_id . " ORDER BY id DESC");
$project_updates = array();
while ($query_result =  mysql_fetch_array($project_update_query_result)) {
    $project_updates[] = $query_result;
}

$project_reward_query_result = $db->query("SELECT * FROM project_rewards WHERE project_id=" . $campaign_id . " ORDER BY id DESC");
$project_rewards = array();
while ($query_result =  mysql_fetch_array($project_reward_query_result)) {
    $project_rewards[] = $query_result;
}

$post_country = ($campaign['country']) ? $campaign['country'] : $db->get_sql_field("SELECT c.id FROM " . DB_PREFIX . "countries c WHERE
				c.parent_id=0 ORDER BY c.country_order ASC, c.name ASC LIMIT 1", 'id');

$template->set('country_dropdown', $tax->countries_dropdown('country', $post_country, 'registration_form'));

$template->set("project_country",getProjectCategoryListToHTML());
$template->set('state_box', $tax->states_box('state', $campaign['state'], $post_country));


$form_submitted = FALSE;

if ($_REQUEST['operation'] == 'submit') {

    $post_country = ($_POST['country']) ? $_POST['country'] : $db->get_sql_field("SELECT c.id FROM " . DB_PREFIX . "countries c WHERE
				c.parent_id=0 ORDER BY c.country_order ASC, c.name ASC LIMIT 1", 'id');

    $template->set('country_dropdown', $tax->countries_dropdown('country', $post_country, 'registration_form'));

    $template->set("project_country",getProjectCategoryListToHTML());

    $template->set('state_box', $tax->states_box('state', $_POST['state'], $post_country));

    $fv = new formchecker;

    $allowed_image_mime_types = array(

        'image/gif',

        'image/jpeg',

        'image/pjpeg',

        'image/png',

        'image/tiff',

        'image/svg+xml'

    );

    define ('FRMCHK_USER', 1);

    (bool) $frmchk_user_edit = 0;

    $frmchk_details = $_POST;

    if (isset ($_FILES["logo"]) && is_uploaded_file($_FILES["logo"]["tmp_name"])) {

        $frmchk_details["logo"]  =array();

        $frmchk_details["logo"]["type"] = $_FILES["logo"]["type"];

        if (in_array($_FILES["logo"]["type"], $allowed_image_mime_types)) {

            $logo_image_size = getimagesize($_FILES["logo"]["tmp_name"]);

            $frmchk_details["logo"]["dimensions"] = array(

                "width" => $logo_image_size[0],

                "max_width" => 160,

                "height" => $logo_image_size[1],

                "max_height" => 160,

                "error_message" => "The dimension of the logo must be 160x160px"

            );
        }
    }

    if (isset ($_FILES["banner"]) && is_uploaded_file($_FILES["banner"]["tmp_name"])) {

        $frmchk_details["banner"]  =array();

        $frmchk_details["banner"]["type"] = $_FILES["banner"]["type"];

        if (in_array($_FILES["logo"]["type"], $allowed_image_mime_types)) {

            $logo_image_size = getimagesize($_FILES["banner"]["tmp_name"]);

            $frmchk_details["banner"]["dimensions"] = array(

                "width" => $logo_image_size[0],

                "max_width" => 600,

                "height" => $logo_image_size[1],

                "max_height" => 400,

                "error_message" => "The dimension of the banner must be 600x400px"
            );
        }
    }


    include ('includes/npprocedure_frmchk_edit_campaign.php');

    $banned_output = check_banned($_POST['email'], 2);

    if ($banned_output['result'])
    {
        $template->set('banned_email_output', $banned_output['display']);
    }
    else if ($fv->is_error())
    {

        $template->set('display_formcheck_errors', $fv->display_errors());

        $template->set('campaign', $campaign);

        $template->set('categories', $categories);

        $template->set('countries', $countries);

        $template->set('pitches', $pitches);

        $template->set('project_updates', $project_updates);

        $template->set('project_rewards', $project_rewards);

        $members_area_page_content = $template->process('members_area_campaigns_edit.tpl.php');

        $template->set('members_area_page_content', $members_area_page_content);
    }
    else
    {

        $form_submitted = TRUE;## PHP Pro Bid v6.00 atm we wont create any emails either until we decide how many ways of registration we have.

        (string) $register_success_message = null;

        $_POST["end_date"] = 0;

        if (isset($_POST["deadline_type_value"]) && $_POST["deadline_type_value"]) {

            if ($_POST["deadline_type_value"] == "time_period")

                $_POST["end_date"] = time() + ($_POST["time_period"] * 86400);

            elseif ($_POST["deadline_type_value"] == "certain_date")

                $_POST["end_date"] = strtotime($_POST["certain_date"]);
        }


        $mysql_update_query = "UPDATE np_users SET
        project_category='" . $_POST["project_category"] . "',
        campaign_basic='" . $_POST["campaign_basic"] . "',
        project_title='" . $_POST["project_title"] . "',
        description='" . $_POST["project_short_description"] . "',
        founddrasing_goal='" . $_POST["founddrasing_goal"] . "',
        funding_type='" . $_POST["funding_type"] . "',
        active='" . $_POST["active"] . "',
        deadline_type_value='" . $_POST["deadline_type_value"] . "',
        time_period='" . $_POST["time_period"] . "',
        certain_date='" . $_POST["certain_date"] . "',
        end_date='" . $_POST["end_date"] . "',
        url='" . $_POST["url"] . "',
        facebook_url='" . $_POST["facebook_url"] . "',
        twitter_url='" . $_POST["twitter_url"] . "',
        name='" . $_POST["name"] . "',
        tax_company_name='" . $_POST["tax_company_name"] . "',
        address='" . $_POST["address"] . "',
        city='" . $_POST["city"] . "',
        zip_code='" . $_POST["zip_code"] . "',
        country='" . $_POST["country"] . "',
        state='" . $_POST["state"] . "',
        phone='" . $_POST["phone"] . "',
        orgtype='" . $_POST["orgtype"] . "',
        pg_paypal_email='" . $_POST["pg_paypal_email"] . "',
        pg_paypal_first_name='" . $_POST["pg_paypal_first_name"] . "',
        pg_paypal_last_name='" . $_POST["pg_paypal_last_name"] . "',
        pitch_text='" . $_POST["pitch_text"] . "',
		include_clickthrough='" . $_POST["include_clickthrough"]."'";

        if (isset($_POST["username"]) && $_POST["username"]) {
            $mysql_update_query .= ", username='" . $_POST["username"] . "'";
        }


        if (!file_exists('../uplimg/partner_logos/')) {
            mkdir('../uplimg/partner_logos/', 0777);
        }

        if (!file_exists('../uplimg/partner_logos/temp/')) {
            mkdir('../uplimg/partner_logos/temp/', 0777);
        }


        if (isset ($_FILES["logo"]) && is_uploaded_file($_FILES["logo"]["tmp_name"])) {
            $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);

            $logo_file_name = '/uplimg/partner_logos/' . md5($_POST["name"] . 'logo') . '.' . $ext;

            array_map(
                'unlink',
                glob('../uplimg/partner_logos/' . md5($_POST["name"] . 'logo') . '*')
            );

            $upload_logo = generate_image_thumbnail(

                $_FILES["logo"]["tmp_name"], trim($logo_file_name, '/'), 160, 160

            );

            $mysql_update_query .= ", logo='" . $logo_file_name . "'";

        }



        if (isset ($_FILES["banner"]) && is_uploaded_file($_FILES["banner"]["tmp_name"])) {
            $ext = pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION);

            $banner_file_name = '/uplimg/partner_logos/' . md5($_POST["name"] . 'banner') . '.' . $ext;

            array_map(
                'unlink',
                glob('../uplimg/partner_logos/' . md5($_POST["name"] . 'banner') . '*')
            );

            $upload_logo = generate_image_thumbnail(

                $_FILES["banner"]["tmp_name"], trim($banner_file_name, '/'), 600, 400

            );
            $mysql_update_query .= ", banner='" . $banner_file_name . "'";

        } elseif (isset ($_POST["video_url"]) && !empty($_POST["video_url"])) {

            $mysql_update_query .= ", banner='" . $_POST["video_url"] . "'";

        }



        $_POST["probid_user_id"] =

            (isset($_SESSION["probid_user_id"]) && !empty($_SESSION["probid_user_id"])) ?

                $_SESSION["probid_user_id"] : 0;



        $mysql_update_query .= " WHERE user_id='" . $_POST["user_id"] . "'";

        $result = $db->query($mysql_update_query);

        if (isset($_POST["pitch_amoun"])) {

            $pitch_data = array();

            $pitches_to_update = array();

            $pitches_to_insert = array();

            $pitches_to_delete = array();

            foreach ($_POST["pitch_amoun"] as $index => $value) {

                if (isset($_POST["pitch_id"][$index]) && $_POST["pitch_id"][$index]) {

                    $pitches_to_update[$index]["id"] = $_POST["pitch_id"][$index];

                    $pitches_to_update[$index]["project_id"] = $_POST["user_id"];

                    $pitches_to_update[$index]["amoun"] = $value;

                    $pitches_to_update[$index]["name"] = $_POST["pitch_name"][$index];

                    $pitches_to_update[$index]["description"] = $_POST["pitch_description"][$index];

                    foreach ($pitches as $key => $pitch) {

                        if ($pitch["id"] == $_POST["pitch_id"][$index]) {

                            unset($pitches[$key]);

                        }

                    }

                } else {

                    $pitches_to_insert[$index]["project_id"] = $_POST["user_id"];

                    $pitches_to_insert[$index]["amoun"] = $value;

                    $pitches_to_insert[$index]["name"] = $_POST["pitch_name"][$index];

                    $pitches_to_insert[$index]["description"] = $_POST["pitch_description"][$index];

                }

            }

            if (count($pitches_to_insert) > 0) {

                $insert_query = "INSERT INTO project_pitch(project_id, amoun, name, description) VALUES";
                $i = 0;

                foreach ($pitches_to_insert as $_pitch_details) {

                    if ($i > 0)

                        $insert_query .= ',';

                    $insert_query .= "(" .

                        $_pitch_details["project_id"] . ", " .

                        $_pitch_details["amoun"] . ", '" .

                        $_pitch_details["name"] . "', '" .

                        $_pitch_details["description"] .

                        "')";

                    $i++;

                }


                $sql_insert_pitch = $db->query($insert_query);

            }


            foreach ($pitches_to_update as $pitch_to_update) {

                $pitch_update_query = "UPDATE project_pitch SET

                project_id='" . $pitch_to_update["project_id"] . "',

                amoun='" . $pitch_to_update["amoun"] . "',

                name='" . $pitch_to_update["name"] . "',

                description='" . $pitch_to_update["description"] . "'

                WHERE id=" . $pitch_to_update["id"];

                $db->query($pitch_update_query);

            }

        }



        if (count($pitches) > 0) {

            $pitch_delete_query = "DELETE FROM project_pitch WHERE id IN (";

            foreach ($pitches as $key => $_pitch) {

                if ($pitch_delete_query == "DELETE FROM project_pitch WHERE id IN (") {

                    $pitch_delete_query .= "'" . $_pitch["id"] . "'";

                } else {

                    $pitch_delete_query .= ", '" . $_pitch["id"] . "'";
                }
            }

            $pitch_delete_query .= ")";

            $db->query($pitch_delete_query);

        }

        $mysql_select_query = "SELECT * FROM np_users WHERE user_id=" . $campaign_id;

        $campaign = $db->get_sql_row($mysql_select_query);



        $categories_query_result = $db->query("SELECT * FROM np_orgtype");

        $categories = array();

        while ($query_result =  mysql_fetch_array($categories_query_result)) {

            $categories[] = $query_result;

        }



        $countries_query_result = $db->query("SELECT * FROM proads_countries");

        $countries = array();

        while ($query_result =  mysql_fetch_array($countries_query_result)) {

            $countries[] = $query_result;
        }

        $pitches_query_result = $db->query("SELECT * FROM project_pitch WHERE project_id=" . $campaign_id);

        $pitches = array();

        while ($query_result =  mysql_fetch_array($pitches_query_result)) {

            $pitches[] = $query_result;

        }



        $project_update_query_result = $db->query("SELECT * FROM project_updates WHERE project_id=" . $campaign_id);

        $project_updates = array();

        while ($query_result =  mysql_fetch_array($project_update_query_result)) {

            $project_updates[] = $query_result;

        }

        $project_reward_query_result = $db->query("SELECT * FROM project_rewards WHERE project_id=" . $campaign_id);

        $project_rewards = array();

        while ($query_result =  mysql_fetch_array($project_reward_query_result)) {

            $project_rewards[] = $query_result;

        }

        $template->set('campaign', $campaign);

        $template->set('categories', $categories);

        $template->set('countries', $countries);

        $template->set('pitches', $pitches);

        $template->set('project_updates', $project_updates);

        $template->set('project_rewards', $project_rewards);

        $members_area_page_content = $template->process('members_area_campaigns_edit.tpl.php');

        $template->set('members_area_page_content', $members_area_page_content);

    }



} else if ($_REQUEST['get_states'] == 'true') {

    $post_country = ($_POST['country']) ? $_POST['country'] : $db->get_sql_field("SELECT c.id FROM " . DB_PREFIX . "countries c WHERE

				c.parent_id=0 ORDER BY c.country_order ASC, c.name ASC LIMIT 1", 'id');

    $template->set('country_dropdown', $tax->countries_dropdown('country', $post_country, 'registration_form'));
    $template->set("project_country",getProjectCategoryListToHTML());
    $template->set('state_box', $tax->states_box('state', $_POST['state'], $post_country));

    $template->set('campaign', $campaign);

    $template->set('categories', $categories);

    $template->set('countries', $countries);

    $template->set('pitches', $pitches);

    $template->set('project_updates', $project_updates);

    $template->set('project_rewards', $project_rewards);

    $members_area_page_content = $template->process('members_area_campaigns_edit.tpl.php');

    $template->set('members_area_page_content', $members_area_page_content);

} else if (!$form_submitted)

{



    $post_country = ($campaign['country']) ? $campaign['country'] : $db->get_sql_field("SELECT c.id FROM " . DB_PREFIX . "countries c WHERE

				c.parent_id=0 ORDER BY c.country_order ASC, c.name ASC LIMIT 1", 'id');


    $template->set('country_dropdown', $tax->countries_dropdown('country', $post_country, 'registration_form'));

    $template->set("project_country",getProjectCategoryListToHTML());

    $template->set('state_box', $tax->states_box('state', $campaign['state'], $post_country));

    $template->set('campaign', $campaign);

    $template->set('categories', $categories);

    $template->set('countries', $countries);

    $template->set('pitches', $pitches);

    $template->set('project_updates', $project_updates);

    $template->set('project_rewards', $project_rewards);

    $members_area_page_content = $template->process('members_area_campaigns_edit.tpl.php');

    $template->set('members_area_page_content', $members_area_page_content);



}