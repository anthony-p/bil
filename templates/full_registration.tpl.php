<?
//var_dump($user_details);
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<!-- TODO: check JS scripts and remove conflicts -->
<!--<script language=JavaScript src='/scripts/jquery/jquery-1.9.1.js'></script>-->
<!--<script language=JavaScript src='/scripts/jquery/jquery-ui-1.10.3.custom.min.js'></script>-->
<style>
    .error {
        border: 1px solid #ff0000;
        background-color: #ff0000;
    }
</style>

<script language="javascript">


    function muteErrFieldsOnChange() {
        $('.error').change(function () {
            $(this).removeClass("error");
        });
    }

    function showValidationErr() {

        $('#validation_errors').empty();
        $('#validation_errors').append('<ul>' + err_msg + '</ul>');

        $("#validation_errors").dialog({
            resizable: false,
            draggable:false,
            height: 200,
            width: 400,
            title: "Validation Errors",
            modal: true,
            buttons: {
                OK: function () {
                    $(this).dialog("close");
                }
            }
        });
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
//        document.registration_form.operation.value = '';
//        document.registration_form.edit_refresh.value = '1';
//        document.registration_form.submit();
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

        var uname	 = username.value;
        var url	 = 'check_username.php';
        var action	 = url + '?username=' + uname;

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




    function showUser(str)
    {
        if (str=="")
        {
            document.getElementById("txtHint").innerHTML="";
            return;
        }
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","getchoice.php?q="+str,true);
        xmlhttp.send();
    }






    function doHttpRequest() {  // This function does the AJAX request
        http.open("GET", "/ajaxb.html", true);
        http.onreadystatechange = getHttpRes;
        http.send(null);
    }

    function doHttpRequest2() {  // This function does the AJAX request


        http.open("GET", "/ajaxprocessor.php?q="+(document.getElementById('zip_code').value)+"&address="+(document.getElementById('address').value)+"&search_name="+(document.getElementById('search_name').value)+"&city="+(document.getElementById('city').value)+"&distancefrom="+(document.getElementById('distancefrom').value)+"&limitresults="+(document.getElementById('limitresults').value), true);



        http.onreadystatechange = getHttpRes;
        http.send(null);
    }

    function getHttpRes() {
        if (http.readyState == 4) {
            res = http.responseText;  // These following lines get the response and update the page
            document.getElementById('div1').innerHTML = res;
        }
    }

    function getXHTTP() {
        var xhttp;
        try {	// The following "try" blocks get the XMLHTTP object for various browsers�
            xhttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                xhttp = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e2) {
                // This block handles Mozilla/Firefox browsers...
                try {
                    xhttp = new XMLHttpRequest();
                } catch (e3) {
                    xhttp = false;
                }
            }
        }
        return xhttp; // Return the XMLHTTP object
    }

    var http = getXHTTP(); // This executes when the page first loads.


</script>

