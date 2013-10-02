<?php
	include_once( "./web/config.php" ) ;
	include_once( "./API/Util_Format.php" ) ;
	include_once( "./API/Util_Error.php" ) ;
	include_once( "./API/Util_Upload.php" ) ;
	include_once( "./API/SQL.php" ) ;
	include_once( "./lang_packs/$CONF[lang].php" ) ;
	include_once( "./API/Ops/get.php" ) ;
	include_once( "./API/Chat/get.php" ) ;
	include_once( "./API/Chat/update.php" ) ;
	include_once( "./API/Footprints/update.php" ) ;

	$ip = $_SERVER['REMOTE_ADDR'] ;
	$btn = Util_Format_Sanatize( Util_Format_GetVar( "btn" ), "ln" ) ;
	$mobile = Util_Format_isMobile() ;

	// todo: error capture on rare occassions of no request info [mod Jake: 82]
	$requestinfo = Chat_get_RequestIPInfo( $dbh, $ip, 1 ) ;
	if ( !isset( $requestinfo["ces"] ) )
	{
		HEADER( "location: blank.php" ) ;
		exit ;
	}

	$ces = $requestinfo["ces"] ;
	$opinfo = Ops_get_OpInfoByID( $dbh, $requestinfo["opID"] ) ;

	// set log to indicate not picked up
	Chat_update_RequestLogValue( $dbh, $ces, "status", 0 ) ;
	Footprints_update_FootprintUniqueValue( $dbh, $ip, "chatting", 1 ) ;

	$stars_five = Util_Format_Stars( 5 ) ;
	$stars_four = Util_Format_Stars( 4 ) ;
	$stars_three = Util_Format_Stars( 3 ) ;
	$stars_two = Util_Format_Stars( 2 ) ;
	$stars_one = Util_Format_Stars( 1 ) ;

	$survey = "<div class='cl'><div class='ctitle'>How would you rate this support session?<div>
		<table cellspacing=0 cellpadding=0 border=0 style='padding-top: 10px; padding-bottom: 10px;'>
			<tr><td><input type='radio' name='rating' value=5 onClick='submit_survey(this, survey_texts)'></td><td style='padding-left: 2px;'>$stars_five</td>
			<td style='padding-left: 25px;'><input type='radio' name='rating' value=4 onClick='submit_survey(this, survey_texts)'></td><td style='padding-left: 5px;'>$stars_four</td>
			<td style='padding-left: 25px;'><input type='radio' name='rating' value=3 onClick='submit_survey(this, survey_texts)'></td><td style='padding-left: 2px;'>$stars_three</td>
			<td style='padding-left: 25px;'><input type='radio' name='rating' value=2 onClick='submit_survey(this, survey_texts)'></td><td style='padding-left: 2px;'>$stars_two</td>
			<td style='padding-left: 25px;'><input type='radio' name='rating' value=1 onClick='submit_survey(this, survey_texts)'></td><td style='padding-left: 2px;'>$stars_one</td></tr>
		</table>
	</div>" ;
	$survey = preg_replace( "/(\r\n)|(\n)|(\r)/", "", $survey ) ;
?>
<?php include_once( "./inc_doctype.php" ) ?>
<head>
<title> PHP Live! Support <?php echo $VERSION ?> </title>

<meta name="description" content="PHP Live! Support <?php echo $VERSION ?>">
<meta name="keywords" content="PHP Live! Support">
<meta name="robots" content="all,index,follow">
<meta name="phplive" content="version: <?php echo $VERSION ?>">
<meta http-equiv="content-type" content="text/html; CHARSET=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<link rel="Stylesheet" href="./themes/initiate/style.css">
<script type="text/javascript" src="./js/global.js"></script>
<script type="text/javascript" src="./js/global_chat.js"></script>
<script type="text/javascript" src="./js/framework.js"></script>
<script type="text/javascript" src="./js/jquery.tools.min.js"></script>

