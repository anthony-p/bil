<?php
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

    $msg_changes_saved = '<p align="center" class="contentfont">Files Exported</p>';

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

        $sql = "select * from giveback_amazon_invoices where (date_time > '".$start_date."' and date_time < '".$end_date."')";
        $sql_select= $db->query($sql);

        $data = array();

        while ($row = mysql_fetch_assoc($sql_select))
        {
            $all_data[] = $row;
            $data[$row['np_user_id']][] = $row;
        }

        $all_filename = "np_reports/all-non-profits ".$_POST['startYear'].$_POST['startMonth'].$_POST['startDay']."-".$_POST['endYear'].$_POST['endMonth'].$_POST['endDay'].".csv";

        if($all_filename){
            $output = fopen($all_filename, 'w+');

            // output the column headings
            fputcsv($output, array('unique_id', 'tracking_id', 'user_id', 'shop_url_id', 'date_time', 'destination', 'np_user_id', 'sales', 'commision'));
            $sales = 0;
            $commision = 0;
            foreach($all_data as $value){
                fputcsv($output, $value);
                $sales += $value['sales'];
                $commision += $value['commision'];
            }
            fputcsv($output, array('', '', '', '', '', '', '', $sales, $commision));
        }

        foreach($data as $key => $values){
            $sql = "select username from np_users where user_id=".$key;
            $sql_select= $db->query($sql);
            while ($row = mysql_fetch_assoc($sql_select))
            {
                $filename = 'np_reports/'.$row['username']." ".$_POST['startYear'].$_POST['startMonth'].$_POST['startDay']."-".$_POST['endYear'].$_POST['endMonth'].$_POST['endDay'].".csv";
            }

            // output headers so that the file is downloaded rather than displayed
            //header('Content-Type: text/csv; charset=utf-8');
            //header('Content-Disposition: attachment; filename='.$filename);
            //echo $filename;
            // create a file pointer connected to the output stream
            if($filename){
                $output = fopen($filename, 'w+');

                // output the column headings
                fputcsv($output, array('unique_id', 'tracking_id', 'user_id', 'shop_url_id', 'date_time', 'destination', 'np_user_id', 'sales', 'commision'));
                $sales = 0;
                $commision = 0;
                foreach($values as $value){
                    fputcsv($output, $value);
                    $sales += $value['sales'];
                    $commision += $value['commision'];
                }
                fputcsv($output, array('', '', '', '', '', '', '', $sales, $commision));
            }
        }
        $template->set('msg_changes_saved', $msg_changes_saved);
    }

    $template->set('header_section', 'Amazon Reports');
    $template->set('subpage_title', 'Amazon Reports Export');

    $template_output .= $template->process('amazon_np_users_export.tpl.php');

    include_once ('footer.php');

    echo $template_output;
}