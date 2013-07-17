<?php
	include_once( "./web/config.php" ) ;
	include_once( "./API/Util_Format.php" ) ;
	include_once( "./API/Util_Error.php" ) ;
	include_once( "./API/Util_Email.php" ) ;
	include_once( "./API/SQL.php" ) ;
	include_once( "./API/Util_Upload.php" ) ;
	include_once( "./API/Depts/get.php" ) ;
	include_once( "./API/Marketing/get.php" ) ;
	include_once( "./lang_packs/$CONF[lang].php" ) ;

	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;
	$deptid = Util_Format_Sanatize( Util_Format_GetVar( "deptid" ), "ln" ) ;
	$ces = Util_Format_Sanatize( Util_Format_GetVar( "ces" ), "ln" ) ;
	$vname = preg_replace( "/<v>/", "", Util_Format_Sanatize( Util_Format_GetVar( "vname" ), "ln" ) ) ;
	$vemail = Util_Format_Sanatize( Util_Format_GetVar( "vemail" ), "e" ) ;
	$vsubject = rawurldecode( Util_Format_Sanatize( Util_Format_GetVar( "vsubject" ), "htmltags" ) ) ;
	$vquestion = rawurldecode( Util_Format_Sanatize( Util_Format_GetVar( "vquestion" ), "htmltags" ) ) ;
	$onpage = rawurldecode( Util_Format_Sanatize( Util_Format_GetVar( "onpage" ), "url" ) ) ;
	$mobile = Util_Format_isMobile() ;

	$agent = $_SERVER["HTTP_USER_AGENT"] ;
	$ip = $_SERVER['REMOTE_ADDR'] ;
	LIST( $os, $browser ) = Util_Format_GetOS( $agent ) ;

	$deptinfo = Depts_get_DeptInfo( $dbh, $deptid ) ;
	if ( !isset( $deptinfo["deptID"] ) )
	{
		$query = $_SERVER["QUERY_STRING"] ;
		$query = preg_replace( "/^d=(\d+)&/", "d=0&", $query ) ;
		HEADER( "location: phplive.php?$query" ) ; exit ;
	}

	if ( $action == "send_email" )
	{
		$opid = Util_Format_Sanatize( Util_Format_GetVar( "opid" ), "ln" ) ;
		$trans = Util_Format_Sanatize( Util_Format_GetVar( "trans" ), "ln" ) ;

		if ( $trans )
		{
			include_once( "./API/Ops/get.php" ) ;
			include_once( "./API/Chat/get_ext.php" ) ;

			$opinfo = Ops_get_OpInfoByID( $dbh, $opid ) ;
			$transcript = Chat_ext_get_Transcript( $dbh, $ces ) ;
			$extra = "trans" ;

			$from_name = $vname ;
			$from_email = $vemail ;
			$vname = $opinfo["name"] ;
			$vemail = $opinfo["email"] ;
			$message = preg_replace( "/%%transcript%%/", $transcript["formatted"], $vquestion ) ;
		}
		else
		{
			include_once( "./API/IPs/get.php" ) ;

			$ipinfo = IPs_get_IPInfo( $dbh, $ip ) ;

			$extra = "" ;
			$from_name = $deptinfo["name"] ;
			$from_email = $deptinfo["email"] ;
			$message = "Message to $from_name:\r\n\r\n$vquestion\r\n\r\n======= Visitor Information =======\r\n\r\nName: $vname\r\nEmail: $vemail\r\n\r\nClicked From:\r\n$onpage\r\n\r\nTotal Footprints: $ipinfo[t_footprints]\r\n\r\n======\r\n\r\n----\r\n".LANG_MSG_EMAIL_FOOTER ;
		}

		if ( is_bool( $error = Util_Email_SendEmail( $vname, $vemail, $from_name, $from_email, $vsubject, $message, $extra ) ) )
			$json_data = "json_data = { \"status\": 1 };" ;
		else
			$json_data = "json_data = { \"status\": 0, \"error\": \"$error\" };" ;

		print "$json_data" ;
		exit ;
	}

	$upload_dir = realpath( "$CONF[DOCUMENT_ROOT]/web" ) ;

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
?>
<?php include_once( "./inc_doctype.php" ) ?>
<head>
<title> PHP Live! Support <?php echo $VERSION ?> </title>

<meta name="description" content="PHP Live! Support <?php echo $VERSION ?>">
<meta name="keywords" content="PHP Live! Support">
<meta name="robots" content="all,index,follow">
<meta http-equiv="content-type" content="text/html; CHARSET=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<link rel="Stylesheet" href="./themes/<?php echo $CONF["THEME"] ?>/style.css">
<script type="text/javascript" src="./js/global.js"></script>
<script type="text/javascript" src="./js/global_chat.js"></script>
<script type="text/javascript" src="./js/framework.js"></script>

