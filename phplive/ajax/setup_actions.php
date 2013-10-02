<?php
	/****************************************/
	// STANDARD header for Setup
	include_once( "../web/config.php" ) ;
	include_once( "../API/Util_Format.php" ) ;
	include_once( "../API/SQL.php" ) ;
	include_once( "../API/Util_Security.php" ) ;
	$ses = Util_Format_Sanatize( Util_Format_GetVar( "ses" ), "ln" ) ;
	if ( !$setupinfo = Util_Security_AuthSetup( $dbh, $ses ) ){ $json_data = "json_data = { \"status\": 0, \"error\": \"Authentication error.\" };" ; exit ; }
	// STANDARD header end
	/****************************************/

	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;
	$deptid = Util_Format_Sanatize( Util_Format_GetVar( "deptid" ), "ln" ) ;
	$opid = Util_Format_Sanatize( Util_Format_GetVar( "opid" ), "ln" ) ;

	if ( $action == "moveup" )
	{
		include_once( "../API/Ops/get.php" ) ;

		if ( Ops_get_IsOpInDept( $dbh, $opid, $deptid ) || !$opid )
		{
			include_once( "../API/Depts/get.php" ) ;
			include_once( "../API/Ops/update.php" ) ;
			
			if ( $deptid )
				Ops_update_OpDeptMoveUp( $dbh, $opid, $deptid ) ;
			$dept_ops = Depts_get_DeptOps( $dbh, $deptid ) ;

			$json_data = "json_data = { \"status\": 1, \"ops\": [ " ;
			for ( $c = 0; $c < count( $dept_ops ); ++$c )
			{
				$dept_op = $dept_ops[$c] ;
				$json_data .= "{ \"name\": \"$dept_op[name]\", \"opid\": $dept_op[opID], \"display\": $dept_op[display] }," ;
			}

			$json_data = substr_replace( $json_data, "", -1 ) ;
			$json_data .= "	] };" ;
		}
		else
			$json_data = "json_data = { \"status\": 0 };" ;
	}
	else if ( $action == "op_dept_remove" )
	{
		include_once( "../API/Ops/remove.php" ) ;

		Ops_remove_OpDept( $dbh, $opid, $deptid ) ;
		$json_data = "json_data = { \"status\": 1 };" ;
	}
	else if ( $action == "optimize" )
	{
		include_once( "../API/Footprints/get_ext.php" ) ;
		include_once( "../API/Footprints/put.php" ) ;

		$today = mktime( 0, 0, 1, date( "m", time() ), date( "j", time() ), date( "Y", time() ) ) ;

		$latest = Footprints_get_LatestStats( $dbh ) ;

		if ( isset( $latest["sdate"] ) )
			$sdate_start = mktime( 0, 0, 1, date( "m", $latest["sdate"] ), date( "j", $latest["sdate"] )+1, date( "Y", $latest["sdate"] ) ) ;
		else
		{
			$latest = Footprints_get_OldestPrint( $dbh ) ;
			if ( isset( $latest["created"] ) )
				$sdate_start = mktime( 0, 0, 1, date( "m", $latest["created"] ), date( "j", $latest["created"] ), date( "Y", $latest["created"] ) ) ;
			else
				$sdate_start = mktime( 0, 0, 1, date( "m", time() ), date( "j", time() )-1, date( "Y", time() ) ) ;
		}

		if ( $sdate_start < $today )
		{
			$sdate_end = mktime( 0, 0, 1, date( "m", $sdate_start ), date( "j", $sdate_start )+1, date( "Y", $sdate_start ) ) ;
			$footprints = Footprints_get_FootData( $dbh, $sdate_start, $sdate_end ) ;
			$refers = Footprints_get_ReferData( $dbh, $sdate_start, $sdate_end ) ;

			for ( $c = 0; $c < count( $footprints ); ++$c )
			{
				$footprint = $footprints[$c] ;
				Footprints_put_PrintStat( $dbh, $sdate_start, $footprint["total"], $footprint["onpage"] ) ;
			}
			if ( !$c )
				Footprints_put_PrintStat( $dbh, $sdate_start, 0, "null" ) ;

			for ( $c = 0; $c < count( $refers ); ++$c )
			{
				$refer = $refers[$c] ;
				Footprints_put_ReferStat( $dbh, $sdate_start, $refer["total"], $refer["refer"] ) ;
			}
			if ( !$c )
				Footprints_put_ReferStat( $dbh, $sdate_start, 0, "null" ) ;

			$date_expanded = date( "l F j, Y", $sdate_start ) ;
			$json_data = "json_data = { \"status\": 1, \"date\": \"$date_expanded\" };" ;
		}
		else
			$json_data = "json_data = { \"status\": 2 };" ;
	}
	else if ( $action == "footprints" )
	{
		include_once( "../API/Footprints/get_ext.php" ) ;

		$sdate_start = Util_Format_Sanatize( Util_Format_GetVar( "sdate" ), "ln" ) ;

		$today = mktime( 0, 0, 1, date( "m", time() ), date( "j", time() ), date( "Y", time() ) ) ;
		$today_end = mktime( 23, 59, 59, date( "m", time() ), date( "j", time() ), date( "Y", time() ) ) ; // 2sec buffer
		$sdate_end = mktime( 0, 0, 1, date( "m", $sdate_start ), date( "j", $sdate_start )+1, date( "Y", $sdate_start ) ) ;

		if ( !$sdate_start )
		{
			$month_start = Util_Format_Sanatize( Util_Format_GetVar( "start" ), "ln" ) ;
			$month_end = Util_Format_Sanatize( Util_Format_GetVar( "end" ), "ln" ) ;

			$footprints_today = Footprints_get_FootData( $dbh, $today, $today_end ) ;
			$footprints_hist = Footprints_get_FootStatsData( $dbh, $month_start, $month_end ) ;

			$footprints = $footprints_pre = Array() ;
			foreach( $footprints_today as $key => $value )
			{
				if ( !isset( $footprints_pre[$value["onpage"]] ) )
				{
					$footprints_pre[$value["onpage"]] = Array() ;
					$footprints_pre[$value["onpage"]]["total"] = 0 ;
				}

				$footprints_pre[$value["onpage"]]["data"] = $value ;
				$footprints_pre[$value["onpage"]]["total"] += $value["total"] ;
			}
			foreach( $footprints_hist as $key => $value )
			{
				if ( !isset( $footprints_pre[$value["onpage"]] ) )
				{
					$footprints_pre[$value["onpage"]] = Array() ;
					$footprints_pre[$value["onpage"]]["total"] = 0 ;
				}

				$footprints_pre[$value["onpage"]]["data"] = $value ;
				$footprints_pre[$value["onpage"]]["total"] += $value["total"] ;
			}
			foreach( $footprints_pre as $key => $value )
				$footprints[] = $value["data"] ;
		}
		else if ( $sdate_start == $today )
			$footprints = Footprints_get_FootData( $dbh, $sdate_start, $sdate_end ) ;
		else
		{
			if ( !$sdate_start )
			{
				$sdate_start = Util_Format_Sanatize( Util_Format_GetVar( "start" ), "ln" ) ;
				$sdate_end = Util_Format_Sanatize( Util_Format_GetVar( "end" ), "ln" ) ;
			}

			$footprints = Footprints_get_FootStatsData( $dbh, $sdate_start, $sdate_end ) ;
		}

		$json_data = "json_data = { \"status\": 1, \"footprints\": [ " ;
		for ( $c = 0; $c < count( $footprints ); ++$c )
		{
			$footprint = $footprints[$c] ;
			if ( $footprint["onpage"] != "null" )
			{
				$url = preg_replace( "/hphp/i", "http", $footprint["onpage"] ) ;
				$url_snap = ( strlen( $url ) > 130 ) ? substr( $url, 0, 130 ) . "..." : $url ;
				$json_data .= "{ \"total\": $footprint[total], \"url_snap\": \"$url_snap\", \"url_raw\": \"$url\" }," ;
			}
		}

		$json_data = substr_replace( $json_data, "", -1 ) ;
		$json_data .= "	] };" ;
	}
	else if ( $action == "refers" )
	{
		include_once( "../API/Footprints/get_ext.php" ) ;

		$sdate_start = Util_Format_Sanatize( Util_Format_GetVar( "sdate" ), "ln" ) ;

		$today = mktime( 0, 0, 1, date( "m", time() ), date( "j", time() ), date( "Y", time() ) ) ;
		$today_end = mktime( 23, 59, 59, date( "m", time() ), date( "j", time() ), date( "Y", time() ) ) ; // 2sec buffer
		$sdate_end = mktime( 0, 0, 1, date( "m", $sdate_start ), date( "j", $sdate_start )+1, date( "Y", $sdate_start ) ) ;

		if ( !$sdate_start )
		{
			$month_start = Util_Format_Sanatize( Util_Format_GetVar( "start" ), "ln" ) ;
			$month_end = Util_Format_Sanatize( Util_Format_GetVar( "end" ), "ln" ) ;

			$footprints_today = Footprints_get_ReferData( $dbh, $today, $today_end ) ;
			$footprints_hist = Footprints_get_ReferStatsData( $dbh, $month_start, $month_end ) ;

			$footprints = $footprints_pre = Array() ;
			foreach( $footprints_today as $key => $value )
			{
				if ( !isset( $footprints_pre[$value["refer"]] ) )
				{
					$footprints_pre[$value["refer"]] = Array() ;
					$footprints_pre[$value["refer"]]["total"] = 0 ;
				}

				$footprints_pre[$value["refer"]]["data"] = $value ;
				$footprints_pre[$value["refer"]]["total"] += $value["total"] ;
			}
			foreach( $footprints_hist as $key => $value )
			{
				if ( !isset( $footprints_pre[$value["refer"]] ) )
				{
					$footprints_pre[$value["refer"]] = Array() ;
					$footprints_pre[$value["refer"]]["total"] = 0 ;
				}

				$footprints_pre[$value["refer"]]["data"] = $value ;
				$footprints_pre[$value["refer"]]["total"] += $value["total"] ;
			}
			foreach( $footprints_pre as $key => $value )
				$footprints[] = $value["data"] ;
		}
		else if ( $sdate_start == $today )
			$footprints = Footprints_get_ReferData( $dbh, $sdate_start, $sdate_end ) ;
		else
		{
			if ( !$sdate_start )
			{
				$sdate_start = Util_Format_Sanatize( Util_Format_GetVar( "start" ), "ln" ) ;
				$sdate_end = Util_Format_Sanatize( Util_Format_GetVar( "end" ), "ln" ) ;
			}

			$footprints = Footprints_get_ReferStatsData( $dbh, $sdate_start, $sdate_end ) ;
		}

		$json_data = "json_data = { \"status\": 1, \"footprints\": [ " ;
		for ( $c = 0; $c < count( $footprints ); ++$c )
		{
			$footprint = $footprints[$c] ;
			if ( $footprint["refer"] != "null" )
			{
				$url = preg_replace( "/hphp/i", "http", $footprint["refer"] ) ;
				$url_snap = ( strlen( $url ) > 130 ) ? substr( $url, 0, 130 ) . "..." : $url ;
				$json_data .= "{ \"total\": $footprint[total], \"url_snap\": \"$url_snap\", \"url_raw\": \"$url\" }," ;
			}
		}

		$json_data = substr_replace( $json_data, "", -1 ) ;
		$json_data .= "	] };" ;
	}
	else if ( $action == "add_eip" )
	{
		include_once( "../API/Util_Vals.php" ) ;

		$ip = Util_Format_Sanatize( Util_Format_GetVar( "ip" ), "ln" ) ;

		if ( !preg_match( "/$ip/", $VALS["TRAFFIC_EXCLUDE_IPS"] ) )
		{
			$val = preg_replace( "/  +/", " ", $VALS["TRAFFIC_EXCLUDE_IPS"] ) . " $ip " ;
			Util_Vals_WriteToFile( "TRAFFIC_EXCLUDE_IPS", $val ) ;
			$json_data = "json_data = { \"status\": 1 }; " ;
		}
		else
			$json_data = "json_data = { \"status\": 0 }; " ;
	}
	else if ( $action == "eips" )
	{
		$ips = explode( " ", $VALS['TRAFFIC_EXCLUDE_IPS'] ) ;

		$json_data = "json_data = { \"status\": 1, \"ips\": [ " ;
		for ( $c = 0; $c < count( $ips ); ++$c )
		{
			if ( preg_match( "/\d+/", $ips[$c] ) )
				$json_data .= "{ \"ip\": \"$ips[$c]\" }," ;
		}

		$json_data = substr_replace( $json_data, "", -1 ) ;
		$json_data .= "	] };" ;
	}
	else if ( $action == "remove_eip" )
	{
		include_once( "../API/Util_Vals.php" ) ;

		$ip = Util_Format_Sanatize( Util_Format_GetVar( "ip" ), "ln" ) ;

		$val = preg_replace( "/$ip /", "", preg_replace( "/  +/", " ", $VALS["TRAFFIC_EXCLUDE_IPS"] ) ) ;
		Util_Vals_WriteToFile( "TRAFFIC_EXCLUDE_IPS", $val ) ;

		$ips = explode( " ", $val ) ;

		$json_data = "json_data = { \"status\": 1, \"ips\": [ " ;
		for ( $c = 0; $c < count( $ips ); ++$c )
		{
			if ( preg_match( "/\d+/", $ips[$c] ) )
				$json_data .= "{ \"ip\": \"$ips[$c]\" }," ;
		}

		$json_data = substr_replace( $json_data, "", -1 ) ;
		$json_data .= "	] };" ;
	}
	else if ( $action == "remove_sip" )
	{
		include_once( "../API/Util_Vals.php" ) ;

		$ip = Util_Format_Sanatize( Util_Format_GetVar( "ip" ), "ln" ) ;

		$val = preg_replace( "/$ip /", "", preg_replace( "/  +/", " ", $VALS["CHAT_SPAM_IPS"] ) ) ;
		Util_Vals_WriteToFile( "CHAT_SPAM_IPS", $val ) ;

		$ips = explode( " ", $val ) ;

		$json_data = "json_data = { \"status\": 1, \"ips\": [ " ;
		for ( $c = 0; $c < count( $ips ); ++$c )
		{
			if ( preg_match( "/\d+/", $ips[$c] ) )
				$json_data .= "{ \"ip\": \"$ips[$c]\" }," ;
		}

		$json_data = substr_replace( $json_data, "", -1 ) ;
		$json_data .= "	] };" ;
	}
	else if ( $action == "sips" )
	{
		$ips = explode( " ", $VALS['CHAT_SPAM_IPS'] ) ;

		$json_data = "json_data = { \"status\": 1, \"ips\": [ " ;
		for ( $c = 0; $c < count( $ips ); ++$c )
		{
			if ( preg_match( "/\d+/", $ips[$c] ) )
				$json_data .= "{ \"ip\": \"$ips[$c]\" }," ;
		}

		$json_data = substr_replace( $json_data, "", -1 ) ;
		$json_data .= "	] };" ;
	}
	else if ( $action == "transcript_get" )
	{
		include_once( "../API/SQL.php" ) ;
		include_once( "../API/Chat/get_ext.php" ) ;

		$ces = Util_Format_Sanatize( Util_Format_GetVar( "ces" ), "ln" ) ;

		$transcript = Chat_ext_get_Transcript( $dbh, $ces ) ;
		$formatted = preg_replace( "/\"/", "&quot;", preg_replace( "/<>/", "", $transcript["formatted"] ) ) ;
		$json_data = "json_data = { \"status\": 1, \"transcript\": \"$formatted\" }; " ;
	}
	else if ( $action == "update_vars" )
	{
		include_once( "../API/Vars/put.php" ) ;

		$varname = Util_Format_Sanatize( Util_Format_GetVar( "varname" ), "ln" ) ;
		$value = Util_Format_Sanatize( Util_Format_GetVar( "value" ), "ln" ) ;

		if ( Vars_put_Var( $dbh, $varname, $value ) )
			$json_data = "json_data = { \"status\": 1 };" ;
		else
			$json_data = "json_data = { \"status\": 0 };" ;
	}
	else if ( $action == "update_profile" )
	{
		$email = Util_Format_Sanatize( Util_Format_GetVar( "email" ), "e" ) ;
		$login = Util_Format_Sanatize( Util_Format_GetVar( "login" ), "ln" ) ;
		$npassword = Util_Format_Sanatize( Util_Format_GetVar( "npassword" ), "ln" ) ;
		$vpassword = Util_Format_Sanatize( Util_Format_GetVar( "vpassword" ), "ln" ) ;

		LIST( $email, $login, $npassword, $vpassword ) = database_mysql_quote( $email, $login, $npassword, $vpassword ) ;

		$password_query = "" ;
		if ( $npassword )
		{
			$npassword = md5($npassword) ;
			$password_query = " , password = '$npassword' " ;
		}

		$query = "UPDATE p_admins SET login = '$login', email = '$email' $password_query WHERE adminID = $setupinfo[adminID]" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
			$json_data = "json_data = { \"status\": 1 };" ;
		else
			$json_data = "json_data = { \"status\": 0, \"error\": \"DB Error: $dbh[error]\" };" ;
	}
	else if ( $action == "remote_disconnect" )
	{
		include_once( "../API/Ops/update.php" ) ;

		$opid = Util_Format_Sanatize( Util_Format_GetVar( "opid" ), "ln" ) ;

		if ( Ops_update_OpValue( $dbh, $opid, "signall", 1 ) )
			$json_data = "json_data = { \"status\": 1 };" ;
		else
			$json_data = "json_data = { \"status\": 0, \"error\": \"DB Error: $dbh[error]\" };" ;
	}
	else
		$json_data = "json_data = { \"status\": 0, \"error\": \"Invalid action.\" };" ;

	if ( isset( $dbh ) && isset( $dbh['con'] ) )
		database_mysql_close( $dbh ) ;

	$json_data = preg_replace( "/\r\n/", "", $json_data ) ;
	$json_data = preg_replace( "/\t/", "", $json_data ) ;
	print "$json_data" ;
	exit ;
?>
