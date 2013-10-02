<?php if (@$gsExport == "") { ?>
			<!-- right column (end) -->
			<?php if (isset($gsTimer)) $gsTimer->Stop(); ?>
		
	<!-- content (end) -->
	<!-- footer (begin) --><!-- *** Note: Only licensed users are allowed to remove or change the following copyright statement. *** -->
	

<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print" || @$gsExport == "email") { ?>
<script type="text/javascript">
<!--
xGetElementsByClassName(EWRPT_TABLE_CLASS, null, "TABLE", ewrpt_SetupTable); // init the table

//-->
</script>
<?php } ?>
<?php if (@$gsExport == "") { ?>
<script type="text/javascript">
<!--
ewrpt_InitEmailDialog(); // Init the email dialog

//-->
</script>
<?php } ?>
</body>
</html>
