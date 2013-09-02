<?php

/**
 *
 * @author Zakaria DOUGHI.
 *
 */

require_once("class_custom_field.php");
require_once(__DIR__ . '/../language/english/site.lang.php');

class projectRewards extends custom_field {

	//--------------------------------------------------------------------------------------------------------------------------
	function save($reward, $user_id) {
        if (!empty($reward['project_id']) && !empty($user_id)) {
			$has_rights = $this->getField("Select count(*) FROM np_users WHERE user_id='".$reward['project_id']."' and probid_user_id='".$user_id."'");
			if($has_rights == 1){
				$rewards_number = $this->getField("Select count(*) FROM project_rewards where project_id='".$reward['project_id']."'");
				if($rewards_number < 20){
					if($this->isAmountUsed($reward['amount'], $reward['project_id'])){
						return MSG_REWARD_AMOUNT_EXIST;
					}
					
					if($this->isNameUsed($reward['name'], $reward['project_id'])){
						return MSG_REWARD_NAME_EXIST;
					}
					
					$this->query("INSERT into project_rewards(project_id, amount, name, description, ".(empty($reward['available_number']) ? "" : "available_number, ").(empty($reward['estimated_delivery_date']) ? "" : "estimated_delivery_date, ")."shipping_address_required) values ('".$reward['project_id']."', '".$reward['amount']."', '".$reward['name']."', '".$reward['description']."', ".(empty($reward['available_number']) ? "" : "'".$reward['available_number']."', ").(empty($reward['estimated_delivery_date']) ? "" : "FROM_UNIXTIME('".strtotime($reward['estimated_delivery_date'])."'), ")."'".$reward['shipping_address_required']."')");
					$reward['id'] = mysql_insert_id();
					return $this->newRewardForm($reward);
				} else {
					return MSG_REWARD_MAX_NUMBER_REACHED;
				}
			} else {
				return MSG_ACCESS_DENIED;
			}
		} else {
            return "error";
        }
    }
	
	//--------------------------------------------------------------------------------------------------------------------------
	function update($reward, $user_id) {
        if (!empty($reward['id']) && !empty($user_id)) {
			$has_rights = $this->getField("Select count(*) FROM project_rewards r left join np_users c on r.project_id = c.user_id WHERE r.id='".$reward['id']."' and c.probid_user_id='".$user_id."'");
			if($has_rights == 1){
				if($this->isAmountUsed($reward['amount'], '', $reward['id'])){
					return MSG_REWARD_AMOUNT_EXIST;
				}
				
				if($this->isNameUsed($reward['name'], '', $reward['id'])){
					return MSG_REWARD_NAME_EXIST;
				}
				$this->query("UPDATE project_rewards SET amount='".$reward['amount']."', name='".$reward['name']."', description='".$reward['description']."', available_number=".$reward['available_number'].", estimated_delivery_date=".$reward['estimated_delivery_date'].", shipping_address_required='".$reward['shipping_address_required']."' WHERE id='".$reward['id']."'");
				return MSG_REWARD_SAVED;
			} else {
				return MSG_ACCESS_DENIED;
			}
		} else {
            return "error";
        }
    }
	
	//--------------------------------------------------------------------------------------------------------------------------
    function delete($rewardId = '', $user_id = '') {
        if (!empty($rewardId) && !empty($user_id)) {
			$has_rights = $this->getField("Select count(*) FROM project_rewards r left join np_users c on r.project_id = c.user_id WHERE r.id='".$rewardId."' and c.probid_user_id='".$user_id."'");
			if($has_rights == 1){
				$this->query("DELETE FROM project_rewards WHERE id='".$rewardId."'");
				return true;
			} else {
				return MSG_ACCESS_DENIED;
			}
        } else {
            return "error";
        }
    }
	
