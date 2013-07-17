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





<table width="90%" border="0" cellspacing="0" cellpadding="0">
    
   
  <tr>
    <td width="50" valign="top">&nbsp;</td>
   <td colspan="2" valign="top"><br />
	<table border="0" cellspacing="0" cellpadding="2" width="610">
        <tr>
          <td colspan="3"><p style="margin-top: 0;">Iphone Consumer app - coming soon</p>
            <p style="margin-bottom: 0;">&nbsp;</p>            </td>
        </tr>
        <tr>
          <td width="320" height="250" rowspan="2" align="center"><img src="/images/iphone-screen.gif" alt="iphone app screen" width="250" height="375" /></td>
          <td width="30" rowspan="2">&nbsp;</td>
          <td width="260">Iphone Consumer app<br />
		  <br />          </td>
        </tr>
        <tr>
          <td valign="top">Shop your local merchants or click through to Amazon to benefit your favorite non-profits easily and quickly by using the Bring It Local consumer app.
			<br><br><strong>Coming soon</strong></td>
        </tr>
      </table>

<table border="0" cellspacing="0" cellpadding="2" width="610">
        <tr><td><br></td></tr>
		
		
		<tr>
		          <td colspan="3"><p style="margin-top: 0;"><a href="http://itunes.apple.com/app/bring-it-local-merchant/id518815742?mt=8" target="_blank"">Iphone Merchant App - available now</a></p>
            <p style="margin-bottom: 0;">&nbsp;</p>            </td>
        </tr>
        <tr>
          <td width="320" height="250" rowspan="2" align="center"><a href="http://itunes.apple.com/app/bring-it-local-merchant/id518815742?mt=8" target="_blank""><img src="/images/iphone-merchant-screen.jpg" alt="iphone app screen" width="250" height="375" /></a></td>
          <td width="30" rowspan="2">&nbsp;</td>
          <td width="260">Iphone Merchant App<br />
		  <br />          </td>
        </tr>
        <tr>
          <td valign="top" class="bodyText">Use the Bring It Local Merchant app to accept Bring It Local coupons or vouchers at your place of business. Scan barcodes directly using the app or enter the coupon code.
			<br><br><strong><a href="http://itunes.apple.com/app/bring-it-local-merchant/id518815742?mt=8" target="_blank"">Install Now</a></strong></td>
        </tr>
      </table>	



	  </td>
	  <td>&nbsp;</td>
  </tr>
  
</table

<table border="0" cellspacing="0" cellpadding="2" width="610">
        <tr><td><br></td></tr>
		
		
		<tr>
		          <td colspan="3">      </td>
        </tr>
        <tr>
          
        <tr>
          <td valign="top" class="bodyText">Are you having difficulties installing or using your app? <br>Contact us here:<br>
<a href="mailto:support@bringitlocal.com">support@bringitlocal.com</a>
<br>
415-704-5040
        </tr>
      </table>	



	  </td>
	  <td>&nbsp;</td>
  </tr>
  
</table

<br><br>
Are you having difficulties installing or using your app? <br>Contact us here:<br>
<a href="mailto:support@bringitlocal.com">support@bringitlocal.com</a>
<br>
415-704-5040




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

$message_header = 'Bring It Local Mobile Apps';

$template->set('message_header', header5($message_header));
$template->set('message_content', $message_content);

$template_output .= $template->process('single_message.tpl.php');

include_once ('global_footer.php');

echo $template_output;

?>
