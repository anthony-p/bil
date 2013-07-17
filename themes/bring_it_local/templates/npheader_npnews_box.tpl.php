<?
#################################################################
## PHP Pro Bid v6.00															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<div class="newsList">
	<?	while ($news_details = $db->fetch_array($sql_select_npnews)) { ?> 
	<div class="date"><?=show_date($news_details['reg_date'], false);?> </div>
	<div class="link"><a href="<?=process_link('npnews', array('news' => 'news', 'news_id' => $news_details['news_id']));?>"><?=$news_details['news_name'];?></a></div>
	<? } ?>
	<div class="bottomLink"><a href="<?=process_link('npnews', array('news' => 'news'));?>"><?=MSG_VIEW_ALL;?> »</a></div>
</div>
