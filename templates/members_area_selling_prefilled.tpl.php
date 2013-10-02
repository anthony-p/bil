<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<link href="/css/tinyeditor.css" rel="stylesheet">
<script language=JavaScript src='/scripts/jquery/tiny.editor.js'></script>

<br>
<form action="" method="post" name="prefilled_fields_form">
   <input type="hidden" name="operation" value="submit">
   <input type="hidden" name="do" value="<?=$do;?>">
   <table border="0" cellpadding="0" cellspacing="0" class="sellingPrefilled border">
      <tr>
         <td colspan="6" class="c7"><b><?=MSG_MM_PREFILLED_FIELDS;?></b></td>
      </tr>
      <tr class="c1">
         <td nowrap="nowrap"><?=MSG_ITEM_TITLE;?></td>
         <td colspan="2"><input name="default_name" type="text" id="default_name" value="<?=$prefilled_fields['default_name'];?>" size="60" maxlength="255" /></td>
      </tr>
      <tr class="c1">
         <td nowrap="nowrap"><?=MSG_ITEM_DESCRIPTION;?></td>
         <td colspan="2">
         	<textarea id="description_main" name="description_main" style="width: 400px; height: 100px; overflow: hidden;"><?=$prefilled_fields['default_description'];?></textarea>
            <?=$item_description_editor;?>
         </td>
      </tr>
      <tr class="c1">
         <td nowrap="nowrap"><?=MSG_CURRENCY;?></td>
         <td colspan="2"><?=$currency_drop_down;?></td>
      </tr>
      <tr class="c1">
         <td nowrap="nowrap"><?=GMSG_DURATION;?></td>
         <td colspan="2"><?=$duration_drop_down;?></td>
      </tr>
      <tr class="c1">
         <td nowrap="nowrap"><?=MSG_PRIVATE_AUCTION;?></td>
         <td colspan="2"><input type="checkbox" name="default_hidden_bidding" value="1" <? echo ($prefilled_fields['default_hidden_bidding']==1) ? 'checked' : ''; ?>/></td>
      </tr>
      <? if ($setts['enable_swaps']) { ?>
      <tr class="c1">
         <td nowrap="nowrap"><?=MSG_ACCEPT_SWAP;?></td>
         <td colspan="2"><input type="checkbox" name="default_enable_swap" value="1" <? echo ($prefilled_fields['default_enable_swap']==1) ? 'checked' : ''; ?>/></td>
      </tr>
      <? } ?>

		<? if ($setts['enable_auto_relist']) { ?>
	   <tr class="c1">
	      <td nowrap="nowrap"><?=MSG_ENABLE_AUTO_RELIST;?> </td>
	      <td colspan="2"><input type="checkbox" name="default_auto_relist" value="1" <? echo ($prefilled_fields['default_auto_relist']==1) ? 'checked' : ''; ?>/></td>
	   </tr>
	   <tr class="c1">
	      <td nowrap="nowrap"><?=MSG_AUTO_RELIST_SOLD;?> </td>
	      <td colspan="2"><input type="checkbox" name="default_auto_relist_bids" value="1" <? echo ($prefilled_fields['default_auto_relist_bids']==1) ? 'checked' : ''; ?>/></td>
	   </tr>
	   <tr class="c1">
	      <td nowrap="nowrap"><?=MSG_NB_AUTO_RELISTS;?> </td>
	      <td colspan="2"><input type="text" name="default_auto_relist_nb" value="<?=$prefilled_fields['default_auto_relist_nb'];?>" size="8" maxlength="2" /></td>
	   </tr>
	   <? } ?>

      <tr class="c1">
         <td nowrap="nowrap"><?=MSG_SHIPPING_CONDITIONS;?></td>
         <td ><input type="radio" name="default_shipping_method" value="1" <? echo ($prefilled_fields['default_shipping_method']==1) ? 'checked' : ''; ?>  />
         <br/>
             <span class="explain"><?=MSG_BUYER_PAYS_SHIPPING;?></span>
         </td>

      </tr>
      <tr>
         <td>&nbsp;</td>
         <td class="c2" >
