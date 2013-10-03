<?php
	if ( !file_exists( "./web/config.php" ) ){ HEADER("location: ./setup/install.php") ; exit ; }
	include_once( "./web/config.php" ) ;
	include_once( "./API/SQL.php" ) ;
	include_once( "./API/Util_Format.php" ) ;
	include_once( "./API/Util_Error.php" ) ;
	/* AUTO PATCH */
	$document_root = preg_replace( "/[\$\!`\"<>'\?;]|(\.\.)/", "", $CONF["DOCUMENT_ROOT"] ) ;
	if ( !file_exists( realpath( "$document_root/web/patches/5" ) ) )
	{
		$c = Util_Format_Sanatize( Util_Format_GetVar( "c" ), "a" ) ;
		if ( count( $c ) > 10 )
			ErrorHandler ( 606, "Patch process is looping", "$_SERVER[HTTP_HOST]/$_SERVER[REQUEST_URI]", 0, Array() ) ;

		include_once( "./API/Util_Patches.php" ) ;
		HEADER( "location: index.php?c[]=".time()."&".$_SERVER["QUERY_STRING"] ) ;
		exit ;
	}
	include_once( "./lang_packs/$CONF[lang].php" ) ;

	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;
	$from = Util_Format_Sanatize( Util_Format_GetVar( "from" ), "ln" ) ;
	$auto = Util_Format_Sanatize( Util_Format_GetVar( "auto" ), "ln" ) ;
	$wp = ( Util_Format_Sanatize( Util_Format_GetVar( "wp" ), "ln" ) ) ? Util_Format_Sanatize( Util_Format_GetVar( "wp" ), "ln" ) : 0 ;
	$menu = ( Util_Format_Sanatize( Util_Format_GetVar( "menu" ), "ln" ) ) ? Util_Format_Sanatize( Util_Format_GetVar( "menu" ), "ln" ) : "operator" ;

	$error = $reload = $ses = ""  ;

	if ( $action == "submit" )
	{
		$menu = Util_Format_Sanatize( Util_Format_GetVar( "menu" ), "ln" ) ;
		$login = Util_Format_Sanatize( Util_Format_GetVar( "login" ), "ln" ) ;
		$password = Util_Format_Sanatize( Util_Format_GetVar( "password" ), "" ) ;

		if ( $menu == "setup" )
		{
			include_once( "./API/Util_Security.php" ) ;
			include_once( "./API/Ops/get_ext.php" ) ;
			include_once( "./API/Ops/update_ext.php" ) ;
			include_once( "./API/Footprints/get_ext.php" ) ;

			$admininfo = Ops_ext_get_AdminInfoByLoginPass( $dbh, $login, $password ) ;

			if ( isset( $admininfo["adminID"] ) )
			{
				$ses = Util_Security_GenSetupSes() ;
				Ops_ext_update_AdminValue( $dbh, $admininfo["adminID"], "lastactive", time() ) ;
				Ops_ext_update_AdminValue( $dbh, $admininfo["adminID"], "ses", $ses ) ;
				setcookie( "phplive_adminID", $admininfo['adminID'], -1 ) ;

				$sdate = mktime( 0, 0, 1, date( "m", time() ), date( "j", time() )-1, date( "Y", time() ) ) ;
				$footstat = Footprints_get_LatestStats( $dbh ) ;
				database_mysql_close( $dbh ) ;

				if ( isset( $footstat["sdate"] ) && ( $footstat["sdate"] >= $sdate ) )
					HEADER( "location: setup/?ses=$ses" ) ;
				else
					HEADER( "location: setup/optimize.php?ses=$ses" ) ;
				exit ;
			}
			else
				$error = 1 ;
		}
		else if ( $menu == "operator" )
		{
			include_once( "./API/Util_Security.php" ) ;
			include_once( "./API/Ops/get_ext.php" ) ;
			include_once( "./API/Ops/update.php" ) ;

			$opinfo = Ops_ext_get_OpInfoByLoginPass( $dbh, $login, $password ) ;
			if ( isset( $opinfo["opID"] ) )
			{
				$ses = Util_Security_GenSetupSes() ;
				Ops_update_OpValue( $dbh, $opinfo["opID"], "lastactive", time() ) ;
				Ops_update_OpValue( $dbh, $opinfo["opID"], "ses", $ses ) ;

				if ( isset( $_COOKIE["phplive_opID"] ) && ( $_COOKIE["phplive_opID"] != $opinfo['opID'] ) )
				{
					include_once( "./API/Ops/get.php" ) ;

					$opinfo_ = Ops_get_OpInfoByID( $dbh, $_COOKIE["phplive_opID"] ) ;
					// lastactive updates ~10 seconds.  if not updated more then 2 cycles, they are offline
					if ( $opinfo_["lastactive"] > time()-25 )
					{
						Ops_update_OpValue( $dbh, $_COOKIE["phplive_opID"], "status", 0 ) ;
						Ops_update_OpValue( $dbh, $_COOKIE["phplive_opID"], "ses", "expired" ) ;
						$reload = 1 ;
					}
				}
				setcookie( "phplive_opID", $opinfo['opID'], -1 ) ;
			}
			else
				$error = 1 ;
		}
	}
	else if ( $action == "logout" )
	{
		$from = Util_Format_Sanatize( Util_Format_GetVar( "from" ), "ln" ) ;

		if ( $from == "setup" )
		{
			include_once( "./API/SQL.php" ) ;
			include_once( "./API/Ops/update_ext.php" ) ;

			if ( isset( $_COOKIE["phplive_adminID"] ) ) { Ops_ext_update_AdminValue( $dbh, $_COOKIE["phplive_adminID"], "ses", "" ) ; setcookie( "phplive_adminID", FALSE ) ; }
		}
		else if ( $from == "operator" )
		{
			include_once( "./API/SQL.php" ) ;
			include_once( "./API/Ops/update.php" ) ;

			if ( isset( $_COOKIE["phplive_opID"] ) ) { Ops_update_OpValue( $dbh, $_COOKIE["phplive_opID"], "status", 0 ) ; Ops_update_OpValue( $dbh, $_COOKIE["phplive_opID"], "ses", "" ) ; setcookie( "phplive_opID", FALSE ) ; }
		}
	}
