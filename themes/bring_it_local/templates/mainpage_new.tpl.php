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

</script>
<script>
    $(document).ready(function(){

        $(".description").each(function(){
            var $minHeight=72;
            var $height=$(this).height();
            if($height > $minHeight)
            {

                $(this).parent(".more_description").addClass("more");
            }
        });

    });

</script>


<div class="content">
       <div class="p-contain">
           <p><?=MSG_COMMUNITY_CROWDFUNDING?><br/>
            <span><?=MSG_DONATIONS_AUCTIONS?></span>
           </p>
       </div>
        <div class="content-buttons">
            <a href="/campaigns.php">
                <?= MSG_MAINPAGE_BTN_BROWSE; ?>
            </a>
            <a href="/about_us,page,content_pages">
                <?= MSG_MAINPAGE_BTN_LEARN; ?>
            </a>
            <a href="/np/npregister.php" class="last">
                <?= MSG_MAINPAGE_BTN_START; ?>
            </a>
        </div>
        <div class="clear"></div>

    <div id="wowslider-container1">
        <?php echo $wowslider_content; ?>
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
                            <span class="votes">Votes:<?php if(!empty($row['votes']))  {echo $row['votes'];} else {echo '0';}?></span>
                            <span class="day">
							<?php $days=round(($row['end_date']-time())/86400); 
							    if($days>0){echo $days."<span>".MSG_DAYS_LEFT."</span>"; }
							    else{echo "<span>closed</span>";}
							?>	
				            </span>				
                            <div class="clear"></div>
							<?php 
								if(($row['end_date']-time())>0){
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
<?php echo $body_script_content; ?>
<script>
$(document).ready(function(){
    $(".list > li:last-child").addClass("last");

});
</script>
