<?php
	/*****  Canned_::update
	 *
	 *  $Id: update.php,v 1.6 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION Canned_update_Dorder( &$dbh,
					  $userid,
					  $deptid,
					  $display )
	{
		if ( ( $userid == "" ) || ( $deptid == "" ) )
			return false ;
		
		LIST( $userid, $deptid, $display ) = database_mysql_quote( $userid, $deptid, $display ) ;

		$query = "REPLACE INTO chat_op_dept VALUES ( $userid, $deptid, $display )" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
			return true ;
		return false ;
	}

	/****************************************************************/
	FUNCTION Canned_update_DorderMoveUp( &$dbh,
					  $userid,
					  $deptid )
	{
		if ( ( $userid == "" ) || ( $deptid == "" ) )
			return false ;
		
		LIST( $userid, $deptid ) = database_mysql_quote( $userid, $deptid ) ;

		$query = "SELECT display FROM chat_op_dept WHERE userID = $userid AND deptId = $deptid" ;
		database_mysql_query( $dbh, $query ) ;
		$data = database_mysql_fetchrow( $dbh ) ;
		
		if ( isset( $data["display"] ) )
		{
			$query = "UPDATE chat_op_dept SET display = display + 1 WHERE deptID = $deptid AND display = $data[display] - 1" ;
			database_mysql_query( $dbh, $query ) ;
			$query = "UPDATE chat_op_dept SET display = display - 1 WHERE deptID = $deptid AND userID = $userid" ;
			database_mysql_query( $dbh, $query ) ;
			return true ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Canned_update_UserOpValue( &$dbh,
					  $userid,
					  $tbl_name,
					  $value )
	{
		if ( ( $userid == "" ) || ( $tbl_name == "" ) || ( $value == "" ) )
			return false ;
		
		LIST( $userid, $tbl_name, $value ) = database_mysql_quote( $userid, $tbl_name, $value ) ;

		$query = "UPDATE chat_ops SET $tbl_name = '$value' WHERE userID = $userid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
			return true ;
		return false ;
	}

	/****************************************************************/
	FUNCTION Canned_update_UserDeptValue( &$dbh,
					  $deptid,
					  $tbl_name,
					  $value )
	{
		if ( ( $deptid == "" ) || ( $tbl_name == "" ) || ( $value == "" ) )
			return false ;
		
		LIST( $deptid, $tbl_name, $value ) = database_mysql_quote( $deptid, $tbl_name, $value ) ;

		$query = "UPDATE chat_depts SET $tbl_name = '$value' WHERE deptID = $deptid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
			return true ;
		return false ;
	}
?>
