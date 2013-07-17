<?php
	include_once( "../web/config.php" ) ;
	include_once( "../API/Util_Format.php" ) ;
	include_once( "../API/Util_Vars.php" ) ;

	$deptid = Util_Format_Sanatize( Util_Format_GetVar( "d" ), "ln" ) ;
	$base_url = rawurldecode( Util_Format_Sanatize( Util_Format_GetVar( "base_url" ), "base_url" ) ) ;
	$text = rawurldecode( Util_Format_Sanatize( Util_Format_GetVar( "text" ), "ln" ) ) ;
	$mobile = Util_Format_isMobile() ;

	$ip = $_SERVER['REMOTE_ADDR'] ;

	srand( (double)microtime() ) ; $btn = mt_rand( 1000,100000000 ) ;

	Header( "content-type:application/x-javascript" ) ;
?>
function phplive_unique() { var date = new Date() ; return date.getTime() ; }
if ( typeof( phplive_js ) == "undefined" )
{
	phplive_js = 1 ;
	var phplive_dom = {} ;
	var phplive_btn = <?php echo $btn ?> ;
	var phplive_stat_refer = encodeURIComponent( document.referrer.replace("http", "hphp") ) ;
	var phplive_stat_onpage = encodeURIComponent( location.toString().replace("http", "hphp") ) ;
	var phplive_stat_title = encodeURIComponent( document.title ) ;
	var win_width = screen.width ;
	var win_height = screen.height ;
	var phplive_initiate_widget = 0 ;
	var phplive_mouse_x = phplive_mouse_y = 0 ;
	var phplive_support_<?php echo $btn ?> ;

	var resolution = escape( win_width + " x " + win_height ) ;

	var js_jquery = js_proto = 0 ;
	if( typeof window.jQuery !== "undefined" )
	{
		// $.fn.jquery contains the version number
		if( window.jQuery === window.$ )
			phplive_dom = $ ;
		else
			phplive_dom = window.jQuery ;
	}
	else
	{
		// jQuery lib load
		var script_url_jquery = "<?php echo $base_url ?>/js/framework.js" ;
		var script_jquery = document.createElement('script') ;
		script_jquery.setAttribute("type","text/javascript") ;
		script_jquery.onload = script_jquery.onreadystatechange = function () {
			phplive_dom = window.jQuery ;
		} ;
		script_jquery.setAttribute("src", script_url_jquery) ;
		document.getElementsByTagName("head")[0].appendChild(script_jquery) ;
	}

	var script_url_ext = "<?php echo $base_url ?>/js/framework_ext.js" ;
	var script_ext = document.createElement('script') ;
	script_ext.setAttribute("type","text/javascript") ;
	script_ext.onload = script_ext.onreadystatechange = function () {
		//
	} ;
	script_ext.setAttribute("src", script_url_ext) ;
	document.getElementsByTagName("head")[0].appendChild(script_ext) ;
}

var ns = (document.layers) ;
var ie = (document.all) ;
var w3 = (document.getElementById && !ie) ;

var phplive_pullimg_footprint_<?php echo $btn ?>, st_phplive_pullimg_<?php echo $btn ?>, phplive_thec_<?php echo $btn ?> = 0 ;
var phplive_status_image_<?php echo $btn ?> = "<?php echo $base_url ?>/ajax/image.php?d=<?php echo $deptid ?>&r="+phplive_stat_refer+"&p="+phplive_stat_onpage+"&title="+phplive_stat_title+"&btn=<?php echo $btn ?>&"+phplive_unique() ;
var phplive_request_url_<?php echo $btn ?> = "<?php echo $base_url ?>/phplive.php?d=<?php echo $deptid ?>&btn=<?php echo $btn ?>&onpage="+phplive_stat_onpage+"&title="+phplive_stat_title ;

<?php if ( $text ): ?>
var phplive_image_or_text_<?php echo $btn ?> = "<?php echo $text ?>" ;
<?php else: ?>
var phplive_image_or_text_<?php echo $btn ?> = "<img src=\""+phplive_status_image_<?php echo $btn ?>+"\" border=0>" ;
<?php endif ; ?>

function phplive_silent_close( phplive_theces, theisadmin, thetimer, theunique )
{
	alert( unescape( phplive_theces ) ) ;
}

function phplive_footprint_tracker_<?php echo $btn ?>()
{
	phplive_pullimg_footprint_<?php echo $btn ?> = new Image ;
	phplive_pullimg_footprint_<?php echo $btn ?>.onload = phplive_pullimg_actions_<?php echo $btn ?> ;
	phplive_pullimg_footprint_<?php echo $btn ?>.src = "<?php echo $base_url ?>/ajax/footprints.php?deptid=<?php echo $deptid ?>&r="+phplive_stat_refer+"&onpage="+phplive_stat_onpage+"&title="+phplive_stat_title+"&c="+phplive_thec_<?php echo $btn ?>+"&resolution="+resolution+"&"+phplive_unique() ;
}

