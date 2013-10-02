<?php
	/*****  External_::put
	 *
	 *  $Id: put.php,v 1.9 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION External_put_External( &$dbh,
					$extid,
					$name,
					$url )
	{
		if ( ( $name == "" ) || ( $url == "" ) )
			return false ;

		LIST( $extid, $name, $url ) = database_mysql_quote( $extid, $name, $url ) ;

		$query = "SELECT * FROM p_external WHERE name = '$name'" ;
		database_mysql_query( $dbh, $query ) ;
		$external = database_mysql_fetchrow( $dbh ) ;

		if ( isset( $external["extID"] ) && ( $external["name"] == $name ) )
		{
			if ( $external["extID"] != $extid )
				return false ;
		}

		$query = "REPLACE INTO p_external VALUES ( $extid, '$name', '$url' )" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$id = database_mysql_insertid ( $dbh ) ;
			return $id ;
		}

		return false ;
	}

	/****************************************************************/
	FUNCTION External_put_ExtOp( &$dbh,
					$extid,
					$opid )
	{
		if ( ( $extid == "" ) || ( $opid == "" ) )
			return false ;

		LIST( $extid, $opid ) = database_mysql_quote( $extid, $opid ) ;

		$query = "REPLACE INTO p_ext_ops VALUES ( $extid, $opid )" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
			return true ;

		return false ;
	}

?>
