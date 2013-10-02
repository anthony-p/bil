<?php
session_start();
ob_start();
?>
<?php include "phprptinc/ewrcfg4.php"; ?>
<?php include "phprptinc/ewmysql.php"; ?>
<?php include "phprptinc/ewrfn4.php"; ?>
<?php include "phprptinc/ewrusrfn.php"; ?>
<?php

// Global variable for table object
$one_np = NULL;

//
// Table class for one np
//
class crone_np {
	var $TableVar = 'one_np';
	var $TableName = 'one np';
	var $TableType = 'REPORT';
	var $ShowCurrentFilter = EWRPT_SHOW_CURRENT_FILTER;
	var $FilterPanelOption = EWRPT_FILTER_PANEL_OPTION;
	var $CurrentOrder; // Current order
	var $CurrentOrderType; // Current order type

	// Table caption
	function TableCaption() {
		global $ReportLanguage;
		return $ReportLanguage->TablePhrase($this->TableVar, "TblCaption");
	}

	// Session Group Per Page
	function getGroupPerPage() {
		return @$_SESSION[EWRPT_PROJECT_VAR . "_" . $this->TableVar . "_grpperpage"];
	}

	function setGroupPerPage($v) {
		@$_SESSION[EWRPT_PROJECT_VAR . "_" . $this->TableVar . "_grpperpage"] = $v;
	}

	// Session Start Group
	function getStartGroup() {
		return @$_SESSION[EWRPT_PROJECT_VAR . "_" . $this->TableVar . "_start"];
	}

	function setStartGroup($v) {
		@$_SESSION[EWRPT_PROJECT_VAR . "_" . $this->TableVar . "_start"] = $v;
	}

	// Session Order By
	function getOrderBy() {
		return @$_SESSION[EWRPT_PROJECT_VAR . "_" . $this->TableVar . "_orderby"];
	}

	function setOrderBy($v) {
		@$_SESSION[EWRPT_PROJECT_VAR . "_" . $this->TableVar . "_orderby"] = $v;
	}

//	var $SelectLimit = TRUE;
	var $Chart2;
	var $giveback_id;
	var $np_userid;
	var $np_name;
	var $points;
	var $invoice_date;
	var $user_id;
	var $username;
	var $invoice_id;
	var $buyerorseller;
	var $transtype;
	var $fields = array();
	var $Export; // Export
	var $ExportAll = FALSE;
	var $UseTokenInUrl = EWRPT_USE_TOKEN_IN_URL;
	var $RowType; // Row type
	var $RowTotalType; // Row total type
	var $RowTotalSubType; // Row total subtype
	var $RowGroupLevel; // Row group level
	var $RowAttrs = array(); // Row attributes

	// Reset CSS styles for table object
	function ResetCSS() {
    	$this->RowAttrs["style"] = "";
		$this->RowAttrs["class"] = "";
		foreach ($this->fields as $fld) {
			$fld->ResetCSS();
		}
	}

