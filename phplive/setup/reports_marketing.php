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

	include_once( "../API/Marketing/get.php" ) ;

	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;
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

	$marketings = Marketing_get_AllMarketing( $dbh ) ;

	$clicks_timespan = Marketing_get_ClicksRangeHash( $dbh, $stat_start, $stat_end ) ;
	$month_total_clicks = 0 ;
	$month_max = $js_stats = "" ;
	foreach ( $clicks_timespan as $sdate => $marketids )
	{
		foreach ( $marketids as $key => $value )
		{
			if ( $sdate )
				$month_total_clicks += $value["clicks"] ;

			if ( $key != "clicks" )
				$js_stats .= "stats[$sdate][$key]['clicks'] = $value[clicks] ; " ;
		}

		if ( isset( $clicks_timespan[$sdate]["clicks"] ) && ( $clicks_timespan[$sdate]["clicks"] > $month_max ) && $sdate )
			$month_max = $clicks_timespan[$sdate]["clicks"] ;
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
<script type="text/javascript" src="../js/tooltip.js"></script>

<script type="text/javascript">
<!--
	var mouse_x ; var mouse_y ;

	var stats = new Object ; stats[0] = new Object ;

	<?php
		for ( $c = 1; $c <= $stat_end_day; ++$c )
		{
			$stat_day = mktime( 0, 0, 1, $m, $c, $y ) ;
			print "stats[$stat_day] = new Object; " ;

			for ( $c2 = 0; $c2 < count( $marketings ); ++$c2 )
			{
				$marketing = $marketings[$c2] ;
				print "stats[$stat_day][$marketing[marketID]] = new Object; " ;
				print "stats[0][$marketing[marketID]] = new Object; " ;
			}
		}
	?>

	eval( "<?php echo $js_stats ?>" ) ;

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		}) ;

		$('#body_sub_title').html( "<img src=\"../pics/icons/graph.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" style=\"margin-right: 5px;\"> Marketing" ) ;

		init_menu() ;
		toggle_menu_setup( "marketing" ) ;

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

	function reset_date()
	{
		select_date( 0, "<?php echo date( "M j, Y", $stat_start ) ?> - <?php echo date( "M j, Y", $stat_end ) ?>" ) ;
	}

	function select_date( theunix, thedayexpand )
	{
		var total = 0 ;
		$('#stat_day_expand').html( thedayexpand ) ;
		for ( var marketid in stats[theunix] )
		{
			if ( typeof( stats[theunix][marketid]["clicks"] ) != "undefined" )
			{
				$('#stat_clicks_'+marketid).html( stats[theunix][marketid]["clicks"] ) ;
				total += stats[theunix][marketid]["clicks"] ;
			}
			else
			{
				$('#stat_clicks_'+marketid).html( 0 ) ;
			}
		}
		$('#stat_total_requests').html( total ) ;
	}
//-->
</script>
</head>
<body>

<?php include_once( "./inc_header.php" ) ?>

		<div class="op_submenu_wrapper">
			<div class="op_submenu" onClick="location.href='marketing.php?ses=<?php echo $ses ?>'">Social Media</div>
			<div class="op_submenu" onClick="location.href='marketing_click.php?ses=<?php echo $ses ?>'">Click Tracking</div>
			<div class="op_submenu_focus">Report: Clicks</div>
			<div class="op_submenu" onClick="location.href='marketing_marquee.php?ses=<?php echo $ses ?>'">Chat Footer Marquee</div>
			<!-- <div class="op_submenu" onClick="location.href='marketing_initiate.php?ses=<?php echo $ses ?>'">Auto Initiate</div> -->
			<div style="clear: both"></div>
		</div>

		<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr><td class="t_tl"></td><td class="t_tm"></td><td class="t_tr"></td></tr>
		<tr>
			<td class="t_ml"></td><td class="t_mm">
				<table cellspacing=1 cellpadding=0 border=0 width="100%">
				<tr>
					<td><div id="td_dept_header">Timeline</div></td>
					<td><div id="td_dept_header">Clicks</div></td>
				</tr>
				<tr>
					<td class="td_dept_td"><?php include_once( "./inc_select_cal.php" ) ; ?></td>
					<td class="td_dept_td"><?php echo $month_total_clicks ?></td>
				</tr>
				</table>

				<div style="width: 100%; background: #FFFFFF;">
					<table cellspacing=0 cellpadding=0 border=0 style="height: 100px; width: 100%;">
					<tr>
						<?php
							$tooltips = Array() ;
							for ( $c = 1; $c <= $stat_end_day; ++$c )
							{
								$stat_day = mktime( 0, 0, 1, $m, $c, $y ) ;
								$stat_day_expand = date( "l, M j, Y", $stat_day ) ;

								$h1 = "0px" ; $meter = "meter_v_purple.gif" ;
								$style = "height: $h1; width: 100%;" ;
								$tooltip = "- <b>$stat_day_expand</b>" ;
								$tooltips[$stat_day] = $tooltip ;
								$tooltip_display = "" ;
								if ( isset( $clicks_timespan[$stat_day] ) )
								{
									$tooltip_display = "$stat_day_expand - <ul><li>clicks: ".$clicks_timespan[$stat_day]["clicks"]."</li></ul>" ;
									if ( $month_max )
										$h1 = round( ( $clicks_timespan[$stat_day]["clicks"]/$month_max ) * 100 ) . "px" ;
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
					<td width="200"><div id="td_dept_header">Campaigns</div></td>
					<td><div id="td_dept_header">Clicks</div></td>
				</tr>
				<?php
					for ( $c = 0; $c < count( $marketings ); ++$c )
					{
						$marketing = $marketings[$c] ;
						$color = $marketing["color"] ;
						$td1 = "td_dept_td" ; $td2 = "td_dept_td_td" ;

						print "
							<tr>
								<td class=\"$td1\" style=\"background: #$color;\" nowrap><img src=\"../pics/icons/book.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\"> $marketing[name]</td>
								<td class=\"$td1\"><div id=\"stat_clicks_$marketing[marketID]\">0</div></td>
							</tr>
						" ;
					}
				?>
				<tr>
					<td class="td_dept_td_blank"><b>Total</td>
					<td class="td_dept_td_blank"><div id="stat_total_requests" style="font-weight: bold;"></div></td>
				</tr>
				</table>
			</div>
		</div>

<?php include_once( "./inc_footer.php" ) ?>
</body>
</html>
