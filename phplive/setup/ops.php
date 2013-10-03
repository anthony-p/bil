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

	include_once( "../API/Ops/get.php" ) ;
	include_once( "../API/Ops/update.php" ) ;
	include_once( "../API/Depts/get.php" ) ;

	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;
	$jump = Util_Format_Sanatize( Util_Format_GetVar( "jump" ), "ln" ) ;

	if ( $action == "submit" )
	{
		include_once( "../API/Ops/put.php" ) ;

		$opid = Util_Format_Sanatize( Util_Format_GetVar( "opid" ), "ln" ) ;
		$rate = Util_Format_Sanatize( Util_Format_GetVar( "rate" ), "ln" ) ;
		$op2op = Util_Format_Sanatize( Util_Format_GetVar( "op2op" ), "ln" ) ;
		$traffic = Util_Format_Sanatize( Util_Format_GetVar( "traffic" ), "ln" ) ;
		$login = Util_Format_Sanatize( Util_Format_GetVar( "login" ), "ln" ) ;
		$password = Util_Format_Sanatize( Util_Format_GetVar( "password" ), "ln" ) ;
		$name = Util_Format_Sanatize( Util_Format_GetVar( "name" ), "ln" ) ;
		$email = Util_Format_Sanatize( Util_Format_GetVar( "email" ), "e" ) ;

		if ( !Ops_put_Op( $dbh, $opid, 1, $rate, $op2op, $traffic, $login, $password, $name, $email ) )
		{
			$error = "$name is already in use." ;
		}
	}
	else if ( $action == "delete" )
	{
		include_once( "../API/Ops/remove.php" ) ;

		$opid = Util_Format_Sanatize( Util_Format_GetVar( "opid" ), "ln" ) ;
		Ops_remove_Op( $dbh, $opid ) ;
	}
	else if ( $action == "submit_assign" )
	{
		include_once( "../API/Ops/put.php" ) ;

		$opids = Util_Format_GetVar( "opids" ) ;
		$deptids = Util_Format_GetVar( "deptids" ) ;

		for ( $c = 0; $c < count( $opids ); ++$c )
		{
			$opid = Util_Format_Sanatize( $opids[$c], "ln" ) ;
			for ( $c2 = 0; $c2 < count( $deptids ); ++$c2 )
			{
				$deptid = Util_Format_Sanatize( $deptids[$c2], "ln" ) ;
				$deptinfo = Depts_get_DeptInfo( $dbh, $deptid ) ;
				Ops_put_OpDept( $dbh, $opid, $deptid, $deptinfo["visible"] ) ;
			}
		}
	}

	Ops_update_IdleOps( $dbh, 0, 0 ) ;
	$operators = Ops_get_AllOps( $dbh ) ;
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
	var mouse_x ; var mouse_y ;

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		}) ;

		$('#body_sub_title').html( "<img src=\"../pics/icons/agent.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" style=\"margin-right: 5px;\"> Operators" ) ;

		init_menu() ;
		toggle_menu_setup( "ops" ) ;
		init_op_dept_list() ;

		var help_tooltips = $( 'body' ).find( '.help_tooltip' ) ;
		help_tooltips.tooltip({
			event: "mouseover",
			track: true,
			delay: 0,
			showURL: false,	
			showBody: "- ",
			fade: 0,
			extraClass: "stat"
		});

		if ( "<?php echo $jump ?>" == "ops_assign" )
			show_div( "<?php echo $jump ?>" ) ;

		$('#remote_disconnect_notice').center() ;

		<?php if ( ( $action == "submit" ) && !$error ): ?>do_alert( 1, "Update Success!" ) ;<?php endif ; ?>
	});

	function init_op_dept_list() { <?php for ( $c = 0; $c < count( $departments ); ++$c ) { $department = $departments[$c] ; if ( $department["name"] != "Archive" ) { print "op_dept_moveup( $department[deptID], 0 ) ;" ; } } ?> }

	function do_edit( theopid, thename, theemail, thelogin, therate, theop2op, thetraffic )
	{
		$( "input#opid" ).val( theopid ) ;
		$( "input#name" ).val( thename ) ;
		$( "input#email" ).val( theemail ) ;
		$( "input#login" ).val( thelogin ) ;
		$( "input#password" ).val( "php-live-support" ) ;
		$( "input#rate_"+therate ).attr( "checked", true ) ;
		$( "input#op2op_"+theop2op ).attr( "checked", true ) ;
		$( "input#traffic_"+thetraffic ).attr( "checked", true ) ;
		location.href = "#a_edit" ;
	}

	function do_delete( theopid )
	{
		if ( confirm( "Delete operator profile?" ) )
			location.href = "ops.php?ses=<?php echo $ses ?>&action=delete&opid="+theopid ;
	}

	function do_submit()
	{
		var name = encodeURIComponent( $( "input#name" ).val() ) ;
		var email = $( "input#email" ).val() ;
		var login = encodeURIComponent( $( "input#login" ).val() ) ;
		var password = encodeURIComponent( $( "input#password" ).val() ) ;

		if ( name == "" )
			do_alert( 0, "Please provide a name." ) ;
		else if ( !check_email( email ) )
			do_alert( 0, "Please provide a valid email address." ) ;
		else if ( login == "" )
			do_alert( 0, "Please provide a login." ) ;
		else if ( password == "" )
			do_alert( 0, "Please provide a password." ) ;
		else
		{
			email = encodeURIComponent( email ) ;
			$('#theform').submit() ;
		}
	}

	function show_div( thediv )
	{
		if ( thediv == "ops_main" )
		{
			$('input#jump').val( "" ) ;
			$('#ops_assign').hide() ;
			$('#ops_main').show() ;
			$('#menu_ops_main').removeClass('op_submenu').addClass('op_submenu_focus') ;
			$('#menu_ops_assign').removeClass('op_submenu_focus').addClass('op_submenu') ;
		}
		else
		{
			$('input#jump').val( "ops_assign" ) ;
			$('#ops_main').hide() ;
			$('#ops_assign').show() ;
			$('#menu_ops_main').removeClass('op_submenu_focus').addClass('op_submenu') ;
			$('#menu_ops_assign').removeClass('op_submenu').addClass('op_submenu_focus') ;
		}
	}

	function op_dept_moveup( thedeptid, theopid )
	{
		$('#dept_ops_'+thedeptid).css({'opacity': 1, 'z-Index': -10}) ;

		$.ajax({
			type: "GET",
			url: "../ajax/setup_actions.php",
			data: "ses=<?php echo $ses ?>&action=moveup&deptid="+thedeptid+"&opid="+theopid+"&"+unixtime(),
			success: function(data){
				eval( data ) ;

				if ( json_data.ops != undefined )
				{
					var ops_string = "<table cellspacing=0 cellpadding=0 border=0 width=\"100%\">" ;
					for ( c = 0; c < json_data.ops.length; ++c )
					{
						var name = json_data.ops[c]["name"] ;
						var opid = json_data.ops[c]["opid"] ;
						var move_up = ( c ) ? "<a href=\"JavaScript:void(0)\" onClick=\"op_dept_moveup( "+thedeptid+", "+opid+" )\"><img src=\"../pics/icons/top.png\" width=\"14\" height=\"14\" border=\"0\" alt=\"move up\" title=\"move up\"></a>" : "<img src=\"../pics/space.gif\" width=\"14\" height=\"14\" border=\"0\">" ;

						ops_string += "<tr><td class=\"td_dept_td\" width=\"14\"><a href=\"JavaScript:void(0)\" onClick=\"op_dept_remove( "+thedeptid+", "+opid+" )\"><img src=\"../pics/icons/delete.png\" width=\"14\" height=\"14\" border=\"0\" alt=\"delete\" title=\"delete\"></a></td><td class=\"td_dept_td\" width=\"14\">"+move_up+"</td><td class=\"td_dept_td\">"+name+"</td></tr>" ;
					}
				}
				ops_string += "</table>" ;
				$('#dept_ops_'+thedeptid).html( ops_string ) ;
				setTimeout(function(){ $('#dept_ops_'+thedeptid).css({'opacity': 1, 'z-Index': 10}) ; }, 500) ;
			}
		});
	}

	function op_dept_remove( thedeptid, theopid )
	{
		$('#dept_ops_'+thedeptid).css({'opacity': 1, 'z-Index': -10}) ;

		$.ajax({
			type: "GET",
			url: "../ajax/setup_actions.php",
			data: "ses=<?php echo $ses ?>&action=op_dept_remove&deptid="+thedeptid+"&opid="+theopid+"&"+unixtime(),
			success: function(data){
				eval( data ) ;

				if ( json_data.status )
					op_dept_moveup( thedeptid, 0 ) ;
			}
		});
	}

	function remote_disconnect( theopid )
	{
		if ( confirm( "Remote disconnect console?" ) )
		{
			$('#remote_disconnect_notice').show() ;

			$.ajax({
				type: "GET",
				url: "../ajax/setup_actions.php",
				data: "ses=<?php echo $ses ?>&action=remote_disconnect&opid="+theopid+"&"+unixtime(),
				success: function(data){
					eval( data ) ;

					if ( json_data.status )
					{
						// 15 seconds of wait since console checks every 10 seconds
						setTimeout( function(){ location.href = 'ops.php?ses=<?php echo $ses ?>' ; }, 15000 ) ;
					}
					else
					{
						$('#remote_disconnect_notice').hide() ;
						do_alert( 0, "Could not remote disconnect console.  Please try again." ) ;
					}
				}
			});
		}
	}

