<?php
	/*****  Chat_ext_::get
	 *
	 *  $Id: get.php,v 1.8 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION Chat_ext_get_RequestsRangeHash( &$dbh,
								$stat_start,
								$stat_end )
	{
		if ( ( $stat_start == "" ) || ( $stat_end == "" ) )
			return false ;

		LIST( $stat_start, $stat_end ) = database_mysql_quote( $stat_start, $stat_end ) ;

		$query = "SELECT * FROM p_reqstats WHERE sdate >= $stat_start AND sdate < $stat_end" ;
		database_mysql_query( $dbh, $query ) ;

		$output = Array() ;
		$output[0] = Array() ; $output[0]["depts"] = Array() ; $output[0]["ops"] = Array() ;
		if ( $dbh[ 'ok' ] )
		{
			while( $data = database_mysql_fetchrow( $dbh ) )
			{
				$sdate = $data["sdate"] ;
				$deptid = $data["deptID"] ;
				$opid = $data["opID"] ;

				if ( !isset( $output[$sdate] ) )
				{
					$output[$sdate] = Array() ;
					$output[$sdate]["depts"] = Array() ;
					$output[$sdate]["ops"] = Array() ;
				}
				
				if ( !isset( $output[$sdate]["depts"][$deptid] ) && $deptid )
				{
					$output[$sdate]["depts"][$deptid] = Array() ;
					$output[$sdate]["depts"][$deptid]["taken"] = 0 ;
					$output[$sdate]["depts"][$deptid]["declined"] = 0 ;
					$output[$sdate]["depts"][$deptid]["message"] = 0 ;
					$output[$sdate]["depts"][$deptid]["initiated"] = 0 ;
					$output[$sdate]["depts"][$deptid]["initiated_taken"] = 0 ;
					$output[$sdate]["depts"][$deptid]["requests"] = 0 ;
				}

				if ( !isset( $output[$sdate]["ops"][$opid] ) && $opid )
				{
					$output[$sdate]["ops"][$opid] = Array() ;
					$output[$sdate]["ops"][$opid]["taken"] = 0 ;
					$output[$sdate]["ops"][$opid]["declined"] = 0 ;
					$output[$sdate]["ops"][$opid]["message"] = 0 ;
					$output[$sdate]["ops"][$opid]["initiated"] = 0 ;
					$output[$sdate]["ops"][$opid]["initiated_taken"] = 0 ;
					$output[$sdate]["ops"][$opid]["requests"] = 0 ;
				}

				if ( !isset( $output[0]["depts"][$deptid] ) && $deptid )
				{
					$output[0]["depts"][$deptid] = Array() ;
					$output[0]["depts"][$deptid]["taken"] = 0 ;
					$output[0]["depts"][$deptid]["declined"] = 0 ;
					$output[0]["depts"][$deptid]["message"] = 0 ;
					$output[0]["depts"][$deptid]["initiated"] = 0 ;
					$output[0]["depts"][$deptid]["initiated_taken"] = 0 ;
					$output[0]["depts"][$deptid]["requests"] = 0 ;
				}
				if ( !isset( $output[0]["ops"][$opid] ) && $opid )
				{
					$output[0]["ops"][$opid] = Array() ;
					$output[0]["ops"][$opid]["taken"] = 0 ;
					$output[0]["ops"][$opid]["declined"] = 0 ;
					$output[0]["ops"][$opid]["message"] = 0 ;
					$output[0]["ops"][$opid]["initiated"] = 0 ;
					$output[0]["ops"][$opid]["initiated_taken"] = 0 ;
					$output[0]["ops"][$opid]["requests"] = 0 ;
				}

				if ( $opid )
				{
					$output[0]["ops"][$opid]["taken"] += $data["taken"] ;
					$output[0]["ops"][$opid]["declined"] += $data["declined"] ;
					$output[0]["ops"][$opid]["message"] += $data["message"] ;
					$output[0]["ops"][$opid]["initiated"] += $data["initiated"] ;
					$output[0]["ops"][$opid]["initiated_taken"] += $data["initiated_taken"] ;
					$output[0]["ops"][$opid]["requests"] += $data["requests"] ;

					$output[$sdate]["ops"][$opid]["taken"] += $data["taken"] ;
					$output[$sdate]["ops"][$opid]["declined"] += $data["declined"] ;
					$output[$sdate]["ops"][$opid]["message"] += $data["message"] ;
					$output[$sdate]["ops"][$opid]["initiated"] += $data["initiated"] ;
					$output[$sdate]["ops"][$opid]["initiated_taken"] += $data["initiated_taken"] ;
					$output[$sdate]["ops"][$opid]["requests"] += $data["requests"] ;
				}

				$output[0]["depts"][$deptid]["taken"] += $data["taken"] ;
				$output[0]["depts"][$deptid]["declined"] += $data["declined"] ;
				$output[0]["depts"][$deptid]["message"] += $data["message"] ;
				$output[0]["depts"][$deptid]["initiated"] += $data["initiated"] ;
				$output[0]["depts"][$deptid]["initiated_taken"] += $data["initiated_taken"] ;
				$output[0]["depts"][$deptid]["requests"] += $data["requests"] ;

				$output[$sdate]["depts"][$deptid]["taken"] += $data["taken"] ;
				$output[$sdate]["depts"][$deptid]["declined"] += $data["declined"] ;
				$output[$sdate]["depts"][$deptid]["message"] += $data["message"] ;
				$output[$sdate]["depts"][$deptid]["initiated"] += $data["initiated"] ;
				$output[$sdate]["depts"][$deptid]["initiated_taken"] += $data["initiated_taken"] ;
				$output[$sdate]["depts"][$deptid]["requests"] += $data["requests"] ;
			}
		}
		return $output ;
	}

	/****************************************************************/
	FUNCTION Chat_ext_get_IPTranscripts( &$dbh,
								$ip,
								$limit )
	{
		if ( ( $ip == "" ) || ( $limit == "" ) )
			return false ;

		LIST( $ip, $limit ) = database_mysql_quote( $ip, $limit ) ;

		$query = "SELECT * FROM p_transcripts WHERE ip = '$ip' ORDER BY created DESC LIMIT $limit" ;
		database_mysql_query( $dbh, $query ) ;

		$output = Array() ;
		if ( $dbh[ 'ok' ] )
		{
			while( $data = database_mysql_fetchrow( $dbh ) )
				$output[] = $data ;
		}
		return $output ;
	}

	/****************************************************************/
	FUNCTION Chat_ext_get_TotalIPTranscripts( &$dbh,
								$ip )
	{
		if ( $ip == "" )
			return false ;

		LIST( $ip ) = database_mysql_quote( $ip ) ;

		$query = "SELECT count(*) AS total FROM p_transcripts WHERE ip = '$ip'" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
				return $data["total"] ;
		}
		return 0 ;
	}

	/****************************************************************/
	FUNCTION Chat_ext_get_RefinedTranscripts( &$dbh,
								$deptid,
								$opid,
								$text,
								$page,
								$limit )
	{
		if ( ( $deptid == "" ) || ( $opid == "" ) || ( $limit == "" ) )
			return false ;

		LIST( $deptid, $opid, $text, $page, $limit ) = database_mysql_quote( $deptid, $opid, $text, $page, $limit ) ;
		$start = ( $page * $limit ) ;

		$search_string = "" ;
		if ( $text )
			$search_string = " AND plain LIKE '%$text%' " ;

		if ( $deptid )
		{
			$query = "SELECT * FROM p_transcripts WHERE deptID = $deptid $search_string ORDER BY created DESC LIMIT $start, $limit" ;
			$query2 = "SELECT count(*) AS total FROM p_transcripts WHERE deptID = $deptid $search_string" ;
		}
		else if ( $opid )
		{
			$query = "SELECT * FROM p_transcripts WHERE opID = $opid $search_string ORDER BY created DESC LIMIT $start, $limit" ;
			$query2 = "SELECT count(*) AS total FROM p_transcripts WHERE opID = $opid $search_string" ;
		}
		else
		{
			$query = "SELECT * FROM p_transcripts WHERE created > 0 $search_string ORDER BY created DESC LIMIT $start, $limit" ;
			$query2 = "SELECT count(*) AS total FROM p_transcripts WHERE created > 0 $search_string" ;
		}
		database_mysql_query( $dbh, $query ) ;

		$output = Array() ;
		if ( $dbh[ 'ok' ] )
		{
			while( $data = database_mysql_fetchrow( $dbh ) )
				$output[] = $data ;
		}

		database_mysql_query( $dbh, $query2 ) ;
		$data = database_mysql_fetchrow( $dbh ) ;
		$output[] = $data["total"] ;

		return $output ;
	}

	/****************************************************************/
	FUNCTION Chat_ext_get_Transcript( &$dbh,
								$ces )
	{
		if ( $ces == "" )
			return false ;

		LIST( $ces ) = database_mysql_quote( $ces ) ;

		$query = "SELECT * FROM p_transcripts WHERE ces = '$ces'" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Chat_ext_get_OpDeptTrans( &$dbh,
								$opid,
								$text,
								$page,
								$limit )
	{
		if ( ( $opid == "" ) || ( $limit == "" ) )
			return false ;

		global $CONF ;
		include_once( realpath( "$CONF[DOCUMENT_ROOT]/API/Depts/get.php" ) ) ;

		LIST( $opid, $text, $page, $limit ) = database_mysql_quote( $opid, $text, $page, $limit ) ;
		$start = ( $page * $limit ) ;

		$departments = Depts_get_OpDepts( $dbh, $opid ) ;
		$dept_string = " ( opID = $opid OR op2op = $opid " ;
		for ( $c = 0; $c < count( $departments ); ++$c )
		{
			if ( $departments[$c]["tshare"] )
				$dept_string .= " OR deptID = " . $departments[$c]["deptID"] ;
		}
		$dept_string .= " ) " ;

		$search_string = "" ;
		if ( $text )
			$search_string = " plain LIKE '%$text%' AND " ;

		$query = "SELECT * FROM p_transcripts WHERE $search_string $dept_string ORDER BY created DESC LIMIT $start, $limit" ;
		database_mysql_query( $dbh, $query ) ;

		$output = Array() ;
		if ( $dbh[ 'ok' ] )
		{
			while( $data = database_mysql_fetchrow( $dbh ) )
				$output[] = $data ;
		}

		$query = "SELECT count(*) AS total FROM p_transcripts WHERE $search_string $dept_string" ;
		database_mysql_query( $dbh, $query ) ;
		$data = database_mysql_fetchrow( $dbh ) ;
		$output[] = $data["total"] ;

		return $output ;
	}

	/****************************************************************/
	FUNCTION Chat_ext_get_AllRequests( &$dbh )
	{
		$query = "SELECT * FROM p_requests ORDER BY created ASC" ;
		database_mysql_query( $dbh, $query ) ;

		$output = Array() ;
		if ( $dbh[ 'ok' ] )
		{
			while( $data = database_mysql_fetchrow( $dbh ) )
				$output[] = $data ;
			return $output ;
		}
		return false ;
	}

?>
