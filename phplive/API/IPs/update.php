<?php
	/*****  IPs_::update
	 *
	 *  $Id: update.php,v 1.6 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION IPs_update_FootprintUniqueValue( &$dbh,
					  $ip,
					  $tbl_name,
					  $value )
	{
		if ( ( $ip == "" ) || ( $tbl_name == "" ) || ( $value == "" ) )
			return false ;

		LIST( $ip, $tbl_name, $value ) = database_mysql_quote( $ip, $tbl_name, $value ) ;

		$query = "UPDATE p_IPs_u SET $tbl_name = '$value' WHERE ip = '$ip'" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$flag = database_mysql_nresults( $dbh ) ;
			return $flag ;
		}
		return false ;
	}

?>
