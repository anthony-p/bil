<?php
require_once("config.php");
require_once("class_custom_field.php");
require_once(__DIR__ . '/../language/english/site.lang.php');

class projectVotes extends custom_field
{
	var $campaignsNumberPerPageInReport = 15;
    /**
     * @var int
     */
    private $user_id = 0;

    /**
     * @var int
     */
    private $campaign_id = 0;

    /**
     * @var string
     */
    private $votes_element = '';
	
    /**
     * @param $user_id
     * @param $campaign_id
     */
    public function __construct($user_id=NULL, $campaign_id=NULL)
    {
        if ($user_id) {
            $this->user_id = $user_id;
        }
        if ($campaign_id) {
            $this->campaign_id = $campaign_id;
        }
    }

    function checkDonated()
    {
//        return true;
        if ($this->user_id) {
            return $this->getField("SELECT count(*) FROM funders WHERE user_id=".$this->user_id .
            " and MONTH(FROM_UNIXTIME(created_at)) = MONTH(NOW()) and YEAR(FROM_UNIXTIME(created_at)) = YEAR(NOW()) AND DAY(FROM_UNIXTIME(created_at)) = DAY(NOW())");
        }
        return false;
    }

    /**
     * @return bool
     */
    function checkVoted()
    {
        if ($this->user_id && $this->campaign_id) {
            $voted = $this->getField("SELECT count(*) FROM project_votes WHERE user_id=" .
                $this->user_id . " AND MONTH(FROM_UNIXTIME(date)) = MONTH(NOW()) AND YEAR(FROM_UNIXTIME(date)) = YEAR(NOW()) AND DAY(FROM_UNIXTIME(date)) = DAY(NOW())");
            if ($voted) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    function checkCfc() {
        if ($this->campaign_id) {
            return $this->getField("SELECT cfc FROM np_users WHERE user_id=".$this->campaign_id);
        }
        return false;
    }

    /**
     * @return int|string
     */
    function getVotesByCampaign()
    {
        if ($this->campaign_id) {
            return $this->getField("SELECT count(*) FROM project_votes WHERE campaign_id=".$this->campaign_id." and MONTH(FROM_UNIXTIME(date)) = MONTH(NOW()) and YEAR(FROM_UNIXTIME(date)) = YEAR(NOW())");
        }
        return 0;
    }

    /**
     * @return string
     */
    function getUserEmail()
    {
        if ($this->user_id) {
            return $this->getField("SELECT email FROM bl2_users WHERE id=" . $this->user_id);
        }
        return '';
    }

    /**
     * @return string
     */
    function getCampaignOwnerEmail()
    {
        if ($this->campaign_id) {
            return $this->getField("SELECT bl2_users.email FROM bl2_users RIGHT JOIN np_users
            ON (bl2_users.id=np_users.probid_user_id) WHERE np_users.user_id=" . $this->campaign_id);
        }
        return '';
    }

    /**
     * @return string
     */
    function getVotesElement($end_date="", $campaign_owner="")
    {
        if ($this->campaign_id && !$this->checkCfc()) {
            if ($this->user_id && $this->checkDonated() && !$this->checkVoted()) {
				$days=round(($end_date-time())/86400);
				if($days>0 && $campaign_owner != $this->user_id ){
					$this->votes_element = '<button id="vote_us">' . MSG_VOTE_US . '</button>';
				}
			}
			if(empty($this->votes_element)) {
				$this->votes_element = '<span class="votes-amount">'.$this->getVotesByCampaign().' '.MSG_VOTES_NUMBER . '</span>';
			}
        }
        return $this->votes_element;
    }

    function vote()
    {
        $success = false;
//        $sql_insert_project_votes = $sql_update_votes_number_query = $insert_result = $update_result = false;
        if ($this->user_id && $this->campaign_id && !$this->checkCfc()) {
            $voted = $this->checkVoted();
            if (!$voted) {
                $sql_insert_project_votes = "INSERT INTO project_votes(user_id, campaign_id, date) VALUES(" .
                    $this->user_id . ", " . $this->campaign_id . ", " . time() . ")";
                $insert_result = $this->query($sql_insert_project_votes);
                $sql_update_votes_number_query = "UPDATE " . NPDB_PREFIX . "users SET votes=IFNULL(votes, 0) + 1
                WHERE user_id={$this->campaign_id}";

                $update_result = $this->query($sql_update_votes_number_query);
                $this->votes_element = '<span class="votes-amount">' . $this->getVotesByCampaign() . ' ' . MSG_VOTES_NUMBER . '</span>';
                $success = true;
            }
        }
        return array(
            "success" => $success,
            "vote_us" => $this->votes_element,
//            "queries" => array(
//                "insert" => array(
//                    "query" => $sql_insert_project_votes,
//                    "result" =>$insert_result,
//                ),
//                "update" => array(
//                    "query" => $sql_update_votes_number_query,
//                    "result" => $update_result,
//                ),
//            ),
        );
    }
	
	function getVotesReportData($month, $year, $page=0){
		$start = $page * $this->campaignsNumberPerPageInReport;
		$end = ($page+1) * $this->campaignsNumberPerPageInReport;
		
		$sql = "SELECT COUNT(v.id) AS campaign_votes_number, c.project_title AS campaign_title, c.username AS campaign_url, MONTH(FROM_UNIXTIME(v.date)) AS month, YEAR(FROM_UNIXTIME(v.date)) AS year FROM project_votes v JOIN np_users c ON c.user_id = v.campaign_id GROUP BY v.campaign_id, month, year HAVING month='".$month."' AND year='".$year."' ORDER BY campaign_votes_number DESC LIMIT ".$start.", ".$end;
		$project_votes_query_result = $this->query($sql);

        $project_votes = array();
		while ($query_result =  mysql_fetch_array($project_votes_query_result)) {
			$project_votes[] = $query_result;
		}
		return $project_votes;
	}
	
	function getCampaignsNumberThatHaveVotes($month, $year){
		$sql = "SELECT COUNT(DISTINCT v.campaign_id), MONTH(FROM_UNIXTIME(v.date)) AS MONTH, YEAR(FROM_UNIXTIME(v.date)) AS YEAR FROM project_votes v GROUP BY MONTH, YEAR HAVING MONTH='".$month."' AND YEAR='".$year."'";
		
		return $this->getField($sql);
	}
	
	function getVotesAndDisbursementsHistoryData(){
		$result = array();
		
		$sql = "SELECT CONCAT(MONTH(FROM_UNIXTIME(end_date)), '_', YEAR(FROM_UNIXTIME(end_date))) as date, payment FROM np_users p WHERE p.cfc='1' AND FROM_UNIXTIME(end_date) < NOW()";
		
		$data_query_result = $this->query($sql);

		while ($query_result =  mysql_fetch_array($data_query_result)) {
			$result[$query_result['date']] = array('amount' => $query_result['payment']);
		}
		
		$sql = "SELECT CONCAT(MONTH, '_', YEAR) AS date, MAX(count_votes) AS max_votes FROM (SELECT COUNT(v.id) AS count_votes, MONTH(FROM_UNIXTIME(v.date)) AS MONTH, YEAR(FROM_UNIXTIME(v.date)) AS YEAR FROM project_votes v GROUP BY v.campaign_id, MONTH, YEAR) t GROUP BY DATE order by DATE DESC";
		
		$data_query_result = $this->query($sql);
		while ($query_result =  mysql_fetch_array($data_query_result)) {
			$key = $query_result['date'];
			if(array_key_exists($key, $result)){
				$result[$key]['max_votes'] = $query_result['max_votes'];
			}
		}
		
		foreach($result as $key=>$value){
			if(isset($value['max_votes'])){
				$campaign_info = mysql_fetch_array($this->query("SELECT c.project_title as campaign_title, c.username as campaign_url FROM project_votes v join np_users c on v.campaign_id=c.user_id GROUP BY v.campaign_id, MONTH(FROM_UNIXTIME(v.date)), YEAR(FROM_UNIXTIME(v.date)) having COUNT(v.id)='".$value['max_votes']."'"));
				$result[$key]['campaign_title'] = $campaign_info['campaign_title'];
				$result[$key]['campaign_url'] = $campaign_info['campaign_url'];
			}
		}
		
		return $result;
	}
}