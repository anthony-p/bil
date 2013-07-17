<?php
	/*****  Ops_::put
	 *
	 *  $Id: put.php,v 1.9 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION Ops_put_Op( &$dbh,
					$opid,
					$status,
					$rate,
					$op2op,
					$traffic,
					$login,
					$password,
					$name,
					$email )
	{
		if ( ( $login == "" ) || ( $name == "" ) || ( $email == "" ) )
			return false ;

		LIST( $login ) = database_mysql_quote( $login ) ;

		$query = "SELECT * FROM p_operators WHERE login = '$login'" ;
		database_mysql_query( $dbh, $query ) ;
		$operator = database_mysql_fetchrow( $dbh ) ;

		if ( isset( $operator["opID"] ) && ( $operator["name"] == $name ) )
		{
			if ( $operator["opID"] != $opid )
				return false ;
		}

		if ( isset( $operator["opID"] ) )
		{
			if ( $password == "php-live-support" )
				$password = $operator["password"] ;
			else
				$password = md5( $password ) ;
		}
		else
			$password = md5( $password ) ;

		LIST( $opid, $status, $rate, $op2op, $traffic, $password, $name, $email ) = database_mysql_quote( $opid, $status, $rate, $op2op, $traffic, $password, $name, $email ) ;

		if ( isset( $operator["opID"] ) )
			$query = "REPLACE INTO p_operators VALUES ( $opid, $operator[lastactive], $operator[lastrequest], $operator[status], 0, $rate, $op2op, $traffic, '$operator[ses]', '$login', '$password', '$name', '$email', '$operator[pic]', '$operator[theme]' )" ;
		else
			$query = "REPLACE INTO p_operators VALUES ( $opid, 0, 0, 1, 0, $rate, $op2op, $traffic, '', '$login', '$password', '$name', '$email', '', 'default' )" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$id = database_mysql_insertid ( $dbh ) ;
			return $id ;
		}

		return false ;
	}

	/****************************************************************/
	FUNCTION Ops_put_OpDept( &$dbh,
					$opid,
					$deptid,
					$visible )
	{
		if ( ( $opid == "" ) || ( $deptid == "" ) )
			return false ;

		LIST( $opid, $deptid, $visible ) = database_mysql_quote( $opid, $deptid, $visible ) ;

		$query = "SELECT count(*) AS total FROM p_dept_ops WHERE deptID = $deptid" ;
		database_mysql_query( $dbh, $query ) ;
		$data = database_mysql_fetchrow( $dbh ) ;
		$display = $data["total"] + 1 ; // add 1 because it starts at ZERO

		$query = "INSERT INTO p_dept_ops VALUES ( $deptid, $opid, $display, $visible )" ;
		database_mysql_query( $dbh, $query ) ;

		return true ;
	}

	/****************************************************************/
	FUNCTION Ops_put_OpReqStat( &$dbh,
					$deptid,
					$opid,
					$tbl_name,
					$incro )
	{
		if ( ( $deptid == "" ) || ( $opid == "" ) || ( $incro == "" ) )
			return false ;

		LIST( $deptid, $opid, $tbl_name, $incro ) = database_mysql_quote( $deptid, $opid, $tbl_name, $incro ) ;

		$sdate = mktime( 0, 0, 1, date("m"), date("j"), date("Y") ) ;

		$query = "SELECT * FROM p_reqstats WHERE sdate = $sdate AND deptID = $deptid AND opID = $opid" ;
		database_mysql_query( $dbh, $query ) ;
		$data = database_mysql_fetchrow( $dbh ) ;
		
		if ( !isset( $data["sdate"] ) )
		{
			$query = "INSERT INTO p_reqstats VALUES ( $sdate, $deptid, $opid, 0, 0, 0, 0, 0, 0 )" ;
			database_mysql_query( $dbh, $query ) ;
		}

		$value = $data[$tbl_name] + $incro ;
		$query = "UPDATE p_reqstats SET $tbl_name = $value WHERE sdate = $sdate AND deptID = $deptid AND opID = $opid" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
			return true ;

		return false ;
	}

?>
