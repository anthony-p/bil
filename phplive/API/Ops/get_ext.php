<?php
	/*****  Ops_ext_::get
	 *
	 *  $Id: get.php,v 1.8 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION Ops_ext_get_AdminInfoByLoginPass( &$dbh,
					$login,
					$password )
	{
		if ( ( $login == "" ) || ( $password == "" ) )
			return false ;

		LIST( $login, $password ) = database_mysql_quote( $login, md5( $password ) ) ;

		$query = "SELECT * FROM p_admins WHERE login = '$login' AND password = '$password'" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}

	/****************************************************************/
	FUNCTION Ops_ext_get_OpInfoByLoginPass( &$dbh,
					$login,
					$password )
	{
		if ( ( $login == "" ) && ( $password == "" ) )
			return false ;

		LIST( $login, $password ) = database_mysql_quote( $login, md5( $password ) ) ;

		$query = "SELECT * FROM p_operators WHERE login = '$login' AND password = '$password'" ;
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
		{
			$data = database_mysql_fetchrow( $dbh ) ;
			return $data ;
		}
		return false ;
	}
?>
