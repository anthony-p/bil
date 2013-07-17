<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright ©2008 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
## (Mods-Store) -> Shopping Cart											##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<SCRIPT LANGUAGE="JavaScript">
function submit_form(form_name) {
	form_name.submit();
}
</script>
<br>
<form name="form_store_postage_setup" action="" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="box_submit" value="1">
	<table width="100%" border="0" cellpadding="3" cellspacing="2" class="border">
	   <tr>
	      <td colspan="3" class="c7"><b><?=MSG_MM_SC_SETTS;?></b></td>
	   </tr>	
	   <tr class="c5">
	      <td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="150" height="1"></td>
	      <td colspan="2" width="100%"><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="100%" height="1"></td>
	   </tr>
	   <? if ($display_formcheck_errors) { ?>
	   <tr>
	      <td colspan="2"><?=$display_formcheck_errors;?></td>
	   </tr>	
	   <? } ?>
	   <? if ($can_add_tax) { ?>
	   <tr class="c1">
         <td align="right"><?=MSG_SC_TAX;?></td>
         <td><input type="checkbox" name="shop_add_tax" value="1" <? echo ($postage_details['shop_add_tax']) ? 'checked' : '';?>></td>
         <td width="100%"></td>
      </tr>		
	   <tr class="reguser">
         <td></td>
         <td colspan="2"><?=MSG_SC_TAX_EXPL;?></td>
      </tr>		
      <? } ?>
	   <tr class="c1">
	      <td width="150" align="right"><?=MSG_DIRECT_PAYMENT_METHODS;?></td>
	      <td nowrap colspan="2"><?=$direct_payment_table;?></td>
	   </tr>
	   <tr class="reguser">
	      <td>&nbsp;</td>
	      <td colspan="2"><?=MSG_SHOP_DIRECT_PAYMENT_METHODS_EXPL;?></td>
	   </tr>
      <tr class="c4">
         <td colspan="3"></td>
      </tr>
      <tr>
         <td colspan="3"><input type="submit" name="form_postage_save" value="<?=GMSG_PROCEED;?>" /></td>
      </tr>
	</table>
</form>
