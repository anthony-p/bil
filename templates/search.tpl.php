<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

//if ( !defined('INCLUDED') ) { die("Access Denied"); }

//var_dump($campaigns); exit;
?>
<!--<script src="../scripts/jquery/jquery-1.6.3.min"></script>-->
<!--<script>-->
<!--    $(document).ready(function(){-->
<!--//        $("#order_result").click(function(){-->
<!--//            alert(1)-->
<!--//            alert(this.val())-->
<!--//            if (this.val() == "ASC" || this.val() == "DESC") {-->
<!--//                alert(this.val());-->
<!--//            }-->
<!--//        });-->
<!--        $(".order").click(function(){-->
<!--            alert(1)-->
<!--            var value = $("#order_result").val();-->
<!--            alert(value)-->
<!--            if (value == "ASC" || value == "DESC") {-->
<!--                var input = "<input type='hidden' name='order' value='" + value + "'>";-->
<!--                alert(input);-->
<!--                $("#search_by_name").add(input);-->
<!--            }-->
<!--        });-->
<!--//        alert(1)-->
<!--    });-->
<!--</script>-->
<? echo (!empty($no_results_message)) ? $no_results_message : '<br>';?>

<div class="searchBox">
<h2> Search Result </h2>
<form id="search_by_name" accept="#">
        <select id="order_result" name="names" class="changeMe">
            <option value="0" selected="selected" class="order">Sort by</option>
            <option value="ASC" class="order">Date asc</option>
            <option value="DESC" class="order">Date desc</option>
        </select>
  <div class="search-input">
      <input type="text" value="" placeholder="Find by name" name="keyword">
      <button type="submit"></button>
  </div>
</form>
<div class="clear"></div>
    <ul class="list" id="pagination">
    <?php $counter=0; foreach ($campaigns as $campaign):  $counter++; ?>
        <li  class="<?php  if(($counter % 4)==0){ echo "fourth";}?>">
            <div class="img">
                <a href="<?php echo isset ($campaign["username"]) ? '/' . $campaign["username"] : '' ?>">
                    <img src="<?php echo isset ($campaign["banner"]) ? $campaign["banner"] : '' ?>">
                </a>
            </div>
            <div class="clear"></div>
            <div class="campaigns-info">
                <p class="name">
                    <a href="<?php echo isset ($campaign["username"]) ? '/' . $campaign["username"] : '' ?>" class="name-camp">
                        <?php echo isset ($campaign["name"]) ? $campaign["name"] : '' ?>
                    </a>
                    <br>by <a href="/about_me.php?user_id=<?php echo isset($campaign['id']) ? $campaign['id'] : '';?>">
                        <?php echo isset ($campaign["first_name"]) ? $campaign["first_name"] : '' ?> <?php echo isset ($campaign["last_name"]) ? $campaign["last_name"] : '' ?>
                    </a>
                </p>
                <p class="description">
                    <?php echo isset ($campaign["description"]) ? $campaign["description"] : '' ?>
                </p>
                <a href="" class="location"><?php echo isset ($campaign["city"]) ? $campaign["city"] : '' ?></a>
            </div>
            <div class="campaign-details">
                <span class="price"><?php echo isset ($campaign["payment"]) ? $campaign["payment"] : '0' ?>$</span>
                <span class="day"><?php echo isset ($campaign["days_left"]) ? $campaign["days_left"] : '0' ?><span>days left</span></span>
                <div class="clear"></div>
                <?php
                $end_time=$campaign['end_date'];
                $create_time=$campaign['reg_date'];
                $current_time=time();
                $total_time = $end_time- $create_time;
                if ($total_time)
                    $completed =round((($current_time - $create_time) / ($total_time)) * 100);
                else
                    $completed = 100;
                ?>
                <?php if ($campaign['end_date'] > $current_time): ?>
                    <div class="progress">
                        <div style="width: <?php echo $completed < 100 ? $completed : 100; ?>%" class="bar">
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