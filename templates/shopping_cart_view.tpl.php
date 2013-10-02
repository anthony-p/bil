<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
## (Mods-Store) -> Shopping Cart											##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<script LANGUAGE="JavaScript">
<!--
function confirm_checkout()
{
var agree=confirm("<?=MSG_CHECKOUT_CONFIRMATION;?>");
if (agree)
	return true ;
else
	return false ;
}
// -->
</script>

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="border">
   <form action="shopping_cart.php" method="post">
      <input type="hidden" name="action" value="<?=$action;?>">
      <input type="hidden" name="sc_id" value="<?=$sc_id;?>">
      <tr>
         <td class="contentfont">
         	<table width="100%" border="0" cellpadding="3" cellspacing="2">
            	<tr class="c4">
               	<td><?=MSG_ITEM_ID;?> - <?=MSG_ITEM_NAME;?></td>
                  <td align="center"><?=GMSG_PRICE;?></td>
                  <td align="center"><?=GMSG_STOCK_STATUS;?></td>
                  <td align="center"><?=GMSG_QUANTITY;?></td>
                  <td align="center"><?=MSG_REMOVE;?></td>
               </tr>
					<tr class="c5">
						<td width="100%"><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="1" height="1"></td>
						<td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="110" height="1"></td>
						<td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="90" height="1"></td>
						<td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="70" height="1"></td>
						<td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="70" height="1"></td>
					</tr>
					<?=$shopping_cart_content;?>
            	<tr>
            		<td colspan="5" class="c4"></td>
            	</tr>
            </table>
			</td>
		</tr>
      <tr>
         <td class="contentfont">
         	<table width="100%">
         		<tr>
         			<td width="50%" valign="bottom">
							<table border="0" cellpadding="3" cellspacing="2" width="90%">
								<tr>
									<td class="c4"><?=MSG_COMMENTS;?></td>
								</tr>  
								<tr>
               				<td class="c1"><textarea name="invoice_comments" style="width: 100%; height: 75px;"><?=$invoice_comments;?></textarea></td>
               			</tr>
							</table>       			
         			</td>
         			<td valign="top">
			            <table border="0" cellpadding="3" cellspacing="2" align="right">
								<tr class="c5">
									<td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="130" height="1"></td>
									<td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="325" height="1"></td>
								</tr>
								<tr>
									<td class="c4"><?=MSG_CART_SUBTOTAL;?></td>
			                  <td class="c1"><?=$cart_subtotal;?></td>
								</tr>
								<? if (!empty($shipping_method)) { ?>
			               <tr>
			               	<td class="c4"><?=MSG_SHIPPING_METHOD;?></td>
									<td class="c1"><? echo $shipping_method;?></td>
								</tr>
								<? } ?>
			               <tr>
			               	<td class="c4"><?=MSG_SHIPPING;?></td>
									<td class="c1"><? echo ($session->value('user_id')) ? $shipping_total : MSG_LOGIN_TO_CALC_SHIPPING;?></td>
								</tr>
			               <tr>
			               	<td class="c4"><?=MSG_INSURANCE;?></td>
			                  <td class="c1"><?=$insurance_total;?></td>
								</tr>
			               <tr>
			               	<td align="right"><?=MSG_APPLY_INSURANCE;?></td>
			                  <td><input type="checkbox" name="apply_insurance" value="1" <? echo ($apply_insurance) ? 'checked' : ''; ?>></td>
								</tr>
								<? if ($tax_details['apply']) { ?>
			               <tr>
			               	<td class="c4"></td>
			                  <td class="c4"></td>
								</tr>
			               <tr>
			               	<td class="c4"><?=MSG_TAX_RATE;?></td>
			               	<td class="c1"><?=$tax_details['tax_rate'];?></td>
								</tr>
			               <tr>
			               	<td class="c4"><?=MSG_TAX_AMOUNT;?></td>
			                  <td class="c1"><?=$total_tax;?></td>
								</tr>
								<? } ?>
			               <tr>
			               	<td class="c4"></td>
			                  <td class="c4"></td>
								</tr>
			               <tr>
			               	<td class="c4"><?=GMSG_TOTAL;?></td>
			                  <td class="c1"><?=$total_amount;?></td>
								</tr>
							</table>
         			
         			</td>
         		</tr>
         	</table>
			</td>
		</tr>
      <tr>
         <td class="contentfont">
            <table width="100%" border="0" cellpadding="3" cellspacing="2">
            	<tr>
            		<td colspan="4" align="right"><?=$shipping_message;?></td>
            	</tr>
            	<tr>
            		<td colspan="4" class="c4"></td>
            	</tr>
               <tr>
                  <td width="100%">&nbsp;</td>
                  <td nowrap align="center"><input name="form_continue_shopping" type="submit" id="form_continue_shopping" value="<?=MSG_CONTINUE_SHOPPING;?>"></td>
                  <td nowrap align="center"><input name="form_update_cart" type="submit" id="form_update_cart" value="<?=MSG_UPDATE_CART;?>"></td>
                  <td nowrap align="center"><input name="form_checkout" type="submit" id="form_checkout" value="<?=MSG_CHECKOUT;?>" <?=$checkout_disabled;?> <? echo ($session->value('user_id')) ? 'onclick="return confirm_checkout();"' : '';?>></td>
               </tr>
            </table>
   		</td>
   	</tr>
   </form>
</table>
