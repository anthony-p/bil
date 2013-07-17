<?php
	/*****  Vars_::remove
	 *
	 *  $Id: remove.php,v 1.5 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION Vars_remove_External( &$dbh,
						$extid )
	{
		if ( $extid == "" )
			return false ;

		LIST( $extid ) = database_mysql_quote( $extid ) ;

		$query = "DELETE FROM p_external WHERE extID = $extid" ;
		database_mysql_query( $dbh, $query ) ;
		$query = "DELETE FROM p_ext_ops WHERE extID = $extid" ;
		database_mysql_query( $dbh, $query ) ;

		return true ;
	}

	/****************************************************************/
	FUNCTION Vars_remove_AllExtOps( &$dbh,
						$extid )
	{
		if ( $extid == "" )
			return false ;

		LIST( $extid ) = database_mysql_quote( $extid ) ;

		$query = "DELETE FROM p_ext_ops WHERE extID = $extid" ;
		database_mysql_query( $dbh, $query ) ;

		return true ;
	}

?>
