
<div class="contributions_page">
    <h2><?= MSG_MEMBERS_AREA_EARNINGS_EARNINGS; ?></h2>

    <table >
        <tr class="table_header">
            <td><h4><?= MSG_MEMBERS_AREA_EARNINGS_TBL_DATE; ?></h4></td>
            <td><h4><?= MSG_MEMBERS_AREA_EARNINGS_TBL_CAMPAIGN; ?></h4></td>
            <td><h4><?= MSG_MEMBERS_AREA_EARNINGS_TBL_AMOUNT; ?></h4></td>
			<td><h4><?= MSG_MEMBERS_AREA_EARNINGS_TBL_TYPE; ?></h4></td>
			<td><h4><?= MSG_MEMBERS_AREA_EARNINGS_TBL_SOURCE; ?></h4></td>
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
				<td style="text-transform: capitalize;">
                    <?=$_campaign['source']?>
                </td>
				<td>
                    <?php if  ($_campaign["user_id"] != 0) : ?>
                        <a href="/about_me.php?user_id=<?= $_campaign["user_id"] ?>"><?= $_campaign["first_name"]." ".$_campaign["last_name"]; ?></a>
                    <?php else:?>
                        <?php  echo "Anonymous"; ?>
                    <?php endif;?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
    <div class="holder">

        <?php
        if (empty($page_selected)) {
            $page_selected = 1;

        }
        if ($total_pages > 1)  {
            if ($page_selected > 1) $disabledclass = ""; else $disabledclass = "jp-disabled";?>
            <a class="jp-previous <?=$disabledclass?>"><?= MSG_PREV ?></a>
        <?php
        for($i = 1; $i <= $total_pages; $i++) : ?>
            <a href="/earnings,page,main,section,<?=$i?>,page_selected,members_area" class="<?php if ($i == $page_selected) echo 'jp-current'?>"><?=$i?></a>

        <?php endfor;?>

            if ($page_selected < $total_pages)  $disabledclass = ""; else $disabledclass = "jp-disabled";?>
            <a class="jp-next <?=$disabledclass?>"><?= MSG_NEXT ?></a>
        <? } ?>

    </div>

</div>