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
	include_once( "../API/Ops/update.php" ) ;
	include_once( "../API/Depts/get.php" ) ;
	include_once( "../API/External/get.php" ) ;

	/***** [ BEGIN ] BASIC CLEANUP WHEN FIRST LOG IN *****/
	include_once( "../API/Chat/remove.php" ) ;
	include_once( "../API/Chat/Util.php" ) ;
	include_once( "../API/Footprints/remove.php" ) ;

	Footprints_remove_Expired_U( $dbh ) ;
	Chat_remove_ExpiredOp2OpRequests( $dbh ) ;
	Chat_remove_OldRequests( $dbh ) ;
	/***** [ END ] BASIC CLEANUP WHEN FIRST LOG IN *****/

	$wp = Util_Format_Sanatize( Util_Format_GetVar( "wp" ), "ln" ) ;
	$mobile = Util_Format_isMobile() ;

	$operators = Ops_get_AllOps( $dbh ) ;
	$departments = Depts_get_AllDepts( $dbh ) ;
	$op_depts = Depts_get_OpDepts( $dbh, $opinfo["opID"] ) ;
	$externals = External_get_OpExternals( $dbh, $opinfo["opID"] ) ;

	$depts_hash = "depts_hash[1111111111] = 'All Departments' ;" ;
	for ( $c = 0; $c < count( $departments ); ++$c )
	{
		$department = $departments[$c] ;
		$depts_hash .= "depts_hash[".$department["deptID"]."] = '$department[name]' ;" ;
	}

	$op_depts_hash = "" ;
	for ( $c = 0; $c < count( $op_depts ); ++$c )
	{
		$department = $op_depts[$c] ;
		$op_depts_hash .= "op_depts_hash[".$department["deptID"]."] = '$department[name]' ;" ;
	}
?>
<?php include_once( "../inc_doctype.php" ) ?>
<head>
<title> PHP Live! <?php echo $VERSION ?> </title>

<meta name="description" content="PHP Live! Support <?php echo $VERSION ?>">
<meta name="keywords" content="PHP Live! Support">
<meta name="robots" content="all,index,follow">
<meta http-equiv="content-type" content="text/html; CHARSET=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<link rel="Stylesheet" href="../themes/<?php echo $opinfo["theme"] ?>/style.css">
<script type="text/javascript" src="../js/global.js"></script>
<script type="text/javascript" src="../js/global_chat.js"></script>
<script type="text/javascript" src="../js/framework.js"></script>
<script type="text/javascript" src="../js/framework_ext.js"></script>
<script type="text/javascript" src="../js/tooltip.js"></script>
<script type="text/javascript" src="../js/jquery.tools.min.js"></script>

