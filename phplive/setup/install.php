<?php
	error_reporting(0) ;
	$pv = phpversion() ; if ( $pv >= "5.1.0" ){ date_default_timezone_set( "America/New_York" ) ; }
	include_once( "../API/Util_Format.php" ) ;
	include_once( "../API/Util_Vals.php" ) ;

	// vars
	$PHPLIVE_VERSION = "4.1" ;
	$debug = 0 ;

	$query = $_SERVER["QUERY_STRING"] ;
	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;

	if ( !$debug )
	{
		if ( file_exists( "../web/config.php" ) && ( $action != "success" ) )
		{
			HEADER( "location: ../index.php" ) ;
			exit ;
		}
	}

	/***** PRE INSTALL CHECK OF PHP SETTINGS *****/
	// gather ini settings
	$ini_open_basedir = ini_get("open_basedir") ;
	$ini_safe_mode = ini_get("safe_mode") ;
	$safe_mode = preg_match( "/on/i", $ini_safe_mode ) ? 1 : 0 ;
	if ( $safe_mode || ( $ini_safe_mode == 1 ) )
	{
		// attempt to creat a directory
		if ( is_dir( "../web/chat_sessions" ) != true )
			mkdir( "../web/chat_sessions", 0777 ) ;

		if ( !is_writable( "../web/chat_sessions" ) )
		{
			print "<pre>Based on your server settings, the following MANUAL PRE INSTALLATION steps are required.\r\n\r\n" ;
			include_once( "../README/PREP.html" ) ;
			exit ;
		}
	}
	/***** PRE INSTALL CHECK OF PHP SETTINGS *****/




	/********************************/
	// pre installation file creation
	/********************************/
	if ( !file_exists( "../web/vals.php" ) )
	{
		$vals_string = "< php \$VALS = Array() ; \$VALS['CHAT_SPAM_IPS'] = \"\" ; \$VALS['TRAFFIC_EXCLUDE_IPS'] = \"\" ; ?>" ;
		$vals_string = preg_replace( "/< php/", "<?php", $vals_string ) ;

		$fp = fopen ("../web/vals.php", "w") ;
		fwrite( $fp, $vals_string, strlen( $vals_string ) ) ;
		fclose( $fp ) ;
	}
	/********************************/



	if ( $action == "create_dirs" )
	{
		$status = 1 ;
		if ( is_dir( "../web/chat_initiate" ) != true )
			mkdir( "../web/chat_initiate", 0777 ) ;
		if ( is_dir( "../web/chat_sessions" ) != true )
			mkdir( "../web/chat_sessions", 0777 ) ;
		if ( is_dir( "../web/patches" ) != true )
			mkdir( "../web/patches", 0777 ) ;

		if ( !file_exists( "../web/chat_initiate" ) )
			$status = 0 ;

		if ( $status )
			$json_data = "json_data = { \"status\": 1 };" ;
		else
			$json_data = "json_data = { \"status\": 0 };" ;

		print $json_data ;
		exit ;
	}
	else if ( $action == "check_dir_per" )
	{
		$dir = Util_Format_Sanatize( Util_Format_GetVar( "dir" ), "ln" ) ;

		if ( $dir == "chat" )
		{
			if ( is_writable( "../web/chat_sessions" ) )
				$json_data = "json_data = { \"status\": 1 };" ;
			else
				$json_data = "json_data = { \"status\": 0, \"error\": \"The web/chat_sessions directory is not writeable.  Please check that you have the proper global wrieable permissions on the web/ directory.\" };" ;
		}
		else if ( $dir == "web" )
		{
			if ( is_writable( "../web" ) )
				$json_data = "json_data = { \"status\": 1 };" ;
			else
				$json_data = "json_data = { \"status\": 0, \"error\": \"The web/ directory is not writeable.  Please check that you have the proper global wrieable permissions on the web/ directory.\" };" ;
		}
		else if ( $dir == "sessions" )
		{
			// session reliance has been defunct... output success
			$json_data = "json_data = { \"status\": 1 };" ;

			// if safe mode enabled then let it pass since manual pre installation was done
			//if ( is_dir( session_save_path() ) || $safe_mode || $ini_open_basedir )
			//	$json_data = "json_data = { \"status\": 1 };" ;
			//else
			//	$json_data = "json_data = { \"status\": 0, \"error\": \"The &#39;session.save_path&#39; directory not set.  Check the php.ini file to set the &#39;session.save_path&#39; to /tmp for UNIX or C:\Temp for windows.\" };" ;
		}

		print $json_data ;
		exit ;
	}
	else if ( $action == "check_document_root" )
	{
		$document_root = realpath( Util_Format_Sanatize( Util_Format_GetVar( "document_root" ), "base_url" ) ) ;

		if ( file_exists( "$document_root/phplive.php" ) )
			$json_data = "json_data = { \"status\": 1 };" ;
		else
			$json_data = "json_data = { \"status\": 0 };" ;

		print $json_data ;
		exit ;
	}
	else if ( $action == "check_base_url" )
	{
		$json_data = "json_data = { \"status\": 1 };" ;

		print $json_data ;
		exit ;
	}
	else if ( $action == "check_db" )
	{
		$db_host = Util_Format_Sanatize( Util_Format_GetVar( "db_host" ), "" ) ;
		$db_name = Util_Format_Sanatize( Util_Format_GetVar( "db_name" ), "" ) ;
		$db_login = Util_Format_Sanatize( Util_Format_GetVar( "db_login" ), "" ) ;
		$db_password = Util_Format_Sanatize( Util_Format_GetVar( "db_password" ), "" ) ;

		if ( $db_host && $db_name && $db_login && $db_password )
		{
			$connection = mysql_connect( $db_host, $db_login, $db_password ) ;
			mysql_select_db( $db_name ) ;
			$sth = mysql_query( "SHOW TABLES", $connection ) ;
			$error = mysql_error() ;
			$error = preg_replace( "/\"/", "&#34;", $error ) ;
			$error = preg_replace( "/'/", "&#39;", $error ) ;

			if ( !$error )
				$json_data = "json_data = { \"status\": 1, \"errorno\": \"0\", \"error\": \"ERROR: $error\" };" ;
			else
			{
				if ( preg_match( "/(access denied)/i", $error ) )
					$json_data = "json_data = { \"status\": 0, \"errorno\": \"1\", \"error\": \"ERROR: $error\" };" ;
				else if ( preg_match( "/(no database selected)/i", $error ) )
					$json_data = "json_data = { \"status\": 0, \"errorno\": \"2\", \"error\": \"ERROR: Database ($db_name) not found.\" };" ;
				else
					$json_data = "json_data = { \"status\": 0, \"errorno\": \"3\", \"error\": \"ERROR: $error\" };" ; // default error
			}
		}
		else
			$json_data = "json_data = { \"status\": 0, \"errorno\": \"0\", \"error\": \"ERROR: Blank MySQL field is invalid.\" };" ;

		print $json_data ;
		exit ;
	}
	else if ( $action == "install" )
	{
		if ( preg_match( "/unix/i", $_SERVER['SERVER_SOFTWARE'] ) )
			$server = "unix" ;
		else
			$server = "windows" ;

		$email = Util_Format_Sanatize( Util_Format_GetVar( "email" ), "e" ) ;
		$login = Util_Format_Sanatize( Util_Format_GetVar( "login" ), "ln" ) ;
		$password = Util_Format_Sanatize( Util_Format_GetVar( "password" ), "" ) ;
		$base_url = Util_Format_Sanatize( Util_Format_GetVar( "base_url" ), "base_url" ) ;
		$document_root = realpath( Util_Format_Sanatize( Util_Format_GetVar( "document_root" ), "base_url" ) ) ;
		$db_host = Util_Format_Sanatize( Util_Format_GetVar( "db_host" ), "" ) ;
		$db_name = Util_Format_Sanatize( Util_Format_GetVar( "db_name" ), "" ) ;
		$db_login = Util_Format_Sanatize( Util_Format_GetVar( "db_login" ), "" ) ;
		$db_password = Util_Format_Sanatize( Util_Format_GetVar( "db_password" ), "" ) ;
		$timezone = Util_Format_Sanatize( Util_Format_GetVar( "timezone" ), "" ) ;

		$str_len = strlen( $base_url ) ;
		$last = $base_url[$str_len-1] ;
		if ( ( $last == "/" ) || ( $last == "\\" ) )
			$base_url = substr( $base_url, 0, $str_len - 1 ) ;

		$str_len = strlen( $document_root ) ;
		$last = $document_root[$str_len-1] ;
		if ( ( $last == "/" ) || ( $last == "\\" ) )
			$document_root = substr( $document_root, 0, $str_len - 1 ) ;

		// install the DB
		$connection = mysql_connect( $db_host, $db_login, $db_password ) ;
		mysql_select_db( $db_name ) ;
		$query_array = get_db_query() ;
		$errors = "" ;
		for ( $c = 0; $c < count( $query_array ); ++$c )
		{
			if ( $query_array[$c] )
			{
				mysql_query( $query_array[$c], $connection ) ;
				$errorno = mysql_errno() ;
				if ( $errorno )
					$errors .= mysql_error() ;
			}
		}

		if ( $errors )
			$json_data = "json_data = { \"status\": 0, \"error\": \"$errors\" };" ;
		else
		{
			include_once( "./KEY.php" ) ;
			include_once( "../API/Util_Email.php" ) ;

			$document_root = Util_Format_Sanatize( $document_root, "htmle" ) ;
			$base_url = Util_Format_Sanatize( $base_url, "htmle" ) ; $db_host = Util_Format_Sanatize( $db_host, "htmle" ) ;
			$db_login = Util_Format_Sanatize( $db_login, "htmle" ) ; $db_password = Util_Format_Sanatize( $db_password, "htmle" ) ;
			$db_name = Util_Format_Sanatize( $db_name, "htmle" ) ; $timezone = Util_Format_Sanatize( $timezone, "htmle" ) ;

			$conf_vars = "\$CONF = Array() ;\n" ;
			$conf_vars .= "\$CONF['DOCUMENT_ROOT'] = \"$document_root\" ;\n" ;
			$conf_vars .= "\$CONF['BASE_URL'] = \"$base_url\" ;\n" ;
			$conf_vars .= "\$CONF['SQLHOST'] = \"$db_host\" ;\n" ;
			$conf_vars .= "\$CONF['SQLLOGIN'] = \"$db_login\" ;\n" ;
			$conf_vars .= "\$CONF['SQLPASS'] = \"$db_password\" ;\n" ;
			$conf_vars .= "\$CONF['DATABASE'] = \"$db_name\" ;\n" ;
			$conf_vars .= "\$CONF['THEME'] = \"default\" ;\n" ;
			$conf_vars .= "\$CONF['TIMEZONE'] = \"$timezone\" ;\n" ;
			$conf_vars .= "\$CONF['icon_online'] = \"\" ;\n" ;
			$conf_vars .= "\$CONF['icon_offline'] = \"\" ;\n" ;
			$conf_vars .= "\$CONF['lang'] = \"english\" ;\n" ;
			$conf_vars .= "\$CONF['logo'] = \"\" ;\n" ;

			$conf_string = "< php\n	$conf_vars" ;
			$conf_string .= "	if ( phpversion() >= \"5.1.0\" ){ date_default_timezone_set( \$CONF[\"TIMEZONE\"] ) ; }\n" ;
			$conf_string .= "	include_once( realpath( \"\$CONF[DOCUMENT_ROOT]/API/Util_Vars.php\" ) ) ;\n?>" ;
			$conf_string = preg_replace( "/< php/", "<?php", $conf_string ) ;

			$fp = fopen ("../web/config.php", "w") ;
			fwrite( $fp, $conf_string, strlen( $conf_string ) ) ;
			fclose( $fp ) ;

			$now = time() ;
			$password_orig = $password ;
			$login = mysql_real_escape_string( $login ) ;
			$password = md5( mysql_real_escape_string( $password ) ) ;

			$query = "INSERT INTO p_admins VALUES(0, $now, 0, 0, '', '$login', '$password', '$email')" ;
			mysql_query( $query, $connection ) ;
			$query = "INSERT INTO p_marquees VALUES (0, 1, 1111111111, 'Powered by PHP Live!', 'Powered by <a href=http://www.phplivesupport.com/?ref=link&loc=req&plk=osicodes-5-ykq-m target=_blank>PHP Live!</a> &copy; OSI Codes Inc.')" ;
			mysql_query( $query, $connection ) ;

			$version_string = "< php \$VERSION = \"$PHPLIVE_VERSION\" ; ?>" ;
			$version_string = preg_replace( "/< php/", "<?php", $version_string ) ;
			$fp = fopen ("../web/patches/VERSION.php", "w") ;
			fwrite( $fp, $version_string, strlen( $version_string ) ) ;
			fclose( $fp ) ;

			$base_url = urlencode( preg_replace( "/http/i", "hphp", $base_url ) ) ;
			$os = urlencode( $_SERVER['SERVER_SOFTWARE'] ) ;

			$tags = get_meta_tags( "http://www.osicodesinc.com/stats/register.php?version=$PHPLIVE_VERSION&key=$KEY&base_url=$base_url&os=$os&" ) ;
			$json_data = "json_data = { \"status\": 1, \"url\": \"$req_url\" };" ;
		}

		print $json_data ;
		exit ;
	}

	include_once( "./KEY.php" ) ;

	$path_translated = ( isset( $_SERVER['PATH_TRANSLATED'] ) && $_SERVER['PATH_TRANSLATED'] ) ? $_SERVER['PATH_TRANSLATED'] : $_SERVER['SCRIPT_FILENAME'] ;
	$document_root = preg_replace( "/setup(.*?).php/i", "", $path_translated ) ;

	$timezones = Util_Vals_Timezones() ;
