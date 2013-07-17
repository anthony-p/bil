<?
#################################################################
## PHP Pro Bid v6.04															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
</td>
</tr>
</table>

<br>
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-top: 1px solid #777777; background-image: url(images/bg_foo.gif); background-repeat: repeat-x; background-position: top;">
   <tr height="35">
      <td class='contentfont'>&nbsp;&nbsp;&nbsp;<b>Copyright &copy;2008 PHP Pro Software LTD. All rights reserved.</b></td>
      <td class='contentfont' style="color: #666666;" align="right"><? if ($setts['debug_load_time']) { ?>
         <?=GMSG_PAGE_LOADED_IN;?>
         <?=$time_passed;?>
         <?=GMSG_SECONDS;?>
         <? } ?>
         <? if ($setts['debug_load_memory']) { ?>
         <?=GMSG_MEMORY_USAGE;?>
         <?=$memory_usage;?>
         KB
         <? } ?>
         &nbsp;&nbsp;&nbsp; </td>
   </tr>
</table>
</body></html>
