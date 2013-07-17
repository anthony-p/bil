<?php
	/*****  Ops_ext_::update
	 *
	 *  $Id: update.php,v 1.6 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION Ops_ext_update_AdminValue( &$dbh,
					  $adminid,
					  $tbl_name,
					  $value )
	{
		if ( ( $adminid == "" ) || ( $tbl_name == "" ) )
			return false ;
		
		LIST( $adminid, $tbl_name, $value ) = database_mysql_quote( $adminid, $tbl_name, $value ) ;

		$query = "UPDATE p_admins SET $tbl_name = '$value' WHERE adminID = $adminid" ;
		database_mysql_query( $dbh, $query ) ;
		
		if ( $dbh[ 'ok' ] )
			return true ;
		return false ;
	}

?>
