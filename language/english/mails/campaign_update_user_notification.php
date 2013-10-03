<?php



if (!defined('INCLUDED')) {
    die("Access Denied");
}

$send = true; // always sent;

// check if comment need normalize
if (strpos($project_updates["comment"], '&lt;!DOCTYPE html&gt;') !== FALSE) {
    $comment = html_entity_decode($project_updates["comment"]);
} else {
    $comment = $project_updates["comment"];
}

// fix with tinymce images links
$comment = str_replace('src="scripts/', 'src="'. SITE_PATH.'scripts/',$comment);

preg_match("/\<body\>(.*)\<\/body\>/uis", $comment, $sresult);

$html_msg = $sresult[1];


// check if email list not empty
if ($funders_users && count($funders_users) ) {


    foreach($funders_users as $fuser){

        $m_subject = "You’ve Received a Campaign Update!";

        $m_html_body = 'Hello ' . $fuser['first_name'] ." ". $fuser['last_name'] . ",<br><br>\r\n\r\n";
        $m_html_body.= "You've received an update from the campaign " . $u_campaign['project_title'] . "<br><br>\r\n\r\n";
        $m_html_body.= $html_msg;
        $m_html_body.= "<br><br>\r\n\r\n";
        $m_html_body.= "Visit the " . $u_campaign['project_title'] ."<br>\r\n";
        $m_html_body.= "Respond directly to the campaign owner <a href='".SITE_PATH."about_me.php?user_id=".$u_user['id']."'>".$u_user['first_name'] ." ".$u_user['last_name']."</a>";


        $m_text_body = 'Hello ' . $fuser['first_name'] . " " . $fuser['last_name'] . ",\r\n\r\n";
        $m_text_body .= "You've received an update from the campaign " . $u_campaign['project_title'] . "\r\n\r\n";
        $m_text_body .= strip_tags($html_msg);
        $m_text_body .= "\r\n\r\n";
        $m_text_body .= "Visit the " . $u_campaign['project_title'] . "\r\n";
        $m_text_body .= "Respond directly to the campaign owner " . SITE_PATH . "about_me.php?user_id=" . $u_user['id'];



        send_mail($fuser['email'],
            $m_subject,
            $m_text_body,
            $setts['admin_email'],
            $m_html_body,
            null,
            $send);

    }


}