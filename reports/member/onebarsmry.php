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
$onebar = NULL;

//
// Table class for onebar
//
class cronebar {
	var $TableVar = 'onebar';
	var $TableName = 'onebar';
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
	var $Chart1;
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
	function cronebar() {
		global $ReportLanguage;

		// giveback_id
		$this->giveback_id = new crField('onebar', 'onebar', 'x_giveback_id', 'giveback_id', '`giveback_id`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->giveback_id->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['giveback_id'] =& $this->giveback_id;
		$this->giveback_id->DateFilter = "";
		$this->giveback_id->SqlSelect = "";
		$this->giveback_id->SqlOrderBy = "";

		// np_userid
		$this->np_userid = new crField('onebar', 'onebar', 'x_np_userid', 'np_userid', '`np_userid`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->np_userid->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['np_userid'] =& $this->np_userid;
		$this->np_userid->DateFilter = "";
		$this->np_userid->SqlSelect = "";
		$this->np_userid->SqlOrderBy = "";

		// np_name
		$this->np_name = new crField('onebar', 'onebar', 'x_np_name', 'np_name', '`np_name`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->np_name->GroupingFieldId = 2;
		$this->fields['np_name'] =& $this->np_name;
		$this->np_name->DateFilter = "";
		$this->np_name->SqlSelect = "";
		$this->np_name->SqlOrderBy = "";
		$this->np_name->FldGroupByType = "";
		$this->np_name->FldGroupInt = "0";
		$this->np_name->FldGroupSql = "";

		// points
		$this->points = new crField('onebar', 'onebar', 'x_points', 'points', '`points`', 5, EWRPT_DATATYPE_NUMBER, -1);
		$this->points->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['points'] =& $this->points;
		$this->points->DateFilter = "";
		$this->points->SqlSelect = "";
		$this->points->SqlOrderBy = "";

		// invoice_date
		$this->invoice_date = new crField('onebar', 'onebar', 'x_invoice_date', 'invoice_date', '`invoice_date`', 133, EWRPT_DATATYPE_DATE, 5);
		$this->invoice_date->GroupingFieldId = 3;
		$this->invoice_date->FldDefaultErrMsg = str_replace("%s", "/", $ReportLanguage->Phrase("IncorrectDateYMD"));
		$this->fields['invoice_date'] =& $this->invoice_date;
		$this->invoice_date->DateFilter = "Month";
		$this->invoice_date->SqlSelect = "SELECT DISTINCT `invoice_date`, CONCAT(CAST(YEAR(`invoice_date`) AS CHAR(4)), '|', CAST(LPAD(MONTH(`invoice_date`),2,'0') AS CHAR(2))) AS ew_report_groupvalue FROM " . $this->SqlFrom();
		$this->invoice_date->SqlOrderBy = "CONCAT(CAST(YEAR(`invoice_date`) AS CHAR(4)), '|', CAST(LPAD(MONTH(`invoice_date`),2,'0') AS CHAR(2)))";
		$this->invoice_date->FldGroupByType = "m";
		$this->invoice_date->FldGroupInt = "0";
		$this->invoice_date->FldGroupSql = "CONCAT(CAST(YEAR(%s) AS CHAR(4)), '|', CAST(LPAD(MONTH(%s),2,'0') AS CHAR(2)))";
		$this->invoice_date->AdvancedFilters = array(); // Popup filter for invoice_date
		$this->invoice_date->AdvancedFilters[0][0] = "@@1";
		$this->invoice_date->AdvancedFilters[0][1] = $ReportLanguage->Phrase("LastMonth");
		$this->invoice_date->AdvancedFilters[0][2] = ewrpt_IsLastMonth(); // Return sql part
		$this->invoice_date->AdvancedFilters[1][0] = "@@2";
		$this->invoice_date->AdvancedFilters[1][1] = $ReportLanguage->Phrase("ThisMonth");
		$this->invoice_date->AdvancedFilters[1][2] = ewrpt_IsThisMonth(); // Return sql part
		$this->invoice_date->AdvancedFilters[2][0] = "@@3";
		$this->invoice_date->AdvancedFilters[2][1] = $ReportLanguage->Phrase("NextMonth");
		$this->invoice_date->AdvancedFilters[2][2] = ewrpt_IsNextMonth(); // Return sql part
		$this->invoice_date->AdvancedFilters[3][0] = "@@4";
		$this->invoice_date->AdvancedFilters[3][1] = $ReportLanguage->Phrase("LastTwoWeeks");
		$this->invoice_date->AdvancedFilters[3][2] = ewrpt_IsLast2Weeks(); // Return sql part
		$this->invoice_date->AdvancedFilters[4][0] = "@@5";
		$this->invoice_date->AdvancedFilters[4][1] = $ReportLanguage->Phrase("LastWeek");
		$this->invoice_date->AdvancedFilters[4][2] = ewrpt_IsLastWeek(); // Return sql part
		$this->invoice_date->AdvancedFilters[5][0] = "@@6";
		$this->invoice_date->AdvancedFilters[5][1] = $ReportLanguage->Phrase("ThisWeek");
		$this->invoice_date->AdvancedFilters[5][2] = ewrpt_IsThisWeek(); // Return sql part
		$this->invoice_date->AdvancedFilters[6][0] = "@@7";
		$this->invoice_date->AdvancedFilters[6][1] = $ReportLanguage->Phrase("NextWeek");
		$this->invoice_date->AdvancedFilters[6][2] = ewrpt_IsNextWeek(); // Return sql part
		$this->invoice_date->AdvancedFilters[7][0] = "@@8";
		$this->invoice_date->AdvancedFilters[7][1] = $ReportLanguage->Phrase("NextTwoWeeks");
		$this->invoice_date->AdvancedFilters[7][2] = ewrpt_IsNext2Weeks(); // Return sql part

		// user_id
		$this->user_id = new crField('onebar', 'onebar', 'x_user_id', 'user_id', '`user_id`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->user_id->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['user_id'] =& $this->user_id;
		$this->user_id->DateFilter = "";
		$this->user_id->SqlSelect = "";
		$this->user_id->SqlOrderBy = "";

		// username
		$this->username = new crField('onebar', 'onebar', 'x_username', 'username', '`username`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->username->GroupingFieldId = 1;
		$this->fields['username'] =& $this->username;
		$this->username->DateFilter = "";
		$this->username->SqlSelect = "";
		$this->username->SqlOrderBy = "";
		$this->username->FldGroupByType = "";
		$this->username->FldGroupInt = "0";
		$this->username->FldGroupSql = "";

		// invoice_id
		$this->invoice_id = new crField('onebar', 'onebar', 'x_invoice_id', 'invoice_id', '`invoice_id`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->invoice_id->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['invoice_id'] =& $this->invoice_id;
		$this->invoice_id->DateFilter = "";
		$this->invoice_id->SqlSelect = "";
		$this->invoice_id->SqlOrderBy = "";

		// buyerorseller
		$this->buyerorseller = new crField('onebar', 'onebar', 'x_buyerorseller', 'buyerorseller', '`buyerorseller`', 16, EWRPT_DATATYPE_NUMBER, -1);
		$this->buyerorseller->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['buyerorseller'] =& $this->buyerorseller;
		$this->buyerorseller->DateFilter = "";
		$this->buyerorseller->SqlSelect = "";
		$this->buyerorseller->SqlOrderBy = "";

		// transtype
		$this->transtype = new crField('onebar', 'onebar', 'x_transtype', 'transtype', '`transtype`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->fields['transtype'] =& $this->transtype;
		$this->transtype->DateFilter = "";
		$this->transtype->SqlSelect = "";
		$this->transtype->SqlOrderBy = "";

		// Chart1
		$this->Chart1 = new crChart('onebar', 'onebar', 'Chart1', 'Chart1', 'invoice_date', 'points', '', 5, 'SUM', 550, 440);
		$this->Chart1->SqlSelect = "SELECT `invoice_date`, '', SUM(`points`) FROM ";
		$this->Chart1->SqlGroupBy = "`invoice_date`";
		$this->Chart1->SqlOrderBy = "`invoice_date` ASC";
		$this->Chart1->SeriesDateType = "";
		$this->Chart1->XAxisDateFormat = 5;
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
		return "`username` ASC, `np_name` ASC, CONCAT(CAST(YEAR(`invoice_date`) AS CHAR(4)), '|', CAST(LPAD(MONTH(`invoice_date`),2,'0') AS CHAR(2))) ASC";
	}

	// Table Level Group SQL
	function SqlFirstGroupField() {
		return "`username`";
	}

	function SqlSelectGroup() {
		return "SELECT DISTINCT " . $this->SqlFirstGroupField() . " FROM " . $this->SqlFrom();
	}

	function SqlOrderByGroup() {
		return "`username` ASC";
	}

	function SqlSelectAgg() {
		return "SELECT SUM(`points`) AS sum_points FROM " . $this->SqlFrom();
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
$onebar_summary = new cronebar_summary();
$Page =& $onebar_summary;

// Page init
$onebar_summary->Page_Init();

// Page main
$onebar_summary->Page_Main();
?>
<?php include "phprptinc/header.php"; ?>
<?php if ($onebar->Export == "") { ?>
<script type="text/javascript">

// Create page object
var onebar_summary = new ewrpt_Page("onebar_summary");

// page properties
onebar_summary.PageID = "summary"; // page ID
onebar_summary.FormID = "fonebarsummaryfilter"; // form ID
var EWRPT_PAGE_ID = onebar_summary.PageID;

// extend page with ValidateForm function
onebar_summary.ValidateForm = function(fobj) {
	if (!this.ValidateRequired)
		return true; // ignore validation

	// Call Form Custom Validate event
	if (!this.Form_CustomValidate(fobj)) return false;
	return true;
}

// extend page with Form_CustomValidate function
onebar_summary.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }
<?php if (EWRPT_CLIENT_VALIDATE) { ?>
onebar_summary.ValidateRequired = true; // uses JavaScript validation
<?php } else { ?>
onebar_summary.ValidateRequired = false; // no JavaScript validation
<?php } ?>
</script>
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-1.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<script language="JavaScript" type="text/javascript">
<!--

// Write your client script here, no need to add script tags.
// To include another .js script, use:
// ew_ClientScriptInclude("my_javascript.js"); 
//-->

</script>
<?php } ?>
<?php $onebar_summary->ShowPageHeader(); ?>
<?php $onebar_summary->ShowMessage(); ?>
<?php if ($onebar->Export == "" || $onebar->Export == "print" || $onebar->Export == "email") { ?>
<script src="FusionChartsFree/JSClass/FusionCharts.js" type="text/javascript"></script>
<?php } ?>
<?php if ($onebar->Export == "") { ?>
<script src="phprptjs/popup.js" type="text/javascript"></script>
<script src="phprptjs/ewrptpop.js" type="text/javascript"></script>
<script type="text/javascript">

// popup fields
<?php $jsdata = ewrpt_GetJsData($onebar->invoice_date, EWRPT_DATATYPE_NONE); ?>
ewrpt_CreatePopup("onebar_invoice_date", [<?php echo $jsdata ?>]);
</script>
<div id="onebar_invoice_date_Popup" class="ewPopup">
<span class="phpreportmaker"></span>
</div>
<?php } ?>
<?php if ($onebar->Export == "") { ?>
<!-- Table Container (Begin) -->
<table id="ewContainer" cellspacing="0" cellpadding="0" border="0">
<!-- Top Container (Begin) -->
<tr><td colspan="3"><div id="ewTop" class="phpreportmaker">
<!-- top slot -->
<a name="top"></a>
<?php } ?>
<?php if ($onebar->Export == "" || $onebar->Export == "print" || $onebar->Export == "email") { ?>
<?php } ?>
<?php echo $onebar->TableCaption() ?>
<?php if ($onebar->Export == "") { ?>
&nbsp;&nbsp;<a href="<?php echo $onebar_summary->ExportExcelUrl ?>"><?php echo $ReportLanguage->Phrase("ExportToExcel") ?></a>
&nbsp;&nbsp;<a href="<?php echo $onebar_summary->ExportWordUrl ?>"><?php echo $ReportLanguage->Phrase("ExportToWord") ?></a>
&nbsp;&nbsp;<a name="emf_onebar" id="emf_onebar" href="javascript:void(0);" onclick="ewrpt_EmailDialogShow({lnk:'emf_onebar',hdr:ewLanguage.Phrase('ExportToEmail')});"><?php echo $ReportLanguage->Phrase("ExportToEmail") ?></a>
<?php if ($onebar_summary->FilterApplied) { ?>
&nbsp;&nbsp;<a href="onebarsmry.php?cmd=reset"><?php echo $ReportLanguage->Phrase("ResetAllFilter") ?></a>
<?php } ?>
<?php } ?>
<br /><br />
<?php if ($onebar->Export == "") { ?>
</div></td></tr>
<!-- Top Container (End) -->
<tr>
	<!-- Left Container (Begin) -->
	<td style="vertical-align: top;"><div id="ewLeft" class="phpreportmaker">
	<!-- Left slot -->
<?php } ?>
<?php if ($onebar->Export == "" || $onebar->Export == "print" || $onebar->Export == "email") { ?>
<?php } ?>
<?php if ($onebar->Export == "") { ?>
	</div></td>
	<!-- Left Container (End) -->
	<!-- Center Container - Report (Begin) -->
	<td style="vertical-align: top;" class="ewPadding"><div id="ewCenter" class="phpreportmaker">
	<!-- center slot -->
<?php } ?>
<!-- summary report starts -->
<div id="report_summary">
<?php if ($onebar->Export == "") { ?>
<?php
if ($onebar->FilterPanelOption == 2 || ($onebar->FilterPanelOption == 3 && $onebar_summary->FilterApplied) || $onebar_summary->Filter == "0=101") {
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
<form name="fonebarsummaryfilter" id="fonebarsummaryfilter" action="onebarsmry.php" class="ewForm" onsubmit="return onebar_summary.ValidateForm(this);">
<table class="ewRptExtFilter">
	<tr>
		<td><span class="phpreportmaker"><?php echo $onebar->invoice_date->FldCaption() ?></span></td>
		<td></td>
		<td colspan="4"><span class="ewRptSearchOpr">
		<select name="sv_invoice_date" id="sv_invoice_date"<?php echo ($onebar_summary->ClearExtFilter == 'onebar_invoice_date') ? " class=\"ewInputCleared\"" : "" ?>>
		<option value="<?php echo EWRPT_ALL_VALUE; ?>"<?php if (ewrpt_MatchedFilterValue($onebar->invoice_date->DropDownValue, EWRPT_ALL_VALUE)) echo " selected=\"selected\""; ?>><?php echo $ReportLanguage->Phrase("PleaseSelect"); ?></option>
<?php

// Popup filter
$cntf = is_array($onebar->invoice_date->CustomFilters) ? count($onebar->invoice_date->CustomFilters) : 0;
$cntd = is_array($onebar->invoice_date->DropDownList) ? count($onebar->invoice_date->DropDownList) : 0;
$totcnt = $cntf + $cntd;
$wrkcnt = 0;
	for ($i = 0; $i < $cntf; $i++) {
		if ($onebar->invoice_date->CustomFilters[$i]->FldName == 'invoice_date') {
?>
		<option value="<?php echo "@@" . $onebar->invoice_date->CustomFilters[$i]->FilterName ?>"<?php if (ewrpt_MatchedFilterValue($onebar->invoice_date->DropDownValue, "@@" . $onebar->invoice_date->CustomFilters[$i]->FilterName)) echo " selected=\"selected\"" ?>><?php echo $onebar->invoice_date->CustomFilters[$i]->DisplayName ?></option>
<?php
		}
		$wrkcnt += 1;
	}

//}
	for ($i = 0; $i < $cntd; $i++) {
?>
		<option value="<?php echo $onebar->invoice_date->DropDownList[$i] ?>"<?php if (ewrpt_MatchedFilterValue($onebar->invoice_date->DropDownValue, $onebar->invoice_date->DropDownList[$i])) echo " selected=\"selected\"" ?>><?php echo ewrpt_DropDownDisplayValue($onebar->invoice_date->DropDownList[$i], $onebar->invoice_date->DateFilter, 5) ?></option>
<?php
		$wrkcnt += 1;
	}

//}
?>
		</select>
		</span></td>
	</tr>
	<tr>
		<td><span class="phpreportmaker"><?php echo $onebar->username->FldCaption() ?></span></td>
		<td><span class="ewRptSearchOpr"><?php echo $ReportLanguage->Phrase("="); ?><input type="hidden" name="so1_username" id="so1_username" value="="></span></td>
		<td>
			<table cellspacing="0" class="ewItemTable"><tr>
				<td><span class="phpreportmaker">
<input type="text" name="sv1_username" id="sv1_username" size="30" maxlength="255" value="<?php echo ewrpt_HtmlEncode($onebar->username->SearchValue) ?>"<?php echo ($onebar_summary->ClearExtFilter == 'onebar_username') ? " class=\"ewInputCleared\"" : "" ?>>
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
<?php } ?>
<?php if ($onebar->ShowCurrentFilter) { ?>
<div id="ewrptFilterList">
<?php $onebar_summary->ShowFilterList() ?>
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
if ($onebar->ExportAll && $onebar->Export <> "") {
	$onebar_summary->StopGrp = $onebar_summary->TotalGrps;
} else {
	$onebar_summary->StopGrp = $onebar_summary->StartGrp + $onebar_summary->DisplayGrps - 1;
}

// Stop group <= total number of groups
if (intval($onebar_summary->StopGrp) > intval($onebar_summary->TotalGrps))
	$onebar_summary->StopGrp = $onebar_summary->TotalGrps;
$onebar_summary->RecCount = 0;

// Get first row
if ($onebar_summary->TotalGrps > 0) {
	$onebar_summary->GetGrpRow(1);
	$onebar_summary->GrpCount = 1;
}
while (($rsgrp && !$rsgrp->EOF && $onebar_summary->GrpCount <= $onebar_summary->DisplayGrps) || $onebar_summary->ShowFirstHeader) {

	// Show header
	if ($onebar_summary->ShowFirstHeader) {
?>
	<thead>
	<tr>
<td class="ewTableHeader">
<?php if ($onebar->Export <> "") { ?>
<?php echo $onebar->username->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($onebar->SortUrl($onebar->username) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $onebar->username->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $onebar->SortUrl($onebar->username) ?>',0);"><?php echo $onebar->username->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($onebar->username->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($onebar->username->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($onebar->Export <> "") { ?>
<?php echo $onebar->np_name->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($onebar->SortUrl($onebar->np_name) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $onebar->np_name->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $onebar->SortUrl($onebar->np_name) ?>',0);"><?php echo $onebar->np_name->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($onebar->np_name->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($onebar->np_name->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($onebar->Export <> "") { ?>
<?php echo $onebar->invoice_date->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($onebar->SortUrl($onebar->invoice_date) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $onebar->invoice_date->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $onebar->SortUrl($onebar->invoice_date) ?>',0);"><?php echo $onebar->invoice_date->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($onebar->invoice_date->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($onebar->invoice_date->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
		<td style="width: 20px;" align="right"><a href="#" onclick="ewrpt_ShowPopup(this.name, 'onebar_invoice_date', true, '<?php echo $onebar->invoice_date->RangeFrom; ?>', '<?php echo $onebar->invoice_date->RangeTo; ?>');return false;" name="x_invoice_date<?php echo $onebar_summary->Cnt[0][0]; ?>" id="x_invoice_date<?php echo $onebar_summary->Cnt[0][0]; ?>"><img src="phprptimages/popup.gif" width="15" height="14" align="texttop" border="0" alt="<?php echo $ReportLanguage->Phrase("Filter") ?>"></a></td>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($onebar->Export <> "") { ?>
<?php echo $onebar->points->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($onebar->SortUrl($onebar->points) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $onebar->points->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $onebar->SortUrl($onebar->points) ?>',0);"><?php echo $onebar->points->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($onebar->points->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($onebar->points->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($onebar->Export <> "") { ?>
<?php echo $onebar->invoice_id->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($onebar->SortUrl($onebar->invoice_id) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $onebar->invoice_id->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $onebar->SortUrl($onebar->invoice_id) ?>',0);"><?php echo $onebar->invoice_id->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($onebar->invoice_id->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($onebar->invoice_id->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($onebar->Export <> "") { ?>
<?php echo $onebar->transtype->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($onebar->SortUrl($onebar->transtype) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $onebar->transtype->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $onebar->SortUrl($onebar->transtype) ?>',0);"><?php echo $onebar->transtype->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($onebar->transtype->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($onebar->transtype->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
	</tr>
	</thead>
	<tbody>
<?php
		$onebar_summary->ShowFirstHeader = FALSE;
	}

	// Build detail SQL
	$sWhere = ewrpt_DetailFilterSQL($onebar->username, $onebar->SqlFirstGroupField(), $onebar->username->GroupValue());
	if ($onebar_summary->Filter != "")
		$sWhere = "($onebar_summary->Filter) AND ($sWhere)";
	$sSql = ewrpt_BuildReportSql($onebar->SqlSelect(), $onebar->SqlWhere(), $onebar->SqlGroupBy(), $onebar->SqlHaving(), $onebar->SqlOrderBy(), $sWhere, $onebar_summary->Sort);
	$rs = $conn->Execute($sSql);
	$rsdtlcnt = ($rs) ? $rs->RecordCount() : 0;
	if ($rsdtlcnt > 0)
		$onebar_summary->GetRow(1);
	while ($rs && !$rs->EOF) { // Loop detail records
		$onebar_summary->RecCount++;

		// Render detail row
		$onebar->ResetCSS();
		$onebar->RowType = EWRPT_ROWTYPE_DETAIL;
		$onebar_summary->RenderRow();
?>
	<tr<?php echo $onebar->RowAttributes(); ?>>
		<td<?php echo $onebar->username->CellAttributes(); ?>><div<?php echo $onebar->username->ViewAttributes(); ?>><?php echo $onebar->username->GroupViewValue; ?></div></td>
		<td<?php echo $onebar->np_name->CellAttributes(); ?>><div<?php echo $onebar->np_name->ViewAttributes(); ?>><?php echo $onebar->np_name->GroupViewValue; ?></div></td>
		<td<?php echo $onebar->invoice_date->CellAttributes(); ?>><div<?php echo $onebar->invoice_date->ViewAttributes(); ?>><?php echo $onebar->invoice_date->GroupViewValue; ?></div></td>
		<td<?php echo $onebar->points->CellAttributes() ?>>
<div<?php echo $onebar->points->ViewAttributes(); ?>><?php echo $onebar->points->ListViewValue(); ?></div>
</td>
		<td<?php echo $onebar->invoice_id->CellAttributes() ?>>
<div<?php echo $onebar->invoice_id->ViewAttributes(); ?>><?php echo $onebar->invoice_id->ListViewValue(); ?></div>
</td>
		<td<?php echo $onebar->transtype->CellAttributes() ?>>
<div<?php echo $onebar->transtype->ViewAttributes(); ?>><?php echo $onebar->transtype->ListViewValue(); ?></div>
</td>
	</tr>
<?php

		// Accumulate page summary
		$onebar_summary->AccumulateSummary();

		// Get next record
		$onebar_summary->GetRow(2);

		// Show Footers
?>
<?php
		if ($onebar_summary->ChkLvlBreak(2)) {
			$onebar->ResetCSS();
			$onebar->RowType = EWRPT_ROWTYPE_TOTAL;
			$onebar->RowTotalType = EWRPT_ROWTOTAL_GROUP;
			$onebar->RowTotalSubType = EWRPT_ROWTOTAL_FOOTER;
			$onebar->RowGroupLevel = 2;
			$onebar_summary->RenderRow();
?>
	<tr<?php echo $onebar->RowAttributes(); ?>>
		<td<?php echo $onebar->username->CellAttributes() ?>>&nbsp;</td>
		<td colspan="5"<?php echo $onebar->np_name->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSumHead") ?> <?php echo $onebar->np_name->FldCaption() ?>: <?php echo $onebar->np_name->GroupViewValue; ?> (<?php echo ewrpt_FormatNumber($onebar_summary->Cnt[2][0],0,-2,-2,-2); ?> <?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</td></tr>
<?php
			$onebar->ResetCSS();
			$onebar->points->Count = $onebar_summary->Cnt[2][1];
			$onebar->points->Summary = $onebar_summary->Smry[2][1]; // Load SUM
			$onebar->RowTotalSubType = EWRPT_ROWTOTAL_SUM;
			$onebar_summary->RenderRow();
?>
	<tr<?php echo $onebar->RowAttributes(); ?>>
		<td<?php echo $onebar->username->CellAttributes() ?>>&nbsp;</td>
		<td colspan="2"<?php echo $onebar->np_name->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum"); ?></td>
		<td<?php echo $onebar->np_name->CellAttributes() ?>>
<div<?php echo $onebar->points->ViewAttributes(); ?>><?php echo $onebar->points->ListViewValue(); ?></div>
</td>
		<td<?php echo $onebar->np_name->CellAttributes() ?>>&nbsp;</td>
		<td<?php echo $onebar->np_name->CellAttributes() ?>>&nbsp;</td>
	</tr>
<?php

			// Reset level 2 summary
			$onebar_summary->ResetLevelSummary(2);
		} // End check level check
?>
<?php
	} // End detail records loop
?>
<?php

	// Next group
	$onebar_summary->GetGrpRow(2);
	$onebar_summary->GrpCount++;
} // End while
?>
	</tbody>
	<tfoot>
<?php
if ($onebar_summary->TotalGrps > 0) {
	$onebar->ResetCSS();
	$onebar->RowType = EWRPT_ROWTYPE_TOTAL;
	$onebar->RowTotalType = EWRPT_ROWTOTAL_GRAND;
	$onebar->RowTotalSubType = EWRPT_ROWTOTAL_FOOTER;
	$onebar->RowAttrs["class"] = "ewRptGrandSummary";
	$onebar_summary->RenderRow();
?>
	<!-- tr><td colspan="6"><span class="phpreportmaker">&nbsp;<br /></span></td></tr -->
	<tr<?php echo $onebar->RowAttributes(); ?>><td colspan="6"><?php echo $ReportLanguage->Phrase("RptGrandTotal") ?> (<?php echo ewrpt_FormatNumber($onebar_summary->TotCount,0,-2,-2,-2); ?> <?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</td></tr>
<?php
	$onebar->ResetCSS();
	$onebar->points->Count = $onebar_summary->TotCount;
	$onebar->points->Summary = $onebar_summary->GrandSmry[1]; // Load SUM
	$onebar->RowTotalSubType = EWRPT_ROWTOTAL_SUM;
	$onebar->points->CurrentValue = $onebar->points->Summary;
	$onebar->RowAttrs["class"] = "ewRptGrandSummary";
	$onebar_summary->RenderRow();
?>
	<tr<?php echo $onebar->RowAttributes(); ?>>
		<td colspan="3" class="ewRptGrpAggregate"><?php echo $ReportLanguage->Phrase("RptSum"); ?></td>
		<td<?php echo $onebar->points->CellAttributes() ?>>
<div<?php echo $onebar->points->ViewAttributes(); ?>><?php echo $onebar->points->ListViewValue(); ?></div>
</td>
		<td<?php echo $onebar->invoice_id->CellAttributes() ?>>&nbsp;</td>
		<td<?php echo $onebar->transtype->CellAttributes() ?>>&nbsp;</td>
	</tr>
<?php } ?>
	</tfoot>
</table>
</div>
<?php if ($onebar->Export == "") { ?>
<div class="ewGridLowerPanel">
<form action="onebarsmry.php" name="ewpagerform" id="ewpagerform" class="ewForm">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td style="white-space: nowrap;">
<?php if (!isset($Pager)) $Pager = new crPrevNextPager($onebar_summary->StartGrp, $onebar_summary->DisplayGrps, $onebar_summary->TotalGrps) ?>
<?php if ($Pager->RecordCount > 0) { ?>
	<table border="0" cellspacing="0" cellpadding="0"><tr><td><span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("Page") ?>&nbsp;</span></td>
<!--first page button-->
	<?php if ($Pager->FirstButton->Enabled) { ?>
	<td><a href="onebarsmry.php?start=<?php echo $Pager->FirstButton->Start ?>"><img src="phprptimages/first.gif" alt="<?php echo $ReportLanguage->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phprptimages/firstdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($Pager->PrevButton->Enabled) { ?>
	<td><a href="onebarsmry.php?start=<?php echo $Pager->PrevButton->Start ?>"><img src="phprptimages/prev.gif" alt="<?php echo $ReportLanguage->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phprptimages/prevdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="pageno" id="pageno" value="<?php echo $Pager->CurrentPage ?>" size="4"></td>
<!--next page button-->
	<?php if ($Pager->NextButton->Enabled) { ?>
	<td><a href="onebarsmry.php?start=<?php echo $Pager->NextButton->Start ?>"><img src="phprptimages/next.gif" alt="<?php echo $ReportLanguage->Phrase("PagerNext") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phprptimages/nextdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerNext") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--last page button-->
	<?php if ($Pager->LastButton->Enabled) { ?>
	<td><a href="onebarsmry.php?start=<?php echo $Pager->LastButton->Start ?>"><img src="phprptimages/last.gif" alt="<?php echo $ReportLanguage->Phrase("PagerLast") ?>" width="16" height="16" border="0"></a></td>	
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
	<?php if ($onebar_summary->Filter == "0=101") { ?>
	<span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("EnterSearchCriteria") ?></span>
	<?php } else { ?>
	<span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("NoRecord") ?></span>
	<?php } ?>
<?php } ?>
		</td>
<?php if ($onebar_summary->TotalGrps > 0) { ?>
		<td style="white-space: nowrap;">&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="right" style="vertical-align: top; white-space: nowrap;"><span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("GroupsPerPage"); ?>&nbsp;
<select name="<?php echo EWRPT_TABLE_GROUP_PER_PAGE; ?>" onchange="this.form.submit();">
<option value="1"<?php if ($onebar_summary->DisplayGrps == 1) echo " selected=\"selected\"" ?>>1</option>
<option value="2"<?php if ($onebar_summary->DisplayGrps == 2) echo " selected=\"selected\"" ?>>2</option>
<option value="3"<?php if ($onebar_summary->DisplayGrps == 3) echo " selected=\"selected\"" ?>>3</option>
<option value="4"<?php if ($onebar_summary->DisplayGrps == 4) echo " selected=\"selected\"" ?>>4</option>
<option value="5"<?php if ($onebar_summary->DisplayGrps == 5) echo " selected=\"selected\"" ?>>5</option>
<option value="10"<?php if ($onebar_summary->DisplayGrps == 10) echo " selected=\"selected\"" ?>>10</option>
<option value="20"<?php if ($onebar_summary->DisplayGrps == 20) echo " selected=\"selected\"" ?>>20</option>
<option value="50"<?php if ($onebar_summary->DisplayGrps == 50) echo " selected=\"selected\"" ?>>50</option>
<option value="ALL"<?php if ($onebar->getGroupPerPage() == -1) echo " selected=\"selected\"" ?>><?php echo $ReportLanguage->Phrase("AllRecords") ?></option>
</select>
		</span></td>
<?php } ?>
	</tr>
</table>
</form>
</div>
<?php } ?>
</td></tr></table>
</div>
<!-- Summary Report Ends -->
<?php if ($onebar->Export == "") { ?>
	</div><br /></td>
	<!-- Center Container - Report (End) -->
	<!-- Right Container (Begin) -->
	<td style="vertical-align: top;" class="ewPadding"><div id="ewRight" class="phpreportmaker">
	<!-- Right slot -->
<?php } ?>
<?php if ($onebar->Export == "" || $onebar->Export == "print" || $onebar->Export == "email") { ?>
<a name="cht_Chart1"></a>
<div id="div_onebar_Chart1"></div>
<?php

// Initialize chart data
$onebar->Chart1->ID = "onebar_Chart1"; // Chart ID
$onebar->Chart1->SetChartParam("type", "5", FALSE); // Chart type
$onebar->Chart1->SetChartParam("seriestype", "0", FALSE); // Chart series type
$onebar->Chart1->SetChartParam("bgcolor", "#FCFCFC", TRUE); // Background color
$onebar->Chart1->SetChartParam("caption", $onebar->Chart1->ChartCaption(), TRUE); // Chart caption
$onebar->Chart1->SetChartParam("xaxisname", $onebar->Chart1->ChartXAxisName(), TRUE); // X axis name
$onebar->Chart1->SetChartParam("yaxisname", $onebar->Chart1->ChartYAxisName(), TRUE); // Y axis name
$onebar->Chart1->SetChartParam("shownames", "1", TRUE); // Show names
$onebar->Chart1->SetChartParam("showvalues", "1", TRUE); // Show values
$onebar->Chart1->SetChartParam("showhovercap", "1", TRUE); // Show hover
$onebar->Chart1->SetChartParam("alpha", "50", FALSE); // Chart alpha
$onebar->Chart1->SetChartParam("colorpalette", "#FF0000|#FF0080|#FF00FF|#8000FF|#FF8000|#FF3D3D|#7AFFFF|#0000FF|#FFFF00|#FF7A7A|#3DFFFF|#0080FF|#80FF00|#00FF00|#00FF80|#00FFFF", FALSE); // Chart color palette
?>
<?php
$onebar->Chart1->SetChartParam("showCanvasBg", "1", TRUE); // showCanvasBg
$onebar->Chart1->SetChartParam("showCanvasBase", "1", TRUE); // showCanvasBase
$onebar->Chart1->SetChartParam("showLimits", "1", TRUE); // showLimits
$onebar->Chart1->SetChartParam("animation", "1", TRUE); // animation
$onebar->Chart1->SetChartParam("rotateNames", "0", TRUE); // rotateNames
$onebar->Chart1->SetChartParam("yAxisMinValue", "0", TRUE); // yAxisMinValue
$onebar->Chart1->SetChartParam("yAxisMaxValue", "0", TRUE); // yAxisMaxValue
$onebar->Chart1->SetChartParam("PYAxisMinValue", "0", TRUE); // PYAxisMinValue
$onebar->Chart1->SetChartParam("PYAxisMaxValue", "0", TRUE); // PYAxisMaxValue
$onebar->Chart1->SetChartParam("SYAxisMinValue", "0", TRUE); // SYAxisMinValue
$onebar->Chart1->SetChartParam("SYAxisMaxValue", "0", TRUE); // SYAxisMaxValue
$onebar->Chart1->SetChartParam("showColumnShadow", "0", TRUE); // showColumnShadow
$onebar->Chart1->SetChartParam("showPercentageValues", "1", TRUE); // showPercentageValues
$onebar->Chart1->SetChartParam("showPercentageInLabel", "1", TRUE); // showPercentageInLabel
$onebar->Chart1->SetChartParam("showBarShadow", "0", TRUE); // showBarShadow
$onebar->Chart1->SetChartParam("showAnchors", "1", TRUE); // showAnchors
$onebar->Chart1->SetChartParam("showAreaBorder", "1", TRUE); // showAreaBorder
$onebar->Chart1->SetChartParam("isSliced", "1", TRUE); // isSliced
$onebar->Chart1->SetChartParam("showAsBars", "0", TRUE); // showAsBars
$onebar->Chart1->SetChartParam("showShadow", "0", TRUE); // showShadow
$onebar->Chart1->SetChartParam("formatNumber", "0", TRUE); // formatNumber
$onebar->Chart1->SetChartParam("formatNumberScale", "0", TRUE); // formatNumberScale
$onebar->Chart1->SetChartParam("decimalSeparator", ".", TRUE); // decimalSeparator
$onebar->Chart1->SetChartParam("thousandSeparator", ",", TRUE); // thousandSeparator
$onebar->Chart1->SetChartParam("decimalPrecision", "2", TRUE); // decimalPrecision
$onebar->Chart1->SetChartParam("divLineDecimalPrecision", "2", TRUE); // divLineDecimalPrecision
$onebar->Chart1->SetChartParam("limitsDecimalPrecision", "2", TRUE); // limitsDecimalPrecision
$onebar->Chart1->SetChartParam("zeroPlaneShowBorder", "1", TRUE); // zeroPlaneShowBorder
$onebar->Chart1->SetChartParam("showDivLineValue", "1", TRUE); // showDivLineValue
$onebar->Chart1->SetChartParam("showAlternateHGridColor", "0", TRUE); // showAlternateHGridColor
$onebar->Chart1->SetChartParam("showAlternateVGridColor", "0", TRUE); // showAlternateVGridColor
$onebar->Chart1->SetChartParam("hoverCapSepChar", ":", TRUE); // hoverCapSepChar

// Define trend lines
?>
<?php
$SqlSelect = $onebar->SqlSelect();
$SqlChartSelect = $onebar->Chart1->SqlSelect;
$sSqlChartBase = $onebar->SqlFrom();

// Load chart data from sql directly
$sSql = $SqlChartSelect . $sSqlChartBase;
$sSql = ewrpt_BuildReportSql($sSql, $onebar->SqlWhere(), $onebar->Chart1->SqlGroupBy, "", $onebar->Chart1->SqlOrderBy, $onebar_summary->Filter, "");
if (EWRPT_DEBUG_ENABLED) echo "(Chart SQL): " . $sSql . "<br>";
ewrpt_LoadChartData($sSql, $onebar->Chart1);
ewrpt_SortChartData($onebar->Chart1->Data, 1, "");

// Call Chart_Rendering event
$onebar->Chart_Rendering($onebar->Chart1);
$chartxml = $onebar->Chart1->ChartXml();

// Call Chart_Rendered event
$onebar->Chart_Rendered($onebar->Chart1, $chartxml);
echo $onebar->Chart1->ShowChartFCF($chartxml);
?>
<br /><br />
<?php } ?>
<?php if ($onebar->Export == "") { ?>
	</div></td>
	<!-- Right Container (End) -->
</tr>
<!-- Bottom Container (Begin) -->
<tr><td colspan="3"><div id="ewBottom" class="phpreportmaker">
	<!-- Bottom slot -->
<?php } ?>
<?php if ($onebar->Export == "" || $onebar->Export == "print" || $onebar->Export == "email") { ?>
<?php } ?>
<?php if ($onebar->Export == "") { ?>
	</div><br /></td></tr>
<!-- Bottom Container (End) -->
</table>
<!-- Table Container (End) -->
<?php } ?>
<?php $onebar_summary->ShowPageFooter(); ?>
<?php if (EWRPT_DEBUG_ENABLED) echo ewrpt_DebugMsg(); ?>
<?php

// Close recordsets
if ($rsgrp) $rsgrp->Close();
if ($rs) $rs->Close();
?>
<?php if ($onebar->Export == "") { ?>
<script language="JavaScript" type="text/javascript">
<!--

// Write your table-specific startup script here
// document.write("page loaded");
//-->

</script>
<?php } ?>
<?php include "phprptinc/footer.php"; ?>
<?php
$onebar_summary->Page_Terminate();
?>
<?php

//
// Page class
//
class cronebar_summary {

	// Page ID
	var $PageID = 'summary';

	// Table name
	var $TableName = 'onebar';

	// Page object name
	var $PageObjName = 'onebar_summary';

	// Page name
	function PageName() {
		return ewrpt_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ewrpt_CurrentPage() . "?";
		global $onebar;
		if ($onebar->UseTokenInUrl) $PageUrl .= "t=" . $onebar->TableVar . "&"; // Add page token
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
		global $onebar;
		if ($onebar->UseTokenInUrl) {
			if (ewrpt_IsHttpPost())
				return ($onebar->TableVar == @$_POST("t"));
			if (@$_GET["t"] <> "")
				return ($onebar->TableVar == @$_GET["t"]);
		} else {
			return TRUE;
		}
	}

	//
	// Page class constructor
	//
	function cronebar_summary() {
		global $conn, $ReportLanguage;

		// Language object
		$ReportLanguage = new crLanguage();

		// Table object (onebar)
		$GLOBALS["onebar"] = new cronebar();

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";

		// Page ID
		if (!defined("EWRPT_PAGE_ID"))
			define("EWRPT_PAGE_ID", 'summary', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EWRPT_TABLE_NAME"))
			define("EWRPT_TABLE_NAME", 'onebar', TRUE);

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
		global $onebar;

	// Get export parameters
	if (@$_GET["export"] <> "") {
		$onebar->Export = $_GET["export"];
	}
	$gsExport = $onebar->Export; // Get export parameter, used in header
	$gsExportFile = $onebar->TableVar; // Get export file, used in header
	if ($onebar->Export == "excel") {
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename=' . $gsExportFile .'.xls');
	}
	if ($onebar->Export == "word") {
		header('Content-Type: application/vnd.ms-word');
		header('Content-Disposition: attachment; filename=' . $gsExportFile .'.doc');
	}

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
		global $onebar;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export to Email (use ob_file_contents for PHP)
		if ($onebar->Export == "email") {
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
		global $onebar;
		global $rs;
		global $rsgrp;
		global $gsFormError;

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 4;
		$nGrps = 4;
		$this->Val = ewrpt_InitArray($nDtls, 0);
		$this->Cnt = ewrpt_Init2DArray($nGrps, $nDtls, 0);
		$this->Smry = ewrpt_Init2DArray($nGrps, $nDtls, 0);
		$this->Mn = ewrpt_Init2DArray($nGrps, $nDtls, NULL);
		$this->Mx = ewrpt_Init2DArray($nGrps, $nDtls, NULL);
		$this->GrandSmry = ewrpt_InitArray($nDtls, 0);
		$this->GrandMn = ewrpt_InitArray($nDtls, NULL);
		$this->GrandMx = ewrpt_InitArray($nDtls, NULL);

		// Set up if accumulation required
		$this->Col = array(FALSE, TRUE, FALSE, FALSE);

		// Set up groups per page dynamically
		$this->SetUpDisplayGrps();
		$onebar->invoice_date->SelectionList = "";
		$onebar->invoice_date->DefaultSelectionList = "";
		$onebar->invoice_date->ValueList = "";

		// Load default filter values
		$this->LoadDefaultFilters();

		// Set up popup filter
		$this->SetupPopup();

		// Extended filter
		$sExtendedFilter = "";

		// Get dropdown values
		$this->GetExtendedFilterValues();

		// Load custom filters
		$onebar->CustomFilters_Load();

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

		// Get total group count
		$sGrpSort = ewrpt_UpdateSortFields($onebar->SqlOrderByGroup(), $this->Sort, 2); // Get grouping field only
		$sSql = ewrpt_BuildReportSql($onebar->SqlSelectGroup(), $onebar->SqlWhere(), $onebar->SqlGroupBy(), $onebar->SqlHaving(), $onebar->SqlOrderByGroup(), $this->Filter, $sGrpSort);
		$this->TotalGrps = $this->GetGrpCnt($sSql);
		if ($this->DisplayGrps <= 0) // Display all groups
			$this->DisplayGrps = $this->TotalGrps;
		$this->StartGrp = 1;

		// Show header
		$this->ShowFirstHeader = ($this->TotalGrps > 0);

		//$this->ShowFirstHeader = TRUE; // Uncomment to always show header
		// Set up start position if not export all

		if ($onebar->ExportAll && $onebar->Export <> "")
		    $this->DisplayGrps = $this->TotalGrps;
		else
			$this->SetUpStartGroup(); 

		// Get current page groups
		$rsgrp = $this->GetGrpRs($sSql, $this->StartGrp, $this->DisplayGrps);

		// Init detail recordset
		$rs = NULL;
	}

	// Check level break
	function ChkLvlBreak($lvl) {
		global $onebar;
		switch ($lvl) {
			case 1:
				return (is_null($onebar->username->CurrentValue) && !is_null($onebar->username->OldValue)) ||
					(!is_null($onebar->username->CurrentValue) && is_null($onebar->username->OldValue)) ||
					($onebar->username->GroupValue() <> $onebar->username->GroupOldValue());
			case 2:
				return (is_null($onebar->np_name->CurrentValue) && !is_null($onebar->np_name->OldValue)) ||
					(!is_null($onebar->np_name->CurrentValue) && is_null($onebar->np_name->OldValue)) ||
					($onebar->np_name->GroupValue() <> $onebar->np_name->GroupOldValue()) || $this->ChkLvlBreak(1); // Recurse upper level
			case 3:
				return (is_null($onebar->invoice_date->CurrentValue) && !is_null($onebar->invoice_date->OldValue)) ||
					(!is_null($onebar->invoice_date->CurrentValue) && is_null($onebar->invoice_date->OldValue)) ||
					($onebar->invoice_date->GroupValue() <> $onebar->invoice_date->GroupOldValue()) || $this->ChkLvlBreak(2); // Recurse upper level
		}
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

	// Get group count
	function GetGrpCnt($sql) {
		global $conn;
		global $onebar;
		$rsgrpcnt = $conn->Execute($sql);
		$grpcnt = ($rsgrpcnt) ? $rsgrpcnt->RecordCount() : 0;
		if ($rsgrpcnt) $rsgrpcnt->Close();
		return $grpcnt;
	}

	// Get group rs
	function GetGrpRs($sql, $start, $grps) {
		global $conn;
		global $onebar;
		$wrksql = $sql;
		if ($start > 0 && $grps > -1)
			$wrksql .= " LIMIT " . ($start-1) . ", " . ($grps);
		$rswrk = $conn->Execute($wrksql);
		return $rswrk;
	}

	// Get group row values
	function GetGrpRow($opt) {
		global $rsgrp;
		global $onebar;
		if (!$rsgrp)
			return;
		if ($opt == 1) { // Get first group

			//$rsgrp->MoveFirst(); // NOTE: no need to move position
			$onebar->username->setDbValue(""); // Init first value
		} else { // Get next group
			$rsgrp->MoveNext();
		}
		if (!$rsgrp->EOF)
			$onebar->username->setDbValue($rsgrp->fields[0]);
		if ($rsgrp->EOF) {
			$onebar->username->setDbValue("");
		}
	}

	// Get row values
	function GetRow($opt) {
		global $rs;
		global $onebar;
		if (!$rs)
			return;
		if ($opt == 1) { // Get first row

	//		$rs->MoveFirst(); // NOTE: no need to move position
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$onebar->giveback_id->setDbValue($rs->fields('giveback_id'));
			$onebar->np_userid->setDbValue($rs->fields('np_userid'));
			$onebar->np_name->setDbValue($rs->fields('np_name'));
			$onebar->points->setDbValue($rs->fields('points'));
			$onebar->invoice_date->setDbValue($rs->fields('invoice_date'));
			$onebar->user_id->setDbValue($rs->fields('user_id'));
			if ($opt <> 1) {
				if (is_array($onebar->username->GroupDbValues))
					$onebar->username->setDbValue(@$onebar->username->GroupDbValues[$rs->fields('username')]);
				else
					$onebar->username->setDbValue(ewrpt_GroupValue($onebar->username, $rs->fields('username')));
			}
			$onebar->invoice_id->setDbValue($rs->fields('invoice_id'));
			$onebar->buyerorseller->setDbValue($rs->fields('buyerorseller'));
			$onebar->transtype->setDbValue($rs->fields('transtype'));
			$this->Val[1] = $onebar->points->CurrentValue;
			$this->Val[2] = $onebar->invoice_id->CurrentValue;
			$this->Val[3] = $onebar->transtype->CurrentValue;
		} else {
			$onebar->giveback_id->setDbValue("");
			$onebar->np_userid->setDbValue("");
			$onebar->np_name->setDbValue("");
			$onebar->points->setDbValue("");
			$onebar->invoice_date->setDbValue("");
			$onebar->user_id->setDbValue("");
			$onebar->username->setDbValue("");
			$onebar->invoice_id->setDbValue("");
			$onebar->buyerorseller->setDbValue("");
			$onebar->transtype->setDbValue("");
		}
	}

	//  Set up starting group
	function SetUpStartGroup() {
		global $onebar;

		// Exit if no groups
		if ($this->DisplayGrps == 0)
			return;

		// Check for a 'start' parameter
		if (@$_GET[EWRPT_TABLE_START_GROUP] != "") {
			$this->StartGrp = $_GET[EWRPT_TABLE_START_GROUP];
			$onebar->setStartGroup($this->StartGrp);
		} elseif (@$_GET["pageno"] != "") {
			$nPageNo = $_GET["pageno"];
			if (is_numeric($nPageNo)) {
				$this->StartGrp = ($nPageNo-1)*$this->DisplayGrps+1;
				if ($this->StartGrp <= 0) {
					$this->StartGrp = 1;
				} elseif ($this->StartGrp >= intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1) {
					$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1;
				}
				$onebar->setStartGroup($this->StartGrp);
			} else {
				$this->StartGrp = $onebar->getStartGroup();
			}
		} else {
			$this->StartGrp = $onebar->getStartGroup();
		}

		// Check if correct start group counter
		if (!is_numeric($this->StartGrp) || $this->StartGrp == "") { // Avoid invalid start group counter
			$this->StartGrp = 1; // Reset start group counter
			$onebar->setStartGroup($this->StartGrp);
		} elseif (intval($this->StartGrp) > intval($this->TotalGrps)) { // Avoid starting group > total groups
			$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to last page first group
			$onebar->setStartGroup($this->StartGrp);
		} elseif (($this->StartGrp-1) % $this->DisplayGrps <> 0) {
			$this->StartGrp = intval(($this->StartGrp-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to page boundary
			$onebar->setStartGroup($this->StartGrp);
		}
	}

	// Set up popup
	function SetupPopup() {
		global $conn, $ReportLanguage;
		global $onebar;

		// Initialize popup
		// Build distinct values for invoice_date

		ewrpt_SetupDistinctValuesFromFilter($onebar->invoice_date->ValueList, $onebar->invoice_date->AdvancedFilters); // Set up popup filter
		$bNullValue = FALSE;
		$bEmptyValue = FALSE;
		$sSql = ewrpt_BuildReportSql($onebar->invoice_date->SqlSelect, $onebar->SqlWhere(), $onebar->SqlGroupBy(), $onebar->SqlHaving(), $onebar->invoice_date->SqlOrderBy, $this->Filter, "");
		$rswrk = $conn->Execute($sSql);
		while ($rswrk && !$rswrk->EOF) {
			$onebar->invoice_date->setDbValue($rswrk->fields[0]);
			if (is_null($onebar->invoice_date->CurrentValue)) {
				$bNullValue = TRUE;
			} elseif ($onebar->invoice_date->CurrentValue == "") {
				$bEmptyValue = TRUE;
			} else {
				$grpval = $rswrk->fields('ew_report_groupvalue');
				$onebar->invoice_date->GroupDbValues[$onebar->invoice_date->CurrentValue] = $grpval;
				$onebar->invoice_date->GroupViewValue = ewrpt_DisplayGroupValue($onebar->invoice_date,$onebar->invoice_date->GroupValue());
				ewrpt_SetupDistinctValues($onebar->invoice_date->ValueList, $onebar->invoice_date->GroupValue(), $onebar->invoice_date->GroupViewValue, TRUE);
			}
			$rswrk->MoveNext();
		}
		if ($rswrk)
			$rswrk->Close();
		if ($bEmptyValue)
			ewrpt_SetupDistinctValues($onebar->invoice_date->ValueList, EWRPT_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
		if ($bNullValue)
			ewrpt_SetupDistinctValues($onebar->invoice_date->ValueList, EWRPT_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);

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
				$this->ClearSessionSelection('invoice_date');
				$this->ResetPager();
			}
		}

		// Load selection criteria to array
		// Get invoice date selected values

		if (is_array(@$_SESSION["sel_onebar_invoice_date"])) {
			$this->LoadSelectionFromSession('invoice_date');
		} elseif (@$_SESSION["sel_onebar_invoice_date"] == EWRPT_INIT_VALUE) { // Select all
			$onebar->invoice_date->SelectionList = "";
		}
	}

	// Reset pager
	function ResetPager() {

		// Reset start position (reset command)
		global $onebar;
		$this->StartGrp = 1;
		$onebar->setStartGroup($this->StartGrp);
	}

	// Set up number of groups displayed per page
	function SetUpDisplayGrps() {
		global $onebar;
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
			$onebar->setGroupPerPage($this->DisplayGrps); // Save to session

			// Reset start position (reset command)
			$this->StartGrp = 1;
			$onebar->setStartGroup($this->StartGrp);
		} else {
			if ($onebar->getGroupPerPage() <> "") {
				$this->DisplayGrps = $onebar->getGroupPerPage(); // Restore from session
			} else {
				$this->DisplayGrps = 3; // Load default
			}
		}
	}

	function RenderRow() {
		global $conn, $rs, $Security;
		global $onebar;
		if ($onebar->RowTotalType == EWRPT_ROWTOTAL_GRAND) { // Grand total

			// Get total count from sql directly
			$sSql = ewrpt_BuildReportSql($onebar->SqlSelectCount(), $onebar->SqlWhere(), $onebar->SqlGroupBy(), $onebar->SqlHaving(), "", $this->Filter, "");
			$rstot = $conn->Execute($sSql);
			if ($rstot) {
				$this->TotCount = ($rstot->RecordCount()>1) ? $rstot->RecordCount() : $rstot->fields[0];
				$rstot->Close();
			} else {
				$this->TotCount = 0;
			}

			// Get total from sql directly
			$sSql = ewrpt_BuildReportSql($onebar->SqlSelectAgg(), $onebar->SqlWhere(), $onebar->SqlGroupBy(), $onebar->SqlHaving(), "", $this->Filter, "");
			$sSql = $onebar->SqlAggPfx() . $sSql . $onebar->SqlAggSfx();
			$rsagg = $conn->Execute($sSql);
			if ($rsagg) {
				$this->GrandSmry[1] = $rsagg->fields("sum_points");
				$rsagg->Close();
			} else {

				// Accumulate grand summary from detail records
				$sSql = ewrpt_BuildReportSql($onebar->SqlSelect(), $onebar->SqlWhere(), $onebar->SqlGroupBy(), $onebar->SqlHaving(), "", $this->Filter, "");
				$rs = $conn->Execute($sSql);
				if ($rs) {
					$this->GetRow(1);
					while (!$rs->EOF) {
						$this->AccumulateGrandSummary();
						$this->GetRow(2);
					}
					$rs->Close();
				}
			}
		}

		// Call Row_Rendering event
		$onebar->Row_Rendering();

		//
		// Render view codes
		//

		if ($onebar->RowType == EWRPT_ROWTYPE_TOTAL) { // Summary row

			// username
			$onebar->username->GroupViewValue = $onebar->username->GroupOldValue();
			$onebar->username->CellAttrs["class"] = ($onebar->RowGroupLevel == 1) ? "ewRptGrpSummary1" : "ewRptGrpField1";
			$onebar->username->GroupViewValue = ewrpt_DisplayGroupValue($onebar->username, $onebar->username->GroupViewValue);

			// np_name
			$onebar->np_name->GroupViewValue = $onebar->np_name->GroupOldValue();
			$onebar->np_name->CellAttrs["class"] = ($onebar->RowGroupLevel == 2) ? "ewRptGrpSummary2" : "ewRptGrpField2";
			$onebar->np_name->GroupViewValue = ewrpt_DisplayGroupValue($onebar->np_name, $onebar->np_name->GroupViewValue);

			// invoice_date
			$onebar->invoice_date->GroupViewValue = $onebar->invoice_date->GroupOldValue();
			$onebar->invoice_date->CellAttrs["class"] = ($onebar->RowGroupLevel == 3) ? "ewRptGrpSummary3" : "ewRptGrpField3";
			$onebar->invoice_date->GroupViewValue = ewrpt_DisplayGroupValue($onebar->invoice_date, $onebar->invoice_date->GroupViewValue);

			// points
			$onebar->points->ViewValue = $onebar->points->Summary;
			$onebar->points->ViewValue = ewrpt_FormatCurrency($onebar->points->ViewValue, 0, -2, -2, -2);

			// invoice_id
			$onebar->invoice_id->ViewValue = $onebar->invoice_id->Summary;

			// transtype
			$onebar->transtype->ViewValue = $onebar->transtype->Summary;
		} else {

			// username
			$onebar->username->GroupViewValue = $onebar->username->GroupValue();
			$onebar->username->CellAttrs["class"] = "ewRptGrpField1";
			$onebar->username->GroupViewValue = ewrpt_DisplayGroupValue($onebar->username, $onebar->username->GroupViewValue);
			if ($onebar->username->GroupValue() == $onebar->username->GroupOldValue() && !$this->ChkLvlBreak(1))
				$onebar->username->GroupViewValue = "&nbsp;";

			// np_name
			$onebar->np_name->GroupViewValue = $onebar->np_name->GroupValue();
			$onebar->np_name->CellAttrs["class"] = "ewRptGrpField2";
			$onebar->np_name->GroupViewValue = ewrpt_DisplayGroupValue($onebar->np_name, $onebar->np_name->GroupViewValue);
			if ($onebar->np_name->GroupValue() == $onebar->np_name->GroupOldValue() && !$this->ChkLvlBreak(2))
				$onebar->np_name->GroupViewValue = "&nbsp;";

			// invoice_date
			$onebar->invoice_date->GroupViewValue = $onebar->invoice_date->GroupValue();
			$onebar->invoice_date->CellAttrs["class"] = "ewRptGrpField3";
			$onebar->invoice_date->GroupViewValue = ewrpt_DisplayGroupValue($onebar->invoice_date, $onebar->invoice_date->GroupViewValue);
			if ($onebar->invoice_date->GroupValue() == $onebar->invoice_date->GroupOldValue() && !$this->ChkLvlBreak(3))
				$onebar->invoice_date->GroupViewValue = "&nbsp;";

			// points
			$onebar->points->ViewValue = $onebar->points->CurrentValue;
			$onebar->points->ViewValue = ewrpt_FormatCurrency($onebar->points->ViewValue, 0, -2, -2, -2);
			$onebar->points->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// invoice_id
			$onebar->invoice_id->ViewValue = $onebar->invoice_id->CurrentValue;
			$onebar->invoice_id->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// transtype
			$onebar->transtype->ViewValue = $onebar->transtype->CurrentValue;
			$onebar->transtype->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
		}

		// username
		$onebar->username->HrefValue = "";

		// np_name
		$onebar->np_name->HrefValue = "";

		// invoice_date
		$onebar->invoice_date->HrefValue = "";

		// points
		$onebar->points->HrefValue = "";

		// invoice_id
		$onebar->invoice_id->HrefValue = "";

		// transtype
		$onebar->transtype->HrefValue = "";

		// Call Row_Rendered event
		$onebar->Row_Rendered();
	}

	// Get extended filter values
	function GetExtendedFilterValues() {
		global $onebar;

		// Field invoice_date
		$sSelect = "SELECT DISTINCT `invoice_date` FROM " . $onebar->SqlFrom();
		$sOrderBy = "`invoice_date` ASC";
		$wrkSql = ewrpt_BuildReportSql($sSelect, $onebar->SqlWhere(), "", "", $sOrderBy, $this->UserIDFilter, "");
		$onebar->invoice_date->DropDownList = ewrpt_GetDistinctValues($onebar->invoice_date->DateFilter, $wrkSql);
	}

	// Return extended filter
	function GetExtendedFilter() {
		global $onebar;
		global $gsFormError;
		$sFilter = "";
		$bPostBack = ewrpt_IsHttpPost();
		$bRestoreSession = TRUE;
		$bSetupFilter = FALSE;

		// Reset extended filter if filter changed
		if ($bPostBack) {

			// Clear dropdown for field invoice_date
			if ($this->ClearExtFilter == 'onebar_invoice_date')
				$this->SetSessionDropDownValue(EWRPT_INIT_VALUE, 'invoice_date');

		// Reset search command
		} elseif (@$_GET["cmd"] == "reset") {

			// Load default values
			// Field invoice_date

			$this->SetSessionDropDownValue($onebar->invoice_date->DropDownValue, 'invoice_date');

			// Field username
			$this->SetSessionFilterValues($onebar->username->SearchValue, $onebar->username->SearchOperator, $onebar->username->SearchCondition, $onebar->username->SearchValue2, $onebar->username->SearchOperator2, 'username');
			$bSetupFilter = TRUE;
		} else {

			// Field invoice_date
			if ($this->GetDropDownValue($onebar->invoice_date->DropDownValue, 'invoice_date')) {
				$bSetupFilter = TRUE;
				$bRestoreSession = FALSE;
			} elseif ($onebar->invoice_date->DropDownValue <> EWRPT_INIT_VALUE && !isset($_SESSION['sv_onebar->invoice_date'])) {
				$bSetupFilter = TRUE;
			}

			// Field username
			if ($this->GetFilterValues($onebar->username)) {
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

			// Field invoice_date
			$this->GetSessionDropDownValue($onebar->invoice_date);

			// Field username
			$this->GetSessionFilterValues($onebar->username);
		}

		// Call page filter validated event
		$onebar->Page_FilterValidated();

		// Build SQL
		// Field invoice_date

		$this->BuildDropDownFilter($onebar->invoice_date, $sFilter, $onebar->invoice_date->DateFilter);

		// Field username
		$this->BuildExtendedFilter($onebar->username, $sFilter);

		// Save parms to session
		// Field invoice_date

		$this->SetSessionDropDownValue($onebar->invoice_date->DropDownValue, 'invoice_date');

		// Field username
		$this->SetSessionFilterValues($onebar->username->SearchValue, $onebar->username->SearchOperator, $onebar->username->SearchCondition, $onebar->username->SearchValue2, $onebar->username->SearchOperator2, 'username');

		// Setup filter
		if ($bSetupFilter) {

			// Field invoice_date
			$sWrk = "";
			$this->BuildDropDownFilter($onebar->invoice_date, $sWrk, $onebar->invoice_date->DateFilter);
			$this->LoadSelectionFromFilter($onebar->invoice_date, $sWrk, $onebar->invoice_date->SelectionList);
			$_SESSION['sel_onebar_invoice_date'] = ($onebar->invoice_date->SelectionList == "") ? EWRPT_INIT_VALUE : $onebar->invoice_date->SelectionList;
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
		$this->GetSessionValue($fld->DropDownValue, 'sv_onebar_' . $parm);
	}

	// Get filter values from session
	function GetSessionFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->SearchValue, 'sv1_onebar_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so1_onebar_' . $parm);
		$this->GetSessionValue($fld->SearchCondition, 'sc_onebar_' . $parm);
		$this->GetSessionValue($fld->SearchValue2, 'sv2_onebar_' . $parm);
		$this->GetSessionValue($fld->SearchOperator2, 'so2_onebar_' . $parm);
	}

	// Get value from session
	function GetSessionValue(&$sv, $sn) {
		if (isset($_SESSION[$sn]))
			$sv = $_SESSION[$sn];
	}

	// Set dropdown value to session
	function SetSessionDropDownValue($sv, $parm) {
		$_SESSION['sv_onebar_' . $parm] = $sv;
	}

	// Set filter values to session
	function SetSessionFilterValues($sv1, $so1, $sc, $sv2, $so2, $parm) {
		$_SESSION['sv1_onebar_' . $parm] = $sv1;
		$_SESSION['so1_onebar_' . $parm] = $so1;
		$_SESSION['sc_onebar_' . $parm] = $sc;
		$_SESSION['sv2_onebar_' . $parm] = $sv2;
		$_SESSION['so2_onebar_' . $parm] = $so2;
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
		global $ReportLanguage, $gsFormError, $onebar;

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
		$_SESSION["sel_onebar_$parm"] = "";
		$_SESSION["rf_onebar_$parm"] = "";
		$_SESSION["rt_onebar_$parm"] = "";
	}

	// Load selection from session
	function LoadSelectionFromSession($parm) {
		global $onebar;
		$fld =& $onebar->fields($parm);
		$fld->SelectionList = @$_SESSION["sel_onebar_$parm"];
		$fld->RangeFrom = @$_SESSION["rf_onebar_$parm"];
		$fld->RangeTo = @$_SESSION["rt_onebar_$parm"];
	}

	// Load default value for filters
	function LoadDefaultFilters() {
		global $onebar;

		/**
		* Set up default values for non Text filters
		*/

		// Field invoice_date
		$onebar->invoice_date->DefaultDropDownValue = allrecords;
		$onebar->invoice_date->DropDownValue = $onebar->invoice_date->DefaultDropDownValue;
		$sWrk = "";
		$this->BuildDropDownFilter($onebar->invoice_date, $sWrk, $onebar->invoice_date->DateFilter);
		$this->LoadSelectionFromFilter($onebar->invoice_date, $sWrk, $onebar->invoice_date->DefaultSelectionList);

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

		// Field username
		$this->SetDefaultExtFilter($onebar->username, "=", 'petsgalore', 'AND', "=", NULL);
		$this->ApplyDefaultExtFilter($onebar->username);

		/**
		* Set up default values for popup filters
		* NOTE: if extended filter is enabled, use default values in extended filter instead
		*/
	}

	// Check if filter applied
	function CheckFilter() {
		global $onebar;

		// Check invoice_date extended filter
		if ($this->NonTextFilterApplied($onebar->invoice_date))
			return TRUE;

		// Check invoice_date popup filter
		if (!ewrpt_MatchedArray($onebar->invoice_date->DefaultSelectionList, $onebar->invoice_date->SelectionList))
			return TRUE;

		// Check username text filter
		if ($this->TextFilterApplied($onebar->username))
			return TRUE;
		return FALSE;
	}

	// Show list of filters
	function ShowFilterList() {
		global $onebar;
		global $ReportLanguage;

		// Initialize
		$sFilterList = "";

		// Field invoice_date
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($onebar->invoice_date, $sExtWrk, $onebar->invoice_date->DateFilter);
		if (is_array($onebar->invoice_date->SelectionList))
			$sWrk = ewrpt_JoinArray($onebar->invoice_date->SelectionList, ", ", EWRPT_DATATYPE_DATE);
		if ($sExtWrk <> "" || $sWrk <> "")
			$sFilterList .= $onebar->invoice_date->FldCaption() . "<br />";
		if ($sExtWrk <> "")
			$sFilterList .= "&nbsp;&nbsp;$sExtWrk<br />";
		if ($sWrk <> "")
			$sFilterList .= "&nbsp;&nbsp;$sWrk<br />";

		// Field username
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($onebar->username, $sExtWrk);
		if ($sExtWrk <> "" || $sWrk <> "")
			$sFilterList .= $onebar->username->FldCaption() . "<br />";
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
		global $onebar;
		$sWrk = "";
		if (!$this->DropDownFilterExist($onebar->invoice_date, $onebar->invoice_date->DateFilter)) {
			if (is_array($onebar->invoice_date->SelectionList)) {
				if ($sWrk <> "") $sWrk .= " AND ";
				$sWrk .= ewrpt_FilterSQL($onebar->invoice_date, "`invoice_date`", EWRPT_DATATYPE_DATE);
			}
		}
		return $sWrk;
	}

	//-------------------------------------------------------------------------------
	// Function GetSort
	// - Return Sort parameters based on Sort Links clicked
	// - Variables setup: Session[EWRPT_TABLE_SESSION_ORDER_BY], Session["sort_Table_Field"]
	function GetSort() {
		global $onebar;

		// Check for a resetsort command
		if (strlen(@$_GET["cmd"]) > 0) {
			$sCmd = @$_GET["cmd"];
			if ($sCmd == "resetsort") {
				$onebar->setOrderBy("");
				$onebar->setStartGroup(1);
				$onebar->username->setSort("");
				$onebar->np_name->setSort("");
				$onebar->invoice_date->setSort("");
				$onebar->points->setSort("");
				$onebar->invoice_id->setSort("");
				$onebar->transtype->setSort("");
			}

		// Check for an Order parameter
		} elseif (@$_GET["order"] <> "") {
			$onebar->CurrentOrder = ewrpt_StripSlashes(@$_GET["order"]);
			$onebar->CurrentOrderType = @$_GET["ordertype"];
			$sSortSql = $onebar->SortSql();
			$onebar->setOrderBy($sSortSql);
			$onebar->setStartGroup(1);
		}
		return $onebar->getOrderBy();
	}

	// Export email
	function ExportEmail($EmailContent) {
		global $ReportLanguage, $onebar;
		$sSender = @$_GET["sender"];
		$sRecipient = @$_GET["recipient"];
		$sCc = @$_GET["cc"];
		$sBcc = @$_GET["bcc"];
		$sContentType = @$_GET["contenttype"];

		// Subject
		$sSubject = ewrpt_StripSlashes(@$_GET["subject"]);
		$sEmailSubject = $sSubject;

		// Message
		$sContent = ewrpt_StripSlashes(@$_GET["message"]);
		$sEmailMessage = $sContent;

		// Check sender
		if ($sSender == "") {
			$this->setMessage($ReportLanguage->Phrase("EnterSenderEmail"));
			return;
		}
		if (!ewrpt_CheckEmail($sSender)) {
			$this->setMessage($ReportLanguage->Phrase("EnterProperSenderEmail"));
			return;
		}

		// Check recipient
		if (!ewrpt_CheckEmailList($sRecipient, EWRPT_MAX_EMAIL_RECIPIENT)) {
			$this->setMessage($ReportLanguage->Phrase("EnterProperRecipientEmail"));
			return;
		}

		// Check cc
		if (!ewrpt_CheckEmailList($sCc, EWRPT_MAX_EMAIL_RECIPIENT)) {
			$this->setMessage($ReportLanguage->Phrase("EnterProperCcEmail"));
			return;
		}

		// Check bcc
		if (!ewrpt_CheckEmailList($sBcc, EWRPT_MAX_EMAIL_RECIPIENT)) {
			$this->setMessage($ReportLanguage->Phrase("EnterProperBccEmail"));
			return;
		}

		// Check email sent count
		$emailcount = ewrpt_LoadEmailCount();
		if (intval($emailcount) >= EWRPT_MAX_EMAIL_SENT_COUNT) {
			$this->setMessage($ReportLanguage->Phrase("ExceedMaxEmailExport"));
			return;
		}
		if ($sEmailMessage <> "") {
			if (EWRPT_REMOVE_XSS) $sEmailMessage = ewrpt_RemoveXSS($sEmailMessage);
			$sEmailMessage .= ($sContentType == "url") ? "\r\n\r\n" : "<br><br>";
		}
		$sAttachmentContent = $EmailContent;
		$sAppPath = ewrpt_FullUrl();
		$sAppPath = substr($sAppPath, 0, strrpos($sAppPath, "/")+1);
		if (strpos($sAttachmentContent, "<head>") !== FALSE)
			$sAttachmentContent = str_replace("<head>", "<head><base href=\"" . $sAppPath . "\" />", $sAttachmentContent); // Add <base href> statement inside the header
		else
			$sAttachmentContent = "<base href=\"" . $sAppPath . "\" />" . $sAttachmentContent; // Add <base href> statement as the first statement

		//$sAttachmentFile = $onebar->TableVar . "_" . Date("YmdHis") . ".html";
		$sAttachmentFile = $onebar->TableVar . "_" . Date("YmdHis") . "_" . ewrpt_Random() . ".html";
		if ($sContentType == "url") {
			ewrpt_SaveFile(EWRPT_UPLOAD_DEST_PATH, $sAttachmentFile, $sAttachmentContent);
			$sAttachmentFile = EWRPT_UPLOAD_DEST_PATH . $sAttachmentFile;
			$sUrl = $sAppPath . $sAttachmentFile;
			$sEmailMessage .= $sUrl; // send URL only
			$sAttachmentFile = "";
			$sAttachmentContent = "";
		}

		// Send email
		$Email = new crEmail();
		$Email->Sender = $sSender; // Sender
		$Email->Recipient = $sRecipient; // Recipient
		$Email->Cc = $sCc; // Cc
		$Email->Bcc = $sBcc; // Bcc
		$Email->Subject = $sEmailSubject; // Subject
		$Email->Content = $sEmailMessage; // Content
		$Email->AttachmentContent = $sAttachmentContent; // Attachment
		$Email->AttachmentFileName = $sAttachmentFile; // Attachment file name
		$Email->Format = ($sContentType == "url") ? "text" : "html";
		$Email->Charset = EWRPT_EMAIL_CHARSET;
		$EventArgs = array();
		$bEmailSent = FALSE;
		if ($onebar->Email_Sending($Email, $EventArgs))
			$bEmailSent = $Email->Send();

		// Check email sent status
		if ($bEmailSent) {

			// Update email sent count and write log
			ewrpt_AddEmailLog($sSender, $sRecipient, $sEmailSubject, $sEmailMessage);

			// Sent email success
			$this->setMessage($ReportLanguage->Phrase("SendEmailSuccess"));
		} else {

			// Sent email failure
			$this->setMessage($Email->SendErrDescription);
		}
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
