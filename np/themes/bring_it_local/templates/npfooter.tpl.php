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
	</div><!-- end middleColumn -->
	<div id="rightColumn">
		<!-- landing page -->
		<?
        /*
		if (  (landingpage == '1') ||  (isset($_COOKIE["np_userid"]))  ){
		$mynp_userid=$_COOKIE["np_userid"];
		$npusername = $db->get_sql_field("SELECT username  FROM np_users WHERE user_id ='" . $mynp_userid . "'", username);
		?>
		<?
		include ('themes/'.$setts['default_theme'].'/templates/landingpage.tpl.php');
		} */
		?>
		<!-- how it works image -->
		<?
//		if (  (landingpage == '1') ||  (isset($_COOKIE["np_userid"]))  ){
		if (  false == true ){
		$mynp_userid=$_COOKIE["np_userid"];
		$npusername = $db->get_sql_field("SELECT username  FROM np_users WHERE user_id ='" . $mynp_userid . "'", username);
		$mynp = $db->get_sql_field("SELECT tax_company_name  FROM np_users WHERE user_id ='" . $mynp_userid . "'", tax_company_name);
		?>
		<?
		}else{
		?>

		<? if (isset($member_active) && $member_active != 'Active') { ?>
			<div id="newUserBanner"><a href="<?=process_link('register');?>"><img src="themes/<?=$setts['default_theme'];?>/img/newuser.gif" width="204" height="150" border="0"></a></div>
		<? } ?>
		<?
		}
		?>
		<!-- site news -->
		<? /* if ($is_news && $layout['d_news_box']) { ?>
		<div class="siteNewsBlock rightBlock">
			<?=$news_box_header;?>
			<?=$news_box_content;?>
		</div>
		<!-- live code -->
		<div id="liveBanner">
		
		
		</div>
		<!-- facebook -->
		<div class="facebook"><iframe src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fbringitlocal&amp;width=292&amp;colorscheme=light&amp;show_faces=true&amp;border_color&amp;stream=false&amp;header=true&amp;height=288" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:292px; height:288px;" allowTransparency="false"></iframe></div>


		<? } */ ?>
		<? if ($setts['enable_header_counter']) { ?>
		<?=$header_site_status;?>
		<table width='100%' border='0' cellpadding='2' cellspacing='1' class='border'>
			<tr class='c1'>
				<td width='20%' align='center'><b><?=$nb_site_users;?></b></td>
				<td width='80%'><font style='font-size: 10px;'><?=MSG_REGISTERED_USERS;?></font></td>
			</tr>
			<tr class='c2'>
				<td width='20%' align='center'><b><?=$nb_live_auctions;?></b></td>
				<td width='80%'><font style='font-size: 10px;'><?=MSG_LIVE_AUCTIONS;?></font></td>
			</tr>
			<? if ($setts['enable_wanted_ads']) { ?>
			<tr class='c1'>
				<td width='20%' align='center'><b><?=$nb_live_wanted_ads;?></b></td>
				<td width='80%'><font style='font-size: 10px;'><?=MSG_LIVE_WANT_ADS;?></font></td>
			</tr>
			<? } ?>
			<tr class='c2'>
				<td width='20%' align='center'><b><?=$nb_online_users;?></b></td>
				<td width='80%'><font style='font-size: 10px;'><?=MSG_ONLINE_USERS;?></font></td>
			</tr>
		</table>
		<? } ?>
	</div><!-- end rightColumn -->
	<div class="clear"></div>
</div></div><!-- end main -->
<!-- 
<div align="center">
   <?=(isset($banner_header_content))?$banner_header_content:'';?>
</div>
 -->
<footer>
<div id="footer">

	<div class="topLinks"><div class="innerContainer">
		<a href="/founders-letter.php" class="link"><span class="header">Start Here</span><span class="description">Read our founder's letter to understand what we are we trying to accomplish</span></a>
		<a href="/searchnp.php" class="link"><span class="header">Support</span><span class="description">Support your local community by choosing a non-profit</span></a>
		<a href="/mobileapps.php" class="link"><span class="header">Mobile Apps</span><span class="description">Iphone App - use Bring It Local directly from your iphone</span></a>
