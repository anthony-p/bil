<?php
	/************** DO NOT MODIFY */
	include_once( realpath( "$CONF[DOCUMENT_ROOT]/web/vals.php" ) ) ;
	include_once( realpath( "$CONF[DOCUMENT_ROOT]/web/patches/VERSION.php" ) ) ;
	include_once( realpath( "$CONF[DOCUMENT_ROOT]/setup/KEY.php" ) ) ;

	$CHAT_IO_DIR = realpath( "$CONF[DOCUMENT_ROOT]/web/chat_sessions" ) ;
	$TYPE_IO_DIR = realpath( "$CONF[DOCUMENT_ROOT]/web/chat_initiate" ) ;

	if ( file_exists( realpath( "$CONF[DOCUMENT_ROOT]/API/Util_Extra.php" ) ) )
		include_once( realpath( "$CONF[DOCUMENT_ROOT]/API/Util_Extra.php" ) ) ;

	$VARS_RTYPE = Array( 1=>"Ordered", 2=>"Round-robin", 3=>"Frenzy" ) ;
	$VARS_BROWSER = Array( 1=>"IE", 2=>"Firefox", 3=>"Chrome", 4=>"Safari", 5=>"Opera", 6=>"Other" ) ;
	$VARS_OS = Array( 1=>"Windows", 2=>"Mac", 3=>"Unix", 4=>"Other", 5=>"Mobile" ) ;
	/************** DO NOT MODIFY */


	/************** You can modify the below for minor adjustments */
	$VARS_JS_ROUTING = 3 ; // seconds
	$VARS_JS_REQUESTING = 2 ; // seconds (operator.php & p_engine.php) -- used for chatting() interval
	$VARS_JS_FOOTPRINT = 10 ; // seconds
	$VARS_FOOTPRINT_EXPIRE = $VARS_JS_FOOTPRINT * 5 ;
	$VARS_JS_FOOTPRINT_MAX_CYCLE = ( $VARS_JS_FOOTPRINT * 6 ) * 20 ; // 10 * 6 in every minute * 20 minutes

	// take it times 5 at the MIMIMUM or chat will go to leave a message before done routing to all ops
	$OP_ONLINE_TIMEOUT = $VARS_JS_ROUTING * 5 ;

	$VARS_OP_DC = $VARS_JS_REQUESTING * 3 ; // 3 cycle failes should be plenty

	$VARS_CYCLE_VUPDATE = 4 ;

	$VARS_CYCLE_CLEAN = 4 ; // ~3 sec = 1 cycle (longer the cycle, slower the clean - not recommended)
	$VARS_CYCLE_RESET = 3 ; // ~2 sec = 1 cycle
	$VARS_EXPIRED_OPS = 45 ; // 45 seconds is long time to be idle (no connection to server)
	// max routing time times operators (should pickup by 7 ops)
	// todo: incorporate # of ops in future version
	$VARS_EXPIRED_REQS = 60 * 7 ;
	$VARS_EXPIRED_CHATS = $VARS_JS_REQUESTING * 7 ;

	$VARS_TRANSFER_BACK = 45 ; // transfer back to original operator after x seconds
?>
