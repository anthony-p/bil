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


<h3>Why Bring It Local </h3>
<blockquote>
I want to share with you my personal perspective on our mission and how the idea of Bring It Local came to be.
<br><br>
Our local communities � our schools, our local businesses, our middle class � are going broke. Millions of dollars are needlessly escaping local communities through online shopping. 
Dollars that could fund local schools, local businesses and local services are vacuumed up by an increasingly small number of huge companies and do not return.
<br><br>

At Bring It Local, we believe we can ethically and responsibly reverse this trend and harness the power of the internet to keep main streets vibrant and local communities thriving.  Bring It Local is a mechanism for capturing a significant portion of these funds and keeping them in our local communities. 
<br><br>
Bring It Local grew out of my personal frustration with the financial crisis of 2008. Our system\'s 
only response was to rally around and to preserve the elite. We did nothing to help address the inequities and 
inconsistencies that had led us into crisis.  I began reading about how the populist movement of the late 1800s 
worked hard to develop creative and practical economic alternatives to the debt peonage of the time. 
I became inspired. Bring It Local comes from this same intellectual thread: practical alternative thinking to 
ethically and fundamentally shift economics in favor of the community.
<br><br>
<a href="/mark-white">Mark White</a> - founder, Bring It Local, LLC
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

$message_header = 'Bring It Local Founder\'s Letter';

$template->set('message_header', header5($message_header));
$template->set('message_content', $message_content);

$template_output .= $template->process('single_message.tpl.php');

include_once ('global_footer.php');

echo $template_output;

?>
