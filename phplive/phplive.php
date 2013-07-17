<?php
	include_once( "./web/config.php" ) ;
	include_once( "./API/Util_Format.php" ) ;
	include_once( "./API/Util_Error.php" ) ;
	include_once( "./API/Util_Security.php" ) ;
	include_once( "./API/Util_Upload.php" ) ;
	include_once( "./API/SQL.php" ) ;
	include_once( "./API/Util_Vals.php" ) ;
	include_once( "./API/Depts/get.php" ) ;
	include_once( "./API/Ops/get.php" ) ;
	include_once( "./API/Chat/get.php" ) ;
	include_once( "./API/Marketing/get.php" ) ;
	/* AUTO PATCH [mod Sam: 60] */ 
	/* do automated patching if visitor requests chat incase it has not been patched yet */
	if ( !file_exists( realpath( "$CONF[DOCUMENT_ROOT]/web/patches/5" ) ) )
	{
		$c = Util_Format_Sanatize( Util_Format_GetVar( "c" ), "a" ) ;
		if ( count( $c ) > 10 )
			ErrorHandler ( 606, "Patch process is looping", "$_SERVER[HTTP_HOST]/$_SERVER[REQUEST_URI]", 0, Array() ) ;

		include_once( "./API/Util_Patches.php" ) ;
		HEADER( "location: phplive.php?c[]=".time()."&".$_SERVER["QUERY_STRING"] ) ;
		exit ;
	}
	include_once( "./lang_packs/$CONF[lang].php" ) ;

	$ces = Util_Security_GenSetupSes() ;
	$onpage = Util_Format_Sanatize( Util_Format_GetVar( "onpage" ), "url" ) ;
	$title = Util_Format_Sanatize( Util_Format_GetVar( "title" ), "title" ) ;
	$deptid = Util_Format_Sanatize( Util_Format_GetVar( "d" ), "ln" ) ;
	$theme = Util_Format_Sanatize( Util_Format_GetVar( "theme" ), "ln" ) ;
	$widget = Util_Format_Sanatize( Util_Format_GetVar( "widget" ), "ln" ) ;
	$mobile = Util_Format_isMobile() ;

	$marquee_test = Util_Format_Sanatize( Util_Format_GetVar( "marquee_test" ), "js" ) ;

	$agent = $_SERVER["HTTP_USER_AGENT"] ;
	$ip = $_SERVER['REMOTE_ADDR'] ;
	LIST( $os, $browser ) = Util_Format_GetOS( $agent ) ;

	$vname = isset( $_COOKIE["phplive_vname"] ) ? $_COOKIE["phplive_vname"] : "" ;
	$vemail = isset( $_COOKIE["phplive_vemail"] ) ? $_COOKIE["phplive_vemail"] : "" ;

	if ( preg_match( "/$ip/", $VALS["CHAT_SPAM_IPS"] ) )
		$spam_exist = 1 ;
	else
		$spam_exist = 0 ;

	if ( $widget )
		$requestinfo = Chat_get_RequestIPInfo( $dbh, $ip, 1 ) ;

	if ( $deptid )
	{
		$deptinfo = Depts_get_DeptInfo( $dbh, $deptid ) ;
		if ( !isset( $deptinfo["deptID"] ) )
		{
			$query = $_SERVER["QUERY_STRING"] ;
			$query = preg_replace( "/^d=(\d+)&/", "d=0&", $query ) ;
			HEADER( "location: phplive.php?$query" ) ; exit ;
		}

		if ( $spam_exist )
			$total = 0 ;
		else
			$total = Ops_get_AnyOpsOnline( $dbh, $deptinfo["deptID"] ) ;
		
		if ( !$total )
		{
			$url = "phplive_m.php?ces=$ces&deptid=$deptid&vname=$vname&vemail=$vemail&vquestion=&onpage=".rawurlencode($onpage) ;
			HEADER( "location: $url" ) ; exit ;
		}
	}
	else
	{
		$total_ops = 0 ;
		$dept_online = Array() ;
		$departments = Depts_get_AllDepts( $dbh ) ;
		for ( $c = 0; $c < count( $departments ); ++$c )
		{
			$department = $departments[$c] ;
			if ( $spam_exist )
				$total = 0 ;
			else
				$total = Ops_get_AnyOpsOnline( $dbh, $department["deptID"] ) ;
			$total_ops += $total ;
			$dept_online[$department["deptID"]] = $total ;
		}
	}

	// todo: finish it [mod Sam: 60]
	// - have to get default leave a message text somewhere...
	/*
	if ( !$total_ops )
	{
		$url = "phplive_m.php?ces=$ces&deptid=&vname=$vname&vemail=$vemail&vquestion=" ;
		HEADER( "location: $url" ) ;
		exit ;
	}
	*/

	include_once( "./setup/KEY.php" ) ;
	$upload_dir = realpath( "$CONF[DOCUMENT_ROOT]/web" ) ;

	if ( $marquee_test )
		$marquees = Array( Array( "snapshot" => "", "message" => "$marquee_test" ) ) ;
	else
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
<meta name="phplive" content="version: <?php echo $VERSION ?>, KEY: <?php echo $KEY ?>">
<meta http-equiv="content-type" content="text/html; CHARSET=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<link rel="Stylesheet" href="./themes/<?php echo ( $theme ) ? $theme : $CONF["THEME"] ; ?>/style.css">
<script type="text/javascript" src="./js/global.js"></script>
<script type="text/javascript" src="./js/global_chat.js"></script>
<script type="text/javascript" src="./js/setup.js"></script>
<script type="text/javascript" src="./js/framework.js"></script>
<script type="text/javascript" src="./js/framework_ext.js"></script>
<script type="text/javascript" src="./js/jquery.tools.min.js"></script>