<script type="text/javascript">

    var regNotEmptyAlpha = /^([\w]+)$/i;
    var regNotEmptyNumbers = /^([\d]+)$/i;
    var regNotEmptyAlphaWS = /^([\w\s]+)$/i;
    var regNotEmptyAlphaNumeric = /^([\w\d]+)$/i;
    var regNotEmptyAlphaNumericWS = /^([\w\d\s]+)$/i;
    var regNotEmptyAlphaNumericWithSpacesAndSpecialLanguagesChars = /^([\w\d\sáíóúăşţäößàâçéèêëîïôûùüÿñæœ .-_&]+)$/i;
    var regZipCode = /^\d{5}(?:[-\s]\d{4})?$/i;
    var regUrl = /^(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(:[a-zA-Z0-9]*)?\/?([a-zA-Z0-9\-\._\?\,\'\/\\\+&amp;%\$#\=~])*$/i;
    var regEmail = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i;
    var err_status = false;
    var err_msg = '';

    $(document).ready(function () {


        $("#registration_form").submit(function(){

            err_status = false;
            err_msg = '';
            $(".error").each(function () {
                $(this).removeClass("error");
            });

            if ($("#fname").val() == '' || !$("#fname").val().match(regNotEmptyAlphaNumericWithSpacesAndSpecialLanguagesChars)) {
                $("#fname").addClass("error");
                err_msg += '<li><?= MSG_MEMBER_ACCOUNT_VALIDATION_ERR_FNAME; ?></li>';
                err_status = true;
            }
            if ($("#lname").val() == '' || !$("#lname").val().match(regNotEmptyAlphaNumericWithSpacesAndSpecialLanguagesChars)) {
                $("#lname").addClass("error");
                err_msg += '<li><?= MSG_MEMBER_ACCOUNT_VALIDATION_ERR_LNAME; ?></li>';
                err_status = true;
            }
            if ($("#organization").val() !== '' && !$("#organization").val().match(regNotEmptyAlphaNumericWithSpacesAndSpecialLanguagesChars)) {
                $("#organization").addClass("error");
                err_msg += '<li><?= MSG_MEMBER_ACCOUNT_VALIDATION_ERR_ORGANIZATION; ?></li>';
                err_status = true;
            }
            if ($("#address").val() == '') {
                $("#address").addClass("error");
                err_msg += '<li><?= MSG_MEMBER_ACCOUNT_VALIDATION_ERR_ADDRESS; ?></li>';
                err_status = true;
            }
            if ($("#city").val() == '' || !$("#city").val().match(regNotEmptyAlphaNumericWithSpacesAndSpecialLanguagesChars)) {
                $("#city").addClass("error");
                err_msg += '<li><?= MSG_MEMBER_ACCOUNT_VALIDATION_ERR_CITY; ?></li>';
                err_status = true;
            }
            if ($("#state").val() == '' || !$("#state").val().match(regNotEmptyAlphaNumericWithSpacesAndSpecialLanguagesChars)) {
                $("#state").addClass("error");
                err_msg += '<li><?= MSG_MEMBER_ACCOUNT_VALIDATION_ERR_STATE; ?></li>';
                err_status = true;
            }
            if ($("#postal_code").val() == '') {
                $("#postal_code").addClass("error");
                err_msg += '<li><?= MSG_MEMBER_ACCOUNT_VALIDATION_ERR_ZIP; ?></li>';
                err_status = true;
            }
            if ($("#old_password").val() == '' && $("#password").val() != '') {
                $("#old_password").addClass("error");
                err_msg += '<li><?= MSG_PASSWORD_REQUIRED; ?></li>';
                err_status = true;
            }
            if ($("#password").val() != '' && $("#password2").val() == '') {
                $("#password2").addClass("error");
                err_msg += '<li><?= MSG_VERIFY_PASSWORD_REQUIRED; ?></li>';
                err_status = true;
            }
            if ($("#phone").val() == '') {
                $("#phone").addClass("error");
                err_msg += '<li><?= MSG_MEMBER_ACCOUNT_VALIDATION_ERR_PHONE; ?></li>';
                err_status = true;
            }

            if ($("#email").val() == '' || !$("#email").val().match(regEmail)) {
                $("#email").addClass("error");
                err_msg += '<li><?= MSG_MEMBER_ACCOUNT_VALIDATION_ERR_EMAIL; ?></li>';
                err_status = true;
            }
            if ($("#email_check").val() == '' || !$("#email_check").val().match(regEmail)) {
                $("#email_check").addClass("error");
                err_msg += '<li><?= MSG_MEMBER_ACCOUNT_VALIDATION_ERR_CEMAIL; ?></li>';
                err_status = true;
            }
            if ($("#email").val() !== $("#email_check").val()) {
                $("#email").addClass("error");
                $("#email_check").addClass("error");
                err_msg += '<li><?= MSG_MEMBER_ACCOUNT_VALIDATION_ERR_EQ_EMAIL; ?></li>';
                err_status = true;
            }
            if ($("#pin_value").val() == '') {
                $("#pin_value").addClass("error");
                err_msg += '<li><?= MSG_MEMBER_ACCOUNT_VALIDATION_ERR_PIN; ?></li>';
                err_status = true;
            }

            if (err_status) {
                showValidationErr();
                muteErrFieldsOnChange();
                return false;
            } else {
                // all ok
                return true;
            }

//            return false;
        });

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
                        $("#states_box").empty();
                        $("#states_box").append(result.data);
                    });

        });


        $('.form_tooltip').tooltip({
            track: true
        });

    });
</script>

