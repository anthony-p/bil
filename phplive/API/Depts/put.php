<?php
	/*****  Depts_::put
	 *
	 *  $Id: put.php,v 1.9 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION Depts_put_Department( &$dbh,
					$deptid,
					$name,
					$email,
					$visible,
					$queue,
					$rtype,
					$rtime,
					$tshare,
					$texpire )
	{
		if ( ( $name == "" ) || ( $email == "" )  || ( $rtime == "" ) )
			return false ;

		LIST( $deptid, $name, $email, $visible, $queue, $rtype, $rtime, $tshare, $texpire, $connecting, $noops ) = database_mysql_quote( $deptid, $name, $email, $visible, $queue, $rtype, $rtime, $tshare, $texpire, LANG_CHAT_NOTIFY_LOOKING_FOR_OP, LANG_CHAT_NOTIFY_OP_NOT_FOUND ) ;

		if ( ( $rtime < 15 ) || ( $rtime > 120 ) ) { $rtime = 45 ; }

		$query = "SELECT * FROM p_departments WHERE name = '$name'" ;
		database_mysql_query( $dbh, $query ) ;
		$department = database_mysql_fetchrow( $dbh ) ;

		if ( isset( $department["deptID"] ) && ( $department["name"] == $name ) )
		{
			if ( $department["deptID"] != $deptid )
				return false ;
		}

		$query = "REPLACE INTO p_departments VALUES ( $deptid, $visible, $queue, $tshare, $texpire, $rtype, $rtime, '', '', '$name', '$email', '$connecting', '$noops', '' )" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$id = database_mysql_insertid ( $dbh ) ;
			return $id ;
		}

		return false ;
	}

	/****************************************************************/
	FUNCTION Depts_put_OpDept( &$dbh,
					$userid,
					$deptid )
	{
		if ( ( $userid == "" ) || ( $deptid == "" ) )
			return false ;

		LIST( $userid, $deptid ) = database_mysql_quote( $userid, $deptid ) ;

		$query = "SELECT count(*) AS total FROM chat_op_dept WHERE deptID = $deptid" ;
		database_mysql_query( $dbh, $query ) ;
		$data = database_mysql_fetchrow( $dbh ) ;

		$query = "INSERT INTO chat_op_dept VALUES ( $userid, $deptid, $data[total] )" ;
		database_mysql_query( $dbh, $query ) ;

		return true ;
	}

?>
