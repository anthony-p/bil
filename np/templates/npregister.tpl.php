<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<link href="/css/ui-darkness/jquery-ui-1.10.3.custom.css" rel="stylesheet">
<link href="/themes/bring_it_local/tabs-style.css" rel="stylesheet">

<script type="text/javascript" src='/scripts/jquery/jquery.validate.min.js'></script>
<script type="text/javascript" src='/scripts/jquery/additional-methods.min.js'></script>
<script type="text/javascript">

    window.error_messages = {
        name: "<?= MSG_REGISTER_CAMPAIGN_ERR_NAME; ?>",
        tax_company_name: "<?= MSG_REGISTER_CAMPAIGN_ERR_TAXCMPNAME; ?>",
        address: "<?= MSG_REGISTER_CAMPAIGN_ERR_ADDRESS; ?>",
        city: "<?= MSG_REGISTER_CAMPAIGN_ERR_CITY; ?>",
        zipcode: "<?= MSG_REGISTER_CAMPAIGN_ERR_ZIP; ?>",
        phone: "<?= MSG_REGISTER_CAMPAIGN_ERR_PHONE; ?>",
        title: "<?= MSG_REGISTER_CAMPAIGN_ERR_PTITLE; ?>",
        pdesc: "<?= MSG_REGISTER_CAMPAIGN_ERR_PDESC; ?>",
        date: "<?= MSG_REGISTER_CAMPAIGN_ERR_CDATEP; ?>",
        url: "<?= MSG_REGISTER_CAMPAIGN_ERR_USERNAME; ?>",
        facebook_url:"<?= MSG_REGISTER_CAMPAIGN_ERR_FACEBOOKURL; ?>",
        twitter_url:"<?= MSG_REGISTER_CAMPAIGN_ERR_TWITTERURL; ?>;",
        logo: "<?= MSG_REGISTER_CAMPAIGN_ERR_LOGOFILE; ?>",
        date_format:"<?= MSG_DEADLINE_CERTAIN_DATE; ?>",
        days_format:"<?= MSG_DEADLINE_TIME_PERIOD; ?>"
    };
    window.messages = {
        confirm_delete_button: '<?= MSG_CAMPAIGN_EDIT_REWARDS_DIALOG_BTN_OK; ?>',
        confirm_cancel_button: '<?= MSG_CAMPAIGN_EDIT_REWARDS_DIALOG_BTN_CANCEL; ?>',
    };


</script>
<script type="text/javascript" src="/scripts/campaign_form_validate.js"></script>
<script type="text/javascript" src="/scripts/init_tinymce.js"></script>
<script type="text/javascript">

    $(document).ready(function () {

        /* Form validated before submit */
        $('input[type="submit"]').each(function() {
            $(this).on('click', function(e){
                e.preventDefault();
                var formElem = $('#formElem');
                validateCampaignForm(formElem, error_messages);
                $("#pin_value" ).rules( "add", {
                    required: true,
                    messages: {
                        required: "<?= MSG_REGISTER_CAMPAIGN_ERR_PIN; ?>"
                    }
                });
                $('input[name="agree_terms"]').rules( "add", {
                    required: true,
                    messages: {
                        required: "<?= MSG_REGISTER_CAMPAIGN_ERR_AGREETERMS; ?>"
                    }
                });
                if (formElem.valid()) {formElem.submit();}
            })
        });



        <?php if (isset($user_details['video_url']) && $user_details['video_url']): ?>
        $("#vide_select_block").css("display", "inline");
        $("#banner").css("display", "none");
        $("#banner").val("");
        $("#video_url").val("<?php echo isset($user_details['video_url']) ? $user_details['video_url'] : ''; ?>");
        $("#banner_type_image").removeAttr("checked");
        $("#banner_type_video").attr("checked", "checked");
        <?php endif; ?>

        <?php if (isset($user_details['deadline_type_value']) && $user_details['deadline_type_value'] == "time_period"): ?>
        jQuery("#time_period").removeAttr('disabled');
        <?php elseif (isset($user_details['deadline_type_value']) && $user_details['deadline_type_value'] == "certain_date"): ?>
        jQuery("#certain_date").removeAttr('disabled');
        <?php endif; ?>


        $(".banner_type").unbind().bind("click", function () {
            if ($(this).val() == "0") {
                $("#vide_select_block").css("display", "none");
                $("#video_url").val("");
                $("#banner").css("display", "inline");
            } else {
                $("#vide_select_block").css("display", "inline");
                $("#banner").css("display", "none");
                $("#banner").val("");
            }
        });

        $("#loadVideo").unbind().bind("click", function () {
            var url = $("#video_url").val();
            if (url === null) {
                return "";
            }
            var vURL = processURL(url);

            if (!vURL) return;
            setPrevImage(vURL);
        });
    });


    var countOfPitch = <?php if (isset($user_details["pitches_number"])) echo $user_details["pitches_number"]; else { ?>0<?php } ?>;

