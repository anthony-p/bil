<?php
/**
 * Created by Lilian Codreanu.
 * User: Lilian Codreanu
 * Date: 2/2/13
 * Time: 11:51 PM
 */
?>
<?php include_once "phprptinc/ewrcfg6.php" ?>
<?php include_once "phprptinc/ewmysql.php" ?>
<?php include_once "phprptinc/ewrfn6.php" ?>
<?php include_once "phprptinc/ewrusrfn.php" ?>
<?php include_once "bringitClientClasses.php" ?>

<?php
if (!isset($Clickthrough_details_report_summary)) $Clickthrough_details_report_summary = new crClickthrough_details_report_summary();
if (isset($Page)) $OldPage = $Page;
$Page = &$Clickthrough_details_report_summary;
$Page->Page_Init();
$Page->Page_Main();


$current_day = mktime(0, 0, 0, date("m")  , date("d"), date("Y")) - 86400;
$time_passed = (date('N')-1)* 24 * 3600;
$current_week = mktime(0,0,0,date('m'),date('d'),date('Y')) - $time_passed;
$current_month = mktime(0, 0, 0, date("m"), 1, date("Y"));

$lastUpdates = $conn->Execute("SELECT * FROM `np_reports_last_updates` WHERE (type='daily' AND time<>$current_day) OR (type='weekly' AND time<>$current_week) OR (type='monthly' AND time <>$current_month)");
$lastUpdates = $lastUpdates->GetArray();

