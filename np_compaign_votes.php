<?
session_start();

include_once ('includes/global.php');
include_once ('includes/class_project_votes.php');
include_once ('includes/npfunctions_login.php');

if($_POST['getCampaignsVotes'] == true){
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$projectVotes   = new projectVotes();
	$currentMonthVoteReport = $projectVotes->getVotesReportData(date('n'), date('Y'), $page);
	$i = 1;
	$result = ' <tr>
					<th>'.MSG_COMMUNITY_FUND_VOTE_REPORT_CAMPAIGN_TITLE.'</th>
					<th>'.MSG_COMMUNITY_FUND_VOTE_REPORT_VOTES.'</th>
				</tr>';
	foreach($currentMonthVoteReport as $campaignVotes):
		$result .= '<tr '.($i == 0 ? 'class="secondary-line"' : '').'>
						<td><a href="/'.$campaignVotes['campaign_url'].'">'.$campaignVotes['campaign_title'].'</a></td>
						<td style="text-align: center;">'.$campaignVotes['campaign_votes_number'].'</td>
					</tr>';
		$i = ($i + 1)%2;
	endforeach;
	
	echo json_encode(array("response" => $result));
}