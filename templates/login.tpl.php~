<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
## (Mods-Store) -> Shopping Cart											##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>

<?=$header_registration_message;?>
<br>
<?=$invalid_login_message;?>



<div class="login-cont">
    <p><span>ATTENTION:</span> This page is for individuals who want to register for the first time or sign in to their account.<br />
    If you are trying to enroll as a <span>non-profit organization</span> or logon to manage your <span>organization's</span> account you need to go <a href="/np/nplogin.php">here </a></p>
   <div class="log-form">
       <div class="left">
           <h2> <?=MSG_NEW_TO;?> <?=$setts['sitename'];?><?=MSG_REGISTRATION_MSG;?>?</h2>
           <form action="register.php" method="post">
               <input name="submit" type="submit" class="buttons" value="<?=MSG_REGISTER_FOR_ACCOUNT;?>">
           </form>
       </div>

       <div class="right">
           <h2>
               <?=MSG_ALREADY_A;?>
               <?=$setts['sitename'];?>
               <?=MSG_USER?>?
           </h2>

           <form action="<?=($setts['enable_enhanced_ssl']) ? $setts['site_path_ssl'] : SITE_PATH;?>login.php" method="post">
               <input type="hidden" name="operation" value="submit">
               <input type="hidden" name="redirect" value="<?=$redirect;?>">
               <input type="hidden" name="sc_id" value="<?=$sc_id;?>">
               <div class="form-row">
                   <input name="email" type="text" id="email" placeholder="<?=MSG_EMAIL_ADDRESS?>" class="text">
               </div>
               <div class="form-row">
                   <input name="password" type="password" id="password" placeholder="<?=MSG_PASSWORD?>" class="text" />
               </div>
               <div class="form-row check">
                   <input type="checkbox" name="remember_username" value="1">
                   <label><?=MSG_REMEMBER_ME;?></label>

               </div>
               <div class="clear"></div>
               <a href="<?=SITE_PATH;?>retrieve_password.php">
                   <?=MSG_LOST_PASSWORD;?>
               </a>
               <div class="clear"></div>
               <input name="form_login_proceed" type="submit" id="form_login_proceed" class="buttons" value="<?=MSG_LOGIN_TO_MEMBERS_AREA;?>">
           </form>
       </div>
   </div>
</div>