<script type="text/javascript">
<!--
	var mouse_x ; var mouse_y ;
	var base_url = "." ; var base_url_full = "<?php echo $CONF["BASE_URL"] ?>" ;
	var isop = 0 ; var cname = "<?php echo $requestinfo["vname"] ?>" ;
	var ces = "<?php echo $requestinfo["ces"] ?>" ;
	var st_typing ;
	var cid = "cid_"+unixtime() ;
	var chat_sound = 1 ;
	var title_orig = document.title ;
	var loaded = 0 ;
	var focused = 1 ;
	var survey_texts = new Array("<?php echo LANG_CHAT_SURVEY_THANK ?>", "<?php echo LANG_CHAT_CLOSE ?>") ;
	var survey = "<?php echo $survey ?>" ;
	var widget = 1 ; // used as logout flag and in p_engine.php to omit routing
	var wp = 0 ;
	var mobile = <?php echo $mobile ?> ;
	
	var clicks = 0 ; // counter to track click to close the widget

	var chats = new Object ;
	chats[ces] = new Object ;

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		}) ;

		loaded = 1 ;

		init_divs(0) ;
		init_connect() ;
		init_disconnect() ;
		init_typing() ;

		//$('#iframe_widget_<?php echo $btn ?>', parent.document).css({'width': '500px'}) ;
	});
	$(window).resize(function() { init_divs(1) ; });

	function init_connect()
	{
		chats[ces] = new Object ;
		chats[ces]["requestid"] = <?php echo $requestinfo["requestID"] ?> ;
		chats[ces]["cid"] = cid ;
		chats[ces]["vname"] = cname ;
		chats[ces]["status"] = 1 ;
		chats[ces]["disconnected"] = 0 ;
		chats[ces]["op2op"] = 0 ;
		chats[ces]["opid"] = <?php echo $requestinfo["opID"] ?> ;
		chats[ces]["opid_orig"] = chats[ces]["opid"] ;
		chats[ces]["oname"] = "<?php echo $opinfo["name"] ?>" ;
		chats[ces]["ip"] = "<?php echo $requestinfo["ip"] ?>" ;
		chats[ces]["fsize"] = 0 ;
		chats[ces]["fline"] = 0 ;
		chats[ces]["survey"] = 0 ;
		chats[ces]["timer"] = unixtime() ;
		chats[ces]["trans"] = "<div style=\"width: 100%; height: 45px; margin-top: 5px; margin-bottom: 5px; background: url( <?php echo Util_Upload_GetLogo( '.', $requestinfo['deptID'] ) ?> ) no-repeat;\"></div>" ;

		$('#chat_body').html( chats[ces]["trans"] ) ;
		$('#chat_vname').html( chats[ces]["oname"] ) ;
		$('textarea#input_text').val( "" ) ;
		init_scrolling() ;
		init_textarea() ;

		$('#options_print').show() ;
		init_timer() ;
	}

	function init_chats()
	{
		if ( chats[ces]["disconnected"] )
			cleanup_disconnect( ces ) ;
	}

	function cleanup_disconnect( theces )
	{
		if ( !chats[ces]["disconnected"] || clicks )
			close_initiate_window() ;
		else
			clicks += 1 ;
	}

	function leave_a_mesg(){}
//-->
</script>
</head>
<body>

<div id="chat_canvas" style="min-height: 100%; width: 100%;"></div>
<div style="position: absolute; top: 2px; padding: 10px; z-Index: 2;">
	<div id="chat_body" style="overflow: auto;"></div>
	<div id="chat_options" style="padding-top: 10px;">
		<div style="height: 16px;">
			<div id="options_print" style="display: none; float: left;"><img src="./pics/space.gif" width="16" height="16" border="0" alt="" style="cursor: pointer;" onClick="do_print(ces)"> <!-- <span style="padding-left: 10px;"><img src="./themes/<?php echo $CONF["THEME"] ?>/sound_on.png" width="16" height="16" border="0" alt="" style="cursor: pointer;" onClick="toggle_chat_sound('<?php echo $CONF["THEME"] ?>')" id="chat_sound"></span> <span id="chat_vtimer" style="position: relative; top: -2px; padding-left: 15px;"></span> <span id="chat_vname" style="position: relative; top: -2px; padding-left: 15px;"></span> <span id="chat_vistyping" style="position: relative; top: -2px;"></span> --></div>
			<div style="clear: both;"></div>
		</div>
	</div>
	<div id="chat_input" style="margin-top: 8px;">
		<table cellspacing=0 cellpadding=0 border=0 width="100%">
		<tr><td align="right" width="100%"><button id="input_btn" type="button" style="font-weight: bold; width: 140px; height: 50px;" onClick="expand_chat()"><?php echo LANG_CHAT_BTN_START_CHAT ?></button></div></tr>
		</table>
	</div>
</div>
<span id="div_sounds_new_text"></span>

<iframe id="iframe_chat_engine" name="iframe_chat_engine" style="position: absolute; width: 100%; border: 0px; bottom: -50px; height: 20px;" src="ops/p_engine.php?ces=<?php echo $ces ?>" scrolling="no"></iframe>

<div id="info_disconnect" class="info_disconnect" style="position: absolute; top: 0px; right: 0px; z-Index: 101;" onClick="disconnect()"><img src="./themes/<?php echo $CONF["THEME"] ?>/close_extra.png" width="14" height="14" border="0" alt=""> <?php echo LANG_TXT_DISCONNECT ?></div>

</body>
</html>
<?php database_mysql_close( $dbh ) ; ?>
