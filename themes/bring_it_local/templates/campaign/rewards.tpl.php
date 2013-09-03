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
	<h1>Select a reward</h1> <h2>for your contribution</h2>
	<?php
	foreach($projectRewards as $project_reward){
	?>
		<div class="reward_display">
			<div class="reward_amount">$<?= $project_reward['amount']; ?></div>
			<div class="reward_name"><?= $project_reward['name']; ?></div>
			<div class="reward_description"><?= $project_reward['description']; ?></div>
			<div class="reward_bottom">
				<div class="reward_claimed_number"><?= $project_reward['given_number']; ?> claimed</div>
				<div class="donate_now_button">
					<span class="uper">Donate Now</span>
					<span>make a donation</span>
				</div>
			</div>
		</div>
	<?php
	}
	?>
</aside>