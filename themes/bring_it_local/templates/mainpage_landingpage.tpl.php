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
<script src="../../../scripts/jquery/jquery-1.9.1.js"></script>
<script>
    $(document).ready(function(){
        $("#vote_us").click(function(){
            var campaign_id = <?php echo (isset($compaigns["user_id"]) && $compaigns["user_id"]) ? $compaigns["user_id"] : '0'; ?>;
            var campaign_title = ' + <?php echo (isset($compaigns["project_title"]) && $compaigns["project_title"]) ? $compaigns["project_title"] : ''; ?> + ';
            if (campaign_id) {
                $.ajax({
                    url: "/vote_us.php",
                    data: {campaign_id: campaign_id, campaign_title: campaign_title},
                    success: function (result) {
//                        console.log(result);
                        result = jQuery.parseJSON(result);
                        if (result.success) {
                            $("#vote_us_block").html(result.vote_us);
                        }
                    }
                });
            }
        });
    });
</script>


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
        <?php if (isset($vote_us) && $vote_us) : ?>
            <div class="campaign-details" id="vote_us_block">
                <?php echo $vote_us; ?>
            </div>
        <?php endif; ?>
        <div class="campaign-details">
            <span class="price">$<? echo $compaigns['payment'];?><span> usd</span></span>
            <span class="day">
                <?php $days=round(($compaigns['end_date']-time())/86400);
                if($days>0){echo $days."<span> ".MSG_DAYS_LEFT."</span>"; }
                elseif($compaigns['payment'] == 0)
                    echo "<span>".MSG_CLOSED."</span>";
                else {
                    echo "<span>".MSG_SUCCESS."</span>";
                }
                ?>
            </span>

            <div class='clear'></div>
            <?php
            if(($compaigns['end_date']-time())>0){
//                $end_time=$compaigns['end_date'];
//                $create_time=$compaigns['reg_date'];
//                $current_time=time();
//                $completed =round((($current_time - $create_time) / ($end_time- $create_time)) * 100);
                $completed = $compaigns["founddrasing_goal"] ? round(($compaigns["payment"] / $compaigns["founddrasing_goal"]) * 100) : "100";
                echo "<div class='progress'><div style='width:". $completed."%' class='bar'></div></div>";
            }
            elseif($compaigns['payment'] == 0){
                echo "<div class='project-unsuccessful'>".MSG_CLOSED."</div>";
            } else {
                if ($compaigns["payment"])
                    echo "<div class='project-successful'>".MSG_SUCCESS."</div>";
                else
                    echo "<div class='project-unsuccessful'>".MSG_CLOSED."</div>";
            }
            ?>
            <p><?=MSG_RAISED_TOWARD_THE_GOAL?> $<?php echo isset($compaigns['founddrasing_goal']) ? $compaigns['founddrasing_goal'] : '0'; ?> </p>
        </div>
        <div class="navigation-btn">
            <h3><?=MSG_MANY_WAYS_TO_GIVE?></h3>
            <?php if ($compaigns['active'] != 2 && ($compaigns['end_date']-time())>0 ): ?>
                <a href="donate.php?np_userid=<?php echo isset($compaigns['user_id']) ? $compaigns['user_id'] : '0'; ?>" class="donation">
                    <span class="uper"><?=MSG_DONATE_NOW?></span>
                    <span><?=MSG_MAKE_DONATION?></span>
                </a>
            <?php endif; ?>
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
                <span class="uper"><?=MSG_SHOP_ONLINE?></span>
                <span><?=MSG_SHOP_ONLINE_INFORMATION_ABOUT_ONLINE_RETAILERS?></span>
            </a>
            <?php
            /*
            <a href="/categories.php" class="auctions">
                <span class="uper"><?=MSG_AUCTIONS?></span>
                <span><?=MSG_CHECK_OUT_AUCTIONS_SUPPORTING?></span>
            </a>
            <a href="http://coupons.bringitlocal.com/" class="merchants">
                <span class="uper">Local merchants</span>
                <span>Check out coupons from merchants supporting this campaign</span>
            </a>
            */
            ?>

            <a href="/bringitlocal" class="funds">
                <span class="uper"><?=MSG_COMMUNITY_FOUND?></span>
                <span><?=MSG_DEDICATE_PORTION_FOR_YOUR_DONATIONS?></span>
            </a>
        </div>
    </div>
    <div class="tabulation">
        <div id="Tab">
            <ul class="resp-tabs-list">
                <li><?=MSG_CAMPAIGN_HOME?></li>
                <li><?=MSG_UPDATES?></li>
                <li><?=MSG_COMMENTS?></li>
                <li><?=MSG_FUNDERS?></li>
                <li><?=MSG_REWARDS?></li>
                <li class="last"><?=MSG_WAYS_TO_SUPPORT?></li>
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

        if ($.fn.easyResponsiveTabs){
            $('#Tab').easyResponsiveTabs({
                type: 'default', //Types: default, vertical, accordion
                width: 'auto', //auto or any width like 600px
                fit: true   // 100% fit in a container
            });
        } else {
            $.getScript("/scripts/jquery/easyResponsiveTabs.js", function(data, textStatus, jqxhr) {
                $('#Tab').easyResponsiveTabs({
                    type: 'default', //Types: default, vertical, accordion
                    width: 'auto', //auto or any width like 600px
                    fit: true   // 100% fit in a container
                });
            });
        }

    });
</script>
