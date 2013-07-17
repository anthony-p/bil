<?php
	//
	// Auto patching of PHP Live! system
	//

	$document_root = preg_replace( "/[\$\!`\"<>'\?;]|(\.\.)/", "", $CONF["DOCUMENT_ROOT"] ) ;

	/* auto patch of versions and needed modifications */
	if ( !file_exists( realpath( "$document_root/web/patches/1" ) ) )
	{
		$query = "ALTER TABLE p_operators CHANGE signal signall TINYINT( 4 ) NOT NULL" ;
		database_mysql_query( $dbh, $query ) ;
		$query = "ALTER TABLE p_footprints_u ADD resolution VARCHAR( 15 ) NOT NULL AFTER browser" ;
		database_mysql_query( $dbh, $query ) ;

		touch( "$document_root/web/patches/1" ) ;
	}
	else if ( !file_exists( realpath( "$document_root/web/patches/2" ) ) )
	{
		$query = "ALTER TABLE p_requests ADD etrans TINYINT( 1 ) NOT NULL AFTER status" ;
		database_mysql_query( $dbh, $query ) ;
		$query = "ALTER TABLE p_req_log ADD etrans TINYINT( 1 ) NOT NULL AFTER status" ;
		database_mysql_query( $dbh, $query ) ;

		touch( "$document_root/web/patches/2" ) ;
	}
	else if ( !file_exists( realpath( "$document_root/web/patches/3" ) ) )
	{
		$query = "ALTER TABLE p_requests ADD tupdated INT NOT NULL AFTER created" ;
		database_mysql_query( $dbh, $query ) ;

		touch( "$document_root/web/patches/3" ) ;
	}
	else if ( !file_exists( realpath( "$document_root/web/patches/4" ) ) )
	{
		$query = "ALTER TABLE p_vars ADD sm_fb TEXT NOT NULL, ADD sm_tw TEXT NOT NULL, ADD sm_yt TEXT NOT NULL, ADD sm_li TEXT NOT NULL " ;
		database_mysql_query( $dbh, $query ) ;

		touch( "$document_root/web/patches/4" ) ;
	}
	else if ( !file_exists( realpath( "$document_root/web/patches/5" ) ) )
	{
		$query = "ALTER TABLE p_requests ADD initiated TINYINT( 1 ) NOT NULL AFTER status" ;
		database_mysql_query( $dbh, $query ) ;
		$query = "ALTER TABLE p_req_log ADD initiated TINYINT( 1 ) NOT NULL AFTER status" ;
		database_mysql_query( $dbh, $query ) ;
		$query = "ALTER TABLE p_footprints_u ADD chatting TINYINT( 1 ) NOT NULL AFTER marketID" ;
		database_mysql_query( $dbh, $query ) ;
		$query = "ALTER TABLE p_reqstats ADD initiated_taken SMALLINT UNSIGNED NOT NULL AFTER initiated" ;
		database_mysql_query( $dbh, $query ) ;
		$query = "ALTER TABLE p_transcripts ADD initiated TINYINT( 1 ) NOT NULL AFTER opID" ;
		database_mysql_query( $dbh, $query ) ;
		$query = "ALTER TABLE p_footprints_u ADD agent VARCHAR( 200 ) NOT NULL AFTER hostname" ;
		database_mysql_query( $dbh, $query ) ;

		touch( "$document_root/web/patches/5" ) ;
	}
	/* end auto patch area */
?>
