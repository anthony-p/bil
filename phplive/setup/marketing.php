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

	include_once( "../API/Vars/get.php" ) ;

	$error = "" ;
	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;

	if ( $action == "submit" )
	{
		include_once( "../API/Vars/put.php" ) ;

		$sm_fb = Util_Format_Sanatize( Util_Format_GetVar( "sm_fb" ), "ln" ) ;
		$sm_tw = Util_Format_Sanatize( Util_Format_GetVar( "sm_tw" ), "ln" ) ;
		$sm_yt = Util_Format_Sanatize( Util_Format_GetVar( "sm_yt" ), "ln" ) ;
		$sm_li = Util_Format_Sanatize( Util_Format_GetVar( "sm_li" ), "ln" ) ;

		$sm_fb_tip = Util_Format_Sanatize( Util_Format_GetVar( "sm_fb_tip" ), "ln" ) ;
		$sm_tw_tip = Util_Format_Sanatize( Util_Format_GetVar( "sm_tw_tip" ), "ln" ) ;
		$sm_yt_tip = Util_Format_Sanatize( Util_Format_GetVar( "sm_yt_tip" ), "ln" ) ;
		$sm_li_tip = Util_Format_Sanatize( Util_Format_GetVar( "sm_li_tip" ), "ln" ) ;

		$sm_fb_url = Util_Format_Sanatize( Util_Format_GetVar( "sm_fb_url" ), "base_url" ) ;
		$sm_tw_url = Util_Format_Sanatize( Util_Format_GetVar( "sm_tw_url" ), "base_url" ) ;
		$sm_yt_url = Util_Format_Sanatize( Util_Format_GetVar( "sm_yt_url" ), "base_url" ) ;
		$sm_li_url = Util_Format_Sanatize( Util_Format_GetVar( "sm_li_url" ), "base_url" ) ;

		$sm_fb_string = serialize( Array( "status" => "$sm_fb", "tooltip" => "$sm_fb_tip", "url" => "$sm_fb_url" ) ) ;
		$sm_tw_string = serialize( Array( "status" => "$sm_tw", "tooltip" => "$sm_tw_tip", "url" => "$sm_tw_url" ) ) ;
		$sm_yt_string = serialize( Array( "status" => "$sm_yt", "tooltip" => "$sm_yt_tip", "url" => "$sm_yt_url" ) ) ;
		$sm_li_string = serialize( Array( "status" => "$sm_li", "tooltip" => "$sm_li_tip", "url" => "$sm_li_url" ) ) ;

		Vars_put_Var( $dbh, "sm_fb", $sm_fb_string ) ;
		Vars_put_Var( $dbh, "sm_tw", $sm_tw_string ) ;
		Vars_put_Var( $dbh, "sm_yt", $sm_yt_string ) ;
		Vars_put_Var( $dbh, "sm_li", $sm_li_string ) ;
	}

	$vars = Vars_get_Vars( $dbh ) ;
	$sm_fb_array = $sm_tw_array = $sm_yt_array = $sm_li_array = Array() ;
	if ( isset( $vars["sm_fb"] ) )
	{
		$sm_fb_array = unserialize( $vars["sm_fb"] ) ;
		$sm_tw_array = unserialize( $vars["sm_tw"] ) ;
		$sm_yt_array = unserialize( $vars["sm_yt"] ) ;
		$sm_li_array = unserialize( $vars["sm_li"] ) ;
	}
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

