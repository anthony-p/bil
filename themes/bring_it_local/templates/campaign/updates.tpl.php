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
                    <?php
                        $time = time();
                        $numDays = round(abs($_update['create_at'] - $time)/60/60/24);
                    ?>
                    <p><a href="#"><?=$_update['first_name'].' '.$_update['last_name']?></a> posted a announcement <?=date("Y-m-d H:i:s", $_update['create_at'])?><br />
                        <?=$_update['comment']?>
                    </p>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</aside>