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
<!--<link href="/css/tinyeditor.css" rel="stylesheet">-->
<!--<script language=JavaScript src='/scripts/jquery/jquery-1.3.2.js'></script>-->
<!--<script language=JavaScript src='/scripts/jquery/sliding.form.js'></script>-->
<!--<script language=JavaScript src='/scripts/jquery/jquery-ui-custom.js'></script>-->
<!--<script language=JavaScript src='/scripts/jquery/jquery.preimage.js'></script>-->
<!--<script language=JavaScript src='/scripts/jquery/tiny.editor.js'></script>-->
<!--<script language="JavaScript" src="/scripts/jquery/tiny.editor.js" js="text/javascript"></script>-->
<!--<script language="JavaScript" src="/scripts/jquery/tiny_mce/tiny_mce_src.js" js="text/javascript"></script>-->
<!--<script language="JavaScript" src="/scripts/jquery/tiny_mce/jquery.tinymce.js" js="text/javascript"></script>-->
<!--<script language="JavaScript" src="/scripts/jquery/tinymce/tinymce.min.js" js="text/javascript"></script>-->
<!--<script language="JavaScript" src="/scripts/jquery/tinymce/jquery.tinymce.min.js" js="text/javascript"></script>-->

<!--<script language="JavaScript" src="/scripts/jquery/tiny_mce/jquery.tinymce.js" js="text/javascript"></script>-->
<!--<script language="JavaScript" src="/scripts/jquery/tiny_mce/plugins/moxiemanager/editor_plugin.js" js="text/javascript"></script>-->

<!--<link href="/scripts/style/tinyeditor.css" rel="stylesheet" type="text/css">-->
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


    .error{
        border: 1px solid #ff0000;
        background-color: #ff0000;
    }

</style>

<script language="javascript">

