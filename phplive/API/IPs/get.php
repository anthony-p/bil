<?php
	/*****  IPs_::get
	 *
	 *  $Id: get.php,v 1.8 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION IPs_get_IPInfo( &$dbh,
					$ip )
	{
		if ( $ip == "" )
			return false ;

		LIST( $ip ) = database_mysql_quote( $ip ) ;

		$query = "SELECT * FROM p_ips WHERE ip = '$ip'" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

?>
