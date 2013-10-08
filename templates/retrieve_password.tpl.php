<?
#################################################################
## PHP Pro Bid v6.00															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<div class="login-cont">

    <?=$header_message;?>
    <br>
    <?=isset($retrieve_password_msg) ? $retrieve_password_msg : '';?>
    <? if (!isset($submitted) || !$submitted) { ?>
        <div class="left">
            <h2><?=MSG_REMEMBER_EMAIL_FORGOT_PASS;?></h2>
<!--            <h2>--><?//=MSG_REMEMBER_USERNAME_FORGOT_PASS;?><!--</h2>-->
            <form action="retrieve_password.php" method="post">
                <input type="hidden" name="operation" value="retrieve_password">
                <div class="form-row">

                    <input name="email" type="text" class="text" id="email" size="20" value="<?=$post_details['email'];?>" placeholder="<?=MSG_ENTER_YOUR_EMAIL?>">
                </div>

                <div class="form-row">

<!--                    <input name="username" type="text" class="text" id="username" size="20" value="--><?//=$post_details['username'];?><!--" placeholder="--><?//=MSG_ENTER_YOUR_USERNAME?><!--">-->
                </div>
                <div class="clear"></div>
                <input name="form_retrieve_password_proceed" type="submit" class="buttons" id="form_retrieve_password_proceed" value="<?=MSG_RETRIEVE_YOUR_PASSWORD;?>">

            </form>
        </div>
    <? } ?>
<!--    <div class="right">-->
<!--        --><?//=isset($retrieve_username_msg) ? $retrieve_username_msg : '';?>
<!--        --><?// if (!isset($submitted) || !$submitted) { ?>
<!--        <h2>--><?//=MSG_REMEMBER_PASS_FORGOT_USERNAME;?><!--</h2>-->
<!--        <form action="retrieve_password.php" method="post">-->
<!--            <input type="hidden" name="operation" value="retrieve_username">-->
<!---->
<!--            <div class="form-row">-->
<!---->
<!--                 <input name="email_address" type="text" id="email_address" size="20" class="text" value="--><?//=$post_details['email_address'];?><!--" placeholder="--><?//=MSG_ENTER_YOUR_EMAIL?><!--">-->
<!--    </div>-->
<!--    <div class="clear"></div>-->
<!--<input name="form_retrieve_username_proceed" type="submit" class="buttons" id="form_retrieve_username_proceed" value="--><?//=MSG_RETRIEVE_YOUR_USERNAME;?><!--">-->
<!---->
<!--    </form>-->
<?// } ?>
<!--</div>-->
</div>
