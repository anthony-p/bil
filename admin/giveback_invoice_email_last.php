<?

if ($mail_input_id and $mail_input_id!='0')
{

    $sql_select= mysql_query("SELECT np.url, u.name, u.globalpartner_email, u.npname, u.user_id, u.npuser_id, u.username, u.email FROM " . DB_PREFIX . "users u  INNER JOIN  np_users np ON u.npuser_id = np.user_id  WHERE u.user_id='" . $mail_input_id . "'")
                    or die(mysql_error());
                    
    $row_details= mysql_fetch_array($sql_select);
    
    if ($row_details["url"]!='')
    {
        //$site = "Don't forget to visit your non-profit\'s website <a href=\"http:\/\/" . $row_details["url"] . ">here<\/a> to stay current on all their news and events.    <br>        <br>";
        $np_link = "Don't forget to visit " .$row_details["npname"]. "'s website <a href=\"http://" . $row_details["url"] . "\">here</a> to stay current on all their news and events. <br><br>";
    }
    else
    {
        $np_link = "";
    }
    
    $npuser_id = $row_details["npuser_id"];

    $sql_news_detail = mysql_query("SELECT news_id, news_name FROM np_news  WHERE user_id=" . $npuser_id);
    
    $news='';
    while ($news_detail = mysql_fetch_array($sql_news_detail))
    {
        if ($news!="")
        {
            $news .= "<br> <br>";
        }
        $news .= "<b><a href=\"http://" . $_SERVER["SERVER_NAME"] . "/npnews.php?news_id=" . $news_detail["news_id"]  . "\">" . $news_detail["news_name"] . "</a></b>";
        $news .="<br><br>";
        
    }
    
    if ($news!='')
    {
        $news = " And by the way, here is their latest news item: <br><br>" . $news;
    }   
    

}


$send = true; // always sent;

## html message - editable
$html_message = '<img src="http://' . $_SERVER["SERVER_NAME"]. '/images/bringitlocalogo.gif" alt="Bring It Local - shop Main Street not Wall Street" /> <br/><br/> ';

$html_message .= 'Hi %1$s,

<br>
<br>

Thanks for using Bring It Local to support %5$s. 
<br>
<br>
We see you made purchases of $%9$s to %6$s. This has raised $%10$s for %5$s. Please come back and click through Bring It Local everytime you shop online. It all adds up and makes a difference!!
<br>

<br>

Thanks again for supporting %5$s.
<br>
<br>

 %7$s

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
The Bring It Local staff';


$input =  $row_details['user_id'];
$key = "bringitlocal firmhashkey"; // you can change it
$encrypted_data = md5($input . $key);

$nonprofit=$row_details["npname"];
$vendor='Amazon';


$activation_link = 'http://' . $_SERVER["SERVER_NAME"].'/global_partners.php?key=' . $encrypted_data . '&sid=' . $input;
##$text_message = sprintf($text_message, $row_details['name'], 'BringItLocal', $row_details['username'], $activation_link, $nonprofit, $vendor, $news, $np_link, $gross, $points);
$html_message = sprintf($html_message, $row_details['name'], 'BringItLocal', $row_details['username'], $activation_link, $nonprofit, $vendor, $news, $np_link, $gross, $points);

 
$subject = 'Your recent shopping from %1$s has benefited %2$s - always use Bring It Local';
$subject = sprintf($subject, $vendor, $nonprofit);

##for testing the notifications use the line below - emails will all go to the specified address not the site users Then comment out when good to go live 
$row_details['email'] = "support@bringitlocal.com";

if ($row_details['globalpartner_email']==null or  $row_details['globalpartner_email']!=0)
{    
   
   $headers = 'From: Bring It Local <support@bringitlocal.com>' . PHP_EOL .
            'X-Mailer: PHP-' . phpversion() . PHP_EOL .
            'Content-type: text/html; charset=iso-8859-1' . PHP_EOL;
        if ($row_details['email']=='')
        {
            console_out("anonymous user email - no email was sent.", false);
        }
        else        
        {
            mail($row_details['email'], $subject, $html_message, $headers) ; 
            console_out("Sent email to ".$row_details['email'], false);
            console_out($html_message, false);
        }
}   
 
?>
