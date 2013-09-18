<aside class="announcement vote_report_tab vote_history_report_tab" id="vote_report_tab_content">
	<h1><?= MSG_COMMUNITY_FUND_VOTE_AND_DISBURSEMENTS_HISTORY_TITLE ?></h1>
	<table id="vote_report_table">
		<tr>
			<th width=15%><?= MSG_COMMUNITY_FUND_VOTE_AND_DISBURSEMENTS_HISTORY_MONTH ?></th>
			<th><?= MSG_COMMUNITY_FUND_VOTE_AND_DISBURSEMENTS_HISTORY_WINNING_CAMPAIGN ?></th>
			<th width=15%><?= MSG_COMMUNITY_FUND_VOTE_AND_DISBURSEMENTS_HISTORY_VOTES_NUMBER ?></th>
			<th width=15%><?= MSG_COMMUNITY_FUND_VOTE_AND_DISBURSEMENTS_HISTORY_AMOUNT_DISBURSED ?></th>
		</tr>
		<?php $i = 1; ?>
		<?php foreach($voteAndDisbursementsHistoryData as $month=>$historyData):?>
		<?php
			$date_parts = explode('_', $month);
			$date = date('M, Y', strtotime($date_parts[1].'0'.$date_parts[0].'01'));
		?>
		<tr <?= $i == 0 ? 'class="secondary-line"' : '';?>>
			<td><?= $date ?></td>
			<td><a href="/<?= $historyData['campaign_url'] ?>"><?= $historyData['campaign_title'] ?></a></td>
			<td style="text-align: center;"><?= $historyData['max_votes'] ?></td>
			<td style="text-align: center;">$<?= $historyData['amount'] ?></td>
		</tr>
		<?php $i = ($i + 1)%2; ?>
		<?php endforeach;?>
	</table>
</aside>