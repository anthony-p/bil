/**
 * Created by Anastasia Batieva on 07.10.13.
 */

var err_msg = '';


function validateCampaignForm(form, messages) {
    $.validator.addMethod('phone', function(phone_number, element) {
            return this.optional(element) || phone_number.length > 9 &&
                phone_number.match(/^((\+)?[1-9]{1,2})?([-\s\.])?((\(\d{1,4}\))|\d{1,4})(([-\s\.])?[0-9]{1,12}){1,2}$/);
        });

    $.validator.addMethod('facebook', function(url, element) {
        return this.optional(element) || (url.indexOf('facebook.com') != -1);
    });
    $.validator.addMethod('twitter', function(url, element) {
        return this.optional(element) || (url.indexOf('twitter.com') != -1);
    });
    form.validate({
        rules: {
            name: "required",
            address: "required",
            city: "required",
            zip_code: "required",
            country: "required",
            state: "required",
            phone: "required",
            pg_paypal_email: {
                email:true
            },
            username: {
                required:true,
                nowhitespace:true,
                alphanumeric:true
            },
            project_title: {
                required:true,
                maxlength:80
            },
            project_short_description:{
                required:true,
                maxlength:160
            },
            founddrasing_goal: {
                required: true,
                digits: true,
                minlength: 1,
                min: 1
            },
            certain_date: {
                required:{
                    depends:function(element) {
                        return $('#deadline_type_date:checked');
                    }
                },
                date:true
            },
            time_period: {
              required:{
                  depends:function(element) {
                      return $('#deadline_type_days:checked');
                  }
              },
              digits:true,
              min: 1

            },
            url: {
                url:true
            },
            logo: {
                accept:"image/*"
            },
            banner: {
                accept:"image/*"
            },
            video_url: {
                url:true
            },
            facebook_url:{
                url:true,
                facebook:true
            },
            twitter_url:{
                url:true,
                twitter:true
            }
        },
        messages: {
            name: messages.name,
            tax_company_name: messages.tax_company_name,
            address: messages.address,
            city: messages.city,
            zipcode: messages.zipcode,
            phone: messages.phone,
            facebook_url: messages.facebook_url,
            certain_date: {
                date: messages.date_format

            },
            time_period: {
                digits: messages.days_format
            }

        },
        focusInvalid:true,
        invalidHandler: function(form, validator) {

            if (!validator.numberOfInvalids())
                return;
            var index_of_invalid_tab = $( "fieldset").index($(validator.errorList[0].element).parents('fieldset'));
            $("#navigation li:eq(" + index_of_invalid_tab + ")").find("a").first().click();

        }

    });
}
function goToSelectedTab() {
    var lastselectedtab = $("#last_selected_tab");
    if (lastselectedtab.val() != "") {
        var idTab = lastselectedtab.val();
        $("#" + idTab).find("a").first().click();
    }

}

function bannerTypeSelect(flag) {
    var banner = $("#banner");
    if (flag == "0") {
        $("#vide_select_block").css("display", "none");
        var videourl = $("#video_url");
        videourl.val("");
        banner.css("display", "inline");
        videourl.val("");
        $('#prev_banner').empty();
        $('.banners_list').hide();
    } else {
        $("#vide_select_block").css("display", "inline");
        banner.css("display", "none");
        banner.val("");
        banner.next('.clear-file-input').hide();
    }
}

function loadBannerVideo() {
    var url = $("#video_url").val();
    if (url === null) {
        return "";
    }
    var vURL = processURL(url);

    if (!vURL) return;
    setPrevImage(vURL);
}

/**
 *
 * @param vURL
 */
function setPrevImage(vURL) {

    var videothumb = $("#video_thumb");

    if (videothumb.length > 0) {
        videothumb.attr("src", vURL);
    } else {
        $("#prev_banner").append('<img width="180px" id="video_thumb" src="' + vURL + '" />');
    }
    $("#prev_banner > .prev_thumb").remove();
}

