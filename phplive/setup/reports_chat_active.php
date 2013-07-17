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
	include_once( "../API/Ops/get.php" ) ;
	include_once( "../API/Chat/get_ext.php" ) ;

	$departments = Depts_get_AllDepts( $dbh ) ;
	$operators = Ops_get_AllOps( $dbh ) ;

	// make hash for quick refrence
	$operators_hash = Array() ;
	for ( $c = 0; $c < count( $operators ); ++$c )
	{
		$operator = $operators[$c] ;
		$operators_hash[$operator["opID"]] = $operator["name"] ;
	}

	$dept_hash = Array() ;
	for ( $c = 0; $c < count( $departments ); ++$c )
	{
		$department = $departments[$c] ;
		$dept_hash[$department["deptID"]] = $department["name"] ;
	}

	$active_requests = Chat_ext_get_AllRequests( $dbh ) ;
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
	var refresh_counter = 60 ;

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		}) ;

		$('#body_sub_title').html( "<img src=\"../pics/icons/book.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" style=\"margin-right: 5px;\"> Active Chats" ) ;

		init_menu() ;
		toggle_menu_setup( "rchats" ) ;

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

		var si_refresh = setInterval(function(){
			if ( refresh_counter <= 0 )
				location.href = "reports_chat_active.php?ses=<?php echo $ses ?>&"+unixtime() ;
			else
			{
				$('#refresh_counter').html( pad( refresh_counter, 2 ) ) ;
				--refresh_counter ;
			}
		}, 1000) ;
	});

	function open_chat( theces )
	{
		window.open( "chat.php?ses=<?php echo $ses ?>&ces="+theces+"&"+unixtime(), "Chat with: ", 'scrollbars=no,resizable=yes,menubar=no,location=no,screenX=50,screenY=100,width=550,height=410' ) ;
	}

//-->
</script>
</head>
<body>

<?php include_once( "./inc_header.php" ) ?>

		<div class="op_submenu_wrapper">
			<div class="op_submenu" onClick="location.href='reports_chat.php?ses=<?php echo $ses ?>'">Chat Reports</div>
			<div class="op_submenu_focus">Active Chats</div>
			<div style="clear: both"></div>
		</div>

		<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr><td class="t_tl"></td><td class="t_tm"></td><td class="t_tr"></td></tr>
		<tr>
			<td class="t_ml"></td><td class="t_mm">
				<div style="margin-bottom: 15px;" class="info_box"><img src="../pics/icons/info.png" width="16" height="16" border="0" alt=""> This page will auto refresh every minute to display the current active chats.  The next refresh will be in <span id="refresh_counter">60</span> seconds.</div>
				<table cellspacing=0 cellpadding=0 border=0 width="100%">
				<tr>
					<!-- <td width="20" nowrap>&nbsp;</td> -->
					<td width="80" nowrap><div id="td_dept_header">Operator</div></td>
					<td width="80" nowrap><div id="td_dept_header">Visitor</div></td>
					<td width="80" nowrap><div id="td_dept_header">Department</div></td>
					<td width="140"><div id="td_dept_header">Created</div></td>
					<td width="140"><div id="td_dept_header">Duration</div></td>
					<td width="80"><div id="td_dept_header">IP</div></td>
					<td><div id="td_dept_header">Question</div></td>
				</tr>
				<?php
					for ( $c = 0; $c < count( $active_requests ); ++$c )
					{
						$chat = $active_requests[$c] ;

						$operator = $operators_hash[$chat["opID"]] ;
						$visitor = $chat["vname"] ;
						$department = $dept_hash[$chat["deptID"]] ;
						$created = date( "M j (g:i:s a)", $chat["created"] ) ;
						$duration = time() - $chat["created"] ;
						$duration = ( ( $duration - 60 ) < 1 ) ? " 1 min" : Util_Format_Duration( $duration ) ;
						$ip = $chat["ip"] ;
						$question = nl2br( $chat["question"] ) ;

						$td1 = "td_dept_td" ; $td2 = "td_dept_td_td" ;
						if ( $c % 2 ) { $td1 = "td_dept_td2" ; $td2 = "td_dept_td_td2" ; }

						print "<tr id=\"tr_$chat[ces]\"><!-- <td class=\"$td1\"><img src=\"../pics/icons/view.png\" style=\"cursor: pointer;\" onClick=\"open_chat('$chat[ces]')\" id=\"img_$chat[ces]\"></td> --><td class=\"$td1\" nowrap><b><div id=\"chat_$chat[ces]\">$operator</div></b></td><td class=\"$td1\" nowrap>$visitor</td><td class=\"$td1\" nowrap>$department</td><td class=\"$td1\" nowrap>$created</td><td class=\"$td1\" nowrap>$duration</td><td class=\"$td1\" nowrap>$ip</td><td class=\"$td1\">$question</td></tr>" ;
					}
					if ( $c == 0 )
						print "<tr><td colspan=7 class=\"td_dept_td\">No active chats at this time.</td></tr>" ;
				?>
				</table>
			</td><td class="t_mr"></td>
		</tr>
		<tr><td class="t_bl"></td><td class="t_bm"></td><td class="t_br"></td></tr>
		</table>

<?php include_once( "./inc_footer.php" ) ?>

<div id="iframe_wrapper" style="display: none; position: absolute; z-index: 100;">
	<div><iframe id="iframe_edit" name="iframe_edit" style="width: 550px; height: 300px; border: 0px;" src="" scrolling="auto"></iframe></div>
	<div id="op_footer" style="height: 23px;"></div>
</div>

</body>
</html>
