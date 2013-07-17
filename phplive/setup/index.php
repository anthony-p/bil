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
<script type="text/javascript" src="../js/tooltip.js"></script>

<script type="text/javascript">
<!--
	var mouse_x ; var mouse_y ;

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		}) ;

		$('#body_sub_title').html( "<img src=\"../pics/icons/asterisk.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" style=\"margin-right: 5px;\"> Home" ) ;

		init_menu() ;
		toggle_menu_setup( "home" ) ;

		var help_tooltips = $( 'body' ).find('.help_tooltip' ) ;
		help_tooltips.tooltip({
			event: "mouseover",
			track: true,
			delay: 0,
			showURL: false,	
			showBody: "- ",
			fade: 0,
			positionLeft: false,
			extraClass: "stat"
		});
	});
//-->
</script>
</head>
<body>

<?php include_once( "./inc_header.php" ) ?>
			<div class="op_submenu_wrapper">
				<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr><td class="t_tl"></td><td class="t_tm"></td><td class="t_tr"></td></tr>
				<tr>
					<td class="t_ml"></td><td class="t_mm">
						<div style="font-size: 22px; font-weight: bold; color: #545D72;">PHP Live! v.<?php echo $VERSION ?></div>

						<div class="info_box" style="margin-top: 10px;">
							<img src="../pics/icons/info.png" width="16" height="16" border="0" alt=""> Navigate the above menu or the below links to setup your PHP Live! Support system.  For assistance, please <a href="http://www.phplivesupport.com/contact.php?key=<?php echo $KEY ?>&plk=osicodes-5-ykq-m" target="_blank">contact us</a>.
							<div style="margin-top: 15px;">Basic things you should do: &#186; <a href="depts.php?ses=<?php echo $ses ?>">Create a Department</a> &#186; <a href="ops.php?ses=<?php echo $ses ?>">Create an Operator</a> &#186; <a href="ops.php?ses=<?php echo $ses ?>&jump=ops_assign">Assign Operator to a Department</a> &#186; <a href="code.php?ses=<?php echo $ses ?>">Generate HTML Code</a> &#186;</div>
						</div>

						<div style="margin-top: 25px;">
							<div class="home_box">
								<div id="td_dept_header">Management</div>
								<div style="margin-top: 5px;">
									<div class="home_menu"><img src="../pics/icons/depts.png" width="16" height="16" border="0" alt=""> <a href="depts.php?ses=<?php echo $ses ?>" class="help_tooltip" title="- <b>Departments</b><br>Create/Edit departments and update their settings.">Departments</a></div>
									<div class="home_menu"><img src="../pics/icons/agent.png" width="16" height="16" border="0" alt=""> <a href="ops.php?ses=<?php echo $ses ?>" class="help_tooltip" title="- <b>Operators</b><br>Create/Edit operators and assign operators to a department.">Operators</a></div>
								</div>
							</div>
							<div class="home_box">
								<div id="td_dept_header">Icons/Interface</div>
								<div style="margin-top: 5px;">
									<div class="home_menu"><img src="../pics/icons/image.png" width="16" height="16" border="0" alt=""> <a href="icons.php?ses=<?php echo $ses ?>" class="help_tooltip" title="- <b>Chat Icons</b><br>Upload chat icon images.  Also upload chat icons for each department (optional).">Chat Icons</a></div>
									<div class="home_menu"><img src="../pics/icons/page_white_code.png" width="16" height="16" border="0" alt=""> <a href="code.php?ses=<?php echo $ses ?>" class="help_tooltip" title="- <b>HTML Code</b><br>Generate HTML code for all departments or for just one specific department.">HTML Code</a></div>
									<div class="home_menu"><img src="../pics/icons/chest.png" width="16" height="16" border="0" alt=""> <a href="settings.php?ses=<?php echo $ses ?>" class="help_tooltip" title="- <b>Logo</b><br>Update your PHP Live! Support logo.  Also update the logo for each department (optional).">Logo</a></div>
									<div class="home_menu"><img src="../pics/icons/chest.png" width="16" height="16" border="0" alt=""> <a href="settings.php?ses=<?php echo $ses ?>&jump=themes" class="help_tooltip" title="- <b>Themes</b><br>Update your PHP Live! Support theme.">Themes</a></div>
								</div>
							</div>
							<div class="home_box">
								<div id="td_dept_header">Chats</div>
								<div style="margin-top: 5px;">
									<div class="home_menu"><img src="../pics/icons/chats.png" width="16" height="16" border="0" alt=""> <a href="transcripts.php?ses=<?php echo $ses ?>" class="help_tooltip" title="- <b>Transcripts</b><br>View past chat transcripts.">Transcripts</a></div>
									<div class="home_menu"><img src="../pics/icons/chats.png" width="16" height="16" border="0" alt=""> <a href="reports_chat_active.php?ses=<?php echo $ses ?>" class="help_tooltip" title="- <b>Transcripts</b><br>View past chat transcripts.">Active Chats</a></div>
								</div>
							</div>
							<div class="home_box">
								<div id="td_dept_header">Reports</div>
								<div style="margin-top: 5px;">
									<div class="home_menu"><img src="../pics/icons/book.png" width="16" height="16" border="0" alt=""> <a href="reports_chat.php?ses=<?php echo $ses ?>" class="help_tooltip" title="- <b>Chats</b><br>View chat request stats and history.">Chats</a></div>
									<div class="home_menu"><img src="../pics/icons/book.png" width="16" height="16" border="0" alt=""> <a href="reports_traffic.php?ses=<?php echo $ses ?>" class="help_tooltip" title="- <b>Traffic</b><br>View your website traffic stats and history.">Traffic</a></div>
									<div class="home_menu"><img src="../pics/icons/book.png" width="16" height="16" border="0" alt=""> <a href="reports_refer.php?ses=<?php echo $ses ?>" class="help_tooltip" title="- <b>Refer URLs</b><br>View refer URL stats and history.">Refer URLs</a></div>
									<div class="home_menu"><img src="../pics/icons/book.png" width="16" height="16" border="0" alt=""> <a href="reports_marketing.php?ses=<?php echo $ses ?>" class="help_tooltip" title="- <b>Click Track</b><br>View marketing click report of your campaigns.">Market Clicks</a></div>
								</div>
							</div>
							<div class="home_box">
								<div id="td_dept_header">Marketing</div>
								<div style="margin-top: 5px;">
									<div class="home_menu"><img src="../pics/icons/graph.png" width="16" height="16" border="0" alt=""> <a href="marketing.php?ses=<?php echo $ses ?>" class="help_tooltip" title="- <b>Click Track</b><br>Integrate your ad campaigns to provide targeted support.">Social Media</a></div>
									<div class="home_menu"><img src="../pics/icons/graph.png" width="16" height="16" border="0" alt=""> <a href="marketing_click.php?ses=<?php echo $ses ?>" class="help_tooltip" title="- <b>Click Track</b><br>Integrate your ad campaigns to provide targeted support.">Click Track</a></div>
									<div class="home_menu"><img src="../pics/icons/graph.png" width="16" height="16" border="0" alt=""> <a href="marketing_marquee.php?ses=<?php echo $ses ?>" class="help_tooltip" title="- <b>Marquees</b><br>Inhance your marketing efforts with chat window marquees.">Marquees</a></div>
								</div>
							</div>
							<div class="home_box">
								<div id="td_dept_header">System Settings</div>
								<div style="margin-top: 5px;">
									<div class="home_menu"><img src="../pics/icons/chest.png" width="16" height="16" border="0" alt=""> <a href="settings.php?ses=<?php echo $ses ?>&jump=time" class="help_tooltip" title="- <b>Time Zone</b><br>Update the timezone to your current location.">Time Zone</a></div>
									<div class="home_menu"><img src="../pics/icons/chest.png" width="16" height="16" border="0" alt=""> <a href="settings.php?ses=<?php echo $ses ?>&jump=langs" class="help_tooltip" title="- <b>Languages</b><br>Update the language for the visitor chat area.">Languages</a></div>
									<div class="home_menu"><img src="../pics/icons/chest.png" width="16" height="16" border="0" alt=""> <a href="settings.php?ses=<?php echo $ses ?>&jump=eips" class="help_tooltip" title="- <b>Excluded IPs</b><br>Add/Delete IPs to be excluded from the footprint logging.">Excluded IPs</a></div>
									<div class="home_menu"><img src="../pics/icons/chest.png" width="16" height="16" border="0" alt=""> <a href="settings.php?ses=<?php echo $ses ?>&jump=sips" class="help_tooltip" title="- <b>Spam IPs</b><br>View/Delete operator reported Spam IPs.">Spam IPs</a></div>
									<div class="home_menu"><img src="../pics/icons/chest.png" width="16" height="16" border="0" alt=""> <a href="settings.php?ses=<?php echo $ses ?>&jump=profile" class="help_tooltip" title="- <b>System Profile</b><br>Update system profile.">Setup Profile</a></div>
								</div>
							</div>
							<div class="home_box">
								<div id="td_dept_header">Extras</div>
								<div style="margin-top: 5px;">
									<div class="home_menu"><img src="../pics/icons/switch.png" width="16" height="16" border="0" alt=""> <a href="external.php?ses=<?php echo $ses ?>" class="help_tooltip" title="- <b>External URLs</b><br>Embed frequently visited webpages into the operator console.">External URLs</a></div>
								</div>
							</div>
							<div style="clear: both;"></div>
						</div>
					</td><td class="t_mr"></td>
				</tr>
				<tr><td class="t_bl"></td><td class="t_bm"></td><td class="t_br"></td></tr>
				</table>
			</div>
<?php include_once( "./inc_footer.php" ) ?>

</body>
</html>
