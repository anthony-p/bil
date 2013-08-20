<?
## File Version -> v6.04
## Email File -> registration confirmation message
## called only from the register.php page

if ( !defined('INCLUDED') ) { die("Access Denied"); }

if ($mail_input_id)
{
	$row_details = $db->get_sql_row("SELECT u.user_id, u.name, u.username, u.email, u.tax_company_name FROM " . NPDB_PREFIX . "users u WHERE 
		u.user_id='" . $mail_input_id . "'");
}
## otherwise row_details is provided from the file calling this email

$send = true; // always sent;

## text message - editable
$text_message = 'Dear %1$s,

Thank you for signing up your organization with %2$s. Please forward this email to
your community so that they will know how they can help raise funds for %4$s by making their online purchases through %2$s. 
Feel free to edit this note or simply send it on as is.


Dear %4$s community:

Help raise funds for %4$s by making your online purchases through %2$s Its simple just do what you already are doing but start here at this landing page on %2$s 
This page is set up so you can track exactly how much funds have been raised by the %3$s community 
Your %4$s landing page is:

	
	- go to this page for all your online purchases: http://www.bringitlocal.com/%3$s


	
Best regards,
The %2$s staff';


## html message - editable
$html_message = 'Dear %1$s, <br>
<br>
Welcome to Bring It Local! Bring It Local is an online fundraising engine that offers your organization a new revenue 
stream without costing your supporters a penny. <br>
<br>
To manage your community page on Bring It Local login to your account page here:  
<a href="http://www.bringitlocal.com/np/nplogin.php">http://www.bringitlocal.com/np/nplogin.php</a>
<br><br>
You also have a toolkit that you can download from there to help you in promoting Bring It Local for your organization.
<br><br>
The toolkit can be downloaded here as a single word document by clicking here: <a href="http://www.bringitlocal.com/np/toolkit.doc">http://www.bringitlocal.com/np/toolkit.doc</a>
<br><br>
At the bottom of this email there is text for an email that you can use to forward on to your community to let them know about Bring It Local.
<br><br>
<strong>How does it work?</strong><br>
Bring It Local combines a full-featured auction site and popular shopping destinations. 
Every time a supporter clicks through Bring It Local to make a purchase, your organization receives a portion of the funds. It’s that simple.  
<br><br>

<strong>How do you promote Bring it Local to your supporters?</strong><br>
<ul>
<li>We provide the templates, tools, and steps you need to get started. Our simple guide, "Getting Started with Bring It Local" suggests several easy ways that you can raise awareness and support for Bring It Local by using the communication tools you already have in place. 
 The <strong>Getting Started with Bring It Local Calendar: The 8-Week Plan</strong> is a sample calendar that you can modify to fit your organization\'s needs. 
<li>Your staff and volunteers follow the steps. You may want to enlist a volunteer to serve as the <strong>Bring It Local coordinator.</strong> 
The coordinator can help you maximize communication opportunities in your organization through 
activities such as flyer distribution, outreach to local businesses, coordination of emails and newsletters, and, 
perhaps most importantly--word of mouth promotion in your community.
<li>You remind your supporters, we\'ll remind you! Bring It Local is easy to use, but, to generate the most income possible, it\'s 
important to <strong>keep raising awareness</strong> of the program to your supporters.   
Every other week or so, we\'ll send you an email newsletter with more tips on Bring It Local and tools to help you achieve your fundraising goals. 
</ul>

<strong>Is it worth your efforts?</strong><br>
Consider this example:  If you can motivate just 100 of your supporters who are spending $250 a month online to use 
Bring It Local, you could be raising about $9000 annually. The more supporters using Bring It Local, the more money you can raise.  
<br><br>

<strong>It\'s just a couple of hours a month of your time</strong><br>
Please remember, encouraging your community to use Bring It Local and 
<strong>creating this new revenue stream should not take more than a couple of hours</strong>  of your time or the time of your volunteer Bring It Local coordinator 
each month. We provide a calendar with suggestions, but each item can be completed very quickly with the resources we provide in the toolkit. 
<br><br>



<strong>Contact us for help</strong><br>
Thank you for registering on Bring It Local.  We\'re here to support you to help your organization exceed your fundraising goals!  
And we welcome your feedback and 
ideas, so always please feel free to contact us at <a href="mailto:support@bringitlocal.com">support@bringitlocal.com</a>.

<br><br>

<strong>Start now: use this text below to email to your community</strong><br>
Please forward this email below to your community so that they will know how they can help raise funds for %4$s by making their 
online purchases through %2$s. 
Feel free to edit this note or simply send it on as is.
<br><br>

Best regards,<br>
The %2$s staff<br>
-----------------------------------------------------------------------------<br>
Dear %4$s community:<br><br>

We are excited to let you know about a new, simple way that you can support %4$s, without spending an extra cent!
<br><br>
%4$s has signed up with Bring It Local – an online fundraising engine that brings together popular online shopping destinations and a 
full-featured auction site.  When you make a purchase through Bring It Local,
%4$s receives a portion of the money spent at no additional cost to you.

<br><br>
<strong>Start your online shopping with Bring It Local</strong>
<br>

All you have to do is start your shopping on our dedicated <a href="http://www.bringitlocal.com/%3$s">community page</a> on Bring It Local. 
It’s really that easy!

<br><br>
<strong>Click through banners</strong>
<br>
As there are so many different ways to shop with Bring It Local, you are sure to find whatever you need. 
Bring It Local has many online retailers affiliated with the site, including popular sites like Amazon and Target.  
<br><br>
<strong>Create and participate in local auctions</strong>
<br>
The site also offers eBay-like auctions, where you can buy, sell or swap items with other supporters of %4$s, 
like an online, ongoing garage sale. 
If an item isn’t available locally, you can buy, sell or swap items from other communities. 
<br><br>
<strong>Local businesses</strong>
<br>
As its name implies, Bring It Local is all about supporting our local communities, so 
we\'d like to include local businesses as shopping partners. 
If you\'d like your business featured on our dedicated community page <a href="http://www.bringitlocal.com/%3$s">community page</a>, 
please get in touch with us.
<br><br>
<strong>All you need to do: start online shopping here on our community page</strong>
<br>
Bring It Local offers %4$s a new revenue stream without costing our supporters anything.  
It’s surely a win-win for all of us. Just add our <a href="http://www.bringitlocal.com/%3$s">community page</a> to your favorites and 
remember to start your shopping there!

<br><br>
Best regards,<br>
%4$s<br>

';


$activation_link = SITE_PATH . $row_details['username'];

$text_message = sprintf($text_message, $row_details['name'], $setts['sitename'], $row_details['username'],  $row_details['tax_company_name'],$activation_link);
$html_message = sprintf($html_message, $row_details['name'], $setts['sitename'], $row_details['username'],  $row_details['tax_company_name'],$activation_link);

send_mail($row_details['email'], $setts['sitename'] . ' - Help '. $row_details['tax_company_name'] .' raise funds', $text_message, 
	$setts['admin_email'], $html_message, null, $send);
?>
