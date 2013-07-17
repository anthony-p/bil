<?php
	include_once( "../web/config.php" ) ;
	include_once( "../API/Util_Format.php" ) ;
	include_once( "../lang_packs/$CONF[lang].php" ) ;

	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;
	$ip = Util_Format_Sanatize( Util_Format_GetVar( "ip" ), "ln" ) ;

	if ( $action == "disconnect" )
	{
		include_once( "../API/SQL.php" ) ;
		include_once( "../API/Chat/get.php" ) ;
		include_once( "../API/Footprints/update.php" ) ;

		$isop = Util_Format_Sanatize( Util_Format_GetVar( "isop" ), "ln" ) ;
		$ces = Util_Format_Sanatize( Util_Format_GetVar( "ces" ), "ln" ) ;
		$widget = Util_Format_Sanatize( Util_Format_GetVar( "widget" ), "ln" ) ;

		$now = time() ;
		if ( $widget )
		{
			$requestinfo = Chat_get_RequestIPInfo( $dbh, $ip, 1 ) ;
			$ces = $requestinfo["ces"] ;
		}
		else
			$requestinfo = Chat_get_RequestCesInfo( $dbh, $ces ) ;

		if ( isset( $requestinfo["requestID"] ) )
		{
			include_once( "../API/Chat/put.php" ) ;
			include_once( "../API/Chat/update.php" ) ;
			include_once( "../API/Chat/remove.php" ) ;
			include_once( "../API/Chat/Util.php" ) ;

			if ( $isop )
				$text = "<div class='cl'><disconnected><d1>".LANG_CHAT_NOTIFY_ODISCONNECT."</div>" ;
			else
			{
				if ( $requestinfo["initiated"] && !$requestinfo["status"] )
					$text = "<div class='cl'><disconnected><d2>Visitor has declined the chat invitation.</div>" ;
				else
					$text = "<div class='cl'><disconnected><d2>".LANG_CHAT_NOTIFY_VDISCONNECT."</div>" ;
			}

			UtilChat_AppendToChatfile( "$ces.txt", $text ) ;
			Chat_update_RequestLogValue( $dbh, $ces, "ended", $now ) ;

			if ( !$requestinfo["initiated"] || ( $requestinfo["initiated"] && $requestinfo["status"] ) )
			{
				$output = UtilChat_ExportChat( "$ces.txt" ) ;
				if ( isset( $output[0] ) )
				{
					$formatted = $output[0] ; $plain = $output[1] ;
					$fsize = strlen( $formatted ) ;
					if ( Chat_put_Transcript( $dbh, $ces, $requestinfo["status"], $requestinfo["etrans"], $requestinfo["created"], $now, $requestinfo["deptID"], $requestinfo["opID"], $requestinfo["initiated"], $requestinfo["op2op"], 0, $fsize, $requestinfo["vname"],	$requestinfo["vemail"], $requestinfo["ip"], $requestinfo["question"], $formatted, $plain ) )
						Chat_remove_Request( $dbh, $requestinfo["requestID"] ) ;
				}
			}
			else if ( $requestinfo["initiated"] )
				Chat_remove_Request( $dbh, $requestinfo["requestID"] ) ;
		}
		else if ( isset( $requestinfo["requestID"] ) && !$requestinfo["status"] )
		{
			if ( $isop && ( $requestinfo["opID"] != $isop ) )
			{
				if ( $requestinfo["op2op"] )
				{
					include_once( "../API/Chat/Util.php" ) ;
					include_once( "../API/Chat/remove.php" ) ;

					Chat_remove_Request( $dbh, $requestinfo["requestID"] ) ;
					UtilChat_RemoveChatfile( "$ces.txt" ) ;
				}
			}
			else
			{
				include_once( "../API/Chat/Util.php" ) ;
				include_once( "../API/Chat/update.php" ) ;
				include_once( "../API/Chat/remove.php" ) ;

				Chat_update_RequestLogValue( $dbh, $ces, "ended", time() ) ;
				Chat_remove_Request( $dbh, $requestinfo["requestID"] ) ;
				UtilChat_RemoveChatfile( "$ces.txt" ) ;
			}
		}

		// safe measure cleaning
		if ( file_exists( realpath( "$CONF[DOCUMENT_ROOT]/web/chat_initiate/$ip.txt" ) ) )
			unlink( "$CONF[DOCUMENT_ROOT]/web/chat_initiate/$ip.txt" ) ;
		Footprints_update_FootprintUniqueValue( $dbh, $ip, "chatting", 0 ) ;
		// end safe measure cleaning

		if ( $widget )
		{
			$image_dir = realpath( "$CONF[DOCUMENT_ROOT]/pics/icons/pixels" ) ;
			$image_path = "$image_dir/1x1.gif" ;
			Header( "Content-type: image/GIF" ) ;
			readfile( $image_path ) ;
			exit ;
		}
		else
			$json_data = "json_data = { \"status\": 1, \"ces\": \"$ces\" };" ;
	}
	else if ( $action == "rating" )
	{
		include_once( "../API/SQL.php" ) ;
		include_once( "../API/Chat/update.php" ) ;

		$requestid = Util_Format_Sanatize( Util_Format_GetVar( "requestid" ), "ln" ) ;
		$ces = Util_Format_Sanatize( Util_Format_GetVar( "ces" ), "ln" ) ;
		$rating = Util_Format_Sanatize( Util_Format_GetVar( "rating" ), "ln" ) ;

		if ( Chat_update_TranscriptValue( $dbh, $ces, "rating", $rating ) )
			$json_data = "json_data = { \"status\": 1 };" ;
		else
			$json_data = "json_data = { \"status\": 0 };" ;
	}
	else if ( $action == "istyping" )
	{
		include_once( "../API/Chat/Util.php" ) ;

		$ces = Util_Format_Sanatize( Util_Format_GetVar( "ces" ), "ln" ) ;
		$wname = Util_Format_Sanatize( Util_Format_GetVar( "wname" ), "v" ) ;
		$rname = Util_Format_Sanatize( Util_Format_GetVar( "rname" ), "v" ) ;
		$flag = Util_Format_Sanatize( Util_Format_GetVar( "flag" ), "ln" ) ;

		if ( $flag )
			UtilChat_WriteIsWriting( $ces, $flag, $wname, $rname ) ;
		else
			UtilChat_WriteIsWriting( $ces, $flag, $wname, $rname ) ;

		$json_data = "json_data = { \"status\": 1 };" ;
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
