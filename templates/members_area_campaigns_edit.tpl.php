<?
//echo '<pre>';
//var_dump($campaign);
//echo '</pre>';
include_once('np/language/english/npsite.lang.php');
//var_dump($categories);exit;
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>



<?=$header_selling_page;?>
<?=$display_formcheck_errors;?>
<SCRIPT LANGUAGE="JavaScript">
function submit_form(form_name) {
	form_name.submit();
}

function form_submit() {
    document.registration_form.operation.value = '';
    document.registration_form.get_states.value = 'true';
    document.registration_form.edit_refresh.value = '1';
    document.registration_form.submit();
}

function nextStepShow(id){
    $("#"+id).next().find("a").first().click();
}
function prevStepShow(id){
    $("#"+id).prev().find("a").first().click();
}
function changeDeadlineType(obj,id)
{
    jQuery("#time_period").attr('disabled','disabled');
    jQuery("#time_period").val('');
    jQuery("#certain_date").attr('disabled','disabled');
    jQuery("#certain_date").val('');
    if (obj.attr('checked') == false) {
        jQuery("#"+id).attr('disabled','disabled');
    } else {
        jQuery("#"+id).removeAttr('disabled');
        jQuery("#deadline_type_value").val(id);
    }
}

$( document ).ready( function (){
    console.log('fuck');
    projectUpdateComment();
    projectRewardComment();
    <?php if (isset($user_details['deadline_type_value']) && $user_details['deadline_type_value'] == "time_period"): ?>
    jQuery("#time_period").removeAttr('disabled');
    <?php elseif (isset($user_details['deadline_type_value']) && $user_details['deadline_type_value'] == "certain_date"): ?>
    jQuery("#certain_date").removeAttr('disabled');
    <?php endif; ?>
    $("#certain_date").datepicker();

    $(".banner_type").unbind().bind("click",function(){
        if ($(this).val() == "0") {
            $("#vide_select_block").css("display","none");
            $("#video_url").val("");
            $("#banner").css("display","inline");
        } else {
            $("#vide_select_block").css("display","inline");
            $("#banner").css("display","none");
            $("#banner").val("");
        }
    });

    $("#loadVideo").unbind().bind("click",function(){
        var url =$("#video_url").val();
        if(url === null){ return ""; }
        var vURL = processURL(url);

        if(!vURL) return;
        setPrevImage(vURL);
    });


});

/*function deletePitch(object)
{
    var pitches = parseInt($("#pitches_number").val());
    $("#pitches_number").val(pitches - 1);
    $(object).parent().remove();
}*/


var countOfPitch = <?php if (isset($pitches) && is_array($pitches)) echo count($pitches); else { ?>0<?php } ?>;
/*function addPitch(){
    var pitches = parseInt($("#pitches_number").val());
    $("#pitches_number").val(pitches + 1);
    aux = $("<div class='pitch-content'> </div>");
    aux.html($("#pitch_template").html());
    aux.find("#amoun").attr("id","pitch["+countOfPitch+"][0]").attr("name","pitch_amoun["+countOfPitch+"]");
    aux.find("#name").attr("id","pitch["+countOfPitch+"][1]").attr("name","pitch_name["+countOfPitch+"]");
    aux.find("#description").attr("id","pitch["+countOfPitch+"][2]").attr("name","pitch_description["+countOfPitch+"]");
    $("#pitch_box").append(aux);
    countOfPitch +=1;
    console.log(countOfPitch);

    $('.removePitchButton').unbind().bind('click',function(){
        var pitches = parseInt($("#pitches_number").val());
        $("#pitches_number").val(pitches - 1);
        $(this).parent().remove();
    });


}*/

function projectUpdateComment() {
    //$("#button_project_update_textarea").click(function() {
        console.log("call projectUpdateComment");
        var data = 'add_project_updates=true' + '&project_id=' + $("#user_id_val").val() + '&comment=' + $("#project_update_textarea").val();
        $.ajax({
            url: "/np_compaign_project.php",
            type: "POST",
            data: data,
            success: function(response){
                var commentObj = jQuery.parseJSON(response);
                console.log(commentObj);
                if (commentObj.response == true) {
                    addProjectUpdateComment(commentObj.id);
                }
            },
            error:function(){
                console.log("failure");
            }
        });
   // });
}

