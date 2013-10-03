<?php if (@$gsExport == "") { ?>
<?php if (@!$gbSkipHeaderFooter) { ?>
			<!-- right column (end) -->
			<?php if (isset($gsTimer)) $gsTimer->Stop(); ?>
		</td></tr>
	</table>
	<!-- content (end) -->
<?php if (!ewr_IsMobile()) { ?>
	<!-- footer (begin) --><!-- *** Note: Only licensed users are allowed to remove or change the following copyright statement. *** -->
	<div class="ewFooterRow">
		<div class="ewFooterText">&nbsp;<?php echo $ReportLanguage->ProjectPhrase("FooterText"); ?></div>
		<!-- Place other links, for example, disclaimer, here -->
	</div>
	<!-- footer (end) -->	
<?php } ?>
</div>
<?php } ?>
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print") { ?>
<?php if (ewr_IsMobile()) { ?>
	</div>
	<!-- footer (begin) --><!-- *** Note: Only licensed users are allowed to remove or change the following copyright statement. *** -->
<!-- *** Remove comment lines to show footer for mobile
	<div data-role="footer">
		<h4>&nbsp;<?php echo $ReportLanguage->ProjectPhrase("FooterText") ?></h4>
	</div>
*** -->
	<!-- footer (end) -->	
</div>
<?php } ?>
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print") { ?>
<?php if (ewr_IsMobile()) { ?>
<script type="text/javascript">
ewr_Select("#ewPageTitle")[0].innerHTML = ewr_Select("#ewPageCaption")[0].innerHTML;
<?php if (@$_GET["chart"] <> "") { ?>
ewrLang.later(500, null, function() {
	var el = document.getElementById("<?php echo $_GET["chart"] ?>");
	if (el) el.scrollIntoView();
});
<?php } ?>
</script>
<?php } ?>
<?php } ?>
<?php if (@$gsExport == "") { ?>
<table class="ewStdTable"><tr><td><div id="ewrEmailDialog" class="phpreportmaker" style="visibility: hidden;">
<?php include_once "phprptinc/ewremail6.php" ?>
</div></td></tr></table>
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print" || @$gsExport == "email" && @$giFcfChartCnt > 0) { ?>
<script type="text/javascript">
ewr_Select("table." + EWR_TABLE_CLASSNAME, document, ewr_SetupTable); // Init tables
ewr_Select("table." + EWR_GRID_CLASSNAME, document, ewr_SetupGrid); // Init grids
</script>
<?php } ?>
<?php if (@$gsExport == "") { ?>
<?php if (@!$gbDrillDownInPanel) { ?>
<div id="ewrLoadingDiv" style="visibility: hidden;"></div>
<div id="ewrDrillDownDiv" style="visibility: hidden;"><div class="ft"></div></div>
<script type="text/javascript">
ewr_InitEmailDialog(); // Init the email dialog
ewr_InitLoadingPanels(); // Init loading panels / dialogs
</script>
<?php } ?>
<?php } ?>
</body>
</html>
