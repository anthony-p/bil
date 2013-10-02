<?php
//------------------------------------------------------------------------------
//
// PHP Connection Script for PHPMaker 9
// (C) 2012 e.World Technology Ltd. All rights reserved.
//
// IMPORTANT NOTE:
// For security reasons, you should remove this script from your site after use.
//
// How to use:
//	1. Upload this script to your site,
//	2. Browse to this script using IE or Firefox,
//	3. Enter the connection info,
//	4. Click "Get Database List", select your database, the encoding and then
//     click "View Schema", check if you can view the schema in XML properly,
//	5. If OK, enter the same connection info and the URL of this script in
//     PHPMaker.
//
//------------------------------------------------------------------------------

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Always modified
header("Cache-Control: private, no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", FALSE);
header("Pragma: no-cache"); // HTTP/1.0

$debug = FALSE; // Change to TRUE to view the XML as HTML

// Get parameters
$majorversion = "9"; // DO NOT CHANGE!
$dbtype = strtolower(@$_GET["dbtype"]);
$host = @$_GET["host"];
$uid = @$_GET["user"];
$pwd = @$_GET["password"];
$port = @$_GET["port"];
$dbname = @$_GET["db"];
//$encoding = @$_GET["encoding"];
$encoding = "utf-8"; // P8
$quotechr = @$_GET["quotechr"];
$cvname = @$_POST["cv"];
$cmd = strtolower(@$_GET["cmd"]); // "db|tbl|vw|cv|test|exec|execs"
$sql = ($cmd == "cv") ? @$_POST["sql"] : @$_GET["sql"];
if (get_magic_quotes_gpc()) {
	$dbname = stripslashes($dbname);
	$quotechr = stripslashes($quotechr);
	$sql = stripslashes($sql);
}
if ($quotechr == "default" || $quotechr == "")
	$quotechr = ($dbtype == "postgresql") ? "\"" : "`";

// Create meta object
$dbtype = ($dbtype == "") ? "mysql" : $dbtype;
if ($host <> "") {
	if ($dbtype == "postgresql") {
		$meta = new MetaPostgreSQL();
	} else {
		$meta = new MetaMySQL();
	}
}

