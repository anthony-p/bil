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
</script>
<script language=JavaScript src='/scripts/jquery/jquery-ui-1.10.3.custom.min.js'></script>
<script>
    $(document).ready(function(){




        $(".delete").click(function(){

            var del_id = $(this).attr('id');


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

//                                alert(result);
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
    <h2><?php echo $campaign_title;?> <?=MSG_CAMPAIGNS?></h2>
<!--    <form accept="#">-->
<!--        <input type="text" value="" placeholder="Find by name" name="keyword">-->
<!--        <button type="submit"></button>-->
<!--    </form>-->
<!--    <div class="select">-->
<!--        <select id="order_result" name="names" class="changeMe">-->
<!--            <option value="0" selected="selected" class="order">Sort by</option>-->
<!--            <option value="" class="order">Date asc</option>-->
<!--            <option value="" class="order">Date des</option>-->
<!--        </select>-->
<!--    </div>-->

    <form id="search_by_name" accept="#">
        <select id="order_result" name="order" class="changeMe">
            <option value="" selected="selected" class="order"><?=MSG_SORT_BY?></option>
            <option value="ASC" class="order"><?=MSG_DATE_ASC?></option>
            <option value="DESC" class="order"><?=MSG_DATE_DESC?></option>
        </select>
        <div class="search-input">
            <input type="text" value="" placeholder="<?= MSG_MEMBER_AREA_CAMPAIGNS_FIELD_SEARCH_BY_NAME; ?>" name="keyword" id="name">
            <button type="submit"></button>
        </div>
    </form>
    <div class="clear"></div>
    <ul class="list" id="pagination">
<!--        --><?php //var_dump($campaigns_list)?>
        <?php foreach( $campaigns_list as $row):?>
        <li id="li_<?php echo $row["user_id"]?>">
            <dl>
                <dt><?=MSG_CAMPAIGN_NAME?>:</dt>
                <dd><?= $row['project_title'];?></dd>
                <dt><?=MSG_CREATE_AT?>:</dt>
                <dd><?= date("m/d/y",$row['reg_date'])?></dd>
                <dt><?=MSG_CLOSED_ON?>:</dt>
                <dd><?=date("m/d/y",$row['end_date'])?></dd>
                <dt><?=MSG_COLLECTED_MONEY?></dt>
                <dd>$<?=$row['payment']?></dd>
            </dl>
            <div class="clear"></div>
            <fieldset>
                <div class="campaignsButtons">
                    <a href="/<?php echo $row['username']; ?>" target="_blank"class="view">view</a>
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
    <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><?= MSG_MEMBER_AREA_DIALOG_DELETE_CAMPAIGN_MSG; ?></p>
</div>