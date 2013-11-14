<?php
if ( !defined('INCLUDED') ) {
    die("Access Denied");
}
if ($mail_input_id)
{
    global $db;

    // Get campaign data
    $npuser_id = $_COOKIE['np_userid'];
    $sql = "SELECT np.username as url, np.project_title, np.name, u.organization, u.email, u.id as user_id FROM bl2_users u  INNER JOIN  np_users np ON u.id = np.probid_user_id  WHERE np.user_id = $npuser_id";
    $row_details = $db->get_sql_row($sql);

    // Get User data...
    $sql = "SELECT * FROM bl2_users WHERE id=$mail_input_id";
    $user_row_details = $db->get_sql_row($sql);

    if($row_details && $user_row_details){
        if ($row_details["url"]!='')
        {
            //$site = "Don't forget to visit your non-profit\'s website <a href=\"http:\/\/" . $row_details["url"] . ">here<\/a> to stay current on all their news and events.    <br>        <br>";
//            $np_link = "Don't forget to visit your non-profit's website <a href=\"http://" . $row_details["url"] . "\">here</a> to stay current on all their news and events. <br><br>";
            $np_link = "Please keep clicking through! And don't forget to visit the campaign page for <a href=\"http://" . $_SERVER["SERVER_NAME"] ."/{$row_details["url"]}>{$row_details["project_title"]}</a> to stay current on all their news and updates.";
        }
        else
        {
            $np_link = "";
        }



        $partner = $db->get_sql_row("SELECT * FROM " . DB_PREFIX . "partners WHERE advert_url='" . str_replace ('&', '%26', $url) ."'");

        $sql_news_detail = $db->query("SELECT news_id, news_name FROM " . NPDB_PREFIX . "news  WHERE user_id=" . $npuser_id);

        $news='';
        while ($news_detail = $db->fetch_array($sql_news_detail))
        {
            if ($news!="")
            {
                $news .= "<br> <br>";
            }
            $news .= "<b><a href=\"http://" . $_SERVER["SERVER_NAME"] . "/npnews.php?news_id=" . $news_detail["news_id"]  . "\">" . $news_detail["news_name"] . "</a></b>";
        }

        if ($news!='')
        {
            $news = " And by the way, here is their latest news item: <br><br>" . $news;
        }




        $send = true; // always sent;

        $wdir = getcwd();
        $t = __DIR__. '/language/english/mails/clickthroughs_template.phtml';
        var_dump($t);
        var_dump(getcwd());
        $message_template  = file_get_contents($wdir . '/language/english/mails/clickthroughs_template.phtml');
        die;
        $message_template  = file_get_contents(__DIR__ . '/language/english/mails/clickthroughs_template.phtml');
        ## text message - editable
        $text_message  = $message_template;
        $html_message = nl2br($text_message);


        $input =  $row_details['user_id'];
        $key = "bringitlocal firmhashkey"; // you can change it
        $encrypted_data = md5($input . $key);

        if ($row_details['organization'] != '')
            $nonprofit=$row_details['organization'];
        else
            $nonprofit=$row_details["name"];

        $vendor=$partner["name"];

        $activation_link = SITE_PATH . 'global_partners.php?key=' . $encrypted_data . '&sid=' . $input;
        $text_message = sprintf($text_message, $user_row_details['first_name'].' '.$user_row_details['last_name'], $setts['sitename'], $row_details['username'], $activation_link, $nonprofit, $vendor, $news, $np_link);
        $html_message = sprintf($html_message, $user_row_details['first_name'].' '.$user_row_details['last_name'], $setts['sitename'], $row_details['username'], $activation_link, $nonprofit, $vendor, $news, $np_link);

        //Mail for CC
        $aditional_mail = '';

        if ($user_row_details['email']!=null or  $user_row_details['email']!=0)
        {
            send_mail($user_row_details['email']. $aditional_mail, $setts['sitename'] . 'Thank you! Your click was a vote for community crowdfunding', $text_message,
                'support@bringitlocal.com', $html_message, null, $send);

            $summarry = "Name: {$user_row_details['first_name']}  {$user_row_details['last_name']}\n";
            $summarry .= "Sitename: {$setts['sitename']} \n";
            $summarry .= "UserId: {$user_row_details['id']} \n";
            $summarry .= "Action Link: $activation_link \n";
            $summarry .= "NonProfit: $nonprofit \n";
            $summarry .= "Vendor: $vendor \n";

            $support_mail = "support@bringitlocal.com";

            send_mail($support_mail,'Notification mail, user supported here community non-profit - Bring It Local', $summarry,
                            'support@bringitlocal.com', nl2br($summarry), null, $send);
        }
    }
}
?>
