<?php
	/****************************************/
	// STANDARD header for Setup
	if ( !file_exists( "../web/config.php" ) ){ HEADER("location: install.php") ; exit ; }
	include_once( "../web/config.php" ) ;
	include_once( "../API/Util_Format.php" ) ;
	//include_once( "../API/Util_Error.php" ) ;
	include_once( "../API/SQL.php" ) ;
	include_once( "../API/Util_Security.php" ) ;
	$ses = Util_Format_Sanatize( Util_Format_GetVar( "ses" ), "ln" ) ;
	if ( !$admininfo = Util_Security_AuthSetup( $dbh, $ses ) ){ ErrorHandler ( 602, "Invalid setup session or session has expired.", "$_SERVER[HTTP_HOST]/$_SERVER[REQUEST_URI]", 0, Array() ) ; }
	// STANDARD header end
	/****************************************/

	$error = "" ;

	include_once( "../API/Depts/get.php" ) ;

	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;

	if ( $action == "submit" )
	{
		include_once( "../lang_packs/$CONF[lang].php" ) ;
		include_once( "../API/Depts/put.php" ) ;
		include_once( "../API/Ops/update.php" ) ;

		$deptid = Util_Format_Sanatize( Util_Format_GetVar( "deptid" ), "ln" ) ;
		$name = Util_Format_Sanatize( Util_Format_GetVar( "name" ), "ln" ) ;
		$email = Util_Format_Sanatize( Util_Format_GetVar( "email" ), "e" ) ;
		$visible = Util_Format_Sanatize( Util_Format_GetVar( "visible" ), "ln" ) ;
		$queue = Util_Format_Sanatize( Util_Format_GetVar( "queue" ), "ln" ) ;
		$rtype = Util_Format_Sanatize( Util_Format_GetVar( "rtype" ), "ln" ) ;
		$rtime = Util_Format_Sanatize( Util_Format_GetVar( "rtime" ), "ln" ) ;
		$tshare = Util_Format_Sanatize( Util_Format_GetVar( "tshare" ), "ln" ) ;
		$traffic = Util_Format_Sanatize( Util_Format_GetVar( "traffic" ), "ln" ) ;
		$texpire = Util_Format_Sanatize( Util_Format_GetVar( "texpire" ), "ln" ) ;

		if ( ( $name != "Archive" ) && !Depts_put_Department( $dbh, $deptid, $name, $email, $visible, $queue, $rtype, $rtime, $tshare, $texpire ) )
		{
			$error = "$name is already in use." ;
		}
		else if ( $name != "Archive" )
		{
			Ops_update_OpDeptVisible( $dbh, $deptid, $visible ) ;
		}
	}
	else if ( $action == "delete" )
	{
		include_once( "../API/Depts/remove.php" ) ;

		$deptid = Util_Format_Sanatize( Util_Format_GetVar( "deptid" ), "ln" ) ;
		Depts_remove_Dept( $dbh, $deptid ) ;
	}

	$departments = Depts_get_AllDepts( $dbh ) ;
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
	var mouse_x, mouse_y ;
	var global_deptid ;

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		});
		$(window).resize(function() { init_iframe() ; });

		$('#body_sub_title').html( "<img src=\"../pics/icons/depts.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" style=\"margin-right: 5px;\"> Departments" ) ;

		init_menu() ;
		toggle_menu_setup( "depts" ) ;

		var help_tooltips = $( 'body' ).find('.help_tooltip' ) ;
		help_tooltips.tooltip({
			event: "mouseover",
			track: true,
			delay: 0,
			showURL: false,	
			showBody: "- ",
			fade: 0,
			extraClass: "stat"
		});

		<?php if ( $action && !$error ): ?>do_alert( 1, "Update Success!" ) ;<?php endif ; ?>
	});

	function init_iframe()
	{
		// for now skip positioning
		return 1 ;
		if ( typeof( global_deptid ) != "undefined" )
		{
			var pos = $('#pos_'+global_deptid).position() ;
			var left = pos.left + 190 ;

			$('#iframe_wrapper').css({'top': pos.top, 'left': left}) ;
		}
	}

	function do_submit()
	{
		var name = $( "input#name" ).val() ;
		var email = $( "input#email" ).val() ;

		if ( name == "" )
			do_alert( 0, "Please provide the department name." ) ;
		else if ( !check_email( email ) )
			do_alert( 0, "Please provide a valid email address." ) ;
		else
			$('#theform').submit() ;
	}

	function do_options( theobject, thedeptid, thename, theemail, thertype, thertime, thetexpire, thevisible, thequeue, thetshare )
	{
		var unique = unixtime() ;

		if( !theobject.selectedIndex )
		{
			$('#div_new_canned_'+thedeptid).hide() ;
			$('#iframe_'+thedeptid).hide() ;
		}
		else
		{
			$('#div_new_canned_'+thedeptid).hide() ;

			if ( theobject.selectedIndex == 1 )
				$('#iframe_edit_'+thedeptid).attr('src', 'iframe_edit.php?ses=<?php echo $ses ?>&action=greeting&deptid='+thedeptid+"&"+unique ) ;
			else if ( theobject.selectedIndex == 2 )
				$('#iframe_edit_'+thedeptid).attr('src', 'iframe_edit.php?ses=<?php echo $ses ?>&action=offline&deptid='+thedeptid+"&"+unique ) ;
			else if ( theobject.selectedIndex == 3 )
			{
				$('#div_new_canned_'+thedeptid).show() ;
				$('#iframe_edit_'+thedeptid).attr('src', 'iframe_edit.php?ses=<?php echo $ses ?>&action=canned&deptid='+thedeptid+"&"+unique ) ;
			}
			else
				$('#iframe_'+thedeptid).hide() ;

			$('#iframe_'+thedeptid).show() ;

			global_deptid = thedeptid ;
			init_iframe() ;
		
			//theobject.selectedIndex = 0 ;
		}
	}

	function do_edit( thedeptid, thename, theemail, thertype, thertime, thetexpire, thevisible, thequeue, thetshare )
	{
		$( "input#deptid" ).val( thedeptid ) ;
		$( "input#name" ).val( thename ) ;
		$( "input#email" ).val( theemail ) ;
		$( "select#rtime" ).val( thertime ) ;
		$( "select#texpire" ).val( thetexpire ) ;
		$( "input#rtype_"+thertype ).attr( "checked", true ) ;
		$( "input#visible_"+thevisible ).attr( "checked", true ) ;
		$( "input#queue_"+thequeue ).attr( "checked", true ) ;
		$( "input#tshare_"+thetshare ).attr( "checked", true ) ;
		location.href = "#a_edit" ;
	}

	function do_delete( thedeptid )
	{
		if ( confirm( "Really delete this department?" ) )
			location.href = "depts.php?ses=<?php echo $ses ?>&action=delete&deptid="+thedeptid ;
	}

	function new_canned( thedeptid )
	{
		$('#iframe_edit_'+thedeptid).attr( "contentWindow" ).toggle_new(1) ;
	}
