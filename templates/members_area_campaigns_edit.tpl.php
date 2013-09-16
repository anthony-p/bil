<?
require_once (__DIR__ . '/../includes/class_project_rewards.php');
if (isset($_POST['operation']))
    include_once(__DIR__ . '/../language/english/site.lang.php');
//    include_once('np/language/english/npsite.lang.php');

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>

<?=(isset($header_selling_page))?$header_selling_page:'';?>
<?=(isset($display_formcheck_errors))?$display_formcheck_errors:'';?>
<script src="/scripts/jquery/jquery-1.9.1.js"></script>
<script type="text/javascript" src='/scripts/jquery/jquery-ui.js'></script>
<link type="text/css" rel="stylesheet" href="/css/ui-lightness/jquery-ui-1.10.3.custom.min.css">

<script language="JavaScript" src="/scripts/jquery/tinymce/tinymce.min.js" js="text/javascript"></script>
<script language="JavaScript" src="/scripts/jquery/tinymce/jquery.tinymce.min.js" js="text/javascript"></script>

<!--<script language="JavaScript" src="/scripts/jquery/tiny_mce/jquery.tinymce.js" js="text/javascript"></script>-->
<!--<script language="JavaScript" src="/scripts/jquery/tiny_mce/plugins/moxiemanager/editor_plugin.js" js="text/javascript"></script>-->



<script type="text/javascript">

$( document ).ready( function (){

    $("#certain_date").datepicker();

    goToSelectedTab();

    <?php if (isset($video_url) && $video_url): ?>
    $("#vide_select_block").css("display","inline");
    $("#banner").css("display","none");
    $("#banner").val("");
    $("#video_url").val("<?php echo isset($video_url) ? $video_url : ''; ?>");
    $("#bannerUpload").removeAttr("checked");
    $("#bannerURL").attr("checked", "checked");
    <?php endif; ?>

    $("#navigation li").click(function() {
        var tab_id = $(this).attr('id');
        $("#last_selected_tab").val(tab_id);
    })


    <?php if (isset($user_details['deadline_type_value']) && $user_details['deadline_type_value'] == "time_period"): ?>
    jQuery("#time_period").removeAttr('disabled');
    <?php elseif (isset($user_details['deadline_type_value']) && $user_details['deadline_type_value'] == "certain_date"): ?>
    jQuery("#certain_date").removeAttr('disabled');
    <?php endif; ?>

});

function bannerTypeSelect(flag){
    if (flag == "0") {
        $("#vide_select_block").css("display","none");
        $("#video_url").val("");
        $("#banner").css("display","inline");
        $("#video_url").val("");
    } else {
        $("#vide_select_block").css("display","inline");
        $("#banner").css("display","none");
        $("#banner").val("");
        clearBannerContent();
    }
}


function loadBannerVideo(){
    var url =$("#video_url").val();
    if(url === null){ return ""; }
    var vURL = processURL(url);

    if(!vURL) return;
    setPrevImage(vURL);
}

/**
 *
 * @param vURL
 */
function setPrevImage(vURL)
{
    if ($("#video_thumb").length > 0) {
        $("#video_thumb").attr("src",vURL);
    } else {
        $("#prev_banner").append('<img width="180px" id="video_thumb" src="'+vURL+'" />');
    }
    $("#prev_banner > .prev_thumb").remove();
}

/**
 *
 * @param url
 * @returns {*}
 */