// Output
if (!empty($cmd)) {
	
	if ($cmd == "db2") {
		$dbnames = $meta->GetDatabases();
		if ($dbnames) {
			$dblist = "";
			foreach ($dbnames as $dbname)
				$dblist .= "<option value=\"" . htmlspecialchars($dbname) . "\">". htmlspecialchars($dbname) . "</option>";
			$dblist = "<select name=\"db\">" . $dblist . "</select>";
		} else { // No databases names
			$dblist = "<input type=\"text\" name=\"db\">";
		}
	} else {
		$xml = "<phpmaker version=\"$majorversion\">";
		$xml .= "<phpversion>". phpversion() ."</phpversion>";
		$xml .= "<serverversion>". $meta->ServerVersion ."</serverversion>";
		if ($cmd == "db") {
			$xml .= "<databases>";
			$dbnames = $meta->GetDatabases();
			if ($dbnames) {
				foreach ($dbnames as $dbname)
					$xml .= "<database>" . htmlspecialchars($dbname) . "</database>";
			} else { // No databases names
				if ($dbname != "")
					$xml .= "<database>". htmlspecialchars($dbname) . "</database>";
			}
			$xml .= "</databases>";
		} elseif ($cmd == "test") {
			if (!empty($dbname)) {
				if ($tables = $meta->GetTables()) {
					$xml .= "<tables>";
					foreach ($tables as $table) {
						$xml .= "<table name=\"" . htmlspecialchars($table["name"]) . "\"";
						if (@$table["type"] <> "")
							$xml .= " tabletype=\"" . htmlspecialchars($table["type"]) . "\"";
						$xml .= " />";
					}
					$xml .= "</tables>";
				}
			}
		} elseif ($cmd == "tbl") {
			if (!empty($dbname)) {
				if ($tables = $meta->GetTables()) {
					$xml .= "<tables>";
					foreach ($tables as $table) {
						$xml .= "<table name=\"" . htmlspecialchars($table["name"]) . "\"";
						if (@$table["type"] <> "")
							$xml .= " tabletype=\"" . htmlspecialchars($table["type"]) . "\"";
						if (@$table["schema"] <> "") // P8
							$xml .= " schema=\"" . htmlspecialchars($table["schema"]) . "\"";
						$xml .= ">";
						// Get schema
						if ($fields = $meta->GetSchema(@$table["schema"], $table["name"])) {
							$xml .= "<fields>";
							foreach ($fields as $field) {
								$xml .= "<field";
								foreach ($field as $key => $value) {
									if (strval($value) <> "")
										$xml .= " " . $key . "=\"" . htmlspecialchars(strval($value)) ."\"";
								}
								$xml .= " />";
							}
							$xml .= "</fields>";
						}
						$xml .= "</table>";
					}
					$xml .= "</tables>";
				}
			}
		} elseif ($cmd == "cv") {
			$xml .= "<tables>";
			$xml .= "<table tabletype=\"CUSTOMVIEW\" name=\"" . htmlspecialchars($cvname) . "\">";
			if (!empty($sql)) {
				if ($fields = $meta->GetSchema("", $cvname, $sql)) {
					$xml .= "<fields>";
					foreach ($fields as $field) {
						$xml .= "<field";
						foreach ($field as $key => $value) {
							if (strval($value) <> "")
								$xml .= " " . $key . "=\"" . htmlspecialchars(strval($value)) ."\"";
						}
						$xml .= " />";
					}
					$xml .= "</fields>";
				}
			}
			$xml .= "</table>";
			$xml .= "</tables>";
		} elseif ($cmd == "exec") {
			$xml .= "<result>" . $meta->Query($sql) . "</result>";
		} elseif ($cmd == "vw") {
			$xml .= "<result>" . htmlspecialchars($meta->ExecuteScalar($sql, 1)) . "</result>";
		} elseif ($cmd == "execs") { // ExecuteScalar
			$xml .= "<result>" . htmlspecialchars($meta->ExecuteScalar($sql)) . "</result>"; // 8.0.2
		}
		$xml .= "</phpmaker>";
		
		if ($debug) {
			echo htmlspecialchars($xml);
		} else {
			header("Content-type: text/xml");
			echo  "<?xml version=\"1.0\"";
			if (!empty($encoding))
				echo " encoding=\"" . $encoding . "\"";
			echo " standalone=\"yes\"?>";
			echo $xml;
		}
		
		exit();
	}

}
?>
<html>
<head>
<title>PHPMaker <?php echo $majorversion ?> Connection Script for MySQL/PostgreSQL</title>
<style type="text/css">
p, td {
	font-family: Verdana;
	font-size: x-small;
}
</style>
</head>
<body>
<p><b>PHPMaker <?php echo $majorversion ?> Connection Script for MySQL/PostgreSQL</b></p>
<form>
  <input type="hidden" name="cmd" value="">
  <table cellspacing="0" cellpadding="5" border="0">
		<tr>
      <td>Database type</td>
      <td>
				<label><input type="radio" name="dbtype" value="mysql"<?php if ($dbtype == "mysql") echo " checked" ?>>MySQL</label>&nbsp;&nbsp;
				<label><input type="radio" name="dbtype" value="postgresql"<?php if ($dbtype == "postgresql") echo " checked" ?>>PostgreSQL</label>
			</td>
    </tr>
    <tr>
      <td>Host</td>
      <td><input type="text" name="host" value="<?php echo (!empty($host)) ? $host : "localhost" ?>"></td>
    </tr>
    <tr>
      <td>User</td>
      <td><input type="text" name="user" value="<?php echo @$uid ?>"></td>
    </tr>
    <tr>
      <td>Password</td>
      <td><input type="text" name="password" value="<?php echo @$pwd ?>"></td>
    </tr>
    <tr>
      <td>Port (if not default)</td>
      <td><input type="text" name="port" value="<?php echo @$port ?>">
      </td>
    </tr>
		<?php if (empty($dblist)) { ?>
    <tr>
      <td>&nbsp;</td>
      <td><input type="button" name="btndb" value="Get Database List" onClick="this.form.cmd.value='db2';this.form.submit();">
      </td>
    </tr>
    <?php } else { ?>
    <tr>
      <td>Database</td>
      <td><?php echo $dblist ?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="button" name="btntbl" value="View Schema" onClick="this.form.cmd.value='tbl';this.form.submit();">
      </td>
    </tr>
    <?php } ?>
  </table>
</form>
<p>(C) 2012 e.World Technology Ltd.</p>
</body>
</html>
<?php
//------------------------------------------------------------------------------
//
// Meta data for MySQL
//
//------------------------------------------------------------------------------

