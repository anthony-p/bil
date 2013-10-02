<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
## (Mods-Store) -> Shopping Cart											##
#################################################################


if ( !defined('INCLUDED') ) { die("Access Denied"); }

global $setts;
?>

<script LANGUAGE="JavaScript">
<!--
function confirm_postage()
{
var agree=confirm("<?=MSG_UPDATE_POSTAGE_CONFIRMATION;?>");
if (agree)
	return true ;
else
	return false ;
}
// -->
</script>

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="border">
   <tr>
      <td class="contentfont"><table width="100%" border="0" cellpadding="3" cellspacing="2">
            <tr class="c4">
               <td><?=MSG_ITEM_ID;?> - <?=MSG_ITEM_NAME;?></td>
               <td align="center"><?=GMSG_PRICE;?></td>
               <td align="center"><?=GMSG_QUANTITY;?></td>
            </tr>
            <tr class="c5">
               <td width="100%"><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="1" height="1"></td>
               <td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="110" height="1"></td>
               <td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="70" height="1"></td>
            </tr>
            <?=$shopping_cart_content;?>
            <tr>
               <td colspan="5" class="c4"></td>
            </tr>
         </table></td>
   </tr>
   <? if ($edit_postage) { ?>
   <form action="" method="POST">
   <input type="hidden" name="sc_id" value="<?=$sc_id;?>">
   <? } ?>
   <tr>
      <td class="contentfont"><table border="0" cellpadding="3" cellspacing="2" align="right">
            <tr class="c5">
               <td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="130" height="1"></td>
               <td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="230" height="1"></td>
            <tr>
               <td class="c4"><?=MSG_CART_SUBTOTAL;?></td>
               <td class="c1"><?=$cart_subtotal;?></td>
            </tr>
            <!--
            <tr>
               <td class="c4"><?=MSG_SHIPPING_METHOD;?></td>
               <td class="c1"><? echo ($edit_postage) ? '<input type="text" name="shipping_method" value="' . $shipping_method . '" style="width: 90%;">' : $shipping_method;?></td>
            </tr>
            -->
            <tr>
               <td class="c4"><?=MSG_SHIPPING;?></td>
               <td class="c1"><? echo ($edit_postage) ? ($currency . ' <input type="text" name="sc_postage" value="' . $shipping_total_value . '" size="8">') : $shipping_total;?></td>
            </tr>
            <tr>
               <td class="c4"><?=MSG_INSURANCE;?></td>
               <td class="c1"><?=$insurance_total;?></td>
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
			   <? if ($edit_postage) { ?>
            <tr>
               <td class="c4" colspan="2"></td>
            </tr>
            <tr>
               <td colspan="2" align="right"><input type="submit" name="form_update_sc_postage" value="<?=MSG_FINALIZE_INVOICE;?>" style="font-size:10px; width: 140px;" onclick="return confirm_postage();"></td>
            </tr>
            <? } ?>
         </table></td>
   </tr>
   <? if ($edit_postage) { ?>
   </form>
   <? } ?>
   <? if (!$is_invoice && !empty($shipping_message)) { ?>
   <tr>
      <td class="contentfont"><table width="100%" border="0" cellpadding="3" cellspacing="2">
            <tr>
               <td colspan="4" align="right"><? echo ($edit_postage) ? MSG_EDIT_POSTAGE_EXPL : $shipping_message;?></td>
            </tr>
         </table></td>
   </tr>
   <? } ?>
</table>