//-->
</script>
</head>
<body>

<?php include_once( "./inc_header.php" ) ?>

		<div class="op_submenu_wrapper">
			<div style="margin-bottom: 15px;">
				<div class="info_box">
					<img src="../pics/icons/info.png" width="16" height="16" border="0" alt=""> Manage and assign operators to departments.  <b>NOTE:</b> If an operator forgets their password, you will need to edit their information and generate a new one.
					<div style="padding-left: 18px; margin-top: 5px;"><span style="font-weight: bold;">Remote Disconnect:</span> If an operator is online, click the <span class="txt_green">green</span> online "Status" icon to remote disconnect the console.</div>
					<div style="padding-left: 18px; margin-top: 15px; font-size: 14px; font-weight: bold;">Operator Login URL: <a href="<?php echo $CONF["BASE_URL"] ?>" target="_blank" style="text-decoration: none;"><?php echo $CONF["BASE_URL"] ?></a></div>
				</div>
			</div>

			<div class="op_submenu_focus" onClick="show_div('ops_main')" id="menu_ops_main">Operator Main</div>
			<div class="op_submenu" onClick="show_div('ops_assign')" id="menu_ops_assign">Assign Operators to Department</div>
			<div style="clear: both"></div>
		</div>

		<div id="ops_main">
			<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr><td class="t_tl"></td><td class="t_tm"></td><td class="t_tr"></td></tr>
			<tr>
				<td class="t_ml"></td><td class="t_mm">
					<table cellspacing=0 cellpadding=0 border=0 width="100%">
					<tr>
						<td width="40">&nbsp;</td>
						<td><div id="td_dept_header">Name</div></td>
						<td><div id="td_dept_header">Login</div></td>
						<td><div id="td_dept_header">Email</div></td>
						<td width="60" nowrap align="center"><div id="td_dept_header">Rate</div></td>
						<td width="70" nowrap align="center"><div id="td_dept_header">Op-2-Op</div></td>
						<td width="60" nowrap align="center"><div id="td_dept_header">Traffic</div></td>
						<td width="60" nowrap align="center"><div id="td_dept_header">Status</div></td>
					</tr>
					<?php
						$image_empty = "<img src=\"../pics/space.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"\">" ;
						$image_checked = "<img src=\"../pics/icons/check.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\">";
						for ( $c = 0; $c < count( $operators ); ++$c )
						{
							$operator = $operators[$c] ;

							$login = $operator["login"] ;
							$email = $operator["email"] ;
							$rate = ( $operator["rate"] ) ? $image_checked : $image_empty ;
							$op2op = ( $operator["op2op"] ) ? $image_checked : $image_empty ;
							$traffic = ( $operator["traffic"] ) ? $image_checked : $image_empty ;
							$status = ( $operator["status"] ) ? "Online" : "Offline" ;
							$status_img = ( $operator["status"] ) ? "online_green.png" : "online_grey.png" ;
							$style = ( $operator["status"] ) ? "cursor: pointer" : "" ;
							$js = ( $operator["status"] ) ? "onClick='remote_disconnect($operator[opID])'" : "" ;

							$edit_delete = "<a href=\"JavaScript:void(0)\" onClick=\"do_delete($operator[opID])\" class=\"help_tooltip nounder\" title=\"- delete operator\"><img src=\"../pics/icons/delete.png\" width=\"14\" height=\"14\" border=\"0\" alt=\"\"></a> &nbsp; <a href=\"JavaScript:void(0)\" onClick=\"do_edit( $operator[opID], '$operator[name]', '$operator[email]', '$operator[login]', $operator[rate], $operator[op2op], $operator[traffic] )\" class=\"help_tooltip nounder\" title=\"- edit operator\"><img src=\"../pics/icons/edit.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\"></a>" ;

							$td1 = "td_dept_td" ; $td2 = "td_dept_td_td" ;
							if ( $c % 2 ) { $td1 = "td_dept_td2" ; $td2 = "td_dept_td_td2" ; }

							print "
							<tr>
								<td class=\"$td1\" nowrap>$edit_delete</td>
								<td class=\"$td1\">
									<div style=\"\">$operator[name]</div>
								</td>
								<td class=\"$td1\">$login</td>
								<td class=\"$td1\">$email</td>
								<td class=\"$td1\" align=\"center\">$rate</td>
								<td class=\"$td1\" align=\"center\">$op2op</td>
								<td class=\"$td1\" align=\"center\">$traffic</td>
								<td class=\"$td1\" align=\"center\"><img src=\"../pics/icons/$status_img\" width=\"12\" height=\"12\" border=0 alt=\"$status\" title=\"$status\" style=\"$style\" $js></td>
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

		<div style="display: none;" id="ops_assign">
			<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr><td class="t_tl"></td><td class="t_tm"></td><td class="t_tr"></td></tr>
			<tr>
				<td class="t_ml"></td><td class="t_mm">
					<table cellspacing=0 cellpadding=0 border=0 width="100%">
					<tr>
						<td valign="top" width="100%">
							<table cellspacing=0 cellpadding=0 border=0 width="100%">
							<form method="POST" action="ops.php?submit">
							<input type="hidden" name="ses" value="<?php echo $ses ?>">
							<input type="hidden" name="action" value="submit_assign">
							<input type="hidden" name="jump" value="ops_assign">
							<tr>
								<td width="20">&nbsp;</td>
								<td><div id="td_dept_header">Name</div></td>
								<td><div id="td_dept_header">Email</div></td>
							</tr>
							<?php
								for ( $c = 0; $c < count( $operators ); ++$c )
								{
									$operator = $operators[$c] ;

									$td1 = "td_dept_td" ; $td2 = "td_dept_td_td" ;
									if ( $c % 2 ) { $td1 = "td_dept_td2" ; $td2 = "td_dept_td_td2" ; }

									print "
									<tr>
										<td class=\"$td1\"><input type=\"checkbox\" name=\"opids[]\" value=\"$operator[opID]\"></td>
										<td class=\"$td1\" nowrap>
											<div style=\"\">$operator[name]</div>
										</td>
										<td class=\"$td1\">$operator[email]</td>
									</tr>
									" ;
								}
							?>
							<tr>
								<td valign="top" colspan=3 nowrap>
									<div style="margin-top: 10px; padding: 10px; -moz-border-radius: 5px; border-radius: 5px;">
										Assign checked operators to department(s). "Ctrl" click to multi-select departments.<br>
										<select name="deptids[]" size="4" multiple style="width: 100%; padding: 2px; background: #D4FFD4; color: #009000;">
											<?php
												for ( $c = 0; $c < count( $departments ); ++$c )
												{
													$department = $departments[$c] ;

													if ( $department["name"] != "Archive" )
														print "<option value=\"$department[deptID]\" style=\"padding: 2px;\">$department[name]</option>" ;
												}
											?>
										</select>
										<div style="margin-top: 10px;">
											<input type="submit" value="Submit">
										</div>
									</div>
								</td>
							</tr>
							</form>
							</table>
						</td>
						<td valign="top">
							<table cellspacing=0 cellpadding=0 border=0 style="margin-left: 15px;">
							<tr>
								<td colspan=2 nowrap><div id="td_dept_header">Current operator to department assignment below.</div></td>
							</tr>
							<tr>
								<td>
									<?php
										for ( $c = 0; $c < count( $departments ); ++$c )
										{
											$department = $departments[$c] ;

											if ( $department["name"] != "Archive" )
											{
												$dept_ops = Depts_get_DeptOps( $dbh, $department["deptID"] ) ;

												print "<div class=\"td_dept_td2\"><b>$department[name]</b></div><div style=\"\"><img src=\"../pics/loading_fb.gif\" width=\"16\" height=\"11\" border=\"0\" alt=\"\"></div><div id=\"dept_ops_$department[deptID]\" style=\"position: relative; top: -16px; left: 0px; background: #FFFFFF; min-height: 25px; min-width: 25px; z-Index: 10;\"></div>" ;
											}
										}
									?>
								</td>
							</tr>
							</table>
						</td>
					</tr>
					</table>
				</td><td class="t_mr"></td>
			</tr>
			<tr><td class="t_bl"></td><td class="t_bm"></td><td class="t_br"></td></tr>
			</table>
		</div>


		<div class="edit_wrapper" style="padding: 5px; padding-top: 25px;">
			<a name="a_edit"></a><div class="edit_title">Create/Edit Operator <span class="txt_red"><?php echo $error ?></span></div>
			<div style="margin-top: 10px;">
				<table cellspacing=0 cellpadding=5 border=0>
				<form method="POST" action="ops.php?submit" id="theform">
				<input type="hidden" name="ses" value="<?php echo $ses ?>">
				<input type="hidden" name="action" value="submit">
				<input type="hidden" name="jump" id="jump" value="">
				<input type="hidden" name="opid" id="opid" value="0">
				<tr>
					<td>Operator Name</td>
					<td> <input type="text" name="name" id="name" size="30" maxlength="40" value="" onKeyPress="return nospecials(event)"></td>
					<td><img src="../pics/space.gif" width="25" height=1></td>

					<td><img src="../pics/icons/help.png" width="14" height="14" border="0" class="help_tooltip" title="- <b>Visitor Rate</b><br>Allow visitors to rate an operator when chat ends."> Visitor Rate</td>
					<td> <input type="radio" name="rate" id="rate_1" value="1" checked> Yes <input type="radio" name="rate" id="rate_0" value="0"> No</td>
				</tr>
				<tr>
					<td>Operator Email</td>
					<td> <input type="text" name="email" id="email" size="30" maxlength="160" value="" onKeyPress="return justemails(event)"></td>
					<td>&nbsp;</td>

					<td><img src="../pics/icons/help.png" width="14" height="14" border="0" class="help_tooltip" title="- <b>Op-2-Op Chat</b><br>Allow this operator to request Op-2-Op chat with other online operators."> Op-2-Op Chat</td>
					<td> <input type="radio" name="op2op" id="op2op_1" value="1" checked> Yes <input type="radio" name="op2op" id="op2op_0" value="0"> No</td>
				</tr>
				<tr>
					<td>Login</td>
					<td> <input type="text" name="login" id="login" size="30" maxlength="160" value="" onKeyPress="return logins(event)"></td>
					<td>&nbsp;</td>

					<td><img src="../pics/icons/help.png" width="14" height="14" border="0" class="help_tooltip" title="- <b>Traffic Monitor</b><br>Allow this operator to view website traffic and perform initiate chat and other proactive functions."> Traffic Monitor</td>
					<td> <input type="radio" name="traffic" id="traffic_1" value="1" checked> Yes <input type="radio" name="traffic" id="traffic_0" value="0"> No</td>
				</tr>
				<tr>
					<td>Password</td>
					<td> <input type="password" name="password" id="password" class="input" size="30" maxlength="15" value="" onKeyPress="return logins(event)"></td>
					<td>&nbsp;</td>

					<td colspan="2"> <div style=""><input type="button" value="Submit" onClick="do_submit()"> <input type="reset" value="Cancel" onClick="$( 'input#opid' ).val(0)"></div></td>
				</tr>
				</form>
				</table>
			</div>
		</div>
<?php include_once( "./inc_footer.php" ) ?>

<div id="remote_disconnect_notice" class="info_warning" style="display: none; position: absolute;">Disconnecting console... <img src="../pics/loading_fb.gif" width="16" height="11" border="0" alt=""></div>

</body>
</html>
