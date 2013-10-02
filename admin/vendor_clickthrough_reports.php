<?php
/**
 * Created by Lilian Codreanu.
 * User: Lilian Codreanu
 * Date: 11/6/12
 * Time: 7:48 AM
 * To change this template use File | Settings | File Templates.
 */

session_start();

define ('IN_ADMIN', 1);

include_once ('../includes/global.php');
include_once ('../includes/class_formchecker.php');

if ($session->value('adminarea')!='Active')
{
    header_redirect('login.php');
}
else{
    include_once ('header.php');

    (string) $management_box = NULL;

    $msg_changes_saved = '<p align="center" class="contentfont">Report was Sent to mail</p>';

    $form_submitted = false;

    if ($_REQUEST['do'] == 'export')
    {
        $tag_exist  = false;

        //Get date range from POST
        $start_date = $_POST['startYear']."-".$_POST['startMonth']."-".$_POST['startDay']." 00:00:00";
        $end_date = $_POST['endYear']."-".$_POST['endMonth']."-".$_POST['endDay']." 23:59:59";

        //Set date range manualy
        //$start_date = "2012-06-30 00:00:00";
        //$end_date = "2012-09-16 23:59:59";

        $sql = "select giveback_amazon_invoices.*, probid_users.name, probid_users.email, np_users.name as np_name, np_users.tax_company_name from giveback_amazon_invoices LEFT JOIN probid_users ON probid_users.user_id = giveback_amazon_invoices.user_id LEFT JOIN  np_users ON np_users.user_id = giveback_amazon_invoices.np_user_id where (date_time > '".$start_date."' and date_time < '".$end_date."')  ";
        $sql_select= $db->query($sql);

        $data = array();
        $sent_mail = array();
        $updated = array();

        while ($row = mysql_fetch_assoc($sql_select))
        {
            if($row["email"]){
                $sent_mail[]="Sent email to ".$row["name"]." ({$row["email"]})";
                $updated[] = "[{$row["date_time"]}] updated giveback_invoices for click through...from \"{$row["tax_company_name"]}\" by user {$row["name"]} ({$row["email"]})";
            }else
                $updated[] = "[{$row["date_time"]}] updated giveback_invoices for click through...from \"{$row["tax_company_name"]}\" by Guest";

            $all_data[] = $row;
            $data[$row['np_user_id']][] = $row;
        }
        $sent_mail = array_unique($sent_mail);

        /*  Send Mail to support*/

        $subject = "Clickthrough report from $start_date to $end_date";
        $html_message = nl2br(implode("\n",$sent_mail)."\n\n\n\n".implode("\n",$updated));
        $headers = 'From: Bring It Local <support@bringitlocal.com>' . PHP_EOL .
            'X-Mailer: PHP-' . phpversion() . PHP_EOL .
            'Content-type: text/html; charset=iso-8859-1' . PHP_EOL;

        $sEmail = "support@bringitlocal.com";
//        $sEmail = "lilian.codreanu@gmail.com";
        mail($sEmail, $subject, $html_message, $headers) ;

        $template->set('msg_changes_saved', $msg_changes_saved);
    }

    $template->set('header_section', 'Click Through Reports');
    $template->set('subpage_title', 'GiveBack  Invoice Reports');

    $template_output .= $template->process('vendor_clickthrough_reports.tpl.php');

    include_once ('footer.php');

    echo $template_output;
}