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

	include_once( "../API/Ops/get.php" ) ;
	include_once( "../API/Depts/get.php" ) ;
	include_once( "../API/Chat/get_ext.php" ) ;

	$error = "" ;
	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;
	$deptid = Util_Format_Sanatize( Util_Format_GetVar( "deptid" ), "ln" ) ;
	$opid = Util_Format_Sanatize( Util_Format_GetVar( "opid" ), "ln" ) ;
	$page = ( Util_Format_Sanatize( Util_Format_GetVar( "page" ), "ln" ) ) ? Util_Format_Sanatize( Util_Format_GetVar( "page" ), "ln" ) : 0 ;
	$index = ( Util_Format_Sanatize( Util_Format_GetVar( "index" ), "ln" ) ) ? Util_Format_Sanatize( Util_Format_GetVar( "index" ), "ln" ) : 0 ;

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

	if ( $action == "delete" )
	{
		include_once( "../API/Chat/remove.php" ) ;

		$ces = Util_Format_Sanatize( Util_Format_GetVar( "ces" ), "ln" ) ;
		$created = Util_Format_Sanatize( Util_Format_GetVar( "created" ), "ln" ) ;

		if ( !Chat_remove_Transcript( $dbh, $ces, $created ) )
			$error = "Transcript delete error: $dbh[error]" ;
	}

	$text = Util_Format_Sanatize( Util_Format_GetVar( "text" ), "" ) ;
	$text = ( $text ) ? $text : "" ;
	$text_query = urlencode( $text ) ;
	$transcripts = Chat_ext_get_RefinedTranscripts( $dbh, $deptid, $opid, $text, $page, 15 ) ;

	$total_index = count($transcripts) - 1 ;
	$pages = Util_Format_Page( $page, $index, 15, $transcripts[$total_index], "transcripts.php", "ses=$ses&text=$text_query&deptid=$deptid&opid=$opid" ) ;
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
	var reset_url = "transcripts.php?ses=<?php echo $ses ?>" ;

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		}) ;

		$('#body_sub_title').html( "<img src=\"../pics/icons/chats.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" style=\"margin-right: 5px;\"> Transcripts" ) ;

		init_menu() ;
		init_tooltips() ;
		toggle_menu_setup( "trans" ) ;

		$('#input_search').focus() ;
		$('#form_search').bind("submit", function() { return false ; }) ;

		<?php if ( $action && ( $action != "search" ) && !$error ): ?>do_alert( 1, "Delete Success!" ) ;<?php endif ; ?>
	});

	function open_transcript( theces, theopname )
	{
		var url = "../ops/op_trans_view.php?ses=<?php echo $ses ?>&ces="+theces+"&id=<?php echo $admininfo["adminID"] ?>&auth=setup&"+unixtime() ;
		newwin = window.open( url, theces, "scrollbars=yes,menubar=no,resizable=1,location=no,width=650,height=450,status=0" ) ;

		if ( newwin )
			newwin.focus() ;
	}

	function switch_dept( theobject )
	{
		location.href = "transcripts.php?ses=<?php echo $ses ?>&deptid="+theobject.value ;
	}

	function switch_op( theobject )
	{
		location.href = "transcripts.php?ses=<?php echo $ses ?>&opid="+theobject.value ;
	}

	function delete_transcript( theces, thecreated )
	{
		if ( confirm( "Really delete this transcript permanently?" ) )
			location.href = "transcripts.php?ses=<?php echo $ses ?>&ces="+theces+"&created="+thecreated+"&action=delete&index=<?php echo $index ?>&page=<?php echo $page ?>" ;
	}

	function input_text_listen_search( e )
	{
		var key = -1 ;
		var shift ;

		key = e.keyCode ;
		shift = e.shiftKey ;

		if ( !shift && ( ( key == 13 ) || ( key == 10 ) ) )
			$('#btn_page_search').click() ;
	}

	function init_tooltips()
	{
		var help_tooltips = $( '#transcripts' ).find( '.help_tooltip' ) ;
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

<?php include_once( "./inc_header.php" ) ?>

		<div class="op_submenu_wrapper info_box" style="margin-bottom: 15px;">
			<img src="../pics/icons/info.png" width="16" height="16" border="0" alt=""> Transcripts can be narrowed by a specific department or operator.
			<form method="POST" action="report_trans.php?submit" id="form_theform">
			<table cellspacing=0 cellpadding=0 border=0 style="margin-top: 15px;">
			<tr>
				<td>
					<select name="deptid" id="deptid" style="font-size: 16px; background: #D4FFD4; color: #009000;" OnChange="switch_dept( this )">
					<option value="0">All Departments</option>
					<?php
						for ( $c = 0; $c < count( $departments ); ++$c )
						{
							$department = $departments[$c] ;
							$selected = ( $deptid == $department["deptID"] ) ? "selected" : "" ;
							print "<option value=\"$department[deptID]\" $selected>$department[name]</option>" ;
						}
					?>
					</select>
				</td> 
				<td><img src="../pics/space.gif" width="10" height=1></td>
				<td>
					<select name="opid" id="deptid" style="font-size: 16px; background: #D4FFD4; color: #009000;" OnChange="switch_op( this )">
					<option value="0">All Operators</option>
					<?php
						for ( $c = 0; $c < count( $operators ); ++$c )
						{
							$operator = $operators[$c] ;
							$selected = ( $opid == $operator["opID"] ) ? "selected" : "" ;
							print "<option value=\"$operator[opID]\" $selected>$operator[name]</option>" ;
						}
					?>
					</select>
				</td>
			</tr>
			</table>
			</form>
		</div>

		<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr><td class="t_tl"></td><td class="t_tm"></td><td class="t_tr"></td></tr>
		<tr>
			<td class="t_ml"></td><td class="t_mm">
				<table cellspacing=0 cellpadding=0 border=0 width="100%" id="transcripts">
				<tr><td colspan="10"><div class="page_top_wrapper"><?php echo $pages ?></div></td>
				<tr>
					<td width="20" nowrap>&nbsp;</td>
					<td width="20" nowrap>&nbsp;</td>
					<td width="80" nowrap><div id="td_dept_header">Operator</div></td>
					<td width="80" nowrap><div id="td_dept_header">Visitor</div></td>
					<td width="40"><div id="td_dept_header">Rating</div></td>
					<td width="140"><div id="td_dept_header">Created</div></td>
					<td width="80"><div id="td_dept_header">Duration</div></td>
					<td width="50"><div id="td_dept_header">Size</div></td>
					<td><div id="td_dept_header">Question</div></td>
				</tr>
				<?php
					for ( $c = 0; $c < count( $transcripts )-1; ++$c )
					{
						$transcript = $transcripts[$c] ;

						// brute fix of rare bug
						if ( $transcript["opID"] )
						{
							$operator = ( $transcript["op2op"] ) ? $operators_hash[$transcript["op2op"]] : $operators_hash[$transcript["opID"]] ;
							$created = date( "M j (g:i:s a)", $transcript["created"] ) ;
							$duration = $transcript["ended"] - $transcript["created"] ;
							$duration = ( ( $duration - 60 ) < 1 ) ? " 1 min" : Util_Format_Duration( $duration ) ;
							$question = $transcript["question"] ;
							$vname = ( $transcript["op2op"] ) ? $operators_hash[$transcript["opID"]] : $transcript["vname"] ;
							$rating = Util_Format_Stars( $transcript["rating"] ) ;
							$initiated = ( $transcript["initiated"] ) ?  "<img src=\"../pics/icons/info_initiate.gif\" width=\"10\" height=\"10\" border=\"0\" alt=\"\" class=\"help_tooltip\" title=\"- Operator Initiated Chat\"> " : "" ;

							if ( $transcript["op2op"] )
								$question = "<div class=\"text_grey\"><img src=\"../pics/icons/agent.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"Op-2-Op Chat\" title=\"Op-2-Op Chat\"></div>" ;

							$td1 = "td_dept_td" ; $td2 = "td_dept_td_td" ;
							if ( $c % 2 ) { $td1 = "td_dept_td2" ; $td2 = "td_dept_td_td2" ; }

							print "<tr id=\"tr_$transcript[ces]\"><td class=\"$td1\"><img src=\"../pics/icons/delete.png\" style=\"cursor: pointer;\" onClick=\"delete_transcript('$transcript[ces]', $transcript[created])\" class=\"help_tooltip\" title=\"- delete transcript\"></td><td class=\"$td1\"><img src=\"../pics/icons/view.png\" style=\"cursor: pointer;\" onClick=\"open_transcript('$transcript[ces]', '$operator')\" id=\"img_$transcript[ces]\" class=\"help_tooltip\" title=\"- view transcript\"></td><td class=\"$td1\" nowrap><b><div id=\"transcript_$transcript[ces]\">$initiated$operator</div></b></td><td class=\"$td1\" nowrap>$vname</td><td class=\"$td1\" align=\"center\">$rating</td><td class=\"$td1\" nowrap>$created</td><td class=\"$td1\" nowrap>$duration</td><td class=\"$td1\">$transcript[fsize]</td><td class=\"$td1\">$question</td></tr>" ;
						}
					}
					if ( $c == 0 )
						print "<tr><td colspan=7 class=\"td_dept_td\">No transcripts.</td></tr>" ;
				?>
				</table>
			</td><td class="t_mr"></td>
		</tr>
		<tr><td class="t_bl"></td><td class="t_bm"></td><td class="t_br"></td></tr>
		</table>

<?php include_once( "./inc_footer.php" ) ?>

</body>
</html>
