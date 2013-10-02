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
					
					$this->query("INSERT into project_rewards(project_id, amount, name, short_description, description, ".(empty($reward['available_number']) ? "" : "available_number, ").(empty($reward['estimated_delivery_date']) ? "" : "estimated_delivery_date, ")."shipping_address_required) values ('".$reward['project_id']."', '".$reward['amount']."', '".$reward['name']."', '".$reward['short_description']."', '".$reward['description']."', ".(empty($reward['available_number']) ? "" : "'".$reward['available_number']."', ").(empty($reward['estimated_delivery_date']) ? "" : "FROM_UNIXTIME('".strtotime($reward['estimated_delivery_date'])."'), ")."'".$reward['shipping_address_required']."')");
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
				$this->query("UPDATE project_rewards SET amount='".$reward['amount']."', name='".$reward['name']."', short_description='".$reward['short_description']."', description='".$reward['description']."', available_number=".$reward['available_number'].", estimated_delivery_date=".$reward['estimated_delivery_date'].", shipping_address_required='".$reward['shipping_address_required']."' WHERE id='".$reward['id']."'");
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
					<!--<button onclick="<?/*=isset($reward['id']) ? 'update' : 'save';*/?>ProjectReward('<?/*= $reward_id; */?>'); return false;" class="validate-reward"></button>-->
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
					<label> <?=MSG_REWARD_SHORT_DESCRIPTION;?> *</label>
					<textarea class="reward_description" id="reward_short_description_<?= $reward_id; ?>"><?= @$reward['short_description']?></textarea>
				</div>
				<div class="account-row">
					<label> <?=MSG_REWARD_DESCRIPTION;?></label>
				</div>
				<div class="account-row">
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
            <div class="clear"> </div>
            <input type="button" value="<?=MSG_SEND?>" onclick="<?=isset($reward['id']) ? 'update' : 'save';?>ProjectReward('<?= $reward_id; ?>'); return false;" />
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
						toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking template pagebreak restoredraft preview",

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
		$sql = "select r.*, p.project_title as campaign_name, p.logo as campaign_logo, p.cfc as is_community_fund from project_rewards r, np_users p where r.project_id = p.user_id and id='".$reward_id."'";
		$result = $this->get_sql_row($sql);
		return $result;
	}
	
	//--------------------------------------------------------------------------------------------------------------------------
	function getRewardCampaignId($reward_id){
		$sql = "select project_id from project_rewards where id='".$reward_id."'";
		return $this->getField($sql);
	}
	
	//--------------------------------------------------------------------------------------------------------------------------
	function getRewardCampaignOwnerDetails($reward_id){
		$sql = "select u.first_name, u.last_name, u.email, c.cfc as is_community_fund from bl2_users u, np_users c, project_rewards r where c.probid_user_id = u.id and r.project_id = c.user_id and r.id='".$reward_id."'";
		$result = $this->get_sql_row($sql);
		return $result;
	}
	
	//--------------------------------------------------------------------------------------------------------------------------
	function getUser($user_id){
		$sql = "select u.*, c.name as country_name from bl2_users u, proads_countries c where u.country= c.id and u.id='".$user_id."'";
		$result = $this->get_sql_row($sql);
		return $result;
	}
	
	//--------------------------------------------------------------------------------------------------------------------------
	function finalizeRewardClaiming($transferred_amount = '') {
		if(!empty($transferred_amount)){
			$reward_id = $_SESSION['reward_claiming']['reward_id'];
			$reward = $this->getReward($reward_id);
			if($transferred_amount >= $reward['amount']){
				$this->query("update project_rewards set given_number = given_number + 1 where id='".$reward_id."'");
				$contribution_details = $_SESSION['reward_claiming'];
				$campaign_owner = $this->getRewardCampaignOwnerDetails($reward_id);
				global $setts;
				include('language/' . $setts['site_lang'] . '/mails/reward_claimed_notification.php');
				include('language/' . $setts['site_lang'] . '/mails/reward_claimed_contributor_confirmation.php');
			}
		}
		unset($_SESSION['reward_claiming']);
	}
	
	//--------------------------------------------------------------------------------------------------------------------------
	function getClaimRewardForm($reward_id, $user_id=""){
		$reward = $this->getReward($reward_id);
		if(!empty($user_id)){
			$user = $this->getUser($user_id);
		}
		$required_mark = ($reward['shipping_address_required'] == 1) ? '<div class="mandatory_star">*</div>' : '';
		ob_start();
		?>
		<div class="reward_contribute_display">
			<?php if(!empty($reward['campaign_logo'])):?>
			<div class="reward_contribute_summary_image">
				<img src="<?= $reward['campaign_logo']?>" width="162" height="162"></img>
			</div>
			<?php endif; ?>
			<div class="reward_contribute_summary_details<?= empty($reward['campaign_logo']) ? ' reward_contribute_summary_details_full_width' : ''?>">
				<div class="reward_contribute_summary_title"><?= MSG_YOUR_CONTRIBUTION_SUMMARY; ?></div>
				<div class="reward_contribute_summary_values">
					<div class="reward_contribute_summary_compaign_title"><?= $reward['campaign_name'];?></div>
					<div class="reward_contribute_summary_value_line">
						<label><?= MSG_REWARD_CONTRIBUTOR_NAME; ?></label>
						<div id="contributor_name_value"><?= empty($user) ? "- - - - - -" : $user['first_name'].' '.$user['last_name'] ?></div>
					</div>
					<div class="reward_contribute_summary_value_line">
						<label><?= MSG_YOUR_REWARD; ?></label>
						<div><?= $reward['name']; ?></div>
					</div>
					<div class="reward_contribute_summary_value_line">
						<label><?= MSG_YOUR_CONTRIBUTION; ?></label>
						<div id="contribution_amount_value">$<?= $reward['amount']; ?></div>
					</div>
					<div class="reward_contribute_summary_value_line" <?= $reward['is_community_fund'] == 0 ? '' : 'style="display: none;"' ?>>
						<label><?= MSG_YOUR_CONTRIBUTION_TO_COMMUNITY_FUND; ?></label>
						<div id="contribution_to_community_amount_value">$0.00</div>
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
				<?php if($reward['is_community_fund'] == 0):?>
			<div style="margin-top: 15px;">
			<input type="checkbox" id="reward_contribution_community_amount_enable" style="width: auto;float: left;"/>
			<label style="width: 361px;margin-left: 8px; float: left;"><?= MSG_REWARD_ADD_5_DOLLARS_TO_COMMUNNITY_FUND ?></label><br />
			</div>
			<label>$</label>
			<input type="text" id="reward_contribution_community_amount" disabled="disabled" value="5.00" />
				<?php endif; ?>
			</div>
			<div class="reward_contribute_title"><?= MSG_YOUR_REWARD; ?></div>
			<div class="reward_contribute_section">
				<div class="reward_contribute_amount">$<?= $reward['amount']; ?>+</div>
				<div class="reward_contribute_details">
					<div class="reward_contribute_name"><?= $reward['name']; ?></div>
					<div class="reward_contribute_claimed"><?= $reward['given_number']; ?> <?= MSG_CLAIMED_NUMBER_LABEL; ?></div>
					<div class="reward_contribute_description"><?= $reward['short_description']; ?></div>
				</div>
			</div>
		</div>
		<div class="reward_contribute_display">
			<div class="reward_contribute_title"><?= MSG_REWARD_CONTACT_INFORMATION?></div>
			<div class="reward_contribute_section">
				<label><?= MSG_REWARD_EMAIL ?> <div class="mandatory_star">*</div></label>
				<input type="text" id="reward_contribution_email" value="<?= $user['email']?>"></input>
			</div>
		</div>
		<div class="reward_contribute_display">
			<div class="reward_contribute_title"><?= MSG_REWARD_SHIPPING_INFORMATION ?></div>
			<div class="reward_contribute_section" id="shipping_information_section">
				<div>
					<label><?= MSG_REWARD_SHIPPING_INFORMATION_NAME ?> <?= $required_mark; ?></label>
					<input type="text" id="reward_contribution_name" value="<?= $user['first_name'].' '.$user['last_name'] ?>"></input>
				</div>
				<div>
					<label><?= MSG_REWARD_SHIPPING_INFORMATION_COUNTRY ?> <?= $required_mark; ?></label>
					<input type="text" id="reward_contribution_country" value="<?= $user['country_name'] ?>"></input>
				</div>
				<div>
					<label><?= MSG_REWARD_SHIPPING_INFORMATION_ADDRESS_1 ?> <?= $required_mark; ?></label>
					<input type="text" id="reward_contribution_address1" value="<?= $user['address'] ?>"></input>
				</div>
				<div>
					<label><?= MSG_REWARD_SHIPPING_INFORMATION_ADDRESS_2 ?> <?= $required_mark; ?></label>
					<input type="text" id="reward_contribution_address2" value=""></input>
				</div>
				<div>
					<label><?= MSG_REWARD_SHIPPING_INFORMATION_CITY ?> <?= $required_mark; ?></label>
					<input type="text" id="reward_contribution_city" value="<?= $user['city'] ?>"></input>
				</div>
				<div>
					<label><?= MSG_REWARD_SHIPPING_INFORMATION_POSTAL_CODE;?> <?= $required_mark; ?></label>
					<input type="text" id="reward_contribution_postal_code" value="<?= $user['postal_code'] ?>"></input>
				</div>
			</div>
		</div>
		<button id="reward_claiming_continue_button"><?= MSG_REWARD_CONTINUE; ?></Button>
		<div id="reward_claiming_bottom_note"><?= MSG_REWARD_CLAIMING_BOTTOM_NOTE; ?></div>
		<script>
			$("#reward_claiming_continue_button").click(function(){
				if (!isEmailFieldValid()) {
					alert("<?= MSG_REWARD_CLAIMING_PROVIDE_VALID_EMAIL_ADDRESS; ?>");
					return false;
				}
				<?php if($reward['shipping_address_required'] == 1) : ?>
				shipping_information_missing = false;
				$('#shipping_information_section :text').each(function (){
					if ($.trim(this.value) == ""){
						shipping_information_missing = true;
					}
				});
				if(shipping_information_missing){
					alert("<?= MSG_REWARD_CLAIMING_ALL_SHIPPING_FIELD_ARE_REQUIRED; ?>");
					return false;
				}
				<?php endif; ?>
				
				if($("#reward_contribution_community_amount_enable").is(":checked")){
					contribution_to_community_fund = $("#reward_contribution_community_amount").val();
					if(!$.isNumeric(contribution_to_community_fund) || contribution_to_community_fund < 0.01){
						contribution_to_community_fund = 5;
					}
				} else {
					contribution_to_community_fund = 0;
				}
				
				$.ajax({
					url:"/np_compaign_reward",
					type: "POST",
					data: {make_donation_for_reward: true, reward_id: <?= $reward_id; ?>, contribution: $("#reward_contribution_value").val(), contribution_to_community_fund: contribution_to_community_fund, email: $("#reward_contribution_email").val(), name: $("#reward_contribution_name").val(), country: $("#reward_contribution_country").val(), address1: $("#reward_contribution_address1").val(), address2: $("#reward_contribution_address2").val(), city: $("#reward_contribution_city").val(), postal_code: $("#reward_contribution_postal_code").val()},
					success: function(response){
						$("#rewards_tab_content").html(jQuery.parseJSON(response).response);
					},
					error:function(){
						alert("Error");
					}
				});
			});
			$("#reward_contribution_name").keyup(function(){
				value = $("#reward_contribution_name").val();
				$("#contributor_name_value").html($.trim(value) == "" ? "- - - - - -" : value);
			});
			function summaryDetailsRefresh(){
				if($("#reward_contribution_community_amount_enable").is(":checked")){
					$("#reward_contribution_community_amount").removeAttr("disabled");
					value = $("#reward_contribution_community_amount").val();
					if(!$.isNumeric(value) || value < 0.01){ value = 5.00; }
					$("#contribution_to_community_amount_value").html("$"+value);
					value = parseFloat(value) + parseFloat($("#reward_contribution_value").val());
					$("#contribution_total_amount").html("$"+value.toFixed(2));
				} else {
					$("#reward_contribution_community_amount").attr("disabled", "disabled");
					$("#contribution_to_community_amount_value").html("$0.00");
					$("#contribution_total_amount").html($("#contribution_amount_value").html());
				}
			}
			$("#reward_contribution_community_amount_enable").click(summaryDetailsRefresh);
			$("#reward_contribution_community_amount").keyup(summaryDetailsRefresh);
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
					alert("<?= MSG_REWARD_CLAIMING_CONTRIBUTION_AMOUNT_MUST_VALID; ?>");
					$("#contribution_amount_value").html("$"+<?= $reward['amount']; ?>);
					$("#contribution_total_amount").html("$"+<?= $reward['amount']; ?>);
				} else {
					if(value < <?= $reward['amount']; ?>){
						alert("<?= MSG_REWARD_CLAIMING_CONTRIBUTION_IS_LESS_THAN_WHAT_IS_REQUIRED . '$'.$reward['amount'].'\n'.MSG_REWARD_CLAIMING_YOU_CAN_STILL_MAKE_THE_DONATION;?>");
					}
					$("#contribution_amount_value").html("$"+value);
					$("#contribution_total_amount").html("$"+value);
				}
			});
			$("#reward_contribution_email").blur(function(){
				if (!isEmailFieldValid()) {
					alert("<?= MSG_REWARD_CLAIMING_PROVIDE_VALID_EMAIL_ADDRESS; ?>");
				}
			});
			function isEmailFieldValid(){
				email = $("#reward_contribution_email").val();
				regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				return regex.test(email);
			}
		</script>
		<?php
		
		$content = ob_get_contents();
		ob_end_clean();
		
		return $content;
	}
	
	//--------------------------------------------------------------------------------------------------------------------------
}