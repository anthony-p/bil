<?php
	include_once( "./web/config.php" ) ;
	include_once( "./API/Util_Format.php" ) ;
	include_once( "./API/Util_Error.php" ) ;
	include_once( "./API/SQL.php" ) ;
	include_once( "./lang_packs/$CONF[lang].php" ) ;
	include_once( "./API/Vars/get.php" ) ;

	$deptid = Util_Format_Sanatize( Util_Format_GetVar( "deptid" ), "ln" ) ;
	$ces = Util_Format_Sanatize( Util_Format_GetVar( "ces" ), "ln" ) ;
	$etrans = Util_Format_Sanatize( Util_Format_GetVar( "etrans" ), "ln" ) ;
	$vname = Util_Format_Sanatize( Util_Format_GetVar( "vname" ), "ln" ) ;
	$vemail = Util_Format_Sanatize( Util_Format_GetVar( "vemail" ), "e" ) ;
	$question = Util_Format_Sanatize( Util_Format_GetVar( "vquestion" ), "htmltags" ) ;
	$onpage = rawurldecode( Util_Format_Sanatize( Util_Format_GetVar( "onpage" ), "url" ) ) ;
	$title = Util_Format_Sanatize( Util_Format_GetVar( "title" ), "title" ) ;
	$resolution = Util_Format_Sanatize( Util_Format_GetVar( "win_dim" ), "ln" ) ;
	$widget = Util_Format_Sanatize( Util_Format_GetVar( "widget" ), "ln" ) ;
	$mobile = Util_Format_isMobile() ;

	$sms = 0 ;

	$agent = $_SERVER["HTTP_USER_AGENT"] ;
	$ip = $_SERVER['REMOTE_ADDR'] ;
	LIST( $os, $browser ) = Util_Format_GetOS( $agent ) ;

	if ( $deptid && $ces && $vname && $vemail && $question )
	{
		include_once( "./API/Depts/get.php" ) ;
		include_once( "./API/Ops/get.php" ) ;
		include_once( "./API/Chat/get.php" ) ;
		include_once( "./API/Chat/put.php" ) ;
		include_once( "./API/Footprints/get_ext.php" ) ;

		$deptinfo = Depts_get_DeptInfo( $dbh, $deptid ) ;
		if ( $deptinfo["rtype"] < 3 )
		{
			if ( $widget )
			{
				$requestinfo = Chat_get_RequestIPInfo( $dbh, $ip, 1 ) ;
				$opinfo_next = Ops_get_OpInfoByID( $dbh, $requestinfo["opID"] ) ;
			}
			else
				$opinfo_next = Ops_get_NextRequestOp( $dbh, $deptid, $deptinfo["rtype"], "" ) ;

			if ( !isset( $opinfo_next["opID"] ) )
			{
				// take them to leave a message
				$url = "phplive_m.php?ces=$ces&deptid=$deptid&vname=$vname&vemail=$vemail&vquestion=".rawurlencode($question)."&title=".rawurlencode($title)."&onpage=".rawurlencode($onpage) ;
				print $url ; exit ;
				HEADER( "location: $url" ) ;
				exit ;
			}
			else
				$opid = isset( $opinfo_next["opID"] ) ? $opinfo_next["opID"] : 0 ;
		}
		else
			$opid = 1111111111 ; // indicates frenzy routing type (goes to all operators) [not implemented yet]

		$question = preg_replace( "/(\r\n)|(\n)|(\r)/", "<br>", preg_replace( "/\"/", "&quot;", $question ) ) ;
		$referinfo_raw = Footprints_get_IPRefer( $dbh, $ip, 1 ) ;
		$referinfo = isset( $referinfo_raw[0] ) ? $referinfo_raw[0] : Array() ;
		$marketid = ( isset( $referinfo["marketID"] ) && $referinfo["marketID"] ) ? $referinfo["marketID"] : 0 ;
		if ( !isset( $_COOKIE["phplive_marketID"] ) )
		{
			if ( $marketid )
				setcookie( "phplive_marketID", $marketid, time()+60*60*24*60 ) ;
			else
				setcookie( "phplive_marketID", $marketid, -1 ) ;
		}

		$refer = ( isset( $referinfo["refer"] ) ) ? $referinfo["refer"] : "" ;
		$vname_orig = $vname ;
		$vname = "<v>$vname" ;
		if ( $requestid = Chat_put_Request( $dbh, $deptid, $opid, 0, $widget, 0, $etrans, $os, $browser, $ces, $resolution, $vname, $vemail, $ip, $agent, $onpage, $title, $question, $marketid, $refer ) )
		{
			include_once( "./API/Ops/put.php" ) ;
			include_once( "./API/Chat/get.php" ) ;
			include_once( "./API/Chat/update.php" ) ;
			include_once( "./API/Chat/Util.php" ) ;
			include_once( "./API/Marketing/get.php" ) ;
			include_once( "./API/IPs/put.php" ) ;
			include_once( "./API/Footprints/update.php" ) ;

			Footprints_update_FootprintUniqueValue( $dbh, $ip, "chatting", 1 ) ;
			IPs_put_IP( $dbh, $ip, 0, 1, 0 ) ;
			Chat_put_ReqLog( $dbh, $requestid ) ;

			if ( $widget )
			{
				if ( file_exists( realpath( "$CONF[DOCUMENT_ROOT]/web/chat_initiate/$ip.txt" ) ) )
					unlink( "$CONF[DOCUMENT_ROOT]/web/chat_initiate/$ip.txt" ) ;

				Ops_put_OpReqStat( $dbh, $deptid, $opid, "initiated_taken", 1 ) ;
				UtilChat_AppendToChatfile( "$ces.txt", "<div class='ca'><b>$vname</b> ".LANG_CHAT_NOTIFY_JOINED."</div>" ) ;
			}
			else
			{
				setcookie( "phplive_vname", $vname_orig, time()+60*60*24*365 ) ;
				setcookie( "phplive_vemail", $vemail, time()+60*60*24*365 ) ;

				Ops_put_OpReqStat( $dbh, $deptid, $opid, "requests", 1 ) ;
				UtilChat_AppendToChatfile( "$ces.txt", "<div class='ca'><i>$question</i></div>" ) ;
			}

			$marquees = Marketing_get_DeptMarquees( $dbh, $deptid ) ;
			$marquee_string = "" ;
			for ( $c = 0; $c < count( $marquees ); ++$c )
			{
				$marquee = $marquees[$c] ;
				$snapshot = preg_replace( "/'/", "&#39;", preg_replace( "/\"/", "&quot;", $marquee["snapshot"] ) ) ;
				$message = preg_replace( "/'/", "&#39;", preg_replace( "/\"/", "", $marquee["message"] ) ) ;

				$marquee_string .= "marquees[$c] = '$snapshot' ; marquees_messages[$c] = '$message' ; " ;
			}
			if ( !count( $marquees ) )
				$marquee_string = "marquees[0] = '' ;" ;

			$stars_five = Util_Format_Stars( 5 ) ;
			$stars_four = Util_Format_Stars( 4 ) ;
			$stars_three = Util_Format_Stars( 3 ) ;
			$stars_two = Util_Format_Stars( 2 ) ;
			$stars_one = Util_Format_Stars( 1 ) ;

			$survey = "<div class='cl'><div class='ctitle'>".LANG_CHAT_NOTIFY_RATE."</div>
				<table cellspacing=0 cellpadding=0 border=0 style='padding-top: 10px; padding-bottom: 10px;'>
					<tr><td><input type='radio' name='rating' value=5 onClick='submit_survey(this, survey_texts)'></td><td style='padding-left: 2px;'>$stars_five</td>
					<td style='padding-left: 25px;'><input type='radio' name='rating' value=4 onClick='submit_survey(this, survey_texts)'></td><td style='padding-left: 5px;'>$stars_four</td>
					<td style='padding-left: 25px;'><input type='radio' name='rating' value=3 onClick='submit_survey(this, survey_texts)'></td><td style='padding-left: 2px;'>$stars_three</td>
					<td style='padding-left: 25px;'><input type='radio' name='rating' value=2 onClick='submit_survey(this, survey_texts)'></td><td style='padding-left: 2px;'>$stars_two</td>
					<td style='padding-left: 25px;'><input type='radio' name='rating' value=1 onClick='submit_survey(this, survey_texts)'></td><td style='padding-left: 2px;'>$stars_one</td></tr>
				</table>
			</div>" ;
			$survey = preg_replace( "/(\r\n)|(\n)|(\r)/", "", $survey ) ;

			$vars = Vars_get_Vars( $dbh ) ;
			$sm_fb_array = $sm_tw_array = $sm_yt_array = $sm_li_array = Array() ;
			if ( isset( $vars["sm_fb"] ) )
			{
				$sm_fb_array = unserialize( $vars["sm_fb"] ) ;
				$sm_tw_array = unserialize( $vars["sm_tw"] ) ;
				$sm_yt_array = unserialize( $vars["sm_yt"] ) ;
				$sm_li_array = unserialize( $vars["sm_li"] ) ;

				if ( isset( $sm_fb_array["status"] ) || isset( $sm_fb_array["status"] ) || isset( $sm_fb_array["status"] ) || isset( $sm_fb_array["status"] ) )
					$sms = 1 ;
			}
		}
		else { ErrorHandler ( 603, "Chat session did not create.  $dbh[query]<br>$dbh[error].", "$_SERVER[HTTP_HOST]/$_SERVER[REQUEST_URI]", 0, Array() ) ; }
	}
	else
	{
		$onpage = rawurlencode( $onpage ) ;
		HEADER( "location: phplive.php?d=$deptid&onpage=$onpage" ) ;
		exit ;
	}
