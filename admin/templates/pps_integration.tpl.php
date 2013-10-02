<?
#################################################################
## PHP Pro Bid v6.04															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
## PHP Pro Bid & PHP Pro Ads Integration v1.00						##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<div class="mainhead"><img src="images/fees.gif" align="absmiddle"> <?=$header_section;?></div>
<?=$msg_changes_saved;?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="4"><img src="images/c1.gif" width="4" height="4"></td>
		<td width="100%" class="ftop"><img src="images/pixel.gif" width="1" height="1"></td>
		<td width="4"><img src="images/c2.gif" width="4" height="4"></td>
	</tr>
</table>

<table width="100%" border="0" cellspacing="3" cellpadding="3" class="fside">
   <form name="form_pps_integration" action="pps_integration.php" method="post">
      <tr>
         <td colspan="2" class="c3"><img src="images/subt.gif" align="absmiddle" hspace="4" vspace="2"> <b><?=AMSG_PPB_PPA_INTEGRATION;?></b></td>
      </tr>
      <tr class="c1">
         <td nowrap="nowrap" align="right"><b><?=AMSG_PPA_RELATIVE_URL;?></b>: </td>
         <td width="100%"><input type="text" name="ppa_url" value="<?=$integration['ppa_url'];?>" size="35"></td>
      </tr>
      <tr>
         <td class="explain" align="right"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_PPA_RELATIVE_URL_EXPL;?></td>
      </tr>
      <? if ($valid_url) { ?>
      <tr class="c4">
         <td colspan="2"><img src="images/subt.gif" align="absmiddle" hspace="4" vspace="2"> <?=AMSG_PPA_INTEGRATION_SETTINGS;?></td>
      </tr>
      <tr class="c1">
         <td nowrap="nowrap" align="right"><b><?=AMSG_ENABLE_INTEGRATION;?></b>:</td>
         <td><input type="checkbox" name="enable_integration" value="1" <? echo ($integration['enable_integration']) ? 'checked' : '';?> ></td>
      </tr>
      <tr>
         <td class="explain" align="right"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_ENABLE_INTEGRATION_EXPL;?></td>
      </tr>
      <tr class="c1">
         <td nowrap="nowrap" align="right"><b><?=AMSG_UNIFIED_MAIN_PAGE;?></b>:</td>
         <td><input type="checkbox" name="main_page_unified" value="1" <? echo ($integration['main_page_unified']) ? 'checked' : '';?> ></td>
      </tr>
      <tr>
         <td class="explain" align="right"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_UNIFIED_MAIN_PAGE_EXPL;?></td>
      </tr>
      <tr class="c1">
         <td nowrap="nowrap" align="right"><b><?=AMSG_PPS_DEFAULT_SKIN;?></b>:</td>
         <td><?=$integration_skins_dropdown;?></td>
      </tr>
      <tr>
         <td class="explain" align="right"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_PPS_DEFAULT_SKIN_EXPL;?></td>
      </tr>
      <? } else if (!empty($integration['ppa_url'])) { ?>
      <tr>
         <td colspan="2" align="center" class="errormessage"><?=AMSG_PPA_URL_ERROR;?></td>
      </tr>
      <? } ?>
      <tr class="">
         <td colspan="2" align="center"><input type="submit" name="form_save_settings" value="<?=AMSG_SAVE_CHANGES;?>"></td>
      </tr>
   </form>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
   <tr>
      <td width="4"><img src="images/c3.gif" width="4" height="4"></td>
      <td width="100%" class="fbottom"><img src="images/pixel.gif" width="1" height="1"></td>
      <td width="4"><img src="images/c4.gif" width="4" height="4"></td>
   </tr>
</table>