</script>
<span id="noscriptdiv" style="border:1px  solid #FF0000;display:block;padding:5px;text-align:left; background: #FDF2F2;color:#000;">Active Scripting (JavaScript) should be enabled in your browser for this application to function properly!</span>

<script type="text/javascript">
    document.getElementById('noscriptdiv').style.visibility = 'hidden';
    document.getElementById('noscriptdiv').style.height = 0;
    document.getElementById('noscriptdiv').style.padding = 0;
    document.getElementById('noscriptdiv').style.border = 0;
</script>

<?=$header_registration_message;?>
<br>
<?=(isset($banned_email_output))?$banned_email_output:'';?>
<?=(isset($display_formcheck_errors))?$display_formcheck_errors:'';?>
<?=(isset($check_voucher_message))?$check_voucher_message:'';?>

<div id="wrapper">
<div id="navigation" style="display:none;">
    <ul>
        <li id="p_account" class="selected">
            <a href="#"><span>1</span><?=REG_CMN_ACCOUNT?></a>
        </li>
        <li id="p_projectDetail">
            <a href="#"><span>2</span><?=REG_CMN_ADD_DETAILS?></a>
        </li>
        <li id="p_projectEdit">
            <a href="#"><span>3</span><?=REG_CMN_ENHANCE_IT?></a>
        </li>
        <li id="p_confirmation">
            <a href="#"><span>4</span><?=REG_CMN_SAVE_IT?></a>
        </li>

    </ul>
</div>
<div id="steps">
<form action="<?=$register_post_url;?>" method="post" name="registration_form" enctype="multipart/form-data" id="formElem" >
<input type="hidden" name="operation" value="submit">
<input type="hidden" name="do" value="<?=(isset($do))?$do:'';?>">
<input type="hidden" name="user_id" value="<?=(isset($user_details['user_id']))?$user_details['user_id']:'';?>">
<input type="hidden" name="edit_refresh" value="0">
<input type="hidden" name="generated_pin" value="<?=(isset($generated_pin))?$generated_pin:'';?>">
<fieldset class="step">
    <div class="tabs">
        <h4><?=MSG_MAIN_DETAILS;?></h4>
