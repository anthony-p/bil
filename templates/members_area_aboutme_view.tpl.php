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
 <h6 class="tittle_tp">  <?=MSG_MM_ABOUT_ME_PAGE;?> </h6>
<table width="100%" border="0" cellpadding="3" cellspacing="2" class="border about_me_table" >

    <tr class="info_tittle">
        <td><h5>Logo</h5></td>
    </tr>
    <tr>
        <td>
            <div class="upload_logo"><img src="http://t3.gstatic.com/images?q=tbn:ANd9GcQXkPY0BlCjoorCHkAemUqNxL9tgZsSmI06sTG_xSIxa-kuAws7"/></div>
            <input type="file"  value="upload new logo"/>
        </td>
    </tr>
    <tr class="info_tittle">
        <td><h5>Social Account information</h5></td>
    </tr>
    <tr>
        <td>
            <label class="facebook">facebook</label>
            <input type="text" />
        </td>
    </tr>
    <tr>
        <td>
            <label class="twitter">twitter</label>
            <input type="text" />
        </td>
    </tr>
    <tr>
        <td>
            <label>google++</label>
            <input type="text" />
        </td>
    </tr>
    <tr class="info_tittle">
        <td nowrap="nowrap"><h5><?=MSG_ABOUT_ME_PAGE_CONTENT;?></h5></td>
    </tr>
	<tr class="c1">

      <td colspan="1"><textarea id="aboutme_page_content" name="aboutme_page_content" style="width: 400px; height: 200px; overflow: hidden;"><?=$user_details['aboutme_page_content'];?></textarea>
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
      <td colspan="1"><input type="submit" name="form_aboutme_save" value="<?=GMSG_PROCEED;?>" /></td>
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
    /* == == == == == == == == ==
    == == == == == == == == == == == == == ==*/
</script>



