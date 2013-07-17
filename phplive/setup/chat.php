<?php
	/****************************************/
	// STANDARD header for Setup
	if ( !file_exists( "../web/config.php" ) ){ HEADER("location: install.php") ; exit ; }
	include_once( "../web/config.php" ) ;
	include_once( "../API/Util_Format.php" ) ;
	include_once( "../API/Util_Error.php" ) ;
	include_once( "../API/SQL.php" ) ;
	include_once( "../API/Util_Security.php" ) ;
	$ses = Util_Format_Sanatize( Util_Format_GetVar( "ses" ), "ln" ) ;
	if ( !$admininfo = Util_Security_AuthSetup( $dbh, $ses ) ){ ErrorHandler ( 602, "Invalid setup session or session has expired.", "$_SERVER[HTTP_HOST]/$_SERVER[REQUEST_URI]", 0, Array() ) ; }
	// STANDARD header end
	/****************************************/

	include_once( "../API/Chat/get.php" ) ;

	$ces = Util_Format_Sanatize( Util_Format_GetVar( "ces" ), "ln" ) ;

	$requestinfo = Chat_get_RequestCesInfo( $dbh, $ces ) ;
	if ( !isset( $requestinfo["requestID"] ) )
		ErrorHandler ( 605, "Chat request not found.  Invalid chat session ID.", "$_SERVER[HTTP_HOST]/$_SERVER[REQUEST_URI]", 0, Array() ) ;
?>
<?php include_once( "../inc_doctype.php" ) ?>
<head>
<title> PHP Live! <?php echo $VERSION ?> </title>

<meta name="description" content="PHP Live! <?php echo $VERSION ?>">
<meta name="keywords" content="PHP Live! Support">
<meta name="robots" content="all,index,follow">
<meta name="phplive" content="version: <?php echo $VERSION ?>">
<meta http-equiv="content-type" content="text/html; CHARSET=utf-8">

<link rel="Stylesheet" href="../themes/<?php echo $CONF["THEME"] ?>/style.css">
<script type="text/javascript" src="../js/global.js"></script>
<script type="text/javascript" src="../js/global_chat.js"></script>
<script type="text/javascript" src="../js/framework.js"></script>
<script type="text/javascript" src="../js/jquery.tools.min.js"></script>

<script type="text/javascript">
<!--
	var mouse_x ; var mouse_y ;
	var base_url = "." ;
	var chats = new Object ;
	var ces = "<?php echo $ces ?>" ;
	chats[ces] = new Object ;
	chats[ces]["requestid"] = <?php echo $requestinfo["requestID"] ; ?> ;
	chats[ces]["cid"] = 0 ;
	chats[ces]["vname"] = "" ;
	chats[ces]["trans"] = "dd" ;
	chats[ces]["status"] = 0 ;
	chats[ces]["disconnected"] = 0 ;
	chats[ces]["op2op"] = 0 ;
	chats[ces]["deptid"] = 0 ;
	chats[ces]["opid"] = 0 ;
	chats[ces]["opid_orig"] = 0 ;
	chats[ces]["oname"] = "" ;
	chats[ces]["fsize"] = 0 ;
	chats[ces]["fline"] = 0 ;
	chats[ces]["vsurvey"] = 0 ;
	chats[ces]["survey"] = 0 ;
	chats[ces]["timer"] = unixtime() ;
	chats[ces]["istyping"] = 0 ;

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;

			init_divs(0) ;
		}) ;

		//$('#chat_body').html( chats[ces]["trans"] ) ;
	});
	$(window).resize(function() { init_divs(1) });
//-->
</script>
</head>
<body>

<div id="chat_canvas" style="min-height: 100%; width: 100%;"></div>
<div style="position: absolute; top: 2px; padding: 10px; z-Index: 2;">
	<div id="chat_body" style="overflow: auto;">dd</div>
	<div id="chat_options" style="padding-top: 10px;">
		<div style="height: 16px;">
			<div id="options_print" style="display: none; float: left;"><img src="../themes/<?php echo $CONF["THEME"] ?>/printer.png" width="16" height="16" border="0" alt="" style="cursor: pointer;" onClick="do_print(ces)"> <span id="chat_vtimer" style="position: relative; top: -2px; padding-left: 15px;"></span> <span id="chat_vname" style="position: relative; top: -2px; padding-left: 15px;"></span> <span id="chat_vistyping" style="position: relative; top: -2px;"></span></div>
			<div style="clear: both;"></div>
		</div>
	</div>
</div>

<!-- <iframe id="iframe_chat_engine" name="iframe_chat_engine" style="position: absolute; width: 100%; border: 0px; bottom: -50px; height: 20px;" src="ops/p_engine.php?ces=<?php echo $ces ?>" scrolling="no"></iframe> -->

</body>
</html>
<?php database_mysql_close( $dbh ) ; ?>