<?
function fetchstate($statecode){
    $sql="SELECT name FROM probid_countries WHERE id = '".$statecode."'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array( $result );
    $statename=$row['name'];
    return $statename;
}
?>



<?php if (isset($members_area_page_content)) echo $header_registration_message;?>
<br>
<?php if (isset($banned_email_output)) echo $banned_email_output;?>
<?php if (isset($display_formcheck_errors)) echo $display_formcheck_errors;?>
<?php if(isset($check_voucher_message)) echo $check_voucher_message;?>

<form action="<?php if(isset($register_post_url)) echo $register_post_url;?>" method="post" name="registration_form" id="registration_form" class="registrationForm">
<input type="hidden" name="operation" value="submit">
<input type="hidden" name="do" value="<?=$do;?>">
<input type="hidden" name="user_id" value="<?=$user_details['id'];?>">
<input type="hidden" name="edit_refresh" value="0">
<input type="hidden" name="generated_pin" value="<? if(isset($generated_pin)) echo $generated_pin;?>">
<!-- main details -->
<table border="0" cellpadding="0" cellspacing="0" class="tbl">
    <tr>
        <th colspan="2"><?=MSG_MAIN_DETAILS;?></th>
    </tr>
<?php /*
    <tr>
        <td class="leftCol"><?=MSG_REGISTER_AS;?></td>
        <td class="contentfont">
            <input name="tax_account_type" type="radio" value="0" onclick="form_submit();" checked />
            <?=GMSG_INDIVIDUAL;?>
            <input name="tax_account_type" type="radio" value="1" onclick="form_submit();" <? echo ($user_details['tax_account_type']) ? 'checked' : ''; ?> />
            <?=GMSG_BUSINESS;?></td>
    </tr>
 */?>
    <!--tr class="reguser">
			<td>&nbsp;</td>
			<td><?=MSG_REGISTER_AS_DESC;?></td>
		</tr-->
    <?php
    if(isset($user_details['name'])){
        list($user_details['first_name'],$user_details['last_name']) = preg_split('/\s+(?=[^\s]+$)/', $user_details['name'], 2);
    }
    ?>
    <tr>
        <td class="leftCol"><?=MSG_FIRST_NAME;?> *</td>
        <td class="contentfont"><input name="fname" type="text" id="fname" value="<?=$user_details['first_name'];?>" size="40" />

            <br>

            <input name="affiliate" type="hidden" id="affiliate" value="<?=(isset($_POST['affiliate']))?$_POST['affiliate']:'';?>" size="40" />

        </td>
    </tr>
    <!--tr class="reguser">
		  <td>&nbsp;</td>
		  <td><?=MSG_FIRST_NAME_EXPL;?></td>
		</tr-->
    <tr>
        <td class="leftCol"><?=MSG_LAST_NAME;?> *</td>
        <td class="contentfont"><input name="lname" type="text" id="lname" value="<?=$user_details['last_name'];?>" size="40" /></td>
    </tr>
    <tr>
        <td class="leftCol"><?=MSG_ORGANIZATION;?></td>
        <td class="contentfont">
            <input name="organization" type="text" id="organization"
                   value="<?=$user_details['organization'];?>" size="40" />
        </td>
    </tr>
    <!--tr class="reguser">
			<td>&nbsp;</td>
			<td><?=MSG_LAST_NAME_EXPL;?></td>
		</tr-->
    <? if (isset($user_details['tax_account_type']) && $user_details['tax_account_type']) { ?>
    <tr>
        <td  class="contentfont"><?=MSG_COMPANY_NAME;?> *</td>
        <td class="contentfont"><input name="tax_company_name" type="text" class="contentfont" id="tax_company_name" value="<?=$user_details['tax_company_name'];?>" size="40" /></td>
    </tr>
    <!--tr class="reguser">
			<td  class="contentfont">&nbsp;</td>
			<td><?=MSG_COMPANY_NAME_DESC;?></td>
		</tr-->
    <? } ?>
    <tr>
        <td class="leftCol"><?=MSG_ADDRESS;?> *</td>
        <td class="contentfont"><input name="address" type="text" id="address" value="<?=$user_details['address'];?>" size="40" /></td>
    </tr>
    <!--tr class="reguser">
			<td>&nbsp;</td>
			<td><?=MSG_ADDRESS_EXPL;?></td>
		</tr-->
    <tr>
        <td class="leftCol"><?=MSG_CITY;?> *</td>
        <td class="contentfont"><input name="city" type="text" id="city" value="<?=$user_details['city'];?>" size="25" /></td>
    </tr>

