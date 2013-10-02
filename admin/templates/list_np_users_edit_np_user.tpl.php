<?

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td width="4"><img src="images/c1.gif" width="4" height="4"></td>
        <td width="100%" class="ftop"><img src="images/pixel.gif" width="1" height="1"></td>
        <td width="4"><img src="images/c2.gif" width="4" height="4"></td>
    </tr>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="3" class="fside">
    <tr class="c3">
        <td colspan="2"><img src="images/subt.gif" align="absmiddle" hspace="4" vspace="2"> <b>Edit np-user</b></td>
    </tr>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="3" class="fside">
    <form action="list_np_users.php" method="post" name="form_np_user">
        <input type="hidden" name="do" value="<?=$do;?>" />
        <input type="hidden" name="np_user_id" value="<?=$user_details['user_id'];?>" />
        <input type="hidden" name="operation" value="submit" />
        <tr class="c1">
            <td nowrap><?=AMSG_USERNAME;?></td>
            <td width="100%"><?=$user_details['npu_username'];?></td>
        </tr>
        <tr class="c1">
            <td nowrap>Active</td>
            <td width="100%"><?=$user_details['active']?'Active':'Inactive';?></td>
        </tr>
        <tr class="c3">
            <td align="right" nowrap></td>
            <td></td>
        </tr>
<?php /*
        <tr class="c2">
            <td nowrap>Users</td>
            <td width="100%"><?=$users?></td>
        </tr>
        <tr class="c2">
            <td nowrap>Link Active</td>
            <td width="100%"><input type="checkbox" name="user_link_active" value="1" <?=$user_details['user_link_active']?'checked="checked"':'';?> /></td>
        </tr>
*/ ?>
        <tr class="c3">
            <td colspan="2" > <b>Repor</b></td>
        </tr>
        <tr class="c2">
            <td nowrap>E-mail</td>
            <td width="100%"><input type="text" name="report_email" value="<?=$user_details["report_email"]?>" /></td>
        </tr>
        <tr class="c2">
            <td nowrap>Daily</td>
            <td width="100%"><input type="checkbox" name="report_daily" value="1" <?=$user_details['report_daily']?'checked="checked"':'';?> /></td>
        </tr>
        <tr class="c2">
            <td nowrap>Weekly</td>
            <td width="100%"><input type="checkbox" name="report_weekly" value="1" <?=$user_details['report_weekly']?'checked="checked"':'';?> /></td>
        </tr>
        <tr class="c2">
            <td nowrap>Monthly</td>
            <td width="100%"><input type="checkbox" name="report_monthly" value="1" <?=$user_details['report_monthly']?'checked="checked"':'';?> /></td>
        </tr>
        <tr>
            <td colspan="2" align="center" class="c3"><input type="submit" name="form_np_user_save" value="<?=AMSG_SAVE_CHANGES;?>">
            </td>
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
<br>
