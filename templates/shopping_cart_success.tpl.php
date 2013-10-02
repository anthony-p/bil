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
<?=$shopping_cart_success_message;?>

<?=$shopping_cart_success_view_cart;?>

<? if (!empty($direct_payment_box)) { ?>
<br>
<table width="100%" border="0" cellpadding="3" cellspacing="2" class="border">
   <tr height="21">
      <td colspan="5" class="c4"><strong>
         <?=MSG_DIRECT_PAYMENT;?>
         </strong></td>
   </tr>
   <tr>
      <td colspan="5" class="c5"><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="1" height="1"></td>
   </tr>
   <tr>
      <td colspan="5" class="border"><?=$direct_payment_box;?></td>
   </tr>
</table>
<? } ?>
