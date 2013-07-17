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

	include_once( "../API/External/get.php" ) ;
	include_once( "../API/Ops/get.php" ) ;

	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;

	if ( $action == "submit" )
	{
		include_once( "../API/External/put.php" ) ;
		include_once( "../API/External/remove.php" ) ;

		$extid = Util_Format_Sanatize( Util_Format_GetVar( "extid" ), "ln" ) ;
		$name = Util_Format_Sanatize( Util_Format_GetVar( "name" ), "ln" ) ;
		$url = Util_Format_Sanatize( Util_Format_GetVar( "url" ), "url" ) ;
		$opids = Util_Format_Sanatize( Util_Format_GetVar( "opids" ), "a" ) ;

		if ( $id = External_put_External( $dbh, $extid, $name, $url ) )
		{
			External_remove_AllExtOps( $dbh, $id ) ;
			for ( $c = 0; $c < count( $opids ); ++$c )
			{
				$opid = Util_Format_Sanatize( $opids[$c], "ln" ) ;
				External_put_ExtOp( $dbh, $id, $opid ) ;
			}
		}
		else	
			$error = "$name is already in use." ;
	}
	else if ( $action == "delete" )
	{
		include_once( "../API/External/remove.php" ) ;

		$extid = Util_Format_Sanatize( Util_Format_GetVar( "extid" ), "ln" ) ;

		External_remove_External( $dbh, $extid ) ;
	}

	$operators = Ops_get_AllOps( $dbh ) ;
	$externals = External_get_AllExternal( $dbh ) ;
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
	var ops = new Array() ;

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		}) ;

		$('#body_sub_title').html( "<img src=\"../pics/icons/switch.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" style=\"margin-right: 5px;\"> External URLs" ) ;

		init_menu() ;
		toggle_menu_setup( "external" ) ;

		<?php if ( ( $action == "submit" ) && !$error ): ?>do_alert( 1, "Update Success!" ) ;<?php endif ; ?>
	});

	function do_options( theoption, theextid, thename, theurl, theops )
	{
		if ( theoption == "edit" )
		{
			ops = theops.split( "," ) ;
			$( "input#extid" ).val( theextid ) ;
			$( "input#name" ).val( thename ) ;
			$( "input#url" ).val( theurl ) ;

			check_all( "undefined" ) ;
			for ( c = 0; c < ops.length; ++c )
				$('#ck_op_'+ops[c]).attr( 'checked', true ) ;

			location.href = "#a_edit" ;
		}
		else if ( theoption == "delete" )
		{
			if ( confirm( "Delete this External URL?" ) )
				location.href = "external.php?ses=<?php echo $ses ?>&action=delete&extid="+theextid ;
		}
	}

	function do_submit()
	{
		var name = $( "input#name" ).val() ;
		var url = $( "input#url" ).val() ;
		var flag = 0 ;

		$( '*', 'body' ).each( function () {
			var div_name = $(this).attr('id') ;
			if ( div_name.indexOf( "ck_op_" ) == 0 )
			{
				if ( $(this).attr( 'checked' ) )
					flag = 1 ;
			}
		}) ;

		if ( name == "" )
			do_alert( 0, "Please provide the external url name." ) ;
		else if ( url == "" )
			do_alert( 0, "Please provide the external url." ) ;
		else if ( !flag )
			do_alert( 0, "At least one operator should be checked." ) ;
		else
			$('#theform').submit() ;
	}

	function launch_external( theextid, theurl )
	{
		var unique = unixtime() ;
		window.open(theurl, unique, 'scrollbars=yes,menubar=yes,resizable=1,location=yes,toolbar=yes,status=1') ;
	}

	function check_all( theobject )
	{
		if ( ( typeof( theobject ) != "undefined" ) && ( theobject.checked ) )
		{
			$( '*', 'body' ).each( function () {
				var div_name = $(this).attr('id') ;
				if ( div_name.indexOf( "ck_op_" ) == 0 )
					$(this).attr( 'checked', true ) ;
			}) ;
		}
		else
		{
			$( '*', 'body' ).each( function () {
				var div_name = $(this).attr('id') ;
				if ( div_name.indexOf( "ck_op_" ) == 0 )
					$(this).attr( 'checked', false ) ;
			}) ;
		}
	}

	function reset_check_all()
	{
		$('#ck_op_all').attr( 'checked', false ) ;
	}

//-->
</script>
</head>
<body>

