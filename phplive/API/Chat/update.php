<?php
	/*****  Chat_::update
	 *
	 *  $Id: update.php,v 1.6 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION Chat_update_RequestValue( &$dbh,
					  $requestid,
					  $tbl_name,
					  $value )
	{
		if ( ( $requestid == "" ) || ( $tbl_name == "" ) )
			return false ;
		
		LIST( $requestid, $tbl_name, $value ) = database_mysql_quote( $requestid, $tbl_name, $value ) ;

		$query = "UPDATE p_requests SET $tbl_name = '$value' WHERE requestID = $requestid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
			return true ;
		return false ;
	}

	/****************************************************************/
	FUNCTION Chat_update_RequestValueByCes( &$dbh,
					  $ces,
					  $tbl_name,
					  $value )
	{
		if ( ( $ces == "" ) || ( $tbl_name == "" ) )
			return false ;
		
		LIST( $ces, $tbl_name, $value ) = database_mysql_quote( $ces, $tbl_name, $value ) ;

		$query = "UPDATE p_requests SET $tbl_name = '$value' WHERE ces = '$ces'" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
			return true ;
		return false ;
	}

	/****************************************************************/
	FUNCTION Chat_update_RequestLogValue( &$dbh,
					  $ces,
					  $tbl_name,
					  $value )
	{
		if ( ( $ces == "" ) || ( $tbl_name == "" ) )
			return false ;
		
		LIST( $ces, $tbl_name, $value ) = database_mysql_quote( $ces, $tbl_name, $value ) ;

		$query = "UPDATE p_req_log SET $tbl_name = '$value' WHERE ces = '$ces'" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
			return true ;
		return false ;
	}

	/****************************************************************/
	FUNCTION Chat_update_TranscriptValue( &$dbh,
					  $ces,
					  $tbl_name,
					  $value )
	{
		if ( ( $ces == "" ) || ( $tbl_name == "" ) || ( $value == "" ) )
			return false ;
		
		LIST( $ces, $tbl_name, $value ) = database_mysql_quote( $ces, $tbl_name, $value ) ;

		$query = "UPDATE p_transcripts SET $tbl_name = '$value' WHERE ces = '$ces'" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
			return true ;
		return false ;
	}

	/****************************************************************/
	FUNCTION Chat_update_TransferChat( &$dbh,
					  $ces,
					  $orig_opid,
					  $deptid,
					  $opid )
	{
		if ( ( $orig_opid == "" ) || ( $ces == "" ) || ( $deptid == "" ) || ( $opid == "" ) )
			return false ;

		$now = time() ;
		LIST( $orig_opid, $ces, $deptid, $opid ) = database_mysql_quote( $orig_opid, $ces, $deptid, $opid ) ;

		// place the transfered op so the call does not route back to the operator during
		// visitor routing process
		$query = "UPDATE p_requests SET tupdated = $now, status = 2, opID = $opid, op2op = $orig_opid, rstring = CONCAT( rstring, ' AND p_operators.opID <> $opid ' ) WHERE ces = '$ces'" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
			return true ;
		return false ;
	}

	/****************************************************************/
	FUNCTION Chat_update_TransferChatOrig( &$dbh,
					  $opid,
					  $ces )
	{
		if ( ( $opid == "" ) || ( $ces == "" ) )
			return false ;

		LIST( $opid, $ces ) = database_mysql_quote( $opid, $ces ) ;

		$query = "UPDATE p_requests SET tupdated = 0, status = 0, opID = $opid, op2op = 0 WHERE ces = '$ces'" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
			return true ;
		return false ;
	}

	/****************************************************************/
	FUNCTION Chat_update_AcceptChat( &$dbh,
					  $requestid,
					  $status,
					  $op2op )
	{
		if ( ( $requestid == "" ) || ( $status == "" ) || ( $op2op == "" ) )
			return false ;

		LIST( $requestid, $status, $op2op ) = database_mysql_quote( $requestid, $status, $op2op ) ;

		if ( ( $status == 2 ) && $op2op )
			$query = "UPDATE p_requests SET status = 1, tupdated = 0, op2op = 0 WHERE requestID = $requestid" ;
		else
			$query = "UPDATE p_requests SET status = 1, tupdated = 0 WHERE requestID = $requestid" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
			return true ;
		return false ;
	}

	/****************************************************************/
	FUNCTION Chat_update_OpRequestUpdated( &$dbh,
					  $opid )
	{
		if ( $opid == "" )
			return false ;
		
		$now = time() ;
		LIST( $opid ) = database_mysql_quote( $opid ) ;

		$query = "UPDATE p_requests SET updated = $now WHERE ( opID = $opid OR op2op = $opid OR opID = 1111111111 ) AND ( status = 0 OR status = 1 OR status = 2 )" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
			return true ;
		return false ;
	}

?>
