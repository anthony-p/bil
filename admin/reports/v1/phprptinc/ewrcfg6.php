<?php

// PHP Report Maker 6 - configuration
// Debug

define("EWR_DEBUG_ENABLED", FALSE, TRUE); // True to debug
if (EWR_DEBUG_ENABLED) {
	@ini_set("display_errors", "1"); // Display errors
	error_reporting(E_ALL ^ E_NOTICE); // Report all errors except E_NOTICE
}
define("EWR_PROJECT_ID", "{1970966F-5C22-4F67-9D47-2A354E499C8D}", TRUE); // Project ID
define("EWR_PROJECT_NAME", "click_through_test_reports_projectlive1", TRUE); // Project Name
define("EWR_PROJECT_VAR", "click_through_test_reports_projectlive1", TRUE); // Project Var
define("EWR_CONFIG_FILE_FOLDER", EWR_PROJECT_VAR . "", TRUE); // Config file folder
define("EWR_IS_WINDOWS", (strtolower(substr(PHP_OS, 0, 3)) === 'win'), TRUE); // Is Windows OS
define("EWR_IS_PHP5", (phpversion() >= "5.0.0"), TRUE); // Is PHP 5 or later
if (!EWR_IS_PHP5) die("This script requires PHP 5. You are running " . phpversion() . ".");
define("EWR_PATH_DELIMITER", ((EWR_IS_WINDOWS) ? "\\" : "/"), TRUE); // Path delimiter

// Language settings
define("EWR_LANGUAGE_FOLDER", "phprptlang/", TRUE);
$EWR_LANGUAGE_FILE = array();
$EWR_LANGUAGE_FILE[] = array("en", "", "english.xml");
define("EWR_LANGUAGE_DEFAULT_ID", "en", TRUE);
define("EWR_SESSION_LANGUAGE_ID", EWR_PROJECT_VAR . "_LanguageId", TRUE); // Language ID
define("EWR_RECORD_DELIMITER", "\r", TRUE); // Record delimiter for Ajax
define("EWR_FIELD_DELIMITER", "\t", TRUE); // Field delimiter for Ajax
if (!function_exists('xml_parser_create') && !class_exists("DOMDocument")) die("This script requires PHP XML Parser or DOM.");
define('EWR_USE_DOM_XML', ((!function_exists('xml_parser_create') && class_exists("DOMDocument")) || FALSE), TRUE);

// Database connection
define("EWR_CONN_HOST", 'www.bringitlocal.com', TRUE);
define("EWR_CONN_PORT", 3306, TRUE);
define("EWR_CONN_USER", 'bringit_userbids', TRUE);
define("EWR_CONN_PASS", '^Xqh#^sqT%xC', TRUE);
define("EWR_CONN_DB", 'bringit_auction', TRUE);

// ADODB (Access/SQL Server)
define("EWR_CODEPAGE", 65001, TRUE); // Code page
define("EWR_CHARSET", "utf-8", TRUE); // Project charset
define("EWR_DBMSNAME", 'MySQL', TRUE); // DBMS Name
define("EWR_IS_MSACCESS", FALSE, TRUE); // Access
define("EWR_IS_MSSQL", FALSE, TRUE); // SQL Server
define("EWR_IS_MYSQL", TRUE, TRUE); // MySQL
define("EWR_IS_POSTGRESQL", FALSE, TRUE); // PostgreSQL
define("EWR_IS_ORACLE", FALSE, TRUE); // Oracle
define("EWR_DB_QUOTE_START", "`", TRUE);
define("EWR_DB_QUOTE_END", "`", TRUE);

// Remove XSS
define("EWR_REMOVE_XSS", TRUE, TRUE);
$EWR_XSS_ARRAY = array('javascript', 'vbscript', 'expression', '<applet', '<meta', '<xml', '<blink', '<link', '<style', '<script', '<embed', '<object', '<iframe', '<frame', '<frameset', '<ilayer', '<layer', '<bgsound', '<title', '<base',
'onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');

// General
define("EWR_ROOT_RELATIVE_PATH", ".", TRUE); // Relative path of app root
define("EWR_DEFAULT_DATE_FORMAT", "yyyy/mm/dd", TRUE); // Default date format
define("EWR_DATE_SEPARATOR", "/", TRUE); // Date separator
define("EWR_UNFORMAT_YEAR", 50, TRUE); // Unformat year
define("EWR_RANDOM_KEY", 'SsM09VyGTQP4R73o', TRUE); // Random key for encryption
define("EWR_PROJECT_STYLESHEET_FILENAME", "phprptcss/click_through_test_reports_projectlive1.css", TRUE); // Project stylesheet file name
define("EWR_CHART_WIDTH", 550, TRUE);
define("EWR_CHART_HEIGHT", 440, TRUE);
define("EWR_CHART_ALIGN", "middle", TRUE);
define("EWR_CHART_SHOW_BLANK_SERIES", FALSE, TRUE); // Show blank series

