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
#not sure why this is not already included since it has the db connection info and how can everything else be working without it
#but in any case without it the queries fail-it doesnt have values for db_host, db_username or db_password
	include ('includes/config.php');	
	

$link = mysql_connect($db_host, $db_username, $db_password);
mysql_select_db($db_name, $link);

$npname = $db->get_sql_field("SELECT npname  FROM probid_users WHERE username ='" . $member_username . "'", npname);
$npusername = $db->get_sql_field("SELECT username  FROM np_users WHERE tax_company_name ='" . $npname . "'", username);



#this code below almost works to make it so the user will see the right column of te landingpage when they browse from here. It sets a cookie. But
#the landingpage gets confused by the fact that the user already has a cookie so the link to the landing page starts to give a 404 not found
$np_userid = $db->get_sql_field("SELECT user_id  FROM np_users WHERE username ='" . $npusername . "'", user_id);
#set cookie so we know if this np has already sales or not. 1 means they do aleady have sales

#SetCookie("np_userid", $np_userid,0, '/', 'bringitlocal.com');
$inThreeMonths = 60 * 60 * 24 * 90 + time();
SetCookieLive("np_userid", $np_userid,$inThreeMonths, '/', 'bringitlocal.com');

define('np_userid', $np_userid);
#define('landingpage', 1);
//do they have sales. if not we dont want to show the chart
$result_sales = mysql_query("SELECT * FROM giveback_invoices WHERE np_userid = '$np_userid'", $link);
$is_sales = mysql_num_rows($result_sales);
if ($is_sales <> '0' )
define('sales', 1);
$salesno = sales;
#set a cookie and define a variable so we know the np when the rest of the homepage loads
#SetCookie("sales", $salesno, 0, '/', 'bringitlocal.com'); 

$inThreeMonths = 60 * 60 * 24 * 90 + time(); 
SetCookie("sales", $salesno, $inThreeMonths, '/', 'bringitlocal.com');
$first_name = $db->get_sql_field("SELECT first_name FROM bl2_users WHERE email ='" . $member_username . "'", first_name);

?>

<div class="top">
    <h4><?=MSG_WELCOME_BACK;?> <?=$first_name; ?></h4>
</div>
<ul class="member-menu">

<? if ($member_active == 'Active') { ?>
    <li>
        <a href="javascript:void(0)"><?=MSG_MM_PROFILE?></a>
         <ul>
             <li><a href="<?=process_link('members_area', array('page' => 'account', 'section' => 'editinfo'));?>"><?=MSG_MM_PERSONAL_INFO;?></a></li>
             <li><a href="javascript:void(0)"><?=MSG_MM_ABOUT_ME?></a>
                 <ul>
                    <li><a href="/about_me,page,edit,section,members_area"><?=MSG_MM_EDIT?></a></li>
                    <li><a href="/about_me,page,view,section,members_area"><?=MSG_MM_VIEW?></a></li>
                 </ul>
             </li>
             <li><a href="javascript:void(0)"><?=MSG_MESSAGES?></a>
                 <ul>
                     <li><a href="<?=process_link('members_area', array('page' => 'messaging', 'section' => 'received'));?>"><?=MSG_MM_RECEIVED;?></a></li>
                     <li><a href="<?=process_link('members_area', array('page' => 'messaging', 'section' => 'sent'));?>"><?=MSG_MM_SENT;?></a></li>
                 </ul>
             </li>


        </ul>
    </li>

    <li>
        <a href="javascript:void(0)"><?=MSG_MY_CAPMAIGNS?></a>
        <ul>
            <li><a href="/np/npregister.php"><?=MSG_NEW_CAPMAIGN?></a></li>
            <li><a href="/campaigns,page,drafts,section,members_area#8ec3489f027e"><?=MSG_DRAFTS_CAPMAIGNS?></a></li>
            <li><a href="/campaigns,page,live,section,members_area#8ec3489f027e"><?=MSG_LIVE_CAPMAIGNS?></a></li>
            <li><a href="/campaigns,page,closed,section,members_area#8ec3489f027e"><?=MSG_CLOSED_CAPMAIGNS?></a></li>
        </ul>
    </li>
    <li>
        <a href="javascript:void(0)"><?=MSG_MY_CONTRIBUTIONS?></a>
    </li>


    <li><a href=""><?=MSG_BUYING_AND_SELLING?></a>
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
                   <li><a href="<?=process_link('members_area', array('page' => 'bidding', 'section' => 'item_watch'));?>"><?=MSG_MM_WATCHED_ITEMS;?></a></li>
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

	<? } ?>



</ul>
