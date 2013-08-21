
<?php
/**
 * Created by Lilian Codreanu.
 * User: Lilian Codreanu
 * Date: 5/25/13
 * Time: 1:28 PM
 */
?>
<?php
//var_dump($partners);
$count = 0;
$column = 4;
$first = 0;
?>
<aside class="suport">
    <?php foreach($partners as $parner):?>
        <?php if ($count == 0): ?>
            <div class="row shop">
        <?php endif; ?>

            <?php if ($first == 0): $first = 1;?>
                <div class="viewall">
                    <div class="text">
                        <h5>Clickthrough shopping</h5>
                        <div class="slide">
                            <ul><li class="border"></li></ul>
                        </div>
                    </div>
                    <a href=""> see all</a>
                </div>
            <?php endif; ?>

        <?php if ($count == 0): ?>
            <ul class="rows-list">
        <?php endif; ?>

            <li <?php if ($count == $column-1) echo 'class="last"' ?> >
                <div class="img">
                    <?php echo html_entity_decode($parner['big_banner_code']); ?>
                </div>
                <div class="info">
                    <h3><?php echo trim($parner['name']) ?></h3>
                    <span><?php echo (int) $parner['advert_pct']; ?>% giveback</span>
                    <a href="<?php echo $parner['advert_url'];  ?>"> Shop on site now</a>
                </div>
            </li>



        <?php if ($count == $column-1): $count = -1; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php $count++; ?>
    <?php endforeach ?>

<!--    <div class="row ">-->
<!--            <div class="viewall">-->
<!--            <div class="text">-->
<!--                <h5>Local Merchants</h5>-->
<!--                <div class="slide">-->
<!--                    <ul><li class="border"></li></ul>-->
<!--                </div>-->
<!--            </div>-->
<!--            <a href=""> see all</a>-->
<!--        </div>-->
<!--        <ul class="rows-list merchants">-->
<!--            <li>-->
<!--                <div class="img">-->
<!--                    <img src="themes/bring_it_local/img/test3.png" />-->
<!--                </div>-->
<!--                <div class="info">-->
<!--                    <h3>Boxing For Health - Kids & Teen Classes </h3>-->
<!--                    <span>$40.00</span>-->
<!--                </div>-->
<!--            </li>-->
<!--            <li>-->
<!--                <div class="img">-->
<!--                    <img src="themes/bring_it_local/img/test3.png" />-->
<!--                </div>-->
<!--                <div class="info">-->
<!--                    <h3>Boxing For Health - Kids & Teen Classes </h3>-->
<!--                    <span>$40.00</span>-->
<!--                </div>-->
<!--            </li>-->
<!--            <li>-->
<!--                <div class="img">-->
<!--                    <img src="themes/bring_it_local/img/test3.png" />-->
<!--                </div>-->
<!--                <div class="info">-->
<!--                    <h3>Boxing For Health - Kids & Teen Classes </h3>-->
<!--                    <span>$40.00</span>-->
<!--                </div>-->
<!--            </li>-->
<!--            <li class="last">-->
<!--                <div class="img">-->
<!--                    <img src="themes/bring_it_local/img/test3.png" />-->
<!--                </div>-->
<!--                <div class="info">-->
<!--                    <h3>Boxing For Health - Kids & Teen Classes </h3>-->
<!--                    <span>$40.00</span>-->
<!--                </div>-->
<!--            </li>-->
<!--        </ul>-->
<!--    </div>-->

    <?php
        //Temporary disable this section
    /*
    ?>
    <div class="row ">
           <div class="viewall">
            <div class="text">
                <h5>Auctions</h5>
                <div class="slide">
                    <ul><li class="border"></li></ul>
                </div>
            </div>
            <a href=""> see all</a>
        </div>
        <ul class="rows-list auctions">
            <li>
                <div class="img">
                    <img src="themes/bring_it_local/img/test2.png" />
                </div>
                <div class="info">
                    <h3>Retro Hot Pink Placecards with Ribbon - Set of 20 </h3>
                    <span>$32.00</span>

                </div>
            </li>
            <li>
                <div class="img">
                    <img src="themes/bring_it_local/img/test2.png" />
                </div>
                <div class="info">
                    <h3>Retro Hot Pink Placecards with Ribbon - Set of 20 </h3>
                    <span>$32.00</span>

                </div>
            </li>
            <li>
                <div class="img">
                    <img src="themes/bring_it_local/img/test2.png" />
                </div>
                <div class="info">
                    <h3>Retro Hot Pink Placecards with Ribbon - Set of 20 </h3>
                    <span>$32.00</span>

                </div>
            </li>
            <li class="last">
                <div class="img">
                    <img src="themes/bring_it_local/img/test3.png" />
                </div>
                <div class="info">
                    <h3>Retro Hot Pink Placecards with Ribbon - Set of 20 </h3>
                    <span>$32.00</span>

                </div>
            </li>
        </ul>
    </div>

    <?php */ ?>

</aside>