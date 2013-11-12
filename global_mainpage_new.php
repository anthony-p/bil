<?
    $cachetime = 60;// * 60 * 24; //1day cache
    $cachefile = "cache/home_content.htm";

    if (file_exists($cachefile) && (time() - $cachetime < filemtime($cachefile)))
    {
        //include_once($cachefile);
        $template_output .= file_get_contents($cachefile);
    }else{
        //ob_start(); // start the output buffer
        $adult_categories = array();

        $sql_select_adult_cats = $db->query("SELECT * FROM " . DB_PREFIX . "categories WHERE minimum_age>0 AND parent_id=0");

        while ($adult_cats = $db->fetch_array($sql_select_adult_cats))
        {
            reset($categories_array);

            foreach ($categories_array as $key => $value)
            {
                if ($adult_cats['category_id'] == $key)
                {
                    list($category_name, $tmp_user_id) = $value;
                }
            }

            reset($categories_array);

            while (list($cat_array_id, $cat_array_details) = each($categories_array))
            {
                list($cat_array_name, $cat_user_id) = $cat_array_details;

                $categories_match = strpos($cat_array_name, $category_name);
                if (trim($categories_match) == "0")
                {
                    $adult_categories[] = intval($cat_array_id);
                }
            }
        }

        $adult_cats_query = null;
        if (count($adult_categories) > 0)
        {
            $adult_cats_list = $db->implode_array($adult_categories, ', ', false);

            $adult_cats_query = ' AND (category_id NOT IN (' . $adult_cats_list . ') AND addl_category_id NOT IN (' . $adult_cats_list . '))';
        }
        #Coupons (magento) recent deals
         #Coupons (magento) recent deals end.


        ## PHP Pro Bid v6.00 home page featured auctions
        $select_condition = "WHERE
            hpfeat=1 AND active=1 AND approved=1 AND closed=0 AND creation_in_progress=0 AND deleted=0 AND
            list_in!='store'" . $adult_cats_query;

        $template->set('feat_fees', $fees);
        $template->set('feat_db', $db);

        $item_details = $db->random_rows('auctions', 'auction_id, name, start_price, max_bid, currency, end_time', $select_condition, 1);
        $template->set('item_details', $item_details);

        ## featured ads
        $select_condition = "WHERE
            hpfeat=1 AND active=1 AND approved=1 AND closed=0 AND deleted=0 AND
            list_in!='store'" . $adult_cats_query;

        $template->set('feat_fees', $fees);
        $template->set('feat_db', $db);

        $global_item_details = $db->random_rows('partners', 'advert_id, name, description, advert_code, big_banner_code, currency, end_time', $select_condition, 1);
        $template->set('global_item_details', $global_item_details);


        $template->change_path('themes/' . $setts['default_theme'] . '/templates/');

        $simple_xml = simplexml_load_file('slider/index.html');


        //*******************************************************************

        $meta_content = '';

        foreach ($simple_xml->head->meta as $meta) {
            $meta_content .= $meta->asXML();
        }

        $link_content = '';

        foreach ($simple_xml->head->link as $link) {
            $link_content .= $link->asXML();
        }

        $head_script_content = '';

        foreach ($simple_xml->head->script as $script) {
            $head_script_content .= $script->asXML();
        }

        $header_content = $meta_content . $link_content . $head_script_content;

        $header_content = str_replace(
            array('data1/', 'engine1/'),
            array('slider/data1/', 'slider/engine1/'),
            $header_content
        );

        $body_script_content = '';

        foreach ($simple_xml->body->script as $script) {
            $body_script_content .= $script->asXML();
        }

        $body_script_content = str_replace(
            array('data1/', 'engine1/'),
            array('/slider/data1/', '/slider/engine1/'),
            $body_script_content
        );

        $wowslider_content = '';

        foreach ($simple_xml->body->div as $div) {
            $div_attributes = $div->attributes();
            if ($div_attributes->id[0] == "wowslider-container1") {

                $wowslider_content .= $div->asXML();

                $wowslider_content = str_replace(
                    array('data1/', 'engine1/'),
                    array('/slider/data1/', '/slider/engine1/'),
                    $wowslider_content
                );
            }
        }

//        var_dump($wowslider_content);
//        exit;

        $template->set("header_content", $header_content);
        $template->set("wowslider_content", $wowslider_content);
        $template->set("body_script_content", $body_script_content);

        //*******************************************************************


        $time = time();

        $group = " GROUP BY np_users.user_id ";
        $fields = NPDB_PREFIX . "users.banner, " . NPDB_PREFIX . "users.name, " .
            NPDB_PREFIX . "users.description, " . NPDB_PREFIX . "users.city, " .
            NPDB_PREFIX . "users.username, " . NPDB_PREFIX . "users.payment, " .
            NPDB_PREFIX . "users.price, " . NPDB_PREFIX . "users.end_date, bl2_users.first_name, " .
            NPDB_PREFIX . "users.founddrasing_goal, " .
            NPDB_PREFIX . "users.project_title, " .
            NPDB_PREFIX . "users.votes, " .
            " bl2_users.last_name, bl2_users.organization, bl2_users.email, bl2_users.id,
            (SELECT COUNT(*)
                FROM project_votes
                WHERE project_votes.campaign_id = np_users.user_id
                AND MONTH(FROM_UNIXTIME(project_votes.date)) = MONTH(NOW())
            ) AS votes ";
        $join = " FROM " . NPDB_PREFIX . "users INNER JOIN bl2_users " .
            " ON " . NPDB_PREFIX . "users.probid_user_id=bl2_users.id ";
        $sql_query = $db->query(
            "SELECT " . $fields . $join . " WHERE np_users.active=1
            AND np_users.homepage_featured=1
            AND np_users.disabled=0
            AND np_users.end_date>$time " . $group . "
            order by rand() limit 4"
        );

        $rows = array();

        while ($row = mysql_fetch_array($sql_query)) {
            $rows[] = $row;

        }

//        foreach ($rows as $_row) {
//            echo $_row['user_id'] . ' : ' . $_row['project_title'] . '<br />';
//        }
//        exit;
//        var_dump($rows); exit;
	 

        $template->set('campaigns_list', $rows);
      //  $template->set('add_class', $class);
      //  $template->set('status', $completed);


        $template_output_main = $template->process('mainpage_new.tpl.php');
        $template_output .= $template->process('mainpage_new.tpl.php');

        $template->change_path('templates/');

        $fp = fopen($cachefile, 'w');
        fwrite($fp, $template_output_main);
        fclose($fp);
        //ob_end_clean();
    }

?>
