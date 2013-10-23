<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

session_start();

define ('IN_SITE', 1);
$GLOBALS['body_id'] = "content_pages";

include_once ('includes/global.php');
include_once ('includes/class_formchecker.php');

include_once ('includes/functions_login.php');
include_once ('includes/functions_item.php');

include_once ('global_header.php');

(string) $message_content = null;






$message_content = '

<div class="topic_content unu" id="topic_content_0" style="display: ;">


<h3>What does it cost to use Bring It Local</h3>
<blockquote>
If you use Bring It Local for your fundraising the site will deduct a 5% fee from the total amount of funds you raise.
<br><br>

<strong>Donations:</strong> Supporters send their donations directly to you via PayPal. At the same moment that you receive your donation the site will also automatically charge you 5% of the amount you receive.
<br><br>

If you don\'t raise any money Bring It Local will never cost you anything. 
<br><br>
<strong>Click through shopping:</strong> When users click through and make purchases from the online vendors we list on the site, you will receive a percentage of each transaction. This amount varies and is shown on the vendors page for each vendor.
On average this is around 6% of the purchase amount. <br><br>So, here is a sample transaction:
<br>
-a supporter of your campaign clicks and makes a purchase for $100 from Amazon.<br>
-you will receive 6% of that transaction or $6.00.<br>
-Bring It Local will deduct a 5% fee on the total of the money you raise. So in this case it would amount to 5% of the $6.00 or $.30 (30 cents).



<br><br>
<strong>Garage sales:</strong> this is an upcoming feature of Bring It Local where we enable campaigns to set up and maintain ongoing garage sales where people buy and sell items from each other. We\'ll add more information here
as this feature becomes available.

</blockquote>


</div>

'.
'<script language="javascript">
	var ie4 = false;
	if(document.all) { ie4 = true; }

	function getObject(id) { if (ie4) { return document.all[id]; } else { return document.getElementById(id); } }
	function toggle(link, divId) {
		var d = getObject(divId);
		if (d.style.display == \'\') { d.style.display = \'none\'; }
		else { d.style.display = \'\'; }
	}
</script>';



$topic_id = intval($_REQUEST['topic_id']);

$message_header = 'Site Fees';

$template->set('message_header', header5($message_header));
$template->set('message_content', $message_content);

$template_output .= $template->process('single_message.tpl.php');

include_once ('global_footer.php');

echo $template_output;

?>
