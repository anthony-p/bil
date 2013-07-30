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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title><?=$page_title. $page_specific_title ;?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?=LANG_CODEPAGE;?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?=$page_meta_tags;?>
    <link rel="shortcut icon" href="http://www.bringitlocal.com/images/favicon.ico" />
    <link href="/themes/<?=$setts['default_theme'];?>/style.css" rel="stylesheet" type="text/css">
    <link href="/themes/<?=$setts['default_theme'];?>/tabs-style.css" rel="stylesheet" type="text/css">
    <link href="/themes/<?=$setts['default_theme'];?>/responsive.css" rel="stylesheet" type="text/css">
    <script language=JavaScript src='/scripts/jquery/jquery-1.3.2.js'></script>
    <script language=JavaScript src='/scripts/jquery/pagination.js'></script>
    <script language=JavaScript src='/scripts/jquery/sort.js'></script>
    <script language=JavaScript src='/scripts/jquery/sliding.form.js'></script>

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
     <!--[if IE 8]>
    <script>
        document.createElement('header');
        document.createElement('nav');
        document.createElement('section');
        document.createElement('article');
        document.createElement('aside');
        document.createElement('footer');
    </script>
    <link rel="stylesheet" type="text/css" media="screen" href="/themes/<?=$setts['default_theme'];?>/ie.css" />
    <![endif]-->

    <!--[if IE]>
    <script type="text/javascript" src="/scripts/jquery/placeholder.js"> </script>
    <![endif]-->

    <script type="text/javascript">  document.createElement('header');  document.createElement('hgroup');  document.createElement('nav');  document.createElement('menu');  document.createElement('section');  document.createElement('article');  document.createElement('aside');  document.createElement('footer'); </script>
    <script language=JavaScript src='/scripts/jquery/sliding.form.js'></script>
    <script>
        $(document).ready(function() {
             $('#menu').click(function(){
                 if ($("#menu-cont").is(":hidden")) {
                     $("#menu-cont").slideDown();
                     $('#menu').addClass('arrow');
                 } else {
                     $("#menu-cont").slideUp();
                     $('#menu').removeClass('arrow');
                 }
              });

            $('#polyglotLanguageSwitcher').polyglotLanguageSwitcher({
                effect: 'fade',
                testMode: true
            });

            $(".myCampaigs .list li:odd").addClass("odd");
            $(".list li:last-child").addClass("last");
            $(".rows-list li:last-child").addClass("last");
            $(".announcement > .post:first-child").addClass("first");
            $(".member-menu > li:last-child").addClass("last")
        });
        $(function() {
            $("div.holder").jPages({
                containerID: "pagination"
            });
        });
        var countOfPitch = 0;
        function addPitch(){
            var pitches = parseInt($("#pitches_number").val());
            $("#pitches_number").val(pitches + 1);
            aux = $("<div class='pitch-content'> </div>");
            aux.html($("#pitch_template").html());
            aux.find("#amoun").attr("id","pitch["+countOfPitch+"][0]").attr("name","pitch_amoun["+countOfPitch+"]");
            aux.find("#name").attr("id","pitch["+countOfPitch+"][1]").attr("name","pitch_name["+countOfPitch+"]");
            aux.find("#description").attr("id","pitch["+countOfPitch+"][2]").attr("name","pitch_description["+countOfPitch+"]");
            $("#pitch_box").append(aux);
            countOfPitch +=1;
            console.log(countOfPitch);

            $('.removePitchButton').unbind().bind('click',function(){
                var pitches = parseInt($("#pitches_number").val());
                $("#pitches_number").val(pitches - 1);
                $(this).parent().remove();
            });


        }
    </script>


<!--    <script language=JavaScript src='/scripts/jquery/transit.js'></script>-->

    <script language="javascript" src="themes/<?=$setts['default_theme'];?>/main.js" type="text/javascript"></script>
<!--    <script language=JavaScript src='scripts/innovaeditor.js'></script>-->
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
    <script src="/scripts/jquery/jquery.polyglot.language.switcher.js" type="text/javascript"></script>

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
     * � 2012 Bitstream Inc
    */
    -->
    <link rel="stylesheet" type="text/css" href="webfonts/MyFontsWebfontsKit.css">
