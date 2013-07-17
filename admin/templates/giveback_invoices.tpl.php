<?
#################################################################
## PHP Pro Bid v6.04															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>


<div class="mainhead"><img src="images/tables.gif" align="absmiddle"> <?=$header_section;?></div>
<?=$msg_changes_saved;?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="4"><img src="images/c1.gif" width="4" height="4"></td>
		<td width="100%" class="ftop"><img src="images/pixel.gif" width="1" height="1"></td>
		<td width="4"><img src="images/c2.gif" width="4" height="4"></td>
	</tr>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="3" class="fside">
   <form action="giveback_invoices.php" method="post">
     <tr class="c3">
       <td colspan="3"><img src="images/subt.gif" align="absmiddle" hspace="4" vspace="2"> <b><?=strtoupper(AMSG_ADD_GIVEBACK_INVOICES);?></b></td>
    </tr>

      <tr class="c4">
         <td><img src="images/subt.gif" align="absmiddle" hspace="4" vspace="2"> <?=AMSG_ADD_NEW_INVOICE;?></td>
      </tr>
      <tr>
         <td>
		 
		 
		 <table width="100%" border="0" cellpadding="3" cellspacing="1" class="border">
            
			
			<tr class="c2">
               <td width="20">tracking id</td>
               <td><input type="text" name="tracking_id" size="50" /></td>
            </tr>
			
			
			<tr class="c2">
               <td width="20">non-profit id</td>
               <td><input type="text" name="np_userid" size="50" /></td>
            </tr>
         
		 <tr class="c2">
               <td width="20">non-profit name </td>
               <td><input type="text" name="np_name" size="50" /></td>
            </tr>
	


<tr class="c2">
               <td width="20">Gross sales </td>
               <td><input type="text" name="Sales" size="50" /></td>
            </tr>

<tr class="c2">
               <td width="20">Commission received </td>
               <td><input type="text" name="Commission" size="50" /></td>
            </tr>


	
		 <tr class="c2">
               <td width="20">Amount to non-profit</td>
               <td><input type="text" name="points" size="50" /></td>
            </tr>
		 
		  <tr class="c2">
               <td width="20">invoice date </td>
               <td><input type="text" name="invoice_date" size="50" />
			   <br> in this format: YYYY-MM-DD
			   
			   
			   
			   </td>
            </tr>
		<tr class="c2">
               <td width="20">user id who made purchase </td>
               <td><input type="text" name="user_id" size="50" /></td>
            </tr> 
		 <tr class="c2">
               <td width="20">user name who made purchase </td>
               <td><input type="text" name="username" size="50" /></td>
            </tr> 
			
			<tr class="c2">
               <td width="20">transaction type </td>
               <td>
			   
			    <select type="text" name="transtype" size="1" />

					<option value="Amazon">Amazon</option>
					<option value="Bring It Local Welcome">Bring It Local Welcome</option>
					<option value="Off site purchase">Off site purchase</option>
			   </select>
			   			   
			   
			   
			   
			   </td>
            </tr> 
			
			<input type="hidden" name="invoice_id" value="0"/>
			
		 </table>
		 
		 
		 
		 
		 </td>
      </tr>
      <tr>
         <td align="center" style="padding: 3px;"><input type="submit" name="form_save_settings" value="<?=AMSG_SAVE_CHANGES;?>"></td>
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
