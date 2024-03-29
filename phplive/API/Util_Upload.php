<?php
	include_once( realpath( "$CONF[DOCUMENT_ROOT]/API/Util_Vals.php" ) ) ;

	function Util_Upload_File( $icon, $deptid )
	{
		global $upload_dir ;
		global $CONF ;
		$now = time() ;
		$extension = $error = $filename = "" ;

		//ini_set( 'upload_tmp_dir', '$CONF[DOCUMENT_ROOT]/web/temp' ) ;

		if ( isset( $_FILES[$icon]['size'] ) )
		{
			$filesize = $_FILES[$icon]['size'] ;
			$filetype = $_FILES[$icon]['type'] ;
			$errorno = $_FILES[$icon]['error'] ;

			if ( $errorno == 6 )
				$error = "Upload temp dir not set or not writeable.  Check the value of \"upload_tmp_dir\" in the php.ini file." ;
			else if ( $errorno == 4 )
				$error = "Nothing to upload." ;
			else if ( $errorno )
				$error = "Error in uploading. [errorno: $errorno]" ;
			else
			{
				if ( preg_match( "/gif/i", $filetype ) )
					$extension = "GIF" ;
				else if ( preg_match( "/jpeg/i", $filetype ) )
					$extension = "JPEG" ;
				else if ( preg_match( "/png/i", $filetype ) )
					$extension = "PNG" ;

				if ( $extension )
				{
					if ( preg_match( "/(online)|(offline)|(initiate)|(logo)/", $icon ) )
					{
						$filename = $icon."_$deptid" ;

						/* does not work on some servers [ disabled ]
						foreach ( glob("$upload_dir/$filename*") as $file_ )
							unlink( $file_ ) ;
						*/

						/*
						$dh = opendir( $upload_dir ) ;
						while( $file = readdir( $dh ) )
						{
							if ( preg_match( "/$filename/", $file ) )
								unlink( "$upload_dir/$file" ) ;
						}
						closedir($dh) ;
						*/

						if ( file_exists( "$upload_dir/$filename.PNG" ) )
							unlink( "$upload_dir/$filename.PNG" ) ;
						else if ( file_exists( "$upload_dir/$filename.JPEG" ) )
							unlink( "$upload_dir/$filename.JPEG" ) ;
						else if ( file_exists( "$upload_dir/$filename.GIF" ) )
							unlink( "$upload_dir/$filename.GIF" ) ;

						$filename = $icon."_$deptid.$extension" ;
					}
					else
						$filename = "$icon.$extension" ;

					if( move_uploaded_file( $_FILES[$icon]['tmp_name'], "$upload_dir/$filename" ) )
					{
						chmod( "$upload_dir/$filename", 0777 ) ;
						if ( preg_match( "/(online)|(offline)|(logo)/", $icon ) && !$deptid )
							$error = Util_Vals_WriteToConfFile( $icon, $filename ) ;
					}
					else
						$error = "Could not process uploading of files." ;
				}
				else
					$error = "Please provide a valid image file.  GIF, PNG or JPEG formats only." ;
			}
		}
		else
			$error = "Please provide a valid image file.  GIF, PNG or JPEG formats only." ;

		return $error ;
	}

	function Util_Upload_GetChatIcon( $base_url, $prefix, $deptid )
	{
		global $CONF ;
		global $upload_dir ;

		$now = time() ;

		if ( file_exists( realpath( "$upload_dir/$prefix"."_$deptid.GIF" ) ) )
			return "$base_url/web/$prefix"."_$deptid.GIF?".$now ;
		else if ( file_exists( realpath( "$upload_dir/$prefix"."_$deptid.JPEG" ) ) )
			return "$base_url/web/$prefix"."_$deptid.JPEG?".$now ;
		else if ( file_exists( realpath( "$upload_dir/$prefix"."_$deptid.PNG" ) ) )
			return "$base_url/web/$prefix"."_$deptid.PNG?".$now ;
		else if ( file_exists( realpath( "$upload_dir/$CONF[$prefix]" ) ) && $CONF["$prefix"] )
			return "$base_url/web/$CONF[$prefix]?".$now ;
		else
			return "$base_url/pics/icons/$prefix".".gif" ;
	}

	function Util_Upload_GetLogo( $base_url, $deptid )
	{
		global $CONF ;
		global $upload_dir ;
		global $theme ;

		if ( isset( $theme ) && $theme )
			$local_theme = $theme ;
		else
			$local_theme = $CONF["THEME"] ;

		$now = time() ;

		if ( file_exists( realpath( "$upload_dir/logo"."_$deptid.GIF" ) ) )
			return "$base_url/web/logo"."_$deptid.GIF?".$now ;
		else if ( file_exists( realpath( "$upload_dir/logo"."_$deptid.JPEG" ) ) )
			return "$base_url/web/logo"."_$deptid.JPEG?".$now ;
		else if ( file_exists( realpath( "$upload_dir/logo"."_$deptid.PNG" ) ) )
			return "$base_url/web/logo"."_$deptid.PNG?".$now ;
		else if ( file_exists( realpath( "$CONF[DOCUMENT_ROOT]/themes/$local_theme/logo.png" ) ) )
			return "$base_url/themes/$local_theme/logo.png?".$now ;
		else
			return "$base_url/pics/logo.png" ;
	}
?>
