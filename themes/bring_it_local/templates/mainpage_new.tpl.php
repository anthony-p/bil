<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
global $coupon_url;
include_once('includes/grab_video_thumbnail.php');
?>
<head>
    <?php echo $header_content; ?>
    <style>
        .ws_effect{
            display: none;
        }
    </style>
</head>
<script>
    function animateProgressBar() {
        $("#progressbar").css({ 'background-position': 0 })
            .animate({ 'background-position': '+=20' }, 500, 'linear',
            function () { animateProgressBar(); });
    }
    $(function () {
        $("#progressbar").progressbar({ value: 50 });
        animateProgressBar();
    });
</script>
<script>
    $(document).ready(function(){
//        $(".description").each(function(){
            var minHeight=72;
            var height=$(.description).height();
            console.log(height);
            if(height > minHeight)
            {

                $(".description").parent(".more_description").addClass("more");
            }
        });

//    });
</script>
<?php /*
<div id="main">
    <div class="innerContainer">
        <div id="middleColumn">
            <h2 class="line1">Keep your money local</h2>
            <h1 class="line2">Support your favorite community nonprofit</h1>
            <h5 class="line3">It's easy:</h5>
            <div class="blocks clearfix">
                <div class="block block1">
                    <a href="categories.php" class="title">Shop<br>Local Auctions</a>
                    <p class="description">We share sellers fees with your favorite non-profit</p>
                    <div class="featured">
                        <h3 class="title">Today's Featured Auction</h3>
                        <div class="info">
                            <?php
                            $counter = 0;
                            if (!empty($item_details[$counter]['name'])) {
                                $main_image = $feat_db->get_sql_field("SELECT media_url FROM " . DB_PREFIX . "auction_media WHERE
                                auction_id='" . $item_details[$counter]['auction_id'] . "' AND media_type=1 AND upload_in_progress=0 ORDER BY media_id ASC LIMIT 0,1", 'media_url');

                                $auction_link = process_link('auction_details', array('name' => $item_details[$counter]['name'], 'auction_id' => $item_details[$counter]['auction_id']));?>
                                <a href="<?=$auction_link;?>" class="imageBlock"><img src="<? echo ((!empty($main_image)) ? 'thumbnail.php?pic=' . $main_image . '&w=' . $layout['hpfeat_width'] . '&sq=Y' : 'themes/' . $setts['default_theme'] . '/img/system/noimg.gif');?>" border="0" alt="<?=$item_details[$counter]['name'];?>"></a>
                                <a href="<?=$auction_link;?>" class="link"><?=title_resize($item_details[$counter]['name']);?></a>
                                <?php }?>
                        </div>
                    </div>
                </div>
                <div class="block block2">
                    <a href="global_partners.php" class="title">Shop<br>Online Retailers</a>
                    <p class="description">Click through: we share the commission with your non-profit</p>
                    <div class="featured">
                        <h3 class="title">Today's Featured Store</h3>
                        <div class="info">
                            <?
                            $counter = 0;
                            if (!empty($global_item_details[$counter]['name'])) {
                                $main_image = $feat_db->get_sql_field("SELECT media_url FROM " . DB_PREFIX . "auction_media WHERE
                            							auction_id='" . $global_item_details[$counter]['advert_id'] . "' AND media_type=1 AND upload_in_progress=0 ORDER BY media_id ASC LIMIT 0,1", 'media_url');

                                $auction_link = process_link('auction_details', array('name' => $global_item_details[$counter]['name'], 'auction_id' => $global_item_details[$counter]['auction_id']));?>

<!--                                --><?//=display_globalad($global_item_details[$counter]['advert_code']);?>
                                <?=display_globalad($global_item_details[$counter]['big_banner_code']);?>
                                <? $counter++;
                            } ?>
                           <a href="http://www.amazon.com/gp/search?ie=UTF8%26keywords=%26tag=bringlocal-20%26index=aps%26linkCode=ur2%26camp=1789%26creative=9325" class="amazon"><img src="themes/bring_it_local/img/lgo_amazon.png"></a>
                        </div>
                    </div>
                </div>
                <div class="block block3">
                    <a href="<?php echo $coupon_url; ?>/" class="title">Shop<br>Local Offers</a>
                    <p class="description">Close to home daily offers: support local businesses</p>
                    <div class="featured">
                        <h3 class="title">Today's Featured Offer</h3>
                        <div class="info">
                            <? if (count($magento_item)) {
                            global $coupon_url;?>
                            <a href="<?=$coupon_url.'/';?><?=$magento_item['url'];?>" class="imageBlock"><img src="<?=$magento_item['image_url'];?>" border="0" alt="<?=$magento_item['name'];?>"></a>
                            <a href="<?=$coupon_url.'/';?><?=$magento_item['url'];?>" class="link"><?=title_resize($magento_item['name']);?></a>
                            <? } ?>
                        </div>
                    </div>
                </div>
            </div>
            <a href="searchnp.php" class="getStarted">Get started today</a>
        </div><!-- end middleColumn -->
    </div>
</div> */?>