?>
<?php include_once( "./inc_doctype.php" ) ?>
<head>
<title> PHP Live! <?php echo $VERSION ?> </title>

<meta name="description" content="PHP Live! <?php echo $VERSION ?>">
<meta name="keywords" content="PHP Live! Support">
<meta name="robots" content="all,index,follow">
<meta name="phplive" content="version: <?php echo $VERSION ?>">
<meta http-equiv="content-type" content="text/html; CHARSET=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<link rel="Stylesheet" href="./themes/<?php echo $CONF["THEME"] ?>/style.css">
<script type="text/javascript" src="./js/global.js"></script>
<script type="text/javascript" src="./js/global_chat.js"></script>
<script type="text/javascript" src="./js/framework.js"></script>
<script type="text/javascript" src="./js/tooltip.js"></script>
<script type="text/javascript" src="./js/jquery.tools.min.js"></script>

<script type="text/javascript">
<!--
	var mouse_x ; var mouse_y ;
	var base_url = "." ; var base_url_full = "<?php echo $CONF["BASE_URL"] ?>" ;
	var isop = 0 ;
	var cname = "<?php echo $vname ?>" ; var cemail = "<?php echo $vemail ?>" ;
	var ces = "<?php echo $ces ?>" ;
	var st_typing ;
	var si_timer, si_title, si_typing ;
	var deptid = <?php echo $deptinfo["deptID"] ?> ;
	var rtype = <?php echo $deptinfo["rtype"] ?> ;
	var rtime = <?php echo $deptinfo["rtime"] ?> ;
	var cid = "cid_"+unixtime() ;
	var chat_sound = 1 ;
	var title_orig = document.title ;
	var si_counter = 0 ;
	var focused = 1 ;
	var widget = 0 ;
	var wp = 0 ;
	var mobile = <?php echo $mobile ?> ;

	var marquees = marquees_messages = new Array() ;
	var marquee_index = 0 ;

	var loaded = 0 ;
	var newwin_print ;
	var survey_texts = new Array("<?php echo LANG_CHAT_SURVEY_THANK ?>", "<?php echo LANG_CHAT_CLOSE ?>") ;
	var survey = "<?php echo $survey ?>" ;

	var chats = new Object ;
	chats[ces] = new Object ;
	chats[ces]["requestid"] = <?php echo $requestid ?> ;
	chats[ces]["cid"] = cid ;
	chats[ces]["vname"] = cname ;
	chats[ces]["trans"] = "<xo><div class=\"ca\"><div class=\"info_box\"><i><?php echo $question ?></i></div><div style=\"margin-top: 10px;\"><?php echo $deptinfo["msg_greet"] ?><div style=\"margin-top: 10px;\"><img src=\"themes/<?php echo $CONF["THEME"] ?>/loading_bar.gif\" border=\"0\" alt=\"\"></div></div></div></xo>".vars() ;
	chats[ces]["status"] = 0 ;
	chats[ces]["disconnected"] = 0 ;
	chats[ces]["op2op"] = 0 ;
	chats[ces]["deptid"] = 0 ;
	chats[ces]["opid"] = 0 ;
	chats[ces]["opid_orig"] = 0 ;
	chats[ces]["oname"] = "" ;
	chats[ces]["ip"] = "<?php echo $ip ?>" ;
	chats[ces]["fsize"] = 0 ;
	chats[ces]["fline"] = 0 ;
	chats[ces]["vsurvey"] = <?php echo $opinfo_next["rate"] ?> ;
	chats[ces]["survey"] = 0 ;
	chats[ces]["timer"] = unixtime() ;
	chats[ces]["istyping"] = 0 ;

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		}) ;

		loaded = 1 ;
		init_divs(0) ;
		init_disconnect() ;
		$('#chat_body').html( chats[ces]["trans"] ) ;
		init_scrolling() ;
		init_marquees( "<?php echo $marquee_string ?>" ) ;
		init_typing() ;

		init_tooltips() ;
	});
	$(window).resize(function() { init_divs(1) ; });
	window.onbeforeunload = function() { disconnect_unload() ; }
	$(window).focus(function() {
		input_focus() ;
	});
	$(window).blur(function() {
		focused = 0 ;
	});

	function disconnect_unload()
	{
		alert( "<?php echo LANG_CHAT_JS_CHAT_EXIT ?>" ) ;
		if ( ( typeof( ces ) != "undefined" ) && ( typeof( chats[ces] ) != "undefined" ) )
		{
			var unique = unixtime() ;

			$.get("./ajax/chat_actions.php", { action: "disconnect", requestid: chats[ces]["requestid"], ces: ces, status: chats[ces]["status"], opid: chats[ces]["opid_orig"], fsize: chats[ces]["fsize"], unique: unique },  function(data){
				eval( data ) ;

				cleanup_disconnect( "" ) ;
			});
		}
	}

	function init_connect( thejson_data )
	{
		if ( thejson_data.initiated )
			setTimeout( function(){ init_connect_doit( thejson_data ) ; }, 3500 ) ;
		else
			init_connect_doit( thejson_data ) ;
	}

	function init_connect_doit( thejson_data )
	{
		chats[ces]["status"] = thejson_data.status_request ;
		chats[ces]["oname"] = thejson_data.name ;
		chats[ces]["opid"] = thejson_data.opid ;
		chats[ces]["deptid"] = thejson_data.deptid ;
		chats[ces]["opid_orig"] = thejson_data.opid ;
		chats[ces]["timer"] = unixtime() ;

		// strip out the connecting div
		chats[ces]["trans"] = chats[ces]["trans"].replace( /<xo>(.*)<\/xo>/, "" ) ;

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
	}

	function cleanup_disconnect( theces )
	{
		if ( !chats[theces]["disconnected"] )
		{
			chats[theces]["disconnected"] = unixtime() ;
			var text = "<div class='cl'><?php echo LANG_CHAT_NOTIFY_VDISCONNECT ?></div>" ;
			if ( !chats[theces]["status"] )
			{
				// clear it out so the loading image is not shown
				$('#chat_body').html( "" ) ;
				chats[theces]["trans"] = "" ;
			}

			add_text( text ) ;
			init_textarea() ;
			$('#iframe_chat_engine').attr( "contentWindow" ).stopit(0) ;

			window.onbeforeunload = null ;
			if ( chats[theces]["status"] || ( chats[theces]["status"] == 2 ) )
			{
				chat_survey() ;
				$('#info_disconnect').hide() ;
			}
			else
				window.close() ;
		}
	}

	function leave_a_mesg()
	{
		var vsubject = encodeURIComponent( "<?php echo LANG_CHAT_JS_LEAVE_MSG ?>" ) ;

		window.onbeforeunload = null ;
		location.href = "../phplive_m.php?ces=<?php echo $ces ?>&deptid=<?php echo $deptid ?>&vname=<?php echo preg_replace( "/<v>/", "", $vname ) ; ?>&vemail=<?php echo $vemail?>&vsubject="+vsubject+"&vquestion=<?php echo rawurlencode($question) ?>&onpage=<?php echo rawurlencode($onpage) ?>" ;
	}

	function init_tooltips()
	{
		var help_tooltips = $( '#options_sm' ).find( '.help_tooltip' ) ;
		help_tooltips.tooltip({
			event: "mouseover",
			track: true,
			delay: 0,
			showURL: false,
			showBody: "- ",
			fade: 0,
			positionLeft: false
		});
	}