class MetaMySQL {
	var $ServerVersion;
	var $Link;

	function MetaMySQL() {
		if (!function_exists("mysql_connect"))
			die("PHP MySQL extension not installed.");
		global $host, $port, $uid, $pwd, $dbname;
		if (is_numeric($port)) {
			$this->Link = mysql_connect($host . ":" . intval($port), $uid, $pwd) or die(mysql_error());
		} else {
			$this->Link = mysql_connect($host, $uid, $pwd) or die(mysql_error());
		}
		mysql_query("SET NAMES 'utf8'"); // P8
		$this->ServerVersion = $this->GetServerVersion();
		if (strval($dbname) <> "")
			mysql_select_db($dbname) or die(mysql_error());
	}

	function GetDatabases() {
		if ($rs = mysql_query("SHOW DATABASES")) {
			$res = array();
			while ($row = mysql_fetch_array($rs))
				$res[] = $row[0];
			return $res;
		}
		return FALSE;
	}
	
	function GetTables() {
//		global $dbname;
//		mysql_select_db($dbname) or die(mysql_error());
		$match = explode('.', $this->ServerVersion);
		$isMySQL5 = intval(sprintf('%d%02d%02d', $match[0], $match[1], intval($match[2]))) >= 50002;
		$rs = mysql_query($isMySQL5 ? "SHOW FULL TABLES" : "SHOW TABLES") or die(mysql_error());
		if ($rs) {
			$res = array();
			$num = mysql_num_rows($rs);
			for ($i = 0; $i < $num; $i++) {
				$tbl = array();
				$row = mysql_fetch_array($rs);
				$tbl["name"] = $row[0];
				$tableType = "";
				if ($isMySQL5) {
					$tableType = $row[1];
					if ($tableType == "BASE TABLE" || $tableType == "SYSTEM VIEW")
						$tableType = "TABLE";
					$tbl["type"] = $tableType;
				}
				$res[] = $tbl;
			}
			return $res;
		}
		return FALSE;
	}
	
	function GetServerVersion() {
		$pResult = mysql_query("SHOW VARIABLES LIKE 'version'") or die(mysql_error());
		$version = "unknown";
		if ($field = mysql_fetch_array($pResult))
			$version = $field["Value"];
		return $version;
	}
	
	function GetSchema($schema, $tablename, $sql = "") {
		global $quotechr;
		$res = FALSE;
//		global $dbname;
//		mysql_select_db($dbname) or die(mysql_error());
		if ($sql == "") { // table
			$result1 = mysql_query("SHOW COLUMNS FROM " . $quotechr . $tablename . $quotechr) or die(mysql_error());
			$result2 = mysql_query("SELECT * FROM " . $quotechr . $tablename . $quotechr . " LIMIT 1") or die(mysql_error());
			if ($result1) {
				$res = array();
				$num = mysql_num_rows($result1);
				for ($i = 0; $i < $num; $i++) {
					$valuelist = "";
					$M = "";
					$D = "";
					$field = mysql_fetch_array($result1);
					$fieldtype = @$field["Type"];
					$fieldkey = @$field["Key"];
					$fieldextra = @$field["Extra"];
					$fieldnull = @$field["Null"];
					$fielddefault = @$field["Default"];
					// parse field info
					$type = strtok($fieldtype, " (,)\n" );
					if (substr_count($fieldtype, "(" )) {
						if ($type == "enum" || $type == "set") {
							$valuelist = strtok( " ()\n");
						} else {
							$M = strtok(" (,)\n");
							if (substr_count($fieldtype, "," ))
								$D = strtok( " (,)\n" );
						}
					}
					$fld = array();
					$fld["name"] = $field["Field"];
					$fld["type"] = $type;
					$fld["precision"] = (is_numeric($D) ? $D : 0);
					$fld["unsigned"] = (substr_count($fieldtype, "unsigned") ? 1 : 0);
					$fld["zerofill"] = (substr_count($fieldtype, "zerofill") ? 1 : 0);
					$fld["binary"] = (substr_count($fieldtype, "binary") ? 1 : 0);
					$fld["valuelist"] = $valuelist;
					$fld["null"] = (($fieldnull == "YES") ? 1 : 0);
					$fld["default"] = $fielddefault;
					if ($type == 'varchar' || $type == 'char') {
						$fld["size"] = (is_numeric($M) ? $M : 0);
					} elseif ($type == 'longtext') {
						$fld["size"] = 4294967295;
					} else {
						$len = mysql_field_len($result2, $i);
						$fld["size"] = $len;
					}
		    		$flags = mysql_field_flags($result2, $i);
					$fld["primarykey"] = (($fieldkey == "PRI" || substr_count($flags, "primary_key")) ? 1 : 0);
					$fld["autoincrement"] = (($fieldextra == "auto_increment" || substr_count($flags, "auto_increment")) ? 1 : 0);
					$fld["uniquekey"] = (substr_count($flags, "unique_key") ? 1 : 0);
					$table = mysql_field_table($result2, $i);
					$fld["table"] = $table;
					$res[] = $fld;
				}	
			}
		} else { // view
			$result2 = mysql_query($sql) or die(mysql_error());
			if ($result2) {
				$res = array();
				$num = mysql_num_fields($result2);
				for ($i = 0; $i < $num; $i++) {
					$fld = array();
					$fld["name"] = mysql_field_name($result2, $i);
					$fld["size"] = mysql_field_len($result2, $i);
					$fld["type"] = mysql_field_type($result2, $i);
					$fld["precision"] = "0";
					$fld["valuelist"] = "";
					$fld["default"] = "";
					$flags = mysql_field_flags($result2, $i);
					$fld["binary"] = (substr_count($flags, "binary") ? 1 : 0);
					$fld["uniquekey"] = (substr_count($flags, "unique_key") ? 1 : 0);
					$fld["unsigned"] = (substr_count($flags, "unsigned") ? 1 : 0);
					$fld["zerofill"] = (substr_count($flags, "zerofill") ? 1 : 0);
					$fld["null"] = (substr_count($flags, "not_null") ? 0 : 1);
					$fld["primarykey"] = (substr_count($flags, "primary_key") ? 1 : 0);
					$fld["autoincrement"] = (substr_count($flags, "auto_increment") ? 1 : 0);
					$table = mysql_field_table($result2, $i);
					if (!empty($table))
						$fld["table"] = $table;
					$res[] = $fld;
				}	
			}
		}
		return $res;
	}
	