?>
<?php include_once( "../inc_doctype.php" ) ?>
<head>
<title> PHP Live! Support Installation </title>

<meta name="description" content="PHP Live! Support">
<meta name="keywords" content="PHP Live! Support">
<meta name="robots" content="all,index,follow">
<meta http-equiv="content-type" content="text/html; CHARSET=utf-8">

<link rel="Stylesheet" href="../css/base_setup.css">
<script type="text/javascript" src="../js/global.js"></script>
<script type="text/javascript" src="../js/setup.js"></script>
<script type="text/javascript" src="../js/framework.js"></script>
<script type="text/javascript" src="../js/framework_ext.js"></script>
<script type="text/javascript" src="../js/tooltip.js"></script>

<script type="text/javascript">
<!--
	var mouse_x ; var mouse_y ;
	var json_data = new Object ;
	var execute ;
	var inputs = Array( "email", "login", "password", "vpassword", "base_url", "document_root", "db_host", "db_name", "db_login", "db_password" ) ;
	var inputs_test = Array( "db_host", "db_name", "db_login", "db_password" ) ;

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		}) ;

		init_menu() ;
		toggle_menu( "install" ) ;
		create_dirs() ;

		$('#base_url').val( location.toString().replace( "setup/install.php", "" ) ) ;

		check_dir_per_chat() ;
	});

	function toggle_menu( themenu )
	{
		var divs = Array( "install" ) ;
		for ( c = 0; c < divs.length; ++c )
			$('#menu_'+divs[c]).removeClass('menu_focus').addClass("menu") ;

		$('#menu_'+themenu).removeClass("menu").addClass("menu_focus") ;

		$('#body_sub_title').empty().html( "<img src=\"../pics/icons/user_key.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" style=\"margin-right: 5px;\"> Installation" ) ;
	}

	function run_installer()
	{
		test_connection(1); check_db(0);
		execute = 1 ;

		for ( c = 0; c < inputs.length; ++c )
		{
			if ( $('#'+inputs[c]).val() == "" ){ $('#status_'+inputs[c]).empty().html( "&nbsp; <img src=\"../pics/icons/alert.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" class=\"help_tooltip\" title=\"- Please provide a value.\">" ) ; execute = 0 ; }
			else { $('#status_'+inputs[c]).empty().html( "&nbsp; <img src=\"../pics/icons/alert_good.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\">" ) ; }
		}

		// check email
		if ( $('#email').val().indexOf( "@" ) == -1 ){ $('#status_email').empty().html( "&nbsp; <img src=\"../pics/icons/alert.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" class=\"help_tooltip\" title=\"- Email format is invalid.<br>(example: you@somewhere.com)\">" ) ; execute = 0 ; }

		// check passwords
		if ( $('#password').val() != $('#vpassword').val() ){ $('#status_password').empty().html( "&nbsp; <img src=\"../pics/icons/alert.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" class=\"help_tooltip\" title=\"- Password and Verify Password do not match.\">" ) ; $('#status_vpassword').empty().html( "&nbsp; <img src=\"../pics/icons/alert.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" class=\"help_tooltip\" title=\"- Password and Verify Password do not match.\">" ) ; execute = 0 ; }

		check_base_url() ;
	}

	function check_base_url()
	{
		var json_data = new Object ;
		var unique = unixtime() ;
		var base_url = $('#base_url').val() ;
		var strlen = base_url.length ;
		var lastchar = base_url[strlen-1] ;
		if (lastchar == "/" )
			base_url = base_url.slice( 0, strlen-1 ) ;

		$.ajax({
		type: "GET",
		url: base_url+"/setup/install.php",
		data: "action=check_base_url&"+unique,
		success: function(data){
			eval( data ) ;

			if ( !json_data.status )
			{
				$('#status_base_url').empty().html( "&nbsp; <img src=\"../pics/icons/alert.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" class=\"help_tooltip\" title=\"- Base URL is invalid. This is the URL of this PHP Live! installation.<br>(example: http://www.mycompany.com/phplive/\">" ) ;
				execute = 0 ;
			}
			else
				check_document_root() ;
		},
		error:function (xhr, ajaxOptions, thrownError){
			$('#status_base_url').empty().html( "&nbsp; <img src=\"../pics/icons/alert.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" class=\"help_tooltip\" title=\"- Base URL is invalid. This is the URL of this PHP Live! installation.<br><br>example: http://www.mycompany.com/phplive/\">" ) ;
			execute = 0 ;
		} });
	}

	function check_document_root()
	{
		var json_data = new Object ;
		var unique = unixtime() ;
		var document_root = encodeURIComponent( $('#document_root').val() ) ;

		$.ajax({
		type: "GET",
		url: "./install.php",
		data: "action=check_document_root&document_root="+document_root+"&"+unique,
		success: function(data){
			eval( data ) ;

			if ( !json_data.status )
			{
				$('#status_document_root').empty().html( "&nbsp; <img src=\"../pics/icons/alert.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" class=\"help_tooltip\" title=\"- Document Root is invalid.  This is the full directory path of this PHP Live! installation.<br><br>windows example: C:/Apache2.2/htdocs/phplive/)<br>unix example: /home/webaccount/phplive\">" ) ;
				execute = 0 ;
			}
			else
				check_db(1) ;
		},
		error:function (xhr, ajaxOptions, thrownError){
			$('#status_document_root').empty().html( "&nbsp; <img src=\"../pics/icons/alert.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" class=\"help_tooltip\" title=\"- Could not connect.  Please reload the installation page.\">" ) ;
			execute = 0 ;
			//check_db(1) ;
		} });
	}

	function check_db( theflag )
	{
		var json_data = new Object ;
		var unique = unixtime() ;
		var db_host = encodeURIComponent( $('#db_host').val() ) ;
		var db_name = encodeURIComponent( $('#db_name').val() ) ;
		var db_login = encodeURIComponent( $('#db_login').val() ) ;
		var db_password = encodeURIComponent( $('#db_password').val() ) ;

		$.ajax({
		type: "GET",
		url: "./install.php",
		data: "action=check_db&db_host="+db_host+"&db_name="+db_name+"&db_login="+db_login+"&db_password="+db_password+"&"+unique,
		success: function(data){
			eval( data ) ;

			if ( json_data.status )
			{
				if ( theflag )
					install() ;

				$('#mysql_test_output').empty().html("Database connection SUCCESS!") ;
			}
			else
			{
				var error = json_data.error ;

				$('#mysql_test_output').empty().html(error) ;
				set_db_error( json_data.errorno, json_data.error ) ;
			}
			init_tooltips() ;
		},
		error:function (xhr, ajaxOptions, thrownError){
			set_db_error( 0, '' ) ;
			init_tooltips() ;
		} });
	}

	function set_db_error( theerrorno, theerror_mesg )
	{
		var json_data = new Object ;
		var error ;
		var status_dbs = new Array() ;

		if ( theerrorno == 1 )
		{
			execute = 0 ;
			status_dbs.push("db_host", "db_name", "db_login", "db_password") ;
			error = "Error: Access is denied.  Please double check the values 'DB Host', 'DB Login', or 'DB Password'." ;
			output_db_error( status_dbs, error ) ;
		}
		else if ( theerrorno == 2 )
		{
			execute = 0 ;
			status_dbs.push("db_name") ;
			error = "Error: Invalid DB Name.  The DB Name is invalid.  Please make sure the MySQL DB '"+$('#db_name').val()+"' exists." ;
			output_db_error( status_dbs, error ) ;
		}
	}

	function output_db_error( thestatus_dbs, theerror )
	{
		for ( c = 0; c < thestatus_dbs.length; ++c )
		{
			$('#status_'+thestatus_dbs[c]).empty().html( "&nbsp; <img src=\"../pics/icons/alert.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" class=\"help_tooltip\" title=\"- "+theerror+"\">" ) ;
		}
	}

	function install()
	{
		if ( execute )
		{
			$(window).scrollTop(0) ;
			$('#canvas_wrapper').show() ;
			$('#canvas_loading').show().center() ;
			$('body').css({'overflow': 'hidden'}) ;
			//$('#btn_run').attr('disabled', true) ;
			install_db() ;
		}
	}

	function install_db()
	{
		var json_data = new Object ;
		var unique = unixtime() ;
		var email = encodeURIComponent( $('#email').val() ) ;
		var login = encodeURIComponent( $('#login').val() ) ;
		var password = encodeURIComponent( $('#password').val() ) ;
		var base_url = encodeURIComponent( $('#base_url').val().replace("http", "hphp") ) ;
		var document_root = encodeURIComponent( $('#document_root').val() ) ;
		var db_host = encodeURIComponent( $('#db_host').val() ) ;
		var db_name = encodeURIComponent( $('#db_name').val() ) ;
		var db_login = encodeURIComponent( $('#db_login').val() ) ;
		var db_password = encodeURIComponent( $('#db_password').val() ) ;
		var timezone = $('#timezone').val() ;

		var query = "email="+email+"&login="+login+"&password="+password+"&base_url="+base_url+"&document_root="+document_root+"&db_host="+db_host+"&db_name="+db_name+"&db_login="+db_login+"&db_password="+db_password+"&timezone="+timezone+"&"+unique ;

		$.ajax({
		type: "GET",
		url: "./install.php",
		data: "action=install&"+query,
		success: function(data){
			eval( data ) ;

			if ( json_data.status )
				setTimeout( function(){ $('#canvas_loading').hide() ; $('#canvas_success').center().show() ; }, 2000 ) ;
			else
			{
				alert( "Error: "+json_data.error ) ;
				$('#canvas_wrapper').hide() ;
				$('#canvas_loading').hide().center() ;
				$('body').css({'overflow': 'visible'}) ;
			}
		},
		error:function (xhr, ajaxOptions, thrownError){
			alert( "Could not connect to server.  Please try again." ) ;
			$('#canvas_wrapper').hide() ;
			$('#canvas_loading').hide().center() ;
			$('body').css({'overflow': 'visible'}) ;
		} });
	}

	function create_dirs()
	{
		var json_data = new Object ;
		var unique = unixtime() ;

		$.ajax({
		type: "GET",
		url: "./install.php",
		data: "action=create_dirs&"+unique,
		success: function(data){
			eval( data ) ;

			if ( !json_data.status )
			{
				$('#status_chat_dirs').empty().html( "&nbsp; <img src=\"../pics/icons/alert.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" class=\"help_tooltip\" title=\"- Chat directories could not be created.  Please check that you have the proper global wrieable permissions on the web/ directory.\">" ) ;
				$('#pre_errorbox').show() ;
			}
			else
				$('#status_chat_dirs').empty().html( "&nbsp; <img src=\"../pics/icons/alert_good.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\">" ) ;
		},
		error:function (xhr, ajaxOptions, thrownError){
			$('#status_chat_dirs').empty().html( "&nbsp; <img src=\"../pics/icons/alert.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" class=\"help_tooltip\" title=\"- Could not connect.  Please reload the installation page.\">" ) ;
			$('#pre_errorbox').show() ;
		} });
	}

	function check_dir_per_chat()
	{
		var json_data = new Object ;
		var unique = unixtime() ;

		$.ajax({
		type: "GET",
		url: "./install.php",
		data: "action=check_dir_per&dir=chat&"+unique,
		success: function(data){
			eval( data ) ;

			if ( !json_data.status )
			{
				$('#status_dir_per_chat').empty().html( "&nbsp; <img src=\"../pics/icons/alert.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" class=\"help_tooltip\" title=\"- "+json_data.error+"\">" ) ;
				$('#pre_errorbox').show() ;
			}
			else
				$('#status_dir_per_chat').empty().html( "&nbsp; <img src=\"../pics/icons/alert_good.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\">" ) ;

			check_dir_per_web() ;
		},
		error:function (xhr, ajaxOptions, thrownError){
			$('#status_dir_per_chat').empty().html( "&nbsp; <img src=\"../pics/icons/alert.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" class=\"help_tooltip\" title=\"- Could not connect.  Please reload the installation page.\">" ) ;
			$('#pre_errorbox').show() ;
		} });
	}

	function check_dir_per_web()
	{
		var json_data = new Object ;
		var unique = unixtime() ;

		$.ajax({
		type: "GET",
		url: "./install.php",
		data: "action=check_dir_per&dir=web&"+unique,
		success: function(data){
			eval( data ) ;

			if ( !json_data.status )
			{
				$('#status_dir_per_web').empty().html( "&nbsp; <img src=\"../pics/icons/alert.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" class=\"help_tooltip\" title=\"- "+json_data.error+"\">" ) ;
				$('#pre_errorbox').show() ;
			}
			else
				$('#status_dir_per_web').empty().html( "&nbsp; <img src=\"../pics/icons/alert_good.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\">" ) ;

			check_dir_per_sessions() ;
		},
		error:function (xhr, ajaxOptions, thrownError){
			$('#status_dir_per_web').empty().html( "&nbsp; <img src=\"../pics/icons/alert.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" class=\"help_tooltip\" title=\"- Could not connect.  Please reload the installation page.\">" ) ;
			$('#pre_errorbox').show() ;
		} });
	}

	function check_dir_per_sessions()
	{
		var json_data = new Object ;
		var unique = unixtime() ;

		$.ajax({
		type: "GET",
		url: "./install.php",
		data: "action=check_dir_per&dir=sessions&"+unique,
		success: function(data){
			eval( data ) ;

			if ( !json_data.status )
			{
				$('#status_dir_per_sessions').empty().html( "&nbsp; <img src=\"../pics/icons/alert.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" class=\"help_tooltip\" title=\"- "+json_data.error+"\">" ) ;
				$('#pre_errorbox').show() ;
			}
			else
				$('#status_dir_per_sessions').empty().html( "&nbsp; <img src=\"../pics/icons/alert_good.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\">" ) ;
			
			if ( $('#pre_errorbox').css( "display" ) == "none" )
				$('#pre_goodbox').show() ;
			init_tooltips() ;
		},
		error:function (xhr, ajaxOptions, thrownError){
			$('#status_dir_per_sessions').empty().html( "&nbsp; <img src=\"../pics/icons/alert.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" class=\"help_tooltip\" title=\"- Could not connect.  Please reload the installation page.\">" ) ;
			$('#pre_errorbox').show() ;
			init_tooltips() ;
		} });
	}

	function next_step()
	{
		$('#pre_install').hide() ;
		$('#form_install').show() ;
	}

	function test_connection( theflag )
	{
		var db_host = $('#db_host').val() ;
		var db_name = $('#db_name').val() ;
		var db_login = $('#db_login').val() ;
		var db_password = $('#db_password').val() ;
		var output_string = "" ;

		if ( theflag )
		{
			for ( c = 0; c < inputs_test.length; ++c )
			{
				if ( $('#'+inputs_test[c]).val() == "" ){ $('#status_'+inputs_test[c]).empty().html( "&nbsp; <img src=\"../pics/icons/alert.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" class=\"help_tooltip\" title=\"- Please provide a value.\">" ) ; execute = 0 ; }
				else { $('#status_'+inputs_test[c]).empty().html( "&nbsp; <img src=\"../pics/icons/alert_good.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\">" ) ; }
			}
		}

		output_string = "connecting...<div style=\"border-top: 1px dashed #D6D6D6;\"></div>host: "+db_host+"<br>database: "+db_name+"<br>login: "+db_login+"<br>password: "+db_password+"<div id=\"mysql_test_output\" style=\"margin-top: 5px;\"><img src=\"../pics/loading_ci.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"\"></div>"  ;
		$('#test_connection_output').empty().html( output_string ) ;
	}

	function init_tooltips()
	{
		var help_tooltips = $('body').find( '.help_tooltip' ) ;
		help_tooltips.tooltip({
			event: "mouseover",
			track: true,
			delay: 0,
			showURL: false,
			showBody: "- ",
			fade: 0,
			extraClass: "stat"
		});
	}