<a href="/loyalty-program" class="link"><span class="header">Loyalty Program</span><span class="description">Earn points - get free local coupons and support your community merchants</span></a>
		<a href="/np/npregister.php" class="link"><span class="header">Non-profits</span><span class="description">Enroll today to harness your community's online spending</span></a>
		</div></div>
	<div class="bottomLinks">
        <div class="innerContainer clearfix">
		<div class="column col1">
			<h5 class="header">Participate</h5>	
			<!--<a href="/searchnp.php">Select a non-profit</a> 	
			<? if (!$setts['enable_private_site'] || $is_seller) { ?>
			<a href="<?=$place_ad_link;?>"><?=MSG_MM_POST_AUCTION;?></a>
			<? } ?>-->
			<a href="<?=$cfc_url;?>"><?=MSG_COMMUNITY_FOUND;?></a> 
			<a href="<?=$register_link;?>"><?=$register_btn_msg;?></a> 
			<a href="<?=$login_link;?>"><?=$login_btn_msg;?></a>
			<? if ($layout['is_contact']) { ?>
			<a href="<?=process_link('content_pages', array('page' => 'contact_us'));?>"><?=MSG_BTN_CONTACT_US;?></a>
			
			<? } ?>
			
		</div>
		<div class="column col2">
			<h5 class="header">About</h5>	
			<a href="/founders-letter.php" class="link">Founder's Letter</a>
			<a href="<?=process_link('content_pages', array('page' => 'faq'));?>"><?=MSG_BTN_FAQ;?></a>
						<? if ($layout['is_about']) { ?>
			<a href="<?=process_link('content_pages', array('page' => 'about_us'));?>"><?=MSG_BTN_ABOUT_US;?></a>
			<? } ?>
			<? if ($layout['is_contact']) { ?>
			<a href="<?=process_link('content_pages', array('page' => 'contact_us'));?>"><?=MSG_BTN_CONTACT_US;?></a>
			
			<? } ?>
		</div>

            <div class="column col3">
                <div class="subscribe_form">
                    <form class="onpageSubscribeEmail">
                        <input type="text" name="email" placeholder="<?=MSG_EMAIL;?>">
                        <input type="submit" value="<?=MSG_SUBSCRIBE;?>">
                    </form>
                    <div class="clear"></div>
                    <a href=""><?=MSG_BLOG;?></a>
                </div>

            </div>
            <div class="column col5">
               <!-- <h5 class="header">Participate</h5>
                <?php //echo $custom_pages_links;?>
                <a href="/loyalty-program">Loyalty Program</a>
                <a href="/mobileapps.php">Mobile Apps</a>
                <a href="/np/npregister_supporter.php">Suggest a Non-Profit</a>
                <a href="http://www.facebook.com/bringitlocal">Facebook</a>-->
            </div>
		<div class="column col4">
            <div class="language clearfix">
                <span class="none">Select your language</span>
                <div id="polyglotLanguageSwitcher">
                    <?php
                        $selectedLanguage = 'en';
                        if (isset($_COOKIE['language']))
                            $selectedLanguage = $_COOKIE['language'];
                    ?>
                    <form action="#">
                        <select id="polyglot-language-options">
                            <option id="en" value="en" <?=($selectedLanguage == 'en')? "selected":'' ?> >English</option>
                            <option id="fr" value="fr" <?=($selectedLanguage == 'fr')? "selected":'' ?> >Français</option>
                            <option id="de" value="de" <?=($selectedLanguage == 'de')? "selected":'' ?> >Deutsch</option>
<!--                            <option id="it" value="it" <?=($selectedLanguage == 'it')? "selected":'' ?> >Italiano</option>-->
                        </select>
                    </form>
            </div>
                </div>

			<div class="follow clearfix">
				<span class="none">Follow</span>
				<a href="http://www.facebook.com/bringitlocal" target="_blank" class="facebook">Facebook</a>
				<a href="http://www.twitter.com/bringitlocal"  target="_blank" class="twitter">Twitter</a>
				
			</div>
			<div class="share clearfix">
				<!--  <span class="none">Share</span>-->
				<!--<span><iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fbringitlocal&amp;send=false&amp;layout=button_count&amp;width=90&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90px; height:21px;" allowTransparency="true"></iframe></span>-->
				<!--<span class="google"><g:plusone size="medium"></g:plusone></span>-->
				<script type="text/javascript">
				  (function() {
					 var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
					 po.src = 'https://apis.google.com/js/plusone.js';
					 var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
				  })();
				</script>
			</div>
		</div>

        </div>
    </div>
</div>
    <div class="copyright"><div class="innerContainer">
            <div class="leftLinks">
            </div>
            <div class="rightLinks">

                <!--
                <a href="np/npregister.php">NON-PROFIT ENROLL</a>
                <a href="np/nplogin.php">NON-PROFIT SIGN-IN</a>
                -->

                <? if ($layout['is_terms']) { ?>
                    <a href="<?=process_link('content_pages', array('page' => 'terms'));?>"><?=MSG_BTN_TERMS;?></a>
                <? } ?>
                <? if ($layout['is_pp']) { ?>
                 <a href="<?=process_link('content_pages', array('page' => 'privacy'));?>"><?=MSG_BTN_PRIVACY;?></a><? } ?>
                <p class="copiright-info">
                    Copyright ©2013 Bring It Local LLC. All rights reserved
                </p>
                <a href="<?=process_link('site_fees');?>">SITE FEES</a>
                <a class="last" href="<?=process_link('content_pages', array('page' => 'help'));?>">HELP</a>
                <?php /*  Copyright &copy;2013 <a href="<?=process_link('content_pages', array('page' => 'about_us'));?>">Bring It Local, LLC</a>. All Rights Reserved
                */?>
 </div>
        </div>
</footer><!-- end footer -->

<div id="dialogModalSubscribe" title="Subscribe" style="display: none;">
    <form>
        <br>
        <p>Please provide your email address</p>
        <br>
        <fieldset>
            <label for="email">Email</label>
            <input type="text" name="dialogModalSubscribeEmail" id="dialogModalSubscribeEmail" value="" class="text ui-widget-content ui-corner-all" />
        </fieldset>
    </form>
</div>

<?=$setts['ga_code'];?>

<script type="text/javascript">
var _ubaq = _ubaq || [];
_ubaq.push(['trackGoal', 'convert']);

(function() {
var ub_script = document.createElement('script');
ub_script.type = 'text/javascript';
ub_script.src =
('https:' == document.location.protocol ? 'https://' : 'http://') +
'd3pkntwtp2ukl5.cloudfront.net/uba.js';
var s = document.getElementsByTagName('script')[0];
s.parentNode.insertBefore(ub_script, s);
}) ();
</script>



</div>
</div><!-- outerContainer -->
</body></html>
