<div class="contributions_page">
    <h2><?= MSG_MEMBERS_AREA_EARNINGS_EARNINGS; ?></h2>

    <table>
        <tr class="table_header">
            <td><h4><?= MSG_MEMBERS_AREA_EARNINGS_TBL_DATE; ?></h4></td>
            <td><h4><?= MSG_MEMBERS_AREA_EARNINGS_TBL_CAMPAIGN; ?></h4></td>
            <td><h4><?= MSG_MEMBERS_AREA_EARNINGS_TBL_AMOUNT; ?></h4></td>
        </tr>
        <?php foreach ($info_earning_campaigns as $_campaign) : ?>
            <tr>
                <td width="150">
                    <?=date("m-d-Y H:i" , $_campaign["created_at"])?>
                </td>
                <td>
                    <a href="/<?=$_campaign['url']?>"><?=$_campaign['project_title']?></a>
                </td>
                <td>
                    <?=$_campaign['amount']?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
    <div>
        <ul>
            <?php for($i = 1; $i <= $total_pages; $i++) : ?>
                <li <?php if ($i == $page_selected) echo 'pagination_selected_page'?> >
                    <a href="/earnings,page,main,section,<?=$i?>,page_selected,members_area"><?=$i?></a>
                </li>
            <?php endfor;?>
        </ul>
    </div>
</div>