//-->
</script>
</head>
<body>

<div id="chat_canvas" style="min-height: 100%; width: 100%;"></div>
<div style="position: absolute; top: 2px; padding: 10px; z-Index: 2;">
	<div id="chat_body" style="overflow: auto;"></div>
	<div id="chat_options" style="padding-top: 10px;">
		<div style="height: 16px;">
			<div id="options_sm" style="float: left; <?php echo ( $sms ) ? "padding-right: 20px;" : "" ?>">
				<?php if ( isset( $sm_fb_array["status"] ) && $sm_fb_array["status"] ): ?><a href="<?php echo $sm_fb_array["url"] ?>" target="_blank" class="help_tooltip" title="- <?php echo $sm_fb_array["tooltip"] ?>"><img src="themes/<?php echo $CONF["THEME"] ?>/social/facebook.png" width="16" height="16" border="0" alt=""></a> &nbsp;<?php endif ; ?>
				<?php if ( isset( $sm_tw_array["status"] ) && $sm_tw_array["status"] ): ?><a href="<?php echo $sm_tw_array["url"] ?>" target="_blank" class="help_tooltip" title="- <?php echo $sm_tw_array["tooltip"] ?>"><img src="themes/<?php echo $CONF["THEME"] ?>/social/twitter.png" width="16" height="16" border="0" alt=""></a> &nbsp;<?php endif ; ?>
				<?php if ( isset( $sm_yt_array["status"] ) && $sm_yt_array["status"] ): ?><a href="<?php echo $sm_yt_array["url"] ?>" target="_blank" class="help_tooltip" title="- <?php echo $sm_yt_array["tooltip"] ?>"><img src="themes/<?php echo $CONF["THEME"] ?>/social/youtube.png" width="16" height="16" border="0" alt=""></a> &nbsp;<?php endif ; ?>
				<?php if ( isset( $sm_li_array["status"] ) && $sm_li_array["status"] ): ?><a href="<?php echo $sm_li_array["url"] ?>" target="_blank" class="help_tooltip" title="- <?php echo $sm_li_array["tooltip"] ?>"><img src="themes/<?php echo $CONF["THEME"] ?>/social/linkedin.png" width="16" height="16" border="0" alt=""></a> &nbsp;<?php endif ; ?>
			</div>
			<div id="options_print" style="display: none; float: left;">
				<span><img src="./themes/<?php echo $CONF["THEME"] ?>/printer.png" width="16" height="16" border="0" alt="" style="cursor: pointer;" onClick="do_print(ces)"></span>
				<span style="padding-left: 10px;"><img src="./themes/<?php echo $CONF["THEME"] ?>/sound_on.png" width="16" height="16" border="0" alt="" style="cursor: pointer;" onClick="toggle_chat_sound('<?php echo $CONF["THEME"] ?>')" id="chat_sound"></span>
				<span id="chat_vtimer" style="position: relative; top: -2px; padding-left: 15px;"></span>
				<span id="chat_vname" style="position: relative; top: -2px; padding-left: 15px;"></span>
				<span id="chat_vistyping" style="position: relative; top: -2px;"></span>
			</div>
			<div style="clear: both;"></div>
		</div>
	</div>
	<div id="chat_input" style="margin-top: 8px;">
		<textarea id="input_text" rows="3" style="padding: 2px; height: 75px; resize: none;" wrap="virtual" onKeyup="input_text_listen(event);" onKeydown="input_text_typing(event);" readonly><?php echo LANG_TXT_CONNECTING ?></textarea>
	</div>
