<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "phprptinc/ewrcfg6.php" ?>
<?php include_once "phprptinc/ewmysql.php" ?>
<?php include_once "phprptinc/ewrfn6.php" ?>
<?php include_once "phprptinc/ewrusrfn.php" ?>
<?php
	$conn = ewr_Connect();
	$ReportLanguage = new crLanguage();
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $ReportLanguage->Phrase("MobileMenu") ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="<?php echo EWR_PROJECT_STYLESHEET_FILENAME ?>">
<link rel="stylesheet" type="text/css" href="phprptcss/ewmobile.css">
<link rel="stylesheet" type="text/css" href="<?php echo ewr_jQueryFile("jquery.mobile-%v.min.css") ?>">
<script type="text/javascript" src="<?php echo ewr_jQueryFile("jquery-%v.min.js") ?>"></script>
<script type="text/javascript">
	$(document).bind("mobileinit", function() {
		jQuery.mobile.ajaxEnabled = false;
		jQuery.mobile.ignoreContentEnabled = true;
	});
</script>
<script type="text/javascript" src="<?php echo ewr_jQueryFile("jquery.mobile-%v.min.js") ?>"></script>
<meta name="generator" content="PHP Report Maker v6.0.0">
</head>
<body>
<div data-role="page">
	<div data-role="header">
		<h1><?php echo $ReportLanguage->ProjectPhrase("BodyTitle") ?></h1>
	</div>
	<div data-role="content">
<?php $RootMenu = new crMenu("RootMenu", TRUE); ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(142, $ReportLanguage->Phrase("DetailSummaryReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("142", "MenuText") . $ReportLanguage->Phrase("DetailSummaryReportMenuItemSuffix"), "Clickthrough_details_reportsmry.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(143, $ReportLanguage->Phrase("DetailSummaryReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("143", "MenuText") . $ReportLanguage->Phrase("DetailSummaryReportMenuItemSuffix"), "Clickthrough_summary_reportsmry.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(144, $ReportLanguage->Phrase("DetailSummaryReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("144", "MenuText") . $ReportLanguage->Phrase("DetailSummaryReportMenuItemSuffix"), "sales_by_monthsmry.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(145, $ReportLanguage->Phrase("ChartReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("145", "MenuText") . $ReportLanguage->Phrase("ChartReportMenuItemSuffix"), "sales_by_monthsmry.php#cht_sales_by_month", 144, "", TRUE, FALSE);
$RootMenu->AddMenuItem(141, $ReportLanguage->Phrase("SimpleReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("141", "MenuText") . $ReportLanguage->Phrase("SimpleReportMenuItemSuffix"), "vendor_click_reportsrpt.php", -1, "", TRUE, FALSE);
$RootMenu->Render();
?>
	</div><!-- /content -->
</div><!-- /page -->
</body>
</html>
<?php

	 // Close connection
	$conn->Close();
?>