/**
 *
 * @param url
 * @returns {*}
 */
function processURL(url) {
    var id;
    if (url.indexOf('youtube.com') > -1) {
        results = url.match("[\\?&]v=([^&#]*)");
        id = ( results === null ) ? url : results[1];
        return processYouTube(id);
    } else if (url.indexOf('youtu.be') > -1) {
        id = url.split('/')[3];
        return processYouTube(id);
    } else if (url.indexOf('vimeo.com') > -1) {
        url = url.replace("http://", "");
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
            success: function (data) {
                setPrevImage(data[0].thumbnail_large);
            }
        });
    } else if (ImageExistFromUrl(url)) {
        return url;
    } else {
        throw new Error('Unrecognised URL');
    }


    function ImageExistFromUrl(url) {
        var img = new Image();
        img.src = url;
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

        return "http://img.youtube.com/vi/" + id + "/0.jpg";
    }
}
function changeDeadlineType(obj, id) {
    var timeperiod = $("#time_period");
    var certaindate = $("#certain_date");

    timeperiod.attr('disabled', 'disabled');
    timeperiod.val('');
    certaindate.attr('disabled', 'disabled');
    certaindate.val('');

    var input = $("#" + id);
    if (obj.attr('checked') == false) {
        input.attr('disabled', 'disabled');
    } else {
        input.removeAttr('disabled');
        $("#deadline_type_value").val(id);
    }
    timeperiod.removeClass('error');
    timeperiod.next('label').remove();
    certaindate.removeClass('error');
    certaindate.next('label').remove();
}
function projectUpdateComment() {
    /* Getting content */
    var wisiwyg = tinymce.get('project_update_textarea'),
        comment = wisiwyg.getContent({format : 'text'}).replace(/<\/?[^>]+>/gi, '').trim();

    /* If empty do nothing */
    if (!comment) {return false;} else {
        var data = 'add_project_updates=true' + '&project_id=' + $("#user_id_val").val() + '&comment=' + wisiwyg.getContent(),
            dialog = $("#confirm_dialog_box");
            dialog.html(window.messages.post_update_text);
            dialog.dialog({
            resizable: false,
            title: window.messages.post_update_title,
            height: 200,
            width: 500,
            modal: true,
            buttons: [
                {
                    text: window.messages.confirm_project_update_button,
                    click: function () {
                        $.ajax({
                            url: "/np_compaign_project.php",
                            type: "POST",
                            data: data,
                            success: function (response) {

                                var commentObj = $.parseJSON(response);
                                if (commentObj.response == true) {
                                    addProjectUpdateComment(commentObj.id);
                                }

                            },
                            error: function () {
                                console.log("failure");
                            }
                        });
                        $(this).dialog("close");
                    }
                },
                {
                    text: window.messages.cancel_project_update_button,
                    click: function () {
                        $(this).dialog("close");
                    }
                }
            ]
        });

    }
}

has_new_reward_form = false;

function projectRewardComment() {
    var data = 'add_project_rewards=true' + '&project_id=' + $("#user_id_val").val() + '&comment=' + $("#project_reward_textarea").val();
    $.ajax({
        url: "/np_compaign_project.php",
        type: "POST",
        data: data,
        success: function (response) {
            var commentObj = $.parseJSON(response);
            console.log(commentObj);
            if (commentObj.response == true) {
                addProjectRewardComment(commentObj.id);
            }
        },
        error: function () {
            console.log("failure");
        }
    });
}


function addProjectUpdateComment(id) {
    /* Getting content */
    var wisiwyg = tinymce.get('project_update_textarea'),
        comment = wisiwyg.getContent();

    /* Adding row with update text */
    $("#project_update_post_comments").prepend('<li id="project_update_comment_row' + id + '">' +
        '<p>' + comment + '</p>' +
        '<div class="delete_btn">' +
        '<span>delete</span></div></li>');
    /* Adding a delete trigger */
    $('#project_update_comment_row' + id).find('.delete_btn').on('click', function() { projectUpdateDelete(id)});
    /* Clearing content from tinymce */
    wisiwyg.setContent('');

}


