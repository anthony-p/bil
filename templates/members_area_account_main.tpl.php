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
    <h2>My Campaigns</h2>

    <div>
        <ul>
           <li><span><?=MSG_MM_PERSONAL_INFO;?></span>
                <a href="<?=process_link('members_area', array('page' => 'account', 'section' => 'editinfo'));?>" class="create"><?=MSG_MM_PERSONAL_INFO;?></a>
            </li>
           <li><span><?=MSG_MM_MANAGE_ACCOUNT;?></span>
                <a href="<?=process_link('members_area', array('page' => 'account', 'section' => 'management'));?>" class="edit"><?=MSG_MM_MANAGE_ACCOUNT;?></a>
            </li>
          <li><span><?=MSG_MM_ABOUT_ME?></span>
              <ul>
                     <li><a href="/about_me,page,edit,section,members_area" class="closed"><?=MSG_MM_EDIT?></a></li>
                     <li><a href="/about_me,page,view,section,members_area" class="closed"><?=MSG_MM_VIEW?></a></li>
                  </ul>
            </li>
            <li><span><?=MSG_MESSAGES?></span>
                <ul>
                        <li><a class="view" href="<?=process_link('members_area', array('page' => 'messaging', 'section' => 'received'));?>"><?=MSG_MM_RECEIVED;?></a></li>
                        <li><a class="view" href="<?=process_link('members_area', array('page' => 'messaging', 'section' => 'sent'));?>"><?=MSG_MM_SENT;?></a></li>
                    </ul>
            </li>
        </ul>
    </div>
</div>
