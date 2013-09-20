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
<!--<script language=JavaScript src='/scripts/jquery/tiny.editor.js'></script>-->
<script language="JavaScript" src="/scripts/jquery/tinymce/tinymce.min.js" js="text/javascript"></script>
<script language="JavaScript" src="/scripts/jquery/tinymce/jquery.tinymce.min.js" js="text/javascript"></script>
<style>
    .error {
        border: 1px solid #ff0000;
        background-color: #ff0000;
    }
</style>
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

            if (err_status) return false;
            else return true;

        });

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
            <div class="upload_logo"><img src="<?=$user_details['avatar']?>"/></div>
            <input type="file" name="avatar" id="avatar" accept="image/*" multiple title="avatar file" />
            <input type="hidden" name="first_name" value="<?=$user_details['first_name']?>" />
            <input type="hidden" name="curr_avatar" value="<?=$user_details['avatar']?>" />
        </td>
    </tr>
    <tr class="info_tittle">
        <td><h5><?=MSG_SOCIAL_ACCOUNT_INFORMATION?></h5></td>
    </tr>
    <tr>
        <td>
            <label class="facebook">facebook</label>
            <input type="text" name="facebook_link" value="<?=$user_details['facebook_link']?>"/>
        </td>
    </tr>
    <tr>
        <td>
            <label class="twitter">twitter</label>
            <input type="text" name="twitter_link" value="<?=$user_details['twitter_link']?>" />
        </td>
    </tr>
    <tr>
        <td>
            <label>google++</label>
            <input type="text" name="google_link" value="<?=$user_details['google_link']?>" />
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
        <!--	<script>
				var oEdit_1 = new InnovaEditor("oEdit_1");
				oEdit_1.width="100%";//You can also use %, for example: oEdit1.width="100%"
				oEdit_1.height=100;
				oEdit_1.REPLACE("aboutme_page_content");//Specify the id of the textarea here
			</script>-->
      </td>
   </tr>
<!--      <tr class="c5">
         <td><img src="themes/<?/*=$setts['default_theme'];*/?>/img/pixel.gif" width="150" height="1"></td>
         <td width="100%"><img src="themes/<?/*=$setts['default_theme'];*/?>/img/pixel.gif" width="1" height="1"></td>
   </tr>-->
	<tr>
      <td colspan="1"><input type="submit" name="form_aboutme_save" value="<?=GMSG_PROCEED;?>" /></td>
   </tr>
</table>
</form>

<script language="javascript">
    /* == == == == == == == == == == == == == == == == == == == == == == ==*/
    tinymce.PluginManager.load('moxiemanager', '/scripts/jquery/tinymce/plugins/moxiemanager/plugin.js');

    tinymce.init({
        selector:'#aboutme_page_content',
        plugins: [
            "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
            		"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            		"table contextmenu directionality emoticons template textcolor paste fullpage textcolor moxiemanager"
        ],
        toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
        	toolbar2: "cut copy paste pastetext | searchreplace | bullist numlist | outdent indent blockquote | undo redo | insertfile link unlink anchor image media code | forecolor backcolor",
        	toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft preview",

        	menubar: false,
            image_advtab: true,
        	toolbar_items_size: 'small',

        	style_formats: [
        		{title: 'Bold text', inline: 'b'},
        		{title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
        		{title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
        		{title: 'Example 1', inline: 'span', classes: 'example1'},
        		{title: 'Example 2', inline: 'span', classes: 'example2'},
        		{title: 'Table styles'},
        		{title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
        	]
    });

    /* == == == == == == == == == == == == == == == == == == == == == == ==*/
</script>



