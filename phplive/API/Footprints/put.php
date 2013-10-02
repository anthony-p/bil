<?php
	/*****  Footprints_::put
	 *
	 *  $Id: put.php,v 1.9 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION Footprints_put_Print( &$dbh,
					$deptid,
					$os,
					$browser,
					$ip,
					$onpage,
					$title )
	{
		if ( ( $deptid == "" ) || ( $os == "" ) || ( $browser == "" )
			|| ( $ip == "" ) || ( $onpage == "" ) )
			return false ;

		$now = time() ;
		$mdfive = md5( $onpage ) ;

		LIST( $deptid, $os, $browser, $ip, $mdfive, $onpage, $title ) = database_mysql_quote( $deptid, $os, $browser, $ip, $mdfive, $onpage, $title ) ;

		$query = "INSERT INTO p_footprints VALUES ( $now, '$ip', $os, $browser, '$mdfive', '$onpage', '$title' )" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
			return true ;

		return false ;
	}

	/****************************************************************/
	FUNCTION Footprints_put_Print_U( &$dbh,
					$deptid,
					$os,
					$browser,
					$resolution,
					$ip,
					$onpage,
					$title,
					$marketid,
					$refer )
	{
		if ( ( $deptid == "" ) || ( $os == "" ) || ( $browser == "" )
			|| ( $ip == "" ) || ( $onpage == "" ) )
			return false ;

		// todo: set own md5 instead of using ip (track multiple visitors from one ip - but rare incidents)
		$now = time() ;
		$hostname = gethostbyaddr( $ip ) ;
		$agent = $_SERVER["HTTP_USER_AGENT"] ;

		LIST( $deptid, $os, $browser, $resolution, $ip, $hostname, $agent, $onpage, $title, $marketid, $refer ) = database_mysql_quote( $deptid, $os, $browser, $resolution, $ip, $hostname, $agent, $onpage, $title, $marketid, $refer ) ;

		$query = "INSERT INTO p_footprints_u VALUES ( $now, $now, $deptid, $marketid, 0, $os, $browser, '$resolution', '$ip', '$hostname', '$agent', '$onpage', '$title', '$refer' )" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
			return true ;

		return false ;
	}

	/****************************************************************/
	FUNCTION Footprints_put_PrintStat( &$dbh,
					$sdate,
					$total,
					$onpage )
	{
		if ( ( $sdate == "" ) || ( $onpage == "" ) )
			return false ;

		LIST( $sdate, $total, $onpage ) = database_mysql_quote( $sdate, $total, $onpage ) ;

		$query = "INSERT INTO p_footstats VALUES ( $sdate, $total, '$onpage' )" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
			return true ;

		return false ;
	}

	/****************************************************************/
	FUNCTION Footprints_put_ReferStat( &$dbh,
					$sdate,
					$total,
					$onpage )
	{
		if ( ( $sdate == "" ) || ( $onpage == "" ) )
			return false ;

		LIST( $sdate, $total, $onpage ) = database_mysql_quote( $sdate, $total, $onpage ) ;

		$query = "INSERT INTO p_referstats VALUES ( $sdate, $total, '$onpage' )" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
			return true ;

		return false ;
	}

	/****************************************************************/
	FUNCTION Footprints_put_Refer( &$dbh,
					$ip,
					$marketid,
					$refer )
	{
		if ( $ip == "" )
			return false ;

		$now = time() ;
		$mdfive = md5( $refer ) ;

		LIST( $ip, $marketid, $refer ) = database_mysql_quote( $ip, $marketid, $refer ) ;

		$query = "INSERT INTO p_refer VALUES ( '$ip', $now, $marketid, '$mdfive', '$refer' )" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
			return true ;

		return false ;
	}

?>
