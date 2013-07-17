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

	include_once( "../API/Footprints/get_ext.php" ) ;

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

	$footprints_timespan = Footprints_get_FootprintsRangeHash( $dbh, $stat_start, $stat_end ) ;
	$month_max = $month_total_footprints = 0 ;
	$month_max_expand = "" ;
	foreach ( $footprints_timespan as $key => $value )
	{
		if ( $value["total"] > $month_max )
		{
			$month_max = $value["total"] ;
			$month_max_expand = date( "D, M j, Y", $key ) ;
		}
		$month_total_footprints += $value["total"] ;
	}
	$month_ave = floor( $month_total_footprints/$stat_end_day ) ;
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

		$('#body_sub_title').html( "<img src=\"../pics/icons/book.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" style=\"margin-right: 5px;\"> Report: Traffic" ) ;

		init_menu() ;
		toggle_menu_setup( "rtraffic" ) ;

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
		$('#stat_day_expand').html( thedayexpand ) ;

		$.ajax({
			type: "GET",
			url: "../ajax/setup_actions.php",
			data: "ses=<?php echo $ses ?>&action=footprints&sdate="+theunix+"&start=<?php echo $stat_start ?>&end=<?php echo $stat_end ?>&"+unixtime(),
			success: function(data){
				eval( data ) ;

				if ( json_data.status )
				{
					var footprints_string = "<table cellspacing=1 cellpadding=0 border=0 width=\"100%\">" ;
					for ( c = 0; c < json_data.footprints.length; ++c )
					{
						total = json_data.footprints[c].total ;
						url_snap = json_data.footprints[c].url_snap ;
						url_raw = json_data.footprints[c].url_raw ;

						var td1 = "td_dept_td" ; var td2 = "td_dept_td_td" ;
						if ( c % 2 ) { td1 = "td_dept_td2" ; td2 = "td_dept_td_td2" ; }

						footprints_string += "<tr><td class=\""+td1+"\" width=\"16\">"+total+"</td><td class=\""+td1+"\" width=\"100%\"><a href=\""+url_raw+"\" target=\"_blank\" style=\"text-decoration: none;\">"+url_snap+"</a></td></tr>" ;
					}
					if ( !c )
						footprints_string += "<tr><td class=\"td_dept_td\" colspan=2>No footprint data.</td></tr>" ;

					footprints_string += "</table>" ;
				}
				$('#dynamic_footprints').html( footprints_string ) ;
			}
		});
	}

//-->
</script>
</head>
<body>

<?php include_once( "./inc_header.php" ) ?>

		<div class="op_submenu_wrapper">
			<div class="op_submenu_focus">Footprints</div>
			<div class="op_submenu" onClick="location.href='reports_refer.php?ses=<?php echo $ses ?>'">Refer URLs</div>
			<div style="clear: both"></div>
		</div>

		<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr><td class="t_tl"></td><td class="t_tm"></td><td class="t_tr"></td></tr>
		<tr>
			<td class="t_ml"></td><td class="t_mm">
				<table cellspacing=1 cellpadding=0 border=0 width="100%">
				<tr>
					<td><div id="td_dept_header">Timeline</div></td>
					<td width="80"><div id="td_dept_header">Total</div></td>
				</tr>
				<tr>
					<td class="td_dept_td"><?php include_once( "./inc_select_cal.php" ) ; ?></td>
					<td class="td_dept_td"><?php echo $month_total_footprints ?></td>
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

								$h1 = "0px" ; $meter = "meter_v_blue.gif" ;
								$style = "height: $h1; width: 100%;" ;
								$tooltip = "- <b>$stat_day_expand</b>" ;
								$tooltips[$stat_day] = $tooltip ;
								$tooltip_display = "" ;
								if ( isset( $footprints_timespan[$stat_day] ) )
								{
									$tooltip_display = "$stat_day_expand - <ul><li>footprints: ".$footprints_timespan[$stat_day]["total"]."</li></ul>" ;
									if ( $month_max )
										$h1 = round( ( $footprints_timespan[$stat_day]["total"]/$month_max ) * 100 ) . "px" ;
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
									<td align=\"center\"><div id=\"requests_bg_day\" OnMouseOver=\"\" OnClick=\"select_date( $stat_day, '$stat_day_expand' );\" class=\"help_tooltip page_report\" style=\"margin: 0px; font-size: 10px; font-weight: bold;\" title=\"$tooltips[$stat_day] - <i>click to select stat day</i>\">$c</div></td>
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
					<td><div id="td_dept_header">Top 25 Footprints</div></td>
				</tr>
				<tr>
					<td><div style="max-height: 350px; overflow: auto;"><div id="dynamic_footprints"></div></div></td>
				</tr>
				</table>
			</div>
		</div>

<?php include_once( "./inc_footer.php" ) ?>
</body>
</html>
