<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
## PHP Pro Bid & PHP Pro Ads Integration v1.00						##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
global $coupon_url;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?=$page_title. $page_specific_title ;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?=LANG_CODEPAGE;?>">
<?=$page_meta_tags;?>
<link rel="shortcut icon" href="http://www.bringitlocal.com/images/favicon.ico" />
<link href="themes/<?=$setts['default_theme'];?>/style.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.lb {
	background-image:  url(themes/<?=$setts['default_theme'];?>/img/lb_bg.gif);
}
.db {
	background-image:  url(themes/<?=$setts['default_theme'];?>/img/db_bg.gif);
}
-->
</style>
<script language="javascript" src="themes/<?=$setts['default_theme'];?>/main.js" type="text/javascript"></script>
<script language=JavaScript src='scripts/innovaeditor.js'></script>
<script type="text/javascript">
var currenttime = '<?=$current_time_display;?>';
var serverdate=new Date(currenttime);
function padlength(what){
	var output=(what.toString().length==1)? "0"+what : what;
	return output;
}
function displaytime(){
	serverdate.setSeconds(serverdate.getSeconds()+1)
	var timestring=padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds());
	document.getElementById("servertime").innerHTML=timestring;
}
window.onload=function(){
	// setInterval("displaytime()", 1000);
}
</script>
<script language=JavaScript src='/scripts/jquery/jquery-1.3.2.js'></script>
<script language=JavaScript src='/scripts/vendor.js'></script>

<script type="text/javascript">  
 function popupAlert(shop_url)
    {    
        <?
            global $nonloggedin_check;
            echo $nonloggedin_check;
            
            if ($_COOKIE['glob_alert']=="0")
            {
                echo "return;";        
            }
            
            if(empty($_COOKIE['np_userid'])) {
                echo "return;";        
            }
            else
            {
                echo "var npid=".$_COOKIE['np_userid'].";";
            }
            
        ?>
        day = new Date();
        id = day.getTime();        
        //check cookies switch       
        URL="global_partner_alert.php?npid=" + npid + "&shop_url=" + shop_url;
        eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=2,location=0,statusbar=1,menubar=0,resizable=0,width=750,height=525,left = 100,top = 134');");
    }
</script>
	<!--
	/* @license
	 * MyFonts Webfont Build ID 2115466, 2012-02-29T08:03:14-0500
	 * 
	 * The fonts listed in this notice are subject to the End User License
	 * Agreement(s) entered into by the website owner. All other parties are 
	 * explicitly restricted from using the Licensed Webfonts(s).
	 * 
	 * You may obtain a valid license at the URLs below.
	 * 
	 * Webfont: Calluna Sans Regular by exljbris
	 * URL: http://www.myfonts.com/fonts/exljbris/calluna-sans/regular/
	 * Copyright: Copyright (c) 2010 by Jos Buivenga. All rights reserved.
	 * Licensed pageviews: unlimited
	 * 
	 * Webfont: Francisco Serial by SoftMaker
	 * URL: http://www.myfonts.com/fonts/softmaker/francisco-serial/regular/
	 * Copyright: Copyright (c) 2011 by SoftMaker Software GmbH and its licensors. All
	 * rights reserved.
	 * Licensed pageviews: unlimited
	 * 
	 * 
	 * License: http://www.myfonts.com/viewlicense?type=web&buildid=2115466
	 * 
	 * © 2012 Bitstream Inc
	*/
	-->
	<link rel="stylesheet" type="text/css" href="webfonts/MyFontsWebfontsKit.css">