//-->
</script>
</head>
<body>

<div id="body" style="padding-bottom: 60px;">
	<div id="body_wrapper" style="z-Index: 5;"></div>
	<div style="width: 100%; z-Index: 10;">
		<div style="width: 480px; margin: 0 auto;">
		<div id="body_sub_title"></div>

			<table cellspacing=0 cellpadding=0 border=0 width="100%" class="op_submenu_wrapper"><tr><td class="t_tl"></td><td class="t_tm"></td><td class="t_tr"></td></tr>
			<tr>
				<td class="t_ml"></td><td class="t_mm">
					<div id="pre_install" style="">
						<div id="td_dept_header">Directory and Permissions</div>
						<div style="margin-top: 10px;">
							<table cellspacing=0 cellpadding=0 border=0 width="100%">
							<tr>
								<td class="td_dept_td">Directory Permissions</td>
								<td class="td_dept_td" width="25px"><span id="status_dir_per_chat"></span></td>
							</tr>
							<tr>
								<td class="td_dept_td">web/ Directory Permissions</td>
								<td class="td_dept_td" width="25px"><span id="status_dir_per_web"></span></td>
							</tr>
							<tr>
								<td class="td_dept_td">Creation of Directories</td>
								<td class="td_dept_td" width="25px"><span id="status_chat_dirs"></span></td>
							</tr>
							<tr>
								<td class="td_dept_td">Session Permissions</td>
								<td class="td_dept_td" width="25px"><span id="status_dir_per_sessions"></span></td>
							</tr>
							<tr>
								<td class="td_dept_td" colspan="2"><div id="pre_goodbox" class="info_good" style="display: none; text-align: center;"><button type="button" onClick="next_step()">Continue to Installation</button></div><div id="pre_errorbox" class="info_error" style="display: none;">Errors were produced.  Mouse over the red alert icon for more details.</div></td>
							</tr>
							<tr>
								<td class="td_dept_td" colspan=2><?php echo "<div  class=\"info_neutral\" style=\"font-size: 10px; color: #7C89A9;\">[ safe mode: $ini_safe_mode, $safe_mode ] [ open_base: $ini_open_basedir ]</div>" ?></td>
							</tr>
							</table>
							
						</div>
					</div>
					
					<form id="form_install" style="display: none;">
					<div id="td_dept_header">Create Setup Profile</div>
					<div style="margin-top: 10px;">
						<table cellspacing=0 cellpadding=0 border=0>
						<tr>
							<td class="td_dept_td" width="120">Your Email</td>
							<td class="td_dept_td"><input type="text" class="input" size="35" maxlength="50" name="email" id="email" onKeyPress="return justemails(event)" value=""> <span id="status_email"></span></td>
						</tr>
						<tr>
							<td class="td_dept_td" width="120">Setup Login</td>
							<td class="td_dept_td"><input type="text" class="input" size="35" maxlength="15" name="login" id="login" onKeyPress="return nospecials(event)" value=""> <span id="status_login"></span></td>
						</tr>
						<tr>
							<td class="td_dept_td" width="120">Setup Password</td>
							<td class="td_dept_td"><input type="password" class="input" size="35" maxlength="25" name="password" id="password" value=""> <span id="status_password"></span></td>
						</tr>
						<tr>
							<td class="td_dept_td" width="120">Verify Password</td>
							<td class="td_dept_td"><input type="password" class="input" size="35" maxlength="25" name="vpassword" id="vpassword" value=""> <span id="status_vpassword"></span></td>
						</tr>
						<?php if ( $pv >= "5.1.0" ): ?>
						<tr>
							<td class="td_dept_td" width="120">Timezone</td>
							<td class="td_dept_td">
								<select id="timezone">
								<?php
									for ( $c = 0; $c < count( $timezones ); ++$c )
									{
										$selected = "" ;
										if ( $timezones[$c] == date_default_timezone_get() )
											$selected = "selected" ;

										print "<option value=\"$timezones[$c]\" $selected>$timezones[$c]</option>" ;
									}
								?>
								</select>
							</td>
						</tr>
						<?php else: ?>
						<tr><td colspan="2"><input type="hidden" id="timezone" value="America/New_York"></td></tr>
						<?php endif ; ?>
						</table>
					</div>

					<div id="td_dept_header" style="margin-top: 10px;">Path Settings<div style="margin-top: 5px; font-weight: normal;">Prefilled values should not be edited in most cases.</div></div>
					<div style="margin-top: 10px;">
						<table cellspacing=0 cellpadding=0 border=0>
						<tr>
							<td class="td_dept_td" width="120">Base URL</td>
							<td class="td_dept_td"><input type="text" class="input" size="35" maxlength="255" name="base_url" id="base_url"> <span id="status_base_url"></span></td>
						</tr>
						<tr>
							<td class="td_dept_td" width="120">Document Root</td>
							<td class="td_dept_td"><input type="text" class="input" size="35" maxlength="255" name="document_root" id="document_root" value="<?php echo $document_root ?>"> <span id="status_document_root"></span></td>
						</tr>
						</table>
					</div>

					<div id="td_dept_header" style="margin-top: 10px;">Database Information<div style="margin-top: 5px; font-weight: normal;">Please make sure you have created the database prior to installation.</div></div>
					<div style="margin-top: 10px;">
						<table cellspacing=0 cellpadding=0 border=0>
						<tr>
							<td class="td_dept_td" width="120">DB Type</td>
							<td class="td_dept_td"><select id="db_type" name="db_type"><option value="mysql">MySQL</option></select></td>
						</tr>
						<tr>
							<td class="td_dept_td" width="120">DB Host</td>
							<td class="td_dept_td"><input type="text" class="input" size="35" maxlength="55" name="db_host" id="db_host" value=""> <span id="status_db_host"></span></td>
						</tr>
						<tr>
							<td class="td_dept_td" width="120">DB Name</td>
							<td class="td_dept_td"><input type="text" class="input" size="35" maxlength="35" name="db_name" id="db_name" value=""> <span id="status_db_name"></span></td>
						</tr>
						<tr>
							<td class="td_dept_td" width="120">DB Login</td>
							<td class="td_dept_td"><input type="text" class="input" size="35" maxlength="35" name="db_login" id="db_login" value=""> <span id="status_db_login"></span></td>
						</tr>
						<tr>
							<td class="td_dept_td" width="120">DB Password</td>
							<td class="td_dept_td"><input type="password" class="input" size="35" maxlength="35" name="db_password" id="db_password" value=""> <span id="status_db_password"></span></td>
						</tr>
						<tr>
							<td class="td_dept_td" width="120">&nbsp;</td>
							<td class="td_dept_td" style="font-size: 10px;">
								<img src="../pics/main_grey.png" width="20" height="10" border="0" alt=""><a href="JavaScript:void(0)" onClick="test_connection(1);check_db(0);">connection output (click to test)</a><br>if loading exceeds 15 seconds, review the values and try again
								<div id="test_connection_output" style="margin-top: 5px; height: 105px; overflow: auto;" class="info_neutral"></div>
							</td>
						</tr>
						</table>
					</div>

					<div id="td_dept_header" style="margin-top: 10px;">Run Installer</div>
					<div style="margin-top: 10px;">
						<table cellspacing=0 cellpadding=0 border=0 width="100%">
						<tr>
							<td class="td_dept_td" align="center"><button type="button" id="btn_run" style="padding: 5px; font-weight: bold; font-size: 14px;" onClick="run_installer()"> Run Installer</button></td>
						</tr>
						</table>
					</div>

					</form>
				</td><td class="t_mr"></td>
			</tr>
			<tr><td class="t_bl"></td><td class="t_bm"></td><td class="t_br"></td></tr>
			</table>
		
		</div>

		<div style="width: 100%;">
			<div style="width: 480px; margin: 0 auto; margin-top: 30px; font-size: 10px; background: url( ../pics/bg_footer.gif ) repeat-x; border-left: 1px solid #B1B4B5; border-right: 1px solid #B1B4B5; border-bottom: 1px solid #B1B4B5; color: #606060; -moz-border-radius: 5px; border-radius: 5px;">
				<div style="float: left; padding: 3px; padding-top: 7px;"><a href="http://www.phplivesupport.com/?ref=link&key=<?php echo $KEY ?>&plk=osicodes-5-ykq-m" target="new">PHP Live!</a> &copy; OSI Codes Inc.</div>
				<div style="float: left; width: 2px; height: 26px; background: url( ../pics/h_divider.gif ) no-repeat;"></div>
				<div style="float: left; padding: 3px; padding-top: 7px;"><a href="http://www.phplivesupport.com/copyright.php?&plk=osicodes-5-ykq-m">copyright policy</a></div>
				<div style="float: left; width: 2px; height: 26px; background: url( ../pics/h_divider.gif ) no-repeat;"></div>
				<div style="float: left; padding: 3px; padding-top: 7px;"><a href="http://www.phplivesupport.com/help_desk.php?&plk=osicodes-5-ykq-m">Help</a></div>
				<div style="clear: both;"></div>
			</div>
		</div>
	</div>
