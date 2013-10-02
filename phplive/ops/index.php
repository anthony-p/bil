<?php
	/****************************************/
	// STANDARD header for Setup
	if ( !file_exists( "../web/config.php" ) ){ HEADER("location: ../setup/install.php") ; exit ; }
	include_once( "../web/config.php" ) ;
	include_once( "../API/Util_Format.php" ) ;
	include_once( "../API/Util_Error.php" ) ;
	include_once( "../API/SQL.php" ) ;
	include_once( "../API/Util_Security.php" ) ;
	$ses = Util_Format_Sanatize( Util_Format_GetVar( "ses" ), "ln" ) ;
	if ( !$opinfo = Util_Security_AuthOp( $dbh, $ses ) ){ ErrorHandler ( 602, "Invalid setup session or session has expired.", "$_SERVER[HTTP_HOST]/$_SERVER[REQUEST_URI]", 0, Array() ) ; }
	// STANDARD header end
	/****************************************/

	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;

	$menu = "go" ;
	$error = "" ;

	if ( $action == "update_password" )
	{
		$cpass = Util_Format_Sanatize( Util_Format_GetVar( "cpass" ), "ln" ) ;
		$npass = Util_Format_Sanatize( Util_Format_GetVar( "npass" ), "ln" ) ;
		$vnpass = Util_Format_Sanatize( Util_Format_GetVar( "vnpass" ), "ln" ) ;

		if ( md5( $cpass ) != $opinfo["password"] )
			$error = "Current password is invalid." ;
		else if ( $npass != $vnpass )
			$error = "Verify password does not match." ;
		else
		{
			include_once( "../API/Ops/update.php" ) ;

			Ops_update_OpValue( $dbh, $opinfo["opID"], "password", md5( $npass ) ) ;
		}

		$menu = "password" ;
	}
	else if ( $action == "update_theme" )
	{
		include_once( "../API/Ops/update.php" ) ;
		$theme = Util_Format_Sanatize( Util_Format_GetVar( "theme" ), "ln" ) ;

		if ( !Ops_update_OpValue( $dbh, $opinfo["opID"], "theme", $theme ) )
			$error = "Error in updating theme." ;
		else
			$opinfo["theme"] = $theme ;
		
		$menu = "themes" ;
	}
	else
		$error = "invalid action" ;

?>
<?php include_once( "../inc_doctype.php" ) ?>
<head>
<title> PHP Live! Support <?php echo $VERSION ?> </title>

<meta name="description" content="PHP Live! Support <?php echo $VERSION ?>">
<meta name="keywords" content="PHP Live! Support">
<meta name="robots" content="all,index,follow">
<meta http-equiv="content-type" content="text/html; CHARSET=utf-8"> 
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<link rel="Stylesheet" href="../css/base_setup.css">
<script type="text/javascript" src="../js/global.js"></script>
<script type="text/javascript" src="../js/setup.js"></script>
<script type="text/javascript" src="../js/framework.js"></script>
<script type="text/javascript" src="../js/framework_ext.js"></script>

<script type="text/javascript">
<!--
	var mouse_x ; var mouse_y ;
	var newwin ;
	var menu ;
	var theme = "<?php echo $opinfo["theme"] ?>" ;

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		}) ;

		$('#op_launch_btn').live('mouseover mouseout', function(event) {
			$('#op_launch_btn').toggleClass('op_launch_btn_focus') ;
		});

		init_menu() ;
		toggle_menu_op( "<?php echo $menu ?>" ) ;

		<?php if ( $action && !$error ): ?>do_alert( 1, "Update Success!" ) ;<?php endif ; ?>
	});

	function launchit()
	{
		var url = "operator.php?ses=<?php echo $ses ?>&"+unixtime() ;

		if ( typeof( newwin ) == "undefined" )
			newwin = window.open( url, "<?php echo $ses ?>", "scrollbars=yes,menubar=no,resizable=1,location=no,width=850,height=480,status=0" ) ;
		else if ( newwin.closed )
			newwin = window.open( url, "<?php echo $ses ?>", "scrollbars=yes,menubar=no,resizable=1,location=no,width=850,height=480,status=0" ) ;
		newwin.focus() ;

		return false ;
	}

	function confirm_theme( thetheme )
	{
		if ( theme != thetheme )
		{
			if ( confirm( "Use the theme: "+thetheme+"?" ) )
				update_theme( thetheme ) ;
			else
				$('#theme_<?php echo $opinfo["theme"] ?>').attr('checked', true) ;
		}
	}

	function update_theme( thetheme )
	{
		location.href = 'index.php?ses=<?php echo $ses ?>&action=update_theme&theme='+thetheme ;
	}

	function update_password()
	{
		if ( $('#cpass').val() == "" )
			do_alert( 0, "Please provide the current password." ) ;
		else if ( $('#npass').val() == "" )
			do_alert( 0, "Please provide the new password." ) ;
		else if ( $('#npass').val() != $('#vnpass').val() )
			do_alert( 0, "New password does not match." ) ;
		else
			$('#theform').submit() ;
	}

	function logout()
	{
		location.href = "../index.php?action=logout&from=operator&ses=<?php echo $ses ?>" ;
	}

