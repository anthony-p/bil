<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

class user extends custom_field
{
	var $item;

    /* Change Method by Lilian Codreanu*/
	function create_salt($length = 10)
	{
        $validCharacters = "0123456789ABCDEFGHIJKLMNOPQRSTUXYVWZabcdefghijklmnopqrstuxyvwz";
        $validCharNumber = strlen($validCharacters);
        $result = "";
        for ($i = 0; $i < $length; $i++) {
            $index = mt_rand(0, $validCharNumber - 1);
            $result .= $validCharacters[$index];
        }
        return $result;

	}

    function insert_magento($user_details, $phone)
    {
        try {
            global $coupon_http_username;
            global $coupon_http_password;
            global $coupon_url;
            global $coupon_soap_username;
            global $coupon_soap_password;
            $woptions['soap_version'] = SOAP_1_2;
            $woptions['login'] = $coupon_http_username;
            $woptions['password'] = $coupon_http_password;

            $proxy = new SoapClient($coupon_url."/index.php/api/soap/?wsdl",$woptions);
            $sessionId = $proxy->login($coupon_soap_username, $coupon_soap_password);

            $newCustomer = array(
                'firstname'     => $user_details['fname'],
                'lastname'      => $user_details['lname'],
                'email'         => $user_details['email'],
                'username'      => $user_details['username'],
                'password_hash' => md5($user_details['password']),
                'store_id'   => 1,
                'website_id' => 1,
                'npuser' =>  $user_details['orgname'],
                'npname' => $user_details['npname']
            );

            $newCustomerId = $proxy->call($sessionId, 'customer.create', array($newCustomer));

            $country_id = '';
            $region_id = '';

            $country_info = $this->get_sql_row("SELECT country_iso_code FROM
                                    			proads_countries WHERE id=" . $user_details['country']);
            if(isset($country_info['country_iso_code'])){
                $country_id = $country_info['country_iso_code'];
            }

            $region_info = $this->get_sql_row("SELECT name FROM
                        			" . DB_PREFIX . "countries WHERE id=" . $user_details['state']);
            if(isset($region_info['name'])){
                $regions = $proxy->call($sessionId, 'region.list', strtoupper($country_id));
                foreach($regions as $region)
                {
                    if($region['name'] == $region_info['name'])
                        $region_id = $region['region_id'];
                }
            }else{
                $region_info['name'] = '';
            }

            $newCustomerAddress = array(
                'firstname'  => $user_details['fname'],
                'lastname'   => $user_details['lname'],
                'country_id' => strtoupper($country_id),
                'region_id'  => $region_id,
                'region'     => $region_info['name'],
                'city'       => $user_details['city'],
                'street'     => $user_details['address'],
                'telephone'  => $phone,
                'postcode'   => $user_details['zip_code'],

                'is_default_billing'  => true,
                'is_default_shipping' => true
            );

            $newAddressId = $proxy->call($sessionId, 'customer_address.create', array($newCustomerId, $newCustomerAddress));
            if($user_details['newsletter']){
                $newsletter = $proxy->call($sessionId, 'bcustomer_authentication.subscribe', array(array('email' => $user_details['email'], 'city' => $user_details['city'], 'is_subscribed' => $user_details['newsletter'])));
            }

            //$newsletter = $proxy->call($sessionId, 'bcustomer_authentication.subscribe', array(array('customer_id' => $newCustomerId, 'is_subscribed' => $user_details['newsletter'])));
        } catch (Exception $e) {
            file_put_contents('magento.log', $e->getMessage());
        }

        return $newCustomerId;
    }

    /**
     * Escape String for MySQl Query
     * @param $data
     * @return array
     */
    function sqlEscapeString ($data)
    {
        $output = array();
        foreach ($data as $key => $value) {
            $output[$key] = mysql_real_escape_string($value);
        }

        return $output;
    }

    /**
     * @param $user_details
     * @return int
     */
    function register ($user_details)
    {

        $user_details = $this->sqlEscapeString($user_details);
        $salt = $this->create_salt();
        $password_hashed = password_hash($user_details['password'], $salt);
      
                        			
                   $this->query("INSERT INTO " . $db_name . "bl2_users
        (`id`, `first_name`, `last_name`, `email`, `password`, `salt`, `active`, `create_date`, `last_login`, `is_subscribe_news`)
         VALUES (NULL, '{$user_details['fname']}', '{$user_details['lname']}', '{$user_details['email']}', '$password_hashed', '$salt', '0', '".time()."', '".time()."', '{$user_details['newsletter']}');");

        $user_id = $this->insert_id();

        /* NewsLetter subscribe constant contact code removed*/

       

        return $user_id;
    }


	function insert ($user_details, $page_handle = 'register')
	{
        $salt = $this->create_salt();

		$password_hashed = password_hash($user_details['password'], $salt);

		$payment_mode = ($this->setts['account_mode_personal'] == 1) ? $this->setts['init_acc_type'] : $this->setts['account_mode'];
		$balance = ($payment_mode == 2) ? ((-1) * $this->setts['init_credit']) : 0;
		$max_credit = ($payment_mode == 2) ? $this->setts['max_credit'] : 0;

		$user_details = $this->rem_special_chars_array($user_details);

		$phone = '(' . $user_details['phone_a'] . ') ' . $user_details['phone_b'];

		if ($this->setts['birthdate_type'] == 1)
		{
			$birthdate = $user_details['birthdate_year'] . '-01-01'; // defaulted to jan 1st of the birthdate year.
			$birthdate_year = $user_details['birthdate_year'];
		}
		else
		{
			$birthdate = $user_details['dob_year'] . '-' . $user_details['dob_month'] . '-' . $user_details['dob_day'];
			$birthdate_year = $user_details['dob_year'];
		}
		
		$tax_apply_exempt = (!empty($user_details['tax_reg_number'])) ? 1 : 0;

        $full_name = $user_details['fname'] . " " . $user_details['lname'];

		$sql_insert_user = $this->query("INSERT INTO " . DB_PREFIX . "users
			(username, password, email, reg_date, payment_mode, balance, max_credit,
			salt,	tax_account_type, tax_company_name, tax_reg_number, tax_apply_exempt, 
			name, address, city, country, state, zip_code, phone, birthdate, birthdate_year, newsletter,
			pg_paypal_email, pg_worldpay_id, pg_checkout_id, pg_nochex_email, 
			pg_ikobo_username, pg_ikobo_password, pg_protx_username, pg_protx_password, 
			pg_authnet_username, pg_authnet_password, pg_mb_email, pg_paymate_merchant_id, 
			pg_gc_merchant_id, pg_gc_merchant_key, pg_amazon_access_key, pg_amazon_secret_key, pg_alertpay_id, pg_alertpay_securitycode,npuser_id,npname,affiliate) VALUES
			('" . $user_details['username'] . "', '" . $password_hashed . "',	'" . $user_details['email'] . "',
			" . CURRENT_TIME . ", " . $payment_mode . ",	'" . $balance . "', '" . $max_credit . "',
			'" . $salt . "', '" . $user_details['tax_account_type'] . "', '" . $user_details['tax_company_name'] . "',
			'" . $user_details['tax_reg_number'] . "', '" . $tax_apply_exempt . "', '" . $full_name  . "',
			'" . $user_details['address'] . "', '" . $user_details['city'] . "',
			'" . $user_details['country'] . "', '" . $user_details['state'] . "', '" . $user_details['zip_code'] . "',
			'" . $phone . "', '" . $birthdate . "', '" . $birthdate_year . "', '" . $user_details['newsletter'] . "', 
			'" . $user_details['pg_paypal_email'] . "', '" . $user_details['pg_worldpay_id'] . "', '" . $user_details['pg_checkout_id'] . "',
			'" . $user_details['pg_nochex_email'] . "', '" . $user_details['pg_ikobo_username'] . "',
			'" . $user_details['pg_ikobo_password'] . "', '" . $user_details['pg_protx_username'] . "',
			'" . $user_details['pg_protx_password'] . "', '" . $user_details['pg_authnet_username'] . "',
			'" . $user_details['pg_authnet_password'] . "', '" . $user_details['pg_mb_email'] . "', 
			'" . $user_details['pg_paymate_merchant_id'] . "', '" . $user_details['pg_gc_merchant_id'] . "', 
			'" . $user_details['pg_gc_merchant_key'] . "', 
			'" . $user_details['pg_amazon_access_key'] . "', '" . $user_details['pg_amazon_secret_key'] . "', '" . $user_details['pg_alertpay_id'] . "', '" . 
			$user_details['pg_alertpay_securitycode']	. "', '" . 	$user_details['orgname']	. "', '" . 	$user_details['npname']	. "', '" . 	$user_details['affiliate']	. "')");

		$user_id = $this->insert_id();

        //@TODO Implement This in Step II
//        $magento_user_id = $this->insert_magento($user_details, $phone);

        if($user_id){
            $points_awarded = $this->get_sql_field("SELECT points_awarded FROM probid_user_activities WHERE activity_id=7", points_awarded);
            $this->query("INSERT INTO probid_user_points (user_id, activity_id, points_awarded, awarded_date)
                              VALUES ('$user_id', '7', '$points_awarded', date(now()))");
        }

		if ($tax_apply_exempt && IN_ADMIN != 1) ## if not in admin, notify admin of a tax exempt request.
		{
			$mail_input_id = $user_id;
			include('language/' . $this->setts['site_lang'] . '/mails/tax_apply_exempt_notification.php');
		}
		
		$this->insert_page_data($user_id, $page_handle, $user_details);
		
		
		
		if (isset($_POST['newsletter'])) { 	
		//begin constant contact api	
		include_once('includes/constantContacts/ConstantContact.php');
		$username = 'bringitlocal';
		$userpassword = 'fortyone1795xX';
		$apiKey = 'c870c529-2ce7-4a5c-873f-c6f95c99040b';
		$consumerSecret = 'ddd354126123477faea722be2546241e';
		//$Datastore = new CTCTDataStore();
		//$DatastoreUser = $Datastore->lookupUser($username);
		
		//if($DatastoreUser){
		try {
		    $ConstantContact = new ConstantContact('basic', $apiKey, $username, $userpassword);
		    $ContactLists = $ConstantContact->getLists();
		    
	        $Contact = new Contact();
	        $Contact->emailAddress = $user_details['email'];
	        $Contact->firstName = $user_details['fname'];
	        $Contact->lastName = $user_details['lname'];
			$Contact->city = $user_details['city'];
			$Contact->stateName = $user_details['state'];
			$Contact->postalCode = $user_details['zip_code'];
			$Contact->companyName = $user_details['npname'];
	       	$lists = array();
		    foreach($ContactLists['lists'] as $list){
		    	if ($list->name == 'Coupon newsletter subscription')
		        	$lists[] = $list->id;
		    }
			$Contact->lists = $lists;
		 	
			$contactSearch = $ConstantContact->searchContactsByEmail($user_details['email']);
			if(!$contactSearch){
	        	$NewContact = $ConstantContact->addContact($Contact);
			} 

		} catch (Exception $e) {
            	
       }
		//}
		
	}
		


		return $user_id;
	}

    function update_magento($user_details, $new_password)
    {
        try {
            $user_info = $this->get_sql_row("SELECT username FROM
            			" . DB_PREFIX . "users WHERE user_id=" . $user_details['user_id']);

            global $coupon_http_username;
            global $coupon_http_password;
            global $coupon_url;
            global $coupon_soap_username;
            global $coupon_soap_password;
            $woptions['soap_version'] = SOAP_1_2;
            $woptions['login'] = $coupon_http_username;
            $woptions['password'] = $coupon_http_password;

            $proxy = new SoapClient($coupon_url."/index.php/api/soap/?wsdl",$woptions);
            $sessionId = $proxy->login($coupon_soap_username, $coupon_soap_password);

            $list = $proxy->call($sessionId, 'customer.list', array(array('username' => $user_info['username'])));
            if(count($list))
            {
                $magento_customer_id = $list[0]['customer_id'];
                $updateCustomer = array(
                    'firstname'     => $user_details['fname'],
                    'lastname'      => $user_details['lname'],
                    'email'         => $user_details['email'],
					'email'         => $user_details['email'],
                    'npuser' =>  $user_details['orgname'],
                    'npname' => $user_details['npname']
                );
                if($new_password){
                    $updateCustomer = array(
                        'firstname'     => $user_details['fname'],
                        'lastname'      => $user_details['lname'],
                        'email'         => $user_details['email'],
                        'password_hash' => md5($user_details['password']),
                        'npuser' =>  $user_details['orgname'],
                        'npname' => $user_details['npname']
                    );
                }
                $resut = $proxy->call($sessionId, 'customer.update', array($magento_customer_id, $updateCustomer));

                $country_id = '';
                $region_id = '';

                $country_info = $this->get_sql_row("SELECT country_iso_code FROM
                                    			proads_countries WHERE id=" . $user_details['country']);
                if(isset($country_info['country_iso_code'])){
                    $country_id = $country_info['country_iso_code'];
                }

                $region_info = $this->get_sql_row("SELECT name FROM
                        			" . DB_PREFIX . "countries WHERE id=" . $user_details['state']);
                if(isset($region_info['name'])){
                    $regions = $proxy->call($sessionId, 'region.list', strtoupper($country_id));
                    foreach($regions as $region)
                    {
                        if($region['name'] == $region_info['name'])
                            $region_id = $region['region_id'];
                    }
                }else{
                    $region_info['name'] = '';
                }

                $addresses = $proxy->call($sessionId, 'customer_address.list',$magento_customer_id);

                $updateCustomerAddress = array(
                    'firstname'  => $user_details['fname'],
                    'lastname'   => $user_details['lname'],
                    'country_id' => strtoupper($country_id),
                    'region_id'  => $region_id,
                    'region'     => $region_info['name'],
                    'city'       => $user_details['city'],
                    'street'     => $user_details['address'],
                    'telephone'  => $user_details['phone'],
                    'postcode'   => $user_details['zip_code'],

                    'is_default_billing'  => true,
                    'is_default_shipping' => true
                );

                $proxy->call($sessionId, 'customer_address.update', array($addresses[0]['customer_address_id'], $updateCustomerAddress));
                $newsletter = $proxy->call($sessionId, 'bcustomer_authentication.subscribe', array(array('email' => $user_details['email'], 'city' => $user_details['city'], 'is_subscribed' => $user_details['newsletter'])));
            }
        } catch (Exception $e) {
            file_put_contents('/tmp/magento.log', $e->getMessage());
        }

        return $resut;
    }

    function extended_update($user_id, $user_details, $page_handle)
    {
        try{

            $user_details = $this->rem_special_chars_array($user_details);
            $phone = '(' . $user_details['phone_a'] . ') ' . $user_details['phone_b'];

            if ($this->setts['birthdate_type'] == 1)
            {
                $birthdate = $user_details['birthdate_year'] . '-01-01'; // defaulted to jan 1st of the birthdate year.
                $birthdate_year = $user_details['birthdate_year'];
            }
            else
            {
                $birthdate = $user_details['dob_year'] . '-' . $user_details['dob_month'] . '-' . $user_details['dob_day'];
                $birthdate_year = $user_details['dob_year'];
            }

            $tax_apply_exempt = (!empty($user_details['tax_reg_number'])) ? 1 : 0;
            $sql_update_query = "UPDATE " . DB_PREFIX . "users SET
			phone='" . $phone . "',
			birthdate='" . $birthdate . "',
			birthdate_year='" . $birthdate_year . "',
			tax_account_type='" . $user_details['tax_account_type'] . "',
			tax_company_name='" . $user_details['tax_company_name'] . "',
			tax_reg_number='" . $user_details['tax_reg_number'] . "',
			pg_paypal_email = '" . $user_details['pg_paypal_email']."'";

            //update magento phone
            $user_info = $this->get_sql_row("SELECT username FROM
            			" . DB_PREFIX . "users WHERE user_id=" . $user_id);

            global $coupon_http_username;
            global $coupon_http_password;
            global $coupon_url;
            global $coupon_soap_username;
            global $coupon_soap_password;
            $woptions['soap_version'] = SOAP_1_2;
            $woptions['login'] = $coupon_http_username;
            $woptions['password'] = $coupon_http_password;

            $proxy = new SoapClient($coupon_url."/index.php/api/soap/?wsdl",$woptions);
            $sessionId = $proxy->login($coupon_soap_username, $coupon_soap_password);

            $list = $proxy->call($sessionId, 'customer.list', array(array('username' => $user_info['username'])));
            if(count($list))
            {
                $magento_customer_id = $list[0]['customer_id'];
                $addresses = $proxy->call($sessionId, 'customer_address.list',$magento_customer_id);

                $updateCustomerAddress = array(
                    'telephone'  => $phone
                );

                $proxy->call($sessionId, 'customer_address.update', array($addresses[0]['customer_address_id'], $updateCustomerAddress));
            }


            $user_old = $this->get_sql_row("SELECT balance, payment_mode, tax_apply_exempt FROM
			" . DB_PREFIX . "users WHERE user_id=" . $user_id);

            if (!$user_old['tax_apply_exempt'] && !empty($user_details['tax_reg_number']))
            {
                $sql_update_query .= ", tax_apply_exempt=1";
            }

            $sql_update_query .= " WHERE user_id=" . $user_id;

            $sql_update_user = $this->query($sql_update_query);

            if (!$user_old['tax_apply_exempt'] && !empty($user_details['tax_reg_number']) && IN_ADMIN != 1)
            {
                $mail_input_id = $user_id;
                include('language/' . $this->setts['site_lang'] . '/mails/tax_apply_exempt_notification.php');
            }
            //echo $sql_update_query; die;
            $this->update_page_data($user_id, $page_handle, $user_details);
        } catch (Exception $e) {
            file_put_contents('user.log', $e->getMessage());
        }
    }

    /**
     * @param $user_id
     * @param $user_details
     * @param $page_handle
     *
     * @TODO check if this function is needed because it is a duplication of
     * extended_update function with the only difference that data are saved in
     * bl2_users table but not in probid_users
     */
    function extended_update_bl2_users($user_id, $user_details, $page_handle)
    {
        try{
//            var_dump($user_details); exit;

            $prefix = "bl2_";

            $user_details = $this->rem_special_chars_array($user_details);
            $phone = '(' . $user_details['phone_a'] . ') ' . $user_details['phone_b'];

//            if ($this->setts['birthdate_type'] == 1)
//            {
//                $birthdate = $user_details['birthdate_year'] . '-01-01'; // defaulted to jan 1st of the birthdate year.
//                $birthdate_year = $user_details['birthdate_year'];
//            }
//            else
//            {
//                $birthdate = $user_details['dob_year'] . '-' . $user_details['dob_month'] . '-' . $user_details['dob_day'];
//                $birthdate_year = $user_details['dob_year'];
//            }

            $birthdate = $user_details["dob_year"] . "-" .
                $user_details["dob_month"] . "-" .
                $user_details["dob_day"];
            $birthdate_year = $user_details["dob_year"];

            $tax_apply_exempt = (!empty($user_details['tax_reg_number'])) ? 1 : 0;
            $sql_update_query = "UPDATE " . $prefix . "users SET
			phone='" . $phone . "',
			birthdate='" . $birthdate . "',
			birthdate_year='" . $birthdate_year . "',
			tax_account_type='" . $user_details['tax_account_type'] . "',
			tax_company_name='" . $user_details['tax_company_name'] . "',
			tax_reg_number='" . $user_details['tax_reg_number'] . "',
			extended_registration='" . $user_details['extended_registration'] . "',
			pg_paypal_first_name='" . $user_details['pg_paypal_first_name'] . "',
			pg_paypal_last_name='" . $user_details['pg_paypal_last_name'] . "',
			pg_paypal_email = '" . $user_details['pg_paypal_email']."'";

            //update magento phone
//            $user_info = $this->get_sql_row("SELECT username FROM
//            			" . $prefix . "users WHERE user_id=" . $user_id);

//            global $coupon_http_username;
//            global $coupon_http_password;
//            global $coupon_url;
//            global $coupon_soap_username;
//            global $coupon_soap_password;
//            $woptions['soap_version'] = SOAP_1_2;
//            $woptions['login'] = $coupon_http_username;
//            $woptions['password'] = $coupon_http_password;
//
//            $proxy = new SoapClient($coupon_url."/index.php/api/soap/?wsdl",$woptions);
//            $sessionId = $proxy->login($coupon_soap_username, $coupon_soap_password);
//
//            $list = $proxy->call($sessionId, 'customer.list', array(array('username' => $user_info['username'])));
//            if(count($list))
//            {
//                $magento_customer_id = $list[0]['customer_id'];
//                $addresses = $proxy->call($sessionId, 'customer_address.list',$magento_customer_id);
//
//                $updateCustomerAddress = array(
//                    'telephone'  => $phone
//                );
//
//                $proxy->call($sessionId, 'customer_address.update', array($addresses[0]['customer_address_id'], $updateCustomerAddress));
//            }
//
//
//            $user_old = $this->get_sql_row("SELECT balance, payment_mode, tax_apply_exempt FROM
//			" . $prefix . "users WHERE user_id=" . $user_id);
//
//            if (!$user_old['tax_apply_exempt'] && !empty($user_details['tax_reg_number']))
//            {
//                $sql_update_query .= ", tax_apply_exempt=1";
//            }

            $sql_update_query .= " WHERE id=" . $user_id;

            var_dump($sql_update_query);

            $sql_update_user = $this->query($sql_update_query);

//            if (!$user_old['tax_apply_exempt'] && !empty($user_details['tax_reg_number']) && IN_ADMIN != 1)
//            {
//                $mail_input_id = $user_id;
//                include('language/' . $this->setts['site_lang'] . '/mails/tax_apply_exempt_notification.php');
//            }
            //echo $sql_update_query; die;
            $this->update_page_data($user_id, $page_handle, $user_details);
        } catch (Exception $e) {
            file_put_contents('user.log', $e->getMessage());
        }
    }

    /**
     * @param $user_id
     * @param $user_details
     * @param $page_handle
     *
     * @TODO check if this function is needed because it is a duplication of
     * extended_update function with the only difference that data are saved in
     * bl2_users table but not in probid_users
     */
    function get_user_data($user_id)
    {
        try{
            $prefix = "bl2_";
            $user_info = $this->get_sql_row("SELECT * FROM
            			" . $prefix . "users WHERE id=" . $user_id);
            return $user_info ? $user_info : null;
        } catch (Exception $e) {
            file_put_contents('user.log', $e->getMessage());
        }
    }

	function update ($user_id, $user_details, $new_password = null, $page_handle = 'register', $admin_edit = false)
	{

        try{

		$user_details = $this->rem_special_chars_array($user_details);
        $full_name = $user_details['fname'] . " " . $user_details['lname'];
        $clickReport = 0;
        if(isset($user_details['clickreport'])) $clickReport=1;

        $sql_update_query = "UPDATE bl2_users SET
            first_name =  '{$user_details['fname']}',
            last_name =  '{$user_details['lname']}',
            email='{$user_details['email']}',
            phone =  '{$user_details['phone']}',
            is_subscribe_news='{$user_details['newsletter']}',
            city =  '{$user_details['city']}',
            tax_reg_number='{$user_details['tax_reg_number']}',
            address =  '{$user_details['address']}',
            pg_paypal_first_name =  '{$user_details['pg_paypal_first_name']}',
            pg_paypal_last_name =  '{$user_details['pg_paypal_last_name']}',
            pg_paypal_email = '{$user_details['pg_paypal_email']}'";
        /*
		$sql_update_query = "UPDATE " . DB_PREFIX . "users SET
			name='" . $full_name . "', address='" . $user_details['address'] . "',
			city='" . $user_details['city'] . "', country='" . $user_details['country'] . "',
			state='" . $user_details['state'] . "', zip_code='" . $user_details['zip_code'] . "',
			phone='" . $user_details['phone'] . "', email='" . $user_details['email'] . "',
			tax_account_type='" . $user_details['tax_account_type'] . "',
			tax_company_name='" . $user_details['tax_company_name'] . "',
			tax_reg_number='" . $user_details['tax_reg_number'] . "',
			newsletter='" . $user_details['newsletter'] . "',
			pg_paypal_email = '" . $user_details['pg_paypal_email'] . "',
			pg_worldpay_id = '" . $user_details['pg_worldpay_id'] . "',
			pg_checkout_id = '" . $user_details['pg_checkout_id'] . "',
			pg_nochex_email = '" . $user_details['pg_nochex_email'] . "',
			pg_ikobo_username = '" . $user_details['pg_ikobo_username'] . "',
			pg_ikobo_password = '" . $user_details['pg_ikobo_password'] . "',
			pg_protx_username = '" . $user_details['pg_protx_username'] . "',
			pg_protx_password = '" . $user_details['pg_protx_password'] . "',
			pg_authnet_username = '" . $user_details['pg_authnet_username'] . "',
			pg_authnet_password = '" . $user_details['pg_authnet_password'] . "',
			pg_mb_email = '" . $user_details['pg_mb_email'] . "',
			pg_paymate_merchant_id = '" . $user_details['pg_paymate_merchant_id'] . "',
			pg_gc_merchant_id = '" . $user_details['pg_gc_merchant_id'] . "',
			pg_gc_merchant_key = '" . $user_details['pg_gc_merchant_key'] . "',
			pg_amazon_access_key = '" . $user_details['pg_amazon_access_key'] . "',
			pg_amazon_secret_key = '" . $user_details['pg_amazon_secret_key'] . "',
			pg_alertpay_id = '" . $user_details['pg_alertpay_id'] . "',
			pg_alertpay_securitycode = '" . $user_details['pg_alertpay_securitycode'] . "',
			npuser_id = '" . $user_details['orgname'] . "',
			clickreport = '" . $clickReport . "',
			npname = '" . $user_details['npname'] . "'";
        */
        //return true/false
        $magento_updated = $this->update_magento($user_details, $new_password);

		$user_old = $this->get_sql_row("SELECT balance, payment_mode, tax_apply_exempt FROM
			" . DB_PREFIX . "users WHERE user_id=" . $user_id);

		if (!$user_old['tax_apply_exempt'] && !empty($user_details['tax_reg_number']))
		{
			$sql_update_query .= ", tax_apply_exempt=1";
		}

		if ($admin_edit)
		{
			$sql_update_query .= ", payment_mode='" . $user_details['payment_mode'] . "'";

			if ($user_old['payment_mode'] == 2)
			{
				$sql_update_query .= ", max_credit='" . $user_details['max_credit'] . "'";
				// We can change here the balance and max_credit values. If the balance is changed, we will also create a line in the invoices table
				$new_balance = $user_details['balance_type'] * $user_details['balance'];

				if ($user_old['balance'] != $new_balance)
				{
					$sql_update_query .= ", balance='" .  $new_balance . "'";

					// now we create the invoice row
					$invoice_amount = $new_balance - $user_old['balance'];

					$fee_name = GMSG_ADMIN_CREDIT_ADJUSTMENT . ' [ ' . (($invoice_amount>=0) ? GMSG_DEBIT : GMSG_CREDIT) . ' ] ' .
						((!empty($user_details['adjustment_reason'])) ? ' - ' . $this->rem_special_chars($user_details['adjustment_reason']) : '');

					$sql_insert_invoice = $this->query("INSERT INTO " . DB_PREFIX . "invoices
						(user_id, name, amount, invoice_date, current_balance, live_fee, credit_adjustment) VALUES
						('" . $user_id . "', '" . $fee_name . "', '" . $invoice_amount . "',
						'" . CURRENT_TIME . "', '" . $new_balance . "', '1', '1')");
				}
			}
		}

		if ($new_password)
		{
			$salt = $this->create_salt();
			$password_hashed = password_hash($new_password, $salt);
			$sql_update_query .= ", password='" . $password_hashed . "', salt='" . $salt . "'";
		}

		$sql_update_query .= " WHERE id=" . $user_id;

//            var_dump($sql_update_query); exit;

		$sql_update_user = $this->query($sql_update_query);

//        $npusername = $this->get_sql_field("SELECT username  FROM np_users WHERE tax_company_name ='" . $user_details['npname'] . "'", username);
//        SetCookieLive("np_userid_update", $user_details['npname'], time()+3600*24*90, '/', 'bringitlocal.com');
//        SetCookieLive("np_username_update", $npusername, time()+3600*24*90, '/', 'bringitlocal.com');

		if (!$user_old['tax_apply_exempt'] && !empty($user_details['tax_reg_number']) && IN_ADMIN != 1)
		{
			$mail_input_id = $user_id;
			include('language/' . $this->setts['site_lang'] . '/mails/tax_apply_exempt_notification.php');
		}

		$this->update_page_data($user_id, $page_handle, $user_details);
        } catch (Exception $e) {
            file_put_contents('user.log', $e->getMessage());
        }
	}

	function delete ($user_id, $page_handle = 'register')
	{
		## delete user and all the related fields including custom fields
		$this->delete_data($user_id, $page_handle);

		$this->item = new item();
		$this->item->setts = $this->setts;
		
		## now select all auctions that the user has listed
		$sql_select_auctions = $this->query("SELECT auction_id FROM " . DB_PREFIX . "auctions WHERE owner_id=" . $user_id);

		$delete_ids = null;

		while ($deleted_details = $this->fetch_array($sql_select_auctions))
		{
			$delete_ids[] = $deleted_details['auction_id'];
		}

		$delete_array = $this->implode_array($delete_ids);

		## delete all auctions the user has listed
		$this->item->delete($delete_array, 0, true, true);
		
		## now select all wanted ads that the user has listed
		$sql_select_wanted_ads = $this->query("SELECT wanted_ad_id FROM " . DB_PREFIX . "wanted_ads WHERE owner_id=" . $user_id);

		$delete_ids = null;

		while ($deleted_details = $this->fetch_array($sql_select_wanted_ads))
		{
			$delete_ids[] = $deleted_details['wanted_ad_id'];
		}

		$delete_array = $this->implode_array($delete_ids);
	
		## delete all wanted ads the user has listed
		$this->item->delete_wanted_ad($delete_array, 0, true);

		## delete the rest of the data
		$this->query("DELETE u, fs, i, ip, r  FROM " . DB_PREFIX . "users u 
			LEFT JOIN " . DB_PREFIX . "favourite_stores fs ON fs.user_id=u.user_id
			LEFT JOIN " . DB_PREFIX . "invoices i ON i.user_id=u.user_id
			LEFT JOIN " . DB_PREFIX . "iphistory ip ON ip.memberid=u.user_id
			LEFT JOIN " . DB_PREFIX . "reputation r ON r.user_id=u.user_id
		 	WHERE u.user_id=" . $user_id);
	}

	function account_status ($active, $approved)
	{
		(string) $display_output = null;

		$display_output = ($approved) ? (($active) ? GMSG_ACTIVE : GMSG_SUSPENDED) : GMSG_NOT_APPROVED;

		return $display_output;
	}

	function payment_mode_desc ($payment_mode)
	{
		(string)	$display_output = null;

		$payment_mode = ($this->setts['account_mode_personal'] == 1) ? (($payment_mode) ? $payment_mode : 1) : $this->setts['account_mode'];

		switch ($payment_mode)
		{
			case 1:
				$display_output = GMSG_LIVE;
				break;
			case 2:
				$display_output = GMSG_ACCOUNT;
				break;
			default:
				$display_output = GMSG_NA;
		}

		return $display_output;
	}

	function show_balance ($balance, $currency)
	{
		(string)	$display_output = null;

		$display_output = fees_main::display_amount(abs($balance), $currency, true) . ' ' . (($balance>0) ? GMSG_DEBIT : GMSG_CREDIT);

		return $display_output;
	}

	function user_manage_direct_payment_methods ($user_details)
	{

	}

	function direct_payment_methods_edit($user_details)
	{
		(string) $display_output = null;

		$sql_select_pg = $this->query("SELECT pg_id, name, logo_url FROM
			" . DB_PREFIX . "payment_gateways WHERE dp_enabled=1");

		$background = 'c1';
		while ($pg_details = $this->fetch_array($sql_select_pg))
		{
			switch ($pg_details['name'])
			{
				case 'PayPal':
					$display_output .= '<tr class="' . $background . '"> '.
		      		'	<td>' . GMSG_PAYPAL_FIRST_NAME . '</td> '.
						'	<td><input name="pg_paypal_first_name" type="text" value="' . $user_details['pg_paypal_first_name'] . '" size="50"></td> '.
						'</tr> ';
					$display_output .= '<tr class="' . $background . '"> '.
		      		'	<td>' . GMSG_PAYPAL_LAST_NAME . '</td> '.
						'	<td><input name="pg_paypal_last_name" type="text" value="' . $user_details['pg_paypal_last_name'] . '" size="50"></td> '.
						'</tr> ';
					$display_output .= '<tr class="' . $background . '"> '.
		      		'	<td>' . GMSG_PAYPAL_EMAIL . '</td> '.
						'	<td><input name="pg_paypal_email" type="text" value="' . $user_details['pg_paypal_email'] . '" size="50"></td> '.
						'</tr> ';





//                    $display_output .= '<tr> '.
//		      		'	<td></td> '.
//						'	<td class="' . $background . '">' . GMSG_PAYPAL_CALLBACK . ':<br><br><b>' . SITE_PATH . 'pp_paypal.php</b></td> '.
//						'</tr> ';
					break;
				case 'Worldpay':
					$display_output .= '<tr class="' . $background . '"> '.
		      		'	<td>' . GMSG_WORLDPAY_ID . '</td> '.
						'	<td><input name="pg_worldpay_id" type="text" value="' . $user_details['pg_worldpay_id'] . '" size="50"></td> '.
						'</tr> ';
					$display_output .= '<tr> '.
		      		'	<td></td> '.
						'	<td class="' . $background . '">' . GMSG_WORLDPAY_CALLBACK . ':<br><br><b>' . SITE_PATH . 'pp_worldpay.php</b></td> '.
						'</tr> ';
					break;
				case '2Checkout':
					$display_output .= '<tr class="' . $background . '"> '.
		      		'	<td>' . GMSG_CHECKOUT_ID . '</td> '.
						'	<td><input name="pg_checkout_id" type="text" value="' . $user_details['pg_checkout_id'] . '" size="50"></td> '.
						'</tr> ';
					$display_output .= '<tr> '.
		      		'	<td></td> '.
						'	<td class="' . $background . '">' . GMSG_CHECKOUT_CALLBACK . ':<br><br><b>' . SITE_PATH . 'pp_checkout.php</b></td> '.
						'</tr> ';
					break;
				case 'Nochex':
					$display_output .= '<tr class="' . $background . '"> '.
		      		'	<td>' . GMSG_NOCHEX_EMAIL . '</td> '.
						'	<td><input name="pg_nochex_email" type="text" value="' . $user_details['pg_nochex_email'] . '" size="50"></td> '.
						'</tr> ';
					$display_output .= '<tr> '.
		      		'	<td></td> '.
						'	<td class="' . $background . '">' . GMSG_NOCHEX_CALLBACK . ':<br><br><b>' . SITE_PATH . 'pp_nochex.php</b></td> '.
						'</tr> ';
					break;
				case 'Ikobo':
					$display_output .= '<tr class="' . $background . '"> '.
		      		'	<td>' . GMSG_IKOBO_USERNAME . '</td> '.
						'	<td><input name="pg_ikobo_username" type="text" value="' . $user_details['pg_ikobo_username'] . '" size="50"></td> '.
						'</tr> ';
					$display_output .= '<tr class="' . $background . '"> '.
		      		'	<td>' . GMSG_IKOBO_PASSWORD . '</td> '.
						'	<td><input name="pg_ikobo_password" type="text" value="' . $user_details['pg_ikobo_password'] . '" size="50"></td> '.
						'</tr> ';
					$display_output .= '<tr> '.
		      		'	<td></td> '.
						'	<td class="' . $background . '">' . GMSG_IKOBO_CALLBACK . ':<br><br><b>' . SITE_PATH . 'pp_ikobo.php</b></td> '.
						'</tr> ';
					break;
				case 'Protx':
					$display_output .= '<tr class="' . $background . '"> '.
		      		'	<td>' . GMSG_PROTX_USERNAME . '</td> '.
						'	<td><input name="pg_protx_username" type="text" value="' . $user_details['pg_protx_username'] . '" size="50"></td> '.
						'</tr> ';
					$display_output .= '<tr class="' . $background . '"> '.
		      		'	<td>' . GMSG_PROTX_PASSWORD . '</td> '.
						'	<td><input name="pg_protx_password" type="text" value="' . $user_details['pg_protx_password'] . '" size="50"></td> '.
						'</tr> ';
					$display_output .= '<tr> '.
		      		'	<td></td> '.
						'	<td class="' . $background . '">' . GMSG_PROTX_CALLBACK . ':<br><br><b>' . SITE_PATH . 'pp_protx.php</b></td> '.
						'</tr> ';
					break;
				case 'Authorize.net':
					$display_output .= '<tr class="' . $background . '"> '.
		      		'	<td>' . GMSG_AUTHNET_USERNAME . '</td> '.
						'	<td><input name="pg_authnet_username" type="text" value="' . $user_details['pg_authnet_username'] . '" size="50"></td> '.
						'</tr> ';
					$display_output .= '<tr class="' . $background . '"> '.
		      		'	<td>' . GMSG_AUTHNET_PASSWORD . '</td> '.
						'	<td><input name="pg_authnet_password" type="text" value="' . $user_details['pg_authnet_password'] . '" size="50"></td> '.
						'</tr> ';
					$display_output .= '<tr> '.
		      		'	<td></td> '.
						'	<td class="' . $background . '">' . GMSG_AUTHNET_CALLBACK . ':<br><br><b>' . SITE_PATH . 'pp_authnet.php</b></td> '.
						'</tr> ';
					break;
				case 'Moneybookers':
					$display_output .= '<tr class="' . $background . '"> '.
		      		'	<td width="250">' . GMSG_MB_EMAIL . '</td> '.
						'	<td><input name="pg_mb_email" type="text" value="' . $user_details['pg_mb_email'] . '" size="50"></td> '.
						'</tr> ';
					$display_output .= '<tr> '.
		      		'	<td></td> '.
						'	<td class="' . $background . '">' . GMSG_MB_CALLBACK . ':<br><br><b>' . SITE_PATH . 'pp_moneybookers.php</b></td> '.
						'</tr> ';
					break;
				case 'Paymate':
					$display_output .= '<tr class="' . $background . '"> '.
		      		'	<td width="250">' . GMSG_PAYMATE_MERCHANT_ID . '</td> '.
						'	<td><input name="pg_paymate_merchant_id" type="text" value="' . $user_details['pg_paymate_merchant_id'] . '" size="50"></td> '.
						'</tr> ';
					$display_output .= '<tr> '.
		      		'	<td></td> '.
						'	<td class="' . $background . '">' . GMSG_PAYMATE_CALLBACK . ':<br><br><b>' . SITE_PATH . 'pp_paymate.php</b></td> '.
						'</tr> ';
					break;
				case 'Google Checkout':
					$display_output .= '<tr class="' . $background . '"> '.
		      		'	<td width="250">' . GMSG_GC_MERCHANT_ID . '</td> '.
						'	<td><input name="pg_gc_merchant_id" type="text" value="' . $user_details['pg_gc_merchant_id'] . '" size="50"></td> '.
						'</tr> ';
					$display_output .= '<tr class="' . $background . '"> '.
		      		'	<td width="250">' . GMSG_GC_MERCHANT_KEY . '</td> '.
						'	<td><input name="pg_gc_merchant_key" type="text" value="' . $user_details['pg_gc_merchant_key'] . '" size="50"></td> '.
						'</tr> ';
					if ($user_details['user_id'] > 0)
					{
						$display_output .= '<tr> '.
			      		'	<td></td> '.
							'	<td class="' . $background . '">' . GMSG_GC_CALLBACK . ':<br><br><b>' . SITE_PATH . 'pp_gc.php?user_id=' . $user_details['user_id'] . '</b></td> '.
							'</tr> ';
					}
					break;
			case 'Amazon':
				$display_output .= '<tr class="' . $background . '"> '.
	      		'	<td width="250">' . GMSG_AMAZON_ACCESS_KEY . '</td> '.
					'	<td><input name="pg_amazon_access_key" type="text" value="' . $user_details['pg_amazon_access_key'] . '" size="50"></td> '.
					'</tr> ';
				$display_output .= '<tr class="' . $background . '"> '.
	      		'	<td width="250">' . GMSG_AMAZON_SECRET_KEY . '</td> '.
					'	<td><input name="pg_amazon_secret_key" type="text" value="' . $user_details['pg_amazon_secret_key'] . '" size="50"></td> '.
					'</tr> ';
				$display_output .= '<tr> '.
	      		'	<td></td> '.
					'	<td class="' . $background . '">' . GMSG_AMAZON_CALLBACK . ':<br><br><b>' . SITE_PATH . 'pp_amazon.php</b></td> '.
					'</tr> ';
				break;
			   case 'AlertPay':
				   $display_output .= '<tr class="' . $background . '"> '.
	      		   '	<td width="250">' . GMSG_ALERTPAY_ID . '</td> '.
					   '	<td><input name="pg_alertpay_id" type="text" value="' . $user_details['pg_alertpay_id'] . '" size="50"></td> '.
					   '</tr> ';
				   $display_output .= '<tr class="' . $background . '"> '.
	      		   '	<td width="250">' . GMSG_ALERTPAY_SECURITY_CODE . '</td> '.
					   '	<td><input name="pg_alertpay_securitycode" type="text" value="' . $user_details['pg_alertpay_securitycode'] . '" size="50"></td> '.
					   '</tr> ';
				   $display_output .= '<tr> '.
	      		   '	<td></td> '.
					   '	<td class="' . $background . '">' . GMSG_ALERTPAY_CALLBACK . ':<br><br><b>' . SITE_PATH . 'pp_alertpay.php</b></td> '.
					   '</tr> ';
					break;
			}
		}

		return $display_output;
	}

	function direct_payment_data($user_details)
	{
		(string) $display_output = null;
        $background = 'c1';

        $display_output .= '<tr class="' . $background . '"> '.
            '	<td>' . GMSG_PAYPAL_FIRST_NAME . '</td> '.
            '	<td><input name="pg_paypal_first_name" type="text" value="' . $user_details['pg_paypal_first_name'] . '" size="50"></td> '.
            '</tr> ';
        $display_output .= '<tr class="' . $background . '"> '.
            '	<td>' . GMSG_PAYPAL_LAST_NAME . '</td> '.
            '	<td><input name="pg_paypal_last_name" type="text" value="' . $user_details['pg_paypal_last_name'] . '" size="50"></td> '.
            '</tr> ';
        $display_output .= '<tr class="' . $background . '"> '.
            '	<td>' . GMSG_PAYPAL_EMAIL . '</td> '.
            '	<td><input name="pg_paypal_email" type="text" value="' . $user_details['pg_paypal_email'] . '" size="50"></td> '.
            '</tr> ';

		return $display_output;
	}

	function birthdate_box($variables_array)
	{
		(string) $display_output = null;

		$months_array = array('01' => GMSG_MTH_JANUARY, '02' => GMSG_MTH_FEBRUARY, '03' => GMSG_MTH_MARCH, '04' => GMSG_MTH_APRIL,
			'05' => GMSG_MTH_MAY, '06' => GMSG_MTH_JUNE, '07' => GMSG_MTH_JULY, '08' => GMSG_MTH_AUGUST,
			'09' => GMSG_MTH_SEPTEMBER, '10' => GMSG_MTH_OCTOBER, '11' => GMSG_MTH_NOVEMBER, '12' => GMSG_MTH_DECEMBER);

		$days_array = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12',
			'13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24',
			'25', '26', '27', '28', '29', '30', '31');

		if ($this->setts['birthdate_type'] == 1)
		{
			$dob_text = MSG_YEAR_OF_BIRTH;
			$dob_expl = MSG_YEAR_OF_BIRTH_EXPL;
			
			$birthdate_box = '<input name="birthdate_year" type="text" id="birthdate_year" value="' . $variables_array['birthdate_year'] . '" size="8" maxlength="4" /> ';
		}
		else
		{
			$dob_text = MSG_DATE_OF_BIRTH;
			$dob_expl = MSG_DATE_OF_BIRTH_EXPL;

			$birthdate_box .= '<select name="dob_month" id="dob_month" class="contentfont"> '.
				'<option> </option> ';
			foreach ($months_array as $key => $value)
			{
				$birthdate_box .= '<option value="' . $key . '" ' . (($key == $variables_array['dob_month']) ? 'selected' : '') . '>' . $value . '</option> ';
			}
			$birthdate_box .= '</select> ';

			$birthdate_box .= '<select name="dob_day" id="dob_day" class="contentfont"> '.
				'<option> </option> ';
			foreach ($days_array as $value)
			{
				$birthdate_box .= '<option value="' . $value . '" ' . (($value == $variables_array['dob_day']) ? 'selected' : '') . '>' . $value . '</option> ';
			}
			$birthdate_box .= '</select> ';

			$birthdate_box .= '<input name="dob_year" type="text" id="dob_year" value="' . $variables_array['dob_year'] . '" size="8" maxlength="4" /> ';
		}

		$display_output = '<br /> '.
			'<table width="100%" border="0" cellpadding="3" cellspacing="2" class="border"> '.
      	'	<tr class="c5"> '.
         '		<td><img src="themes/' . $this->setts['default_theme'] . '/img/pixel.gif" width="1" height="1" /></td> '.
         '		<td><img src="themes/' . $this->setts['default_theme'] . '/img/pixel.gif" width="1" height="1" /></td> '.
      	'	</tr> '.
      	'	<tr class="c1"> '.
         '		<td class="contentfont">' . $dob_text . '</td> '.
         '		<td class="contentfont">' . $birthdate_box . '</td> '.
      	'	</tr> '.
      	'	<tr class="reguser"> '.
         '		<td>&nbsp;</td> '.
         '		<td>' . $dob_expl . '</td> '.
      	'	</tr> '.
   		'</table> ';

   	return $display_output;
	}

	function can_sell($is_seller)
	{
		$output = ($is_seller || $this->setts['enable_private_site'] == 0) ? true : false;

		return $output;
	}

	function full_address($user_details)
	{
		(string) $display_output = null;

		$state = ($user_details['state_name']) ? $user_details['state_name'] : $user_details['state'];
		// state_name and country_name are presumed to be taken from the countries table from the initial query.
		$display_output = $user_details['address'] . '<br>'.
			$user_details['zip_code'] . ', ' . $user_details['city'] . '<br>'.
			$state . ', ' . $user_details['country_name'];
		$display_output = $user_details['address'] . '<br>'.
			$user_details['city'] . ', ' . $state . '<br>'.
			$user_details['zip_code'] . '<br>'.
			$user_details['country_name'];

		return $display_output;
	}

	function show_birthdate($user_details)
	{
		(string) $display_output = null;

		if ($this->setts['birthdate_type'] == 1)
		{
			$display_output = $user_details['birthdate_year'] . ' ' . GMSG_DOB_YEAR;
		}
		else
		{
			$display_output = $user_details['birthdate'] . ' ' . GMSG_DOB_FULL;
		}

		return $display_output;
	}
	
	function postage_calc_save($postage_details, $user_id)
	{
		$user_id = intval($user_id);
		
		$pc_postage_type = (in_array($postage_details['pc_postage_type'], array('item', 'weight', 'amount', 'flat'))) ? $postage_details['pc_postage_type'] : 'item';
		$pc_postage_calc_type = (in_array($postage_details['pc_postage_calc_type'], array('default', 'custom'))) ? $postage_details['pc_postage_calc_type'] : 'default';
		$pc_shipping_locations = (in_array($postage_details['pc_shipping_locations'], array('local', 'global'))) ? $postage_details['pc_shipping_locations'] : 'global';
		
		$this->query("UPDATE " . DB_PREFIX . "users SET 
			pc_free_postage='" . intval($postage_details['pc_free_postage']) . "', 
			pc_free_postage_amount='" . doubleval($postage_details['pc_free_postage_amount']) . "', 
			pc_postage_type='" . $pc_postage_type . "', 
			pc_weight_unit='" . $this->rem_special_chars($postage_details['pc_weight_unit']) . "', 
			pc_postage_calc_type='" . $pc_postage_calc_type . "', 
			pc_shipping_locations='" . $pc_shipping_locations . "', 
			pc_flat_first='" . doubleval($postage_details['pc_flat_first']) . "', 
			pc_flat_additional='" . doubleval($postage_details['pc_flat_additional']) . "' 
			WHERE user_id='" . $user_id . "'"); 
		
		/*
		if ($postage_details['pc_shipping_locations'])
		{
			$this->query("UPDATE " . DB_PREFIX . "shipping_locations SET pc_default=0 WHERE user_id='" . $user_id . "'");			
			$this->query("UPDATE " . DB_PREFIX . "shipping_locations SET 
				pc_default='1' WHERE id='" . intval($postage_details['pc_default']) . "' AND user_id='" . $user_id . "'");
		}
		*/
		
		if ($postage_details['pc_postage_type'] != 'item' && $postage_details['pc_postage_calc_type'] == 'custom')
		{
			$postage_details = convert_amount($postage_details, 'STN');
			
			if (count($postage_details['tier_id']))
			{
				foreach ($postage_details['tier_id'] as $key => $value)
				{
					$sql_update_tiers = $this->query("UPDATE " . DB_PREFIX . "postage_calc_tiers SET
						tier_from='" . $postage_details['tier_from'][$key] . "', tier_to='" . $postage_details['tier_to'][$key] . "', 
						postage_amount='" . $postage_details['postage_amount'][$key] . "' WHERE tier_id=" . $value . " AND user_id=" . $user_id);
				}
			}
	
			if ($postage_details['new_tier_from'] >= 0 && $postage_details['new_tier_to'] > 0 && $postage_details['new_postage_amount'] > 0)
			{
				$sql_insert_tier = $this->query("INSERT INTO " . DB_PREFIX . "postage_calc_tiers 
					(tier_from, tier_to, postage_amount, user_id, tier_type) VALUES
					('" . $postage_details['new_tier_from'] . "', '" . $postage_details['new_tier_to'] . "', '" . $postage_details['new_postage_amount'] . "', 
					'" . $user_id . "', '" . $pc_postage_type . "')");
			}
	
			if (count($postage_details['delete'])>0)
			{
				$delete_array = $this->implode_array($postage_details['delete']);
	
				$sql_delete_increments = $this->query("DELETE FROM " . DB_PREFIX . "postage_calc_tiers WHERE
					tier_id IN (" . $delete_array . ") AND user_id=" . $user_id);
			}			
		}
	}
	
	
}
?>
