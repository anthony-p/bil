<?php

/**
 *
 * @author Zakaria DOUGHI.
 *
 */

require_once("config.php");
require_once("class_custom_field.php");
require_once(__DIR__ . '/../language/english/site.lang.php');

class projectVotes extends custom_field
{
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
        if ($this->user_id) {
            $donated = $this->getField("SELECT count(*) FROM funders WHERE user_id=" .
                $this->user_id);
            if ($donated) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    function checkVoted()
    {
        $compare_date = time() - (3600 * 24 * 30);
        if ($this->user_id && $this->campaign_id) {
            $voted = $this->getField("SELECT count(*) FROM project_votes WHERE user_id=" .
                $this->user_id . " AND date>" . $compare_date);
            if ($voted) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return int|string
     */
    function getVotesByCampaign()
    {
        if ($this->campaign_id) {
            return $this->getField("SELECT count(*) FROM project_votes WHERE campaign_id=" . $this->campaign_id);
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
    function getVotesElement()
    {
        if ($this->user_id && $this->campaign_id) {
            $voted = $this->checkVoted();
            $donated = $this->checkDonated();
            if ($donated) {
                if ($voted) {
                    $this->votes_element = '<h5>' . $this->getVotesByCampaign() . ' ' . MSG_VOTES_NUMBER . '</h5>';
                } else {
                    $this->votes_element = '<button id="vote_us">' . MSG_VOTE_US . '</button>';
                }
            }
        }

        return $this->votes_element;
    }

    function vote()
    {
        $success = false;
        if ($this->user_id && $this->campaign_id) {
            $voted = $this->checkVoted();
            if (!$voted) {
                $this->query("INSERT INTO project_votes(user_id, campaign_id, date) VALUES(" .
                    $this->user_id . ", " . $this->campaign_id . ", " . time() . ")");
                $this->votes_element = '<h5>' . $this->getVotesByCampaign() . ' ' . MSG_VOTES_NUMBER . '</h5>';
                $success = true;
            }
        }
        return array(
            "success" => $success,
            "vote_us" => $this->votes_element,
        );
    }
	
	function getVotesReportData($month, $year){
		$sql = "SELECT COUNT(v.id) AS campaign_votes_number, c.project_title AS campaign_title, MONTH(FROM_UNIXTIME(v.date)) AS month, YEAR(FROM_UNIXTIME(v.date)) AS year FROM project_votes v JOIN np_users c ON c.user_id = v.campaign_id GROUP BY v.campaign_id, month, year HAVING month='".$month."' AND year='".$year."' ORDER BY campaign_votes_number DESC";
		$project_votes_query_result = $this->query($sql);

		while ($query_result =  mysql_fetch_array($project_votes_query_result)) {
			$project_votes[] = $query_result;
		}
		return $project_votes;
	}

    /**
     * @param $reward
     * @param $user_id
     * @return string
     */
    function update($reward, $user_id)
    {
        if (!empty($reward['id']) && !empty($user_id)) {
			$has_rights = $this->getField("Select count(*) FROM project_rewards r left join np_users c on r.project_id = c.user_id WHERE r.id='".$reward['id']."' and c.probid_user_id='".$user_id."'");
			if($has_rights == 1){
				if($this->isAmountUsed($reward['amount'], '', $reward['id'])){
					return MSG_REWARD_AMOUNT_EXIST;
				}
				
				if($this->isNameUsed($reward['name'], '', $reward['id'])){
					return MSG_REWARD_NAME_EXIST;
				}
				$this->query("UPDATE project_rewards SET amount='".$reward['amount']."', name='".$reward['name']."', description='".$reward['description']."', available_number=".$reward['available_number'].", estimated_delivery_date=".$reward['estimated_delivery_date'].", shipping_address_required='".$reward['shipping_address_required']."' WHERE id='".$reward['id']."'");
				return MSG_REWARD_SAVED;
			} else {
				return MSG_ACCESS_DENIED;
			}
		} else {
            return "error";
        }
    }
	
	//--------------------------------------------------------------------------------------------------------------------------
}