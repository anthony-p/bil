<?
#################################################################
## PHP Pro Bid v6.00															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<!-- 
<?=$category_logo;?>
<?=headercat($categories_header_menu);?>
 -->
<?=$categories_search_box;?>

<? if ($layout['catfeat_nb']) { ?>
<div id="featuredAuctions" class="bigBlock blueBlock clearfix">
	<div class="blockTitle">Featured Auctions</div>
	<div class="blocks clearfix">
		<?
		$counter = 0;
		for ($i=0; $i<$featured_columns; $i++) { ?>
			<?
			for ($j=0; $j<$layout['catfeat_nb']; $j++) {
				$width = 100/$layout['catfeat_nb'] . '%'; ?>
			<div class="block"><?
				if (!empty($item_details[$counter]['name'])) {
					$main_image = $db->get_sql_field("SELECT media_url FROM " . DB_PREFIX . "auction_media WHERE
						auction_id='" . $item_details[$counter]['auction_id'] . "' AND media_type=1 AND upload_in_progress=0 ORDER BY media_id ASC LIMIT 0,1", 'media_url');
	
					$auction_link = process_link('auction_details', array('name' => $item_details[$counter]['name'], 'auction_id' => $item_details[$counter]['auction_id']));?>
					<a href="<?=$auction_link;?>" class="image"><img src="<? echo ((!empty($main_image)) ? 'thumbnail.php?pic=' . $main_image . '&w=' . $layout['catfeat_width'] . '&sq=Y' : 'themes/' . $setts['default_theme'] . '/img/system/noimg.gif');?>" border="0" alt="<?=$item_details[$counter]['name'];?>"></a>
					<a href="<?=$auction_link;?>" class="link"><?=$item_details[$counter]['name'];?></a>
				
				<? $counter++;
				} ?></div>
			<? } ?>
		<? } ?>
	</div>
</div>
<? } ?>
<!-- 
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr>
	<? if ($is_subcategories || $is_shop_stores) { ?>
	<td width="170" valign="top"><? if ($is_shop_stores) { ?>
		<table width="100%" border="0" cellpadding="3" cellspacing="2" class="contentfont">
			<tr>
				<td class="c3"><?=MSG_SHOP_IN_STORES;?></td>
			</tr>
			<?=$shop_stores_content;?>
		</table>
		<? } ?>
		<? if ($is_subcategories) { ?>
		<table width="100%" border="0" cellpadding="3" cellspacing="2" class="contentfont">
			<tr>
				<td class="c3"><?=MSG_SUBCATEGORIES;?></td>
			</tr>
			<?=$subcategories_content;?>
		</table>
		<? } ?>
	</td>
	<? } ?>
	<td valign="top">
 -->
