<?php
	/*****  Chat_::put
	 *
	 *  $Id: put.php,v 1.9 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION Chat_put_Request( &$dbh,
					$deptid,
					$opid,
					$status,
					$initiate,
					$op2op,
					$etrans,
					$os,
					$browser,
					$ces,
					$resolution,
					$vname,
					$vemail,
					$ip,
					$agent,
					$onpage,
					$title,
					$question,
					$marketid,
					$refer )
	{
		if ( ( $deptid == "" ) || ( $opid == "" ) || ( $os == "" ) || ( $browser == "" )
			|| ( $ces == "" ) || ( $vname == "" ) || ( $vemail == "" ) || ( $ip == "" )
			|| ( $agent == "" ) || ( $question == "" ) )
			return false ;

		global $CONF ;
		include_once( realpath( "$CONF[DOCUMENT_ROOT]/API/Chat/get.php" ) ) ;

		$now = time() ;
		$hostname = gethostbyaddr( $ip ) ;
		$onpage = strip_tags( $onpage ) ;

		LIST( $deptid, $opid, $status, $initiate, $op2op, $etrans, $os, $browser, $ces, $resolution, $vname, $vemail, $ip, $hostname, $agent, $onpage, $title, $question, $marketid, $refer ) = database_mysql_quote( $deptid, $opid, $status, $initiate, $op2op, $etrans, $os, $browser, $ces, $resolution, $vname, $vemail, $ip, $hostname, $agent, $onpage, $title, $question, $marketid, $refer ) ;

		$requestinfo = Chat_get_RequestCesInfo( $dbh, $ces ) ;

		if ( isset( $requestinfo["requestID"] ) )
		{
			if ( $requestinfo["initiated"] )
			{
				include_once( realpath( "$CONF[DOCUMENT_ROOT]/API/Chat/update.php" ) ) ;

				Chat_update_RequestValue( $dbh, $requestinfo["requestID"], "status", 1 ) ;
			}

			return $requestinfo["requestID"] ;
		}
		else
		{
			$vupdated = time() ;
			$rstring = "AND p_operators.opID <> $opid" ;

			// todo: perhaps put total requests during activation of chat and not here
			$requests = Chat_get_IPTotalRequests( $dbh, $ip ) ;
			$query = "INSERT INTO p_requests VALUES ( 0, $now, 0, $now, $vupdated, $status, $initiate, $etrans, $deptid, $opid, $op2op, $marketid, $os, $browser, $requests, '$ces', '$resolution', '$vname', '$vemail', '$ip', '$hostname', '$agent', '$onpage', '$title', '$rstring', '$refer', '$question' )" ;
			database_mysql_query( $dbh, $query ) ;

			if ( $dbh[ 'ok' ] )
			{
				if ( $initiate )
					touch( "$CONF[DOCUMENT_ROOT]/web/chat_initiate/$ip.txt" ) ;

				$id = database_mysql_insertid ( $dbh ) ;
				return $id ;
			}

			return false ;
		}
	}

	/****************************************************************/
	FUNCTION Chat_put_ReqLog( &$dbh,
					$requestid )
	{
		if ( $requestid == "" )
			return false ;

		LIST( $requestid ) = database_mysql_quote( $requestid ) ;

		$query = "INSERT INTO p_req_log ( ces, created, ended, status, initiated, etrans, deptID, opID, op2op, marketID, os, browser, resolution, vname, vemail, ip, hostname, agent, onpage, title, question ) SELECT ces, created, 0, status, initiated, etrans, deptID, opID, op2op, marketID, os, browser, resolution, vname, vemail, ip, hostname, agent, onpage, title, question FROM p_requests WHERE p_requests.requestID = $requestid" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
			return true ;

		return false ;
	}

	/****************************************************************/
	FUNCTION Chat_put_Transcript( &$dbh,
					$ces,
					$status,
					$etrans,
					$created,
					$ended,
					$deptid,
					$opid,
					$initiated,
					$op2op,
					$rating,
					$fsize,
					$vname,
					$vemail,
					$ip,
					$question,
					$formatted,
					$plain )
	{
		if ( ( $ces == "" ) || ( $deptid == "" ) || ( $opid == "" ) || ( $fsize == "" )
			|| ( $ended == "" ) || ( $vname == "" ) || ( $vemail == "" ) || ( $ip == "" )
			|| ( $question == "" ) || ( $formatted == "" ) || ( $plain == "" ) )
			return false ;

		global $CONF ;
		include_once( realpath( "$CONF[DOCUMENT_ROOT]/API/Ops/get.php" ) ) ;
		include_once( realpath( "$CONF[DOCUMENT_ROOT]/API/Depts/get.php" ) ) ;
		include_once( realpath( "$CONF[DOCUMENT_ROOT]/API/Util_Email.php" ) ) ;

		LIST( $ces, $status, $created, $ended, $deptid, $opid, $initiated, $op2op, $rating, $fsize, $vname, $vemail, $ip, $question, $formatted, $plain ) = database_mysql_quote( $ces, $status, $created, $ended, $deptid, $opid, $initiated, $op2op, $rating, $fsize, $vname, $vemail, $ip, $question, $formatted, $plain ) ;

		$query = "INSERT INTO p_transcripts VALUES ( '$ces', $created, $ended, $deptid, $opid, $initiated, $op2op, $rating, $fsize, '$vname', '$vemail', '$ip', '$question', '$formatted', '$plain' )" ;
		database_mysql_query( $dbh, $query ) ;
		$error = mysql_errno( $dbh["con"] ) ; // duplicate error let it pass

		if ( $dbh[ 'ok' ] || ( $error == 1062 ) )
		{
			if ( ( $error != 1062 ) && $status && $etrans )
			{
				// todo: enhance it for formatted style
				$deptinfo = Depts_get_DeptInfo( $dbh, $deptid ) ;
				$opinfo = Ops_get_OpInfoByID( $dbh, $opid ) ;

				$vname = preg_replace( "/<v>/", "", $vname ) ;
				$subject = "Chat Transcript with $opinfo[name] of $deptinfo[name]" ;
				$message = stripslashes( $formatted ) ;
				$message = preg_replace( "/<>/", "\r\n", $message ) ;
				$message = preg_replace( "/<disconnected><d(\d)>(.*?)<\/div>/", "\r\n$2\r\n=== END OF CHAT ===\r\n", $message ) ;
				$message = preg_replace( "/<div class='ca'><i>(.*?)<\/i><\/div>/", "===\r\nQuestion: $1\r\n===", $message ) ;
				$message = preg_replace( "/<div class='co'><b>(.*?)<timestamp_(\d+)_co>:<\/b> /", "\r\n$1:\r\n", $message ) ;
				$message = preg_replace( "/<div class='cv'><b><v>(.*?)<timestamp_(\d+)_cv>:<\/b> /", "\r\n$1:\r\n", $message ) ;
				$message = preg_replace( "/<(.*?)>/", "", $message ) ;

				$message = "Hello $vname,\r\n\r\nThank you for taking the time to chat with us.  Below is the complete transcript for your reference:\r\n\r\n$message\r\n\r\nThank you,\r\n$opinfo[name]\r\n$opinfo[email]\r\n" ;
				if ( Util_Email_SendEmail( $opinfo["name"], $opinfo["email"], $vname, $vemail, $subject, $message, "trans" ) )
					return true ;
			}
			return true ;
		}

		return false ;
	}

?>
