<?
/**
 * page acts like a multiplexer, to redirect logged user to a tracked amazon link;
 *
 * page expects a 'amazon_url' GET parameter containing the (urlencoded) destination Amazon URL.
 *
 * eg,  calling
 *           http://www.bringitlocal.com/amazon_switch.php?amazon_url=http%3a%2f%2fwww.amazon.com%2fKindle-Wireless-Reader-Wifi-Graphite%2fdp%2fB002Y27P3M%2fref%3das_li_wdgt_js_ex%3f%26camp%3d212361%26linkCode%3dwsw%26tag%3dmainsailstore-20%26creative%3d391881
 *	     
 *      will redirect logged user to (for instance)
 *      
 *           http://www.amazon.com/Kindle-Wireless-Reader-Wifi-Graphite/dp/B002Y27P3M/ref=as_li_wdgt_js_ex?&camp=212361&linkCode=wsw&tag=mainsailstore15-20&creative=391881
 *
 *	and send anonymous user to
 *
 *		http://www.amazon.com/Kindle-Wireless-Reader-Wifi-Graphite/dp/B002Y27P3M/ref=as_li_wdgt_js_ex?&camp=212361&linkCode=wsw&tag=mainsailstore-20&creative=391881
 *           
 */
session_start();

define ('IN_SITE', 1);

include_once ('includes/global.php');
include_once ('includes/class_formchecker.php');
include_once ('includes/class_custom_field.php');
include_once ('includes/class_user.php');
require_once ('includes/class.amazon_link.inc');

##$_URL = !empty($_GET['amazon_url']) ? html_entity_decode(urldecode($_GET['amazon_url'])): "";
$_URL = !empty($_GET['amazon_url']) ? html_entity_decode($_GET['amazon_url']) : "";

if($_REQUEST['SKIP_NP_SELECTION'] == true) {
	// user already declined
	$_rewritten = AmazonTrackingURL::parseUrl($_URL);
	header("Location: {$_rewritten}");
}
else if($_REQUEST['np_selection'] == true) {
	// ask user if she/he wants to choose a quick NP...
	(string) $page_handle = 'register';
	
	include_once ('includes/global.php');
	
	include_once ('includes/class_user.php');
	include_once ('includes/class_fees.php');
	include_once ('includes/functions_login.php');
	
	include_once ('global_header.php');
	
	$template->set('refuse_url', str_replace('np_selection','SKIP_NP_SELECTION',$_SERVER['REQUEST_URI']));
	
	$template_output .= $template->process('searchnp.tpl.php');
	
	include_once ('global_footer.php');
	
	echo $template_output;
}
else if($session->value('user_id')) {
	if($_GET['report'] == true) {
		echo AmazonTrackingURL::printReport($_GET['report_id']);
	}
	else if($_GET['encode'] == true) {
		echo AmazonTrackingURL::generateSwitchingUrl('http://www.amazon.com/Kindle-Wireless-Reader-Wifi-Graphite/dp/B002Y27P3M/ref=as_li_wdgt_js_ex?&camp=212361&linkCode=wsw&tag=mainsailstore15-20&creative=391881');
	}
	else {
		if(!empty($_URL)) {
			$_rewritten = AmazonTrackingURL::parseUrl($_URL);
			header("Location: {$_rewritten}");
		}
		else {
			header("Location: {$_URL}");
		}
	}
}
else {


	if(!empty($_COOKIE['np_userid'])) {
		// we've got a quick NP selected...
		$_rewritten = AmazonTrackingURL::parseUrl($_URL);
		header("Location: {$_rewritten}");
		
	}
	else if(empty($_COOKIE['np_userid']) && empty($_COOKIE['SKIP_NP_SELECTION'])) {
		// anonymous user has neither selected a quick NP nor declined selection, let's go to ASKING PAGE...
		$_URL = $_SERVER['REQUEST_URI']."&np_selection=true";
		header("Location: {$_URL}");
	}
	else {
		// not selected and declined, let's go to plain URL...
		header("Location: {$_URL}");	
	}
}
?>
