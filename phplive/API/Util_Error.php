<?php
	error_reporting(0) ;

	/*****************************************************************/
	function ErrorHandler ( $errno, $errmsg, $filename, $linenum, $vars ) 
	{
		global $CONF ;

		// for now set timezone to default... it will be replaced by
		// user's timezone later
		if ( phpversion() >= "5.1.0" ){ date_default_timezone_set( "America/New_York" ) ; }
		$time = date( "D m/d/Y H:i:s" ) ;
		$ip = $_SERVER['REMOTE_ADDR'] ;

		// 600-699 is custom error reserved for PHP Live! Support
		$errortype = array (
			1		=>  "Error",
			2		=>  "Warning",
			4		=>  "Parsing Error",
			8		=>  "Notice",
			16		=>  "Core Error",
			32		=>  "Core Warning",
			64		=>  "Compile Error",
			128		=>  "Compile Warning",
			256		=>  "User Error",
			512		=>  "User Warning",
			1024	=>  "User Notice",
			600		=>	"PHP Live! Support DB Connection Failed",
			601		=>	"PHP Live! Support Configuration Missing",
			602		=>	"PHP Live! Support Setup Session Expired",
			603		=>	"PHP Live! Support Chat Request Not Created",
			604		=>	"PHP Live! DB Data Error",
			605		=>	"PHP Live! Error",
			606		=>	"PHP Live! Patch Loop Error",
			607		=>	"PHP Live! version not compatible with WinApp"
		);
		// set of errors for which a var trace will be saved
		$user_errors = array( E_ALL ) ;

		// do the redirects here
		if ( $errno == 602 )
		{
			HEADER( "location: $CONF[BASE_URL]/?errno=602" ) ;
			exit ;
		}

		// save to the error log, and e-mail if there is a critical user error
		//error_log($err, 3, "/usr/local/php4/error.log");
		if ( $errno )
		{
			$errmsg_query = urlencode( $errmsg ) ;

			$admin_email = $_SERVER['SERVER_ADMIN'] ;
			print "
				<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML Basic 1.0//EN\" \"http://www.w3.org/TR/xhtml-basic/xhtml-basic10.dtd\">
				<html><head><title> Error: $errortype[$errno] </title>
				</head>
				<body style=\"background: #F6F3F3; margin: 0; padding: 0; overflow: auto; font-family: Arial; font-size: 12px; color: #524F4F;\">
					<div style=\"padding: 10px;\">
						<div style=\"color: #E1001A; font-size: 16px; font-weight: bold;\">Error: $errortype[$errno]</div>
						<div style=\"padding-bottom: 5px; padding-top: 10px;\">Live Support system has produced the following error. Make sure the URL you are attempting to access has not been altered.  Please notify the website admin.</div>

						<div style=\"margin-top: 5px; margin-bottom: 5px;\">
							<table cellspacing=1 cellpadding=2 border=0>
							<tr>
								<td style=\"background: #EBE7E7;\">Time</td><td>: $time</td>
							</tr>
							<tr>
								<td style=\"background: #EBE7E7;\">Error Type</td><td>: [$errno] $errortype[$errno]</td>
							</tr>
							<tr>
								<td style=\"background: #E1001A; color: #FFE9EC;\">Error Message</td><td>: <font color=\"#E1001A\">$errmsg</font> - [ <a href=\"http://www.phplivesupport.com/help_desk.php?errornum=$errno&error=$errmsg_query\" style=\"text-decoration: none\" target=\"_blank\"><font color=\"#47B039\">Check for Solutions</font></a> ]</td>
							</tr>
							<tr>
								<td style=\"background: #EBE7E7;\">File Name</td><td>: $filename</td>
							</tr>
							<tr>
								<td style=\"background: #EBE7E7;\">File Line #</td><td>: $linenum</td>
							</tr>
							<tr>
								<td style=\"background: #EBE7E7;\">Query</td><td>: $_SERVER[QUERY_STRING]</td>
							</tr>
							<tr>
								<td style=\"background: #EBE7E7;\">Your IP</td><td>: $ip</td>
							</tr>
							</table>
						</div>
						
						<div style=\"border: 1px solid #A19E9E; background: #EBE7E7; margin-top: 10px; margin-bottom: 10px;\"><div style=\"padding: 5px;\">Please notify the website admin.</div></div>

						<div style=\"font-size: 10px\">PHP Live! Support &copy; OSI Codes Inc.</div>
					</div>
				</body>
				</html>
			" ;
			if ( isset( $admin_email ) && $admin_email )
			{
				//mail( $admin_email, "Your PHP Live! Support System Error", "Automated Email from PHP Live! Support Error Reporting System.  PHP Live! Support may be down or has errors!\n\nError Below:\n\n$err\n\nPlease take appropriate action to correct this problem.", "From: PHP Live! Support System <$admin_email>") ;
			}
			exit ;
		}
	}
	set_error_handler( "ErrorHandler" ) ;
?>
