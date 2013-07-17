<?php
	$CONF = unserialize( html_entity_decode( "a:12:{s:13:&quot;DOCUMENT_ROOT&quot;;s:33:&quot;/home/bringit/public_html/phplive&quot;;s:8:&quot;BASE_URL&quot;;s:35:&quot;http://www.bringitlocal.com/phplive&quot;;s:7:&quot;SQLHOST&quot;;s:9:&quot;localhost&quot;;s:8:&quot;SQLLOGIN&quot;;s:16:&quot;bringit_chatuser&quot;;s:7:&quot;SQLPASS&quot;;s:12:&quot;4v}Kg*{Hwq+B&quot;;s:8:&quot;DATABASE&quot;;s:19:&quot;bringit_livesupport&quot;;s:5:&quot;THEME&quot;;s:7:&quot;default&quot;;s:8:&quot;TIMEZONE&quot;;s:19:&quot;America/Los_Angeles&quot;;s:11:&quot;icon_online&quot;;s:17:&quot;icon_online_0.GIF&quot;;s:12:&quot;icon_offline&quot;;s:18:&quot;icon_offline_0.GIF&quot;;s:4:&quot;lang&quot;;s:7:&quot;english&quot;;s:4:&quot;logo&quot;;s:10:&quot;logo_0.GIF&quot;;}", ENT_QUOTES, "UTF-8" ) ) ;
	if ( phpversion() >= "5.1.0" ){ date_default_timezone_set( $CONF["TIMEZONE"] ) ; }
	include_once( realpath( "$CONF[DOCUMENT_ROOT]/API/Util_Vars.php" ) ) ;
?>
