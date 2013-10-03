<?php if (@$gsExport == "email" || @$gsExport == "pdf") ob_clean(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title><?php echo $ReportLanguage->ProjectPhrase("BodyTitle") ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php if (@$gsExport == "") { ?>
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print" || @$gsExport == "email" && @$giFcfChartCnt > 0) { ?>
<script type="text/javascript">
var EWR_YUI_HOST = "<?php echo ewr_YuiHost() ?>";

function ewr_GetScript(url) { document.write("<" + "script type=\"text/javascript\" src=\"" + url + "\"><" + "/script>"); }

function ewr_GetCss(url) { document.write("<link rel=\"stylesheet\" type=\"text/css\" href=\"" + url + "\">"); }
if (!window.YAHOO) ewr_GetScript(EWR_YUI_HOST + "build/utilities/utilities.js");
</script>
<?php } ?>
<?php if (@$gsExport == "") { ?>
<script type="text/javascript">
if (!YAHOO.env.getVersion("button")) ewr_GetCss(EWR_YUI_HOST + "build/button/assets/skins/sam/button.css");
if (!YAHOO.env.getVersion("container")) ewr_GetCss(EWR_YUI_HOST + "build/container/assets/skins/sam/container.css");
if (!YAHOO.env.getVersion("resize")) ewr_GetCss(EWR_YUI_HOST + "build/resize/assets/skins/sam/resize.css");
</script>
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print") { ?>
<link rel="stylesheet" type="text/css" href="<?php echo EWR_PROJECT_STYLESHEET_FILENAME ?>">
<?php if (ewr_IsMobile()) { ?>
<link rel="stylesheet" type="text/css" href="phprptcss/ewmobile.css">
<?php } ?>
<?php } else { ?>
<style type="text/css">
<?php $cssfile = (@$gsExport == "pdf") ? (EWR_PDF_STYLESHEET_FILENAME == "" ? EWR_PROJECT_STYLESHEET_FILENAME : EWR_PDF_STYLESHEET_FILENAME) : EWR_PROJECT_STYLESHEET_FILENAME ?>
<?php echo file_get_contents($cssfile) ?>
</style>
<?php } ?>
<?php if (@$gsExport == "") { ?>
<script type="text/javascript">if (!YAHOO.env.getVersion("json")) ewr_GetScript(EWR_YUI_HOST + "build/json/json-min.js");</script>
<script type="text/javascript">if (!YAHOO.env.getVersion("button")) ewr_GetScript(EWR_YUI_HOST + "build/button/button-min.js");</script>
<script type="text/javascript">if (!YAHOO.env.getVersion("datasource")) ewr_GetScript("phprptjs/datenumber-min.js");</script>
<script type="text/javascript">
if (!window.Calendar)
	document.write("<link href=\"jscalendar/calendar-win2k-cold-1.css\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" title=\"win2k-1\">" +
		"<style type=\"text/css\">.ewCalendar {cursor: pointer;}</style>" +
		"<" + "script type=\"text/javascript\" src=\"jscalendar/calendar.js\"><" + "/script>" +
		"<" + "script type=\"text/javascript\" src=\"jscalendar/lang/calendar-en.js\"><" + "/script>" +
		"<" + "script type=\"text/javascript\" src=\"jscalendar/calendar-setup.js\"><" + "/script>" +
		"<" + "script type=\"text/javascript\">var ewSelectDateEvent = new YAHOO.util.CustomEvent(\"SelectDate\");<" + "/script>");

// Create calendar
function ewr_CreateCalendar(formid, id, format) {
	Calendar.setup({
		inputField: document.getElementById(formid).elements[id], // input field
		showsTime: / %H:%M:%S$/.test(format), // shows time
		ifFormat: format, // date format
		button: ewr_ConcatId(formid, id) // button ID
	});
}

// Custom event
var ewSelectDateEvent = new YAHOO.util.CustomEvent("SelectDate");
</script>
<script type="text/javascript">
var EWR_LANGUAGE_ID = "<?php echo $gsLanguage ?>";
var EWR_DATE_SEPARATOR = "/" || "/"; // Default date separator
var EWR_DECIMAL_POINT = "<?php echo $EWR_DEFAULT_DECIMAL_POINT ?>";
var EWR_THOUSANDS_SEP = "<?php echo $EWR_DEFAULT_THOUSANDS_SEP ?>";

//var EWR_EMAIL_EXPORT_BUTTON_SUBMIT_TEXT = "<?php echo ewr_EscapeJs(ewr_BtnCaption($ReportLanguage->Phrase("SendEmailBtn"))) ?>";
//var EWR_BUTTON_CANCEL_TEXT = "<?php echo ewr_EscapeJs(ewr_BtnCaption($ReportLanguage->Phrase("CancelBtn"))) ?>";

var EWR_MAX_EMAIL_RECIPIENT = <?php echo EWR_MAX_EMAIL_RECIPIENT ?>;
var EWR_DISABLE_BUTTON_ON_SUBMIT = true;
var EWR_IMAGES_FOLDER = "phprptimages/"; // Image folder

// Ajax settings
var EWR_RECORD_DELIMITER = "<?php echo ewr_EscapeJs(EWR_RECORD_DELIMITER) ?>";
var EWR_FIELD_DELIMITER = "<?php echo ewr_EscapeJs(EWR_FIELD_DELIMITER) ?>";
var EWR_LOOKUP_FILE_NAME = "ewrajax6.php"; // Lookup file name
var EWR_AUTO_SUGGEST_MAX_ENTRIES = <?php echo EWR_AUTO_SUGGEST_MAX_ENTRIES ?>; // Auto-Suggest max entries
</script>
<script type="text/javascript">
if (!YAHOO.env.getVersion("container")) ewr_GetScript(EWR_YUI_HOST + "build/container/container-min.js");
if (!YAHOO.env.getVersion("resize")) ewr_GetScript(EWR_YUI_HOST + "build/resize/resize-min.js");
</script>
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print" || @$gsExport == "email" && @$giFcfChartCnt > 0) { ?>
<script type="text/javascript">if (!window.jQuery) ewr_GetScript("<?php echo ewr_jQueryFile("jquery-%v.min.js") ?>");</script>
<?php if (ewr_IsMobile()) { ?>
<link rel="stylesheet" type="text/css" href="<?php echo ewr_jQueryFile("jquery.mobile-%v.min.css") ?>">
<script type="text/javascript">
if (!window._jQuery && window.jQuery && !window.jQuery.mobile) {
	jQuery(document).bind("mobileinit", function() {
		jQuery.mobile.ajaxEnabled = false;
		jQuery.mobile.ignoreContentEnabled = true;
	});
	ewr_GetScript("<?php echo ewr_jQueryFile("jquery.mobile-%v.min.js") ?>");
}
</script>
<?php } ?>
<script type="text/javascript" src="phprptjs/ewr6.js"></script>
<?php } ?>
<?php if (@$gsExport == "") { ?>
<script type="text/javascript">
<?php echo $ReportLanguage->ToJSON() ?>
</script>
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print" || @$gsExport == "email" && @$giFcfChartCnt > 0) { ?>
<script src="<?php echo EWR_FUSIONCHARTS_FREE_JSCLASS_FILE ?>" type="text/javascript"></script>
<?php } ?>
<link rel="shortcut icon" type="image/vnd.microsoft.icon" href="<?php echo ewr_ConvertFullUrl("favicon.ico") ?>"><link rel="icon" type="image/vnd.microsoft.icon" href="<?php echo ewr_ConvertFullUrl("favicon.ico") ?>">
<meta name="generator" content="PHP Report Maker v6.0.0">
</head>
<body class="yui-skin-sam">
<?php if (@$gsExport == "" || @$gsExport == "print") { ?>
<?php if (ewr_IsMobile()) { ?>
<div data-role="page">
	<div data-role="header">
		<a href="rmobilemenu.php"><?php echo $ReportLanguage->Phrase("MobileMenu") ?></a>
		<h1 id="ewPageTitle"></h1>
	</div>
<?php } ?>
<?php } ?>
<?php if (@!$gbSkipHeaderFooter) { ?>
<?php if (@$gsExport == "") { ?>
<div class="ewLayout">
<?php if (!ewr_IsMobile()) { ?>
	<!-- header (begin) --><!-- *** Note: Only licensed users are allowed to change the logo *** -->
	<div class="ewHeaderRow"><img src="phprptimages/phprptmkrlogo6.png" alt="" style="border: 0;"></div>
	<!-- header (end) -->
<?php } ?>
<?php if (ewr_IsMobile()) { ?>
	<div data-role="content" data-enhance="false">
	<table class="ewContentTable">
		<tr>
<?php } else { ?>
	<!-- content (begin) -->
	<!-- navigation -->
	<table cellspacing="0" class="ewContentTable">
		<tr>	
			<td class="ewMenuColumn">
<?php include_once "phprptinc/menu.php" ?>
			<!-- left column (end) -->
			</td>
<?php } ?>
			<td class="ewContentColumn">
				<p class="ewSpacer"><span class="ewSiteTitle"><?php echo $ReportLanguage->ProjectPhrase("BodyTitle") ?></span></p>
<?php } ?>
<?php } ?>