function projectRewardComment() {
    //$("#button_project_reward_textarea").click(function() {
        console.log($("#project_reward_textarea").val());
        var data = 'add_project_rewards=true' + '&project_id=' + $("#user_id_val").val() + '&comment=' + $("#project_reward_textarea").val();
        $.ajax({
            url: "/np_compaign_project.php",
            type: "POST",
            data: data,
            success: function(response){
                var commentObj = jQuery.parseJSON(response);
                console.log(commentObj);
                if (commentObj.response == true) {
                    addProjectRewardComment(commentObj.id);
                }
            },
            error:function(){
                console.log("failure");
            }
        });
   // });
}

function addProjectUpdateComment( id )
{
    console.log("call addProjectUpdateComment");
    var comment = $("#project_update_textarea").val();
    $("#project_update_post_comments").prepend('<li id="project_update_comment_row'+ id +'">' +
        '<p>' + comment + '</p>' +
        '<div class="delete_btn" onclick="projectUpdateDelete(' + id + ')">' +
        '<span>delete</span><' +
        '/div>' +
        '</li>');
    $("#project_update_textarea").val('');

}

function addProjectRewardComment( id )
{
    console.log("call addProjectRewardComment");
    var comment = $("#project_reward_textarea").val();
    $("#project_reward_post_comments").prepend('<li id="project_reward_comment_row'+ id +'">' +
        '<p>' + comment + '</p>' +
        '<div class="delete_btn" onclick="projectRewardDelete(' + id + ')">' +
        '   <span>delete</span>' +
        '</div>' +
        '</li>');
    $("#project_reward_textarea").val('');

}

function projectUpdateDelete( id )
{
    var data = 'delete_project_updates=true' + '&updates_id=' + id;
    $.ajax({
        url:"/np_compaign_project",
        type: "POST",
        data: data,
        success: function(response){
            var commentObj = jQuery.parseJSON(response);
            console.log(commentObj);
            if (commentObj.response == true) {
                $("#project_update_comment_row" + id).remove();
            }

        },
        error:function(){
            console.log("failure");
        }
    });
}

function projectRewardDelete( id )
{
    var data = 'delete_project_rewards=true' +'&rewards_id=' + id;
    $.ajax({
        url:"/np_compaign_project",
        type: "POST",
        data: data,
        success: function(response){
            var commentObj = jQuery.parseJSON(response);
            console.log(commentObj);
            if (commentObj.response == true) {
                $("#project_reward_comment_row" + id).remove();
            }

        },
        error:function(){
            console.log("failure");
        }
    });
}


</script>
<div class="editCampaigns">
<h2>Edit Campaigns</h2>
<div id="wrapper">
<a href="/<?=$campaign['username']?>" class="view_campaign_btn" target="_blank"><span>view campaign</span></a>

<div id="navigation" style="display:none;">
    <ul>

        <li id="p_account" class="selected">
            <a href="#">Account</a>
        <li id="p_projectDetail">
            <a href="#">Campaign Details</a>
        </li>
        <li id="p_projectEdit">
            <a href="#">Campaign Edit</a>
        </li>
        <li id="p_projectUpdates">
            <a href="#">Updates</a>
        </li>
        <li id="p_projectRewards">
            <a href="#">Rewards</a>
        </li>
        <li id="p_projectStatus">
            <a href="#">status</a>
        </li>
       <!-- <li id="p_projectPitch">
            <a href="#">Pitch</a>
        </li>-->
    </ul>
</div>
<div id="steps">
<form action="/campaigns,page,edit,section,<?=$campaign['user_id'];?>,campaign_id,members_area" method="post" name="registration_form" enctype="multipart/form-data" id="formElem" >
<input type="hidden" name="operation" value="submit">
<input type="hidden" name="get_states" value="false">
<input type="hidden" name="do" value="<?=$do;?>">
<input type="hidden" name="user_id" value="<?=$campaign['user_id'];?>" id="user_id_val">
<input type="hidden" name="name" value="<?=$campaign['name'];?>">
<input type="hidden" name="edit_refresh" value="0">
<input type="hidden" name="generated_pin" value="<?=$generated_pin;?>">