for($lUi=0, $maxLUi = sizeof($lastUpdates); $lUi< $maxLUi; $lUi++){

    if( $lastUpdates[$lUi]['type'] == "daily" ){
        $currentUnixTime = $current_day;
        $sWhereWrk = "`vendor_click_reports`.`np-id` = `np_users`.`user_id` AND `np_users`.`report_daily`=1";
        //UPDATE `bringit_auction`.`np_reports_last_updates` SET `time`=1 WHERE `id`='1';
    }elseif( $lastUpdates[$lUi]['type'] == "weekly" ){
        continue;
        $currentUnixTime = $current_week;
        $sWhereWrk = "`vendor_click_reports`.`np-id` = `np_users`.`user_id` AND `np_users`.`report_weekly`=1";
    }elseif( $lastUpdates[$lUi]['type'] == "monthly" ){
        continue;
        $currentUnixTime = $current_month;
        $sWhereWrk = "`vendor_click_reports`.`np-id` = `np_users`.`user_id` AND `np_users`.`report_monthly`=1";
    }else
        continue;

//    $conn->Execute("UPDATE `np_reports_last_updates` SET `time`='$currentUnixTime' WHERE `id`='{$lastUpdates[$lUi]["id"]}'");
    if( $lastUpdates[$lUi]['type'] == "daily" ){
        $lastUnixTime = $current_month;
        $dailyFilter_from_date =  date("Y-m-d",$currentUnixTime-86400);
        $dailyFilter_to_date = date("Y-m-d",$currentUnixTime);
    }
    else
        $lastUnixTime = $lastUpdates[$lUi]['time'];
//    $currentUnixTime = time();
//    $lastUnixTime = $currentUnixTime-60480000; // 7 days ago.


    $Filter_from_date =  date("Y-m-d",$lastUnixTime);
    $Filter_to_date = date("Y-m-d",$currentUnixTime);

    $Page->Filter = "`click date` BETWEEN '$Filter_from_date' AND '$Filter_to_date'";
    $Page->Export = "email";

//    $rNpUsers = $conn->Execute('SELECT DISTINCT `np-name`, `np-name` AS `DispFld` FROM `vendor_click_reports` ORDER BY `np-name`');
    $rNpUsers = $conn->Execute("SELECT DISTINCT `vendor_click_reports`.`np-name`, `vendor_click_reports`.`np-name` AS `DispFld`,
                        `np_users`.`email`, `np_users`.`report_email`, `np_users`.`wkey`
                        FROM `vendor_click_reports`, `np_users`
                        WHERE $sWhereWrk GROUP BY `np-name` ORDER BY `np-name`");
    $rNpUsers = $rNpUsers->GetArray();

    $sSqlWrk = "SELECT DISTINCT `np-name`, `np-name` AS `DispFld` FROM `vendor_click_reports`";
//    $sWhereWrk = "";
    if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
        $sSqlWrk .= " ORDER BY `np-name`";

    for($npi = 0, $maxNpi = sizeof($rNpUsers); $npi< $maxNpi; $npi++){
        if(trim($rNpUsers[$npi]["np-name"]) == "")
            continue;

        if( $lastUpdates[$lUi]['type'] == "daily" ){
//            $dailyResult = "SELECT SUM(Sales) as sale, SUM(`np-share`)as giveback FROM `vendor_click_reports` WHERE (`last_update` BETWEEN '$dailyFilter_from_date' AND '$dailyFilter_to_date') AND (`np-name` = '{$rNpUsers[$npi]["np-name"]}')";
            $dailyResult = "SELECT SUM(Sales) as sale, SUM(`np-share`)as giveback FROM `vendor_click_reports` WHERE (`np-name` = '{$rNpUsers[$npi]["np-name"]}')";
            $dailyResult = $conn->Execute($dailyResult);
            $dailyResult = $dailyResult->GetArray();
            $dailyResult = $dailyResult[0];
        }

        $Page = new crClickthrough_details_report_summary();
        $Page->Page_Init();
        $Page->Page_Main();


        $PageContent = file_get_contents("bringitClientReportMailTemplate.html");
        $PageContent = str_replace("{#Organization#}",$rNpUsers[$npi]["np-name"],$PageContent);
        $PageContent = str_replace("{NP-WKEY}",$rNpUsers[$npi]["wkey"],$PageContent);
        $PageContent = str_replace("{#DATEFROM#}",date("Y/m/d",$lastUnixTime),$PageContent);
        $PageContent = str_replace("{#DATETO#}",date("Y/m/d",$currentUnixTime),$PageContent);

        $PageContent = str_replace("{#SALES#}",($dailyResult["sale"]==null)?"0":$dailyResult["sale"],$PageContent);
        $PageContent = str_replace(" {#GIVEBACK#}",($dailyResult["giveback"] == null)?"0":$dailyResult["giveback"],$PageContent);

        $rowsContent = "";
        $Page->ResetPager();
//        $Page->Filter = "(`click date` BETWEEN '$Filter_from_date' AND '$Filter_to_date') AND (`np-name` = '{$rNpUsers[$npi]["np-name"]}')";
        $Page->Filter = "(`last_update` BETWEEN '$Filter_from_date' AND '$Filter_to_date') AND (`np-name` = '{$rNpUsers[$npi]["np-name"]}')";
        $Page->Export = "email";

        // Set the last group to display if not export all
        if ($Page->ExportAll && $Page->Export <> "") {
            $Page->StopGrp = $Page->TotalGrps;
        } else {
            $Page->StopGrp = $Page->StartGrp + $Page->DisplayGrps - 1;
        }

        // Stop group <= total number of groups
        if (intval($Page->StopGrp) > intval($Page->TotalGrps))
            $Page->StopGrp = $Page->TotalGrps;
        $Page->RecCount = 0;

        // Get first row
        if ($Page->TotalGrps > 0) {
            $Page->GetGrpRow(1);
            $Page->GrpCount = 1;
        }
            $Page->ShowHeader = false;

            $rsgrp->MoveFirst();
            // Build detail SQL
            $sWhere = ewr_DetailFilterSQL($Page->np2Dname, $Page->SqlFirstGroupField(), $Page->np2Dname->GroupValue());
            $sWhere="(`np-name` = '{$rNpUsers[$npi]["np-name"]}')";
            $Page->PageFirstGroupFilter .= $sWhere;

            $Page->GetGrpRow(1);
            $Page->GetRow(1);

//            $sSql = ewr_BuildReportSql($Page->SqlSelect(), $Page->SqlWhere(), $Page->SqlGroupBy(), $Page->SqlHaving(), $Page->SqlOrderBy(), $sWhere, $Page->Sort);
            $sSql = ewr_BuildReportSql($Page->SqlSelect(), $Page->SqlWhere(), $Page->SqlGroupBy(), $Page->SqlHaving(), $Page->SqlOrderBy(), $Page->Filter, $Page->Sort);
            $rs = $conn->Execute($sSql);
            $rsdtlcnt = ($rs) ? $rs->RecordCount() : 0;
            if ($rsdtlcnt > 0)
                $Page->GetRow(1);

            while ($rs && !$rs->EOF) { // Loop detail records
                $Page->RecCount++;

                // Render detail row
                $Page->ResetAttrs();
                $Page->RowType = EWR_ROWTYPE_DETAIL;
                $Page->RenderRow();

                $rowsContent .='<tr'.$Page->RowAttributes().'>';
                if ($Page->np2Dname->Visible){
                    $rowsContent .='<td data-field="np2Dname"'.$Page->np2Dname->CellAttributes() .'>';
                    $rowsContent .='<span'. $Page->np2Dname->ViewAttributes().'>'. $Page->np2Dname->GroupViewValue .'</span></td>';
                }
                if ($Page->click_date->Visible){
                    $rowsContent .='<td data-field="click_date" '. $Page->click_date->CellAttributes().'>';
                    $rowsContent .='<span'. $Page->click_date->ViewAttributes().'>'. $Page->click_date->GroupViewValue .'</span></td>';
                }
                if ($Page->tracking_link->Visible){
                    $rowsContent .='<td data-field="tracking_link"'. $Page->tracking_link->CellAttributes() .'>';
                    $rowsContent .='<span'. $Page->tracking_link->ViewAttributes() .'>'. $Page->tracking_link->ListViewValue() .'</span></td>';
                }
                if ($Page->user_name->Visible){
                    $rowsContent .='<td data-field="user_name"'. $Page->user_name->CellAttributes() .'>';
                    $rowsContent .='<span'.$Page->user_name->ViewAttributes() .'>'. $Page->user_name->ListViewValue() .'</span></td>';
                }
                if ($Page->Sales->Visible){
                    $rowsContent .='<td data-field="Sales" '. $Page->Sales->CellAttributes() .'>';
                    $rowsContent .='<span'.$Page->Sales->ViewAttributes() .'>'. $Page->Sales->ListViewValue() .'</span></td>';
                }
                if ($Page->pct_giveback->Visible){
                    $rowsContent .='<td data-field="pct_giveback" '. $Page->pct_giveback->CellAttributes() .'>';
                    $rowsContent .='<span'. $Page->pct_giveback->ViewAttributes() .'>'. $Page->pct_giveback->ListViewValue() .'</span></td>';
                }
                if ($Page->np2Dshare->Visible){
                    $rowsContent .='<td data-field="np2Dshare"' . $Page->np2Dshare->CellAttributes() .'>';
                    $rowsContent .='<span' . $Page->np2Dshare->ViewAttributes() .'>'. $Page->np2Dshare->ListViewValue() .'</span></td>';
                }
                $rowsContent .='</tr>';
                // Accumulate page summary
                $Page->AccumulateSummary();

                // Get next record
                $Page->GetRow(2);

                // Show Footers
            } // End detail records loop

            $Page->GetGrpRow(2);

            // Show header if page break
            if ($Page->Export <> "")
                $Page->ShowHeader = ($Page->ExportPageBreakCount == 0) ? FALSE : ($Page->GrpCount % $Page->ExportPageBreakCount == 0);

            // Page_Breaking server event
            if ($Page->ShowHeader)
                $Page->Page_Breaking($Page->ShowHeader, $Page->PageBreakContent);
            $Page->GrpCount++;

            // Handle EOF
            if (!$rsgrp || $rsgrp->EOF)
                $Page->ShowHeader = FALSE;

        $PageContent = str_replace("{#TBODY#}",$rowsContent,$PageContent);

        $rowsContent = "";
        $Page->ResetAttrs();
        $Page->RowType = EWR_ROWTYPE_TOTAL;
        $Page->RowTotalType = EWR_ROWTOTAL_GRAND;
        $Page->RowTotalSubType = EWR_ROWTOTAL_FOOTER;
        $Page->RowAttrs["class"] = "ewRptGrandSummary";
        $Page->RenderRow();

            $rowsContent .='<tr'. $Page->RowAttributes().'><td colspan="'. ($Page->GrpFldCount + $Page->DtlFldCount) .'">'. $ReportLanguage->Phrase("RptGrandTotal") .'('.  ewr_FormatNumber($Page->TotCount,0,-2,-2,-2).' '. $ReportLanguage->Phrase("RptDtlRec") .')</td></tr>';

        $Page->ResetAttrs();
        $Page->Sales->Count = $Page->GrandCnt[3];
        $Page->Sales->SumValue = $Page->GrandSmry[3]; // Load SUM
        $Page->RowTotalSubType = EWR_ROWTOTAL_SUM;
        $Page->np2Dshare->Count = $Page->GrandCnt[5];
        $Page->np2Dshare->SumValue = $Page->GrandSmry[5]; // Load SUM
        $Page->RowTotalSubType = EWR_ROWTOTAL_SUM;
        $Page->RowAttrs["class"] = "ewRptGrandSummary";
        $Page->RenderRow();
            $rowsContent .='<tr'. $Page->RowAttributes() .'>';
        if ($Page->GrpFldCount > 0)
        {
            $rowsContent .='<td colspan="'.$Page->GrpFldCount.'" class="ewRptGrpAggregate">'. $ReportLanguage->Phrase("RptSum") .'</td>';
        }
        if ($Page->tracking_link->Visible)
        {
            $rowsContent .='<td data-field="tracking_link" '.$Page->tracking_link->CellAttributes() .'>&nbsp;</td>';
        }
        if ($Page->user_name->Visible)
        {
            $rowsContent .='<td data-field="user_name" '. $Page->user_name->CellAttributes() .'>&nbsp;</td>';
        }
        if ($Page->Sales->Visible)
        {
            $rowsContent .='<td data-field="Sales" '. $Page->Sales->CellAttributes() .'>';
            if($Page->Sales->SumViewValue == null)
                $sum = 0;
            else
                $sum = $Page->Sales->SumViewValue;
            $rowsContent .='<span  '. $Page->Sales->ViewAttributes() .'>'. $sum .'</span></td>';
        }
        if ($Page->pct_giveback->Visible)
        {
            $rowsContent .='<td data-field="pct_giveback" '. $Page->pct_giveback->CellAttributes() .'>&nbsp;</td>';
        }
        if ($Page->np2Dshare->Visible)
        {
            $rowsContent .='<td data-field="np2Dshare" '.$Page->np2Dshare->CellAttributes() .'>';
            if ($Page->np2Dshare->SumViewValue == null)
                $gbSum = 0;
            else
                $gbSum = $Page->np2Dshare->SumViewValue;
            $rowsContent .='<span '.$Page->np2Dshare->ViewAttributes() .'>'. $gbSum .'</span></td>';
        }

        $rowsContent .='</tr>';

        $PageContent = str_replace("{#TFOOT#}",$rowsContent,$PageContent);
        $email = $rNpUsers[$npi]["email"];
        echo($PageContent);
        $recipientMail = $rNpUsers[$npi]["report_email"];
        if(trim($recipientMail) == "" || $recipientMail == null)
            $recipientMail = $rNpUsers[$npi]["email"];
        continue;
        $recipientMail = "lilian.codreanu@gmail.com";
        
//            $params["sender"] = "support@bringitlocal.com";
            $params["sender"] = "lilian.codreanu@gmail.com";
            $params["recipient"] = $recipientMail;
            $params["bcc"] = "1pro.cli@gmail.com";
//            $params["recipient"] = "support@bringitlocal.com";
            $params["subject"] = "Your Bring It Local activity report";
            $params["message"] = "See how your community members have been supporting you on Bring It Local";
            $Page->ExportEmail($PageContent, $params);
        exit;
        }
}
?>