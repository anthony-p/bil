<?
#################################################################
## PHP Pro Bid v6.00															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>


<?
global $show_msg;
if ($show_msg==true)
{
?>
    <div class='barTitle'><a href="/global_partners.php"><?='Thanks you';?></a> </div>
    <div class="row">
            <br>
            <br>
                                You have unsubscribed successfully.
            <br>
            <br>
            <br>
            <br>            
     </div>
<?
}

$globalads="1";
if ($globalads=="1")## featured ads
 { ?>
<div id="featuredPartners" class="bigBlock blueBlock">
	<div class='blockTitle'><?=MSG_FEATURED_AFFILIATES;?></div>
 	 <div class="featuredRetailers clearfix">
	<?
	$counter = 0;
    $layout['catfeat_nb'] = 4;
	for ($i=0; $i<$featured_columns; $i++) { ?>
		<?
		for ($j=0; $j<$layout['catfeat_nb']; $j++) {
			$width = 100/$layout['catfeat_nb'] . '%'; ?>
		<div class="block"><?
			if (!empty($partnersitem_details[$counter]['name'])) {
				$main_image = $db->get_sql_field("SELECT media_url FROM " . DB_PREFIX . "auction_media WHERE
					auction_id='" . $partnersitem_details[$counter]['advert_id'] . "' AND media_type=1 AND upload_in_progress=0 ORDER BY media_id ASC LIMIT 0,1", 'media_url');

				$auction_link = process_link('auction_details', array('name' => $partnersitem_details[$counter]['name'], 
			'advert_id' => $partnersitem_details[$counter]['advert_id']));?>
				<?=display_globalad($partnersitem_details[$counter]['big_banner_code']);?>
			<? $counter++;
			} ?>
		</div>
		<? } ?>
	</div>
	<? } ?>
</div>
<? } 
?>

<div id="browsePartners" class='bigBlock'>
	<div class='blockTitle'><?=MSG_BROWSE_AFFILIATES;?></div>
	<?=$categories_search_box;?>
<!-- 
	<table border="1" cellspacing="0" cellpadding="0" border="0">
		<tr>

			<? if ($is_subcategories || $is_shop_stores) { ?> -->
			<!-- add stores as well -->
			<!-- 
			  <td width="170" valign="top"><? if ($is_shop_stores) { ?>
					<table width="80%" border="0" cellpadding="0" cellspacing="0" class="contentfont">
						<tr>
							<td class="c3"><?=MSG_SHOP_IN_STORES;?></td>
						</tr>
						<?=$shop_stores_content;?>
					</table>
					<? } ?>
					<? if ($is_subcategories) { ?>
				  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="contentfont">
						<tr>
							<td class="c3"><?=MSG_SUBCATEGORIES;?></td>
						</tr>
								 <?=$subcategories_content;?>
					</table>
				  <? } ?>
				</td>

			<? } ?>			 --> 
				<td valign="top">