<!--    <tr>-->
<!--        <td class="leftCol">--><?//=MSG_COUNTRY;?><!-- *</td>-->
<!--        <td class="contentfont"><input name="country" type="text" id="country" value="--><?//=(isset($user_details['country']))?$user_details['country']:'';?><!--" size="25" /></td>-->
<!--    </tr>-->

    <tr>
        <td class="leftCol"><?=MSG_COUNTRY;?> *</td>
        <td class="contentfont">
            <?=$country_dropdown;?>
<!--            <input name="country" type="text" id="country" value="--><?//=(isset($user_details['country']))?$user_details['country']:'';?><!--" size="25" />-->
        </td>
    </tr>

<!--    <tr>-->
<!--        <td class="leftCol">--><?//=MSG_STATE;?><!-- *</td>-->
<!--        <td class="contentfont"><input name="state" type="text" id="state" value="--><?//=(isset($user_details['state']))?$user_details['state']:'';?><!--" size="25" /></td>-->
<!--    </tr>-->

    <tr>
        <td class="leftCol"><?=MSG_STATE;?> *</td>
        <td class="contentfont">
            <div id="states_box"><?= $state_box; ?></div>
<!--            <input name="state" type="text" id="state" value="--><?//=(isset($user_details['state']))?$user_details['state']:'';?><!--" size="25" />-->
        </td>
    </tr>

    <tr>
        <td class="leftCol"><?=MSG_POSTALE_CODE;?> *</td>
        <td class="contentfont"><input name="postal_code" type="text" id="postal_code" value="<?=(isset($user_details['postal_code']))?$user_details['postal_code']:'';?>" size="25" /></td>
    </tr>
    <tr>
        <td class="leftCol"><?=MSG_PHONE;?> *</td>
        <td class="contentfont">

            <input name="phone" type="text" id="phone" value="<?=(isset($user_details['phone']))?$user_details['phone']:'';?>" size="25" />
            </td>
    </tr>

    <!--tr class="reguser">
			<td>&nbsp;</td>
			<td><?=MSG_CITY_EXPL;?></td>
		</tr-->