</div>
<div style="position: absolute; top: 0px; left: 0px; width: 100%; z-index: 13;">
	<div style="width: 480px; margin: 0 auto; background: url( ../pics/bg_trans.png ) repeat; border-bottom-left-radius: 5px 5px; -moz-border-radius-bottomleft: 5px 5px; border-bottom-right-radius: 5px 5px; -moz-border-radius-bottomright: 5px 5px;">
		<div id="menu_wrapper">
			<div id="menu_install" class="menu_focus" onClick="toggle_menu('install')">PHP Live! Installation</div>
		</div>
	</div>
</div>
<div style="position: absolute; top: 13px; left: 0px; width: 100%; z-index: 12;">
	<div style="width: 480px; margin: 0 auto; padding-top: 31px;">
		<div style="font-size: 10px; font-weight: bold; color: #CBD1D5; text-align: right;"><span style="font-size: 16px; text-shadow: #5A6787 -1px -1px;">PHP Live! <?php echo $PHPLIVE_VERSION ?></span></div>
	</div>
</div>

<div style="position: absolute; background: url( ../pics/bg_fade_bottom.png ) repeat-x; background-position: bottom; top: 0px; left: 0px; width: 100%; height: 60px; z-index: 11;"></div>

<div id="canvas_wrapper" style="display: none; position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; background: url( ../pics/bg_trans.png ) repeat; z-Index: 100;">
</div>
<div id="canvas_success" style="display: none; position: absolute; padding: 10px; background: #FFFFFF; z-Index: 101; border: 10px solid #8E97AF; text-align: center;">
	<div style="margin-top: 10px; font-size: 16px; font-weight: bold;"><img src="../pics/icons/alert_good.png" width="16" height="16" border="0" alt=""> PHP Live! has been installed!
		<div style="margin-top: 5px; padding: 5px; background: #75EE5B; border: 1px solid #5DBE49; color: #146D23; cursor: pointer;" onClick="location.href='../?menu=setup'">Login to Setup</div>
	</div>
