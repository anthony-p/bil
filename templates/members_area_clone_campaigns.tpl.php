<div class="contributions_page">
    <h2>Clone Campaigns</h2>

    <table>
        <tr class="table_header">
            <td><h4>Date</h4></td>
            <td><h4>Campaign</h4></td>
            <td><h4>Visibility</h4></td>
        </tr>
        <?php foreach ($info_clone_campaigns as $_campaign) : ?>
            <tr>
                <td>
                    <?/*=date("m-d-Y H:m" , $_campaign["created_at"])*/?>
                </td>
                <td>
                    <?=$_campaign['project_title']?>
                </td>

                <td>
                    <?php if  ($_campaign["probid_user_id"] != 0) : ?>
                        <?php echo $_campaign["first_name"] . " " . $_campaign["last_name"]; ?>
                    <?php else:?>
                        <?php  echo "Anonymous"; ?>
                    <?php endif;?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
    <div>
        <ul>
            <?php for($i = 1; $i <= $total_pages; $i++) : ?>
                <li <?php if ($i == $page_selected) echo 'pagination_selected_page'?> >
                    <a href="/clone_campaigns,page,main,section,<?=$i?>,page_selected,members_area"><?=$i?></a>
                </li>
            <?php endfor;?>
        </ul>
    </div>
</div>