<script type="text/javascript">
<!--
	var mouse_x ; var mouse_y ;
	var sms = Array( "fb", "tw", "yt", "li" ) ;

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		}) ;

		$('#body_sub_title').html( "<img src=\"../pics/icons/graph.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" style=\"margin-right: 5px;\"> Marketing" ) ;

		init_menu() ;
		toggle_menu_setup( "marketing" ) ;

		<?php if ( $action && !$error ): ?>do_alert( 1, "Update Success!" ) ;<?php endif ; ?>
	});

	function tcolor_focus( thediv )
	{
		$( '*', 'body' ).each( function(){
			var div_name = $(this).attr('id') ;
			if ( div_name.indexOf( "tcolor_li_" ) != -1 )
				$(this).css( { "border": "1px solid #C2C2C2" } ) ;
		} );

		if ( thediv != undefined )
		{
			$( "#color" ).val( thediv ) ;
			$( "#tcolor_li_"+thediv ).css( { "border": "1px solid #444444" } ) ;
		}
	}

	function do_edit( themarketid, theskey, thename, thecolor )
	{
		$( "input#marketid" ).val( themarketid ) ;
		$( "input#skey" ).val( theskey ) ;
		$( "input#name" ).val( thename ) ;
		tcolor_focus( thecolor ) ;
		location.href = "#a_edit" ;
	}

	function do_delete( themarketid )
	{
		if ( confirm( "Delete this campaign?  All data will be lost." ) )
			location.href = "marketing.php?ses=<?php echo $ses ?>&action=delete&marketid="+themarketid ;
	}

	function do_submit()
	{
		var execute = 1 ;
		for ( c = 0; c < sms.length; ++c )
		{
			if ( !check_sm_vals( sms[c] ) )
			{
				execute = 0 ;
				break ;
			}
		}

		if ( execute )
			$('#theform').submit() ;
	}

	function check_sm_vals( thesm )
	{
		var enabled = $('#sm_'+thesm).is(':checked') ;
		var tooltip = $('#sm_'+thesm+'_tip').val() ;
		var url = $('#sm_'+thesm+'_url').val() ;
		var url_ok = ( url.match( /(http:\/\/)|(https:\/\/)/i ) ) ? 1 : 0 ;

		if ( enabled )
		{
			if ( !tooltip || !url )
			{
				$('#sm_'+thesm).attr('checked', false) ;
				do_alert( 0, "Please provide the Tooltip and the Social Media URL." ) ;
				return false ;
			}
			else if ( !url_ok )
			{
				$('#sm_'+thesm).attr('checked', false) ;
				do_alert( 0, "URL should begin with http:// or https:// for correct loading." ) ;
				return false ;
			}
		}
		
		return true ;
	}

	function launch_sm( thesm )
	{
		var unique = unixtime() ;
		var url = $('#sm_'+thesm+'_url').val() ;
		var url_ok = ( url.match( /(http:\/\/)|(https:\/\/)/i ) ) ? 1 : 0 ;

		if ( !url )
			do_alert( 0, "Please provide the Social Media URL." ) ;
		else if ( !url_ok )
			do_alert( 0, "URL should begin with http:// or https:// for correct loading." ) ;
		else
			window.open(url, unique, 'scrollbars=yes,menubar=yes,resizable=1,location=yes,toolbar=yes,status=1') ;
	}

//-->
</script>
</head>
<body>

