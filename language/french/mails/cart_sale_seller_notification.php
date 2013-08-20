<?
## Email File -> notify seller on a successful cart sale
## called only from the shopping_cart.php page!

if ( !defined('INCLUDED') ) { die("Access Denied"); }

$sale_details = $db->get_sql_row("SELECT sc.*, u.username, u.email, u.mail_item_sold  
	FROM " . DB_PREFIX . "shopping_carts sc 
	LEFT JOIN " . DB_PREFIX . "users u ON u.user_id=sc.seller_id
	WHERE sc.sc_id='" . $mail_input_id . "'");

$send = ($sale_details['mail_item_sold']) ? true : false;

## text message - editable
$text_message = 'Dear %1$s,

A new set of items you have listed on %2$s have been sold through a shopping cart.

	- Shopping Cart ID: %3$s
	
For more details about the sale, please access the "Sold Carts" page, by clicking on the link below:  

%4$s

After you have accessed the page above, click on the "Message Board" link next to each cart sold.
This message board is your direct communication board with your buyer. Please use this board to answer 
any questions the buyer may have regarding payment and delivery.

Important: To help resolve any possible disputes ensure you use the board for all queries and updates.

Best regards,
The %5$s staff';

## html message - editable
$html_message = 'Dear %1$s, <br>
<br>
A new set of items you have listed on %2$s have been sold through a shopping cart. <br>
<br>
<ul>
	<li>Shopping Cart ID: <b>%3$s</b> </li>
</ul>
For more details about the sale, please access the [ <a href="%4$s">Sold Carts</a> ] page. <br>
<br>
After you have accessed the page above, click on the "Message Board" link next to each cart sold. <br>
This message board is your direct communication board with your buyer. Please use this board to answer 
any questions the buyer may have regarding payment and delivery. <br>
<br>
Important: To help resolve any possible disputes ensure you use the board for all queries and updates. <br>
<br>
Best regards, <br>
The %5$s staff';


$carts_sold_link = SITE_PATH . 'login.php?redirect=' . process_link('members_area', array('page' => 'selling', 'section' => 'sold_carts'));

$text_message = sprintf($text_message, $sale_details['username'], $setts['sitename'], $mail_input_id, $carts_sold_link, $setts['sitename']);
$html_message = sprintf($html_message, $sale_details['username'], $setts['sitename'], $mail_input_id, $carts_sold_link, $setts['sitename']);

send_mail($sale_details['email'], 'Shopping Cart ID: ' . $mail_input_id . ' - Successful Sale', $text_message, 
	$setts['admin_email'], $html_message, null, $send);
?>
