function init_menu()
{
	$( '*', 'body' ).each( function(){
		var div_name = jQuery( this ).attr('id') ;
		var class_name = jQuery( this ).attr('class') ;
		if ( class_name == "menu" )
		{
			$(this).hover(
				function () {
					$(this).removeClass('menu').addClass('menu_hover') ;
				}, 
				function () {
					$(this).removeClass('menu_hover').addClass('menu') ;
				}
			);
		}
		else if ( class_name == "menu_footer" )
		{
			$(this).hover(
				function () {
					$(this).removeClass('menu_footer').addClass('menu_footer_focus') ;
				}, 
				function () {
					$(this).removeClass('menu_footer_focus').addClass('menu_footer') ;
				}
			);
		}
	} );
}

function do_alert( theflag, thetext )
{
	var message ;

	var div_exists = $('#login_alert_box').length ;
	if ( div_exists )
		$('#login_alert_box').remove() ;

	if ( theflag )
		message = "<div id=\"login_alert_box\" class=\"info_good\" style=\"display: none; position: absolute; top: 0px; left: 0px; text-align: center; padding: 6px; font-size: 14px; font-weight: bold; z-Index: 200;\">"+thetext+"</div>" ;
	else
		message = "<div id=\"login_alert_box\" class=\"info_error\" style=\"display: none; position: absolute; top: 0px; left: 0px; text-align: center; padding: 6px; font-size: 14px; font-weight: bold; z-Index: 200;\">"+thetext+"</div>" ;

	$('body').append( message ) ;
	$('#login_alert_box').center().show().fadeOut("slow").fadeIn("fast").delay(3000).fadeOut("slow").hide() ;
}

function toggle_menu_op( themenu )
{
	var divs = Array( "go", "themes", "password", "settings" ) ;

	for ( c = 0; c < divs.length; ++c )
	{
		$('#menu_'+divs[c]).removeClass('menu_focus').addClass('menu') ;
		$('#op_'+divs[c]).hide() ;
	}

	menu = themenu ;

	$('#menu_'+themenu).removeClass('menu').addClass('menu_focus') ;
	$('#op_'+themenu).show() ;

	if ( themenu == "go" )
		$('#body_sub_title').html( "<img src=\"../pics/icons/bell_start.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" style=\"margin-right: 5px;\"> Go ONLINE!" ) ;
	else if ( themenu == "themes" )
		$('#body_sub_title').html( "<img src=\"../pics/icons/chest.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" style=\"margin-right: 5px;\"> Themes" ) ;
	else if ( themenu == "password" )
		$('#body_sub_title').html( "<img src=\"../pics/icons/user_key.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" style=\"margin-right: 5px;\"> Update Password" ) ;
	else if ( themenu == "settings" )
		$('#body_sub_title').html( "<img src=\"../pics/icons/user_key.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" style=\"margin-right: 5px;\"> Settings" ) ;
}

function toggle_menu_setup( themenu )
{
	var divs = Array( "home", "depts", "ops", "icons", "html", "trans", "rchats", "rtraffic", "marketing", "settings", "external" ) ;

	for ( c = 0; c < divs.length; ++c )
		$('#menu_'+divs[c]).removeClass('menu_focus').addClass('menu') ;

	$('#menu_'+themenu).removeClass('menu').addClass('menu_focus') ;
	menu = themenu ;
}

function preview_theme( thetheme )
{
	window.open( "../phplive.php?theme="+thetheme, "themed", 'scrollbars=no,resizable=yes,menubar=no,location=no,screenX=50,screenY=100,width=550,height=410' ) ;
}
