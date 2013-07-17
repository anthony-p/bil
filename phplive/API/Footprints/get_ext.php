<?php
	/*****  Footprints_::get
	 *
	 *  $Id: get.php,v 1.8 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION Footprints_get_FootprintsRangeHash( &$dbh,
						$stat_start,
						$stat_end )
	{
		if ( ( $stat_start == "" ) || ( $stat_end == "" ) )
			return false ;

		LIST( $stat_start, $stat_end ) = database_mysql_quote( $stat_start, $stat_end ) ;

		$query = "SELECT SUM( total ) AS total, sdate FROM p_footstats WHERE sdate >= $stat_start AND sdate < $stat_end GROUP BY sdate" ;
		database_mysql_query( $dbh, $query ) ;

		$output = Array() ;
		if ( $dbh[ 'ok' ] )
		{
			while( $data = database_mysql_fetchrow( $dbh ) )
			{
				$sdate = $data["sdate"] ;

				if ( !isset( $output[$sdate] ) )
				{
					$output[$sdate] = Array() ;
					$output[$sdate]["total"] = 0 ;
				}

				$output[$sdate]["total"] = $data["total"] ;
			}

			// get stats for today
			$sdate_start = mktime( 0, 0, 1, date( "m", time() ), date( "j", time() ), date( "Y", time() ) ) ;
			$sdate_end = mktime( 0, 0, 1, date( "m", $sdate_start ), date( "j", $sdate_start )+1, date( "Y", $sdate_start ) ) ;

			$query = "SELECT count(*) AS total FROM p_footprints WHERE created >= $sdate_start AND created < $sdate_end" ;
			database_mysql_query( $dbh, $query ) ;
			if ( $dbh[ 'ok' ] )
			{
				$data = database_mysql_fetchrow( $dbh ) ;
				$output[$sdate_start]["total"] = $data["total"] ;
			}
		}
		return $output ;
	}

	/****************************************************************/
	FUNCTION Footprints_get_ReferRangeHash( &$dbh,
						$stat_start,
						$stat_end )
	{
		if ( ( $stat_start == "" ) || ( $stat_end == "" ) )
			return false ;

		LIST( $stat_start, $stat_end ) = database_mysql_quote( $stat_start, $stat_end ) ;

		$query = "SELECT SUM( total ) AS total, sdate FROM p_referstats WHERE sdate >= $stat_start AND sdate < $stat_end GROUP BY sdate" ;
		database_mysql_query( $dbh, $query ) ;

		$output = Array() ;
		if ( $dbh[ 'ok' ] )
		{
			while( $data = database_mysql_fetchrow( $dbh ) )
			{
				$sdate = $data["sdate"] ;

				if ( !isset( $output[$sdate] ) )
				{
					$output[$sdate] = Array() ;
					$output[$sdate]["total"] = 0 ;
				}

				$output[$sdate]["total"] = $data["total"] ;
			}

			// get stats for today
			$sdate_start = mktime( 0, 0, 1, date( "m", time() ), date( "j", time() ), date( "Y", time() ) ) ;
			$sdate_end = mktime( 0, 0, 1, date( "m", $sdate_start ), date( "j", $sdate_start )+1, date( "Y", $sdate_start ) ) ;

			$query = "SELECT count(*) AS total FROM p_refer WHERE created >= $sdate_start AND created < $sdate_end" ;
			database_mysql_query( $dbh, $query ) ;
			if ( $dbh[ 'ok' ] )
			{
				$data = database_mysql_fetchrow( $dbh ) ;
				$output[$sdate_start]["total"] = $data["total"] ;
			}
		}
		return $output ;
	}

	/****************************************************************/
	FUNCTION Footprints_get_LatestStats( &$dbh )
	{
		$query = "SELECT * FROM p_footstats ORDER BY sdate DESC LIMIT 1" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Footprints_get_OldestPrint( &$dbh )
	{
		$query = "SELECT * FROM p_footprints ORDER BY created ASC LIMIT 1" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Footprints_get_FootData( &$dbh,
					$stat_start,
					$stat_end )
	{
		if ( ( $stat_start == "" ) || ( $stat_end == "" ) )
			return false ;

		LIST( $stat_start, $stat_end ) = database_mysql_quote( $stat_start, $stat_end ) ;

		$query = "SELECT count(*) AS total, onpage FROM p_footprints WHERE created >= $stat_start AND created < $stat_end GROUP BY mdfive ORDER BY total DESC LIMIT 25" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$output = Array() ;
			while( $data = database_mysql_fetchrow( $dbh ) )
				$output[] = $data ;
			return $output ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Footprints_get_ReferData( &$dbh,
					$stat_start,
					$stat_end )
	{
		if ( ( $stat_start == "" ) || ( $stat_end == "" ) )
			return false ;

		LIST( $stat_start, $stat_end ) = database_mysql_quote( $stat_start, $stat_end ) ;

		$query = "SELECT count(*) AS total, refer FROM p_refer WHERE created >= $stat_start AND created < $stat_end GROUP BY mdfive ORDER BY total DESC LIMIT 25" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$output = Array() ;
			while( $data = database_mysql_fetchrow( $dbh ) )
				$output[] = $data ;
			return $output ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Footprints_get_FootStatsData( &$dbh,
					$stat_start,
					$stat_end )
	{
		if ( ( $stat_start == "" ) || ( $stat_end == "" ) )
			return false ;

		LIST( $stat_start, $stat_end ) = database_mysql_quote( $stat_start, $stat_end ) ;

		$query = "SELECT * FROM p_footstats WHERE sdate >= $stat_start AND sdate < $stat_end ORDER BY total DESC" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$output = Array() ;
			while( $data = database_mysql_fetchrow( $dbh ) )
				$output[] = $data ;
			return $output ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Footprints_get_ReferStatsData( &$dbh,
					$stat_start,
					$stat_end )
	{
		if ( ( $stat_start == "" ) || ( $stat_end == "" ) )
			return false ;

		LIST( $stat_start, $stat_end ) = database_mysql_quote( $stat_start, $stat_end ) ;

		$query = "SELECT * FROM p_referstats WHERE sdate >= $stat_start AND sdate < $stat_end ORDER BY total DESC" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$output = Array() ;
			while( $data = database_mysql_fetchrow( $dbh ) )
				$output[] = $data ;
			return $output ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Footprints_get_IPFootprints( &$dbh,
					$ip,
					$limit )
	{
		if ( ( $ip == "" ) || ( $limit == "" ) )
			return false ;

		LIST( $ip, $limit ) = database_mysql_quote( $ip, $limit ) ;

		$query = "SELECT count(*) AS total, onpage, title FROM p_footprints WHERE ip = '$ip' GROUP BY mdfive ORDER BY total DESC LIMIT $limit" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$output = Array() ;
			while( $data = database_mysql_fetchrow( $dbh ) )
				$output[] = $data ;
			return $output ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Footprints_get_IPRefer( &$dbh,
					$ip,
					$limit )
	{
		if ( ( $ip == "" ) || ( $limit == "" ) )
			return false ;

		LIST( $ip, $limit ) = database_mysql_quote( $ip, $limit ) ;

		$query = "SELECT refer, p_marketing.marketID, p_marketing.name, p_marketing.color FROM p_refer LEFT JOIN p_marketing ON p_refer.marketID = p_marketing.marketID WHERE ip = '$ip' ORDER BY created DESC LIMIT $limit" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$output = Array() ;
			while( $data = database_mysql_fetchrow( $dbh ) )
				$output[] = $data ;
			return $output ;
		}
		return false ;
	}

?>
