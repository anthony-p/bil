<?php
	include_once( "../web/config.php" ) ;
	include_once( "../API/SQL.php" ) ;
	include_once( "../API/Util_Format.php" ) ;
	include_once( "../API/Util_Vars.php" ) ;
	include_once( "../API/Chat/get.php" ) ;
	include_once( "../API/Footprints/get.php" ) ;

	$image_dir = realpath( "$CONF[DOCUMENT_ROOT]/pics/icons/pixels" ) ; $image_path = "" ;
	$deptid = Util_Format_Sanatize( Util_Format_GetVar( "d" ), "ln" ) ;
	$onpage = rawurldecode( Util_Format_Sanatize( Util_Format_GetVar( "onpage" ), "url" ) ) ;
	$title = rawurldecode( Util_Format_Sanatize( Util_Format_GetVar( "title" ), "title" ) ) ;
	$refer = rawurldecode( Util_Format_Sanatize( Util_Format_GetVar( "r" ), "url" ) ) ;
	$resolution = Util_Format_Sanatize( Util_Format_GetVar( "resolution" ), "ln" ) ;
	$c = Util_Format_Sanatize( Util_Format_GetVar( "c" ), "ln" ) ;

	$thesite = $marketid = $skey = 0 ;
	preg_match( "/plk(=|%3D)(.*)-m/", $onpage, $matches ) ;
	if ( isset( $matches[2] ) )
		LIST( $thesite, $marketid, $skey ) = explode( "-", $matches[2] ) ;

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
	$ip = $_SERVER['REMOTE_ADDR'] ;

	$footprintinfo = Footprints_get_IPFootprints_U( $dbh, $ip ) ;
	// brute bug fix for situations where the web/chat_initiate/$ip.txt is removed
	// another layer of check so the widget is not closed
	$chatinfo = Chat_get_RequestIPInfo( $dbh, $ip, 1 ) ;

	if ( !$c && !isset( $footprintinfo["ip"] ) )
	{
		// first time situation, put in refer and footprint
		include_once( "../API/Footprints/put.php" ) ;
		include_once( "../API/IPs/put.php" ) ;

		if ( $marketid )
			setcookie( "phplive_marketID", $marketid, time()+60*60*24*60 ) ;
		else
		{
			if ( !isset( $_COOKIE["phplive_marketID"] ) )
				setcookie( "phplive_marketID", "", -1 ) ;
		}

		Footprints_put_Print_U( $dbh, $deptid, $os, $browser, $resolution, $ip, $onpage, $title, $marketid, $refer ) ;
		if ( !preg_match( "/$ip/", $VALS["TRAFFIC_EXCLUDE_IPS"] ) )
			Footprints_put_Print( $dbh, $deptid, $os, $browser, $ip, $onpage, $title ) ;
		IPs_put_IP( $dbh, $ip, 1, 0, 0 ) ;

		// if $refer exists always put it into DB on first visit for logging
		if ( $refer )
		{
			if ( !isset( $_COOKIE["phplive_refer"] ) )
				setcookie( "phplive_refer", $refer, time()+60*60*24*180 ) ;
			Footprints_put_Refer( $dbh, $ip, $marketid, $refer ) ;
		}

		$image_path = "$image_dir/1x1.gif" ;
	}
	else if ( !$c && isset( $footprintinfo["ip"] ) )
	{
		// went to new page, first call on that new page only
		include_once( "../API/Footprints/put.php" ) ;
		include_once( "../API/Footprints/update.php" ) ;
		include_once( "../API/IPs/put.php" ) ;

		if ( $refer )
		{
			// for now don't log [mod Sam: 60]
			//setcookie( "phplive_refer", "", -1 ) ;

			// return visit but perhaps new refer to log
			//setcookie( "phplive_refer", $refer, time()+60*60*24*180 ) ;
			//Footprints_put_Refer( $dbh, $ip, $_COOKIE["phplive_marketID"], $refer ) ;
		}

		if ( !preg_match( "/$ip/", $VALS["TRAFFIC_EXCLUDE_IPS"] ) )
			Footprints_put_Print( $dbh, $deptid, $os, $browser, $ip, $onpage, $title ) ;
		IPs_put_IP( $dbh, $ip, 1, 0, 0 ) ;

		// todo: modify to limit mysql error logging
		$nrows = Footprints_update_Footprint_UOnpage( $dbh, $ip, $onpage, $title ) ;
		if ( $nrows == 0 )
		{
			$refer = ( isset( $_COOKIE["phplive_refer"] ) ) ? $_COOKIE["phplive_refer"] : "" ;

			if ( Footprints_put_Print_U( $dbh, $deptid, $os, $browser, $resolution, $ip, $onpage, $title, $marketid, $refer ) )
				$image_path = "$image_dir/1x1.gif" ;
			else
				$image_path = "$image_dir/4x4.gif" ;
		}
		else if ( $nrows )
			$image_path = "$image_dir/1x1.gif" ;
		else
			$image_path = "$image_dir/4x4.gif" ;
	}
	else if ( $c > $VARS_JS_FOOTPRINT_MAX_CYCLE )
	{
		// stop the ajax from calling again because the visitor has left the
		// browser open or been at the page for a very long time... no need to use up resources
		$image_path = "$image_dir/4x4.gif" ;
	}
	else
	{
		// repeat calling, just update footprint unique
		include_once( "../API/Footprints/update.php" ) ;

		Footprints_update_FootprintUniqueValue( $dbh, $ip, "updated", time() ) ;
		$image_path = "$image_dir/1x1.gif" ;
	}

	// override previous images if initiate flag
	if ( file_exists( realpath( "$CONF[DOCUMENT_ROOT]/web/chat_initiate/$ip.txt" ) ) || ( isset( $chatinfo["ip"] ) && !$chatinfo["status"] ) )
		$image_path = "$image_dir/2x2.gif" ;

	if ( isset( $dbh ) && isset( $dbh['con'] ) )
		database_mysql_close( $dbh ) ;

	Header( "Content-type: image/GIF" ) ;
	readfile( $image_path ) ;
?>
