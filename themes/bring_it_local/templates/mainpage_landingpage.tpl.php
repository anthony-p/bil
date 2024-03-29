<?php global $session; ?>
    <script src="../../../scripts/jquery/jquery-1.9.1.js"></script>
    <script src="../../../scripts/jquery/jquery-ui.js"></script>
    <script>
        $(document).ready(function () {
            $('.campaign_donation').click(function (e) {
                e.preventDefault();
                var url = $(this).attr('href');
                var logged_in = <?php echo $session->value('user_id') ? $session->value('user_id') : '0'; ?>;

                if (logged_in) {
                    window.location = url;
                } else {
                    $("#dialog-confirm").dialog({
                        resizable: false,
                        width: 316,
                        modal: true,

                        buttons: [
                            {
                                text: "<?= MSG_LOGIN_BUTTON ?>",
                                click: function () {
                                    $(this).dialog("close");
                                    window.location = "/login.php?donate=<?php echo $_COOKIE['np_userid']; ?>";
                                }
                            }
                            ,
                            {
                                text: "<?= MSG_CONTINUE_WITHOUT_LOGIN ?>",
                                click: function () {
                                    $(this).dialog("close");
                                    window.location = url;
                                }
                            }
                        ]
                    });
                }
            });
            $('.jp-page').click(function(e){
            	e.preventDefault();
            	$.ajax({
        			url:$(this).attr('href')        			
        		}).done(function(data){
        			$('#funders').html(data);
        		});
            });
        });
    </script>
<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if (!defined('INCLUDED')) {
    die("Access Denied");
}
?>
<?
#$npname = $db->get_sql_field("SELECT npname  FROM probid_users WHERE username ='" . $member_username . "'", npname);
if (!empty($_COOKIE['np_userid'])) {
    $np_userid = $_COOKIE['np_userid'];
    $npname = $db->get_sql_field("SELECT tax_company_name  FROM np_users WHERE user_id ='" . $np_userid . "'", 'tax_company_name');

} else
    $npname = $db->get_sql_field("SELECT npname  FROM probid_users WHERE username ='" . $member_username . "'", 'npname');

