<?php
	/****************************************/
	// STANDARD header for Setup
	include_once( "../web/config.php" ) ;
	include_once( "../API/Util_Format.php" ) ;
	include_once( "../API/Util_Error.php" ) ;
	include_once( "../API/SQL.php" ) ;
	include_once( "../API/Util_Security.php" ) ;
	$ses = Util_Format_Sanatize( Util_Format_GetVar( "ses" ), "ln" ) ;
	if ( !$opinfo = Util_Security_AuthOp( $dbh, $ses ) ){ ErrorHandler ( 602, "Invalid setup session or session has expired.", "$_SERVER[HTTP_HOST]/$_SERVER[REQUEST_URI]", 0, Array() ) ; }
	// STANDARD header end
	/****************************************/

	include_once( "../API/Ops/get.php" ) ;
	include_once( "../API/Depts/get.php" ) ;
	include_once( "../API/IPs/get.php" ) ;
	include_once( "../API/Chat/get_ext.php" ) ;

	$ip = Util_Format_Sanatize( Util_Format_GetVar( "ip" ), "ln" ) ;
	$wp = Util_Format_Sanatize( Util_Format_GetVar( "wp" ), "ln" ) ;

	$ipinfo = IPs_get_IPInfo( $dbh, $ip ) ;
	$operators = Ops_get_AllOps( $dbh ) ;
	$departments = Depts_get_OpDepts( $dbh, $opinfo["opID"] ) ;

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

	$transcripts = Chat_ext_get_IPTranscripts( $dbh, $ip, 50 ) ;
?>
<?php include_once( "../inc_doctype.php" ) ?>
<head>
<title> Transcripts: <?php echo $ip ?> </title>

<meta name="description" content="PHP Live! Support <?php echo $VERSION ?>">
<meta name="keywords" content="PHP Live! Support">
<meta name="robots" content="all,index,follow">
<meta http-equiv="content-type" content="text/html; CHARSET=utf-8"> 
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<link rel="Stylesheet" href="../themes/<?php echo $opinfo["theme"] ?>/style.css">
<script type="text/javascript" src="../js/global.js"></script>
<script type="text/javascript" src="../js/global_chat.js"></script>
<script type="text/javascript" src="../js/setup.js"></script>
<script type="text/javascript" src="../js/framework.js"></script>
<script type="text/javascript" src="../js/framework_ext.js"></script>
<script type="text/javascript" src="../js/tooltip.js"></script>

<script type="text/javascript">
<!--
	var mouse_x ; var mouse_y ;
	var wp = <?php echo $wp ?> ;

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		}) ;

		//init_trs() ;
		init_tooltips() ;
	});

	function init_trs()
	{
		$('#table_trs tr:nth-child(2n+3)').addClass('chat_info_tr_traffic_row') ;
	}

	function open_transcript( theces )
	{
		$( '*', '#table_trs' ).each( function(){
			var div_name = $( this ).attr('id') ;
			if ( div_name.indexOf("td_") != -1 )
				$(this).removeClass('chat_info_td_traffic_img') ;
		} );

		$('#td_'+theces).addClass('chat_info_td_traffic_img') ;

		location.href = "./op_trans_view.php?ses=<?php echo $ses ?>&ces="+theces+"&id=<?php echo $opinfo["opID"] ?>&wp="+wp+"&auth=op&back=1" ;
	}

	function init_tooltips()
	{
		var help_tooltips = $( '#table_trs' ).find( '.help_tooltip' ) ;
		help_tooltips.tooltip({
			event: "mouseover",
			track: true,
			delay: 0,
			showURL: false,
			showBody: "- ",
			fade: 0,
			positionLeft: true,
			left: 130
		});
	}

//-->
</script>
</head>
<body>

