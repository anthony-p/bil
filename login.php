<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
## (Mods-Store) -> Shopping Cart											##
#################################################################

session_start();

define ('IN_SITE', 1);
$GLOBALS['body_id'] = "login";

(string) $page_handle = 'login';

include_once ('includes/global.php');
include_once ('includes/class_fees.php');
include_once ('includes/functions_login.php');


$sc_id  = 0;
if (isset ($_REQUEST['sc_id']))
    $sc_id = intval($_REQUEST['sc_id']);

$isLogin = (string)$session->value('membersarea');
if ($isLogin=='Active')
{
	if (!empty($_REQUEST['redirect']))
	{
		$redirect = @ereg_replace('_AND_', '&', $_REQUEST['redirect']);		
	}elseif(isset($_COOKIE['redirect']) && !empty($_COOKIE['redirect'])) {
    }
	else 
	{
        $redirect = $_SERVER['HTTP_REFERER'];
		$redirect = 'index.php';
	}
	header_redirect($redirect);
}
else if ($setts['is_ssl'] && $_SERVER['HTTPS'] != 'on' && $_REQUEST['operation'] != 'submit')
{
	header_redirect($setts['site_path_ssl'] . 'login.php?' . $_SERVER['QUERY_STRING']);
}
else
{
    $redirect = $_SERVER['HTTP_REFERER'];
    if ($redirect && strpos($redirect,'login.php') == false){
        setcookie("referrer_url",$redirect,time()+120,'/');
    }
	require ('global_header.php');
	
	$banned_output = check_banned($_SERVER['REMOTE_ADDR'], 1);

	if ($banned_output['result'])
	{
		$template->set('message_header', header5(MSG_LOGIN_TO_MEMBERS_AREA));
		$template->set('message_content', $banned_output['display']);

		$template_output .= $template->process('single_message.tpl.php');
	}
	else
	{
		$template->set('header_registration_message', header5(MSG_LOGIN_TO_MEMBERS_AREA));

		if (isset($_REQUEST['operation']) && $_REQUEST['operation'] == 'submit')
		{
			$signup_fee = new fees();
			$signup_fee->setts = &$setts;

			$header_redirect = (empty($_REQUEST['redirect'])) ? 'members_area.php' : $_REQUEST['redirect'];

			$login_output = bl2_login_user($_POST['email'], $_POST['password'], $header_redirect);

            //@TODO uncomit it later.
            /*try{
                $woptions['soap_version'] = SOAP_1_2;
                $woptions['login'] = $coupon_http_username;
                $woptions['password'] = $coupon_http_password;

                $proxy = new SoapClient($coupon_url."/index.php/api/soap/?wsdl",$woptions);
                $sessionId = $proxy->login($coupon_soap_username, $coupon_soap_password);

                $cart = null;
                if(isset($_COOKIE['cart']))
                {
                    $cart = $_COOKIE['cart'];
                }

                $customers = $proxy->call($sessionId, 'bcustomer_authentication.getSessionId', array(array('username' => $login_output['email'], 'password' => $_POST['password'], 'cart' => $cart)));

                setcookie('frontend',$customers[0], 0, '/', '.bringitlocal.com', false, true);

                //Add products to cart
                //set POST variables
                $url = $coupon_url."/glpc/AddToCart.php";
                //open connection
                $ch = curl_init();

                $strCookie = 'frontend=' .$customers[0]. '; domain=.bringitlocal.com; path=/';

                //foreach($customers[1] as $product){
                    //$fields_string = "product=".$product['product_id']."&qty=".$product['qty'];
                    $fields_string = "products=".serialize($customers[1]);

                    curl_setopt($ch, CURLOPT_URL,$url); // Sets the paypal address for curl
                    curl_setopt( $ch, CURLOPT_COOKIE, $strCookie );
                    //curl_setopt($ch, CURLOPT_USERPWD, 'user:pass');
                    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // Returns result to a variable instead of echoing
                    curl_setopt($ch, CURLOPT_TIMEOUT, 3); // Sets a time limit for curl in seconds (do not set too low)
                    curl_setopt($ch, CURLOPT_POST, 1); // Set curl to send data using post
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); // Add the request parameters to the post
                    $result = curl_exec($ch); // run the curl process (and return the result to $result


                    //echo $result = curl_exec($ch);
                    //file_put_contents("lo_request.txt", $url.$fields_string."  res:".$result."   frontend:".$customers[0]);
                //}
                //close connection
                curl_close($ch);
				
				
            }catch(Exception $e)
            {
                file_put_contents('/tmp/magento.log', $e->getMessage());
            }*/

			$session->set('membersarea', $login_output['active']);
			$session->set('username', $login_output['email']);
			$session->set('user_id', $login_output['id']);
			$session->set('is_seller', $login_output['is_seller']);

            $extended_registration = true;
//            if($login_output['phone'] == "()" OR $login_output['birthdate'] == "0000-00-00"){
//                $extended_registration = false;
//            }
//            $session->set('extended_registration', $extended_registration);
			
			//$session->set_cookie("user_id", $login_output['user_id']);
//			setcookie('user_id',$login_output['id'], 0, '/', '.bringitlocal.com', false, true);
			//print "=====".$login_output['user_id'];
			//$_COOKIE['usernamevalue'] = $login_output['username'];
			
			$session->set('remember_username', intval($_REQUEST['remember_username']));
			
//			$session->set('temp_user_id', $login_output['temp_user_id']); /* for use with activate_account.php only! */

			$redirect_url = ($login_output['redirect_url'] == 'sell_item') ? 'sell_item.php' : $login_output['redirect_url'];
			$redirect_url = ($login_output['redirect_url'] == 'shopping_cart') ? (SITE_PATH . 'shopping_cart.php?sc_id=' . $sc_id) : $redirect_url;
			$redirect_url = (eregi('account_activate', $redirect_url)) ? 'members_area.php' : $redirect_url;

            if(isset($_COOKIE['referrer_url']))
            {
                header( 'Location: '.$_COOKIE['referrer_url']);
                exit;
            }
			header_redirect($db->add_special_chars($redirect_url));
		}
		if (isset($_REQUEST['invalid_login']) && $_REQUEST['invalid_login'] == 1)
		{
			$invalid_login_message = '<table width="400" border="0" cellpadding="4" cellspacing="0" align="center" class="errormessage"> '.
			'	<tr><td align="center" class="alertfont"><b>' . MSG_INVALID_LOGIN . '</b></td></tr> '.
			'</table>';

			$template->set('invalid_login_message', $invalid_login_message);
		}

		$redirect = @ereg_replace('_AND_', '&', $_REQUEST['redirect']);		
		$template->set('redirect', $redirect);
	   $template->set('sc_id', $sc_id);

		$template_output .= $template->process('login.tpl.php');
	}

	include_once ('global_footer.php');

	echo $template_output;
}


?>