<?php include_once( "./inc_header.php" ) ?>

		<div class="op_submenu_wrapper">
			<div style="margin-bottom: 15px;">
				<div class="info_box">
					<img src="../pics/icons/info.png" width="16" height="16" border="0" alt=""> External URLs allow operators to easily access a webpage that they visit often.  This could be a "Member Search", "FAQ", "Company Directory" or other useful URLs.  The URLs will appear on the operator console footer menu.
				</div>
			</div>

			<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr><td class="t_tl"></td><td class="t_tm"></td><td class="t_tr"></td></tr>
			<tr>
				<td class="t_ml"></td><td class="t_mm">
					<table cellspacing=1 cellpadding=0 border=0 width="100%">
					<form>
					<tr>
						<td width="40">&nbsp;</td>
						<td><div id="td_dept_header">Name</div></td>
						<td width="100%"><div id="td_dept_header">URL</div></td>
					</tr>
					<?php
						for ( $c = 0; $c < count( $externals ); ++$c )
						{
							$external = $externals[$c] ;
							$ops = External_get_ExtOps( $dbh, $external["extID"] ) ;

							$ops_string = $ops_js_string = "" ;
							for ( $c2 = 0; $c2 < count( $ops ); ++$c2 )
							{
								$op = $ops[$c2] ;
								$ops_string .= " <div class=\"li_op\">$op[name]</div>" ;
								$ops_js_string .= "$op[opID]," ;
							}
							$ops_js_string = substr_replace( $ops_js_string, "", -1 ) ;

							$td1 = "td_dept_td" ; $td2 = "td_dept_td_td" ;
							if ( $c % 2 ) { $td1 = "td_dept_td2" ; $td2 = "td_dept_td_td2" ; }

							print "
								<tr>
									<td class=\"$td1\" nowrap><a href=\"JavaScript:void(0)\" onClick=\"do_options( 'delete', $external[extID], '$external[name]', '$external[url]', '$ops_js_string' )\" class=\"nounder\"><img src=\"../pics/icons/delete.png\" width=\"14\" height=\"14\" border=\"0\" alt=\"\"></a> &nbsp; <a href=\"JavaScript:void(0)\" onClick=\"do_options( 'edit', $external[extID], '$external[name]', '$external[url]', '$ops_js_string' )\" class=\"nounder\"><img src=\"../pics/icons/edit.png\" width=\"14\" height=\"14\" border=\"0\" alt=\"\"></a></td>
									<td class=\"$td1\" nowrap>$external[name]</td>
									<td class=\"$td1\">
										<div style=\"margin-bottom: 5px;\"><a href=\"JavaScript:void(0)\" onClick=\"launch_external( '$external[extID]', '$external[url]' )\">$external[url]</a></div>
										$ops_string
									</td>
								</tr>
							" ;
						}
					?>
					</form>
					</table>
				</td><td class="t_mr"></td>
			</tr>
			<tr><td class="t_bl"></td><td class="t_bm"></td><td class="t_br"></td></tr>
			</table>
		</div>

		<div style="padding: 5px; padding-top: 25px;">
			<a name="a_edit"></a><div class="edit_title">Create/Edit External URL <span class="txt_red"><?php echo $error ?></span></div>
			<div style="margin-top: 10px;">
				<table cellspacing=0 cellpadding=5 border=0>
				<form method="POST" action="external.php?submit" id="theform">
				<input type="hidden" name="action" value="submit">
				<input type="hidden" name="ses" value="<?php echo $ses ?>">
				<input type="hidden" name="extid" id="extid" value="0">
				<tr>
					<td>Name (example: "Client Search")<br><input type="text" name="name" id="name" size="50" maxlength="15" value=""></td>
				</tr>
				<tr>
					<td>Target URL<br><input type="text" name="url" id="url" size="120" maxlength="255" value=""></td>
				</tr>
				<tr>
					<td>
						<div><img src="../pics/icons/alert.png" width="16" height="16" border="0" alt=""> It is recommended that each operator has no more than THREE external URLs to maintain proper operator console formatting.</div>
						<div style="margin-top: 5px;">Operator(s) who can access this external URL:</div>
						<div id="li_ops" style="margin-top: 5px;">
						<div class="li_op_focus"><input type="checkbox" id="ck_op_all" name="opids[]" value="all" onClick="check_all(this)"> Check All</div>
						<?php
							for ( $c = 0; $c < count( $operators ); ++$c )
							{
								$operator = $operators[$c] ;

								if ( $operator["name"] != "Archive" )
									print "<div class=\"li_op\"><input type=\"checkbox\" id=\"ck_op_$operator[opID]\" name=\"opids[]\" value=\"$operator[opID]\" onClick=\"reset_check_all()\"> $operator[name]</div>" ;
							}
						?>
						<div style="clear: both;"></div>
						</div>
					</td>
				</tr>
				<tr>
					<td> <div style=""><input type="button" value="Submit" onClick="do_submit()"> <input type="reset" value="Reset" onClick="$( 'input#extid' ).val(0)"></div></td>
				</tr>
				</form>
				</table>
			</div>
		</div>

<?php include_once( "./inc_footer.php" ) ?>
</body>
</html>
