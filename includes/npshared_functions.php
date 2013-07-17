<?
#################################################################
##Mainsail															##
##functions that may need to be shared by the regular site and the np (non profit login and registration pages) 
##in order to make the landing page work we need to access the np db and then move us to the regular site
#################################################################

function count_orgrows($table_name, $condition = null, $debug = false)
	{
		$query_result = $this->query("SELECT count(*) AS count_rows FROM " . $this->npdb_prefix . $table_name . " " . $condition, $debug);
		$count_rows = $this->sql_result($query_result, 0, 'count_rows');

		return $count_rows;

	}





?>
