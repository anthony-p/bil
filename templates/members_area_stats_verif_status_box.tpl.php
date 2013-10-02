<?
#################################################################
## PHP Pro Bid v6.00															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>

<table border="0" cellspacing="0" cellpadding="0" class="statsVerifStatusBox border">
   <tr>
      <th colspan="2" class="buyingtitle"><b><?=MSG_SELLER_STATUS;?></b></th>
   </tr>
   <tr>
      <td class="status"><? echo ($seller_details['seller_verified']) ? '<b>' . MSG_VERIFIED . '</b>' : '<span style="color: red; ">' . MSG_NOT_VERIFIED . '</span>'; ?></td>      
      <? if (!$seller_details['seller_verified']) { ?>
      <td nowrap class="contentfont">[ <a href="fee_payment.php?do=seller_verification"><?=MSG_GET_VERIFIED;?></a> ]</td>
      <? } ?>
   </tr>
</table>