<fieldset class="step">
    <div class="tabs">
        <h4><?=MSG_ACCOUNT; ?></h4>
        <!--                <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1">-->
        <!--                <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1">-->
        <div class="account-tab">
            <div class="account-row">
                <label> <?=MSG_REGISTERD_AS;?></label>
                <select name="orgtype" id="orgtype" size="1" value = "<? echo ($campaign['orgtype']);?>" ?>
                <option selected="selected"><? echo ($campaign['orgtype']);?></option>

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
                <option value="Other">Other </option>
                </select>
                <span><?=MSG_REGISTER_AS_DESC;?></span>
            </div>
            <div class="account-row">
                <label> <?=MSG_FULL_NAME;?> *</label>
                <input name="name" type="text" id="name"
                       value="<?php echo isset($campaign["name"]) ? $campaign["name"] : ''; ?>"
                       size="40" maxlength="30" />
                <input name="affiliate" type="hidden" id="affiliate"
                       value="<?php echo isset($campaign["affiliate"]) ? $campaign["affiliate"] : ''; ?>" size="40" />
                <span><?=MSG_FULL_NAME_EXPL;?></span>
            </div>
            <div class="account-row">
                <label> <?=MSG_COMPANY_NAME;?> *</label>
                <input name="tax_company_name" type="text" id="tax_company_name"
                       value="<?php echo isset($campaign["tax_company_name"]) ? $campaign["tax_company_name"] : ''; ?>"
                       size="40" maxlength="30" />
                <span><?=MSG_COMPANY_NAME_DESC;?></span>
            </div>
            <div class="account-row">
                <label> <?=MSG_ADDRESS;?> *</label>
                <input name="address" type="text" id="address"
                       value="<?php echo isset($campaign["address"]) ? $campaign["address"] : ''; ?>"
                       size="40" maxlength="30" />
                <span><?=MSG_ADDRESS_EXPL;?></span>
            </div>
            <div class="account-row">
                <label> <?=MSG_CITY;?> *</label>
                <input name="city" type="text" id="city"
                       value="<?php echo isset($campaign["city"]) ? $campaign["city"] : ''; ?>"
                       size="40" maxlength="30" />
                <span><?=MSG_CITY_EXPL;?></span>
            </div>
            <div class="account-row">
                <label> <?=MSG_ZIP_CODE;?> *</label>
                <input name="zip_code" type="text" id="zip_code"
                       value="<?php echo isset($campaign["zip_code"]) ? $campaign["zip_code"] : ''; ?>"
                       size="40" maxlength="30" />
            </div>
            <div class="account-row">
                <label><?=MSG_COUNTRY;?> *</label>
                <?=$country_dropdown;?>
            </div>
            <div class="account-row">
                <label><?=MSG_STATE;?> *</label>
                <?=$state_box;?>

                <input type ="hidden" name="lat" id="lat" value= "<?=$campaign['lat'];?>" />
                <input type ="hidden" name="lng" id="lng" value= "<?=$campaign['lng'];?>" />
            </div>
            <div class="account-row phone">
                <label><?=MSG_PHONE;?> *</label>
                <input name="phone" type="text" id="phone" value="<?=$campaign['phone'];?>" size="25" />
                <span><?=MSG_PHONE_EXPL;?></span>
                <div class="clear"></div>
            </div>
            <div class="account-row">
                <label><?=MSG_PG_PAYPAL_EMAIL_ADDRESS;?> *</label>
                <input name="pg_paypal_email" type="text" id="pg_paypal_email"
                       value="<?=$campaign['pg_paypal_email'];?>" size="40" />
                <span><?=MSG_PG_PAYPAL_EMAIL_ADDRESS_EXPL;?></span>
            </div>
            <div class="account-row">
                <label><?=MSG_PG_PAYPAL_FIRST_NAME;?> *</label>
                <input name="pg_paypal_first_name" type="text" id="pg_paypal_first_name"
                       value="<?=$campaign['pg_paypal_first_name'];?>" size="40" />
                <span><?=MSG_PG_PAYPAL_FIRST_NAME_EXPL;?></span>
            </div>
            <div class="account-row">
                <label><?=MSG_PG_PAYPAL_LAST_NAME;?> *</label>
                <input name="pg_paypal_last_name" type="text" id="pg_paypal_last_name"
                       value="<?=$campaign['pg_paypal_last_name'];?>" size="40" />
                <span><?=MSG_PG_PAYPAL_LAST_NAME_EXPL;?></span>
            </div>

            <div class="next">
                <input name="form_register_proceed" type="submit" id="form_register_proceed" value="Save changes" class="save_btn"/>
                <div class="right">
                    <input type="button" onclick="nextStepShow('p_account')" value="Next" class="next_btn" />
                </div>

            </div>

        </div>
    </div>
