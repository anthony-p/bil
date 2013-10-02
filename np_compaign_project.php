<?

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

include_once ('includes/class_project_rewards.php');

//include_once ('includes/npclass_project_updates.php');

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


        // --- Send email for all campaign funders -----------------

        // get campaign details
        $sql_res = $db->query("SELECT * FROM np_users WHERE user_id ='". intval($_POST['project_id'])."';");
        $u_campaign = $db->fetch_array($sql_res);

        // get owners details
        $sql_res = $db->query("SELECT * FROM bl2_users WHERE id='".intval($u_campaign['probid_user_id'])."';");
        $u_user = $db->fetch_array($sql_res);

        // select all founder's id for this campaign
        $sql_res = $db->query("SELECT * FROM funders WHERE campaign_id ='".intval($_POST['project_id'])."'; ");
        while ($mysql_row = mysql_fetch_array($sql_res)) {
            $funders[] = $mysql_row;
        }
        $funders_id = array();
        foreach($funders as $k=>$v){
            $funders_id[] = $v['user_id'];
        }

        if (count($funders_id)){
            $sql_res = $db->query("SELECT * FROM bl2_users WHERE id IN(" . implode(',', $funders_id) . ");");
            while ($mysql_row = mysql_fetch_array($sql_res)) {
                $funders_users[] = $mysql_row;
            }

            include('language/' . $setts['site_lang'] . '/mails/campaign_update_user_notification.php');
        }


        // --- END Send email for all campaign funders ----------------


        if (!empty($recordId)) {
            echo json_encode(array("response" => true, "id" => $rowSearched['id']));
        } else {
            echo json_encode(array("response" => "error"));
        }

    }
    elseif ($_POST['add_project_rewards'] == true) {

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
	}
    elseif($_POST['save_project_rewards'] == true){
		if(isset($_POST["reward_estimated_delivery_date"]) && !empty($_POST["reward_estimated_delivery_date"])){
			$estimated_delivery_date = strtotime($_POST["reward_estimated_delivery_date"]);
			if($estimated_delivery_date == 0){
				echo json_encode(array("response" => MSG_REWARD_ESTIMATED_DELIVERY_DATE_INVALID));
				exit;
			}
		}
		
		$reward['project_id'] = isset($_POST["campaign_id"]) ? $_POST["campaign_id"] : '';
		$reward['amount'] = isset($_POST["reward_amount"]) ? $_POST["reward_amount"] : '';
		$reward['name'] = isset($_POST["reward_name"]) ? $_POST["reward_name"] : '';
		$reward['short_description'] = isset($_POST["reward_short_description"]) ? $_POST["reward_short_description"] : '';
		$reward['description'] = isset($_POST["reward_description"]) ? html_entity_decode($_POST["reward_description"]) : '';
		$reward['available_number'] = isset($_POST["reward_available_number"]) ? $_POST["reward_available_number"] : '';
		$reward['estimated_delivery_date'] = isset($_POST["reward_estimated_delivery_date"]) ? $_POST["reward_estimated_delivery_date"] : '';
		$reward['shipping_address_required'] = isset($_POST["reward_shipping_address_required"])  && $_POST['reward_shipping_address_required'] == 'true' ? 1 : 0;
		
		$projectRewards   = new projectRewards();
		$result = $projectRewards->save($reward, $session->value('user_id'));
		echo json_encode(array("response" => $result));
	}
    elseif ($_POST['update_project_rewards'] == true){
		if(isset($_POST["reward_estimated_delivery_date"]) && !empty($_POST["reward_estimated_delivery_date"])){
			$estimated_delivery_date = strtotime($_POST["reward_estimated_delivery_date"]);
			if($estimated_delivery_date == 0){
				echo json_encode(array("response" => MSG_REWARD_ESTIMATED_DELIVERY_DATE_INVALID));
				exit;
			}
		}
		$reward['id'] = isset($_POST["rewards_id"]) ? $_POST["rewards_id"] : '';
		$reward['amount'] = isset($_POST["reward_amount"]) ? $_POST["reward_amount"] : '';
		$reward['name'] = isset($_POST["reward_name"]) ? $_POST["reward_name"] : '';
		$reward['short_description'] = isset($_POST["reward_short_description"]) ? $_POST["reward_short_description"] : '';
		$reward['description'] = isset($_POST["reward_description"]) ? html_entity_decode($_POST["reward_description"]) : '';
		$reward['available_number'] = isset($_POST["reward_available_number"]) && !empty($_POST["reward_available_number"]) ? "'".intval($_POST["reward_available_number"])."'" : "NULL";
		$reward['estimated_delivery_date'] = isset($_POST["reward_estimated_delivery_date"]) && !empty($_POST["reward_estimated_delivery_date"]) ? "FROM_UNIXTIME('".strtotime($_POST["reward_estimated_delivery_date"])."')" : "NULL";
		$reward['shipping_address_required'] = isset($_POST["reward_shipping_address_required"])  && $_POST['reward_shipping_address_required'] == 'true' ? 1 : 0;
		
		$projectRewards   = new projectRewards();
		$result = $projectRewards->update($reward, $session->value('user_id'));
		echo json_encode(array("response" => $result));
    }
    elseif ($_POST['delete_project_rewards'] == true) {
        $rewardId = isset($_POST["rewards_id"]) ? $_POST["rewards_id"] : '';
		$projectRewards   = new projectRewards();
        $result = $projectRewards->delete($rewardId, $session->value('user_id'));
		echo json_encode(array("response" => $result));	
	}
    elseif($_POST['addNewRewardToProject']) {
		$has_new_reward_form = isset($_POST['has_new_reward_form']) && $_POST['has_new_reward_form'] == 'true' ? false : true;
		$campaign_id = isset($_POST['campaign_id']) ? $_POST['campaign_id'] : '';
		$projectRewards   = new projectRewards();
		$result = $projectRewards->addNewRewardForm($campaign_id, $has_new_reward_form);
		echo json_encode(array("response" => $result));
    }
    elseif ($_POST['delete_project_updates'] == true) {



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