<div class="account-tab">
    <div class="account-row">
        <label><?=MSG_REGISTER_AS;?></label>
        <select name="orgtype" id="orgtype" size="1">
        <option selected="selected"><? echo (isset($user_details['orgtype']))?($user_details['orgtype']):'';?></option>
        <option value="Charitable organization: homeless shelter">Charitable organization: homeless shelter</option>
        <option value="Charitable organization: disability organization">Charitable organization: disability organization </option>
        <option value="Charitable organization: youth program">Charitable organization: youth program</option>
        <option value="Charitable organization: hospital">Charitable organization: hospital</option>
        <option value="Charitable organization: health care clinic">Charitable organization: health care clinic</option>
        <option value="Charitable organization: animal rights group">Charitable organization: animal rights group</option>
        <option value="Charitable organization: military group">Charitable organization: military group</option>
        <option value="Charitable organization: human rights group">Charitable organization: human rights group</option>
        <option value="Charitable organization: emergency relief">Charitable organization: emergency relief</option>
        <option value="Educational: elementary school">Educational: elementary school </option>
        <option value="Educational: middle school">Educational: middle school</option>
        <option value="Educational: community college">Educational: community college</option>
        <option value="Educational: college or university">Educational: college or university</option>
        <option value="Educational: child care center">Educational: child care center</option>
        <option value="Educational: museum">Educational: museum</option>
        <option value="Educational: conservation group">Educational: conservation group</option>
        <option value="Educational: zoo">Educational: zoo</option>
        <option value="Religious: Church">Religious: Church</option>
        <option value="Religious: Synagogue">Religious: Synagogue</option>
        <option value="Religious: Mosque">Religious: Mosque</option>
        <option value="Religious: Seminary">Religious: Seminary</option>
        <option value="Religious: Church or other religious relief organization">Religious: Church or other religious relief organization</option>
        <option value="Artistic: symphony or orchestra">Artistic: symphony or orchestra</option>
        <option value="Artistic: theater group">Artistic: theater group</option>
        <option value="Artistic: art gallerie">Artistic: art gallery</option>
        <option value="Artistic: writers' organization">Artistic: writers' organization</option>
        <option value="Artistic: youth music group">Artistic: youth music group </option>
        </select>
        <input type="hidden" name="tax_account_type" type="radio" value="1" />
        <span><?=MSG_REGISTER_AS_DESC;?></span>
    </div>
    <div class="account-row">
        <label><?=MSG_FULL_NAME;?> *</label>
        <input name="name" type="text" id="name" value="<?=(isset($user_details['name']))?$user_details['name']:'';?>" size="40" />
        <input name="affiliate" type="hidden" id="affiliate" value="<?=(isset($_POST['affiliate']))?$_POST['affiliate']:'';?>" size="40" />
        <span><?=MSG_FULL_NAME_EXPL;?> *</span>
    </div>

    <div class="account-row">
        <label><?=MSG_COMPANY_NAME;?> </label>
        <input name="tax_company_name" type="text" class="contentfont" id="tax_company_name" value="<?=isset($user_details['tax_company_name'])?$user_details['tax_company_name']:'';?>" size="40" />
        <span><?=MSG_COMPANY_NAME_DESC;?></span>
    </div>
    <? #} ?>
    <div class="account-row">
        <label><?=MSG_ADDRESS;?> *</label>
        <input name="address" type="text" id="address" value="<?=isset($user_details['address'])?$user_details['address']:'';?>" size="40" />
        <span><?=MSG_ADDRESS_EXPL;?></span>
    </div>
    <div class="account-row">
        <label><?=MSG_CITY;?> *</label>
        <input name="city" type="text" id="city" value="<?=isset($user_details['city'])?$user_details['city']:'';?>" size="25" />
        <span><?=MSG_CITY_EXPL;?></span>
    </div>

    <div class="account-row">
        <label><?=MSG_ZIP_CODE;?> *</label>
        <input name="zip_code" type="text" id="zip_code" value="<?=(isset($user_details['zip_code']))?$user_details['zip_code']:'';?>" size="15" />
        <div class="clear"></div><br/>
        <label><?=MSG_COUNTRY;?> *</label>
        <?=$country_dropdown;?>
        <div class="clear"></div><br/>
        <label><?=MSG_STATE;?> *</label>
        <div id="states_box"><?=$state_box;?></div>
        <?php
            $city = (isset($user_details['city']))?$user_details['city']:'';
            $zip_code = (isset($user_details['zip_code']))?$user_details['zip_code']:'';

        ?>
        <input type ="hidden" name="geoaddress" id="geoaddress" value= "<?=(isset($user_details['address']))?$user_details['address']:'' .",". $city .",". $zip_code;?>"/>

        <input type ="hidden" name="lat" id="lat" value= "<?=(isset($user_details['lat']))?$user_details['lat']:'';?>" />
        <input type ="hidden" name="lng" id="lng" value= "<?=(isset($user_details['lng']))?$user_details['lng']:'';?>" />
    </div>

    <div class="account-row phone">
        <label><?=MSG_PHONE;?> *</label>


            <input name="phone" type="text" id="phone" value="<?=(isset($user_details['phone']))?$user_details['phone']:'';?>" size="25" />

        <span><?=MSG_PHONE_EXPL;?></span>
        <div class="clear"></div>
        <?=isset($birthdate_box)?$birthdate_box:'';?>
    </div>
    <div class="account-row">
        <label><?=MSG_PG_PAYPAL_EMAIL_ADDRESS;?></label>
        <input name="pg_paypal_email" type="text" id="pg_paypal_email"
               value="<?=(isset($user_details['pg_paypal_email']))?$user_details['pg_paypal_email']:'';?>" size="40" />
        <span><?=MSG_PG_PAYPAL_EMAIL_ADDRESS_EXPL;?></span>
    </div>
    <div class="account-row">
        <label><?=MSG_PG_PAYPAL_FIRST_NAME;?></label>
        <input name="pg_paypal_first_name" type="text" id="pg_paypal_first_name"
               value="<?=(isset($user_details['pg_paypal_first_name']))?$user_details['pg_paypal_first_name']:'';?>" size="40" />
        <span><?=MSG_PG_PAYPAL_FIRST_NAME_EXPL;?></span>
    </div>
    <div class="account-row">
        <label><?=MSG_PG_PAYPAL_LAST_NAME;?></label>
        <input name="pg_paypal_last_name" type="text" id="pg_paypal_last_name"
               value="<?=(isset($user_details['pg_paypal_last_name']))?$user_details['pg_paypal_last_name']:'';?>" size="40" />
        <span><?=MSG_PG_PAYPAL_LAST_NAME_EXPL;?></span>
    </div>
    <div class="next">
        <input name="form_register_proceed" type="submit" id="form_register_proceed_account"
               value="<?=MSG_SAVE_CAMPAIGN?>" class="save_btn partial_save"/>
        <div class="right">
            <input type="button" value="<?=MSG_NEXT?>" class="next_btn" />    </div>
        </div>
    </div>


    </div>
