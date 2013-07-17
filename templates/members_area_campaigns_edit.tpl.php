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

function nextStepShow(id){
    $("#"+id).next().find("a").first().click();
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

function deletePitch(object)
{
    var pitches = parseInt($("#pitches_number").val());
    $("#pitches_number").val(pitches - 1);
    $(object).parent().remove();
}


var countOfPitch = <?php if (isset($pitches) && is_array($pitches)) echo count($pitches); else { ?>0<?php } ?>;
function addPitch(){
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


}

</script>
<div class="editCampaigns">
<h2>Edit Campaigns</h2>
<div id="wrapper">

<div id="navigation" style="display:none;">
    <ul>

        <li id="p_projectDetail" class="selected">
            <a href="#">Project Details</a>
        </li>
        <li id="p_projectEdit">
            <a href="#">Project Edit</a>
        </li>
        <li id="p_projectPitch">
            <a href="#">Pitch</a>
        </li>
    </ul>
</div>
<div id="steps">
<form action="/campaigns,page,edit,section,<?=$campaign['user_id'];?>,campaign_id,members_area" method="post" name="registration_form" enctype="multipart/form-data" id="formElem" >
<input type="hidden" name="operation" value="submit">
<input type="hidden" name="do" value="<?=$do;?>">
<input type="hidden" name="user_id" value="<?=$campaign['user_id'];?>">
<input type="hidden" name="name" value="<?=$campaign['name'];?>">
<input type="hidden" name="edit_refresh" value="0">
<input type="hidden" name="generated_pin" value="<?=$generated_pin;?>">


<fieldset class="step">
    <div class="tabs">
        <h4><?=MSG_PROJECT_ACCOUNT_DETAILS; ?></h4>
        <!--                <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1">-->
        <!--                <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1">-->
        <div class="account-tab">
            <div class="account-row">
                <label> <?=MSG_CREATE_PROJECT_URL;?> *</label>
                <input name="username" type="text" id="username"
                       value="<?php echo isset($campaign["username"]) ? $campaign["username"] : ''; ?>"
                       size="40" maxlength="30" onchange="check_username(this);" placeholder="<?=MSG_ENTER_PROJECTURL;?>"/>
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
            <div class="account-row">
                <label><?=MSG_FUNDING_TYPE;?> *</label>
                <!--            <input type="text" name="founddrasing_goal" value="500" id="founddrasing_goal" >-->
                <!--            <div class="clear"></div>-->
                <div class="radio">
                    <input type="radio" name="funding_type" value="flexible"
                        <?php echo (isset($campaign["funding_type"]) && ($campaign["funding_type"] == "flexible")) ? "checked" : ''; ?>>
                    <label><?=MSG_FUNDING_TYPE_FLEXIBLE?></label>
                </div>
                <div class="radio">
                    <input type="radio" name="funding_type" value="fixed" checked="checked"
                        <?php echo (isset($campaign["funding_type"]) && ($campaign["funding_type"] == "fixed")) ? "checked" : ''; ?>>
                    <label><?=MSG_FUNDING_TYPE_FIXED?></label>
                </div>
            </div>
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
                <input type="button" onclick="nextStepShow('p_projectDetail')" value="Next" />
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
                    <input type="radio" class="banner_type" name="banner_type" value="0" checked="checked" ><label>Baner Image</label>
                    <div class="clear"></div>
                    <input type="radio" class="banner_type" name="banner_type" value="1" ><label>Video(Youtube or Vimeo)</label>
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



            <div class="account-row">
                <h5> Pitch text </h5>
                <div class="pitch"> <textarea rows="5" cols="30" name="pitch_text" id="pitch_text"><?php echo isset($campaign["pitch_text"]) ? $campaign["pitch_text"] : ''; ?></textarea> </div>
                <span>*user has to be able to edit this until they have received donations.</span>
            </div>
            <div class="next">
                <input type="button" onclick="nextStepShow('p_projectEdit')" value="Next" />
            </div>

        </div>

    </div>
</fieldset>

<fieldset class="step">
    <div class="tabs pitch-tab">
        <div class="pitch-add">
            <input type="hidden" name="pitches_number" id="pitches_number" value="<?php echo (isset($pitches) ? count($pitches) : '0') ?>">
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
                <?php foreach ($pitches as $key => $pitch): ?>
                    <div class="pitch-content">
                        <input type="hidden" name="pitch_id[<?php echo $key; ?>]" value="<?php echo isset($pitch['id']) ? $pitch['id'] : ''; ?>">
                        <div class="account-row">
                            <label>Amount *</label>
                            <input id="pitch[<?php echo $key; ?>][0]" type="text" placeholder="Amoun" name="pitch_amoun[<?php echo $key; ?>]"
                                value="<?php echo isset($pitch['amoun']) ? $pitch['amoun'] : ''; ?>">
                        </div>
                        <div class="account-row">
                            <label>Name *</label>
                            <input id="pitch[<?php echo $key; ?>][1]" type="text" placeholder="Name" name="pitch_name[<?php echo $key; ?>]"
                                   value="<?php echo isset($pitch['name']) ? $pitch['name'] : ''; ?>">
                        </div>
                        <div class="account-row">
                            <label>Description *</label>
                            <textarea id="pitch[<?php echo $key; ?>][2]" rows="5" cols="3" name="pitch_description[<?php echo $key; ?>]"><?php echo isset($pitch['description']) ? $pitch['description'] : ''; ?></textarea>
                        </div>
                        <br>
                        <input type="button" value="Delete" class="removePitchButton" onclick="deletePitch(this)">
                    </div>
                <?php endforeach; ?>
            </div>

<!--            <div class="next">-->
<!--                <input type="button" onclick="nextStepShow('p_projectPitch')" value="Next" />-->
<!--            </div>-->
        </div>
    </div>
    <input name="form_register_proceed" type="submit" id="form_register_proceed" value="Save changes" />
</fieldset>
</form>
</div>
</div>
</div>