<input type="radio" name="default_shipping_method" value="2" <? echo ($prefilled_fields['default_shipping_method']==2) ? 'checked' : ''; ?> />
        <br />
         <span class="explain"> <?=MSG_SELLER_PAYS_SHIPPING;?></span>
         </td>

      </tr>
      <tr>
         <td>&nbsp;</td>
         <td class="c1" nowrap><input type="checkbox" name="default_shipping_int" value="1" <? echo ($prefilled_fields['default_shipping_int']==1) ? 'checked' : ''; ?> />
         <br />
             <span class="explain"><?=MSG_SELLER_SHIPS_INT;?></span>
         </td>

      </tr>
      <? if ($setts['enable_shipping_costs']) { ?>
      <tr class="c1">
         <td nowrap="nowrap"><?=MSG_POSTAGE;?></td>
         <td  colspan="2"><input type="text" name="default_postage_amount" value="<?=$prefilled_fields['default_postage_amount'];?>" size="8"></td>
      </tr>
      <tr class="c1">
         <td nowrap="nowrap"><?=MSG_INSURANCE;?></td>
         <td colspan="2"><input type="text" name="default_insurance_amount" value="<?=$prefilled_fields['default_insurance_amount'];?>" size="8"></td>
      </tr>
      <tr class="c1">
         <td nowrap="nowrap"><?=MSG_SHIPPING_DETAILS;?></td>
         <td colspan="2"><textarea name="default_shipping_details" style="width: 350px; height: 100px;"><?=$prefilled_fields['default_shipping_details'];?></textarea></td>
      </tr>
      <tr class="c1">
         <td nowrap="nowrap"><?=MSG_SHIP_METHOD;?></td>
         <td colspan="2"><?=$shipping_methods_drop_down;?></td>
      </tr>
      <? } ?>
	   <tr class="c1">
	      <td nowrap="nowrap"><?=MSG_DIRECT_PAYMENT_METHODS;?></td>
	      <td colspan="2"><?=$direct_payment_table;?></td>
	   </tr>
	   <tr class="c1">
	      <td nowrap="nowrap"><?=MSG_OFFLINE_PAYMENT;?></td>
	      <td colspan="2"><?=$offline_payment_table;?></td>
	   </tr>
      <tr>
         <td colspan="6" class="c4"><?=MSG_GLOBAL_SETTINGS;?>
         </td>
      </tr>
      <tr class="c5">
         <td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="150" height="1"></td>
         <td colspan="2"><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="1" height="1"></td>
      </tr>
      <tr class="c1">
         <td nowrap="nowrap"><?=MSG_ACCEPT_PUBLIC_Q;?></td>
         <td colspan="2"><input type="checkbox" name="default_public_questions" value="1" <? echo ($prefilled_fields['default_public_questions']==1) ? 'checked' : ''; ?>/></td>
      </tr>
      <? if (!$setts['enable_store_only_mode']) { ?>
      <tr class="c1">
         <td nowrap="nowrap"><?=MSG_NEW_BID_EMAIL_NOTIF;?></td>
         <td colspan="2"><input type="checkbox" name="default_bid_placed_email" value="1" <? echo ($prefilled_fields['default_bid_placed_email']==1) ? 'checked' : ''; ?>/></td>
      </tr>
      <? } ?>
      <? if ($setts['enable_private_reputation']) { ?>
      <tr class="c1">
         <td nowrap="nowrap"><?=MSG_PRIVATE_REPUTATION;?></td>
         <td colspan="2"><input type="checkbox" name="enable_private_reputation" value="1" <? echo ($prefilled_fields['enable_private_reputation']==1) ? 'checked' : ''; ?>/></td>
      </tr>
      <tr>
         <td nowrap="nowrap"></td>
         <td colspan="2" class="explain"><?=MSG_PRIVATE_REPUTATION_EXPL;?></td>
      </tr>
      <? } ?>
      <? if ($setts['enable_force_payment'] && $layout['enable_buyout'] && $setts['buyout_process'] == 1) { ?>
      <tr class="c1">
         <td nowrap="nowrap"><?=MSG_BUYOUT_FORCE_PAYMENT;?></td>
         <td colspan="2"><input type="checkbox" name="enable_force_payment" value="1" <? echo ($prefilled_fields['enable_force_payment']==1) ? 'checked' : ''; ?>/></td>
      </tr>
      <tr>
         <td nowrap="nowrap"></td>
         <td colspan="2" class="explain"><?=MSG_BUYOUT_FORCE_PAYMENT_EXPL;?></td>
      </tr>
      <? } ?>
      <tr class="c5">
         <td><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="1" height="1"></td>
         <td colspan="2"><img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="1" height="1"></td>
      </tr>
      <tr>
         <td></td>
         <td colspan="2"><input name="form_save_settings" type="submit" id="form_save_settings" value="<?=GMSG_PROCEED;?>" /></td>
      </tr>
   </table>
</form>

<script language="javascript">
    /* == == == == == == == == == == == == == == == == == == == == == == ==*/
    var editor = new TINY.editor.edit('editor', {
        id: 'description_main',
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