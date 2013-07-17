<?
# this file takes the registering users choice for non profit and stores it in form input variables
# so it stays available when the form is submitted or reloaded and can then be
#inserted in the datasbe when the user does finally submit their registration form

include_once ('includes/config.php');
function SetCookieLive($name, $value='', $expire=0, $path = '', $domain='')
    {
        $_COOKIE[$name] = $value;
        return setcookie($name, $value, $expire, $path, $domain);
    }

    /* Database Username */
    $username =  $db_username;

    /* Database Login Password */
    $password = $db_password;

    $database=$db_name;
    // Opens a connection to a MySQL server
    $connection = mysql_connect("localhost", $username, $password);
    if (!$connection) {
      die("Not connected : " . mysql_error());
    }

    // Set the active MySQL database
    $db_selected = mysql_select_db($database, $connection);
    if (!$db_selected) {
      die("Can\'t use db : " . mysql_error());
    }

    $q=$_GET["q"];
    $sql="SELECT * FROM np_users WHERE user_id = '".$q."'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array( $result );

    #can we set a cookie here so it works on the searchnp.tpl.php page?
    $mynp_userid = $row['user_id'];
    SetCookieLive("np_userid", $mynp_userid,time()+3600*24*90, '/', 'bringitlocal.com');

//start buffering the output to be send after header
ob_start();
    echo "<b>You've selected:</b><br>";
    $statename=fetchstate($row['state']);

    // Print out the contents of each row into a table
    echo  "<td><strong>" . $row['tax_company_name'] . "</strong>";
    echo  "<br>" . $row['address'];
    echo  "<br>" . $row['city'];
    echo  "<br>" . $statename;

    $_POST['npname']=$row['tax_company_name'];
    $_POST['npaddress']=$row['address'];
    $_POST['npcity']=$row['city'];
    $_POST['npstate']= $statename;
    $_POST['npzipcode']=$row['zip_code'];
    $_POST['orgname']=$row['user_id'];

    echo "<input type='hidden' name='npname' value = '";
    echo $_POST['npname'];
    echo "'>";

    echo "<input type='hidden' name='npaddress' value = '";
    echo $_POST['npaddress'];
    echo "'>";

    echo "<input type='hidden' name='npcity' value = '";
    echo $_POST['npcity'];
    echo "'>";

    echo "<input type='hidden' name='npstate' value = '";
    echo $_POST['npstate'];
    echo "'>";

    echo "<input type='hidden' name='npzipcode' value = '";
    echo $_POST['npzipcode'];
    echo "'>";

    echo "<input type='hidden' name='orgname' value = '";
    echo $_POST['orgname'];
    echo "'>";

    #end debug
    echo  "<br>" . $row['zip_code']. "</td>";

    #convert statecode to full statename for non profit selection
    function fetchstate($statecode){
        $sql="SELECT name FROM probid_countries WHERE id = '".$statecode."'";
        $result = mysql_query($sql);
        $row = mysql_fetch_array( $result );
        $statename=$row['name'];
    return $statename;
}

mysql_close($connection);
//send the contents of the output buffer
ob_flush();
?> 
