<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<script language="javascript">
	function checkEmail() {
		if (document.registration_form.email_check.value==document.registration_form.email.value) document.registration_form.email_img.style.display="inline";
		else document.registration_form.email_img.style.display="none";
	}

	function checkPass() {
		if (document.registration_form.password.value==document.registration_form.password2.value) document.registration_form.pass_img.style.display="inline";
		else document.registration_form.pass_img.style.display="none";
	}

	function form_submit() {
		document.registration_form.operation.value = '';
		document.registration_form.edit_refresh.value = '1';
		document.registration_form.submit();
	}

	function copy_email_value() {
		document.registration_form.email_check.value = document.registration_form.email.value;
	}

	function copy_password_value() {
		document.registration_form.password2.value = document.registration_form.password.value;
	}

	function check_username(username)
	{
		var xmlHttp;

		if (window.XMLHttpRequest)
		{
			var xmlHttp = new XMLHttpRequest();

			if (XMLHttpRequest.overrideMimeType) 
			{
				xmlHttp.overrideMimeType('text/xml');
			}
		}
		else if (window.ActiveXObject) 
		{
			try 
			{
				var xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch(e) 
			{
				try 
				{
					var xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch(e) {}
			}
		}
		else 
		{
			alert('Your browser does not support XMLHTTP!');
			return false;
		}

		var uname    = username.value;
		var url    = 'check_username.php';
		var action    = url + '?username=' + uname;

		if (uname != '') 
		{
			xmlHttp.onreadystatechange = function() { showResult(xmlHttp, uname); };
			xmlHttp.open("GET", action, true);
			xmlHttp.send(null);
		}
	}

	function showResult(xmlHttp, id)
	{
		if (xmlHttp.readyState == 4)
		{
			var response = xmlHttp.responseText;

			usernameResult.innerHTML = unescape(response);
		}
	}

 function setCookie(c_name,value,exdays) {
   var exdate=new Date();
	exdate.setTime(exdate.getTime() + 1000 * 60 * 60 * 24 * exdays);
    var c_value=escape(value) + "; path=/";
    document.cookie=c_name + "=" + c_value;
  }



  function selectQuickNP(value) {
    alert(value);
    setCookie('quick_np',value,30);
    showUser(this.value);
  }



function showUser(str)
{
if (str=="")
  {
  document.getElementById("txtHint").innerHTML="";
  return;
  }

//  setCookie('np_userid', str, 0);

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","getchoice.php?q="+str,true);
xmlhttp.send();
}






function doHttpRequest() {  // This function does the AJAX request
  http.open("GET", "/ajaxb.html", true);
  http.onreadystatechange = getHttpRes;
  http.send(null);
}

function doHttpRequest2() {  // This function does the AJAX request


http.open("GET", "/ajaxprocessor.php?q="+(document.getElementById('zip_code').value)+"&address="+(document.getElementById('address').value)+"&search_name="+(document.getElementById('search_name').value)+"&city="+(document.getElementById('city').value)+"&distancefrom="+(document.getElementById('distancefrom').value)+"&limitresults="+(document.getElementById('limitresults').value), true);



  http.onreadystatechange = getHttpRes;
  http.send(null);
}

function getHttpRes() {
  if (http.readyState == 4) { 
    res = http.responseText;  // These following lines get the response and update the page
    document.getElementById('div1').innerHTML = res;	
  }
}

function getXHTTP() {
  var xhttp;
   try {   // The following "try" blocks get the XMLHTTP object for various browsers
      xhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        xhttp = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (e2) {
 		// This block handles Mozilla/Firefox browsers...
	   try {
	     xhttp = new XMLHttpRequest();
	   } catch (e3) {
	     xhttp = false;
	   }
      }
    }
  return xhttp; // Return the XMLHTTP object
}

var http = getXHTTP(); // This executes when the page first loads.

	$(document).ready(
		function() {
			$('#tax_company_name').live("change", 
				function() {
//					setCookie('np_userid',$('#tax_company_name').val(),30);

				}
			);	
		}
	);
</script>

<?
function fetchstate($statecode){
$sql="SELECT name FROM probid_countries WHERE id = '".$statecode."'";
$result = mysql_query($sql);
$row = mysql_fetch_array( $result );
$statename=$row['name'];
return $statename;
}
?>
<? SetCookieLive("sales", '1', time()+3600*24*90 , '/', 'bringitlocal.com'); ?>


  


<div class='barTitleNew'>Quick Select a Non-Profit to Support</div>	
<div class="contentBlock">
<!--
	<a href='<?= $refuse_url ?>'><p style="font-family:verdana;font-size:120%;color:black; text-decoration: underline;">No, thanks, send me on.</a></p>
	[not sure what this page does? Watch this <a href="http://www.youtube.com/watch?v=SU44NbYoymI" target="_blank">39 second youtube video</a>]
-->
	<?=$banned_email_output;?>
	<?=$display_formcheck_errors;?>
	<?=$check_voucher_message;?>
	
	<form action="searchnp.php" method="post" name="registration_form">
		<input type="hidden" name="operation" value="submit">
		<input type="hidden" name="do" value="<?=$do;?>">
		<input type="hidden" name="user_id" value="<?=$user_details['user_id'];?>">
		<input type="hidden" name="edit_refresh" value="0">
		<input type="hidden" name="generated_pin" value="<?=$generated_pin;?>">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="border">
			<input name="address" type="hidden" id="address" value="" size="40" />
			<input name="city" type="hidden" id="city" value="" size="25" />
		</table>
		
		<h2>STEP ONE</h2>
		<h3>Enter your Zipcode:  <input name="zip_code" type="text" id="zip_code" value="<?=$user_details['zip_code'];?>" size="15" onchange="doHttpRequest2()"/></h3>






<table border="0" cellspacing="0" cellpadding="0"><tr>
 <td  class="contentfont" colspan="3">
	<img src="themes/<?=$setts['default_theme'];?>/img/pixel.gif" width="1" height="5" /><br>	 
		 <h2>STEP TWO</h2></td>

     	 <input name="state" type="hidden" id="state" value="" size="25" />
         <input name="country" type="hidden" id="country" value="" size="25" />
         	</td>





															

</tr><tr><td valign="top">

<div id="div1">

<h3>Click here to see what non profits have signed up in your area:</h3> 


<?

#is the user coming from a np landing page. if they are we default their np choice to that of the landing page
if (  (landingpage == '1') ||  (isset($_COOKIE["np_userid"])) && (empty($user_details["npname"])) ){


	if (isset($_COOKIE["np_userid"]))

		$mynp_userid=$_COOKIE["np_userid"];

	else

		$mynp_userid = np_userid;

		$mynp = $db->get_sql_field("SELECT tax_company_name  FROM np_users WHERE user_id ='" . $mynp_userid . "'", tax_company_name);
		$mynpaddress = $db->get_sql_field("SELECT address  FROM np_users WHERE user_id ='" . $mynp_userid . "'", address);
		$mynpcity = $db->get_sql_field("SELECT city  FROM np_users WHERE user_id ='" . $mynp_userid . "'", city);
		$mynpstate = $db->get_sql_field("SELECT state  FROM np_users WHERE user_id ='" . $mynp_userid . "'", state);
		$mynpzip = $db->get_sql_field("SELECT zip_code  FROM np_users WHERE user_id ='" . $mynp_userid . "'", zip_code);
#set a new cookie or replace the cookie set by the landing page
//SetCookieLive("np_userid", $mynp_userid,time()+3600*24*90, '/', 'bringitlocal.com');


#set a sales cookie if they have sales so the bar graph shows up in the right column
//do they have sales. if not we dont want to show the chart
#not sure why this is not already included since it has the db connection info and how can everything else be working without it
#but in any case without it the queries fail-it doesnt have values for db_host, db_username or db_password
	include ('includes/config.php');	
	$link = mysql_connect($db_host, $db_username, $db_password);
mysql_select_db($db_name, $link);
$result_sales = mysql_query("SELECT * FROM giveback_invoices WHERE np_userid = '$mynp_userid'", $link);
$is_sales = mysql_num_rows($result_sales);
if ($is_sales <> '0' )
define('sales', 1);
$salesno = sales;
#echo "np_userid ";
#echo $np_userid;
#echo "mynp_userid ";
#echo $mynp_userid;
#set a cookie and define a variable so we know the np when the rest of the homepage loads
#SetCookieLive("sales", $salesno, 0, '/', 'bringitlocal.com'); 


#change state code to state name
$statename=fetchstate($mynpstate);
$mynpstate=$statename;
	
$user_details["npname"] = $mynp;	
$_POST["npaddress"] = $mynpaddress;	
$_POST["npcity"] = $mynpcity;	
$_POST["npstate"] = $mynpstate;	
$_POST["npzipcode"] = $mynpzip;	
$_POST["orgname"] = $mynp_userid;
}
?>

<input type="button" value="Search" onClick="doHttpRequest2();">





<input type="hidden" name="orgname" value="<? echo $_POST["orgname"]; ?>" size="50"/>
<input type="hidden" name="npname"  value="<? echo $_POST["npname"]; ?>" size="50"/>
<input name="distancefrom" type="hidden" id="distancefrom" value="25">
<input name="limitresults" type="hidden" id="limitresults" value="25">
<input name="search_name" type="hidden" id="search_name" value="">


</div>

</td><td></td>

<td><div id="txtHint"><b>You've selected:</b><br>
<input type="text" name="npname"  value="<? echo $user_details["npname"]; ?>" size="50" readonly/><br>
<input type="text" name="npaddress"  value="<? echo $_POST["npaddress"]; ?>" size="50" readonly/><br>

<input type="text" name="npcity"  value="<? echo $_POST["npcity"]; ?>" size="50" readonly/><br>
<input type="text" name="npstate"  value="<? echo $_POST["npstate"]; ?>" size="50" readonly/><br>
<input type="text" name="npzipcode"  value="<? echo $_POST["npzipcode"]; ?>" size="50" readonly/>
<input type="hidden" name="orgname"  value="<? echo $_POST["orgname"]; ?>" size="50" readonly/>
</div></td>


</tr>
<tr class="c1">
<td colspan="3" align="left" class="contentfont"><strong>Don't see your favorite non profit? 
<br>
If you represent and want to register your organization <a href="/np/npregister.php" target="_blank">go here to enroll</a>. <br>
If you want to add an organization that you support <a href="/np/npregister_supporter.php" target="_blank">add them here</a>. 
<br><br>
Then return here to finish your selection.</strong>

</td>

</tr>
</table>




			
				
		<h2>STEP THREE - You're Done!</h2>				
			
		<?
		 $amazon_site = !empty($_GET['shop_url']) ? html_entity_decode($_GET['shop_url']) : "";
		 ##$amazon_site = !empty($_GET['shop_url']) ? $_GET['shop_url'] : "";
		#they got here by clicking on a banner		
		if (!empty($_GET['shop_url']))  { ?>  
		<a href='<?=urlencode($amazon_site)?>'><p style="font-family:verdana;font-size:140%;color:black; text-decoration: underline;">Go shopping >></a>
		<? } 
		if (empty($_GET['shop_url']))  { ?>  
		<a href="/global_partners.php"><p style="font-family:verdana;font-size:140%;color:black; text-decoration: underline;">Go shopping >></a>
		<? } ?>
	</form>
</div>

<div class='barTitle'>BROWSE ALL OUR GLOBAL SHOPPING PARTNERS<span class="viewAll"><a href="/global_partners.php">View All</a></span></div>		
	
