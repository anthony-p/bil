<?php if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<!--<button id="paypal">PayPal</button>-->
<script type="text/javascript" src='/scripts/jquery/jquery-1.9.1.js'></script>
<script type="text/javascript" src='/scripts/jquery/jquery.validate.min.js'></script>
<script type="text/javascript" src="/scripts/jquery/additional-methods.min.js"></script>

<style>
    #payment_details{
        /*display: none;*/
    }
    #contribution_details{
        display: block;
    }
</style>
<!--<script src="paypal-button.min.js?merchant=WPGBEPBR4TR5Y" data-button="donate" data-name="product" data-callback="http://devlive.bringitlocal.com/donate.php" data-env="sandbox"></script>-->
<script>
    $(document).ready(function(){
        $("#contribution_form").validate({

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

        $("#community").click(function(){
            if($("#community").is(":checked")){
                $("#community_amount").removeAttr("disabled");
            } else {
                $("#community_amount").attr("disabled", "disabled");
            }
        });
    });
</script>
<div id="container">
    <form id="contribution_form" method="post" action="chained.php">
        <div id="contribution_details">
            <div>
                <h3>How much would you like to contribute?</h3>
            </div>
			<div class="top-description">
    <div class="left" style="width: 160px;"><img src="<? echo $campaign['logo'];?>" /></div>
<!--    <div class="left">-->
<!--        --><?//
//        //var_dump($compaignData);
//        if (file_exists(getcwd()."/".$campaign["banner"])) {
//            echo '<img width="100%" src ="' . $campaign["banner"] . '" />';
//        } else {
//            $banner = $campaign["banner"];
//            if (strpos($banner,"youtube"))
//                $banner = str_replace("http:","",$banner);
//            echo '<iframe width="100%"  src="' . $banner . '"frameborder="0" allowfullscreen></iframe>';
//        }
//        ?>
<!--    </div>-->
    <div class="right">
        <h2><? echo $campaign['project_title'];?></h2>
        <div class="clear"></div>
        <p><? echo $campaign['description'];?></p>
    </div>
            </div>
        <div class="donate_block">
            <div id="contribution_qty">
                <input type="text" name="amount" id="amount" />  <b>(USD)</b><br />
                <div class="clear"></div>
                <?php if (!$campaign['cfc']): ?>
                    <input type="checkbox" name="community" id="community" style="width: auto;float: left;"/>
                    <label style="float: left;width: 240px;margin: 4px 0 0 8px;">Would you like to add $5 toward the Community Fund? </label><br />
                    <div class="clear"></div>
                    <br/>
                    <input type="text" name="community_amount" id="community_amount" disabled="disabled" value="5" />  <b>(USD)</b>
                <?php endif; ?>
                <input type="hidden" name="np_user_id" id="np_user_id" value="<?php echo isset($np_user_id) ? $np_user_id : ''; ?>" />
            </div>
            <div class="clear"></div>
            <br />
            <input id="submit" type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">

        </div>
        </div>
    </form>
</div>