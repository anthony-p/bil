<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }

include_once('includes/grab_video_thumbnail.php');
?>
<? echo (!empty($no_results_message)) ? $no_results_message : '<br>';?>

<script language=JavaScript src='/scripts/jquery/jquery-1.9.1.js'></script>

<script>
    $(document).ready(function(){

            $(".description").each(function(){
                var $minHeight=72;
                var $height=$(this).height();
                if($height > $minHeight)
                {

                    $(this).parent(".more_description").addClass("more");
                }
            });

        $(function() {
                    if ($("div.holder")){
                        if ($.fn.jPages){
                            $("div.holder").jPages({
                                containerID: "pagination"
                            });
                        } else {
                            $.getScript("/scripts/jquery/pagination.js", function(data, textStatus, jqxhr) {
                                $("div.holder").jPages({
                                    containerID: "pagination"
                                });
                            });
                        }
                    }
                });

        });

</script>

<div class="searchBox">
<h2> <?=MSG_ALL_CAMPAIGNS?></h2>
<form id="search_by_name" accept="#">
    <!--    <div class="select">-->
    <select id="order_result" name="names" class="changeMe">
        <option value="0" selected="selected" class="order">Sort by</option>
        <option value="ASC" class="order">Date asc</option>
        <option value="DESC" class="order">Date desc</option>
    </select>
    <!--    </div'>-->
    <div class="search-input">
        <input type="text" value="" placeholder="Find by name" name="keyword">
        <button type="submit"></button>
    </div>
</form>
<!--<div class="select">-->
<!--     <select id="order_result" name="names" class="changeMe">-->
<!--        <option value="0" selected="selected" class="order">Sort by</option>-->
<!--        <option value="ASC" class="order">Date asc</option>-->
<!--        <option value="DESC" class="order">Date desc</option>-->
<!--      </select>-->
<!-- </div>-->

<div class="clear"></div>
    <ul class="list" id="pagination">
        <?php $counter=0; foreach( $compaigns as $row): $counter++;?>
            <li class="<?php  if(($counter % 4)==0){ echo "fourth";}?>">
                <?php
                    if (isset($row['banner'])) {
                      $imageBanner =   $row['banner'];
                        if (strpos($imageBanner,'youtube.com') !== false || strpos($imageBanner,'youtu.be') !== false || strpos($imageBanner,'vimeo.com') !== false)
                      {
                          $gvt = new GrabVideoThumbnail($imageBanner,md5($imageBanner));
                          if ($gvt->getThumbnail());
                            $imageBanner = '/'.$gvt->getImage();
                      }
                    }
                ?>
                <div class="img">
                    <a href="<?php echo isset ($row["username"]) ? '/' . $row["username"] : '' ?>"><img src="<?php echo isset($imageBanner) ? "/get_image_thumbnail.php?image=200x165_image_" . $imageBanner : '';?>"/></a></div>
                <div class="clear"></div>
                <div class="campaigns-info">
                    <p class="name">
                        <a href="<?php echo isset ($row["username"]) ? '/' . $row["username"] : '' ?>" class="name-camp"><?php echo $row['project_title'];?></a>
                        <br/>by <a href="/about_me.php?user_id=<?php echo isset($row['id']) ? $row['id'] : '';?>">

                            <?php if (isset($row['organization']) && $row['organization']): ?>
                                <?php echo $row['organization'];?>
                            <?php else: ?>
                                <?php echo isset ($row["first_name"]) ? $row["first_name"] : '' ?> <?php echo isset ($row["last_name"]) ? $row["last_name"] : '' ?>
                            <?php endif; ?>
                        </a>
                    </p>
                    <div class="more_description">
                        <p class="description">
                                               <?php echo $row['description'];?>
                                           </p>
                        <a href="<?php echo isset ($row["username"]) ? '/' . $row["username"] : '' ?>">..more</a>
                                                </div>

                    <a href="/search.php?city=<?=urlencode($row['city'])?>" class="location"><?php echo $row['city'];?></a>
                </div>
                <div class="campaign-details">
                    <?php

                    $end_time=$row['end_date'];
                    //                    $create_time=$row['reg_date'];
                    $current_time=time();
                    ?>
                    <span class="price">$<?php echo $row['payment'];?></span>
                    <?php if ($current_time > $end_time): ?>
                        <span class="day">0</span>
                    <?php else: ?>
                        <span class="day"><?php $unu=round(($row['end_date']-time())/86400); echo $unu; ?><span> <?=MSG_DAYS_LEFT?></span></span>
                    <?php endif;?>

                    <div class="clear"></div>
                    <?php

//                    $completed =round((($current_time - $create_time) / ($end_time- $create_time)) * 100);
                    $completed =$row["founddrasing_goal"] ? round(($row["payment"] / $row["founddrasing_goal"]) * 100) : "100";
                    echo  $completed."%";
                    ?>
                    <?php if ($current_time > $end_time): ?>
                        <div class="project-unsuccessful"><?=MSG_CLOSED?></div>
                    <?php else: ?>
                        <div class="progress">
                            <div style="width: <?php echo  $completed."%"; ?>" class="bar">
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </li>
        <?php endforeach; ?>

    </ul>
<fieldset>    <div class="holder"></div></fieldset>
</div>