</fieldset>

<fieldset class="step">
    <div class="tabs">
        <h4><?=MSG_PROJECT_ACCOUNT_DETAILS; ?></h4>
        <div class="account-tab">
            <div class="account-row">
            <label> <?=MSG_CREATE_PROJECT_URL;?> *</label>
            <input name="username" type="text" id="username"
                   value="<?php echo isset($user_details['username']) ? $user_details['username'] : '' ?>"
                   size="40" maxlength="30"  placeholder="<?=MSG_ENTER_PROJECTURL;?>"/>
            <span><?=MSG_PROJECTURL_EXPLANATION;?></span>
        </div>
        <div class="account-row">
            <label><?=MSG_CREATE_PROJECT_CHOOSE_CATEGORY;?> *</label>
            <select name="project_category" id="project_category">
                <?php foreach ($project_category as $category): ?>
                    <option value="<?php echo $category["id"]; ?>"
                        <?php if (isset($user_details['project_category']) && $user_details['project_category'] == $category["id"]) echo 'selected' ?>>
                        <?php echo $category["name"]; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="account-row">
            <label><?=MSG_CREATE_PROJECT_CAMPAIGN_BASIC;?> *</label>
            <textarea rows="5" cols="60" class="campaign_basic" name="campaign_basic" id="campaign_basic"><?php echo isset($user_details['campaign_basic']) ? $user_details['campaign_basic'] : '' ?></textarea>
            <span><?=MSG_CREATE_PROJECT_CAMPAIGN_BASIC_EXPLANATION;?></span>
        </div>
        <div class="account-row">
            <label><?=MSG_CREATE_PROJECT_TITLE;?> *</label>
            <input type="text" name="project_title"
                   value="<?php echo isset($user_details['project_title']) ? $user_details['project_title'] : '' ?>"
                   id="project_title" maxlength="80" size="40" >
        </div>
        <div class="account-row">
            <label><?=MSG_CREATE_PROJECT_SHORT_DESCRIPTION;?> *</label>
            <input type="text" name="project_short_description"
                   value="<?php echo isset($user_details['project_short_description']) ? $user_details['project_short_description'] : '' ?>"
                   id="project_short_description" maxlength="160" size="40" >
        </div>
        <div class="account-row">
            <label><?=MSG_CREATE_PROJECT_QUESTION_FOUNDRAISING_GOAL;?> *</label>
            <input type="text" name="founddrasing_goal"
                   value="<?php echo isset($user_details['founddrasing_goal']) ? $user_details['founddrasing_goal'] : '500' ?>"
                   id="founddrasing_goal" >(USD)
        </div>
        <div class="account-row deadline">
            <label><?="Deadline";?> </label>

                        <span>
                        <input type="hidden" name="deadline_type_value" id="deadline_type_value"
                               value="<?php echo (isset($user_details['deadline_type_value'])) ? $user_details['deadline_type_value'] : '' ?>">
                        </span>
                        <span>
                      <span class="radio-span"> <input type="radio" name="deadline_type" id="deadline_type_days" value="deadline_type" onclick="changeDeadlineType(jQuery(this),'time_period')"
                              <?php echo (isset($user_details['deadline_type_value']) && $user_details['deadline_type_value'] == "time_period") ? 'checked="checked"' : '' ?>>
                       # of days</span>
                       <input type="text" name="time_period" id="time_period" disabled="disabled"
                              value="<?php echo isset($user_details['time_period']) ? $user_details['time_period'] : '' ?>">

                         </span>

                          <span>
                              <span class="radio-span">
                              <input type="radio" name="deadline_type" id="deadline_type_date" value="deadline_type" onclick="changeDeadlineType(jQuery(this),'certain_date')"
                                  <?php echo (isset($user_details['deadline_type_value']) && $user_details['deadline_type_value'] == "certain_date") ? 'checked="checked"' : '' ?>>
                      (date)
                         </span>
                      <input type="text" name="certain_date" id="certain_date" disabled="disabled"
                             value="<?php echo isset($user_details['certain_date']) ? $user_details['certain_date'] : '' ?>">

                         </span>
        </div>
        </div>
        <? if ( defined('IN_ADMIN') && IN_ADMIN == 1) { ?>
            <div class="account-row taxt-settings">
                <h6><?=AMSG_PAYMENT_SETTINGS;?></h6>
                <input type="radio" name="payment_mode" value="2" <? echo ($user_details['payment_mode']==2) ? 'checked' : '';?>
                <label><?=AMSG_PAYMENT_MODE;?></label>
                <div class="clear"></div>
                <input type="radio" name="payment_mode" value="1" <? echo ($user_details['payment_mode']==1) ? 'checked' : '';?>>
                <label><?=GMSG_ACCOUNT;?> <?=GMSG_LIVE;?></label>
                <div class="clear"></div>
                <? if ($user_details['payment_mode'] == 2) { ?>

                    <span><?=AMSG_PAYMENT_MODE_EXPL;?></span>
                    <label><?=AMSG_ACCOUNT_BALANCE;?></label>
                    <div class="clear"></div>
                    <?=$setts['currency']; ?> <input name="balance" value="<?=abs($user_details['balance']); ?>" size="8">
                    <select name="balance_type">
                        <option value="-1" selected><?=GMSG_CREDIT;?></option>
                        <option value="1" <? echo ($user_details['balance']>0) ? 'selected' : '';?> ><?=GMSG_DEBIT;?></option>
                    </select>
                    <div class="clear"></div>
                    <label><?=AMSG_BALANCE_ADJ_REASON;?>:</label>
                    <input type="text" name="adjustment_reason" size="20"> (<?=AMSG_OPTIONAL_FIELD;?>)
                    <div class="clear"></div>
                    <span><?=AMSG_ACCOUNT_BALANCE_EXPL;?></span>
                    <div class="clear"></div>
                    <label><?=GMSG_MAX_DEBIT;?></label>
                    <?=$setts['currency']; ?> <input name="max_credit" value="<?=abs($user_details['max_credit']); ?>" size="8">
                    <span><?=AMSG_MAX_DEBIT_EXPL;?></span>

                <? } ?>
            </div>

        <? } ?>
        <div class="clear"></div>
        <? if ($setts['enable_tax']) { ?>
        <div class="account-tab">
                <div class="next">
                    <input name="form_register_proceed" type="submit" id="form_register_proceed_details"
                           value="<?=MSG_SAVE_CAMPAIGN?>" class="save_btn partial_save"/>
                    <div class="right">
                        <input type="button" value="<?=MSG_PREV?>" class="prev_btn" />
                        <input type="button" value="<?=MSG_NEXT?>" class="next_btn" />
                    </div>
                </div>
        </div>
        <? } ?>


    </div>