// Drill down setting
define("EWR_USE_DRILLDOWN_PANEL", TRUE, TRUE); // Use popup panel for drill down

/**
 * Character encoding
 * Note: If you use non English languages, you need to set character encoding
 * for some features. Make sure either iconv functions or multibyte string
 * functions are enabled and your encoding is supported. See PHP manual for
 * details.
 */
define("EWR_ENCODING", "UTF-8", TRUE); // Character encoding
define("EWR_IS_DOUBLE_BYTE", in_array(EWR_ENCODING, array("GBK", "BIG5", "SHIFT_JIS")), TRUE); // Double-byte character encoding

/**
 * MySQL charset (for SET NAMES statement, not used by default)
 * Note: Read http://dev.mysql.com/doc/refman/5.0/en/charset-connection.html
 * before using this setting.
 */
define("EWR_MYSQL_CHARSET", "utf8", TRUE);

/**
 * Password (MD5 and case-sensitivity)
 * Note: If you enable MD5 password, make sure that the passwords in your
 * user table are stored as MD5 hash (32-character hexadecimal number) of the
 * clear text password. If you also use case-insensitive password, convert the
 * clear text passwords to lower case first before calculating MD5 hash.
 * Otherwise, existing users will not be able to login.
 */
define("EWR_ENCRYPTED_PASSWORD", FALSE, TRUE); // Use encrypted password
define("EWR_CASE_SENSITIVE_PASSWORD", FALSE, TRUE); // Case-sensitive password

/**
 * Numeric and monetary formatting options
 * Note: DO NOT CHANGE THE FOLLOWING $DEFAULT_* VARIABLES!
 * If you want to use custom settings, customize the language file,
 * set "use_system_locale" to "0" to override localeconv and customize the
 * phrases under the <locale> node for ew_FormatCurrency/Number/Percent functions
 * Also read http://www.php.net/localeconv for description of the constants
*/
$EWR_DEFAULT_DECIMAL_POINT = ".";
$EWR_DEFAULT_THOUSANDS_SEP = ",";
$EWR_DEFAULT_CURRENCY_SYMBOL = "$";
$EWR_DEFAULT_MON_DECIMAL_POINT = ".";
$EWR_DEFAULT_MON_THOUSANDS_SEP = ",";
$EWR_DEFAULT_POSITIVE_SIGN = "";
$EWR_DEFAULT_NEGATIVE_SIGN = "-";
$EWR_DEFAULT_FRAC_DIGITS = 2;
$EWR_DEFAULT_P_CS_PRECEDES = TRUE;
$EWR_DEFAULT_P_SEP_BY_SPACE = FALSE;
$EWR_DEFAULT_N_CS_PRECEDES = TRUE;
$EWR_DEFAULT_N_SEP_BY_SPACE = FALSE;
$EWR_DEFAULT_P_SIGN_POSN = 3;
$EWR_DEFAULT_N_SIGN_POSN = 3;
$EWR_DEFAULT_LOCALE = array("decimal_point" => &$EWR_DEFAULT_DECIMAL_POINT,
	"thousands_sep" => &$EWR_DEFAULT_THOUSANDS_SEP,
	"currency_symbol" => &$EWR_DEFAULT_CURRENCY_SYMBOL,
	"mon_decimal_point" => &$EWR_DEFAULT_MON_DECIMAL_POINT,
	"mon_thousands_sep" => &$EWR_DEFAULT_MON_THOUSANDS_SEP,
	"positive_sign" => &$EWR_DEFAULT_POSITIVE_SIGN,
	"negative_sign" => &$EWR_DEFAULT_NEGATIVE_SIGN,
	"frac_digits" => &$EWR_DEFAULT_FRAC_DIGITS,
	"p_cs_precedes" => &$EWR_DEFAULT_P_CS_PRECEDES,
	"p_sep_by_space" => &$EWR_DEFAULT_P_SEP_BY_SPACE,
	"n_cs_precedes" => &$EWR_DEFAULT_N_CS_PRECEDES,
	"n_sep_by_space" => &$EWR_DEFAULT_N_SEP_BY_SPACE,
	"p_sign_posn" => &$EWR_DEFAULT_P_SIGN_POSN,
	"n_sign_posn" => &$EWR_DEFAULT_N_SIGN_POSN);