</div>

<div id="chat_btn" style="position: absolute; z-Index: 10;"><button id="input_btn" type="button" class="input_button" style="width: 104px; height: 45px; padding: 6px; font-size: 14px; font-weight: bold;" OnClick="add_text_prepare()" disabled><?php echo LANG_TXT_SUBMIT ?></button></div>
<div id="chat_text_powered" style="position: absolute; margin-top: 5px; font-size: 10px; z-Index: 10;">
	PHP Live! powered
	<div id="sounds" style="width: 1px; height: 1px; overflow: hidden; opacity:0.0; filter:alpha(opacity=0);">
		<span id="div_sounds_new_text"></span>
	</div>
</div>

<iframe id="iframe_chat_engine" name="iframe_chat_engine" style="position: absolute; width: 100%; border: 0px; bottom: -50px; height: 20px;" src="ops/p_engine.php?ces=<?php echo $ces ?>" scrolling="no"></iframe>

<div id="info_disconnect" class="info_disconnect" style="position: absolute; top: 0px; right: 0px; z-Index: 101;" onClick="disconnect()"><img src="./themes/<?php echo $CONF["THEME"] ?>/close_extra.png" width="14" height="14" border="0" alt=""> <?php echo LANG_TXT_DISCONNECT ?></div>

<?php if ( !$mobile ): ?>
<div id="chat_footer" style="position: relative; width: 100%; margin-top: -28px; height: 28px; padding-top: 7px; padding-left: 15px; z-Index: 10;"></div>
<?php endif ; ?>

</body>
</html>
<?php database_mysql_close( $dbh ) ; ?>
