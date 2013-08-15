<?
/*
 * PHP function to resize an image maintaining aspect ratio
 * http://salman-w.blogspot.com/2008/10/resize-images-using-phpgd-library.html
 *
 * Creates a resized (e.g. thumbnail, small, medium, large)
 * version of an image file and saves it as another file
 */
define('THUMBNAIL_IMAGE_MAX_WIDTH', 150);
define('THUMBNAIL_IMAGE_MAX_HEIGHT', 150);

function generate_image_thumbnail(
    $source_image_path,
    $thumbnail_image_path,
    $thumbnail_image_max_width = 600,
    $thumbnail_image_max_height = 400
)
{
//    var_dump(file_exists($thumbnail_image_path));
//    var_dump(file_exists($source_image_path));
    list($source_image_width, $source_image_height, $source_image_type) = getimagesize($source_image_path);
    switch ($source_image_type) {
        case IMAGETYPE_GIF:
            $source_gd_image = imagecreatefromgif($source_image_path);
            break;
        case IMAGETYPE_JPEG:
            $source_gd_image = imagecreatefromjpeg($source_image_path);
            break;
        case IMAGETYPE_PNG:
            $source_gd_image = imagecreatefrompng($source_image_path);
            break;
    }
    if ($source_gd_image === false) {
        return false;
    }
    $source_aspect_ratio = $source_image_width / $source_image_height;
    $thumbnail_aspect_ratio = $thumbnail_image_max_width / $thumbnail_image_max_height;
    if ($source_image_width <= $thumbnail_image_max_width && $source_image_height <= $thumbnail_image_max_height) {
        $thumbnail_image_width = $source_image_width;
        $thumbnail_image_height = $source_image_height;
    } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
        $thumbnail_image_width = (int) ($thumbnail_image_max_height * $source_aspect_ratio);
        $thumbnail_image_height = $thumbnail_image_max_height;
    } else {
        $thumbnail_image_width = $thumbnail_image_max_width;
        $thumbnail_image_height = (int) ($thumbnail_image_max_width / $source_aspect_ratio);
    }

    if (($source_image_width / $source_image_height) <
        ($thumbnail_image_max_width / $thumbnail_image_max_height)) {
        $thumbnail_gd_image = imagecreatetruecolor(
            $thumbnail_image_max_width,
            $thumbnail_image_max_height
        );

        $background_color = imagecolorallocate($thumbnail_gd_image, 255, 255, 255);
//        $background_color = imagecolorallocate($thumbnail_gd_image, 242, 242, 242);
        imagefill($thumbnail_gd_image, 0, 0, $background_color);

        $dest_x = ($thumbnail_image_max_width - $thumbnail_image_width) / 2;
        $dest_y = ($thumbnail_image_max_height - $thumbnail_image_height) / 2;

        imagecopyresampled(
            $thumbnail_gd_image, $source_gd_image, $dest_x, $dest_y, 0, 0, $thumbnail_image_width,
            $thumbnail_image_height, $source_image_width, $source_image_height
        );
    } else {
        $thumbnail_gd_image = imagecreatetruecolor($thumbnail_image_width, $thumbnail_image_height);
        imagecopyresampled(
            $thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $thumbnail_image_width,
            $thumbnail_image_height, $source_image_width, $source_image_height
        );
    }
    imagejpeg($thumbnail_gd_image, $thumbnail_image_path, 90);
    imagedestroy($source_gd_image);
    imagedestroy($thumbnail_gd_image);
    return true;
}
?>