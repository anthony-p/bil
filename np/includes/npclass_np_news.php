<?
#################################################################
## PHP Pro Bid v6.00															##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

class news extends npdatabase
{
	function subpage_title($news_handle)
	{
		(string) $output = null;

		switch ($news_handle)
		{
			case 'help': $output = AMSG_EDIT_HELP_SECTION;
				break;
			case 'news': $output = AMSG_EDIT_NEWS_SECTION;
				break;
			case 'faq': $output = AMSG_EDIT_FAQ_SECTION;
				break;
			case 'custom_page': $output = AMSG_CUSTOM_PAGES_MANAGEMENT;
				break;
			case 'about_us': $output = AMSG_EDIT_ABOUT_US_PAGE;
				break;
			case 'contact_us': $output = AMSG_EDIT_CONTACT_US_PAGE;
				break;
			case 'terms': $output = AMSG_EDIT_TERMS_PAGE;
				break;
			case 'privacy': $output = AMSG_EDIT_PRIVACY_PAGE;
				break;
			case 'announcements': $output = AMSG_EDIT_MEMBERS_ANNOUNCEMENTS;
				break;
			default:
				$output = GMSG_NA;
		}

		return $output;
	}

	function insert_news($variables_array, $lang, $user_id, $news_handle)
	{
		$show_link = ($news_handle == 'custom_page') ? $variables_array['show_link'] : 1;
		
		$result = $this->query("INSERT INTO " . NPDB_PREFIX . "news
			(news_name, news_content, news_lang, reg_date, user_id, news_handle, show_link) VALUES
			('" . $variables_array['news_name_' . $lang] . "', '" . $variables_array['news_content_' . $lang] . "',
			'" . $lang . "', '" . CURRENT_TIME . "', '" . $user_id . "', '" . $news_handle . "', '" . $show_link . "')");

		return $result;
	}

	function edit_news($variables_array, $lang, $news_id, $news_handle)
	{
		$is_topic = $this->count_nprows('news', "WHERE
			news_lang='" . $lang . "' AND news_id='" . $news_id . "' AND news_handle='" . $news_handle . "'");

		if ($is_topic)
		{
			$show_link = ($news_handle == 'custom_page') ? $variables_array['show_link'] : 1;
			
			$result = $this->query("UPDATE " . NPDB_PREFIX . "news SET
				news_name='" . $variables_array['news_name_' . $lang] . "',
				news_content='" . $variables_array['news_content_' . $lang] . "', show_link='" . $show_link . "' WHERE
				news_lang='" . $lang . "' AND news_id='" . $news_id . "' AND news_handle='" . $news_handle . "'");
		}
		else
		{
			$result = $this->insert_news($variables_array, $lang, $news_id, $news_handle);
		}

		return result;
	}

	function delete_news($news_id, $news_handle)
	{
		$result = $this->query("DELETE FROM " . NPDB_PREFIX . "news WHERE news_id='" . $news_id . "' AND news_handle='" . $news_handle . "'");

		return $result;
	}
}

?>