	function Query($sql) {
//		global $dbname;
//		mysql_select_db($dbname) or die(mysql_error());
		$rs = mysql_query($sql);
		if ($rs) {
			$stmt = strtoupper(substr($sql, 0, 7));
			if ($stmt == "SELECT ") {
				return mysql_num_rows($rs);
			} elseif ($stmt == "INSERT " || $stmt == "UPDATE ") {
				return mysql_affected_rows();
			} else {
				return "1";
			}
		} else {
			return mysql_error();
		}
	}
	
	function ExecuteScalar($sql, $idx = 0) {
//		global $dbname;
//		mysql_select_db($dbname) or die(mysql_error());
		$rs = mysql_query($sql);
		if ($rs) {
			if ($row = mysql_fetch_array($rs))
				return $row[$idx];
		} else {
			return mysql_error();
		}
		return FALSE;
	}

}


//------------------------------------------------------------------------------
//
// Meta data for PostgreSQL
//
//------------------------------------------------------------------------------

class MetaPostgreSQL {
	var $ServerVersion;
	var $Link;
	
	var $metaDatabasesSQL = "SELECT datname FROM pg_database WHERE datname NOT IN ('template0','template1') ORDER BY 1";
	
  var $metaTablesSQL = "SELECT tablename, 'TABLE', '' FROM pg_tables WHERE tablename NOT LIKE 'pg\_%'
		AND tablename NOT IN ('sql_features', 'sql_implementation_info', 'sql_languages',
		 'sql_packages', 'sql_sizing', 'sql_sizing_profiles')
		UNION
	  SELECT viewname, 'VIEW', '' FROM pg_views WHERE viewname NOT LIKE 'pg\_%'";

	// Used when schema defined (PostgreSQL >= "7.3")
	var $metaTablesSQL2 = "SELECT tablename, 'TABLE', schemaname FROM pg_tables WHERE tablename NOT LIKE 'pg\_%'
		AND schemaname NOT IN ('pg_catalog','information_schema')
		UNION
	  SELECT viewname, 'VIEW', schemaname FROM pg_views WHERE viewname NOT LIKE 'pg\_%' AND schemaname NOT IN ('pg_catalog','information_schema')";
				
