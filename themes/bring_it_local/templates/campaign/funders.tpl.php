<?php
//var_dump($funders);
/**
 * Created by Lilian Codreanu.
 * User: Lilian Codreanu
 * Date: 5/25/13
 * Time: 1:28 PM
 */
?>

<aside class="announcement funders">
<!--    <div class="inner">-->
<!--        <div class="post first">-->
<!--            <div class="user-photo"><img src="themes/bring_it_local/img/user-photo.png" /></div>-->
<!--            <div class="posted-mess">-->
<!--                <a href="" >Chris Smith</a>-->
<!--                <span>12 hours ago</span>-->
<!--                <span>$222</span>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->

    <?php foreach($funders as $funder): ?>
        <div class="inner">
            <div class="post first">
                <div class="user-photo">
                    <?php if (!empty($funder['avatar'])) {
                        echo '<img style="width:45px" src="'.$funder['avatar'].'" />';
                    } else {
                        echo '<img src="themes/bring_it_local/img/incognito.png" />';
                    } ?>
                </div>
                <div class="posted-mess">
                    <?php if  ($funder["user_id"] != 0) : ?>
                        <a href="/about_me.php?user_id=<?php echo isset($funder['id']) ? $funder['id'] : ''; ?>" >
                            <?php echo $funder["first_name"] . " " . $funder["last_name"]; ?>
                        </a>
                    <?php else:?>
                        <a href="#" >
                            <?php  echo "Anonymous"; ?>
                        </a>
                    <?php endif;?>
                    <span>
                        <?php
                        $elapsed_time = time() - $funder["created_at"];
                        if ($elapsed_time < 86400) {
                            $hours = (int)($elapsed_time / 3600);
                            $minutes = (int)(($elapsed_time % 3600) / 60);
                            $time = '';
                            if ($hours)
                                $time .= $hours . ' hour(s) ';
                            if ($minutes)
                                $time .= $minutes . ' minute(s) ';
                            if ($time)
                                $time .= 'ago';
                            echo $time;
                        } else {
                            echo date("m-d-Y H:m" , $funder["created_at"]);
                        }
                        ?>
                    </span>
                    <span>$<?php echo isset($funder["amount"]) ? $funder["amount"] : '0'; ?>
                    	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                    	  <?php if ($funder["source"]=="click through"){ ?>
                    	  	<?=MSG_FUNDERS_CLICKTHROUGH?>
                    	  <?php }elseif ($funder["source"]=="auction"){?>
                    	  	<?=MSG_FUNDERS_AUCTION?>
                    	  <?}else{?>
                    	  	<?=MSG_FUNDERS_DONATION?>
                    	  <?php }?>
                    </span>
                    
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</aside>