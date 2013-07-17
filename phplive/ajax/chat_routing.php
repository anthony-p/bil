<?php
	/*
	// status json route: -1 no request, 0 same op route, 1 request accepted, 2 new op route, 10 leave a message
	//
	// status DB request: -1 ended by action taken, 0 waiting pick-up, 1 picked up, 2 transfer
	*/
	include_once( "../web/config.php" ) ;
	include_once( "../API/Util_Format.php" ) ;
	include_once( "../API/Util_Vars.php" ) ;

	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;

	if ( $action == "requests" )
	{
		if ( !isset( $_COOKIE["phplive_opID"] ) || !$_COOKIE["phplive_opID"] )
			$json_data = "json_data = { \"status\": -1 };" ;
		else
		{
			include_once( "../API/SQL.php" ) ;
			include_once( "../API/Chat/Util.php" ) ;
			include_once( "../API/Chat/get.php" ) ;
			include_once( "../API/Chat/update.php" ) ;
			include_once( "../API/Footprints/get.php" ) ;

			$prev_status = Util_Format_Sanatize( Util_Format_GetVar( "prev_status" ), "ln" ) ;
			$c_requesting = Util_Format_Sanatize( Util_Format_GetVar( "c_requesting" ), "ln" ) ;
			$traffic = Util_Format_Sanatize( Util_Format_GetVar( "traffic" ), "ln" ) ;

			// do the clean on old requests, transcript files, operator status, etc every xth call
			// ~3sec = 1 cycle
			if ( !( $c_requesting % $VARS_CYCLE_CLEAN ) )
			{
				include_once( "../API/Chat/remove.php" ) ;
				include_once( "../API/Footprints/remove.php" ) ;

				Footprints_remove_Expired_U( $dbh ) ;
				Chat_remove_ExpiredOp2OpRequests( $dbh ) ;
				UtilChat_CleanChatDir( $dbh ) ;
				Chat_remove_OldRequests( $dbh ) ;
			}
			if ( !( $c_requesting % $VARS_CYCLE_RESET ) )
			{
				include_once( "../API/Ops/update.php" ) ;

				Ops_update_IdleOps( $dbh, $_COOKIE["phplive_opID"], $prev_status ) ;
				Ops_update_OpValue( $dbh, $_COOKIE["phplive_opID"], "lastactive", time() ) ;
				Chat_update_OpRequestUpdated( $dbh, $_COOKIE["phplive_opID"] ) ;
			}
			/********** END CLEAN UP AND OTHER ACTIONS ***********/

			$total_traffics = ( $traffic ) ? Footprints_get_TotalFootprints_U( $dbh ) : 0 ;
			$requests_temp = Chat_get_Requests( $dbh, $_COOKIE["phplive_opID"] ) ;

			// filter out various conditions
			$requests = Array() ;
			for ( $c = 0; $c < count( $requests_temp ); ++$c )
			{
				$data = $requests_temp[$c] ;
				if ( ( $data["status"] == 2 ) && ( $data["op2op"] == $_COOKIE["phplive_opID"] ) )
				{
					// 30 seconds then transfer back to original operator
					if ( $data["tupdated"] < ( time() - $VARS_TRANSFER_BACK ) )
					{
						$text = "<c615><restart_router><d4><div class='ca'>Transfer chat not available at this time.  Please hold while attempting to connect to the previous operator...</div></c615>" ;
						UtilChat_AppendToChatfile( "$data[ces].txt", $text ) ;
						Chat_update_TransferChatOrig( $dbh, $data["op2op"], $data["ces"] ) ;
					}
				}
				else
					$requests[] = $data ;
			}

			$json_data = "json_data = { \"status\": 1, \"traffics\": $total_traffics, \"requests\": [  " ;
			for ( $c = 0; $c < count( $requests ); ++$c )
			{
				$request = $requests[$c] ;

				$os = $VARS_OS[$request["os"]] ;
				$browser = $VARS_BROWSER[$request["browser"]] ;
				$title = preg_replace( "/\"/", "&quot;", $request["title"] ) ;
				$question = preg_replace( "/\"/", "&quot;", $request["question"] ) ;
				$onpage = preg_replace( "/hphp/i", "http", $request["onpage"] ) ;
				$refer_raw = preg_replace( "/hphp/i", "http", $request["refer"] ) ;
				$refer_snap = ( strlen( $refer_raw ) > 50 ) ? substr( $refer_raw, 0, 45 ) . "..." : $refer_raw ;

				// if status is 2 then it's a transfer call... keep original visitor name
				if ( ( $request["status"] != 2 ) && $request["op2op"] )
				{
					// dynamically fill name and email according to the requesting operator
					include_once( "../API/Ops/get.php" ) ;

					if ( $_COOKIE["phplive_opID"] == $request["op2op"] )
						$opinfo = Ops_get_OpInfoByID( $dbh, $request["opID"] ) ;
					else
						$opinfo = Ops_get_OpInfoByID( $dbh, $request["op2op"] ) ;
					$vname = $opinfo["name"] ; $vemail = $opinfo["email"] ;
				}
				else
					$vname = $request["vname"] ; $vemail = $request["vemail"] ;

				$json_data .= "{ \"requestid\": $request[requestID], \"ces\": \"$request[ces]\", \"created\": \"$request[created]\", \"deptid\": $request[deptID], \"opid\": $request[opID], \"op2op\": $request[op2op], \"vname\": \"$vname\", \"status\": $request[status], \"initiated\": $request[initiated], \"etrans\": $request[etrans], \"os\": \"$os\", \"browser\": \"$browser\", \"requests\": \"$request[requests]\", \"resolution\": \"$request[resolution]\", \"vemail\": \"$vemail\", \"ip\": \"$request[ip]\", \"hostname\": \"$request[hostname]\", \"agent\": \"$request[agent]\", \"onpage\": \"$onpage\", \"title\": \"$title\", \"question\": \"$question\", \"marketid\": \"$request[marketID]\", \"refer_raw\": \"$refer_raw\", \"refer_snap\": \"$refer_snap\" }," ;
			}
			$json_data = substr_replace( $json_data, "", -1 ) ;
			$json_data .= "	] };" ;
		}
	}
	else
	{
		include_once( "../API/SQL.php" ) ;
		include_once( "../API/Chat/get.php" ) ;

		$ces = Util_Format_Sanatize( Util_Format_GetVar( "ces" ), "ln" ) ;
		$deptid = Util_Format_Sanatize( Util_Format_GetVar( "deptid" ), "ln" ) ;
		$rtype = Util_Format_Sanatize( Util_Format_GetVar( "rtype" ), "ln" ) ;
		$rtime = Util_Format_Sanatize( Util_Format_GetVar( "rtime" ), "ln" ) ;

		$requestinfo = Chat_get_RequestCesInfo( $dbh, $ces ) ;
		if ( !isset( $requestinfo["requestID"] ) )
			$json_data = "json_data = { \"status\": 10 };" ;
		else
		{
			if ( $requestinfo["status"] )
			{
				include_once( "../API/Ops/get.php" ) ;
				include_once( "../API/Ops/put.php" ) ;

				$opinfo = Ops_get_OpInfoByID( $dbh, $requestinfo["opID"] ) ;
				Ops_put_OpReqStat( $dbh, $requestinfo["deptID"], $opinfo["opID"], "taken", 1 ) ;

				$json_data = "json_data = { \"status\": 1, \"status_request\": $requestinfo[status], \"requestid\": $requestinfo[requestID], \"initiated\": $requestinfo[initiated], \"name\": \"$opinfo[name]\", \"deptid\": $deptid, \"opid\": $opinfo[opID], \"email\": \"$opinfo[email]\", \"pic\": \"$opinfo[pic]\" };" ;
			}
			else
			{
				// vupdated is used for routing UNTIL chat is accepted then it is used
				// for visitor's callback updated time
				$rupdated = $requestinfo["vupdated"] + $rtime ;
				if ( time() <= $rupdated )
					$json_data = "json_data = { \"status\": 0 };" ;
				else
				{
					include_once( "../API/Chat/update.php" ) ;
					include_once( "../API/Ops/get.php" ) ;
					include_once( "../API/Ops/put.php" ) ;

					Ops_put_OpReqStat( $dbh, $deptid, $requestinfo["opID"], "declined", 1 ) ;

					$opinfo_next = Ops_get_NextRequestOp( $dbh, $deptid, $rtype, $requestinfo["rstring"] ) ;
					if ( isset( $opinfo_next["opID"] ) )
					{
						Chat_update_RequestValue( $dbh, $requestinfo["requestID"], "vupdated", time() ) ;
						Chat_update_RequestValue( $dbh, $requestinfo["requestID"], "opID", $opinfo_next["opID"] ) ;
						Chat_update_RequestValue( $dbh, $requestinfo["requestID"], "rstring", " $requestinfo[rstring] AND p_operators.opID <> $opinfo_next[opID] " ) ;
						$json_data = "json_data = { \"status\": 2 };" ;
					}
					else
					{
						include_once( "../API/Chat/put.php" ) ;
						include_once( "../API/Chat/update.php" ) ;
						include_once( "../API/Chat/remove.php" ) ;

						// leave a message
						// on stats db the leave a message is not op specific, just use the current opID to track
						// requests that went to leave a messge
						Ops_put_OpReqStat( $dbh, $deptid, $requestinfo["opID"], "message", 1 ) ;
						Chat_remove_RequestByCes( $dbh, $requestinfo["ces"] ) ;
						$json_data = "json_data = { \"status\": 10 };" ;
					}
				}
			}
		}
	}

	if ( isset( $dbh ) && isset( $dbh['con'] ) )
		database_mysql_close( $dbh ) ;

	$json_data = preg_replace( "/\r\n/", "", $json_data ) ;
	$json_data = preg_replace( "/\t/", "", $json_data ) ;
	print "$json_data" ;
	exit ;
?>
