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

	$deptinfo = $opinfo = Array() ;

	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;
	$deptid = Util_Format_Sanatize( Util_Format_GetVar( "deptid" ), "ln" ) ;
	$opid = Util_Format_Sanatize( Util_Format_GetVar( "opid" ), "ln" ) ;
	$m = Util_Format_Sanatize( Util_Format_GetVar( "m" ), "ln" ) ;
	$d = Util_Format_Sanatize( Util_Format_GetVar( "d" ), "ln" ) ;
	$y = Util_Format_Sanatize( Util_Format_GetVar( "y" ), "ln" ) ;

	if ( !$m )
		$m = date( "m", time() ) ;
	if ( !$d )
		$d = date( "j", time() ) ;
	if ( !$y )
		$y = date( "Y", time() ) ;

	$today = mktime( 0, 0, 1, $m, $d, $y ) ;
	$stat_start = mktime( 0, 0, 1, $m, 1, $y ) ;
	$stat_end = mktime( 0, 0, 1, $m+1, 0, $y ) ;
	$stat_end_day = date( "j", $stat_end ) ;

	$departments = Depts_get_AllDepts( $dbh ) ;
	$operators = Ops_get_AllOps( $dbh ) ;
	if ( $deptid ) { $deptinfo = Depts_get_DeptInfo( $dbh, $deptid ) ; }
	if ( $opid ) { $opinfo = Ops_get_OpInfo( $dbh, $opid ) ; }

	$requests_timespan = Chat_ext_get_RequestsRangeHash( $dbh, $stat_start, $stat_end ) ;
	$month_stats = Array() ;
	$month_total_requests = $month_total_taken = $month_total_declined = $month_total_msg = $month_total_initiated = $month_total_initiated_taken = 0 ;
	$month_max = $js_stat_depts = $js_stat_ops = "" ;
	foreach ( $requests_timespan as $sdate => $deptop )
	{
		foreach ( $deptop["depts"] as $key => $value )
		{
			if ( !isset( $month_stats[$sdate] ) )
			{
				$month_stats[$sdate] = Array() ;
				$month_stats[$sdate]["requests"] = $month_stats[$sdate]["taken"] = $month_stats[$sdate]["declined"] = $month_stats[$sdate]["message"] = $month_stats[$sdate]["initiated"] = $month_stats[$sdate]["initiated_taken"] = 0 ;
			}
			
			$month_stats[$sdate]["requests"] += $value["requests"] ;
			$month_stats[$sdate]["taken"] += $value["taken"] ;
			$month_stats[$sdate]["declined"] += $value["declined"] ;
			$month_stats[$sdate]["message"] += $value["message"] ;
			$month_stats[$sdate]["initiated"] += $value["initiated"] ;
			$month_stats[$sdate]["initiated_taken"] += $value["initiated_taken"] ;

			// 0 date means total of timespan so don't add that
			if ( $sdate )
			{
				$month_total_requests += $value["requests"] ;
				$month_total_taken += $value["taken"] ;
				$month_total_declined += $value["declined"] ;
				$month_total_initiated += $value["initiated"] ;
				$month_total_initiated_taken += $value["initiated_taken"] ;
			}

			$js_stat_depts .= "stat_depts[$sdate][$key]['requests'] = $value[requests] ; stat_depts[$sdate][$key]['taken'] = $value[taken] ; stat_depts[$sdate][$key]['declined'] = $value[declined] ; stat_depts[$sdate][$key]['message'] = $value[message] ; stat_depts[$sdate][$key]['initiated'] = $value[initiated] ; stat_depts[$sdate][$key]['initiated_taken'] = $value[initiated_taken] ; " ;
		}
		foreach ( $deptop["ops"] as $key => $value )
		{
			$js_stat_ops .= "stat_ops[$sdate][$key]['requests'] = $value[requests] ; stat_ops[$sdate][$key]['taken'] = $value[taken] ; stat_ops[$sdate][$key]['declined'] = $value[declined] ; stat_ops[$sdate][$key]['message'] = $value[message] ; stat_ops[$sdate][$key]['initiated'] = $value[initiated] ; stat_ops[$sdate][$key]['initiated_taken'] = $value[initiated_taken] ; " ;
		}

		if ( isset( $month_stats[$sdate]["requests"] ) && ( $month_stats[$sdate]["requests"] > $month_max ) && $sdate )
			$month_max = $month_stats[$sdate]["requests"] ;
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

	var stat_depts = new Object ; stat_depts[0] = new Object ;
	var stat_ops = new Object ; stat_ops[0] = new Object ;
	var stat_start = <?php echo $stat_start ?> ;

	<?php
		for ( $c = 1; $c <= $stat_end_day; ++$c )
		{
			$stat_day = mktime( 0, 0, 1, $m, $c, $y ) ;
			print "stat_depts[$stat_day] = new Object; stat_ops[$stat_day] = new Object; " ;

			for ( $c2 = 0; $c2 < count( $departments ); ++$c2 )
			{
				$department = $departments[$c2] ;
				print "stat_depts[$stat_day][$department[deptID]] = new Object; " ;
				print "stat_depts[0][$department[deptID]] = new Object; " ;
			}
			for ( $c3 = 0; $c3 < count( $operators ); ++$c3 )
			{
				$operator = $operators[$c3] ;
				print "stat_ops[$stat_day][$operator[opID]] = new Object; " ;
				print "stat_ops[0][$operator[opID]] = new Object; " ;
			}
		}
	?>

	eval( "<?php echo $js_stat_depts ?>" ) ;
	eval( "<?php echo $js_stat_ops ?>" ) ;

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		}) ;

		$('#body_sub_title').html( "<img src=\"../pics/icons/book.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" style=\"margin-right: 5px;\"> Report: Chats" ) ;

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

		reset_date() ;
	});

	function close_iframe()
	{
		$('#iframe_wrapper').hide() ;
	}

	function reset_date()
	{
		select_date( 0, "<?php echo date( "M j, Y", $stat_start ) ?> - <?php echo date( "M j, Y", $stat_end ) ?>" ) ;
	}

	function select_date( theunix, thedayexpand )
	{
		var stat_total_requests = stat_total_taken = stat_total_declined = stat_total_message = stat_total_initiated = stat_total_initiated_taken = 0 ;
		stat_start = theunix ;
		$('#stat_day_expand').html( thedayexpand ) ;

		for ( var deptid in stat_depts[theunix] )
		{
			if ( typeof( stat_depts[theunix][deptid]["requests"] ) != "undefined" )
			{
				$('#stat_req_dept_requests_'+deptid).html( stat_depts[theunix][deptid]["requests"] ) ;
				$('#stat_req_dept_taken_'+deptid).html( stat_depts[theunix][deptid]["taken"] ) ;
				$('#stat_req_dept_declined_'+deptid).html( stat_depts[theunix][deptid]["declined"] ) ;
				$('#stat_req_dept_message_'+deptid).html( stat_depts[theunix][deptid]["message"] ) ;
				$('#stat_req_dept_initiated_'+deptid).html( stat_depts[theunix][deptid]["initiated"] ) ;
				$('#stat_req_dept_initiated_taken_'+deptid).html( stat_depts[theunix][deptid]["initiated_taken"] ) ;

				stat_total_requests += stat_depts[theunix][deptid]["requests"] ;
				stat_total_taken += stat_depts[theunix][deptid]["taken"] ;
				stat_total_declined += stat_depts[theunix][deptid]["declined"] ;
				stat_total_initiated += stat_depts[theunix][deptid]["initiated"] ;
				stat_total_initiated_taken += stat_depts[theunix][deptid]["initiated_taken"] ;
			}
			else
			{
				$('#stat_req_dept_requests_'+deptid).html( 0 ) ;
				$('#stat_req_dept_taken_'+deptid).html( 0 ) ;
				$('#stat_req_dept_declined_'+deptid).html( 0 ) ;
				$('#stat_req_dept_message_'+deptid).html( 0 ) ;
				$('#stat_req_dept_initiated_'+deptid).html( 0 ) ;
				$('#stat_req_dept_initiated_taken_'+deptid).html( 0 ) ;
			}
		}
		for ( var opid in stat_ops[theunix] )
		{
			if ( typeof( stat_ops[theunix][opid]["requests"] ) != "undefined" )
			{
				$('#stat_req_op_requests_'+opid).html( stat_ops[theunix][opid]["requests"] ) ;
				$('#stat_req_op_taken_'+opid).html( stat_ops[theunix][opid]["taken"] ) ;
				$('#stat_req_op_declined_'+opid).html( stat_ops[theunix][opid]["declined"] ) ;
				$('#stat_req_op_initiated_'+opid).html( stat_ops[theunix][opid]["initiated"] ) ;
				$('#stat_req_op_initiated_taken_'+opid).html( stat_ops[theunix][opid]["initiated_taken"] ) ;
			}
			else
			{
				$('#stat_req_op_requests_'+opid).html( 0 ) ;
				$('#stat_req_op_taken_'+opid).html( 0 ) ;
				$('#stat_req_op_declined_'+opid).html( 0 ) ;
				$('#stat_req_op_initiated_'+opid).html( 0 ) ;
				$('#stat_req_op_initiated_taken_'+opid).html( 0 ) ;
			}
		}

		$('#stat_total_requests').html( stat_total_requests ) ;
		$('#stat_total_taken').html( stat_total_taken ) ;
		$('#stat_total_declined').html( stat_total_declined ) ;
		$('#stat_total_message').html( stat_total_message ) ;
		$('#stat_total_initiated').html( stat_total_initiated ) ;
		$('#stat_total_initiated_taken').html( stat_total_initiated_taken ) ;
	}