</fieldset>


<fieldset class="step">
    <div class="tabs">
        <h4><?=MSG_PROJECT_DETAILS; ?></h4>
        <!--                <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1">-->
        <!--                <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1">-->
        <div class="account-tab">
            <div class="account-row">
                <label> <?=MSG_CREATE_PROJECT_URL;?> *</label>
                <label> <?=MSG_CREATE_PROJECT_URL;?> *</label>
                <input name="username" type="hidden" id="username"
                       value="<?php echo isset($campaign["username"]) ? $campaign["username"] : ''; ?>"/>
                <input name="username_view" type="text" id="username_view" disabled
                       value="<?php echo isset($campaign["username"]) ? $campaign["username"] : ''; ?>"/>
                <span><?=MSG_PROJECTURL_EXPLANATION;?></span>
            </div>
            <div class="account-row">
                <label><?=MSG_CREATE_PROJECT_CHOOSE_CATEGORY;?> *</label>
                <select name="project_category">
                    <?php foreach($categories as $category): ?>
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
<!--                --><?//=$project_country?>
            </div>
            <div class="account-row">
                <label><?=MSG_CREATE_PROJECT_CAMPAIGN_BASIC;?> *</label>
                <textarea rows="5" cols="60" name="campaign_basic" id="campaign_basic"><?php echo isset($campaign["campaign_basic"]) ? $campaign["campaign_basic"] : ''; ?></textarea>
                <span><?=MSG_CREATE_PROJECT_CAMPAIGN_BASIC_EXPLANATION;?></span>
            </div>
            <div class="account-row">
                <label><?=MSG_CREATE_PROJECT_TITLE;?> *</label>
                <input type="text" name="project_title"
                       value="<?php echo isset($campaign["project_title"]) ? $campaign["project_title"] : ''; ?>"
                       id="project_title" maxlength="80" size="40" >
                <span><?=MSG_CREATE_PROJECT_CAMPAIGN_BASIC_EXPLANATION;?></span>
            </div>
            <div class="account-row">
                <label><?=MSG_CREATE_PROJECT_SHORT_DESCRIPTION;?> *</label>
                <input type="text" name="project_short_description"
                       value="<?php echo isset($campaign["description"]) ? $campaign["description"] : ''; ?>"
                       id="project_short_description" maxlength="160" size="40" >
            </div>
            <div class="account-row">
                <label><?=MSG_CREATE_PROJECT_QUESTION_FOUNDRAISING_GOAL;?> *</label>
                <input type="text" name="founddrasing_goal" value="500" id="founddrasing_goal" >(USD)
            </div>
           <!-- <div class="account-row">
                <label><?/*=MSG_FUNDING_TYPE;*/?> *</label>-->
                <!--            <input type="text" name="founddrasing_goal" value="500" id="founddrasing_goal" >-->
                <!--            <div class="clear"></div>-->
               <!-- <div class="radio">
                    <input type="radio" name="funding_type" value="flexible"
                        <?php /*echo (isset($campaign["funding_type"]) && ($campaign["funding_type"] == "flexible")) ? "checked" : ''; */?>>
                    <label><?/*=MSG_FUNDING_TYPE_FLEXIBLE*/?></label>
                </div>
                <div class="radio">
                    <input type="radio" name="funding_type" value="fixed" checked="checked"
                        <?php /*echo (isset($campaign["funding_type"]) && ($campaign["funding_type"] == "fixed")) ? "checked" : ''; */?>>
                    <label><?/*=MSG_FUNDING_TYPE_FIXED*/?></label>
                </div>
            </div>-->
            <div class="account-row deadline">
                <label><?="Deadline";?> </label>
