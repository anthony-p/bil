<?
#################################################################
## PHP Pro Bid v6.00															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
## (Mods-Store) -> Shopping Cart											##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<table border="0" cellspacing="2" cellpadding="3" class="border">
   <tr>
      <td colspan="4" class="buyingtitle"><b><?=MSG_BIDDING_TOTALS;?></b></td>
   </tr>
   <tr class="c1">
      <td><?=MSG_MM_WON_ITEMS;?>: <b><?=$nb_won_items;?></b></td>
 		<? if ($setts['enable_stores']) { ?>
      <td><?=MSG_MM_PURCHASED_CARTS;?>: <b><?=$nb_purchased_carts;?></b></td>
   	<? } ?>
     <td><?=MSG_MM_CURRENT_BIDS;?>: <b><?=$nb_current_bids;?></b></td>
      <td><?=MSG_ACTIVE_BIDS;?>: <b><?=$nb_winning;?></b></td>
   </tr>
</table>
