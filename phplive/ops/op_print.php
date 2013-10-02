<?php
	/****************************************/
	// STANDARD header for Setup
	include_once( "../web/config.php" ) ;
	include_once( "../API/Util_Format.php" ) ;
	include_once( "../API/Util_Error.php" ) ;
	include_once( "../API/SQL.php" ) ;
	include_once( "../API/Util_Security.php" ) ;
	include_once( "../lang_packs/$CONF[lang].php" ) ;
	include_once( "../API/Depts/get.php" ) ;
	include_once( "../API/Ops/get.php" ) ;
	include_once( "../API/Chat/Util.php" ) ;

	$ces = Util_Format_Sanatize( Util_Format_GetVar( "ces" ), "ln" ) ;
	$deptid = Util_Format_Sanatize( Util_Format_GetVar( "deptid" ), "ln" ) ;
	$opid = Util_Format_Sanatize( Util_Format_GetVar( "opid" ), "ln" ) ;

	$department = Depts_get_DeptInfo( $dbh, $deptid ) ;
	$operator = Ops_get_OpInfoByID( $dbh, $opid ) ;

	$output = UtilChat_ExportChat( "$ces.txt" ) ;
	if ( count( $output ) <= 0 )
	{
		include_once( "../API/Chat/get_ext.php" ) ;

		$transcript = Chat_ext_get_Transcript( $dbh, $ces ) ;
		$output[] = $transcript["formatted"] ;
		$output[] = $transcript["plain"] ;
	}

	if ( isset( $output[0] ) )
		$output[0] = preg_replace( "/\"/", "&quot;", $output[0] ) ;
?>
<?php include_once( "../inc_doctype.php" ) ?>
<head>
<title> PHP Live! Support <?php echo $VERSION ?> (Chat Transcript) </title>

<meta name="description" content="PHP Live! Support <?php echo $VERSION ?>">
<meta name="keywords" content="PHP Live! Support">
<meta name="robots" content="all,index,follow">
<meta http-equiv="content-type" content="text/html; CHARSET=utf-8"> 

<link rel="Stylesheet" href="../themes/<?php echo $CONF["THEME"] ?>/style.css">
<script type="text/javascript" src="../js/global.js"></script>
<script type="text/javascript" src="../js/global_chat.js"></script>
<script type="text/javascript" src="../js/framework.js"></script>
<script type="text/javascript" src="../js/tooltip.js"></script>

<script type="text/javascript">
<!--
	var mouse_x ; var mouse_y ;
	var view = 2 ; // flag used in global_chat.js for minor formatting of divs
	var base_url = ".." ;

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		}) ;

		$('#chat_body').append( init_timestamps( "<?php echo $output[0] ?>" ) ) ;
	});

	function do_print()
	{
		$('#chat_body').focus() ;
		window.print() ;
	}
//-->
</script>
</head>
<body id="chat_body" style="overflow: auto; padding: 0px;">
<div id="chat_options">
	<div style="margin-bottonm: 10px;" class="info_box">
		<div id="options_print" style="cursor: pointer; font-size: 16px; font-weight: bold;" onClick="do_print()"><img src="../themes/<?php echo $CONF["THEME"] ?>/printer.png" width="16" height="16" border="0" alt=""> <?php echo LANG_CHAT_PRINT ?></div>
		<div style="padding-top: 5px; font-size: 12px;">Apple computers, select the "File"-&gt;"Print" from the desktop navigation menu bar to print.</div>
	</div>
	<div class="cn"><?php echo LANG_CHAT_CHAT_WITH ?> <span class="text_operator" style="font-weight: bold;"><?php echo $operator["name"] ?></span> - <span class="text_department" style="font-weight: bold;"><?php echo $department["name"] ?></span></div>
</div>

</body>
</html>
<?php database_mysql_close( $dbh ) ; ?>