</head>
<body id="<?=$GLOBALS['body_id']?>">
<div id="outerContainer">
    <header>
        <div class="innerContainer">
            <div class="sun"></div>
            <div class="inner">
                <div class="logo"><a href="<?=$index_link;?>"><img src="images/logo_bringItLocal.png?<?=rand(2,9999);?>" alt="Bring It Local" border="0"></a></div>
                <nav>
                    <ul>
                        <li class="level"><a href="#" id="menu">Browse</a></li>
                        <li><a href="http://dev2.bringitlocal.com/about_us,page,content_pages">Learn</a></li>
                        <li><a href="/np/npregister.php">Start</a></li>
                    </ul>
                </nav>
                <div class="topNav">
                    <!-- <a onclick="alert('Drag me to the bookmarks bar'); return false;" href="http://dev2.bringitlocal.com?npuser=1" > BringitLocal Bookmarklet</a>-->
                    <?php //<a onclick="alert('Drag me to the bookmarks bar'); return false;" href="javascript:q=(document.location.href);void(open('http://dev2.bringitlocal.com?npuser=1','_self','resizable,location,menubar,toolbar,scrollbars,status'));" > BringitLocal Bookmarklet</a> ?>
                    <?php global $session; if($session->value('user_id')) echo "<span class='user-log'>Welcome ".$session->value('username')."</span>"."<div class='clear'></div>";?>

                    <div class="links-nav"> <a href="<?=$login_link;?>"><?=$login_btn_msg;?></a> <a href="<?=$register_link;?>"><?=$register_btn_msg;?></a>

                    <?php

                    if($session->value('user_id')):?>
                        <a href="<?php global $coupon_url; echo $coupon_url."/customer/account/";?>">My Deals</a>
                    <?php endif;?>
<!--                    <a href="--><?//=process_link('content_pages', array('page' => 'contact_us'));?><!--">--><?//=MSG_BTN_CONTACT_US;?><!--</a>-->
                    <!--
		<? if ($setts['enable_stores']) { ?>
													   <a href="shopping_cart.php"><?=GMSG_SHOPPING_CART;?></a>
		<? } ?>
		
		 |    <a href="searchnp.php">Quick Select</a>
		 --> </div>
                    <form action="search.php">
                        <input type="text" value="" placeholder="search" name="search" >
                        <button type="submit"></button>
                    </form>


                </div>
                <?
                #put landing page right column code here
                if (  (landingpage == '1') ||  (isset($_COOKIE["np_userid"]))  ){
                    $mynp_userid=$_COOKIE["np_userid"];
                    $npusername = $db->get_sql_field("SELECT username  FROM np_users WHERE user_id ='" . $mynp_userid . "'", username);
                    $mynp = $db->get_sql_field("SELECT tax_company_name  FROM np_users WHERE user_id ='" . $mynp_userid . "'", tax_company_name);
                    ?>
                    <?php /*<div class="nonprofit"><a href="/<?=$npusername?>">You support <strong><?=$mynp?></strong></a></div>
	*/?>
                <?
                }else{
                    ?>
                <!--    <div class="enroll"><a href="searchnp.php">Choose a non-profit</a></div>-->
                <?
                }
                ?>
                <?php /*<div class="mainMenu">
	
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
			<? } 
			} ?>
            <li class="localAuctions"><a href="<?=process_link('categories')?>"><?=MSG_FEATURED_AUCTIONS;?></a></li>
            <li class="onlineRetailers"><a href="<?=process_link('global_partners')?>"><?=MSG_BTN_SHOP_PARTNERS;?></a></li>
            <li class="localDeals"><a href="<?php echo $coupon_url; ?>"><?=MSG_BTN_COUPONS;?></a></li>
            <?php
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
	</div> */?>


            </div>
        </div>
        <div class="level2" id="menu-cont">
            <ul class="level2-inner">
                <?php foreach ($np_org_types as $index => $np_org_type): ?>
                    <li>
                        <span><?php echo $index; ?></span>
                        <ul>
                            <?php foreach ($np_org_type as $org_type): ?>
                                <li>
                                    <a href="/search.php?category=<?php echo $org_type["id"] ?>">
                                        <?php echo $org_type["name"] ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endforeach; ?>
