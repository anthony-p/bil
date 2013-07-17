<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2008 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<SCRIPT LANGUAGE="JavaScript">
function submit_form(form_name) {
	form_name.submit();
}

function openPopup(url) {
	myPopup = window.open(url,'popupWindow','width=750,height=480,scrollbars=yes,status=yes ');
	if (!myPopup.opener)
       	myPopup.opener = self;
}
</script>
<br>
<form name="form_selling_postage_setup" action="" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="box_submit" value="1">
	<table width="100%" border="0" cellpadding="3" cellspacing="2" class="border calculation-setup">
	   <tr>
	      <td colspan="3" class="c7" ><b><?=MSG_MM_POSTAGE_CALC_SETUP;?></b></td>
	   </tr>	
	   <tr class="c5">
	      <td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="150" height="1"></td>
	      <td colspan="2" width="100%"><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="100%" height="1"></td>
	   </tr>
	   <? if ($display_formcheck_errors) { ?>
	   <tr>
	      <td colspan="2" class="explain"><?=$display_formcheck_errors;?></td>
	   </tr>	
	   <? } ?>
	   <tr >
         <td nowrap="nowrap"><?=MSG_SELLING_FREE_POSTAGE;?></td>
         <td><input type="checkbox" name="pc_free_postage" value="1" <? echo ($postage_details['pc_free_postage']) ? 'checked' : '';?>></td>
         <td width="100%"></td>
      </tr>		
	   <tr>
         <td nowrap="nowrap"></td>
         <td  colspan="2"><?=MSG_IF_INVOICE_AMOUNT_OVER;?> <?=$setts['currency'];?> <input type="text" name="pc_free_postage_amount" value="<?=$postage_details['pc_free_postage_amount'];?>" size="8"></td>
      </tr>		
	   <tr class="reguser">
         <td></td>
         <td colspan="2" class="explain"><?=MSG_SELLING_FREE_POSTAGE_EXPL;?></td>
      </tr>		
      <tr class="c4">
         <td colspan="3"></td>
      </tr>
	   <tr >
         <td nowrap="nowrap"><?=MSG_SELLING_POSTAGE_TYPE;?></td>
         <td><input type="radio" name="pc_postage_type" value="item" checked onclick="submit_form(form_selling_postage_setup);"></td>
         <td width="100%"><?=MSG_POSTAGE_TYPE_NORMAL;?></td>
      </tr>		
	   <tr class="reguser">
         <td></td>
         <td colspan="2" class="explain"><?=MSG_POSTAGE_TYPE_NORMAL_EXPL;?></td>
      </tr>		
	   <tr>
         <td></td>
         <td ><input type="radio" name="pc_postage_type" value="weight" <? echo ($postage_details['pc_postage_type'] == 'weight') ? 'checked' : ''; ?> onclick="submit_form(form_selling_postage_setup);"></td>
         <td width="100%" ><?=MSG_POSTAGE_TYPE_WEIGHT;?></td>
      </tr>		
      <? if ($postage_details['pc_postage_type'] == 'weight') { ?>
	   <tr>
         <td></td>
         <td colspan="2"><?=MSG_WEIGHT_UNIT;?>: <input type="text" name="pc_weight_unit" value="<?=$postage_details['pc_weight_unit'];?>" size="8"></td>
      </tr>		
      <? } ?>
	   <tr class="reguser">
         <td></td>
         <td colspan="2" class="explain"><?=MSG_POSTAGE_TYPE_WEIGHT_EXPL;?></td>
      </tr>		
	   <tr>
         <td></td>
         <td class="c1"><input type="radio" name="pc_postage_type" value="amount" <? echo ($postage_details['pc_postage_type'] == 'amount') ? 'checked' : ''; ?> onclick="submit_form(form_selling_postage_setup);"></td>
         <td width="100%" ><?=MSG_POSTAGE_TYPE_AMOUNT;?></td>
      </tr>		
	   <tr class="reguser">
         <td></td>
         <td colspan="2" class="explain"><?=MSG_POSTAGE_TYPE_AMOUNT_EXPL;?></td>
      </tr>		
	   <tr>
         <td></td>
         <td class="c1"><input type="radio" name="pc_postage_type" value="flat" <? echo ($postage_details['pc_postage_type'] == 'flat') ? 'checked' : ''; ?> onclick="submit_form(form_selling_postage_setup);"></td>
         <td width="100%"><?=MSG_POSTAGE_TYPE_FLAT;?></td>
      </tr>		
      <? if ($postage_details['pc_postage_type'] == 'flat') { ?>
	   <tr>
         <td></td>
         <td colspan="2">
         	<table cellpadding="0" cellspacing="0" border="0">
         		<tr>
         			<td width="250"><?=MSG_POSTAGE_FLAT_FIRST_ITEM;?>:</td>
         			<td>[ <?=MSG_ITEM_CURRENCY;?> ] <input type="text" name="pc_flat_first" value="<?=$postage_details['pc_flat_first'];?>" size="8"></td>
         		</tr>
         	</table></td>
      </tr>		
	   <tr>
         <td></td>
         <td  colspan="2">
         	<table cellpadding="0" cellspacing="0" border="0">
         		<tr>
         			<td width="250"><?=MSG_POSTAGE_FLAT_ADDL_ITEMS;?>:</td>
         			<td>[ <?=MSG_ITEM_CURRENCY;?> ] <input type="text" name="pc_flat_additional" value="<?=$postage_details['pc_flat_additional'];?>" size="8"></td>
         		</tr>
         	</table></td>
      </tr>		
      <? } ?>
	   <tr class="reguser">
         <td></td>
         <td colspan="2" class="explain"><?=MSG_POSTAGE_TYPE_FLAT_EXPL;?></td>
      </tr>		
      <? if (!in_array($postage_details['pc_postage_type'], array('item', 'flat'))) { ?>
      <!-- weight based postage additional settings -->
      <tr class="c4">
         <td colspan="3"></td>
      </tr>
	   <tr class="c1">
         <td nowrap="nowrap"><?=MSG_CALCULATION_METHOD;?></td>
         <td><input type="radio" name="pc_postage_calc_type" value="default" checked onclick="submit_form(form_selling_postage_setup);"></td>
         <td width="100%"><?=MSG_DEFAULT_TIERS_TABLE;?></td>
      </tr>		
	   <tr>
         <td></td>
         <td class="c1"><input type="radio" name="pc_postage_calc_type" value="custom" <? echo ($postage_details['pc_postage_calc_type'] == 'custom') ? 'checked' : ''; ?> onclick="submit_form(form_selling_postage_setup);"></td>
         <td width="100%"><?=MSG_CUSTOM_TIERS_TABLE;?></td>
      </tr>		
	   <tr>
         <td></td>
         <td class="c4" colspan="2" align="center"><b><?=MSG_ACTIVE_TIERS_TABLE;?></b> [ <?=MSG_ITEM_CURRENCY;?> ]</td>
      </tr>		
	   <tr>
         <td></td>
         <td colspan="2" align="center"><?=$postage_tiers_table;?></td>
      </tr>		
	   <tr>
         <td></td>
         <td colspan="2"></td>
      </tr>		
      <? } ?>
      <tr class="c4">
         <td colspan="3"></td>
      </tr>
	   <tr class="c1">
         <td nowrap="nowrap"><?=MSG_SHIPPING_LOCATIONS;?></td>
         <td><input type="radio" name="pc_shipping_locations" value="global" checked onclick="submit_form(form_selling_postage_setup);"></td>
         <td width="100%"><?=MSG_GLOBAL;?></td>
      </tr>		
	   <tr class="reguser">
         <td></td>
         <td colspan="2" class="explain"><?=MSG_SHIPPING_GLOBAL_EXPL;?></td>
      </tr>		
	   <tr>
         <td></td>
         <td class="c1"><input type="radio" name="pc_shipping_locations" value="local" <? echo ($postage_details['pc_shipping_locations'] == 'local') ? 'checked' : ''; ?> onclick="submit_form(form_selling_postage_setup);"></td>
         <td width="100%"><?=MSG_LOCAL;?></td>
      </tr>		
	   <tr class="reguser">
         <td></td>
         <td colspan="2" class="explain"><?=MSG_SHIPPING_LOCAL_EXPL;?></td>
      </tr>		
      <? if ($postage_details['pc_shipping_locations'] == 'local') { ?>
	   <tr>
         <td></td>
         <td class="border" colspan="2">
         	<table width="100%" border="0" cellpadding="3" cellspacing="1">
         		<tr class="c4">
         			<td><?=MSG_LOCATIONS;?></td>
         			<td align="center"><?=MSG_ADDITIONAL_COST;?></td>
         			<!--<td align="center"><?=GMSG_DEFAULT;?></td>-->
         			<td align="center"><?=GMSG_OPTIONS;?></td>
         		</tr>
					<tr class="c5">
				      <td width="100%"><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="100%" height="1"></td>
				      <td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="100" height="1"></td>
				      <!--<td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="50" height="1"></td>-->
				      <td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="75" height="1"></td>
				   </tr>
          		<?=$shipping_locations_table;?>
			      <tr class="c4">
			         <td colspan="4"></td>
			      </tr>      
         		<tr>
			         <td colspan="4" class="contentfont">[ <a href="javascript:;"  onClick="openPopup('<?=SITE_PATH;?>shipping_locations_select.php?option=add&form_name=form_selling_postage_setup')"><?=MSG_ADD_LOCATION;?></a> ]</td>
         		</tr>
         	</table>
         </td>
      </tr>		      
      <? } ?>
      <tr class="c4">
         <td colspan="3"></td>
      </tr>      
      <tr>
         <td colspan="3"><input type="submit" name="form_postage_save" value="<?=GMSG_PROCEED;?>" /></td>
      </tr>
	</table>
</form>
