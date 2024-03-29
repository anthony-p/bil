<?
include_once(__DIR__ . '/../language/english/site.lang.php');


if ( !defined('FRMCHK_USER') ) { die("Access Denied"); }

$fv = new npformchecker($frmchk_details["active"]);

if (isset($frmchk_details['tax_account_type']) && $frmchk_details['tax_account_type'] == 1)
{
	$fv->check_box($frmchk_details['tax_company_name'], MSG_COMPANY_NAME, array('field_empty', 'field_html'));
}

if (isset($frmchk_details['keep_alive']) && $frmchk_details['keep_alive'] == 1)
{
	$fv->check_box($frmchk_details['keep_alive_days'], MSG_KEEP_ALIVE_DAYS, array('field_empty', 'field_html'));
}

$fv->check_box($frmchk_details['name'], MSG_FULL_NAME, array('field_empty', 'field_html'));
$fv->check_box($frmchk_details['address'], MSG_ADDRESS, array('field_empty', 'field_html'));
$fv->check_box($frmchk_details['city'], MSG_CITY, array('field_empty', 'field_html'));
$fv->check_box($frmchk_details['country'], MSG_COUNTRY, array('field_empty'));
$fv->check_box($frmchk_details['state'], MSG_STATE, array('field_empty', 'field_html'));
$fv->check_box($frmchk_details['zip_code'], MSG_ZIP_CODE, array('field_empty', 'field_html'));

if (isset($frmchk_details["deadline_type_value"])) {
    if ($frmchk_details["deadline_type_value"] == "time_period") {
        $fv->check_box(
            $frmchk_details['time_period'],
            MSG_DEADLINE_TIME_PERIOD,
            array('field_empty', 'field_integer_not_null')
        );
    } elseif ($frmchk_details["deadline_type_value"] == "certain_date") {
        $fv->check_box($frmchk_details['certain_date'], MSG_DEADLINE_CERTAIN_DATE, array('field_empty'));
    }
}

if (isset($frmchk_details["url"]) && $frmchk_details["url"])
    $fv->check_box($frmchk_details['url'], AMSG_URL, array('field_url'));

if (isset($frmchk_details["facebook_url"]) && $frmchk_details["facebook_url"])
    $fv->check_box($frmchk_details['facebook_url'], AMSG_FACEBOOK_URL, array('field_facebook_url'));

if (isset($frmchk_details["twitter_url"]) && $frmchk_details["twitter_url"])
    $fv->check_box($frmchk_details['twitter_url'], AMSG_TWITTER_URL, array('field_twitter_url'));

if (isset($frmchk_details["logo"]) && $frmchk_details["logo"])
    $fv->check_box($frmchk_details['logo'], AMSG_LOGO, array('field_image'));

if (isset($frmchk_details["banner"]) && $frmchk_details["banner"])
    $fv->check_box($frmchk_details['banner'], MSG_BANNER, array('field_image'));

//$confirmed_campaign_paypal_email = true;
$confirmed_campaign_paypal_email = false;
if ((isset($frmchk_details["pg_paypal_email"]) && $frmchk_details["pg_paypal_email"]) ||
    (isset($frmchk_details["pg_paypal_first_name"]) && $frmchk_details["pg_paypal_first_name"]) ||
    (isset($frmchk_details["pg_paypal_last_name"]) && $frmchk_details["pg_paypal_last_name"]) ||
    (isset($frmchk_details["active"]) && ($frmchk_details["active"] === "1" || $frmchk_details["active"] === 1))) {
    $confirmed_campaign_paypal_email = $fv->check_box($frmchk_details['pg_paypal_email'], MSG_PG_PAYPAL_EMAIL_ADDRESS, array('field_empty', 'is_paypal_email_address'));
    $fv->check_box($frmchk_details['pg_paypal_first_name'], MSG_PG_PAYPAL_FIRST_NAME, array('field_empty'));
    $fv->check_box($frmchk_details['pg_paypal_last_name'], MSG_PG_PAYPAL_LAST_NAME, array('field_empty'));
}

