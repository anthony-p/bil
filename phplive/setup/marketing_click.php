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

	include_once( "../API/Marketing/get.php" ) ;

	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;

	if ( $action == "submit" )
	{
		include_once( "../API/Marketing/put.php" ) ;

		$marketid = Util_Format_Sanatize( Util_Format_GetVar( "marketid" ), "ln" ) ;
		$skey = Util_Format_Sanatize( Util_Format_GetVar( "skey" ), "ln" ) ;
		$name = Util_Format_Sanatize( Util_Format_GetVar( "name" ), "ln" ) ;
		$color = Util_Format_Sanatize( Util_Format_GetVar( "color" ), "ln" ) ;

		if ( !$skey )
			$skey = Util_Format_RandomString(3);

		if ( !Marketing_put_Marketing( $dbh, $marketid, $skey, $name, $color ) )
		{
			$error = "$name is already in use." ;
		}
	}
	else if ( $action == "delete" )
	{
		include_once( "../API/Marketing/remove.php" ) ;

		$marketid = Util_Format_Sanatize( Util_Format_GetVar( "marketid" ), "ln" ) ;

		Marketing_remove_Marketing( $dbh, $marketid ) ;
	}

	$marketings = Marketing_get_AllMarketing( $dbh ) ;
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

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		}) ;

		$('#body_sub_title').html( "<img src=\"../pics/icons/graph.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" style=\"margin-right: 5px;\"> Marketing" ) ;

		init_menu() ;
		toggle_menu_setup( "marketing" ) ;
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
			location.href = "marketing_click.php?ses=<?php echo $ses ?>&action=delete&marketid="+themarketid ;
	}

	function do_submit()
	{
		var name = $( "#name" ).val() ;
		var color = $( "#color" ).val() ;

		if ( name == "" )
			do_alert( 0, "Please provide the Campaign Name." ) ;
		else if ( color == "" )
			do_alert( 0, "Please select the Indication Color." ) ;
		else
			$('#theform').submit() ;
	}

//-->
</script>
</head>
<body>

