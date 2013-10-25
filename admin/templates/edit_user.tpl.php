<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<script type="text/javascript" src='/scripts/jquery/jquery-1.9.1.js'></script>
<script type="text/javascript" src='/scripts/jquery/jquery.validate.min.js'></script>
<script type="text/javascript" src="/scripts/jquery/additional-methods.min.js"></script>

<style>
    .error{
        color: #ff0000;
        float: right;
        clear: both;
    }
</style>

<script type="text/javascript">
    $(document).ready(function(){


//        $("#form_registration").validate({
//
//            errorElement: 'em',
//
//            rules: {
//
//                first_name: "required",
//                last_name: "required",
//
//                email: {
//                    required: true,
//                    email: true
//                },
//                email_check: {
//                    equalTo: "#email"
//                },
//                password: {
//                    minlength: 6
//                },
//                password2: {
//                    equalTo: "#password"
//                }
//            }
//        });


        $("#form_registration").validate({

            errorElement: 'em',

            rules: {
                password: {
                    minlength: 6
                },
                password2: {
                    equalTo: "#password"
                }
            }
        });


    });
</script>

<?=$header_registration_message;?>
<br>
<?php if (isset($banned_email_output)) echo $banned_email_output;?>
<?php if(isset($display_formcheck_errors)) echo $display_formcheck_errors;?>
<?php if(isset($check_voucher_message)) echo $check_voucher_message;?>

<form action="<?=$register_post_url;?>" method="post" id="form_registration" name="registration_form" class="registrationForm">
    <input type="hidden" name="operation" value="submit">
    <input type="hidden" name="do" value="<? if(isset($do)) echo $do;?>">
    <input type="hidden" name="user_id" value="<? if (isset($user_details['id'])) echo $user_details['id'];?>">
<!--    <input type="hidden" name="phone" value="--><?// if (isset($user_details['phone'])) echo $user_details['phone'];?><!--">-->
<!--    <input type="hidden" name="city" value="--><?// if (isset($user_details['city'])) echo $user_details['city'];?><!--">-->
<!--    <input type="hidden" name="state" value="--><?// if (isset($user_details['state'])) echo $user_details['state'];?><!--">-->
<!--    <input type="hidden" name="country" value="--><?// if (isset($user_details['country'])) echo $user_details['country'];?><!--">-->
<!--    <input type="hidden" name="postal_code" value="--><?// if (isset($user_details['postal_code'])) echo $user_details['postal_code'];?><!--">-->
<!--    <input type="hidden" name="address" value="--><?// if (isset($user_details['address'])) echo $user_details['address'];?><!--">-->
<!--    <input type="hidden" name="confirmed_paypal_email" value="--><?// if (isset($user_details['confirmed_paypal_email'])) echo $user_details['confirmed_paypal_email'];?><!--">-->
    <input type="hidden" name="payment_mode" value="<? if (isset($user_details['payment_mode'])) echo $user_details['payment_mode'];?>">
    <input type="hidden" name="birthdate" value="<? if (isset($user_details['birthdate'])) echo $user_details['birthdate'];?>">
    <input type="hidden" name="birthdate_year" value="<? if (isset($user_details['birthdate_year'])) echo $user_details['birthdate_year'];?>">
<!--    <input type="hidden" name="tax_account_type" value="--><?// if (isset($user_details['tax_account_type'])) echo $user_details['tax_account_type'];?><!--">-->
<!--    <input type="hidden" name="tax_company_name" value="--><?// if (isset($user_details['tax_company_name'])) echo $user_details['tax_company_name'];?><!--">-->
<!--    <input type="hidden" name="tax_reg_number" value="--><?// if (isset($user_details['tax_reg_number'])) echo $user_details['tax_reg_number'];?><!--">-->
<!--    <input type="hidden" name="extended_registration" value="--><?// if (isset($user_details['extended_registration'])) echo $user_details['extended_registration'];?><!--">-->
<!--    <input type="hidden" name="pg_paypal_first_name" value="--><?// if (isset($user_details['pg_paypal_first_name'])) echo $user_details['pg_paypal_first_name'];?><!--">-->
<!--    <input type="hidden" name="pg_paypal_last_name" value="--><?// if (isset($user_details['pg_paypal_last_name'])) echo $user_details['pg_paypal_last_name'];?><!--">-->
<!--    <input type="hidden" name="pg_paypal_email" value="--><?// if (isset($user_details['pg_paypal_email'])) echo $user_details['pg_paypal_email'];?><!--">-->
    <input type="hidden" name="edit_refresh" value="0">
    <!--
    <input type="hidden" name="generated_pin" value="<? if (isset($generated_pin)) echo $generated_pin;?>">   -->

    <!-- main details -->
        <h2><?=MSG_MAIN_DETAILS;?></h2>

        <?php
        if(isset($user_details['name'])){
            list($user_details['fname'],$user_details['lname']) = preg_split('/\s+(?=[^\s]+$)/', $user_details['name'], 2);
        }