if ((isset($frmchk_details["username"]) && $frmchk_details["username"]) ||
    (isset($frmchk_details["active"]) &&
        ($frmchk_details["active"] === "1" || $frmchk_details["active"] === 1) &&
        (isset($frmchk_details["username"]) && $frmchk_details["username"]))) {
    $fv->check_box(
        $frmchk_details['username'],
        MSG_ENTER_PROJECTURL,
        array('field_empty', 'not_url', 'field_alphanumeric')
    );
}

/*if (isset($frmchk_details["pitch_amoun"])) {
    foreach ($frmchk_details["pitch_amoun"] as $index => $pitch_amount) {
//        var_dump($index);
//        var_dump($index);
//        var_dump($pitch_amount);
//        var_dump($frmchk_details["pitch_name"][$index]);
//        var_dump($frmchk_details["pitch_description"][$index]);
//        echo '<pre>';
//        var_dump($_POST);
//        var_dump($frmchk_details);
//        echo '</pre>';
        $fv->check_box($pitch_amount, MSG_PITCH_AMOUNT, array('field_empty', 'field_number'));
        $fv->check_box($frmchk_details["pitch_name"][$index], MSG_PITCH_NAME, array('field_empty'));
        $fv->check_box($frmchk_details["pitch_description"][$index], MSG_PITCH_DESCRIPTION, array('field_empty'));
    }
}*/


//var_dump($frmchk_details['founddrasing_goal']);
//echo '<br>';


//$fv->check_box($frmchk_details['username'], MSG_ENTER_PROJECTURL, array('field_empty', 'not_url'));
//$fv->check_box($frmchk_details['campaign_basic'], MSG_CREATE_PROJECT_CAMPAIGN_BASIC, array('field_empty', 'field_html'));
$fv->check_box($frmchk_details['project_title'], MSG_CREATE_PROJECT_TITLE, array('field_empty', 'field_html'));
$fv->check_box($frmchk_details['project_short_description'], MSG_CREATE_PROJECT_SHORT_DESCRIPTION, array('field_empty', 'field_html'));
$fv->check_box($frmchk_details['founddrasing_goal'], MSG_CREATE_PROJECT_QUESTION_FOUNDRAISING_GOAL, array('field_empty', 'field_number'));
/*$fv->check_box($frmchk_details['funding_type'], MSG_FOUNDING_TYPE, array('field_empty'));*/
$fv->check_box($frmchk_details['deadline_type_value'], MSG_DEADLINE, array('field_empty'));
/*$fv->check_box($frmchk_details['pitch_text'], MSG_PITCH_TEXT, array('field_empty', 'field_html'));*/

$fv->check_box($frmchk_details['phone'], MSG_PHONE, array('field_empty', 'field_html'));
//if (!$frmchk_user_edit)
//{
//	$fv->check_box($frmchk_details['phone_a'], MSG_PHONE_A, array('field_empty', 'field_html', 'is_phone'));
//	$fv->check_box($frmchk_details['phone_b'], MSG_PHONE_B, array('field_empty', 'field_html', 'is_phone'));
//}
//else
//{
//	$fv->check_box($frmchk_details['phone'], MSG_PHONE, array('field_empty', 'field_html', 'is_phone'));
//}



//if (!$frmchk_user_edit && IN_ADMIN != 1)

//{

//#	$frmchk_birthdate = ($setts['birthdate_type'] == 1) ? mktime(0, 0, 0, 1, 1, intval($frmchk_details['birthdate_year'])) : mktime(0, 0, 0, intval($frmchk_details['dob_month']), intval($frmchk_details['dob_day']), intval($frmchk_details['dob_year']));

//#	$frmchk_birthdate = time_difference(CURRENT_TIME, $frmchk_birthdate) / 31536000; // date in years

//

//	if ($setts['birthdate_type'] == 1)

//	{

//#		$fv->check_box($frmchk_details['birthdate_year'], MSG_DATE_OF_BIRTH, 'field_empty');

//	}

//	else

//	{

//#		$fv->check_box($frmchk_details['dob_month'], MSG_BIRTH_MONTH, array('field_empty'));

//#		$fv->check_box($frmchk_details['dob_day'], MSG_DAY_OF_BIRTH, array('field_empty'));

//#		$fv->check_box($frmchk_details['dob_year'], MSG_YEAR_OF_BIRTH, array('field_empty'));

//

//	}

//#	$fv->field_greater($frmchk_birthdate, $setts['min_reg_age'], GMSG_MIN_REG_AGE_A . $setts['min_reg_age'] . GMSG_MIN_REG_AGE_B);

