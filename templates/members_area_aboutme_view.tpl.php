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
            <div class="upload_logo"><img src="<?=$user_details['avatar']?>"/></div>
        <?php endif;?>
    </div>
    <div>
        <p>
            <span><?php echo isset ($user_details["first_name"]) ? $user_details["first_name"] : '' ?></span> <?php echo isset ($user_details["last_name"]) ? $user_details["last_name"] : '' ?>
        </p>
    </div>
    <div id="about_me_content">
        <div>
            <p>
                <span>Location:</span>
                <?php echo isset ($user_details["city"]) ? $user_details["city"] : '' ?> <?php echo isset ($user_details["address"]) ? $user_details["address"] : '' ?>
            </p>
        </div>
        <div>
            <span><?=MSG_ALSO_FIND_ME?></span>
            <?php if (!empty($user_details['facebook_link'])) :?>
            <?php if (!empty($user_details['facebook_link'])) :?>
                <label class="facebook">
                    <a href="<?=$user_details['facebook_link']?>" target="_blank">Facebook</a>
                </label>
            <?php endif;?>
            <?php if (!empty($user_details['twitter_link'])) :?>
                <label class="twitter">
                    <a href="<?=$user_details['twitter_link']?>" target="_blank">Twitter</a>
                </label>
            <?php endif;?>
            <?php if (!empty($user_details['google_link'])) :?>
                <label class="google">
                    <a href="<?=$user_details['google_link']?>" target="_blank">Google+</a>
                </label>
            <?php endif;?>
        </div>
        <div>
            <?php if (!empty($user_details['about_me'])) :?>
                <?=html_entity_decode($user_details['about_me'])?>
            <?php endif; ?>
        </div>
    </div>
</div>

