<?php
	$ERROR_EMAIL = "" ;
	function eMailErrorHandler($errno, $errstr) { global $ERROR_EMAIL; $ERROR_EMAIL = "Server Error: ".preg_replace( "/\"/", "\\\"", $errstr ) ; }
	function Util_Email_SendEmail( $from_name, $from_email, $to_name, $to_email, $subject, $message, $extra )
	{
		set_error_handler('eMailErrorHandler') ;

		$to_name = preg_replace( "/<v>/", "", $to_name ) ;
		global $CONF ;
		global $ERROR_EMAIL ;

		$base_url = $CONF["BASE_URL"] ;
		if ( !preg_match( "/^http:/", $base_url ) )
			$base_url = "http:$base_url" ;

		if ( $extra == "trans" )
		{
			$message = preg_replace( "/<>/", "\r\n", $message ) ;
			$message = preg_replace( "/<disconnected><d(\d)>(.*?)<\/div>/", "\r\n$2\r\n=== END OF CHAT ===\r\n", $message ) ;
			$message = preg_replace( "/<div class='ca'><i>(.*?)<\/i><\/div>/", "===\r\nQuestion: $1\r\n===", $message ) ;
			$message = preg_replace( "/<div class='co'><b>(.*?)<timestamp_(\d+)_co>:<\/b> /", "\r\n$1:\r\n", $message ) ;
			$message = preg_replace( "/<div class='cv'><b><v>(.*?)<timestamp_(\d+)_cv>:<\/b> /", "\r\n$1:\r\n", $message ) ;
			$message = preg_replace( "/<(.*?)>/", "", $message ) ;
			$message = stripslashes( $message ) ;

			// todo: add setup toggle before enable
			//$headers = "MIME-Version: 1.0" . "\r\n";
			//$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
			//$headers .= "To: $to_name <$to_email>" . "\r\n";
			//$headers .= "From: $from_name <$from_email>" . "\r\n";
		}

		if ( mail( $to_email, $subject, $message, "From: $from_name <$from_email>" ) ) { set_error_handler( "ErrorHandler" ) ; return true ; }
		else if ( $ERROR_EMAIL ) { set_error_handler( "ErrorHandler" ) ; return $ERROR_EMAIL ; }
	}
?>