$EWR_DEFAULT_TIME_ZONE = "GMT";

// Filter
define("EWR_SHOW_CURRENT_FILTER", FALSE, TRUE); // True to show current filter
define("EWR_SHOW_DRILLDOWN_FILTER", TRUE, TRUE); // True to show drill down filter

// Session names
define("EWR_SESSION_STATUS", EWR_PROJECT_VAR . "_status", TRUE); // Login Status
define("EWR_SESSION_USER_NAME", EWR_SESSION_STATUS . "_UserName", TRUE); // User Name
define("EWR_SESSION_USER_ID", EWR_SESSION_STATUS . "_UserID", TRUE); // User ID
define("EWR_SESSION_USER_LEVEL_ID", EWR_SESSION_STATUS . "_UserLevel", TRUE); // User Level ID
define("EWR_SESSION_USER_LEVEL", EWR_SESSION_STATUS . "_UserLevelValue", TRUE); // User Level
define("EWR_SESSION_PARENT_USER_ID", EWR_SESSION_STATUS . "_ParentUserID", TRUE); // Parent User ID
define("EWR_SESSION_SYSTEM_ADMIN", EWR_PROJECT_VAR . "_SysAdmin", TRUE); // System Admin
define("EWR_SESSION_AR_USER_LEVEL", EWR_PROJECT_VAR . "_arUserLevel", TRUE); // User Level Array
define("EWR_SESSION_AR_USER_LEVEL_PRIV", EWR_PROJECT_VAR . "_arUserLevelPriv", TRUE); // User Level Privilege Array
define("EWR_SESSION_MESSAGE", EWR_PROJECT_VAR . "_Message", TRUE); // System Message
define("EWR_SESSION_FAILURE_MESSAGE", EWR_PROJECT_VAR . "_Failure_Message", TRUE); // System error message
define("EWR_SESSION_SUCCESS_MESSAGE", EWR_PROJECT_VAR . "_Success_Message", TRUE); // System success message
define("EWR_SESSION_WARNING_MESSAGE", EWR_PROJECT_VAR . "_Warning_Message", TRUE); // Warning message

// Hard-coded admin
define("EWR_ADMIN_USER_NAME", "", TRUE);
define("EWR_ADMIN_PASSWORD", "", TRUE);
define("EWR_USE_CUSTOM_LOGIN", TRUE, TRUE); // Use custom login

// User admin
define("EWR_LOGIN_SELECT_SQL", "", TRUE);

// User table filters
// User level constants

define("EWR_ALLOW_LIST", 8, TRUE); // List
define("EWR_ALLOW_REPORT", 8, TRUE); // Report
define("EWR_ALLOW_ADMIN", 16, TRUE); // Admin

// User id constants
define("EWR_USER_ID_IS_HIERARCHICAL", TRUE, TRUE); // Hierarchical user id

// Table level constants
define("EWR_TABLE_PREFIX", "||PHPReportMaker||", TRUE);
define("EWR_TABLE_PREFIX_OLD", "||PHPReportMaker||", TRUE);
define("EWR_TABLE_GROUP_PER_PAGE", "grpperpage", TRUE);
define("EWR_TABLE_START_GROUP", "start", TRUE);
define("EWR_TABLE_ORDER_BY", "order", TRUE);
define("EWR_TABLE_ORDER_BY_TYPE", "ordertype", TRUE);
define("EWR_TABLE_SORT", "sort", TRUE); // Table sort
define("EWR_TABLE_SORTCHART", "sortc", TRUE); // Table sort chart

// Data types
define("EWR_DATATYPE_NONE", 0, TRUE);
define("EWR_DATATYPE_NUMBER", 1, TRUE);
define("EWR_DATATYPE_DATE", 2, TRUE);
define("EWR_DATATYPE_STRING", 3, TRUE);
define("EWR_DATATYPE_BOOLEAN", 4, TRUE);
define("EWR_DATATYPE_MEMO", 5, TRUE);
define("EWR_DATATYPE_BLOB", 6, TRUE);
define("EWR_DATATYPE_TIME", 7, TRUE);
define("EWR_DATATYPE_GUID", 8, TRUE);
define("EWR_DATATYPE_OTHER", 9, TRUE);

// Row types
define("EWR_ROWTYPE_DETAIL", 1, TRUE); // Row type detail
define("EWR_ROWTYPE_TOTAL", 2, TRUE); // Row type group summary

