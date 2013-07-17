<?php
	/*****  Ops_::get
	 *
	 *  $Id: get.php,v 1.8 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION Ops_get_AllOps( &$dbh )
	{
		$query = "SELECT * FROM p_operators ORDER BY name ASC" ;
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
	FUNCTION Ops_get_TotalOps( &$dbh )
	{
		$query = "SELECT count(*) AS total FROM p_operators" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data["total"] ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Ops_get_IsOpInDept( &$dbh,
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
	FUNCTION Ops_get_OpInfoByID( &$dbh,
					$opid )
	{
		if ( $opid == "" )
			return false ;

		LIST( $opid ) = database_mysql_quote( $opid ) ;

		$query = "SELECT * FROM p_operators WHERE opID = $opid" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Ops_get_OpInfoByName( &$dbh,
					$name )
	{
		if ( $name == "" )
			return false ;

		LIST( $name ) = database_mysql_quote( $name ) ;

		$query = "SELECT * FROM p_operators WHERE name = '$name'" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Ops_get_AnyOpsOnline( &$dbh,
					$deptid )
	{
		if ( $deptid == "" )
			return false ;

		global $OP_ONLINE_TIMEOUT ;
		$dept_query = "" ;
		$lastactive = time() - $OP_ONLINE_TIMEOUT ;

		if ( $deptid )
		{
			LIST( $deptid ) = database_mysql_quote( $deptid ) ;
			$dept_query = "AND p_dept_ops.deptID = $deptid" ;
		}

		$query = "SELECT p_operators.opID AS opID FROM p_operators INNER JOIN p_dept_ops ON p_operators.opID = p_dept_ops.opID WHERE p_operators.status = 1 AND p_dept_ops.visible = 1 AND p_operators.lastactive > $lastactive $dept_query GROUP BY p_operators.opID" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			//$data = database_mysql_fetchrow( $dbh ) ;
			return database_mysql_nresults( $dbh ) ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Ops_get_NextRequestOp( &$dbh,
					$deptid,
					$rtype,
					$rstring )
	{
		if ( ( $deptid == "" ) || ( $rtype == "" ) )
			return false ;

		global $OP_ONLINE_TIMEOUT ;
		$lastactive = time() - $OP_ONLINE_TIMEOUT ;

		if ( $rtype == 1 )
			$order_by = "ORDER BY p_dept_ops.display ASC" ;
		else if ( $rtype == 2 )
			$order_by = "ORDER BY p_operators.lastrequest ASC" ;
		else
			return false ;

		LIST( $deptid ) = database_mysql_quote( $deptid ) ;

		$query = "SELECT p_operators.opID AS opID, p_operators.rate AS rate FROM p_operators INNER JOIN p_dept_ops ON p_operators.opID = p_dept_ops.opID WHERE p_operators.status = 1 AND p_operators.lastactive > $lastactive AND p_dept_ops.deptID = $deptid $rstring GROUP BY p_operators.opID $order_by LIMIT 1" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Ops_get_OpDepts( &$dbh,
						$opid )
	{
		if ( $opid == "" )
			return false ;

		LIST( $opid ) = database_mysql_quote( $opid ) ;

		$query = "SELECT p_dept_ops.deptID AS deptID, p_departments.name AS name FROM p_dept_ops, p_departments WHERE p_dept_ops.opID = $opid AND p_dept_ops.deptID = p_departments.deptID" ;
		database_mysql_query( $dbh, $query ) ;

		$depts = Array() ;
		if ( $dbh[ 'ok' ] )
		{
			while ( $data = database_mysql_fetchrow( $dbh ) )
				$depts[] = $data ;
			return $depts ;
		}
		return false ;
	}

?>
