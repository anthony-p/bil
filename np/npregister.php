<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
## PHP Pro Bid & PHP Pro Ads Integration v1.00						##
#################################################################


session_start();

define ('IN_SITE', 1);

(string) $page_handle = 'register';

include_once ('includes/npglobal.php');

include_once ('includes/npclass_formchecker.php');
include_once ('includes/npclass_custom_field.php');
include_once ('includes/npclass_item.php');
include_once ('includes/npclass_user.php');
include_once ('includes/npclass_pitch.php');
include_once ('includes/npclass_fees.php');
include_once ('includes/npfunctions_login.php');

if (!$session->value('user_id'))
{
	header_redirect('../login.php');
}
else
{
	$custom_fld = new npcustom_field();
	$tax = new nptax();

	$voucher = new item();
	$voucher->setts = &$setts;

	include_once ('npglobal_header.php');

    // TinyMCE MoxieManager Override the rootpath for images
    if (!file_exists(dirname(realpath("../login.php")) . "/uplimg/" . $session->value('user_id'))) mkdir(dirname(realpath("../login.php")) . "/uplimg/" .$session->value('user_id'));
    $_SESSION['filesystem.rootpath'] = dirname(realpath("../login.php")) . "/uplimg/" . $session->value('user_id');


    $banned_output = check_banned($_SERVER['REMOTE_ADDR'], 1);

	if ($banned_output['result'])
	{
		$template->set('message_header', header5(MSG_CREATE_NEW));
		$template->set('message_content', $banned_output['display']);

		$template_output .= $template->process('single_message.tpl.php');
	}
	else
	{
		$template->set('imgarrowit', $imgarrowit);
		$template->set('header_registration_message', header5(MSG_CREATE_NEW));

		/* From here we load the custom registration sections */

		(string) $custom_sections_table = null;

		$user = new npuser();
		$user->setts = &$setts;## PHP Pro Bid v6.00 now we will save all post variables selected
		$user->save_vars($_POST);



		$form_submitted = FALSE;## PHP Pro Bid v6.00 if save button is pressed, proceed
		if (isset($_REQUEST['operation']) && $_REQUEST['operation'] == 'submit')
		{
            $allowed_image_mime_types = array(
                'image/gif',
                'image/jpeg',
                'image/pjpeg',
                'image/png',
                'image/tiff',
                'image/svg+xml'
            );
			define ('FRMCHK_USER', 1);
			(bool) $frmchk_user_edit = 0;
            $frmchk_details = $_POST;

            if (isset ($_FILES["logo"]) && is_uploaded_file($_FILES["logo"]["tmp_name"])) {
                $frmchk_details["logo"]  =array();
                $frmchk_details["logo"]["field"] = "logo";
                $frmchk_details["logo"]["type"] = $_FILES["logo"]["type"];
                if (in_array($_FILES["logo"]["type"], $allowed_image_mime_types)) {
                    $logo_image_size = getimagesize($_FILES["logo"]["tmp_name"]);

                    $frmchk_details["logo"]["dimensions"] = array(
                        "width" => $logo_image_size[0],
                        "max_width" => 160,
                        "height" => $logo_image_size[1],
                        "max_height" => 160,
                        "error_message" => "The dimensions of the logo must be 160x160px"
                    );

                }

            }

            if (isset ($_FILES["banner"]) && is_uploaded_file($_FILES["banner"]["tmp_name"])) {
                $frmchk_details["banner"]  =array();
                $frmchk_details["banner"]["field"] = "banner";
                $frmchk_details["banner"]["type"] = $_FILES["banner"]["type"];
                if (in_array($_FILES["logo"]["type"], $allowed_image_mime_types)) {
                    $logo_image_size = getimagesize($_FILES["banner"]["tmp_name"]);

                    $frmchk_details["banner"]["dimensions"] = array(
                        "width" => $logo_image_size[0],
                        "max_width" => 600,
                        "height" => $logo_image_size[1],
                        "max_height" => 400,
                        "error_message" => "The dimensions of the banner must be 600x400px"
                    );

                }

            }

			include ('includes/npprocedure_frmchk_user.php'); /* Formchecker for user creation/edit */
            $_POST["confirmed_paypal_email"] = $confirmed_paypal_email;
            
			$banned_output = isset($_POST['email']) ?
                check_banned($_POST['email'], 2) : array("result" => 0);

			if ($banned_output['result'])
			{
				$template->set('banned_email_output', $banned_output['display']);
			}
			else if ($fv->is_error())
			{
                if (!file_exists('../uplimg/partner_logos/')) {
                    mkdir('../uplimg/partner_logos/', 0777);
                }

                if (!file_exists('../uplimg/partner_logos/temp/')) {
                    mkdir('../uplimg/partner_logos/temp/', 0777);
                }

                include_once('../includes/generate_image_thumbnail.php');
                if (isset($_SESSION["banner"]) && $_SESSION["banner"] == 'valid') {
                    if (isset ($_FILES["banner"]) && is_uploaded_file($_FILES["banner"]["tmp_name"])) {
                        $ext = pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION);
                        array_map(
                            'unlink',
                            glob('../uplimg/partner_logos/' . md5($_POST["name"] . 'banner') . '*')
                        );
                        $banner_file_name = '/uplimg/partner_logos/temp/' .
                            md5($_SESSION["probid_user_id"] . 'banner') . '.' . $ext;
                        $upload_banner = generate_image_thumbnail(
                            $_FILES["banner"]["tmp_name"], '..' . $banner_file_name, 600, 400
                        );
                        $template->set('banner_image', $banner_file_name);
                        unset($_SESSION["banner"]);
                    } elseif (isset ($_POST["video_url"]) && !empty($_POST["video_url"])) {
                        $template->set('banner_video_url', $_POST["video_url"]);
                    }
                } elseif(isset($_POST["valid_banner_image"]) && $_POST["valid_banner_image"]) {
                    $template->set('banner_image', $_POST["valid_banner_image"]);
                }
                if (isset($_SESSION["logo"]) && $_SESSION["logo"] == 'valid') {
                    if (isset ($_FILES["logo"]) && is_uploaded_file($_FILES["logo"]["tmp_name"])) {
                        $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
                        array_map(
                            'unlink',
                            glob('../uplimg/partner_logos/' . md5($_POST["name"] . 'logo') . '*')
                        );
                        $logo_file_name = '/uplimg/partner_logos/temp/' .
                            md5($_SESSION["probid_user_id"] . 'logo') . '.' . $ext;
                        $upload_logo = generate_image_thumbnail(
                            $_FILES["logo"]["tmp_name"], '..' . $logo_file_name, 160, 160
                        );
                        $template->set('logo_image', $logo_file_name);
                        unset($_SESSION["logo"]);
                    }
                } elseif(isset($_POST["valid_logo_image"]) && $_POST["valid_logo_image"]) {
                    $template->set('logo_image', $_POST["valid_logo_image"]);
                }
				$template->set('display_formcheck_errors', $fv->display_errors());

			}
			else
			{
				$form_submitted = TRUE;## PHP Pro Bid v6.00 atm we wont create any emails either until we decide how many ways of registration we have.


 
				(string) $register_success_message = null;

                include_once('../includes/generate_image_thumbnail.php');

                if (isset ($_FILES["logo"]) && is_uploaded_file($_FILES["logo"]["tmp_name"])) {
                    $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
                    $logo_file_name = '/uplimg/partner_logos/' . md5($_POST["username"] . 'logo') . '.' . $ext;
                    $upload_logo = generate_image_thumbnail(
                        $_FILES["logo"]["tmp_name"], '..' . $logo_file_name, 160, 160
                    );
                    array_map(
                        'unlink',
                        glob(
                            'rm ../uplimg/partner_logos/temp/' .
                                md5($_SESSION["probid_user_id"] . 'logo') .
                                '.*'
                        )
                    );
//                    exec('chmod 0777 ../uplimg/partner_logos/temp/' . md5($_SESSION["probid_user_id"] . 'logo') . '.*');
//                    exec('rm ../uplimg/partner_logos/temp/' . md5($_SESSION["probid_user_id"] . 'logo') . '.*');
//                    move_uploaded_file($_FILES["logo"]["tmp_name"], '..' . $logo_file_name);
                    $_POST["logo"] = $logo_file_name;
                } elseif (isset($_POST["valid_logo_image"]) && $_POST["valid_logo_image"]) {
                    $ext = pathinfo('..' . $_POST["valid_logo_image"], PATHINFO_EXTENSION);
                    $logo_file_name = '/uplimg/partner_logos/' . md5($_POST["username"] . 'logo') . '.' . $ext;
                    $upload_logo = generate_image_thumbnail(
                        '..' . $_POST["valid_logo_image"], '..' . $logo_file_name, 160, 160
                    );
                    $_POST["logo"] = $logo_file_name;
                    exec('chmod 0777 ../uplimg/partner_logos/temp/' . md5($_SESSION["probid_user_id"] . 'logo') . '.*');
                    exec('rm ../uplimg/partner_logos/temp/' . md5($_SESSION["probid_user_id"] . 'logo') . '.*');
                }

                if (isset ($_FILES["banner"]) && is_uploaded_file($_FILES["banner"]["tmp_name"])) {
                    $ext = pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION);
                    $banner_file_name = '/uplimg/partner_logos/' . md5($_POST["username"] . 'banner') . '.' . $ext;
                    $upload_banner = generate_image_thumbnail(
                        $_FILES["banner"]["tmp_name"], '..' . $banner_file_name, 600, 400
                    );
                    exec('chmod 0777 ../uplimg/partner_logos/temp/' . md5($_SESSION["probid_user_id"] . 'banner') . '.*');
                    exec('rm ../uplimg/partner_logos/temp/' . md5($_SESSION["probid_user_id"] . 'banner') . '.*');
//                    move_uploaded_file($_FILES["banner"]["tmp_name"], '..' . $banner_file_name);
                    $_POST["banner"] = $banner_file_name;
                } elseif (isset($_POST["valid_banner_image"]) && $_POST["valid_banner_image"]) {
                    $ext = pathinfo('..' . $_POST["valid_banner_image"], PATHINFO_EXTENSION);
                    $banner_file_name = '/uplimg/partner_logos/' . md5($_POST["username"] . 'banner') . '.' . $ext;
                    $upload_banner = generate_image_thumbnail(
                        '..' . $_POST["valid_banner_image"], '..' . $banner_file_name, 600, 400
                    );
                    $_POST["banner"] = $banner_file_name;
                    exec('chmod 0777 ../uplimg/partner_logos/temp/' . md5($_SESSION["probid_user_id"] . 'banner') . '.*');
                    exec('rm ../uplimg/partner_logos/temp/' . md5($_SESSION["probid_user_id"] . 'banner') . '.*');
                } elseif (isset ($_POST["video_url"]) && !empty($_POST["video_url"])) {
                    $_POST["banner"] = str_replace("watch?v=","embed/",$_POST["video_url"]);
                }

                $_POST["probid_user_id"] =
                    (isset($_SESSION["probid_user_id"]) && !empty($_SESSION["probid_user_id"])) ?
                        $_SESSION["probid_user_id"] : 0;

                $_POST["end_date"] = 0;
                if (isset($_POST["deadline_type_value"]) && $_POST["deadline_type_value"]) {
                    if ($_POST["deadline_type_value"] == "time_period")
                        $_POST["end_date"] = time() + ($_POST["time_period"] * 86400);
                    elseif ($_POST["deadline_type_value"] == "certain_date")
                        $_POST["end_date"] = strtotime($_POST["certain_date"]);
                } else {
                    $_POST["deadline_type_value"] = "time_period";
                }
                if (!$_POST["end_date"]) {
                    $_POST["end_date"] = time() + (30 * 86400);
                }

				$user_id = $user->insert($_POST);

                if ($user_id) {
                    $register_success_message = '<p align="center" class="contentfont">' . MSG_REGISTER_SUCCESS_TYPE3 . '</p>';
//                    header('Location: /campaigns,page,drafts,section,members_area');
                }

                if (isset($_POST["pitch_amoun"])) {

                    $pitch = new nppitch();

                    $pitch_data = array();

                    foreach ($_POST["pitch_amoun"] as $index => $value) {
                        $pitch_data[$index]["project_id"] = $user_id;
                        $pitch_data[$index]["amoun"] = $value;
                        $pitch_data[$index]["name"] = $_POST["pitch_name"][$index];
                        $pitch_data[$index]["description"] = $_POST["pitch_description"][$index];
                    }

                    $pitch->insert($pitch_data);
                }




				$template->set('register_success_header', header5(MSG_REGISTRATION_CONFIRMATION));## PHP Pro Bid v6.00 add signup fee procedure here.
				
				$signup_fee->setts = &$setts;

				// voucher settings
				(array) $voucher_result = null;
				if (!empty($_POST['voucher_value']))
				{
					## voucher is deducted
					$voucher_result = $voucher->check_voucher($_POST['voucher_value'], 'signup', true);
				}
				
				(array) $signup_result = null;
				

//				if ($signup_result['amount'])
//				{
//
//				}
//				else if ($setts['signup_settings'] == 1)
//				{
//					// email confirmation
//
//
//					$sql_update_user = $db->query("UPDATE " . NPDB_PREFIX . "users SET
//					active=1, approved=0, payment_status='confirmed' WHERE user_id=" . $user_id);
//
//					$register_success_message = '<p align="center" class="contentfont">' . MSG_REGISTER_SUCCESS_TYPE1 . '</p>';## PHP Pro Bid v6.00 include registration confirmation email
//					$mail_input_id = $user_id;
//					include('language/' . $setts['site_lang'] . '/mails/npregister_confirm_user_notification.php');
//				}
//				else if ($setts['signup_settings'] == 2)
//				{
//					// admin approval
//					$sql_update_user = $db->query("UPDATE " . NPDB_PREFIX . "users SET
//					active=1, approved=0, payment_status='confirmed' WHERE user_id=" . $user_id);
//
//					$register_success_message = '<p align="center" class="contentfont">' . MSG_REGISTER_SUCCESS_TYPE2 . '</p>';## PHP Pro Bid v6.00 notify user & admin that user approval is required
//					$mail_input_id = $user_id;
//					include('language/' . $setts['site_lang'] . '/npmails/npregister_approval_user_notification.php');
//					include('language/' . $setts['site_lang'] . '/npmails/npregister_approval_admin_notification.php');
//				}
//				else
//				{
//					// instant activation
//					$sql_update_user = $db->query("UPDATE " . NPDB_PREFIX . "users SET
//					active=1, approved=1, payment_status='confirmed', mail_activated=1 WHERE user_id=" . $user_id);
//
//					$register_success_message = '<p align="center" class="contentfont">' . MSG_REGISTER_SUCCESS_TYPE0 . '</p>';## PHP Pro Bid v6.00 include registration success email
//					$mail_input_id = $user_id;
//					include('language/' . $setts['site_lang'] . '/mails/npregister_success_no_fee_user_notification.php');
//				}

                if (!headers_sent())
                    header('Location: /campaigns,page,drafts,section,members_area');
                else {
                    echo '<script type="text/javascript">';
                    echo 'window.location.href="/campaigns,page,drafts,section,members_area";';
                    echo '</script>';
                    echo '<noscript>';
                    echo '<meta http-equiv="refresh" content="0;url=/campaigns,page,drafts,section,members_area" />';
                    echo '</noscript>';
                }

//                header('Location: /campaigns,page,drafts,section,members_area');

				$template->set('register_success_message', $register_success_message);

				$template_output .= $template->process('npregister_success.tpl.php');
			}
		}

		if (!$form_submitted)
		{
			$template->set('register_post_url', 'npregister.php');
			$template->set('proceed_button', GMSG_REGISTER_BTN);

            if (isset($_POST) && count($_POST)) {
                $template->set('user_details', $_POST);
            } else {
                $u_details = $db->fetch_array($db->query("SELECT * FROM bl2_users WHERE id='" . intval($session->value('user_id')) . "';"));

                preg_match("/\\(([\\d]+)\\)(.*)/uis", $u_details['phone'], $u_phone);
                // check if phone number not recognized - pulls empty array.
//                if (!count($u_phone)) $u_phone = array(1=>'', 2=>'');

                $user_details = array(
                    'name'      => $u_details['first_name'] ." ". $u_details['last_name'],
                    'address'   => $u_details['address'],
                    'city'      => $u_details['city'],
                    'zip_code'  => $u_details['postal_code'],
                    'phone'     => $u_details['phone'],
                    'deadline_type_value' => 'time_period',
//                    'phone_a'   => $u_phone[1],
//                    'phone_b'   => $u_phone[2],
                    'tax_company_name'  => $u_details['organization'],
                    'pg_paypal_email'   => $u_details['pg_paypal_email'],
                    'pg_paypal_first_name'  => $u_details['pg_paypal_first_name'],
                    'pg_paypal_last_name'   => $u_details['pg_paypal_last_name']
                );
                $template->set('user_details', $user_details);
            }

			$post_country = (isset($_POST['country']) && $_POST['country']) ? $_POST['country'] : $db->get_sql_field("SELECT c.id FROM " . DB_PREFIX . "countries c WHERE
				c.parent_id=0 ORDER BY c.country_order ASC, c.name ASC LIMIT 1", 'id');

            if ( !count($_POST) && intval($u_details['country']) ) {
                $post_country = intval($u_details['country']);
            }

			$template->set('country_dropdown', $tax->countries_dropdown('country', $post_country));

            $template->set("project_category",getProjectCategoryList());

            if (isset($_POST['state']))
                $pState = $_POST['state'];
            else {
                $pState = $u_details['state'];
            }

			$template->set('state_box', $tax->states_box('state', $pState, $post_country));

#			$template->set('birthdate_box', $user->birthdate_box($_POST));		

			$custom_sections_table = $user->display_sections($_POST, $page_handle);

			$template->set('custom_sections_table', $custom_sections_table);

			$session->set('pin_value', md5(rand(2,99999999)));
			$generated_pin = generate_pin($session->value('pin_value'));

			$pin_image_output = show_pin_image($session->value('pin_value'), $generated_pin, '../');

			$template->set('pin_image_output', $pin_image_output);
			$template->set('generated_pin', $generated_pin);

			// voucher settings-deleted mw 2-7-2011
			
#		$template->set('display_direct_payment_methods', $user->direct_payment_methods_edit($_POST));
#			
#			$template->set('signup_voucher_box', voucher_form('signup', $_POST['voucher_value']));
#			
			$template->set('registration_terms_box', terms_box('registration', (isset($_POST['agree_terms']))?$_POST['agree_terms']:''));

			$template_output .= $template->process('npregister.tpl.php');
		}
	}
	include_once ('npglobal_footer.php');

	echo $template_output;
}



?>