//        var_dump($user_details);
        ?>
    <div class="form-cont">
        <div class="form-row">
            <label><?=MSG_FIRST_NAME;?>*</label>
            <input name="first_name" type="text" id="first_name" class="text"
                   value="<? if (isset($user_details['first_name'])) echo $user_details['first_name'];?>" size="40" />
        </div>
        <div class="form-row">
            <label><?=MSG_LAST_NAME;?>*</label>
            <input name="last_name" type="text" id="last_name"  class="text"
                   value="<? if (isset($user_details['last_name'])) echo $user_details['last_name'];?>" size="40"/>
        </div>
        <div class="form-row">
            <label><?=MSG_ORGANIZATION;?></label>
            <input name="organization" type="text" id="organization"  class="text"
                   value="<? if (isset($user_details['organization'])) echo $user_details['organization'];?>" size="40"/>
        </div>

        <div class="form-row">
            <label><?=MSG_EMAIL_ADDRESS;?>*</label>
            <input name="email" type="text" class="contentfont text" id="email"  value="<? if (isset($user_details['email'])) echo $user_details['email'];?>" size="40" maxlength="120" <? echo (defined('IN_ADMIN') && IN_ADMIN == 1) ? 'onchange="copy_email_value();"' : ''; ?> />
<!--           <span> --><?//=MSG_EMAIL_EXPLANATION;?><!--</span>-->
        </div>

        <div class="form-row">
            <label><?=MSG_PHONE;?>*</label>
            <input name="phone" type="text" class="contentfont text" id="phone"
                   value="<? if (isset($user_details['phone'])) echo $user_details['phone'];?>" size="40" maxlength="120" />
        </div>

        <div class="form-row">
            <label><?=MSG_ADDRESS;?>*</label>
            <input name="address" type="text" class="contentfont text" id="address"
                   value="<? if (isset($user_details['address'])) echo $user_details['address'];?>" />
        </div>

        <div class="form-row">
            <label><?=MSG_CITY;?>*</label>
            <input name="city" type="text" class="contentfont text" id="city"
                   value="<? if (isset($user_details['city'])) echo $user_details['city'];?>" size="40" maxlength="120" />
        </div>

        <div class="form-row">
            <label><?=MSG_COUNTRY;?>*</label>
            <? echo (isset($country_dropdown)) ? $country_dropdown : '';?>
        </div>

        <div class="form-row">
            <label><?=MSG_STATE;?>*</label>
            <? echo (isset($state_box)) ? $state_box : '';?>
        </div>

        <div class="form-row">
            <label><?=MSG_POSTALE_CODE;?>*</label>
            <input name="postal_code" type="text" class="contentfont text" id="postal_code"
                   value="<? if (isset($user_details['postal_code'])) echo $user_details['postal_code'];?>" size="40" maxlength="120" />
        </div>

        <div class="form-row">
            <label><?=AMSG_PAYPAL_EMAIL_ADDRESS;?>*</label>
            <input name="pg_paypal_email" type="text" class="contentfont text" id="pg_paypal_email"
                   value="<? if (isset($user_details['pg_paypal_email'])) echo $user_details['pg_paypal_email'];?>" size="40" maxlength="120" />
        </div>

        <div class="form-row">
            <label><?=AMSG_PAYPAL_FIRST_NAME;?>*</label>
            <input name="pg_paypal_first_name" type="text" class="contentfont text" id="pg_paypal_first_name"
                   value="<? if (isset($user_details['pg_paypal_first_name'])) echo $user_details['pg_paypal_first_name'];?>" size="40" maxlength="120" />
        </div>

        <div class="form-row">
            <label><?=AMSG_PAYPAL_LAST_NAME;?>*</label>
            <input name="pg_paypal_last_name" type="text" class="contentfont text" id="pg_paypal_last_name"
                   value="<? if (isset($user_details['pg_paypal_last_name'])) echo $user_details['pg_paypal_last_name'];?>" size="40" maxlength="120" />
        </div>

        <div class="form-row">
           <label> <?=MSG_PASSWORD;?>*</label>
            <input name="password" type="password" class="contentfont text" id="password"  value="" size="40"
                   maxlength="120" <? echo (defined('IN_ADMIN') && IN_ADMIN == 1) ? 'onchange="copy_password_value();"' : ''; ?> />
           <span> <?=MSG_PASSWORD_EXPLANATION;?></span>
        </div>

        <div class="form-row">
           <label> <?=MSG_RETYPE_PASSWORD;?>* </label>
           <input name="password2" type="password" class="contentfont text"  id="password2" value="" size="40"
                  maxlength="120" onkeyup="checkPass();" class="text" >
                <img src="<?php if (isset($path_relative)) echo $path_relative;?>themes/<?=$setts['default_theme'];?>/img/system/check_img.gif" id="pass_img" align="absmiddle" style="display:none;" />
        </div>

        <div class="form-row check">

            <?
                if (isset ($_POST["newsletter"]))
                    $newsletter = $_POST["newsletter"];
                else
                    $newsletter = "";
            ?>
            <input name="newsletter" type="checkbox" class="newsletter" id="newsletter" value="1" checked />
            <label>Please send me the newsletter</label>
        </div>
        <div class="clear"></div>
        <input name="form_register_proceed" type="submit" id="form_register_proceed" class="buttons" value="<?=$proceed_button;?>" />
    </div>

</form>
