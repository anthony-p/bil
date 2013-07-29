<?
#################################################################
## PHP Pro Bid v6.06															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>

<table border="0" cellpadding="3" cellspacing="3" width="100%" class="c1 border contentfont">
   <tr>
      <td class="c2" height="35" align="center">

<?

if (isset($session)) {
    $row_user = $db->get_sql_row("SELECT * FROM
    				bl2_users WHERE id=" . $session->value('user_id'));
    $username = $row_user['email']; /* the readonly field will not be altered */
} else
    $username = "Guest";

$mynp = $row_user['tax_company_name'];


?>


      	<?=MSG_WELCOME_BACK;?>,<br><b><?=$mynp;?></b><br>

      </td>
	</tr>
	
   <tr>
      <td class="c2"><b>
   <a href = "/reports/npmember/summary.php?sv1_np_name=<?=$mynp?>&sv_invoice_date=%23%23all%23%23" target="_blank">

   <?
   #create a session variable that contains the np name so we can secure the fundraising report to only the logged in np. It wont be possible to simply change the fundraising report url
   $_SESSION['nporgname'] = $mynp
   ?>
Your fundraising report</a></b>
      </td>
	</tr>

   <tr>
      <td class="c4"><b>
   <a href = "/np/npmembers_news.php?news=news">
Non profit news</a></b>
      </td>
	</tr>   
   
   <tr>
      <td class="c2"><b>


<a href="/<?=$username;?>" target="_blank"> Your public landing page </a>


</b></td>

   </tr>
   <tr>
      <td class="c4"><b>
        <a href="/np/toolkit.doc" target="_blank"> Toolkit - download as word doc </a>
        </b>
      </td>
   </tr>
   <tr>
       <td class="c4"><b>
        <a href="/np/npwidget.php" target="_blank"> Widget </a>
        </b>
      </td>
   </tr>

</table>
<br>