<script type="text/javascript">
<!--
	var mouse_x ; var mouse_y ;
	var base_url = ".." ; var base_url_full = "<?php echo $CONF["BASE_URL"] ?>" ;
	var isop = <?php echo $opinfo["opID"] ?> ;
	var cname = "<?php echo $opinfo["name"] ?>" ; var cemail = "<?php echo $opinfo["email"] ?>" ;
	var ces, info, extra, extra_top ;
	var ck_his = ex_his = bk_his = markets = new Array() ;
	var st_network, network_monitor, st_resize, st_typing, st_reconnect ;
	var si_offline, si_timer, si_title, si_typing, si_rating ;
	var si_his = new Object ;
	var tim_offline ;
	var cid = "cid_"+unixtime() ; // only ~14 chars instead of passing md5 32 chars
	var prev_status = 0 ;
	var rupdated ; // flag to tell if chats were removed in DB
	var traffic = <?php echo $opinfo["traffic"] ?> ;
	var prev_traffic = 0 ;
	var firsttime = true ; // used to indicate first time loading for opener
	var total_new_requests = 0 ;
	var traffic_sound = chat_sound = 1 ;
	var title_orig = document.title ;
	var si_counter = 0 ;
	var focused = 1 ;
	var reconnect_counter = 0 ; // reconnection flag so it runs once
	var widget = 0 ;
	var wp = <?php echo $wp ?> ;
	var mobile = <?php echo $mobile ?> ;

	var cans_string ; // make it global so op_traffic.php can reference
	var initiate_canid = 0 ; // make it global op_traffic.php
	var initiate_deptid = 0 ; // make it global op_traffic.php

	var loaded = 0 ;
	var newwin_print ;

	var ip ; // for op_traffic.php

	var chats = new Object ;
	var depts_hash = new Object ;
	var op_depts_hash = new Object ;

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		}) ;

		loaded = 1 ;
		init_divs(0) ;
		init_disconnect() ;
		fetch_markets() ;
		toggle_info( "info" ) ;
		populate_cans(0) ;
		check_network(.5, 1) ;
		update_ratings() ;
		init_typing() ;

		eval( "<?php echo $depts_hash ?>" ) ;
		eval( "<?php echo $op_depts_hash ?>" ) ;

		update_status(1) ;

		<?php if ( $opinfo["traffic"] ): ?>
		update_traffic_counter( pad( prev_traffic, 2 ) ) ;
		<?php endif ; ?>

		check_opener("login") ;
		// disable for now... can cause confusion (with above)
		//setInterval(function(){ check_logout(); }, 200) ;

		si_rating = setInterval( function(){
			if ( !$('#iframe_chat_engine').attr( "contentWindow" ).stopped )
				update_ratings() ;
		}, 10000 ) ;

		$('#reconnect_notice').center() ;
		// fixes UI issues on some devices scrolling past the body
		close_extra( "op2op" ) ;
	});
	$(window).resize(function() { init_divs(1) ; init_scrolling() ; });

	if ( !wp )
		window.onbeforeunload = function() { return "You are about to exit the window." ; }

	$(window).focus(function() {
		input_focus() ;
	});
	$(window).blur(function() {
		focused = 0 ;
	});

	function check_logout()
	{
		if ( ( typeof( opener.loaded ) != "undefined" ) && opener.loaded && !firsttime )
			toggle_status(3) ;
		firsttime = false ;
	}

	function init_info()
	{
		$( '*', 'body' ).each( function(){
			var div_name = $( this ).attr('id') ;
			var class_name = $( this ).attr('class') ;
			if ( ( div_name != "info_menu_"+info ) && ( class_name == "chat_info_menu" ) && total_chats() )
			{
				$(this).hover(
					function () {
						$(this).removeClass('chat_info_menu').addClass('chat_info_menu_hover') ;
					}, 
					function () {
						$(this).removeClass('chat_info_menu_hover').addClass('chat_info_menu') ;
					}
				);
			}
		} );
	}

	function menu_blink( thecolor, theces )
	{
		// todo: more smooth blinking
		if ( typeof( si_his[theces] ) == "undefined" )
		{
			if ( typeof( bk_his[theces] ) == "undefined" )
				bk_his[theces] = 1 ;

			si_his[theces] = setInterval(function(){ menu_blink_doit( thecolor, theces ) ; }, 1000) ;
		}
	}

	function menu_blink_doit( thecolor, theces )
	{
		var offcolor ;
		if ( thecolor == "red" )
			offcolor = "green" ;
		else
			offcolor = "red" ;

		if ( !( bk_his[theces] % 2 ) )
			$('#menu_'+theces).removeClass('chat_switchboard_cell_bl_'+thecolor).removeClass('chat_switchboard_cell_bl_'+offcolor).addClass('chat_switchboard_cell') ;
		else
			$('#menu_'+theces).removeClass('chat_switchboard_cell').addClass('chat_switchboard_cell_bl_'+thecolor) ;

		bk_his[theces] += 1 ;
	}

	function new_chat( thejson_data, theflag )
	{
		var thisces = thejson_data["ces"] ;
		var is_in_his = is_ces_in_his( thisces ) ;
		rupdated = theflag ;

		$(window).scrollTop(0); // fix of random UI quirk

		if ( typeof( chats[thisces] ) == "undefined" )
		{
			if ( !is_in_his )
			{
				chats[thisces] = new Object ;
				chats[thisces]["requestid"] = thejson_data["requestid"] ;
				chats[thisces]["deptid"] = thejson_data["deptid"] ;
				chats[thisces]["opid"] = thejson_data["opid"] ;
				chats[thisces]["op2op"] = ( thejson_data["status"] != 2 ) ? thejson_data["op2op"] : 0 ;
				chats[thisces]["opid_orig"] = <?php echo $opinfo["opID"] ?> ;
				chats[thisces]["status"] = thejson_data["status"] ;
				chats[thisces]["initiated"] = thejson_data["initiated"] ;
				chats[thisces]["etrans"] = thejson_data["etrans"] ;
				chats[thisces]["disconnected"] = 0 ;
				chats[thisces]["closed"] = 0 ;
				chats[thisces]["cid"] = cid ;
				chats[thisces]["vname"] = thejson_data["vname"] ;
				chats[thisces]["os"] = thejson_data["os"] ;
				chats[thisces]["browser"] = thejson_data["browser"] ;
				chats[thisces]["resolution"] = thejson_data["resolution"] ;
				chats[thisces]["vemail"] = thejson_data["vemail"] ;
				chats[thisces]["requests"] = thejson_data["requests"] ;
				chats[thisces]["ip"] = thejson_data["ip"] ;
				chats[thisces]["hostname"] = thejson_data["hostname"] ;
				chats[thisces]["agent"] = thejson_data["agent"] ;
				chats[thisces]["onpage"] = thejson_data["onpage"] ;
				chats[thisces]["title"] = thejson_data["title"] ;
				chats[thisces]["marketid"] = thejson_data["marketid"] ;
				chats[thisces]["refer_raw"] = thejson_data["refer_raw"] ;
				chats[thisces]["refer_snap"] = thejson_data["refer_snap"] ;
				chats[thisces]["footprints"] = 0 ;
				chats[thisces]["transcripts"] = 0 ;
				chats[thisces]["timer"] = thejson_data["created"] ;
				chats[thisces]["istyping"] = 0 ;
				chats[thisces]["input"] = "" ;

				if ( chats[thisces]["initiated"] )
				{
					input_focus() ;
					// todo: look into why above timer is not set
					// for now set it to current unixtime - works but above should work too
					chats[thisces]["timer"] = unixtime() ;
					chats[thisces]["trans"] = "<div class=\"ca\"><div class=\"ctitle\">Initiated Chat.  <span id=\"trans_title\">Connecting</span>...</div></div>" ;
				}
				else if ( chats[thisces]["status"] == 1 )
					chats[thisces]["trans"] = "" ;
				else if ( chats[thisces]["op2op"] == isop )
				{
					// set status as picked up to fix red blinking bug
					// todo: double check on status setting
					chats[thisces]["status"] = 1 ;
					// todo: look into why above timer is not set
					// for now set it to current unixtime - works but above should work too
					chats[thisces]["timer"] = unixtime() ;
					// <op2op> flag to indicate remove when picked up
					chats[thisces]["trans"] = "<c615><op2op><div class=\"ca\">Requesting operator chat. Please hold while connecting...</div></op2op></c615>" ;
				}
				else if ( chats[thisces]["op2op"] && ( chats[thisces]["status"] != 2 ) )
				{
					chats[thisces]["trans"] = "<c615><div class=\"ca\">Operator-to-operator chat request from <b>"+chats[thisces]["vname"]+"</b></div><div class=\"ca\"><button type=\"button\" class=\"input_button\" style=\"font-size: 10px;\" onClick=\"chat_accept();$(this).attr('disabled', 'true');\">accept</button> or <span onClick=\"chat_decline()\" style=\"text-decoration: underline; cursor: pointer;\">decline</span></div></c615>" ;
				}
				else if ( chats[thisces]["status"] == 2 )
				{
					// todo: look into why above timer is not set
					// for now set it to current unixtime - works but above should work too
					// same situation as op2op above
					chats[thisces]["timer"] = unixtime() ;
					chats[thisces]["trans"] = "<c615><div class=\"ca\"><div class=\"info_box\"><i>"+thejson_data["question"]+"</i></div> <div style=\"margin-top: 10px;\"><div class=\"ctitle\">Transferred Chat</div><div style=\"margin-top: 10px;\"><button type=\"button\" class=\"input_button\" style=\"font-size: 10px;\" onClick=\"chat_accept();$(this).attr('disabled', 'true');\">accept</button> or <span onClick=\"chat_decline()\" style=\"text-decoration: underline; cursor: pointer;\">decline</span></div></div></c615>" ;
				}
				else
					chats[thisces]["trans"] = "<c615><div class=\"ca\"><div class=\"info_box\"><i>"+thejson_data["question"]+"</i></div> <div style=\"margin-top: 10px;\"><div class=\"ctitle\">"+depts_hash[chats[thisces]["deptid"]]+"</div> <div style=\"margin-top: 10px;\"><button type=\"button\" style=\"font-size: 10px;\" class=\"input_button\" onClick=\"chat_accept();$(this).attr('disabled', 'true');\">accept</button> or <span onClick=\"chat_decline()\" style=\"text-decoration: underline; cursor: pointer;\">decline</span></div></div></c615>" ;

				chats[thisces]["fsize"] = 0 ;
				chats[thisces]["fline"] = 0 ;

				if ( !chats[thisces]["initiated"] )
					play_sound( "new_request" ) ;

				if ( wp && !chats[thisces]["initiated"] )
					window.external.wp_incoming_chat( thisces, chats[thisces]["vname"].replace( /<v>/, '' ), thejson_data["question"].replace( /<br>/, ' ' ) ) ;
			}
			else
				$('#iframe_chat_engine').attr( "contentWindow" ).write_debug( " [iih] " ) ;
		}
		else if ( ( chats[thisces]["status"] == 3 ) && ( !thejson_data["status"] || ( thejson_data["status"] == 2 ) ) )
		{
			// transferred chat is transferred BACK to the original operator
			add_text( "<c615><div class=\"ca\"><div style=\"margin-top: 10px;\"><div class=\"ctitle\">Transferred Chat</div><div style=\"margin-top: 10px;\"><button type=\"button\" class=\"input_button\" style=\"font-size: 10px;\" class=\"input_button\" onClick=\"chat_accept()\">accept</button> | <span onClick=\"chat_decline()\" style=\"text-decoration: underline; cursor: pointer;\">decline</span></div></div></c615>" ) ;
			
			// set status to transfer so it doesn't repeat the above message
			// also reset fline to start from begin of transcript
			chats[thisces]["status"] = 2 ;
			chats[thisces]["fsize"] = 0 ;
			chats[thisces]["fline"] = 0 ;
			chats[thisces]["disconnected"] = 0 ;

			play_sound( "new_request" ) ;
		}
		else if ( chats[thisces]["op2op"] )
		{
			if ( thejson_data["status"] && ( chats[thisces]["trans"].indexOf( "<op2op>" ) != -1 ) )
			{
				if ( ( typeof( ces ) != "undefined" ) && ( ces == thisces ) )
				{
					chats[thisces]["trans"] = chats[thisces]["trans"].replace(/<op2op>(.*?)<\/op2op>/i, "") ;
					$('#chat_body').html( chats[thisces]["trans"] ) ;
					init_textarea() ;
					disconnect_showhide() ;
				}
			}
		}
		else
			chats[thisces]["status"] = thejson_data["status"] ;

		if ( typeof( chats[thisces] ) != "undefined" )
			chats[thisces]["rupdated"] = rupdated ;
	}

	function init_chat_list( theflag )
	{
		var theclass, thisces ;
		var list_string = "" ;

		// clean out chats array memory of removed chats
		clean_chats( theflag ) ;

		for ( thisces in chats )
		{
			if ( thisces == ces )
				theclass = "chat_switchboard_cell_focus" ;
			else
				theclass = "chat_switchboard_cell" ;

			list_string += "<div id=\"menu_"+thisces+"\" class=\""+theclass+"\" style=\"float: left; padding: 2px; padding-left: 5px; padding-right: 5px;\" onClick=\"activate_chat('"+thisces+"')\"><img src=\"../themes/<?php echo $opinfo["theme"] ?>/online_green.png\" border=\"0\"> "+chats[thisces]["vname"]+"</div>" ;
		}

		if ( total_chats() )
		{
			list_string += "<div style=\"clear:both\"></div>" ;
			$('#options_print').show() ;
		}
		else
		{
			if ( typeof( ces ) != "undefined"  )
				toggle_info( "info" ) ;

			ces = this.undefined ;
			reset_canvas() ;
			disconnect_showhide() ;
			$('#options_print').hide() ;
		}

		if ( !total_new_requests )
			clear_sound( "new_request" ) ;

		$('#chat_switchboard').html( list_string ) ;

		// if first chat then activate it, if not already activated
		if ( ( total_chats() == 1 ) && ( $('#req_email').html() != thisces ) )
		{
			if ( ces != thisces )
				activate_chat( thisces ) ;
		}
		else if ( total_chats() && ( typeof( ces ) == "undefined" ) )
			activate_chat( get_chat_prev() ) ;

		for ( thisces in chats )
		{
			if ( ces != thisces )
			{
				if ( !chats[thisces]["status"] && !chats[thisces]["initiated"] )
				{
					menu_blink( "red", thisces ) ;
				}
			}
		}
	}

	function clean_chats( theflag )
	{
		rupdated = ( theflag ) ? theflag : rupdated ;
		for ( thisces in chats )
		{
			if ( !chats[thisces]["initiated"] )
			{
				if ( ( chats[thisces]["rupdated"] != rupdated ) && !chats[thisces]["disconnected"] && !chats[thisces]["status"] && !chats[thisces]["op2op"] )
					delete_chat_session( thisces ) ;
				else if ( ( chats[thisces]["rupdated"] != rupdated ) && chats[thisces]["op2op"] && !chats[thisces]["status"] )
					delete_chat_session( thisces ) ;
				else if ( ( chats[thisces]["rupdated"] != rupdated ) && ( chats[thisces]["status"] == 2 ) )
					delete_chat_session( thisces ) ;
				else
					$('#iframe_chat_engine').attr( "contentWindow" ).write_debug( " [ncl "+chats[thisces]["status"]+"] " ) ;
			}
		}
	}

	function init_iframe( theiframe )
	{
		var extra_wrapper_height = $('#chat_extra_wrapper').outerHeight() - $('#chat_footer').outerHeight() - 30 ;
		$('#'+theiframe).css({'height': extra_wrapper_height}) ;

		// skip op2op as it does blank loading
		var iframe_loc = String( $('#'+theiframe).attr( 'contentWindow' ).location ) ;
		if ( ( iframe_loc.indexOf("blank.php") == -1 ) && !( iframe_loc.indexOf("\/ops\/" ) == -1 ) )
			$('#'+theiframe).attr( 'contentWindow' ).toggle_cover(0) ;
	}

	function init_extra()
	{
		var pos_footer = $('#chat_footer').position() ;
		var chat_wrapper_top = pos_footer.top - $('#chat_extra_wrapper').outerHeight() ;

		$('#chat_extra_wrapper').css({'top': chat_wrapper_top}).fadeIn('fast', function() {
			//
		}) ;
	}

	function init_status()
	{
		var body_width = $(window).width() - 450 ;
		var chat_status_left = body_width + $('#chat_btn').outerWidth() + $('#chat_cans').outerWidth() + 60 ;
		var chat_network_left = chat_status_left + $('#chat_status').outerWidth() + 10 ;

		$('#chat_status').css({'left': chat_status_left}) ;
		$('#chat_network').css({'left': chat_network_left}) ;
	}

	function init_chats()
	{
		// empty - calls on each chat file checking
	}

	function total_chats()
	{
		var total = 0 ;
		total_new_requests = 0 ;

		for ( var thisces in chats )
		{
			++total ;
			// transferred chats are considered new chats
			if ( ( !chats[thisces]["status"] || ( chats[thisces]["status"] == 2 ) ) && !chats[thisces]["initiated"] )
				++total_new_requests ;
		}
		return total ;
	}

	function activate_chat( theces )
	{
		// store text to memory to place back when focused
		if ( typeof( chats[ces] ) != "undefined" )
			chats[ces]["input"] = $( "textarea#input_text" ).val() ;

		ces = theces ;

		if ( typeof( chats[ces] ) != "undefined" )
		{
			if ( typeof( si_his[ces] ) != "undefined" ) { clearInterval( si_his[ces] ) ; delete bk_his[ces] ; delete si_his[ces] ; }
			if ( typeof( markets[chats[ces]["marketid"]]["name"] ) == "undefined" ) { fetch_markets() ; }

			$('#chat_body').html( chats[ces]["trans"] ) ;
			
			$('textarea#input_text').val( chats[ces]["input"] ) ;
			if ( chats[ces]["input"] )
				$( "button#input_btn" ).attr( "disabled", false ) ;
			else
				$( "button#input_btn" ).attr( "disabled", true ) ;

			init_scrolling() ;
			ck_his.push( ces ) ;

			reset_chat_list_style() ;
			init_textarea() ;
			$('#menu_'+ces).removeClass('chat_switchboard_cell_bl_red').removeClass('chat_switchboard_cell_bl_green').removeClass('chat_switchboard_cell_bl_red').removeClass('chat_switchboard_cell').addClass('chat_switchboard_cell_focus') ;

			$('#chat_vname').html( chats[ces]["vname"] ) ;
			//$('#req_ces').html( ces ) ;

			// populate info section
			if ( !chats[ces]["op2op"] || ( chats[ces]["op2op"] && ( chats[ces]["status"] == 2 ) ) )
			{
				var req_etrans = ( chats[ces]["etrans"] ) ? "<img src=\"../themes/<?php echo $opinfo["theme"] ?>/info_email.gif\" width=\"10\" height=\"10\" border=\"0\" alt=\"\" class=\"help_tooltip\" title=\"- visitor will automatically be emailed a copy of the chat transcript when session ends\"> " : "" ;
				var req_email = ( chats[ces]["initiated"] ) ? "<i>initiated chat - email not available</i>" : req_etrans+"<a href=\"mailto:"+chats[ces]["vemail"]+"\" class=\"nounder\"><span class=\"chat_info_link\">"+chats[ces]["vemail"]+"</span></a>"+"&nbsp;" ;
				var marketing = ( typeof( markets[chats[ces]["marketid"]]["name"] ) != "undefined" ) ? markets[chats[ces]["marketid"]]["name"] : "" ;

				$('#req_dept').empty().html( depts_hash[chats[ces]["deptid"]]+"&nbsp;" ) ; 
				$('#req_email').empty().html( req_email ) ;
				$('#req_request').empty().html( chats[ces]["requests"] + " times(s)"+"&nbsp;" ) ;
				$('#req_onpage').empty().html( "<a href=\""+chats[ces]["onpage"]+"\" target=\"_blank\">"+chats[ces]["title"]+"</a>"+"&nbsp;" ) ;
				$('#req_refer').empty().html( "<a href=\"- "+chats[ces]["refer_raw"]+"\" target=\"_blank\">"+chats[ces]["refer_snap"]+"</a>"+"&nbsp;" ) ;
				$('#req_market').empty().html( marketing+"&nbsp;" ) ;
				$('#req_os').empty().html( "<img src=\"../themes/<?php echo $opinfo["theme"] ?>/os/"+chats[ces]["os"]+".png\" border=0 alt=\""+chats[ces]["os"]+"\" class=\"help_tooltip\" title=\"- "+chats[ces]["os"]+"\" width=\"10\" height=\"10\"> &nbsp; <img src=\"../themes/<?php echo $opinfo["theme"] ?>/browsers/"+chats[ces]["browser"]+".png\" border=0 alt=\""+chats[ces]["browser"]+"\" class=\"help_tooltip\" title=\"- "+chats[ces]["browser"]+"\" width=\"10\" height=\"10\">"+"&nbsp;" ) ;
				$('#req_ip').empty().html( chats[ces]["ip"] + " / " + chats[ces]["hostname"]+"&nbsp;" ) ;
				$('#req_resolution').empty().html( chats[ces]["resolution"]+"&nbsp;" ) ;
			}
			else
			{
				$('#req_dept').empty().html( "Operator 2 Operator Chat" ) ; 
				$('#req_email').empty().html( "&nbsp;" ) ;
				$('#req_request').empty().html( "&nbsp;" ) ;
				$('#req_onpage').empty().html( "&nbsp;" ) ;
				$('#req_refer').empty().html( "&nbsp;" ) ;
				$('#req_market').empty().html( "&nbsp;" ) ;
				$('#req_os').empty().html( "&nbsp;" ) ;
				$('#req_ip').empty().html( "&nbsp;" ) ;
				$('#req_resolution').empty().html( "&nbsp;" ) ;
			}

			$('#input_text').focus() ;
			toggle_info( "info" ) ;
			init_timer() ;

			populate_cans( chats[ces]["deptid"] ) ;
		}
		else
			populate_cans(0) ;

		disconnect_showhide() ;
	}

	function chat_accept()
	{
		var unique = unixtime() ;
		var json_data = new Object ;

		if ( typeof( ces ) != "undefined" )
		{
			$.get("../ajax/chat_actions_op.php", { action: "accept", requestid: chats[ces]["requestid"], ces: ces, unique: unique },  function(data){
				eval( data ) ;

				if ( json_data.status )
				{
					input_focus() ;
					if ( wp )
						wp_hide_tray( ces ) ;

					// if transferred, keep the same created time
					if ( chats[ces]["status"] != 2 )
						chats[ces]["timer"] = unixtime() ;

					chats[ces]["disconnected"] = 0 ; // reset it here just incase (it gets set to 1 in various areas)
					if ( json_data.tooslow )
					{
						chats[ces]["trans"] = "<div class='cl'>Session has expired.  Chat session has ended.</div>" ;
					}
					else
					{
						if ( chats[ces]["status"] == 2 )
						{
							$('#chat_body').html( "" ) ;
							var string = chats[ces]["trans"] ;
							chats[ces]["trans"] = string.c615() ;
						}
						else
							chats[ces]["trans"] = "" ;

						// set status to picked up always
						chats[ces]["status"] = 1 ;
						$('#chat_body').html( chats[ces]["trans"] ) ;
						init_scrolling() ;
						init_textarea() ;
						init_chat_list(0) ;
						toggle_info( "info" ) ;
						disconnect_showhide() ;
						init_timer() ;
					}
				}
				else
					alert( "Error: 1029" ) ;
			});
		}
	}

	function chat_decline()
	{
		var unique = unixtime() ;
		var json_data = new Object ;

		if ( !chats[ces]["status"] || chats[ces]["disconnected"] )
		{
			$.get("../ajax/chat_actions_op.php", { action: "decline", requestid: chats[ces]["requestid"], isop: isop, ces: ces, op2op: chats[ces]["op2op"], status: chats[ces]["status"], unique: unique },  function(data){
				eval( data ) ;

				if ( json_data.status )
					cleanup_disconnect( json_data.ces ) ;
			});
		}
	}

	function populate_cans( thedeptid )
	{
		var unique = unixtime() ;
		var json_data = new Object ;

		$.ajax({
		type: "GET",
		url: "../ajax/chat_actions_op.php",
		data: "action=cans&opid="+isop+"&deptid="+thedeptid+"&"+unique,
		success: function(data){
			eval( data ) ;

			if ( json_data.status )
			{
				var deptid = 0 ;
				cans_string = "" ;
				for ( c = 0; c < json_data.cans.length; ++c )
				{
					if ( !deptid || ( deptid != json_data.cans[c]["deptid"] ) )
					{
						deptid = json_data.cans[c]["deptid"] ;
						dept_name = depts_hash[deptid] ;
						cans_string += "<optgroup label=\""+dept_name+"\">" ;
					}

					cans_string += "<option value=\""+json_data.cans[c]["message"]+"\">"+json_data.cans[c]["title"]+"</option>" ;
				}

				$('#chat_cans_select').empty().html( "<select id=\"canned_select\" style=\"width: 120px;\">"+cans_string+"</select>" ) ;
				init_status() ;
			}
			else
				alert( "Error: 1030" ) ;
		},
		error:function (xhr, ajaxOptions, thrownError){
			alert( "Error: 1031" ) ;
		} });
	}

	function get_chat_prev()
	{
		var thisces ;
		for ( c = ck_his.length-1; c >= 0; --c )
		{
			thisces = ck_his[c] ;
			if ( typeof( chats[thisces] ) != "undefined" )
				return thisces ;
		}

		// otherwise activate the first chat request
		for ( var thisces in chats )
			return thisces ;
	}

	function is_ces_in_his( theces )
	{
		var temp_ces ;
		for ( c = ck_his.length-1; c >= 0; --c )
		{
			temp_ces = ck_his[c] ;
			if ( temp_ces == theces )
				return true ;
		}
		return false ;
	}

	function reset_chat_list_style()
	{
		for ( var thisces in chats )
			$('#menu_'+thisces).removeClass('chat_switchboard_cell_focus').addClass('chat_switchboard_cell') ;
	}

	function reset_canvas()
	{
		$('#chat_body').html("") ;
		$('#chat_vname').html("") ;
		$('#chat_vtimer').html("") ;
		$('*', 'body').each( function(){
			var div_name = $(this).attr('id') ;
			if ( div_name.indexOf( "req_" ) == 0 )
				$(this).html( "&nbsp;" ) ;
		} );
	}

	function pre_disconnect()
	{
		if ( ( chats[ces]["status"] != 1 ) && !chats[ces]["initiated"] )
			chat_decline() ;
		else
		{
			chats[ces]["closed"] = 1 ;
			disconnect() ;
		}
	}

	function cleanup_disconnect( theces )
	{
		if ( wp )
			wp_hide_tray( theces ) ;

		delete_chat_session( theces ) ;

		$('#chat_vname').html( "" ) ; $('#chat_vtimer').html( "" ) ;
		reset_canvas() ;
		init_chat_list(0) ;
		init_textarea() ;
		activate_chat( get_chat_prev() ) ;
		close_extra( extra ) ;

		if ( !total_new_requests )
			clear_sound( "new_request" ) ;
	}

	function delete_chat_session( theces )
	{
		if ( typeof( chats[theces] ) != "undefined" )
		{
			$('#iframe_chat_engine').attr( "contentWindow" ).write_debug( " DEL " ) ;
			delete chats[theces] ;
			if ( wp )
				wp_hide_tray( theces ) ;
			delete_from_his( theces ) ;
		}
	}

	function delete_from_his( theces )
	{
		var temp_ces ;
		// todo: perhaps clean the undefined or element using splice()
		for ( c = ck_his.length-1; c >= 0; --c )
		{
			temp_ces = ck_his[c] ;
			if ( temp_ces == theces )
			{
				$('#iframe_chat_engine').attr( "contentWindow" ).write_debug( " DEL-H " ) ;
				ck_his[c] = this.undefined ;
			}
		}
	}

	function toggle_info( thediv )
	{
		var divs = Array( "info", "footprints", "transcripts", "transfer", "spam" ) ;

		$('#info_close').hide() ;
		for ( c = 0; c < divs.length; ++c )
		{
			$('#info_menu_'+divs[c]).removeClass('chat_info_menu_focus').addClass('chat_info_menu') ;
			$('#info_'+divs[c]).hide() ;
			if ( divs[c] == "transcripts" )
				$('#info_'+divs[c]).removeClass('info_transcripts') ;

			if ( divs[c] != "info" )
				$('#info_'+divs[c]).html( "<img src=\"../themes/<?php echo $opinfo["theme"] ?>/loading_fb.gif\" border=\"0\" alt=\"\">" ) ;
		}

		if ( ( typeof( ces ) != "undefined" ) && ( typeof( chats[ces] ) != "undefined" ) )
		{
			info = thediv ;

			$('#info_menu_'+thediv).removeClass('chat_info_menu').addClass('chat_info_menu_focus') ;
			$('#info_'+thediv).show() ;

			if ( thediv == "info" )
			{
				// hiding and showing does not work.  have to repopulate for tooltip search [mod Jake: 82]
				if ( !chats[ces]["op2op"] )
				{
					var req_etrans = ( chats[ces]["etrans"] ) ? "<img src=\"../themes/<?php echo $opinfo["theme"] ?>/info_email.gif\" width=\"10\" height=\"10\" border=\"0\" alt=\"\" class=\"help_tooltip\" title=\"- visitor will automatically be emailed a copy of the chat transcript when session ends\"> " : "" ;
					var req_email = ( chats[ces]["initiated"] ) ? "<i>initiated chat - email not available</i>" : req_etrans+"<a href=\"mailto:"+chats[ces]["vemail"]+"\" class=\"nounder\"><span class=\"chat_info_link\">"+chats[ces]["vemail"]+"</span></a>"+"&nbsp;" ;

					$('#req_email').empty().html( req_email ) ;
					$('#req_onpage').empty().html( "<a href=\""+chats[ces]["onpage"]+"\" target=\"_blank\">"+chats[ces]["title"]+"</a>" ) ;
					$('#req_os').empty().html( "<img src=\"../themes/<?php echo $opinfo["theme"] ?>/os/"+chats[ces]["os"]+".png\" border=0 alt=\""+chats[ces]["os"]+"\" class=\"help_tooltip\" title=\"- "+chats[ces]["os"]+"\" width=\"10\" height=\"10\"> &nbsp; <img src=\"../themes/<?php echo $opinfo["theme"] ?>/browsers/"+chats[ces]["browser"]+".png\" border=0 alt=\""+chats[ces]["browser"]+"\" class=\"help_tooltip\" title=\"- "+chats[ces]["browser"]+"\" width=\"10\" height=\"10\"> " ) ;
					$('#req_refer').empty().html( "<a href=\""+chats[ces]["refer_raw"]+"\" target=\"_blank\">"+chats[ces]["refer_snap"]+"</a>" ) ;
					init_tooltips() ;
				}
			}
			else if ( thediv == "transfer" )
			{
				if ( ( chats[ces]["status"] == 1 ) && !chats[ces]["op2op"] && !chats[ces]["disconnected"] )
					populate_ops() ;
				else
					$('#info_transfer').html( "<div class=\"info_box\">Chat transfer not available for this session.</div>" ) ;
			}
			else if ( thediv == "footprints" )
				populate_footprints() ;
			else if ( thediv == "transcripts" )
				populate_transcripts() ;
			else if ( thediv == "spam" )
				populate_spam() ;

			close_extra( extra ) ;
		}
		else
		{
			$('#info_menu_info').removeClass('chat_info_menu').addClass('chat_info_menu_focus') ;
			$('#info_info').show() ;
		}

		init_info() ;
		disconnect_showhide() ;
	}

	function disconnect_showhide()
	{
		if ( ( typeof( ces ) != "undefined" ) && ( typeof( chats[ces] ) != "undefined" ) )
		{
			if ( !chats[ces]["closed"] )
				$('#info_disconnect').css({"padding": "3px"}).html( "<img src=\"../themes/<?php echo $opinfo["theme"] ?>/close_extra.png\" width=\"14\" height=\"14\" border=\"0\" alt=\"\"> Close chat with <b>"+chats[ces]["vname"]+"</b>" ) ;
		}
		else
			$('#info_disconnect').css({"padding": "0px"}).html( "" ) ;
	}

	function populate_ops()
	{
		var unique = unixtime() ;
		var json_data = new Object ;

		$.get("../ajax/chat_actions_op.php", { action: "deptops", unique: unique },  function(data){
			eval( data ) ;

			if ( json_data.status )
			{
				var ops_string = "" ;
				for ( c = 0; c < json_data.departments.length; ++c )
				{
					ops_string += "<div class=\"chat_info_td_h\"><b>"+json_data.departments[c]["name"]+"</b></div>" ;
					for ( c2 = 0; c2 < json_data.departments[c].operators.length; ++c2 )
					{
						var status = "offline" ;
						var status_bullet = "online_grey.png" ;
						var btn_transfer = "" ;

						if ( json_data.departments[c].operators[c2]["status"] )
						{
							status = "online" ;
							
							status_bullet= "online_green.png" ;
							btn_transfer = "<button type=\"button\" class=\"input_button\" onClick=\"transfer_chat( "+json_data.departments[c]["deptid"]+",'"+json_data.departments[c]["name"]+"',"+json_data.departments[c].operators[c2]["opid"]+",'"+json_data.departments[c].operators[c2]["name"]+"');$(this).attr('disabled', 'true');\" style=\"font-size: 12px;\">transfer</button>" ;
						}

						if ( json_data.departments[c].operators[c2]["opid"] == isop )
							ops_string += "<div class=\"chat_info_td\"><img src=\"../themes/<?php echo $opinfo["theme"] ?>/"+status_bullet+"\" width=\"12\" height=\"12\" border=\"0\"> <b>(You)</b> are "+status+" chatting with "+json_data.departments[c].operators[c2]["requests"]+" visitors</div>" ;
						else
							ops_string += "<div class=\"chat_info_td\"><img src=\"../themes/<?php echo $opinfo["theme"] ?>/"+status_bullet+"\" width=\"12\" height=\"12\" border=\"0\"> "+btn_transfer+" "+json_data.departments[c].operators[c2]["name"]+" is "+status+" chatting with "+json_data.departments[c].operators[c2]["requests"]+" visitors</div>" ;
					}
				}
				ops_string += "<div class=\"chat_info_end\"></div>" ;
				$('#info_transfer').html( ops_string ) ;
			}
		});
	}

	function populate_footprints()
	{
		var unique = unixtime() ;
		var json_data = new Object ;

		if ( chats[ces]["footprints"] == 0 )
		{
			$.get("../ajax/chat_actions_op.php", { action: "footprints", ip: chats[ces]["ip"], unique: unique },  function(data){
				eval( data ) ;

				if ( json_data.status )
				{
					var footprints_string = "<table cellspacing=0 cellpadding=0 border=0>" ;
					for ( c = 0; c < json_data.footprints.length; ++c )
					{
						footprints_string += "<tr><td width=\"30\" style=\"text-align: center\" class=\"chat_info_td_h\"><b>"+json_data.footprints[c]["total"]+"</b></td><td width=\"100%\" class=\"chat_info_td\"><span class=\"help_tooltip\" title=\"- "+json_data.footprints[c]["onpage"]+"\"><a href=\""+json_data.footprints[c]["onpage"]+"\" target=\"_blank\">"+json_data.footprints[c]["title"]+"</a></td></tr>" ;
					}
					footprints_string += "<tr><td colspan=2 class=\"chat_info_end\"></td></tr></table>" ;

					chats[ces]["footprints"] = footprints_string ;
					$('#info_footprints').html( chats[ces]["footprints"] ) ;
					init_tooltips() ;
				}
			});
		}
		else
		{
			$('#info_footprints').html( chats[ces]["footprints"] ) ;
			init_tooltips() ;
		}
	}

	function init_tooltips()
	{
		var help_tooltips = $( '#chat_data' ).find( '.help_tooltip' ) ;
		help_tooltips.tooltip({
			event: "mouseover",
			track: true,
			delay: 0,
			showURL: false,
			showBody: "- ",
			fade: 0,
			positionLeft: true,
			left: 150
		});
	}

	function populate_transcripts()
	{
		var unique = unixtime() ;
		var json_data = new Object ;

		if ( chats[ces]["transcripts"] == 0 )
		{
			$.get("../ajax/chat_actions_op.php", { action: "transcripts", ip: chats[ces]["ip"], unique: unique },  function(data){
				eval( data ) ;

				if ( json_data.status )
				{
					var transcripts_string = "<table cellspacing=0 cellpadding=0 border=0>" ;
					for ( c = 0; c < json_data.transcripts.length; ++c )
					{
						transcripts_string += "<tr><td nowrap><div id=\"transcript_"+json_data.transcripts[c]["ces"]+"\" class=\"chat_info_td_h chat_info_link\" onClick=\"open_transcript('"+json_data.transcripts[c]["ces"]+"')\">"+json_data.transcripts[c]["created"]+"</div></td><td nowrap><div class=\"chat_info_td\">"+json_data.transcripts[c]["duration"]+"</div></td><td width=\"100%\"><div class=\"chat_info_td\"><b>"+json_data.transcripts[c]["operator"]+"</b></div></td></tr>" ;
					}
					transcripts_string += "<tr><td colspan=2 class=\"chat_info_end\"></td></tr></table>" ;

					if ( json_data.transcripts.length == 0 )
						transcripts_string = "<div class=\"info_box\">Visitor has no past transcripts.</div>" ;

					chats[ces]["transcripts"] = transcripts_string ;
					$('#info_transcripts').html( chats[ces]["transcripts"] ) ;
				}
			});
		}
		else
			$('#info_transcripts').html( chats[ces]["transcripts"] ) ;
	}

	function populate_spam()
	{
		var unique = unixtime() ;
		var json_data = new Object ;

		$.get("../ajax/chat_actions_op.php", { action: "spam_check", ip: chats[ces]["ip"], unique: unique },  function(data){
			eval( data ) ;

			if ( json_data.status )
			{
				var spam_string = "<div class=\"info_box\" style=\"margin-bottom: 10px;\"><ul>&#149; Block visitor from future chat requests.<br>&#149; Blocked visitors will always see an offline status.<br>&#149; Blocked visitor will be unblocked automatically after 5 days.</ul></div>" ;

				if ( json_data.exist == 0 )
					spam_string += "<div id=\"info_spam_action\"><button type=\"button\" class=\"input_button\" onClick=\"spam_block(1, '"+chats[ces]["ip"]+"')\">Spam Block</button></div>" ;
				else
					spam_string += "<div id=\"info_spam_action\" class=\"chat_info_link\" onClick=\"spam_block(0, '"+chats[ces]["ip"]+"')\">Visitor has been blocked.  Click to unblock visitor.</div>" ;

				chats[ces]["spam"] = spam_string ;
				$('#info_spam').html( chats[ces]["spam"] ) ;
			}
		});
	}

	function spam_block( theflag, theip )
	{
		var unique = unixtime() ;
		var json_data = new Object ;

		$('#info_spam_action').html( "<img src=\"../themes/<?php echo $opinfo["theme"] ?>/loading_fb.gif\" border=\"0\" alt=\"\">" ) ;

		$.get("../ajax/chat_actions_op.php", { action: "spam_block", flag: theflag, ip: theip, unique: unique },  function(data){
			eval( data ) ;

			if ( json_data.status )
			{
				// reset spam so it refreshes at populate
				chats[ces]["spam"] = 0 ;
				populate_spam() ;
			}
			else
				alert( "ERROR: 110" ) ;
		});
	}

	function transfer_chat( thedeptid, thedeptname, theopid, theopname )
	{
		var unique = unixtime() ;
		var json_data = new Object ;

		// only transfer if chat has not been transferred already
		if ( chats[ces]["status"] != 3 )
		{
			$.get("../ajax/chat_actions_op.php", { action: "transfer", requestid: chats[ces]["requestid"], ces: ces, deptid: thedeptid, deptname: thedeptname, opid: theopid, opname: theopname, unique: unique },  function(data){
				eval( data ) ;

				if ( json_data.status )
				{
					// we want to delete chat AND remove it from history list so it can
					// repopulate if transferred back to same op
					chats[ces]["status"] = 3 ; // set it to 3, for AFTER transfer (used only here)
					chats[ces]["disconnected"] = unixtime() ;
					var trans_string = "<c615><div class='cl'>Chat has been transferred to <b>"+theopname+"</b> of <b>"+thedeptname+"</b>.  Chat session has ended.  <span onClick=\"pre_disconnect();\" style=\"text-decoration: underline; cursor: pointer;\">Click to disconnect.</span></div></c615>" ;
					chats[ces]["trans"] += trans_string  ;
					$('#chat_body').append( trans_string ) ;
					init_scrolling() ;
					init_textarea() ;
				}
				else
					alert( "ERROR: 110" ) ;
			});
		}
		else
		{
			// todo: display message instead of disabling the buttons
		}
	}

	function fetch_markets()
	{
		var unique = unixtime() ;
		var json_data = new Object ;

		$.get("../ajax/chat_actions_op.php", { action: "markets", unique: unique },  function(data){
			eval( data ) ;

			if ( json_data.status )
			{
				for ( c = 0; c < json_data.markets.length; ++c )
				{
					var marketid = json_data.markets[c].marketid ;
					var name = json_data.markets[c].name ;
					var color = json_data.markets[c].color ;

					markets[marketid] = new Object ;
					markets[marketid]["name"] = name ;
					markets[marketid]["color"] = color ;
				}
				// add the dummy ZERO
				markets["0"] = new Object ;
			}
			else
				alert( "ERROR: 110" ) ;
		});
	}

	function populate_ops_op2op()
	{
		$('#chat_extra_body_op2op').html( "<iframe id=\"iframe_op2op\" name=\"iframe_op2op\" style=\"width: 100%; border: 0px;\" src=\"op_op2op.php?ses=<?php echo $ses ?>\" scrolling=\"auto\" border=0 frameborder=0 onLoad=\"init_iframe( 'iframe_op2op' )\"></iframe>" ).show() ;
	}

	function populate_traffic()
	{
		$('#chat_extra_body_traffic').html( "<iframe id=\"iframe_traffic\" name=\"iframe_traffic\" style=\"width: 100%; border: 0px;\" src=\"op_traffic.php?ses=<?php echo $ses ?>\" scrolling=\"auto\" border=0 frameborder=0 onLoad=\"init_iframe( 'iframe_traffic' )\"></iframe>" ).show() ;
	}

	function populate_canned( theflag )
	{
		$('#chat_extra_body_canned').html( "<iframe id=\"iframe_canned\" name=\"iframe_canned\" style=\"width: 100%; border: 0px;\" src=\"op_canned.php?ses=<?php echo $ses ?>&flag="+theflag+"\" scrolling=\"auto\" border=0 frameborder=0 onLoad=\"init_iframe( 'iframe_canned' )\"></iframe>" ).show() ;
	}

	function populate_trans()
	{
		$('#chat_extra_body_trans').html( "<iframe id=\"iframe_trans\" name=\"iframe_trans\" style=\"width: 100%; border: 0px;\" src=\"op_trans.php?ses=<?php echo $ses ?>\" scrolling=\"auto\" border=0 frameborder=0 onLoad=\"init_iframe( 'iframe_trans' )\"></iframe>" ).show() ;
	}

	function populate_ext( thediv, theurl )
	{
		var temp = "chat_extra_body_ext_"+thediv ;

		if ( typeof( ex_his[temp] ) == "undefined" )
		{
			$('#'+temp).html( "<iframe id=\"iframe_ext_"+thediv+"\" name=\"iframe_ext_"+thediv+"\" style=\"width: 100%; border: 0px;\" src=\""+theurl+"\" scrolling=\"auto\" border=0 frameborder=0 onLoad=\"init_iframe( 'iframe_ext_"+thediv+"' )\"></iframe>" ).show() ;
			ex_his[temp] = 1 ;
		}
		else
			$('#'+temp).show() ;
	}

	function toggle_extra( thediv, theflag, theurl, thename )
	{
		// reset menu first
		reset_footer() ;

		if ( extra == thediv )
		{
			close_extra( thediv ) ;
			$( "textarea#input_text" ).focus() ;

			// todo: take out blank.php method
			if ( ( thediv == "op2op" ) || ( thediv == "traffic" ) )
				setTimeout( function(){ $('#iframe_op2op').attr( 'src', "../blank.php" ) ; }, 500 ) ;
		}
		else
		{
			extra = thediv ;

			if ( typeof( thediv ) == "number" )
				$('#chat_footer_cell_ext_'+thediv).removeClass('chat_footer_cell').addClass('chat_footer_cell_focus') ;
			else
				$('#chat_footer_cell_'+thediv).removeClass('chat_footer_cell').addClass('chat_footer_cell_focus') ;

			$('#chat_extra_body').html( "<div class=\"chat_info_td_blank\"><img src=\"../themes/<?php echo $opinfo["theme"] ?>/loading_fb.gif\" border=\"0\" alt=\"\"></div>" ) ;

			$('#chat_extra_title').html( "<img src=\"../themes/<?php echo $opinfo["theme"] ?>/close_extra.png\" width=\"14\" height=\"14\" border=\"0\" alt=\"\" style=\"cursor: pointer;\" onClick=\"close_extra( '"+extra+"' )\"> " + thename ) ;
			if ( thediv == "op2op" )
			{
				hide_extra( "chat_extra_body_"+thediv ) ;
				populate_ops_op2op() ;
			}
			else if ( thediv == "traffic" )
			{
				$('#chat_extra_title').append( "<span id=\"div_traffic_sound\" class=\"sound_box_on\" style=\"margin-left: 20px; font-weight: normal; font-size: 10px; cursor: pointer;\" onClick=\"toggle_traffic_sound()\"></span>" ) ;
				print_traffic_sound_text() ;
				hide_extra( "chat_extra_body_"+thediv ) ;
				populate_traffic() ;
			}
			else if ( thediv == "canned" )
			{
				$('#chat_extra_title').append( "<span id=\"div_new_canned\" style=\"margin-left: 20px; font-weight: normal; font-size: 10px; cursor: pointer;\" onClick=\"$('#iframe_canned').attr( 'contentWindow' ).toggle_new(1)\">[+] new canned</span>" ) ;
				hide_extra( "chat_extra_body_"+thediv ) ;
				populate_canned( theflag ) ;
			}
			else if ( thediv == "trans" )
			{
				hide_extra( "chat_extra_body_"+thediv ) ;
				populate_trans() ;
			}
			else
			{
				hide_extra( "chat_extra_body_ext_"+thediv ) ;
				populate_ext( thediv, theurl ) ;
			}

			init_extra() ;
		}
	}

	function reset_footer()
	{
		var divs = Array( "op2op", "traffic", "canned", "trans" ) ;
		for ( c = 0; c < divs.length; ++c )
			$('#chat_footer_cell_'+divs[c]).removeClass('chat_footer_cell_focus').addClass('chat_footer_cell') ;

		$('div').filter(function() {
			return this.id.match(/chat_footer_cell_ext_\d/) ;
		}).removeClass('chat_footer_cell_focus').addClass('chat_footer_cell') ;
	}

	function close_extra( thediv )
	{
		var pos_footer = $('#chat_footer').position() ;
		var chat_extra_wrapper_top = pos_footer.top - 1 ;

		if ( typeof( extra ) == "undefined" )
		{
			
		}
		else
		{
			$('#chat_extra_wrapper').fadeOut('fast', function() {
				extra = this.undefined ;
				reset_footer() ;
			}) ;
		}
	}

	function hide_extra( theflag )
	{
		var divs = Array( "chat_extra_body_op2op", "chat_extra_body_traffic", "chat_extra_body_canned", "chat_extra_body_trans" ) ;

		for ( c = 0; c < divs.length; ++c )
		{
			if ( divs[c] != theflag )
				$('#'+divs[c]).hide() ;
		}
		$('div').filter(function() {
			return this.id.match(/chat_extra_body_ext_\d/) ;
		}).hide() ;
	}

	function select_canned()
	{
		if ( total_chats() && ( typeof( ces ) != "undefined" ) && chats[ces]["status"] && !chats[ces]["disconnected"] )
		{
			$( "textarea#input_text" ).val( $('#canned_select').val().stripv().replace( /<br>/g, "\r" ) ) ;
			$( "textarea#input_text" ).focus() ;
			$( "button#input_btn" ).attr( "disabled", false ) ;
		}
	}

	function check_network( themicrotime, theunixtime )
	{
		network_monitor = theunixtime ;
		update_network( themicrotime ) ;

		//if ( typeof( st_network ) == "undefined" )
		//	st_network = setTimeout( function(){ monitor_network() ; }, <?php echo $VARS_JS_REQUESTING ?> * 1000 ) ;
	}

	function monitor_network()
	{
		var idle = unixtime() - <?php echo $VARS_OP_DC ?> ;

		// todo: look into this area further... more testing needed to be used
		if ( network_monitor < idle )
		{
			clearTimeout( st_network ) ; st_network = this.undefined ;
			update_network( 10000 ) ;
		}
		st_network = this.undefined ;
	}

	function update_network( themicrotime )
	{
		if ( themicrotime <= 0.20 )
			$('#chat_network_img').css({'background-position': '0px bottom'}) ; // 5
		else if ( themicrotime <= 0.45 )
			$('#chat_network_img').css({'background-position': '0px -152px'}) ; // 4
		else if ( themicrotime <= 0.75 )
			$('#chat_network_img').css({'background-position': '0px -114px'}) ; // 3
		else if ( themicrotime <= 0.85 )
			$('#chat_network_img').css({'background-position': '0px -76px'}) ; // 2
		else if ( themicrotime <= 50 )
			$('#chat_network_img').css({'background-position': '0px -38px'}) ; // 1
		else
		{
			// disconnected by calling p_engine.php stop() function (attempt to reconnect)
			$('#chat_network_img').css({'background-position': '0px 0px'}) ; // x

			reconnect() ;
		}	
	}

	function toggle_status( thestatus )
	{
		// make it active if status is online to hide logout div
		if ( ( prev_status != thestatus ) || !thestatus )
		{
			if ( typeof( st_logout ) != "undefined" )
			{
				clearTimeout( st_logout ) ;
				st_logout = this.undefined ;
			}

			$('#chat_status_logout').hide() ;
			if ( typeof( si_offline ) != "undefined" ) { clearInterval( si_offline ); si_offline = this.undefined ; $('#offline_timer').html( "60:00" ) ; }

			if ( !thestatus )
			{
				prev_status = thestatus ;
				$('#chat_status').css({'background': ''}) ; $('#chat_status_offline').hide() ;
				update_status(1) ;
			}
			else if ( thestatus == 1 )
			{
				prev_status = thestatus ;
				var color = $('#chat_status_offline').css("background-color") ;

				$('#chat_status').css({'background-color': color}) ;
				$('#chat_status_offline').show() ;

				start_offline_timer( 3600 ) ;
				update_status(0) ;
			}
			else if ( thestatus == 2 )
			{
				$('#chat_status_logout').show() ;
				$('#chat_status').css({'background': ''}) ; $('#chat_status_offline').hide() ;

				// incase they left and idle, then mind as well log out after 5 minutes
				st_logout = setTimeout( function(){ toggle_status( 3 ) ; }, 300000 ) ;
			}
			else if ( ( thestatus == 3 ) || ( thestatus == 4 ) )
			{
				check_opener( "logout" ) ;
				window.onbeforeunload = null ;
				location.href = base_url+"/?action=logout&from=operator&auto=1&wp="+wp ;
			}
			else
			{
				$('input[name=status]:eq('+prev_status+')').attr('checked', 'checked') ;
				$('#chat_status_logout').hide() ;
				
				// reset the prev_status so the status does not equal
				if ( !prev_status )
				{
					prev_status = this.undefined ;
					toggle_status( 0 ) ;
				}
				else if ( prev_status )
				{
					prev_status = this.undefined ;
					toggle_status( 1 ) ;
				}
			}
		}
	}

	function update_status( thestatus )
	{
		var unique = unixtime() ;
		var json_data = new Object ;

		$.get("../ajax/chat_actions_op.php", { action: "update_status", opid: <?php echo $opinfo["opID"] ?>, status: thestatus, unique: unique },  function(data){
			eval( data ) ;

			if ( json_data.status )
			{
				$('#chat_status_logout').hide() ;
			}
			else
				alert( "ERROR: 110" ) ;
		});
	}

	function update_ratings()
	{
		var unique = unixtime() ;
		var json_data = new Object ;

		$.ajax({
		type: "GET",
		url: "../ajax/chat_actions_op.php",
		data: "action=fetch_ratings&opid=<?php echo $opinfo["opID"] ?>&ses=<?php echo $ses ?>&"+unique,
		success: function(data){
			eval( data ) ;

			if ( json_data.status )
			{
				$('#rating_recent').html( json_data.rating_recent ).unbind('click').bind('click', function() {
					if ( json_data.ces )
						open_transcript( json_data.ces ) ;
				});
				$('#rating_overall').html( json_data.rating_overall ) ;
			}

			if ( !json_data.status_op || json_data.signal )
				toggle_status(3) ;
		},
		error:function (xhr, ajaxOptions, thrownError){
			// nothing needed
		} });
	}

	function start_offline_timer( thestart )
	{
		tim_offline = thestart ;
		si_offline = setInterval( "offline_timer()", 1000 ) ;
	}

	function reset_offline_timer()
	{
		if ( typeof( si_offline ) != "undefined" )
		{
			clearInterval( si_offline ) ; si_offline = this.undefined ;
			start_offline_timer( 3600 ) ;
		}
	}

	function offline_timer()
	{
		if ( tim_offline )
		{
			var mins = Math.floor( tim_offline/60 ) ;
			var secs = pad( tim_offline - ( mins * 60 ), 2 ) ;
			var display = mins+":"+secs ;

			$('#offline_timer').html( display ) ;
			--tim_offline ;
		}
		else if ( typeof ( si_offline ) != "undefined" )
		{
			clearInterval( si_offline ) ; si_offline = this.undefined ;
			toggle_status(3) ;
		}
	}

	function launch_home()
	{
		var url = "index.php?ses=<?php echo $ses ?>" ;

		if ( !wp )
			open( url, "operator" ) ;
		else
			wp_new_win( url, "operator", 800, 550 ) ;
	}

	function open_transcript( theces )
	{
		var url = "<?php echo $CONF["BASE_URL"] ?>/ops/op_trans_view.php?ses=<?php echo $ses ?>&ces="+theces+"&id="+isop+"&wp="+wp+"&auth=op&"+unixtime() ;
	
		if ( !wp )
		{
			newwin = window.open( url, "Chat Transcript: "+theces, "scrollbars=yes,menubar=no,resizable=1,location=no,width=650,height=450,status=0" ) ;
			if ( newwin )
				newwin.focus() ;
		}
		else
			wp_new_win( url, "Chat Transcript: "+theces, 650, 450 ) ;
	}

	function open_transcripts_list( theip )
	{
		var url = "<?php echo $CONF["BASE_URL"] ?>/ops/op_trans_list.php?ses=<?php echo $ses ?>&wp="+wp+"&ip="+theip+"&"+unixtime() ;
	
		if ( !wp )
		{
			newwin = window.open( url, "Chat Transcripts", "scrollbars=yes,menubar=no,resizable=1,location=no,width=650,height=470,status=0" ) ;
			if ( newwin )
				newwin.focus() ;
		}
		else
			wp_new_win( url, "Chat Transcripts", 650, 470 ) ;
	}

	function toggle_log()
	{
		var bottom = $('#iframe_chat_engine').show().css('bottom') ;
		
		if ( bottom == "-250px" )
			$('#iframe_chat_engine').css({'bottom': '26px'}) ;
		else
			$('#iframe_chat_engine').css({'bottom': '-250px'}) ;
	}

	function update_traffic_counter( thecounter )
	{
		if ( ( prev_traffic != thecounter ) && ( extra == "traffic" ) && ( typeof( $('#iframe_traffic').attr( "contentWindow" ).loaded ) != "undefined" ) )
			$('#iframe_traffic').attr( "contentWindow" ).populate_traffic() ;

		if ( prev_traffic != thecounter )
		{
			$('#chat_footer_traffic_counter').html( thecounter ) ;
			if ( thecounter && traffic_sound )
				play_sound( "new_traffic" ) ;
		}

		if ( wp )
			wp_total_visitors( thecounter )

		prev_traffic = thecounter ;
	}

	function check_opener( thestatus )
	{
		// for now disable... can cause confusion
		return true ;

		if ( ( typeof( opener ) != "undefined" ) && ( typeof( opener.menu ) != "undefined" ) && !opener.closed && ( thestatus == "logout" ) )
			opener.location.href = "./?action=logout&from=operator&auto=1&wp="+wp ;		
		else if ( ( typeof( opener ) != "undefined" ) && ( typeof( opener.menu ) == "undefined" ) && !opener.closed && ( thestatus == "login" ) )
			opener.location.href = "index.php?ses=<?php echo $ses ?>" ;
	}

	function toggle_traffic_sound()
	{
		if ( traffic_sound )
			traffic_sound = 0 ;
		else
			traffic_sound = 1 ;

		print_traffic_sound_text() ;
	}

	function print_traffic_sound_text()
	{
		if ( traffic_sound )
			$('#div_traffic_sound').html( "<img src=\"../themes/<?php echo $opinfo["theme"] ?>/bell_start.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\"> turn sound off" ).removeClass('sound_box_off').addClass('sound_box_on') ;
		else
			$('#div_traffic_sound').html( "<img src=\"../themes/<?php echo $opinfo["theme"] ?>/bell_stop.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\">  turn sound on" ).removeClass('sound_box_on').addClass('sound_box_off') ;
	}

	function reload_console()
	{
		var unique = unixtime() ;

		window.onbeforeunload = null ;
		location.href = "operator.php?ses=<?php echo $ses ?>&"+unique ;
	}

	function reconnect()
	{
		++reconnect_counter ;

		// 2 minutes of try... then have them reload since faster
		if ( reconnect_counter > 20 )
		{
			$('#iframe_chat_engine').attr( "contentWindow" ).stopit(0) ;
			$('#reconnect_status').empty().html("<img src=\"../themes/<?php echo $opinfo["theme"] ?>/alert.png\" width=\"14\" height=\"14\" border=\"0\" alt=\"\"> Could not reconnect.  <span onClick=\"reload_console()\" style=\"text-decoration: underline; cursor: pointer;\">Try reloading the console.</span>") ;
		}
		else
		{
			$('#iframe_chat_engine').attr( "contentWindow" ).stopit(1) ;

			$('#reconnect_notice').show() ;
			$('#reconnect_attempt').empty().html( "- reconnect attempt: "+reconnect_counter ) ;

			// only need to call requesting() as the function restarts chatting()
			$('#iframe_chat_engine').attr( "contentWindow" ).requesting() ;
		}
	}

	function reconnect_success()
	{
		reconnect_counter = 0 ;
		if ( $('#reconnect_notice').is(':visible') )
			$('#reconnect_notice').hide() ;
	}

	// WinApp Integration
	function wp_decline_chat() { chat_decline() ; }

	function wp_total_visitors( thecounter )
	{
		// put # in taskbar
		// * = logo icon
		window.external.wp_total_visitors( thecounter ) ;
	}

	function wp_focus_chat() { wp_popup() ; }
	function wp_minimize() { window.external.wp_minimize() ; }
	function wp_maximize() { window.external.wp_maximize() ; }
	function wp_popup() { window.external.window.external.wp_popup() ; }
	function wp_hide_tray( theces ) { window.external.wp_hide_tray( theces ) ; }
	function wp_new_win( theurl, thetitle, thew, theh) { window.external.wp_new_win( theurl, thetitle, thew, theh ) ; }

	function wp_pre_go_offline()
	{
		// do pre logout, call functions to go offline
		toggle_status(4) ;
	}

	function wp_go_offline()
	{
		// confirm go offline and logout
		wp_pre_go_offline() ;
		if ( typeof( window.external.wp_go_offline() ) != "undefined" )
			window.external.wp_go_offline() ;
	}

