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

	$error = "" ;

	include_once( "../API/Util_Vals.php" ) ;
	include_once( "../API/Util_Upload.php" ) ;
	include_once( "../API/Depts/get.php" ) ;

	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;
	$jump = ( Util_Format_Sanatize( Util_Format_GetVar( "jump" ), "ln" ) ) ? Util_Format_Sanatize( Util_Format_GetVar( "jump" ), "ln" ) : "logo" ;
	$deptid = Util_Format_Sanatize( Util_Format_GetVar( "deptid" ), "ln" ) ;

	$error = "" ;

	$deptinfo = Array() ;
	$upload_dir = realpath( "$CONF[DOCUMENT_ROOT]/web" ) ;

	if ( $action == "update" )
	{
		if ( $jump == "eips" )
		{
			$ip1 = Util_Format_Sanatize( Util_Format_GetVar( "ip1" ), "ln" ) ;
			$ip2 = Util_Format_Sanatize( Util_Format_GetVar( "ip2" ), "ln" ) ;
			$ip3 = Util_Format_Sanatize( Util_Format_GetVar( "ip3" ), "ln" ) ;
			$ip4 = Util_Format_Sanatize( Util_Format_GetVar( "ip4" ), "ln" ) ;
			$ip = "$ip1.$ip2.$ip3.$ip4" ;

			if ( !preg_match( "/$ip/", $VALS["TRAFFIC_EXCLUDE_IPS"] ) && ( $ip1 != "" ) && ( $ip2 != "" ) && ( $ip3 != "" ) && ( $ip4 != "" ) )
			{
				$val = preg_replace( "/  +/", " ", $VALS["TRAFFIC_EXCLUDE_IPS"] ) . " $ip " ;
				$error = Util_Vals_WriteToFile( "TRAFFIC_EXCLUDE_IPS", $val ) ;
			}
		}
		else if ( $jump == "logo" )
			$error = Util_Upload_File( "logo", $deptid ) ;
		else if ( $jump == "themes" )
		{
			$theme = Util_Format_Sanatize( Util_Format_GetVar( "theme" ), "ln" ) ;

			$error = Util_Vals_WriteToConfFile( "THEME", $theme ) ;
			$CONF["THEME"] = $theme ;
		}
		else if ( $jump == "langs" )
		{
			$lang = Util_Format_Sanatize( Util_Format_GetVar( "lang" ), "ln" ) ;

			$error = Util_Vals_WriteToConfFile( "lang", $lang ) ;
			$CONF["lang"] = $lang ;
		}
		else if ( $jump == "time" )
		{
			$timezone = Util_Format_Sanatize( Util_Format_GetVar( "timezone" ), "ln" ) ;

			$error = Util_Vals_WriteToConfFile( "TIMEZONE", $timezone ) ;
			if ( phpversion() >= "5.1.0" ){ date_default_timezone_set( $timezone ) ; }
		}
	}

	$departments = Depts_get_AllDepts( $dbh ) ;
	if ( $deptid )
		$deptinfo = Depts_get_DeptInfo( $dbh, $deptid ) ;

	$timezones = Util_Vals_Timezones() ;
?>
<?php include_once( "../inc_doctype.php" ) ?>
<head>
<title> PHP Live! Support <?php echo $VERSION ?> </title>

<meta name="description" content="PHP Live! Support <?php echo $VERSION ?>">
<meta name="keywords" content="PHP Live! Support">
<meta name="robots" content="all,index,follow">
<meta http-equiv="content-type" content="text/html; CHARSET=utf-8"> 

<link rel="Stylesheet" href="../css/base_setup.css">
<script type="text/javascript" src="../js/global.js"></script>
<script type="text/javascript" src="../js/setup.js"></script>
<script type="text/javascript" src="../js/framework.js"></script>
<script type="text/javascript" src="../js/framework_ext.js"></script>
<script type="text/javascript" src="../js/tooltip.js"></script>

