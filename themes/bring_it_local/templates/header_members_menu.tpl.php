<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>

<!--<table border="0" cellpadding="3" cellspacing="3" width="100%" class="c1 border contentfont">-->
<!--   <tr>-->
<!--      <td class="c2" height="35" align="center">-->
<!--      	--><!--</b>-->
<!--      </td>-->
<!--</tr><tr>-->
<!--<td class="c2" height="35" align="center">-->

<?
#not sure why this is not already included since it has the db connection info and how can everything else be working without it
#but in any case without it the queries fail-it doesnt have values for db_host, db_username or db_password
	include ('includes/config.php');	
	

$link = mysql_connect($db_host, $db_username, $db_password);
mysql_select_db($db_name, $link);

$npname = $db->get_sql_field("SELECT npname  FROM probid_users WHERE username ='" . $member_username . "'", 'npname');
$npusername = $db->get_sql_field("SELECT username  FROM np_users WHERE tax_company_name ='" . $npname . "'", 'username');



#this code below almost works to make it so the user will see the right column of te landingpage when they browse from here. It sets a cookie. But
#the landingpage gets confused by the fact that the user already has a cookie so the link to the landing page starts to give a 404 not found
$np_userid = $db->get_sql_field("SELECT user_id  FROM np_users WHERE username ='" . $npusername . "'", 'user_id');
#set cookie so we know if this np has already sales or not. 1 means they do aleady have sales

#SetCookie("np_userid", $np_userid,0, '/', 'bringitlocal.com');
$inThreeMonths = 60 * 60 * 24 * 90 + time();
//SetCookieLive("np_userid", $np_userid,$inThreeMonths, '/', 'bringitlocal.com');

define('np_userid', $np_userid);
#define('landingpage', 1);
//do they have sales. if not we dont want to show the chart
$result_sales = mysql_query("SELECT * FROM giveback_invoices WHERE np_userid = '$np_userid'", $link);
$is_sales = mysql_num_rows($result_sales);
if ($is_sales <> '0' ) {
    define('sales', 1);
    $salesno = sales;
} else
    $salesno = 0;
#set a cookie and define a variable so we know the np when the rest of the homepage loads
#SetCookie("sales", $salesno, 0, '/', 'bringitlocal.com'); 

$inThreeMonths = 60 * 60 * 24 * 90 + time(); 
SetCookie("sales", $salesno, $inThreeMonths, '/', 'bringitlocal.com');
$first_name = $db->get_sql_field("SELECT first_name FROM bl2_users WHERE email ='" . $member_username . "'", 'first_name');

?>

