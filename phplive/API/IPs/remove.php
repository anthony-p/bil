<?php
	/*****  IPs_::remove
	 *
	 *  $Id: remove.php,v 1.5 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION IPs_remove_Expired_U( &$dbh )
	{
		global $VARS_FOOTPRINT_EXPIRE ;

		$expired = time() - $VARS_FOOTPRINT_EXPIRE ;
		$query = "DELETE FROM p_IPs_u WHERE updated < $expired" ;
		database_mysql_query( $dbh, $query ) ;

		return true ;
	}
?>
