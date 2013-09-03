<?php
require_once (__DIR__ . '/../includes/class_project_rewards.php');
?>

<aside class="announcement rewards_tab">
	<h1>Select a reward</h1> <h2>for your contribution</h2>
	<?php
	foreach($projectRewards as $project_reward){
	?>
		<div class="reward_display">
			<div class="reward_amount">$<?= $project_reward['amount']; ?></div>
			<div class="reward_name"><?= $project_reward['name']; ?></div>
			<div class="reward_description"><?= $project_reward['description']; ?></div>
		</div>
	<?php
	}
	?>
</aside>