<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<?=isset($search_transactions_box)?$search_transactions_box:'';?>
<br>
<table border="0" cellpadding="0" cellspacing="0" class="openAuctions border">
   <tr>
      <td colspan="10" class="c7"><? echo ($page == 'summary') ? '<b>' . MSG_LAST_FIVE_LISTED_ITEMS . '</b>' : '<b>' . MSG_MM_OPEN_AUCTIONS . '</b> (' . $nb_items . ' ' . MSG_ITEMS . ')';?></td>
   </tr>
    <tr> <td colspan="10"  style="height: 10px;"></td></tr>
   <tr>
      <td class="membmenu" align="center"><?=MSG_PICTURE;?></td>
      <td class="membmenu"><?=MSG_AUCTION_ID;?><br><?=isset($page_order_auction_id)?$page_order_auction_id:'';?></td>
      <td class="membmenu"><?=MSG_ITEM_TITLE;?><br><?=isset($page_order_itemname)?$page_order_itemname:'';?></td>
      <td class="membmenu" align="center"><?=GMSG_START_TIME;?><br><?=isset($page_order_start_time)?$page_order_start_time:'';?></td>
      <td class="membmenu" align="center"><?=GMSG_END_TIME;?><br><?=isset($page_order_end_time)?$page_order_end_time:'';?></td>
      <td class="membmenu" align="center"><?=MSG_NR_BIDS;?><br><?=isset($page_order_nb_bids)?$page_order_nb_bids:'';?></td>
      <td class="membmenu" align="center"><?=MSG_AUTO_RELIST;?></td>
      <td class="membmenu" align="center"><?=MSG_START_BID;?><br><?=isset($page_order_start_bid)?$page_order_start_bid:'';?></td>
      <td class="membmenu" align="center"><?=MSG_MAX_BID;?><br><?=isset($page_order_max_bid)?$page_order_max_bid:'';?></td>
      <td class="membmenu" align="center"><?=GMSG_OPTIONS;?></td>
   </tr>
   <?=$open_auctions_content;?>
   <? if ($nb_items>0) { ?>
   <tr>
      <td colspan="10" align="center" class="contentfont"><?=$pagination;?></td>
   </tr>
	<? } ?>
</table>

