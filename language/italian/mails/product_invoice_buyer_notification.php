<?
## File Version -> v6.05
## Email File -> notify a buyer when a seller has created a new product invoice for him
## called only from the $item->send_invoice() function

if ( !defined('INCLUDED') ) { die("Access Denied"); }

$row_details = $this->get_sql_row("SELECT u.name, u.email, w.invoice_id FROM " . DB_PREFIX . "winners w 
	LEFT JOIN " . DB_PREFIX . "users u on u.user_id=w.buyer_id WHERE 
	w.invoice_id='" . $mail_input_id . "'");
$send = true; ## always send

## text message - editable
$text_message = 'Dear %1$s,
	
A new invoice has been generated by a seller for one or more items you have purchased from him.
	
To view the invoice, please click on the URL below:
	
%2$s
		
Best regards,
The %3$s staff';
	
## html message - editable
$html_message = 'Dear %1$s, <br>
<br>
A new invoice has been generated by a seller for one or more items you have purchased from him. <br>
<br>
Please [ <a href="%2$s">click here</a> ] to view the invoice. <br>
<br>
Best regards, <br>
The %3$s staff';
	
	
$invoice_link = SITE_PATH . 'login.php?redirect=' . process_link('invoice_print', array('invoice_type' => 'product_invoice', 'invoice_id' => $mail_input_id), true);
	
$text_message = sprintf($text_message, $row_details['name'], $invoice_link, $this->setts['sitename']);
$html_message = sprintf($html_message, $row_details['name'], $invoice_link, $this->setts['sitename']);

send_mail($row_details['email'], 'Product Invoice Received - Invoice ID: ' . $row_details['invoice_id'], $text_message, 
	$this->setts['admin_email'], $html_message, null, $send);
?>