</table>
<?php /*
<table border="0" cellpadding="0" cellspacing="0" class="tbl">
    <tr>
        <th colspan="2">Choose a Non-profit</th>
    </tr>
    <tr>
        <td colspan="2">
            <strong>Link your account with a local non profit!</strong><br />That's what this site is all about. Connect your account with a non profit and give back to your community!
        </td>
    </tr>
    <tr>
        <td class="leftCol"><?=MSG_COUNTRY;?> *</td>
        <td><?=$country_dropdown;?></td>
    </tr>
    <tr>
        <td class="leftCol"><?=MSG_STATE;?> *</td>
        <td><?=$state_box;?></td>
    </tr>
    <tr>
        <td class="leftCol"><?=MSG_ZIP_CODE;?> *</td>
        <td><input name="zip_code" type="text" id="zip_code" value="<?=$user_details['zip_code'];?>" size="15" onchange="doHttpRequest2()"/></td>
    </tr>
    <tr>
        <td class="leftCol"></td>
        <td id="div1">
            <!--strong>Click here to see what non profits have signed up in your area:</strong-->
            <?
            #is the user coming from a np landing page. if they are we default their np choice to that of the landing page
            if (  (landingpage == '1') ||  (isset($_COOKIE["np_userid"])) && (empty($user_details["npname"])) ){
                if (isset($_COOKIE["np_userid"]))
                    $mynp_userid=$_COOKIE["np_userid"];
                else
                    $mynp_userid = npuser_id;
                $mynp = $db->get_sql_field("SELECT tax_company_name  FROM np_users WHERE user_id ='" . $mynp_userid . "'", tax_company_name);
                $mynpaddress = $db->get_sql_field("SELECT address  FROM np_users WHERE user_id ='" . $mynp_userid . "'", address);
                $mynpcity = $db->get_sql_field("SELECT city  FROM np_users WHERE user_id ='" . $mynp_userid . "'", city);
                $mynpstate = $db->get_sql_field("SELECT state  FROM np_users WHERE user_id ='" . $mynp_userid . "'", state);
                $mynpzip = $db->get_sql_field("SELECT zip_code  FROM np_users WHERE user_id ='" . $mynp_userid . "'", zip_code);
                #set a new cookie or replace the cookie set by the landing page
                SetCookieLive("np_userid", $mynp_userid,time()+3600*24*90, '/', 'bringitlocal.com');
                #change state code to state name
                $statename=fetchstate($mynpstate);
                $mynpstate=$statename;
                $user_details["npname"] = $mynp;
                $user_details["npuser_id"] = $mynp_userid;
                $_POST["npaddress"] = $mynpaddress;
                $_POST["npcity"] = $mynpcity;
                $_POST["npstate"] = $mynpstate;
                $_POST["npzipcode"] = $mynpzip;
                $_POST["orgname"] = $user_details["npuser_id"];
            }
            ?>
            <input type="button" value="Search" onClick="doHttpRequest2();">
            <input type="hidden" name="orgname" value="<? echo $_POST["orgname"]; ?>" size="50"/>
            <input type="hidden" name="npname"  value="<? echo $_POST["npname"]; ?>" size="50"/>
            <input name="distancefrom" type="hidden" id="distancefrom" value="25">
            <input name="limitresults" type="hidden" id="limitresults" value="5">
            <input name="search_name" type="hidden" id="search_name" value="">
        </td>
    </tr>
    <tr>
        <td colspan="2" id="txtHint">
            <b>You've selected:</b>
            <!--<input type="text" name="orgname"  value="<? echo $user_details['npuser_id']; ?>" size="50" readonly/><br>-->
            <input type="hidden" name="orgname"  value="<? echo $_POST["orgname"]; ?>" size="50"/>
            <input type="text" name="npname"  value="<? echo $user_details["npname"]; ?>" size="50" readonly/>
            <!--
                    <input type="text" name="npaddress"  value="<? echo $_POST["npaddress"]; ?>" size="50" readonly/><br>
                    <input type="text" name="npcity"  value="<? echo $_POST["npcity"]; ?>" size="50" readonly/><br>
                    <input type="text" name="npstate"  value="<? echo $_POST["npstate"]; ?>" size="50" readonly/><br>
                    <input type="text" name="npzipcode"  value="<? echo $_POST["npzipcode"]; ?>" size="50" readonly/>
                    -->
            <?
            #debug start
            $mynp_userid = $user_details["npuser_id"];
            ?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <strong>Don't see your favorite non profit? </strong><br>
            If you represent and want to register your organization <a href="/np/npregister.php" target="_blank">go here to enroll</a>. <br>
            If you want to add an organization that you support <a href="/np/npregister_supporter.php" target="_blank">add them here</a>. <br>
            Then return here to continue your registration.</strong>
        </td>
    </tr>
</table>
*/ ?>
<!-- personal info -->

<?php /*
<table border="0" cellpadding="0" cellspacing="0" class="tbl">
    <tr>
        <th colspan="2">Personal Information</th>
    </tr>
    <tr>
        <td class="leftCol"><?=MSG_PHONE;?> *</td>
        <td class="contentfont">
            <? if (isset($edit_user) && $edit_user == 1)	{ ?>
            <input name="phone" type="text" id="phone" value="<?=(isset($user_details['phone']))?$user_details['phone']:'';?>" size="25" />
            <? } else { ?>
            ( <input name="phone_a" type="text" id="phone_a" value="<?=(isset($user_details['phone_a']))?$user_details['phone_a']:'';?>" size="5" /> )
            <input name="phone_b" type="text" id="phone_b" value="<?=(isset($user_details['phone_b']))?$user_details['phone_b']:'';?>" size="25" />
            <? } ?></td>
    </tr>
    <!--tr class="reguser">
			<td>&nbsp;</td>
			<td><?=MSG_PHONE_EXPL;?></td>
		</tr-->
    <tr class="birthday">
        <td colspan="2"><?=(isset($birthdate_box))?$birthdate_box:'';?></td>
    </tr>
</table>

 */
?>

