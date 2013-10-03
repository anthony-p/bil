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

	include_once( "../API/Depts/get.php" ) ;
	include_once( "../API/Marketing/get.php" ) ;

	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;

	if ( $action == "submit" )
	{
		include_once( "../API/Marketing/put.php" ) ;

		$marqid = Util_Format_Sanatize( Util_Format_GetVar( "marqid" ), "ln" ) ;
		$deptid = Util_Format_Sanatize( Util_Format_GetVar( "deptid" ), "ln" ) ;
		$snapshot = Util_Format_Sanatize( Util_Format_GetVar( "snapshot" ), "htmltags" ) ;
		$message = Util_Format_Sanatize( preg_replace( "/[\"\']|(<script)/i", "", Util_Format_GetVar( "message" ) ), "" ) ;

		if ( !Marketing_put_Marquee( $dbh, $marqid, $deptid, $snapshot, $message ) )
		{
			$error = "Create new Chat Footer Marquee failed." ;
		}
	}
	else if ( $action == "delete" )
	{
		include_once( "../API/Marketing/remove.php" ) ;

		$marqid = Util_Format_Sanatize( Util_Format_GetVar( "marqid" ), "ln" ) ;

		Marketing_remove_Marquee( $dbh, $marqid ) ;
	}

	$departments = Depts_get_AllDepts( $dbh ) ;
	$marquees = Marketing_get_AllMarquees( $dbh ) ;

	$dept_hash = Array() ;
	$dept_hash[1111111111] = "All Departments" ;
	for ( $c = 0; $c < count( $departments ); ++$c )
	{
		$department = $departments[$c] ;
		$dept_hash[$department["deptID"]] = $department["name"] ;
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

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		}) ;

		$('#body_sub_title').html( "<img src=\"../pics/icons/graph.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" style=\"margin-right: 5px;\"> Marketing" ) ;

		init_menu() ;
		toggle_menu_setup( "marketing" ) ;

		<?php if ( ( $action == "submit" ) && !$error ): ?>do_alert( 1, "Update Success!" ) ;<?php endif ; ?>
	});

	function do_edit( themarqid, thedeptid, thesnapshot, themessage )
	{
		$( "input#marqid" ).val( themarqid ) ;
		$( "select#deptid" ).val( thedeptid ) ;
		$( "input#snapshot" ).val( thesnapshot ) ;
		$( "input#message" ).val( themessage ) ;
		location.href = "#a_edit" ;
	}

	function do_delete( themarqid )
	{
		if ( confirm( "Really delete this Marquee?" ) )
			location.href = "marketing_marquee.php?ses=<?php echo $ses ?>&action=delete&marqid="+themarqid ;
	}

	function do_submit()
	{
		var deptid = $( "input#deptid" ).val() ;
		var snapshot = $( "#snapshot" ).val() ;
		var message = $( "#message" ).val().replace( /\"/g, '' ).replace( /\'/g, '' ) ;
		$( "#message" ).val( message ) ;

		if ( snapshot == "" )
			do_alert( 0, "Please provide the reference name." ) ;
		else if ( message == "" )
			do_alert( 0, "Please provide the marquee message." ) ;
		else
			$('#theform').submit() ;
	}

	function view_marquee()
	{
		var message = $( "#message" ).val().replace( /\"/g, '' ).replace( /\'/g, '' ) ;
		$( "#message" ).val( message ) ;
		message = encodeURIComponent( $('#message').val() ) ;

		if ( message == "" )
			do_alert( 0, "Please provide a marquee message." ) ;
		else
		{
			url = "http://192.168.1.102/osicodes/phplive_4.1/phplive.php?marquee_test="+message+"&"+unixtime() ;
			var newwin = window.open( url, "Marquee", 'scrollbars=no,resizable=yes,menubar=no,location=no,screenX=50,screenY=100,width=550,height=410' ) ;
		}
	}

//-->
</script>
</head>
<body>

<?php include_once( "./inc_header.php" ) ?>

		<div class="op_submenu_wrapper">
			<div class="op_submenu" onClick="location.href='marketing.php?ses=<?php echo $ses ?>'">Social Media</div>
			<div class="op_submenu" onClick="location.href='marketing_click.php?ses=<?php echo $ses ?>'">Click Tracking</div>
			<div class="op_submenu" onClick="location.href='reports_marketing.php?ses=<?php echo $ses ?>'">Report: Clicks</div>
			<div class="op_submenu_focus">Chat Footer Marquee</div>
			<!-- <div class="op_submenu" onClick="location.href='marketing_initiate.php?ses=<?php echo $ses ?>'">Auto Initiate</div> -->
			<div style="clear: both"></div>
		</div>

		<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr><td class="t_tl"></td><td class="t_tm"></td><td class="t_tr"></td></tr>
		<tr>
			<td class="t_ml"></td><td class="t_mm">
				<div class="info_box">
					<div style="">Announcement messages can be displayed on the visitor chat window footer.  The messages will cycle every 10 seconds.  Short and sweet messages are recommended to avoid formatting issues. (example: "Free shipping on all orders above $100!")</div>
				</div>

				<div style="margin-top: 15px;">
					<table cellspacing=1 cellpadding=0 border=0 width="100%">
					<form>
					<tr>
						<td width="40">&nbsp;</td>
						<td width="200"><div id="td_dept_header">Department</div></td>
						<td><div id="td_dept_header">Title</div></td>
						<td><div id="td_dept_header">Message</div></td>
					</tr>
					<?php
						for ( $c = 0; $c < count( $marquees ); ++$c )
						{
							$marquee = $marquees[$c] ;
							$dept_name = $dept_hash[$marquee["deptID"]] ;
							$message = $marquee["message"] ;
							$message_js = preg_replace( "/'/", "\'", preg_replace( "/\"/", "&quot;", $marquee["message"] ) ) ;
							$snapshot = $marquee["snapshot"] ;
							$snapshot_js = preg_replace( "/'/", "\'", preg_replace( "/\"/", "&quot;", $marquee["snapshot"] ) ) ;

							$td1 = "td_dept_td" ; $td2 = "td_dept_td_td" ;
							if ( $c % 2 ) { $td1 = "td_dept_td2" ; $td2 = "td_dept_td_td2" ; }

							print "
								<tr>
									<td class=\"$td1\" nowrap><a href=\"JavaScript:void(0)\" onClick=\"do_delete($marquee[marqID])\" class=\"nounder\"><img src=\"../pics/icons/delete.png\" width=\"14\" height=\"14\" border=\"0\" alt=\"\"></a> &nbsp; <a href=\"JavaScript:void(0)\" onClick=\"do_edit( $marquee[marqID], '$marquee[deptID]', '$snapshot_js', '$message_js' )\" class=\"nounder\"><img src=\"../pics/icons/edit.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\"></a></td>
									<td class=\"$td1\" nowrap>$dept_name</td>
									<td class=\"$td1\">$snapshot</td>
									<td class=\"$td1\">$message</td>
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
			<a name="a_edit"></a><div class="edit_title">Create/Edit Chat Footer Marquee <span class="txt_red"><?php echo $error ?></span></div>
			<div style="margin-top: 10px;">
				<table cellspacing=0 cellpadding=5 border=0>
				<form method="POST" action="marketing_marquee.php?submit" id="theform">
				<input type="hidden" name="action" value="submit">
				<input type="hidden" name="ses" value="<?php echo $ses ?>">
				<input type="hidden" name="marqid" id="marqid" value="0">
				<tr>
					<td>Display this Marquee to visitors requesting support to Department(s)<br>
						<select name="deptid" id="deptid" style="font-size: 16px; background: #D4FFD4; color: #009000;">
						<option value="1111111111">All Departments</option>
						<?php
							for ( $c = 0; $c < count( $departments ); ++$c )
							{
								$department = $departments[$c] ;
								if ( $department["name"] != "Archive" )
									print "<option value=\"$department[deptID]\">$department[name]</option>" ;
							}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Reference Name - not displayed (example: "FREE shipping")<br><input type="text" name="snapshot" id="snapshot" size="50" maxlength="35" value=""></td>
				</tr>
				<tr>
					<td>Marquee Message - displayed to visitor (example: "Receive FREE shipping with purchases over $100!")
						<div style="padding: 5px; background: url(../pics/icons/info.png) no-repeat; background-position: 0px 5px; padding-left: 20px;"> HTML is allowed, but the double quote (") and single quote (') characters will be ommitted.</div>
						<div style="padding: 5px; background: url(../pics/icons/info.png) no-repeat; background-position: 0px 5px; padding-left: 20px;"> If providing &lt;a href= &gt; url link, please include <span class="txt_blue">target=_blank</span> so that the url does not replace the chat window.</div>
						<input type="text" name="message" id="message" size="120" maxlength="255" value="" onKeyPress="return noquotes(event)">
						<div style="margin-top: 5px; font-size: 10px">&middot; <a href="JavaScript:void(0)" onClick="view_marquee()">view how the message will look before you submit</a></div>
					</td>
				</tr>
				<tr>
					<td> <div style=""><input type="button" value="Submit" onClick="do_submit()"> <input type="reset" value="Reset" onClick="$( 'input#marqid' ).val(0)"></div></td>
				</tr>
				</form>
				</table>
			</div>
		</div>
<?php include_once( "./inc_footer.php" ) ?>

</body>
</html>
