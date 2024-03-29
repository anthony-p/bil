<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>

<form action="npmembers_news.php" method="post" name="form_news">
	<input type="hidden" name="do" value="<?=$do;?>" />
	<input type="hidden" name="news_id" value="<?=$news_id;?>" />
	<input type="hidden" name="news" value="<?=$news_handle;?>" />
	<input type="hidden" name="operation" value="submit" />
	<table width="100%" border="0" cellpadding="3" cellspacing="3" class="fside">
      <tr>
         <td colspan="2" align="center" class="c4"><?=$manage_box_title;?></td>
      </tr>
      <? foreach ($languages as $value)
      {
      	$sql_select_topic = $db->query("SELECT * FROM " . NPDB_PREFIX . "news WHERE
      		news_id='" . $news_id . "' AND news_lang='" . $value . "'");

      	$is_topic = $db->num_rows($sql_select_topic);

      	(array) $row_topic = null;

      	if ($is_topic) $row_topic = $db->fetch_array($sql_select_topic); ?>

	  <tr class="c3">
         <td colspan="2"><?=GMSG_LANGUAGE;?>
            : <b>
            <?=$value;?>
            </b></td>
      </tr>
      <tr class="c1">
         <td nowrap align="right"><?=AMSG_TOPIC_NAME;?></td>
         <td width="100%"><input type="text" name="news_name_<?=$value;?>" value="<?=$row_topic['news_name'];?>" size="50" /></td>
      </tr>
      <tr class="c1">
         <td nowrap align="right"><?=AMSG_TOPIC_CONTENT;?></td>
         <td width="100%"><textarea id="news_content_<?=$value;?>" name="news_content_<?=$value;?>" style="width: 400px; height: 200px; overflow: hidden;"><?=$row_topic['news_content'];?></textarea>
            <script>
					var oEdit_<?=$value;?> = new InnovaEditor("oEdit_<?=$value;?>");
					oEdit_<?=$value;?>.width="100%";//You can also use %, for example: oEdit1.width="100%"
					oEdit_<?=$value;?>.height=250;
					oEdit_<?=$value;?>.REPLACE("topic_content_<?=$value;?>");//Specify the id of the textarea here
				</script></td>
      </tr>
      <? if ($page_handle == 'custom_page') { ?>
      <tr class="c1">
         <td nowrap align="right"><?=AMSG_SHOW_LINK_HP;?></td>
         <td width="100%"><input type="checkbox" name="show_link" value="1" <? echo ($row_topic['show_link']==1) ? 'checked' : '';?>></td>
      </tr>
      <? } ?>
      <? } ?>
      <tr>
         <td colspan="2" align="center"><input type="submit" name="form_content_save" value="<?=AMSG_SAVE_CHANGES;?>">
         </td>
      </tr>
	</table>

</form>
