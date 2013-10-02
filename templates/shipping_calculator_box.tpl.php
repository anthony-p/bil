<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright ©2009 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>

<SCRIPT LANGUAGE="JavaScript">
function submit_form(form_name, file_type) {
	form_name.submit();
}
</script>
<a name="#shipping_calculator_box"></a>
<table width="100%" border="0" cellspacing="2" cellpadding="3" class="border">
   <tr>
      <td class="c3" colspan="2"><?=MSG_SHIPPING_CALCULATOR;?></td>
   </tr>
   <form method="POST" action="auction_details.php#shipping_calculator_box" name="shipping_calculator_form">
   	<input type="hidden" name="auction_id" value="<?=$auction_id;?>">
	<tr class="c5">
		<td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="150" height="1"></td>
		<td width="100%"><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="1" height="1"></td>
	</tr>
	<tr class="c1">
		<td><?=MSG_COUNTRY;?></td>
		<td><?=$country_dropdown;?></td>
	</tr>
	<? if (!empty($state_dropdown)) { ?>
	<tr class="c1">
		<td><?=MSG_STATE;?></td>
		<td><?=$state_dropdown;?></td>
	</tr>
	<? } ?>
	<? if ($item_details['auction_type'] == 'dutch') { ?>
	<tr class="c1">
		<td><?=MSG_QUANTITY;?></td>
		<td><input type="text" name="sc_quantity" value="<?=$sc_quantity;?>" size="8"></td>
	</tr>
	<? } ?>
	<? if ($sc_postage_value > 0)	{ ?>
	<tr class="c4">
		<td colspan="2"></td>
	</tr>
	<tr class="c2">
		<td><?=MSG_POSTAGE;?></td>
		<td><?=$fees->display_amount($sc_postage_value, $item_details['currency']);?></td>
	</tr>	
	<? } ?>
	<tr class="c4">
		<td colspan="2"></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" name="form_calculate_postage" value="<?=MSG_CALCULATE_POSTAGE;?>" <?=$sc_disabled;?>></td>
	</tr>	
   </form>	
</table>