function processURL(url){
    var id;
    if (url.indexOf('youtube.com') > -1) {
        results = url.match("[\\?&]v=([^&#]*)");
        id = ( results === null ) ? url : results[1];
        return processYouTube(id);
    } else if (url.indexOf('youtu.be') > -1) {
        id = url.split('/')[3];
        return processYouTube(id);
    } else if (url.indexOf('vimeo.com') > -1) {
        url = url.replace("http://","");
        if (url.match(/^vimeo.com\/[0-9]+/)) {
            id = url.split('/')[1];
        } else if (url.match(/^vimeo.com\/channels\/[\d\w]+#[0-9]+/)) {
            id = url.split('#')[1];
        } else if (url.match(/vimeo.com\/groups\/[\d\w]+\/videos\/[0-9]+/)) {
            id = url.split('/')[4];
        } else {
            throw new Error('Unsupported Vimeo URL');
        }

        $.ajax({
            url: 'http://vimeo.com/api/v2/video/' + id + '.json',
            dataType: 'jsonp',
            success: function(data) {
                setPrevImage(data[0].thumbnail_large);
            }
        });
    } else if (ImageExistFromUrl(url)) {
        return url;
    } else {
        throw new Error('Unrecognised URL');
    }


    function ImageExistFromUrl(url)
    {
        var img = new Image();
        img.src = url;
        console.log(img.height);
        if (img.height != 0) {
            return true
        } else {
            return false;
        }
    }

    function processYouTube(id) {
        if (!id) {
            throw new Error('Unsupported YouTube URL');
        }

        return "http://img.youtube.com/vi/"+id+"/0.jpg";
    }
}



function goToSelectedTab(){
    if ($("#last_selected_tab").val() !="") {
        var idTab = $("#last_selected_tab").val();
        $("#"+idTab).find("a").first().click();
    }

}

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


var countOfPitch = <?php if (isset($pitches) && is_array($pitches)) echo count($pitches); else { ?>0<?php } ?>;

function projectUpdateComment () {

    var data = 'add_project_updates=true' + '&project_id=' + $("#user_id_val").val() + '&comment=' + tinymce.get('project_update_textarea').getContent();
    $.ajax({
        url: "/np_compaign_project.php",
        type: "POST",
        data: data,
        success: function(response){
            var commentObj = jQuery.parseJSON(response);
            if (commentObj.response == true) {
                addProjectUpdateComment(commentObj.id);
            }
        },
        error:function(){
            console.log("failure");
        }
    });
}



function projectRewardComment () {
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
}



function addProjectUpdateComment( id ){
    var comment = tinymce.get('project_update_textarea').getContent();
    $("#project_update_post_comments").prepend('<li id="project_update_comment_row'+ id +'">' +
        '<p>' + comment + '</p>' +
        '<div class="delete_btn" onclick="projectUpdateDelete(' + id + ')">' +
        '<span>delete</span><' +
        '/div>' +
        '</li>');
    $("#project_update_textarea").val('');
}



function addProjectRewardComment( id ){
    var comment = $("#project_reward_textarea").val();
    $("#project_reward_post_comments").prepend('<li id="project_reward_comment_row'+ id +'">' +
        '<p>' + comment + '</p>' +
        '<div class="delete_btn" onclick="deleteProjectReward(' + id + ')">' +
        '<span>delete</span>' +
        '</div>' +
        '</li>');
    $("#project_reward_textarea").val('');
}



function projectUpdateDelete( id ){
    var data = 'delete_project_updates=true' + '&updates_id=' + id;
    $.ajax({
        url:"/np_compaign_project",
        type: "POST",
        data: data,
        success: function(response){
            var commentObj = jQuery.parseJSON(response);
            if (commentObj.response == true) {
                $("#project_update_comment_row" + id).remove();
            }
        },
        error:function(){
            console.log("failure");
        }
    });
}



function deleteProjectReward (id){
	is_new = $("#is_new_" + id).val();
	if(is_new == '1'){
		has_new_reward_form = false;
		$("#reward_block_" + id).slideUp(750);
	} else {
		$.ajax({
			url:"/np_compaign_project",
			type: "POST",
			data: {delete_project_rewards: true, rewards_id: id},
			success: function(response){
				response = jQuery.parseJSON(response).response;
				if (response == true) {
					$("#reward_block_" + id).slideUp(750);
				} else {
					alert(response);
				}
			},
			error:function(){
				alert("Error");
			}
		});
	} 
}

function validateProjectReward(id){
	if($("#reward_amount_"+id).val() == ""){
		alert("<?= MSG_REWARD_AMOUNT_MUST_BE_SPECIFIED ?>");
		return false;
	}
	
	if(!$.isNumeric($("#reward_amount_"+id).val())){
		alert("<?= MSG_REWARD_AMOUNT_MUST_BE_A_NUMBER ?>");
		return false;
	}
	
	if($("#reward_name_"+id).val() == ""){
		alert("<?= MSG_REWARD_NAME_MUST_BE_SPECIFIED ?>");
		return false;
	}
	
	if($("#reward_short_description_"+id).val() == ""){
		alert("<?= MSG_REWARD_SHORT_DESCRIPTION_MUST_BE_SPECIFIED ?>");
		return false;
	}
	
	if($("#reward_available_number_"+id).val() != '' && !$.isNumeric($("#reward_available_number_"+id).val())){
		alert("<?= MSG_REWARD_AVAILABLE_NUMBER_MUST_BE_A_NUMBER ?>");
		return false;
	}
	
	return true;
}

function updateProjectReward (id){
	if(validateProjectReward(id)){
		$.ajax({
			url:"/np_compaign_project",
			type: "POST",
			data: {update_project_rewards: true, rewards_id: id, reward_amount: $("#reward_amount_"+id).val(), reward_name: $("#reward_name_"+id).val(), reward_short_description: $("#reward_short_description_"+id).val(), reward_description: tinymce.get('reward_description_'+id).getContent(), reward_available_number: $("#reward_available_number_"+id).val(), reward_estimated_delivery_date: $("#reward_estimated_delivery_date_"+id).val(), reward_available_number: $("#reward_available_number_"+id).val(), reward_shipping_address_required: $("#reward_shipping_address_required_"+id).is(':checked')},
			success: function(response){
				alert(jQuery.parseJSON(response).response);
			},
			error:function(){
				alert("Error");
			}
		});
	}
}

function saveProjectReward (id){
	if(validateProjectReward(id)){
		$.ajax({
			url:"/np_compaign_project",
			type: "POST",
			data: {save_project_rewards: true, campaign_id: <?= $campaign['user_id']; ?>, reward_amount: $("#reward_amount_"+id).val(), reward_name: $("#reward_name_"+id).val(), reward_short_description: $("#reward_short_description_"+id).val(), reward_description: tinymce.get('reward_description_'+id).getContent(), reward_available_number: $("#reward_available_number_"+id).val(), reward_estimated_delivery_date: $("#reward_estimated_delivery_date_"+id).val(), reward_available_number: $("#reward_available_number_"+id).val(), reward_shipping_address_required: $("#reward_shipping_address_required_"+id).is(':checked')},
			success: function(response){
				response = jQuery.parseJSON(response).response;
				if(response.substr(0, 4) == '<div'){
					has_new_reward_form = false;
					$('.reward_block').last().remove();
					$("#rewards-section").append(response);
					alert("<?= MSG_REWARD_SAVED; ?>");
				} else {
					alert(response);
				}
			},
			error:function(){
				alert("Error");
			}
		});
	}
}

var has_new_reward_form = false;

function addNewRewardToProject() {
	if(!has_new_reward_form){
		$.ajax({
			url:"/np_compaign_project",
			type: "POST",
			data: {addNewRewardToProject: true, has_new_reward_form: false, campaign_id: <?= $campaign['user_id']; ?>},
			success: function(response){
				response = jQuery.parseJSON(response).response;
				if(response.substr(0, 4) == '<div'){
					has_new_reward_form = true;
					$("#rewards-section").append(response);
				} else {
					alert(response);
				}
			},
			error:function(){
				alert("Error");
			}
		});
	} else {
		alert("<?= MSG_REWARD_NEEDS_TO_BE_SAVED ?>");
	}
}

function clearLogoContent()
{

    var control = $("#logo");
    control.replaceWith( control = control.clone( true ) );

}

function clearBannerContent()
{
    var control = $("#banner");
    control.replaceWith( control = control.clone( true ) );
}

</script>

<div class="editCampaigns">
<h2><?= MSG_MEMBER_AREA_CAMPAIGNS_EDIT_CAMPAIGN; ?></h2>
<div id="wrapper">
<a href="/view_campaign.php?campaign_id=<?=$campaign['user_id']?>" class="view_campaign_btn" target="_blank"><span><?=MSG_VIEW_CAMPAIGN?></span></a>
<div id="navigation" style="display:none;">
    <ul>
        <li id="p_account" class="selected">
            <a href="#"><?=MSG_CMN_ACCOUNT?></a>
        <li id="p_projectDetail">
            <a href="#"><?=MSG_CMN_DETAILS?></a>
        </li>
        <li id="p_projectEdit">
            <a href="#"><?=MSG_CMN_ENHANCEMENTS?></a>
        </li>
        <li id="p_projectUpdates">
            <a href="#"><?=MSG_CMN_UPDATES?></a>
        </li>
        <li id="p_projectRewards">
            <a href="#"><?=MSG_CMN_REWARDS?></a>
        </li>
        <li id="p_projectStatus">
            <a href="#"><?=MSG_CMN_STATUS?></a>
        </li>
       <!-- <li id="p_projectPitch">
            <a href="#">Pitch</a>
        </li>-->
    </ul>
</div>

<div id="steps">
<form action="/campaigns,page,edit,section,<?=$campaign['user_id'];?>,campaign_id,members_area" method="post" name="registration_form" enctype="multipart/form-data" id="formElem" >
<input type="hidden" id="last_selected_tab" name="last_selected_tab" value="<?=$last_selected_tab?>" />
<input type="hidden" name="operation" value="submit">
<input type="hidden" name="get_states" value="false">
<input type="hidden" name="do" value="<?=(isset($do))?$do:'';?>">
<input type="hidden" name="user_id" value="<?=(isset($campaign['user_id']))?$campaign['user_id']:'';?>" id="user_id_val">
<input type="hidden" name="name" value="<?=(isset($campaign['name']))?$campaign['name']:'';?>">
<input type="hidden" name="edit_refresh" value="0">
<input type="hidden" name="generated_pin" value="<?=(isset($generated_pin))?$generated_pin:'';?>">


<fieldset class="step">

    <div class="tabs">
        <h4><?=MSG_ACCOUNT; ?></h4>
        <div class="account-tab">
            <div class="account-row">

                <label> <?=MSG_REGISTERD_AS;?></label>

                <select name="orgtype" id="orgtype" size="1">

                <option selected="selected"><? if (isset($campaign['orgtype'])) echo ($campaign['orgtype']);?></option>


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
          <?php if (isset($campaign['confirmed_paypal_email']) && $campaign['confirmed_paypal_email']): ?>
            <div class="paypal_block">
                <span class="checked"></span>
          <?php else: ?>
                    <div>
          <?php endif; ?>
              <div class="account-row">
                  <label><? echo MSG_PG_PAYPAL_EMAIL_ADDRESS;?> *</label>
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
          </div>



            <div class="next">

                <input name="form_register_proceed" type="submit" id="form_register_proceed" value="<?=MSG_SAVE_CHANGES?>" class="save_btn"/>

                <div class="right">

                    <input type="button" onclick="nextStepShow('p_account')" value="<?=MSG_NEXT?>" class="next_btn" />

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
                <input name="username" type="text" id="username"
                       value="<?php echo isset($campaign["username"]) ? $campaign["username"] : ''; ?>"
                    <?php if (isset($campaign["username"]) && $campaign["username"]) echo "readonly" ?>/>

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
                <textarea rows="5" cols="60" class="campaign_basic" name="campaign_basic" id="campaign_basic"><?php echo isset($campaign["campaign_basic"]) ? $campaign["campaign_basic"] : ''; ?></textarea>
                <span><?=MSG_CREATE_PROJECT_CAMPAIGN_BASIC_EXPLANATION;?></span>
            </div>
            <div class="account-row">
                <label><?=MSG_CREATE_PROJECT_TITLE;?> *</label>
                <input type="text" name="project_title"
                       value="<?php echo isset($campaign["project_title"]) ? $campaign["project_title"] : ''; ?>"
                       id="project_title" maxlength="80" size="40" >
                <span><?=MSG_CREATE_PROJECT_CAMPAIGN_BASIC_EXPLANATION;?></span>

               <!-- <span><?/*=MSG_CREATE_PROJECT_CAMPAIGN_BASIC_EXPLANATION;*/?></span>-->

            </div>
            <div class="account-row">

                <label><?=MSG_CREATE_PROJECT_SHORT_DESCRIPTION;?> *</label>

                <input type="text" name="project_short_description"

                       value="<?php echo isset($campaign["description"]) ? $campaign["description"] : ''; ?>"

                       id="project_short_description" maxlength="160" size="40" >

            </div>

            <div class="account-row">

                <label><?=MSG_CREATE_PROJECT_QUESTION_FOUNDRAISING_GOAL;?> *</label>

                <input type="text" name="founddrasing_goal" id="founddrasing_goal" value="<?php echo isset($campaign["founddrasing_goal"]) ? $campaign["founddrasing_goal"] : '500'; ?>" >(USD)</div>

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
                <label><?=MSG_DEADLINE;?> </label>
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

                       <?=MSG_TIMEPERIOD;?></span>

                       <input type="text" name="time_period" id="time_period"

                              <?php echo (isset($campaign["time_period"]) && $campaign["time_period"]) ? "" : 'disabled="disabled"'; ?>

                              value="<?php echo (isset($campaign["time_period"]) && $campaign["time_period"]) ? round(($campaign["end_date"] - time()) / 86400) : ''; ?>">



                         </span>



                          <span>

                              <span class="radio-span">

                              <input type="radio" name="deadline_type" value="deadline_type" onclick="changeDeadlineType(jQuery(this),'certain_date')"

                                  <?php echo (isset($campaign["certain_date"]) && $campaign["certain_date"]) ? "checked" : ''; ?>>

                      <?=MSG_DATE;?>

                         </span>

                      <input type="text" name="certain_date" id="certain_date"

                          <?php echo (isset($campaign["certain_date"]) && $campaign["certain_date"]) ? "" : 'disabled="disabled"'; ?>
                             value="<?php echo (isset($campaign["certain_date"]) && $campaign["certain_date"]) ? date('m/d/Y', $campaign["end_date"]) : ''; ?>">

                         </span>

            </div>


            <div class="next">
                <input name="form_register_proceed" type="submit" id="form_register_proceed" value="<?=MSG_SAVE_CHANGES?>" class="save_btn"/>
                <div class="right">
                    <input type="button" onclick="prevStepShow('p_projectDetail')" value="<?=MSG_PREV?>" class="next_btn" />
                    <input type="button" onclick="nextStepShow('p_projectDetail')" value="<?=MSG_NEXT?>" class="next_btn" />
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
                    <span style="cursor: pointer;" onclick="clearLogoContent()"><?=MSG_CLEAR?></span>
                </div>



                <div id="serverresponse">

                    <div id="prev_logo"></div>

                    <span><?=MSG_UPLOAD_LOGO_INFORMATION?></span>

                </div>

            </div>

            <h5><?=MSG_YOUR_STORY?> (<span style="font-size: 8px"><?=MSG_YOUR_STORY2?>)</span></h5>

            <!--                 <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1" />-->

            <!--                    <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1" />-->



            <div class="account-row">
                <?php if (isset($campaign["banner"]) && strstr($campaign["banner"], '/images/partner_logos/') !== false): ?>

                <img src="<?php echo $campaign['banner']."?".time() ?>">

                <?php endif; ?>

                <div class="upload">

                    <input id="bannerUpload" type="radio" class="banner_type" onclick="bannerTypeSelect('0')" name="banner_type" value="0"  <?php if ( !strstr($campaign["banner"], "http://")) { echo "checked";}?> ><label><?=MSG_BANNER_IMAGE?></label>

                    <div class="clear"></div>

                    <input id="bannerURL" type="radio" class="banner_type" onclick="bannerTypeSelect('1')" name="banner_type" value="1" <?php if ( strstr($campaign["banner"], "http://")) { echo "checked";}?> ><label><?=MSG_VIDEO_YOUTUBE?></label>

                </div>

                <div class="clear"></div>

                <br />

                <input  class="file" name="banner" id="banner" type='file' multiple title="banner file" <?php if ( strstr($campaign["banner"], "http://")) { echo "style='display:none'";}?>/>
                <span style="cursor: pointer" onclick="clearBannerContent()"><?=MSG_CLEAR?></span>
                <div id="vide_select_block" <?php if ( !strstr($campaign["banner"], "http://")) { echo "style='display:none'";}?>>

                    <input type="text" name="video_url" id="video_url" value="<?php if ( strstr($campaign["banner"], "http://")) { echo $campaign["banner"];}?>">

                    <input type="button" id="loadVideo" onclick="loadBannerVideo()" value="Get">

                </div>

                <div class="banners_list">

                    <span><?=MSG_UPLOAD_IMAGE_INFORMATION?></span>

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
                    <input name="form_register_proceed" type="submit" id="form_register_proceed" value="<?=MSG_SAVE_CHANGES?>" class="save_btn"/>
                    <div class="right">
                        <input type="button" onclick="prevStepShow('p_projectEdit')"  value="<?=MSG_PREV?>" class="next_btn" />
                        <input type="button" onclick="nextStepShow('p_projectEdit')" value="<?=MSG_NEXT?>" class="next_btn" />
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
            <h4><?=MSG_UPDATES?></h4>
        <div class="account-tab">
            <aside>
                <div class="inner">
                <h3><?=MSG_POST_AN_UPDATE_TO_CAMPAIGN?></h3>
                    <div class="add_post">
<!--                        <div class="user-photo"><img src="themes/bring_it_local/img/incognito.png"></div>-->
                        <textarea name="comment_text" class="project_update_textarea" id="project_update_textarea"></textarea>
                         <div class="clear"></div>
                        <input type="button" value="<?=MSG_SEND?>" id="button_project_update_textarea" onclick="projectUpdateComment()">
                    </div>
                </div>
            </aside>
            <div class="clear"></div>
            <h3><?=MSG_YOUR_UPDATES?></h3>
            <ul class="posted_comments" id="project_update_post_comments">
                <?php foreach ($project_updates as $_update) :?>
                    <li id="<?='project_update_comment_row'.$_update['id'];?>">
                        <p><?= html_entity_decode($_update['comment'])?></p>
                        <div class="delete_btn" onclick="projectUpdateDelete(<?=$_update['id']?>)">
                            <span>delete</span>
                        </div>
                    </li>
                <?php endforeach;?>

            </ul>



            <div class="next">

                <input name="form_register_proceed" type="submit" id="form_register_proceed" value="<?=MSG_SAVE_CHANGES?>" class="save_btn"/>

                <div class="right">

                    <input type="button" onclick="prevStepShow('p_projectUpdates')" value="<?=MSG_PREV?>" class="next_btn" />

                    <input type="button" onclick="nextStepShow('p_projectUpdates')" value="<?=MSG_NEXT?>" class="next_btn" />

                </div>



            </div>



        </div>



    </div>

</fieldset>

<fieldset class="step">
    <div class="tabs">
        <h4><?= MSG_REWARDS ?></h4>
		<h3><?= MSG_REWARDS_NOTE ?></h3>
        <div class="account-tab" style="width: 100%;" id="rewards-section">
			<?php $projectRewards = new projectRewards(); ?>
			<?php foreach ($project_rewards as $reward) :?>
			<?= $projectRewards->newRewardForm($reward); ?>
			<?php endforeach;?>
        </div>
		<button onclick="addNewRewardToProject(); return false;" id="add_new_reward_button"><?= MSG_ADD_REWARD; ?></button>
        <div class="next">
            <!--<input name="form_register_proceed" type="submit" id="form_register_proceed" value="<?=MSG_SAVE_CHANGES?>" class="save_btn"/>-->
            <div class="right">
                <input type="button" onclick="prevStepShow('p_projectRewards')" value="<?=MSG_PREV?>" class="next_btn" />
                <input type="button" onclick="nextStepShow('p_projectRewards')" value="<?=MSG_NEXT?>" class="next_btn" />
            </div>
        </div>
    </div>
</fieldset>

<fieldset class="step">
    <div class="tabs">
        <h4><?=MSG_CMN_STATUS?></h4>
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
            <div class="account-row">
                <label><?=MSG_CRON_CONFIG?></label>
                <div class="radio">
                    <input type="radio" name="cron_company" value="1"
                        <?php echo (isset($campaign["cron_company"]) && ($campaign["cron_company"] == 1)) ? "checked" : ''; ?>>
                    <label><?=MSG_CLONE_CAMPAIGN?></label>
                </div>
                <div class="radio">
                    <input type="radio" name="cron_company" value="0"
                        <?php echo (isset($campaign["cron_company"]) && ($campaign["cron_company"] == 0)) ? "checked" : ''; ?>>
                    <label><?=MSG_EXTENDS_DATE_EXISTING_CAMPAIGN?></label>
                </div>
            </div>
            <div class="clear"></div>
            <div class="input_row">
                <input type="text" name="keep_alive_days" id="keep_alive_days"
                       value="<?php echo (isset($campaign["keep_alive_days"]) && $campaign["keep_alive_days"]) ? $campaign["keep_alive_days"] : '30'; ?>" />
                <label><?=MSG_DAYS?></label>
                <input type="checkbox" name="keep_alive" id="keep_alive" value="1"
                    <?php echo (isset($campaign["keep_alive"]) && $campaign["keep_alive"]) ? 'checked' : ''; ?> />
            </div>
            <div class="next">
                <input type="button" onclick="prevStepShow('p_projectStatus')" value="<?=MSG_PREV?>" class="next_btn" />
                <div class="right">
                    <input name="form_register_proceed" type="submit" id="form_register_proceed" value="<?=MSG_SAVE_CHANGES?>" class="save_btn"/>
                </div>
            </div>
        </div>
    </div>
</fieldset>
</form>
</div>
</div>

</div>


<script>
    $( document ).ready( function (){

        /* == == == == == == == == == == == == == == == == == == == == == == ==*/
        tinymce.PluginManager.load('moxiemanager', '/scripts/jquery/tinymce/plugins/moxiemanager/plugin.js');

        tinymce.init({
            selector:'.campaign_basic',
            plugins: [
                "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
                		"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                		"table contextmenu directionality emoticons template textcolor paste fullpage textcolor moxiemanager"
            ],
            toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
            	toolbar2: "cut copy paste pastetext | searchreplace | bullist numlist | outdent indent blockquote | undo redo | insertfile link unlink anchor image media code | forecolor backcolor",
            	toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft preview",

            	menubar: false,
                image_advtab: true,
            	toolbar_items_size: 'small',

            	style_formats: [
            		{title: 'Bold text', inline: 'b'},
            		{title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            		{title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            		{title: 'Example 1', inline: 'span', classes: 'example1'},
            		{title: 'Example 2', inline: 'span', classes: 'example2'},
            		{title: 'Table styles'},
            		{title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
            	]
        });
        tinymce.init({
            selector:'.project_update_textarea',
            plugins: [
                "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
                		"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                		"table contextmenu directionality emoticons template textcolor paste fullpage textcolor moxiemanager"
            ],
            toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
            	toolbar2: "cut copy paste pastetext | searchreplace | bullist numlist | outdent indent blockquote | undo redo | insertfile link unlink anchor image media code | forecolor backcolor",
            	toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft preview",

            	menubar: false,
            	toolbar_items_size: 'small',

            	style_formats: [
            		{title: 'Bold text', inline: 'b'},
            		{title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            		{title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            		{title: 'Example 1', inline: 'span', classes: 'example1'},
            		{title: 'Example 2', inline: 'span', classes: 'example2'},
            		{title: 'Table styles'},
            		{title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
            	]
        });
        

    });

</script>
