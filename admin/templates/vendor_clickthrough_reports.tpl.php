<?php
/**
 * Created by Lilian Codreanu.
 * User: Lilian Codreanu
 * Date: 11/6/12
 * Time: 7:52 AM
 * To change this template use File | Settings | File Templates.
 */


if ( !defined('INCLUDED') ) { die("Access Denied"); }

$startMonth = $endMonth = date('m');
$startDay = $endDay = date('d');
$startYear = $endYear = date('Y');
if(isset($_POST['startMonth'])) $startMonth = $_POST['startMonth'];
if(isset($_POST['startDay'])) $startDay = $_POST['startDay'];
if(isset($_POST['startYear'])) $startYear = $_POST['startYear'];
if(isset($_POST['endMonth'])) $endMonth = $_POST['endMonth'];
if(isset($_POST['endDay'])) $endDay = $_POST['endDay'];
if(isset($_POST['endYear'])) $endYear = $_POST['endYear'];

?>

<div class="mainhead"><img src="images/user.gif" align="absmiddle">
    <?=$header_section;?>
</div>
<?=$msg_changes_saved;?>
<?=$display_formcheck_errors;?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td width="4"><img src="images/c1.gif" width="4" height="4"></td>
        <td width="100%" class="ftop"><img src="images/pixel.gif" width="1" height="1"></td>
        <td width="4"><img src="images/c2.gif" width="4" height="4"></td>
    </tr>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="3" class="fside">
    <tr class="c3">
        <td colspan="2"><img src="images/subt.gif" align="absmiddle" hspace="4" vspace="2"> <b>
            <?=strtoupper($subpage_title);?>
        </b></td>
    </tr>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="3" class="fside">
    <form method="post" name="clickthrough_export">
        <input type="hidden" name="do" value="export" />
        <input type="hidden" name="operation" value="submit" />
        <tr class="c1">
            <td nowrap>Date From:</td>
            <td width="100%">
                <select name="startMonth">
                    <option value="1" <?php if($startMonth == 1) echo 'selected="selected"';?>>Jan</option>
                    <option value="2" <?php if($startMonth == 2) echo 'selected="selected"';?>>Feb</option>
                    <option value="3" <?php if($startMonth == 3) echo 'selected="selected"';?>>Mar</option>
                    <option value="4" <?php if($startMonth == 4) echo 'selected="selected"';?>>Apr</option>
                    <option value="5" <?php if($startMonth == 5) echo 'selected="selected"';?>>May</option>
                    <option value="6" <?php if($startMonth == 6) echo 'selected="selected"';?>>Jun</option>
                    <option value="7" <?php if($startMonth == 7) echo 'selected="selected"';?>>Jul</option>
                    <option value="8" <?php if($startMonth == 8) echo 'selected="selected"';?>>Aug</option>
                    <option value="9" <?php if($startMonth == 9) echo 'selected="selected"';?>>Sep</option>
                    <option value="10" <?php if($startMonth == 10) echo 'selected="selected"';?>>Oct</option>
                    <option value="11" <?php if($startMonth == 11) echo 'selected="selected"';?>>Nov</option>
                    <option value="12" <?php if($startMonth == 12) echo 'selected="selected"';?>>Dec</option>
                </select>

                <select name="startDay">
                    <?php
                        for($i=1; $i<=31; $i++){
                            echo '<option value="'.$i.'"';
                            if($startDay == $i) echo ' selected="selected" ';
                            echo '>'.$i.'</option>';
                        }
                    ?>
                </select>

                <select name="startYear">
                <?php
                    $year = date('Y');
                    for($i=$year-5; $i<=$year; $i++){
                        echo '<option value="'.$i.'"';
                        if($startYear == $i) echo ' selected="selected" ';
                        echo '>'.$i.'</option>';
                    }
                ?>
                </select>
            </td>
        </tr>
        <tr class="c1">
            <td nowrap>Date To:</td>
            <td width="100%">
                <select name="endMonth">
                    <option value="1" <?php if($endMonth == 1) echo 'selected="selected"';?>>Jan</option>
                    <option value="2" <?php if($endMonth == 2) echo 'selected="selected"';?>>Feb</option>
                    <option value="3" <?php if($endMonth == 3) echo 'selected="selected"';?>>Mar</option>
                    <option value="4" <?php if($endMonth == 4) echo 'selected="selected"';?>>Apr</option>
                    <option value="5" <?php if($endMonth == 5) echo 'selected="selected"';?>>May</option>
                    <option value="6" <?php if($endMonth == 6) echo 'selected="selected"';?>>Jun</option>
                    <option value="7" <?php if($endMonth == 7) echo 'selected="selected"';?>>Jul</option>
                    <option value="8" <?php if($endMonth == 8) echo 'selected="selected"';?>>Aug</option>
                    <option value="9" <?php if($endMonth == 9) echo 'selected="selected"';?>>Sep</option>
                    <option value="10" <?php if($endMonth == 10) echo 'selected="selected"';?>>Oct</option>
                    <option value="11" <?php if($endMonth == 11) echo 'selected="selected"';?>>Nov</option>
                    <option value="12" <?php if($endMonth == 12) echo 'selected="selected"';?>>Dec</option>
                </select>

                <select name="endDay">
                    <?php
                    for($i=1; $i<=31; $i++){
                        echo '<option value="'.$i.'"';
                        if($endDay == $i) echo ' selected="selected" ';
                        echo '>'.$i.'</option>';
                    }
                    ?>
                </select>

                <select name="endYear">
                    <?php
                    $year = date('Y');
                    for($i=$year-5; $i<=$year; $i++){
                        echo '<option value="'.$i.'"';
                        if($endYear == $i) echo ' selected="selected" ';
                        echo '>'.$i.'</option>';
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center" class="c3"><input type="submit" name="form_clickthrough_reports_save" value="Send Report">
            </td>
        </tr>
    </form>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td width="4"><img src="images/c3.gif" width="4" height="4"></td>
        <td width="100%" class="fbottom"><img src="images/pixel.gif" width="1" height="1"></td>
        <td width="4"><img src="images/c4.gif" width="4" height="4"></td>
    </tr>
</table>