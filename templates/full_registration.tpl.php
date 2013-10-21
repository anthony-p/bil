<?
//var_dump($user_details);
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<link rel="stylesheet" href="/themes/bring_it_local/tabs-style.css" />
<script type="text/javascript" src='/scripts/jquery/jquery.validate.min.js'></script>
<script type="text/javascript" src="/scripts/jquery/additional-methods.min.js"></script>
<script type="text/javascript" src="/scripts/account_form_validate.js"></script>
<script language="javascript">

    function copy_email_value() {
        document.registration_form.email_check.value = document.registration_form.email.value;
    }

    function copy_password_value() {
        document.registration_form.password2.value = document.registration_form.password.value;
    }

    window.error_messages = {
        fname: "<?= MSG_MEMBER_ACCOUNT_VALIDATION_ERR_FNAME; ?>",
        lname: "<?= MSG_MEMBER_ACCOUNT_VALIDATION_ERR_LNAME; ?>",
        organization: "<?= MSG_MEMBER_ACCOUNT_VALIDATION_ERR_ORGANIZATION; ?>",
        address: "<?= MSG_MEMBER_ACCOUNT_VALIDATION_ERR_ADDRESS; ?>",
        city: "<?= MSG_MEMBER_ACCOUNT_VALIDATION_ERR_CITY; ?>",
        state: "<?= MSG_MEMBER_ACCOUNT_VALIDATION_ERR_STATE; ?>",
        postal_code: "<?= MSG_MEMBER_ACCOUNT_VALIDATION_ERR_ZIP; ?>",
        phone: "<?= MSG_MEMBER_ACCOUNT_VALIDATION_ERR_PHONE; ?>",

        password2: "<?= MSG_VERIFY_PASSWORD_REQUIRED; ?>",
        old_password: "<?= MSG_PASSWORD_REQUIRED; ?>",
        email: "<?= MSG_MEMBER_ACCOUNT_VALIDATION_ERR_EMAIL; ?>",
        email_check: "<?= MSG_MEMBER_ACCOUNT_VALIDATION_ERR_CEMAIL; ?>",
        email_check_notequal: "<?= MSG_MEMBER_ACCOUNT_VALIDATION_ERR_EQ_EMAIL; ?>",
        pin_value: "<?= MSG_MEMBER_ACCOUNT_VALIDATION_ERR_PIN; ?>"

    };


    $(document).ready(function () {
        // load state list
        $("#country").change(function () {
            $.ajax({
                type: "POST",
                async: false,
                url: "/ajaxprocessors",
                dataType: 'json',
                data: {do: 'stateList', country_id: $("#country").val()}
            })
                .done(function (result) {
                    var statesbox = $("#states_box");
                    statesbox.empty();
                    statesbox.append(result.data);
                });

        });
        $('input[type="submit"]').on('click', function(e){
                e.preventDefault();
                var form = $("#registration_form"),
                    button = $(this);
                validateAccountForm(form);
                if (form.valid()) { ajaxFormSave(button, form)}
        });

        $('.form_tooltip').tooltip({
            track: true
        });

    });
</script>

<?
function fetchstate($statecode){
    $sql="SELECT name FROM probid_countries WHERE id = '".$statecode."'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array( $result );
    $statename=$row['name'];
    return $statename;
}
?>



<?php if (isset($members_area_page_content)) echo $header_registration_message;?>
<br>
<?php if (isset($banned_email_output)) echo $banned_email_output;?>
<?php if (isset($display_formcheck_errors)) echo $display_formcheck_errors;?>
<?php if(isset($check_voucher_message)) echo $check_voucher_message;?>