	//--------------------------------------------------------------------------------------------------------------------------
	function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}

	//--------------------------------------------------------------------------------------------------------------------------
	function newRewardForm($reward = array()){
		$reward_id = isset($reward['id']) ? $reward['id'] : $this->generateRandomString();
		if($reward['estimated_delivery_date'] && !empty($reward['estimated_delivery_date'])){
			$reward['estimated_delivery_date'] = date("m/d/Y", strtotime($reward['estimated_delivery_date']));
		}
		ob_start();
		?><div class="reward_block" id="reward_block_<?= $reward_id; ?>">
			<div class="reward_title">
				<div class="reward_title_label"><?=MSG_REWARD;?></div>
				<div class="rewards-actions">
					<button onclick="<?=isset($reward['id']) ? 'update' : 'save';?>ProjectReward('<?= $reward_id; ?>'); return false;" class="validate-reward"></button>
					<button onclick="deleteProjectReward('<?= $reward_id; ?>'); return false;" class="delete-reward"></button>
				</div>
			</div>
			<div class="reward_content">
				<input type="hidden" id="is_new_<?= $reward_id; ?>" value="<?= isset($reward['id']) ? '0' : '1'; ?>">
				<div class="account-row">
					<label> <?=MSG_REWARD_AMOUNT;?> *</label>
					<input type="text" id="reward_amount_<?= $reward_id; ?>" value="<?= @$reward['amount']?>" size="40" />
					<span class="reward_currency">$</span>
				</div>
				<div class="account-row">
					<label> <?=MSG_REWARD_NAME;?> *</label>
					<input type="text" id="reward_name_<?= $reward_id; ?>" value="<?= @$reward['name']?>" size="40" />
				</div>
				<div class="account-row">
					<label> <?=MSG_REWARD_DESCRIPTION;?> *</label>
					<textarea id="reward_description_<?= $reward_id; ?>"><?= @$reward['description']?></textarea>
				</div>
				<div class="account-row">
					<label> <?=MSG_REWARD_AVAILABLE_NUMBER;?></label>
					<input type="text" value="<?= @$reward['available_number']?>" id="reward_available_number_<?= $reward_id; ?>"></input>
				</div>
				<div class="account-row">
					<label> <?=MSG_REWARD_ESTIMATED_DELIVERY;?></label>
					<input type="text" id="reward_estimated_delivery_date_<?= $reward_id; ?>" value="<?= isset($reward['estimated_delivery_date']) ? $reward['estimated_delivery_date'] : ''?>"></input>
				</div>
				<div class="account-row" style="margin-top: 20px; margin-left: 135px;">
					<input type="checkbox" <?php if(@$reward['shipping_address_required'] == 1){echo 'checked';} ?> id="reward_shipping_address_required_<?= $reward_id; ?>" class="reward_shipping_address_required" value="1"></input>
					<?=MSG_REWARD_SHIPPING_ADDRESS_REQUIRED;?>
				</div>
			</div>
			<script>
				$( "#reward_estimated_delivery_date_<?= $reward_id; ?>" ).datepicker({ 
					dateFormat: "mm/dd/yy", 
					changeMonth: true,
					changeYear: true, 
					minDate: new Date(),
					defaultDate: "<?= isset($reward['estimated_delivery_date']) ? $reward['estimated_delivery_date'] : ''?>"
				});
			</script>
		</div>
	<?php
		$form = ob_get_contents();
		ob_end_clean();
		
		return $form;
	}
	
	//--------------------------------------------------------------------------------------------------------------------------
	function addNewRewardForm($campaign_id, $has_new_reward_form){
		if($has_new_reward_form && !empty($campaign_id)){
			$rewards_number = $this->getField("Select count(*) FROM project_rewards where project_id='".$campaign_id."'");
			if($rewards_number < 20){
				return $this->newRewardForm();
			} else {
				return MSG_REWARD_MAX_NUMBER_REACHED;
			}
		} else {
			return "error";
		}
	}
	
	//--------------------------------------------------------------------------------------------------------------------------
	function isAmountUsed($amount, $campaign_id, $reward_id=''){
		if(empty($reward_id)){
			$count = $this->getField("Select count(*) FROM project_rewards WHERE project_id='".$campaign_id."' and amount='".$amount."'");
		} else {
			$count = $this->getField("Select count(*) FROM project_rewards r WHERE r.project_id=(select s.project_id FROM project_rewards s where s.id='".$reward_id."') and r.amount='".$amount."' and r.id <> '".$reward_id."'");
		}
		return ($count != 0);
	}
	
	//--------------------------------------------------------------------------------------------------------------------------
	function isNameUsed($name, $campaign_id, $reward_id=''){
		if(empty($reward_id)){
			$count = $this->getField("Select count(*) FROM project_rewards WHERE project_id='".$campaign_id."' and name='".$name."'");
		} else {
			$count = $this->getField("Select count(*) FROM project_rewards r WHERE r.project_id=(select s.project_id FROM project_rewards s where s.id='".$reward_id."') and r.name='".$name."' and r.id <> '".$reward_id."'");
		}
		return ($count != 0);
	}
	
	//--------------------------------------------------------------------------------------------------------------------------
}