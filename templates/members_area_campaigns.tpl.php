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
<div class="myCampaigs">
    <h2><?php echo $campaign_title;?> Campaigns</h2>
    <form accept="#">
        <input type="text" value="" placeholder="Find by name" name="keyword">
        <button type="submit"></button>
    </form>
    <div class="select">
        <select id="order_result" name="names" class="changeMe">
            <option value="0" selected="selected" class="order">Sort by</option>
            <option value="" class="order">Date asc</option>
            <option value="" class="order">Date des</option>
        </select>
    </div>
    <div class="clear"></div>
    <ul class="list" id="pagination">
        <?php foreach( $campaigns_list as $row):?>
        <li>
            <dl>
                <dt>Campaign Name1</dt>
                <dd><?php echo $row['name'];?></dd>
                <dt>Create am:</dt>
                <dd><?php echo date("d.m.y",$row['end_date']);?></dd>
                <dt>Closed on:</dt>
                <dd><?php echo date("d.m.y",$row['reg_date']);?></dd>
                <dt>Collected money</dt>
                <dd><?php echo $row['price'];?>$</dd>
            </dl>
            <div class="clear"></div>
            <fieldset>
                <div class="campaignsButtons">
                    <a href="http://dev.bringitlocal.com/<?php echo $row['username']; ?>" target="_blank"class="view">view</a>
                    <a href="http://dev.bringitlocal.com/campaigns,page,edit,section,<?php echo $row["user_id"]?>,campaign_id,members_area?keyword=#<?php echo $row['user_id'];?>;?>" class="edit">edit</a>
                    <a class="delete">delete</a>
                </div>
            </fieldset>
        </li>
        <?php endforeach; ?>

    </ul>
    <fieldset>  <div class="holder"></div></fieldset>
</div>