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



<blockquote>
<h4>Crowdfunding is fundraising by a different name</h4>
Crowdfunding is a trendy new word for an activity that is as old as the hills - fundraising. It\'s a hi-tech version of people getting together to pool their funds to support some activity.
<br><br>
It comes in different forms. Most commonly, the term refers to donation based funding. But there is also a growing interest in equity funding where people purchase shares or part ownership in a business.
<br><br>
What is completely new is the way the internet makes it possible to reach vastly larger numbers of people with an infinitely broader range of fundraising projects to support. And it is this change that opens the door beyond equity and donation crowdfunding to a third type, what we call community crowdfunding.
<br>
<h4>The spirit of Occupy lives on in new economy initiatives</h4>
It is generally understood that our middle-class has been sinking steadily over the last few decades. Money is extracted from our communities every time we turn around. That money goes to an increasingly small number of huge corporations that are not local to any of us.  Corporations whose money is hidden offshore, who employ vast numbers of temp workers often in unregulated developing countries working in sub-optimal conditions. The CEOs of these corporations continue to earn unspeakable amounts, hundreds of times more than their workers.  And meanwhile, many of us are sinking deeper and deeper into debt to a bank that is too big to fail.
<br><br>
That money that leaves our communities is the oxygen of the corporatist system. The system is failing because it\'s depleting its own oxygen source.
<br><br>
Donation crowdfunding and equity crowdfunding don\'t change the fundamentals of any of this. Even if they are in support of a beneficial project the underlying economic system isn\'t touched.
<br><br>
For a given community, receiving donations  is like taking money out of one pocket and putting it into the other. Donations fail even on that count however, because a significant chunk is removed before it even arrives in the other pocket. It is lost in the form of a paypal or credit card fee and the crowdfunding platform fees.  After the money is collected much of it evaporates before it gets used to support the core mission of the recipient.
<br>
<h4>Community Crowdfunding goes beyond donations</h4>
Community Crowdfunding goes beyond donations and  has the seeds of something genuinely revolutionary. It can challenge some of the basic models of our corporatist system. It can change consumer habits and redirect flows of money. It can restore choice to our consumption. It can become a form of voting; voting to keep our money inside of our communities.
<br><br>
<h4>Community Crowdfunding is not left-wing or right-wing</h4>

This is wealth transfer that has nothing to do with singing the Internationale or re-education camps. It has nothing to do with left-wing or right-wing politics. It has everything to do with democracy. It lends us a meaningful vote in the way that speaks the loudest - the way we spend our money.
<br>
<h4>Local is not a place: For us it means a community of people bonded over a common interest or goal</h4>

For us,  \'local\' and çommunity\' just mean any group of people who come together in support of a common interest. They could be youth programs, churches, synagogues, mosques, families and friends of people needing medical care,  or a town or a neighborhood school group. They  might be independent journalists who are speaking truth to power and rely on funds that come without the strings of corporate financial control. A community can be any size and even international.  When money stays within that community there  is a multiplier effect that benefits everyone.
<br><br>
We\'ve set out to establish one model of how money can be kept in the community.
<br><br>
<h4>Donations are just the start: It\'s not about philanthropy</h4>
We accept donations and have a donation button. It is familiar and recognizable to people and they use it to the immediate benefit of the recipients.
<br>
<h4>Buy and sell from each other</h4>
More importantly, we also provide a way for people to buy and sell things directly to one another. We provide a way to bring local brick and mortar merchants into the picture to encourage local commerce. And, finally, if people can\'t find what they need on the site and they absolutely must go to shop at the big box stores, we capture a percentage which will go back to whatever project that user has chosen to support.
<br><br><h4>Our experiment in democracy: the Community Fund</h4>
In addition, we have our <a href="<?=$cfc_url;?>"><?=MSG_COMMUNITY_FOUND;?></a>. Site users are encouraged to contribute and we vote together to decide how to use the funds which  goes to one of the campaigns on our site.
<br>
<h4>Bring It Local: Corporate ownership and a new economy</h4>
And last but central to the concept comes ownership of our company itself. To put our money where our mouth is we\'ll open up the ownership of Bring It Local to the people who use it. At that point the money comes literally full circle and all spending on the site becomes a form of reinvestment in the community.
<br><br>
This is the outline of the path that we have chosen to follow. Many people are coming up with creative ideas to address the same problems. We\'d love to hear about other ideas that you might know of and would like to share. Let\'s not reinvent the wheel in isolation of each other. We need to all come together to be more than the sum of our parts.



<br><br>
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

$message_header = 'Our Mission: community crowdfunding';

$template->set('message_header', header5($message_header));
$template->set('message_content', $message_content);

$template_output .= $template->process('single_message.tpl.php');

include_once ('global_footer.php');

echo $template_output;

?>