<?php include_once( "./inc_header.php" ) ?>

		<div class="op_submenu_wrapper">
			<div class="op_submenu" onClick="location.href='marketing.php?ses=<?php echo $ses ?>'">Social Media</div>
			<div class="op_submenu_focus">Click Tracking</div>
			<div class="op_submenu" onClick="location.href='reports_marketing.php?ses=<?php echo $ses ?>'">Report: Clicks</div>
			<div class="op_submenu" onClick="location.href='marketing_marquee.php?ses=<?php echo $ses ?>'">Chat Footer Marquee</div>
			<!-- <div class="op_submenu" onClick="location.href='marketing_initiate.php?ses=<?php echo $ses ?>'">Auto Initiate</div> -->
			<div style="clear: both"></div>
		</div>

		<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr><td class="t_tl"></td><td class="t_tm"></td><td class="t_tr"></td></tr>
		<tr>
			<td class="t_ml"></td><td class="t_mm">
				<div class="info_box">
					<div style=""><b>Click Tracking</b> allows the PHP Live! system to track visitors whom have arrived from an ad campaign.  The visitor tracking source will be displayed within the traffic monitor and visitor information area within the operator console.</div>
					<div style="margin-top: 10px;"><img src="../pics/icons/info.png" width="16" height="16" border="0" alt=""> Some ad programs (example: Google Ad Sense) does not allow redirection.  As an alternative method, simply append the <b>Query Key</b> to the link back URL of your ad campaign (example: http://www.yoursite.com/?<span class="txt_blue">&plk=the query key</span>)</div>
				</div>

				<div style="margin-top: 15px;">
					<table cellspacing=0 cellpadding=0 border=0 width="100%">
					<form>
					<tr>
						<td width="40">&nbsp;</td>
						<td width="200"><div id="td_dept_header">Name</div></td>
						<td><div id="td_dept_header">Query key to append to URL (example: http://www.yoursite.com/?<span class="txt_blue">&plk=the query key</span></div></td>
					</tr>
					<?php
						for ( $c = 0; $c < count( $marketings ); ++$c )
						{
							$marketing = $marketings[$c] ;
							$td1 = "td_dept_td" ; $td2 = "td_dept_td_td" ;
							if ( $c % 2 ) { $td1 = "td_dept_td2" ; $td2 = "td_dept_td_td2" ; }

							print "
								<tr>
									<td class=\"$td1\" nowrap><a href=\"JavaScript:void(0)\" onClick=\"do_delete($marketing[marketID])\" class=\"nounder\"><img src=\"../pics/icons/delete.png\" width=\"14\" height=\"14\" border=\"0\" alt=\"\"></a> &nbsp; <a href=\"JavaScript:void(0)\" onClick=\"do_edit( $marketing[marketID], '$marketing[skey]', '$marketing[name]', '$marketing[color]' );\" class=\"nounder\"><img src=\"../pics/icons/edit.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\"></a></td>
									<td class=\"$td1\" nowrap style=\"background: #$marketing[color];\">
										<div style=\"font-weight: bold;\">$marketing[name]</div>
									</td>
									<td class=\"$td1\"> <input type=\"text\" style=\"background: transparent; border: 1px solid transparent; font-weight: bold; color: #6E6E6E; width: 100%;\" size=\"80\" value=\"&plk=pi-$marketing[marketID]-$marketing[skey]-m\" readonly></td>
								</tr>
							" ;
						}
					?>
					</form>
					</table>
				</div>
			</td><td class="t_mr"></td>
		</tr>
		<tr><td class="t_bl"></td><td class="t_bm"></td><td class="t_br"></td></tr>
		</table>

		<div class="edit_wrapper" style="padding: 5px; padding-top: 25px;">
			<a name="a_edit"></a><div class="edit_title">Create/Edit Marketing Click Tracking <span class="txt_red"><?php echo $error ?></span></div>
			<div style="margin-top: 10px;">
				<table cellspacing=0 cellpadding=5 border=0>
				<form method="POST" action="marketing_click.php?submit" id="theform">
				<input type="hidden" name="action" value="submit">
				<input type="hidden" name="ses" value="<?php echo $ses ?>">
				<input type="hidden" name="marketid" id="marketid" value="0">
				<input type="hidden" name="skey" id="skey" value="">
				<input type="hidden" name="color" id="color" value="">
				<tr>
					<td>Campaign Name (example: "Google PPC")<br><input type="text" name="name" id="name" size="50" maxlength="40" value="" onKeyPress="return nospecials(event)"></td>
				</tr>
				<tr>
					<td>Select Indication Color (indicator color on the traffic monitor and operator chat)<br>
						<div id="tcolor_li_DDFFEE" style="float: left; cursor: pointer; width: 15px; height: 15px; margin-right: 3px; border: 1px solid #C2C2C2; background: #DDFFEE;" OnClick="tcolor_focus( 'DDFFEE' )"></div>
						<div id="tcolor_li_FFE07B" style="float: left; cursor: pointer; width: 15px; height: 15px; margin-right: 3px; border: 1px solid #C2C2C2; background: #FFE07B;" OnClick="tcolor_focus( 'FFE07B' )"></div>
						<div id="tcolor_li_A4C3E3" style="float: left; cursor: pointer; width: 15px; height: 15px; margin-right: 3px; border: 1px solid #C2C2C2; background: #A4C3E3;" OnClick="tcolor_focus( 'A4C3E3' )"></div>
						<div id="tcolor_li_FADADB" style="float: left; cursor: pointer; width: 15px; height: 15px; margin-right: 3px; border: 1px solid #C2C2C2; background: #FADADB;" OnClick="tcolor_focus( 'FADADB' )"></div>
						<div id="tcolor_li_FABEFF" style="float: left; cursor: pointer; width: 15px; height: 15px; margin-right: 3px; border: 1px solid #C2C2C2; background: #FABEFF;" OnClick="tcolor_focus( 'FABEFF' )"></div>
						<div id="tcolor_li_ABE3FA" style="float: left; cursor: pointer; width: 15px; height: 15px; margin-right: 3px; border: 1px solid #C2C2C2; background: #ABE3FA;" OnClick="tcolor_focus( 'ABE3FA' )"></div>
						<div id="tcolor_li_F9FABE" style="float: left; cursor: pointer; width: 15px; height: 15px; margin-right: 3px; border: 1px solid #C2C2C2; background: #F9FABE;" OnClick="tcolor_focus( 'F9FABE' )"></div>
						<div id="tcolor_li_BDBEF9" style="float: left; cursor: pointer; width: 15px; height: 15px; margin-right: 3px; border: 1px solid #C2C2C2; background: #BDBEF9;" OnClick="tcolor_focus( 'BDBEF9' )"></div>
						<div id="tcolor_li_DAB195" style="float: left; cursor: pointer; width: 15px; height: 15px; margin-right: 3px; border: 1px solid #C2C2C2; background: #DAB195;" OnClick="tcolor_focus( 'DAB195' )"></div>
						<div id="tcolor_li_C1ADD0" style="float: left; cursor: pointer; width: 15px; height: 15px; margin-right: 3px; border: 1px solid #C2C2C2; background: #C1ADD0;" OnClick="tcolor_focus( 'C1ADD0' )"></div>
						<div id="tcolor_li_B7E3A3" style="float: left; cursor: pointer; width: 15px; height: 15px; margin-right: 3px; border: 1px solid #C2C2C2; background: #B7E3A3;" OnClick="tcolor_focus( 'B7E3A3' )"></div>

						<div style="clear: both"></div>
					</td>
				</tr>
				<tr>
					<td> <div style=""><input type="button" value="Submit" onClick="do_submit()"> <input type="reset" value="Reset" onClick="$( 'input#opid' ).val(0)"></div></td>
				</tr>
				</form>
				</table>
			</div>
		</div>
<?php include_once( "./inc_footer.php" ) ?>

</body>
</html>
