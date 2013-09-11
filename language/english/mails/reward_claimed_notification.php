<?
## File Version -> v6.06
## Email File -> send contact form to site admin
## called only from the content_pages.php page!
## added reply-to path in v6.06

if ( !defined('INCLUDED') ) { die("Access Denied"); }

$send = true; // always sent;

## text message - editable
$text_message = 'Dear %1$s,

A new donation has been made for your campaign %2$s. The contributor has claimed this reward in return.

Contribution Summary:
	- The campaign: %2$s
	- Your reward: %3$s
	- Your contribution: $ %4$s
	- Your contribution to the community fund:: $ %5$s
	- Total: $ %6$s
	
Contact Information:
	- Email: %7$s
';
if(!empty($contribution_details['name'])){
	$text_message .= 'Shipping Information:
		- Name: %8$s
		- Country: %9$s
		- Address 1: %10$s
		- Address 2: %11$s
		- City: %12$s
		- Postal Code: %13$s
	';
}

$text_message .= 'Best Regards,
';

## html message - editable
$html_message = 'Dear %1$s,<br>
<br />
A new donation has been made for your campaign <b>%2$s</b>. The contributor has claimed this reward in return.<br>
<br />
<u><i>Contribution Summary:</i></u>
<br />
<ul>
	<li>The campaign: <b>%2$s</b></li>
	<li>Your reward: <b>%3$s</b></li>
	<li>Your contribution: $<b>%4$s</b></li>
	<li>Your contribution to the community fund:: $<b>%5$s</b></li>
	<li>Total: $<b>%6$s</b></li>
</ul>
<br />
<u><i>Contact Information:</i></u>
<br />
<ul>
	<li>Email: <b>%7$s</b></li>
</ul>
<br />';
if(!empty($contribution_details['name'])){
	$html_message .= '<u><i>Shipping Information:</i></u>
	<br />
	<ul>
		<li>Name: <b>%8$s</b></li>
		<li>Country: <b>%9$s</b></li>
		<li>Address 1: <b>%10$s</b></li>
		<li>Address 2: <b>%11$s</b></li>
		<li>City: <b>%12$s</b></li>
		<li>Postal Code: <b>%13$s</b></li>
	</ul>';
}
$html_message .= 'Best Regards,';

$campaign_owner_name = $campaign_owner['first_name'].' '.$campaign_owner['last_name'];
$campaign_owner_email = $campaign_owner['email'];

$total_contributed_amount = doubleval($contribution_details['contribution']) + doubleval($contribution_details['contribution_to_community_fund']);
$text_message = sprintf($text_message, $contribution_details['name'], $reward['campaign_name'], $reward['name'], $contribution_details['contribution'], $contribution_details['contribution_to_community_fund'], $total_contributed_amount, $contribution_details['email'], $contribution_details['name'], $contribution_details['country'], $contribution_details['address1'], $contribution_details['address2'], $contribution_details['city'], $contribution_details['postal_code']);
$html_message = sprintf($html_message, $contribution_details['name'], $reward['campaign_name'], $reward['name'], $contribution_details['contribution'], $contribution_details['contribution_to_community_fund'], $total_contributed_amount, $contribution_details['email'], $contribution_details['name'], $contribution_details['country'], $contribution_details['address1'], $contribution_details['address2'], $contribution_details['city'], $contribution_details['postal_code']);

$email_subject = 'Bring it local - Contribution reward notification: a reward has just been claimed';

send_mail($campaign_owner_email, $email_subject, $text_message, $setts['admin_email'], $html_message, null, $send, '');
?>
