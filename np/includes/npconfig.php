<?php 
switch($_SERVER['SERVER_NAME']) {
    case 'local.bringit':
        $db_host = 'localhost'; 
        $db_username = 'root'; 
        $db_password = 'toor'; 
        define('DB_PREFIX', 'probid_'); ## Do not edit ! 
        define('SESSION_PREFIX', 'probid_'); 
        $db_name = 'bringit_auction';
    break;
    case 'dev.bringitlocal.com':
         $db_host = 'localhost'; 
        $db_username = 'devbr0_userbid'; 
        $db_password = '^Xqh#^sqT%xC'; 
        define('DB_PREFIX', 'probid_'); ## Do not edit ! 
        define('SESSION_PREFIX', 'probid_'); 
        $db_name = 'devbr0_auction';
        $coupon_url = 'http://devcoupons.bringitlocal.com';
        $coupon_http_username = 'main2';
        $coupon_http_password = 'starburst~!@';
        $coupon_soap_username = 'glpc';
        $coupon_soap_password = 'glpc2012';
    break;
	 case 'dev2.bringitlocal.com':
        $db_host = 'localhost'; 
        $db_username = 'dev2brin_user'; 
        $db_password = '^Xqh#^sqT%xC'; 
        define('DB_PREFIX', 'probid_'); ## Do not edit ! 
        define('SESSION_PREFIX', 'probid_'); 
        $db_name = 'dev2brin_bil';
        $coupon_url = 'http://devcoupons.bringitlocal.com';
        $coupon_http_username = 'main2';
        $coupon_http_password = 'starburst~!@';
        $coupon_soap_username = 'glpc';
        $coupon_soap_password = 'glpc2012';
    break;
 case 'stage.bringitlocal.com':
    default:
        $db_host = 'localhost'; 
        $db_username = 'stagebri_userbid'; 
        $db_password = '^Xqh#^sqT%xC'; 
        define('DB_PREFIX', 'probid_'); ## Do not edit ! 
        define('SESSION_PREFIX', 'probid_'); 
        $db_name = 'stagebri_auction';
		 $coupon_url = 'http://stagecoupons.bringitlocal.com';
        $coupon_http_username = 'main2';
        $coupon_http_password = 'starburst~!@';
        $coupon_soap_username = 'glpc';
        $coupon_soap_password = 'glpc2012';
    break;
    case 'www.bringitlocal.com':
    default:
        $db_host = 'localhost'; 
        $db_username = 'bringit_userbids'; 
        $db_password = '^Xqh#^sqT%xC'; 
        define('DB_PREFIX', 'probid_'); ## Do not edit ! 
        define('SESSION_PREFIX', 'probid_'); 
        $db_name = 'bringit_auction';
		$coupon_url = 'http://coupons.bringitlocal.com';
        $coupon_http_username = 'main2';
        $coupon_http_password = 'starburst~!@';
        $coupon_soap_username = 'glpc';
        $coupon_soap_password = 'glpc2012';
    break;
	case 'wwwlive.bringitlocal.com':
    default:
        $db_host = 'localhost'; 
        $db_username = 'bringit_userbids'; 
        $db_password = '^Xqh#^sqT%xC'; 
        define('DB_PREFIX', 'probid_'); ## Do not edit ! 
        define('SESSION_PREFIX', 'probid_'); 
        $db_name = 'bringit_auction';
		$coupon_url = 'http://coupons.bringitlocal.com';
        $coupon_http_username = 'main2';
        $coupon_http_password = 'starburst~!@';
        $coupon_soap_username = 'glpc';
        $coupon_soap_password = 'glpc2012';
    break;
}

?>
