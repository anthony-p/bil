<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<? echo (defined("IS_SHOP") && IS_SHOP == 1) ? $shop_header : $header_browse_auctions . ((IS_CATEGORIES != 1) ? '<br>' : '');?>
<? echo (IS_CATEGORIES == 1) ? $categories_header : '';?>

<div class="msg"><?= MSG_PARTNER_SUBSCR_INFORMATION; ?></div>

<div class="alphabetically">
    <a href="/global_partners.php"><?= MSG_PARTNER_PAGINATION_ALL; ?></a>
    <?php foreach($alphabetically as $key=>$value): ?>
        <?php if($value == ""): ?>
            <?php echo $key; ?>
        <?php else: ?>
            <?php echo $value; ?>
        <?php endif; ?>
    <?php endforeach; ?>

</div>

<table border="0" cellpadding="3" cellspacing="0" id="retailerList">
   <form action="compare_items.php" method="POST">
   <input type="hidden" name="redirect" value="<?=$redirect;?>">
	<tr valign="top">
  <!--     <td align="center"></td> --> 
 
	 <th align="center">
	  <!-- 
	  <input type="submit" name="form_compare_items" value="<?=MSG_COMPARE;?>">
	  --> 
	  </th> 
      <th class="link"><span><?=MSG_PARTNER_NAME;?></span><?=$page_order_itemname;?></th>
      <th class="percent"><?= MSG_PARTNER_PERCENT_GIVEBACK; ?></th>
      <th class="go"></th>
      <!--
      <td align="center"><?=MSG_START_BID;?><br><?=$page_order_start_price;?></td>
      <td align="center"><?=MSG_MAX_BID;?><br><?=$page_order_max_bid;?></td>
     
      <td align="center"><?=MSG_NR_BIDS;?><br><?=$page_order_nb_bids;?></td>
      <td align="center"><?=MSG_CURRENTLY;?><br><?=$page_order_current_price;?></td>      
      <td align="center"><?=MSG_ENDS;?><br><?=$page_order_end_time;?></td>
	   -->
   </tr>
<!-- 
   <tr class="<? echo (IS_SHOP == 1) ? 'c5_shop' : 'c5';?>">
	  <td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="15" height="1"></td>
      <td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="90" height="1"></td>
      <td width="100%"><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="100%" height="1"></td>
      
      <td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="80" height="1"></td>
      <td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="80" height="1"></td>
  
      <td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="50" height="1"></td>
      <td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="80" height="1"></td>
      <td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="100" height="1"></td>

 </tr>
 --> 
 <?=$browse_auctions_content;?>
   <? if ($nb_items>0) { ?>
   <tr>
      <td colspan="3" align="center" class="pagination"><?=$pagination;?></td>
   </tr>
	<? } ?>
	</form>
</table>

<? echo (defined("IS_SHOP") && IS_SHOP == 1) ? $shop_footer : '';?>
<? echo (IS_CATEGORIES == 1) ? $categories_footer : '';?>

