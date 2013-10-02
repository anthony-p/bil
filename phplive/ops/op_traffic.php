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

	include_once( "../lang_packs/$CONF[lang].php" ) ;
	include_once( "../API/Depts/get.php" ) ;
	include_once( "../API/Canned/get.php" ) ;

	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;
?>
<?php include_once( "../inc_doctype.php" ) ?>
<head>
<title> PHP Live! Support <?php echo $VERSION ?> </title>

<meta name="description" content="PHP Live! Support <?php echo $VERSION ?>">
<meta name="keywords" content="PHP Live! Support">
<meta name="robots" content="all,index,follow">
<meta http-equiv="content-type" content="text/html; CHARSET=utf-8"> 
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<link rel="Stylesheet" href="../themes/<?php echo $opinfo["theme"] ?>/style.css">
<script type="text/javascript" src="../js/global.js"></script>
<script type="text/javascript" src="../js/setup.js"></script>
<script type="text/javascript" src="../js/framework.js"></script>
<script type="text/javascript" src="../js/framework_ext.js"></script>
<script type="text/javascript" src="../js/tooltip.js"></script>

<script type="text/javascript">
<!--
	var mouse_x ; var mouse_y ;
	var loaded = 1 ;

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		}) ;

		populate_traffic() ;
		init_depts() ;
	});

	function init_divs()
	{
		var width = $('#canned_body').width() ;

		$('#footprint_info').css({'width': width}) ;
	}

	function init_depts()
	{
		var depts_select = "<select name=\"ini_deptid\" id=\"ini_deptid\" onChange=\"parent.initiate_deptid = this.value;\"><option value=0></option>" ;
		for ( var thisdeptid in parent.op_depts_hash )
		{
			var selected = "" ;
			if ( thisdeptid == parent.initiate_deptid )
				selected = "selected" ;

			depts_select += "<option value=\""+thisdeptid+"\" "+selected+">"+parent.op_depts_hash[thisdeptid]+"</option>" ;
		}
		depts_select += "</select>" ;

		$('#depts_select').html( depts_select ) ;
	}

	function init_trs()
	{
		$('#table_trs tr:nth-child(2n+3)').addClass('chat_info_tr_traffic_row') ;
	}

	function populate_traffic()
	{
		var json_data = new Object ;
		var unique = unixtime() ;
		var image, image_info ;

		if ( parent.extra == "traffic" )
		{
			$.get("../ajax/chat_actions_op.php", { action: "traffic", unique: unique },  function(data){
				eval( data ) ;

				if ( json_data.status )
				{
					var ip_exist = 0 ;
					var traffic_string = "<table cellspacing=0 cellpadding=0 border=0 width=\"100%\" id=\"table_trs\"><tr><td width=\"23\"></td><td width=\"80\"><div id=\"chat_info_td_h\">Duration</div></td><td width=\"80\"><div id=\"chat_info_td_h\">Market</div></td><td width=\"90\"><div id=\"chat_info_td_h\">IP</div></td><td width=\"70\"><div id=\"chat_info_td_h\">Platform</div></td><td width=\"30\"><div id=\"chat_info_td_h\">F</div></td><td width=\"30\"><div id=\"chat_info_td_h\">R</div></td><td width=\"30\"><div id=\"chat_info_td_h\">I</div></td><td><div id=\"chat_info_td_h\">On Page</div></td><td width=\"80\"><div id=\"chat_info_td_h\">Refer</div></td></tr>" ;

					for ( c = 0; c < json_data.traffics.length; ++c )
					{
						var market_name = ( typeof( parent.markets[json_data.traffics[c]["marketid"]]["name"] ) != "undefined" ) ? parent.markets[json_data.traffics[c]["marketid"]]["name"] : "&nbsp;" ;
						image = "actions.png" ; image_info = "&nbsp;" ;

						if ( json_data.traffics[c]["ip"] == parent.ip )
							ip_exist = 1 ;

						if ( json_data.traffics[c]["chatting"] )
						{
							image = "chats.png" ;
							image_info = "class=\"help_tooltip\" title=\"- currently chatting\"" ;
						}

						traffic_string += "<tr style=\"\"><td class=\"chat_info_td_traffic\" width=\"23\" style=\"-moz-border-radius: 5px; border-radius: 5px;\" id=\"td_"+json_data.traffics[c]["md5"]+"\"><img src=\"../themes/<?php echo $opinfo["theme"] ?>/"+image+"\" border=\"0\" alt=\"\" style=\"cursor: pointer;\" "+image_info+" onClick=\"expand_footprint('"+json_data.traffics[c]["md5"]+"', '"+json_data.traffics[c]["duration"]+"', '"+market_name+"', '"+json_data.traffics[c]["ip"]+"', '"+json_data.traffics[c]["hostname"]+"', '"+json_data.traffics[c]["os"]+"', '"+json_data.traffics[c]["browser"]+"', '"+json_data.traffics[c]["resolution"]+"', '"+json_data.traffics[c]["t_footprints"]+"', '"+json_data.traffics[c]["t_requests"]+"', '"+json_data.traffics[c]["t_initiated"]+"', '"+json_data.traffics[c]["title"]+"', '"+json_data.traffics[c]["onpage"]+"', '"+json_data.traffics[c]["refer_snap"]+"', '"+json_data.traffics[c]["refer_raw"]+"' )\" id=\"footprint_"+json_data.traffics[c]["md5"]+"\"></td><td class=\"chat_info_td_traffic\" nowrap>"+json_data.traffics[c]["duration"]+"</td><td class=\"chat_info_td_traffic\">"+market_name+"</td><td class=\"chat_info_td_traffic\" onClick=\"$('#footprint_"+json_data.traffics[c]["md5"]+"').click()\"><span class=\"help_tooltip\" title=\"- Hostname: <b>"+json_data.traffics[c]["hostname"]+"</b>\" onClick=\"\"onClick=\"$('#footprint_"+json_data.traffics[c]["md5"]+"').click()\">"+json_data.traffics[c]["ip"]+"</span></td><td class=\"chat_info_td_traffic\" style=\"text-align: center;\" onClick=\"$('#footprint_"+json_data.traffics[c]["md5"]+"').click()\"><img src=\"../themes/<?php echo $opinfo["theme"] ?>/os/"+json_data.traffics[c]["os"]+".png\" border=0 alt=\""+json_data.traffics[c]["os"]+"\" class=\"help_tooltip\" title=\"- "+json_data.traffics[c]["os"]+"\" width=\"14\" height=\"14\"> &nbsp; <img src=\"../themes/<?php echo $opinfo["theme"] ?>/browsers/"+json_data.traffics[c]["browser"]+".png\" border=0 alt=\""+json_data.traffics[c]["browser"]+"\" class=\"help_tooltip\" title=\"- "+json_data.traffics[c]["browser"]+"\" width=\"14\" height=\"14\"></td><td class=\"chat_info_td_traffic\" onClick=\"$('#footprint_"+json_data.traffics[c]["md5"]+"').click()\"><span class=\"help_tooltip\" title=\"- Footprints: "+json_data.traffics[c]["t_footprints"]+"\">"+json_data.traffics[c]["t_footprints"]+"</span></td><td class=\"chat_info_td_traffic\" onClick=\"$('#footprint_"+json_data.traffics[c]["md5"]+"').click()\"><span class=\"help_tooltip\" title=\"- Requested Chat <b>"+json_data.traffics[c]["t_requests"]+"</b> times\">"+json_data.traffics[c]["t_requests"]+"</span></td><td class=\"chat_info_td_traffic\" onClick=\"$('#footprint_"+json_data.traffics[c]["md5"]+"').click()\"><span class=\"help_tooltip\" title=\"- Initiated: "+json_data.traffics[c]["t_initiated"]+"\">"+json_data.traffics[c]["t_initiated"]+"</span></td><td class=\"chat_info_td_traffic\"><span class=\"help_tooltip\" title=\"- "+json_data.traffics[c]["onpage"]+"\"><a href=\""+json_data.traffics[c]["onpage"]+"\" target=\"_blank\">"+json_data.traffics[c]["title"]+"</a></span></td><td class=\"chat_info_td_traffic\"><span class=\"help_tooltip\" title=\"- "+json_data.traffics[c]["refer_raw"]+"\"><a href=\""+json_data.traffics[c]["refer_raw"]+"\" target=\"_blank\">"+json_data.traffics[c]["refer_snap"]+"</a></span></td></tr>" ;
					}

					traffic_string += "</table>" ;

					$('#canned_body').empty().html( traffic_string ) ;
					init_tooltips( 'canned_body' ) ;
					init_trs() ;

					if ( ip_exist )
					{
						// todo: [mod Vic: 90] open up the ip automatically
					}
					$('#canned_body').show() ;

					init_divs() ;
				}
			});
		}
	}

	function expand_footprint( themd5, theduration, themarket, theip, thehostname, theos, thebrowser, theresolution, thet_footprints, thet_requests, thet_initiated, thetitle, theonpage, therefer_snap, therefer_raw )
	{
		parent.ip = theip ;

		$( '*', '#canned_body' ).each( function(){
			var div_name = $( this ).attr('id') ;
			if ( div_name.indexOf("td_") != -1 )
				$(this).removeClass('chat_info_td_traffic_img') ;
		} );

		$('#td_'+themd5).addClass('chat_info_td_traffic_img') ;
		populate_footprint( themd5, theduration, themarket, theip, thehostname, theos, thebrowser, theresolution, thet_footprints, thet_requests, thet_initiated, thetitle, theonpage, therefer_snap, therefer_raw ) ;
	}

	function populate_footprint( themd5, theduration, themarket, theip, thehostname, theos, thebrowser, theresolution, thet_footprints, thet_requests, thet_initiated, thetitle, theonpage, therefer_snap, therefer_raw )
	{
		var unique = unixtime() ;
		var json_data = new Object ;

		$.get("../ajax/chat_actions_op.php", { action: "footprints", ip: theip, unique: unique },  function(data){
			eval( data ) ;

			if ( json_data.status )
			{
				var footprints_string = "<table cellspacing=0 cellpadding=0 border=0>" ;
				for ( c = 0; c < json_data.footprints.length; ++c )
				{
					footprints_string += "<tr><td width=\"30\" style=\"text-align: center\" class=\"chat_info_td_h\"><b>"+json_data.footprints[c]["total"]+"</b></td><td width=\"100%\" class=\"chat_info_td\"><span class=\"help_tooltip\" title=\"- "+json_data.footprints[c]["onpage"]+"\"><a href=\""+json_data.footprints[c]["onpage"]+"\" target=\"_blank\">"+json_data.footprints[c]["title"]+"</a></td></tr>" ;
				}
				footprints_string += "<tr><td colspan=2 class=\"chat_info_end\"></td></tr></table>" ;

				$('#info_market').empty().html( themarket ) ;
				$('#info_duration').empty().html( theduration ) ;
				$('#info_platform').empty().html( "<img src=\"../themes/<?php echo $opinfo["theme"] ?>/os/"+theos+".png\" border=0 alt=\""+theos+"\" class=\"help_tooltip\" title=\"- "+theos+"\" width=\"14\" height=\"14\"> &nbsp; <img src=\"../themes/<?php echo $opinfo["theme"] ?>/browsers/"+thebrowser+".png\" border=0 alt=\""+thebrowser+"\" class=\"help_tooltip\" title=\"- "+thebrowser+"\" width=\"14\" height=\"14\">" ) ;
				$('#info_resolution').empty().html( theresolution ) ;
				$('#info_requests').empty().html( thet_requests ) ;
				$('#info_initiated').empty().html( thet_initiated ) ;
				$('#info_onpage').empty().html( "<span class=\"help_tooltip\" title=\"- "+theonpage+"\"><a href=\""+theonpage+"\" target=\"_blank\">"+thetitle+"</a></span>" ) ;
				$('#info_refer').empty().html( "<span class=\"help_tooltip\" title=\"- "+therefer_raw+"\"><a href=\""+therefer_raw+"\" target=\"_blank\">"+therefer_snap+"</a></span>" ) ;
				$('#info_footprints').empty().html( footprints_string ) ;

				$('#footprint_info_wrapper').fadeIn('fast', function() {
					//
				}) ;

				populate_requestinfo( theip, thehostname ) ;
			}
		});
	}

	function populate_requestinfo( theip, thehostname )
	{
		var unique = unixtime() ;
		var json_data = new Object ;

		$.get("../ajax/chat_actions_op.php", { action: "requestinfo", ip: theip, unique: unique },  function(data){
			eval( data ) ;

			$('#info_ip').empty().html( "<span class=\"help_tooltip\" title=\"- "+thehostname+"\">"+theip+"</span>" ) ;
			if ( json_data.status )
			{
				$('#info_duration').append( " <span class=\"info_good\"><img src=\"../themes/<?php echo $opinfo["theme"] ?>/info_chats.gif\" width=\"10\" height=\"10\" border=\"0\" alt=\"\" style=\"cursor: pointer;\" class=\"help_tooltip\" title=\"- currently in a chat session with "+json_data.name+"\"></span>" ) ;
			}
			$('#info_trans').empty().html( " <span style=\"text-decoration: underline; cursor: pointer;\" onClick=\"open_transcripts('"+theip+"')\">"+json_data.total_trans+"</span>" ) ;

			$('#chat_info_cans_select').empty().html( "<select id=\"canned_info_select\" style=\"width: 120px;\" onChange=\"select_canned()\"><option value=\"\"></option>"+parent.cans_string+"</select>" ) ;

			init_tooltips( 'footprint_info_wrapper' ) ;
			if ( typeof( parent.initiate_canid ) != "undefined" )
			{
				$('#canned_info_select').attr( 'selectedIndex', parent.initiate_canid ) ;
				select_canned() ;
			}
		});
	}

	function select_canned()
	{
		$( "#chat_info_initiate_message" ).val( $('#canned_info_select').val().stripv().replace( /<br>/g, "\r" ) ) ;
		parent.initiate_canid = $('#canned_info_select' ).attr( 'selectedIndex' ) ;
		
	}

	function close_footprint_info()
	{
		parent.ip = this.undefined ;
	
		$('#footprint_info_wrapper').fadeOut('fast', function() {
			$('#chatting_with').empty().html( "" ) ;
		}) ;
	}

	function initiate_chat()
	{
		var unique = unixtime() ;
		var json_data = new Object ;
		var deptid = parseInt( $('#ini_deptid').val() ) ;
		var message = encodeURIComponent( $('#chat_info_initiate_message').val() ) ;

		if ( deptid && message )
		{
			$('#btn_initiate').attr('disabled', true) ;

			$.get("../ajax/chat_actions_op.php", { action: "initiate", ip: parent.ip, deptid: deptid, question: message, unique: unique },  function(data){
				eval( data ) ;

				if ( json_data.status )
				{
					parent.input_focus() ;
					parent.close_extra( "traffic" ) ;
				}
				else
				{
					do_alert( 0, json_data.error ) ;
					$('#btn_initiate').attr('disabled', false) ;
				}
			});
		}
		else if ( !message )
		{
			$('#chat_info_initiate_message').focus() ;
			do_alert( 0, "Blank Initiate Message is invalid." ) ;
		}
		else if ( !deptid )
		{
			$('#ini_deptid').focus() ;
			do_alert( 0, "Blank Department is invalid" ) ;
		}
	}

	function open_transcripts( theip )
	{
		parent.open_transcripts_list( theip ) ;
	}

	function init_tooltips( thediv )
	{
		var help_tooltips = $( '#'+thediv ).find( '.help_tooltip' ) ;
		help_tooltips.tooltip({
			track: true, 
			delay: 0, 
			showURL: false, 
			showBody: "- ", 
			fade: 0
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
			<div id="canned_body" style="display: none; padding-bottom: 10px;"></div>
		</td><td class="t_mr"></td>
	</tr>
	<tr><td class="t_bl"></td><td class="t_bm"></td><td class="t_br"></td></tr>
	</table>
</div>

<div id="footprint_info_wrapper" style="position: absolute; display: none; top: 0px; left: 0px; height: 100%;">
	<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr><td class="t_tl"></td><td class="t_tm"></td><td class="t_tr"></td></tr>
	<tr>
		<td class="t_ml"></td><td class="t_mm">
			<div id="footprint_info">
				<table cellspacing=1 cellpadding=0 border=0 width="100%">
				<tr><td colspan="6"><div id="chat_info_td_h" style="cursor: pointer;" onClick="close_footprint_info()"><img src="../themes/<?php echo $opinfo["theme"] ?>/close_extra.png" width="12" height="12" border="0" alt=""> close</div></td></tr>
				<tr><td colspan="6"><div style="height: 5px;"></div></td></tr>
				<tr>
					<td class="chat_info_td_traffic" width="10" nowrap><img src="../themes/<?php echo $opinfo["theme"] ?>/info_bullet.gif" width="10" height="10" border="0" alt=""> IP</td>
					<td class="chat_info_td_traffic_info" nowrap><div id="info_ip"></div></td>
					<td class="chat_info_td_blank">&nbsp;</td>
					<td class="chat_info_td_traffic" width="10" nowrap><img src="../themes/<?php echo $opinfo["theme"] ?>/info_star.gif" width="10" height="10" border="0" alt=""> On Page</td>
					<td class="chat_info_td_traffic_info"><div id="info_onpage"></div></td>
				</tr>
				<tr>
					<td class="chat_info_td_traffic" width="10" nowrap><img src="../themes/<?php echo $opinfo["theme"] ?>/info_clock.gif" width="10" height="10" border="0" alt=""> Duration</td>
					<td class="chat_info_td_traffic_info" nowrap><div id="info_duration"></div></td>
					<td class="chat_info_td_blank">&nbsp;</td>
					<td class="chat_info_td_traffic"><img src="../themes/<?php echo $opinfo["theme"] ?>/info_refer.gif" width="10" height="10" border="0" alt=""> Refer</td>
					<td class="chat_info_td_traffic_info"><div id="info_refer"></div></td>
				</tr>
				<tr>
					<td class="chat_info_td_traffic" nowrap><img src="../themes/<?php echo $opinfo["theme"] ?>/info_chats.gif" width="10" height="10" border="0" alt=""> Chat Requests</td>
					<td class="chat_info_td_traffic_info" nowrap><div id="info_requests"></div></td>
					<td class="chat_info_td_blank">&nbsp;</td>
					<td class="chat_info_td_traffic" nowrap><img src="../themes/<?php echo $opinfo["theme"] ?>/info_search.gif" width="10" height="10" border="0" alt=""> Footprints</td>
					<td class="chat_info_td_traffic" rowspan="4">
						<div style="height: 85px; overflow: auto;"><div id="info_footprints"></div></div>
					</td>
				</tr>
				<tr>
					<td class="chat_info_td_traffic" nowrap><img src="../themes/<?php echo $opinfo["theme"] ?>/info_chats.gif" width="10" height="10" border="0" alt=""> Transcripts</td>
					<td class="chat_info_td_traffic_info" nowrap><div id="info_trans"></div></td>
				</tr>
				<tr>
					<td class="chat_info_td_traffic" nowrap><img src="../themes/<?php echo $opinfo["theme"] ?>/info_initiate.gif" width="10" height="10" border="0" alt=""> Initiated</td>
					<td class="chat_info_td_traffic_info" id="info_initiated"></td>
				</tr>
				<tr>
					<td class="chat_info_td_traffic" nowrap><img src="../themes/<?php echo $opinfo["theme"] ?>/info_globe.gif" width="10" height="10" border="0" alt=""> Platform</td>
					<td class="chat_info_td_traffic_info" nowrap><div id="info_platform"></div></td>
				</tr>
				<tr>
					<td class="chat_info_td_traffic" nowrap><img src="../themes/<?php echo $opinfo["theme"] ?>/info_box.gif" width="10" height="10" border="0" alt=""> Resolution</td>
					<td class="chat_info_td_traffic_info" nowrap><div id="info_resolution"></div></td>
				</tr>
				<!-- <tr>
					<td class="chat_info_td_traffic" nowrap><img src="../themes/<?php echo $opinfo["theme"] ?>/info_market.gif" width="10" height="10" border="0" alt=""> Market</td>
					<td class="chat_info_td_traffic_info" nowrap><div id="info_market"></div></td>
				</tr> -->
				</table>

				<form action="#">
				<div style="margin-top: 5px;">
					<table cellspacing=0 cellpadding=0 border=0>
					<tr>
						<td class="chat_info_td_traffic" nowrap><div style="font-size: 14px; font-weight: bold;">Initiate Chat</div></td><td class="chat_info_td_traffic"><input type="text" id="chat_info_initiate_message" class="input_text" size="100" maxlength="255" value=""></td>
					</tr>
					<tr>
						<td class="chat_info_td_traffic">&nbsp;</td><td class="chat_info_td_traffic">Canned Message: <span class="chat_cans_text_new" style="text-decoration: underline; cursor: pointer;" onClick="parent.toggle_extra( 'canned', 'new_canned', '', 'Create/Edit Canned' );">[+]</span> &nbsp; <span id="chat_info_cans_select" style="padding-right: 10px;"></span> Department: <span id="depts_select"></span> <input type="button" value="Initiate Chat" onClick="initiate_chat()" id="btn_initiate" style="margin-left: 10px;"></td>
					</tr>
					</table>
				</div>
				</form>
			</div>
		</td><td class="t_mr"></td>
	</tr>
	<tr><td class="t_bl"></td><td class="t_bm"></td><td class="t_br"></td></tr>
	</table>
</div>

</body>
</html>
<?php database_mysql_close( $dbh ) ; ?>