<div id="canned_wrapper" style="height: 100%; overflow: auto;">
	<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr><td class="t_tl"></td><td class="t_tm"></td><td class="t_tr"></td></tr>
	<tr>
		<td class="t_ml"></td><td class="t_mm">

			<table cellspacing=0 cellpadding=0 border=0 width="100%" id="table_trs">
			<tr>
				<td></td><td colspan="6"><div style="padding-bottom: 10px;"><img src="../themes/<?php echo $opinfo["theme"] ?>/info_bullet.gif" width="10" height="10" border="0" alt=""> IP: <?php echo $ip ?> / <?php echo gethostbyaddr( $ip ) ?></div></td>
			</tr>
			<tr>
				<td width="28" nowrap>&nbsp;</td>
				<td width="70" nowrap><div id="chat_info_td_h">Operator</div></td>
				<td><div id="chat_info_td_h">Rating</div></td>
				<td><div id="chat_info_td_h">Created</div></td>
				<td width="80"><div id="chat_info_td_h">Duration</div></td>
				<td width="50"><div id="chat_info_td_h">Size</div></td>
				<td><div id="chat_info_td_h">Question</div></td>
			</tr>
			<?php
				for ( $c = 0; $c < count( $transcripts ); ++$c )
				{
					$transcript = $transcripts[$c] ;

					// weed out random bugs of no operator data
					if ( $transcript["opID"] )
					{
						$operator = ( $transcript["op2op"] ) ? $operators_hash[$transcript["op2op"]] : $operators_hash[$transcript["opID"]] ;
						$created = date( "M j (g:i:s a)", $transcript["created"] ) ;
						$duration = $transcript["ended"] - $transcript["created"] ;
						if ( $duration < 60 )
							$duration = 60 ;
						$duration = Util_Format_Duration( $duration ) ;
						$question = $transcript["question"] ;
						$vname = ( $transcript["op2op"] ) ? $operators_hash[$transcript["opID"]] : $transcript["vname"] ;
						$rating = Util_Format_Stars( $transcript["rating"] ) ;
						$initiated = ( $transcript["initiated"] ) ?  "<img src=\"../themes/$opinfo[theme]/info_initiate.gif\" width=\"10\" height=\"10\" border=\"0\" alt=\"\" class=\"help_tooltip\" title=\"- Operator Initiated Chat\"> " : "" ;

						if ( $transcript["op2op"] )
							$question = "<div class=\"text_grey\"><img src=\"../themes/$opinfo[theme]/agent.png\" width=\"16\" height=\"16\" border=\"0\" class=\"help_tooltip\" title=\"- Operator to Operator Chat\"></div>" ;

						print "<tr style=\"cursor: pointer;\" onClick=\"open_transcript('$transcript[ces]')\"><td class=\"chat_info_td_traffic\" width=\"16\" id=\"td_$transcript[ces]\" style=\"-moz-border-radius: 5px; border-radius: 5px;\"><img src=\"../themes/$opinfo[theme]/view.png\" id=\"img_$transcript[ces]\" width=\"16\" height=\"16\" class=\"help_tooltip\" title=\"- view transcript\"></td><td class=\"chat_info_td_traffic\"><b><div id=\"transcript_$transcript[ces]\">$initiated $operator</div></b></td><td class=\"chat_info_td_traffic\" align=\"center\">$rating</td><td class=\"chat_info_td_traffic\" nowrap>$created</td><td class=\"chat_info_td_traffic\">$duration</td><td class=\"chat_info_td_traffic\">$transcript[fsize]</td><td class=\"chat_info_td_traffic\">$question</td></tr>" ;
					}
				}
				if ( $c == 0 )
					print "<tr><td class=\"chat_info_td_traffic\">&nbsp;</td><td colspan=7 class=\"chat_info_td_traffic\">No transcripts.</td></tr>" ;
			?>
			</table>
			<div class="chat_info_end"></div>

		</td><td class="t_mr"></td>
	</tr>
	<tr><td class="t_bl"></td><td class="t_bm"></td><td class="t_br"></td></tr>
	</table>

	<div style="height: 25px; width: 10px;"></div>
</div>

</body>
</html>
<?php database_mysql_close( $dbh ) ; ?>