?>
<?php include_once( "./inc_doctype.php" ) ?>
<head>
<title> PHP Live! Support <?php echo $VERSION ?> </title>

<meta name="description" content="PHP Live! Support <?php echo $VERSION ?>">
<meta name="keywords" content="PHP Live! Support">
<meta name="robots" content="all,index,follow">
<meta http-equiv="content-type" content="text/html; CHARSET=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<link rel="Stylesheet" href="./css/base_setup.css">
<script type="text/javascript" src="./js/global.js"></script>
<script type="text/javascript" src="./js/global_chat.js"></script>
<script type="text/javascript" src="./js/setup.js"></script>
<script type="text/javascript" src="./js/framework.js"></script>
<script type="text/javascript" src="./js/framework_ext.js"></script>
<script type="text/javascript" src="./js/jquery.tools.min.js"></script>

<script type="text/javascript">
<!--
	var mouse_x ; var mouse_y ;
	var loaded = 1 ;
	var base_url = "." ;
	var widget = 0 ;

	// for console extra iframe situation (disabled for now - causes confusion)
	if ( typeof( parent.isop ) != "undefined" )
	{
		parent.base_url = "." ; // set it because calling from phplive root dir
		parent.toggle_status( 3 ) ;
	}

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		}) ;

		init_menu() ;

		// when this loads in the op console, checking if opener is logged in
		//if ( opener == "[object Window]" )
		//{
			// for now disable... may cause confusion
			//setInterval(function(){ check_login( opener ); }, 200) ;
		//}

		<?php if ( $error ): ?>do_alert( 0, "Invalid login or password." ) ;<?php endif; ?>

		toggle_menu( "<?php echo $menu ?>" ) ;

		<?php
			if ( ( $action == "submit" ) && ( $menu == "operator" ) && !$error )
			{
				if ( $reload )
				{
					print "$('#div_reload').show() ;" ;
					print "setTimeout( function(){ $('#theform').submit() ; }, 12000 ) ;" ;
				}
				else
				{
					print "$('#div_sound').show() ; play_sound( \"login_op\" ) ;" ;
					if ( $wp || $auto )
						print "setTimeout( function(){ location.href='ops/operator.php?ses=$ses&wp=$wp' ; }, 4000 ) ;" ;
					else
						print "setTimeout( function(){ location.href='ops/?ses=$ses' ; }, 4000 ) ;" ;
				}
			}
		?>

		<?php if ( !preg_match( "/(submit)|(logout)/", $action ) && !$error ): ?>
		flashembed( "flash_result", {
			src: "./media/expressInstall.swf",
			version: [8, 0],
			expressInstall: "./media/expressInstall.swf",
			onFail: function() {
				$('#flash_detect').show() ;
			}
		});
		<?php endif ; ?>


		if ( <?php echo $wp ?> && ( "<?php echo $action ?>" != "logout" ) )
			window.external.wp_save_history( location.href.replace( /index.php.+/i, "winapp.php" ) ) ;
		else if ( <?php echo $wp ?> && ( "<?php echo $action ?>" == "logout" ) )
			wp_total_visitors( 0 ) ;
	});

	function check_login( theopener )
	{
		if ( ( typeof( theopener.menu ) != "undefined" ) )
		{
			//theopener.window.launchit() ; // blocked by popups... disabled for now
			theopener.focus() ;
			window.close() ;
		}
	}

	function toggle_menu( themenu )
	{
		var divs = Array( "operator", "setup" ) ;
		for ( c = 0; c < divs.length; ++c )
			$('#menu_'+divs[c]).removeClass('menu_focus').addClass("menu") ;

		$('#menu_'+themenu).removeClass("menu").addClass("menu_focus") ;
		if ( themenu == "operator" )
		{
			$('#body_sub_title').html( "<img src=\"./pics/icons/user_key.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" style=\"margin-right: 5px;\"> Login as Operator" ) ;
			$('#btn_login').val( "Login as Operator" ) ;
			//$('#login').val( "" ).attr("readonly", false) ;
		}
		else
		{
			$('#body_sub_title').html( "<img src=\"./pics/icons/user_key.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" style=\"margin-right: 5px;\"> Login to Setup" ) ;
			$('#btn_login').val( "Login as Setup" ) ;
		}

		$('#menu').val( themenu ) ;
	}

	function do_login()
	{
		if ( $('#login').val() == "" )
			alert( "Please provide a login." ) ;
		else if ( $('#password').val() == "" )
			alert( "Please provide a password." ) ;
		else
			$('#theform').submit() ;
	}

	function wp_total_visitors( thecounter )
	{
		// put # in taskbar
		// * = logo icon
		window.external.wp_total_visitors( thecounter ) ;
	}