<form action="<?php if(isset($register_post_url)) echo $register_post_url;?>" method="post" name="registration_form" id="registration_form" class="registrationForm">
<input type="hidden" name="operation" value="submit">
<input type="hidden" name="do" value="<?=$do;?>">
<input type="hidden" name="user_id" value="<?=$user_details['id'];?>">
<input type="hidden" name="edit_refresh" value="0">
<input type="hidden" name="generated_pin" value="<? if(isset($generated_pin)) echo $generated_pin;?>">
<!-- main details -->
<table class="tbl">
    <tr>
        <th colspan="2"><?=MSG_MAIN_DETAILS;?></th>
    </tr>

    <?php
    if(isset($user_details['name'])){
        list($user_details['first_name'],$user_details['last_name']) = preg_split('/\s+(?=[^\s]+$)/', $user_details['name'], 2);
    }
    ?>
    <tr>
        <td class="leftCol"><?=MSG_FIRST_NAME;?> *</td>
        <td class="contentfont"><input name="fname" type="text" id="fname" value="<?=$user_details['first_name'];?>" size="40" />

            <br>

            <input name="affiliate" type="hidden" id="affiliate" value="<?=(isset($_POST['affiliate']))?$_POST['affiliate']:'';?>" size="40" />

        </td>
    </tr>

    <tr>
        <td class="leftCol"><?=MSG_LAST_NAME;?> *</td>
        <td class="contentfont"><input name="lname" type="text" id="lname" value="<?=$user_details['last_name'];?>" size="40" /></td>
    </tr>
    <tr>
        <td class="leftCol"><?=MSG_ORGANIZATION;?></td>
        <td class="contentfont">
            <input name="organization" type="text" id="organization"
                   value="<?=$user_details['organization'];?>" size="40" />
        </td>
    </tr>

    <? if (isset($user_details['tax_account_type']) && $user_details['tax_account_type']) { ?>
    <tr>
        <td  class="contentfont"><?=MSG_COMPANY_NAME;?> *</td>
        <td class="contentfont"><input name="tax_company_name" type="text" class="contentfont" id="tax_company_name" value="<?=$user_details['tax_company_name'];?>" size="40" /></td>
    </tr>

    <? } ?>
    <tr>
        <td class="leftCol"><?=MSG_ADDRESS;?> *</td>
        <td class="contentfont"><input name="address" type="text" id="address" value="<?=$user_details['address'];?>" size="40" /></td>
    </tr>

    <tr>
        <td class="leftCol"><?=MSG_CITY;?> *</td>
        <td class="contentfont"><input name="city" type="text" id="city" value="<?=$user_details['city'];?>" size="25" /></td>
    </tr>


    <tr>
        <td class="leftCol"><?=MSG_COUNTRY;?> *</td>
        <td class="contentfont">
            <?=$country_dropdown;?>
        </td>
    </tr>

    <tr>
        <td class="leftCol"><?=MSG_STATE;?> *</td>
        <td class="contentfont">
            <div id="states_box"><?= $state_box; ?></div>
        </td>
    </tr>

    <tr>
        <td class="leftCol"><?=MSG_POSTALE_CODE;?> *</td>
        <td class="contentfont"><input name="postal_code" type="text" id="postal_code" value="<?=(isset($user_details['postal_code']))?$user_details['postal_code']:'';?>" size="25" /></td>
    </tr>
    <tr>
        <td class="leftCol"><?=MSG_PHONE;?> *</td>
        <td class="contentfont">

            <input name="phone" type="text" id="phone" value="<?=(isset($user_details['phone']))?$user_details['phone']:'';?>" size="25" />
            </td>
    </tr>

</table>

<!-- User Account Details -->
<table class="tbl">
    <tr>
        <th colspan="2"><?=MSG_USER_ACCOUNT_DETAILS; ?></th>
    </tr>
    <tr>
        <td class="leftCol"><?=MSG_EMAIL_ADDRESS;?> *
        </td>
        <td class="contentfont"><input name="email" type="text" class="contentfont" id="email" value="<?=(isset($user_details['email']))?$user_details['email']:'';?>" size="40" maxlength="120" <? echo (defined('IN_ADMIN') && IN_ADMIN == 1) ? 'onchange="copy_email_value();"' : ''; ?> /></td>
    </tr>
    <tr>
        <td class="contentfont"><?=MSG_RETYPE_EMAIL;?> *</td>
        <td class="contentfont"><input name="email_check" type="text" class="contentfont" id="email_check" value="<?=$email_check_value;?>" size="40" maxlength="120" >
            <img src="<?=(isset($path_relative))?$path_relative:'';?>themes/<?=$setts['default_theme'];?>/img/system/check_img.gif" id="email_img" align="absmiddle" style="display:none;" /></td>
    </tr>
    <tr>
        <td class="leftCol"><?=MSG_PLEASE_SEND;?>....</td>
        <td class="contentfont">

            <?
            $newsletter = isset($user_details['is_subscribe_news'])?$user_details['is_subscribe_news']:0;
            ?>

            <input name="newsletter" type="checkbox" class="newsletter" id="newsletter" value="1" <?php if($newsletter):?>checked<?php endif;?> />


        </td>

    </tr>
    <tr>
        <td  class="contentfont"><?=MSG_CREATE_PASS;?>
        </td>
        <td class="contentfont"><input name="password" type="password" class="contentfont" autocomplete="off" id="password" size="40" maxlength="20" <? echo ((defined('IN_ADMIN')) && IN_ADMIN == 1) ? 'onchange="copy_password_value();"' : ''; ?> /></td>
    </tr>
    <tr>
        <td  class="contentfont"><?=MSG_VERIFY_PASS;?></td>
        <td class="contentfont"><input name="password2" type="password" autocomplete="off" id="password2" size="40" maxlength="20" />
            <img src="<?=(isset($path_relative))?$path_relative:'';?>themes/<?=$setts['default_theme'];?>/img/system/check_img.gif" id="pass_img" align="absmiddle" style="display:none;" /></td>
    </tr>
    <tr>
        <td  class="contentfont"><?=MSG_OLD_PASS;?>
        </td>
        <td class="contentfont">
            <input name="old_password" type="password" class="contentfont" autocomplete="off"
                   id="old_password" size="40" maxlength="20" />
        </td>
    </tr>
