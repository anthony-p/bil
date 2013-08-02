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
        <div class="upload_logo"><img src="http://t3.gstatic.com/images?q=tbn:ANd9GcQXkPY0BlCjoorCHkAemUqNxL9tgZsSmI06sTG_xSIxa-kuAws7"/></div>
    </div>
    <div id="about_me_content">
        <div>
            Location : <?=$user_details['city'] . ', ' . $user_details['address']?>
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
            <?=$user_details['about_me']?>
        </div>
    </div>
</div>