//}

//

//## check for blocked domains

//$email_split = explode('@', $frmchk_details['email']);

//$email_domain = $email_split[1];

//

//$is_blocked_domain = $db->count_rows('blocked_domains', "WHERE domain='" . $email_domain . "'");

//

//if ($is_blocked_domain)

//{

//	$fv->error_list[] = array('value' => $email_domain, 'msg' => MSG_FRMCHK_EMAIL_DOMAIN_BLOCKED . ' (<b>' . $email_domain . '</b>)');

//}



//if (!$frmchk_user_edit)

//{

//	$fv->field_duplicate_fulltext('users', 'email', $frmchk_details['email'], MSG_FRMCHK_DUPLICATE_EMAIL);

//}

//else

//{

//	$fv->field_duplicate_fulltext('users', 'email', $frmchk_details['email'], MSG_FRMCHK_DUPLICATE_EMAIL, 'user_id', $frmchk_details['user_id']);

//}



//$fv->check_box($frmchk_details['email'], MSG_EMAIL_ADDRESS, array('is_email_address', 'pass_confirm'), $_POST['email_check'], MSG_RETYPE_EMAIL);



## first the standard boxes error checking

//if (!$frmchk_user_edit)

//{

//	$fv->field_duplicate_fulltext('users', 'username', $frmchk_details['username'], MSG_FRMCHK_DUPLICATE_USERNAME);

//	$fv->check_box($frmchk_details['username'], MSG_CREATE_USERNAME, array('field_empty', 'field_html'));

//}



/*if (!$frmchk_user_edit || !empty($frmchk_details['password']))

{

	$fv->check_box($frmchk_details['password'], MSG_CREATE_PASS, array('within_length', 'pass_confirm'), $_POST['password2'], MSG_VERIFY_PASS);

}*/



## now check the custom boxes

//$fv->check_custom_fields($frmchk_details);

//

//if (!$frmchk_user_edit && IN_ADMIN != 1)

//{

//	$fv->check_box($frmchk_details['pin_value'], MSG_CONF_PIN, array('field_equal'), $frmchk_details['generated_pin'], MSG_REG_PIN);

//	if ($layout['enable_reg_terms'])

//	{

//		$fv->field_checked($frmchk_details['agree_terms'], GMSG_AGREE_TO_REG_TERMS);

//	}

//}

if ($campaign['cfc'] == 1) {
    $cfc = mysql_fetch_assoc($db->query("SELECT user_id, active FROM np_users WHERE cfc = 1 AND active = 1"));
    if ($campaign['active'] == 1 && $_POST['active'] != 1)
        $fv->error_list[] = array('value' => 1, 'msg' => "You can't change status until the end of campaign");
    if (isset($cfc['user_id']) && ($cfc['user_id'] != $campaign['user_id']))
        $fv->error_list[] = array('value' => 1, 'msg' => "CFC campaign already exists.");
    if ($cfc['active'] != 1 && strtotime('today') != strtotime('first day of' . date('F Y', time()))) 
        $fv->error_list[] = array('value' => strtotime('today'), 'msg' => "The CFC campaign must be started on the 1st of the month only");
    if (isset($_POST['time_period']) && (strtotime('today + ' . $_POST['time_period'] . ' days') != strtotime('last day of ' . date('F Y', time())))) {
        $fv->error_list[] = array('value' => strtotime('today'), 'msg' => "Your CFC campaign ends not on the last day of the month");
    }
    if (isset($_POST['certain_date']) && (strtotime($_POST['certain_date']) != strtotime('last day of ' . date('F Y', time())))) {
        $fv->error_list[] = array('value' => strtotime('today'), 'msg' => "Your CFC campaign ends not on the last day of the month");
    }
}

if ($_POST['username'] != '') {
    $query_result = $db->query("SELECT username FROM np_users WHERE user_id <> " . $campaign['user_id']);
    while ($url = mysql_fetch_assoc($query_result)) {
        $reserved_urls[] = $url['username'];
    }
    if (in_array($_POST['username'], $reserved_urls)) {
        $fv->error_list[] = array('value' => $_POST['username'], 'msg' => "Selected URL is already in use.");
    }
}

?>

