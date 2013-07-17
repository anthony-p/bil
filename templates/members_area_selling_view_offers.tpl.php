<?
#################################################################
## PHP Pro Bid v6.04															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>

<br>
<table width="100%" border="0" cellpadding="3" cellspacing="2" class="border">
   <tr>
      <td colspan="2" class="c7"><?=MSG_VIEW_OFFERS_FOR;?>
         <?=MSG_AUCTION_ID;?>
         : <b>
         <?=$item_details['auction_id'];?>
         </b> - <b>
         <?=$item_details['name'];?>
         </b></td>
   </tr>
   <tr>
      <td colspan="2" class="c4"<img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="1" height="1"></td>
   </tr>
   <? if ($item_details['auction_type']=='dutch') { ?>
   <tr class="c1">
      <td align="right"><strong>
         <?=MSG_AVAILABLE_QUANTITY;?>
         </strong></td>
      <td valign="top"><?=$item_details['quantity'];?></td>
   </tr>
   <? } ?>
   <tr class="c1">
      <td align="right"><strong>
         <?=MSG_CURRENT_BID;?>
         </strong></td>
      <td><? echo $fees->display_amount($item_details['max_bid'], $item_details['currency']);?></td>
   </tr>
   <tr class="c1">
      <td align="right"><strong>
         <?=MSG_NR_BIDS;?>
         </strong></td>
      <td><?=$item_details['nb_bids'];?></td>
   </tr>
   <tr class="c4">
      <td></td>
      <td></td>
   </tr>
   <tr class="c1">
      <td width="150" align="right"><?=MSG_SHIPPING_CONDITIONS;?></td>
      <td><? echo ($item_details['shipping_method'] == 1) ? MSG_BUYER_PAYS_SHIPPING : MSG_SELLER_PAYS_SHIPPING; ?></td>
   </tr>
   <? if ($item_details['shipping_int'] == 1) { ?>
   <tr>
      <td>&nbsp;</td>
      <td><?=MSG_SELLER_SHIPS_INT;?></td>
   </tr>
   <? } ?>
   <tr class="c1">
      <td width="150" align="right"><?=MSG_POSTAGE;?></td>
      <td><?=$fees->display_amount($item_details['postage_amount'], $item_details['currency']); ?></td>
   </tr>
   <tr class="c1">
      <td width="150" align="right"><?=MSG_INSURANCE;?></td>
      <td><?=$fees->display_amount($item_details['insurance_amount'], $item_details['currency']); ?></td>
   </tr>
   <tr class="c1">
      <td width="150" align="right"><?=MSG_SHIP_METHOD;?></td>
      <td><?=$item_details['type_service'];?></td>
   </tr>
   <tr class="c4">
      <td></td>
      <td></td>
   </tr>
   <? if ($item_details['direct_payment']) { ?>
   <tr class="c1">
      <td width="150" align="right"><b>
         <?=MSG_DIRECT_PAYMENT;?>
         </b></td>
      <td><?=$direct_payment_methods_display;?></td>
   </tr>
   <tr class="c4">
      <td></td>
      <td></td>
   </tr>
   <? } ?>
   <? if ($item_details['payment_methods']) { ?>
   <tr class="c1">
      <td width="150" align="right"><b>
         <?=MSG_OFFLINE_PAYMENT;?>
         </b></td>
      <td><?=$offline_payment_methods_display;?></td>
   </tr>
   <tr class="c4">
      <td></td>
      <td></td>
   </tr>
   <? } ?>
   <tr class="c5">
      <td width="150"><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="150" height="1"></td>
      <td width="100%"><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="1" height="1"></td>
   </tr>
   <? if ($winning_bids_content) { ?>
   <tr class="c4">
      <td colspan="2"><?=MSG_WINNER_S;?></td>
   </tr>
   <tr>
      <td colspan="2" class="border">
      	<table width="100%" border="0" cellpadding="2" cellspacing="2" class="contentfont">
      		<tr class="membmenu">
      			<td><b><?=MSG_USERNAME;?></b></td>
      			<td width="80" align="center"><b><?=GMSG_QUANTITY;?></b></td>
      			<td width="100" align="center"><b><?=MSG_BID_AMOUNT;?></b></td>
      			<td width="150" align="center"><b><?=MSG_PURCHASE_DATE;?></b></td>
      			<td width="80" align="center"><b><?=MSG_STATUS;?></b></td>
      		</tr>
			   <?=$winning_bids_content;?>
      	</table>
      </td>
   </tr>
   <? } ?>      
   <? if ($make_offer_content) { ?>
   <tr class="c4">
      <td colspan="2"><?=MSG_AUCTION_OFFERS;?></td>
   </tr>
   <tr>
      <td colspan="2" class="border">
      	<table width="100%" border="0" cellpadding="2" cellspacing="2" class="contentfont">
      		<tr class="membmenu">
      			<td><b><?=MSG_USERNAME;?></b></td>
      			<td width="80" align="center"><b><?=GMSG_QUANTITY;?></b></td>
      			<td width="100" align="center"><b><?=GMSG_AMOUNT;?></b></td>
      			<td width="120" align="center"><b><?=MSG_ACCEPTED;?></b></td>
      			<td width="180" align="center"><b><?=GMSG_OPTIONS;?></b></td>
      		</tr>
			   <?=$make_offer_content;?>
      	</table>
      </td>
   </tr>
   <? } ?>
   <? if ($reserve_offer_content) { ?>
   <tr class="c4">
      <td colspan="2"><?=MSG_RESERVE_OFFERS;?></td>
   </tr>
   <tr>
      <td colspan="2" class="border">
      	<table width="100%" border="0" cellpadding="2" cellspacing="2" class="contentfont">
      		<tr class="membmenu">
      			<td><b><?=MSG_USERNAME;?></b></td>
      			<td width="80" align="center"><b><?=GMSG_QUANTITY;?></b></td>
      			<td width="100" align="center"><b><?=MSG_BID_AMOUNT;?></b></td>
      			<td width="120" align="center"><b><?=MSG_ACCEPTED;?></b></td>
      			<td width="180" align="center"><b><?=GMSG_OPTIONS;?></b></td>
      		</tr>
			   <?=$reserve_offer_content;?>
      	</table>
      </td>
   </tr>
   <? } ?>
   <? if ($second_chance_content) { ?>
   <tr class="c4">
      <td colspan="2"><?=MSG_SECOND_CHANCE_PURCHASING;?></td>
   </tr>
   <tr>
      <td colspan="2" class="border">
      	<table width="100%" border="0" cellpadding="2" cellspacing="2" class="contentfont">
      		<tr class="membmenu">
      			<td><b><?=MSG_USERNAME;?></b></td>
      			<td width="80" align="center"><b><?=GMSG_QUANTITY;?></b></td>
      			<td width="100" align="center"><b><?=MSG_BID_AMOUNT;?></b></td>
      			<td width="180" align="center"><b><?=GMSG_OPTIONS;?></b></td>
      		</tr>
			   <?=$second_chance_content;?>
      	</table>
      </td>
   </tr>
   <? } ?>
   <? if ($swap_offer_content) { ?>
   <tr class="c4">
      <td colspan="2"><?=MSG_SWAP_OFFERS;?></td>
   </tr>
   <tr>
      <td colspan="2" class="border">
      	<table width="100%" border="0" cellpadding="2" cellspacing="2" class="contentfont">
      		<tr class="membmenu">
      			<td width="150"><b><?=MSG_USERNAME;?></b></td>
      			<td width="80" align="center"><b><?=GMSG_QUANTITY;?></b></td>
      			<td align="center"><b><?=GMSG_DESCRIPTION;?></b></td>
      			<td width="120" align="center"><b><?=MSG_ACCEPTED;?></b></td>
      			<td width="180" align="center"><b><?=GMSG_OPTIONS;?></b></td>
      		</tr>
			   <?=$swap_offer_content;?>
      	</table>
      </td>
   </tr>
   <? } ?>
</table>
