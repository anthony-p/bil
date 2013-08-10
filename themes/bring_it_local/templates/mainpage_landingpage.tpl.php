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
    $npname = $db->get_sql_field("SELECT tax_company_name  FROM np_users WHERE user_id ='" . $np_userid . "'", 'tax_company_name');

}
else
    $npname = $db->get_sql_field("SELECT npname  FROM probid_users WHERE username ='" . $member_username . "'", 'npname');

$layout['hpfeat_nb'] = 2;
global $coupon_url;
$featured_columns = 14;
?>
<script src="/scripts/jquery/tabs.min.js"></script>
<script language=JavaScript src='/scripts/jquery/easyResponsiveTabs.js'></script>


<div class="top-description">
    <?php if (isset($compaigns["logo"]) && $compaigns["logo"]): ?>
    <div class="left"><img src="<? echo $compaigns["logo"];?>" /></div>
    <div class="right">
        <h2><? echo $compaigns["project_title"];?></h2>
      <!--  <a href="" class="location"><?/* echo $compaigns["city"];*/?></a>-->
        <div class="clear"></div>
        <p><? echo $compaigns["description"];?></p>
    </div>
    <?php else: ?>
    <div class="right" style="float: left">
        <h2><? echo $compaigns["project_title"];?></h2>
     <!--   <a href="" class="location"><?/* echo $compaigns["city"];*/?></a>-->
        <div class="clear"></div>
        <p><? echo $compaigns["description"];?></p>
    </div>
    <?php endif; ?>
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
                if ($compaigns["payment"])
                    echo "<div class='project-successful'>Successful</div>";
                else
                    echo "<div class='project-unsuccessful'>Closed</div>";
            }
            ?>
            <p>Raised of $<?php echo isset($compaigns['founddrasing_goal']) ? $compaigns['founddrasing_goal'] : '0'; ?>USD goal</p>
        </div>
        <div class="navigation-btn">
            <h3>There are many ways to give</h3>
            <a href="donate.php?np_userid=<?php echo isset($compaigns['user_id']) ? $compaigns['user_id'] : '0'; ?>" class="donation">
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
            <?php
            /*
            <a href="http://coupons.bringitlocal.com/" class="merchants">
                <span class="uper">Local merchants</span>
                <span>Check out coupons from merchants supporting this campaign</span>
            </a>
            */
            ?>

            <a href="/bringitlocal" class="funds">
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