//-->
</script>
</head>
<body>

<div id="chat_canvas" style="min-height: 100%; width: 100%;" class="chat_canvas_op">
	<div id="chat_switchboard" style="height: 19px; padding-left: 10px;"></div>
</div>
<div style="position: absolute; top: 20px; padding: 10px; z-Index: 2;" onClick="close_extra( extra )">
	<div id="chat_body" style="overflow: auto;"></div>
	<div id="chat_options" style="padding-top: 10px;">
		<div style="height: 16px;">
			<div id="options_print" style="display: none; float: left;"><img src="../themes/<?php echo $opinfo["theme"] ?>/printer.png" width="16" height="16" border="0" alt="" style=" cursor: pointer;" onClick="do_print(ces)"> <span style="padding-left: 15px;"><img src="../themes/<?php echo $opinfo["theme"] ?>/sound_on.png" width="16" height="16" border="0" alt="" style="cursor: pointer;" onClick="toggle_chat_sound('<?php echo $opinfo["theme"] ?>')" id="chat_sound"></span> <span id="chat_vtimer" style="position: relative; top: -2px; padding-left: 15px;"></span> <span id="chat_vname" style="position: relative; top: -2px; padding-left: 15px;"></span> <span id="chat_vistyping" style="position: relative; top: -2px;"></span></div>
			<div style="clear: both;"></div>
		</div>
	</div>
	<div id="chat_input" style="margin-top: 8px;">
		<textarea id="input_text" rows="3" style="padding: 2px; height: 75px; resize: none;" wrap="virtual" onKeyup="input_text_listen(event);" onKeydown="input_text_typing(event);" readonly></textarea>
	</div>
