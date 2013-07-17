<?php
	/*****  Depts_::update
	 *
	 *  $Id: update.php,v 1.6 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION Depts_update_UserOpValue( &$dbh,
					  $userid,
					  $tbl_name,
					  $value )
	{
		if ( ( $userid == "" ) || ( $tbl_name == "" ) || ( $value == "" ) )
			return false ;
		
		LIST( $userid, $tbl_name, $value ) = database_mysql_quote( $userid, $tbl_name, $value ) ;

		$query = "UPDATE p_operators SET $tbl_name = '$value' WHERE userID = $userid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
			return true ;
		return false ;
	}

	/****************************************************************/
	FUNCTION Depts_update_UserDeptValue( &$dbh,
					  $deptid,
					  $tbl_name,
					  $value )
	{
		if ( ( $deptid == "" ) || ( $tbl_name == "" ) || ( $value == "" ) )
			return false ;
		
		LIST( $deptid, $tbl_name, $value ) = database_mysql_quote( $deptid, $tbl_name, $value ) ;

		$query = "UPDATE p_departments SET $tbl_name = '$value' WHERE deptID = $deptid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
			return true ;
		return false ;
	}
?>
