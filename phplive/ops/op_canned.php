<?php
	/****************************************/
	// STANDARD header for Setup
	include_once( "../web/config.php" ) ;
	include_once( "../API/Util_Format.php" ) ;
	include_once( "../API/Util_Error.php" ) ;
	include_once( "../API/SQL.php" ) ;
	include_once( "../API/Util_Security.php" ) ;
	$ses = Util_Format_Sanatize( Util_Format_GetVar( "ses" ), "ln" ) ;
	if ( !$opinfo = Util_Security_AuthOp( $dbh, $ses ) ){ ErrorHandler ( 602, "Invalid setup session or session has expired.", "$_SERVER[HTTP_HOST]/$_SERVER[REQUEST_URI]", 0, Array() ) ; }
	// STANDARD header end
	/****************************************/

	include_once( "../API/Depts/get.php" ) ;
	include_once( "../API/Canned/get.php" ) ;

	$page = ( Util_Format_Sanatize( Util_Format_GetVar( "page" ), "ln" ) ) ? Util_Format_Sanatize( Util_Format_GetVar( "page" ), "ln" ) : 0 ;
	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;
	$flag = Util_Format_Sanatize( Util_Format_GetVar( "flag" ), "ln" ) ;

	$error = "" ;

	if ( $action == "submit" )
	{
		include_once( "../API/Canned/put.php" ) ;

		$canid = Util_Format_Sanatize( Util_Format_GetVar( "canid" ), "ln" ) ;
		$deptid = Util_Format_Sanatize( Util_Format_GetVar( "deptid" ), "ln" ) ;
		$title = Util_Format_Sanatize( Util_Format_GetVar( "title" ), "ln" ) ;
		$message = Util_Format_Sanatize( Util_Format_GetVar( "message" ), "" ) ;

		$caninfo = Canned_get_CanInfo( $dbh, $canid ) ;
		if ( isset( $caninfo["opID"] ) )
			$opid = $caninfo["opID"] ;
		else
			$opid = $opinfo["opID"] ;

		if ( !Canned_put_Canned( $dbh, $canid, $opinfo["opID"], $deptid, $title, $message ) )
			$error = "Error processing canned message." ;
	}
	else if ( $action == "delete" )
	{
		include_once( "../API/Canned/remove.php" ) ;

		$canid = Util_Format_Sanatize( Util_Format_GetVar( "canid" ), "ln" ) ;

		$caninfo = Canned_get_CanInfo( $dbh, $canid ) ;
		if ( $caninfo["opID"] == $opinfo["opID"] )
			Canned_remove_Canned( $dbh, $opinfo["opID"], $canid ) ;
	}

	$departments = Depts_get_OpDepts( $dbh, $opinfo["opID"] ) ;
	$cans = Canned_get_OpCanned( $dbh, $opinfo["opID"], 0 ) ;

	// make hash for quick refrence
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
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<link rel="Stylesheet" href="../themes/<?php echo $opinfo["theme"] ?>/style.css">
<script type="text/javascript" src="../js/global.js"></script>
<script type="text/javascript" src="../js/setup.js"></script>
<script type="text/javascript" src="../js/framework.js"></script>
<script type="text/javascript" src="../js/framework_ext.js"></script>
<script type="text/javascript" src="../js/tooltip.js"></script>