</fieldset>

<fieldset class="step">
    <div class="tabs">
        <h4><?=MSG_WEBSITE_ADDRESS;?></h4>
        <div class="account-tab">
        <div class="account-row">
            <label><?=MSG_WEBSITE_ADDRESS_INSTRUCTIONS;?></label>
            <input name="url" type="text" class="contentfont" id="url" value="<?=(isset($user_details['url']))?$user_details['url']:'';?>" size="40" />
            <span><?=MSG_WEBSITE_ADDRESS_INSTRUCTIONS2;?></span>
        </div>

        <div class="account-row">
            <label><?=MSG_FACEBOOK_PAGE_INSTRUCTIONS;?></label>
            <input name="facebook_url" type="text" class="contentfont" id="facebook_url" value="<?=(isset($user_details['facebook_url']))?$user_details['facebook_url']:'';?>" size="40" />
        </div>

        <div class="account-row">
            <label><?=MSG_TWITTER_PAGE_INSTRUCTIONS;?></label>
            <input name="twitter_url" type="text" class="contentfont" id="twitter_url" value="<?=(isset($user_details['twitter_url']))?$user_details['twitter_url']:'';?>" size="40" />
        </div>

        <h5><?=MSG_LOGO_DESC;?></h5>
        <div class="account-row">
            <?php if(isset($logo_image) && $logo_image): ?>
                <input type="hidden" name="valid_logo_image" id="valid_logo_image" value="<?php echo $logo_image; ?>"/>
                <img src="<?php echo $logo_image; ?>" />
            <?php elseif(isset($user_details['logo']) && $user_details['logo']): ?>
                <input type="hidden" name="valid_logo_image" id="valid_logo_image" value="<?php echo $user_details['logo']; ?>"/>
                <img src="<?php echo $user_details['logo']; ?>" />
            <?php endif; ?>
            <div id="MultiPowUpload_holder">
                <input class="file" name="logo" id="logo" accept="image/*" type='file' multiple title="logo file"/>
                <span class="clear-file-input">Clear</span>
            </div>

            <div id="serverresponse">
                <div id="prev_logo"></div>
                <span>For best results upload an image that is not more than 160 pixels wide.</span>
            </div>
        </div>
        <h5>Your Story (<span style="font-size: 8px">Tell potential contributors more about your campaign.)</span></h5>

        <div class="account-row">
            <div class="upload">
                <input type="radio" class="banner_type" id="banner_type_image" name="banner_type" value="0" checked="checked" ><label><?=MSG_BANNER_IMAGE?></label>
                <div class="clear"></div>
                <input type="radio" class="banner_type" id="banner_type_video" name="banner_type" value="1" ><label><?=MSG_VIDEO_YOUTUBE?></label>
            </div>
            <div class="clear"></div>
            <br />
            <?php if(isset($banner_image) && $banner_image): ?>
                <input type="hidden" name="valid_banner_image" id="valid_banner_image" value="<?php echo $banner_image; ?>"/>
                <img src="<?php echo $banner_image; ?>" />
            <?php elseif(isset($user_details['banner']) && $user_details['banner']): ?>
                <input type="hidden" name="valid_banner_image" id="valid_banner_image" value="<?php echo $user_details['banner']; ?>"/>
                <img src="<?php echo $user_details['banner']; ?>" />
            <?php endif; ?>
            <input class="file" name="banner" id="banner" type='file' multiple title="banner file"/>
            <span class="clear-file-input" >Clear</span>
            <div id="vide_select_block" style="display: none">
                <input type="text" name="video_url" id="video_url" value="">
                <input type="button" id="loadVideo" value="Get">
            </div>
            <div class="banners_list">
             <span> <?=MSG_UPLOAD_IMAGE_INFORMATION?>.</span>
                <div id="prev_banner"></div>
            </div>
        </div>

            <div class="next">
                <input name="form_register_proceed" type="submit"
                       id="form_register_proceed_enhance" value="<?=MSG_SAVE_CAMPAIGN?>" class="save_btn partial_save"/>
                <div class="right">
                    <input type="button"  value="<?=MSG_PREV?>" class="prev_btn" />
                    <input type="button"  value="<?=MSG_NEXT?>" class="next_btn" />
                </div>
            </div>

    </div>

    </div>