<!--                        <span>-->
<!--                            <span class="radio-span">-->
<!--                            <input type="radio" name="deadline_type" value="deadline_type" onclick="changeDeadlineType(jQuery(this),'deadline_type_value')">-->
<!--                      </span>-->
<!--                        <input type="text" name="deadline_type_value" id="deadline_type_value" disabled="disabled" value="">-->
<!--                        </span>-->
                <input type="hidden" name="deadline_type_value" id="deadline_type_value"
                       value="<?php echo isset($campaign["deadline_type_value"]) ? $campaign["deadline_type_value"] : ''; ?>">
                        <span>
                      <span class="radio-span">
                          <input type="radio" name="deadline_type" value="deadline_type" onclick="changeDeadlineType(jQuery(this),'time_period')"
                              <?php echo (isset($campaign["time_period"]) && $campaign["time_period"]) ? "checked" : ''; ?>>
                       # of days</span>
                       <input type="text" name="time_period" id="time_period"
                              <?php echo (isset($campaign["time_period"]) && $campaign["time_period"]) ? "" : 'disabled="disabled"'; ?>
                              value="<?php echo (isset($campaign["time_period"]) && $campaign["time_period"]) ? round(($campaign["end_date"] - time()) / 86400) : ''; ?>">

                         </span>

                          <span>
                              <span class="radio-span">
                              <input type="radio" name="deadline_type" value="deadline_type" onclick="changeDeadlineType(jQuery(this),'certain_date')"
                                  <?php echo (isset($campaign["certain_date"]) && $campaign["certain_date"]) ? "checked" : ''; ?>>
                      (date)
                         </span>
                      <input type="text" name="certain_date" id="certain_date"
                          <?php echo (isset($campaign["certain_date"]) && $campaign["certain_date"]) ? "" : 'disabled="disabled"'; ?>
                             value="<?php echo (isset($campaign["certain_date"]) && $campaign["certain_date"]) ? date('d-m-Y', $campaign["end_date"]) : ''; ?>">

                         </span>
            </div>

            <div class="next">
                <input name="form_register_proceed" type="submit" id="form_register_proceed" value="Save changes" class="save_btn"/>
                <div class="right">
                    <input type="button" onclick="prevStepShow('p_projectDetail')" value="Prev" class="next_btn" />
                    <input type="button" onclick="nextStepShow('p_projectDetail')" value="Next" class="next_btn" />
                </div>
            </div>
        </div>
    </div>
</fieldset>

