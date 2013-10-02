<?php
	/////////////////////////////////////////
	//
	// Copyright OSI Codes Inc.
	//
	/////////////////////////////////////////

	$connection = mysql_connect( $CONF["SQLHOST"], $CONF["SQLLOGIN"], $CONF["SQLPASS"] ) ;
	if ( mysql_select_db( $CONF["DATABASE"] ) )
		$dbh['con'] = $connection ;
	else
		ErrorHandler ( 600, "DB Connection Failed", "$_SERVER[HTTP_HOST]/$_SERVER[REQUEST_URI]", 0, Array() ) ;

	// The &$dbh is passed by reference so you can call it
	// anywhere in the code.  you can always access
	// the $dbh[error] to see the most recent db error.
	function database_mysql_query( &$dbh, $query )
	{
		$dbh['ok'] = 0 ;
		$dbh['result'] = 0 ;
		$dbh['error'] = "None" ; 
		$dbh['query'] = $query ;

		$result = mysql_query( $query, $dbh['con'] ) ;
		if ( $result )
		{
			$dbh['result'] = $result ;
			$dbh['ok'] = 1 ;
			$dbh['error'] = "None" ;
		}
		else
		{
			$dbh['result'] = 0 ;
			$dbh['ok'] = 0 ;
			$dbh['error'] = mysql_error() ;
		}
	}

	function database_mysql_fetchrow( &$dbh )
	{
		$result = mysql_fetch_array( $dbh['result'] ) ;
		return $result ;
	}

	function database_mysql_insertid( &$dbh )
	{
		$id = mysql_insert_id( $dbh['con'] ) ;
		return $id ;
	}

	function database_mysql_nresults( &$dbh )
	{
		$total = mysql_affected_rows( $dbh['con'] ) ;
		return $total ;
	}

	function database_mysql_quote()
	{
		$output = Array() ;
		for ( $i = 0; $i < func_num_args(); $i++ )
			$output[] = mysql_real_escape_string( stripslashes( func_get_arg( $i ) ) ) ;
		return $output ;
	}

	function database_mysql_close( &$dbh )
	{
		if ( mysql_close( $dbh['con'] ) )
			return true ;
		return false ;
	}

?>