// Row total types
define("EWR_ROWTOTAL_GROUP", 1, TRUE); // Page summary
define("EWR_ROWTOTAL_PAGE", 2, TRUE); // Page summary
define("EWR_ROWTOTAL_GRAND", 3, TRUE); // Grand summary

// Row total sub types
define("EWR_ROWTOTAL_FOOTER", 1, TRUE); // Footer
define("EWR_ROWTOTAL_SUM", 2, TRUE); // SUM
define("EWR_ROWTOTAL_AVG", 3, TRUE); // AVG
define("EWR_ROWTOTAL_MIN", 4, TRUE); // MIN
define("EWR_ROWTOTAL_MAX", 5, TRUE); // MAX
define("EWR_ROWTOTAL_CNT", 6, TRUE); // CNT

// Empty/Null/Not Null/Init/all values
define("EWR_EMPTY_VALUE", "##empty##", TRUE);
define("EWR_NULL_VALUE", "##null##", TRUE);
define("EWR_NOT_NULL_VALUE", "##notnull##", TRUE);
define("EWR_INIT_VALUE", "##init##", TRUE);
define("EWR_ALL_VALUE", "##all##", TRUE);

// Boolean values for ENUM('Y'/'N') or ENUM(1/0)
define("EWR_TRUE_STRING", "'Y'", TRUE);
define("EWR_FALSE_STRING", "'N'", TRUE);

// SQL formats
define("EWR_YEAR_SQL", "YEAR(%s)", TRUE);
define("EWR_QUARTER_SQL", "QUARTER(%s)", TRUE);
define("EWR_MONTH_SQL", "MONTH(%s)", TRUE);

// Use token in URL (reserved, not used, do NOT change!)
define("EWR_USE_TOKEN_IN_URL", FALSE, TRUE);

// Email
define("EWR_EMAIL_COMPONENT", "PHPMAILER", TRUE); // Always use PHPMAILER

define("EWR_SMTP_SERVER", "localhost", TRUE); // SMTP server
define("EWR_SMTP_SERVER_PORT", 25, TRUE); // SMTP server port
define("EWR_SMTP_SECURE_OPTION", "", TRUE);
define("EWR_SMTP_SERVER_USERNAME", "", TRUE); // SMTP server user name
define("EWR_SMTP_SERVER_PASSWORD", "", TRUE); // SMTP server password
define("EWR_MAX_EMAIL_RECIPIENT", 100, TRUE);
define("EWR_MAX_EMAIL_SENT_COUNT", 100, TRUE);
define("EWR_MAX_EMAIL_SENT_PERIOD", 20, TRUE);
define("EWR_EXPORT_EMAIL_COUNTER", EWR_SESSION_STATUS . "_EmailCounter", TRUE);
define("EWR_EMAIL_CHARSET", EWR_CHARSET, TRUE); // Email charset
define("EWR_EMAIL_WRITE_LOG", TRUE, TRUE); // Write to log file
define("EWR_EMAIL_LOG_SIZE_LIMIT", 20, TRUE); // Email log field size limit
define("EWR_EMAIL_WRITE_LOG_TO_DATABASE", FALSE, TRUE); // Write email log to database
define("EWR_EMAIL_LOG_TABLE_NAME", "", TRUE); // Email log table name
define("EWR_EMAIL_LOG_FIELD_NAME_DATETIME", "", TRUE); // Email log DateTime field name
define("EWR_EMAIL_LOG_FIELD_NAME_IP", "", TRUE); // Email log IP field name
define("EWR_EMAIL_LOG_FIELD_NAME_SENDER", "", TRUE); // Email log Sender field name
define("EWR_EMAIL_LOG_FIELD_NAME_RECIPIENT", "", TRUE); // Email log Recipient field name
define("EWR_EMAIL_LOG_FIELD_NAME_SUBJECT", "", TRUE); // Email log Subject field name
define("EWR_EMAIL_LOG_FIELD_NAME_MESSAGE", "", TRUE); // Email log Message field name

// Export records
$EWR_EXPORT = array(
	"email" => "ExportEmail",
	"print" => "ExportHtml",
	"html" => "ExportHtml",
	"word" => "ExportWord",
	"excel" => "ExportExcel",
	"pdf" => "ExportPdf"
);

