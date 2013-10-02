<?php
//get
$do = $_GET['do'];

// swich between 3 options captcha, upload image and insert data in database
switch($do){

// load captcha image
case 'captcha':

// start captcha session
session_start();

$string = '';

for ($i = 0; $i < 5; $i++) {
	// this numbers refer to numbers of the ascii table (lower case)
	$string .= chr(rand(97, 122));
}

$_SESSION['rand_code'] = $string;

// font director to create captcha
$dir = 'inc/font/';

// create image
$image = imagecreatetruecolor(120, 32);
$black = imagecolorallocate($image, 0, 0, 0);
$color = imagecolorallocate($image, 196, 199, 202);
$white = imagecolorallocate($image, 255, 255, 255);

imagefilledrectangle($image,0,0,399,99,$white);
imagettftext ($image, 26, 0, 20, 26, $color, $dir."font.ttf", $_SESSION['rand_code']);

header("Content-type: image/png");
imagepng($image);

break;

// upload image section
case 'upload':

// get file size
$filesize = $_FILES['uploadfile']['size'];

//maxim image limit
$maxlimit = 1048576;

//folder where to save images
$folder = "images";

// temporary file
$uploadedfile = $_FILES['uploadfile']['tmp_name'];

// check filesize
 if($filesize > $maxlimit){ 
	echo "File size is too big.";
  } else if($filesize < 1){ 
	echo "File size is empty.";
  } else {	

// all characters lowercase
$filename = strtolower($_FILES['uploadfile']['name']);

// replace all spaces with _
$filename = preg_replace('/\s/', '_', $filename);

// extract filename and extension
$pos = strrpos($filename, '.'); 
$basename = substr($filename, 0, $pos); 
$ext = substr($filename, $pos+1);

// get random number
$rand = time();

// image name
$image = $basename .'-'. $rand . "." . $ext;

// check if file exists
$check = $folder . '/' . $image;
if (file_exists($check)) {
    echo 'Image already exists';
} else {

// create an image from it so we can do the resize
 switch($ext){
  case "gif":
	$src = imagecreatefromgif($uploadedfile);
  break;
  case "jpg":
	$src = imagecreatefromjpeg($uploadedfile);
  break;
  case "jpeg":
	$src = imagecreatefromjpeg($uploadedfile);
  break;
  case "png":
	$src = imagecreatefrompng($uploadedfile);
  break;
 }

// capture the original size of the uploaded image
list($width,$height) = getimagesize($uploadedfile);

// resize image to 400 width x 400 height pixels 
$newwidth = 400;
$newheight = ($height/$width)*400;
$tmp=imagecreatetruecolor($newwidth,$newheight);

imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);

// write resized image to disk
$write_image = $folder . '/' . $image;
 switch($ext){
  case "gif":
	imagegif($tmp,$write_image);
  break;
  case "jpg":
	imagejpeg($tmp,$write_image,100);
  break;
  case "jpeg":
	imagejpeg($tmp,$write_image,100);
  break;
  case "png":
	imagepng($tmp,$write_image,0);
  break;
 }

// create thumbnail image - resize original to 105 width x 105 height pixels 
$newheight = 105;
$newwidth = 105;
$tmp = imagecreatetruecolor($newwidth,$newheight);

imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);

// write thumbnail to disk
$write_image = $folder .'/thumb-'. $image;
 switch($ext){
  case "gif":
	imagegif($tmp,$write_image);
  break;
  case "jpg":
	imagejpeg($tmp,$write_image,100);
  break;
  case "jpeg":
	imagejpeg($tmp,$write_image,100);
  break;
  case "png":
	imagepng($tmp,$write_image,0);
  break;
 }

// all is done. clean temporary files
imagedestroy($src);
imagedestroy($tmp);

// image preview
echo "<img src='" . $write_image . "' alt='". $image ."' title='". $image ."' />
<input type='text' name='uploadedfile' id='uploadedfile' value='". $image ."' class='hidden' />
<script language='javascript' type='text/javascript'>window.top.window.formEnable();</script>";
	}
  }
break;

// insert in mysql database section
case 'insert':

// session start
session_start();

	// check form inputs if are empty
	
	// database connection
	include('inc/db.inc.php');

			// insert into mysql database and show success message
			mysql_query("INSERT INTO `user_submit` (`id`, `image`) VALUES (NULL,  '".$_POST['uploadedfile']."')"); 
												
			echo '<div class="success">Successfully added, thanks!</div>
			<script language="javascript" type="text/javascript">window.top.window.formDone();</script>';

				//error invalid url
				
		
break;
default:
 // error message - file can't be accessed directly
 echo 'You can&acute;t access this file directly';
break;
  }
?>
