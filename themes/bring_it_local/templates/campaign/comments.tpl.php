<link type="text/css" rel="stylesheet" href="/css/ui-darkness/jquery-ui-1.10.3.custom.css">

<script type="text/javascript">
    $(document).ready(function(){

        $("#add_comment_btn_submit").click(function(){

            $("#comment-dialog-confirm").dialog({
                resizable: false,
                height: 200,
                width: 450,
                modal: true,
                buttons: {
                    "<?= MSG_CAMPAIGN_DIALOG_POST_COMMENT_BTN_OK; ?>": function () {

                        $(this).dialog("close");
                        $("#add_comment").submit();
                        return true;
                    },
                    "<?= MSG_CAMPAIGN_DIALOG_POST_COMMENT_BTN_CANCEL; ?>": function () {
                        $(this).dialog("close");
                        return false;
                    }
                }
            });

            return false;

        });


    });
</script>

<aside class="announcement user-post">
  <div class="inner">
      <h3><?=MSG_POST_A_COMMENT?></h3>
      <div class="write_post">
          <div class="user-photo"><img src="themes/bring_it_local/img/incognito.png" /></div>
          <form name="add_comment" id="add_comment" method="post" action="/campaign/comments.php">
              <input name="compaign" type="hidden" value="<?=$compaignId?>" />
              <textarea name="comment_text"></textarea>
              <div class="check">
                  <input name="keep_private" type="checkbox">
                  <label><?=MSG_KEEP_PRIVATE?></label>
              </div>
              <input type="submit" id="add_comment_btn_submit" value="<?=MSG_SEND?>"/>
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
          ?>
      <div class="post first">
          <div class="user-photo">
              <?php if (!empty($comment['avatar'])) {
                  echo '<img style="width:45px" src="'.$comment['avatar'].'" />';
              } else {
                  echo '<img src="themes/bring_it_local/img/incognito.png" />';
              }
              ?>
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

    <div id="comment-dialog-confirm" title="<?= MSG_CAMPAIGN_DIALOG_POST_COMMENT_TITLE; ?>" style="display: none;">
        <br>
        <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><?= MSG_CAMPAIGN_DIALOG_POST_COMMENT_MSG; ?></p>
    </div>

</aside>
