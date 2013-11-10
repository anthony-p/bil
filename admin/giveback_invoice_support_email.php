<?php
global $start_date;
global $end_date;
global $csvFile;

if($start_date && $end_date){

    $sql = "select giveback_amazon_invoices.*, bl2_users.first_name, bl2_users.last_name, bl2_users.email, np_users.name as np_name, np_users.tax_company_name from giveback_amazon_invoices LEFT JOIN bl2_users ON bl2_users.id = giveback_amazon_invoices.user_id LEFT JOIN  np_users ON np_users.user_id = giveback_amazon_invoices.np_user_id where (date_time > '".$start_date."' and date_time < '".$end_date."')  ";
    $sql_select= mysql_query($sql);

    $data = array();
    $sent_mail = array();
    $updated = array();

    while ($row = mysql_fetch_assoc($sql_select))
    {
        if($row["email"]){
            $sent_mail[]="Sent email to ".$row["name"]." ({$row["email"]})";
            $updated[] = "[{$row["date_time"]}] updated giveback_invoices for click through...from \"{$row["tax_company_name"]}\" by user {$row["first_name"]} {$row["last_name"]} ({$row["email"]})";
        }else
            $updated[] = "[{$row["date_time"]}] updated giveback_invoices for click through...from \"{$row["tax_company_name"]}\" by Guest";
    }

    $sent_mail = array_unique($sent_mail);

    /*  Send Mail to support*/

    $subject = "Clickthrough report from $start_date to $end_date";
    $html_message = nl2br(implode("\n",$sent_mail)."\n\n\n\n".implode("\n",$updated))."\r\n\r\n";
    

    $headers = 'From: Bring It Local <support@bringitlocal.com>' . PHP_EOL .
    'X-Mailer: PHP-' . phpversion() . PHP_EOL .
    'Content-type: text/html; charset=iso-8859-1' . PHP_EOL;

    $uid = md5(uniqid(time()));
    $header = "From: Bring It Local <support@bringitlocal.com> \r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
    $header .= "This is a multi-part message in MIME format.\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $header .= implode("\n",$sent_mail)."\n\n\n\n".implode("\n",$updated)."\r\n\r\n";
    if($csvFile){
        $header .= "--".$uid."\r\n";
//        $header .= "Content-Type: application/zip; name=\"".$zipFile."\"\r\n"; // use different content types here
        $header .= "Content-Type: application/vnd.ms-excel; name=\"".$csvFile."\"\r\n"; // use different content types here
        $header .= "Content-Transfer-Encoding: base64\r\n";
        $header .= "Content-Disposition: attachment; filename=\"".$csvFile."\"\r\n\r\n";
        $header .= @file_get_contents($dir.$csvFile)."\r\n\r\n";
		$html_message .= 'These are the changes that have been done on your database:\r\n\r\n';
		$html_message .= @file_get_contents($dir.$csvFile)."\r\n\r\n";
    }
    $header .= "--".$uid."--";
//    file_get_contents($dir.$zipFile);
//    var_dump($header);
//    $sEmail = "support@bringitlocal.com";
//    mail($sEmail, $subject, $html_message, $header) ;

//    $sEmail = "markmainsail@gmail.com";
//    mail($sEmail, $subject, $html_message, $header) ;

    $sEmail = "support@bringitlocal.com";
    mail($sEmail, $subject, $html_message, $header) ;

//    $sEmail = "lilian.codreanu@yopeso.com";
//    mail($sEmail, $subject, $html_message, $header) ;
}