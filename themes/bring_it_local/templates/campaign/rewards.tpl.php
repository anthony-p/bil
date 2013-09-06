<?php
require_once ( dirname(__FILE__).'/../../../../includes/class_project_rewards.php');
?>
<script>
    function claimProjectReward(id){
		$.ajax({
			url:"/np_compaign_reward",
			type: "POST",
			data: {claim_project_reward: true, rewards_id: id},
			success: function(response){
				claimProjectRewardContent = jQuery.parseJSON(response).response;
				$("#rewards_tab_content").html(claimProjectRewardContent);
			},
			error:function(){
				alert("Error");
			}
		});
	}
</script>
<aside class="announcement rewards_tab" id="rewards_tab_content">
	<?php if(count($projectRewards) > 0){ ?>
		<h1><?= MSG_SELECT_A_REWARD; ?></h1> <h2><?= MSG_FOR_YOUR_CONTRIBUTION; ?></h2>
		<?php
		foreach($projectRewards as $project_reward){
			$available = $project_reward['available_number'] ==NULL || $project_reward['available_number'] > $project_reward['given_number'];
		?>
			<div class="reward_display">
				<div class="reward_amount">$<?= $project_reward['amount']; ?></div>
				<div class="reward_name"><?= $project_reward['name']; ?></div>
				<div class="reward_description"><?= $project_reward['description']; ?></div>
				<div class="reward_bottom">
					<div class="reward_claimed_number"><?= $project_reward['given_number']; ?> <?= MSG_CLAIMED_NUMBER_LABEL; ?></div>
		<?php if($available) : ?>
					<div class="donate_now_button" onclick="claimProjectReward(<?= $project_reward['id']; ?>)">
						<span class="uper"><?= MSG_DONATE_NOW; ?></span>
						<span><?= MSG_MAKE_DONATION; ?></span>
					</div>
		<?php endif; ?>
				</div>
			</div>
		<?php
		}
		?>
	<?php } else {?>
		<h1 class="empty_rewards_message"><?= MSG_NO_REWARDS_AVAILABLE; ?></h1>
	<?php }?>
</aside>