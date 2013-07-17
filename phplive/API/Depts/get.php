<?php
	/*****  Depts_::get
	 *
	 *  $Id: get.php,v 1.8 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION Depts_get_AllDepts( &$dbh )
	{
		$query = "SELECT * FROM p_departments ORDER BY name ASC" ;
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
	FUNCTION Depts_get_DeptOps( &$dbh,
						$deptid )
	{
		if ( $deptid == "" )
			return false ;

		LIST( $deptid ) = database_mysql_quote( $deptid ) ;

		$query = "SELECT * FROM p_operators, p_dept_ops WHERE p_operators.opID = p_dept_ops.opID AND p_dept_ops.deptID = $deptid ORDER BY p_dept_ops.display ASC" ;
		database_mysql_query( $dbh, $query ) ;

		$ops = Array() ;
		if ( $dbh[ 'ok' ] )
		{
			while( $data = database_mysql_fetchrow( $dbh ) )
				$ops[] = $data ;
			return $ops ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Depts_get_OpDepts( &$dbh,
						$opid )
	{
		if ( $opid == "" )
			return false ;

		LIST( $opid ) = database_mysql_quote( $opid ) ;

		$query = "SELECT * FROM p_departments, p_dept_ops WHERE p_departments.deptID = p_dept_ops.deptID AND p_dept_ops.opID = $opid ORDER BY p_departments.name ASC" ;
		database_mysql_query( $dbh, $query ) ;

		$depts = Array() ;
		if ( $dbh[ 'ok' ] )
		{
			while( $data = database_mysql_fetchrow( $dbh ) )
				$depts[] = $data ;
			return $depts ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Depts_get_DeptInfo( &$dbh,
						$deptid )
	{
		if ( $deptid == "" )
			return false ;

		LIST( $deptid ) = database_mysql_quote( $deptid ) ;

		$query = "SELECT * FROM p_departments WHERE deptID = $deptid" ;
		database_mysql_query( $dbh, $query ) ;

		$ops = Array() ;
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Depts_get_DeptInfoByName( &$dbh,
						$name )
	{
		if ( $name == "" )
			return false ;

		LIST( $name ) = database_mysql_quote( $name ) ;

		$query = "SELECT * FROM p_departments WHERE name = '$name'" ;
		database_mysql_query( $dbh, $query ) ;

		$ops = Array() ;
		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

?>
