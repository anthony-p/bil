<?php if (isset($compaignData["banner"]) && $compaignData["banner"]): ?>

<?
    $isVideo = 0;
    $htmlOut = '';
        if (file_exists(getcwd()."/".$compaignData["banner"])) {
            echo "<img src ='".$compaignData["banner"]."'/>";
        } else {
            $baner = $compaignData["banner"];
            if (strpos($baner,"youtube")) {
                $res = explode("?v=", $baner);
                $idYoutubeVideo = substr($res[1], 0, 11); // standart lenght youtube Id;
                $embedUrl = "http://www.youtube.com/embed/" . $idYoutubeVideo;
                //$baner = str_replace("watch?v=","embed/",$baner);
                $htmlOut = '<iframe  src="'.$embedUrl.'"frameborder="0" allowfullscreen width="560" height="315" ></iframe>';
                $isVideo = 1;
            } else if (strpos($baner,"youtu.be")) {
                $res = explode("/", $baner);
                $idYoutubeVideo = $res[3]; // standart lenght youtube Id;
                $embedUrl = "http://www.youtube.com/embed/" . $idYoutubeVideo;
                $htmlOut = '<iframe  src="'.$embedUrl.'"frameborder="0" allowfullscreen width="560" height="315"></iframe>';
                $isVideo = 1;
            } else if (strpos($baner,"vimeo")) {
                $htmlOut = '<iframe  src="'.$baner.'"frameborder="0" allowfullscreen width="560" height="315"></iframe>';
                $isVideo = 1;
            } else {
                $htmlOut = "<img src ='".$baner."'/>";
            }
        }
        ?>

    <div class="video" style="<?php if ($isVideo == 0) echo 'height: auto;' ?>">
        <?
               echo $htmlOut;
        ?>
    </div>
<?php endif; ?>
<aside class="nav-social clfix">
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
                        <a target="_blank" href="<?php
                        if (isset($compaignData['facebook_url']) && $compaignData['facebook_url']) {
                            if (strpos($compaignData['facebook_url'], 'http') === 0) {
                                echo urldecode($compaignData['facebook_url']);
                            } else {
                                echo 'http://' . urldecode($compaignData['facebook_url']);
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
                    <a target="_blank" href="<?php
                    if (isset($compaignData['twitter_url']) && $compaignData['twitter_url']) {
                        if (strpos($compaignData['twitter_url'], 'http') === 0) {
                            echo urldecode($compaignData['twitter_url']);
                        } else {
                            echo 'http://' . urldecode($compaignData['twitter_url']);
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
                <?php
                if (strpos($compaignData['url'], 'http') === 0) {
                    $website_url = urldecode($compaignData['url']);
                } else {
                    $website_url = 'http://' . urldecode($compaignData['url']);
                }
                ?>
                    <a target="_blank" href="<? echo urldecode($website_url); ?>" target="_blank">Website</a>
                    <!--            <label></label>-->
                </li>
            <?php endif; ?>

            </ul>
        </div>
    <?php endif; ?>
</aside>

<aside class="info">
    <div class="inner">
        <p><?=html_entity_decode($compaignData["campaign_basic"])?></p>
    </div>
</aside>

<aside class="donation">
  <div class="inner">
      <p><?=MSG_MAKE_HAPPEN?><span><?php echo $compaignData['project_title']; ?></p></span>
	  <?php global $session; ?>
      <?php if ($compaignData['probid_user_id'] != $session->value('user_id') && $compaignData['active'] != 2 && ($compaignData['end_date']-time())>0 ): ?>
          <a href="donate.php?np_userid=<?php echo $compaignData['user_id']; ?>"
             class="donation campaign_donation">
              <span class="uper"><?=MSG_DONATE_NOW?></span>
              <span><?=MSG_MAKE_DONATION?></span>
          </a>
      <?php endif; ?>
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
