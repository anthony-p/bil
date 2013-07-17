<?php
	/*****  Footprints_::get
	 *
	 *  $Id: get.php,v 1.8 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION Footprints_get_RequestsRangeHash( &$dbh )
	{
		return Array() ;
	}

	/****************************************************************/
	FUNCTION Footprints_get_Footprints_U( &$dbh,
					$dept_string )
	{
		LIST( $dept_string ) = database_mysql_quote( $dept_string ) ;

		// todo: chat_actions_op.php - look there [mod Sam: 60]
		//$query = "SELECT * FROM p_footprints_u WHERE ( deptID = 0 $dept_string ) ORDER BY created ASC" ;
		$query = "SELECT p_footprints_u.created AS created, p_footprints_u.updated AS updated, p_footprints_u.deptID AS deptID, p_footprints_u.marketID AS marketID, p_footprints_u.chatting AS chatting, p_footprints_u.os AS os, p_footprints_u.browser AS browser, p_footprints_u.resolution AS resolution, p_footprints_u.ip AS ip, p_footprints_u.hostname AS hostname, p_footprints_u.onpage AS onpage, p_footprints_u.title AS title, p_footprints_u.refer AS refer, p_ips.t_footprints AS t_footprints, p_ips.t_requests AS t_requests, p_ips.t_initiate AS t_initiate FROM p_footprints_u LEFT JOIN p_ips ON p_footprints_u.ip = p_ips.ip ORDER BY p_footprints_u.created ASC" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$output = Array() ;
			while ( $data = database_mysql_fetchrow( $dbh ) )
				$output[] = $data ;
			return $output ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Footprints_get_TotalFootprints_U( &$dbh )
	{
		$query = "SELECT count(*) AS total FROM p_footprints_u" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data["total"] ;
		}
		return 0 ;
	}

	/****************************************************************/
	FUNCTION Footprints_get_IPFootprints_U( &$dbh,
					$ip )
	{
		if ( $ip == "" )
			return false ;

		LIST( $ip ) = database_mysql_quote( $ip ) ;

		$query = "SELECT * FROM p_footprints_u WHERE ip = '$ip'" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

?>
