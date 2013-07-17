<?php
	/*****  Chat_::remove
	 *
	 *  $Id: remove.php,v 1.5 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION Chat_remove_Request( &$dbh,
						$requestid )
	{
		if ( $requestid == "" )
			return false ;

		LIST( $requestid ) = database_mysql_quote( $requestid ) ;

		$query = "DELETE FROM p_requests WHERE requestID = $requestid" ;
		database_mysql_query( $dbh, $query ) ;

		return true ;
	}

	/****************************************************************/
	FUNCTION Chat_remove_RequestByCes( &$dbh,
						$ces )
	{
		if ( $ces == "" )
			return false ;

		LIST( $ces ) = database_mysql_quote( $ces ) ;

		$query = "DELETE FROM p_requests WHERE ces = '$ces'" ;
		database_mysql_query( $dbh, $query ) ;

		return true ;
	}

	/****************************************************************/
	FUNCTION Chat_remove_OpDept( &$dbh,
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

	/****************************************************************/
	FUNCTION Chat_remove_ExpiredOp2OpRequests( &$dbh )
	{
		// for now don't expire since op 2 op...
		// todo: put condition of when op closes window and they left
		return true ;

		global $VARS_EXPIRED_OP2OP ;
		$expired_op2op = time() - $VARS_EXPIRED_OP2OP ;

		$query = "DELETE FROM p_requests WHERE vupdated < $expired_op2op AND op2op <> 0" ;
		database_mysql_query( $dbh, $query ) ;

		return true ;
	}

	/****************************************************************/
	FUNCTION Chat_remove_OldRequests( &$dbh )
	{
		global $CONF ;
		include_once( realpath( "$CONF[DOCUMENT_ROOT]/API/Chat/Util.php" ) ) ;
		include_once( realpath( "$CONF[DOCUMENT_ROOT]/lang_packs/$CONF[lang].php" ) ) ;

		global $VARS_EXPIRED_REQS ;
		$expired = time() - $VARS_EXPIRED_REQS ;

		// when chat window was improperly closed
		$query = "DELETE FROM p_requests WHERE ( created < $expired AND ( updated < $expired OR vupdated < $expired ) ) AND vupdated = 1 AND op2op = 0" ;
		database_mysql_query( $dbh, $query ) ;

		// cycle it so data is put in transcript .txt file for warning BEFORE delete
		// set it AFTER delete so it sets it on next pass to delete
		$query = "SELECT * FROM p_requests WHERE ( created < $expired AND ( updated < $expired OR vupdated < $expired ) ) AND vupdated <> 1 AND op2op = 0" ;
		database_mysql_query( $dbh, $query ) ;
		if ( $dbh[ 'ok' ] )
		{
			while( $data = database_mysql_fetchrow( $dbh ) )
			{
				$ces = $data["ces"] ;
				$ip = $data["ip"] ;
				UtilChat_AppendToChatfile( "$ces.txt", "<div class='cl'><disconnected><d6>".LANG_CHAT_NOTIFY_DISCONNECT."</div>" ) ;
			}
			$query = "UPDATE p_requests SET vupdated = 1 WHERE ( created < $expired AND ( updated < $expired OR vupdated < $expired ) ) AND op2op = 0" ;
			database_mysql_query( $dbh, $query ) ;
		}

		return true ;
	}

	/****************************************************************/
	FUNCTION Chat_remove_Transcript( &$dbh,
						$ces,
						$created )
	{
		if ( ( $ces == "" ) || ( $created == "" ) )
			return false ;

		LIST( $ces, $created ) = database_mysql_quote( $ces, $created ) ;

		$query = "DELETE FROM p_transcripts WHERE ces = '$ces' AND created = $created" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
			return true ;
		else
			return false ;
	}

?>
