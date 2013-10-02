<?
#################################################################
## PHP Pro Bid v6.04															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<br>
<table border="0" cellpadding="0" cellspacing="0" class="currentBids border">
   <tr>
      <td colspan="8" class="c7"><? echo ($page == 'summary') ? '<b>' . MSG_LAST_FIVE_BIDS . '</b>' : '<b>' . MSG_MM_CURRENT_BIDS . '</b> (' . $nb_bids . ' ' . MSG_BIDS . ')';?></td>
   </tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
   <tr>
      <td class="membmenu" align="center"><?=MSG_PICTURE;?></td>
      <td class="membmenu"><?=MSG_AUCTION_ID;?>
         <br>
         <?=$page_order_auction_id;?></td>
      <td class="membmenu"><?=MSG_ITEM_TITLE;?>
         <br>
         <?=$page_order_itemname;?></td>
      <td class="membmenu" align="center"><?=MSG_BID_AMOUNT;?>
         <br>
         <?=$page_order_bid_amount;?></td>
      <td class="membmenu" align="center"><?=MSG_PROXY_BID;?>
         <br>
         <?=$page_order_bid_proxy;?></td>
      <td class="membmenu" align="center"><?=GMSG_DATE;?>
         <br>
         <?=$page_order_bid_date;?></td>
      <td class="membmenu" align="center"><?=GMSG_STATUS;?></td>
      <td class="membmenu" align="center"><?=GMSG_OPTIONS;?></td>
   </tr>
   <?=$current_bids_content;?>
   <? if ($nb_bids>0) { ?>
   <tr>
      <td colspan="8" align="center" class="contentfont"><?=$pagination;?></td>
   </tr>
	<? } ?>
</table>