<script type="text/javascript">
<!--
	var mouse_x ; var mouse_y ;

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		}) ;

		if ( ( "<?php echo $action ?>" == "submit" ) && ( "<?php echo $error ?>" == "" ) )
		{
			if ( typeof( parent.isop ) != "undefined" )
				parent.populate_cans(0) ;
		}
		else if ( "<?php echo $action ?>" == "delete" )
		{
			if ( typeof( parent.isop ) != "undefined" )
				parent.populate_cans(0) ;
		}

		if ( "<?php echo $flag ?>" == "new_canned" )
			toggle_new(1) ;

		init_trs() ;
		init_tooltips() ;
	});

	function init_trs()
	{
		$('#table_trs tr:nth-child(2n+3)').addClass('chat_info_tr_traffic_row') ;
	}

	function toggle_new( theflag )
	{
		// theflag = 1 means force show, not toggle
		if ( $('#canned_box_new').is(':visible') && !theflag )
		{
			$( "input#canid" ).val( "" ) ;
			$( "input#title" ).val( "" ) ;
			$( "#deptid" ).val( "" ) ;
			$( "#message" ).val( "" ) ;

			$('#canned_box_new').hide() ;
		}
		else
		{
			$('#canned_box_new').show() ;
		}
	}

	function do_edit( thecanid, thetitle, thedeptid, themessage )
	{
		$( "input#canid" ).val( thecanid ) ;
		$( "input#title" ).val( thetitle ) ;
		$( "#deptid" ).val( thedeptid ) ;
		$( "#message" ).val( themessage.replace(/<br>/g, "\r\n").replace( /&-#39;/g, "'" ) ) ;
		
		toggle_new(0) ;
	}

	function do_delete( thiscanid )
	{
		if ( confirm( "Really delete this canned response?" ) )
			location.href = "op_canned.php?ses=<?php echo $ses ?>&action=delete&canid="+thiscanid ;
	}

	function do_submit()
	{
		var canid = $('#canid').val() ;
		var title = $('#title').val() ;
		var deptid = $('#deptid').val() ;
		var message = $('#message').val() ;

		if ( title == "" )
			do_alert( 0, "Please provide a title." ) ;
		else if ( message == "" )
			do_alert( 0, "Please provide a message." ) ;
		else
			$('#theform').submit() ;
	}

	function init_tooltips()
	{
		var help_tooltips = $( '#canned_wrapper' ).find( '.help_tooltip' ) ;
		help_tooltips.tooltip({
			event: "mouseover",
			track: true,
			delay: 0,
			showURL: false,
			showBody: "- ",
			fade: 0,
			positionLeft: true,
			left: 120,
			extraClass: "stat"
		});
	}

//-->
</script>
</head>
<body>

<div id="canned_wrapper" style="height: 100%; overflow: auto;">
	<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr><td class="t_tl"></td><td class="t_tm"></td><td class="t_tr"></td></tr>
	<tr>
		<td class="t_ml"></td><td class="t_mm">
			<table cellspacing=0 cellpadding=0 border=0 width="100%" id="table_trs">
			<tr>
				<td width="18" nowrap><div id="chat_info_td_h">&nbsp;</div></td>
				<td width="180" nowrap><div id="chat_info_td_h">Title</div></td>
				<td width="180"><div id="chat_info_td_h">Department</div></td>
				<td><div id="chat_info_td_h">Message</div></td>
			</tr>
			<?php
				for ( $c = 0; $c < count( $cans ); ++$c )
				{
					$can = $cans[$c] ;
					$title = $can["title"] ;
					$dept_name = $dept_hash[$can["deptID"]] ;
					$message = preg_replace( "/\"/", "&quot;", preg_replace( "/'/", "&-#39;", preg_replace( "/(\r\n)|(\n)|(\r)/", "<br>", $can["message"] ) ) ) ;
					$message_display = preg_replace( "/(\r\n)|(\n)|(\r)/", "<br>", $can["message"] ) ;
					$delete_image = ( $can["opID"] == $opinfo["opID"] ) ? "<img src=\"../themes/$opinfo[theme]/delete.png\" style=\"cursor: pointer;\" onClick=\"do_delete($can[canID])\" class=\"help_tooltip\" title=\"- delete canned\" width=\"14\" height=\"14\" border=0>" : "<img src=\"../pics/space.gif\" width=\"14\" height=\"14\" border=0>" ;
					$edit_image = ( $can["opID"] == $opinfo["opID"] ) ? "<img src=\"../themes/$opinfo[theme]/edit.png\" style=\"cursor: pointer;\" onClick=\"do_edit($can[canID], '$title', '$can[deptID]', '$message')\" class=\"help_tooltip\" title=\"- edit canned\"  width=\"14\" height=\"14\" border=0>" : "<img src=\"../pics/space.gif\" width=\"14\" height=\"14\" border=0>" ;

					print "<tr><td class=\"chat_info_td_traffic\" nowrap>$delete_image &nbsp; $edit_image</td><td class=\"chat_info_td_traffic\"><b>$title</b></td><td class=\"chat_info_td_traffic\">$dept_name</td><td class=\"chat_info_td_traffic\">$message_display</td></tr>" ;
				}
				if ( $c == 0 )
					print "<tr><td class=\"chat_info_td_traffic\">&nbsp;</td><td colspan=3 class=\"chat_info_td_traffic\">No canned responses.</td></tr>" ;
			?>
			</table>
			<div class="chat_info_end"></div>
		</td><td class="t_mr"></td>
	</tr>
	<tr><td class="t_bl"></td><td class="t_bm"></td><td class="t_br"></td></tr>
	</table>
</div>

<div id="canned_box_new" style="position: absolute; display: none; top: 0px; width: 100%; height: 100%; z-Index: 5;">
	<table cellspacing=0 cellpadding=0 border=0 width="100%">
	<tr>
		<form method="POST" action="op_canned.php?<?php echo time() ?>" id="theform">
		<td valign="top" width="300" style="padding: 5px;">
			<input type="hidden" name="ses" value="<?php echo $ses ?>">
			<input type="hidden" name="action" value="submit">
			<input type="hidden" name="canid" id="canid" value="0">
			<div id="canned_text_title" style="margin-bottom: 5px;">Create/Edit Canned Responses</div>
			<div>
				Title<br>
				<input type="text" name="title" id="title" class="input_text" style="width: 98%; margin-bottom: 10px;" maxlength="25">
				Department<br>
				<select name="deptid" id="deptid" style="width: 99%; margin-bottom: 10px;" class="input_text">
				<option value="1111111111">All Departments</option>
				<?php
					for ( $c = 0; $c < count( $departments ); ++$c )
					{
						$department = $departments[$c] ;

						print "<option value=\"$department[deptID]\">$department[name]</option>" ;
					}
				?>
				</select>
				Canned Message<br>
				<textarea name="message" id="message" class="input_text" rows="5" style="min-width: 98%; margin-bottom: 10px;" wrap="virtual"></textarea>

				<button type="button" onClick="do_submit()" class="input_button">Submit</button> or <span style="text-decoration: underline; cursor: pointer;" onClick="toggle_new(0)">Cancel</span>
			</div>
		</td>
		</form>
		<td valign="center">
			<ul>
				<li> HTML is not allowed in canned messages.
				<li style="margin-top: 5px;"> Your created canned messages are private and are not shared.
				<!-- <li style="margin-top: 5px;"> To automatically execute some helpful functions, use the below commands:
					<ul>
						<li> <span class="text_brown"><b>load:</b></span>URL (automatically loads a URL)<br>
							example: <i>load:http://www.phplivesupport.com/trial.php</i>
					</ul> -->
				<li style="margin-top: 5px;"> To dynamically populate data, use the below variables:
					<ul>
						<li> <span class="text_brown"><b>%%visitor%%</b></span> = visitor's name
						<li> <span class="text_brown"><b>%%operator%%</b></span> = your name
						<li> <span class="text_brown"><b>%%op_email%%</b></span> = your email

						<li style="padding-top: 5px;"> <span class="text_brown"><b>email:</b><i>someone@somewhere.com</i> - link an email</span>
						<li> <span class="text_brown"><b>url:</b><i>http://www.someurl.com</i> - link an URL</span>
						<li> <span class="text_brown"><b>image</b><i>http://www.someurl.com/image.gif</i> - display an image</span>
					</ul>
			</ul>
		</td>
	</tr>
	</table>
</div>

</body>
</html>
<?php database_mysql_close( $dbh ) ; ?>