//-->
</script>
</head>
<body>

<?php include_once( "./inc_header.php" ) ?>

		<div class="op_submenu_wrapper">
			<div class="op_submenu_focus">Chat Reports</div>
			<div class="op_submenu" onClick="location.href='reports_chat_active.php?ses=<?php echo $ses ?>'">Active Chats</div>
			<div style="clear: both"></div>
		</div>

		<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr><td class="t_tl"></td><td class="t_tm"></td><td class="t_tr"></td></tr>
		<tr>
			<td class="t_ml"></td><td class="t_mm">
				<table cellspacing=0 cellpadding=0 border=0 width="100%">
				<tr>
					<td><div id="td_dept_header">Timeline</div></td>
					<td width="80"><div id="td_dept_header">Requests</div></td>
					<td width="60"><div id="td_dept_header">Taken</div></td>
					<td width="80"><div id="td_dept_header">Declined</div></td>
					<td width="110"><div id="td_dept_header">Initiated</div></td>
					<td width="110"><div id="td_dept_header">Initaite Taken</div></td>
					<td width="110"><div id="td_dept_header">Message</div></td>
				</tr>
				<tr>
					<td class="td_dept_td"><?php include_once( "./inc_select_cal.php" ) ; ?></td>
					<td class="td_dept_td"><?php echo $month_total_requests ?></td>
					<td class="td_dept_td"><?php echo $month_total_taken ?></td>
					<td class="td_dept_td"><?php echo $month_total_declined ?></td>
					<td class="td_dept_td"><?php echo $month_total_initiated ?></td>
					<td class="td_dept_td"><?php echo $month_total_initiated_taken ?></td>
					<td class="td_dept_td"><?php echo $month_total_msg ?></td>
				</tr>
				</table>

				<div style="width: 100%;">
					<table cellspacing=0 cellpadding=0 border=0 style="height: 100px; width: 100%;">
					<tr>
						<?php
							$tooltips = Array() ;
							for ( $c = 1; $c <= $stat_end_day; ++$c )
							{
								$stat_day = mktime( 0, 0, 1, $m, $c, $y ) ;
								$stat_day_expand = date( "l, M j, Y", $stat_day ) ;

								$h1 = "0px" ; $meter = "meter_v_green.gif" ;
								$style = "height: $h1; width: 100%;" ;
								$tooltip = "- <b>$stat_day_expand</b>" ;
								$tooltips[$stat_day] = $tooltip ;
								$tooltip_display = "" ;
								if ( isset( $month_stats[$stat_day] ) )
								{
									$tooltip_display = "$stat_day_expand - <ul><li> requests: ".$month_stats[$stat_day]["requests"]."<li> taken: ".$month_stats[$stat_day]["taken"]."<li> declined: ".$month_stats[$stat_day]["declined"]."<li> message: ".$month_stats[$stat_day]["message"]."<li> initiated: ".$month_stats[$stat_day]["initiated"]."</ul>" ;
									if ( $month_max )
										$h1 = round( ( $month_stats[$stat_day]["requests"]/$month_max ) * 100 ) . "px" ;
								}
								else if ( ( $c == $stat_end_day ) && ( !$month_max ) )
								{
									$h1 = "100px" ;
									$meter = "meter_v_clear.gif" ;
								}

								print "
									<td valign=\"bottom\" width=\"2%\"><div id=\"bar_v_requests_$c\" class=\"help_tooltip\" title=\"$tooltip_display\" style=\"height: $h1; background: url( ../pics/meters/$meter ) repeat-y; border-top-left-radius: 5px 5px; -moz-border-radius-topleft: 5px 5px; border-top-right-radius: 5px 5px; -moz-border-radius-topright: 5px 5px;\" OnMouseOver=\"\" OnClick=\"select_date( $stat_day, '$stat_day_expand' );\"></div></td>
									<td><img src=\"../pics/space.gif\" width=\"5\" height=1></td>
								" ;
							}
						?>
					</tr>
					<tr>
						<?php
							for ( $c = 1; $c <= $stat_end_day; ++$c )
							{
								$stat_day = mktime( 0, 0, 1, $m, $c, $y ) ;
								$stat_day_expand = date( "l, M j, Y", $stat_day ) ;
								print "
									<td align=\"center\"><div id=\"requests_bg_day\" class=\"help_tooltip page_report\" style=\"margin: 0px; font-size: 10px; font-weight: bold;\" title=\"$tooltips[$stat_day] - <i>click to select stat day</i>\" OnMouseOver=\"\" OnClick=\"select_date( $stat_day, '$stat_day_expand' );\">$c</div></td>
									<td><img src=\"../pics/space.gif\" width=\"5\" height=1></td>
								" ;
							}
						?>
					</tr>
					</table>
				</div>
			</td><td class="t_mr"></td>
		</tr>
		<tr><td class="t_bl"></td><td class="t_bm"></td><td class="t_br"></td></tr>
		</table>

		<div id="overview_day_chart" style="margin-top: 10px;">
			<div id="overview_date_title"><div id="stat_day_expand"></div></div>
			<div id="overview_data_container">
				<table cellspacing=0 cellpadding=0 border=0 width="100%">
				<tr>
					<td><div id="td_dept_header">Department</div></td>
					<td width="80"><div id="td_dept_header">Requests</div></td>
					<td width="60"><div id="td_dept_header">Taken</div></td>
					<td width="80"><div id="td_dept_header">Declined</div></td>
					<td width="80"><div id="td_dept_header">Initiated</div></td>
					<td width="110"><div id="td_dept_header">Initiated Taken</div></td>
					<td width="110"><div id="td_dept_header">Message</div></td>
				</tr>
				<?php
					for ( $c = 0; $c < count( $departments ); ++$c )
					{
						$department = $departments[$c] ;
						$td1 = "td_dept_td" ; $td2 = "td_dept_td_td" ;
						if ( $c % 2 ) { $td1 = "td_dept_td2" ; $td2 = "td_dept_td_td2" ; }

						print "
							<tr>
								<td class=\"$td1\" nowrap>$department[name]</td>
								<td class=\"$td1\"><class id=\"stat_req_dept_requests_$department[deptID]\">0</class></td>
								<td class=\"$td1\"><class id=\"stat_req_dept_taken_$department[deptID]\">0</class></td>
								<td class=\"$td1\"><class id=\"stat_req_dept_declined_$department[deptID]\">0</class></td>
								<td class=\"$td1\"><class id=\"stat_req_dept_initiated_$department[deptID]\">0</class></td>
								<td class=\"$td1\"><class id=\"stat_req_dept_initiated_taken_$department[deptID]\">0</class></td>
								<td class=\"$td1\"><class id=\"stat_req_dept_message_$department[deptID]\">0</class></td>
							</tr>
						" ;
					}
				?>
				<tr><td colspan=2>&nbsp;</td></tr>
				<tr>
					<td><div id="td_dept_header">Operator</div></td>
					<td width="80"><div id="td_dept_header">Requests</div></td>
					<td width="60"><div id="td_dept_header">Taken</div></td>
					<td width="80"><div id="td_dept_header">Declined</div></td>
					<td width="80"><div id="td_dept_header">Initiated</div></td>
					<td width="110"><div id="td_dept_header">Initiated Taken</div></td>
					<td width="110">&nbsp;</td>
				</tr>
				<?php
					for ( $c = 0; $c < count( $operators ); ++$c )
					{
						$operator = $operators[$c] ;
						$td1 = "td_dept_td" ; $td2 = "td_dept_td_td" ;
						if ( $c % 2 ) { $td1 = "td_dept_td2" ; $td2 = "td_dept_td_td2" ; }

						print "
							<tr>
								<td class=\"$td1\">$operator[name]</td>
								<td class=\"$td1\"><class id=\"stat_req_op_requests_$operator[opID]\">0</class></td>
								<td class=\"$td1\"><class id=\"stat_req_op_taken_$operator[opID]\">0</class></td>
								<td class=\"$td1\"><class id=\"stat_req_op_declined_$operator[opID]\">0</class></td>
								<td class=\"$td1\"><class id=\"stat_req_op_initiated_$operator[opID]\">0</class></td>
								<td class=\"$td1\"><class id=\"stat_req_op_initiated_taken_$operator[opID]\">0</class></td>
							</tr>
						" ;
					}
				?>
				<tr>
					<td class="td_dept_td_blank"><b>Total</td>
					<td class="td_dept_td_blank"><div id="stat_total_requests" style="font-weight: bold;"></div></td>
					<td class="td_dept_td_blank"><div id="stat_total_taken" style="font-weight: bold;"></div></td>
					<td class="td_dept_td_blank"><div id="stat_total_declined" style="font-weight: bold;"></div></td>
					<td class="td_dept_td_blank"><div id="stat_total_initiated" style="font-weight: bold;"></div></td>
					<td class="td_dept_td_blank"><div id="stat_total_initiated_taken" style="font-weight: bold;"></div></td>
					<td class="td_dept_td_blank"><div id="stat_total_message" style="font-weight: bold;"></div></td>
				</tr>
				</table>
			</div>
		</div>

<?php include_once( "./inc_footer.php" ) ?>

<div id="iframe_wrapper" style="display: none; position: absolute; z-index: 100;">
	<div><iframe id="iframe_edit" name="iframe_edit" style="width: 550px; height: 300px; border: 0px;" src="" scrolling="auto"></iframe></div>
	<div id="op_footer" style="height: 23px;"></div>
</div>

</body>
</html>
