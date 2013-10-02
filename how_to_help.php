<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Quick Select a Non-Profit to Support | Bring It Local</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="description" content="non-profit fundraising - buy sell and swap online and support your local community and favorite non-profit">
<meta name="keywords" content="non-profit community fundraising "><script type="text/javascript">
	
		var url_address = window.location.href;
		if (url_address.indexOf('#') == -1) 
		{
			window.location.href = url_address + '#' + '158e5acaff05';
		}	
	</script><link rel="shortcut icon" href="http://www.bringitlocal.com/images/favicon.ico" />
<link href="themes/bring_it_local/style.css" rel="stylesheet" type="text/css">
<style type="text/css"> 
<!--
.lb {
	background-image:  url(themes/bring_it_local/img/lb_bg.gif);
}
.db {
	background-image:  url(themes/bring_it_local/img/db_bg.gif);
}
-->
</style>
<script language="javascript" src="themes/bring_it_local/main.js" type="text/javascript"></script>
<script language=JavaScript src='scripts/innovaeditor.js'></script>
<script type="text/javascript"> 
var currenttime = 'August 09, 2011 15:34:01';
var serverdate=new Date(currenttime);
 
function padlength(what){
	var output=(what.toString().length==1)? "0"+what : what;
	return output;
}
 
function displaytime(){
	serverdate.setSeconds(serverdate.getSeconds()+1)
	var timestring=padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds());
	document.getElementById("servertime").innerHTML=timestring;
}
 
window.onload=function(){
	// setInterval("displaytime()", 1000);
}
 
 
 
 
 
 
</script>

<script language=JavaScript src='/scripts/jquery/jquery-1.3.2.js'></script>
<script language=JavaScript src='/scripts/vendor.js'></script>
 
</head>
<body>

<!-- end header -->

<!-- 
 
Aug. 09, 2011<span id="servertime"></span>
BROWSE<form name="cat_browse_form" method="get" action="categories.php">
	 <select name="parent_id" id="parent_id" class="contentfont" onChange="javascript:cat_browse_form.submit()"> <option value="" selected>Choose a Category</option><option value="215" >Antiques &amp; Art</option> <option value="263" >Automobiles &amp; Bikes</option> <option value="355" >Books</option> <option value="887" >Businesses For Sale</option> <option value="474" >Clothing &amp; Accessories</option> <option value="634" >Coins</option> <option value="669" >Collectables</option> <option value="877" >Computing</option> <option value="1117" >Dolls &amp; Dolls Houses</option> <option value="1040" >Electronics</option> <option value="1777" >Everything Else</option> <option value="57" >Gaming</option> <option value="1868" >Home and Garden</option> <option value="1211" >Jewelry &amp; Watches</option> <option value="1243" >Music</option> <option value="1866" >Pet Supplies</option> <option value="1311" >Photography</option> <option value="1351" >Pottery &amp; Glass</option> <option value="890" >Properties</option> <option value="878" >Services</option> <option value="1404" >Sports</option> <option value="1554" >Stamps</option> <option value="1588" >Tickets &amp; Travel</option> <option value="1628" >Toys &amp; Games</option> <option value="1139" >TV &amp; Movies</option> <option value="1723" >Wholesale Items</option> <option value="">------------------------</option> <option value="0">All Categories</option></select></form>
 
 -->
            

	
	<div id="facebookColumn">
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
 
  setCookie('np_userid', str, 0);
 
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
					setCookie('np_userid',$('#tax_company_name').val(),30);	
 
				}
			);	
		}
	);
</script>
 
 <div class='barTitle'>1: Build the community <br> LIKE this page  |   Tell friends         </div>	
 <p style="font-family:verdana;font-size:150%;color:black; text-decoration: underline;"><a href="recommend.html">Select friends to let them know</a></p>
 <iframe src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fbringitlocal&amp;width=450&amp;colorscheme=light&amp;show_faces=true&amp;border_color&amp;stream=false&amp;header=true&amp;height=362" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:362px;" allowTransparency="true"></iframe>
 <!--
 
 <div class='barTitle'>2: Recommend it to your friends</div>
 <a href="recommend.html"><p style="font-family:verdana;font-size:120%;color:black; text-decoration: underline;">Click here</a> to invite 
specific friends <br>to support the Bring It Local community <a href="test.html">...</a></p>
 -->
  <div class='barTitle'>2: Support our growing list of non-profits</div>	
 <p style="font-family:verdana;font-size:100%;color:black; text-decoration: underline;"> <a href="http://www.bringitlocal.com/kpfa" target="_blank">KPFA, Berkeley CA</a>
  </p>
 <p style="font-family:verdana;font-size:100%;color:black; text-decoration: underline;"> <a href="http://www.bringitlocal.com/sgvcc" target="_blank">San Geronimo Valley Community Center, San Geronimo, CA</a>
  </p>
  <p style="font-family:verdana;font-size:100%;color:black; text-decoration: underline;"> <a href="http://www.bringitlocal.com/destinyarts" target="_blank">Destiny Arts, Oakland, CA</a> 
 </p> 
 
 <p style="font-family:verdana;font-size:100%;color:black; text-decoration: underline;"> <a href="http://www.bringitlocal.com/westportalschool" target="_blank">West Portal Elementary School, San Francisco, CA</a> 
 </p> 
  <p style="font-family:verdana;font-size:100%;color:black; text-decoration: underline;"> <a href="http://www.bringitlocal.com/whistlestop" target="_blank">Whistlestop, San Rafael, CA</a> 
 </p> 
 <p style="font-family:verdana;font-size:100%;color:black; text-decoration: underline;"> <a href="http://www.bringitlocal.com/kpft" target="_blank">KPFT, Houston, TX</a> 
 </p> 
  <div class='barTitle'>3: Recommend other non-profits - form your own communities</div>	

 <p style="font-family:verdana;font-size:120%;color:black; "> <a href="mailto:info@bringitlocal.com">Send us a note</a> or <a href="http://www.bringitlocal.com/np/npregister_supporter.php" target="_blank">enroll them here</a>. 
  </p>
  
	</div><!-- end rightColumn -->
	<div class="clear"></div>


<div id="footer">
	
	         
</div>
<!--div align="center" style="padding: 5px; color: #666666;">
Page loaded in 0.046941 seconds</div-->
 
<script type="text/javascript"> 
 
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-16905078-5']);
  _gaq.push(['_trackPageview']);
 
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
 
</script>
<!-- inner/outerContainer -->
</body></html>
