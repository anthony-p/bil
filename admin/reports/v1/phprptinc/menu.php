<!-- Begin Main Menu -->
<div class="phpreportmaker">
<?php $RootMenu = new crMenu("RootMenu"); ?>
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
</div>
<!-- End Main Menu -->
