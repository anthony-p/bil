<script>
    $(document).ready(function () {

        var pagination_wrapper = $(".holder");
        if (pagination_wrapper) {
            if ($.fn.jPages) {
                pagination_wrapper.jPages({
                    containerID: "itemContainer"
                });
            } else {
                $.getScript("/scripts/jquery/pagination.js", function (data, textStatus, jqxhr) {
                    pagination_wrapper.jPages({
                        containerID: "itemContainer"
                    });
                });
            }
        }


    });


</script>
<div class="contributions_page">
    <h2><?= MSG_MEMBERS_AREA_CONTRIBUTIONS_CONTRIBUTION; ?></h2>
    <table id="itemContainer">
        <tr class="table_header">
            <td><h4><?= MSG_MEMBERS_AREA_CONTRIBUTIONS_TBL_DATE; ?></h4></td>
            <td><h4><?= MSG_MEMBERS_AREA_CONTRIBUTIONS_TBL_CAMPAIGN; ?></h4></td>
            <td><h4><?= MSG_MEMBERS_AREA_CONTRIBUTIONS_TBL_AMOUNT; ?></h4></td>
			<td><h4><?= MSG_MEMBERS_AREA_CONTRIBUTIONS_SOURCE; ?></h4></td>
        </tr>
        <?php foreach ($info_contribution_campaigns as $_campaign) : ?>
            <tr>
                <td>
                    <?=date("m-d-Y H:m" , $_campaign["created_at"])?>
                </td>
                <td>
                    <a href="/<?=$_campaign['url']?>"><?=$_campaign['project_title']?></a>
                </td>
                <td>
                    <?=$_campaign['amount']?>
                </td>
				<td style="text-transform: capitalize;">
                    <?=$_campaign['source']?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
    <div class="holder"></div>
    <div>
        <ul>
            <?php for($i = 1; $i <= $total_pages; $i++) : ?>
                <li <?php if ($i == $page_selected) echo 'pagination_selected_page'?> >
                    <a href="/contributions,page,main,section,<?=$i?>,page_selected,members_area"><?=$i?></a>
                </li>
            <?php endfor;?>
        </ul>
    </div>
</div>