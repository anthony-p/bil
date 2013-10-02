<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
## (Mods-Store) -> Shopping Cart											##
## mod version	 -> 1.01														##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<script type="text/javascript">
function noenter() {
  return !(window.event && window.event.keyCode == 13); }
</script>
<?=$shopping_cart_header_message;?>

<table width="100%" border="0" cellpadding="0" cellspacing="2" class="subitem">
   <tr>
      <td><img src="themes/<?=$setts['default_theme'];?>/img/system/status1.gif" vspace="5" align="absmiddle"></td>
      <td nowrap><?=MSG_WELCOME;?>, <br>
         <b><? echo ($session->value('user_id')) ? $session->value('username') : MSG_GUEST;?></b></td>
      
		<form action="shopping_cart.php" method="GET" name="form_filter_carts">
      <td class="contentfont" width="100%" align="right" style="padding-right: 10px;">
			<b><?=MSG_FILTER_CARTS;?></b>: <select name="filter_carts" onchange="form_filter_carts.submit();">
				<option value="all" <? echo ($filter_carts == 'all') ? 'selected' : ''; ?>><?=GMSG_ALL;?></option>
				<option value="pending" <? echo ($filter_carts == 'pending') ? 'selected' : ''; ?>><?=GMSG_PENDING;?></option>
				<option value="unpaid" <? echo ($filter_carts == 'unpaid') ? 'selected' : ''; ?>><?=MSG_UNPAID;?></option>
				<option value="paid" <? echo ($filter_carts == 'paid') ? 'selected' : ''; ?>><?=MSG_PAID;?></option>
			</select>
		</td>
      </form>
   </tr>
</table>
<br>
<? if (PC_DM == 1) { ?>
<table width="100%" border="0" cellpadding="4" cellspacing="0" class="subitem">
   <tr>
      <form action="shopping_cart.php" method="GET" name="list_carts">
      	<input type="hidden" name="filter_carts" value="<?=$filter_carts;?>">
      <td class="contentfont" width="100%">
		<? if (!empty($select_cart_drop_down)) { ?>      	
			<b><?=MSG_SELECT_CART;?></b>: <?=$select_cart_drop_down;?>&nbsp;&nbsp;
		<? } ?>
		</td>
		<td>
      </form>   
      
      <? if ($sc_details['sc_purchased'] == 1) { ?>
      <? if ($cart_paid) { ?>
		<? if ($sc_details['shipping_calc_auto']) { ?>
		<form action="invoice_print.php" method="GET" target="_blank">
		<input type="hidden" name="invoice_type" value="sc_invoice">
		<input type="hidden" name="invoice_id" value="<?=$sc_details['sc_id'];?>">
		<td nowrap>
			<input type="submit" name="form_view_invoice" value="<?=MSG_VIEW_INVOICE;?>">
		</td>
		</form>
		<? } ?>
		<form action="message_board.php" method="GET" target="_blank">
		<input type="hidden" name="message_handle" value="6">
		<input type="hidden" name="sc_id" value="<?=$sc_details['sc_id'];?>">
		<td nowrap>
			<input type="submit" name="form_msg_board" value="<?=MSG_MESSAGE_BOARD;?>">
		</td>
		</form>
		<form action="members_area.php" method="GET">
		<input type="hidden" name="do" value="delete_cart">
		<input type="hidden" name="option" value="buyer">
		<input type="hidden" name="sc_id" value="<?=$sc_details['sc_id'];?>">
		<td nowrap>
			<input type="submit" name="form_delete_cart" value="<?=MSG_DELETE;?>" onclick="return confirm('<?=MSG_DELETE_CONFIRM;?>');">
		</td>
		</form>
		<?	} else { 
			$sale_fee = new fees();
			$sale_fee->setts = &$setts;

			$sale_fee->set_fees($sc_details['buyer_id']);

			if (eregi('b', $sale_fee->fee['sa_sale_fee_applies'])) { ?>
		<form action="fee_payment.php" method="GET">
		<input type="hidden" name="do" value="sc_sale_fee_payment">
		<input type="hidden" name="sc_id" value="<?=$sc_details['sc_id'];?>">
		<td nowrap>
		<input type="submit" name="form_pay_sale_fee" value="<?=MSG_PAY_SALE_FEE;?>">
		</td>
		</form>
		<? } else { ?>
		<td nowrap><span class="redfont"><?=MSG_ENDAUCTION_FEE_NOT_PAID;?></span></td>
		<? } ?>
		<? } ?>
		<? } ?>						
   </tr>
	<? if ($sc_details['sc_purchased'] == 1) { ?>
	<tr class="c1">
      <td colspan="5" align="center" style="border-top: 1px solid #CCCCCC; padding: 7px;"><?=GMSG_DATE;?>: <b><?=show_date($sc_details['sc_date']);?></b> 
      	<? if ($cart_paid) { ?>
			&nbsp;|&nbsp; <?=MSG_PAYMENT_STATUS;?>: <b><?=$item->flag_paid($sc_details['flag_paid'], $sc_details['direct_payment_paid']);?></b>
			&nbsp;|&nbsp; <?=MSG_ORDER_STATUS;?>: <b><?=$item->flag_status($sc_details['flag_status']);?></b>
			<? } ?>
			&nbsp; 
		</td> 
	</tr>
	<? } ?>						   
</table>
<br>
<? } ?>
<?=$shopping_cart_message;?>
<?=$shopping_cart_page_content;?>