</fieldset>

<fieldset class="step">
    <div class="tabs">
        <div class="account-tab">
        <? if ((!defined('IN_ADMIN') || IN_ADMIN != 1) && (!isset($edit_user) || !$edit_user ) ) { ?>
            <div class="account-row enter-pin">
                <label><?=MSG_REG_PIN;?></label>
                <span class="img-pin"><?=$pin_image_output;?></span>
                <span><?=MSG_REG_PIN_EXPL;?></span>
                <div class="clear"></div>
                <br />
                <label><?=MSG_CONF_PIN;?> *</label>
                <input name="pin_value" type="text" class="contentfont" id="pin_value" value="" size="20" />
            </div>

        <? } ?>
            <? if (!empty($display_direct_payment_methods)) { ?>
        <h4><?=MSG_DIRECT_PAYMENT_SETTINGS;?></h4>

    <?=$display_direct_payment_methods;?>

    <? } ?>
        <?=(isset($signup_voucher_box))?$signup_voucher_box:'';?>
            <br />
        <?=(isset($registration_terms_box))?$registration_terms_box:'';?>
         <div class="next">
             <input type="button" value="<?= MSG_PREV ?>" class="prev_btn" />
             <div class="right">
                 <input name="form_register_proceed" type="submit" id="form_register_proceed" class="save_btn" value="<?=MSG_SAVE?>"/>
             </div>
         </div>
    </div>
    </div>
</fieldset>
</form>
</div>


<div id="validation_errors" title="Basic dialog" style="display: none;">

</div>