var regNotEmptyAlpha = /^([\w]+)/i;
var regNotEmptyNumbers = /^([\d]+)$/i;
var regNotEmptyAlphaWS = /^([\w\s]+)/i;
var regNotEmptyAlphaNumeric = /^([\w\d]+)/i;
var regNotEmptyAlphaNumericWS = /^([\w\d\s]+)/i;
var regZipCode = /^\d{5}(?:[-\s]\d{4})?$/i;
var regPhone = /^((((\(\d{3}\))|(\d{3}-))\d{3}-\d{4})|(\+?\d{2}((-| )\d{1,8}){1,5}))(( x| ext)\d{1,5}){0,1}$/i;
var regUrl = /^(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(:[a-zA-Z0-9]*)?\/?([a-zA-Z0-9\-\._\?\,\'\/\\\+&amp;%\$#\=~])*$/i;
var err_status = false;


$(document).ready(function()
{



    // form validation
    $("#formElem").submit(function(){

        err_status = false;
        $(".error").each(function(){
            $(this).removeClass("error");
        });


        // check 1st tab field and if err - focus this tab
        if ($("#name").val() == '' || !$("#name").val().match(regNotEmptyAlphaNumericWS)) {
            $("#name").addClass("error");
            err_status = true;
        } else {
            $("#name").removeClass("error");
        }
        if ($("#tax_company_name").val() == '' || !$("#tax_company_name").val().match(regNotEmptyAlphaNumericWS)) {
            $("#tax_company_name").addClass("error");
            err_status = true;
        } else {
            $("#tax_company_name").removeClass("error");
        }
        if ($("#address").val() == '') {
            $("#address").addClass("error");
            err_status = true;
        } else {
            $("#address").removeClass("error");
        }
        if ($("#city").val() == '' || !$("#city").val().match(regNotEmptyAlphaWS)) {
            $("#city").addClass("error");
            err_status = true;
        } else {
            $("#city").removeClass("error");
        }
        if ($("#zip_code").val() == ''
//                || !$("#zip_code").val().match(regZipCode)
                ) {
            $("#zip_code").addClass("error");
            err_status = true;
        } else {
            $("#zip_code").removeClass("error");
        }
        if ($("#phone").val() == '' || !$("#phone").val().match(regPhone)) {
            $("#phone").addClass("error");
            err_status = true;
        } else {
            $("#phone").removeClass("error");
        }

        if (err_status) {
            $("#p_account").children('a').click();
            return false;
        }


        // check 2nd tab field and if err - focus this tab
        if ( $("#username").val() == '' || !$("#username").val().match(regNotEmptyAlphaNumeric) || checkCampaignUrl($("#username").val()) )  {
            $("#username").addClass("error");
            err_status = true;
        } else {
            $("#username").removeClass("error");
        }

        var mce_cont = tinyMCE.activeEditor.getBody();

        if ($(mce_cont).html() == '<p><br data-mce-bogus="1"></p>' && $(mce_cont).text() == '') {
            $("#campaign_basic").addClass("error");
            err_status = true;
        } else {
            $("#campaign_basic").removeClass("error");
        }
        if ($("#project_title").val() == '' || !$("#project_title").val().match(regNotEmptyAlphaWS)) {
            $("#project_title").addClass("error");
            err_status = true;
        } else {
            $("#project_title").removeClass("error");
        }
        if ($("#project_short_description").val() == '' || !$("#project_short_description").val().match(regNotEmptyAlphaWS)) {
            $("#project_short_description").addClass("error");
            err_status = true;
        } else {
            $("#project_short_description").removeClass("error");
        }
        if ($("#founddrasing_goal").val() == '' || parseInt($("#founddrasing_goal").val()) != parseFloat($("#founddrasing_goal").val()) || !$("#founddrasing_goal").val().match(regNotEmptyNumbers) ) {
            $("#founddrasing_goal").addClass("error");
            err_status = true;
        } else {
            $("#founddrasing_goal").removeClass("error");
        }

        if ( $("#deadline_type_value").val() == 'time_period' ){
            if ($("#time_period").val() == '' || parseInt($("#time_period").val()) != parseFloat($("#time_period").val()) ||
                    !$("#time_period").val().match(regNotEmptyNumbers)) {
                $("#time_period").addClass("error");
                err_status = true;
            } else {
                $("#time_period").removeClass("error");
            }
        }
        if ( $("#deadline_type_value").val() == 'certain_date'){
            if ($("#certain_date").val() == '' ) {
                $("#certain_date").addClass("error");
                err_status = true;
            } else {
                $("#certain_date").removeClass("error");
            }
        }

        if (err_status) {
            $("#p_projectDetail").children('a').click();
            return false;
        }




        // check 3nd tab field and if err - focus this tab

        if ($("#url").val() !== ''){
            if (!$("#url").val().match(regUrl)) {
                $("#url").addClass("error");
                err_status = true;
            } else {
                $("#url").removeClass("error");
            }
        }
        if ($("#facebook_url").val() !== '') {
            alert($("#facebook_url").val().indexOf('facebook.com'));
            if ($("#facebook_url").val().indexOf('facebook.com') == -1 || !$("#facebook_url").val().match(regUrl)) {
                $("#facebook_url").addClass("error");
                err_status = true;
            } else {
                $("#facebook_url").removeClass("error");
            }
        }
        if ($("#twitter_url").val() !== '') {
            if ($("#twitter_url").val().indexOf('twitter.com') == -1 || !$("#twitter_url").val().match(regUrl)) {
                $("#twitter_url").addClass("error");
                err_status = true;
            } else {
                $("#twitter_url").removeClass("error");
            }
        }

        if($('#logo').val() !== ''){
            var ext = $('#logo').val().split('.').pop().toLowerCase();
            if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                $("#logo").addClass("error");
                err_status = true;
            } else {
                $("#logo").removeClass("error");
            }
        }

        if ($("input:radio[name='banner_type']:checked").val() == 0 && $('#banner').val() !== '') {
            var ext = $('#banner').val().split('.').pop().toLowerCase();
            if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                $("#banner").addClass("error");
                err_status = true;
            } else {
                $("#banner").removeClass("error");
            }
        }

        if (err_status) {
            $("#p_projectEdit").children('a').click();
            return false;
        }

        // check 4d tab field and if err - focus this tab

        if ($("#pin_value").val() == '' ) {
            $("#pin_value").addClass("error");
            err_status = true;
        } else {
            $("#pin_value").removeClass("error");
        }

        if ( !$("[name='agree_terms']").is(':checked') ) {
            $("[name='agree_terms']").addClass("error");
            err_status = true;
        } else {
            $("[name='agree_terms']").removeClass("error");
        }

        if (err_status) {
            $("#p_confirmation").children('a').click();
            return false;
        }


        return true;
    });

//    $("#phone").mask("(999) 999-9999");

    // load state list
    $("#country").change(function(){
        $.ajax({
            type: "POST",
            async: false,
            url: "/ajaxprocessors",
            dataType: 'json',
            data: {do:'stateList', country_id: $("#country").val()}
        })
        .done(function(result){
                    $("#states_box").empty();
                    $("#states_box").append(result.data);
        });

    });

    /* == == == == == == == == == == == == == == == == == == == == == == ==*/
    tinymce.PluginManager.load('moxiemanager', '/scripts/jquery/tinymce/plugins/moxiemanager/plugin.js');

    tinymce.init({
        selector: '.campaign_basic',
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


    $('.file').preimage();

    $(".partial_save").click(function(){
//        alert(this.id);
        $('#formElem').append(
            '<input type="hidden" name="registration_integrity" value="partial_registration" />'
        );
    });

});

function checkCampaignUrl(uname){
    var rstatus = false;

    $.ajax({
        type: "POST",
        async: false,
        url: "/ajaxprocessors.php",
        dataType: 'json',
        data: {do: 'checkCampaignName', name: uname }
    })
            .done(function (result) {
                rstatus =  result.status;
            });

    return rstatus;

}

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
        url = url.replace("http://","");
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
    } else if (ImageExistFromUrl(url)) {
        return url;
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

function ImageExistFromUrl(url)
{
    var img = new Image();
    img.src = url;
    if (img.height != 0) {
        return true
    } else {
        return false;
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
<!--        <option value="Other">Other </option>-->
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

    <? #if ($user_details['tax_account_type']) { ?>
    <div class="account-row">
        <label><?=MSG_COMPANY_NAME;?> *</label>
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
        <?
        #include 'includes/npgeocode_user.php';
        ?>

        <input type ="hidden" name="lat" id="lat" value= "<?=(isset($user_details['lat']))?$user_details['lat']:'';?>" />
        <input type ="hidden" name="lng" id="lng" value= "<?=(isset($user_details['lng']))?$user_details['lng']:'';?>" />
    </div>

    <div class="account-row phone">
        <label><?=MSG_PHONE;?> *</label>


            <input name="phone" type="text" id="phone" placeholder="(xxx)xxx-xxxx" value="<?=(isset($user_details['phone']))?$user_details['phone']:'';?>" size="25" />

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
            <input type="button" onclick="nextStepShow('p_account')" value="<?=MSG_NEXT?>" class="next_btn" />    </div>
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
<!--            --><?//=$project_country?>
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
         <!--   <span><?/*=MSG_CREATE_PROJECT_CAMPAIGN_BASIC_EXPLANATION;*/?></span>-->
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
        <? if ( defined('IN_ADMIN') && IN_ADMIN == 1) { ?>
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
        <div class="account-tab">
                <div class="next">
                    <input name="form_register_proceed" type="submit" id="form_register_proceed_details"
                           value="<?=MSG_SAVE_CAMPAIGN?>" class="save_btn partial_save"/>
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
        <!--               <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1" />-->
        <!--               <img src="themes/--><?//=$setts['default_theme'];?><!--/img/pixel.gif" width="1" height="1" />-->
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
                <span style="cursor: pointer;" onclick="clearLogoContent()">Clear</span>
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
            <?php elseif(isset($user_details['banner']) && $user_details['banner']): ?>
                <input type="hidden" name="valid_banner_image" id="valid_banner_image" value="<?php echo $user_details['banner']; ?>"/>
                <img src="<?php echo $user_details['banner']; ?>" />
            <?php endif; ?>
            <input class="file" name="banner" id="banner" type='file' multiple title="banner file"/>
            <span style="cursor: pointer" onclick="clearBannerContent()">Clear</span>
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
                    <input type="button" onclick="prevStepShow('p_projectEdit')"  value="<?=MSG_PREV?>" class="next_btn" />
                    <input type="button" onclick="nextStepShow('p_projectEdit')" value="<?=MSG_NEXT?>" class="next_btn" />
                </div>
            </div>

    </div>

    </div>
</fieldset>

<fieldset class="step">
    <div class="tabs">
        <div class="account-tab">
        <? if ((!defined('IN_ADMIN') || IN_ADMIN != 1) && (!isset($edit_user) || !$edit_user ) ) { ?>
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
        <?=(isset($signup_voucher_box))?$signup_voucher_box:'';?>
            <br />
        <?=(isset($registration_terms_box))?$registration_terms_box:'';?>
            <input name="form_register_proceed" type="submit"
                   id="form_register_proceed_save" value="<?=MSG_SAVE_CAMPAIGN?>" class="save_btn partial_save"/>
        <input name="form_register_proceed" type="submit" id="form_register_proceed"
               value="<?=(isset($proceed_button))?$proceed_button:'';?>"/>
    </div>
    </div>
</fieldset>
</form>
</div>


