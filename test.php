<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php require_once( $_SERVER["DOCUMENT_ROOT"] . '/includes/sitemap_include.php'); ?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=8" />
<title>Biomarin</title>
<style media="all" type="text/css">
@import "css/all.css";
</style>

<script type="text/javascript" src="js/jquery-1.2.6.pack.js"></script>
<script type="text/javascript" src="js/mainfade.js"></script>
<script type="text/javascript" src="js/sifr.js"></script>
<script type="text/javascript" src="js/sifr-config.js"></script>
<script type="text/javascript" src="js/sifr-debug.js"></script>
<script src="Scripts/swfobject_modified.js" type="text/javascript"></script>
<script type="text/javascript" src="js/video.js"></script>

<!--[if lt IE 7]><link rel="stylesheet" type="text/css" href="css/ie6.css" media="screen"/><![endif]-->
<link rel="stylesheet" href="css/sifr.css" type="text/css" />
<link rel="shortcut icon" href="/favicon.ico">
<script language="Javascript">
 <!--

 function doClear(theText)
{
     if (theText.value == theText.defaultValue)
 {
         theText.value = ""
     }
 }
 //-->
 </script>
 <?php define('MAGPIE_INPUT_ENCODING', 'UTF-8');
define('MAGPIE_OUTPUT_ENCODING', 'UTF-8');
require_once($_SERVER["DOCUMENT_ROOT"] . '/includes/magpierss/rss_fetch.inc');
$rssurl ="http://apps.shareholder.com/rss/rss.aspx?channels=9149&companyid=ABEA-3W276N&sh_auth=2102730351.0.0.55adf1ac66a52728a14f809011099dbf";
$rss = fetch_rss($rssurl); // NOTE: Uncomment this line when the RSS issue is fixed.
?>
     <style type="text/css" media="print">
#preload
{
display: none
}
</style>
</head>
<body>
<div id="preload">
<img src="/images/subnav-orange.png"  alt="PRELOAD" />
<img src="/images/subnav-blue.png" alt="PRELOAD" />
<img src="/images/subnav-blue2.png" alt="PRELOAD" />
<img src="/images/subnav-violet-dark.png" alt="PRELOAD" />
<img src="/images/subnav-violet.png" alt="PRELOAD" />
</div>
<div id="main-container">
  <div id="header"> <strong><a href="/index.php">Biomarin</a></strong>
    <form action="/search/search.php">
      <div class="link">
        <fieldset>
        <input type="text" class="text" name="query" value="Search..." onFocus="doClear(this)" />
        <input type="hidden" name="search" value="1"/>
        <input type="image" src="images/button-submit.gif" />
        </fieldset>
        <ul>
          <li><a href="/index.php">Home</a></li>
          <li><a href="/contact-us">Contact Us</a></li>
          <li><a href="sitemap.php">Site Map</a></li>
        </ul>
      </div>
    </form>
    <?php getTopMenu()  ?>
  </div>
  <div id="container">
    <div id="promo">
      <?php $swf_list = array('BioMarin_Hmpg_Bnr_v1_0710.swf', 'BioMarin_Hmpg_Bnr_v2_0710.swf');
		$num = mt_rand(0, count($swf_list) - 1);			?>
      <object id="FlashID" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="922" height="377">
        <param name="movie" value="swf/<?php echo $swf_list[$num]; ?>" />
        <param name="quality" value="high" />
        <param name="wmode" value="transparent" />
        <param name="swfversion" value="8.0.35.0" />
        <!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don’t want users to see the prompt. -->
        <param name="expressinstall" value="Scripts/expressInstall.swf" />
        <!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
        <!--[if !IE]>-->
		  <object type="application/x-shockwave-flash" data="swf/<?php echo $swf_list[$num]; ?>" width="922" height="377">
		    <!--<![endif]-->
        <param name="quality" value="high" />
        <param name="wmode" value="transparent" />
        <param name="swfversion" value="8.0.35.0" />
        <param name="expressinstall" value="Scripts/expressInstall.swf" />
        <!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
        <div>
          <h4>Content on this page requires a newer version of Adobe Flash Player.</h4>
          <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" width="112" height="33" /></a></p>
        </div>
        <!--[if !IE]>-->
	      </object>
		  <!--<![endif]-->
      </object>
    </div>
    <div id="inform">
      <div class="i-tbg">
        <div class="i-bbg">
          <div class="sifr-text">
            <p>With four products on the market and a fully-integrated
              multinational organization in place, BioMarin is providing innovative
              therapeutics to patients with serious unmet medical needs. </p>
          </div>
          <div class="news">
            <h3>LATEST NEWS</h3>
            <ul>
<?php
    $count=0;
   foreach ($rss->items as $item )
    {
    if ($count >=3)
   	 break;
   	$title =  substr($item['title'] ,0, 250  )."...";
   	$url   = $item['link'];
   	echo "<li><strong>$title</strong>&nbsp;&nbsp;<a href=$url target=_blank>MORE&nbsp;&gt;</a></li>";
   	$count=$count+1;
    }
 ?>
   </ul>
          </div>
           
          <div class="events">
            <ul>
              <li> <a href="/patients-physicians/bpps.php"><img src="images/bppslogo.jpg" width="173" height="71" alt="Contact BioMarin Patient and Physician Support Services"/><//></a>
                
                <a href="/patients-physicians/bpps.php">Contact BioMarin Patient and Physician Support Services</a></li>
              <li style="border-bottom:none;"> <a href="http://www.pku.com/" target="_blank"><img src="images/img02.jpg" width="89" height="89" alt=""/></a>
                <h3>PKU (PHENYLKETONURIA)</h3>
<a href="http://www.pku.com/" target="_blank">PKU.com is a comprehensive online resource for the PKU community.</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div id="footer">
      <p> &copy; Copyright <?php
echo date("Y") ?> BioMarin Pharmaceutical Inc. All Rights Reserved. </p>
      <ul>
        <li><a href="legal-notice.php">Legal Notice</a></li>
        <li><a href="glossary.php">Glossary</a></li>
        <li><a href="sitemap.php">Site Map</a></li>
        <li><a href="/contact-us">Contact Us</a></li>
      </ul>
    </div>
  </div>
</div>
<script type="text/javascript">
<!--
swfobject.registerObject("FlashID");
//-->
    </script>
</body>
</html>
