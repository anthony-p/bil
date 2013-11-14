<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
## PHP Pro Bid & PHP Pro Ads Integration v1.00						##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?=$page_title;?></title>
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
<script language=JavaScript src='/scripts/jquery/jquery-1.3.2.js'></script>
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
	setInterval("displaytime()", 1000);
}

</script>
</head>
<body>
<div id="topBanner"><?=$banner_position[5];?></div>
<div id="outerContainer"><div id="innerContainer">
<div id="header">
	<div class="logo"><a href="/index.php"><img src="/images/bringitlocalogo.gif?<?=rand(2,9999);?>" alt="Bring It Local" border="0"></a></div>
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
                  <? } ?>
			<? if ($setts['user_lang']) { ?>
				 | <?=$languages_list;?>
			<? } ?>
               </div>	
	</div>
	<div class="welcomeMsg"><b>Welcome!</b> <a href="<?=$login_link;?>"><?=$login_btn_msg;?></a> or <a href="<?=$register_link;?>"><?=$register_btn_msg;?></a>.</div>
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
			<li><a href="<?=process_link('stores');?>"><?=MSG_BTN_STORES;?></a></li>
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
 <li><span style="position:relative; right:-300px;"><a href="/npregister.php">Schools and non profits: enroll here!</a></span></li>

		</ul>
	</div>
</div><!-- end header -->

<!-- 

<?=$current_date;?>
<span id="servertime"></span>
<?=strtoupper(GMSG_BROWSE);?>
<form name="cat_browse_form" method="get" action="categories.php">
	 <?=$categories_browse_box;?>
</form>

 -->
            
<div id="main">
	<div id="menuWrapper">
         	<script language="javascript">
			var ie4 = false;
//			if(document.all) { ie4 = true; }

			function getObject(id) { if (ie4) { return document.all[id]; } else { return document.getElementById(id); } }
			function toggle(link, divId) {
				var lText = link.innerHTML;
				var d = getObject(divId);
				if (lText == '+') { link.innerHTML = '&#8211;'; d.style.display = 'block'; }
				else { link.innerHTML = '+'; d.style.display = 'none'; }
			}
		</script>
		<? if ($is_announcements && $member_active == 'Active') { ?>
		<?=$announcements_box_header;?>
		<div id="exp1102170555">
			<?=$announcements_box_content;?>
		</div>
		<? } ?>
		<div class="menuBox">
			<?=$menu_box_header;?>
			<div id="exp1102170142">
			<?=$menu_box_content;?>
			</div>
		</div>
            <noscript>
            <?=MSG_JS_NOT_SUPPORTED;?>
            </noscript>
		<div class="categoryBox">
			<?=$category_box_header;?>
			<div id="exp1102170166">
			<?=$category_box_content;?>
			</div>
		</div>
		<div class="banner"> 
			<?=$banner_position[1];?>
			<?=$banner_position[2];?>
		</div>
		<div class="rss" align="center"><a href="rss_feed.php"><img src="themes/<?=$setts['default_theme'];?>/img/system/rss.gif" border="0" alt="" align="absmiddle"></a></div>   
		<? if ($setts['enable_skin_change']) { ?>
		<form action="index.php" method="GET">
			<div align="center">
				<?=MSG_CHOOSE_SKIN;?>:<br>
				<?=$site_skins_dropdown;?>
				<input type="submit" name="change_skin" value="<?=GMSG_GO;?>">
			</div>   				
		</form>
		<? } ?>
	</div><!-- end menuWrapper -->
	<div id="middleColumn">
