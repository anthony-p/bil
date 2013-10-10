<?
require_once(__DIR__ . '/../includes/class_project_rewards.php');
if (isset($_POST['operation']))
    include_once(__DIR__ . '/../language/english/site.lang.php');

if (!defined('INCLUDED')) {
    die("Access Denied");
}
?>

<?= (isset($header_selling_page)) ? $header_selling_page : ''; ?>
<?= (isset($display_formcheck_errors)) ? $display_formcheck_errors : ''; ?>

<script type="text/javascript" src="/scripts/jquery/tinymce/tinymce.min.js" ></script>
<script type="text/javascript" src="/scripts/jquery/tinymce/jquery.tinymce.min.js" ></script>
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
        confirm_delete_reward_button: '<?= MSG_CAMPAIGN_EDIT_REWARDS_DIALOG_BTN_OK; ?>',
        confirm_cancel_reward_button: '<?= MSG_CAMPAIGN_EDIT_REWARDS_DIALOG_BTN_CANCEL; ?>',
        delete_reward_text: '<?= MSG_CAMPAIGN_EDIT_REWARDS_DIALOG_MSG; ?>',
        confirm_delete_update_button: '<?= MSG_MEMBER_AREA_DIALOG_DELETE_UPDATE_CONFIRM_BTN_OK; ?>',
        confirm_cancel_update_button: '<?= MSG_MEMBER_AREA_DIALOG_DELETE_UPDATE_CONFIRM_BTN_CANCEL; ?>',
        delete_update_title: '<?= MSG_MEMBER_AREA_DIALOG_DELETE_UPDATE_TITLE; ?>',
        delete_update_text: '<?= MSG_MEMBER_AREA_DIALOG_DELETE_UPDATE_MSG; ?>',
        post_update_title:'<?= MSG_MEMBER_AREA_DIALOG_POST_UPDATE_TITLE; ?>',
        post_update_text:'<?= MSG_MEMBER_AREA_DIALOG_POST_UPDATE_MSG; ?>',
        confirm_project_update_button:'<?= MSG_MEMBER_AREA_DIALOG_POST_UPDATE_CONFIRM_BTN_OK; ?>',
        cancel_project_update_button:'<?= MSG_MEMBER_AREA_DIALOG_POST_UPDATE_CONFIRM_BTN_CANCEL; ?>'
    };


</script>
<script type="text/javascript" src="/scripts/init_tinymce.js"></script>
<script type="text/javascript" src="/scripts/campaign_form_validate.js"></script>
<script type="text/javascript">

    $(document).on('ready', function () {
        /* Form validated before submit */
        $('input[type="submit"]').each(function() {
            $(this).on('click', function(e){
                e.preventDefault();
                var formElem = $('#formElem');
                validateCampaignForm(formElem, window.error_messages);
                if (formElem.valid()) {formElem.submit();}
            })
        });
        <?php if (isset($video_url) && $video_url): ?>
            $("#vide_select_block").css("display", "inline");
            var banner = $("#banner");
            banner.css("display", "none");
            banner.val("");
            $("#video_url").val("<?php echo isset($video_url) ? $video_url : ''; ?>");
            $("#bannerUpload").removeAttr("checked");
            $("#bannerURL").attr("checked", "checked");
        <?php endif; ?>



        <?php if (isset($user_details['deadline_type_value']) && $user_details['deadline_type_value'] == "time_period"): ?>
        $("#time_period").removeAttr('disabled');
        <?php elseif (isset($user_details['deadline_type_value']) && $user_details['deadline_type_value'] == "certain_date"): ?>
        $('#certain_date').removeAttr('disabled');
        <?php endif; ?>
        $('#add_new_reward_button').on('click', function (e) {
            e.preventDefault();
            addNewRewardToProject("<?= MSG_REWARD_NEEDS_TO_BE_SAVED ?>", <?= $campaign['user_id']; ?>)
        });
        $('.delete_reward_comment').on('click', function(e) {
            e.preventDefault();
            var id = $(this).parents('li').attr('id').replace('project_reward_comment_row', '');
            deleteProjectReward(id);
        });

    });

    var countOfPitch = <?php if (isset($pitches) && is_array($pitches)) echo count($pitches); else { ?>0<?php } ?>;


</script>

