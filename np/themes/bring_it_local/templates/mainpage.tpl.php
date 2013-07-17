<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
		<div class="searchBox">
			<!--form action="auction_search.php" method="post">
			<input type="hidden" name="option" value="basic_search">
				<div class="input"><input type="text" size="25" name="basic_search" value="Type in a description" onfocus="this.value=''"></div>
				<div class="btn"><input name="form_basic_search" type="image" src="themes/bring_it_local/img/bg_search_btn.gif" value="<?=GMSG_SEARCH;?>"></div>
				<div class="link"><a href="<?=process_link('search');?>"><?=strtoupper(MSG_ADVANCED_SEARCH);?></a></div>
			</form-->
			
			<?include("searchbycat.php"); ?>			
		</div>
		<? if ($layout['hpfeat_nb']) { ?>
		<?=$featured_auctions_header;?>
		<div class="featuredAuctions">
			<?
			$counter = 0;
			for ($i=0; $i<$featured_columns; $i++) { ?>
			<div class="row">
				<?
				for ($j=0; $j<$layout['hpfeat_nb']; $j++) {
					$width = 100/$layout['hpfeat_nb'] . '%'; ?>
				<div class="block">
					<?
					if (!empty($item_details[$counter]['name'])) {
						$main_image = $feat_db->get_sql_field("SELECT media_url FROM " . DB_PREFIX . "auction_media WHERE
							auction_id='" . $item_details[$counter]['auction_id'] . "' AND media_type=1 AND upload_in_progress=0 ORDER BY media_id ASC LIMIT 0,1", 'media_url');
		
						$auction_link = process_link('auction_details', array('name' => $item_details[$counter]['name'], 'auction_id' => $item_details[$counter]['auction_id']));?>
					<a href="<?=$auction_link;?>" class="image"><img src="<? echo ((!empty($main_image)) ? 'thumbnail.php?pic=' . $main_image . '&w=' . $layout['hpfeat_width'] . '&sq=Y' : 'themes/' . $setts['default_theme'] . '/img/system/noimg.gif');?>" border="0" alt="<?=$item_details[$counter]['name'];?>"></a>
					<a href="<?=$auction_link;?>" class="link"><?=title_resize($item_details[$counter]['name']);?></a>
<!-- 
					<b><?=MSG_START_BID;?>:</b> <? echo $feat_fees->display_amount($item_details[$counter]['start_price'], $item_details[$counter]['currency']);?> <br>
					<b><?=MSG_CURRENT_BID;?>:</b> <? echo $feat_fees->display_amount($item_details[$counter]['max_bid'], $item_details[$counter]['currency']);?> <br>
					<b><?=MSG_ENDS;?>:</b> <? echo show_date($item_details[$counter]['end_time']); ?>
 -->
					<? $counter++;
					} ?></div>
				<? } ?>
				<div class="clear"></div>
			</div>
			<? } ?>
		</div>
		<? } ?>
		<? if ($layout['r_hpfeat_nb'] && $setts['enable_reverse_auctions']) { ?>
		<?=$featured_reverse_auctions_header;?>
		<table width="100%" border="0" cellpadding="3" cellspacing="3" >
			<?
			$counter = 0;
			for ($i=0; $i<$featured_ra_columns; $i++) { ?>
			<tr>
				<?
				for ($j=0; $j<$layout['r_hpfeat_nb']; $j++) {
					$width = 100/$layout['r_hpfeat_nb'] . '%'; ?>
				<td width="<?=$width;?>" align="center" valign="top">
					<?
					if (!empty($ra_details[$counter]['name'])) {
						$auction_link = process_link('reverse_details', array('name' => $ra_details[$counter]['name'], 'reverse_id' => $ra_details[$counter]['reverse_id']));?>
					<table width="100%" border="0" cellspacing="1" cellpadding="3">
					  <tr>
							<td class="c3">&nbsp;&raquo;<a href="<?=$auction_link;?>"><?=title_resize($ra_details[$counter]['name']);?></a></td>
						</tr>
						<tr>
							<td class="c1 smallfont">
								
								<?=MSG_BUDGET;?>: <? echo $feat_fees->budget_output($ra_details[$counter]['budget_id'], null, $ra_details[$counter]['currency']);?> 
								<br>
								<?=MSG_NR_BIDS;?>: <? echo $ra_details[$counter]['nb_bids'];?>
								<br>
								<?=MSG_ENDS;?>: <? echo show_date($ra_details[$counter]['end_time']); ?>
								</td>
						</tr>
					</table>
					<? $counter++;
					} ?></td>
				<? } ?>
			</tr>
			<? } ?>
		</table>
		<? } ?>
		<? if ($layout['nb_recent_auct']) { ?>
		<?=$recent_auctions_header;?>
		<table border="0" cellpadding="0" cellspacing="0" id="recentAuctions" class="mainTable">
			<tr>
				<th class="itemTitle"><b><?=MSG_ITEM_TITLE;?><b></td>
				<th nowrap="nowrap" class="time"><b><?=GMSG_START_TIME;?></b></th>
				<th nowrap="nowrap" class="bid"><b><?=MSG_START_BID;?></b></th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
			</tr>
			<?
			while ($item_details = mysql_fetch_array($sql_select_recent_items))
			{
				$background = ($counter++%2) ? '' : '';
				$background .= ($item_details['bold']) ? ' bold_item' : '';
				$background .= ($item_details['hl']) ? ' hl_item' : ''; ?>
		
			<tr class="<?=$background;?>">
				<td class="itemTitle"><a href="<?=process_link('auction_details', array('name' => $item_details['name'], 'auction_id' => $item_details['auction_id']));?>"><?=title_resize($item_details['name']);?></a></td> 
				<td nowrap="nowrap"><?=show_date($item_details['start_time']);?></td>
				<td nowrap="nowrap"><?=$fees->display_amount($item_details['start_price'], $item_details['currency']);?></td> 
				<td class="end"><img src="themes/<?=$setts['default_theme'];?>/img/recent.gif" width="13" height="12" hspace="3"></td>
				<td><?=item_pics($item_details);?></td>
			</tr> 
			<? } ?>
		</table>
		<? } ?>
		<? if ($layout['nb_popular_auct'] && $is_popular_items) { ?>
		<?=$popular_auctions_header;?>
		<table width="100%" border="0" cellpadding="3" cellspacing="1" class="border">
			<tr>
				<td></td>
				<td nowrap="nowrap"><b><?=MSG_MAX_BID;?></b></td>
				<td width="100%"><b><?=MSG_ITEM_TITLE;?><b></td>
				<td nowrap></td>
			</tr>
			<? 
			while ($item_details = mysql_fetch_array($sql_select_popular_items))
			{
				$background = ($counter++%2) ? '' : '';
				$background .= ($item_details['bold']) ? ' bold_item' : '';
				$background .= ($item_details['hl']) ? ' hl_item' : ''; ?>
				
			<tr height="15" class="<?=$background;?>">
				<td><img src="themes/<?=$setts['default_theme'];?>/img/popular.gif" width="13" height="12" hspace="3"></td> 
				<td nowrap="nowrap"><?=$fees->display_amount($item_details['max_bid'], $item_details['currency']);?></td> 
				<td width="100%"><a href="<?=process_link('auction_details', array('name' => $item_details['name'], 'auction_id' => $item_details['auction_id']));?>"><?=title_resize($item_details['name']);?></a></td> 
				<td nowrap="nowrap"><?=item_pics($item_details);?></td> 
			</tr> 
			<? } ?>
		</table>
		<? } ?>
		<? if ($layout['nb_ending_auct']) { ?>
		<?=$ending_auctions_header;?>
		<table border="0" cellpadding="0" cellspacing="0" id="endingAuctions" class="mainTable">
			<tr>
				<th class="itemTitle"><?=MSG_ITEM_TITLE;?></th>
				<th class="time"><?=MSG_TIME_LEFT;?></th>
				<th class="bid"><?=MSG_CURRENTLY;?></th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
			</tr>
			<?
			while ($item_details = mysql_fetch_array($sql_select_ending_items))
			{
				$item_details['max_bid'] = ($item_details['max_bid'] > 0) ? $item_details['max_bid'] : $item_details['start_price'];
				
				$background = ($counter++%2) ? '' : '';
				$background .= ($item_details['bold']) ? ' bold_item' : '';
				$background .= ($item_details['hl']) ? ' hl_item' : ''; ?>
		
			<tr height="15" class="<?=$background;?>"> 
				<td class="itemTitle"><a href="<?=process_link('auction_details', array('name' => $item_details['name'], 'auction_id' => $item_details['auction_id']));?>"><?=title_resize($item_details['name']);?></a></td> 
				<td nowrap="nowrap"><?=time_left($item_details['end_time']);?></td> 
				<td nowrap="nowrap"><?=$fees->display_amount($item_details['max_bid'], $item_details['currency']);?></td> 
				<td class="end"><img src="themes/<?=$setts['default_theme'];?>/img/soon.gif" width="13" height="12" hspace="3"></td> 
				<td nowrap="nowrap"><?=item_pics($item_details);?></td> 
			</tr> 
			<? } ?>
		</table>
		<? } ?>
		<? if ($layout['nb_want_ads']) { ?>
		<?=$recent_wa_header;?>
		<table border="0" cellpadding="0" cellspacing="0" id="recentWantedAds" class="mainTable">
			<tr>
				<th class="itemTitle"><b><?=MSG_ITEM_TITLE;?><b></th>
				<th nowrap="nowrap" class="time"><b><?=GMSG_START_TIME;?></b></th>
				<th>&nbsp;</th>
			</tr>
			<?
			while ($item_details = mysql_fetch_array($sql_select_recent_wa))
			{
				$background = ($counter++%2) ? '' : ''; ?>
		
			<tr height="15" class="<?=$background;?>">
				<td><a href="<?=process_link('wanted_details', array('name' => $item_details['name'], 'wanted_ad_id' => $item_details['wanted_ad_id']));?>"><?=title_resize($item_details['name']);?></a></td> 
				<td nowrap="nowrap"><b><?=show_date($item_details['start_time']);?></b></td> 
				<td><img src="themes/<?=$setts['default_theme'];?>/img/wanted.gif" width="13" height="12" hspace="3"></td> 
			</tr> 
			<? } ?>
		</table>
		<? } ?>

	</div><!-- end middleColumn -->
	<div id="rightColumn">
		<? if ($setts['enable_addthis']) { ?>
			<div class="shareBtn"><?=$share_code;?></div>
		<? } ?>

		<? if ($member_active != 'Active') { ?>
		<div class="newUserBanner"><a href="<?=process_link('register');?>">
<?include("avatar.html"); ?>

</a></div>
		<? } ?>
		
		
		<? if ($is_news && $layout['d_news_box']) { ?>
		<div class="siteNews">
			<?=$news_box_header;?>
			<?=$news_box_content;?>
		</div>
		<? } ?>
		
		<? if ($setts['enable_header_counter']) { ?>
		<?=$header_site_status;?>
		<table width='100%' border='0' cellpadding='2' cellspacing='1' class='border'>
			<tr class='c1'>
				<td width='20%' align='center'><b><?=$nb_site_users;?></b></td>
				<td width='80%'><font style='font-size: 10px;'><?=MSG_REGISTERED_USERS;?></font></td>
			</tr>
			<tr class='c2'>
				<td width='20%' align='center'><b><?=$nb_live_auctions;?></b></td>
				<td width='80%'><font style='font-size: 10px;'><?=MSG_LIVE_AUCTIONS;?></font></td>
			</tr>
			<? if ($setts['enable_wanted_ads']) { ?>
			<tr class='c1'>
				<td width='20%' align='center'><b><?=$nb_live_wanted_ads;?></b></td>
				<td width='80%'><font style='font-size: 10px;'><?=MSG_LIVE_WANT_ADS;?></font></td>
			</tr>
			<? } ?>
			<tr class='c2'>
				<td width='20%' align='center'><b><?=$nb_online_users;?></b></td>
				<td width='80%'><font style='font-size: 10px;'><?=MSG_ONLINE_USERS;?></font></td>
			</tr>
		</table>
		<? } ?>
		<div class="banner">
			<?=$banner_position[3];?>
			<?=$banner_position[4];?>
		</div>