</div>
<div id="chat_data" style="position: absolute; overflow: hidden;">
	<div class="chat_info_wrapper" style="margin-right: 8px;">
		<div id="chat_info_header" style="margin-bottom: 5px;">
			<div style="float: left;">
				<div class="rating_title">recent rating:</div>
				<div id="rating_recent" style="cursor: pointer"></div>
			</div>
			<div style="float: left; margin-left: 25px;">
				<div class="rating_title">overall rating:</div>
				<div id="rating_overall"></div>
			</div>
			<div style="clear: both;"></div>
		</div>
		<div id="chat_info_menu_list">
			<div id="info_menu_info" class="chat_info_menu" onClick="toggle_info('info')">Visitor Info</div>
			<div id="info_menu_footprints" class="chat_info_menu" onClick="toggle_info('footprints')">Footprints</div>
			<div id="info_menu_transcripts" class="chat_info_menu" onClick="toggle_info('transcripts')">Transcripts</div>
			<div id="info_menu_transfer" class="chat_info_menu" onClick="toggle_info('transfer')">Transfer Chat</div>
			<div id="info_menu_spam" class="chat_info_menu" onClick="toggle_info('spam')">Spam Block</div>
			<div style="clear: both"></div>
		</div>
		<div id="chat_info_body" style="overflow: auto;">
			<div id="info_info" style="display: none;">
				<table cellspacing=0 cellpadding=0 border=0>
				<tr><td class="chat_info_td_h"><b>Department</b></td><td width="100%" class="chat_info_td"> <span id="req_dept">&nbsp;</span></td></tr>
				<tr><td class="chat_info_td_h"><b>Visitor Email</b></td><td class="chat_info_td"> <span id="req_email">&nbsp;</span></td></tr>
				<tr><td class="chat_info_td_h"><b>Chat Request</b></td><td class="chat_info_td"> <span id="req_request">&nbsp;</span></td></tr>
				<tr><td class="chat_info_td_h"><b>Clicked From</b></td><td class="chat_info_td"> <span id="req_onpage">&nbsp;</span></td></tr>
				<tr><td class="chat_info_td_h"><b>Refer URL</b></td><td class="chat_info_td"> <span id="req_refer">&nbsp;</span></td></tr>
				<tr><td class="chat_info_td_h"><b>Marketing</b></td><td class="chat_info_td"> <span id="req_market">&nbsp;</span></td></tr>
				<tr><td nowrap class="chat_info_td_h"><b>OS / Browser</b></td><td class="chat_info_td"> <span id="req_os">&nbsp;</span></td></tr>
				<tr><td nowrap class="chat_info_td_h" nowrap><b>IP / Hostname</b></td><td class="chat_info_td"> <span id="req_ip">&nbsp;</span></td></tr>
				<tr><td class="chat_info_td_h"><b>Resolution</b></td><td class="chat_info_td"> <span id="req_resolution">&nbsp;</span></td></tr>
				</table>
			</div>
			<div id="info_footprints" style="display: none;"><img src="../themes/<?php echo $opinfo["theme"] ?>/loading_fb.gif" border="0" alt=""></div>
			<div id="info_transcripts" style="display: none;"><img src="../themes/<?php echo $opinfo["theme"] ?>/loading_fb.gif" border="0" alt=""></div>
			<div id="info_transfer" style="display: none;"><img src="../themes/<?php echo $opinfo["theme"] ?>/loading_fb.gif" border="0" alt=""></div>
			<div id="info_spam" style="display: none;"></div>

			<div id="sounds" style="width: 1px; height: 1px; overflow: hidden; opacity:0.0; filter:alpha(opacity=0);">
				<span id="div_sounds_new_request"></span><span id="div_sounds_new_text"></span><span id="div_sounds_new_traffic"></span><span id="div_sounds_new_liner"></span></span>
			</div>
		</div>
	</div>
