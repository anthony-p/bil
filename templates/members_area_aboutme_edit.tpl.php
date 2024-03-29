<?
#################################################################
## PHP Pro Bid v6.00															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<link href="/css/tinyeditor.css" rel="stylesheet">
<script type="text/javascript" src="/scripts/jquery/tinymce/tinymce.min.js" ></script>
<script type="text/javascript" src="/scripts/jquery/tinymce/jquery.tinymce.min.js" ></script>
<script type="text/javascript" src="/scripts/init_tinymce.js"></script>
<script type="text/javascript" src="/scripts/jquery/jquery.ui.widget.js"></script>
<script type="text/javascript" src="/scripts/jquery/jquery.iframe-transport.js"></script>
<script type="text/javascript" src="/scripts/jquery/jquery.fileupload.js"></script>
<script type="text/javascript">
    $(document).ready(function(){

        $('#avatar').fileupload({
            dataType: 'json',
            done: function (e, data) {
               $('#avatar_img').attr('src',data.result.path.replace(/\\/g, ''));
               $('#curr_avatar').attr('value',data.result.path.replace(/\\/g, ''));
            }
        });
        $('#member_area_edit_form').tooltip({
            track: true
        });
    });
</script>

<br>
<form action="members_area.php?page=about_me&section=edit" id="member_area_edit_form" method="POST" enctype="multipart/form-data">
<h6 class="tittle_tp">  <?=MSG_MM_ABOUT_ME_PAGE;?> </h6>
<table width="100%" border="0" cellpadding="3" cellspacing="2" class="border about_me_table" >

    <tr class="info_tittle">
        <td><h5><?= MSG_MM_ABOUT_ME_AVATAR; ?></h5></td>
    </tr>
    <tr>
        <td>
        <div id ='avatar-wrap'>
            <div>
                <div class="upload_logo">
                <?php if(!empty($user_details['avatar'])) :?>
                        <img id="avatar_img" src="<?$dt = New DateTime(); echo $user_details['avatar']."?".$dt->format('Y-m-d H:i:s'); ?>"/>
                <?php else: ?>
                    <img id="avatar_img" src="/themes/bring_it_local/img/bring-it-local-no-user-photo.jpg"/>
                <?php endif;?>
            	</div>
            </div>
            <div>
                <input type="file" name="avatar" id="avatar" data-url="members_area.php?page=about_me&section=edit&avatar=true&ajaximageupload=true" accept="image/*" multiple title="avatar file" />
                <input type="hidden" name="first_name" value="<?=$user_details['first_name']?>" />
                <input type="hidden" name="curr_avatar" id="curr_avatar" value="<?=$user_details['avatar']?>" />
                <input type="submit" name="form_aboutme_logo_remove" class="remove" value="<?=MSG_REMOVE_FILE;?>"/>
            </div>
            <div style="clear:both;"></div>
        </div>
    </td>
    </tr>
    <tr class="info_tittle">
        <td><h5><?=MSG_SOCIAL_ACCOUNT_INFORMATION?></h5></td>
    </tr>
    <tr>
        <td>
            <label class="facebook" style="width: 80px;">facebook</label>
            <label class="social-network-url">http://www.facebook.com/</label><input type="text" name="facebook_link" value="<?=str_replace('http://www.facebook.com/', '', $user_details['facebook_link']);?>"/>
            <img src="/images/question_help.png" height="16" alt="help"  title="<?= MSG_FACEBOOK_EXPLANATION_TOOLTIP ?>" style="margin-left: 10px;"></h3>

        </td>
    </tr>
    <tr>
        <td>
            <label class="twitter" style="width: 80px;">twitter</label>
            <label class="social-network-url">http://www.twitter.com/</label><input type="text" name="twitter_link" value="<?=str_replace('http://www.twitter.com/', '', $user_details['twitter_link']);?>" />
            <img src="/images/question_help.png" height="16" alt="help"  title="<?= MSG_TWITTER_EXPLANATION_TOOLTIP ?>" style="margin-left: 10px;"></h3>

        </td>
    </tr>
    <tr>
        <td>
            <label style="width: 80px;">google+</label>
            <label class="social-network-url">https://plus.google.com/</label><input type="text" name="google_link" value="<?=str_replace('https://plus.google.com/', '', $user_details['google_link']);?>" />
            <img src="/images/question_help.png" height="16" alt="help"  title="<?= MSG_GOOGLEPLUS_EXPLANATION_TOOLTIP ?>" style="margin-left: 10px;"></h3>

        </td>
    </tr>
    <tr class="info_tittle">
        <td nowrap="nowrap"><h5><?=MSG_ABOUT_ME_PAGE_CONTENT;?></h5></td>
    </tr>
	<tr class="c1">

      <td colspan="1">
          <textarea id="aboutme_page_content" name="about_me" style="width: 560px; height: 200px;s">
              <?=$user_details['about_me'];?>
          </textarea>

      </td>
   </tr>

	<tr>
      <td colspan="1"><input type="submit" name="form_aboutme_save" value="<?=MSG_SAVE_CHANGES;?>" /></td>
   </tr>
</table>
</form>




