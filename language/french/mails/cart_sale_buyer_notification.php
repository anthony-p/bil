<?
## Email File -> notify buyer on a successful cart purchase
## called only from the shopping_cart.php page!

if ( !defined('INCLUDED') ) { die("Access Denied"); }

$sale_details = $db->get_sql_row("SELECT sc.*, u.username, u.email, u.mail_item_won  
	FROM " . DB_PREFIX . "shopping_carts sc 
	LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=sc.buyer_id
	WHERE sc.sc_id='" . $mail_input_id . "'");

$send = ($sale_details['mail_item_won']) ? true : false;

## text message - editable
$text_message = 'Dear %1$s,

You have successfully purchased through a shopping cart a set of items listed on %2$s.

	- Shopping Cart ID: %3$s
	
For more details about the purchase, please access the "Purchased Carts" page, by clicking on the link below:  

%4$s

After you have accessed the page above, click on the "Message Board" link next to each cart won.
This message board is your direct communication board with the seller. Please use this board to ask 
any post sale questions you might have.

Important: To help resolve any possible disputes ensure you use the board for all queries and updates.

Best regards,
The %5$s staff';

## html message - editable
$html_message = 'Dear %1$s, <br>
<br>
You have successfully purchased through a shopping cart a set of items listed on %2$s.<br>
<ul>
	<li>Shopping Cart ID: <b>%3$s</b> </li>
</ul>
For more details about the purchase, please access the [ <a href="%4$s">Purchased Carts</a> ] page. <br>
<br>
After you have accessed the page above, click on the "Message Board" link next to each cart won. <br>
This message board is your direct communication board with the seller. Please use this board to ask 
any post sale questions you might have. <br>
<br>
Important: To help resolve any possible disputes ensure you use the board for all queries and updates. <br>
<br>
Best regards, <br>
The %5$s staff';


$carts_won_link = SITE_PATH . 'login.php?redirect=' . process_link('members_area', array('page' => 'buying', 'section' => 'purchased_carts'));

$text_message = sprintf($text_message, $sale_details['username'], $setts['sitename'], $mail_input_id, $carts_won_link, $setts['sitename']);
$html_message = sprintf($html_message, $sale_details['username'], $setts['sitename'], $mail_input_id, $carts_won_link, $setts['sitename']);

send_mail($sale_details['email'], 'Shopping Cart ID: ' . $mail_input_id . ' - Successful Purchase', $text_message, 
	$setts['admin_email'], $html_message, null, $send);
?>
