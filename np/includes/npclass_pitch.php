<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

class nppitch extends npcustom_field
{
	var $item;

	function insert ($pitch_details)
	{
        if (count($pitch_details) > 0) {
            $pitch_details = $this->rem_special_chars_array($pitch_details);

            $insert_query = "INSERT INTO project_pitch(project_id, amoun, name, description) VALUES";

            $i = 0;
            foreach ($pitch_details as $_pitch_details) {
                if ($i > 0)
                    $insert_query .= ',';
                $insert_query .= "(" .
                    $_pitch_details["project_id"] . ", " .
                    $_pitch_details["amoun"] . ", '" .
                    $_pitch_details["name"] . "', '" .
                    $_pitch_details["description"] .
                    "')";
                $i++;
            }

            $sql_insert_pitch = $this->query($insert_query);
        }
	}
}
