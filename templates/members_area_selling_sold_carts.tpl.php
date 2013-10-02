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
<br>
<table width="100%" border="0" cellpadding="3" cellspacing="2" class="border">
	<tr>
      <td colspan="8" class="c7 contentfont"><b><?=MSG_MM_SOLD_CARTS;?></b> (<?=$nb_items;?> <?=MSG_CARTS;?>)
      </td>
   </tr>
   <tr valign="top">
      <td class="membmenu"><?=MSG_SC_DETAILS;?><br><?=$page_order_sc_id;?></td>
      <td class="membmenu" align="center"><?=MSG_CONTACT_INFO;?>
      	<table>
      		<tr valign="top">
      			<td><?=MSG_PURCHASE_DATE;?><br><?=$page_order_purchase_date;?></td>
      			<td> / </td>
      			<td><?=MSG_STATUS;?></td>
      		</tr>
      	</table></td>
      <td align="center" class="membmenu"><?=GMSG_OPTIONS;?></td>
   </tr>
   <tr class="c5">
      <td width="100%"><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="100%" height="1"></td>
      <td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="150" height="1"></td>
      <td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="175" height="1"></td>
   </tr>
   <?=$sold_carts_content;?>
   <? if ($nb_items>0) { ?>
   <tr>
      <td colspan="8" align="center" class="contentfont"><?=$pagination;?></td>
   </tr>
	<? } ?>
</table>