function addProjectRewardComment(id) {
    var comment = $("#project_reward_textarea"),
        commentval = comment.val();
    $("#project_reward_post_comments").prepend('<li id="project_reward_comment_row' + id + '">' +
        '<p>' + commentval + '</p>' +
        '<div class="delete_btn delete_reward_comment">' +
        '<span>delete</span>' +
        '</div>' +
        '</li>');
    comment.val('');
}


function projectUpdateDelete(id) {
    var data = 'delete_project_updates=true' + '&updates_id=' + id,
        confirm_dialog_box = $('#confirm_dialog_box');

    confirm_dialog_box.html(window.messages.delete_update_text);
    confirm_dialog_box.dialog({
        resizable: false,
        title:window.messages.delete_update_title,
        height: 200,
        width: 400,
        modal: true,
        buttons: [
            {
                text: window.messages.confirm_delete_update_button,
                click: function () {

                    $.ajax({
                        url: "/np_compaign_project",
                        type: "POST",
                        data: data,
                        success: function (response) {
                            var commentObj = $.parseJSON(response);
                            if (commentObj.response == true) {
                                $("#project_update_comment_row" + id).remove();
                            }
                        },
                        error: function () {
                            console.log("failure");
                        }
                    });
                    $(this).dialog("close");
                }
            },
            {
                text: window.messages.confirm_cancel_update_button,
                click: function () {
                    $(this).dialog("close");
                }
            }]
    });


}

function confirmDeleteRewards(id) {

    var data = 'delete_project_updates=true' + '&updates_id=' + id;

    $("#confirm_dialog_box").dialog({
        resizable: false,
        height: 200,
        width: 400,
        modal: true,
        buttons: [
            {
                text: window.messages.confirm_delete_reward_button,
                click: function () {

                    is_new = $("#is_new_" + id).val();
                    if (is_new == '1') {
                        has_new_reward_form = false;
                        $("#reward_block_" + id).slideUp(750);
                    } else {
                        $.ajax({
                            url: "/np_compaign_project",
                            type: "POST",
                            async: false,
                            data: {delete_project_rewards: true, rewards_id: id},
                            success: function (response) {
                                response = $.parseJSON(response).response;
                                if (response == true) {
                                    $("#reward_block_" + id).slideUp(750);
                                } else {
                                    alert(response);
                                }
                            },
                            error: function () {
                                alert("Error");
                            }
                        });
                    }


                    $(this).dialog("close");
                }
            },
            {
                text: window.messages.confirm_cancel_reward_button,
                click: function () {
                    $(this).dialog("close");
                }
            }]
    });
    return false;
}
function deleteProjectReward(id) {

    confirmDeleteRewards(id);
    return false;

    is_new = $("#is_new_" + id).val();
    if (is_new == '1') {
        has_new_reward_form = false;
        $("#reward_block_" + id).slideUp(750);
    } else {
        $.ajax({
            url: "/np_compaign_project",
            type: "POST",
            data: {delete_project_rewards: true, rewards_id: id},
            success: function (response) {
                response = $.parseJSON(response).response;
                if (response == true) {
                    $("#reward_block_" + id).slideUp(750);
                } else {
                    alert(response);
                }
            },
            error: function () {
                alert("Error");
            }
        });
    }
}
function addNewRewardToProject(message, campaign_id) {

    if (!has_new_reward_form) {
        $.ajax({
            url: "/np_compaign_project",
            type: "POST",
            data: {addNewRewardToProject: true, has_new_reward_form: false, campaign_id: campaign_id},
            success: function (response) {
                response = $.parseJSON(response).response;
                if (response.substr(0, 4) == '<div') {
                    has_new_reward_form = true;
                    $("#rewards-section").append(response);
                } else {
                    alert(response);
                }
            },
            error: function () {
                alert("Error");
            }
        });
    } else {
        alert(message);
    }
}