</div>
<div id="chat_btn" style="position: absolute; padding-right: 10px;"><button id="input_btn" type="button" class="input_button" style="width: 104px; height: 45px; padding: 6px; font-size: 14px; font-weight: bold;" OnClick="add_text_prepare()" disabled>Submit</button></div>
<div id="chat_text_powered" style="position: absolute; margin-top: 5px;">operator:<br><?php if ( !$wp ): ?><span style="text-decoration: underline; cursor: pointer;" onClick="launch_home()"><?php else: ?><span><?php endif ; ?><?php echo $opinfo["name"] ?></span></div>

<div id="chat_panel" style="position: absolute;">
	<div id="chat_cans" style="float: left; width: 120px; height: 75px; padding-left: 10px; padding-right: 10px;">
		<form>
		Canned Responses:<br>
		<div id="chat_cans_select" style="margin-top: 5px;"></div>
		<div><button type="button" id="canned_select_btn" onClick="select_canned()">select</button> <span class="chat_cans_text_new" style="text-decoration: underline; cursor: pointer;" onClick="toggle_extra( 'canned', 'new_canned', '', 'Create/Edit Canned' );">add new</span></div>
		</form>
	</div>
	<div id="chat_status" style="float: left; height: 75px; padding-left: 10px; padding-right: 10px;">
		Status:<br>
		<table cellspacing=0 cellpadding=0 border=0 style="font-size: 10px;">
		<form>
		<tr><td><input type="radio" name="status" value=1 checked onClick="toggle_status(0)"></td><td>&nbsp;online &nbsp;</td></tr>
		<tr><td style="padding-top: 3px;"><input type="radio" name="status" value=0 onClick="toggle_status(1)"></td><td>&nbsp;offline &nbsp;</td></tr>
		<tr><td style="padding-top: 3px;"><input type="radio" name="status" value=2 onClick="toggle_status(2)"></td><td>&nbsp;logout &nbsp;</td></tr>
		</form>
		</table>
	</div>
	<div id="chat_network" style="float: left; height: 75px; padding-left: 10px;">
		Network<br>
		<div id="chat_network_img" style="width: 50px; height: 38px;"></div>
	</div>
	<div style="clear: both;"></div>
