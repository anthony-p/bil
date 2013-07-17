<?php
	/* Note: This thumbnail creation script requires the GD PHP Extension.  
		If GD is not installed correctly PHP does not render this page correctly
		and SWFUpload will get "stuck" never calling uploadSuccess or uploadError
	 */

	// Get the session Id passed from SWFUpload. We have to do this to work-around the Flash Player Cookie Bug
	
	function _log($message) {
		if(!empty($message)) {
			$logger = "/tmp/upload.log";
			$fh = fopen($logger, 'a');
			fwrite($fh, $message."\n");
			fclose($fh);	
		}
	}
	
	_log("starting");
	if(isset($_REQUEST["PHPSESSID"])) {
		session_id($_REQUEST["PHPSESSID"]);
		session_start();
		_log("session: {$_REQUEST["PHPSESSID"]}");
		_log("session: ".print_r($_SESSION,true));
	}
	
	define ('IN_SITE', 1);
	include_once ('includes/npglobal.php');
	include_once ('includes/npclass_formchecker.php');
	include_once ('includes/npclass_custom_field.php');
	include_once ('includes/npclass_user.php');
	include_once ('includes/npfunctions_login.php');
	
	_log("check session...");
	if ($session->value('user_id')) {
		_log("passed! id is {$session->value('user_id')}");
		// If you want to do the same thing as me you will need an instance of the atkDb
		$_action = $_POST['action'];
	
		_log("action is {$_action}");
		switch($_action) {
			case 'upload_logo':
				// Check the upload
				if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
					echo "ERROR:invalid upload";
					exit(0);
				}
				_log("got file");
				require_once($_SERVER['DOCUMENT_ROOT']."/includes/libraries/wideimage/WideImage.php");
				
				$nameinfo = pathinfo($_FILES["Filedata"]["name"]);
				$origname = md5('store').'-'.uniqid()."_original.".$nameinfo['extension'];
				$name = md5('store').'-'.uniqid();
				
				_log("name: {$name}");
				
				$img = WideImage::loadFromUpload('Filedata');
				//_log("save to ".$_SERVER['DOCUMENT_ROOT']."np/uplimg/logos/{$origname}");
				//$img->saveToFile($_SERVER['DOCUMENT_ROOT']."np/uplimg/logos/{$origname}");
				
				$logoname = "{$name}.jpg";
				$logo = $img->resize(160,null);
				$logo->saveToFile($_SERVER['DOCUMENT_ROOT']."/np/uplimg/logos/{$logoname}");
				// trick to show preview after upload...
				$logo->saveToFile("/tmp/{$_FILES["Filedata"]["name"]}");
				_log("save resized to ".$_SERVER['DOCUMENT_ROOT']."/np/uplimg/logos/{$logoname}");
				
				if(is_object($db)) {
					_log("got DB");
					
					// removing old
					$_q = "select logo from np_users where user_id = {$session->value('user_id')}";
					_log($_q);
					$current = $db->query($_q);
					
					if($current) {
						
						$logo = mysql_fetch_assoc($current);
						$logo = $logo['logo'];
						$_logo = $_SERVER['DOCUMENT_ROOT']."/np/uplimg/logos/{$logo}";
						if(is_file($_logo) && is_writable($_logo)) {
							_log("unlinking: {$_logo}");
							unlink($_logo);
						}
					}
					
					$logoname = addslashes($logoname);
					$_q = "update np_users set logo = '{$logoname}' where user_id = {$session->value('user_id')}";
					_log($_q);
					$db->query($_q);
					_log("saved");
				}
				
				echo "{$logoname}";
			break;
		}
	}
	else {
		/// uh-oh: no session...
	}
	
?>
