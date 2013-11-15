<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

include_once('includes/grab_video_thumbnail.php');
?>
<? echo (!empty($no_results_message)) ? $no_results_message : '<br>';?>

<script>

    function select(order, obj)
    {
        var orderSelected = $(obj).attr('rel');
        console.log(orderSelected);
        $element = $('#order_result');
        $options = $element.find('option');
        $wanted_element = $options.filter(function () {
            return $(this).val() == orderSelected || $(this).text() == orderSelected
        });
        console.log($wanted_element);
        $wanted_element.attr("selected", true);

        $("#search_by_name").submit();
    }

    jQuery(document).ready(function(){

        $(".description").each(function(){
            var $minHeight=72;
            var $height=$(this).height();
            if($height > $minHeight)
            {

                $(this).parent(".more_description").addClass("more");
            }
        });



    });

</script>
<div class="searchBox">
<h2> Search Result
<form id="search_by_name" accept="#">
     <!--   <select id="order_result" name="names" class="changeMe">
            <option value="0" selected="selected" class="order">Sort by</option>
            <option value="ASC" <?php /*if ($order == "ASC") echo 'selected';*/?> class="order">Date asc</option>
            <option value="DESC" <?php /*if ($order == "DESC") echo 'selected';*/?> class="order">Date desc</option>
        </select>-->
  <div class="search-input">
      <input type="text" value="<?php if(!empty($keyword)) echo $keyword;?>" placeholder="Find by name" name="keyword">
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
    <div class="clear"></div>
    <ul class="list" id="pagination">
    <?php $counter=0; foreach ($campaigns as $campaign):  $counter++; ?>
        <li  class="<?php  if(($counter % 4)==0){ echo "fourth";}?>">
            <?php
                if (isset($campaign['banner'])) {
                  $imageBanner =   $campaign['banner'];
                    if (strpos($imageBanner,'youtube.com') !== false || strpos($imageBanner,'youtu.be') !== false || strpos($imageBanner,'vimeo.com') !== false)
                  {
                      $gvt = new GrabVideoThumbnail($imageBanner,md5($imageBanner));
                      if ($gvt->getThumbnail());
                        $imageBanner = '/'.$gvt->getImage();
                  }
                }
            ?>
            <div class="img">
                <a href="<?php echo isset ($campaign["username"]) ? '/' . $campaign["username"] : '' ?>">
                    <img src="<?php echo isset ($imageBanner) ?
                        "/get_image_thumbnail.php?image=200x150_image_" . $imageBanner : '' ?>">
                </a>
            </div>
            <div class="clear"></div>
            <div class="campaigns-info">
                <p class="name">
                    <a href="<?php echo isset ($campaign["username"]) ? '/' . $campaign["username"] : '' ?>" class="name-camp">
                        <?php echo isset ($campaign["project_title"]) ? $campaign["project_title"] : '' ?>
                    </a>
                    <br>by <a href="/about_me.php?user_id=<?php echo isset($campaign['id']) ? $campaign['id'] : '';?>">
                        <?php if (isset($campaign['organization']) && $campaign['organization']): ?>
                            <?php echo $campaign['organization'];?>
                        <?php else: ?>
                            <?php echo isset ($campaign["first_name"]) ? $campaign["first_name"] : '' ?> <?php echo isset ($campaign["last_name"]) ? $campaign["last_name"] : '' ?>
                        <?php endif; ?>
                    </a>
                </p>
                <div class="more_description">
                    <p class="description">
                        <?php echo isset ($campaign["description"]) ? $campaign["description"] : '' ?>
                    </p>
                    <a href="<?php echo isset ($campaign["username"]) ? '/' . $campaign["username"] : '' ?>">..more</a>
                </div>
                <a href="" class="location"><?php echo isset ($campaign["city"]) ? $campaign["city"] : '' ?></a>

            </div>
            <div class="campaign-details">
                <span class="price">$<?php echo isset ($campaign["payment"]) ? $campaign["payment"] : '0' ?></span>
                <span class="day"><?php echo isset ($campaign["days_left"]) ? $campaign["days_left"] : '0' ?><span><?=MSG_DAYS_LEFT?></span></span>
                <div class="clear"></div>
                <?php
                $current_time=time();
                $completed = $campaign["founddrasing_goal"] ? round(($campaign["payment"] / $campaign["founddrasing_goal"]) * 100) : 100;
                echo $completed . "%";
                ?>
                <span class="votes"><?php if (!empty($campaign['votes'])) {
                        echo $campaign['votes'];
                    } else {
                        echo '0';
                    } ?> <?= MSG_CAMPAIGNS_VOTES_NUMBER ?></span>
                <?php if ($campaign['end_date'] > $current_time): ?>
                    <div class="progress">
                        <div class="bar" style="width: <? echo $completed < 100 ? $completed : 100; ?>%">
                        </div>
                    </div>
                <?php elseif ($campaign["payment"]): ?>
                    <div class="project-successful">Successful</div>
                <?php elseif (!$campaign["payment"]): ?>
                    <div class="project-unsuccessful">Closed</div>
                <?php endif; ?>
            </div>
           
        </li>
    <?php endforeach; ?>
    </ul>

 <fieldset> <div class="holder"></div></fieldset>
</div>
 </div>
