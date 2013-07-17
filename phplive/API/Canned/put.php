<?php
	/*****  Canned_::put
	 *
	 *  $Id: put.php,v 1.9 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION Canned_put_Canned( &$dbh,
					$canid,
					$opid,
					$deptid,
					$title,
					$message )
	{
		if ( ( $opid == "" ) || ( $deptid == "" )  || ( $title == "" )
			|| ( $message == "" ) )
			return false ;

		LIST( $canid, $opid, $deptid, $title, $message ) = database_mysql_quote( $canid, $opid, $deptid, $title, $message ) ;

		$query = "REPLACE INTO p_canned VALUES( $canid, $opid, $deptid, '$title', '$message' )" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$id = database_mysql_insertid ( $dbh ) ;
			return $id ;
		}

		return false ;
	}

?>