<script type="text/javascript">
<!--
	var mouse_x ; var mouse_y ;
	var theme = "<?php echo $CONF["THEME"] ?>" ;
	var lang = "<?php echo $CONF["lang"] ?>" ;

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		}) ;

		$('#body_sub_title').html( "<img src=\"../pics/icons/chest.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" style=\"margin-right: 5px;\"> Settings" ) ;

		init_menu() ;
		toggle_menu_setup( "settings" ) ;

		fetch_eips() ;
		fetch_sips() ;
		show_div( "<?php echo $jump ?>" ) ;

		<?php if ( $action && !$error ): ?>do_alert( 1, "Update Success!" ) ;<?php endif ; ?>
		<?php if ( $action && $error ): ?>do_alert( 0, "<?php echo $error ?>" ) ;<?php endif ; ?>
	});

	function fetch_eips()
	{
		$.ajax({
			type: "GET",
			url: "../ajax/setup_actions.php",
			data: "ses=<?php echo $ses ?>&action=eips&"+unixtime(),
			success: function(data){
				print_eips( data ) ;
			}
		});
	}

	function fetch_sips()
	{
		$.ajax({
			type: "GET",
			url: "../ajax/setup_actions.php",
			data: "ses=<?php echo $ses ?>&action=sips&"+unixtime(),
			success: function(data){
				print_sips( data ) ;
			}
		});
	}

	function print_eips( thedata )
	{
		eval( thedata ) ;

		if ( json_data.ips != undefined )
		{
			var ip_string = "<table cellspacing=1 cellpadding=0 border=0 width=\"100%\">" ;
			for ( c = 0; c < json_data.ips.length; ++c )
			{
				var ip = json_data.ips[c]["ip"] ;
				var ip_ = ip.replace( /\./g, "" ) ;

				ip_string += "<tr><td class=\"td_dept_td\" width=\"14\"><div id=\"eip_"+ip_+"\"><a href=\"JavaScript:void(0)\" onClick=\"remove_eip( '"+ip+"' )\"><img src=\"../pics/icons/delete.png\" width=\"14\" height=\"14\" border=\"0\" alt=\"\"></a></div></td><td class=\"td_dept_td\">"+ip+"</td></tr>" ;
			}
			if ( !c )
				ip_string += "<tr><td class=\"td_dept_td\">No excluded IPs currently.</td></tr>" ;
		}
		ip_string += "</table>" ;
		$('#eips').html( ip_string ) ;
	}

	function print_sips( thedata )
	{
		eval( thedata ) ;

		if ( json_data.ips != undefined )
		{
			var ip_string = "<table cellspacing=1 cellpadding=0 border=0 width=\"100%\">" ;
			for ( c = 0; c < json_data.ips.length; ++c )
			{
				var ip = json_data.ips[c]["ip"] ;
				var ip_ = ip.replace( /\./g, "" ) ;

				ip_string += "<tr><td class=\"td_dept_td\" width=\"14\"><div id=\"sip_"+ip_+"\"><a href=\"JavaScript:void(0)\" onClick=\"remove_sip( '"+ip+"' )\"><img src=\"../pics/icons/delete.png\" width=\"14\" height=\"14\" border=\"0\" alt=\"\"></a></div></td><td class=\"td_dept_td\">"+ip+"</td></tr>" ;
			}
			if ( !c )
				ip_string += "<tr><td class=\"td_dept_td\">No spam IPs currently.</td></tr>" ;
		}
		ip_string += "</table>" ;
		$('#sips').html( ip_string ) ;
	}

	function add_eip()
	{
		var ip1 = $('#ip1').val() ; var ip2 = $('#ip2').val() ; var ip3 = $('#ip3').val() ; var ip4 = $('#ip4').val() ;

		if ( !ip1 || !ip2 || !ip3 || !ip4 )
			do_alert( 0, "Blank IP field is invalid." ) ;
		else
		{
			var ip = ip1+"."+ip2+"."+ip3+"."+ip4 ;

			$.ajax({
				type: "GET",
				url: "../ajax/setup_actions.php",
				data: "ses=<?php echo $ses ?>&action=add_eip&ip="+ip+"&"+unixtime(),
				success: function(data){
					eval(data) ;
					if ( json_data.status )
					{
						$('#ip1').val('') ; $('#ip2').val('') ; $('#ip3').val('') ; $('#ip4').val('') ;
						fetch_eips() ;
					}
					else
						do_alert( 0, "IP ("+ip+") already excluded." ) ;
				}
			});
		}
	}

	function remove_eip( theip )
	{
		var theip_ = theip.replace( /\./g, "" ) ;
		$('#eip_'+theip_).html( "<img src=\"../pics/loading_ci.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"\">" ) ;

		$.ajax({
			type: "GET",
			url: "../ajax/setup_actions.php",
			data: "ses=<?php echo $ses ?>&action=remove_eip&ip="+theip+"&"+unixtime(),
			success: function(data){
				print_eips( data ) ;
			}
		});
	}

	function remove_sip( theip )
	{
		var theip_ = theip.replace( /\./g, "" ) ;
		$('#sip_'+theip_).html( "<img src=\"../pics/loading_ci.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"\">" ) ;

		$.ajax({
			type: "GET",
			url: "../ajax/setup_actions.php",
			data: "ses=<?php echo $ses ?>&action=remove_sip&ip="+theip+"&"+unixtime(),
			success: function(data){
				print_sips( data ) ;
			}
		});
	}

	function show_div( thediv )
	{
		var divs = Array( "logo", "themes", "time", "langs", "eips", "sips", "profile" ) ;
		for ( c = 0; c < divs.length; ++c )
		{
			$('#settings_'+divs[c]).hide() ;
			$('#menu_'+divs[c]).removeClass('op_submenu_focus').addClass('op_submenu') ;
		}

		$('input#jump').val( thediv ) ;
		$('#settings_'+thediv).show() ;
		$('#menu_'+thediv).removeClass('op_submenu').addClass('op_submenu_focus') ;
	}

	function confirm_theme( thetheme )
	{
		if ( confirm( "Use the theme: "+thetheme+"?" ) )
			update_theme( thetheme ) ;
		else
			$('#theme_<?php echo $CONF["THEME"] ?>').attr('checked', true) ;
	}

	function update_theme( thetheme )
	{
		location.href = 'settings.php?ses=<?php echo $ses ?>&action=update&jump=themes&theme='+thetheme ;
	}

	function switch_dept( theobject )
	{
		location.href = "settings.php?ses=<?php echo $ses ?>&deptid="+theobject.value ;
	}

	function update_timezone()
	{
		var timezone = $('#timezone').val() ;
		location.href = "settings.php?ses=<?php echo $ses ?>&action=update&jump=time&timezone="+timezone ;
	}

	function confirm_theme( thetheme )
	{
		if ( theme != thetheme )
		{
			if ( confirm( "Use the theme: "+thetheme+"?" ) )
				update_theme( thetheme ) ;
			else
				$('#theme_<?php echo $CONF["THEME"] ?>').attr('checked', true) ;
		}
	}

	function update_theme( thetheme )
	{
		location.href = 'settings.php?ses=<?php echo $ses ?>&action=update&jump=themes&theme='+thetheme ;
	}

	function confirm_lang( thelang )
	{
		if ( lang != thelang )
		{
			if ( confirm( "Use language: "+thelang+"?" ) )
				update_lang( thelang ) ;
			else
				$('#lang_<?php echo $CONF["lang"] ?>').attr('checked', true) ;
		}
	}

	function update_lang( thelang )
	{
		location.href = 'settings.php?ses=<?php echo $ses ?>&action=update&jump=langs&lang='+thelang ;
	}

	function update_profile()
	{
		execute = 1 ;
		var inputs = Array( "email", "login" ) ;

		for ( c = 0; c < inputs.length; ++c )
		{
			if ( $('#'+inputs[c]).val() == "" ){ $('#status_'+inputs[c]).empty().html( "&nbsp; <img src=\"../pics/icons/alert.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" class=\"help_tooltip\" title=\"- Please provide a value.\">" ) ; execute = 0 ; }
			else { $('#status_'+inputs[c]).empty().html( "&nbsp; <img src=\"../pics/icons/alert_good.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\">" ) ; }
		}

		// check email
		if ( $('#email').val().indexOf( "@" ) == -1 ){ $('#status_email').empty().html( "&nbsp; <img src=\"../pics/icons/alert.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" class=\"help_tooltip\" title=\"- Email format is invalid.<br>(example: you@somewhere.com)\">" ) ; execute = 0 ; }

		// check new passwords
		if ( $('#npassword').val() || $('#vpassword').val() )
		{
			if ( $('#npassword').val() != $('#vpassword').val() ){ $('#status_npassword').empty().html( "&nbsp; <img src=\"../pics/icons/alert.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" class=\"help_tooltip\" title=\"- New Password and Verify Password do not match.\">" ) ; $('#status_vpassword').empty().html( "&nbsp; <img src=\"../pics/icons/alert.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" class=\"help_tooltip\" title=\"- New Password and Verify Password do not match.\">" ) ; execute = 0 ; }
		}
		else
		{
			$('#status_npassword').empty() ;
			$('#status_vpassword').empty() ;
		}

		init_tooltips() ;
		if ( execute ){ update_profile_doit() ; } ;
	}

	function update_profile_doit()
	{
		var json_data = new Object ;
		var unique = unixtime() ;

		var email = $('#email').val() ;
		var login = $('#login').val() ;
		var npassword = $('#npassword').val() ;
		var vpassword = $('#vpassword').val() ;

		$.ajax({
		type: "GET",
		url: "../ajax/setup_actions.php",
		data: "action=update_profile&ses=<?php echo $ses ?>&email="+email+"&login="+login+"&npassword="+npassword+"&vpassword="+vpassword+"&"+unique,
		success: function(data){
			eval( data ) ;
			if ( json_data.status )
			{
				$('#status_email').empty() ;
				$('#status_login').empty() ;
				$('#npassword').val('') ;
				$('#status_npassword').empty() ;
				$('#vpassword').val('') ;
				$('#status_vpassword').empty() ;
				do_alert( 1, "Update Success!" ) ;
			}
			else
				do_alert( 0, json_data.error ) ;

		},
		error:function (xhr, ajaxOptions, thrownError){
			do_alert( 0, "Connection to server was lost.  Please reload the page." ) ;
		} });
	}

	function init_tooltips()
	{
		var help_tooltips = $('body').find( '.help_tooltip' ) ;
		help_tooltips.tooltip({
			event: "mouseover",
			track: true,
			delay: 0,
			showURL: false,
			showBody: "- ",
			fade: 0,
			extraClass: "stat"
		});
	}

