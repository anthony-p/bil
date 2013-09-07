<?php

/**
 *
 * @author Zakaria DOUGHI.
 *
 */

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
    public function __construct($user_id, $campaign_id)
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
        if ($this->user_id && $this->campaign_id) {
            $donated = $this->getField("SELECT count(*) FROM funders WHERE user_id=" .
                $this->user_id . " AND campaign_id=" . $this->campaign_id);
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
        if ($this->user_id && $this->campaign_id) {
            $voted = $this->getField("SELECT count(*) FROM project_votes WHERE user_id=" .
                $this->user_id . " AND campaign_id=" . $this->campaign_id);
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
            $this->query("INSERT INTO project_votes(user_id, campaign_id, date) VALUES(" .
                $this->user_id . ", " . $this->campaign_id . ", " . time() . ")");
            $this->votes_element = '<h5>' . $this->getVotesByCampaign() . ' ' . MSG_VOTES_NUMBER . '</h5>';
            $success = true;
        }
        return array(
            "success" => $success,
            "vote_us" => $this->votes_element,
        );
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
	function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
}