</div>
<div id="chat_status_offline" style="position: absolute; display: none; padding: 5px; width: 80px; height: 85px; z-Index: 90;">
	<div id="chat_status_offline_text" style="padding: 2px; font-weight: bold; ">OFFLINE</div>
	<div>auto logout in:</div>
	<div id="offline_timer" style="margin-top: 3px; font-family: Impact, Serif; font-size: 18px;">60:00</div>
	<div style="margin-top: 3px;"><form><button type="button" style="font-size: 10px;" onClick="reset_offline_timer()">Reset</button></form></div>
</div>

<div id="chat_footer" style="position: absolute; width: 100%; bottom: 0px; height: 25px; z-Index: 100;">
	<?php if ( $opinfo["op2op"] ): ?>
	<div class="chat_footer_cell_noclick"><img src="../themes/<?php echo $opinfo["theme"] ?>/divider.png" border="0" alt=""></div>
	<div id="chat_footer_cell_op2op" class="chat_footer_cell" onClick="toggle_extra( 'op2op', '', '', 'Op2Op Chat' )">Op2Op Chat</div>
	<?php endif ; ?>

	<?php if ( $opinfo["traffic"] ): ?>
	<div class="chat_footer_cell_noclick"><img src="../themes/<?php echo $opinfo["theme"] ?>/divider.png" border="0" alt=""></div>
	<div id="chat_footer_cell_traffic" class="chat_footer_cell" onClick="toggle_extra( 'traffic', '', '', 'Traffic Monitor' )">Traffic Monitor <span id="chat_footer_traffic_counter">00</span></div>
	<?php endif; ?>

	<div class="chat_footer_cell_noclick"><img src="../themes/<?php echo $opinfo["theme"] ?>/divider.png" border="0" alt=""></div>
	<div id="chat_footer_cell_canned" class="chat_footer_cell" onClick="toggle_extra( 'canned', '', '', 'Create/Edit Canned' )">Create/Edit Canned</div>

	<div class="chat_footer_cell_noclick"><img src="../themes/<?php echo $opinfo["theme"] ?>/divider.png" border="0" alt=""></div>
	<div id="chat_footer_cell_trans" class="chat_footer_cell" onClick="toggle_extra( 'trans', '', '', 'Transcripts' )">Transcripts</div>

	<?php
		for ( $c = 0; $c < count( $externals ); ++$c )
		{
			$external = $externals[$c] ;

			print "
				<div class=\"chat_footer_cell_noclick\"><img src=\"../themes/$opinfo[theme]/divider.png\" border=\"0\" alt=\"\"></div>
				<div id=\"chat_footer_cell_ext_$external[extID]\" class=\"chat_footer_cell\" onClick=\"toggle_extra( $external[extID], '', '$external[url]', '$external[name]' )\">$external[name]</div>
			" ;
		}
	?>

	<div class="chat_footer_cell_noclick"><img src="../themes/<?php echo $opinfo["theme"] ?>/divider.png" border="0" alt=""></div>
	<div style="clear: both;"></div>
