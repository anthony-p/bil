<form action="categories.php" method="POST" name="form_advanced_search">
<input type="hidden" name="option" value="category_search">
<!-- <?=GMSG_SEARCH;?> -->
<input name="keywords_cat_search" type="text" id="shop_name" size="50" value="<?=$item_details['keywords_cat_search'];?>" onfocus="this.value=''" class="input">

<?=GMSG_IN;?>


<?
$cats_src_drop_down = '<select name="parent_id" id="parent_id" class="selectBox"> '.
	
	(array) $src_categories = null;
	
	$src_categories[] = array('category_id' => $parent_id, 'name' => $category_lang[$parent_id]);
	
	if ($parent_id > 0 && $parent_id != $main_category_id)
	{
		$cat_id = $parent_id;
	
		while ($cat_id)
		{
			$cat_id = $db->get_sql_field("SELECT parent_id FROM " . DB_PREFIX . "categories WHERE category_id='" . $cat_id . "'", 'parent_id');
			if ($cat_id)
			{
				$src_categories[] = array('category_id' => $cat_id, 'name' => ' - ' . $category_lang[$cat_id]);
			}
		}
	
		$src_categories[] = array('category_id' => 0, 'name' => '----------------');

		$sql_select_src_subcats = $db->query("SELECT category_id FROM " . DB_PREFIX . "categories WHERE 
			parent_id=0 AND hidden=0 AND user_id=0 ORDER BY order_id ASC, name ASC");
		
		while ($row_cats = $db->fetch_array($sql_select_src_subcats)) 
		{
			$src_categories[] = array('category_id' => $row_cats['category_id'], 'name' => $category_lang[$row_cats['category_id']]);
		}
	}
	else
	{
		$src_categories[] = array('category_id' => 0, 'name' => 'ALL CATEGORIES');
		$sql_select_src_subcats = $db->query("SELECT category_id FROM " . DB_PREFIX . "categories WHERE 
			parent_id='" . $parent_id . "' ORDER BY order_id ASC, name ASC");
		
		while ($row_cats = $db->fetch_array($sql_select_src_subcats)) 
		{
			$src_categories[] = array('category_id' => $row_cats['category_id'], 'name' => $category_lang[$row_cats['category_id']]);
		}
	}
	
	foreach ($src_categories as $key => $value)
	{
		$category_link = process_link('categories', array('category' => $value['name'], 'parent_id' => $value['category_id']));
	
		$cats_src_drop_down .= '<option value="' . $value['category_id'] . '" ' . (($value['category_id'] == $parent_id) ? 'selected' : '') . '>'.
			$value['name'] . '</option> ';
	}
	$cats_src_drop_down .= '</select>';


?>









 <?=$cats_src_drop_down;?>
<input type="submit" value="<?=GMSG_SEARCH;?>" name="form_search_proceed" class="btn">

</form>
