/**
 * Created by Anastasia Batieva on 21.10.13.
 */

function validateAccountForm(form) {
    form.validate({
        rules: {
            fname:"required",
            lname:"required",
            address:"required",
            city:"required",
            state:"required",
            postal_code:"required",
            phone:"required",
            password:{
                equalTo:'#password2'
            },
            password2: {
                required:{
                    depends:function(elem) {
                        return $('#password').val();
                    }
                }
            },
            email: {
                required: true,
                email: true
            },
            email_check: {
                required: true,
                email: true,
                equalTo: "#email"
            }
            // ,
            // TODO: uncomment pin before LIVE
            // pin_value:"required"
        },
        messages: {
            fname:  window.error_messages.fname,
            lname: window.error_messages.lname,
            organization: window.error_messages.organization,
            address: window.error_messages.address,
            city: window.error_messages.city,
            state: window.error_messages.state,
            postal_code: window.error_messages.postal_code,
            phone: window.error_messages.phone,
            password2: window.error_messages.password2,
            old_password: window.error_messages.old_password,
            email: window.error_messages.email,
            email_check: {
                required:window.error_messages.email_check,
                equalTo:window.error_messages.email_check_notequal
            },
            pin_value: window.error_messages.pin_value
        }
    });
}

function ajaxFormSave(button, form) {
    if (button.parent('div').hasClass('right')) {
        button.before('<span id="loading-msg" style="float:left;">Saving...</span>');
    } else button.after('<span id="loading-msg">Saving...</span>');
    var loading_msg = $('#loading-msg');
    $.ajax({
        url:form.attr('action'),
        type: "POST",
        data: form.serialize() + "&ajaxsubmit=true",
        success: function (response) {
            response = $.parseJSON(response);
            if (response.status == "success") {
                loading_msg.remove();
                if (button.parent('div').hasClass('right')) {
                    button.before('<span id="saved-msg" style="float:left;">Saved!</span>');
                } else button.after('<span id="saved-msg">Saved!</span>');
                var saved_msg = $('#saved-msg');
                saved_msg.fadeOut(2000, function() { saved_msg.remove(); });
            } else {
                var dialog = $('#validation_errors');
                loading_msg.remove();
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