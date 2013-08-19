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
                        <br/>by <a href="#"><?php echo $row['first_name']."  ".$row['last_name'];?></a>
                    </p>
                    <p class="description">
                        <?php echo $row['description'];?>
                    </p>
                    <a href="" class="location"><?php echo $row['city'];?></a>
                </div>
                <div class="campaign-details">
                    <span class="price">$<?php echo $row['payment'];?></span>
                    <span class="day"><?php $unu=round(($row['end_date']-time())/86400); echo $unu; ?><span> days left</span></span>
                    <div class="clear"></div>
                    <?php
                    $end_time=$row['end_date'];
                    $create_time=$row['reg_date'];
                    $current_time=time();
                    $completed =round((($current_time - $create_time) / ($end_time- $create_time)) * 100);
                    echo  $completed."%";
                    ?>
                    <?php if ($current_time > $end_time): ?>
                        <div class="project-unsuccessful">Closed</div>
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
