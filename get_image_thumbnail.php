<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dev
 * Date: 8/14/13
 * Time: 8:37 PM
 */

include_once("includes/generate_image_thumbnail.php");

$image = "220x165_image_/images/partner_logos/db5e800e773c5a7dc77af8e0c712adf3.jpg";
$image = isset($_GET["image"]) ? $_GET["image"] : '';

//if (!$image && isset($campaign["banner"])) {
//    $image = $campaign["banner"];
//}

$image_data = explode("_image_", $image);
$dimensions = explode("x", $image_data[0]);
$image_path_details = explode("/", $image_data[1]);

$image_name = $image_path_details[count($image_path_details) - 1];

unset($image_path_details[count($image_path_details) - 1]);

$root_image_path = implode("/", $image_path_details);

$thumbnail_image = $root_image_path . "/" . $image_data[0] . "_" . $image_name;

if (!file_exists("." . $thumbnail_image)) {
    generate_image_thumbnail(
        "." . $image_data[1], "." . $thumbnail_image, $dimensions[0], $dimensions[1], true
    );
}

header("Content-Type: image/jpeg");
readfile("." . $thumbnail_image);