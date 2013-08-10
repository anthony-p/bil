<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<link href="/css/cupertino/jquery-ui-1.10.3.custom.css" rel="stylesheet">

<link href="css/tabs-style.css" rel="stylesheet">
<link href="/css/tinyeditor.css" rel="stylesheet">
<script language=JavaScript src='/scripts/jquery/jquery-1.3.2.js'></script>
<script language=JavaScript src='/scripts/jquery/sliding.form.js'></script>
<script language=JavaScript src='/scripts/jquery/jquery-ui-custom.js'></script>
<script language=JavaScript src='/scripts/jquery/jquery.preimage.js'></script>
<script language=JavaScript src='/scripts/jquery/tiny.editor.js'></script>
<style>
    /*.prev_container{*/
        /*overflow: auto;*/
        /*width: 300px;*/
        /*height: 100%;*/
    /*}*/

    /*.prev_thumb{*/
        /*margin: 10px;*/
        /*height: 100px;*/
    /*}*/
</style>

<script language="javascript">
$(document).ready(function()
{
    $('.file').preimage();

    /* == == == == == == == == == == == == == == == == == == == == == == ==*/
//    var editor = new TINY.editor.edit('editor', {
//        id: 'pitch_text',
//        width: 584,
//        height: 175,
//        cssclass: 'tinyeditor',
//        controlclass: 'tinyeditor-control',
//        rowclass: 'tinyeditor-header',
//        dividerclass: 'tinyeditor-divider',
//        controls: ['bold', 'italic', 'underline', 'strikethrough', '|', 'subscript', 'superscript', '|',
//            'orderedlist', 'unorderedlist', '|', 'outdent', 'indent', '|', 'leftalign',
//            'centeralign', 'rightalign', 'blockjustify', '|', 'unformat', '|', 'undo', 'redo', 'n',
//            'font', 'size', 'style', '|', 'image', 'hr', 'link', 'unlink', '|', 'print'],
//        footer: true,
//        fonts: ['Verdana','Arial','Georgia','Trebuchet MS'],
//        xhtml: true,
//        cssfile: 'custom.css',
//        bodyid: 'editor',
//        footerclass: 'tinyeditor-footer',
//        toggle: {text: 'source', activetext: 'wysiwyg', cssclass: 'toggle'},
//        resize: {cssclass: 'resize'}
//    });
    /* == == == == == == == == == == == == == == == == == == == == == == ==*/
});

function checkEmail() {
    if (document.registration_form.email_check.value==document.registration_form.email.value) document.registration_form.email_img.style.display="inline";
    else document.registration_form.email_img.style.display="none";
}

function checkPass() {
    if (document.registration_form.password.value==document.registration_form.password2.value) document.registration_form.pass_img.style.display="inline";
    else document.registration_form.pass_img.style.display="none";
}

function form_submit() {
    document.registration_form.operation.value = '';
    document.registration_form.edit_refresh.value = '1';
    document.registration_form.submit();
}

function copy_email_value() {
    document.registration_form.email_check.value = document.registration_form.email.value;
}

function copy_password_value() {
    document.registration_form.password2.value = document.registration_form.password.value;
}

