<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<?
#$npname = $db->get_sql_field("SELECT npname  FROM probid_users WHERE username ='" . $member_username . "'", npname);
if(!empty($_COOKIE['np_userid'])) {
    $np_userid = $_COOKIE['np_userid'];
    $npname = $db->get_sql_field("SELECT tax_company_name  FROM np_users WHERE user_id ='" . $np_userid . "'", tax_company_name);

}
else
    $npname = $db->get_sql_field("SELECT npname  FROM probid_users WHERE username ='" . $member_username . "'", npname);

$layout['hpfeat_nb'] = 2;
global $coupon_url;
$featured_columns = 14;
?>
<script src="/scripts/jquery/tabs.min.js"></script>
<script language=JavaScript src='/scripts/jquery/easyResponsiveTabs.js'></script>



<?php/*
<div class="barTitleNew">
    <div class="middle_bartitle">
    <span class="middle_logo"><?php if (isset($np_logo)): ?><img src="/np/uplimg/logos/<?php echo $np_logo ?>" height="73" /> <?php endif; ?></span>
    <span class="middle_tit"><?=$npname?> Community Page</span>
    </div>
</div>
<div class="main_cont">
<div class="middle_top_banner"><span class="pos_top"><span class="pos_mid"><img src="<?php if(isset($np_banner)): ?> np/banners/<?php echo $np_banner ?> <?php else: ?>themes/bring_it_local/img/banner_.jpg<?php endif; ?>" width="930" height="300" /></span></span></div>
<div class="searchBox">
    <div class="searchBox_bg">
	<!--form action="auction_search.php" method="post">
	<input type="hidden" name="option" value="basic_search">
		<div class="input"><input type="text" size="25" name="basic_search" value="Type in a description" onfocus="this.value=''"></div>
		<div class="btn"><input name="form_basic_search" type="image" src="themes/bring_it_local/img/bg_search_btn.gif" value="<?=GMSG_SEARCH;?>"></div>
		<div class="link"><a href="<?=process_link('search');?>"><?=strtoupper(MSG_ADVANCED_SEARCH);?></a></div>
	</form-->
	<?include("searchbycat.php"); ?>
     </div>
</div>

<div class="auctions_main">
<div id="auctionsBlock" class="bigBlock blueBlock">
    <? if($probid_user_box) { ?>
    <?=$featured_auctions_user_header;?>
    <div class="auctionBlocks">
        <?if(count($items_user_details)){?>
        <?php $cols = min(count($items_user_details),$displayed_acuctions_user_items);?>
        <div class="row clearfix">
            <? for ($i=0; $i<$cols; $i++) { ?>
            <div class="block">
                <?
                $main_image = $feat_db->get_sql_field("SELECT media_url FROM " . DB_PREFIX . "auction_media WHERE
                            auction_id='" . $items_user_details[$i]['auction_id'] . "' AND media_type=1 AND upload_in_progress=0 ORDER BY media_id ASC LIMIT 0,1", 'media_url');

                $auction_link = process_link('auction_details', array('name' => $items_user_details[$i]['name'], 'auction_id' => $items_user_details[$i]['auction_id']));?>
                <a href="<?=$auction_link;?>" class="image"><img src="<? echo ((!empty($main_image)) ? 'thumbnail.php?pic=' . $main_image . '&w=' . $layout['hpfeat_width'] . '&sq=Y' : 'themes/' . $setts['default_theme'] . '/img/system/noimg.gif');?>" border="0" alt="<?=$items_user_details[$i]['name'];?>"></a>
                <a href="<?=$auction_link;?>" class="link"><?=title_resize($items_user_details[$i]['name']);?></a>
            </div>
            <? } ?>
        </div>
        <? }else echo "<div class='auctions_no_items'>There are currently no auctions available</div>";?>
    </div>
    <? } ?>
</div>

<div id="dealsBlock" class="bigBlock blueBlock">
    <?=$coupons_recent_deals_header;?>
    <div class="auctionBlocks">
        <? if (count($magento_items)) { ?>
        <?php $cols = min(count($magento_items),$magento_items_nr);?>
                <div class="row clearfix">
                       <? for ($i=0; $i<$cols; $i++) { ?>
                    <div class="block">
                        <a href="<?=$coupon_url;?>/<?=$magento_items[$i]['url'];?>" class="image"><img src="<?=$magento_items[$i]['image_url'];?>" border="0" style="width: 125px; height: 85px;" alt="<?=$magento_items[$i]['name'];?>"></a>
                        <a href="<?=$coupon_url;?>/<?=$magento_items[$i]['url'];?>" class="link"><?=title_resize($magento_items[$i]['name']);?></a>
                        </div>
                       <? } ?>
                </div>

        <? } else echo "<div class='auctions_no_items'>There are currently no deals available</div>";?>
    </div>
</div>


<div id="auctionsBlock" class="bigBlock blueBlock">
<? $localauctions="1";?>
<? if ($localauctions=="1") { ?>
	<?=$featured_localauctions_header;?>
	<div class="auctionBlocks">
		<?
        if(count($item_localdetails)){
        $counter = 0;
		for ($i=0; $i<$featured_columns; $i++) { ?>
<!--		<div class="row clearfix">-->
			<?
			for ($j=0; $j<$layout['hpfeat_nb']; $j++) {
				$width = 100/$layout['hpfeat_nb'] . '%';
                if (!empty($item_localdetails[$counter]['name'])) {
                ?>
			<div class="block">
				<?

                        $main_image = $feat_db->get_sql_field("SELECT media_url FROM " . DB_PREFIX . "auction_media WHERE
                            auction_id='" . $item_localdetails[$counter]['auction_id'] . "' AND media_type=1 AND upload_in_progress=0 ORDER BY media_id ASC LIMIT 0,1", 'media_url');

                        $auction_link = process_link('auction_details', array('name' => $item_localdetails[$counter]['name'], 'auction_id' => $item_localdetails[$counter]['auction_id']));?>
                    <a href="<?=$auction_link;?>" class="image"><img src="<? echo ((!empty($main_image)) ? 'thumbnail.php?pic=' . $main_image . '&w=' . $layout['hpfeat_width'] . '&sq=Y' : 'themes/' . $setts['default_theme'] . '/img/system/noimg.gif');?>" border="0" alt="<?=$item_localdetails[$counter]['name'];?>"></a>
                    <a href="<?=$auction_link;?>" class="link"><?=title_resize($item_localdetails[$counter]['name']);?></a>
    <!--
                    <b><?=MSG_START_BID;?>:</b> <? echo $feat_fees->display_amount($item_localdetails[$counter]['start_price'], $item_localdetails[$counter]['currency']);?> <br>
                    <b><?=MSG_CURRENT_BID;?>:</b> <? echo $feat_fees->display_amount($item_localdetails[$counter]['max_bid'], $item_localdetails[$counter]['currency']);?> <br>
                    <b><?=MSG_ENDS;?>:</b> <? echo show_date($item_localdetails[$counter]['end_time']); ?>
    -->             </div>
                    <? $counter++;
                    }
                ?>
			<? } ?>
<!--		</div>-->
		<? } }else echo "<div class='auctions_no_items'>There are currently no auctions available</div>";?>
	</div>
<? } ?>
</div>

<div id="retailersBlock" class="bigBlock blueBlock">
<?
$globalads="1";
if ($globalads=="1")## featured ads
	 { ?>

		<div class='barTitle'><?=MSG_AFFILIATES;?> <span class="viewAll"><a href="/global_partners.php">View All</a></span></div>
		<div class="auctionBlocks">
			<?
            if(count($global_item_details)){
			$counter = 0;
			for ($i=0; $i<$featured_columns; $i++) { ?>
			<!--<div class="row"> -->
				<?
				for ($j=0; $j<$layout['hpfeat_nb']; $j++) {
					$width = 100/$layout['hpfeat_nb'] . '%';

                    if (!empty($global_item_details[$counter]['name'])) {?>
				<div class="block">
					<?

						$main_image = $feat_db->get_sql_field("SELECT media_url FROM " . DB_PREFIX . "auction_media WHERE
							auction_id='" . $global_item_details[$counter]['advert_id'] . "' AND media_type=1 AND upload_in_progress=0 ORDER BY media_id ASC LIMIT 0,1", 'media_url');

						$auction_link = process_link('auction_details', array('name' => $global_item_details[$counter]['name'], 'auction_id' => $global_item_details[$counter]['auction_id']));?>

					<?=display_globalad($global_item_details[$counter]['advert_code']);?>


<!--
					<b><?=MSG_START_BID;?>:</b> <? echo $feat_fees->display_amount($global_item_details[$counter]['start_price'], $global_item_details[$counter]['currency']);?> <br>
					<b><?=MSG_CURRENT_BID;?>:</b> <? echo $feat_fees->display_amount($global_item_details[$counter]['max_bid'], $global_item_details[$counter]['currency']);?> <br>
					<b><?=MSG_ENDS;?>:</b> <? echo show_date($global_item_details[$counter]['end_time']); ?>
 -->                </div>
					<? $counter++;
					} ?>
				<? } ?>
                <!--	<div class="clear"></div>
			</div>-->
			<? } }else echo "<div class='auctions_no_items'>The are currently no auctions available</div>";?>
		</div>
		<? } ?>
    <br clear="all" />
</div>

<? if ($nb_featured_stores) { ?>

<?=headercat(MSG_FEATURED_LOCALSTORES . ' <span class="viewAll"><a href="local_stores.php">' . MSG_VIEW_ALL . '</a></span>');?>
<div><table width="100%" border="0" cellpadding="3" cellspacing="2" class="border">
   <?=$featured_stores_table;?>
</table>
<br>
</div>
<? } ?>


<? if ($layout['nb_want_ads']) { ?>
		<?=$recent_localwa_header;?>
<div>
		<table border="0" cellpadding="0" cellspacing="0" id="recentWantedAds" class="mainTable">
			<tr>
				<th class="itemTitle"><b><?=MSG_ITEM_TITLE;?><b></th>
				<th nowrap="nowrap" class="time"><b><?=GMSG_START_TIME;?></b></th>
				<th>&nbsp;</th>
			</tr>
			<?
			while ($waitem_details = mysql_fetch_array($sql_select_recent_localwa))
			{
				$background = ($counter++%2) ? '' : ''; ?>

			<tr height="15" class="<?=$background;?>">
				<td><a href="<?=process_link('wanted_details', array('name' => $waitem_details['name'], 'wanted_ad_id' => $waitem_details['wanted_ad_id']));?>"><?=title_resize($waitem_details['name']);?></a></td>
				<td nowrap="nowrap"><b><?=show_date($waitem_details['start_time']);?></b></td>
				<td><a href="<?=process_link('wanted_details', array('name' => $waitem_details['name'], 'wanted_ad_id' => $waitem_details['wanted_ad_id']));?>"><img src="themes/<?=$setts['default_theme'];?>/img/wanted.gif" width="13" height="12" hspace="3" border="0"></a></td>
			</tr>
			<? } ?>
		</table>
</div>
		<? } ?>


		<? if ($setts['enable_addthis']) { ?>
			<div class="shareBtn"><?=$share_code;?>

			<!-- BEGIN PHP Live! code, (c) OSI Codes Inc. -->
<!--<br>			<script type="text/javascript" src="//www.bringitlocal.com/phplive/js/phplive.js.php?d=0&base_url=%2F%2Fwww.bringitlocal.com%2Fphplive&text="></script>-->
			<!-- END PHP Live! code, (c) OSI Codes Inc. -->


			</div>
		<? } ?>

	<!--

		<div class="banner">
			<?=$banner_position[3];?>
			<?=$banner_position[4];?>
		</div>

-->
<br clear="all" />
</div>
</div>
*/?>


