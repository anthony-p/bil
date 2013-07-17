<?php
/**
 * Created by Lilian Codreanu.
 * User: Lilian Codreanu
 * Date: 2/2/13
 * Time: 11:55 PM
 */


class crClickthrough_details_report extends crTableBase {

//	var $SelectLimit = TRUE;
	var $unique_id;
	var $tracking_link;
	var $site_user;
	var $user_name;
	var $click_date;
	var $vendor;
	var $np2Did;
	var $np2Dname;
	var $Sales;
	var $Commission;
	var $pct;
	var $pct_giveback;
	var $np2Dshare;
	var $bil2Dshare;

	//
	// Table class constructor
	//
	function __construct() {
		global $ReportLanguage;
		$this->TableVar = 'Clickthrough_details_report';
		$this->TableName = 'Clickthrough details report';
		$this->TableType = 'REPORT';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0;

		// unique id
		$this->unique_id = new crField('Clickthrough_details_report', 'Clickthrough details report', 'x_unique_id', 'unique id', '`unique id`', 200, EWR_DATATYPE_STRING, -1);
		$this->fields['unique_id'] = &$this->unique_id;
		$this->unique_id->DateFilter = "";
		$this->unique_id->SqlSelect = "";
		$this->unique_id->SqlOrderBy = "";

		// tracking link
		$this->tracking_link = new crField('Clickthrough_details_report', 'Clickthrough details report', 'x_tracking_link', 'tracking link', '`tracking link`', 200, EWR_DATATYPE_STRING, -1);
		$this->fields['tracking_link'] = &$this->tracking_link;
		$this->tracking_link->DateFilter = "";
		$this->tracking_link->SqlSelect = "";
		$this->tracking_link->SqlOrderBy = "";

		// site user
		$this->site_user = new crField('Clickthrough_details_report', 'Clickthrough details report', 'x_site_user', 'site user', '`site user`', 200, EWR_DATATYPE_STRING, -1);
		$this->fields['site_user'] = &$this->site_user;
		$this->site_user->DateFilter = "";
		$this->site_user->SqlSelect = "";
		$this->site_user->SqlOrderBy = "";

		// user name
		$this->user_name = new crField('Clickthrough_details_report', 'Clickthrough details report', 'x_user_name', 'user name', '`user name`', 200, EWR_DATATYPE_STRING, -1);
		$this->fields['user_name'] = &$this->user_name;
		$this->user_name->DateFilter = "";
		$this->user_name->SqlSelect = "";
		$this->user_name->SqlOrderBy = "";

		// click date
		$this->click_date = new crField('Clickthrough_details_report', 'Clickthrough details report', 'x_click_date', 'click date', '`click date`', 133, EWR_DATATYPE_DATE, 5);
		$this->click_date->GroupingFieldId = 2;
		$this->click_date->FldDefaultErrMsg = str_replace("%s", "/", $ReportLanguage->Phrase("IncorrectDateYMD"));
		$this->fields['click_date'] = &$this->click_date;
		$this->click_date->DateFilter = "";
		$this->click_date->SqlSelect = "";
		$this->click_date->SqlOrderBy = "";
		$this->click_date->FldGroupByType = "";
		$this->click_date->FldGroupInt = "0";
		$this->click_date->FldGroupSql = "";

		// vendor
		$this->vendor = new crField('Clickthrough_details_report', 'Clickthrough details report', 'x_vendor', 'vendor', '`vendor`', 200, EWR_DATATYPE_STRING, -1);
		$this->fields['vendor'] = &$this->vendor;
		$this->vendor->DateFilter = "";
		$this->vendor->SqlSelect = "";
		$this->vendor->SqlOrderBy = "";

		// np-id
		$this->np2Did = new crField('Clickthrough_details_report', 'Clickthrough details report', 'x_np2Did', 'np-id', '`np-id`', 200, EWR_DATATYPE_STRING, -1);
		$this->fields['np2Did'] = &$this->np2Did;
		$this->np2Did->DateFilter = "";
		$this->np2Did->SqlSelect = "";
		$this->np2Did->SqlOrderBy = "";

		// np-name
		$this->np2Dname = new crField('Clickthrough_details_report', 'Clickthrough details report', 'x_np2Dname', 'np-name', '`np-name`', 200, EWR_DATATYPE_STRING, -1);
		$this->np2Dname->GroupingFieldId = 1;
		$this->fields['np2Dname'] = &$this->np2Dname;
		$this->np2Dname->DateFilter = "";
		$this->np2Dname->SqlSelect = "";
		$this->np2Dname->SqlOrderBy = "";
		$this->np2Dname->FldGroupByType = "";
		$this->np2Dname->FldGroupInt = "0";
		$this->np2Dname->FldGroupSql = "";

		// Sales
		$this->Sales = new crField('Clickthrough_details_report', 'Clickthrough details report', 'x_Sales', 'Sales', '`Sales`', 5, EWR_DATATYPE_NUMBER, -1);
		$this->Sales->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['Sales'] = &$this->Sales;
		$this->Sales->DateFilter = "";
		$this->Sales->SqlSelect = "";
		$this->Sales->SqlOrderBy = "";

		// Commission
		$this->Commission = new crField('Clickthrough_details_report', 'Clickthrough details report', 'x_Commission', 'Commission', '`Commission`', 5, EWR_DATATYPE_NUMBER, -1);
		$this->Commission->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['Commission'] = &$this->Commission;
		$this->Commission->DateFilter = "";
		$this->Commission->SqlSelect = "";
		$this->Commission->SqlOrderBy = "";

		// pct
		$this->pct = new crField('Clickthrough_details_report', 'Clickthrough details report', 'x_pct', 'pct', '`pct`', 200, EWR_DATATYPE_STRING, -1);
		$this->fields['pct'] = &$this->pct;
		$this->pct->DateFilter = "";
		$this->pct->SqlSelect = "";
		$this->pct->SqlOrderBy = "";

		// pct_giveback
		$this->pct_giveback = new crField('Clickthrough_details_report', 'Clickthrough details report', 'x_pct_giveback', 'pct_giveback', '`pct_giveback`', 200, EWR_DATATYPE_STRING, -1);
		$this->fields['pct_giveback'] = &$this->pct_giveback;
		$this->pct_giveback->DateFilter = "";
		$this->pct_giveback->SqlSelect = "";
		$this->pct_giveback->SqlOrderBy = "";

		// np-share
		$this->np2Dshare = new crField('Clickthrough_details_report', 'Clickthrough details report', 'x_np2Dshare', 'np-share', '`np-share`', 5, EWR_DATATYPE_NUMBER, -1);
		$this->np2Dshare->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['np2Dshare'] = &$this->np2Dshare;
		$this->np2Dshare->DateFilter = "";
		$this->np2Dshare->SqlSelect = "";
		$this->np2Dshare->SqlOrderBy = "";

		// bil-share
		$this->bil2Dshare = new crField('Clickthrough_details_report', 'Clickthrough details report', 'x_bil2Dshare', 'bil-share', '`bil-share`', 5, EWR_DATATYPE_NUMBER, -1);
		$this->bil2Dshare->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['bil2Dshare'] = &$this->bil2Dshare;
		$this->bil2Dshare->DateFilter = "";
		$this->bil2Dshare->SqlSelect = "";
		$this->bil2Dshare->SqlOrderBy = "";
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
		return "`vendor_click_reports`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlWhere() { // Where
		$sWhere = "";
		return $sWhere;
	}

	function SqlGroupBy() { // Group By
		return "";
	}

	function SqlHaving() { // Having
		return "";
	}

	function SqlOrderBy() { // Order By
		return "`np-name` ASC, `click date` ASC";
	}

	// Table Level Group SQL
	function SqlFirstGroupField() {
		return "`np-name`";
	}

	function SqlSelectGroup() {
		return "SELECT DISTINCT " . $this->SqlFirstGroupField() . " FROM " . $this->SqlFrom();
	}

	function SqlOrderByGroup() {
		return "`np-name` ASC";
	}

