<?
    error_reporting(E_ALL);

    #############################################################################################
    #
    #  Import Amazon Report
    #
    ##############################################################################################
    include_once ('../includes/config.php');
    
    
    ## list of users to send email
    $user_arr= array();
    $zero_user_arr=array();
    $tag_exist  = false;
    $consolidate_date="";

 
    ## Database Username
    $username =  $db_username;     
    ## Database Login Password
    $password = $db_password;     
    $database=$db_name;   
    ##  Opens a connection to a MySQL server
    $connection = mysql_connect("localhost", $username, $password);    
    if (!$connection) {
        die("Not connected : " . mysql_error());
    }   
    ## Set the active MySQL database
    $db_selected = mysql_select_db($database, $connection);
    
    if (!$db_selected) 
    {
        die("Can\'t use db : " . mysql_error());
    }    

    $file_arr = scandir_by_mtime("./report");
    
    foreach($file_arr as $filename)
    {        
        if ($filename!='done')
        {
            
            $xml = simplexml_load_file("./report/" . $filename);
            
            console_out("start importing file:<b> ".$filename . "</b>", true);
            
            console_out("Checking date ...", false);
            //input file name format: yyyymmdd.xml
            $consolidate_date = substr($filename,0,4) . "-" . substr($filename,4,2) . "-" . substr($filename,6,2) . " 12:00:00";
            
            if (checkdate(substr($filename,4,2),substr($filename,6,2), substr($filename,0,4)))
            {
                console_out("Consolidated date is ok " . $consolidate_date, false);            
                foreach($xml->children() as $child)
                {            
                    if ("TagDetails"==$child->getName())
                    {
                        console_out("File existed, start importing", false);
                        getOneTag($child);
                        console_out("import - done", false);
                    }
                }
                
                console_out("moving file to backup folder", false);
                ##move file to backup folder
                if (copy("./report/".$filename,"./report/done/".$filename))
                {
                    ##echo "delete old file";
                    unlink("./report/".$filename);
                }
                console_out("moving file to backup folder - done", false);
                console_out("done - imported file:<b>".$filename." </b>successfully", false);                
            }
            else
            {
                console_out("Invalid file name, should be in <b>yyyymmdd.xml</b> format", false);
            }
        }
    }
    
    sendNotificationEmail($user_arr);
    
    console_out("ALL Done - imported successfully.", false);
    console_out("", true);
    
    mysql_close();
    

    function getOneTag($xml)
    {
        global $tag_exist;
        global $consolidate_date;
        
        foreach($xml->children() as $child)
        {
            if ("OneTag"==$child->getName())
            {                
            
                $sql="insert into giveback_invoices({fieldname}) value({value})";
                
                $sql = str_replace("{fieldname}", "invoice_id,{fieldname}", $sql);
                $sql = str_replace("{fieldname}", "date_paid,{fieldname}", $sql);
                ##$sql = str_replace("{fieldname}", "points,{fieldname}", $sql);
                
                $sql = str_replace("{value}", "0,{value}", $sql);
                $today = getdate();                
                $sql = str_replace("{value}", "'". $today ."',{value}", $sql);
                ##$sql = str_replace("{value}", "0,{value}", $sql);                
        
                $sql2="update giveback_invoices SET {SET} WHERE {ID}";
                
                $tracking_id = '';
                $amount = 0;
                $invoice_date="";
                $user_id='';
                
                foreach($child->attributes() as $name => $value)
                {
                   buildTrack($sql, $sql2, $name, $value, $tracking_id, $amount, $user_id, $invoice_date);
                }
                
                $sql = str_replace(",{fieldname}", "", $sql);
                $sql = str_replace(",{value}", "", $sql);
                $sql2 = str_replace(",{SET}", "", $sql2);
                
                ##execute FINAL sql.
                if ($tag_exist==true)
                {
                    if ($sql2!='')
                    {
                        mysql_query($sql2);
                        ##console_out($sql2);
                        console_out( "<b>Tag existed and was changed, updated new amount</b>", false);
                    }                     
                }
                else
                {
                    mysql_query($sql);
                    ##console_out($sql);
                    if ($sql!='')
                    {
                        console_out( "<b>Tag has not existed, insert new record.</b>", false);
                    }
                }
                
                console_out("Imported tracking :<b> ".$tracking_id. "</b>", false);                
                
            }
        }
        return false;
    }

    
   function scandir_by_mtime($folder)
   {
       $folder = realpath($folder).DIRECTORY_SEPARATOR; 
       $dircontent = scandir($folder);
       
        $arr = array();
        foreach($dircontent as $filename) 
        {       

            if ($filename != '.' && $filename != '..') {
            if (filemtime($folder.$filename) === false) return false;
            $dat = date("YmdHis", filemtime($folder.$filename));
            $arr[$dat] = $filename;
            }
        }
       if (!ksort($arr)) return false;
        return $arr;
    }
    
    
    function buildTrack(&$sql, &$sql2,  $name, $value, &$tracking_id, &$amount, &$user_id, &$invoice_date)
    {    
        global $tag_exist;
        global $user_arr;
        global $zero_user_arr;
        global $consolidate_date;        
        
        if ($name=='Tag')
        {               
                ##get any tracks that happen before consolidated date and  within a week.
                $s_condition =   "(click_date < '".$consolidate_date."' and DATEDIFF('" . $consolidate_date ."',click_date)<14)";
                $s1 = "select * from shop_tracking_links where " .$s_condition." and target_url like '%amazon%' and tracking_id='" .  $value . "'order by click_date desc  limit 0,1";
                $sql_select= mysql_query($s1);
                
                while ($row = mysql_fetch_assoc($sql_select))
                {
                    $id = $row["id"];
                    $click_date = $row["click_date"];
                    $invoice_date = $click_date;
                }
                
                ##look up tracking detail.
                $s2 = "select s.*, u.user_id, u.username, np.user_id npuser_id, np.tax_company_name npuser_name
                        from shop_tracking_links s
                        left join probid_users u  ON s.user_id =  u.user_id 
                        left join np_users np  ON s.np_userid =  np.user_id
                        where s.id=". $id;
    
                $sql_select= mysql_query($s2);
                    
                while ($row = mysql_fetch_assoc($sql_select))
                {
                    $user_id = $row["user_id"];   
                    if ($user_id=='')
                        $user_id=0;
                    $user_name = $row["username"];
                    if ($user_name==null)
                        $user_name='siteuser';
                    $npuser_id = $row["npuser_id"];
                    $np_name = $row["npuser_name"];
                    if ($np_name==null)
                        $np_name='Amazon';
                    $tracking_id = $row["tracking_id"];                    
                }
    
                $tag_exist = TagExist($value, $user_id, $click_date);

                if(!$tag_exist AND $user_id){
                    $activity_sql="SELECT points_awarded FROM probid_user_activities WHERE activity_id = 8";
                    $activity_result = mysql_query($activity_sql);
                    $activity_row = mysql_fetch_array($activity_result);
                    $points_awarded = $activity_row['points_awarded'];
                    mysql_query("INSERT INTO probid_user_points (user_id, activity_id, points_awarded, awarded_date)
                                                  VALUES ('$user_id', '8', '$points_awarded', date(now()))");

                    $email_user_id = $user_id;
                    $activity_id = 8;
#                    include('../global_loyality_user_notification.php');
                }
                    
                $sql=str_replace("{fieldname}",  "tracking_id,{fieldname}", $sql);
                $sql=str_replace("{value}",  "'" . $value . "',{value}", $sql);
    
                $sql=str_replace("{fieldname}",  "np_userid,{fieldname}", $sql);
                $sql=str_replace("{value}",  "'" . $npuser_id . "',{value}", $sql);
                $sql=str_replace("{fieldname}",  "np_name,{fieldname}", $sql);
                $sql=str_replace( "{value}",  "'" . $np_name . "',{value}", $sql);            
    
                $sql=str_replace("{fieldname}",  "user_id,{fieldname}", $sql);
                $sql=str_replace("{value}",  "'" . $user_id . "',{value}", $sql);
                $sql=str_replace("{fieldname}",  "username,{fieldname}", $sql);
                $sql=str_replace("{value}",  "'" . $user_name . "',{value}", $sql);

                $sql=str_replace("{fieldname}",  "invoice_date,{fieldname}", $sql);
                $sql=str_replace("{value}",  "'" . $click_date . "',{value}", $sql);
                
                
                $sql2=str_replace("{ID}",  " user_id='" . $user_id . "' and tracking_id='".$value."'", $sql2);
                
                ##add user array to send emails.
                if (!array_key_exists($user_id, $user_arr))
                {
                    $user_arr[$user_id] = $value;
                }
                $tracking_id = $value;                
        }
        
        if ($name=='TotalEarnings')
        {       
            if ($value==0)
            {
                ##ignore zero amount rows
                $sql="";
                $sql2="";                
                ##ban email list
                if (!array_key_exists($user_id.'->'.$tracking_id, $zero_user_arr))
                {
                    $zero_user_arr[$user_id.'->'.$tracking_id] = $tracking_id;
                }
            }
            else
            {
                $sql=str_replace("{fieldname}",  "Commission,{fieldname}", $sql);
                $sql=str_replace("{value}",  $value .",{value}", $sql);

                $sql=str_replace("{fieldname}",  "Paid,{fieldname}", $sql);
                $sql=str_replace("{value}", "0,{value}", $sql);

                $sql=str_replace("{fieldname}",  "points,{fieldname}", $sql);
                $sql=str_replace("{value}",  $value ." / 2.0,{value}", $sql);
                
                ##$sql = str_replace("{fieldname}", "points,{fieldname}", $sql);
                
                
                $sql2=str_replace("{SET}",  "Commission = '" . $value . "',{SET}", $sql2);
                ##$sql2=str_replace("{SET}",  "Paid = " . $value . " / 2.0,{SET}", $sql2);
                $sql2=str_replace("{SET}",  "points = " . $value . " / 2.0,{SET}", $sql2);                
               
            }
        }

        if ($name=='ShippedRevenue')
        {
            if ($value==0)
            {
                ##ignore zero amount rows
                $sql="";
                $sql2="";
                
                ##ban email list
                if (!array_key_exists($user_id.'->'.$tracking_id, $zero_user_arr))
                {
                    $zero_user_arr[$user_id.'->'.$tracking_id] = $tracking_id;
                }                
            }
            else
            {
                $sql = str_replace("{fieldname}",  "Sales,{fieldname}", $sql);
                $sql = str_replace("{value}",  "'" . $value . "',{value}", $sql);
                $sql2 = str_replace("{SET}",  "Sales = '" . $value . "',{SET}", $sql2);
                $amount = $value;
            }
        }
        
        if ($amount!=0 && $tracking_id!=''&& $user_id!='' && $invoice_date!='')
        {
            if (!TagChange($tracking_id,$amount, $user_id, $invoice_date))
            {
                $sql="";
                $sql2="";    
                ##ban email list
                if (!array_key_exists($user_id.'->'.$tracking_id, $zero_user_arr))
                {
                    $zero_user_arr[$user_id.'->'.$tracking_id] = $tracking_id;
                }                
                
            }
        }
       return true;
    }
    
    function console_out($msg, $start)
    {
        if ($start==true)
        {
            echo "<br/>";
            echo "<br/>";
            echo "================================================================================";
        }
        echo "<br/>";
        echo "<br/>";
        
        echo $msg;
        
        return true;
    }

    function sendNotificationEmail()
    {
        global $user_arr;
        global $zero_user_arr;
        
                        
        foreach($user_arr as $mail_input_id => $tracking_id)
        {          
            
            if (!array_key_exists($mail_input_id.'->'.$tracking_id, $zero_user_arr))            
            {
                $sql = "select * from giveback_invoices where tracking_id = '".$tracking_id."' and user_id!=0 and user_id='" .$mail_input_id ."' order by invoice_date desc limit 0,1;";
                $sql_select= mysql_query($sql);
                
                while ($row = mysql_fetch_assoc($sql_select))
                {
                    $gross = $row['Sales'];
                    $points = $row['points'];
                    
                    include ('giveback_invoice_email.php');
                }            
            }
            
        }
        return true;    
    }
    
    function TagExist($tag, $user_id, $click_date)
    {   
    
        if ($user_id=='')
            $user_id=0;
        
        $s = "select * from giveback_invoices where datediff(invoice_date,'".$click_date."')=0 and tracking_id = '".$tag."' and user_id=".$user_id ; 
        
       
        $sql_select= mysql_query($s);
                
        while ($row = mysql_fetch_assoc($sql_select))
        {        
            return true;                
        }
        
        return false;
    }    

    function TagChange($tracking_id, $amount, $user_id, $click_date)
    {

       if ($user_id=='')
         $user_id=0;            
        $s = "select * from giveback_invoices where datediff(invoice_date,'" .$click_date . "')=0 and user_id='" . $user_id . "' and tracking_id = '".$tracking_id."' and Sales=". $amount;
        $sql_select= mysql_query($s);
                
        while ($row = mysql_fetch_assoc($sql_select))
        {        
            return false;                
        }        
        return true;
    }
?>
