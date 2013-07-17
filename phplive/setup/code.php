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
	include_once( "../API/Depts/get.php" ) ;
	include_once( "../API/Vars/get.php" ) ;

	$deptinfo = Array() ;

	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;
	$deptid = Util_Format_Sanatize( Util_Format_GetVar( "deptid" ), "ln" ) ;
	$flag = Util_Format_Sanatize( Util_Format_GetVar( "flag" ), "ln" ) ;
	$update = Util_Format_Sanatize( Util_Format_GetVar( "update" ), "ln" ) ;

	$departments = Depts_get_AllDepts( $dbh ) ;
	if ( $deptid )
		$deptinfo = Depts_get_DeptInfo( $dbh, $deptid ) ;

	$total_ops = Ops_get_TotalOps( $dbh ) ;

	if ( $update )
	{
		include_once( "../API/Vars/put.php" ) ;

		Vars_put_Var( $dbh, "code", $flag ) ;
	}
	$vars = Vars_get_Vars( $dbh ) ;
	if ( isset( $vars["code"] ) )
		$flag = $vars["code"] ;

	$now = time() ;
	$base_url = $CONF["BASE_URL"] ;
	$code = "&lt;!-- BEGIN PHP Live! code, (c) OSI Codes Inc. --&gt;&lt;script type=\"text/javascript\" src=\"%%base_url%%/js/phplive.js.php?d=$deptid&base_url=%%base_url_query%%&text=\"&gt;&lt;/script&gt;&lt;!-- END PHP Live! code, (c) OSI Codes Inc. --&gt;" ;

	if ( $flag == "http" )
		$base_url = preg_replace( "/(http:)|(https:)/", "http:", $base_url ) ;
	else if ( $flag == "https" )
		$base_url = preg_replace( "/(http:)|(https:)/", "https:", $base_url ) ;
	else
		$base_url = preg_replace( "/(http:)|(https:)/", "", $base_url ) ;

	$base_url_query = urlencode( preg_replace( "/http/", "hphp", $base_url ) ) ;
	$thecode = preg_replace( "/%%base_url%%/", $base_url, $code ) ;
	$thecode = preg_replace( "/%%base_url_query%%/", $base_url_query, $thecode ) ;

	$code_html = preg_replace( "/&lt;/", "<", $thecode ) ;
	$code_html = preg_replace( "/&gt;/", ">", $code_html ) ;
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

<script type="text/javascript">
<!--
	var mouse_x ; var mouse_y ;
	var thecode = '<?php echo $thecode ?>' ;
	thecode = thecode.replace( /&lt;/g, "<" ) ;
	thecode = thecode.replace( /&gt;/g, ">" ) ;

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		}) ;

		$('#body_sub_title').html( "<img src=\"../pics/icons/page_white_code.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" style=\"margin-right: 5px;\"> HTML Code" ) ;

		init_menu() ;
		toggle_menu_setup( "html" ) ;

		populate_code() ;
	});

	function switch_dept( theobject )
	{
		location.href = "code.php?ses=<?php echo $ses ?>&deptid="+theobject.value+"&flag=<?php echo $flag ?>&"+unixtime() ;
	}

	function populate_code()
	{
		$('#textarea_code').val( thecode ) ;
	}

	function toggle_code( theflag )
	{
		location.href = "code.php?ses=<?php echo $ses ?>&deptid=<?php echo $deptid ?>&flag="+theflag+"&update=1&"+unixtime() ;
	}

//-->
</script>
</head>
<body>

