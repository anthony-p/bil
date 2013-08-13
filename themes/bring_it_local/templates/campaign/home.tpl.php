<?php if (isset($compaignData["banner"]) && $compaignData["banner"]): ?>
    <div class="video" style="height: auto;">
        <?
        //var_dump($compaignData);
        if (file_exists(getcwd()."/".$compaignData["banner"])) {
            echo "<img src ='".$compaignData["banner"]."'/>";
        } else {
            $baner = $compaignData["banner"];
            if (strpos($baner,"youtube"))
                $baner = str_replace("watch?v=","embed/",$baner);
            echo '<iframe  src="'.$baner.'"frameborder="0" allowfullscreen></iframe>';
        }
        ?>
    </div>
<?php endif; ?>
<aside class= nav-social>
    <?php
    if ((isset($compaignData['facebook_url']) && $compaignData['facebook_url']) ||
        (isset($compaignData['twitter_url']) && $compaignData['twitter_url']) ||
        (isset($compaignData['url']) && $compaignData['url'])):
        ?>
        <div class="inner">
            <h5><?=MSG_FIND_THIS_CAMPAIGN?>:</h5>
            <ul>
                <?php if (isset($compaignData['facebook_url']) && $compaignData['facebook_url']): ?>
                    <li class="a_facebook">
                        <a href="<?php
                        if (isset($compaignData['facebook_url']) && $compaignData['facebook_url']) {
                            if (strpos($compaignData['facebook_url'], 'http') === 0) {
                                echo $compaignData['facebook_url'];
                            } else {
                                echo 'http://' . $compaignData['facebook_url'];
                            }
                        } else {
                            echo '#';
                        }
                        ?>">Facebook</a>
                        <!--            <label></label>-->
                    </li>
                <?php endif; ?>
                <?php if (isset($compaignData['twitter_url']) && $compaignData['twitter_url']): ?>
                <li class="a_twitter">
                    <a href="<?php
                    if (isset($compaignData['twitter_url']) && $compaignData['twitter_url']) {
                        if (strpos($compaignData['twitter_url'], 'http') === 0) {
                            echo $compaignData['twitter_url'];
                        } else {
                            echo 'http://' . $compaignData['twitter_url'];
                        }
                    } else {
                        echo '#';
                    }
                    ?>">Twitter</a>
                    <?php endif; ?>
                    <!--            <label></label>-->
                </li>
                <li>
                    <!--            <a href="<?
//            if (!file_exists(getcwd()."/".$compaignData["banner"])) {
//                echo "<img src ='".$compaignData["banner"]."'/>";
//            } else {
//                echo '<iframe  src="'.$compaignData["banner"].'"frameborder="0" allowfullscreen></iframe>';
//            }
            ?>
            ">Youtube</a>
<!--            <label></label>-->
                    <!--        </li>-->
                    <?php if (isset($compaignData['url']) && $compaignData['url']): ?>
                <li>
                    <a href="http://<? echo $compaignData["url"]; ?>" target="_blank">Website</a>
                    <!--            <label></label>-->
                </li>
            <?php endif; ?>

            </ul>
        </div>
    <?php endif; ?>
</aside>
<?php
// var_dump($compaignData);
?>
<aside class="info">
    <p><?=$compaignData["campaign_basic"]?></p>
</aside>
<aside class="donation">
  <div class="inner">
      <p>Help make it happen! Support <span><?php echo $compaignData['name']; ?></p></span>
      <a href="donate.php?np_userid=<?php echo $compaignData['user_id']; ?>" class="donation">
          <span class="uper">Donate Now</span>
          <span>make a donation</span>
      </a>
  </div>
</aside>
<aside class="social">
    <div class="inner">
        <h5>Share</h5>
        <ul>
            <li>
                <fb:like href="http://www.facebook.com/bringitlocal" send="false" layout="button_count" width="450" show_faces="false"></fb:like>
                <div id="fb-root"></div>
                <script>(function(d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id)) return;
                        js = d.createElement(s); js.id = id;
                        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
                        fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));</script>
            </li>
            <li>
                <g:plusone size="medium"></g:plusone>
                <script type="text/javascript">
                    (function() {
                        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                        po.src = 'https://apis.google.com/js/plusone.js';
                        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                    })();
                </script>
            </li>
            <li class="buttons"><a href=""><span>embed</span></a></li>
            <li class="buttons"><a href=""><span>email</span></a></li>
        </ul>
    </div>
</aside>