<div class="content">
       <div class="p-contain">
           <p><?=MSG_COMMUNITY_CROWDFUNDING?><br/>
            <span><?=MSG_DONATIONS_AUCTIONS?></span>
           </p>
       </div>
        <div class="content-buttons">
            <a href="/campaigns.php">
              <span class="uper">Browse </span>
              <span>our campaigns</span>
            </a>
            <a href="/about_us,page,content_pages">
                <span class="uper">Learn </span>
                <span>how it works</span>
            </a>
            <a href="/np/npregister.php" class="last">
                <span class="uper">Start </span>
                <span>giving today</span>
            </a>
        </div>
        <div class="clear"></div>

    <div id="wowslider-container1">
        <?php echo $wowslider_content; ?>
<!--        <div class="ws_images"><ul>-->
<!--                <li><img src="/slider-test/data1/images/20121221_142302.jpg" alt="20121221_142302" id="wows1_0"/></li>-->
<!--                <li><img src="/slider-test/data1/images/20121225_132138.jpg" alt="20121225_132138" id="wows1_1"/></li>-->
<!--                <li><img src="/slider-test/data1/images/20130113_180047.jpg" alt="20130113_180047" id="wows1_2"/></li>-->
<!--            </ul>-->
<!--        </div>-->
<!--        <a href="#" class="ws_prev"><img src="themes/bring_it_local/img/next-arrow.png" /></a>-->
<!--        <a href="#" class="ws_next"><img src="themes/bring_it_local/img/next.png" /></a>-->
<!--        <div class="wsl">-->
<!--       <p> Destiny Arts is looking for a new home <span>$2,031 raised</span>-->
<!--       <br/>-->
<!--           <a href="">-->
                <?/*=MSG_VIEW_CAMPAIGN*/?>
        <!--            </a>-->
<!--       </p>-->
<!--       </div>-->

    </div>
        <div class="clear"></div>
        <div class="nav-content">


            <div class="viewall">
              <span><?=MSG_FEATURED?></span>
              <div class="border"></div>
              <a href="/campaigns.php"><?=MSG_SEE_ALL_CAMPAIGNS?></a>
          </div>
            <div class="clear"></div>
            <div class="campaigns">
                <ul class="list">
                    <?php foreach( $campaigns_list as $row):?>
                    <li>
					<a href="/<?php echo $row['username']; ?>">
<!--                        <div class="img"><img src="--><?php //echo isset($row['banner']) ? $row["banner"] : '';?><!--"/></div>-->
                        <?php
                            if (isset($row['banner'])) {
                              $imageBanner =   $row['banner'];
                              if (strpos($imageBanner,'youtube.com') !== false || strpos($imageBanner,'youtu.be') !== false || strpos($imageBanner,'vimeo.com') !== false)
                              {
                                  $gvt = new GrabVideoThumbnail($imageBanner,md5($imageBanner));
                                  if ($gvt->getThumbnail());
                                    $imageBanner = '/'.$gvt->getImage();
                              }
                            }
                        ?>
                        <div class="img"><img src="<?php echo isset($imageBanner) ? "/get_image_thumbnail.php?image=200x165_image_" . $imageBanner : '';?>"/></div>
                        <div class="clear"></div>
					</a>
                        <div class="campaigns-info">
                            <p class="name">
                                <a href="/<?php echo $row['username']; ?>" class="name-camp">
                                    <?php echo $row['project_title'];?>
                                </a>
                                <br/>by 
								<a href="/about_me.php?user_id=<?php echo isset($row['id']) ? $row['id'] : ''; ?>">
                                    <?php if (isset($row['organization']) && $row['organization']): ?>
                                    <?php echo $row['organization'];?>
                                    <?php else: ?>
                                    <?php echo $row['first_name']."  ".$row['last_name'];?>
                                    <?php endif; ?>
                                </a>,
                            </p>
                            <div class="more_description">
                                <p class="description">
                                    <?php echo $row['description'];?>
                                </p>
                                <a href="/<?php echo $row['username']; ?>">..more</a>
                            </div>
                           <a href="/search.php?city=<?=urlencode($row['city'])?>" class="location"><?php echo $row['city'];?></a>
                        </div>
                        <div class="campaign-details">
                            <span class="price">$<?php echo $row['payment'];?></span>
                            <span class="day">
							<?php $days=round(($row['end_date']-time())/86400); 
							    if($days>0){echo $days."<span>days left</span>"; }
							    else{echo "<span>closed</span>";}
							?>	
				            </span>				
                            <div class="clear"></div>
							<?php 
								if(($row['end_date']-time())>0){
//                                    $end_time=$row['end_date'];
//                                    $create_time=$row['reg_date'];
//                                    $current_time=time();
//                                    $completed =round((($current_time - $create_time) / ($end_time- $create_time)) * 100);
                                    $completed =$row["founddrasing_goal"] ? round(($row["payment"] / $row["founddrasing_goal"]) * 100) : "100";
	                            	echo "<div class='progress'><div style='width:". $completed."%' class='bar'></div></div>";
	                            }
                                else{
                                    if ($row["payment"])
                                        echo "<div class='project-successful'>Successful</div>";
                                    else
                                        echo "<div class='project-unsuccessful'>Closed</div>";
		                          }    
							?>
                        </div>
				    
                    </li>
                    <?php endforeach; ?>

                </ul>

            </div>
        </div>
    </div>
<!--<script type="text/javascript" src="/slider-test/engine1/wowslider.js"></script>-->
<!--<script type="text/javascript" src="/slider-test/engine1/script.js"></script>-->
<?php echo $body_script_content; ?>
<script>
$(document).ready(function(){
    $(".list > li:last-child").addClass("last");

});
</script>
