<?
#################################################################
## PHP Pro Bid v6.06															
##
##-------------------------------------------------------------##
## Copyright ©2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>
<?=$setts['sitename'];?> -
<?=MSG_SUBMIT_EDIT_ANSWER;?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?=LANG_CODEPAGE;?>">
<link href="themes/<?=$setts['default_theme'];?>/style.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
}
-->
</style>
    
    
<script language="Javascript">
    
    function set_unsubcribe()
    {
        setCookie('glob_alert', 0, 30);
        alert("Thank you! Popups were switched off successfully.");
        return;
    }
    
    function setCookie(c_name,value,exdays) {
    var exdate=new Date();
        exdate.setTime(exdate.getTime() + 1000 * 60 * 60 * 24 * exdays);
        var c_value=escape(value) + "; path=/";
        document.cookie=c_name + "=" + c_value;
    }

    </script>
    
</head>
<body id="global_partner_alert">
<table border="0" width="100%" cellpadding="2" cellspacing="2" class="border contentfont">
   <form name="popup_form">
      <tr class="c1">
         <td nowrap></td>
         <td width="100%">
        <?       
        
        global $nonprofit;
        global $vendor;
        global $activation_link;
        global $news;
        
            ## html message - editable
            $html_message = '<div class="logo"><img src="http://' . $_SERVER["SERVER_NAME"]. '/images/bringitlocalogo.gif" alt="Bring It Local - shop Main Street not Wall Street" /> </div><br> ';
            $html_message .= 'Hi %1$s,
            
            <br>
            <br>
            
            Thanks for using Bring It Local to support %5$s. We see you just clicked through the banner to %6$s. 
            If you do end up making a purchase, please expect to see the fundraising results on your member page report within 2 days.
            If this does not show up or you think there was some error please let us know.
            <br>
            <br>
            
            Thanks again for supporting %5$s.
            <br>
            <br>
            %7$s
            If you don\'t want to see these pop-ups in the future click 
            <a href ="#" onclick="return set_%4$s();">here</a> to disable them
            <br>
            <br>
            
            Best regards,
            <br>
            <br>
            Bring It Local';    
            $html_message = sprintf($html_message, "", "","", $activation_link, $nonprofit, $vendor, $news, $np_link);
            
            echo $html_message;
        ?>            
         </td>
      </tr>      
   </form>
</table>
</body>
</html>
