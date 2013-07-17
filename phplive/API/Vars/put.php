<?php
	/*****  Vars_::put
	 *
	 *  $Id: put.php,v 1.9 2008/12/09 04:16:56 osicodes Exp $
	 *
	 ****************************************************************/

	/****************************************************************/
	FUNCTION Vars_put_Var( &$dbh,
					$varname,
					$value )
	{
		if ( $varname == "" )
			return false ;

		LIST( $varname, $value ) = database_mysql_quote( $varname, $value ) ;

		$query = "SELECT * FROM p_vars" ;
		database_mysql_query( $dbh, $query ) ;
		$vars = database_mysql_fetchrow( $dbh ) ;

		if ( isset( $vars[$varname] ) )
			$query = "UPDATE p_vars SET $varname = '$value'" ;
		else
		{
			if ( $varname == "code" )
				$query = "INSERT INTO p_vars VALUES( '$value', '', '', '', '' )" ;
			else if ( preg_match( "/sm_/", $varname ) )
			{
				// insert blank first
				$query = "INSERT INTO p_vars VALUES( '', '', '', '', '' )" ;
				database_mysql_query( $dbh, $query ) ;
	
				$query = "UPDATE p_vars SET $varname = '$value'" ;
			}
		}
		database_mysql_query( $dbh, $query ) ;

		if ( $dbh[ 'ok' ] )
			return true ;

		return false ;
	}

?>