//-->
</script>
</head>
<body>

<?php include_once( "./inc_header.php" ) ?>

		<div class="op_submenu_wrapper">
			<div style="position: relative; float: left; padding: 5px;" class="op_submenu" onClick="show_div('logo')" id="menu_logo">Logo</div>
			<div style="position: relative; float: left; padding: 5px;" class="op_submenu" onClick="show_div('themes')" id="menu_themes">Themes</div>
			<?php if ( phpversion() >= "5.1.0" ): ?>
			<div style="position: relative; float: left; padding: 5px;" class="op_submenu" onClick="show_div('time')" id="menu_time">Time Zone</div>
			<?php endif; ?>
			<div style="position: relative; float: left; padding: 5px;" class="op_submenu" onClick="show_div('langs')" id="menu_langs">Languages</div>
			<div style="position: relative; float: left; padding: 5px;" class="op_submenu" onClick="show_div('eips')" id="menu_eips">Excluded IPs</div>
			<div style="position: relative; float: left; padding: 5px;" class="op_submenu" onClick="show_div('sips')" id="menu_sips">Spam IPs</div>
			<div style="position: relative; float: left; padding: 5px;" class="op_submenu" onClick="show_div('profile')" id="menu_profile">Setup Profile</div>
			<div style="clear: both"></div>
		</div>

		<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr><td class="t_tl"></td><td class="t_tm"></td><td class="t_tr"></td></tr>
		<tr>
			<td class="t_ml"></td><td class="t_mm">
				<form method="POST" action="settings.php?submit" enctype="multipart/form-data">
				<input type="hidden" name="action" value="update">
				<input type="hidden" name="jump" id="jump" value="">
				<input type="hidden" name="ses" value="<?php echo $ses ?>">

				<div style="display: none;" id="settings_logo">
					<div class="info_box"><img src="../pics/icons/info.png" width="16" height="16" border="0" alt=""> Each department can have it's own logo for additional customazation.  NOTE: The <b>max height</b> of the logo should not exceed 45 pixels for proper chat window formatting.

						<div style="margin-top: 15px">
							<select name="deptid" id="deptid" style="font-size: 16px; background: #D4FFD4; color: #009000;" OnChange="switch_dept( this )">
								<option value="0">Global Default</option>
								<?php
									for ( $c = 0; $c < count( $departments ); ++$c )
									{
										$department = $departments[$c] ;
										if ( $department["name"] != "Archive" )
										{
											$selected = ( $deptid == $department["deptID"] ) ? "selected" : "" ;
											print "<option value=\"$department[deptID]\" $selected>$department[name]</option>" ;
										}
									}
								?>
							</select>
						</div>
					</div>

					<table cellspacing=0 cellpadding=0 border=0 width="100%" class="edit_wrapper" style="margin-top: 20px;">
					<tr>
						<td valign="top">
							<?php if ( isset( $deptinfo["deptID"] ) && !$deptinfo["visible"] ): ?>
							<?php echo $deptinfo["name"] ?> Department is <a href="depts.php?ses=<?php echo $ses ?>">not visible</a> to the public.  Department Logo not available.

							<?php else: ?>
							<div class="edit_title"><?php echo ( isset( $deptinfo["name"] ) ) ? $deptinfo["name"] : "Global Default" ; ?> LOGO</div>
							<div style="margin-top: 10px;">
								<input type="file" name="logo" size="30"><p>
								<input type="submit" value="Upload Image" style="margin-top: 10px;">
							</div>
							
							<div style="margin-top: 15px; font-size: 10px;">To ensure proper chat window formatting, logo should not exceed 500 pixels in width and 45 pixels in height.</div>
							<div style="margin-top: 5px; background: url( <?php print Util_Upload_GetLogo( "..", $deptid ) ?> ) no-repeat; height: 45px; width: 500px;">&nbsp;</div>
							
							<?php endif; ?>
						</td>
					</tr>
					</table>
				</div>

				<div style="display: none;" id="settings_themes">
					<div class="info_box"><img src="../pics/icons/info.png" width="16" height="16" border="0" alt=""> Click on the theme name to preview the theme, select the radio button to choose.</div>
					<table cellspacing=0 cellpadding=2 border=0 width="100%" style="margin-top: 15px;">
					<tr>
						<td>
							<?php
								$dir_themes = opendir( realpath( "$CONF[DOCUMENT_ROOT]/themes/" ) ) ;

								$themes = Array() ;
								while ( $this_theme = readdir( $dir_themes ) )
									$themes[] = $this_theme ;
								closedir( $dir_themes ) ;

								for ( $c = 0; $c < count( $themes ); ++$c )
								{
									$this_theme = $themes[$c] ;

									$checked = "" ;
									if ( $CONF["THEME"] == $this_theme )
										$checked = "checked" ;

									if ( preg_match( "/[a-z]/i", $this_theme ) && ( $this_theme != "initiate" ) )
										print "<div class=\"op_theme_cell\"><input type=\"radio\" name=\"theme\" id=\"theme_$this_theme\" value=\"$this_theme\" $checked onClick=\"confirm_theme('$this_theme')\"> <span style=\"cursor: pointer;\" onClick=\"preview_theme('$this_theme')\">$this_theme</span></div>" ;
								}
							?>
							<div style="clear: both;"></div>
						</td>
					</tr>
					</table>
				</div>

				<?php if ( phpversion() >= "5.1.0" ): ?>
				<div style="display: none;" id="settings_time">
					<div class="info_box"><img src="../pics/icons/info.png" width="16" height="16" border="0" alt=""> Adjust the timezone to correctly display the time to your current location.
					</div>
					<div style="margin-top: 15px;">
						<div style="margin-bottom: 20px; font-size: 38px; font-weight: bold; color: #66C1E9; font-family: sans-serif;"><?php echo date( "M j, Y (g:i:s a)", time() ) ; ?></div>
						<select id="timezone">
						<?php
							for ( $c = 0; $c < count( $timezones ); ++$c )
							{
								$selected = "" ;
								if ( $timezones[$c] == date_default_timezone_get() )
									$selected = "selected" ;

								print "<option value=\"$timezones[$c]\" $selected>$timezones[$c]</option>" ;
							}
						?>
						</select>
						
						<div style="margin-top: 15px;"><button type="button" onClick="update_timezone()">Update</button></div>
					</div>
				</div>
				<?php endif; ?>

				<div style="display: none;" id="settings_langs">
					<div class="info_box"><img src="../pics/icons/info.png" width="16" height="16" border="0" alt=""> Select the language of the visitor chat window (this only effects the visitor chat session).</div>
					<table cellspacing=0 cellpadding=2 border=0 width="100%" style="margin-top: 15px;">
					<tr>
						<td>
							<?php
								$dir_langs = opendir( realpath( "$CONF[DOCUMENT_ROOT]/lang_packs/" ) ) ;

								$langs = Array() ;
								while ( $this_lang = readdir( $dir_langs ) )
									$langs[] = $this_lang ;
								closedir( $dir_langs ) ;

								for ( $c = 0; $c < count( $langs ); ++$c )
								{
									$this_lang = preg_replace( "/.php/", "", $langs[$c] ) ;

									$checked = "" ;
									if ( $CONF["lang"] == $this_lang )
										$checked = "checked" ;

									if ( preg_match( "/[a-z]/i", $this_lang ) )
										print "<div class=\"op_theme_cell\"><input type=\"radio\" name=\"lang\" id=\"lang_$this_lang\" value=\"$this_lang\" $checked onClick=\"confirm_lang('$this_lang')\"> $this_lang</div>" ;
								}
							?>
							<div style="clear: both;"></div>
						</td>
					</tr>
					</table>
				</div>

				<div style="display: none;" id="settings_eips">
					<div class="info_box"><img src="../pics/icons/info.png" width="16" height="16" border="0" alt=""> To avoid misleading visitor page view and footprint tracking stats, you can exclude IPs from the tracking system.

						<ul style="margin-top: 10px;">
							<li> All IPs that are excluded WILL NOT be stored or tracked for the total page views and footprint data.
							<li> This is useful when you are developing your site and need to refresh your pages quite often.
							<li> Also useful to exclude your internal company visits from that of actual visitors.
						</ul>
					</div>
					<div style="margin-top: 15px;"><div id="td_dept_header">Current Excluded IPs:</div></div>
					<div id="eips"></div>
					<div style="margin-top: 15px;">
						<div>
							<p>Your current IP: <span class="txt_orange"><?php echo $_SERVER['REMOTE_ADDR'] ?></span>
						</div>
						<div style="margin-top: 15px;"><input type="text" name="ip1" id="ip1" size=3 maxlength=3 style="width:30px;" onKeyPress="return numbersonly(event)"> . <input type="text" name="ip2" id="ip2" size=3 maxlength=3 style="width:30px;" onKeyPress="return numbersonly(event)"> . <input type="text" name="ip3"  id="ip3" size=3 maxlength=3 style="width:30px;" onKeyPress="return numbersonly(event)"> . <input type="text" name="ip4" id="ip4" size=3 maxlength=3 style="width:30px;" onKeyPress="return numbersonly(event)"></div>
						<div style="margin-top: 10px;"><input type="submit" onClick="add_eip()" value="Add IP"></div>
					</div>
				</div>

				<div style="display: none;" id="settings_sips">
					<div class="info_box"><img src="../pics/icons/info.png" width="16" height="16" border="0" alt=""> Below are the current reported Spam IPs by the operators.  Visitor who's IP matches one of the below will always see an OFFLINE status icon and will not be able to reach an operator, even if an operator is online.
					</div>
					<div style="margin-top: 15px;"><div id="td_dept_header">Current Spam IPs:</div></div>
					<div id="sips"></div>
				</div>

				<div style="display: none;" id="settings_profile">
					<div class="info_box"><img src="../pics/icons/info.png" width="16" height="16" border="0" alt=""> Update your setup profile.</div>
					<div style="margin-top: 15px;">
						<input type="hidden" name="login" id="login" value="<?php echo $admininfo["login"] ?>">
						<table cellspacing=0 cellpadding=4 border=0>
						<tr>
							<td class="td_dept_td" width="120">Your Email</td>
							<td class="td_dept_td"><input type="text" class="input" size="35" maxlength="50" name="email" id="email" value="<?php echo $admininfo["email"] ?>" onKeyPress="return justemails(event)" value=""> <span id="status_email"></span></td>
						</tr>
						<tr>
							<td colspan="4" class="info_neutral">
								<table cellspacing=0 cellpadding=4 border=0>
								<tr><td colspan="4" class="td_dept_td"><div style="font-size: 14px; font-weight: bold;">Update Password (optional)</div> </td></tr>
								<tr> 
									<td class="td_dept_td" width="120">New Password</td> 
									<td class="td_dept_td"><input type="password" class="input" size="35" maxlength="32" id="npassword" onKeyPress="return nospecials(event)"> <span id="status_npassword"></span></td> 
								</tr>
								<tr>
									<td class="td_dept_td" width="120">Verify Password</td> 
									<td class="td_dept_td"><input type="password" class="input" size="35" maxlength="32" id="vpassword" onKeyPress="return nospecials(event)"> <span id="status_vpassword"></span></td> 
								</tr>
								</table>
							</td>
						</tr>
						<tr> 
							<td></td> 
							<td class="td_dept_td"><input type="button" value="Update Profile" id="btn_submit" onClick="update_profile()"></td> 
						</tr> 
						</table>
					</div>
				</div>
				</form>
			</td><td class="t_mr"></td>
		</tr>
		<tr><td class="t_bl"></td><td class="t_bm"></td><td class="t_br"></td></tr>
		</table>

<?php include_once( "./inc_footer.php" ) ?>
</body>
</html>