<fieldset class="step">
    <div class="tabs">
        <h4><?=MSG_WEBSITE_ADDRESS;?></h4>
        <!--                <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1" />-->
        <!--                <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1" />-->
        <div class="account-tab">

                <div class="account-row">
                    <label><?=MSG_WEBSITE_ADDRESS_INSTRUCTIONS;?></label>
                    <input name="url" type="text" class="contentfont" id="url" value="<?=$campaign['url'];?>" size="40" />
                    <span><?=MSG_WEBSITE_ADDRESS_INSTRUCTIONS2;?></span>
                </div>

                <div class="account-row">
                    <label><?=MSG_FACEBOOK_PAGE_INSTRUCTIONS;?></label>
                    <input name="facebook_url" type="text" class="contentfont" id="facebook_url" value="<?=$campaign['facebook_url'];?>" size="40" />
                </div>

                <div class="account-row">
                    <label><?=MSG_TWITTER_PAGE_INSTRUCTIONS;?></label>
                    <input name="twitter_url" type="text" class="contentfont" id="twitter_url" value="<?=$campaign['twitter_url'];?>" size="40" />
            </div>

            <h5><?=MSG_LOGO_DESC;?></h5>
            <!--               <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1" />-->
            <!--               <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1" />-->
            <div class="account-row">
                <?php if (isset($campaign["logo"]) && $campaign["logo"]): ?>
                    <img src="<?php echo $campaign["logo"]."?".time(); ?>">
                <?php endif; ?>
                <div id="MultiPowUpload_holder">
                    <input class="file" name="logo" id="logo" type='file' multiple title="logo file"/>
                </div>

                <div id="serverresponse">
                    <div id="prev_logo"></div>
                    <span>For best results upload an image that is not more than 160 pixels wide.</span>
                </div>
            </div>
            <h5>Your Story(<span style="font-size: 8px">Tell potential contributors more about your campaign.)</span></h5>
            <!--                 <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1" />-->
            <!--                    <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1" />-->

            <div class="account-row">
                <?php if (isset($campaign["banner"]) && strstr($campaign["banner"], '/images/partner_logos/') !== false): ?>
                <img src="<?php echo $campaign['banner']."?".time() ?>">
                <?php endif; ?>
                <div class="upload">
                    <input type="radio" class="banner_type" name="banner_type" value="0" checked="checked" ><label>Banner Image</label>
                    <div class="clear"></div>
                    <input type="radio" class="banner_type" name="banner_type" value="1" ><label>Video (Youtube or Vimeo)</label>
                </div>
                <div class="clear"></div>
                <br />
                <input class="file" name="banner" id="banner" type='file' multiple title="banner file"/>
                <div id="vide_select_block" style="display: none">
                    <input type="text" name="video_url" id="video_url" value="">
                    <input type="button" id="loadVideo" value="Get">
                </div>
                <div class="banners_list">
                    <span> For best results upload an image that is not more than 600x400 pixels wide.</span>
                    <div id="prev_banner"></div>
                </div>
            </div>
            <?php /*
               <style>
                   .image-checkbox-container input[type="checkbox"]{
                       display: none;
                   }
                   .image-checkbox-container img{
                       border: 3px solid #C3C3C3;
                       margin: 1px;
                       width: 100px;
                   }
               </style>
               */ ?>

            <?php /*
                   $default_banners_dir = ($_SERVER['DOCUMENT_ROOT'] ."/np/banners/default/");

                   $banners = array();
                   if ($handle = opendir($default_banners_dir)) {
                       while (false !== ($banner = readdir($handle))) {
                           if ($banner != '.' && $banner != '..') {
                           ?>
                               <span class="image-checkbox-container">
                                   <input type="checkbox" name="banner" class="banner" value="<?php echo $banner?>" />
                                   <img src="banners/default/<?php echo $banner?>">
                               </span>
                           <?php
                           }
                       }
                       closedir($handle);
                   }
                    <span class="image-checkbox-container" id="uploaded_banner">
                        <?php if(!empty($user_details['banner']) AND (strpos($user_details['banner'],'default') !== false)){?>
                            <script type="text/javascript">
                                jQuery('input[value="<?php echo $user_details['banner']?>"]').attr('checked','checked');
                                jQuery('input[value="<?php echo $user_details['banner']?>"]').closest('.image-checkbox-container').find("img").css("border", '3px solid #4475C6');
                            </script>
                        <?php }elseif(!empty($user_details['banner'])){?>
                            <input type="checkbox" name="banner" class="banner" value="<?php echo $user_details['banner']?>" checked="checked" />
                            <img src="banners/<?php echo $user_details['banner']?>" style="border: 3px solid #4475C6;">
                            <script type="text/javascript">
                                jQuery('.image-checkbox-container img').css("border", "3px solid #C3C3C3");
                                jQuery('.banner').each(function(i,element){
                                    jQuery(element).removeAttr('checked');
                                });
                                jQuery('#uploaded_banner img').css("border", '3px solid #4475C6');
                                jQuery('#uploaded_banner').find(".banner").attr('checked','checked');
                            </script>
                        <?php }else{?>
                            <script type="text/javascript">
                                jQuery('.image-checkbox-container img:first').css("border", '3px solid #4475C6');
                                jQuery('.image-checkbox-container:first').find(".banner").attr('checked','checked');
                            </script>
                        <?php }?>
                        <script type="text/javascript">
                            jQuery('.image-checkbox-container img').live('click', function(){
                                jQuery('.image-checkbox-container img').css("border", "3px solid #C3C3C3");
                                jQuery('.banner').each(function(i,element){
                                    jQuery(element).removeAttr('checked');
                                });
                                jQuery(this).closest('.image-checkbox-container').find(".banner").attr('checked','checked');
                                jQuery(this).css("border", '3px solid #4475C6');
                            });
                        </script>
                    </span>
                    */?>



        <!--    <div class="account-row">
                <h5> Pitch text </h5>
                <div class="pitch"> <textarea rows="5" cols="30" name="pitch_text" id="pitch_text"><?php /*echo isset($campaign["pitch_text"]) ? $campaign["pitch_text"] : ''; */?></textarea> </div>
                <span>*user has to be able to edit this until they have received donations.</span>
            </div>-->

                <div class="next">
                    <input name="form_register_proceed" type="submit" id="form_register_proceed" value="Save changes" class="save_btn"/>
                    <div class="right">
                        <input type="button" onclick="prevStepShow('p_projectEdit')"  value="Prev" class="next_btn" />
                        <input type="button" onclick="nextStepShow('p_projectEdit')" value="Next" class="next_btn" />
                    </div>
                </div>

    </div>