<script type="text/javascript">
<!--
	var mouse_x ; var mouse_y ;
	var marquees = marquees_messages = new Array() ;
	var marquee_index = 0 ;

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		}) ;

		init_divs_pre() ;

		init_marquees( "<?php echo $marquee_string ?>" ) ;
	});
	$(window).resize(function() { init_divs_pre() ; });

	function init_divs_pre()
	{
		var browser_height = $(window).height() ; var browser_width = $(window).width() ;
		var body_height = browser_height - $('#chat_footer').height() - 45 ;
		var body_width = browser_width - 42 ;
		var logo_width = body_width - 10 ;
		var deptid_width = logo_width + 5 ;
		var input_width = Math.floor( logo_width/2 ) - 15 ;

		$('#chat_logo').css({'width': logo_width}) ;
		$('#chat_body').css({'height': body_height, 'width': body_width}) ;
		$('#vdeptid').css({'width': deptid_width}) ;
		$('#vname').css({'width': input_width }) ;
		$('#vemail').css({'width': input_width }) ;
		$('#vsubject').css({'width': input_width }) ;
		$('#vquestion').css({'width': input_width }) ;
	}

	function do_submit()
	{
		if ( !$('#vname').val() )
			alert( "<?php echo LANG_CHAT_JS_BLANK_NAME ?>" ) ;
		else if ( !$('#vemail').val() )
			alert( "<?php echo LANG_CHAT_JS_BLANK_EMAIL ?>" ) ;
		else if ( !check_email( $('#vemail').val() ) )
			alert( "<?php echo LANG_CHAT_JS_INVALID_EMAIL ?>" ) ;
		else if ( !$('#vsubject').val() )
			alert( "<?php echo LANG_CHAT_JS_BLANK_SUBJECT ?>" ) ;
		else if ( !$('#vquestion').val() )
			alert( "<?php echo LANG_CHAT_JS_BLANK_QUESTION ?>" ) ;
		else
			do_it() ;
	}

	function do_it()
	{
		var unique = unixtime() ;
		var vname = $('#vname').val() ;
		var vemail = $('#vemail').val() ;
		var vsubject = encodeURIComponent( $('#vsubject').val() ) ;
		var vquestion =  encodeURIComponent( $('#vquestion').val() ) ;
		var onpage =  encodeURIComponent( "<?php echo $onpage ?>" ) ;

		$('#chat_button_start').blur() ;
		$.post("./phplive_m.php", { action: "send_email", deptid: <?php echo $deptid ?>, vname: vname, vemail: vemail, vsubject: vsubject, vquestion: vquestion, onpage: onpage, unique: unique },  function(data){
			eval( data ) ;

			if ( json_data.status )
			{
				$('#chat_button_start').attr( "disabled", true ) ;
				$('#chat_button_start').html( "<img src=\"./themes/default/alert_good.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\"> <?php echo LANG_CHAT_JS_EMAIL_SENT ?>" ) ;
				setTimeout( function(){ window.close() }, 1000 ) ;
			}
			else
			{
				alert( json_data.error ) ;
				$('#chat_button_start').attr( "disabled", false ) ;
				$('#chat_button_start').html( "<?php echo LANG_CHAT_BTN_EMAIL ?>" ) ;
			}
		});
	}

//-->
</script>
</head>
<body>

<div id="chat_canvas" style="min-height: 100%; width: 100%;"></div>
<div style="position: absolute; top: 2px; padding: 10px; z-Index: 2;">
	<div id="chat_body" style="padding: 10px;">

		<div id="chat_logo" style="width: 100%; height: 45px; background: url( <?php echo Util_Upload_GetLogo( ".", $deptid ) ?> ) no-repeat;"></div>
		<div id="chat_text_header" style="margin-top: 10px;"><?php echo LANG_MSG_LEAVE_MESSAGE ?></div>
		<div id="chat_text_header_sub" style="margin-top: 5px;"><?php echo $deptinfo["msg_offline"] ?></div>

		<form method="POST" action="phplive_m.php?submit" id="theform">
		<input type="hidden" name="action" value="submit">
		<input type="hidden" name="deptid" id="deptid" value="<?php echo $deptid ?>">
		<input type="hidden" name="ces" value="<?php echo $ces ?>">
		<input type="hidden" name="onpage" value="<?php echo $onpage ?>">
		<div style="margin-top: 10px;">
			<table cellspacing=0 cellpadding=0 border=0>
			<tr>
				<td>
					<div style="margin-top: 5px;">
					<?php echo LANG_TXT_NAME ?><br>
					<input type="input" class="input_text" id="vname" name="vname" maxlength="40" value="<?php echo $vname ?>">
					</div>
				</td>
				<td>
					<div style="margin-top: 5px; margin-left: 23px;">
					<?php echo LANG_TXT_EMAIL ?><br>
					<input type="input" class="input_text" id="vemail" name="vemail" maxlength="160" value="<?php echo $vemail ?>">
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div style="margin-top: 5px;">
					<?php echo LANG_TXT_SUBJECT ?><br>
					<input type="input" class="input_text" id="vsubject" name="vsubject" maxlength="40" value="<?php echo ( $vsubject ) ? $vsubject : "" ; ?>">
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div style="margin-top: 5px;">
					<?php echo LANG_TXT_MESSAGE ?><br>
					<textarea class="input_text" id="vquestion" name="vquestion" rows="4" wrap="virtual" style="resize: none;"><?php echo preg_replace( "/&lt;br&gt;/i", "\r\n", $vquestion ) ?></textarea>
					</div>
				</td>
				<td>
					<div style="margin-top: 5px; margin-left: 23px;">
						<div id="chat_btn" style="margin-top: 5px;"><button id="chat_button_start" type="button" class="input_button" style="width: 140px; height: 45px; padding: 6px; font-size: 14px; font-weight: bold;" onClick="do_submit()"><?php echo LANG_CHAT_BTN_EMAIL ?></button></div>
						<div id="chat_text_powered" style="margin-top: 10px; font-size: 10px;">PHP Live! powered</div>
					</div>
				</td>
			</tr>
			</table>
		</div>
		</form>

	</div>
</div>

<?php if ( !$mobile ): ?>
<div id="chat_footer" style="position: relative; width: 100%; margin-top: -28px; height: 28px; padding-top: 7px; padding-left: 15px; z-Index: 10;"></div>
<?php endif ; ?>

</body>
</html>
<?php database_mysql_close( $dbh ) ; ?>