	//
	// Table class constructor
	//
	function crone_np() {
		global $ReportLanguage;

		// giveback_id
		$this->giveback_id = new crField('one_np', 'one np', 'x_giveback_id', 'giveback_id', '`giveback_id`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->giveback_id->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['giveback_id'] =& $this->giveback_id;
		$this->giveback_id->DateFilter = "";
		$this->giveback_id->SqlSelect = "";
		$this->giveback_id->SqlOrderBy = "";

		// np_userid
		$this->np_userid = new crField('one_np', 'one np', 'x_np_userid', 'np_userid', '`np_userid`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->np_userid->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['np_userid'] =& $this->np_userid;
		$this->np_userid->DateFilter = "";
		$this->np_userid->SqlSelect = "";
		$this->np_userid->SqlOrderBy = "";

		// np_name
		$this->np_name = new crField('one_np', 'one np', 'x_np_name', 'np_name', '`np_name`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->fields['np_name'] =& $this->np_name;
		$this->np_name->DateFilter = "";
		$this->np_name->SqlSelect = "SELECT DISTINCT `np_name` FROM " . $this->SqlFrom();
		$this->np_name->SqlOrderBy = "`np_name`";

		// points
		$this->points = new crField('one_np', 'one np', 'x_points', 'points', '`points`', 5, EWRPT_DATATYPE_NUMBER, -1);
		$this->points->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['points'] =& $this->points;
		$this->points->DateFilter = "";
		$this->points->SqlSelect = "";
		$this->points->SqlOrderBy = "";

		// invoice_date
		$this->invoice_date = new crField('one_np', 'one np', 'x_invoice_date', 'invoice_date', '`invoice_date`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->invoice_date->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['invoice_date'] =& $this->invoice_date;
		$this->invoice_date->DateFilter = "";
		$this->invoice_date->SqlSelect = "";
		$this->invoice_date->SqlOrderBy = "";

		// user_id
		$this->user_id = new crField('one_np', 'one np', 'x_user_id', 'user_id', '`user_id`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->user_id->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['user_id'] =& $this->user_id;
		$this->user_id->DateFilter = "";
		$this->user_id->SqlSelect = "";
		$this->user_id->SqlOrderBy = "";

		// username
		$this->username = new crField('one_np', 'one np', 'x_username', 'username', '`username`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->fields['username'] =& $this->username;
		$this->username->DateFilter = "";
		$this->username->SqlSelect = "";
		$this->username->SqlOrderBy = "";

		// invoice_id
		$this->invoice_id = new crField('one_np', 'one np', 'x_invoice_id', 'invoice_id', '`invoice_id`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->invoice_id->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['invoice_id'] =& $this->invoice_id;
		$this->invoice_id->DateFilter = "";
		$this->invoice_id->SqlSelect = "";
		$this->invoice_id->SqlOrderBy = "";

		// buyerorseller
		$this->buyerorseller = new crField('one_np', 'one np', 'x_buyerorseller', 'buyerorseller', '`buyerorseller`', 16, EWRPT_DATATYPE_NUMBER, -1);
		$this->buyerorseller->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['buyerorseller'] =& $this->buyerorseller;
		$this->buyerorseller->DateFilter = "";
		$this->buyerorseller->SqlSelect = "";
		$this->buyerorseller->SqlOrderBy = "";

		// transtype
		$this->transtype = new crField('one_np', 'one np', 'x_transtype', 'transtype', '`transtype`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->fields['transtype'] =& $this->transtype;
		$this->transtype->DateFilter = "";
		$this->transtype->SqlSelect = "";
		$this->transtype->SqlOrderBy = "";

		// Chart2
		$this->Chart2 = new crChart('one_np', 'one np', 'Chart2', 'Chart2', 'np_name', 'points', '', 5, 'SUM', 550, 440);
		$this->Chart2->SqlSelect = "SELECT `np_name`, '', SUM(`points`) FROM ";
		$this->Chart2->SqlGroupBy = "`np_name`";
		$this->Chart2->SqlOrderBy = "";
		$this->Chart2->SeriesDateType = "";
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
		} else {
			if ($ofld->GroupingFieldId == 0) $ofld->setSort("");
		}
	}

	// Get Sort SQL
	function SortSql() {
		$sDtlSortSql = "";
		$argrps = array();
		foreach ($this->fields as $fld) {
			if ($fld->getSort() <> "") {
				if ($fld->GroupingFieldId > 0) {
					if ($fld->FldGroupSql <> "")
						$argrps[$fld->GroupingFieldId] = str_replace("%s", $fld->FldExpression, $fld->FldGroupSql) . " " . $fld->getSort();
					else
						$argrps[$fld->GroupingFieldId] = $fld->FldExpression . " " . $fld->getSort();
				} else {
					if ($sDtlSortSql <> "") $sDtlSortSql .= ", ";
					$sDtlSortSql .= $fld->FldExpression . " " . $fld->getSort();
				}
			}
		}
		$sSortSql = "";
		foreach ($argrps as $grp) {
			if ($sSortSql <> "") $sSortSql .= ", ";
			$sSortSql .= $grp;
		}
		if ($sDtlSortSql <> "") {
			if ($sSortSql <> "") $sSortSql .= ",";
			$sSortSql .= $sDtlSortSql;
		}
		return $sSortSql;
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`giveback_invoices`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlWhere() { // Where
		return "";
	}

	function SqlGroupBy() { // Group By
		return "";
	}

	function SqlHaving() { // Having
		return "";
	}

	function SqlOrderBy() { // Order By
		return "";
	}

	// Table Level Group SQL
	function SqlFirstGroupField() {
		return "";
	}

	function SqlSelectGroup() {
		return "SELECT DISTINCT " . $this->SqlFirstGroupField() . " FROM " . $this->SqlFrom();
	}

	function SqlOrderByGroup() {
		return "";
	}

	function SqlSelectAgg() {
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlAggPfx() {
		return "";
	}

	function SqlAggSfx() {
		return "";
	}

	function SqlSelectCount() {
		return "SELECT COUNT(*) FROM " . $this->SqlFrom();
	}

	// Sort URL
	function SortUrl(&$fld) {
		return "";
	}

	// Row attributes
	function RowAttributes() {
		$sAtt = "";
		foreach ($this->RowAttrs as $k => $v) {
			if (trim($v) <> "")
				$sAtt .= " " . $k . "=\"" . trim($v) . "\"";
		}
		return $sAtt;
	}

	// Field object by fldvar
	function &fields($fldvar) {
		return $this->fields[$fldvar];
	}

	// Table level events
	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// Load Custom Filters event
	function CustomFilters_Load() {

		// Enter your code here	
		// ewrpt_RegisterCustomFilter($this-><Field>, 'LastMonth', 'Last Month', 'GetLastMonthFilter'); // Date example
		// ewrpt_RegisterCustomFilter($this-><Field>, 'StartsWithA', 'Starts With A', 'GetStartsWithAFilter'); // String example

	}

	// Page Filter Validated event
	function Page_FilterValidated() {

		// Example:
		//global $MyTable;
		//$MyTable->MyField1->SearchValue = "your search criteria"; // Search value

	}

	// Chart Rendering event
	function Chart_Rendering(&$chart) {

		// var_dump($chart);
	}

	// Chart Rendered event
	function Chart_Rendered($chart, &$chartxml) {

		//var_dump($chart);
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}
}
?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Always modified
header("Cache-Control: private, no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
?>
<?php

// Create page object
$one_np_summary = new crone_np_summary();
$Page =& $one_np_summary;

// Page init
$one_np_summary->Page_Init();

// Page main
$one_np_summary->Page_Main();
?>
<?php include "phprptinc/header.php"; ?>
<script type="text/javascript">

// Create page object
var one_np_summary = new ewrpt_Page("one_np_summary");

// page properties
one_np_summary.PageID = "summary"; // page ID
one_np_summary.FormID = "fone_npsummaryfilter"; // form ID
var EWRPT_PAGE_ID = one_np_summary.PageID;

// extend page with ValidateForm function
one_np_summary.ValidateForm = function(fobj) {
	if (!this.ValidateRequired)
		return true; // ignore validation

	// Call Form Custom Validate event
	if (!this.Form_CustomValidate(fobj)) return false;
	return true;
}

// extend page with Form_CustomValidate function
one_np_summary.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }
<?php if (EWRPT_CLIENT_VALIDATE) { ?>
one_np_summary.ValidateRequired = true; // uses JavaScript validation
<?php } else { ?>
one_np_summary.ValidateRequired = false; // no JavaScript validation
<?php } ?>
</script>
<script language="JavaScript" type="text/javascript">
<!--

// Write your client script here, no need to add script tags.
// To include another .js script, use:
// ew_ClientScriptInclude("my_javascript.js"); 
//-->

</script>
<?php $one_np_summary->ShowPageHeader(); ?>
<?php $one_np_summary->ShowMessage(); ?>
<script src="FusionChartsFree/JSClass/FusionCharts.js" type="text/javascript"></script>
<script src="phprptjs/popup.js" type="text/javascript"></script>
<script src="phprptjs/ewrptpop.js" type="text/javascript"></script>
<script type="text/javascript">

// popup fields
<?php $jsdata = ewrpt_GetJsData($one_np->np_name, $one_np->np_name->FldType); ?>
ewrpt_CreatePopup("one_np_np_name", [<?php echo $jsdata ?>]);
</script>
<div id="one_np_np_name_Popup" class="ewPopup">
<span class="phpreportmaker"></span>
</div>
<!-- Table Container (Begin) -->
<table id="ewContainer" cellspacing="0" cellpadding="0" border="0">
<!-- Top Container (Begin) -->
<tr><td colspan="3"><div id="ewTop" class="phpreportmaker">
<!-- top slot -->
<a name="top"></a>
<?php echo $one_np->TableCaption() ?>
<?php if ($one_np_summary->FilterApplied) { ?>
&nbsp;&nbsp;<a href="one_npsmry.php?cmd=reset"><?php echo $ReportLanguage->Phrase("ResetAllFilter") ?></a>
<?php } ?>
<br /><br />
</div></td></tr>
<!-- Top Container (End) -->
<tr>
	<!-- Left Container (Begin) -->
	<td style="vertical-align: top;"><div id="ewLeft" class="phpreportmaker">
	<!-- Left slot -->
	</div></td>
	<!-- Left Container (End) -->
	<!-- Center Container - Report (Begin) -->
	<td style="vertical-align: top;" class="ewPadding"><div id="ewCenter" class="phpreportmaker">
	<!-- center slot -->
<!-- summary report starts -->
<div id="report_summary">
<?php
if ($one_np->FilterPanelOption == 2 || ($one_np->FilterPanelOption == 3 && $one_np_summary->FilterApplied) || $one_np_summary->Filter == "0=101") {
	$sButtonImage = "phprptimages/collapse.gif";
	$sDivDisplay = "";
} else {
	$sButtonImage = "phprptimages/expand.gif";
	$sDivDisplay = " style=\"display: none;\"";
}
?>
<a href="javascript:ewrpt_ToggleFilterPanel();" style="text-decoration: none;"><img id="ewrptToggleFilterImg" src="<?php echo $sButtonImage ?>" alt="" width="9" height="9" border="0"></a><span class="phpreportmaker">&nbsp;<?php echo $ReportLanguage->Phrase("Filters") ?></span><br /><br />
<div id="ewrptExtFilterPanel"<?php echo $sDivDisplay ?>>
<!-- Search form (begin) -->
<form name="fone_npsummaryfilter" id="fone_npsummaryfilter" action="one_npsmry.php" class="ewForm" onsubmit="return one_np_summary.ValidateForm(this);">
<table class="ewRptExtFilter">
	<tr>
		<td><span class="phpreportmaker"><?php echo $one_np->np_name->FldCaption() ?></span></td>
		<td><span class="ewRptSearchOpr"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so1_np_name" id="so1_np_name" value="LIKE"></span></td>
		<td>
			<table cellspacing="0" class="ewItemTable"><tr>
				<td><span class="phpreportmaker">
<input type="text" name="sv1_np_name" id="sv1_np_name" size="30" maxlength="255" value="<?php echo ewrpt_HtmlEncode($one_np->np_name->SearchValue) ?>"<?php echo ($one_np_summary->ClearExtFilter == 'one_np_np_name') ? " class=\"ewInputCleared\"" : "" ?>>
</span></td>
				<td><span class="ewRptSearchOpr" id="btw0_np_name" name="btw0_np_name"><label><input type="radio" name="sc_np_name" value="AND"<?php if ($one_np->np_name->SearchCondition <> "OR") echo " checked=\"checked\"" ?>><?php echo $ReportLanguage->Phrase("AND"); ?></label>&nbsp;<span class="ewSearchOpr" name="_sc_np_name" id="_sc_np_name"><label><input type="radio" name="sc_np_name" value="OR"<?php if ($one_np->np_name->SearchCondition == "OR") echo " checked=\"checked\"" ?>><?php echo $ReportLanguage->Phrase("OR"); ?></label></span></span></td>
				<td><span class="ewRptSearchOpr" id="btw0_np_name" name="btw0_np_name" ><select name="so2_np_name" id="so2_np_name"><option value="="<?php if ($one_np->np_name->SearchOperator2 == "=") echo " selected=\"selected\"" ?>><?php echo $ReportLanguage->Phrase("="); ?></option><option value="<>"<?php if ($one_np->np_name->SearchOperator2 == "<>") echo " selected=\"selected\"" ?>><?php echo $ReportLanguage->Phrase("<>"); ?></option><option value="<"<?php if ($one_np->np_name->SearchOperator2 == "<") echo " selected=\"selected\"" ?>><?php echo $ReportLanguage->Phrase("<"); ?></option><option value="<="<?php if ($one_np->np_name->SearchOperator2 == "<=") echo " selected=\"selected\"" ?>><?php echo $ReportLanguage->Phrase("<="); ?></option><option value=">"<?php if ($one_np->np_name->SearchOperator2 == ">") echo " selected=\"selected\"" ?>><?php echo $ReportLanguage->Phrase(">"); ?></option><option value=">="<?php if ($one_np->np_name->SearchOperator2 == ">=") echo " selected=\"selected\"" ?>><?php echo $ReportLanguage->Phrase(">="); ?></option><option value="LIKE"<?php if ($one_np->np_name->SearchOperator2 == "LIKE") echo " selected=\"selected\"" ?>><?php echo $ReportLanguage->Phrase("LIKE"); ?></option><option value="NOT LIKE"<?php if ($one_np->np_name->SearchOperator2 == "NOT LIKE") echo " selected=\"selected\"" ?>><?php echo $ReportLanguage->Phrase("NOT LIKE"); ?></option><option value="STARTS WITH"<?php if ($one_np->np_name->SearchOperator2 == "STARTS WITH") echo " selected=\"selected\"" ?>><?php echo $ReportLanguage->Phrase("STARTS WITH"); ?></option></select></span></td>
				<td><span class="phpreportmaker">
<input type="text" name="sv2_np_name" id="sv2_np_name" size="30" maxlength="255" value="<?php echo ewrpt_HtmlEncode($one_np->np_name->SearchValue2) ?>"<?php echo ($one_np_summary->ClearExtFilter == 'one_np_np_name') ? " class=\"ewInputCleared\"" : "" ?>>
</span></td>
			</tr></table>			
		</td>
	</tr>
</table>
<table class="ewRptExtFilter">
	<tr>
		<td><span class="phpreportmaker">
			<input type="Submit" name="Submit" id="Submit" value="<?php echo $ReportLanguage->Phrase("Search") ?>">&nbsp;
			<input type="Reset" name="Reset" id="Reset" value="<?php echo $ReportLanguage->Phrase("Reset") ?>">&nbsp;
		</span></td>
	</tr>
</table>
</form>
<!-- Search form (end) -->
</div>
<br />
<?php if ($one_np->ShowCurrentFilter) { ?>
<div id="ewrptFilterList">
<?php $one_np_summary->ShowFilterList() ?>
</div>
<br />
<?php } ?>
<table class="ewGrid" cellspacing="0"><tr>
	<td class="ewGridContent">
<!-- Report Grid (Begin) -->
<div class="ewGridMiddlePanel">
<table class="ewTable ewTableSeparate" cellspacing="0">
<?php

// Set the last group to display if not export all
if ($one_np->ExportAll && $one_np->Export <> "") {
	$one_np_summary->StopGrp = $one_np_summary->TotalGrps;
} else {
	$one_np_summary->StopGrp = $one_np_summary->StartGrp + $one_np_summary->DisplayGrps - 1;
}

// Stop group <= total number of groups
if (intval($one_np_summary->StopGrp) > intval($one_np_summary->TotalGrps))
	$one_np_summary->StopGrp = $one_np_summary->TotalGrps;
$one_np_summary->RecCount = 0;

// Get first row
if ($one_np_summary->TotalGrps > 0) {
	$one_np_summary->GetRow(1);
	$one_np_summary->GrpCount = 1;
}
while (($rs && !$rs->EOF && $one_np_summary->GrpCount <= $one_np_summary->DisplayGrps) || $one_np_summary->ShowFirstHeader) {

	// Show header
	if ($one_np_summary->ShowFirstHeader) {
?>
	<thead>
	<tr>
<td class="ewTableHeader">
<?php if ($one_np->Export <> "") { ?>
<?php echo $one_np->giveback_id->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($one_np->SortUrl($one_np->giveback_id) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $one_np->giveback_id->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $one_np->SortUrl($one_np->giveback_id) ?>',0);"><?php echo $one_np->giveback_id->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($one_np->giveback_id->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($one_np->giveback_id->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($one_np->Export <> "") { ?>
<?php echo $one_np->np_userid->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($one_np->SortUrl($one_np->np_userid) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $one_np->np_userid->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $one_np->SortUrl($one_np->np_userid) ?>',0);"><?php echo $one_np->np_userid->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($one_np->np_userid->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($one_np->np_userid->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($one_np->Export <> "") { ?>
<?php echo $one_np->np_name->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($one_np->SortUrl($one_np->np_name) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $one_np->np_name->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $one_np->SortUrl($one_np->np_name) ?>',0);"><?php echo $one_np->np_name->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($one_np->np_name->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($one_np->np_name->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
		<td style="width: 20px;" align="right"><a href="#" onclick="ewrpt_ShowPopup(this.name, 'one_np_np_name', false, '<?php echo $one_np->np_name->RangeFrom; ?>', '<?php echo $one_np->np_name->RangeTo; ?>');return false;" name="x_np_name<?php echo $one_np_summary->Cnt[0][0]; ?>" id="x_np_name<?php echo $one_np_summary->Cnt[0][0]; ?>"><img src="phprptimages/popup.gif" width="15" height="14" align="texttop" border="0" alt="<?php echo $ReportLanguage->Phrase("Filter") ?>"></a></td>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($one_np->Export <> "") { ?>
<?php echo $one_np->points->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($one_np->SortUrl($one_np->points) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $one_np->points->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $one_np->SortUrl($one_np->points) ?>',0);"><?php echo $one_np->points->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($one_np->points->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($one_np->points->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($one_np->Export <> "") { ?>
<?php echo $one_np->invoice_date->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($one_np->SortUrl($one_np->invoice_date) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $one_np->invoice_date->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $one_np->SortUrl($one_np->invoice_date) ?>',0);"><?php echo $one_np->invoice_date->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($one_np->invoice_date->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($one_np->invoice_date->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($one_np->Export <> "") { ?>
<?php echo $one_np->user_id->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($one_np->SortUrl($one_np->user_id) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $one_np->user_id->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $one_np->SortUrl($one_np->user_id) ?>',0);"><?php echo $one_np->user_id->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($one_np->user_id->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($one_np->user_id->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($one_np->Export <> "") { ?>
<?php echo $one_np->username->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($one_np->SortUrl($one_np->username) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $one_np->username->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $one_np->SortUrl($one_np->username) ?>',0);"><?php echo $one_np->username->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($one_np->username->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($one_np->username->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($one_np->Export <> "") { ?>
<?php echo $one_np->invoice_id->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($one_np->SortUrl($one_np->invoice_id) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $one_np->invoice_id->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $one_np->SortUrl($one_np->invoice_id) ?>',0);"><?php echo $one_np->invoice_id->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($one_np->invoice_id->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($one_np->invoice_id->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($one_np->Export <> "") { ?>
<?php echo $one_np->buyerorseller->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($one_np->SortUrl($one_np->buyerorseller) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $one_np->buyerorseller->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $one_np->SortUrl($one_np->buyerorseller) ?>',0);"><?php echo $one_np->buyerorseller->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($one_np->buyerorseller->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($one_np->buyerorseller->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($one_np->Export <> "") { ?>
<?php echo $one_np->transtype->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($one_np->SortUrl($one_np->transtype) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $one_np->transtype->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $one_np->SortUrl($one_np->transtype) ?>',0);"><?php echo $one_np->transtype->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($one_np->transtype->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($one_np->transtype->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
	</tr>
	</thead>
	<tbody>
<?php
		$one_np_summary->ShowFirstHeader = FALSE;
	}
	$one_np_summary->RecCount++;

		// Render detail row
		$one_np->ResetCSS();
		$one_np->RowType = EWRPT_ROWTYPE_DETAIL;
		$one_np_summary->RenderRow();
?>
	<tr<?php echo $one_np->RowAttributes(); ?>>
		<td<?php echo $one_np->giveback_id->CellAttributes() ?>>
<div<?php echo $one_np->giveback_id->ViewAttributes(); ?>><?php echo $one_np->giveback_id->ListViewValue(); ?></div>
</td>
		<td<?php echo $one_np->np_userid->CellAttributes() ?>>
<div<?php echo $one_np->np_userid->ViewAttributes(); ?>><?php echo $one_np->np_userid->ListViewValue(); ?></div>
</td>
		<td<?php echo $one_np->np_name->CellAttributes() ?>>
<div<?php echo $one_np->np_name->ViewAttributes(); ?>><?php echo $one_np->np_name->ListViewValue(); ?></div>
</td>
		<td<?php echo $one_np->points->CellAttributes() ?>>
<div<?php echo $one_np->points->ViewAttributes(); ?>><?php echo $one_np->points->ListViewValue(); ?></div>
</td>
		<td<?php echo $one_np->invoice_date->CellAttributes() ?>>
<div<?php echo $one_np->invoice_date->ViewAttributes(); ?>><?php echo $one_np->invoice_date->ListViewValue(); ?></div>
</td>
		<td<?php echo $one_np->user_id->CellAttributes() ?>>
<div<?php echo $one_np->user_id->ViewAttributes(); ?>><?php echo $one_np->user_id->ListViewValue(); ?></div>
</td>
		<td<?php echo $one_np->username->CellAttributes() ?>>
<div<?php echo $one_np->username->ViewAttributes(); ?>><?php echo $one_np->username->ListViewValue(); ?></div>
</td>
		<td<?php echo $one_np->invoice_id->CellAttributes() ?>>
<div<?php echo $one_np->invoice_id->ViewAttributes(); ?>><?php echo $one_np->invoice_id->ListViewValue(); ?></div>
</td>
		<td<?php echo $one_np->buyerorseller->CellAttributes() ?>>
<div<?php echo $one_np->buyerorseller->ViewAttributes(); ?>><?php echo $one_np->buyerorseller->ListViewValue(); ?></div>
</td>
		<td<?php echo $one_np->transtype->CellAttributes() ?>>
<div<?php echo $one_np->transtype->ViewAttributes(); ?>><?php echo $one_np->transtype->ListViewValue(); ?></div>
</td>
	</tr>
<?php

		// Accumulate page summary
		$one_np_summary->AccumulateSummary();

		// Get next record
		$one_np_summary->GetRow(2);
	$one_np_summary->GrpCount++;
} // End while
?>
	</tbody>
	<tfoot>
<?php
if ($one_np_summary->TotalGrps > 0) {
	$one_np->ResetCSS();
	$one_np->RowType = EWRPT_ROWTYPE_TOTAL;
	$one_np->RowTotalType = EWRPT_ROWTOTAL_GRAND;
	$one_np->RowTotalSubType = EWRPT_ROWTOTAL_FOOTER;
	$one_np->RowAttrs["class"] = "ewRptGrandSummary";
	$one_np_summary->RenderRow();
?>
	<!-- tr><td colspan="10"><span class="phpreportmaker">&nbsp;<br /></span></td></tr -->
	<tr<?php echo $one_np->RowAttributes(); ?>><td colspan="10"><?php echo $ReportLanguage->Phrase("RptGrandTotal") ?> (<?php echo ewrpt_FormatNumber($one_np_summary->TotCount,0,-2,-2,-2); ?> <?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</td></tr>
<?php } ?>
	</tfoot>
</table>
</div>
<div class="ewGridLowerPanel">
<form action="one_npsmry.php" name="ewpagerform" id="ewpagerform" class="ewForm">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td style="white-space: nowrap;">
<?php if (!isset($Pager)) $Pager = new crPrevNextPager($one_np_summary->StartGrp, $one_np_summary->DisplayGrps, $one_np_summary->TotalGrps) ?>
<?php if ($Pager->RecordCount > 0) { ?>
	<table border="0" cellspacing="0" cellpadding="0"><tr><td><span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("Page") ?>&nbsp;</span></td>
<!--first page button-->
	<?php if ($Pager->FirstButton->Enabled) { ?>
	<td><a href="one_npsmry.php?start=<?php echo $Pager->FirstButton->Start ?>"><img src="phprptimages/first.gif" alt="<?php echo $ReportLanguage->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phprptimages/firstdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($Pager->PrevButton->Enabled) { ?>
	<td><a href="one_npsmry.php?start=<?php echo $Pager->PrevButton->Start ?>"><img src="phprptimages/prev.gif" alt="<?php echo $ReportLanguage->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phprptimages/prevdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="pageno" id="pageno" value="<?php echo $Pager->CurrentPage ?>" size="4"></td>
<!--next page button-->
	<?php if ($Pager->NextButton->Enabled) { ?>
	<td><a href="one_npsmry.php?start=<?php echo $Pager->NextButton->Start ?>"><img src="phprptimages/next.gif" alt="<?php echo $ReportLanguage->Phrase("PagerNext") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phprptimages/nextdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerNext") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--last page button-->
	<?php if ($Pager->LastButton->Enabled) { ?>
	<td><a href="one_npsmry.php?start=<?php echo $Pager->LastButton->Start ?>"><img src="phprptimages/last.gif" alt="<?php echo $ReportLanguage->Phrase("PagerLast") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phprptimages/lastdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerLast") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
	<td><span class="phpreportmaker">&nbsp;<?php echo $ReportLanguage->Phrase("of") ?> <?php echo $Pager->PageCount ?></span></td>
	</tr></table>
	</td>	
	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td>
	<span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("Record") ?> <?php echo $Pager->FromIndex ?> <?php echo $ReportLanguage->Phrase("To") ?> <?php echo $Pager->ToIndex ?> <?php echo $ReportLanguage->Phrase("Of") ?> <?php echo $Pager->RecordCount ?></span>
<?php } else { ?>
	<?php if ($one_np_summary->Filter == "0=101") { ?>
	<span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("EnterSearchCriteria") ?></span>
	<?php } else { ?>
	<span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("NoRecord") ?></span>
	<?php } ?>
<?php } ?>
		</td>
<?php if ($one_np_summary->TotalGrps > 0) { ?>
		<td style="white-space: nowrap;">&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="right" style="vertical-align: top; white-space: nowrap;"><span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("GroupsPerPage"); ?>&nbsp;
<select name="<?php echo EWRPT_TABLE_GROUP_PER_PAGE; ?>" onchange="this.form.submit();">
<option value="1"<?php if ($one_np_summary->DisplayGrps == 1) echo " selected=\"selected\"" ?>>1</option>
<option value="2"<?php if ($one_np_summary->DisplayGrps == 2) echo " selected=\"selected\"" ?>>2</option>
<option value="3"<?php if ($one_np_summary->DisplayGrps == 3) echo " selected=\"selected\"" ?>>3</option>
<option value="4"<?php if ($one_np_summary->DisplayGrps == 4) echo " selected=\"selected\"" ?>>4</option>
<option value="5"<?php if ($one_np_summary->DisplayGrps == 5) echo " selected=\"selected\"" ?>>5</option>
<option value="10"<?php if ($one_np_summary->DisplayGrps == 10) echo " selected=\"selected\"" ?>>10</option>
<option value="20"<?php if ($one_np_summary->DisplayGrps == 20) echo " selected=\"selected\"" ?>>20</option>
<option value="50"<?php if ($one_np_summary->DisplayGrps == 50) echo " selected=\"selected\"" ?>>50</option>
<option value="ALL"<?php if ($one_np->getGroupPerPage() == -1) echo " selected=\"selected\"" ?>><?php echo $ReportLanguage->Phrase("AllRecords") ?></option>
</select>
		</span></td>
<?php } ?>
	</tr>
</table>
</form>
</div>
</td></tr></table>
</div>
<!-- Summary Report Ends -->
	</div><br /></td>
	<!-- Center Container - Report (End) -->
	<!-- Right Container (Begin) -->
	<td style="vertical-align: top;"><div id="ewRight" class="phpreportmaker">
	<!-- Right slot -->
	</div></td>
	<!-- Right Container (End) -->
</tr>
<!-- Bottom Container (Begin) -->
<tr><td colspan="3" class="ewPadding"><div id="ewBottom" class="phpreportmaker">
	<!-- Bottom slot -->
<a name="cht_Chart2"></a>
<div id="div_one_np_Chart2"></div>
<?php

// Initialize chart data
$one_np->Chart2->ID = "one_np_Chart2"; // Chart ID
$one_np->Chart2->SetChartParam("type", "5", FALSE); // Chart type
$one_np->Chart2->SetChartParam("seriestype", "0", FALSE); // Chart series type
$one_np->Chart2->SetChartParam("bgcolor", "#FCFCFC", TRUE); // Background color
$one_np->Chart2->SetChartParam("caption", $one_np->Chart2->ChartCaption(), TRUE); // Chart caption
$one_np->Chart2->SetChartParam("xaxisname", $one_np->Chart2->ChartXAxisName(), TRUE); // X axis name
$one_np->Chart2->SetChartParam("yaxisname", $one_np->Chart2->ChartYAxisName(), TRUE); // Y axis name
$one_np->Chart2->SetChartParam("shownames", "1", TRUE); // Show names
$one_np->Chart2->SetChartParam("showvalues", "1", TRUE); // Show values
$one_np->Chart2->SetChartParam("showhovercap", "0", TRUE); // Show hover
$one_np->Chart2->SetChartParam("alpha", "50", FALSE); // Chart alpha
$one_np->Chart2->SetChartParam("colorpalette", "#FF0000|#FF0080|#FF00FF|#8000FF|#FF8000|#FF3D3D|#7AFFFF|#0000FF|#FFFF00|#FF7A7A|#3DFFFF|#0080FF|#80FF00|#00FF00|#00FF80|#00FFFF", FALSE); // Chart color palette
?>
<?php
$one_np->Chart2->SetChartParam("showCanvasBg", "1", TRUE); // showCanvasBg
$one_np->Chart2->SetChartParam("showCanvasBase", "1", TRUE); // showCanvasBase
$one_np->Chart2->SetChartParam("showLimits", "1", TRUE); // showLimits
$one_np->Chart2->SetChartParam("animation", "1", TRUE); // animation
$one_np->Chart2->SetChartParam("rotateNames", "0", TRUE); // rotateNames
$one_np->Chart2->SetChartParam("yAxisMinValue", "0", TRUE); // yAxisMinValue
$one_np->Chart2->SetChartParam("yAxisMaxValue", "0", TRUE); // yAxisMaxValue
$one_np->Chart2->SetChartParam("PYAxisMinValue", "0", TRUE); // PYAxisMinValue
$one_np->Chart2->SetChartParam("PYAxisMaxValue", "0", TRUE); // PYAxisMaxValue
$one_np->Chart2->SetChartParam("SYAxisMinValue", "0", TRUE); // SYAxisMinValue
$one_np->Chart2->SetChartParam("SYAxisMaxValue", "0", TRUE); // SYAxisMaxValue
$one_np->Chart2->SetChartParam("showColumnShadow", "0", TRUE); // showColumnShadow
$one_np->Chart2->SetChartParam("showPercentageValues", "1", TRUE); // showPercentageValues
$one_np->Chart2->SetChartParam("showPercentageInLabel", "1", TRUE); // showPercentageInLabel
$one_np->Chart2->SetChartParam("showBarShadow", "0", TRUE); // showBarShadow
$one_np->Chart2->SetChartParam("showAnchors", "1", TRUE); // showAnchors
$one_np->Chart2->SetChartParam("showAreaBorder", "1", TRUE); // showAreaBorder
$one_np->Chart2->SetChartParam("isSliced", "1", TRUE); // isSliced
$one_np->Chart2->SetChartParam("showAsBars", "0", TRUE); // showAsBars
$one_np->Chart2->SetChartParam("showShadow", "0", TRUE); // showShadow
$one_np->Chart2->SetChartParam("formatNumber", "0", TRUE); // formatNumber
$one_np->Chart2->SetChartParam("formatNumberScale", "0", TRUE); // formatNumberScale
$one_np->Chart2->SetChartParam("decimalSeparator", ".", TRUE); // decimalSeparator
$one_np->Chart2->SetChartParam("thousandSeparator", ",", TRUE); // thousandSeparator
$one_np->Chart2->SetChartParam("decimalPrecision", "2", TRUE); // decimalPrecision
$one_np->Chart2->SetChartParam("divLineDecimalPrecision", "2", TRUE); // divLineDecimalPrecision
$one_np->Chart2->SetChartParam("limitsDecimalPrecision", "2", TRUE); // limitsDecimalPrecision
$one_np->Chart2->SetChartParam("zeroPlaneShowBorder", "1", TRUE); // zeroPlaneShowBorder
$one_np->Chart2->SetChartParam("showDivLineValue", "1", TRUE); // showDivLineValue
$one_np->Chart2->SetChartParam("showAlternateHGridColor", "0", TRUE); // showAlternateHGridColor
$one_np->Chart2->SetChartParam("showAlternateVGridColor", "0", TRUE); // showAlternateVGridColor
$one_np->Chart2->SetChartParam("hoverCapSepChar", ":", TRUE); // hoverCapSepChar

// Define trend lines
?>
<?php
$SqlSelect = $one_np->SqlSelect();
$SqlChartSelect = $one_np->Chart2->SqlSelect;
$sSqlChartBase = $one_np->SqlFrom();

// Load chart data from sql directly
$sSql = $SqlChartSelect . $sSqlChartBase;
$sSql = ewrpt_BuildReportSql($sSql, $one_np->SqlWhere(), $one_np->Chart2->SqlGroupBy, "", $one_np->Chart2->SqlOrderBy, $one_np_summary->Filter, "");
if (EWRPT_DEBUG_ENABLED) echo "(Chart SQL): " . $sSql . "<br>";
ewrpt_LoadChartData($sSql, $one_np->Chart2);
ewrpt_SortChartData($one_np->Chart2->Data, 0, "");

// Call Chart_Rendering event
$one_np->Chart_Rendering($one_np->Chart2);
$chartxml = $one_np->Chart2->ChartXml();

// Call Chart_Rendered event
$one_np->Chart_Rendered($one_np->Chart2, $chartxml);
echo $one_np->Chart2->ShowChartFCF($chartxml);
?>
<a href="#top"><?php echo $ReportLanguage->Phrase("Top") ?></a>
<br /><br />
	</div><br /></td></tr>
<!-- Bottom Container (End) -->
</table>
<!-- Table Container (End) -->
<?php $one_np_summary->ShowPageFooter(); ?>
<?php if (EWRPT_DEBUG_ENABLED) echo ewrpt_DebugMsg(); ?>
<?php

// Close recordsets
if ($rsgrp) $rsgrp->Close();
if ($rs) $rs->Close();
?>
<script language="JavaScript" type="text/javascript">
<!--

// Write your table-specific startup script here
// document.write("page loaded");
//-->

</script>
<?php include "phprptinc/footer.php"; ?>
<?php
$one_np_summary->Page_Terminate();
?>
<?php

//
// Page class
//
class crone_np_summary {

	// Page ID
	var $PageID = 'summary';

	// Table name
	var $TableName = 'one np';

	// Page object name
	var $PageObjName = 'one_np_summary';

	// Page name
	function PageName() {
		return ewrpt_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ewrpt_CurrentPage() . "?";
		global $one_np;
		if ($one_np->UseTokenInUrl) $PageUrl .= "t=" . $one_np->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Export URLs
	var $ExportPrintUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;

	// Message
	function getMessage() {
		return @$_SESSION[EWRPT_SESSION_MESSAGE];
	}

	function setMessage($v) {
		if (@$_SESSION[EWRPT_SESSION_MESSAGE] <> "") { // Append
			$_SESSION[EWRPT_SESSION_MESSAGE] .= "<br />" . $v;
		} else {
			$_SESSION[EWRPT_SESSION_MESSAGE] = $v;
		}
	}

	// Show message
	function ShowMessage() {
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage);
		if ($sMessage <> "") { // Message in Session, display
			echo "<p><span class=\"ewMessage\">" . $sMessage . "</span></p>";
			$_SESSION[EWRPT_SESSION_MESSAGE] = ""; // Clear message in Session
		}
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p><span class=\"phpreportmaker\">" . $sHeader . "</span></p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Fotoer exists, display
			echo "<p><span class=\"phpreportmaker\">" . $sFooter . "</span></p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $one_np;
		if ($one_np->UseTokenInUrl) {
			if (ewrpt_IsHttpPost())
				return ($one_np->TableVar == @$_POST("t"));
			if (@$_GET["t"] <> "")
				return ($one_np->TableVar == @$_GET["t"]);
		} else {
			return TRUE;
		}
	}

	//
	// Page class constructor
	//
	function crone_np_summary() {
		global $conn, $ReportLanguage;

		// Language object
		$ReportLanguage = new crLanguage();

		// Table object (one_np)
		$GLOBALS["one_np"] = new crone_np();

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";

		// Page ID
		if (!defined("EWRPT_PAGE_ID"))
			define("EWRPT_PAGE_ID", 'summary', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EWRPT_TABLE_NAME"))
			define("EWRPT_TABLE_NAME", 'one np', TRUE);

		// Start timer
		$GLOBALS["gsTimer"] = new crTimer();

		// Open connection
		$conn = ewrpt_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $ReportLanguage, $Security;
		global $one_np;

	// Get export parameters
	if (@$_GET["export"] <> "") {
		$one_np->Export = $_GET["export"];
	}
	$gsExport = $one_np->Export; // Get export parameter, used in header
	$gsExportFile = $one_np->TableVar; // Get export file, used in header

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;
		global $ReportLanguage;
		global $one_np;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export to Email (use ob_file_contents for PHP)
		if ($one_np->Export == "email") {
			$sContent = ob_get_contents();
			$this->ExportEmail($sContent);
			ob_end_clean();

			 // Close connection
			$conn->Close();
			header("Location: " . ewrpt_CurrentPage());
			exit();
		}

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EWRPT_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}

	// Initialize common variables
	// Paging variables

	var $RecCount = 0; // Record count
	var $StartGrp = 0; // Start group
	var $StopGrp = 0; // Stop group
	var $TotalGrps = 0; // Total groups
	var $GrpCount = 0; // Group count
	var $DisplayGrps = 3; // Groups per page
	var $GrpRange = 10;
	var $Sort = "";
	var $Filter = "";
	var $UserIDFilter = "";

	// Clear field for ext filter
	var $ClearExtFilter = "";
	var $FilterApplied;
	var $ShowFirstHeader;
	var $Cnt, $Col, $Val, $Smry, $Mn, $Mx, $GrandSmry, $GrandMn, $GrandMx;
	var $TotCount;

	//
	// Page main
	//
	function Page_Main() {
		global $one_np;
		global $rs;
		global $rsgrp;
		global $gsFormError;

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 11;
		$nGrps = 1;
		$this->Val = ewrpt_InitArray($nDtls, 0);
		$this->Cnt = ewrpt_Init2DArray($nGrps, $nDtls, 0);
		$this->Smry = ewrpt_Init2DArray($nGrps, $nDtls, 0);
		$this->Mn = ewrpt_Init2DArray($nGrps, $nDtls, NULL);
		$this->Mx = ewrpt_Init2DArray($nGrps, $nDtls, NULL);
		$this->GrandSmry = ewrpt_InitArray($nDtls, 0);
		$this->GrandMn = ewrpt_InitArray($nDtls, NULL);
		$this->GrandMx = ewrpt_InitArray($nDtls, NULL);

		// Set up if accumulation required
		$this->Col = array(FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE);

		// Set up groups per page dynamically
		$this->SetUpDisplayGrps();
		$one_np->np_name->SelectionList = "";
		$one_np->np_name->DefaultSelectionList = "";
		$one_np->np_name->ValueList = "";

		// Load default filter values
		$this->LoadDefaultFilters();

		// Set up popup filter
		$this->SetupPopup();

		// Extended filter
		$sExtendedFilter = "";

		// Get dropdown values
		$this->GetExtendedFilterValues();

		// Load custom filters
		$one_np->CustomFilters_Load();

		// Build extended filter
		$sExtendedFilter = $this->GetExtendedFilter();
		if ($sExtendedFilter <> "") {
			if ($this->Filter <> "")
  				$this->Filter = "($this->Filter) AND ($sExtendedFilter)";
			else
				$this->Filter = $sExtendedFilter;
		}

		// Build popup filter
		$sPopupFilter = $this->GetPopupFilter();

		//ewrpt_SetDebugMsg("popup filter: " . $sPopupFilter);
		if ($sPopupFilter <> "") {
			if ($this->Filter <> "")
				$this->Filter = "($this->Filter) AND ($sPopupFilter)";
			else
				$this->Filter = $sPopupFilter;
		}

		// Check if filter applied
		$this->FilterApplied = $this->CheckFilter();

		// Get sort
		$this->Sort = $this->GetSort();

		// Get total count
		$sSql = ewrpt_BuildReportSql($one_np->SqlSelect(), $one_np->SqlWhere(), $one_np->SqlGroupBy(), $one_np->SqlHaving(), $one_np->SqlOrderBy(), $this->Filter, $this->Sort);
		$this->TotalGrps = $this->GetCnt($sSql);
		if ($this->DisplayGrps <= 0) // Display all groups
			$this->DisplayGrps = $this->TotalGrps;
		$this->StartGrp = 1;

		// Show header
		$this->ShowFirstHeader = ($this->TotalGrps > 0);

		//$this->ShowFirstHeader = TRUE; // Uncomment to always show header
		// Set up start position if not export all

		if ($one_np->ExportAll && $one_np->Export <> "")
		    $this->DisplayGrps = $this->TotalGrps;
		else
			$this->SetUpStartGroup(); 

		// Get current page records
		$rs = $this->GetRs($sSql, $this->StartGrp, $this->DisplayGrps);
	}

	// Accummulate summary
	function AccumulateSummary() {
		$cntx = count($this->Smry);
		for ($ix = 0; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 1; $iy < $cnty; $iy++) {
				$this->Cnt[$ix][$iy]++;
				if ($this->Col[$iy]) {
					$valwrk = $this->Val[$iy];
					if (is_null($valwrk) || !is_numeric($valwrk)) {

						// skip
					} else {
						$this->Smry[$ix][$iy] += $valwrk;
						if (is_null($this->Mn[$ix][$iy])) {
							$this->Mn[$ix][$iy] = $valwrk;
							$this->Mx[$ix][$iy] = $valwrk;
						} else {
							if ($this->Mn[$ix][$iy] > $valwrk) $this->Mn[$ix][$iy] = $valwrk;
							if ($this->Mx[$ix][$iy] < $valwrk) $this->Mx[$ix][$iy] = $valwrk;
						}
					}
				}
			}
		}
		$cntx = count($this->Smry);
		for ($ix = 1; $ix < $cntx; $ix++) {
			$this->Cnt[$ix][0]++;
		}
	}

	// Reset level summary
	function ResetLevelSummary($lvl) {

		// Clear summary values
		$cntx = count($this->Smry);
		for ($ix = $lvl; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 1; $iy < $cnty; $iy++) {
				$this->Cnt[$ix][$iy] = 0;
				if ($this->Col[$iy]) {
					$this->Smry[$ix][$iy] = 0;
					$this->Mn[$ix][$iy] = NULL;
					$this->Mx[$ix][$iy] = NULL;
				}
			}
		}
		$cntx = count($this->Smry);
		for ($ix = $lvl; $ix < $cntx; $ix++) {
			$this->Cnt[$ix][0] = 0;
		}

		// Reset record count
		$this->RecCount = 0;
	}

	// Accummulate grand summary
	function AccumulateGrandSummary() {
		$this->Cnt[0][0]++;
		$cntgs = count($this->GrandSmry);
		for ($iy = 1; $iy < $cntgs; $iy++) {
			if ($this->Col[$iy]) {
				$valwrk = $this->Val[$iy];
				if (is_null($valwrk) || !is_numeric($valwrk)) {

					// skip
				} else {
					$this->GrandSmry[$iy] += $valwrk;
					if (is_null($this->GrandMn[$iy])) {
						$this->GrandMn[$iy] = $valwrk;
						$this->GrandMx[$iy] = $valwrk;
					} else {
						if ($this->GrandMn[$iy] > $valwrk) $this->GrandMn[$iy] = $valwrk;
						if ($this->GrandMx[$iy] < $valwrk) $this->GrandMx[$iy] = $valwrk;
					}
				}
			}
		}
	}

	// Get count
	function GetCnt($sql) {
		global $conn;
		$rscnt = $conn->Execute($sql);
		$cnt = ($rscnt) ? $rscnt->RecordCount() : 0;
		if ($rscnt) $rscnt->Close();
		return $cnt;
	}

	// Get rs
	function GetRs($sql, $start, $grps) {
		global $conn;
		$wrksql = $sql;
		if ($start > 0 && $grps > -1)
			$wrksql .= " LIMIT " . ($start-1) . ", " . ($grps);
		$rswrk = $conn->Execute($wrksql);
		return $rswrk;
	}

	// Get row values
	function GetRow($opt) {
		global $rs;
		global $one_np;
		if (!$rs)
			return;
		if ($opt == 1) { // Get first row

	//		$rs->MoveFirst(); // NOTE: no need to move position
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$one_np->giveback_id->setDbValue($rs->fields('giveback_id'));
			$one_np->np_userid->setDbValue($rs->fields('np_userid'));
			$one_np->np_name->setDbValue($rs->fields('np_name'));
			$one_np->points->setDbValue($rs->fields('points'));
			$one_np->invoice_date->setDbValue($rs->fields('invoice_date'));
			$one_np->user_id->setDbValue($rs->fields('user_id'));
			$one_np->username->setDbValue($rs->fields('username'));
			$one_np->invoice_id->setDbValue($rs->fields('invoice_id'));
			$one_np->buyerorseller->setDbValue($rs->fields('buyerorseller'));
			$one_np->transtype->setDbValue($rs->fields('transtype'));
			$this->Val[1] = $one_np->giveback_id->CurrentValue;
			$this->Val[2] = $one_np->np_userid->CurrentValue;
			$this->Val[3] = $one_np->np_name->CurrentValue;
			$this->Val[4] = $one_np->points->CurrentValue;
			$this->Val[5] = $one_np->invoice_date->CurrentValue;
			$this->Val[6] = $one_np->user_id->CurrentValue;
			$this->Val[7] = $one_np->username->CurrentValue;
			$this->Val[8] = $one_np->invoice_id->CurrentValue;
			$this->Val[9] = $one_np->buyerorseller->CurrentValue;
			$this->Val[10] = $one_np->transtype->CurrentValue;
		} else {
			$one_np->giveback_id->setDbValue("");
			$one_np->np_userid->setDbValue("");
			$one_np->np_name->setDbValue("");
			$one_np->points->setDbValue("");
			$one_np->invoice_date->setDbValue("");
			$one_np->user_id->setDbValue("");
			$one_np->username->setDbValue("");
			$one_np->invoice_id->setDbValue("");
			$one_np->buyerorseller->setDbValue("");
			$one_np->transtype->setDbValue("");
		}
	}

	//  Set up starting group
	function SetUpStartGroup() {
		global $one_np;

		// Exit if no groups
		if ($this->DisplayGrps == 0)
			return;

		// Check for a 'start' parameter
		if (@$_GET[EWRPT_TABLE_START_GROUP] != "") {
			$this->StartGrp = $_GET[EWRPT_TABLE_START_GROUP];
			$one_np->setStartGroup($this->StartGrp);
		} elseif (@$_GET["pageno"] != "") {
			$nPageNo = $_GET["pageno"];
			if (is_numeric($nPageNo)) {
				$this->StartGrp = ($nPageNo-1)*$this->DisplayGrps+1;
				if ($this->StartGrp <= 0) {
					$this->StartGrp = 1;
				} elseif ($this->StartGrp >= intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1) {
					$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1;
				}
				$one_np->setStartGroup($this->StartGrp);
			} else {
				$this->StartGrp = $one_np->getStartGroup();
			}
		} else {
			$this->StartGrp = $one_np->getStartGroup();
		}

		// Check if correct start group counter
		if (!is_numeric($this->StartGrp) || $this->StartGrp == "") { // Avoid invalid start group counter
			$this->StartGrp = 1; // Reset start group counter
			$one_np->setStartGroup($this->StartGrp);
		} elseif (intval($this->StartGrp) > intval($this->TotalGrps)) { // Avoid starting group > total groups
			$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to last page first group
			$one_np->setStartGroup($this->StartGrp);
		} elseif (($this->StartGrp-1) % $this->DisplayGrps <> 0) {
			$this->StartGrp = intval(($this->StartGrp-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to page boundary
			$one_np->setStartGroup($this->StartGrp);
		}
	}

	// Set up popup
	function SetupPopup() {
		global $conn, $ReportLanguage;
		global $one_np;

		// Initialize popup
		// Build distinct values for np_name

		$bNullValue = FALSE;
		$bEmptyValue = FALSE;
		$sSql = ewrpt_BuildReportSql($one_np->np_name->SqlSelect, $one_np->SqlWhere(), $one_np->SqlGroupBy(), $one_np->SqlHaving(), $one_np->np_name->SqlOrderBy, $this->Filter, "");
		$rswrk = $conn->Execute($sSql);
		while ($rswrk && !$rswrk->EOF) {
			$one_np->np_name->setDbValue($rswrk->fields[0]);
			if (is_null($one_np->np_name->CurrentValue)) {
				$bNullValue = TRUE;
			} elseif ($one_np->np_name->CurrentValue == "") {
				$bEmptyValue = TRUE;
			} else {
				$one_np->np_name->ViewValue = $one_np->np_name->CurrentValue;
				ewrpt_SetupDistinctValues($one_np->np_name->ValueList, $one_np->np_name->CurrentValue, $one_np->np_name->ViewValue, FALSE);
			}
			$rswrk->MoveNext();
		}
		if ($rswrk)
			$rswrk->Close();
		if ($bEmptyValue)
			ewrpt_SetupDistinctValues($one_np->np_name->ValueList, EWRPT_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
		if ($bNullValue)
			ewrpt_SetupDistinctValues($one_np->np_name->ValueList, EWRPT_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);

		// Process post back form
		if (ewrpt_IsHttpPost()) {
			$sName = @$_POST["popup"]; // Get popup form name
			if ($sName <> "") {
				$cntValues = (is_array(@$_POST["sel_$sName"])) ? count($_POST["sel_$sName"]) : 0;
				if ($cntValues > 0) {
					$arValues = ewrpt_StripSlashes($_POST["sel_$sName"]);
					if (trim($arValues[0]) == "") // Select all
						$arValues = EWRPT_INIT_VALUE;
					if (!ewrpt_MatchedArray($arValues, $_SESSION["sel_$sName"])) {
						if ($this->HasSessionFilterValues($sName))
							$this->ClearExtFilter = $sName; // Clear extended filter for this field
					}
					$_SESSION["sel_$sName"] = $arValues;
					$_SESSION["rf_$sName"] = ewrpt_StripSlashes(@$_POST["rf_$sName"]);
					$_SESSION["rt_$sName"] = ewrpt_StripSlashes(@$_POST["rt_$sName"]);
					$this->ResetPager();
				}
			}

		// Get 'reset' command
		} elseif (@$_GET["cmd"] <> "") {
			$sCmd = $_GET["cmd"];
			if (strtolower($sCmd) == "reset") {
				$this->ClearSessionSelection('np_name');
				$this->ResetPager();
			}
		}

		// Load selection criteria to array
		// Get np name selected values

		if (is_array(@$_SESSION["sel_one_np_np_name"])) {
			$this->LoadSelectionFromSession('np_name');
		} elseif (@$_SESSION["sel_one_np_np_name"] == EWRPT_INIT_VALUE) { // Select all
			$one_np->np_name->SelectionList = "";
		}
	}

	// Reset pager
	function ResetPager() {

		// Reset start position (reset command)
		global $one_np;
		$this->StartGrp = 1;
		$one_np->setStartGroup($this->StartGrp);
	}

	// Set up number of groups displayed per page
	function SetUpDisplayGrps() {
		global $one_np;
		$sWrk = @$_GET[EWRPT_TABLE_GROUP_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayGrps = intval($sWrk);
			} else {
				if (strtoupper($sWrk) == "ALL") { // display all groups
					$this->DisplayGrps = -1;
				} else {
					$this->DisplayGrps = 3; // Non-numeric, load default
				}
			}
			$one_np->setGroupPerPage($this->DisplayGrps); // Save to session

			// Reset start position (reset command)
			$this->StartGrp = 1;
			$one_np->setStartGroup($this->StartGrp);
		} else {
			if ($one_np->getGroupPerPage() <> "") {
				$this->DisplayGrps = $one_np->getGroupPerPage(); // Restore from session
			} else {
				$this->DisplayGrps = 3; // Load default
			}
		}
	}

	function RenderRow() {
		global $conn, $rs, $Security;
		global $one_np;
		if ($one_np->RowTotalType == EWRPT_ROWTOTAL_GRAND) { // Grand total

			// Get total count from sql directly
			$sSql = ewrpt_BuildReportSql($one_np->SqlSelectCount(), $one_np->SqlWhere(), $one_np->SqlGroupBy(), $one_np->SqlHaving(), "", $this->Filter, "");
			$rstot = $conn->Execute($sSql);
			if ($rstot) {
				$this->TotCount = ($rstot->RecordCount()>1) ? $rstot->RecordCount() : $rstot->fields[0];
				$rstot->Close();
			} else {
				$this->TotCount = 0;
			}
		}

		// Call Row_Rendering event
		$one_np->Row_Rendering();

		//
		// Render view codes
		//

		if ($one_np->RowType == EWRPT_ROWTYPE_TOTAL) { // Summary row

			// giveback_id
			$one_np->giveback_id->ViewValue = $one_np->giveback_id->Summary;

			// np_userid
			$one_np->np_userid->ViewValue = $one_np->np_userid->Summary;

			// np_name
			$one_np->np_name->ViewValue = $one_np->np_name->Summary;

			// points
			$one_np->points->ViewValue = $one_np->points->Summary;

			// invoice_date
			$one_np->invoice_date->ViewValue = $one_np->invoice_date->Summary;

			// user_id
			$one_np->user_id->ViewValue = $one_np->user_id->Summary;

			// username
			$one_np->username->ViewValue = $one_np->username->Summary;

			// invoice_id
			$one_np->invoice_id->ViewValue = $one_np->invoice_id->Summary;

			// buyerorseller
			$one_np->buyerorseller->ViewValue = $one_np->buyerorseller->Summary;

			// transtype
			$one_np->transtype->ViewValue = $one_np->transtype->Summary;
		} else {

			// giveback_id
			$one_np->giveback_id->ViewValue = $one_np->giveback_id->CurrentValue;
			$one_np->giveback_id->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// np_userid
			$one_np->np_userid->ViewValue = $one_np->np_userid->CurrentValue;
			$one_np->np_userid->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// np_name
			$one_np->np_name->ViewValue = $one_np->np_name->CurrentValue;
			$one_np->np_name->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// points
			$one_np->points->ViewValue = $one_np->points->CurrentValue;
			$one_np->points->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// invoice_date
			$one_np->invoice_date->ViewValue = $one_np->invoice_date->CurrentValue;
			$one_np->invoice_date->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// user_id
			$one_np->user_id->ViewValue = $one_np->user_id->CurrentValue;
			$one_np->user_id->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// username
			$one_np->username->ViewValue = $one_np->username->CurrentValue;
			$one_np->username->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// invoice_id
			$one_np->invoice_id->ViewValue = $one_np->invoice_id->CurrentValue;
			$one_np->invoice_id->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// buyerorseller
			$one_np->buyerorseller->ViewValue = $one_np->buyerorseller->CurrentValue;
			$one_np->buyerorseller->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// transtype
			$one_np->transtype->ViewValue = $one_np->transtype->CurrentValue;
			$one_np->transtype->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
		}

		// giveback_id
		$one_np->giveback_id->HrefValue = "";

		// np_userid
		$one_np->np_userid->HrefValue = "";

		// np_name
		$one_np->np_name->HrefValue = "";

		// points
		$one_np->points->HrefValue = "";

		// invoice_date
		$one_np->invoice_date->HrefValue = "";

		// user_id
		$one_np->user_id->HrefValue = "";

		// username
		$one_np->username->HrefValue = "";

		// invoice_id
		$one_np->invoice_id->HrefValue = "";

		// buyerorseller
		$one_np->buyerorseller->HrefValue = "";

		// transtype
		$one_np->transtype->HrefValue = "";

		// Call Row_Rendered event
		$one_np->Row_Rendered();
	}

	// Get extended filter values
	function GetExtendedFilterValues() {
		global $one_np;
	}

	// Return extended filter
	function GetExtendedFilter() {
		global $one_np;
		global $gsFormError;
		$sFilter = "";
		$bPostBack = ewrpt_IsHttpPost();
		$bRestoreSession = TRUE;
		$bSetupFilter = FALSE;

		// Reset extended filter if filter changed
		if ($bPostBack) {

			// Clear extended filter for field np_name
			if ($this->ClearExtFilter == 'one_np_np_name')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'np_name');

		// Reset search command
		} elseif (@$_GET["cmd"] == "reset") {

			// Load default values
			// Field np_name

			$this->SetSessionFilterValues($one_np->np_name->SearchValue, $one_np->np_name->SearchOperator, $one_np->np_name->SearchCondition, $one_np->np_name->SearchValue2, $one_np->np_name->SearchOperator2, 'np_name');
			$bSetupFilter = TRUE;
		} else {

			// Field np_name
			if ($this->GetFilterValues($one_np->np_name)) {
				$bSetupFilter = TRUE;
				$bRestoreSession = FALSE;
			}
			if (!$this->ValidateForm()) {
				$this->setMessage($gsFormError);
				return $sFilter;
			}
		}

		// Restore session
		if ($bRestoreSession) {

			// Field np_name
			$this->GetSessionFilterValues($one_np->np_name);
		}

		// Call page filter validated event
		$one_np->Page_FilterValidated();

		// Build SQL
		// Field np_name

		$this->BuildExtendedFilter($one_np->np_name, $sFilter);

		// Save parms to session
		// Field np_name

		$this->SetSessionFilterValues($one_np->np_name->SearchValue, $one_np->np_name->SearchOperator, $one_np->np_name->SearchCondition, $one_np->np_name->SearchValue2, $one_np->np_name->SearchOperator2, 'np_name');

		// Setup filter
		if ($bSetupFilter) {

			// Field np_name
			$sWrk = "";
			$this->BuildExtendedFilter($one_np->np_name, $sWrk);
			$this->LoadSelectionFromFilter($one_np->np_name, $sWrk, $one_np->np_name->SelectionList);
			$_SESSION['sel_one_np_np_name'] = ($one_np->np_name->SelectionList == "") ? EWRPT_INIT_VALUE : $one_np->np_name->SelectionList;
		}
		return $sFilter;
	}

	// Get drop down value from querystring
	function GetDropDownValue(&$sv, $parm) {
		if (ewrpt_IsHttpPost())
			return FALSE; // Skip post back
		if (isset($_GET["sv_$parm"])) {
			$sv = ewrpt_StripSlashes($_GET["sv_$parm"]);
			return TRUE;
		}
		return FALSE;
	}

	// Get filter values from querystring
	function GetFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		if (ewrpt_IsHttpPost())
			return; // Skip post back
		$got = FALSE;
		if (isset($_GET["sv1_$parm"])) {
			$fld->SearchValue = ewrpt_StripSlashes($_GET["sv1_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["so1_$parm"])) {
			$fld->SearchOperator = ewrpt_StripSlashes($_GET["so1_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["sc_$parm"])) {
			$fld->SearchCondition = ewrpt_StripSlashes($_GET["sc_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["sv2_$parm"])) {
			$fld->SearchValue2 = ewrpt_StripSlashes($_GET["sv2_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["so2_$parm"])) {
			$fld->SearchOperator2 = ewrpt_StripSlashes($_GET["so2_$parm"]);
			$got = TRUE;
		}
		return $got;
	}

	// Set default ext filter
	function SetDefaultExtFilter(&$fld, $so1, $sv1, $sc, $so2, $sv2) {
		$fld->DefaultSearchValue = $sv1; // Default ext filter value 1
		$fld->DefaultSearchValue2 = $sv2; // Default ext filter value 2 (if operator 2 is enabled)
		$fld->DefaultSearchOperator = $so1; // Default search operator 1
		$fld->DefaultSearchOperator2 = $so2; // Default search operator 2 (if operator 2 is enabled)
		$fld->DefaultSearchCondition = $sc; // Default search condition (if operator 2 is enabled)
	}

	// Apply default ext filter
	function ApplyDefaultExtFilter(&$fld) {
		$fld->SearchValue = $fld->DefaultSearchValue;
		$fld->SearchValue2 = $fld->DefaultSearchValue2;
		$fld->SearchOperator = $fld->DefaultSearchOperator;
		$fld->SearchOperator2 = $fld->DefaultSearchOperator2;
		$fld->SearchCondition = $fld->DefaultSearchCondition;
	}

	// Check if Text Filter applied
	function TextFilterApplied(&$fld) {
		return (strval($fld->SearchValue) <> strval($fld->DefaultSearchValue) ||
			strval($fld->SearchValue2) <> strval($fld->DefaultSearchValue2) ||
			(strval($fld->SearchValue) <> "" &&
				strval($fld->SearchOperator) <> strval($fld->DefaultSearchOperator)) ||
			(strval($fld->SearchValue2) <> "" &&
				strval($fld->SearchOperator2) <> strval($fld->DefaultSearchOperator2)) ||
			strval($fld->SearchCondition) <> strval($fld->DefaultSearchCondition));
	}

	// Check if Non-Text Filter applied
	function NonTextFilterApplied(&$fld) {
		if (is_array($fld->DefaultDropDownValue) && is_array($fld->DropDownValue)) {
			if (count($fld->DefaultDropDownValue) <> count($fld->DropDownValue))
				return TRUE;
			else
				return (count(array_diff($fld->DefaultDropDownValue, $fld->DropDownValue)) <> 0);
		}
		else {
			$v1 = strval($fld->DefaultDropDownValue);
			if ($v1 == EWRPT_INIT_VALUE)
				$v1 = "";
			$v2 = strval($fld->DropDownValue);
			if ($v2 == EWRPT_INIT_VALUE || $v2 == EWRPT_ALL_VALUE)
				$v2 = "";
			return ($v1 <> $v2);
		}
	}

	// Load selection from a filter clause
	function LoadSelectionFromFilter(&$fld, $filter, &$sel) {
		$sel = "";
		if ($filter <> "") {
			$sSql = ewrpt_BuildReportSql($fld->SqlSelect, "", "", "", $fld->SqlOrderBy, $filter, "");
			ewrpt_LoadArrayFromSql($sSql, $sel);
		}
	}

	// Get dropdown value from session
	function GetSessionDropDownValue(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->DropDownValue, 'sv_one_np_' . $parm);
	}

	// Get filter values from session
	function GetSessionFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->SearchValue, 'sv1_one_np_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so1_one_np_' . $parm);
		$this->GetSessionValue($fld->SearchCondition, 'sc_one_np_' . $parm);
		$this->GetSessionValue($fld->SearchValue2, 'sv2_one_np_' . $parm);
		$this->GetSessionValue($fld->SearchOperator2, 'so2_one_np_' . $parm);
	}

	// Get value from session
	function GetSessionValue(&$sv, $sn) {
		if (isset($_SESSION[$sn]))
			$sv = $_SESSION[$sn];
	}

	// Set dropdown value to session
	function SetSessionDropDownValue($sv, $parm) {
		$_SESSION['sv_one_np_' . $parm] = $sv;
	}

	// Set filter values to session
	function SetSessionFilterValues($sv1, $so1, $sc, $sv2, $so2, $parm) {
		$_SESSION['sv1_one_np_' . $parm] = $sv1;
		$_SESSION['so1_one_np_' . $parm] = $so1;
		$_SESSION['sc_one_np_' . $parm] = $sc;
		$_SESSION['sv2_one_np_' . $parm] = $sv2;
		$_SESSION['so2_one_np_' . $parm] = $so2;
	}

	// Check if has Session filter values
	function HasSessionFilterValues($parm) {
		return ((@$_SESSION['sv_' . $parm] <> "" && @$_SESSION['sv_' . $parm] <> EWRPT_INIT_VALUE) ||
			(@$_SESSION['sv1_' . $parm] <> "" && @$_SESSION['sv1_' . $parm] <> EWRPT_INIT_VALUE) ||
			(@$_SESSION['sv2_' . $parm] <> "" && @$_SESSION['sv2_' . $parm] <> EWRPT_INIT_VALUE));
	}

	// Dropdown filter exist
	function DropDownFilterExist(&$fld, $FldOpr) {
		$sWrk = "";
		$this->BuildDropDownFilter($fld, $sWrk, $FldOpr);
		return ($sWrk <> "");
	}

	// Build dropdown filter
	function BuildDropDownFilter(&$fld, &$FilterClause, $FldOpr) {
		$FldVal = $fld->DropDownValue;
		$sSql = "";
		if (is_array($FldVal)) {
			foreach ($FldVal as $val) {
				$sWrk = $this->GetDropDownfilter($fld, $val, $FldOpr);
				if ($sWrk <> "") {
					if ($sSql <> "")
						$sSql .= " OR " . $sWrk;
					else
						$sSql = $sWrk;
				}
			}
		} else {
			$sSql = $this->GetDropDownfilter($fld, $FldVal, $FldOpr);
		}
		if ($sSql <> "") {
			if ($FilterClause <> "") $FilterClause = "(" . $FilterClause . ") AND ";
			$FilterClause .= "(" . $sSql . ")";
		}
	}

	function GetDropDownfilter(&$fld, $FldVal, $FldOpr) {
		$FldName = $fld->FldName;
		$FldExpression = $fld->FldExpression;
		$FldDataType = $fld->FldDataType;
		$sWrk = "";
		if ($FldVal == EWRPT_NULL_VALUE) {
			$sWrk = $FldExpression . " IS NULL";
		} elseif ($FldVal == EWRPT_EMPTY_VALUE) {
			$sWrk = $FldExpression . " = ''";
		} else {
			if (substr($FldVal, 0, 2) == "@@") {
				$sWrk = ewrpt_getCustomFilter($fld, $FldVal);
			} else {
				if ($FldVal <> "" && $FldVal <> EWRPT_INIT_VALUE && $FldVal <> EWRPT_ALL_VALUE) {
					if ($FldDataType == EWRPT_DATATYPE_DATE && $FldOpr <> "") {
						$sWrk = $this->DateFilterString($FldOpr, $FldVal, $FldDataType);
					} else {
						$sWrk = $this->FilterString("=", $FldVal, $FldDataType);
					}
				}
				if ($sWrk <> "") $sWrk = $FldExpression . $sWrk;
			}
		}
		return $sWrk;
	}

	// Extended filter exist
	function ExtendedFilterExist(&$fld) {
		$sExtWrk = "";
		$this->BuildExtendedFilter($fld, $sExtWrk);
		return ($sExtWrk <> "");
	}

	// Build extended filter
	function BuildExtendedFilter(&$fld, &$FilterClause) {
		$FldName = $fld->FldName;
		$FldExpression = $fld->FldExpression;
		$FldDataType = $fld->FldDataType;
		$FldDateTimeFormat = $fld->FldDateTimeFormat;
		$FldVal1 = $fld->SearchValue;
		$FldOpr1 = $fld->SearchOperator;
		$FldCond = $fld->SearchCondition;
		$FldVal2 = $fld->SearchValue2;
		$FldOpr2 = $fld->SearchOperator2;
		$sWrk = "";
		$FldOpr1 = strtoupper(trim($FldOpr1));
		if ($FldOpr1 == "") $FldOpr1 = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		$wrkFldVal1 = $FldVal1;
		$wrkFldVal2 = $FldVal2;
		if ($FldDataType == EWRPT_DATATYPE_BOOLEAN) {
			if (EWRPT_IS_MSACCESS) {
				if ($wrkFldVal1 <> "") $wrkFldVal1 = ($wrkFldVal1 == "1") ? "True" : "False";
				if ($wrkFldVal2 <> "") $wrkFldVal2 = ($wrkFldVal2 == "1") ? "True" : "False";
			} else {

				//if ($wrkFldVal1 <> "") $wrkFldVal1 = ($wrkFldVal1 == "1") ? EWRPT_TRUE_STRING : EWRPT_FALSE_STRING;
				//if ($wrkFldVal2 <> "") $wrkFldVal2 = ($wrkFldVal2 == "1") ? EWRPT_TRUE_STRING : EWRPT_FALSE_STRING;

				if ($wrkFldVal1 <> "") $wrkFldVal1 = ($wrkFldVal1 == "1") ? "1" : "0";
				if ($wrkFldVal2 <> "") $wrkFldVal2 = ($wrkFldVal2 == "1") ? "1" : "0";
			}
		} elseif ($FldDataType == EWRPT_DATATYPE_DATE) {
			if ($wrkFldVal1 <> "") $wrkFldVal1 = ewrpt_UnFormatDateTime($wrkFldVal1, $FldDateTimeFormat);
			if ($wrkFldVal2 <> "") $wrkFldVal2 = ewrpt_UnFormatDateTime($wrkFldVal2, $FldDateTimeFormat);
		}
		if ($FldOpr1 == "BETWEEN") {
			$IsValidValue = ($FldDataType <> EWRPT_DATATYPE_NUMBER ||
				($FldDataType == EWRPT_DATATYPE_NUMBER && is_numeric($wrkFldVal1) && is_numeric($wrkFldVal2)));
			if ($wrkFldVal1 <> "" && $wrkFldVal2 <> "" && $IsValidValue)
				$sWrk = $FldExpression . " BETWEEN " . ewrpt_QuotedValue($wrkFldVal1, $FldDataType) .
					" AND " . ewrpt_QuotedValue($wrkFldVal2, $FldDataType);
		} elseif ($FldOpr1 == "IS NULL" || $FldOpr1 == "IS NOT NULL") {
			$sWrk = $FldExpression . " " . $wrkFldVal1;
		} else {
			$IsValidValue = ($FldDataType <> EWRPT_DATATYPE_NUMBER ||
				($FldDataType == EWRPT_DATATYPE_NUMBER && is_numeric($wrkFldVal1)));
			if ($wrkFldVal1 <> "" && $IsValidValue && ewrpt_IsValidOpr($FldOpr1, $FldDataType))
				$sWrk = $FldExpression . $this->FilterString($FldOpr1, $wrkFldVal1, $FldDataType);
			$IsValidValue = ($FldDataType <> EWRPT_DATATYPE_NUMBER ||
				($FldDataType == EWRPT_DATATYPE_NUMBER && is_numeric($wrkFldVal2)));
			if ($wrkFldVal2 <> "" && $IsValidValue && ewrpt_IsValidOpr($FldOpr2, $FldDataType)) {
				if ($sWrk <> "")
					$sWrk .= " " . (($FldCond == "OR") ? "OR" : "AND") . " ";
				$sWrk .= $FldExpression . $this->FilterString($FldOpr2, $wrkFldVal2, $FldDataType);
			}
		}
		if ($sWrk <> "") {
			if ($FilterClause <> "") $FilterClause .= " AND ";
			$FilterClause .= "(" . $sWrk . ")";
		}
	}

	// Validate form
	function ValidateForm() {
		global $ReportLanguage, $gsFormError, $one_np;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EWRPT_SERVER_VALIDATE)
			return ($gsFormError == "");

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			$gsFormError .= ($gsFormError <> "") ? "<br />" : "";
			$gsFormError .= $sFormCustomError;
		}
		return $ValidateForm;
	}

	// Return filter string
	function FilterString($FldOpr, $FldVal, $FldType) {
		if ($FldOpr == "LIKE" || $FldOpr == "NOT LIKE") {
			return " " . $FldOpr . " " . ewrpt_QuotedValue("%$FldVal%", $FldType);
		} elseif ($FldOpr == "STARTS WITH") {
			return " LIKE " . ewrpt_QuotedValue("$FldVal%", $FldType);
		} else {
			return " $FldOpr " . ewrpt_QuotedValue($FldVal, $FldType);
		}
	}

	// Return date search string
	function DateFilterString($FldOpr, $FldVal, $FldType) {
		$wrkVal1 = ewrpt_DateVal($FldOpr, $FldVal, 1);
		$wrkVal2 = ewrpt_DateVal($FldOpr, $FldVal, 2);
		if ($wrkVal1 <> "" && $wrkVal2 <> "") {
			return " BETWEEN " . ewrpt_QuotedValue($wrkVal1, $FldType) . " AND " . ewrpt_QuotedValue($wrkVal2, $FldType);
		} else {
			return "";
		}
	}

	// Clear selection stored in session
	function ClearSessionSelection($parm) {
		$_SESSION["sel_one_np_$parm"] = "";
		$_SESSION["rf_one_np_$parm"] = "";
		$_SESSION["rt_one_np_$parm"] = "";
	}

	// Load selection from session
	function LoadSelectionFromSession($parm) {
		global $one_np;
		$fld =& $one_np->fields($parm);
		$fld->SelectionList = @$_SESSION["sel_one_np_$parm"];
		$fld->RangeFrom = @$_SESSION["rf_one_np_$parm"];
		$fld->RangeTo = @$_SESSION["rt_one_np_$parm"];
	}

	// Load default value for filters
	function LoadDefaultFilters() {
		global $one_np;

		/**
		* Set up default values for non Text filters
		*/

		/**
		* Set up default values for extended filters
		* function SetDefaultExtFilter(&$fld, $so1, $sv1, $sc, $so2, $sv2)
		* Parameters:
		* $fld - Field object
		* $so1 - Default search operator 1
		* $sv1 - Default ext filter value 1
		* $sc - Default search condition (if operator 2 is enabled)
		* $so2 - Default search operator 2 (if operator 2 is enabled)
		* $sv2 - Default ext filter value 2 (if operator 2 is enabled)
		*/

		// Field np_name
		$this->SetDefaultExtFilter($one_np->np_name, "LIKE", 'Central High School', 'AND', "USER SELECT", NULL);
		$this->ApplyDefaultExtFilter($one_np->np_name);
		$sWrk = "";
		$this->BuildExtendedFilter($one_np->np_name, $sWrk);
		$this->LoadSelectionFromFilter($one_np->np_name, $sWrk, $one_np->np_name->DefaultSelectionList);
		$one_np->np_name->SelectionList = $one_np->np_name->DefaultSelectionList;

		/**
		* Set up default values for popup filters
		* NOTE: if extended filter is enabled, use default values in extended filter instead
		*/
	}

	// Check if filter applied
	function CheckFilter() {
		global $one_np;

		// Check np_name text filter
		if ($this->TextFilterApplied($one_np->np_name))
			return TRUE;

		// Check np_name popup filter
		if (!ewrpt_MatchedArray($one_np->np_name->DefaultSelectionList, $one_np->np_name->SelectionList))
			return TRUE;
		return FALSE;
	}

	// Show list of filters
	function ShowFilterList() {
		global $one_np;
		global $ReportLanguage;

		// Initialize
		$sFilterList = "";

		// Field np_name
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($one_np->np_name, $sExtWrk);
		if (is_array($one_np->np_name->SelectionList))
			$sWrk = ewrpt_JoinArray($one_np->np_name->SelectionList, ", ", EWRPT_DATATYPE_STRING);
		if ($sExtWrk <> "" || $sWrk <> "")
			$sFilterList .= $one_np->np_name->FldCaption() . "<br />";
		if ($sExtWrk <> "")
			$sFilterList .= "&nbsp;&nbsp;$sExtWrk<br />";
		if ($sWrk <> "")
			$sFilterList .= "&nbsp;&nbsp;$sWrk<br />";

		// Show Filters
		if ($sFilterList <> "")
			echo $ReportLanguage->Phrase("CurrentFilters") . "<br />$sFilterList";
	}

	// Return poup filter
	function GetPopupFilter() {
		global $one_np;
		$sWrk = "";
		if (!$this->ExtendedFilterExist($one_np->np_name)) {
			if (is_array($one_np->np_name->SelectionList)) {
				if ($sWrk <> "") $sWrk .= " AND ";
				$sWrk .= ewrpt_FilterSQL($one_np->np_name, "`np_name`", EWRPT_DATATYPE_STRING);
			}
		}
		return $sWrk;
	}

	//-------------------------------------------------------------------------------
	// Function GetSort
	// - Return Sort parameters based on Sort Links clicked
	// - Variables setup: Session[EWRPT_TABLE_SESSION_ORDER_BY], Session["sort_Table_Field"]
	function GetSort() {
		global $one_np;

		// Check for a resetsort command
		if (strlen(@$_GET["cmd"]) > 0) {
			$sCmd = @$_GET["cmd"];
			if ($sCmd == "resetsort") {
				$one_np->setOrderBy("");
				$one_np->setStartGroup(1);
				$one_np->giveback_id->setSort("");
				$one_np->np_userid->setSort("");
				$one_np->np_name->setSort("");
				$one_np->points->setSort("");
				$one_np->invoice_date->setSort("");
				$one_np->user_id->setSort("");
				$one_np->username->setSort("");
				$one_np->invoice_id->setSort("");
				$one_np->buyerorseller->setSort("");
				$one_np->transtype->setSort("");
			}

		// Check for an Order parameter
		} elseif (@$_GET["order"] <> "") {
			$one_np->CurrentOrder = ewrpt_StripSlashes(@$_GET["order"]);
			$one_np->CurrentOrderType = @$_GET["ordertype"];
			$sSortSql = $one_np->SortSql();
			$one_np->setOrderBy($sSortSql);
			$one_np->setStartGroup(1);
		}
		return $one_np->getOrderBy();
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Message Showing event
	function Message_Showing(&$msg) {

		// Example:
		//$msg = "your new message";

	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