<script type="text/javascript">
<!--
	var mouse_x ; var mouse_y ;
	var marquees = marquees_messages = new Array() ;
	var marquee_index = 0 ;

	var win_width = screen.width ;
	var win_height = screen.height ;

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		}) ;

		$('#win_dim').val( win_width + " x " + win_height ) ;
		init_divs_pre() ;

		init_marquees( "<?php echo $marquee_string ?>" ) ;

		$('#chat_button_start').html( "<?php echo LANG_CHAT_BTN_START_CHAT ?>" ).unbind('click').bind('click', function() {
			start_chat() ;
		}) ;

		<?php echo ( isset( $requestinfo["ces"] ) ) ? "start_chat() ; " : "$('body').show() ; " ?>
		
		flashembed( "flash_result", {
			src: "./media/expressInstall.swf",
			version: [8, 0],
			expressInstall: "./media/expressInstall.swf",
			onFail: function() {
				$('#flash_detect').show() ;
			}
		});
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
		$('#vquestion').css({'width': input_width }) ;
	}

	function select_dept( thevalue )
	{
		$('#deptid').val( thevalue ) ;
		if ( $('#vdeptid>option:selected').attr( "class" ) == "offline" )
		{
			$('#chat_button_start').html( "<?php echo LANG_CHAT_BTN_EMAIL ?>" ).unbind('click').bind('click', function() {
				send_email() ;
			});
		}
		else
		{
			$('#chat_button_start').html( "<?php echo LANG_CHAT_BTN_START_CHAT ?>" ).unbind('click').bind('click', function() {
				start_chat() ;
			});
		}
	}

	function check_form()
	{
		if ( !$('#deptid').val() ){
			do_alert( 0, "<?php echo LANG_CHAT_JS_BLANK_DEPT ?>" ) ;
			return false ;
		}
		else if ( !$('#vname').val() ){
			do_alert( 0, "<?php echo LANG_CHAT_JS_BLANK_NAME ?>" ) ;
			return false ;
		}
		else if ( !$('#vemail').val() ){
			do_alert( 0, "<?php echo LANG_CHAT_JS_BLANK_EMAIL ?>" ) ;
			return false ;
		}
		else if ( !$('#vquestion').val() ){
			do_alert( 0, "<?php echo LANG_CHAT_JS_BLANK_QUESTION ?>" ) ;
			return false ;
		}
		else
			return true ;
	}

	function start_chat()
	{
		if ( check_form() )
			$('#theform').submit() ;
	}

	function send_email()
	{
		if( check_form() )
		{
			var unique = unixtime() ;
			var deptid = $('#deptid').val() ;
			var vname = $('#vname').val() ;
			var vemail = $('#vemail').val() ;
			var vsubject = encodeURIComponent( "<?php echo LANG_CHAT_JS_LEAVE_MSG ?>" ) ;
			var vquestion =  encodeURIComponent( $('#vquestion').val() ) ;
			var onpage =  encodeURIComponent( "<?php echo $onpage ?>" ) ;

			$('#chat_button_start').blur() ;
			$.post("./phplive_m.php", { action: "send_email", deptid: deptid, vname: vname, vemail: vemail, vsubject: vsubject, vquestion: vquestion, onpage: onpage, unique: unique },  function(data){
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
	}

//-->
</script>
</head>
<body style="display: none; overflow: hidden;">
<div id="chat_canvas" style="min-height: 100%; width: 100%;"></div>
<div style="position: absolute; top: 2px; padding: 10px; z-Index: 2;">
	<div id="chat_body" style="padding: 10px;">

		<div id="chat_logo" style="width: 100%; height: 45px; background: url( <?php echo Util_Upload_GetLogo( ".", $deptid ) ?> ) no-repeat;"></div>
		<div id="chat_text_header" style="margin-top: 10px;"><?php echo LANG_CHAT_WELCOME ?></div>
		<div id="chat_text_header_sub" style="margin-top: 5px;"><?php echo LANG_CHAT_WELCOME_SUBTEXT ?></div>

		<form method="POST" action="phplive_.php?submit" id="theform">
		<input type="hidden" name="deptid" id="deptid" value="<?php echo isset( $requestinfo["deptID"] ) ? $requestinfo["deptID"] : $deptid ; ?>">
		<input type="hidden" name="ces" value="<?php echo isset( $requestinfo["ces"] ) ? $requestinfo["ces"] : $ces ; ?>">
		<input type="hidden" name="onpage" value="<?php echo isset( $requestinfo["ces"] ) ? $requestinfo["onpage"] : $onpage ; ?>">
		<input type="hidden" name="title" value="<?php echo isset( $requestinfo["ces"] ) ? $requestinfo["title"] :$title ; ?>">
		<input type="hidden" name="win_dim" id="win_dim" value="">
		<input type="hidden" name="widget" value="<?php echo $widget ?>">
		<div style="margin-top: 10px;">
			<table cellspacing=0 cellpadding=0 border=0>
			<tr>
				<td colspan=2>
					<?php if ( !$deptid ): ?>
						<?php echo LANG_TXT_DEPARTMENT ?><br>
						<select class="input_text" id="vdeptid" onChange="select_dept(this.value)"><option value=""><?php echo LANG_CHAT_SELECT_DEPT ?></option>
						<?php
							for ( $c = 0; $c < count( $departments ); ++$c )
							{
								$department = $departments[$c] ;
								if ( $department["visible"] )
								{
									$class = "offline" ; $text = LANG_TXT_OFFLINE ;
									if ( $dept_online[$department["deptID"]] )
									{
										$class = "online" ;
										$text = LANG_TXT_ONLINE ;
									}
									
									print "<option class=\"$class\" value=\"$department[deptID]\">$department[name] - $text</option>" ;
								}
							}
						?>
						</select>
					<?php endif ; ?>
				</td>
			</tr>
			<tr>
				<td>
					<div style="margin-top: 5px;">
					<?php echo LANG_TXT_NAME ?><br>
					<input type="input" class="input_text" id="vname" name="vname" maxlength="40" value="<?php echo isset( $requestinfo["vname"] ) ? preg_replace( "/<v>/", "", $requestinfo["vname"] ) : $vname ; ?>" onKeyPress="return nospecials(event)">
					</div>
				</td>
				<td>
					<div style="margin-top: 5px; margin-left: 23px;">
					<?php echo LANG_TXT_EMAIL ?><br>
					<input type="input" class="input_text" id="vemail" name="vemail" maxlength="160" value="<?php echo isset( $requestinfo["vemail"] ) ? $requestinfo["vemail"] : $vemail ; ?>" onKeyPress="return justemails(event)">
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div style="margin-top: 5px;">
					<?php echo LANG_TXT_QUESTION ?><br>
					<textarea class="input_text" id="vquestion" name="vquestion" rows="5" wrap="virtual" style="resize: none;"><?php echo isset( $requestinfo["question"] ) ? $requestinfo["question"] : "" ; ?></textarea>
					</div>
				</td>
				<td>
					<div id="chat_btn" style="margin-top: 5px; margin-left: 23px;">
						<input type="checkbox" class="input_text" name="etrans" value=1 checked> <?php echo LANG_CHAT_AUTO_EMAIL_TRANS ?><br>
						<div style="margin-top: 10px;"><button id="chat_button_start" class="input_button" type="button" style="width: 140px; height: 45px; padding: 6px; font-size: 14px; font-weight: bold;"><?php echo LANG_CHAT_BTN_START_CHAT ?></button></div>
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

<?php include_once( "./inc_flash.php" ) ; ?>

</body>
</html>
<?php database_mysql_close( $dbh ) ; ?>