<?php include_once( "./inc_header.php" ) ?>

		<div class="op_submenu_wrapper">
			<div class="op_submenu_focus">Social Media</div>
			<div class="op_submenu" onClick="location.href='marketing_click.php?ses=<?php echo $ses ?>'">Click Tracking</div>
			<div class="op_submenu" onClick="location.href='reports_marketing.php?ses=<?php echo $ses ?>'">Report: Clicks</div>
			<div class="op_submenu" onClick="location.href='marketing_marquee.php?ses=<?php echo $ses ?>'">Chat Footer Marquee</div>
			<!-- <div class="op_submenu" onClick="location.href='marketing_initiate.php?ses=<?php echo $ses ?>'">Auto Initiate</div> -->
			<div style="clear: both"></div>
		</div>

		<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr><td class="t_tl"></td><td class="t_tm"></td><td class="t_tr"></td></tr>
		<tr>
			<td class="t_ml"></td><td class="t_mm">
				<div class="info_box">
					<div style="">Social media presence on the web.  The social media icons will be visible on the visitor chat window for convenient access to your company profiles.</div>
				</div>

				<div style="margin-top: 15px;">
					<form action="marketing.php?submit" method="POST" id="theform">
					<input type="hidden" name="action" value="submit">
					<input type="hidden" name="ses" value="<?php echo $ses ?>">
					<table cellspacing=1 cellpadding=0 border=0 width="100%">
					<tr>
						<td width="15"><div id="td_dept_header">Enable</td></td>
						<td width="100"><div id="td_dept_header">&nbsp;</td></td>
						<td><div id="td_dept_header">Tooltip (example: Follow us on Twitter!)</td></td>
						<td><div id="td_dept_header">Social Media URL</td></td>
					</tr>
					<tr>
						<td class="td_dept_td"><input type="checkbox" class="input_text" name="sm_fb" id="sm_fb" value="1" onClick="check_sm_vals('fb')" <?php echo ( isset( $sm_fb_array["status"] ) && $sm_fb_array["status"] ) ? "checked" : "" ; ?>></td>
						<td class="td_dept_td" nowrap><img src="../pics/icons/social/facebook.png" width="16" height="16" border="0" alt=""> Facebook</td>
						<td class="td_dept_td"><input type="text" class="input_text" size="40" maxlength="100" name="sm_fb_tip" id="sm_fb_tip" onKeyPress="return noquotes(event)" value="<?php echo ( isset( $sm_fb_array["tooltip"] ) && $sm_fb_array["tooltip"] ) ? $sm_fb_array["tooltip"] : "" ; ?>"></td>
						<td class="td_dept_td"><input type="text" class="input_text" size="60" maxlength="255" name="sm_fb_url" id="sm_fb_url" value="<?php echo ( isset( $sm_fb_array["url"] ) && $sm_fb_array["url"] ) ? $sm_fb_array["url"] : "" ; ?>"> &nbsp; <span style="font-size: 10px;">&middot; <a href="JavaScript:void(0)" onClick="launch_sm('fb')">visit</a></span></td>
					</tr>
					<tr>
						<td class="td_dept_td"><input type="checkbox" class="input_text" name="sm_tw" id="sm_tw" value="1" onClick="check_sm_vals('tw')" <?php echo ( isset( $sm_tw_array["status"] ) && $sm_tw_array["status"] ) ? "checked" : "" ; ?>></td>
						<td class="td_dept_td" nowrap><img src="../pics/icons/social/twitter.png" width="16" height="16" border="0" alt=""> Twitter</td>
						<td class="td_dept_td"><input type="text" class="input_text" size="40" maxlength="100" name="sm_tw_tip" id="sm_tw_tip" onKeyPress="return noquotes(event)" value="<?php echo ( isset( $sm_tw_array["tooltip"] ) && $sm_tw_array["tooltip"] ) ? $sm_tw_array["tooltip"] : "" ; ?>"></td>
						<td class="td_dept_td"><input type="text" class="input_text" size="60" maxlength="255" name="sm_tw_url" id="sm_tw_url" value="<?php echo ( isset( $sm_tw_array["url"] ) && $sm_tw_array["url"] ) ? $sm_tw_array["url"] : "" ; ?>"> &nbsp; <span style="font-size: 10px;">&middot; <a href="JavaScript:void(0)" onClick="launch_sm('tw')">visit</a></span></td>
					</tr>
					<tr>
						<td class="td_dept_td"><input type="checkbox" class="input_text" name="sm_yt" id="sm_yt" value="1" onClick="check_sm_vals('yt')" <?php echo ( isset( $sm_yt_array["status"] ) && $sm_yt_array["status"] ) ? "checked" : "" ; ?>></td>
						<td class="td_dept_td" nowrap><img src="../pics/icons/social/youtube.png" width="16" height="16" border="0" alt=""> YouTube</td>
						<td class="td_dept_td"><input type="text" class="input_text" size="40" maxlength="100" name="sm_yt_tip" id="sm_yt_tip" onKeyPress="return noquotes(event)" value="<?php echo ( isset( $sm_yt_array["tooltip"] ) && $sm_yt_array["tooltip"] ) ? $sm_yt_array["tooltip"] : "" ; ?>"></td>
						<td class="td_dept_td"><input type="text" class="input_text" size="60" maxlength="255" name="sm_yt_url" id="sm_yt_url" value="<?php echo ( isset( $sm_yt_array["url"] ) && $sm_yt_array["url"] ) ? $sm_yt_array["url"] : "" ; ?>"> &nbsp; <span style="font-size: 10px;">&middot; <a href="JavaScript:void(0)" onClick="launch_sm('yt')">visit</a></span></td>
					</tr>
					<tr>
						<td class="td_dept_td"><input type="checkbox" class="input_text" name="sm_li" id="sm_li" value="1" onClick="check_sm_vals('li')" <?php echo ( isset( $sm_li_array["status"] ) && $sm_li_array["status"] ) ? "checked" : "" ; ?>></td>
						<td class="td_dept_td" nowrap><img src="../pics/icons/social/linkedin.png" width="16" height="16" border="0" alt=""> LinkedIn</td>
						<td class="td_dept_td"><input type="text" class="input_text" size="40" maxlength="100" name="sm_li_tip" id="sm_li_tip" onKeyPress="return noquotes(event)" value="<?php echo ( isset( $sm_li_array["tooltip"] ) && $sm_li_array["tooltip"] ) ? $sm_li_array["tooltip"] : "" ; ?>"></td>
						<td class="td_dept_td"><input type="text" class="input_text" size="60" maxlength="255" name="sm_li_url" id="sm_li_url" value="<?php echo ( isset( $sm_li_array["url"] ) && $sm_li_array["url"] ) ? $sm_li_array["url"] : "" ; ?>"> &nbsp; <span style="font-size: 10px;">&middot; <a href="JavaScript:void(0)" onClick="launch_sm('li')">visit</a></span></td>
					</tr>
					<tr>
						<td class="td_dept_td" colspan="3"><input type="button" value="Update" onClick="do_submit()"></td>
					</tr>
					</table>
					</form>
				</div>
			</td><td class="t_mr"></td>
		</tr>
		<tr><td class="t_bl"></td><td class="t_bm"></td><td class="t_br"></td></tr>
		</table>

<?php include_once( "./inc_footer.php" ) ?>

</body>
</html>