$layout['hpfeat_nb'] = 2;
global $coupon_url;
$featured_columns = 14;
?>
    <script src="/scripts/jquery/tabs.min.js"></script>
    <script language=JavaScript src='/scripts/jquery/easyResponsiveTabs.js'></script>
    <script src="../../../scripts/jquery/jquery-1.9.1.js"></script>

    <script type="text/javascript" src='/scripts/jquery/jquery-ui.js'></script>

    <script>
        $(document).ready(function () {
            $("#vote_us").click(function () {
                var campaign_id = <?php echo (isset($compaigns["user_id"]) && $compaigns["user_id"]) ? $compaigns["user_id"] : '0'; ?>;
                var campaign_title = ' + <?php echo (isset($compaigns["project_title"]) && $compaigns["project_title"]) ? $compaigns["project_title"] : ''; ?> + ';
                if (campaign_id) {
                	
                    $.ajax({
                        url: "/vote_us.php",
                        data: {campaign_id: campaign_id, campaign_title: campaign_title},
                        success: function (result) {
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
            <div class="left"><img src="<? echo $compaigns["logo"]; ?>"/></div>
            <div style="margin-left:120px">
            <?php endif; ?>
                <h2><? echo $compaigns["project_title"]; ?></h2>
                <p><? echo $compaigns["description"]; ?></p>

                <p>by
                    <a style="color: #7eb041"
                       href="/about_me.php?user_id=<?php echo isset($compaigns['probid_user_id']) ? $compaigns['probid_user_id'] : ''; ?>">
                        <?php if (isset($compaigns['tax_company_name']) && $compaigns['tax_company_name']): ?>
                            <?php echo $compaigns['tax_company_name']; ?>
                        <?php else: ?>
                            <?php echo $compaigns['name'];?>
                        <?php endif; ?>

                    </a>
                </p>
<?php if (isset($compaigns["logo"]) && $compaigns["logo"]): ?> </div><?php endif; ?>
    </div>
    <div class="campaign-content">
        <div class="nav-right">
            <script type="text/javascript">
                var state = 0;
                window.setInterval(function () {
                    if (state == 0) {
                        $("#vote_us").css("border", "none");
                        state = 1;
                    } else {
                        $("#vote_us").css("border", "2px solid #8CACAC");
                        state = 0;
                    }
                }, 1000);
            </script>

            <?php if (isset($vote_us) && $vote_us) : ?>
                <div class="campaign-details" id="vote_us_block">
                    <?= $vote_us ?>
                </div>
            <?php endif; ?>
            <div class="campaign-details">
                <span class="price">$<? echo $compaigns['payment']; ?><span> usd</span></span>
            <span class="day">
                <?php
                $days = (($compaigns['end_date'] - time()) / 86400);
                if ($days >= 1) {
                    echo round($days) . "<span> " . MSG_DAYS_LEFT . "</span>";
                } elseif ($days > 0) {
                    echo "<span>" . MSG_LESS_THEN_DAY . "</span>";
                } elseif ($compaigns['payment'] == 0) {
                    echo "<span>" . MSG_CLOSED . "</span>";
                } else {
                    echo "<span>" . MSG_SUCCESS . "</span>";
                }
                ?>
            </span>

                <div class='clear'></div>
                <?php
                if (($compaigns['end_date'] - time()) > 0) {
                    $completed = $compaigns["founddrasing_goal"] ? round(($compaigns["payment"] / $compaigns["founddrasing_goal"]) * 100) : "100";
                    echo "<div class='progress'><div style='width:" . $completed . "%' class='bar'></div></div>";
                } elseif ($compaigns['payment'] == 0) {
                    echo "<div class='project-unsuccessful'>" . MSG_CLOSED . "</div>";
                } else {
                    if ($compaigns["payment"])
                        echo "<div class='project-successful'>" . MSG_SUCCESS . "</div>";
                    else
                        echo "<div class='project-unsuccessful'>" . MSG_CLOSED . "</div>";
                }
                ?>
                <p><?= MSG_RAISED_TOWARD_THE_GOAL ?>
                    $<?php echo isset($compaigns['founddrasing_goal']) ? $compaigns['founddrasing_goal'] : '0'; ?> </p>
            </div>
            <div class="navigation-btn">
                <h3><?= MSG_MANY_WAYS_TO_GIVE ?></h3>
                <?php if ($compaigns['probid_user_id'] != $session->value('user_id') && $compaigns['active'] != 2 && ($compaigns['end_date'] - time()) > 0): ?>
                    <a href="donate.php?np_userid=<?php echo isset($compaigns['user_id']) ? $compaigns['user_id'] : '0'; ?>"
                       class="donation campaign_donation">
                        <span class="uper"><?= MSG_DONATE_NOW ?></span>
                        <span><?= MSG_MAKE_DONATION ?></span>
                    </a>
                <?php endif; ?>
                <?php if (isset($compaigns['include_clickthrough']) && $compaigns['include_clickthrough'] == 1): ?>
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
                        <span class="uper"><?= MSG_SHOP_ONLINE ?></span>
                        <span><?= MSG_SHOP_ONLINE_INFORMATION_ABOUT_ONLINE_RETAILERS ?></span>
                    </a>
                <?php endif; ?>
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
                <?php if ($compaigns['cfc'] == 0): ?>
                    <a href="<?= $cfc_url; ?>" class="funds">
                        <span class="uper"><?= MSG_COMMUNITY_FOUND ?></span>
                        <span><?= MSG_DEDICATE_PORTION_FOR_YOUR_DONATIONS ?></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="tabulation">
            <div id="Tab">
                <ul class="resp-tabs-list">
                    <li><?= MSG_CAMPAIGN_HOME ?></li>
                    <li><?= MSG_UPDATES ?></li>
                    <li><?= MSG_COMMENTS ?></li>
                    <li><?= MSG_FUNDERS ?></li>
                    <li><?= MSG_REWARDS ?></li>
                    <?php if (isset($compaigns['include_clickthrough']) && $compaigns['include_clickthrough'] == 1): ?>
                        <li<?= $compaigns['cfc'] == 0 ? ' class="last"' : '' ?>><?= MSG_WAYS_TO_SUPPORT ?></li>
                    <?php endif; ?>
                    <?php if ($compaigns['cfc'] == 1): ?>
                        <li><?= MSG_COMMUNITY_FUND_VOTE_REPORT ?></li>
                        <li class="last"><?= MSG_COMMUNITY_FUND_HISTORY ?></li>
                    <?php endif; ?>
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
                    <?php if (isset($compaigns['include_clickthrough']) && $compaigns['include_clickthrough'] == 1): ?>
                        <div class="tab-step">
                            <?php echo $cSupport; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($compaigns['cfc'] == 1): ?>
                        <div class="tab-step">
                            <?php echo $cVoteReport; ?>
                        </div>
                        <div class="tab-step">
                            <?php echo $cHistoryReport; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="clear"></div>


    </div>
    <script type="text/javascript">
        $(document).ready(function () {

            if ($.fn.easyResponsiveTabs) {
                $('#Tab').easyResponsiveTabs({
                    type: 'default', //Types: default, vertical, accordion
                    width: 'auto', //auto or any width like 600px
                    fit: true   // 100% fit in a container
                });
            } else {
                $.getScript("/scripts/jquery/easyResponsiveTabs.js", function (data, textStatus, jqxhr) {
                    $('#Tab').easyResponsiveTabs({
                        type: 'default', //Types: default, vertical, accordion
                        width: 'auto', //auto or any width like 600px
                        fit: true   // 100% fit in a container
                    });
                });
            }

        });
    </script>
<?php global $session; ?>
<?php if (!$session->value('user_id')): ?>
    <div id="dialog-confirm" title="<?= MSG_DONATION_LOGIN_INVITATION_POPUP_TITLE ?>">
        <p style="margin: 10px"><?= MSG_DONATION_LOGIN_INVITATION ?></p>
    </div>
<?php endif; ?>