<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
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

<div class="topic_content" id="topic_content_0" style="display: ;"> 




<br><br>
<h2>Our Community Loyalty Program</h2>
You already know that you benefit when you invest in your community.  Here at Bring It Local we are finding new ways to translate your support into further rewards for both you personally and your local community.
<br><br>
Every time you shop with Bring It Local, you collect Bring It Local community loyalty points.  After you earn 200 points, you can redeem them for a $25 gift certificate to use at any Bring It Local community merchant*. 
<br><br>
All gift certificates are purchased at face value by Bring It Local, so you can be sure that your activity at Bring It Local brings real cash into your community. Merchants can enroll in the community loyalty program when they start working with Bring It Local.
<br><br>
What It’s Worth<br>
You can earn loyalty points in many different ways. And once you reach 200, you can buy yourself a present to thank yourself for supporting your community. 
<br><br>
<table><tr><td>Activity</td><td>Points Earned</td></tr>
<tr><td>Share a local deal with friends </td><td align="center">				2</td></tr>
<tr><td>Register on Bring It Local	</td><td align="center">			 5</td></tr>
<tr><td>Post an auction item for sale</td><td align="center"> 				10</td></tr>
<tr><td>Buy a local deal 		</td><td align="center">			10</td></tr>
<tr><td>Buy an auction item 	</td><td align="center">				10</td></tr>
<tr><td>Buy an item through an online retailer partner 	</td><td align="center">	10</td></tr>
<tr><td>Sell an auction item		</td><td align="center">		 	20</td></tr>

</table>


<br><br>
*Terms of the Bring It Local community loyalty program, including but not limited to the number of points per transaction, the points needed to earn a gift certificate and the gift certificate value, may be changed at anytime


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

$message_header = 'Our Community Loyalty Program ';

$template->set('message_header', header5($message_header));
$template->set('message_content', $message_content);

$template_output .= $template->process('single_message.tpl.php');

include_once ('global_footer.php');

echo $template_output;

?>