<div class="editCampaigns">
    <h2><?= MSG_MEMBER_AREA_CAMPAIGNS_EDIT_CAMPAIGN; ?></h2>

    <div id="wrapper">
        <a href="/view_campaign.php?campaign_id=<?= $campaign['user_id'] ?>" class="view_campaign_btn" target="_blank"><span><?= MSG_VIEW_CAMPAIGN ?></span></a>

        <!-- Tabs navigation -->
        <div id="navigation">
            <ul>
                <li id="p_account" class="selected">
                    <a href="#"><?= MSG_CMN_ACCOUNT ?></a>
                <li id="p_projectDetail">
                    <a href="#"><?= MSG_CMN_DETAILS ?></a>
                </li>
                <li id="p_projectEdit">
                    <a href="#"><?= MSG_CMN_ENHANCEMENTS ?></a>
                </li>
                
                <li id="p_projectUpdates">
                    <a href="#"><?= MSG_CMN_UPDATES ?></a>
                </li>
                <li id="p_projectRewards">
                    <a href="#"><?= MSG_CMN_REWARDS ?></a>
                </li>
                <li id="p_projectStatus">
                    <a href="#"><?= MSG_CMN_STATUS ?></a>
                </li>

            </ul>
        </div>

        <div id="steps">

            <!-- Form for creating campaign -->
            <form action="/campaigns,page,edit,section,<?= $campaign['user_id']; ?>,campaign_id,members_area" method="post"
                  name="registration_form" enctype="multipart/form-data" id="formElem">
            <input type="hidden" id="last_selected_tab" name="last_selected_tab" value="<?= $last_selected_tab ?>"/>
            <input type="hidden" name="operation" value="submit">
            <input type="hidden" name="get_states" value="false">
            <input type="hidden" name="do" value="<?= (isset($do)) ? $do : ''; ?>">
            <input type="hidden" name="user_id" value="<?= (isset($campaign['user_id'])) ? $campaign['user_id'] : ''; ?>"
                   id="user_id_val">
            <input type="hidden" name="name" value="<?= (isset($campaign['name'])) ? $campaign['name'] : ''; ?>">
            <input type="hidden" name="edit_refresh" value="0">
            <input type="hidden" name="generated_pin" value="<?= (isset($generated_pin)) ? $generated_pin : ''; ?>">

            <!-- Basic user data -->
            <fieldset class="step">
                <div class="tabs">
                    <h4><?= MSG_ACCOUNT; ?></h4>
                    <div class="account-tab">
                        <div class="account-row">
                            <label> <?= MSG_REGISTERD_AS; ?></label>
                            <select name="orgtype" id="orgtype" size="1">
                                <option selected="selected"><? if (isset($campaign['orgtype'])) echo($campaign['orgtype']); ?></option>

                                <option value="Charitable organization: homeless shelter">Charitable organization: homeless
                                    shelter
                                </option>
                                <option value="Charitable organization: disability organization">Charitable organization: disability
                                    organization
                                </option>
                                <option value="Charitable organization: youth program">Charitable organization: youth program
                                </option>
                                <option value="Charitable organization: hospital">Charitable organization: hospital</option>
                                <option value="Charitable organization: health care clinic">Charitable organization: health care
                                    clinic
                                </option>
                                <option value="Charitable organization: animal rights group">Charitable organization: animal rights
                                    group
                                </option>
                                <option value="Charitable organization: military group">Charitable organization: military group
                                </option>
                                <option value="Charitable organization: human rights group">Charitable organization: human rights
                                    group
                                </option>
                                <option value="Charitable organization: emergency relief">Charitable organization: emergency
                                    relief
                                </option>
                                <option value="Educational: elementary school">Educational: elementary school</option>
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
                                <option value="Religious: Church or other religious relief organization">Religious: Church or other
                                    religious relief organization
                                </option>
                                <option value="Artistic: symphony or orchestra">Artistic: symphony or orchestra</option>
                                <option value="Artistic: theater group">Artistic: theater group</option>
                                <option value="Artistic: art gallerie">Artistic: art gallery</option>
                                <option value="Artistic: writers' organization">Artistic: writers' organization</option>
                                <option value="Artistic: youth music group">Artistic: youth music group</option>
                                <option value="Other">Other</option>
                            </select>
                            <span><?= MSG_REGISTER_AS_DESC; ?></span>
                        </div>

                        <div class="account-row">
                            <label for="name"> <?= MSG_FULL_NAME; ?> *</label>
                            <input name="name" type="text" id="name"
                                   value="<?php echo isset($campaign["name"]) ? $campaign["name"] : ''; ?>"
                                   size="40" maxlength="30"/>
                            <input name="affiliate" type="hidden" id="affiliate"
                                   value="<?php echo isset($campaign["affiliate"]) ? $campaign["affiliate"] : ''; ?>" size="40"/>
                            <span><?= MSG_FULL_NAME_EXPL; ?></span>
                        </div>
                        <div class="account-row">
                            <label> <?= MSG_COMPANY_NAME; ?></label>
                            <input name="tax_company_name" type="text" id="tax_company_name"
                                   value="<?php echo isset($campaign["tax_company_name"]) ? $campaign["tax_company_name"] : ''; ?>"
                                   size="40" maxlength="30"/>
                            <span><?= MSG_COMPANY_NAME_DESC; ?></span>
                        </div>
                        <div class="account-row">
                            <label> <?= MSG_ADDRESS; ?> *</label>
                            <input name="address" type="text" id="address"
                                   value="<?php echo isset($campaign["address"]) ? $campaign["address"] : ''; ?>"
                                   size="40" maxlength="30"/>
                            <span><?= MSG_ADDRESS_EXPL; ?></span>
                        </div>
                        <div class="account-row">
                            <label> <?= MSG_CITY; ?> *</label>
                            <input name="city" type="text" id="city"
                                   value="<?php echo isset($campaign["city"]) ? $campaign["city"] : ''; ?>"
                                   size="40" maxlength="30"/>
                            <span><?= MSG_CITY_EXPL; ?></span>
                        </div>
                        <div class="account-row">
                            <label> <?= MSG_ZIP_CODE; ?> *</label>
                            <input name="zip_code" type="text" id="zip_code"
                                   value="<?php echo isset($campaign["zip_code"]) ? $campaign["zip_code"] : ''; ?>"
                                   size="40" maxlength="30"/>
                        </div>
                        <div class="account-row">
                            <label><?= MSG_COUNTRY; ?> *</label>
                            <?= $country_dropdown; ?>
                        </div>
                        <div class="account-row">
                            <label><?= MSG_STATE; ?> *</label>

                            <div id="states_box"><?= $state_box; ?></div>

                            <input type="hidden" name="lat" id="lat" value="<?= $campaign['lat']; ?>"/>
                            <input type="hidden" name="lng" id="lng" value="<?= $campaign['lng']; ?>"/>
                        </div>
                        <div class="account-row phone">
                            <label><?= MSG_PHONE; ?> *</label>
                            <input name="phone" type="text" id="phone" value="<?= $campaign['phone']; ?>" size="25"/>
                            <span><?= MSG_PHONE_EXPL; ?></span>

                            <div class="clear"></div>
                        </div>

                        <div class="paypal_block">
                            <h3>PayPal</h3>
                            <?php if (isset($campaign['confirmed_paypal_email']) && $campaign['confirmed_paypal_email']): ?>
                                 <span class="checked"></span>
                            <?php endif; ?>
                            <div class="account-row">
                                <label><? echo MSG_PG_PAYPAL_EMAIL_ADDRESS; ?> *</label>
                                <input name="pg_paypal_email" type="text" id="pg_paypal_email" value="<?= $campaign['pg_paypal_email']; ?>" size="40"/>
                                <span><?= MSG_PG_PAYPAL_EMAIL_ADDRESS_EXPL; ?></span>
                            </div>
                            <div class="account-row">
                                <label><?= MSG_PG_PAYPAL_FIRST_NAME; ?> *</label>
                                <input name="pg_paypal_first_name" type="text" id="pg_paypal_first_name" value="<?= $campaign['pg_paypal_first_name']; ?>" size="40"/>
                                <span><?= MSG_PG_PAYPAL_FIRST_NAME_EXPL; ?></span>
                            </div>
                            <div class="account-row">
                                <label><?= MSG_PG_PAYPAL_LAST_NAME; ?> *</label>
                                <input name="pg_paypal_last_name" type="text" id="pg_paypal_last_name" value="<?= $campaign['pg_paypal_last_name']; ?>" size="40"/>
                                <span><?= MSG_PG_PAYPAL_LAST_NAME_EXPL; ?></span>
                            </div>
                        </div>   
                        <div class="next">
                            <input name="form_register_proceed_step1" type="submit"
                                   value="<?= MSG_SAVE_CHANGES ?>" class="save_btn"/>
                            <div class="right">
                                <input type="button" value="<?= MSG_NEXT ?>"
                                       class="next_btn"/>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <!-- Project basic data -->
            <fieldset class="step">
                <div class="tabs">
                    <h4><?= MSG_PROJECT_DETAILS; ?></h4>
                    <div class="account-tab">
                        <div class="account-row">
                            <label> <?= MSG_CREATE_PROJECT_URL; ?> *</label>
                            <input name="username" type="text" id="username" value="<?php echo isset($campaign["username"]) ? $campaign["username"] : ''; ?>"
                                <?php if (isset($campaign["username"]) && $campaign["username"]) echo "readonly" ?>/>
                            <span><?= MSG_PROJECTURL_EXPLANATION; ?></span>
                        </div>
                        <div class="account-row">
                            <label><?= MSG_CREATE_PROJECT_CHOOSE_CATEGORY; ?> *</label>
                            <select name="project_category">
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo isset($category["id"]) ? $category["id"] : ''; ?>"
                                        <?php
                                            echo (isset($category["id"]) && ($category["id"] == $campaign["project_category"]))
                                            ? 'selected' : '';
                                        ?>
                                        >
                                        <?php echo isset($category["name"]) ? $category["name"] : ''; ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <h5><?= MSG_YOUR_STORY ?> (<span style="font-size: 8px"><?= MSG_YOUR_STORY2 ?>)</span></h5>
                        <div class="account-row">
                            <label><?= MSG_CREATE_PROJECT_CAMPAIGN_BASIC; ?> *</label>
                            <textarea rows="5" cols="60" class="campaign_basic" name="campaign_basic"
                                      id="campaign_basic"><?php echo isset($campaign["campaign_basic"]) ? $campaign["campaign_basic"] : ''; ?></textarea>
                            <span><?= MSG_CREATE_PROJECT_CAMPAIGN_BASIC_EXPLANATION; ?></span>
                        </div>
                        <div class="account-row">
                            <label><?= MSG_CREATE_PROJECT_TITLE; ?> *</label>
                            <input type="text" name="project_title"
                                   value="<?php echo isset($campaign["project_title"]) ? $campaign["project_title"] : ''; ?>"
                                   id="project_title" maxlength="80" size="40">
                            <span><?= MSG_CREATE_PROJECT_CAMPAIGN_BASIC_EXPLANATION; ?></span>
                        </div>
                        <div class="account-row">
                            <label><?= MSG_CREATE_PROJECT_SHORT_DESCRIPTION; ?> *</label>
                            <input type="text" name="project_short_description"
                                   value="<?php echo isset($campaign["description"]) ? $campaign["description"] : ''; ?>"
                                   id="project_short_description" maxlength="160" size="40">
                        </div>
                        <div class="account-row">
                            <label><?= MSG_CREATE_PROJECT_QUESTION_FOUNDRAISING_GOAL; ?> *</label>
                            <input type="text" name="founddrasing_goal" id="founddrasing_goal"
                                   value="<?php echo isset($campaign["founddrasing_goal"]) ? $campaign["founddrasing_goal"] : '500'; ?>">(USD)
                        </div>
                        <div class="account-row deadline">
                            <label><?= MSG_DEADLINE; ?> </label>
                            <input type="hidden" name="deadline_type_value" id="deadline_type_value"
                                   value="<?php echo isset($campaign["deadline_type_value"]) ? $campaign["deadline_type_value"] : ''; ?>">
                            <span>
                                <span class="radio-span">
                                    <input type="radio" name="deadline_type" id="deadline_type_days" value="deadline_type"

                                        <?php echo (isset($campaign["time_period"]) && $campaign["time_period"]) ? "checked" : ''; ?>>
                                    <?= MSG_TIMEPERIOD; ?></span>
                                    <input type="text" name="time_period" id="time_period"
                                        <?php echo (isset($campaign["time_period"]) && $campaign["time_period"]) ? "" : 'disabled="disabled"'; ?>
                                        value="<?php echo (isset($campaign["time_period"]) && $campaign["time_period"]) ? round(($campaign["end_date"] - time()) / 86400) : ''; ?>">
                            </span>
                            <span>
                                <span class="radio-span">
                                    <input type="radio" name="deadline_type" id="deadline_type_date" value="deadline_type"

                                          <?php echo (isset($campaign["certain_date"]) && $campaign["certain_date"]) ? "checked" : ''; ?>>
                                    <?= MSG_DATE; ?>
                                </span>
                                <input type="text" name="certain_date" id="certain_date"
                                  <?php echo (isset($campaign["certain_date"]) && $campaign["certain_date"]) ? "" : 'disabled="disabled"'; ?>
                                     value="<?php echo (isset($campaign["certain_date"]) && $campaign["certain_date"]) ? date('m/d/Y', $campaign["end_date"]) : ''; ?>"
                            </span>
                        </div>
                        
                        <div class="next">
                            <input name="form_register_proceed" type="submit" id="form_register_proceed"
                                   value="<?= MSG_SAVE_CHANGES ?>" class="save_btn"/>
                            <div class="right">
                                <input type="button" value="<?= MSG_PREV ?>"
                                       class="prev_btn"/>
                                <input type="button" value="<?= MSG_NEXT ?>"
                                       class="next_btn"/>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <!--Enhancement Tab-->
           <!--2013/10/15 Edit Start (Anthony)-->
            <fieldset class="step">
                <div class="tabs">
                    <h4><?= MSG_WEBSITE_ADDRESS; ?></h4>
                    <div class="account-tab">
                        <div class="account-row">
                            <label><?= MSG_WEBSITE_ADDRESS_INSTRUCTIONS; ?></label>
                            <input name="url" type="text" class="contentfont" id="url" value="<?= urldecode($campaign['url']); ?>"
                                   size="40"/>
                            <span><?= MSG_WEBSITE_ADDRESS_INSTRUCTIONS2; ?></span>
                        </div>
                        <div class="account-row">
                            <label><?= MSG_FACEBOOK_PAGE_INSTRUCTIONS; ?></label>
                            <input name="facebook_url" type="text" class="contentfont" id="facebook_url"
                                   value="<?= urldecode($campaign['facebook_url']); ?>" size="40"/>
                        </div>
                        <div class="account-row">
                            <label><?= MSG_TWITTER_PAGE_INSTRUCTIONS; ?></label>
                            <input name="twitter_url" type="text" class="contentfont" id="twitter_url"
                                   value="<?= urldecode($campaign['twitter_url']); ?>" size="40"/>
                        </div>
                        <h5><?= MSG_LOGO_DESC; ?></h5>

                        <div class="account-row">
                            <?php if (isset($campaign["logo"]) && $campaign["logo"]): ?>
                                <img src="<?php echo $campaign["logo"] . "?" . time(); ?>">
                            <?php endif; ?>
                            <div id="MultiPowUpload_holder">
                                <input class="file" name="logo" id="logo" accept="image/*" type='file' multiple title="logo file"/>
                                <span class="clear-file-input"><?= MSG_CLEAR ?></span>
                            </div>
                            <div id="serverresponse">
                                <div id="prev_logo"></div>
                                <span><?= MSG_UPLOAD_LOGO_INFORMATION ?></span>
                            </div>
                        </div>
                        <!--<h5><?= MSG_YOUR_STORY ?> (<span style="font-size: 8px"><?= MSG_YOUR_STORY2 ?>)</span></h5>-->
                        <div class="account-row">
                            <?php if (isset($campaign["banner"]) && strstr($campaign["banner"], '/images/partner_logos/') !== false): ?>
                                <img src="<?php echo $campaign['banner'] . "?" . time() ?>">
                            <?php endif; ?>
                            <div class="upload">
                                <div class="radio">
                                    <input id="bannerUpload" type="radio" class="banner_type" onclick="bannerTypeSelect('0')"
                                           name="banner_type"
                                           value="0"  <?php if (!strstr($campaign["banner"], "http://")) {
                                        echo "checked";
                                    } ?> ><label><?= MSG_BANNER_IMAGE ?></label>
                                    <div>
                                        <input class="file" name="banner" id="banner" accept="image/*" type='file' multiple
                                               title="banner file" <?php if (strstr($campaign["banner"], "http://")) {
                                            echo "style='display:none'";
                                        } ?>/>
                                        <span class="clear-file-input"><?= MSG_CLEAR ?></span>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="radio">
                                    <input id="bannerURL" type="radio" class="banner_type" onclick="bannerTypeSelect('1')"
                                           name="banner_type"
                                           value="1" <?php if (strstr($campaign["banner"], "http://")) {
                                        echo "checked";
                                    } ?> ><label><?= MSG_VIDEO_YOUTUBE ?></label>
                                    <div>

                                        <div id="vide_select_block" <?php if (!strstr($campaign["banner"], "http://")) {
                                            echo "style='display:none'";
                                        } ?>>
                                            <div class="float:right;">
                                                <input type="text" name="video_url" id="video_url"
                                                       value="<?php if (strstr($campaign["banner"], "http://")) {
                                                           echo $campaign["banner"];
                                                       } ?>">

                                                <input type="button" id="loadVideo" onclick="loadBannerVideo()" value="Get">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="banners_list">
                                <span><?= MSG_UPLOAD_IMAGE_INFORMATION ?></span>
                                <div id="prev_banner"></div>
                            </div>
                        </div>
                        <div class="next">
                            <input name="form_register_proceed" type="submit" id="form_register_proceed"
                                   value="<?= MSG_SAVE_CHANGES ?>"
                                   class="save_btn"/>

                            <div class="right">
                                <input type="button" value="<?= MSG_PREV ?>" class="prev_btn"/>
                                <input type="button" value="<?= MSG_NEXT ?>" class="next_btn"/>
                            </div>
                    </div>
                </div>
            </fieldset>
           
            <!--2013/10/15 Edit End (Anthony)-->
            
            <!--Updates Tab-->
            <fieldset class="step">
                <div class="tabs">
                    <h4><?= MSG_UPDATES ?></h4>
                    <div class="account-tab">
                        <div class="inner">
                            <h3><?= MSG_POST_AN_UPDATE_TO_CAMPAIGN ?> <img src="/images/question_help.png" height="16" alt="help"
                                                                           title="<?= MSG_POST_AN_UPDATE_TO_CAMPAIGN_TOOLTIP ?>" style="margin-left: 10px;"></h3>
                            <div class="add_post">
                                <textarea name="comment_text" class="project_update_textarea"
                                          id="project_update_textarea"></textarea>

                                <div class="clear"></div>
                                <input type="button" value="<?= MSG_SEND ?>" id="button_project_update_textarea">
                            </div>
                        </div>
                        <div class="clear"></div>
                        <h3><?= MSG_YOUR_UPDATES ?></h3>
                        <ul class="posted_comments" id="project_update_post_comments">
                            <?php foreach ($project_updates as $_update) : ?>
                                <li id="<?= 'project_update_comment_row' . $_update['id']; ?>">
                                    <p><?= html_entity_decode($_update['comment']) ?></p>

                                    <div class="delete_btn">
                                        <span>delete</span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="next">
                            <input name="form_register_proceed" type="submit" id="form_register_proceed"
                                   value="<?= MSG_SAVE_CHANGES ?>" class="save_btn"/>
                            <div class="right">
                                <input type="button" value="<?= MSG_PREV ?>"
                                       class="prev_btn"/>
                                <input type="button" value="<?= MSG_NEXT ?>"
                                       class="next_btn"/>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="step">
                <div class="tabs">
                    <h4><?= MSG_REWARDS ?></h4>
                    <div class="account-tab" id="rewards-section">
                        <h3><?= MSG_REWARDS_NOTE ?></h3>
                        <?php $projectRewards = new projectRewards(); ?>
                        <?php foreach ($project_rewards as $reward) : ?>
                            <?= $projectRewards->newRewardForm($reward); ?>
                        <?php endforeach; ?>
                    </div>
                    <button id="add_new_reward_button"><?= MSG_ADD_REWARD; ?></button>
                    <div class="next">
                        <div class="right">
                            <input type="button" value="<?= MSG_PREV ?>"
                                   class="prev_btn"/>
                            <input type="button" value="<?= MSG_NEXT ?>"
                                   class="next_btn"/>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="step">
            <div class="tabs">
                <h4><?= MSG_CMN_STATUS ?></h4>
                <div class="account-tab">
                    <div class="account-row">
                        <label><?= MSG_ACTIVITY_STATUS; ?> </label>
                        <div class="radio">
                            <input type="radio" name="active" value="0"
                                <?php echo (isset($campaign["active"]) && ($campaign["active"] == 0)) ? "checked" : ''; ?>>
                            <label><?= MSG_ACTIVITY_STATUS_DRAFT ?></label>
                            <img src="/images/question_help.png" height="16" alt="help"
                                 title="<?= MSG_MEMBER_AREA_LIVE_STATUS_TOOLTIP ?>" style="margin-left: 10px;">
                        </div>
                        <div class="radio">
                            <input type="radio" name="active"
                                   value="1" <?php echo (isset($campaign["active"]) && ($campaign["active"] == 1)) ? "checked" : ''; ?>>
                            <label><?= MSG_ACTIVITY_STATUS_LIVE ?></label>
                        </div>
                        <div class="radio">
                            <input type="radio" name="active" value="2"
                                <?php echo (isset($campaign["active"]) && ($campaign["active"] == 2)) ? "checked" : ''; ?>>
                            <label><?= MSG_ACTIVITY_STATUS_CLOSED ?></label>
                        </div>
                    </div>
                    <div class="line-sep"></div>
                    <div class="account-row campaign-cron">
                        <label><?= MSG_CRON_CONFIG ?></label>

                        <div class="radio extend-params">
                            <input type="radio" name="clone_campaign" value="2"
                                <?php echo (isset($campaign["clone_campaign"]) && ($campaign["clone_campaign"] == 2)) ? "checked" : ''; ?>>
                            <label><?= MSG_EXTENDS_DATE_EXISTING_CAMPAIGN ?>&nbsp;</label>

                            <input type="text" name="keep_alive_days" id="keep_alive_days"
                                   value="<?php echo (isset($campaign["keep_alive_days"]) && $campaign["keep_alive_days"]) ? $campaign["keep_alive_days"] : '30'; ?>"/>
                            <label><?= MSG_DAYS ?></label>
                            <img src="/images/question_help.png" height="16" alt="help"
                                 title="<?= MSG_MEMBER_AREA_EXTENDS_DATE_EXISTING_CAMPAIGN_TOOLTIP ?>"
                                 style="margin-left: 10px;">
                        </div>
                        <div class="radio">
                            <input type="radio" name="clone_campaign" value="0"
                                <?php echo (isset($campaign["clone_campaign"]) && ($campaign["clone_campaign"] == 0)) ? "checked" : ''; ?>>
                            <label><?= MSG_LET_CAMPAIGN_CLOSE ?></label>
                            <img src="/images/question_help.png" height="16" alt="help" title="<?= MSG_LET_CAMPAIGN_CLOSE ?>"
                                 style="margin-left: 10px;">
                        </div>
                    </div>
                </div>
                <div class="next">
                    <input type="button" value="<?= MSG_PREV ?>" class="prev_btn"/>
                    <input class="clone_btn" type="button" value="<?= MSG_CLONE_CAMPAIGN ?>"/><img src="/images/question_help.png"height="16" alt="help" title="<?= MSG_MEMBER_AREA_CLONE_CAMPAIGN_TOOLTIP ?>">
                    <div class="right">
                        <input name="form_register_proceed" type="submit" id="form_register_proceed"
                               value="<?= MSG_SAVE_CHANGES ?>"
                               class="save_btn"/>
                    </div>
                </div>
            </div>
			<div class="account-row">
                <label><strong><?=MSG_INCLUDE_CLICKTHROUGH_STATUS;?></strong></label>
				<div class="radio">
					<input type="radio" name="include_clickthrough" value="0" <?php echo (isset($campaign["include_clickthrough"]) && ($campaign["include_clickthrough"] == 0)) ? "checked" : ''; ?>>
					<label><?=MSG_INCLUDE_CLICKTHROUGH_STATUS_NO?></label>
				</div>
				<div class="radio">
					<input type="radio" name="include_clickthrough" value="1" <?php echo (isset($campaign["include_clickthrough"]) && ($campaign["include_clickthrough"] == 1)) ? "checked" : ''; ?>>
					<label><?=MSG_INCLUDE_CLICKTHROUGH_STATUS_YES?></label>
				</div>
            </div>
            <div class="next">
                <input type="button" onclick="prevStepShow('p_projectStatus')" value="<?=MSG_PREV?>" class="next_btn" />
                <div class="right">
                    <input name="form_register_proceed" type="submit" id="form_register_proceed" value="<?=MSG_SAVE_CHANGES?>" class="save_btn"/>
                </div>
            </div>
        </div>
        </fieldset>
    </form>
</div>
</div>

</div>


<!-- Dialogs bodies -->
<div id="confirm_dialog_box" title="<?= MSG_CAMPAIGN_EDIT_REWARDS_DIALOG_TITLE; ?>" style="display: none;">
    <p><?= MSG_CAMPAIGN_EDIT_REWARDS_DIALOG_MSG; ?></p>
</div>

