<?php

/**

 * Created by Lilian Codreanu.

 * User: Lilian Codreanu

 * Date: 5/25/13

 * Time: 1:28 PM

 */

?>

<aside class="announcement">
    <div class="inner">
      <a href="#">All Updates</a><span>Campaign Announcements Only</span>
        <div class="post first">
            <?php $count = 0; foreach ($projectUpdates as $_update) :?>
                <?php $count++; ?>
                <div class="<?php if ($count == 1) { echo'posted-mess first';} else {echo 'post'; }?>">
                    <div class="user-photo">
                        <?php if (!empty($_update['avatar'])) {
                            echo '<img style="width:45px" src="'.$_update['avatar'].'" />';
                        } else {
                            echo '<img src="themes/bring_it_local/img/incognito.png" />';
                        }
                        ?>
                    </div>
                    <?php
                        $day = time() - $_update["create_at"];
                        $day = floor($day/86400);

                        if ($day < 1){
                            $day = 'today<br />';
                        }elseif ($day == 1){
                            $day = 'yesterday<br />';
                        }else{
                            $day =$day.' days ago<br />';
                        }
               /*         $time = time();
                        $numDays = round(abs($_update['create_at'] - $time)/60/60/24);*/
                    ?>
                    <p><a href="/about_me.php?user_id=<?php echo isset($_update['id']) ? $_update['id'] : ''; ?>""><?=$_update['first_name'].' '.$_update['last_name']?></a> posted an update <?=$day?><br />
                        <?=html_entity_decode($_update['comment'])?>
                    </p>
                </div>
            <?php endforeach;?>
        </div>

    </div>

</aside>