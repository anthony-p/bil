<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<!-- these two javascript calls need to be present everywhere where the calendar function is required -->
<SCRIPT LANGUAGE="JavaScript" SRC="includes/calendar.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">document.write(getCalendarStyles());</SCRIPT>
<SCRIPT LANGUAGE="JavaScript">
function submit_form(form_name, file_type) {
	form_name.box_submit.value = "1";
	form_name.file_upload_type.value = file_type;
	form_name.onsubmit();
	form_name.submit();
}

function delete_media(form_name, file_type, file_id) {
	form_name.box_submit.value = "1";
	form_name.file_upload_type.value = file_type;
	form_name.file_upload_id.value = file_id;
	form_name.onsubmit();
	form_name.submit();
}

myPopup = '';

function openPopup(url) {
	myPopup = window.open(url,'popupWindow','width=750,height=480,scrollbars=yes,status=yes ');
	if (!myPopup.opener)
       	myPopup.opener = self;
}
</SCRIPT>
<?=$sell_item_header;?>
<br>
<?=$display_formcheck_errors;?>

<form action="<?=$post_url;?>" method="post" enctype="multipart/form-data" name="ad_create_form">
   <input type="hidden" name="do" value="<?=$do;?>" >
   <input type="hidden" name="box_submit" value="0" >
   <input type="hidden" name="file_upload_type" value="" >
   <input type="hidden" name="file_upload_id" value="" >
   <input type="hidden" name="reverse_id" value="<?=$item_details['reverse_id'];?>" >
	<input type="hidden" name="category_id" value="<?=$item_details['category_id'];?>">
	<input type="hidden" name="addl_category_id" value="<?=$item_details['addl_category_id'];?>">
	<input type="hidden" name="old_category_id" value="<?=$old_category_id;?>">
	<input type="hidden" name="old_addl_category_id" value="<?=$old_addl_category_id;?>">
	<input type="hidden" name="start_time" value="<?=$item_details['start_time'];?>">
	<?=$media_upload_fields;?>
	<table width="100%" border="0" cellpadding="3" cellspacing="2" class="border">
	   <tr class="c4">
	      <td colspan="3"><?=MSG_MAIN_CATEGORY;?></td>
	   </tr>
	   <tr class="c5">
	      <td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="1" height="1"></td>
	      <td colspan="2"><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="1" height="1"></td>
	   </tr>
	   <tr class="c1">
	      <td width="150" align="right"></td>
	      <td class="contentfont" colspan="2"><?=$main_category_display;?>
	      	[ <a href="javascript:;"  onClick="openPopup('<?=SITE_PATH;?>category_selector.php?cat=category_id&category_id=<?=$item_details['category_id'];?>&reverse_id=<?=$item_details['reverse_id'];?>&form_name=ad_create_form')">
				<?=GMSG_MODIFY;?></a> ]</td>
	   </tr>
	   <tr class="reguser">
	      <td></td>
	      <td colspan="2"><?=MSG_CATEGORY_CHANGE_NOTE;?></td>
	   </tr>
	   <tr class="c4">
	      <td colspan="3"><?=MSG_ADDL_CATEGORY;?></td>
	   </tr>
	   <tr class="c5">
	      <td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="1" height="1"></td>
	      <td colspan="2"><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="1" height="1"></td>
	   </tr>
	   <tr class="c1">
	      <td width="150" align="right"><?=$addlcat_fee_expl_message;?></td>
	      <td class="contentfont" colspan="2"><?=$addl_category_display;?>
	      	[ <a href="javascript:;"  onClick="openPopup('<?=SITE_PATH;?>category_selector.php?cat=addl_category_id&category_id=<?=$item_details['addl_category_id'];?>&reverse_id=<?=$item_details['reverse_id'];?>&form_name=ad_create_form')">
				<?=GMSG_MODIFY;?></a> ]</td>
	   </tr>
	   <tr class="c4">
	      <td colspan="3"><?=MSG_REVERSE_AUCTION_DETAILS;?></td>
	   </tr>
	   <tr class="c5">
	      <td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="1" height="1"></td>
	      <td colspan="2"><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="1" height="1"></td>
	   </tr>
	   <tr class="c1">
	      <td width="150" align="right"><?=MSG_REVERSE_AUCTION_TITLE;?></td>
	      <td colspan="2"><input name="name" type="text" id="name" value="<?=$item_details['name'];?>" size="60" maxlength="255" /></td>
	   </tr>
	   <tr class="reguser">
	      <td>&nbsp;</td>
	      <td colspan="2"><?=MSG_ITEM_TITLE_EXPL;?></td>
	   </tr>
	   <? if (!empty($setup_fee_expl_message)) { ?>
	   <tr>
	      <td>&nbsp;</td>
	      <td colspan="2"><?=$setup_fee_expl_message;?></td>
	   </tr>
	   <? } ?>
	   <tr class="c1">
	      <td width="150" align="right"><?=MSG_ITEM_DESCRIPTION;?></td>
	      <td colspan="2"><textarea id="description_main" name="description_main" style="width: 400px; height: 200px; overflow: hidden;"><?=$item_details['description'];?></textarea>
		      <script>
					var oEdit_1 = new InnovaEditor("oEdit_1");
					oEdit_1.width="100%";//You can also use %, for example: oEdit1.width="100%"
					oEdit_1.height=250;
					oEdit_1.REPLACE("description_main");//Specify the id of the textarea here
				</script></td>
	   </tr>
	   <tr class="reguser">
	      <td></td>
	      <td colspan="2"><?=MSG_ITEM_DESC_EXPL;?></td>
	   </tr>
	   <tr class="c1">
	      <td width="150" align="right"><?=MSG_CURRENCY;?></td>
	      <td colspan="2"><?=$currency_drop_down;?></td>
	   </tr>
	   <tr class="reguser">
	      <td>&nbsp;</td>
	      <td colspan="2"><?=MSG_CURRENCY_EXPL;?></td>
	   </tr>
	   <tr class="c1">
	      <td width="150" align="right"><?=MSG_BUDGET;?></td>
	      <td colspan="2"><?=$budget_drop_down;?></td>
	   </tr>
	   <tr class="reguser">
	      <td></td>
	      <td colspan="2"><?=MSG_BUDGET_EXPL;?></td>
	   </tr>
	   <tr class="c1">
	      <td width="150" align="right"><?=MSG_AD_FEATURING;?></td>
	      <td nowrap><input type="checkbox" name="hpfeat" value="1" <? echo ($item_details['hpfeat']==1) ? 'checked' : ''; ?> />
	         <?=MSG_HP_FEATURED;?></td>
	      <td width="100%"><?=$hpfeat_fee_expl_message;?></td>
	   </tr>
	   <tr>
	      <td>&nbsp;</td>
	      <td class="c2" nowrap><input type="checkbox" name="catfeat" value="1" <? echo ($item_details['catfeat']==1) ? 'checked' : ''; ?> />
	         <?=MSG_CAT_FEATURED;?></td>
	      <td class="c2"><?=$catfeat_fee_expl_message;?></td>
	   </tr>
	   <tr>
	      <td>&nbsp;</td>
	      <td class="c1" nowrap><input type="checkbox" name="hl" value="1" <? echo ($item_details['hl']==1) ? 'checked' : ''; ?> />
	         <?=MSG_HL_AD;?></td>
	      <td class="c1"><?=$hl_fee_expl_message;?></td>
	   </tr>
	   <tr>
	      <td>&nbsp;</td>
	      <td class="c2" nowrap><input type="checkbox" name="bold" value="1" <? echo ($item_details['bold']==1) ? 'checked' : ''; ?> />
	         <?=MSG_BOLD_AD;?></td>
	      <td class="c2"><?=$bold_fee_expl_message;?></td>
	   </tr>
	   <tr class="reguser">
	      <td>&nbsp;</td>
	      <td colspan="2"><?=MSG_FEATURING_EXPL;?></td>
	   </tr>
	   <? if ($item_details['start_time'] > CURRENT_TIME || !$auction_edit) { ?>
	   <tr class="c1">
	      <td width="150" align="right"><?=GMSG_START_TIME;?>
	      </td>
	      <td colspan="2"><input name="start_time_type" type="radio" value="now" checked onclick = "submit_form(ad_create_form, '');"/>
	         <?=GMSG_NOW;?>
	      </td>
	   </tr>
	   <tr>
	      <td>&nbsp;</td>
	      <td class="c2" nowrap><input name="start_time_type" type="radio" value="custom" <? echo ($item_details['start_time_type'] == 'custom') ? 'checked' : ''; ?> onclick = "submit_form(ad_create_form, '');"/>
	         <?=GMSG_CUSTOM;?> <?=$custom_start_fee_expl_message;?></td>
	      <td class="c2"><? echo ($item_details['start_time_type'] == 'custom') ? $start_date_box : '';?></td>
	   </tr>
	   <tr class="reguser">
	      <td>&nbsp;</td>
	      <td colspan="2"><?=MSG_START_TIME_EXPL;?></td>
	   </tr>
	   <? } ?>
	   <tr class="c1">
	      <td width="150" align="right"><?=GMSG_END_TIME;?></td>
	      <td><input name="end_time_type" type="radio" value="duration" checked onclick = "submit_form(ad_create_form, '');"/>
	         <?=GMSG_DURATION;?>
	      </td>
	      <td><? echo ($item_details['end_time_type'] != 'custom') ? $duration_drop_down : '';?></td>
	   </tr>
	   <tr>
	      <td>&nbsp;</td>
	      <td class="c2" nowrap><input name="end_time_type" type="radio" value="custom" <? echo ($item_details['end_time_type'] == 'custom') ? 'checked' : ''; ?> onclick = "submit_form(ad_create_form, '');"/>
	         <?=GMSG_CUSTOM;?>
	      </td>
	      <td class="c2"><? echo ($item_details['end_time_type'] == 'custom') ? $end_date_box : '';?></td>
	   </tr>
	   <tr class="reguser">
	      <td>&nbsp;</td>
	      <td colspan="2"><?=MSG_END_TIME_EXPL;?></td>
	   </tr>
	   <tr class="c1">
	      <td width="150" align="right"><?=MSG_PRIVATE_AUCTION;?></td>
	      <td colspan="2"><input type="checkbox" name="hidden_bidding" value="1" <? echo ($item_details['hidden_bidding']==1) ? 'checked' : ''; ?>/></td>
	   </tr>
	   <tr class="reguser">
	      <td>&nbsp;</td>
	      <td colspan="2"><?=MSG_REVERSE_PRIVATE_AUCTION_EXPL;?></td>
	   </tr>
	   <?=$custom_sections_table;?>

		<? echo ($setts['max_images'] > 0) ? $image_upload_manager : ''; ?>
		<? echo ($setts['max_media'] > 0) ? $video_upload_manager : ''; ?>
		<? echo ($setts['dd_enabled'] && $setts['max_dd'] > 0) ? $dd_upload_manager : ''; ?>
	</table>
	<br />
   <table width="100%" border="0" cellpadding="3" cellspacing="2" class="border">
      <tr>
         <td width="150" class="contentfont"><input name="form_edit_proceed" type="submit" id="form_edit_proceed" value="<?=GMSG_PROCEED;?>" />
         </td>
         <td class="contentfont">&nbsp;</td>
      </tr>
   </table>
</form>
