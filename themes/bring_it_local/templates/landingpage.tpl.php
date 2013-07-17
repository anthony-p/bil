<div id="communityBlock" class="rightBlock">
	<?
	
	if (isset($_COOKIE["np_userid"]))
	
	$mynp_userid=$_COOKIE["np_userid"];
	
	else
	
	$mynp_userid = np_userid;
	
	$mynp = $db->get_sql_field("SELECT tax_company_name  FROM np_users WHERE user_id ='" . $mynp_userid . "'", tax_company_name);
	$np_logo = $db->get_sql_field("SELECT logo  FROM np_users WHERE user_id ='" . $mynp_userid . "'", logo);
	$npusername = $db->get_sql_field("SELECT username  FROM np_users WHERE user_id ='" . $mynp_userid . "'", username);
	
	// if ($mynp_userid!='')
	// {
	// 	 echo "<a href=rss.php?feed=10&user_id="  . $mynp_userid . "><img src=\"/images/rss_community.gif\" border=0></a>";
	// }
	
	echo "<div class='welcomeMsg'><a href=\"$npusername\">Welcome  supporters of $mynp!</a>";
	#echo "sales?";
	#echo sales;
	$npsales = sales;
	
	
	if (isset($np_logo))
				{
					echo "<img src=\"/np/uplimg/logos/";
	echo $np_logo;
	echo "\">"; 
				}
	
	if (($npsales  == '1') || ($_COOKIE["sales"] == '1'))
	{
	echo "</div>";
	  ?>
	<div class='funds'>
                <div class="funds_info">
                    <div class="funds_cont">
                    <h4>Congratulations!</h4>
                    Here are the funds your community has raised to date.<br>
                    </div>
                </div>
		<?php
			if(strpos($_SERVER['HTTP_USER_AGENT'],"iPhone OS") ==false && strpos($_SERVER['HTTP_USER_AGENT'],"iPod OS") ==false && strpos($_SERVER['HTTP_USER_AGENT'],"iPad OS") == false && strpos($_SERVER['HTTP_USER_AGENT'],"iPad") == false && strpos($_SERVER['HTTP_USER_AGENT'],"iPod") == false && strpos($_SERVER['HTTP_USER_AGENT'],"iPhone") == false):
		?>
		<div id="iframe_container"><iframe src="/reports/landingpage/onebar.php?sv1_np_name=<?=$mynp?>&sv_invoice_date=%23%23all%23%23" width="160px" height="300px" frameborder="0" scrolling="no"></iframe></div>
		<?php endif;s ?>
                <br clear="all" />
	</div>
	<?php
	} else {
	?>
	<div class='funds'>
		Your community hasn't raised any funds yet. <br>
		<br>
		Watch this 2 minute <strong><a href="http://youtu.be/9OQpAAxTU5E" target="_blank">quick start video</a></strong> to see how to use Bring It Local to raise funds for your organization!!
	</div>
		<?php
	}
	
	
	$npnews_box_header =  header6($mynp . " News");
		if ($is_npnews)		{
			?>
			<div class="siteNewsBlock rightBlock">
				<?= $npnews_box_header;?>
				<?=$npnews_box_content;?>
			</div>
			<? } 
	?>
</div>
