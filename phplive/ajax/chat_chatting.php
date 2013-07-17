<?php
	include_once( "../web/config.php" ) ;
	include_once( "../API/Util_Format.php" ) ;
	include_once( "../API/Util_Vars.php" ) ;
	include_once( "../API/Chat/Util.php" ) ;
	include_once( "../lang_packs/$CONF[lang].php" ) ;

	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;
	$ces = Util_Format_Sanatize( Util_Format_GetVar( "ces" ), "ln" ) ;
	$isop = Util_Format_Sanatize( Util_Format_GetVar( "isop" ), "ln" ) ;
	$wname = Util_Format_Sanatize( Util_Format_GetVar( "wname" ), "v" ) ;
	$rname = Util_Format_Sanatize( Util_Format_GetVar( "rname" ), "v" ) ;
	$status = Util_Format_Sanatize( Util_Format_GetVar( "status" ), "ln" ) ;
	$c_chatting = Util_Format_Sanatize( Util_Format_GetVar( "c_chatting" ), "ln" ) ;
	$q_ces = Util_Format_Sanatize( Util_Format_GetVar( "q_ces" ), "a" ) ;
	$q_fsizes = Util_Format_Sanatize( Util_Format_GetVar( "q_fsizes" ), "a" ) ;
	$q_flines = Util_Format_Sanatize( Util_Format_GetVar( "q_flines" ), "a" ) ;
	$q_cids = Util_Format_Sanatize( Util_Format_GetVar( "q_cids" ), "a" ) ;

	$istyping = UtilChat_CheckIsWriting( $ces, $wname, $rname ) ;

	$json_data = "json_data = { \"status\": 1, \"ces\": \"$ces\", \"istyping\": $istyping, \"chats\": [  " ;
	for ( $c = 0; $c < count( $q_ces ); ++$c )
	{
		$ces = Util_Format_Sanatize( $q_ces[$c], "ln" ) ;
		$fsize = Util_Format_Sanatize( $q_fsizes[$c], "ln" ) ;
		$fline = Util_Format_Sanatize( $q_flines[$c], "ln" ) ;
		$cid = Util_Format_Sanatize( $q_cids[$c], "ln" ) ;

		$chat_file = realpath( "$CHAT_IO_DIR/$ces.txt" ) ; 
		if ( file_exists( $chat_file ) )
		{
			$file_size = filesize( $chat_file ) ;

			if ( $fsize != $file_size )
			{
				$trans_raw = file( $chat_file ) ;
				$trans = explode( "<>", implode( "", $trans_raw ) ) ;
				$file_lines = count( $trans ) - 1 ;
				$text = preg_replace( "/\"/", "&quot;", implode( "", array_slice( $trans, $fline, $file_lines-$fline ) ) ) ;
				if ( $fline )
					$text = preg_replace( "/<$cid>(.*?)<\/$cid>/", "", $text ) ;

				$json_data .= "{ \"ces\": \"$ces\", \"fsize\": $file_size, \"fline\": $file_lines, \"text\": \"$text\" }," ;
			}

			if ( !$isop && !( $c_chatting % $VARS_CYCLE_VUPDATE ) )
			{
				include_once( "../API/SQL.php" ) ;
				include_once( "../API/Chat/update.php" ) ;

				Chat_update_RequestValueByCes( $dbh, $ces, "vupdated", time() ) ;
			}
		}
		else
		{
			$json_data .= "{ \"ces\": \"$ces\", \"fsize\": 0, \"fline\": 0, \"text\": \"<div class='cl'><disconnected><d5>".LANG_CHAT_NOTIFY_DISCONNECT."</div>\" }," ;
		}
	}
	$json_data = substr_replace( $json_data, "", -1 ) ;
	$json_data .= "	] };" ;
	
	$json_data = preg_replacE( "/\r\n/", "", $json_data ) ;
	$json_data = preg_replacE( "/\t/", "", $json_data ) ;
	print "$json_data" ;
	exit ;
?>