</div>
<div id="canvas_loading" style="display: none; position: absolute; padding: 10px; background: #FFFFFF; z-Index: 101; border: 10px solid #8E97AF; text-align: center;">
	<div><img src="../pics/loading_ci.gif" border="0" alt=""></div>
	<div style="margin-top: 10px; font-size: 16px; font-weight: bold;"><img src="../pics/icons/info.png" width="16" height="16" border="0" alt=""> PHP Live! is being installed...</div>
</div>

</body>
</html>

<?php
	function get_db_query()
	{
		$query = "DROP TABLE IF EXISTS p_admins; CREATE TABLE IF NOT EXISTS p_admins (   adminID int(10) unsigned NOT NULL AUTO_INCREMENT,   created int(10) unsigned NOT NULL,   lastactive int(10) unsigned NOT NULL,   `status` tinyint(4) NOT NULL,   ses varchar(32) NOT NULL,   login varchar(15) NOT NULL,   `password` varchar(32) NOT NULL,   email varchar(160) NOT NULL,   PRIMARY KEY (adminID),   KEY ses (ses) ); DROP TABLE IF EXISTS p_canned; CREATE TABLE IF NOT EXISTS p_canned (   canID int(10) unsigned NOT NULL AUTO_INCREMENT,   opID int(10) unsigned NOT NULL,   deptID int(10) unsigned NOT NULL,   title varchar(35) NOT NULL,   message mediumtext NOT NULL,   PRIMARY KEY (canID),   KEY opID (opID),   KEY deptID (deptID) ); DROP TABLE IF EXISTS p_departments; CREATE TABLE IF NOT EXISTS p_departments (   deptID int(10) unsigned NOT NULL AUTO_INCREMENT,   visible tinyint(4) NOT NULL,   queue tinyint(4) NOT NULL,   tshare tinyint(4) NOT NULL,   texpire int(10) unsigned NOT NULL,   rtype tinyint(4) NOT NULL,   rtime int(10) unsigned NOT NULL,   img_offline varchar(50) NOT NULL,   img_online varchar(50) NOT NULL,   `name` varchar(40) NOT NULL,   email varchar(160) NOT NULL,   msg_greet text NOT NULL,   msg_offline text NOT NULL,   msg_email text NOT NULL,   PRIMARY KEY (deptID) ); DROP TABLE IF EXISTS p_dept_ops; CREATE TABLE IF NOT EXISTS p_dept_ops (   deptID int(10) unsigned NOT NULL,   opID int(10) unsigned NOT NULL,   display tinyint(4) NOT NULL,   visible tinyint(4) NOT NULL,   PRIMARY KEY (deptID,opID) ); DROP TABLE IF EXISTS p_external; CREATE TABLE IF NOT EXISTS p_external (   extID int(10) unsigned NOT NULL AUTO_INCREMENT,   `name` varchar(40) NOT NULL,   url varchar(255) NOT NULL,   PRIMARY KEY (extID) ); DROP TABLE IF EXISTS p_ext_ops; CREATE TABLE IF NOT EXISTS p_ext_ops (   extID int(10) NOT NULL,   opID int(10) NOT NULL,   UNIQUE KEY extID (extID,opID) ); DROP TABLE IF EXISTS p_footprints; CREATE TABLE IF NOT EXISTS p_footprints (   created int(10) unsigned NOT NULL,   ip varchar(25) NOT NULL,   os tinyint(1) NOT NULL,   browser tinyint(1) NOT NULL,   mdfive varchar(32) NOT NULL,   onpage varchar(255) NOT NULL,   title varchar(150) NOT NULL,   KEY ip (ip),   KEY created (created) ); DROP TABLE IF EXISTS p_footprints_u; CREATE TABLE IF NOT EXISTS p_footprints_u (   created int(10) unsigned NOT NULL,   updated int(10) unsigned NOT NULL,   deptID int(10) unsigned NOT NULL,   marketID int(10) unsigned NOT NULL,   os tinyint(1) NOT NULL,   browser tinyint(1) NOT NULL,   resolution varchar(15) NOT NULL,   ip varchar(25) NOT NULL,   hostname varchar(150) NOT NULL,   onpage varchar(255) NOT NULL,   title varchar(150) NOT NULL,   refer varchar(255) NOT NULL,   UNIQUE KEY ip (ip),   KEY updated (updated) ); DROP TABLE IF EXISTS p_footstats; CREATE TABLE IF NOT EXISTS p_footstats (   sdate int(10) unsigned NOT NULL,   total int(10) unsigned NOT NULL,   onpage varchar(255) NOT NULL,   KEY sdate (sdate) ); DROP TABLE IF EXISTS p_ips; CREATE TABLE IF NOT EXISTS p_ips (   ip varchar(25) NOT NULL,   created int(10) unsigned NOT NULL,   t_footprints int(10) unsigned NOT NULL,   t_requests int(10) unsigned NOT NULL,   t_initiate int(11) NOT NULL,   PRIMARY KEY (ip) ); DROP TABLE IF EXISTS p_marketing; CREATE TABLE IF NOT EXISTS p_marketing (   marketID int(10) unsigned NOT NULL AUTO_INCREMENT,   skey varchar(4) NOT NULL,   `name` varchar(40) NOT NULL,   color varchar(6) NOT NULL,   PRIMARY KEY (marketID),   KEY skey (skey) ); DROP TABLE IF EXISTS p_market_c; CREATE TABLE IF NOT EXISTS p_market_c (   sdate int(10) unsigned NOT NULL,   marketID int(10) unsigned NOT NULL,   clicks mediumint(8) unsigned NOT NULL,   PRIMARY KEY (sdate,marketID) ); DROP TABLE IF EXISTS p_marquees; CREATE TABLE IF NOT EXISTS p_marquees (   marqID int(10) unsigned NOT NULL AUTO_INCREMENT,   display tinyint(4) NOT NULL,   deptID int(10) unsigned NOT NULL,   `snapshot` varchar(35) NOT NULL,   message varchar(255) NOT NULL,   PRIMARY KEY (marqID) ); DROP TABLE IF EXISTS p_operators; CREATE TABLE IF NOT EXISTS p_operators (   opID int(10) unsigned NOT NULL AUTO_INCREMENT,   lastactive int(10) unsigned NOT NULL,   lastrequest int(11) unsigned NOT NULL,   `status` tinyint(4) NOT NULL,   signall tinyint(4) NOT NULL,   rate tinyint(4) NOT NULL,   op2op tinyint(4) NOT NULL,   traffic tinyint(4) NOT NULL,   ses varchar(32) NOT NULL,   login varchar(15) NOT NULL,   `password` varchar(32) NOT NULL,   `name` varchar(40) NOT NULL,   email varchar(160) NOT NULL,   pic varchar(50) NOT NULL,   theme varchar(15) NOT NULL,   PRIMARY KEY (opID),   KEY ses (ses),   KEY lastactive (lastactive,`status`) ); DROP TABLE IF EXISTS p_opstatus_log; CREATE TABLE IF NOT EXISTS p_opstatus_log (   created int(11) NOT NULL,   opID int(11) NOT NULL,   `status` tinyint(4) NOT NULL,   KEY created (created) ); DROP TABLE IF EXISTS p_refer; CREATE TABLE IF NOT EXISTS p_refer (   ip varchar(25) NOT NULL,   created int(10) unsigned NOT NULL,   marketID int(10) unsigned NOT NULL,   mdfive varchar(32) NOT NULL,   refer varchar(255) NOT NULL,   KEY mdfive (mdfive),   KEY ip (ip) ); DROP TABLE IF EXISTS p_referstats; CREATE TABLE IF NOT EXISTS p_referstats (   sdate int(10) unsigned NOT NULL,   total int(10) unsigned NOT NULL,   refer varchar(255) NOT NULL,   KEY sdate (sdate) ); DROP TABLE IF EXISTS p_reqstats; CREATE TABLE IF NOT EXISTS p_reqstats (   sdate int(10) unsigned NOT NULL,   deptID int(10) unsigned NOT NULL,   opID int(10) unsigned NOT NULL,   requests int(10) NOT NULL,   taken smallint(5) unsigned NOT NULL,   declined smallint(5) unsigned NOT NULL,   message smallint(5) unsigned NOT NULL,   initiated smallint(5) unsigned NOT NULL,   PRIMARY KEY (sdate,deptID,opID) ); DROP TABLE IF EXISTS p_requests; CREATE TABLE IF NOT EXISTS p_requests (   requestID int(10) unsigned NOT NULL AUTO_INCREMENT,   created int(10) unsigned NOT NULL,   updated int(10) unsigned NOT NULL,   vupdated int(10) unsigned NOT NULL,   `status` tinyint(1) NOT NULL,   deptID int(11) unsigned NOT NULL,   opID int(11) unsigned NOT NULL,   op2op int(10) unsigned NOT NULL,   marketID int(10) NOT NULL,   os tinyint(1) NOT NULL,   browser tinyint(1) NOT NULL,   requests int(10) unsigned NOT NULL,   ces varchar(32) NOT NULL,   resolution varchar(15) NOT NULL,   vname varchar(40) NOT NULL,   vemail varchar(160) NOT NULL,   ip varchar(25) NOT NULL,   hostname varchar(150) NOT NULL,   agent varchar(200) NOT NULL,   onpage varchar(255) NOT NULL,   title varchar(150) NOT NULL,   rstring varchar(255) NOT NULL,   refer varchar(255) NOT NULL,   question text NOT NULL,   PRIMARY KEY (requestID),   UNIQUE KEY ces (ces),   KEY opID (opID),   KEY op2op (op2op),   KEY updated (updated),   KEY `status` (`status`) ); DROP TABLE IF EXISTS p_req_log; CREATE TABLE IF NOT EXISTS p_req_log (   ces varchar(32) NOT NULL,   created int(10) unsigned NOT NULL,   ended int(10) unsigned NOT NULL,   `status` tinyint(1) NOT NULL,   deptID int(11) unsigned NOT NULL,   opID int(11) unsigned NOT NULL,   op2op int(11) NOT NULL,   marketID int(10) NOT NULL,   os tinyint(1) NOT NULL,   browser tinyint(1) NOT NULL,   resolution varchar(15) NOT NULL,   vname varchar(40) NOT NULL,   vemail varchar(160) NOT NULL,   ip varchar(25) NOT NULL,   hostname varchar(150) NOT NULL,   agent varchar(200) NOT NULL,   onpage varchar(255) NOT NULL,   title varchar(150) NOT NULL,   question text NOT NULL,   PRIMARY KEY (ces),   KEY opID (opID),   KEY ip (ip) ); DROP TABLE IF EXISTS p_transcripts; CREATE TABLE IF NOT EXISTS p_transcripts (   ces varchar(32) NOT NULL,   created int(11) unsigned NOT NULL,   ended int(10) unsigned NOT NULL,   deptID int(11) unsigned NOT NULL,   opID int(11) unsigned NOT NULL,   op2op tinyint(4) NOT NULL,   rating tinyint(1) NOT NULL,   fsize mediumint(9) NOT NULL,   vname varchar(40) NOT NULL,   vemail varchar(160) NOT NULL,   ip varchar(25) NOT NULL,   question text NOT NULL,   formatted text NOT NULL,   plain text NOT NULL,   PRIMARY KEY (ces),   KEY ip (ip),   KEY created (created),   KEY rating (rating),   KEY opID (opID) ); DROP TABLE IF EXISTS p_vars; CREATE TABLE IF NOT EXISTS p_vars (   `code` varchar(10) NOT NULL );" ;

		$query_array = split( ";", $query ) ;
		return $query_array ;
	}
?>
