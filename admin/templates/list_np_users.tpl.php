<?

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>

<div class="mainhead"><img src="images/user.gif" align="absmiddle">
   <?=$header_section;?>
</div>
<?=$msg_changes_saved;?>
<?=$display_formcheck_errors;?>
<?=$management_box;?>
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
<table width="100%" border="0" cellpadding="3" cellspacing="3" class="fside tablesorter" id="npList">
    <thead>
        <tr class="c4">
          <th class="header" width="50" align="center">UserId</th>
          <th width="90" >np-Username</th>
          <th width="150" align="center">E-mail address</th>
          <th width="90" align="center">Contact Name</th>
          <th width="150" align="center">Report selected</th>
          <th filter="false" width="150" align="center"><?=AMSG_OPTIONS;?></th>
       </tr>
    </thead>
    <tbody>
        <?=$np_users_content;?>
    </tbody>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
   <tr>
      <td width="4"><img src="images/c3.gif" width="4" height="4"></td>
      <td width="100%" class="fbottom"><img src="images/pixel.gif" width="1" height="1"></td>
      <td width="4"><img src="images/c4.gif" width="4" height="4"></td>
   </tr>
</table>

<script type="text/javascript">
		$(document).ready(function() {
			$('#npList').tableFilter();
            $("#npList").tablesorter(
                {
                    headers: {
                                5: {
                                    sorter: false
                                }
                            }
                }
            );
		});
</script>