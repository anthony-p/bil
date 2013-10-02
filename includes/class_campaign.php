<?php

/**
 *
 * @author Zakaria DOUGHI.
 *
 */

require_once("class_custom_field.php");

class Campaign extends custom_field {
	
	//--------------------------------------------------------------------------------------------------------------------------
	function getCommunityFundCampaignUrl(){
		$sql = "SELECT c.username FROM np_users c WHERE c.cfc = '1' AND MONTH(FROM_UNIXTIME(c.end_date)) = MONTH(NOW())";
		return '/'.$this->getField($sql);
	}
	
	//--------------------------------------------------------------------------------------------------------------------------
}