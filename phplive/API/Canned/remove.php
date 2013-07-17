<?php
	/*****  Canned_::remove
	 *
	 *  $Id: remove.php,v 1.5 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION Canned_remove_Canned( &$dbh,
						$opid,
						$canid )
	{
		if ( ( $opid == "" ) || ( $canid == "" ) )
			return false ;

		LIST( $canid ) = database_mysql_quote( $canid ) ;

		$query = "DELETE FROM p_canned WHERE canID = $canid AND opID = $opid" ;
		database_mysql_query( $dbh, $query ) ;

		return true ;
	}

?>
