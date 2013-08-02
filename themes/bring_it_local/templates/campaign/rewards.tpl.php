<?php
/**
 * Created by Lilian Codreanu.
 * User: Lilian Codreanu
 * Date: 5/25/13
 * Time: 1:29 PM
 */
?>

<aside class="announcement rewards">
    <div class="inner">
        <?php $count = 0; foreach ($projectRewards as $_reward) :?>
        <?php $count++; ?>
            <div class="<?php if ($count == 1) { echo 'post first';} else {echo 'post'; }?>">
                <p>
                    <?=$_reward['comment']?>
                </p>
            </div>
        <?php endforeach;?>
       <!-- <div class="post first">
                <p>This is how perks work. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.
                  Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. </p>
           </div>
        <div class="post">
            <h3>Perk 1</h3>
            <p>This is how perks work. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.
                Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. </p>
        </div>
        <div class="post">
            <h3>Perk 2</h3>
            <p>This is how perks work. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.
                Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. </p>
        </div>
        <div class="a-view"><a href="" class="viewall"><span>view all</span></a></div>-->
    </div>
</aside>