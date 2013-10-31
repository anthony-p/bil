<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if (!defined('INCLUDED')) {
    die("Access Denied");
}
?>
<link href="/css/tinyeditor.css" rel="stylesheet">
<br>
<div class="about-me_block">
    <div id="avatar_about_me">
        <?php if (!empty($user_details['avatar'])) : ?>
            <div class="upload_logo"><img src="<?= $user_details['avatar'] ?>"/></div>
        <?php endif; ?>
    </div>
    <div class="about_me_content">
        <div class="summary-block">
            <p>
               <span class="label name">
                   <?php echo isset ($user_details["first_name"]) ? $user_details["first_name"] : '' ?> <?php echo isset ($user_details["last_name"]) ? $user_details["last_name"] : '' ?>
               </span>
            </p>
            <p>
                  <span class="label location">
                    <?php echo isset ($user_details["city"]) ? $user_details["city"] : '' ?> <?php echo isset ($user_details["address"]) ? $user_details["address"] : '' ?>
                  </span>
            </p>
            <div class="social-block">
                <?php if ((!empty($user_details['facebook_link'])) || (!empty($user_details['twitter_link'])) || (!empty($user_details['google_link']))) : ?>
                    <span class="label"><?= MSG_ALSO_FIND_ME ?></span>
                <?php endif; ?>
                <?php if (!empty($user_details['facebook_link'])) : ?>
                    <a href="<?= $user_details['facebook_link'] ?>" target="_blank">
                        <span class="social-icon inline-block facebook"></span>
                    </a>
                <?php endif; ?>
                <?php if (!empty($user_details['twitter_link'])) : ?>
                    <a href="<?= $user_details['twitter_link'] ?>" target="_blank">
                        <span class="social-icon inline-block twitter"></span>
                    </a>
                <?php endif; ?>
                <?php if (!empty($user_details['google_link'])) : ?>
                    <a href="<?= $user_details['google_link'] ?>" target="_blank">
                        <span class="social-icon inline-block google"></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <br/>

        <div id="about_me_content">

            <div>
                <?php if (!empty($user_details['about_me'])) : ?>
                    <?= html_entity_decode($user_details['about_me']) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

