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

// clenup all tags to make text message
$text_body = strip_tags($sresult[1]);


$html_body = $sresult[1];


// check if email list not empty
if ($funders_emails && count($funders_emails) ) {


    foreach($funders_emails as $f_email){

        send_mail($f_email,
                $setts['sitename'] . ' - Campaign Update',
            $text_body,
            $setts['admin_email'],
            $html_body,
            null,
            $send);

    }


}