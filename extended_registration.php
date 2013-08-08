<?php


define ('IN_SITE', 1);
$GLOBALS['body_id'] = "members_area";

(string) $page_handle = 'extended_registration';

include_once ('includes/global.php');

include_once ('includes/class_formchecker.php');
include_once ('includes/class_custom_field.php');
include_once ('includes/class_item.php');
include_once ('includes/class_user.php');
include_once ('includes/class_fees.php');
include_once ('includes/functions_login.php');

    $custom_fld = new custom_field();
    $tax = new tax();

    $voucher = new item();
    $voucher->setts = &$setts;

    include_once ('global_header_interior.php');


        $template->set('imgarrowit', $imgarrowit);
        $template->set('header_registration_message', header5("Update User Account"));

        /* From here we load the custom registration sections */

        (string) $custom_sections_table = null;

        $user = new user();
        $user->setts = &$setts;## PHP Pro Bid v6.00 now we will save all post variables selected
        $user->save_vars($_POST);

        $form_submitted = FALSE;## PHP Pro Bid v6.00 if save button is pressed, proceed
        if ($_REQUEST['operation'] == 'submit')
        {
            define ('FRMCHK_USER', 1);
            (bool) $frmchk_user_edit = 0;
            $frmchk_details = $_POST;

            //include ('includes/procedure_frmchk_user.php'); /* Formchecker for user creation/edit */
            $fv = new formchecker;
            if ($frmchk_details['tax_account_type'] == 1)
            {
                $fv->check_box($frmchk_details['tax_company_name'], MSG_COMPANY_NAME, array('field_empty', 'field_html'));
            }
            if (!$frmchk_user_edit)
            {
                $fv->check_box($frmchk_details['phone_a'], MSG_PHONE_A, array('field_empty', 'field_html', 'is_phone'));
                $fv->check_box($frmchk_details['phone_b'], MSG_PHONE_B, array('field_empty', 'field_html', 'is_phone'));
            }
            else
            {
                $fv->check_box($frmchk_details['phone'], MSG_PHONE, array('field_empty', 'field_html', 'is_phone'));
            }
            if (!$frmchk_user_edit && IN_ADMIN != 1)
            {
                $frmchk_birthdate = ($setts['birthdate_type'] == 1) ? mktime(0, 0, 0, 1, 1, intval($frmchk_details['birthdate_year'])) : mktime(0, 0, 0, intval($frmchk_details['dob_month']), intval($frmchk_details['dob_day']), intval($frmchk_details['dob_year']));
                $frmchk_birthdate = time_difference(CURRENT_TIME, $frmchk_birthdate) / 31536000; // date in years

                if ($setts['birthdate_type'] == 1)
                {
                    $fv->check_box($frmchk_details['birthdate_year'], MSG_DATE_OF_BIRTH, 'field_empty');
                }
                else
                {
                    $fv->check_box($frmchk_details['dob_month'], MSG_BIRTH_MONTH, array('field_empty'));
                    $fv->check_box($frmchk_details['dob_day'], MSG_DAY_OF_BIRTH, array('field_empty'));
                    $fv->check_box($frmchk_details['dob_year'], MSG_YEAR_OF_BIRTH, array('field_empty'));

                }
                $fv->field_greater($frmchk_birthdate, $setts['min_reg_age'], GMSG_MIN_REG_AGE_A . $setts['min_reg_age'] . GMSG_MIN_REG_AGE_B);
            }
            if (!$frmchk_user_edit && IN_ADMIN != 1)
            {
                if ($layout['enable_reg_terms'])
                {
                    $fv->field_checked($frmchk_details['agree_terms'], GMSG_AGREE_TO_REG_TERMS);
                }
            }

            if ($fv->is_error())
            {
                $template->set('display_formcheck_errors', $fv->display_errors());

            }
            else
            {
                $form_submitted = TRUE;## PHP Pro Bid v6.00 atm we wont create any emails either until we decide how many ways of registration we have.
                (string) $register_success_message = "You have updated your account successfully";

                $_POST["extended_registration"] = true;
                $user_id = $user->extended_update_bl2_users(
                    $session->value('user_id'), $_POST, $page_handle
                );

                header('Location: new_item,option,sell_item');
//                $user_id = $user->extended_update($session->value('user_id'), $_POST, $page_handle);

                $template->set('register_success_header', header5("Update User Account"));## PHP Pro Bid v6.00 add signup fee procedure here.
		
                $session->set('extended_registration', TRUE);

                $template->set('register_success_message', $register_success_message);

                $template_output .= $template->process('register_success.tpl.php');
            }
        }

        if (!$form_submitted)
        {
            $template->set('register_post_url', 'extended_registration.php');
            $template->set('proceed_button', GMSG_REGISTER_BTN);

            $user_data = $user->get_user_data($session->value('user_id'));

            if ($user_data) {
                $phone = explode(')', $user_data["phone"]);
                $user_data["phone_b"] = (is_array($phone) && isset($phone[1])) ? $phone[1] : '';
                $user_data["phone_a"] = (is_array($phone) && isset($phone[0])) ?
                    str_replace('(', '', $phone[0]) : '';
                $birth_date = explode('-', $user_data["birthdate"]);
                $user_data["dob_year"] = (is_array($birth_date) && isset($birth_date[0])) ?
                    $birth_date[0] : '';
                $user_data["dob_month"] = (is_array($birth_date) && isset($birth_date[1])) ?
                    $birth_date[1] : '';
                $user_data["dob_day"] = (is_array($birth_date) && isset($birth_date[2])) ?
                    $birth_date[2] : '';
            } else {
                $user_data = $_POST;
            }
//            echo '<pre>';
//            var_dump($user_data);
//            echo '</pre>';


            $template->set('user_details', $user_data);

            $template->set('birthdate_box', $user->birthdate_box($user_data));
//            $template->set('birthdate_box', $user->birthdate_box($_POST));

            $custom_sections_table = $user->display_sections($_POST, $page_handle);

            $template->set('custom_sections_table', $custom_sections_table);

			if (!empty($_POST['voucher_value']))
			{
				$voucher_details = $voucher->check_voucher($_POST['voucher_value'], 'signup');

				$template->set('check_voucher_message', $voucher_details['display']);
			}
			$template->set('display_direct_payment_methods', $user->direct_payment_data($user_data));
//			$template->set('display_direct_payment_methods', $user->direct_payment_methods_edit($_POST));

			$template->set('signup_voucher_box', voucher_form('signup', $_POST['voucher_value']));

			$template->set('registration_terms_box', terms_box('registration', $_POST['agree_terms']));


            $template_output .= $template->process('extended_register.tpl.php');
        }

    include_once ('global_footer.php');

    echo $template_output;
?>
