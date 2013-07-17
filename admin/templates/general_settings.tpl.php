<?
#################################################################
## PHP Pro Bid v6.07															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>

<div class="mainhead"><img src="images/set.gif" align="absmiddle">
   <?=$header_section;?>
</div>
<?=$msg_changes_saved;?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
   <tr>
      <td width="4"><img src="images/c1.gif" width="4" height="4"></td>
      <td width="100%" class="ftop"><img src="images/pixel.gif" width="1" height="1"></td>
      <td width="4"><img src="images/c2.gif" width="4" height="4"></td>
   </tr>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="3" class="fside">
   <tr class="c3">
      <td colspan="2"><img src="images/subt.gif" align="absmiddle" hspace="4" vspace="2"> <b>
         <?=strtoupper($subpage_title);?>
         </b></td>
   </tr>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="3" class="fside">
   <form name="form_general_settings" method="post" action="general_settings.php">
      <input type="hidden" name="page" value="<?=$page;?>">
      <? if ($_REQUEST['page']=="signup_settings") { ?>
      <!-- Enable registration and mandatory registration section -->
      <tr class="c1">
         <td align="right" nowrap><?=AMSG_NO_CONF_REQ;?></td>
         <td><input type="radio" name="signup_settings" value="0" <? echo ($setts_tmp['signup_settings']==0) ? 'checked' : ''; ?> /></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_NO_CONF_REQ_DESC;?></td>
      </tr>
      <tr class="c1">
         <td align="right" nowrap><?=AMSG_EMAIL_ADDR_CONF;?></td>
         <td><input type="radio" name="signup_settings" value="1" <? echo ($setts_tmp['signup_settings']==1) ? 'checked' : ''; ?> /></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_EMAIL_ADDR_CONF_DESC;?></td>
      </tr>
      <tr class="c1">
         <td align="right" nowrap><?=AMSG_ADMIN_APPROVAL;?></td>
         <td><input type="radio" name="signup_settings" value="2" <? echo ($setts_tmp['signup_settings']==2) ? 'checked' : ''; ?> /></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_ADMIN_APPROVAL_DESC;?></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_SIGNUP_SETTS_NOTE;?></td>
      </tr>
      <? } else if ($page == 'closed_auctions_deletion') { ?>
      <!-- Closed Auctions Deletion Settings BEGIN HERE -->
      <tr class="c1">
         <td width="150" align="right"><?=AMSG_CLOSED_AUCT_DEL;?></td>
         <td><input name="closed_auction_deletion_days" type="text" id="closed_auction_deletion_days" value="<?=$setts_tmp['closed_auction_deletion_days'];?>" size="8">
            <?=GMSG_DAYS;?></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_CLOSED_AUCT_DEL_EXPL;?></td>
      </tr>
      <? } else if ($page == 'hpfeat_items') { ?>
      <!-- Home Page Featured Auctions Settings BEGIN HERE -->
      <tr class="c1">
         <td width="150" align="right"><?=AMSG_ITEMS_PER_ROW;?></td>
         <td><input name="hpfeat_nb" type="text" id="hpfeat_nb" value="<?=$layout_tmp['hpfeat_nb'];?>" size="8"></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_ITEMS_PER_ROW_EXPL;?></td>
      </tr>
      <tr class="c1">
         <td align="right"><?=AMSG_FEAT_BOX_WIDTH;?></td>
         <td><input name="hpfeat_width" type="text" id="hpfeat_width" value="<?=$layout_tmp['hpfeat_width'];?>" size="8">
            <?=GMSG_PIXELS;?></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_FEAT_BOX_WIDTH_EXPL;?></td>
      </tr>
      <tr class="c1">
         <td align="right"><?=AMSG_MAX_ITEMS;?></td>
         <td><input name="hpfeat_max" type="text" id="hpfeat_max" value="<?=$layout_tmp['hpfeat_max'];?>" size="8"></td>
      </tr>
      <? } else if ($page == 'catfeat_items') { ?>
      <!-- Category Featured Auctions Settings BEGIN HERE -->
      <tr class="c1">
         <td width="150" align="right"><?=AMSG_ITEMS_PER_ROW;?></td>
         <td><input name="catfeat_nb" type="text" id="catfeat_nb" value="<?=$layout_tmp['catfeat_nb'];?>" size="8"></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_ITEMS_PER_ROW_EXPL;?></td>
      </tr>
      <tr class="c1">
         <td align="right"><?=AMSG_FEAT_BOX_WIDTH;?></td>
         <td><input name="catfeat_width" type="text" id="catfeat_width" value="<?=$layout_tmp['catfeat_width'];?>" size="8">
            <?=GMSG_PIXELS;?></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_FEAT_BOX_WIDTH_EXPL;?></td>
      </tr>
      <tr class="c1">
         <td align="right"><?=AMSG_MAX_ITEMS;?></td>
         <td><input name="catfeat_max" type="text" id="catfeat_max" value="<?=$layout_tmp['catfeat_max'];?>" size="8"></td>
      </tr>
      <? } else if ($page == 'recently_listed_auctions') { ?>
      <!-- Last Created Auctions Settings BEGIN HERE -->
      <tr class="c1">
         <td width="150" align="right"><?=AMSG_RECENT_AUCTIONS;?></td>
         <td><input name="nb_recent_auct" type="text" id="nb_recent_auct" value="<?=$layout_tmp['nb_recent_auct'];?>" size="8"></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_RECENT_AUCTIONS_EXPL;?></td>
      </tr>
      <? } else if ($page == 'popular_auctions') { ?>
      <!-- Hot Auctions Settings BEGIN HERE -->
      <tr class="c1">
         <td width="150" align="right"><?=AMSG_POPULAR_AUCTIONS;?></td>
         <td><input name="nb_popular_auct" type="text" id="nb_popular_auct" value="<?=$layout_tmp['nb_popular_auct'];?>" size="8"></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_POPULAR_AUCTIONS_EXPL;?></td>
      </tr>
      <? } else if ($page == 'ending_soon_auctions') { ?>
      <!-- Ending Soon Auctions Settings BEGIN HERE -->
      <tr class="c1">
         <td width="150" align="right"><?=AMSG_ENDING_SOON_AUCTIONS;?></td>
         <td><input name="nb_ending_auct" type="text" id="nb_ending_auct" value="<?=$layout_tmp['nb_ending_auct'];?>" size="8"></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_ENDING_SOON_AUCTIONS_EXPL;?></td>
      </tr>
      <? } else if ($page == 'auction_images') { ?>
      <!-- Images Settings BEGIN HERE -->
      <tr class="c1">
         <td width="150" align="right"><?=AMSG_NB_IMAGES;?></td>
         <td><input name="max_images" type="text" id="max_images" value="<?=$setts_tmp['max_images'];?>" size="8" maxlength="2"></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_NB_IMAGES_EXPL;?></td>
      </tr>
      <tr class="c1">
         <td align="right"><?=AMSG_MAX_FILE_SIZE;?></td>
         <td><input name="images_max_size" type="text" id="images_max_size" value="<?=$setts_tmp['images_max_size'];?>" size="8" maxlength="8">
            KBytes </td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_IMAGE_MAX_SIZE_EXPL;?></td>
      </tr>
      <tr class="c1">
         <td align="right"><?=AMSG_WATERMARK_TEXT;?></td>
         <td><input name="watermark_text" type="text" id="watermark_text" value="<?=$setts_tmp['watermark_text'];?>" size="20" maxlength="80"></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_WATERMARK_TEXT_EXPL;?></td>
      </tr>
      <tr class="c1">
         <td align="right"><?=AMSG_MAX_RESIZE;?></td>
         <td><input name="watermark_size" type="text" id="watermark_size" value="<?=$setts_tmp['watermark_size'];?>" size="20" maxlength="80"></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_MAX_RESIZE_EXPL;?></td>
      </tr>
      <? } else if ($page == 'currency_setts') { ?>
      <!-- Currency Settings BEGIN HERE -->
      <tr class="c1">
         <td width="150" align="right"><?=AMSG_SELECT_SITE_CURRENCY;?></td>
         <td><?=$currency_drop_down;?></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_SELECT_SITE_CURRENCY_EXPL;?></td>
      </tr>
      <tr class="c1">
         <td align="right"><?=AMSG_AMOUNT_DISPLAY_FORMAT;?></td>
         <td><input type="radio" name="amount_format" value="1" checked>
            <? echo AMSG_US_STYLE . ': ' . $setts_tmp['currency'] . ' 2,999.95'; ?></td>
      </tr>
      <tr class="c1">
         <td>&nbsp;</td>
         <td><input type="radio" name="amount_format" value="2" <? echo ($setts_tmp['amount_format']==2) ? 'checked' : ''; ?>>
            <? echo AMSG_EURO_STYLE . ': ' . $setts_tmp['currency'] . ' 2.999,95'; ?></td>
      </tr>
      <tr class="c1">
         <td align="right"><?=AMSG_AMOUNT_DIGITS;?></td>
         <td><input name="amount_digits" type="text" id="amount_digits" value="<?=$setts_tmp['amount_digits'];?>" size="8" maxlength="1"></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_AMOUNT_DIGITS_EXPL;?></td>
      </tr>
      <tr class="c1">
         <td align="right"><?=AMSG_CURRENCY_SYMBOL_POSITION;?></td>
         <td><input type="radio" name="currency_position" value="1" checked>
            <? echo AMSG_SYMBOL_BEFORE_AMOUNT . ': ' . $setts_tmp['currency'] . ' 395'; ?></td>
      </tr>
      <tr class="c1">
         <td>&nbsp;</td>
         <td><input type="radio" name="currency_position" value="2" <? echo ($setts_tmp['currency_position']==2) ? 'checked' : ''; ?>>
            <? echo AMSG_SYMBOL_AFTER_AMOUNT . ': 395 ' . $setts_tmp['currency']; ?> </td>
      </tr>
      <? } else if ($page == 'time_date_setts') { ?>
      <!-- Time and Date Settings BEGIN HERE -->
      <tr class="c4">
         <td colspan="2"><?=AMSG_TIME_OFFSET;?></td>
      </tr>
      <tr class="c1">
         <td width="150" align="right"><?=AMSG_SELECT_TIME_OFFSET;?></td>
         <td><?=$timezones_drop_down;?></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_SELECT_TIME_OFFSET_EXPL;?></td>
      </tr>
      <tr class="c4">
         <td colspan="2"><?=AMSG_DATE_FORMAT;?></td>
      </tr>
      <?
      $sql_select_dateformat = mysql_query("SELECT * FROM " . DB_PREFIX . "dateformat");
		while ($date_format_details = mysql_fetch_array($sql_select_dateformat)) { ?>
      <tr class="c1">
         <td width="150" align="right"><? echo $date_format_details['type'] . ' ' .AMSG_FORMAT;?></td>
         <td><input type="radio" name="date_format" value="<? echo $date_format_details['id'];?>" <? echo $date_format_details['active'];?>>
            <? echo $date_format_details['name'];?></td>
      </tr>
      <? } ?>
      <? } else if ($page == 'ssl_support') { ?>
      <!-- SSL Settings BEGIN HERE -->
      <tr class="c1">
         <td width="150" align="right"><?=AMSG_ACTIVATE_SSL;?></td>
         <td><input type="radio" name="is_ssl" value="1" checked>
            <?=GMSG_YES;?>
            <input type="radio" name="is_ssl" value="0" <? echo (($setts_tmp['is_ssl'] == 0) ? 'checked' : '');?>>
            <?=GMSG_NO;?></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_ACTIVATE_SSL_EXPL;?></td>
      </tr>
      <tr class="c1">
         <td width="150" align="right"><?=AMSG_ACTIVATE_EHANCED_SSL;?></td>
         <td><input type="radio" name="enable_enhanced_ssl" value="1" checked>
            <?=GMSG_YES;?>
            <input type="radio" name="enable_enhanced_ssl" value="0" <? echo (($setts_tmp['enable_enhanced_ssl'] == 0) ? 'checked' : '');?>>
            <?=GMSG_NO;?></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_ACTIVATE_EHANCED_SSL_EXPL;?></td>
      </tr>
      <tr class="c1">
         <td align="right"><?=AMSG_ENTER_SSL_ADDRESS;?></td>
         <td><input name="site_path_ssl" type="text" id="content" value="<?=$setts_tmp['site_path_ssl'];?>" size="50">
         </td>
      </tr>
      <? } else if ($page == 'meta_tags') { ?>
      <!-- Meta Tags Settings BEGIN HERE -->
      <tr class="c1">
         <td align="right" width="150"><?=AMSG_ADD_META_TAGS;?></td>
         <td><textarea name="metatags" style="width: 100%; height: 150px;" id="metatags"><?=$setts_tmp['metatags'];?></textarea></td>
      </tr>
      <? } else if ($page == 'cron_jobs') { ?>
      <!-- Cron Jobs Settings BEGIN HERE -->
      <tr class="c1">
         <td align="right" width="50%"><?=AMSG_CRON_JOB_CPANEL;?></td>
         <td><input type="radio" name="cron_job_type" value="1" <? echo ($setts_tmp['cron_job_type'] == 1) ? 'checked' : '';?>>
         </td>
      </tr>
      <tr class="c1">
         <td align="right"><?=AMSG_CRON_JOB_PPB;?></td>
         <td><input type="radio" name="cron_job_type" value="2" <? echo ($setts_tmp['cron_job_type'] == 2) ? 'checked' : '';?>>
         </td>
      </tr>
      <? } else if ($page == 'min_reg_age') { ?>
      <!-- Minimum registration age settings BEGIN HERE -->
      <tr class="c1">
         <td align="right" width="150"><?=AMSG_MIN_AGE;?></td>
         <td><input type="text" name="min_reg_age" value="<?=$setts_tmp['min_reg_age'];?>" size="6">
            <?=GMSG_YEARS;?></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_MIN_AGE_EXPL;?></td>
      </tr>
      <tr class="c1">
         <td align="right"><?=AMSG_DATE_OF_BIRTH_TYPE;?></td>
         <td nowrap><input type="radio" name="birthdate_type" value="0" checked>
            <?=AMSG_DOB_FULL_FORMAT;?></td>
      </tr>
      <tr>
         <td></td>
         <td class="c1"><input type="radio" name="birthdate_type" value="1" <? echo ($setts_tmp['birthdate_type']==1) ? 'checked' : '';?>>
            <?=AMSG_DOB_YEAR_ONLY;?></td>
      </tr>
      <? } else if ($page == 'recent_wanted_ads') { ?>
      <!-- Recently listed wanted ads Settings BEGIN HERE -->
      <tr class="c1">
         <td width="150" align="right"><?=AMSG_RECENT_WANTED_ADS;?></td>
         <td><input name="nb_want_ads" type="text" id="nb_want_ads" value="<?=$layout_tmp['nb_want_ads'];?>" size="8"></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_RECENT_WANTED_ADS_EXPL;?></td>
      </tr>
      <? } else if ($page == 'auction_media') { ?>
      <!-- Movie Settings BEGIN HERE -->
      <tr class="c1">
         <td width="150" align="right"><?=AMSG_NB_MEDIA;?></td>
         <td><input name="max_media" type="text" id="max_media" value="<?=$setts_tmp['max_media'];?>" size="8" maxlength="2"></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_NB_MEDIA_EXPL;?></td>
      </tr>
      <tr class="c1">
         <td align="right"><?=AMSG_ALLOW_EMBEDDED_CODE;?></td>
         <td><input type="checkbox" name="enable_embedded_media" value="1" <? echo ($setts_tmp['enable_embedded_media']==1) ? 'checked' : '';?>></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_ALLOW_EMBEDDED_CODE_EXPL;?></td>
      </tr>
      <tr class="c1">
         <td align="right"><?=AMSG_MAX_FILE_SIZE;?></td>
         <td><input name="media_max_size" type="text" id="media_max_size" value="<?=$setts_tmp['media_max_size'];?>" size="8" maxlength="8">
            KBytes </td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_MEDIA_MAX_SIZE_EXPL;?></td>
      </tr>
      <? } else if ($page == 'buy_out_method') { ?>
      <!-- Buyout procedure selection BEGINS HERE -->
      <tr class="c1">
         <td width="150" align="right"><?=AMSG_CHOOSE_METHOD;?></td>
         <td nowrap><input type="checkbox" name="buyout_process" value="1" <? echo ($setts_tmp['buyout_process']==1) ? 'checked' : '';?>>
            <?=AMSG_BUY_OUT_BUYOUT;?></td>
      </tr>
      <tr>
         <td></td>
         <td class="c1"><input type="checkbox" name="makeoffer_process" value="1" <? echo ($setts_tmp['makeoffer_process']==1) ? 'checked' : '';?>>
            <?=AMSG_BUY_OUT_MAKEOFFER;?></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_BUY_OUT_ALTER_WARNING;?></td>
      </tr>
      <? } else if ($page == 'sellitem_buttons') { ?>
      <!-- Sell Navigation buttons position page BEGINS HERE -->
      <tr class="c1">
         <td width="150" align="right"><?=AMSG_CHOOSE_POSITION;?></td>
         <td nowrap><input type="radio" name="sell_nav_position" value="1" checked>
            <?=AMSG_SELLNAV_POS_1;?></td>
      </tr>
      <tr>
         <td></td>
         <td class="c1"><input type="radio" name="sell_nav_position" value="2" <? echo ($setts_tmp['sell_nav_position']==2) ? 'checked' : '';?>>
            <?=AMSG_SELLNAV_POS_2;?></td>
      </tr>
      <? } else if ($page == 'nb_autorelists') { ?>
      <!-- Maximum auto relist number page BEGINS HERE -->
      <tr class="c1">
         <td width="150" align="right"><?=AMSG_ENABLE_AUTO_RELIST;?></td>
         <td><input type="checkbox" name="enable_auto_relist" value="1" <? echo ($setts_tmp['enable_auto_relist']==1) ? 'checked' : ''; ?> /></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td><?=AMSG_ENABLE_AUTO_RELIST_EXPL;?></td>
      </tr>
      <tr class="c2">
         <td width="150" align="right"><?=AMSG_ENTER_NUMBER;?></td>
         <td><input name="nb_autorelist_max" type="text" id="nb_autorelist_max" value="<?=$setts_tmp['nb_autorelist_max'];?>" size="8"></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_NB_AUTORELISTS_EXPL;?></td>
      </tr>
      <? } else if ($_REQUEST['page']=="enable_tax") { ?>
      <!-- Enable tax for site fees and standard ads -->
      <tr class="c1">
         <td align="right" nowrap><?=AMSG_ENABLE_TAX;?></td>
         <td><input type="checkbox" name="enable_tax" value="1" <? echo ($setts_tmp['enable_tax']==1) ? 'checked' : ''; ?> /></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_ENABLE_TAX_DESC;?></td>
      </tr>
      <tr class="c1">
         <td align="right" nowrap><?=AMSG_VAT_NUMBER;?></td>
         <td><input type="text" name="vat_number" value="<?=$setts_tmp['vat_number'];?>" size="20" /></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_VAT_NUMBER_DESC;?></td>
      </tr>
      <? } else if ($_REQUEST['page']=="invoices_settings") { ?>
      <!-- Site Invoices Settings -->
      <tr class="c1">
         <td align="right" nowrap><?=AMSG_INVOICE_HEADER;?></td>
         <td><textarea name="invoice_header" style="width=100%; height: 80px;"><?=$setts_tmp['invoice_header'];?></textarea></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_INVOICE_HEADER_DESC;?></td>
      </tr>
      <tr class="c1">
         <td align="right" nowrap><?=AMSG_INVOICE_FOOTER;?></td>
         <td><textarea name="invoice_footer" style="width=100%; height: 80px;"><?=$setts_tmp['invoice_footer'];?></textarea></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_INVOICE_FOOTER_DESC;?></td>
      </tr>
      <tr class="c1">
         <td align="right" nowrap><?=AMSG_INVOICE_COMMENTS;?></td>
         <td><textarea name="invoice_comments" style="width=100%; height: 80px;"><?=$setts_tmp['invoice_comments'];?></textarea></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_INVOICE_COMMENTS_DESC;?></td>
      </tr>
      <? } else if ($_REQUEST['page']=="mcrypt") { ?>
      <!-- Enable tax for site fees and standard ads -->
      <tr class="c1">
         <td align="right" nowrap><?=AMSG_ENABLE_MCRYPT;?></td>
         <td><input type="checkbox" name="mcrypt_enabled" value="1" <? echo ($setts_tmp['mcrypt_enabled']==1) ? 'checked' : ''; ?> /></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_ENABLE_MCRYPT_EXPL;?></td>
      </tr>
      <tr class="c1">
         <td align="right" nowrap><?=AMSG_MCRYPT_KEY;?></td>
         <td><input type="text" name="mcrypt_key" value="<?=$setts_tmp['mcrypt_key'];?>" size="20" /></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_MCRYPT_KEY_EXPL;?></td>
      </tr>
      <? } else if ($page == 'digital_downloads') { ?>
      <!-- Digital Downloads Settings BEGIN HERE -->
      <tr class="c1">
         <td align="right" nowrap><?=AMSG_ENABLE_DD;?></td>
         <td><input type="checkbox" name="dd_enabled" value="1" <? echo ($setts_tmp['dd_enabled']==1) ? 'checked' : ''; ?> /></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_ENABLE_DD_EXPL;?></td>
      </tr>
      <tr class="c1">
         <td align="right" nowrap><?=AMSG_DD_FOLDER;?></td>
         <td><input name="dd_folder" type="text" id="dd_folder" value="<?=$setts_tmp['dd_folder'];?>" size="40"></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_DD_FOLDER_EXPL;?></td>
      </tr>
      <tr class="c1">
         <td align="right"><?=AMSG_MAX_FILE_SIZE;?></td>
         <td><input name="dd_max_size" type="text" id="dd_max_size" value="<?=$setts_tmp['dd_max_size'];?>" size="8" maxlength="8">
            KBytes </td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_MEDIA_MAX_SIZE_EXPL;?></td>
      </tr>
      <tr class="c1">
         <td align="right"><?=AMSG_DD_EXPIRATION;?></td>
         <td><input name="dd_expiration" type="text" id="dd_expiration" value="<?=$setts_tmp['dd_expiration'];?>" size="8" maxlength="4"> <?=GMSG_DAYS;?></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_DD_EXPIRATION_EXPL;?></td>
      </tr>
      <tr class="c1">
         <td align="right"><?=AMSG_DD_DISCLAIMER;?></td>
         <td><textarea name="dd_terms" style="width: 100%; height: 150px;" id="dd_terms"><?=$setts_tmp['dd_terms'];?></textarea></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_DD_DISCLAIMER_EXPL;?></td>
      </tr>
      <? } else if ($_REQUEST['page']=="convert_tax") { ?>
      <!-- Enable tax for site fees and standard ads -->
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_TO_DO_TAX_1;?> <b><?=$convert_tax['invoices'];?></b> <?=AMSG_TO_DO_TAX_2;?>
      	<b><?=$convert_tax['winners'];?></b> <?=AMSG_TO_DO_TAX_3;?>.</td>
      </tr>
      <? if ($convert_tax['result']) { ?>
      <tr>
         <td align="right" class="explain" width="150"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_CLICK;?> [ <a href="convert_tax.php"><?=AMSG_HERE;?></a> ] <?=AMSG_TO_DO_TAX_5;?></td>
      </tr>
      <? } ?>
      <? } else if ($page == 'force_payment') { ?>
      <!-- Buyout force payment settings page BEGINS HERE -->
      <tr class="c1">
         <td width="150" align="right"><?=AMSG_ENABLE_FORCE_PAYMENT;?></td>
         <td nowrap><input type="checkbox" name="enable_force_payment" value="1" <? echo ($setts_tmp['enable_force_payment']==1) ? 'checked' : '';?>></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_ENABLE_FORCE_PAYMENT_EXPL;?></td>
      </tr>
      <tr class="c1">
         <td width="150" align="right"><?=AMSG_FORCE_PAYMENT_TIME;?></td>
         <td nowrap><input name="force_payment_time" type="text" id="force_payment_time" value="<?=$setts_tmp['force_payment_time'];?>" size="8"> <?=GMSG_MINUTES;?></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_FORCE_PAYMENT_TIME_EXPL;?></td>
      </tr>
      <? } else if ($page == 'ga_code') { ?>
      <!-- Google Analytics Code Settings BEGIN HERE -->
      <tr class="c1">
         <td align="right" width="150"><?=AMSG_ADD_GA_CODE;?></td>
         <td><textarea name="ga_code" style="width: 100%; height: 150px;" id="ga_code"><?=$setts_tmp['ga_code'];?></textarea></td>
      </tr>
      <? } else if ($page == 'r_hpfeat_items') { ?>
      <!-- Home Page Featured Reverse Auctions Settings BEGIN HERE -->
      <tr class="c1">
         <td width="150" align="right"><?=AMSG_ITEMS_PER_ROW;?></td>
         <td><input name="r_hpfeat_nb" type="text" id="r_hpfeat_nb" value="<?=$layout_tmp['r_hpfeat_nb'];?>" size="8"></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_ITEMS_PER_ROW_EXPL;?></td>
      </tr>
      <tr class="c1">
         <td align="right"><?=AMSG_MAX_ITEMS;?></td>
         <td><input name="r_hpfeat_max" type="text" id="r_hpfeat_max" value="<?=$layout_tmp['r_hpfeat_max'];?>" size="8"></td>
      </tr>
      <? } else if ($page == 'r_catfeat_items') { ?>
      <!-- Category Featured Reverse Auctions Settings BEGIN HERE -->
      <tr class="c1">
         <td width="150" align="right"><?=AMSG_ITEMS_PER_ROW;?></td>
         <td><input name="r_catfeat_nb" type="text" id="r_catfeat_nb" value="<?=$layout_tmp['r_catfeat_nb'];?>" size="8"></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_ITEMS_PER_ROW_EXPL;?></td>
      </tr>
      <tr class="c1">
         <td align="right"><?=AMSG_MAX_ITEMS;?></td>
         <td><input name="r_catfeat_max" type="text" id="r_catfeat_max" value="<?=$layout_tmp['r_catfeat_max'];?>" size="8"></td>
      </tr>
      <? } else if ($page == 'recent_reverse') { ?>
      <!-- Recently listed wanted ads Settings BEGIN HERE -->
      <tr class="c1">
         <td width="150" align="right"><?=AMSG_RECENT_REVERSE;?></td>
         <td><input name="r_recent_nb" type="text" id="r_recent_nb" value="<?=$layout_tmp['r_recent_nb'];?>" size="8"></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_RECENT_REVERSE_EXPL;?></td>
      </tr>
      <? } else if ($_REQUEST['page']=="fulltext_search_method") { ?>
      <!-- fulltext search method section -->
      <tr class="c1">
         <td align="right" nowrap><?=AMSG_USE_PLUS_SIGN;?></td>
         <td><input type="radio" name="fulltext_search_method" value="0" <? echo ($setts_tmp['fulltext_search_method']==0) ? 'checked' : ''; ?> /></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_USE_PLUS_SIGN_EXPL;?></td>
      </tr>
      <tr class="c1">
         <td align="right" nowrap><?=AMSG_USE_PLUS_AND_ASTERISK;?></td>
         <td><input type="radio" name="fulltext_search_method" value="1" <? echo ($setts_tmp['fulltext_search_method']==1) ? 'checked' : ''; ?> /></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_USE_PLUS_AND_ASTERISK_EXPL;?></td>
      </tr>
      <tr>
         <td align="right" class="explain"><img src="images/info.gif"></td>
         <td class="explain"><?=AMSG_FULLTEXT_SEARCH_METHOD_EXPL;?></td>
      </tr>
      <? } ?>
      <tr align="center">
         <td colspan="2"><input type="submit" name="form_save_settings" value="<?=AMSG_SAVE_CHANGES;?>"></td>
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
