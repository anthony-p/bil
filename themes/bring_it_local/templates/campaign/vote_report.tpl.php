<aside class="announcement vote_report_tab" id="vote_report_tab_content">
	<h1><?= MSG_COMMUNITY_FUND_VOTE_REPORT_TITLE; ?></h1>
	<div class="vote_report_date">Month: <div><?= $voteReportMonth; ?></div></div>
	<?php if(isset($todaysDate)): ?>
		<div class="vote_report_date">Today's date: <div><?= $todaysDate; ?></div></div>
	<?php endif; ?>
	<?php if(!empty($voteReportData)): ?>
		<table class="vote_report_table">
			<tr>
				<th><?= MSG_COMMUNITY_FUND_VOTE_REPORT_CAMPAIGN_TITLE ?></th>
				<th><?= MSG_COMMUNITY_FUND_VOTE_REPORT_VOTES ?></th>
			</tr>
			<?php $i = 1; ?>
			<?php foreach($voteReportData as $campaignVotes):?>
			<tr <?= $i == 0 ? 'class="secondary-line"' : '';?>>
				<td><?= $campaignVotes['campaign_title'] ?></td>
				<td style="text-align: center;"><?= $campaignVotes['campaign_votes_number'] ?></td>
			</tr>
			<?php $i = ($i + 1)%2; ?>
			<?php endforeach;?>
		</table>
	<?php else: ?>
		<div class="no_votes_message"><?= MSG_COMMUNITY_FUND_NO_VOTES_MADE_IN_CURRENT_MONTH ?></div>
	<?php endif; ?>
</aside>