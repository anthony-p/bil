<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<script language="javascript">
    function form_submit() {
        document.extended_registration_form.operation.value = '';
        document.extended_registration_form.edit_refresh.value = '1';
        document.extended_registration_form.submit();
    }
</script>

<?=$header_registration_message;?>
<br>
<?=$banned_email_output;?>
<?=$display_formcheck_errors;?>
<?=$check_voucher_message;?>

<form action="<?=$register_post_url;?>" method="post" name="extended_registration_form" class="registrationForm">
<input type="hidden" name="operation" value="submit">
<input type="hidden" name="do" value="<?=$do;?>">
<input type="hidden" name="user_id" value="<?=$user_details['user_id'];?>">
<input type="hidden" name="edit_refresh" value="0">
<!-- main details -->
<table border="0" cellpadding="0" cellspacing="0" class="tbl">
    <tr>
        <th colspan="2"><?=MSG_MAIN_DETAILS;?></th>
    </tr>
    <tr><td></td></tr>
    <tr>
        <td class="leftCol"><?=MSG_REGISTER_AS;?></td>
        <td class="contentfont">
            <input name="tax_account_type" type="radio" value="0" onclick="form_submit();" checked />
            <?=GMSG_INDIVIDUAL;?>
            <input name="tax_account_type" type="radio" value="1" onclick="form_submit();" <? echo ($user_details['tax_account_type']) ? 'checked' : ''; ?> />
            <?=GMSG_BUSINESS;?></td>
    </tr>
    <? if ($user_details['tax_account_type']) { ?>
    <tr>
        <td class="contentfont"><?=MSG_COMPANY_NAME;?> *</td>
        <td class="contentfont"><input name="tax_company_name" type="text" class="contentfont" id="tax_company_name" value="<?=$user_details['tax_company_name'];?>" size="40" /></td>
    </tr>
    <? } ?>
</table>
<!-- personal info -->
<table border="0" cellpadding="0" cellspacing="0" class="tbl">
    <tr>
        <th colspan="2">Personal Information</th>
    </tr>
    <tr><td></td></tr>
    <tr>
        <td class="leftCol"><?=MSG_PHONE;?> *1</td>
        <td class="contentfont">
            <? if ($edit_user == 1)	{ ?>
            <input name="phone" type="text" id="phone" value="<?=$user_details['phone'];?>" size="25" />
            <? } else { ?>
            ( <input name="phone_a" type="text" id="phone_a" value="<?=$user_details['phone_a'];?>" size="5" /> )
            <input name="phone_b" type="text" id="phone_b" value="<?=$user_details['phone_b'];?>" size="25" />
            <? } ?></td>
    </tr>
    <tr class="birthday">
        <td colspan="2"><?=$birthdate_box;?></td>
    </tr>
</table>

<? if (IN_ADMIN == 1) { ?>
<table border="0" cellpadding="0" cellspacing="0" class="tbl">
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
    <tr class="reguser">
        <td>&nbsp;</td>
        <td><?=AMSG_PAYMENT_MODE_EXPL;?></td>
    </tr>
    <tr>
        <td class="contentfont"><?=AMSG_ACCOUNT_BALANCE;?>
        </td>
        <td class="contentfont"><?=$setts['currency']; ?> <input name="balance" value="<?=abs($user_details['balance']); ?>" size="8">
            <select name="balance_type">
                <option value="-1" selected><?=GMSG_CREDIT;?></option>
                <option value="1" <? echo ($user_details['balance']>0) ? 'selected' : '';?> ><?=GMSG_DEBIT;?></option>
            </select> &nbsp; <?=AMSG_BALANCE_ADJ_REASON;?>: <input type="text" name="adjustment_reason" size="20"> (<?=AMSG_OPTIONAL_FIELD;?>)</td>
    </tr>
    <tr class="reguser">
        <td>&nbsp;</td>
        <td><?=AMSG_ACCOUNT_BALANCE_EXPL;?></td>
    </tr>
    <tr>
        <td class="contentfont"><?=GMSG_MAX_DEBIT;?></td>
        <td class="contentfont"><?=$setts['currency']; ?> <input name="max_credit" value="<?=abs($user_details['max_credit']); ?>" size="8"></td>
    </tr>
    <tr class="reguser">
        <td>&nbsp;</td>
        <td><?=AMSG_MAX_DEBIT_EXPL;?></td>
    </tr>
    <? } ?>
</table>
    <? } ?>
<? if ($setts['enable_tax']) { ?>
<!-- Tax Settings -->
<table border="0" cellpadding="0" cellspacing="0" class="tbl">
    <tr>
        <th colspan="2"><?=MSG_TAX_SETTINGS;?></th>
    </tr>
    <tr><td></td></tr>
    <tr>
        <td class="leftCol"><?=MSG_TAX_REG_NUMBER;?></td>
        <td><input name="tax_reg_number" type="text" class="contentfont" id="tax_reg_number" value="<?=$user_details['tax_reg_number'];?>" size="40" /></td>
    </tr>
    <tr class="reguser">
        <td class="contentfont">&nbsp;</td>
        <td><?=MSG_TAX_REG_NUMBER_DESC;?></td>
    </tr>
</table>
    <? } ?>
<? if (!empty($display_direct_payment_methods)) { ?>
<!-- Direct Payment Settings -->
<table border="0" cellpadding="0" cellspacing="0" class="tbl" id="directPayment">
    <tr>
        <th colspan="2"><?=MSG_DIRECT_PAYMENT_SETTINGS;?></th>
    </tr>
    <tr><td></td></tr>
    <?=$display_direct_payment_methods;?>
</table>
    <? } ?>

<?=$signup_voucher_box;?>
<!-- terms and conditions -->
<div id="terms"><?=$registration_terms_box;?></div>

<!-- submit -->
<table border="0" cellpadding="0" cellspacing="0" class="tbl submit-proced">
    <tr>
        <td class="leftCol"></td>
        <td>

            <input name="form_register_proceed" type="submit" id="form_register_proceed" value="<?=$proceed_button;?>" /></td>
    </tr>
</table>
</form>
