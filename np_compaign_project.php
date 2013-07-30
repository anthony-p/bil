<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Igor
 * Date: 7/26/13
 * Time: 9:20 PM
 * To change this template use File | Settings | File Templates.
 */
session_start();

/*include_once ('includes/npglobal.php');
include_once ('includes/npclass_formchecker.php');
include_once ('includes/npclass_custom_field.php');
include_once ('includes/npclass_item.php');*/
include_once ('includes/global.php');

/*include_once ('includes/npclass_project_rewards.php');
include_once ('includes/npclass_project_updates.php');*/
include_once ('includes/npfunctions_login.php');

if (!$session->value('user_id')) {
    header_redirect('../login.php');
} else {
    if ($_POST['add_project_updates'] == true) {

        $data['project_id'] = $_POST['project_id'];
        $data['comment']    = $_POST['comment'];
        $data['user_id']    = $session->value('user_id');
        /*
        $npProjectUpdates   = new npProjectUpdates();
        $npProjectUpdates->insert($data);
        $recordId = $npProjectUpdates->getLastComment();*/

        $project_updates = $db->rem_special_chars_array($data);
        $insert_query = "INSERT INTO project_updates(user_id, project_id, parrent_id, comment, create_at) VALUES";

        $insert_query .= "(" .
            $project_updates["user_id"] . ", " .
            $project_updates["project_id"] . ", '" .
            $project_updates["parrent_id"] . "', '" .
            $project_updates["comment"] . "', '" .
            $currentTime .
            "')";

        $sql_insert_pitch = $db->query($insert_query);

        $recordId = $db->query("SELECT id FROM project_updates ORDER BY id DESC LIMIT 1");
        $rowSearched = array();
        while ($row = mysql_fetch_array($recordId)) {
            $rowSearched = $row;
        }
        if (!empty($recordId)) {
            echo json_encode(array("response" => true, "id" => $rowSearched['id']));
        } else {
            echo json_encode(array("response" => "error"));
        }

    } elseif ($_POST['add_project_rewards'] == true) {

        $data['project_id'] = $_POST['project_id'];
        $data['comment']    = $_POST['comment'];
        $data['user_id']    = $session->value('user_id');

        /* this function don't work
        $npProjectRewards   = new npProjectRewards();
        $npProjectRewards->insert($data);
        $recordId = $npProjectRewards->getLastComment();
        */

        $project_rewards = $db->rem_special_chars_array($data);
        $insert_query = "INSERT INTO project_rewards(user_id, project_id, parrent_id, comment, create_at) VALUES";

        $insert_query .= "(" .
            $project_rewards["user_id"] . ", " .
            $project_rewards["project_id"] . ", '" .
            $project_rewards["parrent_id"] . "', '" .
            $project_rewards["comment"] . "', '" .
            $currentTime .
            "')";

        $sql_insert_pitch = $db->query($insert_query);

        $recordId = $db->query("SELECT id FROM project_rewards ORDER BY id DESC LIMIT 1");
        $rowSearched = array();
        while ($row = mysql_fetch_array($recordId)) {
            $rowSearched = $row;
        }
        if (!empty($recordId)) {
            echo json_encode(array("response" => true, "id" => $rowSearched['id']));
        } else {
            echo json_encode(array("response" => "error"));
        }
    } elseif ($_POST['delete_project_rewards'] == true) {

        $rewardsId = $_POST["rewards_id"];
        if (!empty($rewardsId)) {
            /*
            $npProjectRewards   = new npProjectRewards();
            $npProjectRewards->delete($rewardsId);*/
            $db->query("DELETE FROM project_rewards WHERE id=" . $rewardsId);
            echo json_encode(array("response" => true));
        } else {
            echo json_encode(array("response" => "error"));
        }

    } elseif ($_POST['delete_project_updates'] == true) {

        $updatesId = $_POST["updates_id"];
        if (!empty($updatesId)) {
            /*
            $npProjectUpdates   = new npProjectUpdates();
            $npProjectUpdates->delete($rewardsId);
            */
            $db->query("DELETE FROM project_updates WHERE id=" . $updatesId);
            echo json_encode(array("response" => true));
        } else {
            echo json_encode(array("response" => "error"));
        }
    }
}