<!--                <li>-->
<!--                    <span>creative</span>-->
<!--                    <ul>-->
<!--                        <li><a href="">Art</a></li>-->
<!--                        <li><a href=""> Comic</a></li>-->
<!--                        <li><a href="">Dance</a></li>-->
<!--                        <li><a href="">Design</a></li>-->
<!--                        <li><a href="">Fashion</a></li>-->
<!--                        <li><a href="">Film</a></li>-->
<!--                        <li><a href="">Gaming</a></li>-->
<!--                    </ul>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <span>Cause</span>-->
<!--                    <ul>-->
<!--                        <li><a href="">Animals</a></li>-->
<!--                        <li><a href="">Community</a></li>-->
<!--                        <li><a href="">Education</a></li>-->
<!--                        <li><a href="">Environment</a></li>-->
<!--                        <li><a href="">Health</a></li>-->
<!--                        <li><a href="">Politics</a></li>-->
<!--                        <li><a href="">Religion</a></li>-->
<!--                    </ul>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <span>entrepreneurial</span>-->
<!--                <ul>-->
<!--                    <li><a href="">Food</a></li>-->
<!--                    <li><a href="">Small Business</a></li>-->
<!--                    <li><a href="">Sports</a></li>-->
<!--                    <li><a href="">Technology</a></li>-->
<!--                </ul>-->
<!---->
<!--                </li>-->
                <li class="nav-buttons">
                <div class="level-buttons">
                    <span>browse campaigns</span>
                    <ul>
                        <li>
                            <a href="#">
                                <span>New campaigns</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                               <span>New campaigns</span>
                           </a>
                        </li>
                        <li>
                            <a href="#">
                               <span>Ending Soon</span>
                              </a>
                        </li>
                    </ul>
                </div>
                <div class="level-buttons last">
                    <span>ways to give</span>
                    <ul>
                        <li>
                            <a href="#">
                               <span >Auctions</span>
                           </a>
                        </li>
                        <li>
                            <a href="#">
                               <span>Local merchants</span>
                           </a>
                        </li>
                        <li>
                            <a href="#">
                                <span>Local merchants</span>
                             </a>
                        </li>
                    </ul>
                </div>
                  <div class="nav-links">
                    <span><a href="">Click here to subscribe to updates</a></span>
                    <span><a href="">Go to the Community fund </a><a href="" class="what">What's that?</a></span>
                  </div>
              </li>
            </ul>
        </div>
    </header><!-- end header -->
<!--    <div id="topBanner"><a href="/content_pages.php?page=about_us"><img src="/images/bil_banner3.gif" alt="Shop Main Street not Wall Street" border="0"></a></div>-->
    <div id="main"><div class="innerContainer">
            <div id="leftColumn">
                <script language="javascript">
                    var ie4 = false;
                    if(document.all) { ie4 = true; }

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
                <?php if($session->value('user_id') && strpos($_SERVER["SCRIPT_NAME"],"members_area.php")):;?>
                    <div id="menuBox" class="leftBlock">
                        <?=$menu_box_header;?>
                        <div id="exp1102170142">
                            <?=$menu_box_content;?>
                        </div>
                    </div>
                <?php endif;?>
                <noscript>
                    <?=MSG_JS_NOT_SUPPORTED;?>
                </noscript>
                <?php /*
        <div id="categoryBox" class="leftBlock">
			<?=$category_box_header;?>
			<div id="exp1102170166">
			<?=$category_box_content;?>
			</div>
		</div>
		<div class="banner"> 
			<?=$banner_position[1];?>
			<?=$banner_position[2];?>
			<?=$banner_position[7];?>
		</div>
		<div class="rss" align="center"><a href="<?=$rss_link;?>"><img src="themes/<?=$setts['default_theme'];?>/img/system/rss.gif" border="0" alt="" align="absmiddle"></a></div>
        */ ?>
                <? if ($setts['enable_skin_change']) { ?>
                    <form action="index.php" method="GET">
                        <div align="center">
                            <?=MSG_CHOOSE_SKIN;?>:<br>
                            <?=$site_skins_dropdown;?>
                            <input type="submit" name="change_skin" value="<?=GMSG_GO;?>">
                        </div>
                    </form>
                <? } ?>
            </div><!-- end leftColumn -->
            <div id="middleColumn">
