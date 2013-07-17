<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
global $coupon_url;
?>
<!--div align="center">
   <?=$banner_header_content;?>
</div-->
<div id="footer">
	<div class="topLinks"><div class="innerContainer">
		<a href="#" class="link"><span class="header">Sell</span><span class="description">Merchant Login * Start your own deal and enjoy the benefits</span></a>
		<a href="#" class="link"><span class="header">Invite</span><span class="description">Refer a friend and get discount, via Advert System</span></a>
		<a href="#" class="link"><span class="header">Promote</span><span class="description">Get your business featured on Bring It Local - Coupons</span></a>
		<a href="#" class="link"><span class="header">Support</span><span class="description">Support your local community by choosing a non-profit</span></a>
		<a href="#" class="link"><span class="header">Help</span><span class="description">Merchant Login - Start your own deal and enjoy the benefits</span></a>
	</div></div>
	<div class="bottomLinks"><div class="innerContainer">
		<div class="column col1">
			<h5 class="header">Navigate</h5>	
			<? if (!$setts['enable_private_site'] || $is_seller) { ?>
			<a href="<?=$place_ad_link;?>"><?=$place_ad_btn_msg;?></a>
			<? } ?>
			<a href="<?=$register_link;?>"><?=$register_btn_msg;?></a>
			<a href="<?=$login_link;?>"><?=$login_btn_msg;?></a>
			<a href="<?=process_link('content_pages', array('page' => 'help'));?>"><?=MSG_BTN_HELP;?></a>
		</div>
		<div class="column col2">
			<h5 class="header">About</h5>	
			<a href="<?=process_link('content_pages', array('page' => 'faq'));?>"><?=MSG_BTN_FAQ;?></a>
			<a href="<?=process_link('site_fees');?>"><?=MSG_BTN_SITE_FEES;?></a>
			<? if ($layout['is_about']) { ?>
			<a href="<?=process_link('content_pages', array('page' => 'about_us'));?>"><?=MSG_BTN_ABOUT_US;?></a>
			<? } ?>
			<? if ($layout['is_contact']) { ?>
			<a href="<?=process_link('content_pages', array('page' => 'contact_us'));?>"><?=MSG_BTN_CONTACT_US;?></a>
			<? } ?>
		</div>
		<div class="column col3">
			<h5 class="header">Terms</h5>	
            	<?=$custom_pages_links;?>
			<a href="/np/npregister_supporter.php">Submit a non profit organization</a> 
			<a href="http://blog.bringitlocal.com" target="_blank">Blog</a>
			<a href="/stores.php">Stores</a>
		</div>
		<div class="column col4">
			<div class="nonProfits">
				<div class="title">Choose from lots of non-profits</div>
				<a href="searchnp.php" class="link">view list</a>
				<div class="avatars clearfix">
					<a href="#" class="avatar"><img src="http://profile.ak.fbcdn.net/hprofile-ak-snc4/370030_685634406_1043706957_q.jpg" border="1"></a>
					<a href="#" class="avatar"><img src="http://profile.ak.fbcdn.net/hprofile-ak-snc4/370030_685634406_1043706957_q.jpg" border="1"></a>
					<a href="#" class="avatar"><img src="http://profile.ak.fbcdn.net/hprofile-ak-snc4/370030_685634406_1043706957_q.jpg" border="1"></a>
					<a href="#" class="avatar"><img src="http://profile.ak.fbcdn.net/hprofile-ak-snc4/370030_685634406_1043706957_q.jpg" border="1"></a>
					<a href="#" class="avatar"><img src="http://profile.ak.fbcdn.net/hprofile-ak-snc4/370030_685634406_1043706957_q.jpg" border="1"></a>
					<a href="#" class="avatar"><img src="http://profile.ak.fbcdn.net/hprofile-ak-snc4/370030_685634406_1043706957_q.jpg" border="1"></a>
					<a href="#" class="avatar"><img src="http://profile.ak.fbcdn.net/hprofile-ak-snc4/370030_685634406_1043706957_q.jpg" border="1"></a>
					<a href="#" class="avatar"><img src="http://profile.ak.fbcdn.net/hprofile-ak-snc4/370030_685634406_1043706957_q.jpg" border="1"></a>
				</div>
			</div>
			<div class="follow clearfix">
				<span>Follow</span>
				<a href="http://www.facebook.com/bringitlocal" target="_blank" class="facebook">Facebook</a>
				<a href="#" class="twitter">Twitter</a>
				<a href="#" class="google">Google+</a>
				<a href="#" class="rss">Rss</a>
			</div>
			<div class="share clearfix">
				<span>Share</span>
				<span><iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fbringitlocal&amp;send=false&amp;layout=button_count&amp;width=90&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90px; height:21px;" allowTransparency="true"></iframe></span>
				<span><g:plusone size="medium"></g:plusone></span>
				<script type="text/javascript">
				  (function() {
					 var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
					 po.src = 'https://apis.google.com/js/plusone.js';
					 var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
				  })();
				</script>
			</div>
		</div>
	</div></div>
	<div class="copyright"><div class="innerContainer">
		<div class="leftLinks">
		</div>
		<div class="rightLinks">
			Copyright &copy;2012 <a href="<?=process_link('content_pages', array('page' => 'about_us'));?>">Bring It Local, LLC</a>. All Rights Reserved<br>
			<? if ($layout['is_terms']) { ?>
			<a href="<?=process_link('content_pages', array('page' => 'terms'));?>"><?=MSG_BTN_TERMS;?></a>
			<? } ?>
			<? if ($layout['is_pp']) { ?> 
			&nbsp;|&nbsp; <a href="<?=process_link('content_pages', array('page' => 'privacy'));?>"><?=MSG_BTN_PRIVACY;?></a>
			<? } ?>         
		</div>
	</div></div>
</div><!-- end footer -->
<?=$setts['ga_code'];?>
</div><!-- outerContainer -->
</body></html>
