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





<table border="0" cellspacing="0" cellpadding="0" width="610">
       <tr><td></td></tr> 
        <tr>
          <td width="320"  rowspan="2" align="center" valign="top">
		  <br>
		  <img src="/images/mark-with-sonya.jpg" alt="Sonya is the more beautiful" width="200" height="182" /></td>
          
          
        </tr>
        <tr>
          <td valign="top"><br><br>
Mark White founded Bring It Local in 2011 with a mission to use the Internet to help keep Main Streets vibrant and local communities thriving. He believes that Bring It Local represents how individuals can harness the strength of capitalism to support our local communities.
</td></tr>
<tr><td colspan="2">
<br>Mark is a serial entrepreneur and for the past 16 years has led the web development company <a href="http://www.mainsail.com">Mainsail</a>. Prior to Mainsail, Mark founded a chain of newsstands in the Bay Area called The Kiosk that started on the corner of Haight and Ashbury. Mark received his M.A. from the Johns Hopkins School of Advanced International Studies (SAIS).
<br><br>
On the personal side: Mainsail was named after  a  notable personal feat: in a fit of analog nostalgia circa 1989, Mark sailed his 26 ft sailboat from Berkeley to Hawaii, finding the islands using nothing more than celestial navigation and a $150 plastic sextant. 
<br><br>
More recently, Mark has taken up the sport of endurance riding (the horseback version of marathon running) which he participates in with his 16 year old daughter. In 2006 he completed the Tevis Cup Endurance Ride - a grueling 100 mile ride through the Sierras considered by some to be the most challenging endurance ride in the world.
<br><br>
<a href="mailto:mwhite@bringitlocal.com">mwhite@bringitlocal.com</a>
<br>
415.704.5040
</td>
        </tr>
      </table>







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

$message_header = 'Mark White welcomes you to Bring It Local';

$template->set('message_header', header5($message_header));
$template->set('message_content', $message_content);

$template_output .= $template->process('single_message.tpl.php');

include_once ('global_footer.php');

echo $template_output;

?>
