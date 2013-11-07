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
<script type="text/javascript">
    $(document).ready(function(){

        $("#member_area_edit_form").submit(function(){

            var err_status = false;

            if ($('#avatar').val() !== '') {
                var ext = $('#avatar').val().split('.').pop().toLowerCase();
                if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                    $("#avatar").addClass("error");
                    err_status = true;
                } else {
                    $("#avatar").removeClass("error");
                }
            }

            return !err_status;

        });
//        init_tinymce('#aboutme_page_content');
    });
</script>

<br>
<form action="members_area.php?page=about_me&section=edit" id="member_area_edit_form" method="POST" enctype="multipart/form-data">
 <h6 class="tittle_tp">  <?=MSG_MM_ABOUT_ME_PAGE;?> </h6>
<table width="100%" border="0" cellpadding="3" cellspacing="2" class="border about_me_table" >

    <tr class="info_tittle">
        <td><h5>Logo</h5></td>
    </tr>
    <tr>
        <td>
        <div>
            <div style="float:left">
            <?php if(!empty($user_details['avatar'])) :?>
                <div class="upload_logo">
                    <img id="avatar_img" src="<?$dt = New DateTime(); echo $user_details['avatar']."?".$dt->format('Y-m-d H:i:s'); ?>"/>
                </div>
            <?php endif;?>
            </div>            
            <div style="float:right">
                <input type="file" name="avatar" id="avatar" accept="image/*" multiple title="avatar file" />
                <input type="hidden" name="first_name" value="<?=$user_details['first_name']?>" />
                <input type="hidden" name="curr_avatar" id="curr_avatar" value="<?=$user_details['avatar']?>" />
			    <input type="submit" name="form_aboutme_save" value="<?=MSG_UPLOAD_FILE;?>" style="float: none; margin: 72px 0 0 25px;" />                                       
            </div>
            <div style="clear:both;"></div>
        </div>
        <?php if(!empty($user_details['avatar'])) :?>
        <div class="remove_logo">
            <input type="submit" name="form_aboutme_logo_remove" class="remove" value="<?=MSG_REMOVE_FILE;?>"/>
        </div>
        <?php endif;?>
    </td>
    </tr>
    <tr class="info_tittle">
        <td><h5><?=MSG_SOCIAL_ACCOUNT_INFORMATION?></h5></td>
    </tr>
    <tr>
        <td>
            <label class="facebook" style="width: 80px;">facebook</label>
            <label class="social-network-url">http://www.facebook.com/</label><input type="text" name="facebook_link" value="<?=str_replace('http://www.facebook.com/', '', $user_details['facebook_link']);?>"/>
        </td>
    </tr>
    <tr>
        <td>
            <label class="twitter" style="width: 80px;">twitter</label>
            <label class="social-network-url">http://www.twitter.com/</label><input type="text" name="twitter_link" value="<?=str_replace('http://www.twitter.com/', '', $user_details['twitter_link']);?>" />
        </td>
    </tr>
    <tr>
        <td>
            <label style="width: 80px;">google+</label>
            <label class="social-network-url">https://plus.google.com/</label><input type="text" name="google_link" value="<?=str_replace('https://plus.google.com/', '', $user_details['google_link']);?>" />
        </td>
    </tr>
    <tr class="info_tittle">
        <td nowrap="nowrap"><h5><?=MSG_ABOUT_ME_PAGE_CONTENT;?></h5></td>
    </tr>
	<tr class="c1">

      <td colspan="1">
          <textarea class="ckeditor" id="aboutme_page_content" name="about_me" style="width: 400px; height: 200px; overflow: hidden;">
              <?=$user_details['about_me'];?>
          </textarea>

      </td>
   </tr>

	<tr>
      <td colspan="1"><input type="submit" name="form_aboutme_save" value="<?=MSG_SAVE_CHANGES;?>" /></td>
   </tr>
</table>
</form>




