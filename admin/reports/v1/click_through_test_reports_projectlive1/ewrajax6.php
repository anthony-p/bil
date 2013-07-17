<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "phprptinc/ewrcfg6.php" ?>
<?php include_once "phprptinc/ewmysql.php" ?>
<?php include_once "phprptinc/ewrfn6.php" ?>
<?php include_once "phprptinc/ewrusrfn.php" ?>
<?php
ewr_Header(FALSE, 'utf-8');
$lookup = new clookup;
$lookup->Page_Main();

//
// Page class for lookup
//
class clookup {

	// Page ID
	var $PageID = "lookup";

	// Project ID
	var $ProjectID = "{1970966F-5C22-4F67-9D47-2A354E499C8D}";

	// Page object name
	var $PageObjName = "lookup";

	// Page name
	function PageName() {
		return ewr_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		return ewr_CurrentPage() . "?";
	}

	// Main
	function Page_Main() {
		global $ReportLanguage;
		$post = ewr_StripSlashes($_POST);
		if (count($post) == 0)
			die("Missing post data.");

		//$sql = $qs->getValue("s");
		$sql = @$post["s"];
		$sql = TEAdecrypt($sql);
		if ($sql == "")
			die("Missing SQL.");

		// Field delimiter
		$dlm = @$post["dlm"];
		$dlm = TEAdecrypt($dlm);

		// Language object
		$ReportLanguage = new crLanguage();
		if (strpos($sql, "{filter}") > 0) {
			$filters = "";
			for ($i = 0; $i < 5; $i++) {

				// Get the filter values (for "IN")
				$filter = TEAdecrypt(@$post["f" . $i]);
				if ($filter <> "") {
					$value = @$post["v" . $i];
					if ($value == "") {
						if ($i > 0) // Empty parent field

							//continue; // Allow
							ewr_AddFilter($filters, "1=0"); // Disallow
						continue;
					}
					$arValue = explode(",", $value);
					$fldtype = intval(@$post["t" . $i]);
					$wrkfilter = "";
					for ($j = 0, $cnt = count($arValue); $j < $cnt; $j++) {
						if ($wrkfilter <> "") $wrkfilter .= " OR ";
						$val = $arValue[$j];
						if ($val == EWR_NULL_VALUE)
							$wrkfilter .= str_replace(" = {filter_value}", " IS NULL", $filter);
						elseif ($val == EWR_NOT_NULL_VALUE)
							$wrkfilter .= str_replace(" = {filter_value}", " IS NOT NULL", $filter);
						elseif ($val == EWR_EMPTY_VALUE)
							$wrkfilter .= str_replace(" = {filter_value}", " = ''", $filter);
						else
							$wrkfilter .= str_replace("{filter_value}", ewr_QuotedValue($val, ewr_FieldDataType($fldtype)), $filter);
					}
					ewr_AddFilter($filters, $wrkfilter);
				}
			}
			$sql = str_replace("{filter}", ($filters <> "") ? $filters : "1=1", $sql);
		}

		// Get the query value (for "LIKE" or "=")
		$value = ewr_AdjustSql(@$post["q"]);
		if ($value <> "") {
			$sql = preg_replace('/LIKE \'(%)?\{query_value\}%\'/', ewr_Like('\'$1{query_value}%\''), $sql);
			$sql = str_replace("{query_value}", $value, $sql);
		}

		// Check custom function
		$fn = @$post["fn"];
		if ($fn <> "" && function_exists($fn)) // Custom function(&$sql)
			$sql = $fn($sql);
		$ds = @$post["ds"]; // Date search type
		$df = @$post["df"]; // Date format
		$this->GetLookupValues($sql, $ds, $df, $dlm);
	}

	// Get lookup values
	function GetLookupValues($sql, $ds, $df, $dlm) {
		global $ReportLanguage;
		$rsarr = array();
		$rowcnt = 0;
		$conn = ewr_Connect();
		if ($rs = $conn->Execute($sql)) {
			$rowcnt = $rs->RecordCount();
			$fldcnt = $rs->FieldCount();
			$rsarr = $rs->GetRows();
			$rs->Close();
		}
		$conn->Close();

		// Clean output buffer
		if (!EWR_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Output
		$key = array();
		if (is_array($rsarr) && $rowcnt > 0) {
			for ($i = 0; $i < $rowcnt; $i++) {
				$row = $rsarr[$i];
				if ($dlm <> "") {
					$cnt = 0;
					for ($j = 0; $j < $fldcnt; $j++) {
						if (strpos(strval($row[$j]), $dlm) !== FALSE) {
							$row[$j] = explode($dlm, $row[$j]);
							if (count($row[$j]) > $cnt) $cnt = count($row[$j]);
						} else {
							if ($cnt < 1) $cnt = 1;
						}
					}
				} else {
					$cnt = 1;
				}
				for ($k = 0; $k < $cnt; $k++) {
					$val0 = "";
					$str0 = "";
					for ($j = 0; $j < $fldcnt; $j++) {
						if ($dlm <> "" && is_array($row[$j])) {
							if (count($row[$j]) > $k)
								$val = $row[$j][$k];
							else
								$val = $row[$j][0];
						} else {
							$val = $row[$j];
						}
						if ($j == 0) {
							$str = ewr_ConvertValue($ds, $val);
							$val0 = $val;
							$str0 = $str;
						} elseif ($j == 1 && is_null($val0)) {
							$str = $ReportLanguage->Phrase("NullLabel");
						} elseif ($j == 1 && strval($val0) == "") {
							$str = $ReportLanguage->Phrase("EmptyLabel");
						} elseif ($j == 1) {
							$str = ewr_DropDownDisplayValue(ewr_ConvertValue($ds, $val), $ds, $df);
						} else {
							$str = strval($val);
						}
						$str = ewr_ConvertToUtf8($str);
						if (!in_array($str0, $key))
							echo $this->RemoveDelimiters($str) . EWR_FIELD_DELIMITER;
					}
					if (!in_array($str0, $key)) {
						echo ewr_ConvertToUtf8(EWR_RECORD_DELIMITER);
						$key[] = $str0;
					}
				}
			}
		}
	}

	// Process values
	function RemoveDelimiters($s) {
		$wrkstr = $s;
		if (strlen($wrkstr) > 0) {
			$wrkstr = str_replace("\r", " ", $wrkstr);
			$wrkstr = str_replace("\n", " ", $wrkstr);
			$wrkstr = str_replace(EWR_RECORD_DELIMITER, "", $wrkstr);
			$wrkstr = str_replace(EWR_FIELD_DELIMITER, " ", $wrkstr);
		}
		return $wrkstr;
	}
}
?>
