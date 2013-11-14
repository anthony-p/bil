<?
#################################################################
## PHP Pro Bid v6.00															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if (!defined('INCLUDED')) {
    die("Access Denied");
}
?>

<div class="about-me_block">
    <div class="summary-block clrfix">
        <div id="avatar_about_me">
            <div class="upload_logo">
                <?php if (!empty($user_details['avatar'])) : ?>
                    <img src="<?= $user_details['avatar'] ?>"/>
                <?php else: ?>
                    <img src="/themes/bring_it_local/img/bring-it-local-no-user-photo.jpg"/>
                <?php endif; ?>
            </div>
        </div>
        <div class="info">
            <p>
            <span class="label name">
            	<?php if (!empty($user_details['organization'])) {?>
            		<?php echo $user_details["organization"];?>	
            	<?php }else {?>
            		<?php echo isset ($user_details["first_name"]) ? $user_details["first_name"] : '' ?>  <?php echo isset ($user_details["last_name"]) ? $user_details["last_name"] : '' ?>
            	<?php }?>
            </span>
            </p>

            <p>
                <span class="label location">
                <?php echo isset ($user_details["city"]) ? $user_details["city"] : '' ?> <?php echo isset ($user_details["address"]) ? $user_details["address"] : '' ?>
                    </span>
            </p>

            <div class="social-block">
                <?php if (($user_details['facebook_link'] != 'http://www.facebook.com/') || ($user_details['twitter_link'] != 'http://www.twitter.com/') || ($user_details['google_link'] != 'https://plus.google.com/')) : ?>
                    <span class="label"><?= MSG_ALSO_FIND_ME ?></span>
                <?php endif; ?>
                <?php if ($user_details['facebook_link'] != 'http://www.facebook.com/') : ?>
                    <a href="<?= $user_details['facebook_link'] ?>" target="_blank">
                        <span class="social-icon inline-block facebook"></span>
                    </a>
                <?php endif; ?>
                <?php if ($user_details['twitter_link'] != 'http://www.twitter.com/') : ?>
                    <a href="<?= $user_details['twitter_link'] ?>" target="_blank">
                        <span class="social-icon inline-block twitter"></span>
                    </a>
                <?php endif; ?>
                <?php if ($user_details['google_link'] != 'https://plus.google.com/') : ?>
                    <a href="<?= $user_details['google_link'] ?>" target="_blank">
                        <span class="social-icon inline-block google"></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <div class="clear"></div>
    <div id="about_me_content">
        <div class="content_about">
            <?php if (!empty($user_details['about_me'])) : ?>
                <?= html_entity_decode($user_details['about_me']) ?>
            <?php endif; ?>
        </div>
    </div>