<?php include_once( "./inc_header.php" ) ?>

		<div class="op_submenu_wrapper">
			<div style="margin-bottom: 15px;">
				<div class="info_box">
					<img src="../pics/icons/info.png" width="16" height="16" border="0" alt=""> HTML can be created to display all departments or just one specific department.  This is helpful to the visitor for direct access to a department.  Select from the below drop-down menu to choose a department or to generate HTML code to display all departments.
					<form method="POST" action="manager_canned.php?submit" id="form_theform">
					<select name="deptid" id="deptid" style="font-size: 16px; background: #D4FFD4; color: #009000; margin-top: 15px;" OnChange="switch_dept( this )">
					<option value="0">Global Default</option>
					<?php
						$ops_assigned = 0 ;
						for ( $c = 0; $c < count( $departments ); ++$c )
						{
							$department = $departments[$c] ;
							$ops = Depts_get_DeptOps( $dbh, $department["deptID"] ) ;
							if ( count( $ops ) )
								$ops_assigned = 1 ;

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
					<?php echo $deptinfo["name"] ?> Department is <a href="depts.php?ses=<?php echo $ses ?>">not visible</a> to the public.  HTML not available.
					<?php elseif ( !count( $departments ) ): ?>
					No <a href="depts.php?ses=<?php echo $ses ?>">departments</a> have been created.  HTML not available.
					<?php elseif ( !$total_ops ): ?>
					No <a href="ops.php?ses=<?php echo $ses ?>">operators</a> have been created.  HTML not available.
					<?php elseif ( !$ops_assigned ): ?>
					No operators have been <a href="ops.php?ses=<?php echo $ses ?>&jump=ops_assign">assigned to a department</a>.  HTML not available.

					<?php else: ?>
					<div style="font-weight: bold; font-size: 16px;">HTML Code for <span class="info_box"><?php echo ( isset( $deptinfo["name"] ) ) ? "$deptinfo[name] Department only" : "ALL Departments" ?></span></div>
					<div style="margin-top: 10px;">Copy and paste the below HTML code anywhere within your website.  Please do not alter the code to avoid possible chat function issues.</div>
					<form>
					<div style="margin-top: 10px;">
						<div style="float: left;" class="info_warning"><input type="radio" name="type" value="1" id="" <?php echo ( !isset( $vars["code"] ) || !$vars["code"] ) ? "checked" : "" ?> onClick="toggle_code( '' )"> Toggle between <b><i>http:/https:</i></b> based on current URL protocol. (recommended)</div>
						<div style="float: left; margin-left: 15px;" class="info_warning"><input type="radio" name="type" value="2" <?php echo ( $vars["code"] == "http" ) ? "checked" : "" ?> onClick="toggle_code( 'http' )"> Only <b><i>http:</i></b> requests.</div>
						<div style="float: left; margin-left: 15px;" class="info_warning"><input type="radio" name="type" value="2" <?php echo ( $vars["code"] == "https" ) ? "checked" : "" ?> onClick="toggle_code( 'https' )"> Only <b><i>https:</i></b> requests.</div>
						<div style="clear: both;"></div>
					</div>
					</form>
					<div style="margin-top: 10px;"><textarea wrap="virtual" id="textarea_code" style="width: 99%; height: 80px; resize: none;" onMouseDown="setTimeout(function(){ $('#textarea_code').select(); }, 200);"></textarea></div>
		
					<div style="margin-top: 10px;">The above code will produce the below Support Icon Link.</div>
					<div id="output_code" style="margin-top: 5px;"><?php echo $code_html ?></div>

					<div class="info_neutral" style="margin-top: 20px;">
						<div style="font-weight: bold; font-size: 14px;">Direct Link TEXT ONLY code</div>
						<div style="margin-top: 10px;">
							To display TEXT ONLY link, simply provide a value for the <span class="txt_blue"><b>text=</b></span> query on the above code:<br><br>
							
							example:
							<div style="margin-top: 5px; padding: 5px; background: #FFFFFF; border: 1px solid #E9E9E9;">
							<code>
								<?php
									$text_only = preg_replace( "/</", "&lt;", $code_html ) ;
									$text_only = preg_replace( "/text=/", "<span class=\"txt_blue\"><b>text=Click for Live Support!</b></span>", $text_only ) ;
									print $text_only ;
								?>
							</code>
							</div>
						
						</div>
					</div>

					<?php endif; ?>
				</td><td class="t_mr"></td>
			</tr>
			<tr><td class="t_bl"></td><td class="t_bm"></td><td class="t_br"></td></tr>
			</table>
		</div>

<?php include_once( "./inc_footer.php" ) ?>

</body>
</html>
