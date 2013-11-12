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

		<!-- how it works image -->
		<?

		if (  false == true ){
		$mynp_userid=$_COOKIE["np_userid"];
		$npusername = $db->get_sql_field("SELECT username  FROM np_users WHERE user_id ='" . $mynp_userid . "'", username);
		$mynp = $db->get_sql_field("SELECT tax_company_name  FROM np_users WHERE user_id ='" . $mynp_userid . "'", tax_company_name);
		?>
		<?
		}else{
		?>

		<? if (!isset($member_active) || $member_active != 'Active') { ?>
			<div id="newUserBanner"><a href="<?=process_link('register');?>"><img src="themes/<?=$setts['default_theme'];?>/img/newuser.gif" width="204" height="150" border="0"></a></div>
		<? } ?>
		<?
		}
		?>
		<!-- site news -->

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
			<h5 class="header"><?= MSG_FOOTER_PARTICIPATE; ?></h5>

			<a href="<?=$cfc_url;?>"><?=MSG_COMMUNITY_FOUND;?></a> 
			<a href="<?=$register_link;?>"><?=$register_btn_msg;?></a> 
			<a href="<?=$login_link;?>"><?=$login_btn_msg;?></a>
			<? if ($layout['is_contact']) { ?>
			<a href="<?=process_link('content_pages', array('page' => 'contact_us'));?>"><?=MSG_BTN_CONTACT_US;?></a>
			
			<? } ?>
			
		</div>
		<div class="column col2">
            <h5 class="header"><?= MSG_FOOTER_ABOUT; ?></h5>
            <a href="/founders-letter.php" class="link"><?= MSG_FOOTER_FOUNDERS_LETTER; ?></a>
			<a href="<?=process_link('content_pages', array('page' => 'faq'));?>"><?=MSG_BTN_FAQ;?></a>
						<? if ($layout['is_about']) { ?>
			<a href="<?=process_link('content_pages', array('page' => 'about_us'));?>"><?=MSG_BTN_ABOUT_US;?></a>
			<? } ?>
			<? if ($layout['is_contact']) { ?>
		<a href="/mission.php" class="link"><?= MSG_FOOTER_MISSION; ?></a>
			
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

            </div>
		<div class="column col4">
            <div class="language clearfix">
                <span class="none"><?= MSG_FOOTER_SELECT_LANGUAGE; ?></span>
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
                           <option id="it" value="it" <?=($selectedLanguage == 'it')? "selected":'' ?> >Italiano</option>
                           <option id="sp" value="sp" <?=($selectedLanguage == 'sp')? "selected":'' ?> >Espagnol</option> 
                            
                        </select>
                    </form>
            </div>
                </div>

			<div class="follow clearfix">
				<span class="none"><?= MSG_FOOTER_FOLLOW; ?></span>
				<a href="http://www.facebook.com/bringitlocal" target="_blank" class="facebook">Facebook</a>
				<a href="http://www.twitter.com/bringitlocal"  target="_blank" class="twitter">Twitter</a>
				
			</div>
			<div class="share clearfix">
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

                <? if ($layout['is_terms']) { ?>
                    <a href="<?=process_link('content_pages', array('page' => 'terms'));?>"><?=MSG_BTN_TERMS;?></a>
                <? } ?>
                <? if ($layout['is_pp']) { ?>
                 <a href="<?=process_link('content_pages', array('page' => 'privacy'));?>"><?=MSG_BTN_PRIVACY;?></a><? } ?>
                <p class="copiright-info">
                    <?= MSG_FOOTER_COPYRIGHT; ?>
                </p>
                <a href="<?= process_link('site_fees'); ?>"><?= MSG_FOOTER_SITE_FEES; ?></a>
                <a class="last" href="<?= process_link('content_pages', array('page' => 'help')); ?>"><?= MSG_FOOTER_HELP; ?></a>
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
