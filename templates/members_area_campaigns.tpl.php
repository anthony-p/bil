<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<?=(isset($header_selling_page))?$header_selling_page:'';?>
<?=(isset($display_formcheck_errors))?$display_formcheck_errors:'';?>
<SCRIPT LANGUAGE="JavaScript">
function submit_form(form_name) {
	form_name.submit();
}

function select(order, obj)
{

    var orderSelected = $(obj).attr('rel');
    console.log(orderSelected);
    $element = $('#order_result');
    $options = $element.find('option');
    $wanted_element = $options.filter(function () {
        return $(this).val() == orderSelected || $(this).text() == orderSelected
    });
    $wanted_element.attr("selected", true);

    $("#search_by_name").submit();
}
</script>
<script language=JavaScript src='/scripts/jquery/jquery-ui-1.10.3.custom.min.js'></script>
<script>
    $(document).ready(function(){




        $(".delete").click(function(){

            var del_id = $(this).attr('id');
            var cmpgname = $(this).parent().parent().parent().find(".row_campaign_name").html();
            var dialog_msg = '<?= MSG_MEMBER_AREA_DIALOG_DELETE_CAMPAIGN_MSG; ?>';

            dialog_msg =  dialog_msg.replace("%name%", cmpgname) ;

            $("#dialog-confirm-msg").empty();
            $("#dialog-confirm-msg").append(dialog_msg);

            $("#dialog-confirm").dialog({
                resizable: false,
                height: 200,
                width: 400,
                modal: true,
                buttons: {
                    "<?= MSG_MEMBER_AREA_DIALOG_DELETE_CAMPAIGN_BTN_CONFIRM; ?>": function () {

                        var id = this.id;
                        $.ajax({
                            type: "GET",
                            async: false,
                            url: "/np/npdelete.php?np_userid=" + del_id,
                            success: function (result) {

                                $('#li_' + del_id).remove();

                            }
                        });

                        $(this).dialog("close");
                    },
                    "<?= MSG_MEMBER_AREA_DIALOG_DELETE_CAMPAIGN_BTN_CANCEL; ?>": function () {
                        $(this).dialog("close");
                        return false;
                    }
                }
            });
            return false;

        });
        $(".myCampaigs .list li:odd").addClass("odd");



        $("#search_by_name").submit(function(e){
            e.preventDefault();
            var window_location = '/campaigns,page,<?php echo $section; ?>,section,';
            if ($("#order_result").val()) {
                window_location += $("#order_result").val() + ',order,';
            }
            if ($("#name").val()) {
                window_location += $("#name").val() +  ',keyword,';
            }
            window_location += 'members_area'
            window.location = window_location;
        });
    });
</script>
<div class="myCampaigs">
    <?php if (isset($explanation_message) && $explanation_message): ?>
    <?php echo $explanation_message; ?>
    <?php endif; ?>
    <h2><?php echo $campaign_title;?> <?=MSG_CAMPAIGNS?></h2>

    <form id="search_by_name" accept="#">
        <select id="order_result" name="order" class="changeMe">
            <option value="" selected="selected" class="order"><?=MSG_SORT_BY?></option>
            <option value="ASC" <?php if ($order == "ASC") echo 'selected';?> class="order"><?=MSG_DATE_ASC?></option>
            <option value="DESC" <?php if ($order == "DESC") echo 'selected';?> class="order"><?=MSG_DATE_DESC?></option>
        </select>
        <div class="search-input">
            
            <input type="text" value="<?php if(!empty($keyword)) echo $keyword;?>" placeholder="<?= MSG_MEMBER_AREA_CAMPAIGNS_FIELD_SEARCH_BY_NAME; ?>" name="keyword" id="name">
            
            <button type="submit"></button>
        </div>
    </form>
    <div class="clear"></div>
    <ul class="list" id="pagination">
        <?php foreach( $campaigns_list as $row):?>
        <li id="li_<?php echo $row["user_id"]?>">
            <dl>
                <dt><?=MSG_CAMPAIGN_NAME?>:</dt>
                <dd class="row_campaign_name"><?= $row['project_title'];?></dd>
                <dt><?=MSG_CREATE_AT?>:</dt>
                <dd><?= date("m/d/y",$row['reg_date'])?></dd>
                <dt><?=MSG_CLOSED_ON?>:</dt>
                <dd><?=date("m/d/y",$row['end_date'])?></dd>
                <dt><?=MSG_COLLECTED_MONEY?>:</dt>
                <dd>$<?=$row['payment']?></dd>
            </dl>
            <div class="clear"></div>
            <fieldset>
                <div class="campaignsButtons">
                    <a href="/view_campaign.php?campaign_id=<?= $row['user_id'] ?>" class="view" target="_blank"><?= MSG_PREVIEW ?></a>
                    <a href="/campaigns,page,edit,section,<?php echo $row["user_id"]?>,campaign_id,members_area" class="edit"><?=MSG_MM_EDIT?></a>
                    <a href="/np/npdelete.php?np_userid=<?php echo $row["user_id"]?>" id="<?php echo $row["user_id"]?>" class="delete"><?=MSG_DELETE?></a>
                </div>
            </fieldset>
        </li>
        <?php endforeach; ?>

    </ul>
    <fieldset>  <div class="holder"></div></fieldset>
</div>

<div id="dialog-confirm" title="<?= MSG_MEMBER_AREA_DIALOG_DELETE_CAMPAIGN_TITLE; ?>" style="display: none;">
    <br>
    <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><p id="dialog-confirm-msg"></p>
</div>