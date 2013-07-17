<?php
	/*****  Depts_::remove
	 *
	 *  $Id: remove.php,v 1.5 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	include_once( realpath( "$CONF[DOCUMENT_ROOT]/API/Depts/get.php" ) ) ;

	/****************************************************************/
	FUNCTION Depts_remove_Dept( &$dbh,
						$deptid )
	{
		if ( $deptid == "" )
			return false ;

		LIST( $deptid ) = database_mysql_quote( $deptid ) ;

		$query = "DELETE FROM p_canned WHERE deptID = $deptid" ;
		database_mysql_query( $dbh, $query ) ;

		$query = "DELETE FROM p_dept_ops WHERE deptID = $deptid" ;
		database_mysql_query( $dbh, $query ) ;

		$query = "DELETE FROM p_marquees WHERE deptID = $deptid" ;
		database_mysql_query( $dbh, $query ) ;

		$query = "DELETE FROM p_reqstats WHERE deptID = $deptid" ;
		database_mysql_query( $dbh, $query ) ;

		$query = "DELETE FROM p_req_log WHERE deptID = $deptid" ;
		database_mysql_query( $dbh, $query ) ;

		$query = "DELETE FROM p_transcripts WHERE deptID = $deptid" ;
		database_mysql_query( $dbh, $query ) ;

		$query = "DELETE FROM p_departments WHERE deptID = $deptid" ;
		database_mysql_query( $dbh, $query ) ;

		return true ;
	}

	/****************************************************************/
	FUNCTION Depts_remove_OpDept( &$dbh,
						$userid,
						$deptid )
	{
		if ( ( $userid == "" ) || ( $deptid == "" ) )
			return false ;

		LIST( $userid, $deptid ) = database_mysql_quote( $userid, $deptid ) ;

		$query = "DELETE FROM chat_op_dept WHERE userID = $userid AND deptID = $deptid" ;
		database_mysql_query( $dbh, $query ) ;

		return true ;
	}
?>
