<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>

<?=$header_registration_message;?>
<br>
<?=$invalid_login_message;?>
<table width="80%" border="0" cellpadding="5" cellspacing="5" align="center" class="contentfont">
   <tr><td colspan="2"><p style="font-family:verdana;font-size:180%;color:red; line-height:25px">STOP: This page is for non-profit organizations who want to enroll or sign in to their account.</p>
  <p style="font-family:verdana;font-size:120%;color:red; line-height:25px">If you are trying to register or sign in as a <i>user</i> on the site you need to go <a href="/login.php">here>></a></p></td></tr>


   <tr valign="top">
      <td width="50%" align="center" class="border c2"><p><b>
            <?=MSG_NEW_TO;?>
            <?=$setts['sitename'];?>?</b><br>
            <br>
            <?=MSG_REGISTRATION_MSG;?>
         <form action="npregister.php" method="post">
            <input name="submit" type="submit" class="buttons" value="<?=MSG_REGISTER_FOR_ACCOUNT;?>">
         </form>
         </p>
      </td>
      <td width="50%" align="center" class="border c2"><b>
         <?=MSG_ALREADY_A;?>
         <?=$setts['sitename'];?>
         <?=MSG_USER?>?
         </b><br>
         <form action="<?=($setts['enable_enhanced_ssl']) ? $setts['site_path_ssl'] : SITE_PATH;?>np/nplogin.php" method="post">
         <input type="hidden" name="operation" value="submit">
         <input type="hidden" name="redirect" value="<?=$redirect;?>">
         	<table width="100%" border="0" cellpadding="2" cellspacing="2" align="center">
               <tr>
                  <td align="right"><?=MSG_USERNAME?></td>
                  <td><input name="username" type="text" id="username"></td>
               </tr>
               <tr>
                  <td align="right"><?=MSG_PASSWORD?></td>
                  <td><input name="password" type="password" id="password"></td>
               </tr>
               <tr>
                  <td align="center" colspan="2"><input type="checkbox" name="remember_username" value="1"> <?=MSG_REMEMBER_ME;?></td>
               </tr>
               <tr>
                  <td colspan="2" align="center"><input name="form_login_proceed" type="submit" id="form_login_proceed" value="<?=MSG_LOGIN_TO_MEMBERS_AREA;?>"></td>
               </tr>
            </table>
         </form>
         <a href="<?=SITE_PATH;?>np/npretrieve_password.php">
         <?=MSG_LOST_PASSWORD;?>
         </a> </td>
   </tr>
</table>