</fieldset>


<!--<fieldset class="step">
    <div class="tabs pitch-tab">
        <div class="pitch-add">
            <input type="hidden" name="pitches_number" id="pitches_number" value="<?php /*echo (isset($pitches) ? count($pitches) : '0') */?>">
            <div class="add-button"> <input type="button" value="Add" onclick="addPitch()" /> </div>
            <br/>
            <div style="display: none" id="pitch_template">
                <div class="account-row"><label>Amount *</label> <input id="amoun" type="text" placeholder="Amoun" /> </div>
                <div class="account-row"><label>Name *</label> <input id="name" type="text" placeholder="Name" /> </div>
                <div class="account-row"><label>Description *</label> <textarea id="description" rows="5" cols="3"></textarea> </div>
                <br/>
                <input type="button" value="Delete" class="removePitchButton"/>
            </div>
            <div id="pitch_box">
                <?php /*foreach ($pitches as $key => $pitch): */?>
                    <div class="pitch-content">
                        <input type="hidden" name="pitch_id[<?php /*echo $key; */?>]" value="<?php /*echo isset($pitch['id']) ? $pitch['id'] : ''; */?>">
                        <div class="account-row">
                            <label>Amount *</label>
                            <input id="pitch[<?php /*echo $key; */?>][0]" type="text" placeholder="Amoun" name="pitch_amoun[<?php /*echo $key; */?>]"
                                value="<?php /*echo isset($pitch['amoun']) ? $pitch['amoun'] : ''; */?>">
                        </div>
                        <div class="account-row">
                            <label>Name *</label>
                            <input id="pitch[<?php /*echo $key; */?>][1]" type="text" placeholder="Name" name="pitch_name[<?php /*echo $key; */?>]"
                                   value="<?php /*echo isset($pitch['name']) ? $pitch['name'] : ''; */?>">
                        </div>
                        <div class="account-row">
                            <label>Description *</label>
                            <textarea id="pitch[<?php /*echo $key; */?>][2]" rows="5" cols="3" name="pitch_description[<?php /*echo $key; */?>]"><?php /*echo isset($pitch['description']) ? $pitch['description'] : ''; */?></textarea>
                        </div>
                        <br>
                        <input type="button" value="Delete" class="removePitchButton" onclick="deletePitch(this)">
                    </div>
                <?php /*endforeach; */?>
            </div>

<!--            <div class="next">-->
<!--                <input type="button" onclick="nextStepShow('p_projectPitch')" value="Next" />-->
<!--            </div>
        </div>
    </div>