</div>
<div id="chat_extra_wrapper" style="position: absolute; display: none; margin-top: 30px; width: 100%; height: 380px; overflow: auto; z-Index: 99;">
	<div id="chat_extra_title" style="font-size: 16px; font-weight: bold; padding: 2px; padding-left: 10px;"></div>
	<div id="chat_extra_body_op2op" style="display: none;"></div>
	<div id="chat_extra_body_traffic" style="display: none;"></div>
	<div id="chat_extra_body_canned" style="display: none;"></div>
	<div id="chat_extra_body_trans" style="display: none;"></div>
	<?php
		for ( $c = 0; $c < count( $externals ); ++$c )
		{
			$external = $externals[$c] ;

			print "<div id=\"chat_extra_body_ext_$external[extID]\" style=\"display: none;\"></div>" ;
		}
	?>
</div>
<div id="info_disconnect" class="info_disconnect" style="position: absolute; top: 1px; right: 0px; text-align: right; z-Index: 101;" onClick="pre_disconnect();"></div>
<div id="chat_status_logout" style="position: absolute; display: none; width: 100%; bottom: 0px; height: 80px; z-Index: 102;">
	<div id="chat_status_logout_confirm" style="position: absolute; bottom: 0px; right: 0px; padding-bottom: 10px; padding-right: 20px; ">
		<table cellspacing=0 cellpadding=5 border=0>
		<tr>
			<td><img src="../themes/<?php echo $opinfo["theme"] ?>/alert.png" width="16" height="16" border="0" alt=""></td>
			<form>
			<td nowrap>
				<div class="info_error">Really logout and go offline?</div>
				<div style="margin-top: 5px;"><button type="button" onClick="toggle_status(3)">Yes, Log Out.</button> <button type="button" onClick="toggle_status(5)">Cancel</button></div>
			</td>
			</form>
		</tr>
		</table>
	</div>
</div>

<iframe id="iframe_chat_engine" name="iframe_chat_engine" style="display: none; position: absolute; width: 100%; border: 0px; bottom: -250px; height: 150px; z-Index: 110;" src="./p_engine.php?ses=<?php echo $ses ?>" scrolling="no"></iframe>

<div id="debug_menu" style="display: none; position: absolute; top: 25px; left: 5px; padding: 4px; font-size: 10px; background: #000000; color: #BDFF91; cursor: pointer; z-index: 101;" onClick="toggle_log()">DEBUG</div>

<div id="reconnect_notice" class="info_warning" style="display: none; position: absolute; z-Index: 1000;">
	<div id="reconnect_status">Operator console disconnected.  Reconnecting... <img src="../themes/<?php echo $opinfo["theme"] ?>/loading_fb.gif" width="16" height="11" border="0" alt=""></div>
	<div id="reconnect_attempt" style="margin-top: 2px; font-size: 10px;">&nbsp;</div>
</div>

</body>
</html>
<?php database_mysql_close( $dbh ) ; ?>