	function SqlSelectAgg() {
		return "SELECT SUM(`Sales`) AS `sum_sales`, SUM(`np-share`) AS `sum_np2dshare` FROM " . $this->SqlFrom();
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

	// Table level events
	// Page Selecting event
	function Page_Selecting(&$filter) {

		// Enter your code here
	}

	// Page Breaking event
	function Page_Breaking(&$break, &$content) {

		// Example:
		//$break = FALSE; // Skip page break, or
		//$content = "<div style=\"page-break-after:always;\">&nbsp;</div>"; // Modify page break content

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here
	}

	// Cell Rendered event
	function Cell_Rendered(&$Field, $CurrentValue, &$ViewValue, &$ViewAttrs, &$CellAttrs, &$HrefValue, &$LinkAttrs) {

		//$ViewValue = "xxx";
		//$ViewAttrs["style"] = "xxx";

	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}

	// Load Filters event
	function Page_FilterLoad() {

		// Enter your code here
		// Example: Register/Unregister Custom Extended Filter
		//ewr_RegisterFilter($this-><Field>, 'StartsWithA', 'Starts With A', 'GetStartsWithAFilter'); // With function, or
		//ewr_RegisterFilter($this-><Field>, 'StartsWithA', 'Starts With A'); // No function, use Page_Filtering event
		//ewr_UnregisterFilter($this-><Field>, 'StartsWithA');

	}

	// Page Filter Validated event
	function Page_FilterValidated() {

		// Example:
		//$this->MyField1->SearchValue = "your search criteria"; // Search value

	}

	// Page Filtering event
	function Page_Filtering(&$fld, &$filter, $typ, $opr = "", $val = "", $cond = "", $opr2 = "", $val2 = "") {

		// Note: ALWAYS CHECK THE FILTER TYPE ($typ)! Example:
		// if ($typ == "dropdown" && $fld->FldName == "MyField") // Dropdown filter
		//     $filter = "..."; // Modify the filter
		// if ($typ == "extended" && $fld->FldName == "MyField") // Extended filter
		//     $filter = "..."; // Modify the filter
		// if ($typ == "popup" && $fld->FldName == "MyField") // Popup filter
		//     $filter = "..."; // Modify the filter
		// if ($typ == "custom" && $opr == "..." && $fld->FldName == "MyField") // Custom filter, $opr is the custom filter ID
		//     $filter = "..."; // Modify the filter

	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}
}


class crClickthrough_details_report_summary extends crClickthrough_details_report {

	// Page ID
	var $PageID = 'summary';

	// Project ID
	var $ProjectID = "{1970966F-5C22-4F67-9D47-2A354E499C8D}";

	// Page object name
	var $PageObjName = 'Clickthrough_details_report_summary';