//-->
</script>
</head>
<body style="overflow: hidden;">

<div id="body" style="padding-bottom: 60px;">
	<div id="body_wrapper" style="z-Index: 5;"></div>
	<div style="width: 100%; z-Index: 10;">
		<div style="width: 480px; margin: 0 auto;">
		<div id="body_sub_title"></div>

			<table cellspacing=0 cellpadding=0 border=0 width="100%" class="op_submenu_wrapper"><tr><td class="t_tl"></td><td class="t_tm"></td><td class="t_tr"></td></tr>
			<tr>
				<td class="t_ml"></td><td class="t_mm">
					<table cellspacing=0 cellpadding=5 border=0>
					<form method="POST" action="index.php?submit" id="theform">
					<input type="hidden" name="action" value="submit">
					<input type="hidden" name="auto" value="<?php echo $auto ?>">
					<input type="hidden" name="wp" value="<?php echo $wp ?>">
					<input type="hidden" name="menu" id="menu" value="">
					<tr>
						<td>Login</td>
						<td> <input type="text" name="login" id="login" size="15" maxlength="15" value="<?php echo ( isset( $login ) ) ? $login : "" ?>" onKeyPress="return nospecials(event)"></td>
						<td>Password</td>
						<td> <input type="password" class="input" name="password" id="password" size="15" maxlength="25" value="<?php echo ( isset( $password ) && $reload ) ? $password : "" ; ?>"></td>
					</tr>
					<tr>
						<td></td><td><div style="margin-top: 10px;"><input type="button" id="btn_login" value="Login as Operator" onClick="do_login()"></div></td>
					</tr>
					</form>
					</table>
				</td><td class="t_mr"></td>
			</tr>
			<tr><td class="t_bl"></td><td class="t_bm"></td><td class="t_br"></td></tr>
			</table>
		
		</div>

		<div style="width: 100%;">
			<div style="width: 480px; margin: 0 auto; margin-top: 30px; font-size: 10px; background: url( ./pics/bg_footer.gif ) repeat-x; border-left: 1px solid #B1B4B5; border-right: 1px solid #B1B4B5; border-bottom: 1px solid #B1B4B5; color: #606060; -moz-border-radius: 5px; border-radius: 5px;">
				<div style="float: left; padding: 3px; padding-top: 7px;"><a href="http://www.phplivesupport.com/?ref=link&key=<?php echo $KEY ?>&plk=osicodes-5-ykq-m" target="_blank">PHP Live! Support</a> &copy; OSI Codes Inc.</div>
				<div style="clear: both;"></div>
			</div>
		</div>
	</div>
