<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<? echo (IS_SHOP == 1) ? $shop_header : $header_browse_auctions . ((IS_CATEGORIES != 1) ? '<br>' : '');?>
<? echo (IS_CATEGORIES == 1) ? $categories_header : '';?>
<div id="browseAuctions" class="bigBlock clearfix">
	<div class="blockTitle">Browse Auctions</div>
	<div class="block">
		<table id="browseAuctionsTbl" width="100%" border="0" cellpadding="0" cellspacing="0" class="<? echo (IS_SHOP == 1 || IS_CATEGORIES == 1) ? '' : 'border'; ?>">
			<form action="compare_items.php" method="POST">
			<input type="hidden" name="redirect" value="<?=$redirect;?>">
			<tr>
				<th colspan="2"><input type="submit" name="form_compare_items" value="<?=MSG_COMPARE;?>"></th>
				<th class="itemTitle"><?=MSG_ITEM_TITLE;?><span><?=$page_order_itemname;?></span></th>
				<!--
				<td align="center"><?=MSG_START_BID;?><br><?=$page_order_start_price;?></td>
				<td align="center"><?=MSG_MAX_BID;?><br><?=$page_order_max_bid;?></td>
				-->
				<th align="center"><?=MSG_NR_BIDS;?><span><?=$page_order_nb_bids;?></span></th>
				<th align="center"><?=MSG_CURRENTLY;?><span><?=$page_order_current_price;?></span></th>      
				<th align="center"><?=MSG_ENDS;?><span><?=$page_order_end_time;?></span></th>
			</tr>
			<!--tr class="<? echo (IS_SHOP == 1) ? 'c5_shop' : 'c5';?>">
				<td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="15" height="1"></td>
				<td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="90" height="1"></td>
				<td width="100%"><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="100%" height="1"></td>
				<td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="80" height="1"></td>
				<td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="80" height="1"></td>
				<td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="50" height="1"></td>
				<td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="80" height="1"></td>
				<td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="100" height="1"></td>
			</tr-->
			<?=$browse_auctions_content;?>
			<? if ($nb_items>0) { ?>
			<tr>
				<td colspan="7" align="center" class="pagination"><?=$pagination;?></td>
			</tr>
			<? } ?>
			</form>
		</table>
	</div>
</div>

<div id="browseCategories" class="bigBlock clearfix">
	<div class="blockTitle">Categories</div>
	<?=$category_box_content;?>
</div>


<? echo (IS_SHOP == 1) ? $shop_footer : '';?>
<? echo (IS_CATEGORIES == 1) ? $categories_footer : '';?>

