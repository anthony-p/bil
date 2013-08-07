
<aside class="announcement user-post">
  <div class="inner">
      <h3>Post a comment</h3>
      <div class="write_post">
          <div class="user-photo"><img src="themes/bring_it_local/img/incognito.png" /></div>
          <form name="add_comment" method="post" action="/campaign/comments.php">
              <input name="compaign" type="hidden" value="<?=$compaignId?>" />
              <textarea name="comment_text"></textarea>
              <div class="check">
                  <input name="keep_private" type="checkbox">
                  <label>keep private</label>
              </div>
              <input type="submit" value="send"/>
          </form>
      </div>
      <?php foreach($comments as $comment): ?>
          <?php
          if ($comment["first_name"] && $comment["last_name"]) {
                $name = $comment["first_name"]." ".$comment["last_name"];
                $link_user = "/about_me.php?user_id=" . $comment['id'] ;
          } else {
                $name = "Anonymous";
                $link_user = "#";
          }



          $day = time() - $comment["create_at"];
          $day = floor($day/86400);

          if ($day < 1){
              $day = 'today<br />';
          }elseif ($day == 1){
              $day = 'yesterday<br />';
          }else{
              $day =$day.' days ago<br />';
          }
            var_dump($comment);
          ?>
      <div class="post first">
          <div class="user-photo">
     <!--         --><?php /*if (!empty($comment['avatar'])) {
                  echo '<img src="'.$comment['avatar'].'" />';
              } else {
                  echo '<img src="themes/bring_it_local/img/incognito.png" />';
              }
              */?>
          </div>
          <div class="posted-mess">
              <p>
                  <a href="<?=$link_user?>"><?=$name ?></a> <?=MSG_POSTED_A_COMMENT?> <?=$day?>
                  <?php /*<?=$name ?> posted an announcement <?=$day?> */ ?>
                  <?=$comment["comment"]?>
              </p>
          </div>
      </div>
      <?php endforeach; ?>
  </div>
</aside>