	// Page name
	function PageName() {
		return ewr_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ewr_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Export URLs
	var $ExportPrintUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportPdfUrl;
	var $ReportTableClass;

	// Message
	function getMessage() {
		return @$_SESSION[EWR_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EWR_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EWR_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EWR_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			$html .= "<div class=\"ewSpacer ewMessage\">" . $sMessage . "</div>";
			$_SESSION[EWR_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewWarningIcon\"></td><td class=\"ewWarningMessage\">" . $sWarningMessage . "</td></tr></table>";
			$_SESSION[EWR_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewSuccessIcon\"></td><td class=\"ewSuccessMessage\">" . $sSuccessMessage . "</td></tr></table>";
			$_SESSION[EWR_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewErrorIcon\"></td><td class=\"ewErrorMessage\">" . $sErrorMessage . "</td></tr></table>";
			$_SESSION[EWR_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<div class=\"ewSpacer\"><span class=\"phpreportmaker\">" . $sHeader . "</span></div>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Fotoer exists, display
			echo "<div class=\"ewSpacer\"><span class=\"phpreportmaker\">" . $sFooter . "</span></div>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		if ($this->UseTokenInUrl) {
			if (ewr_IsHttpPost())
				return ($this->TableVar == @$_POST("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == @$_GET["t"]);
		} else {
			return TRUE;
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $ReportLanguage, $UserAgent;

		// Language object
		$ReportLanguage = new crLanguage();

		// User agent
		$UserAgent = ewr_UserAgent();

		// Parent constuctor
		parent::__construct();

		// Table object (Clickthrough_details_report)
		if (!isset($GLOBALS["Clickthrough_details_report"])) {
			$GLOBALS["Clickthrough_details_report"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["Clickthrough_details_report"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";

		// Page ID
		if (!defined("EWR_PAGE_ID"))
			define("EWR_PAGE_ID", 'summary', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EWR_TABLE_NAME"))
			define("EWR_TABLE_NAME", 'Clickthrough details report', TRUE);

		// Start timer
		$GLOBALS["gsTimer"] = new crTimer();

		// Open connection
		$conn = ewr_Connect();

		// Export options
		$this->ExportOptions = new crListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->TagClassName = "ewExportOption";
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $giFcfChartCnt, $ReportLanguage, $Security;

		// Get export parameters
		if (@$_GET["export"] <> "")
			$this->Export = strtolower($_GET["export"]);
		elseif (@$_POST["export"] <> "")
			$this->Export = strtolower($_POST["export"]);
		$gsExport = $this->Export; // Get export parameter, used in header
		$gsExportFile = $this->TableVar; // Get export file, used in header
		$giFcfChartCnt = 0; // Get chart count, used in header

		// Setup export options
		$this->SetupExportOptions();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	// Set up export options
	function SetupExportOptions() {
		global $ReportLanguage;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\">" . $ReportLanguage->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\">" . $ReportLanguage->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\">" . $ReportLanguage->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\">" . $ReportLanguage->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Uncomment codes below to show export to Pdf link
//		$item->Visible = TRUE;
		// Export to Email

		$item = &$this->ExportOptions->Add("email");
		$exportid = session_id();
		$url = $this->PageUrl() . "export=email";
		$item->Body = "<a id=\"emf_Clickthrough_details_report\" href=\"javascript:void(0);\" onclick=\"ewr_EmailDialogShow({lnk:'emf_Clickthrough_details_report',hdr:ewLanguage.Phrase('ExportToEmail'),url:'$url',exportid:'$exportid',el:this});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
		$item->Visible = TRUE;

		// Reset filter
		$item = &$this->ExportOptions->Add("resetfilter");
		$item->Body = "<a href=\"" . ewr_CurrentPage() . "?cmd=reset\">" . $ReportLanguage->Phrase("ResetAllFilter") . "</a>";
		$item->Visible = TRUE;
		$this->SetupExportOptionsExt();

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();

		// Set up table class
		if ($this->Export == "word" || $this->Export == "excel" || $this->Export == "pdf")
			$this->ReportTableClass = "ewTable";
		else
			$this->ReportTableClass = "ewTable ewTableSeparate";
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn, $ReportLanguage, $EWR_EXPORT;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		if ($this->Export <> "" && array_key_exists($this->Export, $EWR_EXPORT)) {
			$sContent = ob_get_contents();
			$fn = $EWR_EXPORT[$this->Export];
			$this->$fn($sContent);
			if ($this->Export == "email") { // Email
				ob_end_clean();
				$conn->Close(); // Close connection
				header("Location: " . ewr_CurrentPage());
				exit();
			}
		}

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EWR_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}

	// Initialize common variables
	var $ExportOptions; // Export options

	// Paging variables
	var $RecCount = 0; // Record count
	var $StartGrp = 0; // Start group
	var $StopGrp = 0; // Stop group
	var $TotalGrps = 0; // Total groups
	var $GrpCount = 0; // Group count
	var $DisplayGrps = 10; // Groups per page
	var $GrpRange = 10;
	var $Sort = "";
	var $Filter = "";
	var $PageFirstGroupFilter = "";
	var $UserIDFilter = "";
	var $DrillDown = FALSE;
	var $DrillDownInPanel = FALSE;
	var $DrillDownList = "";

	// Clear field for ext filter
	var $ClearExtFilter = "";
	var $PopupName = "";
	var $PopupValue = "";
	var $FilterApplied;
	var $SearchCommand = FALSE;
	var $ShowHeader;
	var $GrpFldCount = 0;
	var $SubGrpFldCount = 0;
	var $DtlFldCount = 0;
	var $Cnt, $Col, $Val, $Smry, $Mn, $Mx, $GrandCnt, $GrandSmry, $GrandMn, $GrandMx;
	var $TotCount;
	var $GrandSummarySetup = FALSE;

	//
	// Page main
	//
	function Page_Main() {
		global $rs;
		global $rsgrp;
		global $gsFormError;
		global $gbDrillDownInPanel;

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 6;
		$nGrps = 3;
		$this->Val = &ewr_InitArray($nDtls, 0);
		$this->Cnt = &ewr_Init2DArray($nGrps, $nDtls, 0);
		$this->Smry = &ewr_Init2DArray($nGrps, $nDtls, 0);
		$this->Mn = &ewr_Init2DArray($nGrps, $nDtls, NULL);
		$this->Mx = &ewr_Init2DArray($nGrps, $nDtls, NULL);
		$this->GrandCnt = &ewr_InitArray($nDtls, 0);
		$this->GrandSmry = &ewr_InitArray($nDtls, 0);
		$this->GrandMn = &ewr_InitArray($nDtls, NULL);
		$this->GrandMx = &ewr_InitArray($nDtls, NULL);

		// Set up if accumulation required (array(Accum, SkipNullOrZero)
		$this->Col = array(array(FALSE, FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(TRUE,FALSE), array(FALSE,FALSE), array(TRUE,FALSE));

		// Set up groups per page dynamically
		$this->SetUpDisplayGrps();

		// Check if search command
//		$this->SearchCommand = (@$_GET["cmd"] == "search");
		$this->SearchCommand = true;

		// Load default filter values
		$this->LoadDefaultFilters();

		// Load custom filters
		$this->Page_FilterLoad();

		// Set up popup filter
		$this->SetupPopup();

		// Load group db values if necessary
		$this->LoadGroupDbValues();

		// Handle Ajax popup
		$this->ProcessAjaxPopup();

		// Extended filter
		$sExtendedFilter = "";

		// Build extended filter
		$sExtendedFilter = $this->GetExtendedFilter();
		ewr_AddFilter($this->Filter, $sExtendedFilter);

		// Build popup filter
		$sPopupFilter = $this->GetPopupFilter();

		//ewr_SetDebugMsg("popup filter: " . $sPopupFilter);
		ewr_AddFilter($this->Filter, $sPopupFilter);

		// Check if filter applied
		$this->FilterApplied = $this->CheckFilter();

		// Call Page Selecting event
		$this->Page_Selecting($this->Filter);
		$this->ExportOptions->GetItem("resetfilter")->Visible = $this->FilterApplied;

		// Get sort
		$this->Sort = $this->GetSort();

		// Get total group count
		$sGrpSort = ewr_UpdateSortFields($this->SqlOrderByGroup(), $this->Sort, 2); // Get grouping field only
		$sSql = ewr_BuildReportSql($this->SqlSelectGroup(), $this->SqlWhere(), $this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderByGroup(), $this->Filter, $sGrpSort);
		$this->TotalGrps = $this->GetGrpCnt($sSql);
		if ($this->DisplayGrps <= 0 || $this->DrillDown) // Display all groups
			$this->DisplayGrps = $this->TotalGrps;
		$this->StartGrp = 1;

		// Show header
		$this->ShowHeader = ($this->TotalGrps > 0);

		// Set up start position if not export all
		if ($this->ExportAll && $this->Export <> "")
		    $this->DisplayGrps = $this->TotalGrps;
		else
			$this->SetUpStartGroup();

		// Hide all options if export
		if ($this->Export <> "") {
			$this->ExportOptions->HideAllOptions();
		}

		// Get current page groups
		$rsgrp = $this->GetGrpRs($sSql, $this->StartGrp, $this->DisplayGrps);

		// Init detail recordset
		$rs = NULL;
		$this->SetupFieldCount();
	}

	// Check level break
	function ChkLvlBreak($lvl) {
		switch ($lvl) {
			case 1:
				return (is_null($this->np2Dname->CurrentValue) && !is_null($this->np2Dname->OldValue)) ||
					(!is_null($this->np2Dname->CurrentValue) && is_null($this->np2Dname->OldValue)) ||
					($this->np2Dname->GroupValue() <> $this->np2Dname->GroupOldValue());
			case 2:
				return (is_null($this->click_date->CurrentValue) && !is_null($this->click_date->OldValue)) ||
					(!is_null($this->click_date->CurrentValue) && is_null($this->click_date->OldValue)) ||
					($this->click_date->GroupValue() <> $this->click_date->GroupOldValue()) || $this->ChkLvlBreak(1); // Recurse upper level
		}
	}

	// Accummulate summary
	function AccumulateSummary() {
		$cntx = count($this->Smry);
		for ($ix = 0; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 1; $iy < $cnty; $iy++) {
				if ($this->Col[$iy][0]) { // Accumulate required
					$valwrk = $this->Val[$iy];
					if (is_null($valwrk) || !is_numeric($valwrk)) {
						if (!$this->Col[$iy][1])
							$this->Cnt[$ix][$iy]++;
					} else {
						if (!$this->Col[$iy][1] || $valwrk <> 0) {
							$this->Cnt[$ix][$iy]++;
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
		}
		$cntx = count($this->Smry);
		for ($ix = 0; $ix < $cntx; $ix++) {
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
				if ($this->Col[$iy][0]) {
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
		$this->TotCount++;
		$cntgs = count($this->GrandSmry);
		for ($iy = 1; $iy < $cntgs; $iy++) {
			if ($this->Col[$iy][0]) {
				$valwrk = $this->Val[$iy];
				if (is_null($valwrk) || !is_numeric($valwrk)) {
					if (!$this->Col[$iy][1])
						$this->GrandCnt[$iy]++;
				} else {
					if (!$this->Col[$iy][1] || $valwrk <> 0) {
						$this->GrandCnt[$iy]++;
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
	}

	// Get group count
	function GetGrpCnt($sql) {
		global $conn;
		$rsgrpcnt = $conn->Execute($sql);
		$grpcnt = ($rsgrpcnt) ? $rsgrpcnt->RecordCount() : 0;
		if ($rsgrpcnt) $rsgrpcnt->Close();
		return $grpcnt;
	}

	// Get group rs
	function GetGrpRs($sql, $start, $grps) {
		global $conn;
		$wrksql = $sql;
		if ($start > 0 && $grps > -1)
			$wrksql .= " LIMIT " . ($start-1) . ", " . ($grps);
		$rswrk = $conn->Execute($wrksql);
		return $rswrk;
	}

	// Get group row values
	function GetGrpRow($opt) {
		global $rsgrp;
		if (!$rsgrp)
			return;
		if ($opt == 1) { // Get first group

			//$rsgrp->MoveFirst(); // NOTE: no need to move position
			$this->np2Dname->setDbValue(""); // Init first value
		} else { // Get next group
			$rsgrp->MoveNext();
		}
		if (!$rsgrp->EOF)
			$this->np2Dname->setDbValue($rsgrp->fields[0]);
		if ($rsgrp->EOF) {
			$this->np2Dname->setDbValue("");
		}
	}

	// Get row values
	function GetRow($opt) {
		global $rs;
		if (!$rs)
			return;
		if ($opt == 1) { // Get first row

	//		$rs->MoveFirst(); // NOTE: no need to move position
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$this->unique_id->setDbValue($rs->fields('unique id'));
			$this->tracking_link->setDbValue($rs->fields('tracking link'));
			$this->site_user->setDbValue($rs->fields('site user'));
			$this->user_name->setDbValue($rs->fields('user name'));
			$this->click_date->setDbValue($rs->fields('click date'));
			$this->vendor->setDbValue($rs->fields('vendor'));
			$this->np2Did->setDbValue($rs->fields('np-id'));
			if ($opt <> 1) {
				if (is_array($this->np2Dname->GroupDbValues))
					$this->np2Dname->setDbValue(@$this->np2Dname->GroupDbValues[$rs->fields('np-name')]);
				else
					$this->np2Dname->setDbValue(ewr_GroupValue($this->np2Dname, $rs->fields('np-name')));
			}
			$this->Sales->setDbValue($rs->fields('Sales'));
			$this->Commission->setDbValue($rs->fields('Commission'));
			$this->pct->setDbValue($rs->fields('pct'));
			$this->pct_giveback->setDbValue($rs->fields('pct_giveback'));
			$this->np2Dshare->setDbValue($rs->fields('np-share'));
			$this->bil2Dshare->setDbValue($rs->fields('bil-share'));
			$this->Val[1] = $this->tracking_link->CurrentValue;
			$this->Val[2] = $this->user_name->CurrentValue;
			$this->Val[3] = $this->Sales->CurrentValue;
			$this->Val[4] = $this->pct_giveback->CurrentValue;
			$this->Val[5] = $this->np2Dshare->CurrentValue;
		} else {
			$this->unique_id->setDbValue("");
			$this->tracking_link->setDbValue("");
			$this->site_user->setDbValue("");
			$this->user_name->setDbValue("");
			$this->click_date->setDbValue("");
			$this->vendor->setDbValue("");
			$this->np2Did->setDbValue("");
			$this->np2Dname->setDbValue("");
			$this->Sales->setDbValue("");
			$this->Commission->setDbValue("");
			$this->pct->setDbValue("");
			$this->pct_giveback->setDbValue("");
			$this->np2Dshare->setDbValue("");
			$this->bil2Dshare->setDbValue("");
		}
	}

	//  Set up starting group
	function SetUpStartGroup() {

		// Exit if no groups
		if ($this->DisplayGrps == 0)
			return;

		// Check for a 'start' parameter
		if (@$_GET[EWR_TABLE_START_GROUP] != "") {
			$this->StartGrp = $_GET[EWR_TABLE_START_GROUP];
			$this->setStartGroup($this->StartGrp);
		} elseif (@$_GET["pageno"] != "") {
			$nPageNo = $_GET["pageno"];
			if (is_numeric($nPageNo)) {
				$this->StartGrp = ($nPageNo-1)*$this->DisplayGrps+1;
				if ($this->StartGrp <= 0) {
					$this->StartGrp = 1;
				} elseif ($this->StartGrp >= intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1) {
					$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1;
				}
				$this->setStartGroup($this->StartGrp);
			} else {
				$this->StartGrp = $this->getStartGroup();
			}
		} else {
			$this->StartGrp = $this->getStartGroup();
		}

		// Check if correct start group counter
		if (!is_numeric($this->StartGrp) || $this->StartGrp == "") { // Avoid invalid start group counter
			$this->StartGrp = 1; // Reset start group counter
			$this->setStartGroup($this->StartGrp);
		} elseif (intval($this->StartGrp) > intval($this->TotalGrps)) { // Avoid starting group > total groups
			$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to last page first group
			$this->setStartGroup($this->StartGrp);
		} elseif (($this->StartGrp-1) % $this->DisplayGrps <> 0) {
			$this->StartGrp = intval(($this->StartGrp-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to page boundary
			$this->setStartGroup($this->StartGrp);
		}
	}

	// Load group db values if necessary
	function LoadGroupDbValues() {
		global $conn;
	}

	// Process Ajax popup
	function ProcessAjaxPopup() {
		global $conn, $ReportLanguage;
		$fld = NULL;
		if (@$_GET["popup"] <> "") {
			$popupname = $_GET["popup"];

			// Check popup name
			// Output data as Json

			if (!is_null($fld)) {
				$jsdb = ewr_GetJsDb($fld, $fld->FldType);
				ob_end_clean();
				echo $jsdb;
				exit();
			}
		}
	}

	// Set up popup
	function SetupPopup() {
		global $conn, $ReportLanguage;
		if ($this->DrillDown)
			return;

		// Process post back form
		if (ewr_IsHttpPost()) {
			$sName = @$_POST["popup"]; // Get popup form name
			if ($sName <> "") {
				$cntValues = (is_array(@$_POST["sel_$sName"])) ? count($_POST["sel_$sName"]) : 0;
				if ($cntValues > 0) {
					$arValues = ewr_StripSlashes($_POST["sel_$sName"]);
					if (trim($arValues[0]) == "") // Select all
						$arValues = EWR_INIT_VALUE;
					$this->PopupName = $sName;
					if (ewr_IsAdvancedFilterValue($arValues) || $arValues == EWR_INIT_VALUE)
						$this->PopupValue = $arValues;
					if (!ewr_MatchedArray($arValues, $_SESSION["sel_$sName"])) {
						if ($this->HasSessionFilterValues($sName))
							$this->ClearExtFilter = $sName; // Clear extended filter for this field
					}
					$_SESSION["sel_$sName"] = $arValues;
					$_SESSION["rf_$sName"] = ewr_StripSlashes(@$_POST["rf_$sName"]);
					$_SESSION["rt_$sName"] = ewr_StripSlashes(@$_POST["rt_$sName"]);
					$this->ResetPager();
				}
			}

		// Get 'reset' command
		} elseif (@$_GET["cmd"] <> "") {
			$sCmd = $_GET["cmd"];
			if (strtolower($sCmd) == "reset") {
				$this->ResetPager();
			}
		}

		// Load selection criteria to array
	}

	// Reset pager
	function ResetPager() {

		// Reset start position (reset command)
		$this->StartGrp = 1;
		$this->setStartGroup($this->StartGrp);
        $this->GrandSummarySetup = false;
	}

	// Set up number of groups displayed per page
	function SetUpDisplayGrps() {
		$sWrk = @$_GET[EWR_TABLE_GROUP_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayGrps = intval($sWrk);
			} else {
				if (strtoupper($sWrk) == "ALL") { // display all groups
					$this->DisplayGrps = -1;
				} else {
					$this->DisplayGrps = 10; // Non-numeric, load default
				}
			}
			$this->setGroupPerPage($this->DisplayGrps); // Save to session

			// Reset start position (reset command)
			$this->StartGrp = 1;
			$this->setStartGroup($this->StartGrp);
		} else {
			if ($this->getGroupPerPage() <> "") {
				$this->DisplayGrps = $this->getGroupPerPage(); // Restore from session
			} else {
				$this->DisplayGrps = 10; // Load default
			}
		}
	}

	// Render row
	function RenderRow() {
		global $conn, $rs, $Security;
		if ($this->RowTotalType == EWR_ROWTOTAL_GRAND && !$this->GrandSummarySetup) { // Grand total
			$bGotCount = FALSE;
			$bGotSummary = FALSE;

			// Get total count from sql directly
			$sSql = ewr_BuildReportSql($this->SqlSelectCount(), $this->SqlWhere(), $this->SqlGroupBy(), $this->SqlHaving(), "", $this->Filter, "");
			$rstot = $conn->Execute($sSql);
			if ($rstot) {
				$this->TotCount = ($rstot->RecordCount()>1) ? $rstot->RecordCount() : $rstot->fields[0];
				$rstot->Close();
				$bGotCount = TRUE;
			} else {
				$this->TotCount = 0;
			}

			// Get total from sql directly
			$sSql = ewr_BuildReportSql($this->SqlSelectAgg(), $this->SqlWhere(), $this->SqlGroupBy(), $this->SqlHaving(), "", $this->Filter, "");
			$sSql = $this->SqlAggPfx() . $sSql . $this->SqlAggSfx();
			$rsagg = $conn->Execute($sSql);
			if ($rsagg) {
				$this->GrandCnt[1] = $this->TotCount;
				$this->GrandCnt[2] = $this->TotCount;
				$this->GrandCnt[3] = $this->TotCount;
				$this->GrandSmry[3] = $rsagg->fields("sum_sales");
				$this->GrandCnt[4] = $this->TotCount;
				$this->GrandCnt[5] = $this->TotCount;
				$this->GrandSmry[5] = $rsagg->fields("sum_np2dshare");
				$rsagg->Close();
				$bGotSummary = TRUE;
			}

			// Accumulate grand summary from detail records
			if (!$bGotCount || !$bGotSummary) {
				$sSql = ewr_BuildReportSql($this->SqlSelect(), $this->SqlWhere(), $this->SqlGroupBy(), $this->SqlHaving(), "", $this->Filter, "");
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
			$this->GrandSummarySetup = TRUE; // No need to set up again
		}

		// Call Row_Rendering event
		$this->Row_Rendering();

		//
		// Render view codes
		//

		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row

			// np-name
			$this->np2Dname->GroupViewValue = $this->np2Dname->GroupOldValue();
			$this->np2Dname->CellAttrs["class"] = ($this->RowGroupLevel == 1) ? "ewRptGrpSummary1" : "ewRptGrpField1";
			$this->np2Dname->GroupViewValue = ewr_DisplayGroupValue($this->np2Dname, $this->np2Dname->GroupViewValue);
			$this->np2Dname->GroupSummaryOldValue = $this->np2Dname->GroupSummaryValue;
			$this->np2Dname->GroupSummaryValue = $this->np2Dname->GroupViewValue;
			$this->np2Dname->GroupSummaryViewValue = ($this->np2Dname->GroupSummaryOldValue <> $this->np2Dname->GroupSummaryValue) ? $this->np2Dname->GroupSummaryValue : "&nbsp;";

			// click date
			$this->click_date->GroupViewValue = $this->click_date->GroupOldValue();
			$this->click_date->GroupViewValue = ewr_FormatDateTime($this->click_date->GroupViewValue, 5);
			$this->click_date->CellAttrs["class"] = ($this->RowGroupLevel == 2) ? "ewRptGrpSummary2" : "ewRptGrpField2";
			$this->click_date->GroupViewValue = ewr_DisplayGroupValue($this->click_date, $this->click_date->GroupViewValue);
			$this->click_date->GroupSummaryOldValue = $this->click_date->GroupSummaryValue;
			$this->click_date->GroupSummaryValue = $this->click_date->GroupViewValue;
			$this->click_date->GroupSummaryViewValue = ($this->click_date->GroupSummaryOldValue <> $this->click_date->GroupSummaryValue) ? $this->click_date->GroupSummaryValue : "&nbsp;";

			// Sales
			$this->Sales->SumViewValue = $this->Sales->SumValue;
			$this->Sales->SumViewValue = ewr_FormatNumber($this->Sales->SumViewValue, $this->Sales->DefaultDecimalPrecision, -1, 0, 0);
			$this->Sales->CellAttrs["class"] =  ($this->RowTotalType == EWR_ROWTOTAL_PAGE || $this->RowTotalType == EWR_ROWTOTAL_GRAND) ? "ewRptGrpAggregate" : "ewRptGrpSummary" . $this->RowGroupLevel;

			// np-share
			$this->np2Dshare->SumViewValue = $this->np2Dshare->SumValue;
			$this->np2Dshare->SumViewValue = ewr_FormatNumber($this->np2Dshare->SumViewValue, $this->np2Dshare->DefaultDecimalPrecision, -1, 0, 0);
			$this->np2Dshare->CellAttrs["class"] =  ($this->RowTotalType == EWR_ROWTOTAL_PAGE || $this->RowTotalType == EWR_ROWTOTAL_GRAND) ? "ewRptGrpAggregate" : "ewRptGrpSummary" . $this->RowGroupLevel;

			// np-name
			$this->np2Dname->HrefValue = "";

			// click date
			$this->click_date->HrefValue = "";

			// tracking link
			$this->tracking_link->HrefValue = "";

			// user name
			$this->user_name->HrefValue = "";

			// Sales
			$this->Sales->HrefValue = "";

			// pct_giveback
			$this->pct_giveback->HrefValue = "";

			// np-share
			$this->np2Dshare->HrefValue = "";
		} else {

			// np-name
			$this->np2Dname->GroupViewValue = $this->np2Dname->GroupValue();
			$this->np2Dname->CellAttrs["class"] = "ewRptGrpField1";
			$this->np2Dname->GroupViewValue = ewr_DisplayGroupValue($this->np2Dname, $this->np2Dname->GroupViewValue);
			if ($this->np2Dname->GroupValue() == $this->np2Dname->GroupOldValue() && !$this->ChkLvlBreak(1))
				$this->np2Dname->GroupViewValue = "&nbsp;";

			// click date
			$this->click_date->GroupViewValue = $this->click_date->GroupValue();
			$this->click_date->GroupViewValue = ewr_FormatDateTime($this->click_date->GroupViewValue, 5);
			$this->click_date->CellAttrs["class"] = "ewRptGrpField2";
			$this->click_date->GroupViewValue = ewr_DisplayGroupValue($this->click_date, $this->click_date->GroupViewValue);
			if ($this->click_date->GroupValue() == $this->click_date->GroupOldValue() && !$this->ChkLvlBreak(2))
				$this->click_date->GroupViewValue = "&nbsp;";

			// tracking link
			$this->tracking_link->ViewValue = $this->tracking_link->CurrentValue;
			$this->tracking_link->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// user name
			$this->user_name->ViewValue = $this->user_name->CurrentValue;
			$this->user_name->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// Sales
			$this->Sales->ViewValue = $this->Sales->CurrentValue;
			$this->Sales->ViewValue = ewr_FormatNumber($this->Sales->ViewValue, $this->Sales->DefaultDecimalPrecision, -1, 0, 0);
			$this->Sales->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// pct_giveback
			$this->pct_giveback->ViewValue = $this->pct_giveback->CurrentValue;
			$this->pct_giveback->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// np-share
			$this->np2Dshare->ViewValue = $this->np2Dshare->CurrentValue;
			$this->np2Dshare->ViewValue = ewr_FormatNumber($this->np2Dshare->ViewValue, $this->np2Dshare->DefaultDecimalPrecision, -1, 0, 0);
			$this->np2Dshare->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// np-name
			$this->np2Dname->HrefValue = "";

			// click date
			$this->click_date->HrefValue = "";

			// tracking link
			$this->tracking_link->HrefValue = "";

			// user name
			$this->user_name->HrefValue = "";

			// Sales
			$this->Sales->HrefValue = "";

			// pct_giveback
			$this->pct_giveback->HrefValue = "";

			// np-share
			$this->np2Dshare->HrefValue = "";
		}

		// Call Cell_Rendered event
		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row

			// np-name
			$CurrentValue = $this->np2Dname->GroupViewValue;
			$ViewValue = &$this->np2Dname->GroupViewValue;
			$ViewAttrs = &$this->np2Dname->ViewAttrs;
			$CellAttrs = &$this->np2Dname->CellAttrs;
			$HrefValue = &$this->np2Dname->HrefValue;
			$LinkAttrs = &$this->np2Dname->LinkAttrs;
			$this->Cell_Rendered($this->np2Dname, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// click date
			$CurrentValue = $this->click_date->GroupViewValue;
			$ViewValue = &$this->click_date->GroupViewValue;
			$ViewAttrs = &$this->click_date->ViewAttrs;
			$CellAttrs = &$this->click_date->CellAttrs;
			$HrefValue = &$this->click_date->HrefValue;
			$LinkAttrs = &$this->click_date->LinkAttrs;
			$this->Cell_Rendered($this->click_date, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// Sales
			$CurrentValue = $this->Sales->SumValue;
			$ViewValue = &$this->Sales->SumViewValue;
			$ViewAttrs = &$this->Sales->ViewAttrs;
			$CellAttrs = &$this->Sales->CellAttrs;
			$HrefValue = &$this->Sales->HrefValue;
			$LinkAttrs = &$this->Sales->LinkAttrs;
			$this->Cell_Rendered($this->Sales, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// np-share
			$CurrentValue = $this->np2Dshare->SumValue;
			$ViewValue = &$this->np2Dshare->SumViewValue;
			$ViewAttrs = &$this->np2Dshare->ViewAttrs;
			$CellAttrs = &$this->np2Dshare->CellAttrs;
			$HrefValue = &$this->np2Dshare->HrefValue;
			$LinkAttrs = &$this->np2Dshare->LinkAttrs;
			$this->Cell_Rendered($this->np2Dshare, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
		} else {

			// np-name
			$CurrentValue = $this->np2Dname->GroupValue();
			$ViewValue = &$this->np2Dname->GroupViewValue;
			$ViewAttrs = &$this->np2Dname->ViewAttrs;
			$CellAttrs = &$this->np2Dname->CellAttrs;
			$HrefValue = &$this->np2Dname->HrefValue;
			$LinkAttrs = &$this->np2Dname->LinkAttrs;
			$this->Cell_Rendered($this->np2Dname, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// click date
			$CurrentValue = $this->click_date->GroupValue();
			$ViewValue = &$this->click_date->GroupViewValue;
			$ViewAttrs = &$this->click_date->ViewAttrs;
			$CellAttrs = &$this->click_date->CellAttrs;
			$HrefValue = &$this->click_date->HrefValue;
			$LinkAttrs = &$this->click_date->LinkAttrs;
			$this->Cell_Rendered($this->click_date, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// tracking link
			$CurrentValue = $this->tracking_link->CurrentValue;
			$ViewValue = &$this->tracking_link->ViewValue;
			$ViewAttrs = &$this->tracking_link->ViewAttrs;
			$CellAttrs = &$this->tracking_link->CellAttrs;
			$HrefValue = &$this->tracking_link->HrefValue;
			$LinkAttrs = &$this->tracking_link->LinkAttrs;
			$this->Cell_Rendered($this->tracking_link, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// user name
			$CurrentValue = $this->user_name->CurrentValue;
			$ViewValue = &$this->user_name->ViewValue;
			$ViewAttrs = &$this->user_name->ViewAttrs;
			$CellAttrs = &$this->user_name->CellAttrs;
			$HrefValue = &$this->user_name->HrefValue;
			$LinkAttrs = &$this->user_name->LinkAttrs;
			$this->Cell_Rendered($this->user_name, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// Sales
			$CurrentValue = $this->Sales->CurrentValue;
			$ViewValue = &$this->Sales->ViewValue;
			$ViewAttrs = &$this->Sales->ViewAttrs;
			$CellAttrs = &$this->Sales->CellAttrs;
			$HrefValue = &$this->Sales->HrefValue;
			$LinkAttrs = &$this->Sales->LinkAttrs;
			$this->Cell_Rendered($this->Sales, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// pct_giveback
			$CurrentValue = $this->pct_giveback->CurrentValue;
			$ViewValue = &$this->pct_giveback->ViewValue;
			$ViewAttrs = &$this->pct_giveback->ViewAttrs;
			$CellAttrs = &$this->pct_giveback->CellAttrs;
			$HrefValue = &$this->pct_giveback->HrefValue;
			$LinkAttrs = &$this->pct_giveback->LinkAttrs;
			$this->Cell_Rendered($this->pct_giveback, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// np-share
			$CurrentValue = $this->np2Dshare->CurrentValue;
			$ViewValue = &$this->np2Dshare->ViewValue;
			$ViewAttrs = &$this->np2Dshare->ViewAttrs;
			$CellAttrs = &$this->np2Dshare->CellAttrs;
			$HrefValue = &$this->np2Dshare->HrefValue;
			$LinkAttrs = &$this->np2Dshare->LinkAttrs;
			$this->Cell_Rendered($this->np2Dshare, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
		}

		// Call Row_Rendered event
		$this->Row_Rendered();
		$this->SetupFieldCount();
	}

	// Setup field count
	function SetupFieldCount() {
		$this->GrpFldCount = 0;
		$this->SubGrpFldCount = 0;
		$this->DtlFldCount = 0;
		if ($this->np2Dname->Visible) $this->GrpFldCount += 1;
		if ($this->click_date->Visible) { $this->GrpFldCount += 1; $this->SubGrpFldCount += 1; }
		if ($this->tracking_link->Visible) $this->DtlFldCount += 1;
		if ($this->user_name->Visible) $this->DtlFldCount += 1;
		if ($this->Sales->Visible) $this->DtlFldCount += 1;
		if ($this->pct_giveback->Visible) $this->DtlFldCount += 1;
		if ($this->np2Dshare->Visible) $this->DtlFldCount += 1;
	}

	function SetupExportOptionsExt() {
		global $ReportLanguage;
		$item =& $this->ExportOptions->GetItem("pdf");
		$item->Visible = TRUE;
		$exportid = session_id();
		$url = $this->ExportPdfUrl;
		$item->Body = "<a href=\"javascript:void(0);\" onclick=\"ewr_ExportCharts(this, '" . $url . "', '" . $exportid . "');\">" . $ReportLanguage->Phrase("ExportToPDF") . "</a>";
	}

	// Return extended filter
	function GetExtendedFilter() {
		global $gsFormError;
		$sFilter = "";
		if ($this->DrillDown)
			return "";
		$bPostBack = ewr_IsHttpPost();
		$bRestoreSession = TRUE;
		$bSetupFilter = FALSE;

		// Reset extended filter if filter changed
		if ($bPostBack) {

		// Reset search command
		} elseif (@$_GET["cmd"] == "reset") {

			// Load default values
			$this->SetSessionDropDownValue($this->user_name->DropDownValue, 'user_name'); // Field user name
			$this->SetSessionFilterValues($this->click_date->SearchValue, $this->click_date->SearchOperator, $this->click_date->SearchCondition, $this->click_date->SearchValue2, $this->click_date->SearchOperator2, 'click_date'); // Field click date
			$this->SetSessionDropDownValue($this->np2Dname->DropDownValue, 'np2Dname'); // Field np-name

			//$bSetupFilter = TRUE; // No need to set up, just use default
		} else {
			$bRestoreSession = !$this->SearchCommand;

			// Field user name
			if ($this->GetDropDownValue($this->user_name->DropDownValue, 'user_name')) {
				$bSetupFilter = TRUE;
			} elseif ($this->user_name->DropDownValue <> EWR_INIT_VALUE && !isset($_SESSION['sv_Clickthrough_details_report_user_name'])) {
				$bSetupFilter = TRUE;
			}

			// Field click date
			if ($this->GetFilterValues($this->click_date)) {
				$bSetupFilter = TRUE;
			}

			// Field np-name
			if ($this->GetDropDownValue($this->np2Dname->DropDownValue, 'np2Dname')) {
				$bSetupFilter = TRUE;
			} elseif ($this->np2Dname->DropDownValue <> EWR_INIT_VALUE && !isset($_SESSION['sv_Clickthrough_details_report_np2Dname'])) {
				$bSetupFilter = TRUE;
			}
			if (!$this->ValidateForm()) {
				$this->setFailureMessage($gsFormError);
				return $sFilter;
			}
		}

		// Restore session
		if ($bRestoreSession) {
			$this->GetSessionDropDownValue($this->user_name); // Field user name
			$this->GetSessionFilterValues($this->click_date); // Field click date
			$this->GetSessionDropDownValue($this->np2Dname); // Field np-name
		}

		// Call page filter validated event
		$this->Page_FilterValidated();

		// Build SQL
		$this->BuildDropDownFilter($this->user_name, $sFilter, "", FALSE, TRUE); // Field user name
		$this->BuildExtendedFilter($this->click_date, $sFilter, FALSE, TRUE); // Field click date
		$this->BuildDropDownFilter($this->np2Dname, $sFilter, "", FALSE, TRUE); // Field np-name

		// Save parms to session
		$this->SetSessionDropDownValue($this->user_name->DropDownValue, 'user_name'); // Field user name
		$this->SetSessionFilterValues($this->click_date->SearchValue, $this->click_date->SearchOperator, $this->click_date->SearchCondition, $this->click_date->SearchValue2, $this->click_date->SearchOperator2, 'click_date'); // Field click date
		$this->SetSessionDropDownValue($this->np2Dname->DropDownValue, 'np2Dname'); // Field np-name

		// Setup filter
		if ($bSetupFilter) {
		}

		// Field user name
		ewr_LoadDropDownList($this->user_name->DropDownList, $this->user_name->DropDownValue);

		// Field np-name
		ewr_LoadDropDownList($this->np2Dname->DropDownList, $this->np2Dname->DropDownValue);
		return $sFilter;
	}

	// Build dropdown filter
	function BuildDropDownFilter(&$fld, &$FilterClause, $FldOpr, $Default = FALSE, $SaveFilter = FALSE) {
		$FldVal = ($Default) ? $fld->DefaultDropDownValue : $fld->DropDownValue;
		$sSql = "";
		if (is_array($FldVal)) {
			foreach ($FldVal as $val) {
				$sWrk = $this->GetDropDownfilter($fld, $val, $FldOpr);

				// Call Page Filtering event
				if (substr($val, 0, 2) <> "@@") $this->Page_Filtering($fld, $sWrk, "dropdown", $FldOpr, $val);
				if ($sWrk <> "") {
					if ($sSql <> "")
						$sSql .= " OR " . $sWrk;
					else
						$sSql = $sWrk;
				}
			}
		} else {
			$sSql = $this->GetDropDownfilter($fld, $FldVal, $FldOpr);

			// Call Page Filtering event
			if (substr($FldVal, 0, 2) <> "@@") $this->Page_Filtering($fld, $sSql, "dropdown", $FldOpr, $FldVal);
		}
		if ($sSql <> "") {
			ewr_AddFilter($FilterClause, $sSql);
			if ($SaveFilter) $fld->CurrentFilter = $sSql;
		}
	}

	function GetDropDownFilter(&$fld, $FldVal, $FldOpr) {
		$FldName = $fld->FldName;
		$FldExpression = $fld->FldExpression;
		$FldDataType = $fld->FldDataType;
		$FldDelimiter = $fld->FldDelimiter;
		$FldVal = strval($FldVal);
		$sWrk = "";
		if ($FldVal == EWR_NULL_VALUE) {
			$sWrk = $FldExpression . " IS NULL";
		} elseif ($FldVal == EWR_NOT_NULL_VALUE) {
			$sWrk = $FldExpression . " IS NOT NULL";
		} elseif ($FldVal == EWR_EMPTY_VALUE) {
			$sWrk = $FldExpression . " = ''";
		} elseif ($FldVal == EWR_ALL_VALUE) {
			$sWrk = "1 = 1";
		} else {
			if (substr($FldVal, 0, 2) == "@@") {
				$sWrk = $this->GetCustomFilter($fld, $FldVal);
			} elseif ($FldDelimiter <> "" && trim($FldVal) <> "") {
				$sWrk = ewr_GetMultiSearchSql($FldExpression, trim($FldVal));
			} else {
				if ($FldVal <> "" && $FldVal <> EWR_INIT_VALUE) {
					if ($FldDataType == EWR_DATATYPE_DATE && $FldOpr <> "") {
						$sWrk = ewr_DateFilterString($FldOpr, $FldVal, $FldDataType);
					} else {
						$sWrk = ewr_FilterString("=", $FldVal, $FldDataType);
					}
				}
				if ($sWrk <> "") $sWrk = $FldExpression . $sWrk;
			}
		}
		return $sWrk;
	}

	// Get custom filter
	function GetCustomFilter(&$fld, $FldVal) {
		$sWrk = "";
		if (is_array($fld->AdvancedFilters)) {
			foreach ($fld->AdvancedFilters as $filter) {
				if ($filter->ID == $FldVal && $filter->Enabled) {
					$sFld = $fld->FldExpression;
					$sFn = $filter->FunctionName;
					$wrkid = (substr($filter->ID,0,2) == "@@") ? substr($filter->ID,2) : $filter->ID;
					if ($sFn <> "")
						$sWrk = $sFn($sFld);
					else
						$sWrk = "";
					$this->Page_Filtering($fld, $sWrk, "custom", $wrkid);
					break;
				}
			}
		}
		return $sWrk;
	}

	// Build extended filter
	function BuildExtendedFilter(&$fld, &$FilterClause, $Default = FALSE, $SaveFilter = FALSE) {
		$sWrk = ewr_GetExtendedFilter($fld, $Default);
		if (!$Default)
			$this->Page_Filtering($fld, $sWrk, "extended", $fld->SearchOperator, $fld->SearchValue, $fld->SearchCondition, $fld->SearchOperator2, $fld->SearchValue2);
		if ($sWrk <> "") {
			ewr_AddFilter($FilterClause, $sWrk);
			if ($SaveFilter) $fld->CurrentFilter = $sWrk;
		}
	}

	// Get drop down value from querystring
	function GetDropDownValue(&$sv, $parm) {
		if (ewr_IsHttpPost())
			return FALSE; // Skip post back
		if (isset($_GET["sv_$parm"])) {
			$sv = ewr_StripSlashes(@$_GET["sv_$parm"]);
			return TRUE;
		}
		return FALSE;
	}

	// Get filter values from querystring
	function GetFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		if (ewr_IsHttpPost())
			return; // Skip post back
		$got = FALSE;
		if (isset($_GET["sv_$parm"])) {
			$fld->SearchValue = ewr_StripSlashes(@$_GET["sv_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["so_$parm"])) {
			$fld->SearchOperator = ewr_StripSlashes(@$_GET["so_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["sc_$parm"])) {
			$fld->SearchCondition = ewr_StripSlashes(@$_GET["sc_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["sv2_$parm"])) {
			$fld->SearchValue2 = ewr_StripSlashes(@$_GET["sv2_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["so2_$parm"])) {
			$fld->SearchOperator2 = ewr_StripSlashes($_GET["so2_$parm"]);
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
		if (is_array($fld->DropDownValue)) {
			if (is_array($fld->DefaultDropDownValue)) {
				if (count($fld->DefaultDropDownValue) <> count($fld->DropDownValue))
					return TRUE;
				else
					return (count(array_diff($fld->DefaultDropDownValue, $fld->DropDownValue)) <> 0);
			} else {
				return TRUE;
			}
		} else {
			if (is_array($fld->DefaultDropDownValue))
				return TRUE;
			else
				$v1 = strval($fld->DefaultDropDownValue);
			if ($v1 == EWR_INIT_VALUE)
				$v1 = "";
			$v2 = strval($fld->DropDownValue);
			if ($v2 == EWR_INIT_VALUE || $v2 == EWR_ALL_VALUE)
				$v2 = "";
			return ($v1 <> $v2);
		}
	}

	// Get dropdown value from session
	function GetSessionDropDownValue(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->DropDownValue, 'sv_Clickthrough_details_report_' . $parm);
	}

	// Get filter values from session
	function GetSessionFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->SearchValue, 'sv_Clickthrough_details_report_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_Clickthrough_details_report_' . $parm);
		$this->GetSessionValue($fld->SearchCondition, 'sc_Clickthrough_details_report_' . $parm);
		$this->GetSessionValue($fld->SearchValue2, 'sv2_Clickthrough_details_report_' . $parm);
		$this->GetSessionValue($fld->SearchOperator2, 'so2_Clickthrough_details_report_' . $parm);
	}

	// Get value from session
	function GetSessionValue(&$sv, $sn) {
		if (array_key_exists($sn, $_SESSION))
			$sv = $_SESSION[$sn];
	}

	// Set dropdown value to session
	function SetSessionDropDownValue($sv, $parm) {
		$_SESSION['sv_Clickthrough_details_report_' . $parm] = $sv;
	}

	// Set filter values to session
	function SetSessionFilterValues($sv1, $so1, $sc, $sv2, $so2, $parm) {
		$_SESSION['sv_Clickthrough_details_report_' . $parm] = $sv1;
		$_SESSION['so_Clickthrough_details_report_' . $parm] = $so1;
		$_SESSION['sc_Clickthrough_details_report_' . $parm] = $sc;
		$_SESSION['sv2_Clickthrough_details_report_' . $parm] = $sv2;
		$_SESSION['so2_Clickthrough_details_report_' . $parm] = $so2;
	}

	// Check if has Session filter values
	function HasSessionFilterValues($parm) {
		return ((@$_SESSION['sv_' . $parm] <> "" && @$_SESSION['sv_' . $parm] <> EWR_INIT_VALUE) ||
			(@$_SESSION['sv_' . $parm] <> "" && @$_SESSION['sv_' . $parm] <> EWR_INIT_VALUE) ||
			(@$_SESSION['sv2_' . $parm] <> "" && @$_SESSION['sv2_' . $parm] <> EWR_INIT_VALUE));
	}

	// Dropdown filter exist
	function DropDownFilterExist(&$fld, $FldOpr) {
		$sWrk = "";
		$this->BuildDropDownFilter($fld, $sWrk, $FldOpr);
		return ($sWrk <> "");
	}

	// Extended filter exist
	function ExtendedFilterExist(&$fld) {
		$sExtWrk = "";
		$this->BuildExtendedFilter($fld, $sExtWrk);
		return ($sExtWrk <> "");
	}

	// Validate form
	function ValidateForm() {
		global $ReportLanguage, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EWR_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ewr_CheckDate($this->click_date->SearchValue)) {
			if ($gsFormError <> "") $gsFormError .= "<br>";
			$gsFormError .= $this->click_date->FldErrMsg();
		}
		if (!ewr_CheckDate($this->click_date->SearchValue2)) {
			if ($gsFormError <> "") $gsFormError .= "<br>";
			$gsFormError .= $this->click_date->FldErrMsg();
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			$gsFormError .= ($gsFormError <> "") ? "<div class=\"ewSpacer\">&nbsp;</div>" : "";
			$gsFormError .= $sFormCustomError;
		}
		return $ValidateForm;
	}

	// Clear selection stored in session
	function ClearSessionSelection($parm) {
		$_SESSION["sel_Clickthrough_details_report_$parm"] = "";
		$_SESSION["rf_Clickthrough_details_report_$parm"] = "";
		$_SESSION["rt_Clickthrough_details_report_$parm"] = "";
	}

	// Load selection from session
	function LoadSelectionFromSession($parm) {
		$fld = &$this->fields($parm);
		$fld->SelectionList = @$_SESSION["sel_Clickthrough_details_report_$parm"];
		$fld->RangeFrom = @$_SESSION["rf_Clickthrough_details_report_$parm"];
		$fld->RangeTo = @$_SESSION["rt_Clickthrough_details_report_$parm"];
	}

	// Load default value for filters
	function LoadDefaultFilters() {

		/**
		* Set up default values for non Text filters
		*/

		// Field user name
		$this->user_name->DefaultDropDownValue = EWR_INIT_VALUE;
		if (!$this->SearchCommand) $this->user_name->DropDownValue = $this->user_name->DefaultDropDownValue;

		// Field np-name
		$this->np2Dname->DefaultDropDownValue = allrecords;
		if (!$this->SearchCommand) $this->np2Dname->DropDownValue = $this->np2Dname->DefaultDropDownValue;

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

		// Field click date
		$this->SetDefaultExtFilter($this->click_date, "BETWEEN", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->click_date);

		/**
		* Set up default values for popup filters
		*/
	}

	// Check if filter applied
	function CheckFilter() {

		// Check user name extended filter
		if ($this->NonTextFilterApplied($this->user_name))
			return TRUE;

		// Check click date text filter
		if ($this->TextFilterApplied($this->click_date))
			return TRUE;

		// Check np-name extended filter
		if ($this->NonTextFilterApplied($this->np2Dname))
			return TRUE;
		return FALSE;
	}

	// Show list of filters
	function ShowFilterList() {
		global $ReportLanguage;

		// Initialize
		$sFilterList = "";

		// Field user name
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($this->user_name, $sExtWrk, "");
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->user_name->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field click date
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->click_date, $sExtWrk);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->click_date->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field np-name
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($this->np2Dname, $sExtWrk, "");
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->np2Dname->FldCaption() . "</span>" . $sFilter . "</div>";

		// Show Filters
		if ($sFilterList <> "") {
			$sMessage = "<div>" . $ReportLanguage->Phrase("CurrentFilters") . "</div>" . $sFilterList;
			$this->Message_Showing($sMessage, "");
			echo $sMessage;
		}
	}

	// Return popup filter
	function GetPopupFilter() {
		$sWrk = "";
		if ($this->DrillDown)
			return "";
		return $sWrk;
	}

	//-------------------------------------------------------------------------------
	// Function GetSort
	// - Return Sort parameters based on Sort Links clicked
	// - Variables setup: Session[EWR_TABLE_SESSION_ORDER_BY], Session["sort_Table_Field"]
	function GetSort() {
		if ($this->DrillDown)
			return "";

		// Check for a resetsort command
		if (strlen(@$_GET["cmd"]) > 0) {
			$sCmd = @$_GET["cmd"];
			if ($sCmd == "resetsort") {
				$this->setOrderBy("");
				$this->setStartGroup(1);
				$this->np2Dname->setSort("");
				$this->click_date->setSort("");
				$this->tracking_link->setSort("");
				$this->user_name->setSort("");
				$this->Sales->setSort("");
				$this->pct_giveback->setSort("");
				$this->np2Dshare->setSort("");
			}

		// Check for an Order parameter
		} elseif (@$_GET["order"] <> "") {
			$this->CurrentOrder = ewr_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$sSortSql = $this->SortSql();
			$this->setOrderBy($sSortSql);
			$this->setStartGroup(1);
		}
		return $this->getOrderBy();
	}

	// Export email
	function ExportEmail($EmailContent, $Params) {
		global $gTmpImages, $ReportLanguage;
		$sSender = @$Params["sender"];
		$sRecipient = @$Params["recipient"];
		$sCc = "";//@$Params["cc"];
		$sBcc = @$Params["bcc"];//@$Params["bcc"];
		$sContentType = "html";//@$Params["contenttype"];

		// Subject
		$sSubject = ewr_StripSlashes(@$Params["subject"]);
		$sEmailSubject = $sSubject;

		// Message
		$sContent = ewr_StripSlashes(@$Params["message"]);
		$sEmailMessage = $sContent;

		// Check sender
		if ($sSender == "") {
			$this->setFailureMessage($ReportLanguage->Phrase("EnterSenderEmail"));
			return;
		}
		if (!ewr_CheckEmail($sSender)) {
			$this->setFailureMessage($ReportLanguage->Phrase("EnterProperSenderEmail"));
			return;
		}

		// Check recipient
		if (!ewr_CheckEmailList($sRecipient, EWR_MAX_EMAIL_RECIPIENT)) {
			$this->setFailureMessage($ReportLanguage->Phrase("EnterProperRecipientEmail"));
			return;
		}

		// Check cc
		if (!ewr_CheckEmailList($sCc, EWR_MAX_EMAIL_RECIPIENT)) {
			$this->setFailureMessage($ReportLanguage->Phrase("EnterProperCcEmail"));
			return;
		}

		// Check bcc
		if (!ewr_CheckEmailList($sBcc, EWR_MAX_EMAIL_RECIPIENT)) {
			$this->setFailureMessage($ReportLanguage->Phrase("EnterProperBccEmail"));
			return;
		}

		// Check email sent count
		$emailcount = ewr_LoadEmailCount();
		if (intval($emailcount) >= EWR_MAX_EMAIL_SENT_COUNT) {
			$this->setFailureMessage($ReportLanguage->Phrase("ExceedMaxEmailExport"));
			return;
		}
		if ($sEmailMessage <> "") {
			if (EWR_REMOVE_XSS) $sEmailMessage = ewr_RemoveXSS($sEmailMessage);
			$sEmailMessage .= ($sContentType == "url") ? "\r\n\r\n" : "<br><br>";
		}
		$sAttachmentContent = $EmailContent;
		$sAppPath = ewr_FullUrl();
		$sAppPath = substr($sAppPath, 0, strrpos($sAppPath, "/")+1);
		if (strpos($sAttachmentContent, "<head>") !== FALSE)
			$sAttachmentContent = str_replace("<head>", "<head><base href=\"" . $sAppPath . "\">", $sAttachmentContent); // Add <base href> statement inside the header
		else
			$sAttachmentContent = "<base href=\"" . $sAppPath . "\">" . $sAttachmentContent; // Add <base href> statement as the first statement

		//$sAttachmentFile = $this->TableVar . "_" . Date("YmdHis") . ".html";
		$sAttachmentFile = $this->TableVar . "_" . Date("YmdHis") . "_" . ewr_Random() . ".html";
		if ($sContentType == "url") {
			ewr_SaveFile(EWR_UPLOAD_DEST_PATH, $sAttachmentFile, $sAttachmentContent);
			$sAttachmentFile = EWR_UPLOAD_DEST_PATH . $sAttachmentFile;
			$sUrl = $sAppPath . $sAttachmentFile;
			$sEmailMessage .= $sUrl; // send URL only
			$sAttachmentFile = "";
			$sAttachmentContent = "";
		} else {
                $sEmailMessage .= $sAttachmentContent;
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
		if ($sAttachmentFile <> "" || $sAttachmentContent <> "") {
			$Email->AttachmentContent = $sAttachmentContent; // Attachment
			$Email->AttachmentFileName = $sAttachmentFile; // Attachment file name
		} elseif ($sContentType <> "url") { // Inline attachment
			foreach ($gTmpImages as $tmpimage)
				$Email->AddEmbeddedImage($tmpimage);
		}
        // Attach PDF to mail
        $Email->pdfAttachmentFileName = $this->ExportPDFToFile($EmailContent);
//        $Email->pdfAttachmentContent = $this->ExportPDF($EmailContent);//$EmailContent

		$Email->Format = ($sContentType == "url") ? "text" : "html";
		$Email->Charset = EWR_EMAIL_CHARSET;
		$EventArgs = array();
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $EventArgs))
			$bEmailSent = $Email->Send();

		// Check email sent status
		if ($bEmailSent) {

			// Update email sent count and write log
			ewr_AddEmailLog($sSender, $sRecipient, $sEmailSubject, $sEmailMessage);

			// Sent email success
			$this->setSuccessMessage($ReportLanguage->Phrase("SendEmailSuccess"));
		} else {

			// Sent email failure
			$this->setFailureMessage($Email->SendErrDescription);
		}
		ewr_DeleteTmpImages();
	}

	// Export to HTML
	function ExportHtml($html) {

		//global $gsExportFile;
		//header('Content-Type: text/html' . (EWR_CHARSET <> '' ? ';charset=' . EWR_CHARSET : ''));
		//header('Content-Disposition: attachment; filename=' . $gsExportFile . '.html');
		//echo $html;

	}

	// Export to WORD
	function ExportWord($html) {
		global $gsExportFile;
		header('Content-Type: application/vnd.ms-word' . (EWR_CHARSET <> '' ? ';charset=' . EWR_CHARSET : ''));
		header('Content-Disposition: attachment; filename=' . $gsExportFile . '.doc');
		echo $html;
	}

	// Export to EXCEL
	function ExportExcel($html) {
		global $gsExportFile;
		header('Content-Type: application/vnd.ms-excel' . (EWR_CHARSET <> '' ? ';charset=' . EWR_CHARSET : ''));
		header('Content-Disposition: attachment; filename=' . $gsExportFile . '.xls');
		echo $html;
	}

	// Export PDF
	function ExportPDF($html) {
		global $gsExportFile;
		include_once "dompdf060b3/dompdf_config.inc.php";
		@ini_set("memory_limit", EWR_PDF_MEMORY_LIMIT);
		set_time_limit(EWR_PDF_TIME_LIMIT);
		$dompdf = new DOMPDF();
		$dompdf->load_html($html);
		ob_end_clean();
		$dompdf->set_paper("a4", "portrait");
		$dompdf->render();
		ewr_DeleteTmpImages();
        return $dompdf->output();
//        file_put_contents("files/".$gsExportFile.".pdf",$dompdf->output(array("compress"=>0)));

//        return "files/".$gsExportFile.".pdf";
//		$dompdf->stream($gsExportFile . ".pdf", array("Attachment" => 1)); // 0 to open in browser, 1 to download

//		exit();
	}

	// Export PDF To File
	function ExportPDFToFile($html) {
		global $gsExportFile;
		include_once "dompdf060b3/dompdf_config.inc.php";
		@ini_set("memory_limit", EWR_PDF_MEMORY_LIMIT);
		set_time_limit(EWR_PDF_TIME_LIMIT);
		$dompdf = new DOMPDF();
		$dompdf->load_html($html);
		ob_end_clean();
		$dompdf->set_paper("a4", "portrait");
		$dompdf->render();
		ewr_DeleteTmpImages();
        file_put_contents("files/".$gsExportFile.".pdf",$dompdf->output(array("compress"=>0)));

        return "files/".$gsExportFile.".pdf";

	}

	// Render chart content
	function RenderChart(&$Chart) {
		$Chart->LoadChartParms();

		//echo "id: " . $Chart->ID . "<br>";
		//echo "type: " . $Chart->ChartType . "<br>";
		// Get chart xml

		$chartxml = $Chart->ChartXml();
		return $chartxml;
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
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

		// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

		ob_start();

	//if ($this->fields["np-name"]->DropDownValue != EWR_ALL_VALUE) echo $this->fields["np-name"]->DropDownValue;
	echo "<strong>Organization:</strong> ";

	//find field name in Clickthrough_details_reportsmry.php
	echo $this->fields["np2Dname"]->DropDownValue;
	echo "<br><strong>Date range of report:</strong> ";
	echo $this->click_date->SearchValue;
	echo " through ";
	echo $this->click_date->SearchValue2;
	$myStr = ob_get_contents();
	ob_end_clean();
		  $header = $myStr;
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