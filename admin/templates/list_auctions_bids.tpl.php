<?
#################################################################
## PHP Pro Bid v6.04															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>

<div class="mainhead"><img src="images/user.gif" align="absmiddle">
   <?=$header_section;?>
</div>
<?=$bid_sucess_msg;?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
   <tr>
      <td width="4"><img src="images/c1.gif" width="4" height="4"></td>
      <td width="100%" class="ftop"><img src="images/pixel.gif" width="1" height="1"></td>
      <td width="4"><img src="images/c2.gif" width="4" height="4"></td>
   </tr>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="3" class="fside">
   <tr class="c3">
      <td colspan="2"><img src="images/subt.gif" align="absmiddle" hspace="4" vspace="2"> <b>
         <?=strtoupper($subpage_title);?>
         </b></td>
   </tr>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="3" class="fside">
   <tr>
      <td colspan="8" class="c3" align="center">
          <b>View bids for auction [#<?=$auction_details['auction_id']. " " . $auction_details['name'] ;?>]</b>
      </td>
   </tr>
   <tr>
      <td colspan="8" align="center"><?=$query_results_message;?></td>
   </tr>
   <tr class="c4">
      <td width="70">Username</td>
      <td width="100" align="center"><?=MSG_BID_AMOUNT;?></td>
      <td align="center" width="150"><?=GMSG_DATE?></td>
      <td align="center" width="60"><?=GMSG_QUANTITY;?></td>
      <td align="center" width="60"><?=AMSG_BID_STATUS;?></td>
      <td align="center" width="200"><?=MSG_REMOVE_BIDS;?></td>
   </tr>
   <?=$bid_history_content;?>
</table>

<form action="list_auctions_bids.php" method="post">
    <input type="hidden" name="auction_id" value="<?=$auction_details['auction_id'];?>">
    <input type="hidden" name="action" value="bid_confirm">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="fside">
        <tr>
            <td colspan="8" class="c3" align="center">
                <b>Bid on auction [#<?=$auction_details['auction_id']. " " . $auction_details['name'] ;?>]</b>
            </td>
        </tr>
            <tr class="c1">
                <td nowrap width="60px;" style="padding-left: 5px;">Users</td>
                <td><?=$users?></td>
                <td width="100%"></td>
            </tr>
            <tr>
                <td class="c1" style="padding-left: 5px;"><?=GMSG_QUANTITY;?> :</td>
                <td><input name="quantity" type="text" id="quantity" value="1" size="3"></td>
                <td width="100%"></td>
            </tr>
            <tr>
                <td class="c1" style="padding-left: 5px;"><strong><?=$auction_details['currency'];?></strong></td>
                <td>
                    <input name="max_bid" type="text" id="max_bid" size="7" />
                    <div class="minBid"><?=MSG_MINIMUM_BID;?>: <? echo $fees->display_amount($item->min_bid_amount($item_details), $item_details['currency']);?></div>
                </td>
                <td width="100%"></td>
            </tr>
            <tr class="c2">
                <td></td>
                <td><input name="form_place_bid" type="submit" id="form_place_bid" value="<?=MSG_PLACE_BID;?>" ></td>
                <td width="100%"></td>
            </tr>
    </table>
</form>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
   <tr>
      <td width="4"><img src="images/c3.gif" width="4" height="4"></td>
      <td width="100%" class="fbottom"><img src="images/pixel.gif" width="1" height="1"></td>
      <td width="4"><img src="images/c4.gif" width="4" height="4"></td>
   </tr>
</table>