<div class="top-description">
    <div class="left"><img src="<? echo $compaigns["logo"];?>" /></div>
    <div class="right">
        <h2><? echo $compaigns["name"];?></h2>
        <a href="" class="location"><? echo $compaigns["city"];?></a>
        <div class="clear"></div>
        <p><? echo $compaigns["description"];?></p>
    </div>
</div>
<div class="campaign-content">
    <div class="nav-right">
        <div class="campaign-details">
            <span class="price">$<? echo $compaigns['payment'];?><span>usd</span></span>
            <span class="day">
                <?php $days=round(($compaigns['end_date']-time())/86400);
                if($days>0){echo $days."<span>days left</span>"; }
                elseif($compaigns['payment'] == 0)
                    echo "<span>closed</span>";
                else {
                    echo "<span>successfully</span>";
                }
                ?>
            </span>

            <div class='clear'></div>
            <?php
            if(($compaigns['end_date']-time())>0){
                $end_time=$compaigns['end_date'];
                $create_time=$compaigns['reg_date'];
                $current_time=time();
                $completed =round((($current_time - $create_time) / ($end_time- $create_time)) * 100);
                echo "<div class='progress'><div style='width:". $completed."%' class='bar'></div></div>";
            }
            elseif($compaigns['payment'] == 0){
                echo "<div class='project-unsuccessful'>Closed</div>";
            } else {
                echo "<div class='project-successful'>Successful</div>";
            }
            ?>
            <p>Raised of $<?php echo isset($compaigns['founddrasing_goal']) ? $compaigns['founddrasing_goal'] : '0'; ?>USD goal</p>
        </div>
        <div class="navigation-btn">
            <h3>There are many ways to give</h3>
            <a href="donate.php" class="donation">
                <span class="uper">Donate Now</span>
                <span>make a donation</span>
            </a>
            <a href="/global_partners.php<?php /*
            if (isset($compaigns['url']) && $compaigns['url']) {
                if (strpos($compaigns['url'], 'http') === 0) {
                    echo $compaigns['url'];
                } else {
                    echo 'http://' . $compaigns['url'];
                }
            } else {
                echo '#';
            } */
            ?>" class="shop">
                <span class="uper">Shop Online</span>
                <span>Click through to online retailers: a % of your purchase will go to this campaign</span>
            </a>
            <a href="/categories.php" class="auctions">
                <span class="uper">Auctions</span>
                <span>Check out auctions supporting this campaign</span>
            </a>
            <a href="http://coupons.bringitlocal.com/" class="merchants">
                <span class="uper">Local merchants</span>
                <span>Check out coupons from merchants supporting this campaign</span>
            </a>

            <a href="/about_community_fund.php" class="funds">
                <span class="uper">Community Fund</span>
                <span>Dedicate a portion of your donations to the Community Fund</span>
            </a>
        </div>
    </div>
    <div class="tabulation">
        <div id="Tab">
            <ul class="resp-tabs-list">
                <li>Campaign home</li>
                <li>UPDATES</li>
                <li>Comments</li>
                <li>FUNDERS</li>
                <li>REWARDS</li>
                <li class="last">WAYS TO support</li>
            </ul>
            <div class="resp-tabs-container">
                <div class="tab-step">
                    <?php echo $cHome; ?>
                </div>

                <div class="tab-step">
                    <?php echo $cUpdates; ?>
                </div>
                <div class="tab-step">
                    <?php echo $cComments; ?>
                </div>
                <div class="tab-step">
                    <?php echo $cFunders; ?>
                </div>
                <div class="tab-step">
                    <?php echo $cRewards; ?>
                </div>
                <div class="tab-step">
                    <?php echo $cSupport; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>


</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#Tab').easyResponsiveTabs({
            type: 'default', //Types: default, vertical, accordion
            width: 'auto', //auto or any width like 600px
            fit: true   // 100% fit in a container
        });

    });
</script>