function check_username(username)
{
    var xmlHttp;

    if (window.XMLHttpRequest)
    {
        var xmlHttp = new XMLHttpRequest();

        if (XMLHttpRequest.overrideMimeType)
        {
            xmlHttp.overrideMimeType('text/xml');
        }
    }
    else if (window.ActiveXObject)
    {
        try
        {
            var xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch(e)
        {
            try
            {
                var xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch(e) {}
        }
    }
    else
    {
        alert('Your browser does not support XMLHTTP!');
        return false;
    }

    var uname    = username.value;
    var url    = 'npcheck_username.php';
    var action    = url + '?username=' + encodeURIComponent(uname);

    if (uname != '')
    {
        xmlHttp.onreadystatechange = function() { showResult(xmlHttp, uname); };
        xmlHttp.open("GET", action, true);
        xmlHttp.send(null);
    }
}

function showResult(xmlHttp, id)
{
    if (xmlHttp.readyState == 4)
    {
        var response = xmlHttp.responseText;

        usernameResult.innerHTML = unescape(response);
    }
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

    <?php if (isset($user_details['video_url']) && $user_details['video_url']): ?>
    $("#vide_select_block").css("display","inline");
    $("#banner").css("display","none");
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
        id = url.split('/')[1];
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
    } else {
        throw new Error('Unrecognised URL');
    }

    function processYouTube(id) {
        if (!id) {
            throw new Error('Unsupported YouTube URL');
        }

        return "http://img.youtube.com/vi/"+id+"/0.jpg";
    }
}

/**
 * Switch Next Form Panel
 * @param id
 */
function nextStepShow(id){
    $("#"+id).next().find("a").first().click();
}
function prevStepShow(id){
    $("#"+id).prev().find("a").first().click();
}

/**
 *
 */

var countOfPitch = <?php if (isset($user_details["pitches_number"])) echo $user_details["pitches_number"]; else { ?>0<?php } ?>;
/*function addPitch(){
//    alert(2);
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
/**
 * <div id="pitch_template">
 <div style="display: block"><span>Amount *</span> <input id="amoun" type="text" value="" placeholder="" /> </div>
 <div style="display: block"><span>Nmae *</span> <input id="name" type="text" value="" placeholder="" /> </div>
 <div style="display: block"><span>Description *</span> <textarea id="description"rows="5" cols="3"></textarea> </div>
 </div>
 */
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
<?=$banned_email_output;?>
<?=$display_formcheck_errors;?>
<?=$check_voucher_message;?>

<div id="wrapper">
<div id="navigation" style="display:none;">
    <ul>
        <li id="p_account" class="selected">
            <a href="#">Account</a>
        </li>
        <li id="p_projectDetail">
            <a href="#">Details</a>
        </li>
        <li id="p_projectEdit">
            <a href="#">Enhancements</a>
        </li>
       <!-- <li id="p_projectPitch">
            <a href="#">Pitch</a>
        </li>-->
        <li id="p_confirmation">
            <a href="#">Confirmation</a>
        </li>

    </ul>
</div>
<div id="steps">
<form action="<?=$register_post_url;?>" method="post" name="registration_form" enctype="multipart/form-data" id="formElem" >
<input type="hidden" name="operation" value="submit">
<input type="hidden" name="do" value="<?=$do;?>">
<input type="hidden" name="user_id" value="<?=$user_details['user_id'];?>">
<input type="hidden" name="edit_refresh" value="0">
<input type="hidden" name="generated_pin" value="<?=$generated_pin;?>">
<fieldset class="step">
    <div class="tabs">
        <h4><?=MSG_MAIN_DETAILS;?></h4>
<div class="account-tab">
    <!--                <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1" />-->
    <!--                <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1" />-->
    <div class="account-row">
        <label><?=MSG_REGISTER_AS;?></label>
        <select name="orgtype" id="orgtype" size="1">
        <option selected="selected"><? echo ($user_details['orgtype']);?></option>
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
        <input type="hidden" name="tax_account_type" type="radio" value="1" />
        <span><?=MSG_REGISTER_AS_DESC;?></span>
    </div>
    <div class="account-row">
        <label><?=MSG_FULL_NAME;?> *</label>
        <input name="name" type="text" id="name" value="<?=$user_details['name'];?>" size="40" />
        <input name="affiliate" type="hidden" id="affiliate" value="<?=$_POST['affiliate'];?>" size="40" />
        <span><?=MSG_FULL_NAME_EXPL;?> *</span>
    </div>

    <? #if ($user_details['tax_account_type']) { ?>
    <div class="account-row">
        <label><?=MSG_COMPANY_NAME;?> *</label>
        <input name="tax_company_name" type="text" class="contentfont" id="tax_company_name" value="<?=$user_details['tax_company_name'];?>" size="40" />
        <span><?=MSG_COMPANY_NAME_DESC;?></span>
    </div>
    <? #} ?>
    <div class="account-row">
        <label><?=MSG_ADDRESS;?> *</label>
        <input name="address" type="text" id="address" value="<?=$user_details['address'];?>" size="40" />
        <span><?=MSG_ADDRESS_EXPL;?></span>
    </div>
    <div class="account-row">
        <label><?=MSG_CITY;?> *</label>
        <input name="city" type="text" id="city" value="<?=$user_details['city'];?>" size="25" />
        <span><?=MSG_CITY_EXPL;?></span>
    </div>
    <!---->
    <!--                        <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1" />-->
    <!--                        <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1" />-->
    <!--                        <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1" />-->

    <div class="account-row">
        <label><?=MSG_ZIP_CODE;?> *</label>
        <input name="zip_code" type="text" id="zip_code" value="<?=$user_details['zip_code'];?>" size="15" />
        <div class="clear"></div><br/>
        <label><?=MSG_COUNTRY;?> *</label>
        <?=$country_dropdown;?>
        <div class="clear"></div><br/>
        <label><?=MSG_STATE;?> *</label>
        <?=$state_box;?>
        <input type ="hidden" name="geoaddress" id="geoaddress" value= "<?=$user_details['address'] .",". $user_details['city'] .",". $user_details['zip_code'];?>"/>
        <?
        #include 'includes/npgeocode_user.php';
        ?>

        <input type ="hidden" name="lat" id="lat" value= "<?=$user_details['lat'];?>" />
        <input type ="hidden" name="lng" id="lng" value= "<?=$user_details['lng'];?>" />
    </div>


    <!--                        <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1" />-->
    <!--                        <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1" />-->

    <div class="account-row phone">
        <label><?=MSG_PHONE;?> *</label>

        <? if ($edit_user == 1)	{ ?>
            <input name="phone" type="text" id="phone" value="<?=$user_details['phone'];?>" size="25" />
        <? } else { ?>
            ( <input name="phone_a" type="text" id="phone_a" value="<?=$user_details['phone_a'];?>" size="5" /> )
            <input name="phone_b" type="text" id="phone_b" value="<?=$user_details['phone_b'];?>" size="25" />
        <? } ?>
        <span><?=MSG_PHONE_EXPL;?></span>
        <div class="clear"></div>
        <?=$birthdate_box;?>
    </div>
    <div class="account-row">
        <label><?=MSG_PG_PAYPAL_EMAIL_ADDRESS;?></label>
        <input name="pg_paypal_email" type="text" id="pg_paypal_email"
               value="<?=$user_details['pg_paypal_email'];?>" size="40" />
        <span><?=MSG_PG_PAYPAL_EMAIL_ADDRESS_EXPL;?></span>
    </div>
    <div class="account-row">
        <label><?=MSG_PG_PAYPAL_FIRST_NAME;?></label>
        <input name="pg_paypal_first_name" type="text" id="pg_paypal_first_name"
               value="<?=$user_details['pg_paypal_first_name'];?>" size="40" />
        <span><?=MSG_PG_PAYPAL_FIRST_NAME_EXPL;?></span>
    </div>
    <div class="account-row">
        <label><?=MSG_PG_PAYPAL_LAST_NAME;?></label>
        <input name="pg_paypal_last_name" type="text" id="pg_paypal_last_name"
               value="<?=$user_details['pg_paypal_last_name'];?>" size="40" />
        <span><?=MSG_PG_PAYPAL_LAST_NAME_EXPL;?></span>
    </div>
    <div class="next">
        <div class="right">
            <input type="button" onclick="nextStepShow('p_account')" value="<?=MSG_NEXT?>" class="next_btn" />    </div>
        </div>
    </div>


    </div>
</fieldset>

<fieldset class="step">
    <div class="tabs">
        <h4><?=MSG_PROJECT_ACCOUNT_DETAILS; ?></h4>
        <!--                <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1">-->
        <!--                <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1">-->
        <div class="account-tab">
            <div class="account-row">
            <label> <?=MSG_CREATE_PROJECT_URL;?> *</label>
            <input name="username" type="text" id="username"
                   value="<?php echo isset($user_details['username']) ? $user_details['username'] : '' ?>"
                   size="40" maxlength="30" onchange="check_username(this);" placeholder="<?=MSG_ENTER_PROJECTURL;?>"/>
            <span><?=MSG_PROJECTURL_EXPLANATION;?></span>
        </div>
        <div class="account-row">
            <label><?=MSG_CREATE_PROJECT_CHOOSE_CATEGORY;?> *</label>
<!--            --><?//=$project_country?>
            <select name="project_category" id="project_category">
                <?php foreach ($project_category as $key => $category): ?>
                    <option value="<?php echo $key; ?>"
                        <?php if (isset($user_details['project_category']) && $user_details['project_category'] == $key) echo 'selected' ?>>
                        <?php echo $category; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="account-row">
            <label><?=MSG_CREATE_PROJECT_CAMPAIGN_BASIC;?> *</label>
            <textarea rows="5" cols="60" name="campaign_basic" id="campaign_basic"><?php echo isset($user_details['campaign_basic']) ? $user_details['campaign_basic'] : '' ?></textarea>
            <span><?=MSG_CREATE_PROJECT_CAMPAIGN_BASIC_EXPLANATION;?></span>
        </div>
        <div class="account-row">
            <label><?=MSG_CREATE_PROJECT_TITLE;?> *</label>
            <input type="text" name="project_title"
                   value="<?php echo isset($user_details['project_title']) ? $user_details['project_title'] : '' ?>"
                   id="project_title" maxlength="80" size="40" >
            <span><?=MSG_CREATE_PROJECT_CAMPAIGN_BASIC_EXPLANATION;?></span>
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
     <!--   <div class="account-row">
            <label><?/*=MSG_FUNDING_TYPE;*/?> *</label>-->
<!--            <input type="text" name="founddrasing_goal" value="500" id="founddrasing_goal" >-->
<!--            <div class="clear"></div>-->
           <!-- <div class="radio">
                <input type="radio" name="funding_type" value="flexible" <?php /*if (isset($user_details["funding_type"]) && $user_details["funding_type"] == "flexible") echo 'checked="checked"' */?>>
                <label><?/*=MSG_FUNDING_TYPE_FLEXIBLE*/?></label>
            </div>
            <div class="radio"><input type="radio" name="funding_type" value="fixed" <?php /*if (!isset($user_details["funding_type"]) || $user_details["funding_type"] != "flexible") echo 'checked="checked"' */?> ><label><?/*=MSG_FUNDING_TYPE_FIXED*/?></label></div>
        </div>-->
        <div class="account-row deadline">
            <label><?="Deadline";?> </label>

<!--            --><?php //var_dump($user_details['deadline_type_value']); ?>
                        <span>
                        <input type="hidden" name="deadline_type_value" id="deadline_type_value"
                               value="<?php echo (isset($user_details['deadline_type_value'])) ? $user_details['deadline_type_value'] : '' ?>">
                        </span>
                        <span>
                      <span class="radio-span"> <input type="radio" name="deadline_type" value="deadline_type" onclick="changeDeadlineType(jQuery(this),'time_period')"
                              <?php echo (isset($user_details['deadline_type_value']) && $user_details['deadline_type_value'] == "time_period") ? 'checked="checked"' : '' ?>>
                       # of days</span>
                       <input type="text" name="time_period" id="time_period" disabled="disabled"
                              value="<?php echo isset($user_details['time_period']) ? $user_details['time_period'] : '' ?>">

                         </span>

                          <span>
                              <span class="radio-span">
                              <input type="radio" name="deadline_type" value="deadline_type" onclick="changeDeadlineType(jQuery(this),'certain_date')"
                                  <?php echo (isset($user_details['deadline_type_value']) && $user_details['deadline_type_value'] == "certain_date") ? 'checked="checked"' : '' ?>>
                      (date)
                         </span>
                      <input type="text" name="certain_date" id="certain_date" disabled="disabled"
                             value="<?php echo isset($user_details['certain_date']) ? $user_details['certain_date'] : '' ?>">

                         </span>
        </div>
        </div>
<!--        <h4>--><?//=$custom_sections_table;?><!--</h4>-->
        <? if (IN_ADMIN == 1) { ?>
            <div class="account-row taxt-settings">
                <h6><?=AMSG_PAYMENT_SETTINGS;?></h6>
                <!--                   <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1">-->
                <!--                   <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1">-->
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
       <!-- <h4><?/*=MSG_TAX_SETTINGS;*/?></h4>-->
            <!--              <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1" />-->
            <!--              <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1" />-->
        <div class="account-tab">
<!--            <div class="account-row">
                <label><?/*=MSG_TAX_REG_NUMBER;*/?></label>
                <input name="tax_reg_number" type="text" class="contentfont" id="tax_reg_number" value="<?/*=$user_details['tax_reg_number'];*/?>" size="40" />
                <span><?/*=MSG_TAX_REG_NUMBER_DESC;*/?></span>
            </div>-->

                <div class="next">
                    <div class="right">
                        <input type="button" onclick="prevStepShow('p_projectDetail')"  value="<?=MSG_PREV?>" class="next_btn" />
                        <input type="button" onclick="nextStepShow('p_projectDetail')" value="<?=MSG_NEXT?>" class="next_btn" />
                    </div>
                </div>
        </div>
        <? } ?>


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
            <input name="url" type="text" class="contentfont" id="url" value="<?=$user_details['url'];?>" size="40" />
            <span><?=MSG_WEBSITE_ADDRESS_INSTRUCTIONS2;?></span>
        </div>

        <div class="account-row">
            <label><?=MSG_FACEBOOK_PAGE_INSTRUCTIONS;?></label>
            <input name="facebook_url" type="text" class="contentfont" id="url" value="<?=$user_details['facebook_url'];?>" size="40" />
        </div>

        <div class="account-row">
            <label><?=MSG_TWITTER_PAGE_INSTRUCTIONS;?></label>
            <input name="twitter_url" type="text" class="contentfont" id="url" value="<?=$user_details['twitter_url'];?>" size="40" />
        </div>

        <h5><?=MSG_LOGO_DESC;?></h5>
        <!--               <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1" />-->
        <!--               <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1" />-->
        <div class="account-row">
            <?php if(isset($logo_image) && $logo_image): ?>
                <input type="hidden" name="valid_logo_image" id="valid_logo_image" value="<?php echo $logo_image; ?>"/>
                <img src="<?php echo $logo_image; ?>" />
            <?php endif; ?>
            <div id="MultiPowUpload_holder">
                <input class="file" name="logo" id="logo" type='file' multiple title="logo file"/>
            </div>

            <div id="serverresponse">
                <div id="prev_logo"></div>
                <span>For best results upload an image that is not more than 160 pixels wide.</span>
            </div>
        </div>
        <h5>Your Story (<span style="font-size: 8px">Tell potential contributors more about your campaign.)</span></h5>
        <!--                 <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1" />-->
        <!--                    <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1" />-->

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
            <?php endif; ?>
            <input class="file" name="banner" id="banner" type='file' multiple title="banner file"/>
            <div id="vide_select_block" style="display: none">
                <input type="text" name="video_url" id="video_url" value="">
                <input type="button" id="loadVideo" value="Get">
            </div>
            <div class="banners_list">
             <span> <?=MSG_UPLOAD_IMAGE_INFORMATION?>.</span>
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



     <!--   <div class="account-row">
            <h5> Pitch text </h5>
            <div class="pitch"><textarea rows="5" cols="30" name="pitch_text" id="pitch_text"><?php /*echo isset($user_details['pitch_text']) ? $user_details['pitch_text'] : '' */?></textarea> </div>
            <span>*user has to be able to edit this until they have received donations.</span>
        </div>-->

            <div class="next">
                <div class="right">
                    <input type="button" onclick="prevStepShow('p_projectEdit')"  value="<?=MSG_PREV?>" class="next_btn" />
                    <input type="button" onclick="nextStepShow('p_projectEdit')" value="<?=MSG_NEXT?>" class="next_btn" />
                </div>
            </div>

    </div>

    </div>
</fieldset>

<!--<fieldset class="step">
    <div class="tabs pitch-tab">
      <div class="pitch-add">
          <input type="hidden" name="pitches_number" id="pitches_number"
                 value="<?php /*echo (isset($user_details["pitches_number"]) ? $user_details["pitches_number"] : '0') */?>">
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
              <?php /*if (isset($user_details["pitch_amoun"])): */?>
                  <?php /*foreach ($user_details["pitch_amoun"] as $key => $pitch_amoun): */?>
                      <div class="pitch-content">
                          <div class="account-row">
                              <label>Amount *</label>
                              <input id="pitch[<?php /*echo $key; */?>][0]" type="text" placeholder="Amoun" name="pitch_amoun[<?php /*echo $key; */?>]"
                                     value="<?php /*echo isset($pitch_amoun) ? $pitch_amoun : ''; */?>">
                          </div>
                          <div class="account-row">
                              <label>Name *</label>
                              <input id="pitch[<?php /*echo $key; */?>][1]" type="text" placeholder="Name" name="pitch_name[<?php /*echo $key; */?>]"
                                     value="<?php /*echo isset($user_details['pitch_name'][$key]) ? $user_details['pitch_name'][$key] : ''; */?>">
                          </div>
                          <div class="account-row">
                              <label>Description *</label>
                              <textarea id="pitch[<?php /*echo $key; */?>][2]" rows="5" cols="3" name="pitch_description[<?php /*echo $key; */?>]"><?php /*echo isset($user_details['pitch_description'][$key]) ? $user_details['pitch_description'][$key] : ''; */?></textarea>
                          </div>
                          <br>
                          <input type="button" value="Delete" class="removePitchButton" onclick="deletePitch(this)">
                      </div>
                  <?php /*endforeach; */?>
              <?php /*endif; */?>
          </div>

          <div class="next">
              <input type="button" onclick="nextStepShow('p_projectPitch')" value="Next" />
          </div>
      </div>
    </div>
</fieldset>
--><fieldset class="step">
    <div class="tabs">
        <div class="account-tab">
        <? if (IN_ADMIN != 1 && !$edit_user) { ?>
            <!--                           <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1" />-->
            <!--                           <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1" />-->
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
        <?=$signup_voucher_box;?>
            <br />
        <?=$registration_terms_box;?>
        <input name="form_register_proceed" type="submit" id="form_register_proceed" value="<?=$proceed_button;?>"/>
    </div>
    </div>
</fieldset>
</form>
</div>