	var $metaColumnsSQL = "SELECT a.attname, t.typname, a.attlen, a.atttypmod, a.attnotnull, a.atthasdef, a.attnum
		FROM pg_class c, pg_attribute a, pg_type t
		WHERE relkind in ('r','v') AND c.relname = '%s' AND a.attname NOT LIKE '....%%'
		AND a.attnum > 0 AND a.atttypid = t.oid AND a.attrelid = c.oid ORDER BY a.attnum";

	// Used when schema defined
	var $metaColumnsSQL2 = "SELECT a.attname, t.typname, a.attlen, a.atttypmod, a.attnotnull, a.atthasdef, a.attnum
		FROM pg_class c, pg_attribute a, pg_type t, pg_namespace n
		WHERE relkind IN ('r','v') AND c.relname = '%s' AND c.relnamespace = n.oid AND n.nspname = '%s'
		AND a.attname NOT LIKE '....%%' AND a.attnum > 0
		AND a.atttypid = t.oid AND a.attrelid = c.oid ORDER BY a.attnum";
	
	var $metaKeySQL = "SELECT ic.relname AS index_name, a.attname AS column_name, i.indisunique AS unique_key, i.indisprimary AS primary_key, i.indnatts AS column_count	FROM pg_class bc, pg_class ic, pg_index i, pg_attribute a WHERE bc.oid = i.indrelid AND ic.oid = i.indexrelid AND (i.indkey[0] = a.attnum OR i.indkey[1] = a.attnum OR i.indkey[2] = a.attnum OR i.indkey[3] = a.attnum OR i.indkey[4] = a.attnum OR i.indkey[5] = a.attnum OR i.indkey[6] = a.attnum OR i.indkey[7] = a.attnum) AND a.attrelid = bc.oid AND bc.relname = '%s'";

	var $metaDefaultsSQL = "SELECT d.adnum AS num, d.adsrc AS def FROM pg_attrdef d, pg_class c WHERE d.adrelid = c.oid AND c.relname = '%s' ORDER BY d.adnum";

	function MetaPostgreSQL() {
		if (!function_exists("pg_connect"))
			die("PHP PostgreSQL extension not installed.");
		global $host, $port, $uid, $pwd, $dbname;
		$connstr = "host=$host user=$uid password=$pwd";
		if (is_numeric($port))
			$connstr .= " port=$port";
		if ($dbname <> "")
			$connstr .= " dbname=$dbname";
		$this->Link = pg_connect($connstr);
		$this->ServerVersion = $this->GetServerVersion();
	}

	function GetDatabases() {
		if ($rs =& $this->Execute($this->metaDatabasesSQL)) {
			$res = array();
			while ($row = pg_fetch_array($rs))
				$res[] = $row[0];
			return $res;
		}
		return FALSE;
	}
	
	function GetTables() {
		$metaTablesSQL = ($this->ServerVersion >= "7.3") ? $this->metaTablesSQL2 : $this->metaTablesSQL;
        if ($rs =& $this->Execute($metaTablesSQL)) {
			$res = array();
			$num = pg_num_rows($rs);
			for ($i = 0; $i < $num; $i++) {
				$tbl = array();
				$row = pg_fetch_array($rs);
				$tbl["name"] = $row[0];
				$tbl["type"] = $row[1];
				$tbl["schema"] = $row[2]; 
				$res[] = $tbl;
			}
			return $res;
		}
		return FALSE;
	}
	
	function GetServerVersion() {
		$rs =& $this->Execute("select version()");
		$row = pg_fetch_array($rs, NULL);
		pg_free_result($rs);
		if (preg_match('/([0-9]+\.([0-9\.])+)/', $row[0], $arr))
			return $arr[1];
		else
			return "";
	}
	