</head>
<body id="<?=$GLOBALS['body_id']?>">
<div id="outerContainer">
<div id="header"><div class="innerContainer">
	<div class="logo"><a href="<?=$index_link;?>"><img src="images/logo_bringItLocal.png?<?=rand(2,9999);?>" alt="Bring It Local" border="0"></a></div>
	<div class="topNav">
		<a href="<?=$login_link;?>"><?=$login_btn_msg;?></a> or <a href="<?=$register_link;?>"><?=$register_btn_msg;?></a>
		 | <a href="<?=process_link('content_pages', array('page' => 'contact_us'));?>"><?=MSG_BTN_CONTACT_US;?></a>
		<!-- 
		<? if ($setts['enable_stores']) { ?>
															|    <a href="shopping_cart.php"><?=GMSG_SHOPPING_CART;?></a>
		<? } ?>
		
		 |    <a href="searchnp.php">Quick Select</a>
		 -->
	</div>
	<?
	#put landing page right column code here
	if (  (landingpage == '1') ||  (isset($_COOKIE["np_userid"]))  ){
	$mynp_userid=$_COOKIE["np_userid"];
	$npusername = $db->get_sql_field("SELECT username  FROM np_users WHERE user_id ='" . $mynp_userid . "'", username);
	$mynp = $db->get_sql_field("SELECT tax_company_name  FROM np_users WHERE user_id ='" . $mynp_userid . "'", tax_company_name);
	?>
	<div class="nonprofit"><a href="/<?=$npusername?>">You support <strong><?=$mynp?></strong></a></div>
	<?
	}else{
	?>
	<div class="enroll"><a href="searchnp.php">Choose a non-profit</a></div>
	<?
	}
	?>
	<div class="mainMenu">
		<ul>
			<? if (eregi("index.php",$_SERVER['PHP_SELF'])) { ?>
			<li class="home"><a href="<?=$index_link;?>"><?=MSG_BTN_HOME;?></a></li>
			<? } else {?>
			<li class="home"><a href="<?=$index_link;?>"><?=MSG_BTN_HOME;?></a></li>
			<? } 
			if (!$setts['enable_private_site'] || $is_seller)  { 
				if (eregi("sell_item.php",$_SERVER['PHP_SELF'])) { ?>
			<!--li><a href="<?=$place_ad_link;?>"><?=$place_ad_btn_msg;?></a></li-->
			<? } else { ?>
			<!--li><a href="<?=$place_ad_link;?>"><?=$place_ad_btn_msg;?></a></li-->
			<? } 
			} 
			if (eregi("members_area.php",$_SERVER['PHP_SELF'])||eregi("register.php",$_SERVER['PHP_SELF'])) { ?>
			
			<? } else { ?>
			
			<? } if (eregi("login.php",$_SERVER['PHP_SELF'])) { ?>
			
			<? } else { ?>
			
			<? }  
			
			if ($setts['enable_stores']) {
				if (eregi("stores.php",$_SERVER['PHP_SELF'])) { ?>
			<li><a href="<?=process_link('stores');?>"><?=MSG_BTN_STORES;?></a></li>
			<? } else { ?>
			<!-- 
				<li><a href="<?=process_link('stores');?>"><?=MSG_BTN_STORES;?></a></li>
			-->
			<li class="localAuctions"><a href="<?=process_link('categories')?>"><?=MSG_FEATURED_AUCTIONS;?></a></li>
			<li class="onlineRetailers"><a href="<?=process_link('global_partners')?>"><?=MSG_BTN_SHOP_PARTNERS;?></a></li>
			<li class="localDeals"><a href="<?php global $coupon_url; echo $coupon_url."/index.php/recent-deals.html";?>"><?=MSG_BTN_COUPONS;?></a></li>
			<? } 
			} 
			if ($setts['enable_reverse_auctions']) {
				if (eregi("reverse_auctions.php",$_SERVER['PHP_SELF'])) { ?>
			<li><a href="<?=process_link('reverse_auctions');?>"><?=MSG_REVERSE;?></a></li>
			<? } else { ?>
			<li><a href="<?=process_link('reverse_auctions');?>"><?=MSG_REVERSE;?></a></li>
			<? } 
			} 
			if ($setts['enable_wanted_ads']) { 
				if (eregi("wanted_ads.php",$_SERVER['PHP_SELF'])) { ?>
			<li><a href="<?=process_link('wanted_ads');?>"><?=MSG_BTN_WANTED_ADS;?></a></li>
			<? } else { ?>
			<li><a href="<?=process_link('wanted_ads');?>"><?=MSG_BTN_WANTED_ADS;?></a></li>
			<? }  
			} 

			if (eregi("site_fees.php",$_SERVER['PHP_SELF'])) { ?>
			
			<? } else { ?>
			
			<? } ?>
			<? if ($integration['enable_integration'] == 1) { ?>
			<!--			<li><a href="<?=$integration['ppa_url'];?>"><?=MSG_BTN_CLASSIFIEDS;?></a></li>-->
			<? } ?>
		</ul>
	</div>
</div></div><!-- end header -->
<div id="topBanner"><a href="/content_pages.php?page=about_us"><img src="/images/bil_banner3.gif" alt="Shop Main Street not Wall Street" border="0"></a></div>

 