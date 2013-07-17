<?php
	/****************************************/
	// STANDARD header for Setup
	if ( !file_exists( "../web/config.php" ) ){ HEADER("location: install.php") ; exit ; }
	include_once( "../web/config.php" ) ;
	include_once( "../API/Util_Format.php" ) ;
	include_once( "../API/Util_Error.php" ) ;
	include_once( "../API/SQL.php" ) ;
	include_once( "../API/Util_Security.php" ) ;
	$ses = Util_Format_Sanatize( Util_Format_GetVar( "ses" ), "ln" ) ;
	if ( !$admininfo = Util_Security_AuthSetup( $dbh, $ses ) ){ ErrorHandler ( 602, "Invalid setup session or session has expired.", "$_SERVER[HTTP_HOST]/$_SERVER[REQUEST_URI]", 0, Array() ) ; }
	// STANDARD header end
	/****************************************/

	$error = "" ;
?>
<?php include_once( "../inc_doctype.php" ) ?>
<head>
<title> PHP Live! Support <?php echo $VERSION ?> </title>

<meta name="description" content="PHP Live! Support <?php echo $VERSION ?>">
<meta name="keywords" content="PHP Live! Support">
<meta name="robots" content="all,index,follow">
<meta http-equiv="content-type" content="text/html; CHARSET=utf-8"> 

<link rel="Stylesheet" href="../css/base_setup.css">
<script type="text/javascript" src="../js/global.js"></script>
<script type="text/javascript" src="../js/setup.js"></script>
<script type="text/javascript" src="../js/framework.js"></script>

<script type="text/javascript">
<!--
	var mouse_x ; var mouse_y ;

	var st_optimize ;

	$(document).ready(function()
	{
		$(document).mousemove(function(e){
			mouse_x = e.pageX ; mouse_y = e.pageY ;
		}) ;

		$('#body_sub_title').html( "<img src=\"../pics/icons/asterisk.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" style=\"margin-right: 5px;\"> Optimize" ) ;

		init_menu() ;
		optimize() ;
	});

	function optimize()
	{
		$.ajax({
			type: "GET",
			url: "../ajax/setup_actions.php",
			data: "ses=<?php echo $ses ?>&action=optimize&"+unixtime(),
			success: function(data){
				eval( data ) ;

				if ( json_data.status == 1 )
				{
					st_optimize = setTimeout(function(){ optimize() ; }, 1000) ;
					$('#txt_status').html( "Please hold.  Optimizing data for day: "+json_data.date+"..." ) ;
				}
				else
				{
					if ( typeof( st_optimize ) != "undefined" )
						clearTimeout( st_optimize ) ;
					
					$('#txt_status').hide() ;
					$('#txt_status_complete').html( "<img src=\"../pics/icons/alert_good.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"\"> COMPLETE!  Loading setup area..." ).show() ;
					setTimeout(function(){ location.href = "index.php?ses=<?php echo $ses ?>" ; }, 5000) ;
				}
			}
		});
	}
//-->
</script>
</head>
<body>

<?php include_once( "./inc_header.php" ) ?>

<div id="body_main" style="padding: 10px; padding-bottom: 20px;  background: #FFFFFF; min-height: 80px;">
	<div id="title_txt"></div>
</div>

<div style="padding: 5px;">
	<?php include_once( "./inc_footer.php" ) ?>
</div>

<div style="position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; background: url( ../pics/bg_trans.png ) repeat; overflow: hidden; z-index: 20;">
	<div style="position: relative; width: 940px; margin: 0 auto; top: 100px;">
		<div class="info_box">
			<div style="margin-bottom: 5px; font-size: 22px; font-weight: bold;">Optimizing your database and stat data.  Please hold... <img src="../pics/loading_bar.gif" border="0" alt=""></div>
			To keep your database running smoothly, an automated optimzation is in progress.  You will be redirected to the setup area once this operation has been completed.
			<div id="txt_status" style="margin-top: 25px; padding-bottom: 25px; font-size: 16px; font-weight: bold;"></div>
			<div id="txt_status_complete" style="display: none; margin-top: 25px; padding-bottom: 25px; font-size: 16px; font-weight: bold; cursor: pointer;" onClick="location.href='index.php?ses=<?php echo $ses ?>'"></div>
			
		</div>
	</div>
</div>

</body>
</html>
