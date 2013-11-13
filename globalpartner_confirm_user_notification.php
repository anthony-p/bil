<?php
if ( !defined('INCLUDED') ) {
    die("Access Denied");
}
if ($mail_input_id)
{
    global $db;

    // Get campaign data
    $npuser_id = $_COOKIE['np_userid'];
    $sql = "SELECT np.url, np.name, u.organization, u.email, u.id as user_id FROM bl2_users u  INNER JOIN  np_users np ON u.id = np.probid_user_id  WHERE np.user_id = $npuser_id";
    $row_details = $db->get_sql_row($sql);

    // Get User data...
    $sql = "SELECT * FROM bl2_users WHERE id=$mail_input_id";
    $user_row_details = $db->get_sql_row($sql);

    if($row_details && $user_row_details){
        if ($row_details["url"]!='')
        {
            //$site = "Don't forget to visit your non-profit\'s website <a href=\"http:\/\/" . $row_details["url"] . ">here<\/a> to stay current on all their news and events.    <br>        <br>";
            $np_link = "Don't forget to visit your non-profit's website <a href=\"http://" . $row_details["url"] . "\">here</a> to stay current on all their news and events. <br><br>";
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

        ## text message - editable
                $text_message = '<div class="globalPartnerMsg">Hi %1$s,
        \r\n
        Thanks for using Bring It Local to support  %5$s. We see you just clicked through the banner to %6$s.
        If you did end up making a purchase, please expect to see the fundraising results on your member page report within 2 days.
        If this does not show up or you think there was some error please let us know.
        \r\n

        Thanks again for supporting %7$s.
        \r\n\r\n
        %7$s
        \r\n

        %8$s

        If you don\'t want to receive similar notification emails any more, please click the link below to unsubscribe.
        \r\n
        %4$s
        \r\n
        Best regards,
        \r\n
        The Bring It Local staff</div>';



        ## html message - editable
        $html_message = '<div class="globalPartnerMsg">Hi %1$s,

        <br>
        <br>

        Thanks for using Bring It Local to support %5$s. We see you just clicked through the banner to %6$s.
        If you did end up making a purchase, please expect to see the fundraising results on your member page report within 2 days.
        If this does not show up or you think there was some error please let us know.
        <br>
        <br>

        Thanks again for supporting %5$s.
        <br>
        <br>

         %7$s

        <br>
        <br>
         %8$s

        If you don\'t want to receive similar notification emails any more, please click the link below to unsubscribe.
        <br>
        <br>
        <a href ="%4$s">%4$s</a>
        <br>
        <br>

        Best regards,
        <br>
        <br>
        The Bring It Local staff</div>';

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
            send_mail($user_row_details['email']. $aditional_mail, $setts['sitename'] . 'Thanks for supporting your community non-profit - Bring It Local', $text_message,
                'support@bringitlocal.com', $html_message, null, $send);

            $summarry = "Name: {$user_row_details['first_name']}  {$user_row_details['last_name']}\n";
            $summarry .= "Sitename: {$setts['sitename']} \n";
            $summarry .= "UserId: {$user_row_details['id']} \n";
            $summarry .= "Action Link: $activation_link \n";
            $summarry .= "NonProfit: $nonprofit \n";
            $summarry .= "Vendor: $vendor \n";

//            $support_mail = "support@bringitlocal.com";
            $support_mail = "lilian.codreanu@gmail.com";

            send_mail($support_mail,'Notification mail, user supported here community non-profit - Bring It Local', $summarry,
                            'support@bringitlocal.com', nl2br($summarry), null, $send);
        }
    }
}
?>