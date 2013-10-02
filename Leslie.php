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




<img src="/images/leslie-sheridan.jpg" alt="Leslie Sheridan" width="150" height="200" style="float:right"; />
<form method="post" action="/np/npregister.php">
<h2>Are you a non-profit organization?</h2>
<input type="hidden" name="affiliate" value="lesliesheridan"/> <br/>
<input type="submit" name="Enroll here" value="ENROLL NOW" class="button"/>
</form>


<form method="post" action="/register.php">
<h2>Are you a supporter of a non-profit?</h2>
<input type="hidden" name="affiliate" value="lesliesheridan"/> <br/>
<input type="submit" name="Enroll here" value="REGISTER HERE" class="button"/>
</form>
<h2>Are you a local merchant wanting to offer coupons?</h2>
Contact Bring It Local by <a href="mailto:support@bringitlocal.com">email</a> or at 415.704.5040  



<br><br>
<h2>More about Bring It Local</h2>
<h3> You\'re already shopping online. It won\'t cost you a penny more to simply start your shopping on Bring It Local and your local community benefits.</h3> 
A portion of all proceeds collected by Bring It Local is shared directly with your local community - specifically with the non-profit organization that you choose to connect 
with your account. When you use Bring It Local every transaction earns Bring It Local points that get credited to your account. By connecting your account with the non-profit 
of your choice we know where to send the dollars. At the end of each month we send the funds you have earned by using the site to your chosen non-profit. You can always track 
exactly how much you\'ve earned by going to your account management page and looking at your fundraising report<h3>Start now - non-profit:</h3>If you are a non-profit organization 
wanting to raise funds enroll <a href="/np/npregister.php">here</a>.<h3>Start now - if you are an individual community member or local business interested in supporting a 
chosen non-profit:</h3>Register for an account <a href="/register.php">here</a>, choose a non-profit and start buying or selling on the site.<br>or<br>simply go to 
this <a href="/searchnp.php">Quick Select page</a>, choose a non profit and then click through to Amazon to complete your shopping - no registration necessary.
<h3>Here\'s a 2 minute video that explains Bring It Local and what we\'re doing:
<br></h3><iframe width="550" height="343" src="http://www.youtube.com/embed/iZsIPY2DYqo" frameborder="0" allowfullscreen></iframe>
<h3>Buy Global, Bring It Local </h3> At Bring It Local, we want to use the Internet to keep your Main Street vibrant and your community thriving. 
Imagine having the ease of use and limitless choice of Internet shopping, while at the same time supporting your local community and businesses. 
Bring It Local is your one-stop, community fund- raising, ecommerce site where you can find, purchase, sell and swap items while at the same time promote 
your local businesses and community. A portion of every transaction flows directly back to your community - directly to the non profit of your choice, 
such as your local school. Bring It Local combines a full featured auction site for both individuals and stores as well as products available for fixed 
price purchase and a means for community businesses to offer discount promotions to attract new customers. It\'s the best features of Ebay, Amazon and the social purchasing 
of sites like Groupon--all with a dedication to local community renewal and prosperity. And if no one on Bring It Local is offering you exactly what 
you\'re looking for we\'ll send you to the shopping site of your choice to make your purchase. We\'ll make the search easy, 
you\'ll always find what you need and you won\'t spend anything extra when you start your shopping, selling, 
swapping or advertising on Bring It Local. <br><p> </p><h3> Putting the Fun in Fundraising </h3>
Our dedication to local commerce, businesses, schools and families is the very reason for our business. 
You\'re already shopping online. Simply start your shopping on Bring It Local and your local community benefits. 
A portion of all proceeds collected by Bring It Local on your purchases are shared directly with your local community. 
We exist as a tool that you can use to start funneling dollars directly back into your community, whether it is 
through a local school or other non profit organization of your choice. On Bring It Local you get to tell us where you 
want us to send the portion of your online purchases that we give back to the community. <br><h3> We promote local sales but 
we offer you the global market </h3>Our site makes it possible for you to buy local when the item you need is available, to buy global 
if it isn\'t, but to always bring the benefits of your purchases back to your community. Bring It Local will always direct you first to 
the local options when those exist. If the product you want isn\'t found locally, we\'ll show you where else you can find it. And no 
matter where you end up making your online purchase, as long as you started from Bring It Local, a portion of the purchase price 
will always come back to your community. <br><h3> Are you selling a product or interested in promoting your business?</h3>
Bring It Local will always match you up with a buyer in your locale whenever possible. But whether your product is bought by 
someone local or by someone far away you can be sure that your transaction is benefiting your own community. When you use Bring 
It Local proceeds from every sale come back to your personally chosen local non profit organization. <br><h3>Are you a local 
business looking for new customers?</h3>Offer promotions for your product or service and Bring It Local will match you up with 
local customers who may not have otherwise known about your business. Gain new customers and name recognition in your community 
and all the while being sure that a part of Bring It Local\'s fees will directly benefit your local school or other non profit 
organization. <br><h3> The right way to shop and the right thing to do</h3>We believe in operating in an environment of total transparency. 
When you buy or sell something on Bring it Local we want you to see just how much of our proceeds on your purchase are being given back to the community. 
You will always see this before you finalize your purchase or sale. Not only are you able to see how much your purchases have contributed to your community 
but you can visit the Bring It Local page for your community and see just how much money has been raised by everyone in your community acting together.



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

$message_header = 'Leslie Sheridan welcomes you to Bring It Local';

$template->set('message_header', header5($message_header));
$template->set('message_content', $message_content);

$template_output .= $template->process('single_message.tpl.php');

include_once ('global_footer.php');

echo $template_output;

?>
