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
<script language=JavaScript src='/scripts/jquery/tiny.editor.js'></script>
<br>
<form action="members_area.php?page=about_me&section=view" method="POST">
<table width="100%" border="0" cellpadding="3" cellspacing="2" class="border">
	<tr>
      <td colspan="2" class="c7"><b>
         <?=MSG_MM_ABOUT_ME_PAGE;?>
         </b></td>
   </tr>
	<tr class="c1">
      <td colspan="2"><?=MSG_ABOUT_ME_PAGE_EXPL;?></td>
   </tr>
	<tr>
      <td colspan="2"><b>
         <?=MSG_STORE_STATUS;?>
         </b>:
         <?=$shop_status['display'];?></td>
   </tr>
   <tr class="c5">
      <td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="150" height="1"></td>
      <td width="100%"><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="1" height="1"></td>
   </tr>
	<tr class="c1">
      <td  nowrap="nowrap"><?=MSG_ENABLE_ABOUT_ME_PAGE;?></td>
      <td><input name="enable_aboutme_page" type="checkbox" id="enable_aboutme_page" value="1" <? echo ($user_details['enable_aboutme_page']) ? 'checked' : ''; ?>></td>
   </tr>
	<tr class="c1">
      <td nowrap="nowrap"><?=MSG_ABOUT_ME_PAGE_CONTENT;?></td>
      <td><textarea id="aboutme_page_content" name="aboutme_page_content" style="width: 400px; height: 200px; overflow: hidden;"><?=$user_details['aboutme_page_content'];?></textarea>
        	<script>
				var oEdit_1 = new InnovaEditor("oEdit_1");
				oEdit_1.width="100%";//You can also use %, for example: oEdit1.width="100%"
				oEdit_1.height=100;
				oEdit_1.REPLACE("aboutme_page_content");//Specify the id of the textarea here
			</script></td>
   </tr>
      <tr class="c5">
         <td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="150" height="1"></td>
         <td width="100%"><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="1" height="1"></td>
   </tr>
	<tr>
      <td colspan="2"><input type="submit" name="form_aboutme_save" value="<?=GMSG_PROCEED;?>" /></td>
   </tr>
</table>
</form>

<script language="javascript">
    /* == == == == == == == == == == == == == == == == == == == == == == ==*/
    var editor = new TINY.editor.edit('editor', {
        id: 'aboutme_page_content',
        width: 584,
        height: 175,
        cssclass: 'tinyeditor',
        controlclass: 'tinyeditor-control',
        rowclass: 'tinyeditor-header',
        dividerclass: 'tinyeditor-divider',
        controls: ['bold', 'italic', 'underline', 'strikethrough', '|', 'subscript', 'superscript', '|',
            'orderedlist', 'unorderedlist', '|', 'outdent', 'indent', '|', 'leftalign',
            'centeralign', 'rightalign', 'blockjustify', '|', 'unformat', '|', 'undo', 'redo', 'n',
            'font', 'size', 'style', '|', 'image', 'hr', 'link', 'unlink', '|', 'print'],
        footer: true,
        fonts: ['Verdana','Arial','Georgia','Trebuchet MS'],
        xhtml: true,
        cssfile: 'custom.css',
        bodyid: 'editor',
        footerclass: 'tinyeditor-footer',
        toggle: {text: 'source', activetext: 'wysiwyg', cssclass: 'toggle'},
        resize: {cssclass: 'resize'}
    });
    /* == == == == == == == == == == == == == == == == == == == == == == ==*/
</script>