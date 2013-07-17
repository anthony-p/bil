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

	<?	while ($news_details = $db->fetch_array($sql_select_news)) { ?> 
	<div class="date"><?=show_date($news_details['reg_date'], false);?> </div>
	<div class="link"><a href="<?=process_link('content_pages', array('page' => 'news', 'topic_id' => $news_details['topic_id']));?>"><?=$news_details['topic_name'];?></a></div>
	<? } ?>

	<div class="bottomLink"><a href="<?=process_link('content_pages', array('page' => 'news'));?>"><?=MSG_VIEW_ALL;?> »</a></div>
</div>
