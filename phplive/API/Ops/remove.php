<?php
	/*****  Ops_::remove
	 *
	 *  $Id: remove.php,v 1.5 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	 include_once( realpath( "$CONF[DOCUMENT_ROOT]/API/Ops/get.php" ) ) ;

	/****************************************************************/
	FUNCTION Ops_remove_Op( &$dbh,
						$opid )
	{
		if ( $opid == "" )
			return false ;

		LIST( $opid ) = database_mysql_quote( $opid ) ;

		$query = "DELETE FROM p_canned WHERE opID = $opid" ;
		database_mysql_query( $dbh, $query ) ;
		$query = "DELETE FROM p_dept_ops WHERE opID = $opid" ;
		database_mysql_query( $dbh, $query ) ;
		$query = "DELETE FROM p_ext_ops WHERE opID = $opid" ;
		database_mysql_query( $dbh, $query ) ;
		$query = "DELETE FROM p_req_log WHERE opID = $opid" ;
		database_mysql_query( $dbh, $query ) ;
		$query = "DELETE FROM p_transcripts WHERE opID = $opid" ;
		database_mysql_query( $dbh, $query ) ;
		$query = "DELETE FROM p_operators WHERE opID = $opid" ;
		database_mysql_query( $dbh, $query ) ;
		$query = "DELETE FROM p_opstatus_log WHERE opID = $opid" ;
		database_mysql_query( $dbh, $query ) ;
		// DELETE FROM p_reqstats WHERE opID not in ( SELECT DISTINCT opID from p_operators )
		$query = "DELETE FROM p_reqstats WHERE opID = $opid" ;
		database_mysql_query( $dbh, $query ) ;
		$query = "DELETE FROM p_req_log WHERE opID = $opid" ;
		database_mysql_query( $dbh, $query ) ;

		return true ;
	}

	/****************************************************************/
	FUNCTION Ops_remove_OpDept( &$dbh,
						$opid,
						$deptid )
	{
		if ( ( $opid == "" ) || ( $deptid == "" ) )
			return false ;

		LIST( $opid, $deptid ) = database_mysql_quote( $opid, $deptid ) ;

		$query = "SELECT * FROM p_dept_ops WHERE deptID = $deptid AND opID = $opid" ;
		database_mysql_query( $dbh, $query ) ;
		$data = database_mysql_fetchrow( $dbh ) ;

		$query = "DELETE FROM p_dept_ops WHERE deptID = $deptid AND opID = $opid" ;
		database_mysql_query( $dbh, $query ) ;

		$query = "UPDATE p_dept_ops SET display = display-1 WHERE deptID = $deptid AND display >= $data[display]" ;
		database_mysql_query( $dbh, $query ) ;

		return true ;
	}

?>
