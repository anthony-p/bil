<?php
	include_once( "../web/config.php" ) ;
	include_once( "../API/Util_Format.php" ) ;
	include_once( "../lang_packs/$CONF[lang].php" ) ;

	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;

	// todo: $opinfo [mod Jake: 82]
	if ( !isset( $_COOKIE["phplive_opID"] ) )
		$json_data = "json_data = { \"status\": -1 };" ;
	else if ( $action == "accept" )
	{
		include_once( "../API/SQL.php" ) ;
		include_once( "../API/Ops/update.php" ) ;
		include_once( "../API/Chat/get.php" ) ;
		include_once( "../API/Chat/update.php" ) ;
		include_once( "../API/Chat/Util.php" ) ;

		$requestid = Util_Format_Sanatize( Util_Format_GetVar( "requestid" ), "ln" ) ;
		$ces = Util_Format_Sanatize( Util_Format_GetVar( "ces" ), "ln" ) ;
		$tooslow = 0 ; // to catch collisions on slow chat accept when other op already accept

		$requestinfo = Chat_get_RequestCesInfo( $dbh, $ces ) ;
		// todo: op2op condition review for transfered chats
		if ( !isset( $requestinfo["status"] ) || ( $requestinfo["vupdated"] == 1 ) || ( ( $requestinfo["vupdated"] < ( time() - $VARS_EXPIRED_REQS ) ) && !$requestinfo["op2op"] ) )
			$tooslow = 1 ;
		else
		{
			include_once( "../API/Ops/get.php" ) ;

			// could use $_COOKIE["phplive_opID"] here - fix if error
			$opinfo = Ops_get_OpInfoByID( $dbh, $requestinfo["opID"] ) ;

			Ops_update_OpValue( $dbh, $_COOKIE["phplive_opID"], "lastrequest", time() ) ;
			Chat_update_AcceptChat( $dbh, $requestinfo["requestID"], $requestinfo["status"], $requestinfo["op2op"] ) ;

			// if transferred, keep the same created time
			if ( $requestinfo["status"] != 2 )
			{
				if ( $_COOKIE["phplive_opID"] != $requestinfo["opID"] )
					$tooslow = 1 ;
				else
				{
					Chat_update_RequestValue( $dbh, $requestid, "created", time() ) ;
					Chat_update_RequestLogValue( $dbh, $ces, "created", time() ) ;
					UtilChat_AppendToChatfile( "$ces.txt", "<div class='ca'><b>$opinfo[name]</b> ".LANG_CHAT_NOTIFY_JOINED."</div>" ) ;
				}
			}
			else
				UtilChat_AppendToChatfile( "$ces.txt", "<div class='ca'><b>$opinfo[name]</b> ".LANG_CHAT_NOTIFY_JOINED."</div>" ) ;

			Chat_update_RequestLogValue( $dbh, $ces, "status", 1 ) ;
		}

		if ( $tooslow )
			$json_data = "json_data = { \"status\": 1, \"tooslow\": 1 };" ;
		else
			$json_data = "json_data = { \"status\": 1, \"tooslow\": 0 };" ;
	}
	else if ( $action == "decline" )
	{
		include_once( "../API/SQL.php" ) ;
		include_once( "../API/Chat/get.php" ) ;
		include_once( "../API/Chat/update.php" ) ;

		$requestid = Util_Format_Sanatize( Util_Format_GetVar( "requestid" ), "ln" ) ;
		$isop = Util_Format_Sanatize( Util_Format_GetVar( "isop" ), "ln" ) ;
		$ces = Util_Format_Sanatize( Util_Format_GetVar( "ces" ), "ln" ) ;
		$op2op = Util_Format_Sanatize( Util_Format_GetVar( "op2op" ), "ln" ) ;
		$status = Util_Format_Sanatize( Util_Format_GetVar( "status" ), "ln" ) ;

		$requestinfo = Chat_get_RequestCesInfo( $dbh, $ces ) ;

		if ( ( $op2op || ( $status == 2 ) ) && ( $requestinfo["opID"] == $isop ) && ( $status == $requestinfo["status"] ) )
		{
			include_once( "../API/Chat/Util.php" ) ;
			include_once( "../API/Chat/remove.php" ) ;

			if ( !$status )
			{
				$text = "<c615><disconnected><d4><div class='cl'>Operator was not available for op2op chat.  Chat session has ended.</div></c615>" ;
				UtilChat_AppendToChatfile( "$ces.txt", $text ) ;
				Chat_remove_Request( $dbh, $requestinfo["requestID"] ) ;
			}
			else
			{
				$text = "<c615><restart_router><d4><div class='cl'>Transfer chat not available at this time.  Please hold while attempting to connect to the previous operator....</div></c615>" ;
				UtilChat_AppendToChatfile( "$ces.txt", $text ) ;
				Chat_update_TransferChatOrig( $dbh, $requestinfo["op2op"], $ces ) ;
			}
		}
		else if ( $requestinfo["opID"] == $isop )
		{
			// not a transfer, a standard request
			Chat_update_RequestValue( $dbh, $requestid, "vupdated", time() - 300 ) ; // 5 min back to auto route to next op
			Chat_update_RequestValue( $dbh, $requestid, "opID", 0 ) ;
		}

		$json_data = "json_data = { \"status\": 1, \"ces\": \"$ces\" };" ;
	}
	else if ( $action == "deptops" )
	{
		include_once( "../API/SQL.php" ) ;
		include_once( "../API/Depts/get.php" ) ;
		include_once( "../API/Ops/get.php" ) ;
		include_once( "../API/Chat/get.php" ) ;

		$departments = Depts_get_AllDepts( $dbh ) ;
		$json_data = "json_data = { \"status\": 1, \"departments\": [  " ;
		for ( $c = 0; $c < count( $departments ); ++$c )
		{
			$department = $departments[$c] ;
			$dept_ops = Depts_get_DeptOps( $dbh, $department["deptID"] ) ;

			$json_data .= "{ \"deptid\": $department[deptID], \"name\": \"$department[name]\", \"operators\": [  " ;
			for ( $c2 = 0; $c2 < count( $dept_ops ); ++$c2 )
			{
				$operator = $dept_ops[$c2] ;
				$requests = Chat_get_OpTotalRequests( $dbh, $operator["opID"] ) ;

				$json_data .= "{ \"opid\": $operator[opID], \"status\": $operator[status], \"name\": \"$operator[name]\", \"email\": \"$operator[email]\", \"requests\": $requests }," ;
			}
			$json_data = substr_replace( $json_data, "", -1 ) ;
			$json_data .= "	] }," ;
		}
		$json_data = substr_replace( $json_data, "", -1 ) ;
		$json_data .= "	] };" ;
	}
	else if ( $action == "footprints" )
	{
		include_once( "../API/SQL.php" ) ;
		include_once( "../API/Footprints/get_ext.php" ) ;
	
		$ip = Util_Format_Sanatize( Util_Format_GetVar( "ip" ), "ln" ) ;

		$footprints = Footprints_get_IPFootprints( $dbh, $ip, 25 ) ;
		$json_data = "json_data = { \"status\": 1, \"footprints\": [  " ;
		for ( $c = 0; $c < count( $footprints ); ++$c )
		{
			$footprint = $footprints[$c] ;
			$title = preg_replace( "/\"/", "&quot;", $footprint["title"] ) ;
			$onpage = preg_replace( "/hphp/i", "http", $footprint["onpage"] ) ;

			$json_data .= "{ \"total\": $footprint[total], \"onpage\": \"$onpage\", \"title\": \"$title\" }," ;
		}
		$json_data = substr_replace( $json_data, "", -1 ) ;
		$json_data .= "	] };" ;
	}
	else if ( $action == "transcripts" )
	{
		include_once( "../API/SQL.php" ) ;
		include_once( "../API/Ops/get.php" ) ;
		include_once( "../API/Chat/get_ext.php" ) ;
	
		$ip = Util_Format_Sanatize( Util_Format_GetVar( "ip" ), "ln" ) ;

		$operators = Ops_get_AllOps( $dbh ) ;
		$operators_hash = Array() ;
		for ( $c = 0; $c < count( $operators ); ++$c )
		{
			$operator = $operators[$c] ;
			$operators_hash[$operator["opID"]] = $operator["name"] ;
		}

		$transcripts = Chat_ext_get_IPTranscripts( $dbh, $ip, 25 ) ;
		$json_data = "json_data = { \"status\": 1, \"transcripts\": [  " ;
		for ( $c = 0; $c < count( $transcripts ); ++$c )
		{
			$transcript = $transcripts[$c] ;
			$operator = isset( $operators_hash[$transcript["opID"]] ) ? $operators_hash[$transcript["opID"]] : "INVALID" ;
			$created = date( "M j, Y (g:i:s a)", $transcript["created"] ) ;
			$duration_diff = $transcript["ended"] - $transcript["created"] ;
			if ( $duration_diff < 60 )
				$duration_diff = 60 ;

			$duration = Util_Format_Duration( $duration_diff ) ;

			$json_data .= "{ \"ces\": \"$transcript[ces]\", \"created\": \"$created\", \"operator\": \"$operator\", \"duration\": \"$duration\" }," ;
		}
		$json_data = substr_replace( $json_data, "", -1 ) ;
		$json_data .= "	] };" ;
	}
	else if ( $action == "spam_check" )
	{
		$ip = Util_Format_Sanatize( Util_Format_GetVar( "ip" ), "ln" ) ;

		if ( preg_match( "/$ip/", $VALS["CHAT_SPAM_IPS"], $matches ) && isset( $matches[0] ) )
			$exist = 1 ;
		else
			$exist = 0 ;

		$json_data = "json_data = { \"status\": 1, \"exist\": $exist }; " ;
	}
	else if ( $action == "spam_block" )
	{
		include_once( "../API/Util_Vals.php" ) ;

		$flag = Util_Format_Sanatize( Util_Format_GetVar( "flag" ), "ln" ) ;
		$ip = Util_Format_Sanatize( Util_Format_GetVar( "ip" ), "ln" ) ;

		if ( !preg_match( "/$ip/", $VALS["CHAT_SPAM_IPS"] ) && $flag )
		{
			$val = preg_replace( "/  +/", " ", $VALS["CHAT_SPAM_IPS"] ) . " $ip " ;
			Util_Vals_WriteToFile( "CHAT_SPAM_IPS", $val ) ;
			$json_data = "json_data = { \"status\": 1 }; " ;
		}
		else if ( preg_match( "/$ip /", $VALS["CHAT_SPAM_IPS"] ) && !$flag )
		{
			$val = preg_replace( "/$ip /", "", preg_replace( "/  +/", " ", $VALS["CHAT_SPAM_IPS"] ) ) ;
			Util_Vals_WriteToFile( "CHAT_SPAM_IPS", $val ) ;
			$json_data = "json_data = { \"status\": 1 }; " ;
		}
		else
			$json_data = "json_data = { \"status\": 1 }; " ;
	}
	else if ( $action == "transfer" )
	{
		include_once( "../API/SQL.php" ) ;
		include_once( "../API/Chat/update.php" ) ;
		include_once( "../API/Chat/Util.php" ) ;

		$requestid = Util_Format_Sanatize( Util_Format_GetVar( "requestid" ), "ln" ) ;
		$ces = Util_Format_Sanatize( Util_Format_GetVar( "ces" ), "ln" ) ;
		$deptid = Util_Format_Sanatize( Util_Format_GetVar( "deptid" ), "ln" ) ;
		$deptname = Util_Format_Sanatize( Util_Format_GetVar( "deptname" ), "ln" ) ;
		$opid = Util_Format_Sanatize( Util_Format_GetVar( "opid" ), "ln" ) ;
		$opname = Util_Format_Sanatize( Util_Format_GetVar( "opname" ), "ln" ) ;

		$text = "<div class='ca'>Transferring chat to <b><top>$opname</top></b> of <b>$deptname</b>.<div style='margin-top: 10px;'><div class='ctitle'>Transferring...</div></div></div>" ;

		Chat_update_TransferChat( $dbh, $ces, $_COOKIE["phplive_opID"], $deptid, $opid ) ;
		UtilChat_AppendToChatfile( "$ces.txt", $text ) ;
		$json_data = "json_data = { \"status\": 1 };" ;
	}
	else if ( $action == "opop" )
	{
		include_once( "../API/SQL.php" ) ;
		include_once( "../API/Util_Security.php" ) ;
		include_once( "../API/Ops/get.php" ) ;
		include_once( "../API/Chat/put.php" ) ;

		$deptid = Util_Format_Sanatize( Util_Format_GetVar( "deptid" ), "ln" ) ;
		$opid = Util_Format_Sanatize( Util_Format_GetVar( "opid" ), "ln" ) ;
		$resolution = Util_Format_Sanatize( Util_Format_GetVar( "win_dim" ), "ln" ) ;

		$ces = Util_Security_GenSetupSes() ;
		$agent = $_SERVER["HTTP_USER_AGENT"] ;
		if ( Util_Format_isMobile( $agent ) ) { $os = 5 ; }
		else if ( preg_match( "/Windows/i", $agent ) ) { $os = 1 ; }
		else if ( preg_match( "/Mac/i", $agent ) ) { $os = 2 ; }
		else { $os = 4 ; }

		if ( preg_match( "/MSIE/i", $agent ) ) { $browser = 1 ; }
		else if ( preg_match( "/Firefox/i", $agent ) ) { $browser = 2 ; }
		else if ( preg_match( "/Chrome/i", $agent ) ) { $browser = 3 ; }
		else if ( preg_match( "/Safari/i", $agent ) ) { $browser = 4 ; }
		else { $browser = 6 ; }

		$opinfo = Ops_get_OpInfoByID( $dbh, $_COOKIE["phplive_opID"] ) ;

		if ( isset( $opinfo["opID"] ) )
		{
			if ( $requestid = Chat_put_Request( $dbh, $deptid, $opid, 0, 0, $_COOKIE["phplive_opID"], 0, $os, $browser, $ces, $resolution, $opinfo["name"], $opinfo["email"], $_SERVER['REMOTE_ADDR'], $agent, "", "", "<op2op>", 0, "" ) )
			{
				Chat_put_ReqLog( $dbh, $requestid ) ;
				$json_data = "json_data = { \"status\": 1, \"ces\": \"$ces\" };" ;
			}
			else
				$json_data = "json_data = { \"status\": -1 };" ;
		}
		else
			$json_data = "json_data = { \"status\": 0 };" ;
	}
	else if ( $action == "traffic" )
	{
		include_once( "../API/SQL.php" ) ;
		include_once( "../API/Util_Format.php" ) ;
		include_once( "../API/Ops/get.php" ) ;
		include_once( "../API/Footprints/get.php" ) ;

		$dept_string = "" ;
		// todo: disabled for further testing [mod Sam: 60]
		/*
		$departments = Ops_get_OpDepts( $dbh, $_COOKIE["phplive_opID"] ) ;
		for ( $c = 0; $c < count( $departments ); ++$c )
		{
			$department = $departments[$c] ;
			$dept_string .= " OR deptID = $department[deptID]" ;
		}
		*/

		$traffics = Footprints_get_Footprints_U( $dbh, $dept_string ) ;
		$json_data = "json_data = { \"status\": 1, \"traffics\": [  " ;
		for ( $c = 0; $c < count( $traffics ); ++$c )
		{
			$traffic = $traffics[$c] ;
			$duration = $traffic["updated"] - $traffic["created"] ;
			if ( $duration < 60 )
				$duration = 60 ;
			$duration = Util_Format_Duration( $duration ) ;
			$os = $VARS_OS[$traffic["os"]] ;
			$browser = $VARS_BROWSER[$traffic["browser"]] ;
			$title = preg_replace( "/\"/", "&quot;", $traffic["title"] ) ;
			$onpage = preg_replace( "/hphp/i", "http", $traffic["onpage"] ) ;
			$refer_raw = preg_replace( "/hphp/i", "http", preg_replace( "/\"/", "&quot;", $traffic["refer"] ) ) ;
			$refer_snap = ( strlen( $refer_raw ) > 30 ) ? substr( $refer_raw, 0, 30 ) . "..." : $refer_raw ;
			$refer_snap = preg_replace( "/((http)|(https)):\/\/(www.)/", "", $refer_snap ) ;

			$t_footprints = $traffic["t_footprints"] ;
			$t_requests = $traffic["t_requests"] ;
			$t_initiated = $traffic["t_initiate"] ;

			$md5 = md5( $traffic["ip"] ) ;

			$json_data .= "{ \"md5\": \"$md5\", \"chatting\": $traffic[chatting], \"ip\": \"$traffic[ip]\", \"hostname\": \"$traffic[hostname]\", \"onpage\": \"$onpage\", \"title\": \"$title\", \"duration\": \"$duration\", \"os\": \"$os\", \"browser\": \"$browser\", \"resolution\": \"$traffic[resolution]\", \"marketid\": \"$traffic[marketID]\", \"refer_snap\": \"$refer_snap\", \"refer_raw\": \"$refer_raw\", \"t_footprints\": $t_footprints, \"t_requests\": $t_requests, \"t_initiated\": $t_initiated }," ;
		}
		$json_data = substr_replace( $json_data, "", -1 ) ;
		$json_data .= "	] };" ;
	}
	else if ( $action == "cans" )
	{
		include_once( "../API/SQL.php" ) ;
		include_once( "../API/Canned/get.php" ) ;

		$deptid = Util_Format_Sanatize( Util_Format_GetVar( "deptid" ), "ln" ) ;

		$cans = Canned_get_OpCanned( $dbh, $_COOKIE["phplive_opID"], $deptid ) ;
		$json_data = "json_data = { \"status\": 1, \"cans\": [  " ;
		for ( $c = 0; $c < count( $cans ); ++$c )
		{
			$can = $cans[$c] ;
			$message = preg_replace( "/\"/", "&quot;", preg_replace( "/'/", "&#39;", preg_replace( "/(\r\n)|(\n)|(\r)/", "<br>", $can["message"] ) ) ) ;

			$json_data .= "{ \"deptid\": $can[deptID], \"title\": \"$can[title]\", \"message\": \"$message\" }," ;
		}
		$json_data = substr_replace( $json_data, "", -1 ) ;
		$json_data .= "	] };" ;
	}
	else if ( $action == "update_status" )
	{
		include_once( "../API/SQL.php" ) ;
		include_once( "../API/Ops/update.php" ) ;
	
		$opid = Util_Format_Sanatize( Util_Format_GetVar( "opid" ), "ln" ) ;
		$status = Util_Format_Sanatize( Util_Format_GetVar( "status" ), "ln" ) ;

		Ops_update_OpValue( $dbh, $opid, "status", $status ) ;
		Ops_update_OpValue( $dbh, $opid, "lastactive", time() ) ;
		$json_data = "json_data = { \"status\": 1 }; " ;
	}
	else if ( $action == "markets" )
	{
		include_once( "../API/SQL.php" ) ;
		include_once( "../API/Marketing/get.php" ) ;

		$markets = Marketing_get_AllMarketing( $dbh ) ;
		$json_data = "json_data = { \"status\": 1, \"markets\": [  " ;
		for ( $c = 0; $c < count( $markets ); ++$c )
		{
			$market = $markets[$c] ;

			$json_data .= "{ \"marketid\": $market[marketID], \"name\": \"$market[name]\", \"color\": \"$market[color]\" }," ;
		}
		$json_data = substr_replace( $json_data, "", -1 ) ;
		$json_data .= "	] };" ;
	}
	else if ( $action == "fetch_ratings" )
	{
		include_once( "../API/SQL.php" ) ;
		include_once( "../API/Ops/get.php" ) ;
		include_once( "../API/Ops/update.php" ) ;
		include_once( "../API/Chat/get.php" ) ;

		$ses = Util_Format_Sanatize( Util_Format_GetVar( "ses" ), "ln" ) ;

		$opinfo = Ops_get_OpInfoByID( $dbh, $_COOKIE["phplive_opID"] ) ;

		$ratings = Chat_get_OpOverallRatings( $dbh, $_COOKIE["phplive_opID"] ) ;
		$overall = ( $ratings["total"] ) ? $ratings["rating"]/$ratings["total"] : 0 ;
		$rating_overall_stars = Util_Format_Stars( $overall ) ;
		$recent = Chat_get_OpRecentRatings( $dbh, $_COOKIE["phplive_opID"] ) ;
		$recent_ces = ( isset( $recent["ces"] ) ) ? $recent["ces"] : "" ;
		$recent_rating = ( isset( $recent["rating"] ) ) ? $recent["rating"] : 0 ;
		$rating_recent_stars =  Util_Format_Stars( $recent_rating ) ;

		$status = ( $opinfo["ses"] != $ses ) ? 0 : 1 ;
		$signal = $opinfo["signall"] ;
		if ( $signal )
			Ops_update_OpValue( $dbh, $opinfo["opID"], "signall", 0 ) ;

		$json_data = "json_data = { \"status\": 1, \"rating_overall\": \"$rating_overall_stars\", \"rating_recent\": \"$rating_recent_stars\", \"ces\": \"$recent_ces\", \"status_op\": $status, \"signal\": $signal }; " ;
	}
	else if ( $action == "logout" )
	{
		include_once( "../API/SQL.php" ) ;
		include_once( "../API/Ops/update.php" ) ;

		if ( isset( $_COOKIE["phplive_opID"] ) )
		{
			Ops_update_OpValue( $dbh, $_COOKIE["phplive_opID"], "status", 0 ) ;
			setcookie( "phplive_opID", FALSE ) ;
			$json_data = "json_data = { \"status\": 1 };" ;
		}
		else
			$json_data = "json_data = { \"status\": 0 };" ;
	}
	else if ( $action == "initiate" )
	{
		include_once( "../API/SQL.php" ) ;
		include_once( "../API/Util_Security.php" ) ;
		include_once( "../API/Ops/get.php" ) ;
		include_once( "../API/Ops/put.php" ) ;
		include_once( "../API/Chat/get.php" ) ;
		include_once( "../API/Chat/put.php" ) ;
		include_once( "../API/Chat/Util.php" ) ;
		include_once( "../API/Footprints/get.php" ) ;
		include_once( "../API/IPs/put.php" ) ;

		$deptid = Util_Format_Sanatize( Util_Format_GetVar( "deptid" ), "ln" ) ;
		$ip = Util_Format_Sanatize( Util_Format_GetVar( "ip" ), "ln" ) ;
		$question = rawurldecode( Util_Format_Sanatize( Util_Format_GetVar( "question" ), "htmltags" ) ) ;

		if ( $question && $deptid && $ip )
		{
			$ces = Util_Security_GenSetupSes() ;
			$footprintinfo = Footprints_get_IPFootprints_U( $dbh, $ip ) ;

			$opinfo = Ops_get_OpInfoByID( $dbh, $_COOKIE["phplive_opID"] ) ;

			if ( isset( $opinfo["opID"] ) && isset( $footprintinfo["ip"] ) )
			{
				$requestinfo = Chat_get_RequestIPInfo( $dbh, $footprintinfo["ip"], 1 ) ;

				if ( !isset( $requestinfo["requestID"] ) )
				{
					if ( $requestid = Chat_put_Request( $dbh, $deptid, $opinfo["opID"], 0, 1, 0, 0, $footprintinfo["os"], $footprintinfo["browser"], $ces, $footprintinfo["resolution"], "Visitor", "null", $ip, "&nbsp;", $footprintinfo["onpage"], $footprintinfo["title"], $question, 0, $footprintinfo["refer"] ) )
					{
						IPs_put_IP( $dbh, $ip, 0, 0, 1 ) ;
						Ops_put_OpReqStat( $dbh, $deptid, $opinfo["opID"], "initiated", 1 ) ;
						Chat_put_ReqLog( $dbh, $requestid ) ;
						UtilChat_AppendToChatfile( "$ces.txt", "<div class='ca'><b>".LANG_CHAT_WELCOME."</b></div><div class='co'><b>$opinfo[name]<timestamp_".time()."_co>:</b> $question</div>" ) ;

						$json_data = "json_data = { \"status\": 1, \"ces\": \"$ces\" };" ;
					}
					else
						$json_data = "json_data = { \"status\": 0, \"error\": \"Could not initiate: $dbh[error]\" };" ;
				}
				else
					$json_data = "json_data = { \"status\": 0, \"error\": \"Visitor is busy with another initiate chat session.\" };" ;
			}
			else
				$json_data = "json_data = { \"status\": 0, \"error\": \"Invalid initiate data.\" };" ;
		}
		else
			$json_data = "json_data = { \"status\": 0, \"error\": \"Blank initiate message or department is invalid.\" };" ;
	}
	else if ( $action == "requestinfo" )
	{
		include_once( "../API/SQL.php" ) ;
		include_once( "../API/Chat/get.php" ) ;
		include_once( "../API/Chat/get_ext.php" ) ;
	
		$ip = Util_Format_Sanatize( Util_Format_GetVar( "ip" ), "ln" ) ;

		$requestinfo = Chat_get_RequestIPInfo( $dbh, $ip, 0 ) ;
		$total_trans = Chat_ext_get_TotalIPTranscripts( $dbh, $ip ) ;

		if ( isset( $requestinfo["requestID"] ) )
		{
			include_once( "../API/Ops/get.php" ) ;

			$opinfo = Ops_get_OpInfoByID( $dbh, $requestinfo["opID"] ) ;

			$json_data = "json_data = { \"status\": 1, \"name\": \"$opinfo[name]\", \"total_trans\": \"$total_trans\" }; " ;
		}
		else
			$json_data = "json_data = { \"status\": 0, \"total_trans\": \"$total_trans\" }; " ;
	}
	else
		$json_data = "json_data = { \"status\": 0 };" ;

	if ( isset( $dbh ) && isset( $dbh['con'] ) )
		database_mysql_close( $dbh ) ;

	$json_data = preg_replace( "/\r\n/", "", $json_data ) ;
	$json_data = preg_replace( "/\t/", "", $json_data ) ;
	print "$json_data" ;
	exit ;
?>
