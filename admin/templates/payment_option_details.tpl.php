<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<style>
    input, select{
        float: left;
        width: 200px;
    }
    label{
        float: left;
        width: 200px;
    }
    .input_field{
        width: 500px;
    }
    #paypal_application_credentials{
        display: none;
    }
</style>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />

<!--<script>-->
<!--    $('head').append('<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">');-->
<!--</script>-->

<script src="../../scripts/jquery/jquery-1.9.1.js"></script>
<script src="../../scripts/jquery/jquery-ui.js"></script>

<script>
    $(function() {
        $( "#tabs" ).tabs();
    });
</script>

<script>
    $(document).ready(function(){
        paypal_options_json = <?php echo $paypal_options_json; ?>;
        $("#change_credentials").change(function(){
            if ($("#change_credentials").prop("checked")) {
                $("#paypal_application_credentials").show();
            }
            else {
                $("#paypal_application_credentials").hide();
            }
        });

        $("#payment_environment").change(function(){
            selected_value = $("#" + this.id).val();
            $.each(paypal_options_json, function(i, item){
                if (i == selected_value) {
                    if (item) {
                        $("#payment_option_id").val(item.id);
                        $("#API_UserName").val(item.API_UserName);
                        $("#API_Password").val(item.API_Password);
                        $("#API_Signature").val(item.API_Signature);
                        $("#API_AppID").val(item.API_AppID);
                        $("#payment_account").val(item.payment_account);
                        $("#rate_of_pay").val(item.rate_of_pay);
                    } else {
                        $("#payment_option_id").val('');
                        $("#API_UserName").val('');
                        $("#API_Password").val('');
                        $("#API_Signature").val('');
                        $("#API_AppID").val('');
                        $("#payment_account").val('');
                        $("#rate_of_pay").val('');
                    }
                    return false;
                }
            });

        });
    });
</script>

<div class="mainhead"><img src="images/set.gif" align="absmiddle">
    <?=$header_section;?>
</div>
<?=$msg_changes_saved;?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td width="4"><img src="images/c1.gif" width="4" height="4"></td>
        <td width="100%" class="ftop"><img src="images/pixel.gif" width="1" height="1"></td>
        <td width="4"><img src="images/c2.gif" width="4" height="4"></td>
    </tr>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="3" class="fside">
    <tr class="c3">
        <td colspan="2"><img src="images/subt.gif" align="absmiddle" hspace="4" vspace="2"> <b>
                <?=strtoupper($subpage_title);?>
            </b></td>
    </tr>
</table>

<div id="tabs">
    <ul>
        <li><a href="#tabs-1">PayPal</a></li>
    </ul>
    <div id="tabs-1" style="height: 200px">
        <form name="payment_option_details" method="post" action="payment_option_details.php">
            <input type="hidden" name="page" value="<?=$page;?>">
            <input type="hidden" id="payment_option_id" name="payment_option_id"
                <?php if (is_array($paypal_options["current"])
                && isset($paypal_options["current"]["id"])): ?>
                value="<?php echo $paypal_options["current"]["id"]; ?>"
            <?php endif;?>
                >
            <input type="hidden" name="payment_method" value="paypal">
            <div class="input_field">
                <label>Change credentials: </label>
                <input type="checkbox" name="change_credentials" id="change_credentials">
            </div>
            <div id="paypal_application_credentials">
                <div class="input_field">
                    <label>Payment type: </label>
                    <select name="payment_environment" id="payment_environment">
                        <option value="sandbox"
                            <?php
                            if (is_array($paypal_options["current"])
                                && isset($paypal_options["current"]["payment_environment"])
                                && $paypal_options["current"]["payment_environment"] == "sandbox"):
                                ?>
                                selected="selected"
                            <?php endif;?>
                            >
                            Test
                        </option>
                        <option value="live"
                            <?php
                            if (is_array($paypal_options["current"])
                                && isset($paypal_options["current"]["payment_environment"])
                                && $paypal_options["current"]["payment_environment"] == "live"):
                                ?>
                                selected="selected"
                            <?php endif;?>
                            >
                            Live
                        </option>
                    </select>
                </div>
                <div class="input_field">
                    <label>Application username: </label>
                    <input type="text" id="API_UserName" name="API_UserName"
                        <?php if (is_array($paypal_options["current"])
                        && isset($paypal_options["current"]["API_UserName"])): ?>
                        value="<?php echo $paypal_options["current"]["API_UserName"]; ?>"
                    <?php endif;?>
                        >
                </div>
                <div class="input_field">
                    <label>Application password: </label>
                    <input type="text" id="API_Password" name="API_Password"
                        <?php if (is_array($paypal_options["current"])
                        && isset($paypal_options["current"]["API_Password"])): ?>
                        value="<?php echo $paypal_options["current"]["API_Password"]; ?>"
                    <?php endif;?>
                        >
                </div>
                <div class="input_field">
                    <label>Application signature: </label>
                    <input type="text" id="API_Signature" name="API_Signature"
                        <?php if (is_array($paypal_options["current"])
                        && isset($paypal_options["current"]["API_Signature"])): ?>
                        value="<?php echo $paypal_options["current"]["API_Signature"]; ?>"
                    <?php endif;?>
                        >
                </div>
                <div class="input_field">
                    <label>Application ID: </label>
                    <input type="text" id="API_AppID" name="API_AppID"
                        <?php if (is_array($paypal_options["current"])
                        && isset($paypal_options["current"]["API_AppID"])): ?>
                        value="<?php echo $paypal_options["current"]["API_AppID"]; ?>"
                    <?php endif;?>
                        >
                </div>
                <div class="input_field">
                    <label>Account: </label>
                    <input type="text" id="payment_account" name="payment_account"
                        <?php if (is_array($paypal_options["current"])
                        && isset($paypal_options["current"]["payment_account"])): ?>
                        value="<?php echo $paypal_options["current"]["payment_account"]; ?>"
                    <?php endif;?>
                        >
                </div>
            </div>
            <div class="input_field">
                <label>Rate of pay %: </label>
                <input type="text" id="rate_of_pay" name="rate_of_pay"
                    <?php if (is_array($paypal_options["current"])
                    && isset($paypal_options["current"]["rate_of_pay"])): ?>
                    value="<?php echo $paypal_options["current"]["rate_of_pay"]; ?>"
                <?php endif;?>
                    >
            </div>
            <div class="input_field">
                <label></label>
                <input type="submit" name="form_save_settings" value="<?=AMSG_SAVE_CHANGES;?>">
            </div>
        </form>
    </div>
</div>
