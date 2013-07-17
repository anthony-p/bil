<?php
	/****************************************/
	// STANDARD header for Setup
	include_once( "../web/config.php" ) ;
	include_once( "../API/Util_Format.php" ) ;
	include_once( "../API/Util_Error.php" ) ;
	include_once( "../API/SQL.php" ) ;
	include_once( "../API/Util_Security.php" ) ;
	$ses = Util_Format_Sanatize( Util_Format_GetVar( "ses" ), "ln" ) ;
	$auth = Util_Format_Sanatize( Util_Format_GetVar( "auth" ), "ln" ) ;
	$id = Util_Format_Sanatize( Util_Format_GetVar( "id" ), "ln" ) ;
	$wp = Util_Format_Sanatize( Util_Format_GetVar( "wp" ), "ln" ) ;
	if ( $auth == "setup" )
	{
		if ( !$admininfo = Util_Security_AuthSetup( $dbh, $ses, $id ) ){ ErrorHandler ( 602, "Invalid setup session.", "$_SERVER[HTTP_HOST]/$_SERVER[REQUEST_URI]", 0, Array() ) ; }
		$theme = $CONF["THEME"] ;
	}
	else
	{
		if ( !$opinfo = Util_Security_AuthOp( $dbh, $ses, $id, $wp ) ){ ErrorHandler ( 602, "Invalid setup session.", "$_SERVER[HTTP_HOST]/$_SERVER[REQUEST_URI]", 0, Array() ) ; }
		$theme = $opinfo["theme"] ;
	}
	// STANDARD header end
	/****************************************/

	include_once( "../API/Ops/get.php" ) ;
	include_once( "../API/Depts/get.php" ) ;
	include_once( "../API/Chat/get.php" ) ;
	include_once( "../API/Chat/get_ext.php" ) ;

	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;
	$ces = Util_Format_Sanatize( Util_Format_GetVar( "ces" ), "ln" ) ;
	$back = Util_Format_Sanatize( Util_Format_GetVar( "back" ), "ln" ) ;

	$error = "" ;

	$transcript = Chat_ext_get_Transcript( $dbh, $ces ) ;
	$formatted = preg_replace( "/\"/", "&quot;", $transcript["formatted"] ) ;

	$requestinfo = Chat_get_RequestHistCesInfo( $dbh, $ces ) ;
	$requestinfo["vname"] = preg_replace( "/<v>/", "", $requestinfo["vname"] ) ;
	$opinfo = Ops_get_OpInfoByID( $dbh, $transcript["opID"] ) ;
	$deptinfo = Depts_get_DeptInfo( $dbh, $transcript["deptID"] );

	if ( isset( $requestinfo["os"] ) )
	{
		include_once( "../API/Footprints/get_ext.php" ) ;

		$os = $VARS_OS[$requestinfo["os"]] ;
		$browser = $VARS_BROWSER[$requestinfo["browser"]] ;

		$onpage_title = preg_replace( "/\"/", "&quot;", $requestinfo["title"] ) ;
		$onpage_title_raw = $onpage_title ;
		$onpage_title_snap = ( strlen( $onpage_title_raw ) > 20 ) ? substr( $onpage_title_raw, 0, 40 ) . "..." : $onpage_title_raw ;
		$onpage_raw = preg_replace( "/hphp/i", "http", $requestinfo["onpage"] ) ;
		$onpage_snap = ( strlen( $onpage_raw ) > 20 ) ? substr( $onpage_raw, 0, 40 ) . "..." : $onpage_raw ;

		$refer_raw = $refer_snap = $refer_hist_string = "" ;
		$referinfo_raw = Footprints_get_IPRefer( $dbh, $requestinfo["ip"], 5 ) ;
		if ( isset( $referinfo_raw[0] ) )
		{
			$referinfo = $referinfo_raw[0] ;

			for ( $c = 0; $c < count( $referinfo_raw ); ++$c )
			{
				$this_referinfo = $referinfo_raw[$c] ;
				$refer_raw = preg_replace( "/((http)|(https)):\/\/(www.)/", "", preg_replace( "/hphp/i", "http", $this_referinfo["refer"] ) ) ;
				$refer_snap = ( strlen( $refer_raw ) > 20 ) ? substr( $refer_raw, 0, 40 ) . "..." : $refer_raw ;

				$refer_hist_string .= "$refer_raw<br>" ;
			}
		}

		if ( $requestinfo["marketID"] )
		{
			include_once( "../API/Marketing/get.php" ) ;

			$marketinfo = Marketing_get_MarketingByID( $dbh, $requestinfo["marketID"] ) ;
		}
	}
?>
<?php include_once( "../inc_doctype.php" ) ?>
<head>
<title> PHP Live! Support <?php echo $VERSION ?> </title>

