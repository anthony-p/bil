<?php
	/*****  Canned_::get
	 *
	 *  $Id: get.php,v 1.8 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION Canned_get_CanInfo( &$dbh,
						$canid )
	{
		if ( $canid == "" )
			return false ;

		LIST( $canid ) = database_mysql_quote( $canid ) ;

		$query = "SELECT * FROM p_canned WHERE canID = $canid" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Canned_get_OpCanned( &$dbh,
						$opid,
						$deptid )
	{
		if ( $opid == "" )
			return false ;

		global $CONF ;
		include_once( realpath( "$CONF[DOCUMENT_ROOT]/API/Depts/get.php" ) ) ;

		// for now disable $deptid specific input
		LIST( $opid, $deptid ) = database_mysql_quote( $opid, $deptid ) ;

		$dept_string = "" ;
		$departments = Depts_get_OpDepts( $dbh, $opid ) ;
		for ( $c = 0; $c < count( $departments ); ++$c )
			$dept_string .= "deptID = " . $departments[$c]["deptID"] . " OR " ;
		if ( $dept_string )
			$dept_string = substr( $dept_string, 0, strlen( $dept_string ) - 3 ) ;

		$condition1 = " opID = $opid " ;
		$condition2 = " opID = 1111111111 AND deptID = 1111111111 " ;
		$condition3 = " opID = 1111111111 AND ( $dept_string ) " ;

		$query1 = "SELECT * FROM p_canned WHERE $condition1 UNION " ;
		$query2 = "SELECT * FROM p_canned WHERE $condition2 UNION " ;
		$query3 = "SELECT * FROM p_canned WHERE $condition3 " ;
		$query = "$query1 $query2 $query3 ORDER BY deptID ASC " ;
		database_mysql_query( $dbh, $query ) ;

		$output = Array() ;
		if ( $dbh[ 'ok' ] )
		{
			// do some sorting to take out extra cans based on conditions
			while( $data = database_mysql_fetchrow( $dbh ) )
			{
				// todo: refine the searched based on $deptid for future version
				$output[] = $data ;
			}
		}

		return $output ;
	}

	/****************************************************************/
	FUNCTION Canned_get_DeptCanned( &$dbh,
						$deptid,
						$page,
						$limit )
	{
		if ( ( $deptid == "" ) || ( $limit == "" ) )
			return false ;

		LIST( $deptid, $page, $limit ) = database_mysql_quote( $deptid, $page, $limit ) ;
		$start = ( $page * $limit ) ;

		$query = "SELECT * FROM p_canned WHERE deptID = $deptid OR deptID = 1111111111 ORDER BY title ASC LIMIT $start, $limit" ;
		database_mysql_query( $dbh, $query ) ;

		$output = Array() ;
		if ( $dbh[ 'ok' ] )
		{
			while( $data = database_mysql_fetchrow( $dbh ) )
				$output[] = $data ;
		}

		$query = "SELECT count(*) AS total FROM p_canned WHERE deptID = $deptid OR deptID = 1111111111" ;
		database_mysql_query( $dbh, $query ) ;
		$data = database_mysql_fetchrow( $dbh ) ;
		$output[] = $data["total"] ;

		return $output ;
	}

?>
