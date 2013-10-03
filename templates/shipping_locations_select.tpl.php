<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<link href="<? echo (IN_ADMIN == 1) ? '' : 'themes/' . $setts['default_theme'] . '/'; ?>style.css" rel="stylesheet" type="text/css">
<script language="javascript" src="includes/main_functions.js" type="text/javascript"></script>
<SCRIPT LANGUAGE="JavaScript"><!--
	function copyForm()
	{
		// we first save the locations set
		SelectOption(popupForm.countries_id);
		popupForm.submit_form.value = 1;
		popupForm.submit();
		
		// now we close the popup and refresh the parent form so the new locations set is shown
		opener.document.<?=$form_name;?>.submit();
		window.opener.focus;
		window.close();
		return false;
	}
//-->
</SCRIPT>
</head>

<body>
<form name="popupForm" onSubmit="return copyForm()">
	<input type="hidden" name="option" value="<?=$option;?>">
	<input type="hidden" name="id" value="<?=$id;?>">
	<input type="hidden" name="submit_form" value="0">
   <table width="100%" border="0" cellpadding="3" cellspacing="2" class="border">
      <tr>
         <td class="c3" colspan="2"><b>
            <?=$page_title;?>
            </b></td>
      </tr>
      <tr class="c1">
         <td colspan="2"><table width="100%" border="0" cellspacing="2" cellpadding="2">
               <tr>
                  <td width="45%">[ <?=GMSG_ALL_COUNTRIES;?> ] </td>
                  <td width="10%">&nbsp;</td>
                  <td width="45%">[ <?=GMSG_SELECTED_COUNTRIES;?> ] </td>
               </tr>
               <tr>
                  <td><?=$all_countries_table;?></td>
                  <td align="center"><input type="button" name="location_Disable" value=" -&gt; " style="width: 50px;" onclick="MoveOption(this.form.all_countries, this.form.countries_id)" />
                     <br />
                     <br />
                     <input type="button" name="location_Enable" value=" &lt;- " style="width: 50px;" onclick="MoveOption(this.form.countries_id, this.form.all_countries)" /></td>
                  <td><?=$selected_countries_table;?></td>
               </tr>
            </table></td>
      </tr>
      <tr class="c2">
      	<td width="150"><?=MSG_ADDITIONAL_COST;?></td>
      	<td><?=$setts['currency'];?> <input type="text" name="amount" value="<?=$location_details['amount'];?>" size="8"> 
      		<select name="amount_type" id="amount_type"> '.
					<option value="flat" selected><?=GMSG_FLAT;?></option>
					<option value="percent" <? echo (($location_details['amount_type']== 'percent') ? 'selected' : '');?>><?=GMSG_PERCENT;?></option> 
				</select></td>
      </tr>
      <!--
      <tr class="c2">
      	<td><?=MSG_SET_AS_DEFAULT_LOC;?></td>
      	<td><input type="checkbox" name="pc_default" value="1" <? echo ($location_details['pc_default']) ? 'checked' : ''; ?>></td>
      </tr>
      -->
      <tr class="c4">
         <td align="center" colspan="2"><INPUT TYPE="button" VALUE="<?=GMSG_PROCEED;?>" onClick="copyForm()"></td>
      </tr>
   </table>
</form>
</body>
</html>
