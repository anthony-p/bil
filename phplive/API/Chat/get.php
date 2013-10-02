<?php
	/*****  Chat_::get
	 *
	 *  $Id: get.php,v 1.8 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION Chat_get_IsOpInDept( &$dbh,
					$opid,
					$deptid )
	{
		if ( ( $opid == "" ) || ( $deptid == "" ) )
			return false ;

		LIST( $opid, $deptid ) = database_mysql_quote( $opid, $deptid ) ;

		$query = "SELECT * FROM p_dept_ops WHERE deptID = $deptid AND opID = $opid" ;
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
	FUNCTION Chat_get_RequestInfo( &$dbh,
					$requestid )
	{
		if ( $requestid == "" )
			return false ;

		LIST( $requestid ) = database_mysql_quote( $requestid ) ;

		$query = "SELECT * FROM p_requests WHERE requestID = $requestid" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Chat_get_RequestCesInfo( &$dbh,
					$ces )
	{
		if ( $ces == "" )
			return false ;

		LIST( $ces ) = database_mysql_quote( $ces ) ;

		$query = "SELECT * FROM p_requests WHERE ces = '$ces'" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Chat_get_RequestIPInfo( &$dbh,
					$ip,
					$initiate_flag )
	{
		if ( $ip == "" )
			return false ;

		LIST( $ip ) = database_mysql_quote( $ip ) ;

		$initiate_string = "" ;
		if ( $initiate_flag )
			$initiate_string = "AND initiated = 1" ;

		$query = "SELECT * FROM p_requests WHERE ip = '$ip' $initiate_string" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Chat_get_RequestHistCesInfo( &$dbh,
					$ces )
	{
		if ( $ces == "" )
			return false ;

		LIST( $ces ) = database_mysql_quote( $ces ) ;

		$query = "SELECT * FROM p_req_log WHERE ces = '$ces'" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Chat_get_Requests( &$dbh,
						$opid )
	{
		if ( $opid == "" )
			return false ;

		global $VARS_TRANSFER_BACK ;
		LIST( $opid ) = database_mysql_quote( $opid ) ;

		// if transfer (status = 2) and op2op is same, not a new request
		$query = "SELECT * FROM p_requests WHERE ( opID = $opid OR op2op = $opid OR opID = 1111111111 ) AND ( status = 0 OR status = 1 OR status = 2 ) ORDER BY created ASC" ;
		database_mysql_query( $dbh, $query ) ;

		$requests = Array() ;
		if ( $dbh[ 'ok' ] )
		{
			while ( $data = database_mysql_fetchrow( $dbh ) )
				$requests[] = $data ;
		}
		return $requests ;
	}

	/****************************************************************/
	FUNCTION Chat_get_OpTotalRequests( &$dbh,
						$opid )
	{
		if ( $opid == "" )
			return false ;

		LIST( $opid ) = database_mysql_quote( $opid ) ;

		$query = "SELECT count(*) AS total FROM p_requests WHERE ( opID = $opid OR op2op = $opid OR opID = 1111111111 ) AND ( status = 0 OR status = 1 OR status = 2 )" ;
		database_mysql_query( $dbh, $query ) ;

		$requests = Array() ;
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data["total"] ;
		}
		return 0 ;
	}

	/****************************************************************/
	FUNCTION Chat_get_IPTotalRequests( &$dbh,
						$ip )
	{
		if ( $ip == "" )
			return false ;

		LIST( $ip ) = database_mysql_quote( $ip ) ;

		$query = "SELECT count(*) AS total FROM p_req_log WHERE ip = '$ip'" ;
		database_mysql_query( $dbh, $query ) ;

		$requests = Array() ;
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data["total"] ;
		}
		return 0 ;
	}

	/****************************************************************/
	FUNCTION Chat_get_ActiveRequests( &$dbh )
	{
		global $VARS_EXPIRED_CHATS ;
		// if not updated since $expired then chat has ended... 1 minute is the
		// max poll time so make it over 1 minute
		$expired = time() - $VARS_EXPIRED_CHATS ;

		// todo: 
		// $query = "SELECT * FROM p_requests WHERE updated >= $expired AND vupdated >= $expired OR op2op <> 0 AND ( status = 1 OR status = 2 )" ;
		$query = "SELECT * FROM p_requests WHERE op2op != 0 OR status = 2 OR ( ( status = 0 AND vupdated >= $expired ) OR ( status = 1 AND ( updated >= $expired ) ) )" ;
		database_mysql_query( $dbh, $query ) ;

		$requests = Array() ;
		if ( $dbh[ 'ok' ] )
		{
			while ( $data = database_mysql_fetchrow( $dbh ) )
				$requests[] = $data ;
			return $requests ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Chat_get_OpRecentRatings( &$dbh,
						$opid )
	{
		if ( $opid == "" )
			return false ;

		LIST( $opid ) = database_mysql_quote( $opid ) ;

		$query = "SELECT ces, rating FROM p_transcripts WHERE opID = $opid ORDER BY created DESC LIMIT 1" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Chat_get_OpOverallRatings( &$dbh,
						$opid )
	{
		if ( $opid == "" )
			return false ;

		LIST( $opid ) = database_mysql_quote( $opid ) ;

		$query = "SELECT count(*) AS total, SUM(rating) AS rating FROM p_transcripts WHERE opID = $opid AND rating <> 0" ;
		database_mysql_query( $dbh, $query ) ;

		$output = Array() ;
		if ( $dbh[ 'ok' ] )
			$output = database_mysql_fetchrow( $dbh ) ;

		return $output ;
	}

?>
