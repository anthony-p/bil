<?php
	/*****  Ops_::update
	 *
	 *  $Id: update.php,v 1.6 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION Ops_update_OpDeptMoveUp( &$dbh,
					  $opid,
					  $deptid )
	{
		if ( ( $opid == "" ) || ( $deptid == "" ) )
			return false ;

		LIST( $opid, $deptid ) = database_mysql_quote( $opid, $deptid ) ;

		$query = "SELECT display FROM p_dept_ops WHERE opID = $opid AND deptID = $deptid" ;
		database_mysql_query( $dbh, $query ) ;
		$data = database_mysql_fetchrow( $dbh ) ;
		
		if ( isset( $data["display"] ) )
		{
			$query = "UPDATE p_dept_ops SET display = display + 1 WHERE deptID = $deptid AND display = $data[display] - 1" ;
			database_mysql_query( $dbh, $query ) ;
			$query = "UPDATE p_dept_ops SET display = display - 1 WHERE deptID = $deptid AND opID = $opid" ;
			database_mysql_query( $dbh, $query ) ;
			return true ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Ops_update_OpValue( &$dbh,
					  $opid,
					  $tbl_name,
					  $value )
	{
		if ( ( $opid == "" ) || ( $tbl_name == "" ) )
			return false ;
		
		LIST( $opid, $tbl_name, $value ) = database_mysql_quote( $opid, $tbl_name, $value ) ;

		$query = "UPDATE p_operators SET $tbl_name = '$value' WHERE opID = $opid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
			return true ;
		return false ;
	}

	/****************************************************************/
	/* 2 vars: $opid, $prev_status - to kick start online status if
	/*		put on offline in error */
	FUNCTION Ops_update_IdleOps( &$dbh,
					$opid,
					$prev_status )
	{
		global $VARS_EXPIRED_OPS ;

		LIST( $opid ) = database_mysql_quote( $opid ) ;

		// chat_routing.php has the # of times cycled (2 sec x 4 = 8)
		$idle = time() - $VARS_EXPIRED_OPS ;

		$query = "UPDATE p_operators SET status = 0 WHERE lastactive < $idle" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $opid && !$prev_status )
		{
			$query = "UPDATE p_operators SET status = 1 WHERE lastactive >= $idle AND opID = '$opid' AND status = 0" ;
			database_mysql_query( $dbh, $query ) ;
		}

		return true ;
	}

	/****************************************************************/
	FUNCTION Ops_update_OpDeptVisible( &$dbh,
					  $deptid,
					  $visible )
	{
		if ( ( $deptid == "" ) || ( $visible == "" ) )
			return false ;
		
		LIST( $deptid, $visible ) = database_mysql_quote( $deptid, $visible ) ;

		$query = "UPDATE p_dept_ops SET visible = $visible WHERE deptID = $deptid" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
			return true ;
		return false ;
	}

?>
