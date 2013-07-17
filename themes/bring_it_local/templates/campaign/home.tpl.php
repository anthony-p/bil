<div class="video">
<?
//var_dump($compaignData);
 if (file_exists(getcwd()."/".$compaignData["banner"])) {
     echo "<img src ='".$compaignData["banner"]."'/>";
} else {
     $baner = $compaignData["banner"];
     if (strpos($baner,"youtube"))
         $baner = str_replace("http:","",$baner);
     echo '<iframe  src="'.$baner.'"frameborder="0" allowfullscreen></iframe>';
}
?>
</div>

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
<?php
// var_dump($compaignData);
?>
<aside class="info">
    <p><?=$compaignData["campaign_basic"]?></p>
</aside>
<aside class="donation">
  <div class="inner">
      <p>Help make it happen! Support <span>Destiny Arts -Finance the move fund drive</p></span>
      <a href="donate.php" class="donation">
          <span class="uper">Donate Now</span>
          <span>make a donation</span>
      </a>
  </div>
</aside>
<aside class= nav-social>
<div class="inner">
    <h5>Also find this Campaign On:</h5>
    <ul>
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
<!--            <label></label>-->
        </li>
        <li>
            <a href="<?
//            if (!file_exists(getcwd()."/".$compaignData["banner"])) {
//                echo "<img src ='".$compaignData["banner"]."'/>";
//            } else {
//                echo '<iframe  src="'.$compaignData["banner"].'"frameborder="0" allowfullscreen></iframe>';
//            }
//            ?>
            ">Youtube</a>
<!--            <label></label>-->
        </li>
        <li>
            <a href="http://<? echo $compaignData["url"]; ?>" target="_blank">Website</a>
<!--            <label></label>-->
        </li>

    </ul>
</div>
</aside>