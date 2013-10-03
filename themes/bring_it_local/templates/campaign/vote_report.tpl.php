<script>
	function getCampaingsVotes(page, element){
		$.ajax({
			url:"/np_compaign_votes",
			type: "POST",
			data: {getCampaignsVotes: true, page: page},
			success: function(response){
				$(".resp-tab-content aside.vote_report_tab .votes_report_pagination a").removeClass("current_page_link");
				element.addClass("current_page_link");
				campaignsVotesContent = jQuery.parseJSON(response).response;
				$("#vote_report_table").html(campaignsVotesContent);
			},
			error:function(){
				alert("Error");
			}
		});
	}
</script>
<aside class="announcement vote_report_tab" id="vote_report_tab_content">
	<h1><?= MSG_COMMUNITY_FUND_VOTE_REPORT_TITLE; ?></h1>
	<div class="vote_report_info"><?= MSG_COMMUNITY_FUND_VOTE_REPORT_MONTH ?>: <div><?= $voteReportMonth ?></div></div>
	<div class="vote_report_info"><?= MSG_COMMUNITY_FUND_VOTE_TODAY_DATE ?>: <div><?= $todaysDate ?></div></div>
	<div class="vote_report_info"><?= MSG_COMMUNITY_FUND_HAS_THIS_MUCH ?>: <div>$<?= $communityTotalFund ?></div></div>
	<?php if(!empty($voteReportData)): ?>
		<table id="vote_report_table">
			<tr>
				<th><?= MSG_COMMUNITY_FUND_VOTE_REPORT_CAMPAIGN_TITLE ?></th>
				<th><?= MSG_COMMUNITY_FUND_VOTE_REPORT_VOTES ?></th>
			</tr>
			<?php $i = 1; ?>
			<?php foreach($voteReportData as $campaignVotes):?>
			<tr <?= $i == 0 ? 'class="secondary-line"' : '';?>>
				<td><a href="/<?= $campaignVotes['campaign_url'] ?>"><?= $campaignVotes['campaign_title'] ?></a></td>
				<td style="text-align: center;"><?= $campaignVotes['campaign_votes_number'] ?></td>
			</tr>
			<?php $i = ($i + 1)%2; ?>
			<?php endforeach;?>
		</table>
		<div class="votes_report_pagination">
		<?php if($campaignsPagesNumber > 1):?>
		<?php for($k=0; $k<$campaignsPagesNumber; $k++):?>
			<a <?= $k == 0 ? 'class="current_page_link"' : ''?> onclick="getCampaingsVotes(<?= $k ?>, $(this))"><?= $k + 1 ?></a>
		<?php endfor; ?>
		<?php endif; ?>
		</div>
	<?php else: ?>
		<div class="no_votes_message"><?= MSG_COMMUNITY_FUND_NO_VOTES_MADE_IN_CURRENT_MONTH ?></div>
	<?php endif; ?>
</aside>