<meta name="description" content="PHP Live! Support <?php echo $VERSION ?>">
<meta name="keywords" content="PHP Live! Support">
<meta name="robots" content="all,index,follow">
<meta http-equiv="content-type" content="text/html; CHARSET=utf-8"> 
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<link rel="Stylesheet" href="../themes/<?php echo $theme ?>/style.css">
<script type="text/javascript" src="../js/global.js"></script>
<script type="text/javascript" src="../js/global_chat.js"></script>
<script type="text/javascript" src="../js/setup.js"></script>
<script type="text/javascript" src="../js/framework.js"></script>
<script type="text/javascript" src="../js/framework_ext.js"></script>
<script type="text/javascript" src="../js/tooltip.js"></script>

<script type="text/javascript">
<!--
	var mouse_x ; var mouse_y ;
	var view = 1 ; // flag used in global_chat.js for minor formatting of divs
	var base_url = ".." ; var base_url_full = "<?php echo $CONF["BASE_URL"] ?>" ;
	var wp = <?php echo $wp ?> ;
	var widget ;

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		}) ;

		init_divs(0) ;
		init_tooltips() ;
		$('#chat_body').html( init_timestamps( "<?php echo $formatted ?>" ) ) ;

		$(window).resize(function() { init_divs(1) ; });

		$('#btn_email').attr( "disabled", false ) ; // reset it... firebox caches disabled

		//$('#table_info tr:nth-child(1n)').addClass('chat_info_tr_traffic_row') ;
	});

	function init_tooltips()
	{
		var help_tooltips = $( '#chat_input' ).find( '.help_tooltip' ) ;
		help_tooltips.tooltip({
			event: "mouseover",
			track: true,
			delay: 0,
			showURL: false,
			showBody: "- ",
			fade: 0,
			positionLeft: true,
			left: 100
		});
	}

	function toggle_info()
	{
		$('#table_email').hide();
		$('#table_info').show();
	}

	function toggle_email()
	{
		$('#table_info').hide() ;
		$('#table_email').show() ;
		$('#btn_email').attr( "disabled", false ) ;
	}

	function send_email()
	{
		if ( !$('#vemail').val() )
			alert( "Blank Email is invalid." ) ;
		else if ( !$('#message').val() )
			alert( "Blank Message is invalid." ) ;
		else
		{
			$('#btn_email').attr( "disabled", true ) ;

			var unique = unixtime() ;
			var deptid = $('#deptid').val() ;
			var vname = "<?php echo $requestinfo["vname"] ?>" ;
			var vemail = $('#vemail').val() ;
			var subject = encodeURIComponent( "Chat Transcript: "+vname ) ;
			var message =  encodeURIComponent( $('#message').val() ) ;

			$('#chat_button_start').blur() ;
			$.get("../phplive_m.php", { action: "send_email", ces: '<?php echo $ces ?>', trans: 1, opid: <?php echo $opinfo["opID"] ?>, deptid: deptid, vname: vname, vemail: vemail, vsubject: subject, vquestion: message, unique: unique },  function(data){
				eval( data ) ;

				if ( json_data.status )
				{
					toggle_info() ;
					do_alert( 1, "Transactipt emailed to "+vemail+"." ) ;
				}
				else
				{
					alert( json_data.error ) ;
					$('#btn_email').attr( "disabled", false ) ;
				}
			});
		}
	}

//-->
</script>
</head>
<body>

