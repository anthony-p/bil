
<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if (!defined('INCLUDED')) {
    die("Access Denied");
}

include_once('includes/grab_video_thumbnail.php');
?>
<? echo (!empty($no_results_message)) ? $no_results_message : ''; ?>


<script>
    $(document).ready(function () {

        $(".description").each(function () {
            var $minHeight = 72;
            var $height = $(this).height();
            if ($height > $minHeight) {

                $(this).parent(".more_description").addClass("more");
            }
        });

        var pagination_wrapper = $("div.holder");
        if (pagination_wrapper) {
            if ($.fn.jPages) {
                pagination_wrapper.jPages({
                    containerID: "pagination"
                });
            } else {
                $.getScript("/scripts/jquery/pagination.js", function (data, textStatus, jqxhr) {
                    pagination_wrapper.jPages({
                        containerID: "pagination"
                    });
                });
            }
        }


    });

    function select(order, obj) {

        var orderSelected = $(obj).attr('rel');
        $element = $('#order_result');
        $options = $element.find('option');
        $wanted_element = $options.filter(function () {
            return $(this).val() == orderSelected || $(this).text() == orderSelected
        });
        $wanted_element.attr("selected", true);

        $("#search_by_name").submit();
    }

</script>

<div class="searchBox">
    <h2> <?= MSG_ALL_CAMPAIGNS ?>
        <form id="search_by_name" action="campaigns.php">
            <!--
        <select id="order_result" name="names" class="changeMe">
            <option value="0" class="order">Sort by</option>
            <option value="ASC" <?php /*if ($order == "ASC") echo 'selected'; */?> class="order">Date asc</option>
            <option value="DESC" <?php /*if ($order == "DESC") echo 'selected'; */?> class="order">Date desc</option>
        </select>-->

            <div class="search-input">
                <input type="text" value="<?php if (!empty($keyword)) echo $keyword; ?>"
                       placeholder="<?= MSG_MEMBER_AREA_CAMPAIGNS_FIELD_SEARCH_BY_NAME_OR_KEYWORD; ?>" name="keyword">
                <button type="submit"></button>
            </div>
        </form>
    <div class="campaigns-filters">
        <a href="/search.php?order_by=start_date&order_type=DESC">
            <span><?=MSG_NEWCAMPAIGNS;?></span>
        </a>
        <a href="/search.php?order_by=end_date&order_type=ASC">
            <span><?=MSG_ENDING_SOON;?></span>
        </a>
        <a href="<?=$cfc_url;?>">
            <span ><?=MSG_COMMUNITY_FOUND;?></span>
        </a>
        <a href="/search.php?order_by=votes&order_type=DESC">
            <span><?=MSG_VOTE;?></span>
        </a>
    </div>

    </h2>
    <div class="searchBox">
            <div class="holder"></div>

        <div class="clear"></div>
        <ul class="list" id="pagination">
            <?php $counter = 0;
            foreach ($compaigns as $row): $counter++; ?>
                <li class="<?php if (($counter % 4) == 0) {
                    echo "fourth";
                } ?>">
                    <?php
                    if (isset($row['banner'])) {
                        $imageBanner = $row['banner'];
                        if (strpos($imageBanner, 'youtube.com') !== false || strpos($imageBanner, 'youtu.be') !== false || strpos($imageBanner, 'vimeo.com') !== false) {
                            $gvt = new GrabVideoThumbnail($imageBanner, md5($imageBanner));
                            if ($gvt->getThumbnail()) ;
                            $imageBanner = '/' . $gvt->getImage();
                        }
                    }
                    ?>
                    <div class="img">
                        <a href="<?php echo isset ($row["username"]) ? '/' . $row["username"] : '' ?>"><img
                                src="<?php echo isset($imageBanner) ? "/get_image_thumbnail.php?image=200x150_image_" . $imageBanner : ''; ?>"/></a>
                    </div>
                    <div class="clear"></div>
                    <div class="campaigns-info">
                        <p class="name">
                            <a href="<?php echo isset ($row["username"]) ? '/' . $row["username"] : '' ?>"
                               class="name-camp"><?php echo $row['project_title']; ?></a>
                            <br/>by <a href="/about_me.php?user_id=<?php echo isset($row['id']) ? $row['id'] : ''; ?>">

                                <?php if (isset($row['organization']) && $row['organization']): ?>
                                    <?php echo $row['organization']; ?>
                                <?php else: ?>
                                    <?php echo isset ($row["first_name"]) ? $row["first_name"] : '' ?> <?php echo isset ($row["last_name"]) ? $row["last_name"] : '' ?>
                                <?php endif; ?>
                            </a>
                        </p>

                        <div class="more_description">
                            <p class="description">
                                <?php echo $row['description']; ?>
                            </p>
                          </div>

                        <a href="/search.php?city=<?= urlencode($row['city']) ?>"
                           class="location"><?php echo $row['city']; ?></a>
                    </div>
                    <div class="campaign-details clrfix">
                        <?php

                        $end_time = $row['end_date'];
                        $current_time = time();
                        ?>
                        <span class="price">$<?php echo floor($row['payment']); ?></span>

                        <?php if ($current_time > $end_time): ?>
                            <span class="day">0</span>
                        <?php else: ?>
                            <span class="day"><?php $unu = round(($row['end_date'] - time()) / 86400);
                                echo $unu; ?><span> <?= MSG_DAYS_LEFT ?></span></span>
                        <?php endif; ?>

                        <div class="clear"></div>
                        <?php

                        $completed = $row["founddrasing_goal"] ? round(($row["payment"] / $row["founddrasing_goal"]) * 100) : "100";
                        echo $completed . "%";
                        ?>
                        <span class="votes"><?php if (!empty($row['votes'])) {
                                echo $row['votes'];
                            } else {
                                echo '0';
                            } ?> <?= MSG_CAMPAIGNS_VOTES_NUMBER ?></span>
                        <?php if ($current_time > $end_time): ?>
                            <div class="project-unsuccessful"><?= MSG_CLOSED ?></div>
                        <?php else: ?>
                            <div class="progress">
                                <div style="width: <?php echo $completed . "%"; ?>" class="bar">
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>

        </ul>
        <fieldset>
            <div class="holder"></div>
        </fieldset>
    </div>
