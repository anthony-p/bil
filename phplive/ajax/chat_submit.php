<?php
	include_once( "../web/config.php" ) ;
	include_once( "../API/Util_Format.php" ) ;

	$requestid = Util_Format_Sanatize( Util_Format_GetVar( "requestid" ), "ln" ) ;
	$wname = Util_Format_Sanatize( Util_Format_GetVar( "wname" ), "v" ) ;
	$rname = Util_Format_Sanatize( Util_Format_GetVar( "rname" ), "v" ) ;
	$ces = Util_Format_Sanatize( Util_Format_GetVar( "ces" ), "ln" ) ;
	$text = preg_replace( "/(p_br)/", "<br>", Util_Format_Sanatize( Util_Format_GetVar( "text" ), "" ) ) ;

	if ( isset( $_COOKIE["phplive_marketID"] ) || isset( $_COOKIE["phplive_opID"] ) )
	{
		include_once( "../API/Chat/Util.php" ) ;

		// override javascript timestamp
		$now = time() ;
		$text = preg_replace( "/<timestamp_(\d+)_((co)|(cv))>/", "<timestamp_".$now."_$2>", $text ) ;
		
		UtilChat_AppendToChatfile( "$ces.txt", $text ) ;
		UtilChat_WriteIsWriting( $ces, 0, $wname, $rname ) ;
		$json_data = "json_data = { \"status\": 1 };" ;
	}
	else
		$json_data = "json_data = { \"status\": -1 };" ;
	
	$json_data = preg_replace( "/\r\n/", "", $json_data ) ;
	$json_data = preg_replace( "/\t/", "", $json_data ) ;
	print "$json_data" ;
	exit ;
?>
