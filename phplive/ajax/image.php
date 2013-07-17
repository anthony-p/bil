<?php
	include_once( "../web/config.php" ) ;
	include_once( "../API/Util_Format.php" ) ;
	include_once( "../API/SQL.php" ) ;
	include_once( "../API/Util_Vals.php" ) ;
	include_once( "../API/Ops/get.php" ) ;
	include_once( "../API/Ops/update.php" ) ;

	$image_dir = realpath( "$CONF[DOCUMENT_ROOT]/web" ) ; $image_path = $image_type = "" ;
	$deptid = Util_Format_Sanatize( Util_Format_GetVar( "d" ), "ln" ) ;
	$onpage = Util_Format_Sanatize( Util_Format_GetVar( "p" ), "" ) ;

	$ip = $_SERVER['REMOTE_ADDR'] ;

	preg_match( "/plk(=|%3D)(.*)-m/", $onpage, $matches ) ;
	if ( isset( $matches[2] ) )
	{
		include_once( "../API/Marketing/get.php" ) ;
		include_once( "../API/Marketing/update.php" ) ;
		include_once( "../API/Marketing/put.php" ) ;

		LIST( $thesite, $marketid, $skey ) = explode( "-", $matches[2] ) ;
		$marketinfo = Marketing_get_MarketingByID( $dbh, $marketid ) ;

		if ( $marketinfo["skey"] == $skey )
		{
			$clickinfo = Marketing_get_ClickInfo( $dbh, $marketid ) ;
			if ( isset( $clickinfo["marketID"] ) )
				Marketing_update_MarketClickValue( $dbh, $marketid, "clicks", $clickinfo["clicks"]+1 ) ;
			else
				Marketing_put_Click( $dbh, $marketid, 1 ) ;
		}
	}
	else
		$marketid = 0 ;

	Ops_update_IdleOps( $dbh, 0, 0 ) ;
	if ( preg_match( "/$ip/", $VALS["CHAT_SPAM_IPS"] ) )
		$total_ops = 0 ;
	else
		$total_ops = Ops_get_AnyOpsOnline( $dbh, $deptid ) ;

	if ( $total_ops )
		$prefix = "icon_online" ;
	else
		$prefix = "icon_offline" ;
	
	if ( file_exists( realpath( "$image_dir/$prefix"."_$deptid.GIF" ) ) )
	{
		$image_type = "GIF" ;
		$image_path = "$image_dir/$prefix"."_$deptid.GIF" ;
	}
	else if ( file_exists( realpath( "$image_dir/$prefix"."_$deptid.JPEG" ) ) )
	{
		$image_type = "JPEG" ;
		$image_path = "$image_dir/$prefix"."_$deptid.JPEG";
	}
	else if ( file_exists( realpath( "$image_dir/$prefix"."_$deptid.PNG" ) ) )
	{
		$image_type = "PNG" ;
		$image_path = "$image_dir/$prefix"."_$deptid.PNG" ;
	}
	else if ( file_exists( realpath( "$image_dir/$CONF[$prefix]" ) ) && $CONF[$prefix] )
	{
		$image_type = "NULL" ;
		$image_path = "$image_dir/$CONF[$prefix]" ;
	}
	else
	{
		$image_type = "GIF" ;
		$image_path = realpath( "$CONF[DOCUMENT_ROOT]/pics/icons/$prefix.gif" ) ;
	}

	if ( isset( $dbh ) && isset( $dbh['con'] ) )
		database_mysql_close( $dbh ) ;

	Header( "Content-type: image/$image_type" ) ;
	readfile( $image_path ) ;
?>