<div id="chat_canvas" style="min-height: 100%; width: 100%;"></div>
<div style="position: absolute; top: 2px; padding: 10px; z-Index: 2; overflow: auto;">
	<div id="chat_body" style="overflow: auto;"></div>
	<div id="chat_input" style="margin-top: 8px; padding: 5px; -moz-border-radius: 5px; border-radius: 5px;">
		
		<?php if ( isset( $requestinfo["os"] ) ): ?>
		<table cellspacing=0 cellpadding=0 border=0 width="100%" id="table_info">
		<tr>
			<td width="50" nowrap><div class="chat_info_td_traffic"><span class="text_trans_view_td">Date:</span></div></td>
			<td><div class="chat_info_td_traffic"><b><?php echo date( "M j, Y, g:i a", $requestinfo["created"] ) ; ?></b></div></td>
			<td width="50" nowrap><div class="chat_info_td_traffic" style="padding-left: 10px;"><span class="text_trans_view_td">Visitor:</span></div></td>
			<td><div class="chat_info_td_traffic"><b><?php echo $requestinfo["vname"] ?> <?php echo ( $requestinfo["vemail"] != "null" ) ? "&lt;$requestinfo[vemail]&gt;" : "" ; ?></b></div></td>
		</tr>
		<tr>
			<td><div class="chat_info_td_traffic"><span class="text_trans_view_td">Duration:</span></div></td>
			<td><div class="chat_info_td_traffic"><b><?php $duration = $transcript["ended"] - $transcript["created"] ; if ( $duration < 60 ) { $duration = 60 ; } echo Util_Format_Duration( $duration ) ; ?></b></div></td>
			<td><div class="chat_info_td_traffic" style="padding-left: 10px;"><span class="text_trans_view_td">Operator:</span></div></td>
			<td><div class="chat_info_td_traffic"><?php echo ( $requestinfo["initiated"] ) ? "<img src=\"../themes/$CONF[THEME]/info_initiate.gif\" width=\"10\" height=\"10\" border=\"0\" alt=\"\" class=\"help_tooltip\" title=\"- Operator Initiated Chat\"> " : "" ; ?><b><?php echo $opinfo["name"] ; ?> &lt;<?php echo $opinfo["email"] ?>&gt;</b></div></td>
		</tr>
		<tr>
			<td><div class="chat_info_td_traffic"><span class="text_trans_view_td">Rating:</span></div></td>
			<td><div class="chat_info_td_traffic"><?php echo Util_Format_Stars( $transcript["rating"] ) ; ?></div></div></td>
			<td><div class="chat_info_td_traffic" style="padding-left: 10px;"><span class="text_trans_view_td">Department:</span></div></td>
			<td><div class="chat_info_td_traffic"><b><?php echo $deptinfo["name"] ; ?></b></div></td>
		</tr>
		<tr>
			<td><div class="chat_info_td_traffic"><span class="text_trans_view_td">IP / Host:</span></div></td>
			<td><div class="chat_info_td_traffic"><b><span class="help_tooltip" title="- <?php echo $requestinfo["hostname"] ?>"><?php echo $requestinfo["ip"] ?></span></b> <img src="../themes/<?php echo $theme ?>/os/<?php echo $os ?>.png" width="14" height="14" border="0"  class="help_tooltip" title="- <?php echo $os ?>"> <img src="../themes/<?php echo $theme ?>/browsers/<?php echo $browser ?>.png" width="14" height="14" border="0" class="help_tooltip" title="- <?php echo $browser ?>"></div></td>
			<td><div class="chat_info_td_traffic" style="padding-left: 10px;"><span class="text_trans_view_td">On Page:</span></div></td>
			<td><div class="chat_info_td_traffic"><b><a href="<?php echo $onpage_raw ?>" target="_blank"><?php echo $onpage_title_snap ?></a></b></div></td>
		</tr>
		<tr>
			<td><div class="chat_info_td_traffic" style=""><span class="text_trans_view_td">Resolution:</span></div></td>
			<td><div class="chat_info_td_traffic"><b><?php echo $requestinfo["resolution"] ?></b></div></td>
			<td><div class="chat_info_td_traffic" style="padding-left: 10px;"><span class="text_trans_view_td">Refer:</span></div></td>
			<td><div class="chat_info_td_traffic"><b><a href="<?php echo $refer_raw ?>" target="_blank"><?php echo $refer_snap ?></a></b></div></td>
		</tr>
		<tr>
			<td colspan="4"><div class="info_neutral" style="margin-top: 10px;">
				<div style="float: left;">Options: </div>
				<div style="float: left; margin-left: 15px; cursor: pointer" onClick="toggle_email()"><img src="../themes/<?php echo $theme ?>/email.png" width="16" height="16" border="0" alt=""></div>

				<?php if ( !$back ): ?>
				<div style="float: left; margin-left: 15px; cursor: pointer;" onClick="do_print('<?php echo $ces ?>', <?php echo $requestinfo["deptID"] ?>, <?php echo $requestinfo["opID"] ?> )"><img src="../themes/<?php echo $theme ?>/printer.png" width="16" height="16" border="0" alt=""></div>
				<?php endif ; ?>

				<div style="clear: both;"></div>
			</div></td>
		</tr>
		</table>

		<div id="table_email" style="display: none; position: relative; top: -108px; padding: 10px;" class="info_content">
			<form>
			<input type="hidden" name="deptid" id="deptid" value="<?php echo $requestinfo["deptID"] ?>">
			<table cellspacing=0 cellpadding=0 border=0 width="100%">
			<tr>
				<td style="">To Email:<br><input type="text" class="input_text" name="vmail" id="vemail" size="38" maxlength="160" value="<?php echo ( $requestinfo["vemail"] != "null" ) ? $requestinfo["vemail"] : "" ; ?>"></td>
			</tr>
			<tr><td style="height: 5px;"></td></tr>
			<tr>
				<td colspan=2>Message:<br><textarea class="input_text" rows="9" style="width: 99%; resize: none;" wrap="virtual" id="message">Hello <?php echo $requestinfo["vname"] ?>,

Please reference the chat transcript below:

%%transcript%%

Thank you,
<?php echo $opinfo["name"] ?>

</textarea></td>
			</tr>
			<tr><td style="height: 15px;"></td></tr>
			<tr><td><input type="button" id="btn_email" value="Email Transcript" onClick="send_email()" class="input_button"> or <a href="JavaScript:void(0)" onClick="toggle_info()">cancel</a></td></tr>
			</table>
			</form>
		</div>
		<?php else: ?>

		<div class="info_box">Data is not available.</a>.</div>

		<?php endif ; ?>

	</div>
</div>

<?php if ( $back ): ?>
<div class="info_disconnect" style="position: absolute; top: 0px; right: 0px; z-Index: 101;" onClick="history.go(-1)"><img src="../themes/<?php echo $theme ?>/close_extra.png" width="14" height="14" border="0" alt=""> back to transcript list</div>
<?php endif ; ?>

</body>
</html>
<?php database_mysql_close( $dbh ) ; ?>
