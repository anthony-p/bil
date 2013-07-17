<?php if (@$gsExport == "email") ob_clean(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title></title>
<?php if (@$gsExport == "") { ?>
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.0/build/button/assets/skins/sam/button.css" />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.0/build/container/assets/skins/sam/container.css" />
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print" || @$gsExport == "email") { ?>
<link rel="stylesheet" type="text/css" href="<?php echo EWRPT_PROJECT_STYLESHEET_FILENAME ?>" />
<?php } ?>
<meta name="generator" content="PHP Report Maker v4.0.0.2" />
</head>
<body class="yui-skin-sam">
<?php if (@$gsExport == "" || @$gsExport == "print" || @$gsExport == "email") { ?>
<script type="text/javascript" src="http://yui.yahooapis.com/2.8.0/build/utilities/utilities.js"></script>
<?php } ?>
<?php if (@$gsExport == "") { ?>
<script type="text/javascript" src="http://yui.yahooapis.com/2.8.0/build/button/button-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.8.0/build/container/container-min.js"></script>
<script type="text/javascript">
<!--
var EWRPT_LANGUAGE_ID = "<?php echo $gsLanguage ?>";
var EWRPT_DATE_SEPARATOR = "/";
if (EWRPT_DATE_SEPARATOR == "") EWRPT_DATE_SEPARATOR = "/"; // Default date separator

//var EWRPT_EMAIL_EXPORT_BUTTON_SUBMIT_TEXT = "<?php echo ewrpt_EscapeJs(ewrpt_BtnCaption($ReportLanguage->Phrase("SendEmailBtn"))) ?>";
//var EWRPT_BUTTON_CANCEL_TEXT = "<?php echo ewrpt_EscapeJs(ewrpt_BtnCaption($ReportLanguage->Phrase("CancelBtn"))) ?>";

var EWRPT_MAX_EMAIL_RECIPIENT = <?php echo EWRPT_MAX_EMAIL_RECIPIENT ?>;

//-->
</script>
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print" || @$gsExport == "email") { ?>
<script type="text/javascript" src="phprptjs/ewrpt.js"></script>
<script src="phprptjs/x.js" type="text/javascript"></script>
<?php } ?>
<?php if (@$gsExport == "") { ?>
<script type="text/javascript">
<!--
<?php echo $ReportLanguage->ToJSON() ?>

//-->
</script>
<script type="text/javascript">
var EWRPT_IMAGES_FOLDER = "phprptimages";
</script>
<div class="ewLayout">
	<!-- header (begin) --><!-- *** Note: Only licensed users are allowed to change the logo *** -->
	<div class="ewHeaderRow"><img src="/images/bringitlocalogo.gif" alt="" border="0" /></div>
	<!-- header (end) -->
	<!-- content (begin) -->
	<!-- navigation -->
	<table cellspacing="0" class="ewContentTable">
		<tr>	
			<td class="ewMenuColumn">

			<!-- left column (end) -->
			</td>
			<td class="ewContentColumn">
<?php } ?>
