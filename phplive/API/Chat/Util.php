<?php

	/*****************************************************************/
	FUNCTION UtilChat_AppendToChatfile( $chatfile,
							$string )
	{
		if ( ( $chatfile == "" ) || ( $string == "" ) )
			return false ;

		global $CHAT_IO_DIR ;
		$chat_to_dir = realpath( preg_replace( "/[\$\!`\"<>'\?;]|(\.\.)/", "", $CHAT_IO_DIR ) ) ;

		$string .= "<>" ; // add new line marker

		if ( phpversion() >= "5.0.0" )
			file_put_contents( "$chat_to_dir/$chatfile", $string, FILE_APPEND ) ;
		else
		{
			$fp = fopen( "$chat_to_dir/$chatfile", "a" ) ;
			fwrite( $fp, $string, strlen( $string ) ) ;
			fclose( $fp ) ;
		}

		return true ;
	}

	/*****************************************************************/
	FUNCTION UtilChat_RemoveChatfile( $chatfile )
	{
		if ( $chatfile == "" )
			return false ;

		global $CHAT_IO_DIR ;
		$chat_to_dir = realpath( preg_replace( "/[\$\!`\"<>'\?;]|(\.\.)/", "", $CHAT_IO_DIR ) ) ;

		if ( file_exists( "$chat_to_dir/$chatfile" ) )
			unlink( "$chat_to_dir/$chatfile" ) ;
		return true ;
	}

	/*****************************************************************/
	FUNCTION UtilChat_ExportChat( $chatfile )
	{
		if ( $chatfile == "" )
			return false ;

		global $CHAT_IO_DIR ;
		$chat_to_dir = realpath( preg_replace( "/[\$\!`\"<>'\?;]|(\.\.)/", "", $CHAT_IO_DIR ) ) ;

		$output = Array() ;

		if ( file_exists( realpath( "$chat_to_dir/$chatfile" ) ) )
		{
			$trans_raw = file_get_contents( "$chat_to_dir/$chatfile" ) ;
			$output[] = $trans_raw ;
			$output[] = preg_replace( "/<(.*?)>/", "", preg_replace( "/<>/", "\r\n", $trans_raw ) ) ;
		}

		return $output ;
	}

	/*****************************************************************/
	// todo: check for existing transcripts for duplicate entries
	FUNCTION UtilChat_CleanChatDir( $dbh )
	{
		global $CONF ;
		global $CHAT_IO_DIR ;
		$chat_to_dir = realpath( preg_replace( "/[\$\!`\"<>'\?;]|(\.\.)/", "", $CHAT_IO_DIR ) ) ;
		$document_root = preg_replace( "/[\$\!`\"<>'\?;]|(\.\.)/", "", $CONF["DOCUMENT_ROOT"] ) ;

		$trans_removed = Array() ;

		if ( !file_exists( "$chat_to_dir/TIMESTAMP" ) )
			touch( "$chat_to_dir/TIMESTAMP" ) ;

		// only do checking if last check was done x timeframe ago..
		// make it over 1 minute or longer to help load
		$flagtime = time() - 180 ;
		$filetime = filemtime( "$chat_to_dir/TIMESTAMP" ) ;

		if ( $filetime < $flagtime )
		{
			include_once( realpath( "$document_root/API/Chat/put.php" ) ) ;
			include_once( realpath( "$document_root/API/Chat/get.php" ) ) ;
			include_once( realpath( "$document_root/API/Chat/update.php" ) ) ;

			$now = time() ;

			$query = "UPDATE p_footprints_u SET chatting = 0 WHERE chatting <> 0" ;
			database_mysql_query( $dbh, $query ) ;

			$transcripts = $active_requests_hash = Array() ;
			$active_requests = Chat_get_ActiveRequests( $dbh ) ;
			for ( $c = 0; $c < count( $active_requests ); ++$c )
			{
				$ip = $active_requests[$c]["ip"] ;
				$active_requests_hash[$active_requests[$c]["ces"]] = $ip ;

				$query = "UPDATE p_footprints_u SET chatting = 1 WHERE ip = '$ip'" ;
				database_mysql_query( $dbh, $query ) ;
			}

			$chatdir = opendir( $chat_to_dir ) ;
			while ( $this_transcript = readdir( $chatdir ) )
				$transcripts[] = trim( $this_transcript ) ;
			closedir( $chatdir ) ;

			if ( count( $transcripts ) )
			{
				include_once( realpath( "$document_root/API/Chat/remove.php" ) ) ;
				//include_once( realpath( "$document_root/API/Footprints/update.php" ) ) ;

				for ( $c = 0; $c < count( $transcripts ); ++$c )
				{
					$transcript = $transcripts[$c] ;
					$ces = preg_replace( "/(.txt)/", "", $transcript ) ;

					if ( preg_match( "/(txt)/", $transcript ) && !isset( $active_requests_hash[$ces] ) )
					{
						$trans_removed[$ces] = 1 ;
						$output = UtilChat_ExportChat( $transcript ) ;
						if ( isset( $output[0] ) && file_exists( "$chat_to_dir/$transcript" ) )
						{
							LIST( $formatted, $plain ) = $output ;

							$fsize = strlen( $formatted ) ;
							$requestinfo = Chat_get_RequestHistCesInfo( $dbh, $ces ) ;
							$ip = $requestinfo["ip"] ;
							
							if ( file_exists( realpath( "$CONF[DOCUMENT_ROOT]/web/chat_initiate/$ip.txt" ) ) )
								unlink( "$CONF[DOCUMENT_ROOT]/web/chat_initiate/$ip.txt" ) ;

							if ( !$requestinfo["ended"] )
								Chat_update_RequestLogValue( $dbh, $ces, "ended", filemtime( "$chat_to_dir/$transcript" ) ) ;

							if ( !$requestinfo["initiated"] )
							{
								if ( Chat_put_Transcript( $dbh, $ces, $requestinfo["status"], $requestinfo["etrans"], $requestinfo["created"], $now, $requestinfo["deptID"], $requestinfo["opID"], $requestinfo["initiated"], $requestinfo["op2op"], 0, $fsize, $requestinfo["vname"],	$requestinfo["vemail"], $requestinfo["ip"], $requestinfo["question"], $formatted, $plain ) )
									Chat_remove_RequestByCes( $dbh, $ces ) ;
							}
						}

						UtilChat_RemoveChatfile( $transcript ) ;
					}
				}

				touch( "$chat_to_dir/TIMESTAMP" ) ; // reset timestamp
			}
		}
		
		return $trans_removed ;
	}

	/*****************************************************************/
	FUNCTION UtilChat_WriteIsWriting( $theces, $theflag, $thewname, $thername )
	{
		if ( ( $theces == "" ) || ( $thewname == "" ) || ( $thername == "" ) )
			return false ;

		global $CHAT_IO_DIR ;
		global $TYPE_IO_DIR ;
		$chat_to_dir = realpath( preg_replace( "/[\$\!`\"<>'\?;]|(\.\.)/", "", $CHAT_IO_DIR ) ) ;
		$type_to_dir = realpath( preg_replace( "/[\$\!`\"<>'\?;]|(\.\.)/", "", $TYPE_IO_DIR ) ) ;

		$typing_file = "$theces.$thewname.txt" ;
		if ( file_exists( "$chat_to_dir/$theces.txt" ) )
		{
			if ( $theflag )
			{
				if ( !file_exists( realpath( "$type_to_dir/$typing_file" ) ) )
					touch( "$type_to_dir/$typing_file" ) ;
			}
			else
			{
				if ( file_exists( "$type_to_dir/$typing_file" ) )
					unlink( "$type_to_dir/$typing_file" ) ;
			}

			return true ;
		}
		else
			return false ;
	}

	/*****************************************************************/
	FUNCTION UtilChat_CheckIsWriting( $theces, $thewname, $thername )
	{
		if ( ( $theces == "" ) || ( $thewname == "" ) || ( $thername == "" ) )
			return 0 ;

		global $TYPE_IO_DIR ;
		$type_to_dir = realpath( preg_replace( "/[\$\!`\"<>'\?;]|(\.\.)/", "", $TYPE_IO_DIR ) ) ;

		$typing_file = "$theces.$thername.txt" ;
		if ( file_exists( realpath( "$type_to_dir/$typing_file" ) ) )
			return 1 ;

		return 0 ;
	}

?>
