<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
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

</head>
<body>
<div id="outerContainer"><div id="innerContainer">
<div id="header">
	<div class="logo"><a href="<?=$index_link;?>"><img src="images/bringitlocalogo.gif?<?=rand(2,9999);?>" alt="Bring It Local" border="0"></a></div>
		
	<div class ="topBanner"><!-- BEGIN PHP Live! code, (c) OSI Codes Inc. --><script type="text/javascript" src="//www.bringitlocal.com/phplive/js/phplive.js.php?d=0&base_url=%2F%2Fwww.bringitlocal.com%2Fphplive&text="></script><!-- END PHP Live! code, (c) OSI Codes Inc. --></div>
	<div class="topLinks">
		<div class="toplink" align="right" style="padding: 10px;"> 
                  <? if ($layout['is_about']) { ?>
                  <a href="<?=process_link('content_pages', array('page' => 'about_us'));?>"><?=MSG_BTN_ABOUT_US;?></a>
			| <a href="<?=process_link('content_pages', array('page' => 'faq'));?>"><?=MSG_BTN_FAQ;?></a>
                  <? } ?>
                  <? if ($layout['is_contact']) { ?>
		| <a href="<?=process_link('site_fees');?>"><?=MSG_BTN_SITE_FEES;?></a>
		| <a href="<?=process_link('content_pages', array('page' => 'help'));?>"><?=MSG_BTN_HELP;?></a>
                | <a href="<?=process_link('content_pages', array('page' => 'contact_us'));?>"><?=MSG_BTN_CONTACT_US;?></a>
		| <a href="http://blog.bringitlocal.com" target="_blank">Blog</a>
                  <? } ?>
			<? if ($setts['user_lang']) { ?>
				 | <?=$languages_list;?>
			<? } ?>
               </div>	
	</div>
	<div class="welcomeMsg"><b>Welcome!</b> <a href="<?=$login_link;?>"><?=$login_btn_msg;?></a> or <a href="<?=$register_link;?>"><?=$register_btn_msg;?></a>


<? if ($setts['enable_stores']) { ?>
                    		               |    <a href="shopping_cart.php"><?=GMSG_SHOPPING_CART;?></a>
		               <? } ?>

 |    <a href="searchnp.php">Quick Select</a>

</div>
	<div class="mainMenu">
		<ul>
			<? if (eregi("index.php",$_SERVER['PHP_SELF'])) { ?>
			<li><a href="<?=$index_link;?>"><?=MSG_BTN_HOME;?></a></li>
			<? } else {?>
			<li><a href="<?=$index_link;?>"><?=MSG_BTN_HOME;?></a></li>
			<? } 
			if (!$setts['enable_private_site'] || $is_seller)  { 
				if (eregi("sell_item.php",$_SERVER['PHP_SELF'])) { ?>
			<li><a href="<?=$place_ad_link;?>"><?=$place_ad_btn_msg;?></a></li>
			<? } else { ?>
			<li><a href="<?=$place_ad_link;?>"><?=$place_ad_btn_msg;?></a></li>
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
<li><a href="<?=process_link('categories');?>"><?=MSG_FEATURED_AUCTIONS;?></a></li>
<li><a href="<?=process_link('global_partners');?>"><?=MSG_BTN_SHOP_PARTNERS;?></a></li>
<li><a href="<?php echo $coupon_url; ?>"><?=MSG_BTN_COUPONS;?></a></li>
    <?php //<li><a href="http://coupons.bringitlocal.com"><?=MSG_BTN_COUPONS;?></a></li> ?>
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
			
			<li class="enroll"><a href="<?=$nplogin_link;?>">Schools and non profits: enroll here!</a></li>

		</ul>
	</div>
</div><!-- end header -->
<div id="topBanner"><a href="/content_pages.php?page=about_us"><img src="/images/bil_banner3.gif" alt="Shop Main Street not Wall Street" border="0"></a></div>
<!-- 

<?=$current_date;?>
<span id="servertime"></span>
<?=strtoupper(GMSG_BROWSE);?>
<form name="cat_browse_form" method="get" action="categories.php">
	 <?=$categories_browse_box;?>
</form>

 -->
