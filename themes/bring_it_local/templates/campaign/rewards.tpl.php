<?php
require_once (__DIR__ . '/../includes/class_project_rewards.php');
?>
<script>
    $(document).ready(function(){
		$(".donate_now_button").click(function(){
			alert("This feature has not been implemented yet.");
		});
	});
</script>
<aside class="announcement rewards_tab">
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
					<div class="donate_now_button">
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