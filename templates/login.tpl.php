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
<script language=JavaScript src='/scripts/jquery/jquery-1.9.1.js'></script>
<?php if (isset($header_registration_message)) echo $header_registration_message;?>
<br>
<?php if (isset($invalid_login_message)) echo $invalid_login_message;?>



<div class="login-cont clrfix">

   <div class="log-form">
       <div class="left">
           <h2> <?=MSG_NEW_TO;?> <?=$setts['sitename'];?>? <?=MSG_REGISTRATION_MSG;?></h2>
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
                   <input name="email" type="text" id="email" placeholder="<?=MSG_EMAIL_ADDRESS?>" value="<?=isset($_COOKIE['probid_username_cookie']) ? $_COOKIE['probid_username_cookie'] : ''; ?>" class="text">
               </div>
               <div class="form-row">
                   <input name="password" type="password" id="password" placeholder="<?=MSG_PASSWORD?>" class="text" />
               </div>
               <div class="form-row check">
                   <input type="checkbox" name="remember_username" value="1" <?php if(isset($_COOKIE['probid_username_cookie'])) {
                       echo 'checked="checked"';
                   }
                   else {
                       echo '';
                   }
                   ?>>
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