// Image resize
define("EWR_UPLOADED_FILE_MODE", 0666, TRUE); // Uploaded file mode
define("EWR_UPLOAD_TMP_PATH", "", TRUE); // User upload temp path (relative to app root) e.g. "tmp/"
define("EWR_UPLOAD_DEST_PATH", "files/", TRUE); // Upload destination path (relative to app root)
define("EWR_THUMBNAIL_DEFAULT_WIDTH", 0, TRUE); // Thumbnail default width
define("EWR_THUMBNAIL_DEFAULT_HEIGHT", 0, TRUE); // Thumbnail default height
define("EWR_THUMBNAIL_DEFAULT_QUALITY", 75, TRUE); // Thumbnail default qualtity (JPEG)

// Use ILIKE for PostgreSql
define("EWR_USE_ILIKE_FOR_POSTGRESQL", TRUE, TRUE);

// Use collation for MySQL
define("EWR_LIKE_COLLATION_FOR_MYSQL", "", TRUE);

// Use collation for MsSQL
define("EWR_LIKE_COLLATION_FOR_MSSQL", "", TRUE);

// Comma separated values delimiter
$EWR_CSV_DELIMITER = ",";

// Use mobile menu
$EWR_USE_MOBILE_MENU = FALSE;

// Float fields default decimal position
define("EWR_DEFAULT_DECIMAL_PRECISION", 2, TRUE);

// Validate option
define("EWR_CLIENT_VALIDATE", FALSE, TRUE);
define("EWR_SERVER_VALIDATE", FALSE, TRUE);

// Auto suggest max entries
define("EWR_AUTO_SUGGEST_MAX_ENTRIES", 10, TRUE);

// Checkbox and radio button groups
define("EWR_ITEM_TEMPLATE_CLASSNAME", "ewTemplate", TRUE);
define("EWR_ITEM_TABLE_CLASSNAME", "ewItemTable", TRUE);

// Cookies
define("EWR_COOKIE_EXPIRY_TIME", time() + 365*24*60*60, TRUE); // Change cookie expiry time here

/**
 * Time zone (Note: Requires PHP 5 >= 5.1.0)
 * Read http://www.php.net/date_default_timezone_set for details
 * and http://www.php.net/timezones for supported time zones
*/
if (function_exists("date_default_timezone_set"))
	date_default_timezone_set($EWR_DEFAULT_TIME_ZONE);
if (!isset($conn)) {

	// Common objects
	$conn = NULL; // Connection
	$rs = NULL; // Recordset
	$rsgrp = NULL; // Recordset
	$Page = NULL; // Page
	$OldPage = NULL; // Old Page
	$Table = NULL; // Main table

	// Current language
	$gsLanguage = "";
}
if (!isset($ReportLanguage)) {
	$ReportLanguage = NULL; // Language
}

// Timer
$gsTimer = NULL;

// Used by header.php, export checking
$gsExport = "";
$gsExportFile = "";
$gbDrillDownInPanel = FALSE;
$giFcfChartCnt = 0;

// Used by header.php/footer.php, skip header/footer checking
$gbSkipHeaderFooter = FALSE;
$gbOldSkipHeaderFooter = $gbSkipHeaderFooter;

// Used by extended filter
$gsFormError = "";

// Debug message
$gsDebugMsg = "";
if (!isset($ADODB_OUTP)) $ADODB_OUTP = 'ewr_SetDebugMsg';

// Keep temp images name for PDF export for delete
$gTmpImages = array();

// User agent
$UserAgent = NULL;
?>
<?php

// Menu
define("EWR_MENUBAR_CLASSNAME", "ewMenuBarVertical", TRUE);
define("EWR_MENUBAR_ITEM_CLASSNAME", "", TRUE);
define("EWR_MENUBAR_ITEM_LABEL_CLASSNAME", "", TRUE);
define("EWR_MENU_CLASSNAME", "ewMenuBarVertical", TRUE);
define("EWR_MENU_ITEM_CLASSNAME", "", TRUE);
define("EWR_MENU_ITEM_LABEL_CLASSNAME", "", TRUE);
?>
<?php
define("EWR_PDF_STYLESHEET_FILENAME", "phprptcss/ewrpdf.css", TRUE); // export PDF CSS styles
define("EWR_PDF_MEMORY_LIMIT", "128M", TRUE); // Memory limit
define("EWR_PDF_TIME_LIMIT", 120, TRUE); // Time limit
?>
<?php
define("EWR_FUSIONCHARTS_FREE", TRUE, TRUE); // FusionCharts Free
define("EWR_FUSIONCHARTS_FREE_JSCLASS_FILE", "FusionChartsFree/JSClass/FusionCharts.js", TRUE); // FusionCharts Free
define("EWR_FUSIONCHARTS_FREE_CHART_PATH", "FusionChartsFree/Charts/", TRUE); // FusionCharts Free
?>