//-->
</script>
</head>
<body>

<?php include_once( "./inc_header.php" ) ?>

		<div class="op_submenu_wrapper">
			<div style="margin-bottom: 15px;">
				<div class="info_box">
					<img src="../pics/icons/info.png" width="16" height="16" border="0" alt=""> "Customer Support", "Tech Support", or "Sales" are examples of different departments you can create.  Department specific logo can be configured in the <a href="settings.php?ses=<?php echo $ses ?>">Settings</a> area.
				</div>
			</div>
			<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr><td class="t_tl"></td><td class="t_tm"></td><td class="t_tr"></td></tr>
			<tr>
				<td class="t_ml"></td><td class="t_mm">
					<table cellspacing=0 cellpadding=0 border=0 width="100%">
					<tr>
						<td width="40">&nbsp;</td>
						<td><div id="td_dept_header">Department</div></td>
						<td><div id="td_dept_header">Email</div></td>
						<td width="95" nowrap><div id="td_dept_header">Routing Type</div></td>
						<td width="95" nowrap><div id="td_dept_header">Route Time</div></td>
						<td width="60" align="center"><div id="td_dept_header">Visible</div></td>
						<!-- <td width="60" align="center"><div id="td_dept_header">Queue</div></td> -->
						<td width="90" nowrap align="center"><div id="td_dept_header">Share Trans</div></td>
					</tr>
					<?php
						$image_empty = "<img src=\"../pics/space.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"\">" ;
						$image_checked = "<img src=\"../pics/icons/check.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\">";
						for ( $c = 0; $c < count( $departments ); ++$c )
						{
							$department = $departments[$c] ;

							$rtype = $VARS_RTYPE[$department["rtype"]] ;
							$rtime = "$department[rtime] sec" ;
							$visible = ( $department["visible"] ) ? $image_checked : $image_empty ;
							$queue = ( $department["queue"] ) ? $image_checked : $image_empty ;
							$tshare = ( $department["tshare"] ) ? $image_checked : $image_empty ;

							$edit_delete = "<a href=\"JavaScript:void(0)\" onClick=\"do_delete($department[deptID])\" class=\"help_tooltip nounder\" title=\"- delete department\"><img src=\"../pics/icons/delete.png\" width=\"14\" height=\"14\" border=\"0\" alt=\"\"></a> &nbsp; <a href=\"JavaScript:void(0)\" onClick=\"do_edit( $department[deptID], '$department[name]', '$department[email]', '$department[rtype]', '$department[rtime]', '$department[texpire]', '$department[visible]', '$department[queue]', '$department[tshare]' )\" class=\"help_tooltip nounder\" title=\"- edit department\"><img src=\"../pics/icons/edit.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\"></a>" ;
							$options = "<select id=\"pos_$department[deptID]\" style=\"background: #D4FFD4; color: #009000; padding-bottom: 5px;\" OnChange=\"do_options( this, $department[deptID], '$department[name]', '$department[email]', '$department[rtype]', '$department[rtime]', '$department[texpire]', '$department[visible]', '$department[queue]', '$department[tshare]' );\"><option value=\"\"></option><optgroup label=\"Messages\"><option value=\"greeting\">Chat Greeting</option><option value=\"offline\">Offline Message</option><option value=\"greeting\">Canned Responses</option></optgroup></select> <span id=\"div_new_canned_$department[deptID]\" class=\"info_box\" style=\"display: none; margin-left: 10px; padding-left: 25px; background: url( ../pics/icons/add.png ) no-repeat #FAFAA6; background-position: 5px 5px; cursor: pointer;\" onClick=\"new_canned( $department[deptID] )\">new canned</span>" ;

							$td1 = "td_dept_td" ; $td2 = "td_dept_td_td" ;
							if ( $c % 2 ) { $td1 = "td_dept_td2" ; $td2 = "td_dept_td_td2" ; }

							print "
							<tr>
								<td class=\"$td1\" nowrap>$edit_delete</td>
								<td class=\"$td1\">
									<div>$department[name]</div>
								</td>
								<td class=\"$td1\">$department[email]</td>
								<td class=\"$td1\">$rtype</td>
								<td class=\"$td1\">$rtime</td>
								<td class=\"$td1\" align=\"center\">$visible</td>
								<!-- <td class=\"$td1\" align=\"center\">$queue</td> -->
								<td class=\"$td1\" align=\"center\">$tshare</td>
							</tr>
							<tr>
								<td class=\"$td2\">&nbsp;</td><td class=\"$td2\" colspan=\"7\">$options<div id=\"iframe_$department[deptID]\" style=\"display: none;\" class=\"iframe_edit\"><div><iframe id=\"iframe_edit_$department[deptID]\" name=\"iframe_edit_$department[deptID]\" style=\"width: 100%; height: 290px; border: 0px;\" src=\"\" scrolling=\"auto\"></iframe></div></div></td>
							</tr>
							" ;
						}
					?>
					</table>
				</td><td class="t_mr"></td>
			</tr>
			<tr><td class="t_bl"></td><td class="t_bm"></td><td class="t_br"></td></tr>
			</table>
		</div>

		<div class="edit_wrapper" style="padding: 5px; padding-top: 25px;">
			<a name="a_edit"></a><div class="edit_title">Create/Edit Department <span class="txt_red"><?php echo $error ?></span></div>
			<div style="margin-top: 10px;">
				<table cellspacing=0 cellpadding=5 border=0>
				<form method="POST" action="depts.php?submit" id="theform">
				<input type="hidden" name="ses" value="<?php echo $ses ?>">
				<input type="hidden" name="action" value="submit">
				<input type="hidden" name="deptid" id="deptid" value="0">
				<input type="hidden" name="queue" value="0">
				<tr>
					<td>Department Name</td>
					<td> <input type="text" name="name" id="name" size="30" maxlength="40" value="" onKeyPress="return nospecials(event)"></td>
					<td><img src="../pics/space.gif" width="25" height=1></td>

					<td><img src="../pics/icons/help.png" width="14" height="14" border="0" class="help_tooltip" title="- <b>Visible to Public</b><br>When a visitor requests support, choose whether to display this department on the selection list."> Visible to Public</td>
					<td> <input type="radio" name="visible" id="visible_1" value="1" checked> Yes <input type="radio" name="visible" id="visible_0" value="0"> No</td>
				</tr>
				<tr>
					<td colspan="2"> "Leave a Message Form" emails will be sent to the Department Email address below.</td>
					<td>&nbsp;</td>

					<td><img src="../pics/icons/help.png" width="14" height="14" border="0" class="help_tooltip" title="- <b>Op Share Transcripts</b><br>Select if operators of the same department can view each other's saved transcripts."> Share Transcripts</td>
					<td> <input type="radio" name="tshare" id="tshare_1" value="1" checked> Yes <input type="radio" name="tshare" id="tshare_0" value="0"> No</td>
				</tr>
				<tr>
					<td>Department Email</td>
					<td> <input type="text" name="email" id="email" size="30" maxlength="160" value="" onKeyPress="return justemails(event)"></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<tr>
					<td>Transcripts Expire</td>
					<td> after <select name="texpire" id="texpire" ><option value="31104000" selected>1 year</option><option value="62208000">2 years</option><option value="93312000">3 years</option><option value="0">no expire</option></select> transcripts will be automatically deleted</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>Routing Type</td>
					<td>
						<div><input type="radio" name="rtype" id="rtype_1" value="1"> Defined order: set on <a href="ops.php?ses=<?php echo $ses ?>&jump=ops_assign">operator to department assignment</a> area</div>
						<div style="margin-top: 3px;"><input type="radio" name="rtype" id="rtype_2" value="2" checked> Round-robin: operator that hasn't taken a chat the longest gets the current request</div>
						<!-- <div style="margin-top: 3px;"><input type="radio" name="rtype" id="rtype_3" value="3"> Frenzy: all operators get the request at the same time, first to accept takes the chat</div> -->
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>Route Time</td>
					<td> after <select name="rtime" id="rtime" ><option value="15">15 seconds</option><option value="30">30 seconds</option><option value="45" selected>45 seconds</option><option value="60">60 seconds</option><option value="90">90 seconds</option><option value="120">2 minutes</option><option value="180">3 minutes</option></select> chat request is routed to the next operator</td>
					<td>&nbsp;</td>

					<td colspan="2"> <div style=""><input type="button" value="Submit" onClick="do_submit()"> <input type="reset" value="Reset"></div></td>
				</tr>
				</form>
				</table>
			</div>
		</div>
<?php include_once( "./inc_footer.php" ) ?>

</body>
</html>
