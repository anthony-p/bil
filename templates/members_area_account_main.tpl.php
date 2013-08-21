<?php
/**
 * Created by Lilian Codreanu.
 * User: Lilian Codreanu
 * Date: 8/21/13
 * Time: 12:59 AM
 */

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>

<div class="main_campaigns_page">
    <h2><?=MSG_MY_PROFILE;?></h2>

    <div>
        <ul>
           <li><span><?=MSG_MM_PERSONAL_INFO;?></span>
                <a href="<?=process_link('members_area', array('page' => 'account', 'section' => 'editinfo'));?>" class="create"><?=MSG_MM_PERSONAL_INFO;?></a>
            </li>
          
          <li><span><?=MSG_MM_ABOUT_ME?></span>
            
                     <li><a href="/about_me,page,edit,section,members_area" class="closed"><?=MSG_MM_EDIT?></a></li>
                     <li><a href="/about_me,page,view,section,members_area" class="closed"><?=MSG_MM_VIEW?></a></li>
                 
            </li>
           
        </ul>
    </div>
</div>
