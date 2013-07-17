<?
#################################################################
## PHP Pro Bid v6.00															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>

<table border="0" cellpadding="0" cellspacing="0" class="sellItemPreview">
	<tr class="c4">
		<td colspan="3" class="title">Auction Preview</td>
	</tr>
	<tr>
		<td colspan="3"><?=$auction_details_page;?></td>
	</tr>
	<?=$auction_fees_box;?>
	<?=$auction_fees_suspension_warning;?>
	<?=$auction_terms_box;?>
	<tr>
		<td></td>
		<td colspan="2"><?=nav_btns_position(true, true); ?></td>
	</tr>
</table>
<br>
