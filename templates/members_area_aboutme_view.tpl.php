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
<div>
    <div id="avatar_about_me">
        <?php if(!empty($user_details['avatar'])) :?>
            <div class="upload_logo"><img src="<?=$user_details['avatar']?>"/></div>
        <?php endif;?>
    </div>
    <div>
        <p>
            <?php echo isset ($campaign["first_name"]) ? $campaign["first_name"] : '' ?> <?php echo isset ($campaign["last_name"]) ? $campaign["last_name"] : '' ?>
        </p>
    </div>
    <div id="about_me_content">
        <div>
            <p>
                Location:
                <?php echo isset ($campaign["city"]) ? $campaign["city"] : '' ?> <?php echo isset ($campaign["address"]) ? $campaign["address"] : '' ?>
            </p>
        </div>
        <div>
            Also Find Me on
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
            <?php if (!empty($user_details['google_link'])) :?>
                <?=$user_details['about_me']?>
            <?php endif; ?>
        </div>
    </div>
</div>

