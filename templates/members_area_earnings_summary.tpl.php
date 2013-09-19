<div class="contributions_page">
    <h2><?= MSG_MY_EARNINGS_SUMMARY_REPORT_TITLE ?></h2>
	<?php if(empty($campaigns_earnings)): ?>
		<div class="no_earnings_message"><?= MSG_MY_EARNINGS_SUMMARY_REPORT_NO_EARNINGS_MADE_SO_FAR ?></div>
	<?php else: ?>
		<table>
			<tr class="table_header">
				<td width="30%" style="text-align: center;"><h4><?= MSG_MY_EARNINGS_SUMMARY_REPORT_DATE ?></h4></td>
				<td><h4><?= MSG_MY_EARNINGS_SUMMARY_REPORT_CAMPAIGN_NAME ?></h4></td>
				<td width="15%" style="text-align: center;"><h4><?= MSG_MY_EARNINGS_SUMMARY_REPORT_TOTAL_EARNED ?></h4></td>
			</tr>
			<?php foreach($campaigns_earnings as $earning_data):?>
			<tr>
				<?php
				$ended = $earning_data['end_date'] < time();
				$end_date = $ended ? date("m/d/Y", $earning_data['end_date']) : " ...";
				?>
				<td style="text-align: center;"><?= date("m/d/Y", $earning_data['reg_date'])?> - <?= $end_date ?></td>
				<td><a href="/<?= $earning_data['project_url'] ?>"><?= $earning_data['project_title'] ?></a></td>
				<td style="text-align: center;">$<?= $earning_data['payment'] ?> <?= $ended ? '' : MSG_MY_EARNINGS_SUMMARY_SO_FAR ?></td>
			</tr>
			<?php endforeach;?>
		</table>
	<?php endif; ?>  
</div>