function updateProjectReward(id) {
    if (validateProjectReward(id)) {
        $.ajax({
            url: "/np_compaign_project",
            type: "POST",
            data: {update_project_rewards: true, rewards_id: id, reward_amount: $("#reward_amount_" + id).val(), reward_name: $("#reward_name_" + id).val(), reward_short_description: $("#reward_short_description_" + id).val(), reward_description: tinymce.get('reward_description_' + id).getContent(), reward_available_number: $("#reward_available_number_" + id).val(), reward_estimated_delivery_date: $("#reward_estimated_delivery_date_" + id).val(), reward_available_number: $("#reward_available_number_" + id).val(), reward_shipping_address_required: $("#reward_shipping_address_required_" + id).is(':checked')},
            success: function (response) {
                alert($.parseJSON(response).response);
            },
            error: function () {
                alert("Error");
            }
        });
    }
}
function validateProjectReward(id) {
    var form = $('#formElem');
    form.validate();
    $('#reward_amount_' + id).rules("add",{
        required:true,
        digits:true,
        min:1,
        messages: {
        required: window.error_messages.rewards.amount,
        digits:window.error_messages.rewards.amount_num
    }
    });
    $('#reward_name_' + id).rules("add",{
        required:true,
        messages: {
            required: window.error_messages.rewards.name
        }
    });
    $('#reward_short_description_' + id).rules("add",{
        required:true,
        messages: {
            required: window.error_messages.rewards.short_desc
        }
    });
    $('#reward_available_number_' + id).rules("add",{
        required:true,
        digits:true,
        min:1,
        messages: {
            required:window.error_messages.rewards.available_number,
            digits:window.error_messages.rewards.available_number_num,
            min:window.error_messages.rewards.available_number_positive
        }
    });

    $('#reward_estimated_delivery_date_' + id).rules("add",{
        date:true,
        messages: {
            date:window.error_messages.rewards.delivery_date
        }
    });

    return form.valid();
}
function saveProjectReward(id, campaign_id) {
    if (validateProjectReward(id)) {
        $.ajax({
            url: "/np_compaign_project",
            type: "POST",
            data: {save_project_rewards: true, campaign_id:$('#user_id_val').val(), reward_amount: $("#reward_amount_" + id).val(), reward_name: $("#reward_name_" + id).val(), reward_short_description: $("#reward_short_description_" + id).val(), reward_description: tinymce.get('reward_description_' + id).getContent(),  reward_estimated_delivery_date: $("#reward_estimated_delivery_date_" + id).val(), reward_available_number: $("#reward_available_number_" + id).val(), reward_shipping_address_required: $("#reward_shipping_address_required_" + id).is(':checked')},
            success: function (response) {
                response = $.parseJSON(response).response;
                if (response.substr(0, 4) == '<div') {
                    has_new_reward_form = false;
                    $('.reward_block').last().remove();
                    $("#rewards-section").append(response);
                    alert(window.messages.reward_saved);
                } else {
                    alert(response);
                }
            },
            error: function () {
                alert("Error");
            }
        });
    }
}
function resetFormElement(e) {
    e.wrap('<form>').closest('form').get(0).reset();
    e.unwrap();
}