//-->
</script>
</head>
<body>

<div id="body" style="padding-bottom: 60px;">
	<div id="body_wrapper" style="z-Index: 5;"></div>
	<div style="width: 100%; z-Index: 10;">
		<div style="width: 480px; margin: 0 auto;">
		<div id="body_sub_title"></div>

			<table cellspacing=0 cellpadding=0 border=0 width="100%" class="op_submenu_wrapper"><tr><td class="t_tl"></td><td class="t_tm"></td><td class="t_tr"></td></tr>
			<tr>
				<td class="t_ml"></td><td class="t_mm">
					
					<div id="op_go" class="body_main" style="margin: 0 auto;">
						<div class="info_box">
							<table cellspacing=0 cellpadding=2 border=0>
							<tr><td><img src="../pics/icons/info.png" width="16" height="16" border="0" alt=""></td><td>You can add canned responses, view past transcripts, update spam guard and other options in your <b>Chat Request Console</b> below.</td></tr>
							</table>
						</div>
						<div id="op_launch_btn" class="op_launch_btn" style="margin-top: 30px; width: 250px; height: 30px;" onClick="launchit()"><div id="op_launch_btn_text">Launch Chat Request Console</div></div>
					</div>

					<div id="op_themes" class="body_main" style="display: none; margin: 0 auto;">
						<div class="info_box"><img src="../pics/icons/info.png" width="16" height="16" border="0" alt=""> Click on the theme name to preview, select the radio button to choose.</div>
						<div style="margin-top: 5px; font-size: 10px;">If the Operator Console is opended, you'll want to reload the console for the new theme to take affect.</div>
						<table cellspacing=0 cellpadding=2 border=0 width="100%" style="margin-top: 30px;">
						<form>
						<tr>
							<td>
								<?php
									$dir_themes = opendir( realpath( "$CONF[DOCUMENT_ROOT]/themes/" ) ) ;

									$themes = Array() ;
									while ( $theme = readdir( $dir_themes ) )
										$themes[] = $theme ;
									closedir( $dir_themes ) ;

									for ( $c = 0; $c < count( $themes ); ++$c )
									{
										$theme = $themes[$c] ;

										$checked = "" ;
										if ( $opinfo["theme"] == $theme )
											$checked = "checked" ;

										if ( preg_match( "/[a-z]/i", $theme ) && ( $theme != "initiate" ) )
											print "<div class=\"op_theme_cell\"><input type=\"radio\" name=\"theme\" id=\"theme_$theme\" value=\"$theme\" $checked onClick=\"confirm_theme('$theme')\"> <span style=\"cursor: pointer;\" onClick=\"preview_theme('$theme')\">$theme</span></div>" ;
									}
								?>
								<div style="clear: both;"></div>
							</td>
						</tr>
						</form>
						</table>
					</div>

					<div id="op_password" class="body_main" style="display: none; margin: 0 auto;">
						<?php if ( $action && $error ): ?>
							<div id="div_error" class="info_error" style="margin-bottom: 10px;"><img src="../pics/icons/alert.png" width="16" height="16" border="0" alt=""> <?php echo $error ?></div>
						<?php endif; ?>
						<div class="info_box">
							<table cellspacing=0 cellpadding=2 border=0>
							<tr><td><img src="../pics/icons/info.png" width="16" height="16" border="0" alt=""></td><td>Password should contain only letters and numbers.</td></tr>
							</table>
						</div>

						<table cellspacing=0 cellpadding=5 border=0 style="margin-top: 30px;">
						<form method="POST" action="index.php?submit" id="theform">
						<input type="hidden" name="action" value="update_password">
						<input type="hidden" name="ses" value="<?php echo $ses ?>">
						<tr>
							<td>Current Password</td>
							<td> <input type="password" class="input" name="cpass" id="cpass" size="18" maxlength="15" value="" onKeyPress="return nospecials(event)"></td>
						</tr>
						<tr>
							<td>New Password</td>
							<td> <input type="password" class="input" name="npass" id="npass" size="18" maxlength="15" value="" onKeyPress="return nospecials(event)"></td>
						</tr>
						<tr>
							<td>Verify New Password</td>
							<td> <input type="password" class="input" name="vnpass" id="vnpass" size="18" maxlength="15" value="" onKeyPress="return nospecials(event)"></td>
						</tr>
						<tr>
							<td></td><td> <div style=""><input type="button" value="Update" onClick="update_password()"></div></td>
						</tr>
						</table>
					</div>

					<div id="op_settings" class="body_main" style="display: none; margin: 0 auto;">
						
					</div>

				</td><td class="t_mr"></td>
			</tr>
			<tr><td class="t_bl"></td><td class="t_bm"></td><td class="t_br"></td></tr>
			</table>
		
		</div>

		<div style="width: 100%;">
			<div style="width: 480px; margin: 0 auto; margin-top: 30px; font-size: 10px; background: url( ../pics/bg_footer.gif ) repeat-x; border-left: 1px solid #B1B4B5; border-right: 1px solid #B1B4B5; border-bottom: 1px solid #B1B4B5; color: #606060; -moz-border-radius: 5px; border-radius: 5px;">
				<div style="float: left; padding: 3px; padding-top: 7px;"><a href="http://www.phplivesupport.com/?ref=link&key=<?php echo $KEY ?>&plk=osicodes-5-ykq-m" target="_blank">PHP Live! Support</a> &copy; OSI Codes Inc.</div>
				<div style="clear: both;"></div>
			</div>
		</div>
	</div>
