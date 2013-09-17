<?
#################################################################
## PHP Pro Bid v6.00															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<link href="/css/tinyeditor.css" rel="stylesheet">
<br>
<div class="about-me_block">
    <div id="avatar_about_me">
        <?php if(!empty($user_details['avatar'])) :?>
            <div class="upload_logo_about"><img src="<?=$user_details['avatar']?>"/></div>
        <?php endif;?>
    </div>
    <div id="about_me_content">
        <div>
            <p>
                <span><?php echo isset ($user_details["first_name"]) ? $user_details["first_name"] : '' ?>  <?php echo isset ($user_details["last_name"]) ? $user_details["last_name"] : '' ?></span>
            </p>
        </div>

        <div>
            <p>
                <span>Location:</span>
                <?php echo isset ($user_details["city"]) ? $user_details["city"] : '' ?> <?php echo isset ($user_details["address"]) ? $user_details["address"] : '' ?>
            </p>
        </div>
        <div>
            <p>
            <span><?=MSG_ALSO_FIND_ME?></span>
            <?php if (!empty($user_details['facebook_link'])) :?>
                <label class="facebook">
                    <a href="<?=$user_details['facebook_link']?>" target="_blank">facebook</a>
                </label>
            <?php endif;?>
            <?php if (!empty($user_details['twitter_link'])) :?>
                <label class="twitter">
                    <a href="<?=$user_details['twitter_link']?>" target="_blankle">twitter</a>
                </label>
            <?php endif;?>
            <?php if (!empty($user_details['google_link'])) :?>
                <label class="google">
                    <a href="<?=$user_details['google_link']?>" target="_blank">Google ++</a>
                </label>
            <?php endif;?>
            </p>
        </div>
        <div class="clear"></div>
        <div class="content_about">
            <?php if (!empty($user_details['about_me'])) :?>
                <?=html_entity_decode($user_details['about_me'])?>
            <?php endif; ?>
        </div>
    </div>
</div>

