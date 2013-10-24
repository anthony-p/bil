<div class="contributions_page">
    <h2><?= MSG_MEMBERS_AREA_CONTRIBUTIONS_CONTRIBUTION; ?></h2>
    <table>
        <tr class="table_header">
            <td><h4><?= MSG_MEMBERS_AREA_CONTRIBUTIONS_TBL_DATE; ?></h4></td>
            <td><h4><?= MSG_MEMBERS_AREA_CONTRIBUTIONS_TBL_CAMPAIGN; ?></h4></td>
            <td><h4><?= MSG_MEMBERS_AREA_CONTRIBUTIONS_TBL_AMOUNT; ?></h4></td>
            <td><h4><?= MSG_MEMBERS_AREA_CONTRIBUTIONS_TBL_VISIBILITY; ?></h4></td>
            <!--<td><h4><? //MSG_MEMBERS_AREA_CONTRIBUTIONS_TBL_PP_STATUS; ?></h4></td>-->
			<td><h4><?= MSG_MEMBERS_AREA_CONTRIBUTIONS_SOURCE; ?></h4></td>
        </tr>
        <?php foreach ($info_contribution_campaigns as $_campaign) : ?>
            <tr>
                <td>
                    <?=date("m-d-Y H:m" , $_campaign["created_at"])?>
                </td>
                <td>
                    <?=$_campaign['project_title']?>
                </td>
                <td>
                    <?=$_campaign['amount']?>
                </td>
                <td>
                    <?php if  ($_campaign["user_id"] != 0) : ?>
                        <?php echo $_campaign["first_name"] . " " . $_campaign["last_name"]; ?>
                    <?php else:?>
                        <?php  echo "Anonymous"; ?>
                    <?php endif;?>
                </td>
				<!--
                <td>
                    <?php //if (isset($_campaign["confirmed_paypal_email"]) && $_campaign["confirmed_paypal_email"]): ?>
                        <span class="paypal_checked"></span>
                    <?php //endif; ?>
                </td>
				-->
				<td style="text-transform: capitalize;">
                    <?=$_campaign['source']?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
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