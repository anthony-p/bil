<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
global $coupon_url;
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


<div class="content">
       <div class="p-contain">
           <p>Community crowdfunding<br/>
            <span>Donations, auctions, gift certificates, click through shopping</span>
           </p>
       </div>
        <div class="content-buttons">
            <a href="">
              <span class="uper">Browse </span>
              <span>our campaigns</span>
            </a>
            <a href="">
                <span class="uper">Learn </span>
                <span>how it works</span>
            </a>
            <a href="" class="last">
                <span class="uper">Start </span>
                <span>giving today</span>
            </a>
        </div>
        <div class="clear"></div>

    <div id="wowslider-container1">
        <?php echo $wowslider_content; ?>

    </div>
        <div class="clear"></div>
        <div class="nav-content">


            <div class="viewall">
              <span>Featured</span>
              <div class="border"></div>
              <a href="/campaigns.php">see all campaigns</a>
          </div>
            <div class="clear"></div>
            <div class="campaigns">
                <ul class="list">
                    <?php foreach( $campaigns_list as $row):?>
                    <li>
					<a href="<?php echo $setts['site_path'] . $row['username']; ?>" target="_blank">
                        <div class="img"><img src="<?php echo $row['banner'];?>"/></div>
                        <div class="clear"></div>
					</a>
                        <div class="campaigns-info">
                            <p class="name">
                                <a href="" class="name-camp"><?php echo $row['name'];?></a>
                                <br/>by 
								<a href=""><?php echo $row['first_name']."  ".$row['last_name'];?></a>
                            </p>
                            <p class="description">
                                <?php echo $row['description'];?>
                            </p>
                           <a href="" class="location"><?php echo $row['city'];?></a>
                        </div>
                        <div class="campaign-details">
                            <span class="price"><?php echo $row['price'];?>$</span>
                            <span class="day">
							<?php $days=round(($row['end_date']-time())/86400); 
							    if($days>0){echo $days."<span>days left</span>"; }
							    else{echo "<span>closed</span>";}
							?>	
				            </span>				
                            <div class="clear"></div>
							<?php 
								if(($row['end_date']-time())>0){
                                    $end_time=$row['end_date'];
                                    $create_time=$row['reg_date'];
                                    $current_time=time();
                                    $completed =round((($current_time - $create_time) / ($end_time- $create_time)) * 100);
	                            	echo "<div class='progress'><div style='width:". $completed."%' class='bar'></div></div>";
	                            }
                                else{
	                            	echo "<div class='project-successful'>Successful</div>";
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