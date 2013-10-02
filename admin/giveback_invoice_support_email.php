<?php
/**
 * Created by Lilian Codreanu.
 * User: Lilian Codreanu
 * Date: 11/7/12
 * Time: 10:19 PM
 * To change this template use File | Settings | File Templates.
 */

if($start_date && $end_date){

    $sql = "select giveback_amazon_invoices.*, probid_users.name, probid_users.email, np_users.name as np_name, np_users.tax_company_name from giveback_amazon_invoices LEFT JOIN probid_users ON probid_users.user_id = giveback_amazon_invoices.user_id LEFT JOIN  np_users ON np_users.user_id = giveback_amazon_invoices.np_user_id where (date_time > '".$start_date."' and date_time < '".$end_date."')  ";
    $sql_select= mysql_query($sql);

    $data = array();
    $sent_mail = array();
    $updated = array();

    /* Name to Csv File*/
    $dir = "/tmp/";
    $csvFile =  str_replace("-","",substr($start_date,0,10))."-".str_replace("-","",substr($end_date,0,10)).".csv";

    $fields = array();
                        $fields[]="test";//unique id
                        $fields[]="test";//tracking link
                        $fields[]="test";//site user
                        $fields[]="test";//user name
                        $fields[]="test";//click date
                        $fields[]="test";//vendor
                        $fields[]="test";//np-id
                        $fields[]="test";//np-name
                        $fields[]="test";//Sales
                        $fields[]="test";//Commission
                        $fields[]="test";//pct
                        $fields[]="test";//pct giveback
                        $fields[]="test";//np-share
                        $fields[]="test";//bil-share

                        $fp = fopen($dir.$csvFile, 'a+');
                        fputcsv($fp,$fields);


//    $zipFile =  str_replace("-","",substr($start_date,0,10))."-".str_replace("-","",substr($end_date,0,10)).".zip";
//
//    $zip = new ZipArchive();
//    if ($zip->open($dir.$zipFile, ZIPARCHIVE::CREATE)!==TRUE)
//    {
//        echo("cannot open <$dir.$zipFile>\n");
//    }
//    $zip->addFile($csvFile);
//    $zip->close();

    while ($row = mysql_fetch_assoc($sql_select))
    {
        if($row["email"]){
            $sent_mail[]="Sent email to ".$row["name"]." ({$row["email"]})";
            $updated[] = "[{$row["date_time"]}] updated giveback_invoices for click through...from \"{$row["tax_company_name"]}\" by user {$row["name"]} ({$row["email"]})";
        }else
            $updated[] = "[{$row["date_time"]}] updated giveback_invoices for click through...from \"{$row["tax_company_name"]}\" by Guest";
    }

    $sent_mail = array_unique($sent_mail);

    /*  Send Mail to support*/

    $subject = "Clickthrough report from $start_date to $end_date";
    $html_message = nl2br(implode("\n",$sent_mail)."\n\n\n\n".implode("\n",$updated));
    $html_message = "application/vnd.ms-excel";

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
    }
    $header .= "--".$uid."--";
//    file_get_contents($dir.$zipFile);
//    var_dump($header);
//    $sEmail = "support@bringitlocal.com";
//    mail($sEmail, $subject, $html_message, $header) ;

//    $sEmail = "markmainsail@gmail.com";
//    mail($sEmail, $subject, $html_message, $header) ;

    $sEmail = "lilian.codreanu@gmail.com";
    mail($sEmail, $subject, $html_message, $header) ;

//    $sEmail = "lilian.codreanu@yopeso.com";
//    mail($sEmail, $subject, $html_message, $header) ;
}