	function GetSchema($schema, $tablename, $sql = "") {
		global $quotechr;
		$res = FALSE;
		if ($sql == "") { // table
			$tablename = str_replace("'", "''", $tablename);
			if ($schema <> "") {
				$schema = str_replace("'", "''", $schema);
				$metaColumnsSQL = sprintf($this->metaColumnsSQL2, $tablename, $schema);
			} else {
				$metaColumnsSQL = sprintf($this->metaColumnsSQL, $tablename);
			}
			$rs =& $this->Execute($metaColumnsSQL);
			if (!empty($this->metaKeySQL)) {
				if ($rskey =& $this->Execute(sprintf($this->metaKeySQL, $tablename))) {
					$keys = array();
					while ($row = pg_fetch_array($rskey, NULL))
						$keys[] = $row;
					pg_free_result($rskey);
				}
			}
			$rsdefa = array();
			if (!empty($this->metaDefaultsSQL)) {
				if ($rsdef =& $this->Execute(sprintf($this->metaDefaultsSQL, $tablename))) {
					while ($row = pg_fetch_array($rsdef, NULL)) {
						$num = $row['num'];
						$s = $row['def'];
						if (strpos($s, "::") === FALSE && substr($s, 0, 1) == "'" && substr($s, -1) == "'") {
							$s = substr($s, 1, strlen($s) - 2);
							$s = str_replace("''", "'", $s);
						}
						$rsdefa[$num] = $s;
					}
					pg_free_result($rsdef);
				}
			}
			$res = array();
			while ($row = pg_fetch_array($rs)) {
				$fld = array();
				$fld["name"] = $row[0];
				$fld["type"] = $row[1];
				$fld["size"] = $row[2];
				if ($fld["size"] <= 0)
					$fld["size"] = $row[3]-4;
				if ($fld["size"] <= 0)
					$fld["size"] = 0;
				if ($fld["type"] == 'numeric') {
					$fld["scale"] = $fld["size"] & 0xFFFF;
					$fld["size"] >>= 16;
				}
				$fld["binary"] = ($row[1] == "bytea") ? 1 : 0;
				$has_default = ($row[5] == 't');
				$fld["default"] = ($has_default) ? $rsdefa[$row[6]] : "";
				$fld["null"] = ($row[4] <> 't') ? 1 : 0;
				$fld["primarykey"] = 0;
				$fld["uniquekey"] = 0;
				if (is_array($keys)) {
					foreach ($keys as $key) {
						if ($fld["name"] == $key['column_name'] && $key['primary_key'] == 't')
							$fld["primarykey"] = 1;
						if ($fld["name"] == $key['column_name'] && $key['unique_key'] == 't' && $key['column_count'] == 1)
							$fld["uniquekey"] = 1;
					}
				}
				$fld["autoincrement"] = ($fld["primarykey"] == 1 && $fld["uniquekey"] == 1 && $has_default && substr($fld["default"], 0, 8) == "nextval(") ? 1 : 0;
				if ($has_default) {
					if (substr($fld["default"], 0, 8) == "nextval(") {
						//$fld["default"] = ""; // keep the value
					} else if (strpos($fld["default"] , "::") !== FALSE) {
						$fld["default"] = strtok($fld["default"], "::");
						if ($fld["default"] == "NULL") {
							$fld["default"] = "";
						} elseif (substr($fld["default"], 0, 1) == "'" && substr($fld["default"], -1) == "'") {
							$fld["default"] = substr($fld["default"], 1, strlen($fld["default"]) - 2);
							$fld["default"] = str_replace("''", "'", $fld["default"]);
						}
					}
				}
				$res[] = $fld;
			}
		} else { // view
			if ($result2 =& $this->Execute($sql)) {
				$res = array();
				$num = pg_num_fields($result2);
				for ($i = 0; $i < $num; $i++) {
					$fld = array();
					$fld["name"] = pg_field_name($result2, $i);
					$fld["type"] = pg_field_type($result2, $i);
					$fld["size"] = pg_field_size($result2, $i);
					$fld["binary"] = ($fld["type"] == "bytea") ? 1 : 0;
					$fld["default"] = "";
					$table = pg_field_table($result2, $i);
					if (!empty($table))
						$fld["table"] = $table;
					$res[] = $fld;
				}
			}
		}
		return $res;
	}
	
	function Query($sql) {
		$res = FALSE;
		$rs = pg_query($sql);
		if ($rs) {
			$stmt = strtoupper(substr($sql, 0, 7));
			if ($stmt == "SELECT ") {
				return pg_num_rows($rs);
			} elseif ($stmt == "INSERT " || $stmt == "UPDATE ") {
				return pg_affected_rows();
			} else {
				return "1";
			}
		} else {
			return "Failed to execute SQL: " . $sql;
		}
	}
	
	function &Execute($sql) {
		$rs = pg_query($sql);
		if ($rs) {
			return $rs;
		} else {
			die("Failed to execute SQL: ". $sql);
		}
	}
	
	function ExecuteScalar($sql, $idx = 0) {
		$rs = pg_query($sql);
		if ($rs) {
			if ($row = pg_fetch_array($rs))
				return $row[$idx];
		}
		return FALSE;
	}

}

?>