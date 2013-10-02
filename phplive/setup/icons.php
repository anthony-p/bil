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

	include_once( "../API/Util_Upload.php" ) ;
	include_once( "../API/Ops/get.php" ) ;
	include_once( "../API/Depts/get.php" ) ;

	$deptinfo = Array() ;

	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;
	$deptid = Util_Format_Sanatize( Util_Format_GetVar( "deptid" ), "ln" ) ;

	$upload_dir = realpath( "$CONF[DOCUMENT_ROOT]/web" ) ;

	if ( $action == "upload" )
	{
		$icon = isset( $_FILES['icon_online']['name'] ) ? "icon_online" : "icon_offline" ;

		$error = Util_Upload_File( $icon, $deptid ) ;
	}

	$departments = Depts_get_AllDepts( $dbh ) ;
	if ( $deptid )
		$deptinfo = Depts_get_DeptInfo( $dbh, $deptid ) ;
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

		$('#body_sub_title').html( "<img src=\"../pics/icons/image.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" style=\"margin-right: 5px;\"> Chat Icons" ) ;

		init_menu() ;
		toggle_menu_setup( "icons" ) ;

		<?php if ( $action && !$error ): ?>do_alert( 1, "Update Success!" ) ;<?php endif ; ?>
		<?php if ( $action && $error ): ?>do_alert( 0, "<?php echo $error ?>" ) ;<?php endif ; ?>
	});

	function switch_dept( theobject )
	{
		location.href = "icons.php?ses=<?php echo $ses ?>&deptid="+theobject.value+"&"+unixtime() ;
	}

//-->
</script>
</head>
<body>

<?php include_once( "./inc_header.php" ) ?>

		<div class="op_submenu_wrapper">
			<div style="margin-bottom: 15px;">
				<div class="info_box">
					<img src="../pics/icons/info.png" width="16" height="16" border="0" alt=""> Each department can have its own unique online/offline status icons.  Select from the below drop-down menu to choose a department.  "Global Default" chat icons will be displayed until new icons have been uploaded for that department.
					<form method="POST" action="manager_canned.php?submit" id="form_theform">
					<select name="deptid" id="deptid" style="font-size: 16px; background: #D4FFD4; color: #009000; margin-top: 15px;" OnChange="switch_dept( this )">
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
					</form>
				</div>
			</div>

			<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr><td class="t_tl"></td><td class="t_tm"></td><td class="t_tr"></td></tr>
			<tr>
				<td class="t_ml"></td><td class="t_mm">
					<?php if ( isset( $deptinfo["deptID"] ) && !$deptinfo["visible"] ): ?>
					<?php echo $deptinfo["name"] ?> Department is <a href="depts.php?ses=<?php echo $ses ?>">not visible</a> to the public.  Icons not available.

					<?php else: ?>
					<table cellspacing=0 cellpadding=0 border=0 width="100%">
					<tr>
						<form method="POST" action="icons.php?submit" enctype="multipart/form-data">
						<input type="hidden" name="ses" value="<?php echo $ses ?>">
						<input type="hidden" name="action" value="upload">
						<input type="hidden" name="deptid" value="<?php echo $deptid ?>">
						<td valign="top" width="50%">
							<div class="edit_title"><?php echo ( isset( $deptinfo["name"] ) ) ? $deptinfo["name"] : "Global Default" ; ?> ONLINE</div>
							<div style="margin-top: 10px;">
								<input type="file" name="icon_online" size="30"><p>
								<input type="submit" value="Upload Image" style="margin-top: 10px;">
							</div>
							
							<div style="margin-top: 15px;"><img src="<?php print Util_Upload_GetChatIcon( "..", "icon_online", $deptid ) ?>" border="0" alt=""></div>
						</td>
						</form>
						<form method="POST" action="icons.php?submit" enctype="multipart/form-data">
						<input type="hidden" name="ses" value="<?php echo $ses ?>">
						<input type="hidden" name="action" value="upload">
						<input type="hidden" name="deptid" value="<?php echo $deptid ?>">
						<td valign="top" width="50%">
							<div class="edit_title"><?php echo ( isset( $deptinfo["name"] ) ) ? $deptinfo["name"] : "Global Default" ; ?> OFFLINE</div>
							<div style="margin-top: 10px;">
								<input type="file" name="icon_offline" size="30"><p>
								<input type="submit" value="Upload Image" style="margin-top: 10px;">
							</div>
							
							<div style="margin-top: 15px;"><img src="<?php print Util_Upload_GetChatIcon( "..", "icon_offline", $deptid ) ?>" border="0" alt=""></div>
						</td>
						</form>
					</tr>
					</table>
					<?php endif; ?>
				</td><td class="t_mr"></td>
			</tr>
			<tr><td class="t_bl"></td><td class="t_bm"></td><td class="t_br"></td></tr>
			</table>
		</div>

<?php include_once( "./inc_footer.php" ) ?>

</body>
</html>
