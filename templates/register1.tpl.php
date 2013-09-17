<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if (!defined('INCLUDED')) {
    die("Access Denied");
}
?>
<script language=JavaScript src='/scripts/jquery/jquery-1.9.1.js'></script>
<script language=JavaScript src='/scripts/jquery/jquery-ui-1.10.3.custom.min.js'></script>
<script language="javascript">
    function checkEmail() {
        if (document.registration_form.email_check.value ==
                document.registration_form.email.value) document.registration_form.email_img.style.display = "inline";
        else document.registration_form.email_img.style.display = "none";
    }

    function checkPass() {
        if (document.registration_form.password.value ==
                document.registration_form.password2.value) document.registration_form.pass_img.style.display = "inline";
        else document.registration_form.pass_img.style.display = "none";
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

    function showResult(xmlHttp, id) {
        if (xmlHttp.readyState == 4) {
            var response = xmlHttp.responseText;

            document.getElementById('usernameResult').innerHTML = unescape(response);
        }
    }

    function showUser(str) {
        if (str == "") {
            document.getElementById("txtHint").innerHTML = "";
            return;
        }
        if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "getchoice.php?q=" + str, true);
        xmlhttp.send();
    }

    function doHttpRequest() {  // This function does the AJAX request
        http.open("GET", "/ajaxb.html", true);
        http.onreadystatechange = getHttpRes;
        http.send(null);
    }

    function doHttpRequest2() {  // This function does the AJAX request

        http.open("GET",
                "/ajaxprocessor.php?q=" + (document.getElementById('zip_code').value) + "&address=" +
                        (document.getElementById('address').value) + "&search_name=" +
                        (document.getElementById('search_name').value) + "&city=" +
                        (document.getElementById('city').value) + "&distancefrom=" +
                        (document.getElementById('distancefrom').value) + "&limitresults=" +
                        (document.getElementById('limitresults').value), true);
        //

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
    $(document).ready(function () {

        $(document).tooltip({
            track: true
        });

    });
</script>


<?
function fetchstate($statecode)
{
    $sql = "SELECT name FROM probid_countries WHERE id = '" . $statecode . "'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $statename = $row['name'];
    return $statename;
}

?>



<?= $header_registration_message; ?>
<br>
<?php if (isset($banned_email_output)) echo $banned_email_output; ?>
<?php if (isset($display_formcheck_errors)) echo $display_formcheck_errors; ?>
<?php if (isset($check_voucher_message)) echo $check_voucher_message; ?>

<form action="<?= $register_post_url; ?>" method="post" name="registration_form" class="registrationForm">
    <input type="hidden" name="operation" value="submit">
    <input type="hidden" name="do" value="<? if (isset($do)) echo $do; ?>">
    <input type="hidden"
            name="user_id"
            value="<? if (isset($user_details['user_id'])) echo $user_details['user_id']; ?>">
    <input type="hidden" name="edit_refresh" value="0">
    <!--
    <input type="hidden" name="generated_pin" value="<? if (isset($generated_pin)) echo $generated_pin;?>">   -->

    <!-- main details -->
    <h2><?= MSG_MAIN_DETAILS; ?></h2>

    <?php
    if (isset($user_details['name'])) {
        list($user_details['fname'], $user_details['lname']) = preg_split('/\s+(?=[^\s]+$)/', $user_details['name'], 2);
    }
    ?>
    <div class="form-cont">
        <div class="form-row">
            <div style="float:left; margin-right: 10px;" class="form_tooltip"><a href="#"
                        title="hello world"><img src="/images/question_32x37.png" height="24" alt="some test"></a></div>
            <label><?= MSG_FIRST_NAME; ?>*</label>
            <input name="fname"
                    type="text"
                    id="fname"
                    class="text"
                    value="<? if (isset($user_details['fname'])) echo $user_details['fname']; ?>"
                    size="40" />
            <input name="affiliate"
                    type="hidden"
                    class="text"
                    id="affiliate"
                    value="<? if (isset($_POST['affiliate'])) echo $_POST['affiliate']; ?>"
                    size="40" />
        </div>
        <div class="form-row">
            <label><?= MSG_LAST_NAME; ?>*</label>
            <input name="lname"
                    type="text"
                    id="lname"
                    class="text"
                    value="<? if (isset($user_details['lname'])) echo $user_details['lname']; ?>"
                    size="40" />
        </div>
        <div class="form-row">
            <label><?= MSG_ORGANIZATION; ?></label>
            <input name="organization"
                    type="text"
                    id="organization"
                    class="text"
                    value="<? if (isset($user_details['organization'])) echo $user_details['organization']; ?>"
                    size="40" />
        </div>

        <div class="form-row">
            <label><?= MSG_EMAIL_ADDRESS; ?>*</label>
            <input name="email"
                    type="text"
                    class="contentfont text"
                    id="email"
                    value="<? if (isset($user_details['email'])) echo $user_details['email']; ?>"
                    size="40"
                    maxlength="120" <? echo (defined('IN_ADMIN') && IN_ADMIN == 1) ? 'onchange="copy_email_value();"' :
                    ''; ?> />
            <span> <?= MSG_EMAIL_EXPLANATION; ?></span>
        </div>


        <div class="form-row">
            <label><?= MSG_RETYPE_EMAIL; ?>*</label>
            <input name="email_check"
                    type="text"
                    class="contentfont text"
                    id="email_check"
                    value="<? if (isset($email_check_value)) echo $email_check_value; ?>"
                    size="40"
                    maxlength="120"
                    onkeyup="checkEmail();">
            <img src="<?php if (isset($path_relative)) echo $path_relative; ?>themes/<?= $setts['default_theme']; ?>/img/system/check_img.gif"
                    id="email_img"
                    align="absmiddle"
                    style="display:none;" />
        </div>


        <div class="form-row">
            <label> <?= MSG_PASSWORD; ?>*</label>
            <input name="password"
                    type="password"
                    class="contentfont text"
                    id="password"
                    value=""
                    size="40"
                    maxlength="120" <? echo (defined('IN_ADMIN') && IN_ADMIN == 1) ?
                    'onchange="copy_password_value();"' : ''; ?> />
            <span> <?= MSG_PASSWORD_EXPLANATION; ?></span>
        </div>

        <div class="form-row">
            <label> <?= MSG_RETYPE_PASSWORD; ?>* </label>
            <input name="password2"
                    type="password"
                    class="contentfont text"
                    id="password2"
                    value=""
                    size="40"
                    maxlength="120"
                    onkeyup="checkPass();"
                    class="text">
            <img src="<?php if (isset($path_relative)) echo $path_relative; ?>themes/<?= $setts['default_theme']; ?>/img/system/check_img.gif"
                    id="pass_img"
                    align="absmiddle"
                    style="display:none;" />
        </div>

        <div class="form-row check">

            <?
            if (isset ($_POST["newsletter"]))
                $newsletter = $_POST["newsletter"];
            else
                $newsletter = "";
            ?>
            <input name="newsletter" type="checkbox" class="newsletter" id="newsletter" value="1" checked />
            <label>Please send me the newsletter</label>
        </div>
        <div class="clear"></div>
        <input name="form_register_proceed"
                type="submit"
                id="form_register_proceed"
                class="buttons"
                value="<?= $proceed_button; ?>" />
    </div>

</form>