<!--<div class="top">-->
<!--    <h4>--><?//=MSG_WELCOME_BACK;?><!-- --><?//=$first_name; ?><!--</h4>-->
<!--<!--   <div class="buttons">-->
<!--<!--       <a href="/--><?////=$npusername?><!--<!--"><span>Go to your community page</span></a>-->
<!--<!--       <a href = "/reports/member/summary.php?sv1_username=--><?////=$member_username?><!--<!--&sv_invoice_date=%23%23all%23%23" target="_blank"><span>Fund Raising Report</span></a>-->
<!--<!---->
<!--<!--   </div>-->
<!--</div>-->
<ul class="member-menu">
<? if ($member_active == 'Active') { ?>
    <li <?php if ($selected_section == 'my_profile'): ?> class="active" <?php endif; ?>>
        <a href="/account,page,main,section,members_area"><?=MSG_MM_PROFILE?></a>
         <ul>
             <li><a href="<?=process_link('members_area', array('page' => 'account', 'section' => 'editinfo'));?>"><?=MSG_MM_PERSONAL_INFO;?></a></li>
<!--             <li><a href="--><?//=process_link('members_area', array('page' => 'account', 'section' => 'management'));?><!--">--><?//=MSG_MM_MANAGE_ACCOUNT;?><!--</a></li>-->
             <li><a href="javascript:void(0)"><?=MSG_MM_ABOUT_ME?></a>
                 <ul>
                    <li><a href="/about_me,page,edit,section,members_area"><?=MSG_MM_EDIT?></a></li>
                    <li><a href="/about_me,page,view,section,members_area"><?=MSG_MM_VIEW?></a></li>
                 </ul>
             </li>
<?php /*
             <li><a href="javascript:void(0)"><?=MSG_MESSAGES?></a>
                 <ul>
                     <li><a href="<?=process_link('members_area', array('page' => 'messaging', 'section' => 'received'));?>"><?=MSG_MM_RECEIVED;?></a></li>
                     <li><a href="<?=process_link('members_area', array('page' => 'messaging', 'section' => 'sent'));?>"><?=MSG_MM_SENT;?></a></li>
                 </ul>
             </li>
*/ ?>



            <!--            <li>&raquo; <a href="--><?//=process_link('members_area', array('page' => 'account', 'section' => 'invoices'));?><!--">--><?//=MSG_MM_INVOICES;?><!--</a></li>-->
<!--            <li><a href="--><?//=process_link('members_area', array('page' => 'account', 'section' => 'history'));?><!--">--><?//=MSG_MM_ACCOUNT_HISTORY;?><!--</a></li>-->
<!--            <li><a href="--><?//=process_link('members_area', array('page' => 'account', 'section' => 'mailprefs'));?><!--">--><?//=MSG_MM_MAIL_PREFS;?><!--</a></li>-->
<!--            <li><a href="--><?//=process_link('members_area', array('page' => 'account', 'section' => 'abuse_report'));?><!--">--><?//=MSG_MM_ABUSE_REPORT;?><!--</a></li>-->
<!--            --><?// if ($setts['enable_refunds']) { ?>
<!--                <li><a href="--><?//=process_link('members_area', array('page' => 'account', 'section' => 'refund_requests'));?><!--">--><?//=MSG_MM_REFUND_REQUESTS;?><!--</a></li>-->
<!--            --><?// } ?>
<!--            <li><a href="/reports/npmember/summary.php?sv1_np_name=&amp;sv_invoice_date=%23%23all%23%23" target="_blank"> Your fundraising report</a></li>-->
            <!--            <li><a href="/np/npmembers_news.php?news=news">Non profit news</a></li>-->
            <!--            <li><a href="/lilian.codreanu@gmail.com" target="_blank"> Your public landing page </a></li>-->
            <!--            <li><a href="/np/toolkit.doc" target="_blank"> Toolkit - download as word doc </a></li>-->
<!--            <li><a href="/widget,page,view,section,members_area#8ec3489f027e" target="_blank"> Widget </a></li>-->
        </ul>
    </li>

<!--<li><a href="--><?//=process_link('members_area', array('page' => 'summary'));?><!--">--><?//=MSG_MM_SUMMARY;?><!--</a></li>-->
    <li <?php if ($selected_section == 'my_campaigns'): ?> class="active" <?php endif; ?>>
        <a href="/campaigns,page,main,section,members_area#8ec3489f027e"><?=MSG_MY_CAPMAIGNS?></a>
        <ul>
            <li><a href="/np/npregister.php"><?=MSG_NEW_CAPMAIGN?></a></li>
            <li><a href="/campaigns,page,drafts,section,members_area#8ec3489f027e"><?=MSG_DRAFTS_CAPMAIGNS?></a></li>
            <li><a href="/campaigns,page,live,section,members_area#8ec3489f027e"><?=MSG_LIVE_CAPMAIGNS?></a></li>
            <li><a href="/campaigns,page,closed,section,members_area#8ec3489f027e"><?=MSG_CLOSED_CAPMAIGNS?></a></li>
        </ul>
    </li>
    <li <?php if ($selected_section == 'my_contributions' || $selected_section == 'my_earnings'): ?> class="active" <?php endif; ?>>
        <a href="/contributions,page,main,section,members_area#8ec3489f027e"><?=MSG_MY_CONTRIBUTIONS?></a>
        <ul>
            <li><a href="/earnings,page,main,section,members_area#8ec3489f027e"><?=MSG_MY_EARNINGS?></a></li>
        </ul>
    </li>


    <?php
        //Temporary disable this section
    /*
    ?>
    <li <?php if ($selected_section == 'buying_and_selling'): ?> class="active" <?php endif; ?>>
        <a href=""><?=MSG_BUYING_AND_SELLING?></a>
       <ul>
           <li><a href="javascript:void(0)"><?=MSG_MM_SELLING;?></a>
               <ul class="selling">
                   <li><a href="<?=$place_ad_link;?>"><?=MSG_MM_POST_AUCTION;?></a></li>
                   <li><a href="<?=process_link('members_area', array('page' => 'selling', 'section' => 'open'));?>"><?=MSG_MM_OPEN_AUCTIONS;?></a> </li>
                   <li><a href="<?=process_link('members_area', array('page' => 'selling', 'section' => 'bids_offers'));?>"><?=MSG_MM_ITEMS_WITH_BIDS;?></a></li>
                   <li><a href="<?=process_link('members_area', array('page' => 'selling', 'section' => 'scheduled'));?>"><?=MSG_MM_SCHEDULED_AUCTIONS;?></a> </li>
                   <li><a href="<?=process_link('members_area', array('page' => 'selling', 'section' => 'closed'));?>"><?=MSG_MM_CLOSED_AUCTIONS;?></a></li>
                   <li><a href="<?=process_link('members_area', array('page' => 'selling', 'section' => 'drafts'));?>"><?=MSG_MM_DRAFTS;?></a></li>
                   <li> <a href="<?=process_link('members_area', array('page' => 'selling', 'section' => 'sold'));?>"><?=MSG_MM_SOLD_ITEMS;?></a></li>
<!--                   --><?// if ($setts['enable_stores']) { ?>
<!--                       <li> <a href="--><?//=process_link('members_area', array('page' => 'selling', 'section' => 'sold_carts'));?><!--">--><?//=MSG_MM_SOLD_CARTS;?><!--</a></li>-->
<!--                   --><?// } ?>
<!--                   <li><a href="--><?//=process_link('members_area', array('page' => 'selling', 'section' => 'invoices_sent'));?><!--">--><?//=MSG_MM_INVOICES_SENT;?><!--</a></li>-->
<!--                   <li><a href="--><?//=process_link('members_area', array('page' => 'selling', 'section' => 'fees_calculator'));?><!--">--><?//=MSG_MM_FEES_CALCULATOR;?><!--</a></li>-->
<!--                   <li><a href="--><?//=process_link('members_area', array('page' => 'selling', 'section' => 'prefilled_fields'));?><!--">--><?//=MSG_MM_PREFILLED_FIELDS;?><!--</a></li>-->
<!--                   <li><a href="--><?//=process_link('members_area', array('page' => 'selling', 'section' => 'block_users'));?><!--">--><?//=MSG_MM_BLOCK_USERS;?><!--</a></li>-->
<!--                   <li><a href="--><?//=process_link('members_area', array('page' => 'selling', 'section' => 'suggest_category'));?><!--">--><?//=MSG_MM_SUGGEST_CATEGORY;?><!--</li>-->
<!--                   --><?// if ($setts['enable_shipping_costs']) { ?>
<!--                       <li><a href="--><?//=process_link('members_area', array('page' => 'selling', 'section' => 'postage_setup'));?><!--">--><?//=MSG_MM_POSTAGE_CALC_SETUP;?><!--</a></li>-->
<!--                   --><?// } ?>
               </ul>
           </li>
           <li>
               <a href="javascript:void(0)">Buying</a>
               <ul>
                   <li><a href="<?=process_link('members_area', array('page' => 'bidding', 'section' => 'won_items'));?>"><?=MSG_MM_WON_ITEMS;?></a></li>
                   <li><a href="<?=process_link('members_area', array('page' => 'bidding', 'section' => 'current_bids'));?>"><?=MSG_MM_CURRENT_BIDS;?></a></li>
                   <li>	<? if ($setts['enable_stores']) { ?>
                           <a href="<?=process_link('members_area', array('page' => 'bidding', 'section' => 'purchased_carts'));?>"><?=MSG_MM_PURCHASED_CARTS;?></a><br>
                       <? } ?></li>
<!--                   <li><a href="--><?//=process_link('members_area', array('page' => 'bidding', 'section' => 'invoices_received'));?><!--">--><?//=MSG_MM_INVOICES_RECEIVED;?><!--</a></li>-->
                   <li><a href="<?=process_link('members_area', array('page' => 'bidding', 'section' => 'item_watch'));?>"><?=MSG_MM_WATCHED_ITEMS;?></a></li>
<!--                   <li>	--><?// if ($setts['enable_stores']) { ?>
<!--                           <a href="--><?//=process_link('members_area', array('page' => 'bidding', 'section' => 'favorite_stores'));?><!--">--><?//=MSG_MM_FAVORITE_STORES;?><!--</a><br>-->
<!--                       --><?// } ?>
<!--                   </li>-->
                   <li><a href="<?=process_link('members_area', array('page' => 'bidding', 'section' => 'keywords_watch'));?>"><?=MSG_MM_KEYWORDS_WATCH;?></a>
                   </li>
               </ul>
           </li>
           <li>
               <a href="javascript:void(0)"><?=MSG_MM_REPUTATION;?></a>
               <ul>
                   <li><a href="<?=process_link('members_area', array('page' => 'reputation', 'section' => 'received'));?>"><?=MSG_MM_MY_REPUTATION;?></a></li>
                   <li><a href="<?=process_link('members_area', array('page' => 'reputation', 'section' => 'sent'));?>"><?=MSG_MM_LEAVE_COMMENTS;?></a></li>
               </ul>
           </li>

    </ul>

    </li>
    <?php */ ?>
<!--    --><?// if ($is_seller) { ?>
<!-- <li>-->
<!--     <a href="javascript:void(0)">--><?//=MSG_MM_SELLING;?><!--  </a>-->
<!---->
<!-- </li>-->
<!--    --><?// } ?>


<!--    --><?// if ($is_seller && $setts['enable_bulk_lister']) { ?>
<!--<li>-->
<!--    <a href="">--><?//=MSG_MM_BULK;?><!--</a>-->
<!--    <ul>-->
<!--        <li><a href="--><?//=process_link('members_area', array('page' => 'bulk', 'section' => 'details'));?><!--">--><?//=MSG_MM_DETAILS;?><!--</a></li>-->
<!--    </ul>-->
<!--</li>-->
<!---->
<!--	--><?// } ?>
<!--   <li>-->
<!--       <a href="javascript:void(0)">--><?//=MSG_MM_ABOUT_ME;?><!--</a>-->
<!--       <ul>-->
<!--           <li><a href="--><?//=process_link('members_area', array('page' => 'about_me', 'section' => 'view'));?><!--">--><?//=MSG_MM_VIEW;?><!--</a></li>-->
<!--    --><?// if ($setts['enable_profile_page']) { ?>
<!--           <li><a href="--><?//=process_link('members_area', array('page' => 'about_me', 'section' => 'profile'));?><!--">--><?//=MSG_PROFILE_PAGE;?><!--</a></li>-->
<!--    --><?// } ?>
<!--       </ul>-->
<!--   </li>-->
<!--   	--><?// if ($setts['enable_stores'] && $is_seller) { ?>
<!--   <li>-->
<!--       <a href="">--><?//=MSG_MM_STORE;?><!--</a>-->
<!--       <ul>-->
<!--           <li><a href="--><?//=process_link('members_area', array('page' => 'store', 'section' => 'subscription'));?><!--">--><?//=MSG_MM_SUBSCRIPTION_SETUP;?><!--</a></li>-->
<!--           <li><a href="--><?//=process_link('members_area', array('page' => 'store', 'section' => 'setup'));?><!--">--><?//=MSG_MM_MAIN_SETTINGS;?><!--</a></li>-->
<!--           <li><a href="--><?//=process_link('members_area', array('page' => 'store', 'section' => 'postage'));?><!--">--><?//=MSG_MM_SC_SETTS;?><!--</a></li>-->
<!--           <li><a href="--><?//=process_link('members_area', array('page' => 'store', 'section' => 'store_pages'));?><!--">--><?//=MSG_MM_STORE_PAGES;?><!--</a></li>-->
<!--           <li><a href="--><?//=process_link('members_area', array('page' => 'store', 'section' => 'categories'));?><!--">--><?//=MSG_MM_CUSTOM_CATS;?><!--</a></li>-->
<!--       </ul>-->
<!--   </li>-->
<!-- 	--><?// } ?>
<!--	--><?// if ($setts['enable_wanted_ads']) { ?>
<!--        <li>-->
<!--            <a href="">--><?//=MSG_MM_WANTED_ADS;?><!--</a>-->
<!--            <ul>-->
<!--                <li><a href="--><?//=process_link('members_area', array('page' => 'wanted_ads', 'section' => 'new'));?><!--">--><?//=MSG_MM_ADD_NEW;?><!--</a></li>-->
<!--                <li><a href="--><?//=process_link('members_area', array('page' => 'wanted_ads', 'section' => 'open'));?><!--">--><?//=MSG_MM_OPEN;?><!--</a></li>-->
<!--                <li><a href="--><?//=process_link('members_area', array('page' => 'wanted_ads', 'section' => 'closed'));?><!--">--><?//=MSG_MM_CLOSED;?><!--</a></li>-->
<!--            </ul>-->
<!--        </li>-->
<!-- 	--><?// } ?>
<!--	--><?// if ($setts['enable_reverse_auctions']) { ?>
<!--        <li>-->
<!--            <a href="">--><?//=MSG_MM_REVERSE_AUCTIONS;?><!--</a>-->
<!--            <ul>-->
<!--                <li>-->
<!--                    <a href="javascript:void(0)">--><?//=MSG_MM_GET_SERVICES;?><!--</a>-->
<!--                    <ul>-->
<!--                        <li><a href="--><?//=process_link('members_area', array('page' => 'reverse', 'section' => 'open'));?><!--">--><?//=MSG_MM_OPEN;?><!--</a></li>-->
<!--                        <li><a href="--><?//=process_link('members_area', array('page' => 'reverse', 'section' => 'closed'));?><!--">--><?//=MSG_MM_CLOSED;?><!--</a></li>-->
<!--                        <li><a href="--><?//=process_link('members_area', array('page' => 'reverse', 'section' => 'scheduled'));?><!--">--><?//=MSG_MM_SCHEDULED;?><!--</a></li>-->
<!--                        <li><a href="--><?//=process_link('members_area', array('page' => 'reverse', 'section' => 'awarded'));?><!--">--><?//=MSG_MM_AWARDED;?><!--</a></li>-->
<!--                    </ul>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <a href="javascript:void(0)">--><?//=MSG_MM_PROVIDE_SERVICES;?><!--</a>-->
<!--                    <ul>-->
<!--                        <li><a href="--><?//=process_link('members_area', array('page' => 'reverse', 'section' => 'my_profile'));?><!--">--><?//=MSG_MM_PROFILE;?><!--</a></li>-->
<!--                        <li><a href="--><?//=process_link('members_area', array('page' => 'reverse', 'section' => 'my_bids'));?><!--">--><?//=MSG_MM_MY_BIDS;?><!--</a></li>-->
<!--                        <li><a href="--><?//=process_link('members_area', array('page' => 'reverse', 'section' => 'won'));?><!--">--><?//=MSG_MM_MY_PROJECTS;?><!--</a></li>-->
<!--                    </ul>-->
<!--                </li>-->
<!--            </ul>-->
<!--        </li>-->
<!--	--><?// } ?>
	<? } ?>



</ul>
<!--      </td>-->
<!--	</tr>-->
<!--</table>-->
<!--<br>-->