<!-- User Account Details -->
<table border="0" cellpadding="0" cellspacing="0" class="tbl">
    <tr>
        <th colspan="2"><?=MSG_USER_ACCOUNT_DETAILS; ?></th>
    </tr>
    <tr>
        <td class="leftCol"><?=MSG_EMAIL_ADDRESS;?> *
        </td>
        <td class="contentfont"><input name="email" type="text" class="contentfont" id="email" value="<?=(isset($user_details['email']))?$user_details['email']:'';?>" size="40" maxlength="120" <? echo (defined('IN_ADMIN') && IN_ADMIN == 1) ? 'onchange="copy_email_value();"' : ''; ?> /></td>
    </tr>
    <?php /* if(!isset($user_details['id']) || !$user_details['id']): ?>
        <tr class="reguser">
            <td>&nbsp;</td>
            <td><?=MSG_EMAIL_EXPLANATION;?></td>
        </tr>
    <?php endif; */?>
    <tr>
        <td class="contentfont"><?=MSG_RETYPE_EMAIL;?> *</td>
        <td class="contentfont"><input name="email_check" type="text" class="contentfont" id="email_check" value="<?=$email_check_value;?>" size="40" maxlength="120" onkeyup="checkEmail();">
            <img src="<?=(isset($path_relative))?$path_relative:'';?>themes/<?=$setts['default_theme'];?>/img/system/check_img.gif" id="email_img" align="absmiddle" style="display:none;" /></td>
    </tr>
    <tr>
        <td class="leftCol"><?=MSG_PLEASE_SEND;?>....</td>
        <td class="contentfont">

            <?
            $newsletter = isset($user_details['is_subscribe_news'])?$user_details['is_subscribe_news']:0;
            ?>

            <input name="newsletter" type="checkbox" class="newsletter" id="newsletter" value="1" <?php if($newsletter):?>checked<?php endif;?> />


        </td>





    </tr>
<?php /*
    <tr>
        <td class="leftCol"><?=MSG_CREATE_USERNAME;?> *</td>
        <td class="contentfont"><table cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td><input name="username" type="text" id="username" value="<?=$user_details['username'];?>" size="40" maxlength="30" <?=$edit_disabled;?> onchange="check_username(this);"/></td>
                <td>&nbsp; &nbsp;</td>
                <td id="usernameResult"><?=MSG_ENTER_USERNAME;?></td>
            </tr>
        </table></td>
    </tr>
    <tr class="reguser">
        <td>&nbsp;</td>
        <td><?=MSG_USERNAME_EXPLANATION;?></td>
    </tr>
 */ ?>
    <tr>
        <td  class="contentfont"><?=MSG_CREATE_PASS;?>
        </td>
        <td class="contentfont"><input name="password" type="password" class="contentfont" autocomplete="off" id="password" size="40" maxlength="20" <? echo ((defined('IN_ADMIN')) && IN_ADMIN == 1) ? 'onchange="copy_password_value();"' : ''; ?> /></td>
    </tr>
    <!--tr class="reguser">
			<td>&nbsp;</td>
			<td><?=MSG_PASSWORD_EXPLANATION;?></td>
		</tr-->
    <tr>
        <td  class="contentfont"><?=MSG_VERIFY_PASS;?></td>
        <td class="contentfont"><input name="password2" type="password" autocomplete="off" id="password2" size="40" maxlength="20" onkeyup="checkPass();" />
            <img src="<?=(isset($path_relative))?$path_relative:'';?>themes/<?=$setts['default_theme'];?>/img/system/check_img.gif" id="pass_img" align="absmiddle" style="display:none;" /></td>
    </tr>
    <tr>
        <td  class="contentfont"><?=MSG_OLD_PASS;?>
        </td>
        <td class="contentfont">
            <input name="old_password" type="password" class="contentfont" autocomplete="off"
                   id="old_password" size="40" maxlength="20" />
        </td>
    </tr>
