<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<?=$header_selling_page;?>
<?=$display_formcheck_errors;?>
<SCRIPT LANGUAGE="JavaScript">
function submit_form(form_name) {
	form_name.submit();
}
</script>
<script language=JavaScript src='/scripts/jquery/jquery-1.3.2.js'></script>
<script>
    $(document).ready(function(){
        $(".delete").click(function(e){
            e.preventDefault();
            var id = this.id;
            $.ajax({
                type : "GET",
                url : "/np/npdelete.php?np_userid=" + id,
                success : function() {
                    $('#li_' + id).remove();
//                    location.reload();
                }
            });
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
    <h2><?php echo $campaign_title;?> Campaigns</h2>
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
            <input type="text" value="" placeholder="Find by name" name="keyword" id="name">
            <button type="submit"></button>
        </div>
    </form>
    <div class="clear"></div>
    <ul class="list" id="pagination">
        <?php foreach( $campaigns_list as $row):?>
        <li id="li_<?php echo $row["user_id"]?>">
            <dl>
                <dt><?=MSG_CAMPAIGN_NAME?>:</dt>
                <dd><?php echo $row['name'];?></dd>
                <dt><?=MSG_CREATE_AT?>:</dt>
                <dd><?php echo date("d.m.y",$row['end_date']);?></dd>
                <dt><?=MSG_CLOSED_ON?>:</dt>
                <dd><?php echo date("d.m.y",$row['reg_date']);?></dd>
                <dt><?=MSG_COLLECTED_MONEY?></dt>
                <dd><?php echo $row['price'];?>$</dd>
            </dl>
            <div class="clear"></div>
            <fieldset>
                <div class="campaignsButtons">
                    <a href="/<?php echo $row['username']; ?>" target="_blank"class="view">view</a>
                    <a href="/campaigns,page,edit,section,<?php echo $row["user_id"]?>,campaign_id,members_area?keyword=#<?php echo $row['user_id'];?>;?>" class="edit"><?=MSG_MM_EDIT?></a>
                    <a href="/np/npdelete.php?np_userid=<?php echo $row["user_id"]?>" id="<?php echo $row["user_id"]?>" class="delete"><?=MSG_DELETE?></a>
                </div>
            </fieldset>
        </li>
        <?php endforeach; ?>

    </ul>
    <fieldset>  <div class="holder"></div></fieldset>
</div>