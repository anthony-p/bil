function wp_pre_go_offline()
{
	// do pre logout, call functions to go offline
	toggle_status(3) ;
}
 
function wp_go_offline()
{
	// confirm go offline and logout
	wp_pre_go_offline() ;
	if ( typeof( window.external.wp_go_offline() ) != "undefined" )
		window.external.wp_go_offline() ;
}
 
function wp_maximize()
{
	if ( typeof ( window.external.wp_maximize() ) != "undefined" )
		window.external.wp_maximize() ;
}
 
function wp_minimize()
{
	window.external.wp_minimize() ;
}
 
function wp_focus_chat()
{
	wp_maximize() ;
}
