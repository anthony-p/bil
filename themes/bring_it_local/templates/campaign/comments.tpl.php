<script type="text/javascript">
    $(document).on('ready', function () {
        var comment_textarea = $('textarea[name="comment_text"]'),
            comment_submit = $('#leave-comment');
        comment_textarea.on('blur', function (e) {
            if (comment_textarea.val().trim() != '') {
                comment_submit.removeClass("disabled");
            } else  comment_submit.addClass("disabled");
        });
        comment_submit.on('click', function (e) {
            e.preventDefault();
            if (comment_textarea.val().trim() != '') {
                var dialog = $("#confirm_comment_dialog_box");
                dialog.html("<?= MSG_CAMPAIGN_DIALOG_POST_COMMENT_MSG;?>");
                dialog.dialog({
                    resizable: false,
                    title: "<?= MSG_CAMPAIGN_DIALOG_POST_COMMENT_TITLE;?>",
                    height: 200,
                    width: 500,
                    modal: true,
                    buttons: [
                        {
                            text: "<?= MSG_CAMPAIGN_DIALOG_POST_COMMENT_BTN_OK;?>",
                            click: function () {
                                $('#add-comment').submit();
                                $(this).dialog("close");
                            }
                        },
                        {
                            text: "<?= MSG_CAMPAIGN_DIALOG_POST_COMMENT_BTN_CANCEL;?>",
                            click: function () {
                                $(this).dialog("close");
                            }
                        }
                    ]
                });
            }
        })
    })
</script>

<aside class="announcement user-post">
    <div class="inner">
        <h3><?= MSG_POST_A_COMMENT ?></h3>

        <div class="write_post clrfix">
            <div class="user-photo"><img src="themes/bring_it_local/img/incognito.png"/></div>
            <form name="add_comment" id="add-comment" method="post" action="/campaign/comments.php">
                <input name="compaign" type="hidden" value="<?= $compaignId ?>"/>
                <textarea name="comment_text"></textarea>

                <div class="check">
                    <input name="keep_private" type="checkbox">
                    <label><?= MSG_KEEP_PRIVATE ?></label>
                </div>
                <input class="disabled" type="submit" value="<?= MSG_SEND ?>" id="leave-comment"/>
            </form>
        </div>
        <?php foreach ($comments as $comment): ?>
            <?php
            if ($comment["first_name"] && $comment["last_name"]) {
                $name = $comment["first_name"] . " " . $comment["last_name"];
                $link_user = "/about_me.php?user_id=" . $comment['id'];
            } else {
                $name = "Anonymous";
                $link_user = "#";
            }


            $day = time() - $comment["create_at"];
            $day = floor($day / 86400);

            if ($day < 1) {
                $day = 'today<br />';
            } elseif ($day == 1) {
                $day = 'yesterday<br />';
            } else {
                $day = $day . ' days ago<br />';
            }
            ?>
            <div class="post first">
                <div class="user-photo">
                    <?php if (!empty($comment['avatar'])) {
                        echo '<img style="width:45px" src="' . $comment['avatar'] . '" />';
                    } else {
                        echo '<img src="themes/bring_it_local/img/incognito.png" />';
                    }
                    ?>
                </div>
                <div class="posted-mess">
                    <p>
                        <a href="<?= $link_user ?>"><?= $name ?></a> <?= MSG_POSTED_A_COMMENT ?> <?= $day ?>
                        <?= $comment["comment"] ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div id="confirm_comment_dialog_box" style="display:none;"></div>
</aside>
