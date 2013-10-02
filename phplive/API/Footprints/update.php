<?php
	/*****  Footprints_::update
	 *
	 *  $Id: update.php,v 1.6 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION Footprints_update_FootprintUniqueValue( &$dbh,
					  $ip,
					  $tbl_name,
					  $value )
	{
		if ( ( $ip == "" ) || ( $tbl_name == "" ) )
			return false ;

		LIST( $ip, $tbl_name, $value ) = database_mysql_quote( $ip, $tbl_name, $value ) ;

		$query = "UPDATE p_footprints_u SET $tbl_name = '$value' WHERE ip = '$ip'" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$flag = database_mysql_nresults( $dbh ) ;
			return $flag ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Footprints_update_Footprint_UOnpage( &$dbh,
					  $ip,
					  $onpage,
					  $title )
	{
		if ( ( $onpage == "" ) || ( $title == "" ) )
			return false ;

		$now = time() ;
		LIST( $ip, $onpage, $title ) = database_mysql_quote( $ip, $onpage, $title ) ;

		$query = "UPDATE p_footprints_u SET updated = $now, onpage = '$onpage', title = '$title' WHERE ip = '$ip'" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$flag = database_mysql_nresults( $dbh ) ;
			return $flag ;
		}
		return false ;
	}

?>