</fieldset>-->
<fieldset class="step">
    <div class="tabs">
            <h4>UPDATES</h4>
        <div class="account-tab">
            <aside>
                <div class="inner">
                <h3>Post a comment</h3>
                    <div class="write_post">
                        <div class="user-photo"><img src="themes/bring_it_local/img/incognito.png"></div>
                        <textarea name="comment_text" id="project_update_textarea"></textarea>
                        <input type="button" value="send" id="button_project_update_textarea" onclick="projectUpdateComment()">
                    </div>
                </div>
            </aside>
            <div class="clear"></div>
            <h3>Your comments</h3>
            <ul class="posted_comments" id="project_update_post_comments">
                <?php foreach ($project_updates as $_update) :?>
                    <li id="<?='project_reward_comment_row'.$_update['id'];?>">
                        <p><?=$_update['comment']?></p>
                        <div class="delete_btn" onclick="projectRewardDelete(<?=$_update['id']?>)">
                            <span>delete</span>
                        </div>
                    </li>
                <?php endforeach;?>
            </ul>

            <div class="next">
                <input name="form_register_proceed" type="submit" id="form_register_proceed" value="Save changes" class="save_btn"/>
                <div class="right">
                    <input type="button" onclick="prevStepShow('p_projectUpdates')" value="Prev" class="next_btn" />
                    <input type="button" onclick="nextStepShow('p_projectUpdates')" value="Next" class="next_btn" />
                </div>

            </div>

        </div>

    </div>
</fieldset>


<fieldset class="step">
    <div class="tabs">
        <h4>REWARDS</h4>
        <div class="account-tab">
            <aside>
                <div class="inner">
                    <h3>Post a comment</h3>
                    <div class="write_post">
                        <div class="user-photo"><img src="themes/bring_it_local/img/incognito.png"></div>
                        <input name="compaign" type="hidden" value="10275">
                        <textarea name="comment_text" id="project_reward_textarea"></textarea>
                        <input type="button" value="send" id="button_project_reward_textarea" onclick="projectRewardComment()">
                    </div>
                </div>
            </aside>
            <div class="clear"></div>
            <h3>Your comments</h3>
            <ul class="posted_comments" id="project_reward_post_comments">
                <?php foreach ($project_rewards as $_reward) :?>
                    <li id="<?='project_reward_comment_row'.$_reward['id'];?>">
                        <p><?=$_reward['comment']?></p>
                        <div class="delete_btn" onclick="projectRewardDelete(<?=$_reward['id']?>)">
                               <span>delete</span>
                        </div>
                    </li>
                <?php endforeach;?>
            </ul>
            <div class="next">
                <input name="form_register_proceed" type="submit" id="form_register_proceed" value="Save changes" class="save_btn"/>
              <div class="right">
                  <input type="button" onclick="prevStepShow('p_projectRewards')" value="Prev" class="next_btn" />
                  <input type="button" onclick="nextStepShow('p_projectRewards')" value="Prev" class="next_btn" />

              </div>
            </div>
        </div>
    </div>
</fieldset>

<fieldset class="step">
    <div class="tabs">
        <h4>Status</h4>
        <div class="account-tab">
            <div class="account-row">
                <label><?=MSG_ACTIVITY_STATUS;?> *</label>
                <!--            <input type="text" name="founddrasing_goal" value="500" id="founddrasing_goal" >-->
                <!--            <div class="clear"></div>-->
                <div class="radio">
                    <input type="radio" name="active" value="0"
                        <?php echo (isset($campaign["active"]) && ($campaign["active"] == 0)) ? "checked" : ''; ?>>
                    <label><?=MSG_ACTIVITY_STATUS_DRAFT?></label>
                </div>
                <div class="radio">
                    <input type="radio" name="active" value="1"
                        <?php echo (isset($campaign["active"]) && ($campaign["active"] == 1)) ? "checked" : ''; ?>>
                    <label><?=MSG_ACTIVITY_STATUS_LIVE?></label>
                </div>
                <div class="radio">
                    <input type="radio" name="active" value="2"
                        <?php echo (isset($campaign["active"]) && ($campaign["active"] == 2)) ? "checked" : ''; ?>>
                    <label><?=MSG_ACTIVITY_STATUS_CLOSED?></label>
                </div>
            </div>

            <div class="next">
                <input type="button" onclick="prevStepShow('p_projectStatus')" value="Prev" class="next_btn" />
                <div class="right">
                    <input name="form_register_proceed" type="submit" id="form_register_proceed" value="Save changes" class="save_btn"/>
                </div>
            </div>
        </div>
    </div>
</fieldset>



</form>
</div>
</div>
</div>