</div>
<div style="position: absolute; top: 0px; left: 0px; width: 100%; z-index: 13;">
	<div style="width: 480px; margin: 0 auto; background: url( ./pics/bg_trans.png ) repeat; border-bottom-left-radius: 5px 5px; -moz-border-radius-bottomleft: 5px 5px; border-bottom-right-radius: 5px 5px; -moz-border-radius-bottomright: 5px 5px;">
		<div id="menu_wrapper">
			<div id="menu_operator" class="menu" onClick="toggle_menu('operator')">Login as Operator</div>
			<?php if ( !$wp && !$auto ): ?><div id="menu_setup" class="menu" onClick="toggle_menu('setup')">Login to Setup</div><?php endif; ?>
			<div style="clear: both;"></div>
		</div>
	</div>
</div>
<div style="position: absolute; top: 13px; left: 0px; width: 100%; z-index: 12;">
	<div style="width: 480px; margin: 0 auto; padding-top: 32px;">
		<div style="font-size: 10px; font-weight: bold; color: #CBD1D5; text-align: right;"><span style="font-size: 16px; text-shadow: #5A6787 -1px -1px;">PHP Live! v.<?php echo $VERSION ?></span> &nbsp; <span style="padding: 5px; background: #C5C7D5; color: #7C89A9;">not signed in</span></div>
	</div>
</div>

<div style="position: absolute; background: url( ./pics/bg_fade_bottom.png ) repeat-x; background-position: bottom; top: 0px; left: 0px; width: 100%; height: 60px; z-index: 11;"></div>

<div id="div_sound" style="display: none; position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; background: url( ./pics/bg_trans.png ) repeat; overflow: hidden; z-index: 20;">
	<div style="position: relative; width: 460px; margin: 0 auto; top: 130px; text-align: center;">
		<div class="info_box" style="font-size: 14px; font-weight: bold;">
			<img src="pics/icons/vcard.png" width="16" height="16" border="0" alt=""> Authentication success.  Logging in...
			<div id="sounds" style="width: 1px; height: 1px; overflow: hidden; opacity:0.0; filter:alpha(opacity=0);">
				<div id="div_sounds_login_op"></div>
			</div>
		</div>
	</div>
</div>

<div id="div_reload" style="display: none; position: absolute; top: 0px; left: 0px; width: 100%; height: 2000px; background: url( ./pics/bg_trans.png ) repeat; overflow: hidden; z-index: 20;">
	<div style="position: relative; width: 460px; margin: 0 auto; top: 130px; text-align: center;">
		<div class="info_box" style="font-size: 14px; font-weight: bold;">
			<img src="pics/loading_ci.gif" width="16" height="16" border="0" alt=""> Multiple sessions detected.  Please hold...
		</div>
	</div>
</div>

<?php include_once( "./inc_flash.php" ) ; ?>

<!-- [winapp=4] -->

</body>
</html>
<?php
	if ( isset( $dbh ) && isset( $dbh['con'] ) )
		database_mysql_close( $dbh ) ;
?>
