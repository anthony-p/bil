<?php if (!defined('INCLUDED')) {
    die("Access Denied");
}
?>
<script type="text/javascript" src='/scripts/jquery/jquery-1.9.1.js'></script>
<script type="text/javascript" src='/scripts/jquery/jquery.validate.min.js'></script>
<script type="text/javascript" src="/scripts/jquery/additional-methods.min.js"></script>

<link href="/css/ui-darkness/jquery-ui-1.10.3.custom.css" rel="stylesheet">

<link href="/css/tabs-style.css" rel="stylesheet">

<script src="/scripts/jquery/jquery-ui.js"></script>

<style>

    #contribution_details {
        display: block;
    }
</style>
<script>
    $(document).ready(function () {
        $('#contribution_qty').tooltip({
            track: true
        });
        var contribution_form = $("#contribution_form");
        contribution_form.validate({

            errorElement: 'em',

            rules: {

                amount: {
                    required: true,
                    number: true,
                    min: 1
                },
                community_amount: {
                    number: true,
                    min: 1,
                    required: function (element) {
                        return $("#community").val().length > 0;
                    }

                }


            }
        });

        contribution_form.submit(function (e) {
//            e.preventDefault();
            var amount = $("#amount").val();
            var community_amount = $("#community_amount").val();
            if (amount && $.isNumeric(amount)) {
                if (Math.floor(amount) == amount) {
                    if (community_amount && (!($.isNumeric(community_amount)) || Math.floor(community_amount) != community_amount)) {
                        showErrorPopup(e);
                    } else {
                        $("#contribution_form").submit();
                    }
                } else {
                    showErrorPopup(e);
                }
            }
        });

        $("#community").click(function () {
            if ($("#community").is(":checked")) {
                $("#community_amount").removeAttr("disabled");
            } else {
                $("#community_amount").attr("disabled", "disabled");
            }
        });
    });

    function showErrorPopup(e) {
        e.preventDefault();
        var err_msg = '<?php echo MSG_DONATION_INVALID_AMOUNT; ?>';
        var validation_errors = $('#validation_errors');

        validation_errors.empty();
        validation_errors.append('<p>' + err_msg + '</p>');

        validation_errors.dialog({
            resizable: false,
            height: 180,
            width: 300,
            title: "Validation Errors",
            modal: true,
            buttons: {
                OK: function () {
                    $(this).dialog("close");
                }
            }
        });
    }
</script>
<div id="container">
    <form id="contribution_form" method="post" action="chained.php">
        <div id="contribution_details" class="donate_block">
            <div>
                <h3>How much would you like to contribute?</h3>
            </div>
            <div id="contribution_qty">
                <div class="top-description">
                    <div class="left" style="width: 160px;"><img src="<? echo $campaign['logo']; ?>"/></div>
                    <div class="right">
                        <h2 class="clearfix"><? echo $campaign['project_title']; ?></h2>

                        <p><? echo $campaign['description']; ?></p>

                        <p>
                            <input type="text" name="amount" id="amount"/> <strong>(USD)</strong>
                        </p>
                    </div>
                </div>


                <?php if (!$campaign['cfc']): ?>
                    <div class="community_donate clearfix">

                        <label class="inline-block">Would you like to add <strong>$5</strong> toward the
                            <a href="/bringitlocal" target="_blank">Community Fund</a>?  <img src="/images/question_help.png" height="16" alt="help"
                                                  title="<?= MSG_DONATE_FUND_TOOLTIP; ?>" ></label>
                        <input type="checkbox" name="community" id="community" class="inline-block"/>
                        <div class="inline-block">
                            <input type="text" name="community_amount" id="community_amount" disabled="disabled" value="5"/>
                            <strong>(USD)</strong>
                        </div>

                    </div>

                <?php endif; ?>
                <div class="paypal_donate">
                    <input type="hidden" name="np_user_id" id="np_user_id"
                           value="<?php echo isset($np_user_id) ? $np_user_id : ''; ?>"/>
                    <span><?= MSG_DONATE_PAYPAL_LABEL; ?></span>
                    <input id="submit" type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif"
                           name="submit" alt="PayPal - The safer, easier way to pay online!">
                </div>

            </div>
        </div>
    </form>
</div>

<div id="validation_errors" title="Basic dialog" style="display: none;">