</div>
<div style="position: absolute; top: 0px; left: 0px; width: 100%; z-index: 13;">
	<div style="width: 480px; margin: 0 auto; background: url( ../pics/bg_trans.png ) repeat; border-bottom-left-radius: 5px 5px; -moz-border-radius-bottomleft: 5px 5px; border-bottom-right-radius: 5px 5px; -moz-border-radius-bottomright: 5px 5px;">
		<div id="menu_wrapper">
			<div id="menu_go" class="menu" onClick="toggle_menu_op('go')">Go ONLINE!</div>
			<div id="menu_themes" class="menu" onClick="toggle_menu_op('themes')">Themes</div>
			<div id="menu_password" class="menu" onClick="toggle_menu_op('password')">Update Password</div>
			<!-- <div id="menu_settings" class="menu" onClick="toggle_menu_op('settings')">Settings</div> -->
			<div style="clear: both;"></div>
		</div>
	</div>
</div>
<div style="position: absolute; top: 13px; left: 0px; width: 100%; z-index: 12;">
	<div style="width: 480px; margin: 0 auto; padding-top: 32px;">
		<div style="font-size: 10px; font-weight: bold; color: #CBD1D5; text-align: right;"><span style="font-size: 16px; text-shadow: #5A6787 -1px -1px;">PHP Live! v.<?php echo $VERSION ?></span> &nbsp; <span style="padding: 5px; background: #C5C7D5; color: #7C89A9;"><?php echo ( isset($opinfo['opID']) ) ? "$opinfo[name] <a href=\"JavaScript:void(0)\" onClick=\"logout()\">sign out</a>" : "not signed in" ; ?></span></div>
	</div>
</div>

<div style="position: absolute; background: url( ../pics/bg_fade_bottom.png ) repeat-x; background-position: bottom; top: 0px; left: 0px; width: 100%; height: 60px; z-index: 11;"></div>

</body>
</html>
<?php database_mysql_close( $dbh ) ; ?>
