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

	$error = $sub = "" ;

	include_once( "../API/Ops/get.php" ) ;
	include_once( "../API/Depts/get.php" ) ;

	$page = ( Util_Format_Sanatize( Util_Format_GetVar( "page" ), "ln" ) ) ? Util_Format_Sanatize( Util_Format_GetVar( "page" ), "ln" ) : 0 ;
	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;
	$sub = Util_Format_Sanatize( Util_Format_GetVar( "sub" ), "ln" ) ;
	$deptid = Util_Format_Sanatize( Util_Format_GetVar( "deptid" ), "ln" ) ;

	if ( $action == "update" )
	{
		include_once( "../API/Depts/update.php" ) ;

		$message = Util_Format_Sanatize( Util_Format_GetVar( "message" ), "" ) ;

		if ( $sub == "greeting" )
			Depts_update_UserDeptValue( $dbh, $deptid, "msg_greet", $message ) ;
		else if ( $sub == "offline" )
			Depts_update_UserDeptValue( $dbh, $deptid, "msg_offline", $message ) ;
		else if ( $sub == "temail" )
			Depts_update_UserDeptValue( $dbh, $deptid, "msg_email", $message ) ;
		else if ( $sub == "canned" )
		{
			include_once( "../API/Canned/get.php" ) ;
			include_once( "../API/Canned/put.php" ) ;

			$message = Util_Format_Sanatize( Util_Format_GetVar( "message" ), "" ) ;
			$canid = Util_Format_Sanatize( Util_Format_GetVar( "canid" ), "ln" ) ;
			$title = Util_Format_Sanatize( Util_Format_GetVar( "title" ), "ln" ) ;
			$message = Util_Format_Sanatize( Util_Format_GetVar( "message" ), "" ) ;
			$sub_deptid = Util_Format_Sanatize( Util_Format_GetVar( "sub_deptid" ), "ln" ) ;

			$caninfo = Canned_get_CanInfo( $dbh, $canid ) ;
			if ( isset( $caninfo["opID"] ) )
				$opid = $caninfo["opID"] ;
			else
				$opid = 1111111111 ;

			if ( !Canned_put_Canned( $dbh, $canid, $opid, $deptid, $title, $message ) )
				$error = "Error processing canned message." ;
			$deptid = $sub_deptid ;
		}
		$action = $sub ;
	}
	else if ( $action == "delete" )
	{
		include_once( "../API/Canned/get.php" ) ;
		include_once( "../API/Canned/remove.php" ) ;

		$canid = Util_Format_Sanatize( Util_Format_GetVar( "canid" ), "ln" ) ;

		$caninfo = Canned_get_CanInfo( $dbh, $canid ) ;
		Canned_remove_Canned( $dbh, $caninfo["opID"], $canid ) ;
		$action = $sub ;
	}
	
	$deptinfo = Depts_get_DeptInfo( $dbh, $deptid ) ;
	$deptname = $deptinfo["name"] ;

	switch ( $action )
	{
		case "greeting":
			$title = "Chat Greeting" ;
			$message = $deptinfo["msg_greet"] ;
			break ;
		case "offline":
			$title = "Offline Message" ;
			$message = $deptinfo["msg_offline"] ;
			break ;
		case "temail":
			$title = "Transcript Email" ;
			$message = $deptinfo["msg_email"] ;
			break ;
		case "canned":
			$title = "Canned Responses" ;
			break ;
		default:
			$title = "Invalid" ;
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
<script type="text/javascript" src="../js/tooltip.js"></script>

<script type="text/javascript">
<!--
	var mouse_x ; var mouse_y ;
	var winname = unixtime() ;

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		}) ;

		<?php if ( $action == "canned" ): ?>toggle_new(0) ; <?php endif ; ?>

		<?php if ( $sub ): ?>parent.do_alert( 1, "Update Success!" ) ;<?php endif ; ?>

		init_tooltips() ;
	});

	function do_edit( thecanid, thetitle, thedeptid, themessage )
	{
		$( "input#canid" ).val( thecanid ) ;
		$( "input#title" ).val( thetitle ) ;
		$( "#deptid" ).val( thedeptid ) ;
		$( "#message" ).val( themessage.replace(/<br>/g, "\r\n").replace( /&-#39;/g, "'" ) ) ;
		
		toggle_new(0) ;
	}

	function toggle_new( theflag )
	{
		// theflag = 1 means force show, not toggle
		if ( $('#canned_box_new').is(':visible') && !theflag )
		{
			$( "input#canid" ).val( "0" ) ;
			$( "input#title" ).val( "" ) ;
			$( "#deptid" ).val( <?php echo $deptid ?> ) ;
			$( "#message" ).val( "" ) ;

			$('#canned_box_new').hide() ;
			$('#canned_list').show() ;
		}
		else
		{
			$('#canned_list').hide() ;
			$('#canned_box_new').show() ;
		}

		$('#title').focus() ;
	}

	function do_delete( thecanid )
	{
		var unique = unixtime() ;

		if ( confirm( "Really delete this canned response?" ) )
			location.href = "iframe_edit.php?ses=<?php echo $ses ?>&action=delete&sub=canned&deptid=<?php echo $deptid ?>&canid="+thecanid+"&"+unique ;
	}

	function do_submit()
	{
		var canid = $('#canid').val() ;
		var title = $('#title').val() ;
		var deptid = $('#deptid').val() ;
		var message = $('#message').val() ;

		if ( title == "" )
			parent.do_alert( 0, "Please provide a title." ) ;
		else if ( message == "" )
			parent.do_alert( 0, "Please provide a message." ) ;
		else
			$('#theform').submit() ;
	}

	function init_tooltips()
	{
		var help_tooltips = $( '#canned_list' ).find( '.help_tooltip' ) ;
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

<div id="iframe_body">
	<?php if ( $action != "canned" ): ?>
	<form action="iframe_edit.php?submit" method="POST">
	<input type="hidden" name="ses" value="<?php echo $ses ?>">
	<input type="hidden" name="action" value="update">
	<input type="hidden" name="sub" value="<?php echo $action ?>">
	<input type="hidden" name="deptid" value="<?php echo $deptid ?>">
	<div>
		<div style="padding: 10px;">
			<div id="info_greeting" style="display: none;">
				<div class="info_title">Chat Greeting</div>
				<div style="margin-top: 5px;">Edit your department chat greeting. This message is displayed when a visitor waits while a support representative is being contacted.</div>
				<div style="margin-top: 10px;"><b>%%visitor%%</b> = visitor's name</div>
			</div>
			<div id="info_offline" style="display: none;">
				<div class="info_title">Offline Message</div>
				<div style="margin-top: 5px;">You can place a short message stating your department hours, offline message, or best time or ways to reach the department.</div>
				<div style="margin-top: 10px;"></div>
			</div>
			<div id="info_temail" style="display: none;">
				
			</div>
		</div>
		<div style="">
			<input type="text" style="width: 95%" id="message" name="message" maxlength="155" value="<?php echo $message ?>">
			<div style="padding-top: 15px;"><input type="submit" value="Update <?php echo $title ?>"></div>
		</div>
	</div>
	</form>
	<script type="text/javascript">$('#info_<?php echo $action ?>').show();</script>

	<?php
		else:
		include_once( "../API/Canned/get.php" ) ;

		$departments = Depts_get_AllDepts( $dbh ) ;
		$operators = Ops_get_AllOps( $dbh ) ;

		// make hash for quick refrence
		$operators_hash = Array() ;
		$operators_hash[1111111111] = "<img src=\"../pics/icons/user_key.png\" width=\"16\" height=\"16\" border=\"0\" class=\"help_tooltip\" title=\"- created by setup admin\">" ;
		for ( $c = 0; $c < count( $operators ); ++$c )
		{
			$operator = $operators[$c] ;
			$operators_hash[$operator["opID"]] = $operator["name"] ;
		}

		// make hash for quick refrence
		$dept_hash = Array() ;
		$dept_hash[1111111111] = "All Departments" ;
		for ( $c = 0; $c < count( $departments ); ++$c )
		{
			$department = $departments[$c] ;
			$dept_hash[$department["deptID"]] = $department["name"] ;
		}

		$cans = Canned_get_DeptCanned( $dbh, $deptid, $page, 100 ) ;
	?>
	<div id="canned_list" style="min-height: 145px; overflow: auto;">
		<table cellspacing=1 cellpadding=0 border=0 width="100%">
		<tr>
			<td width="18" nowrap>&nbsp;</td>
			<td width="180" nowrap><div id="td_dept_header">Title</div></td>
			<td width="80" nowrap><div id="td_dept_header">Operator</div></td>
			<td width="180"><div id="td_dept_header">Department</div></td>
			<td><div id="td_dept_header">Message</div></td>
		</tr>
		<?php
			for ( $c = 0; $c < count( $cans )-1; ++$c )
			{
				$can = $cans[$c] ;
				$title = $can["title"] ;
				$op_name = $operators_hash[$can["opID"]] ;
				$dept_name = $dept_hash[$can["deptID"]] ;
				$message = preg_replace( "/\"/", "&quot;", preg_replace( "/'/", "&-#39;", preg_replace( "/(\r\n)|(\n)|(\r)/", "<br>", $can["message"] ) ) ) ;
				$message_display = preg_replace( "/(\r\n)|(\n)|(\r)/", "<br>", $can["message"] ) ;

				$td1 = "td_dept_td" ; $td2 = "td_dept_td_td" ;
				if ( $c % 2 ) { $td1 = "td_dept_td2" ; $td2 = "td_dept_td_td2" ; }

				print "<tr><td class=\"$td1\" nowrap><img src=\"../pics/icons/delete.png\" width=\"14\" height=\"14\" onClick=\"do_delete($can[canID])\" style=\"cursor: pointer;\" class=\"help_tooltip\" title=\"- delete canned\"> &nbsp; <img src=\"../pics/icons/edit.png\" width=\"14\" height=\"14\" onClick=\"do_edit($can[canID], '$title', '$can[deptID]', '$message')\" style=\"cursor: pointer;\" class=\"help_tooltip\" title=\"- edit canned\"></td><td class=\"$td1\"><b>$title</b></td><td class=\"$td1\" nowrap>$op_name</td><td class=\"$td1\">$dept_name</td><td class=\"$td1\">$message_display</td></tr>" ;
			}
			if ( $c == 0 )
				print "<tr><td></td><td colspan=3 class=\"td_dept_td\">No canned responses.</td></tr>" ;
		?>
		</table>
		<div class="chat_info_end"></div>

	</div>

	<div id="canned_box_new" style="padding: 5px; z-Index: 5;">
		<table cellspacing=0 cellpadding=0 border=0 width="100%">
		<tr>
			<form method="POST" action="iframe_edit.php?<?php echo time() ?>" id="theform">
			<td valign="top" width="300" style="padding: 5px;">
				<input type="hidden" name="ses" value="<?php echo $ses ?>">
				<input type="hidden" name="action" value="update">
				<input type="hidden" name="sub" value="<?php echo $action ?>">
				<input type="hidden" name="sub_deptid" value="<?php echo $deptid ?>">
				<input type="hidden" name="canid" id="canid" value="0">
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
					<textarea name="message" id="message" class="input_text" rows="5" style="width: 98%; resize: none; margin-bottom: 10px;" wrap="virtual"></textarea>

					<button type="button" onClick="do_submit()">Submit</button> or <a href="JavaScript:void(0)" onClick="toggle_new(0)">Cancel</a>
				</div>
			</td>
			</form>
			<td valign="center">
				<ul>
					<li> HTML is not allowed in canned messages.
					<li style="margin-top: 5px;"> Your created canned messages are private and are not shared.
					<!-- <li style="margin-top: 5px;"> To automatically execute some helpful functions, use the below commands:
						<ul>
							<li> <span class="text_blue"><b>load:</b></span>URL (automatically loads a URL)<br>
								example: <i>load:http://www.phplivesupport.com/trial.php</i>
						</ul> -->
					<li style="margin-top: 5px;"> To dynamically populate data, use the below variables:
						<ul>
							<li> <span class="text_blue"><b>%%visitor%%</b></span> = visitor's name
							<li> <span class="text_blue"><b>%%operator%%</b></span> = your name
							<li> <span class="text_blue"><b>%%op_email%%</b></span> = your email

							<li style="padding-top: 5px;"> <span class="text_blue"><b>email:</b><i>someone@somewhere.com</i> - link an email</span>
							<li> <span class="text_blue"><b>url:</b><i>http://www.someurl.com</i> - link an URL</span>
							<li> <span class="text_blue"><b>image</b><i>http://www.someurl.com/image.gif</i> - display an image</span>
						</ul>
				</ul>
			</td>
		</tr>
		</table>
	</div>

	<?php endif ; ?>
</div>

</body>
</html>
