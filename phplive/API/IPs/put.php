<?php
	/*****  IPs_::put
	 *
	 *  $Id: put.php,v 1.9 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION IPs_put_IP( &$dbh,
					$ip,
					$t_footprints,
					$t_requests,
					$t_initiate )
	{
		if ( $ip == "" )
			return false ;

		global $CONF ;
		include_once( realpath( "$CONF[DOCUMENT_ROOT]/API/IPs/get.php" ) ) ;

		$now = time() ;
		$ipinfo = IPs_get_IPInfo( $dbh, $ip ) ;

		LIST( $ip, $t_footprints, $t_requests, $t_initiate ) = database_mysql_quote( $ip, $t_footprints, $t_requests, $t_initiate ) ;

		if ( isset( $ipinfo["ip"] ) )
		{
			$t_footprints = $ipinfo["t_footprints"] + $t_footprints ;
			$t_requests = $ipinfo["t_requests"] + $t_requests ;
			$t_initiate = $ipinfo["t_initiate"] + $t_initiate ;
		}

		$query = "REPLACE INTO p_ips VALUES ( '$ip', $now, $t_footprints, $t_requests, $t_initiate )" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
			return true ;

		return false ;
	}

?>
