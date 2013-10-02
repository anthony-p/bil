<?php
session_start();
ob_start();
?>
<?php include "phprptinc/ewrcfg4.php"; ?>
<?php include "phprptinc/ewmysql.php"; ?>
<?php include "phprptinc/ewrfn4.php"; ?>
<?php

// Get resize parameters
$resize = (@$_GET["resize"] <> "");
$width = (@$_GET["width"] <> "") ? $_GET["width"] : 0;
$height = (@$_GET["height"] <> "") ? $_GET["height"] : 0;
if (@$_GET["width"] == "" && @$_GET["height"] == "") {
	$width = EWRPT_THUMBNAIL_DEFAULT_WIDTH;
	$height = EWRPT_THUMBNAIL_DEFAULT_HEIGHT;
}
$quality = (@$_GET["quality"] <> "") ? $_GET["quality"] : EWRPT_THUMBNAIL_DEFAULT_QUALITY;

// Resize image from physical file
if (@$_GET["fn"] <> "") {
	$fn = ewrpt_StripSlashes($_GET["fn"]);
	$fn = str_replace("\0", "", $fn);
	$fn = ewrpt_PathCombine(ewrpt_AppRoot(), $fn, TRUE);
	if (file_exists($fn)) {
		$pathinfo = pathinfo($fn);
		$ext = strtolower($pathinfo['extension']);
		$size = getimagesize($fn);
		if ($size)
			header("Content-type: {$size['mime']}");
		echo ewrpt_ResizeFileToBinary($fn, $width, $height, $quality);
	}
	exit();
}?>
