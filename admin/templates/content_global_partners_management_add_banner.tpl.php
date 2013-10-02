<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<script language="javascript" src="../includes/main_functions.js" type="text/javascript"></script>
<SCRIPT LANGUAGE="JavaScript">
function delete_media(form_name, file_type, file_id) {
	form_name.box_submit.value = "1";
	form_name.file_upload_type.value = file_type;
	form_name.file_upload_id.value = file_id;
	form_name.submit();
}

function submit_form(form_name, file_type) {
	form_name.box_submit.value = "1";
	form_name.file_upload_type.value = file_type;

	SelectOption(form_name.categories_id)

	form_name.submit();
}

function submit_form_b(form_name) {

	form_name.submit();
}
</SCRIPT>
<table width="100%" border="0" cellpadding="3" cellspacing="3" class="fside">
   <form action="content_global_partners_management.php" method="post" name="form_content_banners" enctype="multipart/form-data" onSubmit="SelectOption(this.categories_id)">
      <input type="hidden" name="do" value="<?=$do;?>" />
      <input type="hidden" name="box_submit" value="0" >
	   <input type="hidden" name="file_upload_type" value="" >
	   <input type="hidden" name="file_upload_id" value="" >
	   <input type="hidden" name="advert_id" value="<?=$advert_id;?>" >
	 <input type="hidden" name="advert_type" value="2">  
	  
	 <!-- Hello -->  
	   <?=$media_upload_fields;?>
	   <tr>
         <td colspan="2" class="c3"><img src="images/subt.gif" align="absmiddle" hspace="4" vspace="2"> <b><?=$manage_box_title;?></b></td>
      </tr>
      <tr class="c1">
         <td width="150" align="right"><b><?=AMSG_BANNER_TYPE;?></b></td>
         <td>
         	<? if ($advert_type) {
         		echo ($advert_type == 1) ? AMSG_CUSTOM_ADVERT : AMSG_CODE_ADVERT; ?>
         		<input type="hidden" name="advert_type" value="2">
         	<? } else { ?>
         	<select name="advert_type" onchange="submit_form_b(form_content_banners);">
         		<option value="0" selected><?=AMSG_SELECT_BANNER_TYPE;?></option>
 <!--        		<option value="1" <? echo ($advert_type == 1) ? 'selected' : ''; ?>><?=AMSG_CUSTOM_ADVERT;?></option> -->  
         		<option value="2" <? echo ($advert_type == 2) ? 'selected' : ''; ?>><?=AMSG_CODE_ADVERT;?></option>
         	</select>
         	<? } ?></td>
      </tr>
  <!-- 
  <tr>
         <td class="explain" align="right"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_BANNER_TYPE_EXPL;?></td>
      </tr>
     
      <? if ($advert_type) { ?>
      <tr class="c1">
         <td width="150" align="right"><?=AMSG_BANNER_POSITION;?></td>
         <td><select name="section_id">
         		<?	foreach ($banner_positions as $key => $value) { ?>
         		<option value="<?=$key;?>" <? echo ($banner_details['section_id'] == $key) ? 'selected' : ''; ?>><?=$value;?></option>
         		<? } ?>
         	</select></td>
      </tr>
      <tr>
         <td class="explain" align="right"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_BANNER_POSITION_EXPL;?></td>
      </tr>
      <tr class="c1">
         <td width="150" align="right"><b><?=AMSG_VIEWS_PURCHASED;?></b></td>
         <td><input type="text" name="views_purchased" value="<?=$banner_details['views_purchased'];?>" size="8"></td>
      </tr>
      <tr>
         <td class="explain" align="right"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_VIEWS_PURCHASED_EXPL;?></td>
      </tr>
      <? if ($advert_type == 1) { ?>
      <tr class="c1">
         <td width="150" align="right"><?=AMSG_CLICKS_PURCHASED;?></td>
         <td><input type="text" name="clicks_purchased" value="<?=$banner_details['clicks_purchased'];?>" size="8"></td>
      </tr>
      <tr>
         <td class="explain" align="right"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_CLICKS_PURCHASED_EXPL;?></td>
      </tr>
      <? } ?>
	    --> 
      <tr class="c1">
         <td width="150" align="right"><b><?=AMSG_DISPLAY_IN_CATEGORIES;?></b></td>
         <td><table width="100%" border="0" cellspacing="2" cellpadding="2">
               <tr>
                  <td width="45%"><b>[ <?=AMSG_ALL_CATEGORIES;?> ]</b> </td>
                  <td width="10%">&nbsp;</td>
                  <td width="45%"><b>[ <?=AMSG_SELECTED_CATEGORIES;?> ]</b> </td>
               </tr>
               <tr>
                  <td><?=$all_categories_table;?></td>
                  <td align="center"><input type="button" name="Disable" value=" -&gt; " style="width: 50px;" onclick="MoveOption(this.form.all_categories, this.form.categories_id)" />
                     <br />
                     <br />
                     <input type="button" name="Enable" value=" &lt;- " style="width: 50px;" onclick="MoveOption(this.form.categories_id, this.form.all_categories)" /></td>
                  <td><?=$selected_categories_table;?></td>
               </tr>
            </table></td>
      </tr>
      <tr>
         <td class="explain" align="right"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_DISPLAY_IN_CATEGORIES_EXPL;?></td>
      </tr>
      <? if ($advert_type == 1) { // custom advert ?>
      <tr class="c1">
         <td width="150" align="right"><b><?=AMSG_BANNER_IMAGE;?></b></td>
         <td><?=$image_upload_manager;?></td>
      </tr>
      <tr class="c1">
         <td width="150" align="right"><b><?=AMSG_BANNER_URL;?></b></td>
         <td><input type="text" name="advert_url" value="<?=$banner_details['advert_url'];?>" size="140"></td>
      </tr>
      <tr class="c1">
         <td width="150" align="right"><b><?=AMSG_TEXT_UNDER;?></b></td>
         <td><input type="text" name="advert_text_under" value="<?=$banner_details['advert_text_under'];?>" size="40"></td>
      </tr>
      <tr class="c1">
         <td width="150" align="right"><b><?=AMSG_ALT_TEXT;?></b></td>
         <td><input type="text" name="advert_alt_text" value="<?=$banner_details['advert_alt_text'];?>" size="40"></td>
      </tr>
      <? } else if ($advert_type == 2) { // code advert ?>
     

	 <tr class="c1">
         <td width="150" align="right"><b><?=AMSG_BANNER_URL;?></b></td>
         <td><input type="text" name="advert_url" value="<?=$banner_details['advert_url'];?>" size="140"></td>
      </tr>
	  
	  <tr class="c1">
      	 
		 <td align="right"><b>Percent commission from this advertiser - eg: 6.5</b></td>
         <td> <input type="text" name="advert_pct" size="1" value="<?=$banner_details['advert_pct'];?>" ></td>
      </tr>
	  <tr>
	  
	  
	  
	  
	 <tr class="c1">
      	 
		 <td align="right"><b>Feature on home page</b></td>
         <td> <input type="text" name="hpfeat" size="1" value="<?=$banner_details['hpfeat'];?>" ></td>
      </tr>
	  <tr>
	 <td align="right"><b>Feature on category page</b></td>
         <td> <input type="text" name="catfeat" size="1" value="<?=$banner_details['catfeat'];?>" ></td>
      </tr>
	 <tr>
	 <td align="right"><b>Set it to be active</b></td>
         <td> <input type="text" name="active" size="1" value="<?=$banner_details['active'];?>" ></td>
      </tr>
	  <tr>
	<td align="right"><b>Approved</b></td>
         <td> <input type="text" name="approved" size="1" value="<?=$banner_details['approved'];?>" ></td>
      </tr> 
	 <tr>
	 <td align="right"><b>Advertisers name</b></td>
         <td> <input type="text" name="name" size="60" value="<?=$banner_details['name'];?>" ></td>
      </tr> 
	 <tr>
	 <td align="right"><b>Key words</b></td>
         <td> <input type="text" name="description" size="260" value="<?=$banner_details['description'];?>" ></td>
      </tr> 
	 <tr class="c1">
         	 <tr class="c1"><td colspan="3">Sample code for google affiliate networks links - note the '&pubid' in the url needs to be changed to %26pubid or the link won't work:<br><br>
			 
			 <pre>
&lt;center&gt;&lt;a href=&quot;http://gan.doubleclick.net/gan_click?lid=41000000014351544%26pubid=21000000000382531&quot; &gt;
&lt;img src=&quot;http://gan.doubleclick.net/gan_impression?lid=41000000014351544&amp;pubid=21000000000382531&quot; width=&quot;125&quot; 
height=&quot;125&quot; border=&quot;0&quot; alt=&quot;Barnes and Noble&quot; class=&quot;image&quot;&gt;&lt;/a&gt;&lt;/center&gt;
			 
			 </pre>

               <p>
                   <u><b>REMINDER:</b> for linksynergy vendor links
                   In the links where it says:
                   &offerid=

                   you MUST change it to:
                   %26offerid= </u>
               </p>
			 
			 </td></tr>
<tr>
		 
		 <td align="right"><b><?=AMSG_ADVERT_CODE;?></b></td>
         <td><textarea id="advert_code" name="advert_code" style="width: 100%; height: 150px; overflow: hidden;"><?=$db->add_special_chars($banner_details['advert_code']);?></textarea></td>



	 </tr>
<tr>

		 <td align="right"><b>Big Banner Code</b></td>
         <td><textarea id="advert_bigbanner_code" name="big_banner_code" style="width: 100%; height: 150px; overflow: hidden;"><?=$db->add_special_chars($banner_details['big_banner_code']);?></textarea></td>



	 </tr>






      <? } ?>
      <tr>
         <td colspan="2" align="center"><input type="submit" name="form_save_settings" value="<?=AMSG_SAVE_CHANGES;?>"></td>
      </tr>
      <? } ?>
   </form>
</table>
<br />
