<?php
	function Util_Format_Sanatize( $string, $flag )
	{
		switch ( $flag )
		{
			case ( "a" ):
				return ( is_array( $string ) ) ? $string : Array() ;
				break ;
			case ( "ln" ):
				return preg_replace( "/[\$%=<>\(\)\[\]\|\{\}]/i", "", trim( $string, "\x00" ) ) ;
				break ;
			case ( "e" ):
				return preg_replace( "/[^a-z0-9_.\-@]/i", "", trim( $string, "\x00" ) ) ;
				break ;
			case ( "v" ):
				return preg_replace( "/( )|(%20)|(%00)|(%3Cv%3E)|(<v>)/", "", trim( $string, "\x00" ) ) ;
				break ;
			case ( "base_url" ):
				return preg_replace( "/[\$\!`\"<>'\(\)\?; ]/i", "", preg_replace( "/hphp/i", "http", trim( $string, "\x00" ) ) ) ;
				break ;
			case ( "url" ):
				return preg_replace( "/[\$\!`\"<>'\(\);]/i", "", preg_replace( "/hphp/i", "http", trim( $string, "\x00" ) ) ) ;
				break ;
			case ( "title" ):
				return preg_replace( "/[\$=\!<>;]/i", "", preg_replace( "/hphp/i", "http", trim( $string, "\x00" ) ) ) ;
				break ;
			case ( "htmltags" ):
				return Util_Format_ConvertTags( trim( $string, "\x00" ) ) ;
				break ;
			case ( "notags" ):
				return strip_tags( trim( $string, "\x00" ) ) ;
				break ;
			case ( "htmle" ):
				return htmlentities( trim( $string, "\x00" ) ) ;
				break ;
			default:
			{
				return trim( $string, "\x00" ) ;
			}
		}
	}

	function Util_Format_ConvertTags( $string )
	{
		$string = preg_replace( "/</", "&lt;", $string ) ; 
		$string = preg_replace( "/>/", "&gt;", $string ) ;
		return $string ;
	}

	function Util_Format_Bytes( $bytes )
	{
		$string = "" ;

		$kils = round ( $bytes/1000 ) ;
		$kil_re = ( $bytes % 1000 ) ;

		if ( $kils >= 1000 )
		{
			$megs = floor ( $kils/1000 ) ;
			$meg_re = ( $kils % 1000 ) ;
			$meg_per = $meg_re/1000 ;
			$megs_final = $megs + $meg_per ;
			$string = "$megs_final M" ;
		}
		elseif ( ( $bytes < 1000 ) && ( $bytes ) )
			$string = "$bytes byte" ;
		else if ( $bytes )
			$string = "$kils k" ;

		return $string ;
	}

	function Util_Format_isMobile( $thisagent = "" )
	{
		$thisagent = ( $thisagent == "" ) ? $_SERVER["HTTP_USER_AGENT"] : $thisagent ;
		if( preg_match('/android.+mobile|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$thisagent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($thisagent,0,4)) )
			return 1 ;
		else
			return 0 ;
	}

	function Util_Format_Duration( $duration )
	{
		$string = $minutes = $hours = "" ;

		$minutes = round( $duration/60 ) ;
		if ( $minutes >= 60 )
		{
			$hours = floor( $minutes/60 ) ;
			$minutes = $minutes % 60 ;
			$string = "$hours h $minutes min" ;
		}
		else
			$string = "$minutes min" ;

		return $string ;
	}

	function Util_Format_Page( $page, $index, $page_per, $total, $url, $query )
	{
		global $text ;
		if ( !isset( $text ) )
			$text = "" ;
		$string = "" ;
		
		$string .= "<div class=\"page_focus\">Page: </div>" ;
		$pages = $remainder = 0 ;

		$remainder = ( $total % $page_per ) ;
		$pages = floor( $total/$page_per ) ;
		$pages = ( $remainder ) ? $pages + 1 : $pages ;

		$span = 10 ;
		$remainder = ( $pages % $span ) ;
		$groups = floor( $pages/$span ) ;
		$groups = ( $remainder ) ? $groups + 1 : $groups ;
		$start = ( $index * $span ) ;
		$end = $start + $span ;

		$group_prev = "" ;
		if ( $index > 0 )
		{
			$c = $start - $span ;
			$new_index = $index - 1 ;
			$group_prev = "<div class=\"page\" onClick=\"location.href='$url?page=$c&index=$new_index&$query'\">...prev</div>" ;
		}

		$group_next = "" ;
		if ( $index < ( $groups - 1 ) )
		{
			$c = $end ;
			$new_index = $index + 1 ;
			$group_next = "<div class=\"page\" onClick=\"location.href='$url?page=$c&index=$new_index&$query'\">next...</div>" ;
		}

		$string .= $group_prev ;
		for ( $c = $start; $c < $end; ++$c )
		{
			if ( $c < $pages )
			{
				$this_page = $c + 1 ;

				if ( $c == $page )
					$string .= "<div class=\"page_focus\">$this_page</div>" ;
				else
					$string .= "<div class=\"page\" onClick=\"location.href='$url?page=$c&index=$index&$query'\">$this_page</div>" ;
			}
		}
		$string .= $group_next ;

		if ( preg_match( "/(op_trans.php)|(transcripts.php)/", $url ) )
			$string .= "<div style=\"float: left; padding-left: 10px;\"><form method=\"POST\" onSubmit=\"return false;\" id=\"form_search\">Search: <input type=\"text\" class=\"input_text\" size=\"25\" maxlength=\"25\" style=\"font-size: 10px;\" id=\"input_search\" value=\"$text\" onKeydown=\"input_text_listen_search(event);\"> <input type=\"button\" id=\"btn_page_search\" style=\"font-size: 10px;\" class=\"input_button\" value=\"go\" onClick=\"do_search('$url?$query')\"> <input type=\"button\" style=\"font-size: 10px;\" class=\"input_button\" value=\"reset\" onClick=\"location.href=reset_url\"></form></div>" ;

		$string .= "<div style=\"clear: both;\"></div>" ;

		return $string ;
	}

	function Util_Format_GetVar( $varname )
	{
		$varout = 0 ;

		// is_string

		if ( isset( $_REQUEST[$varname] ) )
			$varout = $_REQUEST[$varname] ;

		if ( get_magic_quotes_gpc() && !is_array( $varout ) )
			$varout = stripslashes( $varout ) ;
		return $varout ;
	}

	function Util_Format_GetOS( $agent )
	{
		if ( Util_Format_isMobile( $agent ) ) { $os = 5 ; }
		else if ( preg_match( "/Windows/i", $agent ) ) { $os = 1 ; }
		else if ( preg_match( "/Mac/i", $agent ) ) { $os = 2 ; }
		else { $os = 4 ; }

		if ( preg_match( "/MSIE/i", $agent ) ) { $browser = 1 ; }
		else if ( preg_match( "/Firefox/i", $agent ) ) { $browser = 2 ; }
		else if ( preg_match( "/Chrome/i", $agent ) ) { $browser = 3 ; }
		else if ( preg_match( "/Safari/i", $agent ) ) { $browser = 4 ; }
		else { $browser = 6 ; }

		return Array( $os, $browser ) ;
	}

	function Util_Format_Stars( $rating )
	{
		global $opinfo ;

		$base_url = Util_Format_IsProtoHttps( 1 ) ;
		$theme = ( isset( $opinfo["theme"] ) ) ? $opinfo["theme"] : "default" ;
		$star_img = "$base_url/themes/$theme/stars.png" ;

		$output = "<div style='width: 60px;'>" ;
		for ( $c = 1; $c <= $rating; ++$c )
			$output .= "<div style='float: left; width: 12px; height: 12px; background: url( $star_img ) no-repeat; background-position: 0px -12px;'></div>" ;
		for ( $c2 = $c; $c2 <= 5; ++$c2 )
			$output .= "<div style='float: left; width: 12px; height: 12px; background: url( $star_img ) no-repeat;'></div>" ;
		$output .= "<div style='clear: both;'></div></div>" ;
		
		return $output ;
	}

	function Util_Format_RandomString( $length = 5, $chars = '23456789abcdefghjkmnpqrstuvwxyz')
	{
		$charLength = strlen($chars)-1;

		$randomString = "";
		for($i = 0 ; $i < $length ; $i++)
			$randomString .= $chars[mt_rand(0,$charLength)];

		return $randomString;
	}

	function Util_Format_IsProtoHttps( $theflag )
	{
		$proto = 0 ;
		if ( isset( $_SERVER["HTTPS"] ) && preg_match( "/on/i", $_SERVER["HTTPS"] ) )
			$proto = 1 ;

		if ( $theflag )
		{
			global $CONF ;

			if ( $proto )
				return preg_replace( "/http:\/\//", "https://", $CONF["BASE_URL"] ) ;
			else
				return $CONF["BASE_URL"] ;
		}
		else
			return $proto ;
	}

	function Util_Format_DEBUG( $string )
	{
		global $CONF ;
		file_put_contents( "$CONF[DOCUMENT_ROOT]/web/debug.txt", $string, FILE_APPEND ) ;
	}
?>