</table>
<?=$custom_sections_table;?>
<? if (defined('IN_ADMIN') && IN_ADMIN == 1) { ?>
<table border="0" cellpadding="0" cellspacing="0" class="tbl">
    <tr>
        <td colspan="2"><?=AMSG_PAYMENT_SETTINGS;?></td>
    </tr>
    <tr>
        <td class="leftCol"><?=AMSG_PAYMENT_MODE;?></td>
        <td class="contentfont"><input type="radio" name="payment_mode" value="2" <? echo ($user_details['payment_mode']==2) ? 'checked' : '';?>>
            <?=GMSG_ACCOUNT;?>
            <input type="radio" name="payment_mode" value="1" <? echo ($user_details['payment_mode']==1) ? 'checked' : '';?>>
            <?=GMSG_LIVE;?></td>
    </tr>
    <? if ($user_details['payment_mode'] == 2) { ?>
    <tr class="reguser">
        <td>&nbsp;</td>
        <td><?=AMSG_PAYMENT_MODE_EXPL;?></td>
    </tr>
    <tr>
        <td  class="contentfont"><?=AMSG_ACCOUNT_BALANCE;?>
        </td>
        <td class="contentfont"><?=$setts['currency']; ?> <input name="balance" value="<?=abs($user_details['balance']); ?>" size="8">
            <select name="balance_type">
                <option value="-1" selected><?=GMSG_CREDIT;?></option>
                <option value="1" <? echo ($user_details['balance']>0) ? 'selected' : '';?> ><?=GMSG_DEBIT;?></option>
            </select> &nbsp; <?=AMSG_BALANCE_ADJ_REASON;?>: <input type="text" name="adjustment_reason" size="20"> (<?=AMSG_OPTIONAL_FIELD;?>)</td>
    </tr>
    <tr class="reguser">
        <td>&nbsp;</td>
        <td><?=AMSG_ACCOUNT_BALANCE_EXPL;?></td>
    </tr>
    <tr>
        <td  class="contentfont"><?=GMSG_MAX_DEBIT;?></td>
        <td class="contentfont"><?=$setts['currency']; ?> <input name="max_credit" value="<?=abs($user_details['max_credit']); ?>" size="8"></td>
    </tr>
    <tr class="reguser">
        <td>&nbsp;</td>
        <td><?=AMSG_MAX_DEBIT_EXPL;?></td>
    </tr>
    <? } ?>
</table>
    <? } ?>

<? if (!empty($display_direct_payment_methods)) { ?>
<!-- Direct Payment Settings -->
<table border="0" cellpadding="0" cellspacing="0" class="tbl"
    <?php if (isset($user_details['confirmed_paypal_email']) && $user_details['confirmed_paypal_email']) echo 'id="directPayment"' ?>>
    <tr>
        <th colspan="2"><?=MSG_DIRECT_PAYMENT_SETTINGS;?>
            <div style="width:40px; float: right; margin-left: 10px;" class="form_tooltip"><a href="#" title="<?= TOOLTIP_REGISTRATION_DIRECT_PAYMENT_EXPLAIN; ?>"><img src="/images/question_32x37.png" height="24" alt="some test"></a></div>
        </th>
    </tr>
    <?=$display_direct_payment_methods;?>
</table>
    <? } ?>

<?=(isset($signup_voucher_box))?$signup_voucher_box:'';?>
<!-- terms and conditions -->
<div id="terms"><?=(isset($registration_terms_box))?$registration_terms_box:'';?></div>
<? if ((!defined('IN_ADMIN') || IN_ADMIN != 1 )&& !isset($edit_user) ) { ?>
<!-- PIN -->
<table border="0" cellpadding="0" cellspacing="0" class="tbl">
    <tr>
        <th colspan="2">Confirm PIN</th>
    </tr>
    <tr>
        <td class="leftCol"><?=MSG_REG_PIN;?></td>
        <td><?=(isset($pin_image_output))?$pin_image_output:'';?></td>
    </tr>
    <tr class="reguser">
        <td  class="contentfont">&nbsp;</td>
        <td><?=MSG_REG_PIN_EXPL;?></td>
    </tr>
    <tr>
        <td class="leftCol"><?=MSG_CONF_PIN;?> *</td>
        <td><input name="pin_value" type="text" class="contentfont" id="pin_value" value="" size="20" /></td>
    </tr>
</table>
    <? } ?>
<!-- submit -->
<table border="0" cellpadding="0" cellspacing="0" class="tbl">
    <tr>
        <td class="leftCol"></td>
        <td>

            <input name="form_register_proceed" type="submit" id="form_register_proceed" value="<?=$proceed_button;?>" /></td>
    </tr>
</table>
</form>
<div id="validation_errors" title="Basic dialog" style="display: none;">
</div>