function phplive_pullimg_actions_<?php echo $btn ?>()
{
	var thisflag = phplive_pullimg_footprint_<?php echo $btn ?>.width ;
	var obj_div = phplive_dom( "#phplive_widget_<?php echo $btn ?>" ) ;
	var obj_div_cover = phplive_dom( "#phplive_widget_cover_<?php echo $btn ?>" ) ;
	var obj_iframe = phplive_dom ( "#iframe_widget_<?php echo $btn ?>" ) ;

	if ( ( thisflag == 1 ) || ( thisflag == 2 ) )
	{
		if ( ( thisflag == 2 ) && !phplive_initiate_widget )
		{
			phplive_initiate_widget = 1 ;
			obj_iframe.attr( 'src', "<?php echo $base_url ?>/widget.php?btn=<?php echo $btn ?>&"+phplive_unique() ) ;
			obj_div_cover.center().show() ;
			obj_div.center().fadeIn( "fast" ) ;

			// another one for good measure
			phplive_widget_init_<?php echo $btn ?>( obj_div_cover, obj_div ) ;
		}
		else if ( ( thisflag == 1 ) && phplive_initiate_widget )
		{
			phplive_initiate_widget = 0 ;
			obj_div.fadeOut( "fast" ) ;
			obj_div_cover.hide() ;
		}

		++phplive_thec_<?php echo $btn ?> ;
		st_phplive_pullimg_<?php echo $btn ?> = setTimeout(function(){ phplive_footprint_tracker_<?php echo $btn ?>() }, <?php echo $VARS_JS_FOOTPRINT ?> * 1000) ;
	}
	else if ( thisflag == 4 )
	{
		clearTimeout( st_phplive_pullimg_<?php echo $btn ?> ) ;
	}
}

function phplive_widget_init_<?php echo $btn ?>( theobj_div_cover, theobj_div )
{
	theobj_div_cover.center() ;
	theobj_div.center() ;
}

function phplive_launch_chat_<?php echo $btn ?>(thewidget)
{
	var winname = phplive_unique() ;
	phplive_request_url_<?php echo $btn ?> = phplive_request_url_<?php echo $btn ?>+"&widget="+thewidget ;

	phplive_support_<?php echo $btn ?> = window.open( phplive_request_url_<?php echo $btn ?>, winname, 'scrollbars=no,resizable=yes,menubar=no,location=no,screenX=50,screenY=100,width=550,height=410' ) ;
}

function phplive_write_widget_<?php echo $btn ?>()
{
	// NOTE: we do not recommend modifying the width and height of the widget window as this will break the window
	// communication behavior based on mouse coordinates in function phplive_pullimg_actions_<?php echo $btn ?>()
	var phplive_widget_<?php echo $btn ?> = "<map name='initiate_chat_cover'><area shape='rect' coords='364,0,450,25' href='JavaScript:void(0)' onClick='phplive_widget_decline_<?php echo $btn ?>()'><area shape='rect' coords='300,210,440,260' href='JavaScript:void(0)' onClick='phplive_widget_launch_<?php echo $btn ?>()'></map><div id='phplive_widget_<?php echo $btn ?>' style='display: none; position: fixed; background: url( <?php echo $base_url ?>/themes/initiate/bg_trans.png ) repeat; padding: 10px; width: 450px; height: 280px; -moz-border-radius: 5px; border-radius: 5px; z-Index: 1000;'><iframe id='iframe_widget_<?php echo $btn ?>' name='iframe_widget_<?php echo $btn ?>' style='width: 450px; height: 280px;' src='<?php echo $base_url ?>/blank.php' scrolling='no' border=0 frameborder=0 onLoad=''></iframe></div><div id='phplive_widget_cover_<?php echo $btn ?>' style='display: none; position: fixed; padding: 10px; width: 450px; height: 280px; -moz-border-radius: 5px; border-radius: 5px; z-Index: 10000;'><div style='width: 450px; height: 280px;'><img src='<?php echo $base_url ?>/pics/space.gif' width='450' height='280' border=0 usemap='#initiate_chat_cover'></div></div>" ;
	document.write( phplive_widget_<?php echo $btn ?> ) ;
}

function phplive_widget_launch_<?php echo $btn ?>()
{
	if ( phplive_initiate_widget )
	{
		var obj_div = phplive_dom( "#phplive_widget_<?php echo $btn ?>" ) ;
		var obj_div_cover = phplive_dom( "#phplive_widget_cover_<?php echo $btn ?>" ) ;
		var obj_iframe = phplive_dom ( "#iframe_widget_<?php echo $btn ?>" ) ;

		obj_div.fadeOut( "fast" ) ;
		obj_div_cover.hide() ;
		phplive_launch_chat_<?php echo $btn ?>(1) ;
	}
}

function phplive_widget_decline_<?php echo $btn ?>()
{
	if ( phplive_initiate_widget )
	{
		var obj_div = phplive_dom( "#phplive_widget_<?php echo $btn ?>" ) ;
		var obj_div_cover = phplive_dom( "#phplive_widget_cover_<?php echo $btn ?>" ) ;
		var obj_iframe = phplive_dom ( "#iframe_widget_<?php echo $btn ?>" ) ;

		obj_div.fadeOut( "fast" ) ;
		obj_div_cover.hide() ;

		phplive_pullimg_widget_<?php echo $btn ?> = new Image ;
		phplive_pullimg_widget_<?php echo $btn ?>.onload = function() {
			//
		};
		phplive_pullimg_widget_<?php echo $btn ?>.src = "<?php echo $base_url ?>/ajax/chat_actions.php?action=disconnect&isop=0&widget=1&ip=<?php echo $ip ?>&"+phplive_unique() ;
		phplive_initiate_widget = 0 ;
	}
}

if ( typeof( phplive_footprint_js ) == "undefined" )
{
	phplive_footprint_js = 1 ;
	phplive_footprint_tracker_<?php echo $btn ?>() ;
}

var phplive_status_image_write_<?php echo $btn ?> = "<a href=\"Javascript:void(0)\" onClick=\"phplive_launch_chat_<?php echo $btn ?>(0)\">"+phplive_image_or_text_<?php echo $btn ?>+"</a>" ;
document.write( phplive_status_image_write_<?php echo $btn ?> ) ;

phplive_write_widget_<?php echo $btn ?>() ;