function ajaxFormSave(button, form, showMessage) {
    if (showMessage) {
        if (button.parent('div').hasClass('right')) {
            button.before('<span id="loading-msg" style="float:left;">Saving...</span>');
        } else button.after('<span id="loading-msg">Saving...</span>');
        var loading_msg = $('#loading-msg');
    }
    $.ajax({
        url:form.attr('action'),
        type: "POST",
        data: form.serialize() + "&ajaxsubmit=true",
        success: function (response) {
            response = $.parseJSON(response);
            if (response.status == "success") {
                
                if (showMessage) {
                    loading_msg.remove();
                    if (button.parent('div').hasClass('right')) {
                        button.before('<span id="saved-msg" style="float:left;">Saved!</span>');
                    } else button.after('<span id="saved-msg">Saved!</span>');
                    var saved_msg = $('#saved-msg');
                    saved_msg.fadeOut(2000, function() { saved_msg.remove(); });
                } else {
                    var dialog = $('#confirm_dialog_box');
                    dialog.html(window.messages.campaign_live);
                    dialog.dialog({
                        resizable: false,
                        title: "Success",
                        height: 250,
                        width: 500,
                        modal: true,
                        buttons: [
                            {
                                text: "Ok",
                                click: function () {
                                    $(this).dialog("close");
                                }
                            }
                        ]
                    });
                }
            } else {
                dialog = $('#confirm_dialog_box');
                if (showMessage) { loading_msg.remove();}
                dialog.html(response.errors);
                dialog.dialog({
                    resizable: false,
                    title: "Validation error",
                    height: 250,
                    width: 500,
                    modal: true,
                    buttons: [
                        {
                            text: "Ok",
                            click: function () {
                                $(this).dialog("close");
                            }
                        }
                    ]
                });
            }
        }
    });
}

$(document).on('ready', function () {
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

    /* Initialize form functionality*/
    var certaindate = $("#certain_date");
    certaindate.datepicker({minDate: '+1d'});

    init_tinymce('.project_update_textarea');
    init_tinymce('.campaign_basic');


    /* Navigation functionality*/

    goToSelectedTab();

    var navigation = $("#navigation");
    navigation.find("li").click(function () {
        var tab_id = $(this).attr('id');
        $("#last_selected_tab").val(tab_id);
    });

    $('.next_btn').each(function () {
        $(this).on('click', function (e) {
            //e.preventDefault();
            navigation.find('li.selected').next().find('a').click();
        });
    });
    $('.prev_btn').each(function () {
        $(this).on('click', function (e) {
            //e.preventDefault();
            navigation.find('li.selected').prev().find('a').click();
        });
    });

    $('#button_project_update_textarea').on('click', function(e) {
        e.preventDefault();
        projectUpdateComment();
    });
    $('.delete_btn').on('click', function(e) {
        e.preventDefault();
        var id = $(this).parents('li').attr('id').replace('project_update_comment_row', '');
        projectUpdateDelete(id);
    });

    $('.clear-file-input').on('click', function(){
        resetFormElement($(this).prev('input[type="file"]'));
    });
    $('#deadline_type_days').on('click', function() {
        changeDeadlineType($(this), 'time_period' );
    });
    $('#deadline_type_date').on('click', function() {
        changeDeadlineType($(this), 'certain_date' );
    });
    $('.tooltip-trigger').tooltip();
    $("#submit_renew").on('click', function(e) {
        e.preventDefault();
        var save_info = new Array();
        var times, url, user_id, data = '';
        times = $("#renew_times").val();
        if (times < 1) {
            alert("Set number of renews");
            $("#renew_times").val('');
            $("#renew_times").focus();
        } else {
            user_id = $("#user_id_val").val();
            url = '/campaigns,page,edit,section,' + user_id + ',campaign_id,members_area';
            data = 'operation=save_renew&times=' + times + '&campaign_id=' + user_id;
            $("input:checkbox[name=keep_info]:checked").each(function(){        
                data = data + '&' + $(this).val() + '=1';
            });
            $.ajax({
                url:url,
                type: "POST",
                data: data,
                success: function (response) {
                    response = $.parseJSON(response);
                    if (response.status == 'success') {
                        alert('Saved!');
                    } else {
                        alert("Unknown error!");
                    }
                }
            });
        }
    });
});