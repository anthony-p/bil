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
					<textarea class="reward_description" id="reward_description_<?= $reward_id; ?>"><?= @$reward['description']?></textarea>
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
				tinymce.init({
					selector:'#reward_description_<?= $reward_id; ?>',
					plugins: [
						"advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
								"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
								"table contextmenu directionality emoticons template textcolor paste fullpage textcolor moxiemanager"
					],
					toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
						toolbar2: "cut copy paste pastetext | searchreplace | bullist numlist | outdent indent blockquote | undo redo | insertfile link unlink anchor image media code | forecolor backcolor",
						toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft preview",

						menubar: false,
						toolbar_items_size: 'small',

						style_formats: [
							{title: 'Bold text', inline: 'b'},
							{title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
							{title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
							{title: 'Example 1', inline: 'span', classes: 'example1'},
							{title: 'Example 2', inline: 'span', classes: 'example2'},
							{title: 'Table styles'},
							{title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
						]
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
	function getAllRewards($campaign_id, $order_by='id'){
		$project_rewards = array();
		if(!empty($campaign_id)){
			$sql = "SELECT r.* FROM project_rewards r LEFT JOIN np_users np ON r.project_id = np.user_id LEFT JOIN bl2_users u ON np.probid_user_id =  u.id WHERE r.project_id=" . $campaign_id . " ORDER BY r.".$order_by;
			$project_reward_query_result = $this->query($sql);

			while ($query_result =  mysql_fetch_array($project_reward_query_result)) {
				$project_rewards[] = $query_result;
			}
		}
		return $project_rewards;
	}
	
	//--------------------------------------------------------------------------------------------------------------------------
	function getReward($reward_id){
		if(empty($reward_id)) {
			return "error";
		}
		$sql = "select r.*, p.name as campaign_name from project_rewards r, np_users p where r.project_id = p.user_id and id='".$reward_id."'";
		$result = $this->get_sql_row($sql);
		return $result;
	}
	
	//--------------------------------------------------------------------------------------------------------------------------
	function getUser($user_id){
		$sql = "select * from bl2_users where id='".$user_id."'";
		$result = $this->get_sql_row($sql);
		return $result;
	}
	
	//--------------------------------------------------------------------------------------------------------------------------
	function getClaimRewardForm($reward_id, $user_id=""){
		$reward = $this->getReward($reward_id);
		if(!empty($user_id)){
			$user = $this->getUser($user_id);
		}
		ob_start();
		?>
		<div class="reward_contribute_display">
			<div class="reward_contribute_summary_image">
				<img src="" alt=""></img>
			</div>		
			<div class="reward_contribute_summary_details">
				<div class="reward_contribute_summary_title"><?= MSG_YOUR_CONTRIBUTION_SUMMARY; ?></div>
				<div class="reward_contribute_summary_values">
					<div class="reward_contribute_summary_compaign_title"><?= $reward['campaign_name'];?></div>
					<div class="reward_contribute_summary_value_line">
						<label><?= MSG_REWARD_CONTRIBUTOR_NAME; ?></label>
						<div><?= $user['first_name'].' '.$user['last_name'] ?></div>
					</div>
					<div class="reward_contribute_summary_value_line">
						<label><?= MSG_YOUR_REWARD; ?></label>
						<div><?= $reward['name']; ?></div>
					</div>
					<div class="reward_contribute_summary_value_line">
						<label><?= MSG_YOUR_CONTRIBUTION; ?></label>
						<div id="contribution_amount_value">$<?= $reward['amount']; ?></div>
					</div>
					<div class="reward_contribute_summary_value_line reward_contribute_summary_total">
						<label><?= MSG_REWARD_TOTAL; ?></label>
						<div id="contribution_total_amount">$<?= $reward['amount']; ?></div>
					</div>
				</div>
			</div>
		</div>
		<div class="reward_contribute_display">
			<div class="reward_contribute_title"><?= MSG_HOW_MUCH_YOU_WOULD_LIKE_TO_CONTRIBUTE; ?></div>
			<div class="reward_contribute_section">
				<label>$</label>
				<input type="text" id="reward_contribution_value" value="<?= $reward['amount']; ?>"></input>
			</div>
			<div class="reward_contribute_title"><?= MSG_YOUR_REWARD; ?></div>
			<div class="reward_contribute_section">
				<div class="reward_contribute_amount">$<?= $reward['amount']; ?>+</div>
				<div class="reward_contribute_details">
					<div class="reward_contribute_name"><?= $reward['name']; ?></div>
					<div class="reward_contribute_claimed"><?= $reward['given_number']; ?> <?= MSG_CLAIMED_NUMBER_LABEL; ?></div>
					<div class="reward_contribute_description"><?= $reward['description']; ?></div>
				</div>
			</div>
		</div>
		<div class="reward_contribute_display">
			<div class="reward_contribute_title"><?= MSG_REWARD_CONTACT_INFORMATION?></div>
			<div class="reward_contribute_section">
				<label><?= MSG_REWARD_EMAIL ?></label>
				<input type="text" id="reward_contribution_email" value="<?= $user['email']?>"></input>
			</div>
		</div>
		<div class="reward_contribute_display">
			<div class="reward_contribute_title"><?= MSG_REWARD_SHIPPING_INFORMATION ?></div>
			<div class="reward_contribute_section">
				<div>
					<label><?= MSG_REWARD_SHIPPING_INFORMATION_NAME ?></label>
					<input type="text" id="reward_contribution_name" value="<?= $user['first_name'].' '.$user['last_name'] ?>"></input>
				</div>
				<div>
					<label><?= MSG_REWARD_SHIPPING_INFORMATION_COUNTRY ?></label>
					<input type="text" id="reward_contribution_country" value="<?= $user['country'] ?>"></input>
				</div>
				<div>
					<label><?= MSG_REWARD_SHIPPING_INFORMATION_ADDRESS_1 ?></label>
					<input type="text" id="reward_contribution_address1" value="<?= $user['address'] ?>"></input>
				</div>
				<div>
					<label><?= MSG_REWARD_SHIPPING_INFORMATION_ADDRESS_2 ?></label>
					<input type="text" id="reward_contribution_address2" value=""></input>
				</div>
				<div>
					<label><?= MSG_REWARD_SHIPPING_INFORMATION_CITY ?></label>
					<input type="text" id="reward_contribution_city" value="<?= $user['city'] ?>"></input>
				</div>
				<div>
					<label><?= MSG_REWARD_SHIPPING_INFORMATION_POSTAL_CODE;?></label>
					<input type="text" id="reward_contribution_postal_code" value="<?= $user['postal_code'] ?>"></input>
				</div>
			</div>
		</div>
		<script>
			$("#reward_contribution_value").keyup(function(){
				value = $("#reward_contribution_value").val();
				if($.isNumeric(value) && value >= 0.01){
					$("#contribution_amount_value").html("$"+value);
					$("#contribution_total_amount").html("$"+value);
				} else {
					$("#contribution_amount_value").html("$"+<?= $reward['amount']; ?>);
					$("#contribution_total_amount").html("$"+<?= $reward['amount']; ?>);
				}
			});
			$("#reward_contribution_value").blur(function(){
				value = $("#reward_contribution_value").val();
				if(!$.isNumeric(value) || value < 0.01){
					alert("The contribution amount must be a number greater than 0.01");
					$("#contribution_amount_value").html("$"+<?= $reward['amount']; ?>);
					$("#contribution_total_amount").html("$"+<?= $reward['amount']; ?>);
				} else {
					if(value < <?= $reward['amount']; ?>){
						alert("The amount you have chosen is less than what is required for this reward which is $<?= $reward['amount']?>\nYou can still donate this amount but you will not receive the reward.");
					}
					$("#contribution_amount_value").html("$"+value);
					$("#contribution_total_amount").html("$"+value);
				}
			});
			$("#reward_contribution_email").blur(function(){
				
			});
		</script>
		<?php
		
		$content = ob_get_contents();
		ob_end_clean();
		
		return $content;
	}
	
	//--------------------------------------------------------------------------------------------------------------------------
}