</table>
<?=$custom_sections_table;?>
<? if (defined('IN_ADMIN') && IN_ADMIN == 1) { ?>
<table class="tbl">
    <tr>
        <td colspan="2"><?=AMSG_PAYMENT_SETTINGS;?></td>
    </tr>
    <tr>
        <td class="leftCol"><?=AMSG_PAYMENT_MODE;?></td>
        <td class="contentfont"><input type="radio" name="payment_mode" value="2" <? echo ($user_details['payment_mode']==2) ? 'checked' : '';?>>
            <?=GMSG_ACCOUNT;?>
            <input type="radio" name="payment_mode" value="1" <? echo ($user_details['payment_mode']==1) ? 'checked' : '';?>>
            <?=GMSG_LIVE;?></td>
    </tr>
    <? if ($user_details['payment_mode'] == 2) { ?>
    <tr>
        <td colspan="2"><span class="reguser"><?=AMSG_PAYMENT_MODE_EXPL;?></span></td>
    </tr>
    <tr>
        <td  class="contentfont"><?=AMSG_ACCOUNT_BALANCE;?>
        </td>
        <td class="contentfont"><?=$setts['currency']; ?> <input name="balance" value="<?=abs($user_details['balance']); ?>" size="8">
            <select name="balance_type">
                <option value="-1" selected><?=GMSG_CREDIT;?></option>
                <option value="1" <? echo ($user_details['balance']>0) ? 'selected' : '';?> ><?=GMSG_DEBIT;?></option>
            </select> &nbsp; <?=AMSG_BALANCE_ADJ_REASON;?>: <input type="text" name="adjustment_reason" size="20"> (<?=AMSG_OPTIONAL_FIELD;?>)</td>
    </tr>
    <tr class="reguser">
        <td colspan="2"><span class="reguser"><?=AMSG_ACCOUNT_BALANCE_EXPL;?></span></td>
    </tr>
    <tr>
        <td  class="contentfont"><?=GMSG_MAX_DEBIT;?></td>
        <td class="contentfont"><?=$setts['currency']; ?> <input name="max_credit" value="<?=abs($user_details['max_credit']); ?>" size="8"></td>
    </tr>
    <tr >
        <td colspan="2"><span class="reguser"><?=AMSG_MAX_DEBIT_EXPL;?></span></td>
    </tr>
    <? } ?>
</table>
    <? } ?>

<? if (!empty($display_direct_payment_methods)) { ?>
<!-- Direct Payment Settings -->
<table border="0" cellpadding="0" cellspacing="0" class="tbl"
    <?php if (isset($user_details['confirmed_paypal_email']) && $user_details['confirmed_paypal_email']) echo 'id="directPayment"' ?>>
    <tr>
        <th colspan="2"><?=MSG_DIRECT_PAYMENT_SETTINGS;?>
            <div style="width:40px; float: right; margin-left: 10px;" class="form_tooltip"><a href="#" title="<?= TOOLTIP_REGISTRATION_DIRECT_PAYMENT_EXPLAIN; ?>"><img src="/images/question_32x37.png" height="24" alt="some test"></a></div>
        </th>
    </tr>
    <?=$display_direct_payment_methods;?>
</table>
    <? } ?>

<?=(isset($signup_voucher_box))?$signup_voucher_box:'';?>
<!-- terms and conditions -->
<div id="terms"><?=(isset($registration_terms_box))?$registration_terms_box:'';?></div>
<? if ((!defined('IN_ADMIN') || IN_ADMIN != 1 )&& !isset($edit_user) ) { ?>
<!-- PIN -->
<table border="0" cellpadding="0" cellspacing="0" class="tbl">
    <tr>
        <th colspan="2">Confirm PIN</th>
    </tr>
    <tr>
        <td class="leftCol"><?=MSG_REG_PIN;?></td>
        <td><?=(isset($pin_image_output))?$pin_image_output:'';?></td>
    </tr>
    <tr class="reguser">
        <td colspan="2"><span class="reguser"><?=MSG_REG_PIN_EXPL;?></span></td>
    </tr>
    <tr>
        <td class="leftCol"><?=MSG_CONF_PIN;?> *</td>
        <td><input name="pin_value" type="text" class="contentfont" id="pin_value" value="" size="20" /></td>
    </tr>
</table>
    <? } ?>
<!-- submit -->
<table class="tbl">
    <tr>
        <td colspan="2">
            <div class="next">
                <input class="save_btn" name="form_register_proceed" type="submit" id="form_register_proceed" value="<?=$proceed_button;?>" />
           </div>
        </td>
    </tr>
</table>
</form>
<div id="validation_errors" title="Basic dialog" style="display: none;">
</div>