
		</div>

		<div style="width: 100%;">
			<div style="width: 940px; margin: 0 auto; margin-top: 30px; font-size: 10px; background: url( ../pics/bg_footer.gif ) repeat-x; border-left: 1px solid #B1B4B5; border-right: 1px solid #B1B4B5; border-bottom: 1px solid #B1B4B5; color: #606060; -moz-border-radius: 5px; border-radius: 5px;">
				<div style="float: left; padding: 3px; padding-top: 7px;"><a href="http://www.phplivesupport.com/?ref=link&key=<?php echo $KEY ?>&plk=osicodes-5-ykq-m" target="_blank">PHP Live! Support</a> &copy; OSI Codes Inc.</div>
				<div style="float: left; width: 2px; height: 26px; background: url( ../pics/h_divider.gif ) no-repeat;"></div>
				<div style="float: left; padding: 3px; padding-top: 7px;"><a href="http://www.phplivesupport.com/copyright.php?&plk=osicodes-5-ykq-m" target="_blank">copyright policy</a></div>
				<div style="float: left; width: 2px; height: 26px; background: url( ../pics/h_divider.gif ) no-repeat;"></div>
				<div style="float: left; padding: 3px; padding-top: 7px;"><a href="http://www.phplivesupport.com/help_desk.php?&plk=osicodes-5-ykq-m" target="_blank">help</a></div>
				<div style="float: left; width: 2px; height: 26px; background: url( ../pics/h_divider.gif ) no-repeat;"></div>
				<div style="float: left; padding: 3px; padding-top: 7px;">software license key: <a href="http://www.phplivesupport.com/?ref=link&key=<?php echo $KEY ?>&plk=osicodes-5-ykq-m" target="_blank"><?php echo $KEY ?></a></div>
				<div style="clear: both;"></div>
			</div>
		</div>
	</div>
</div>
<div style="position: absolute; top: 0px; left: 0px; width: 100%; z-index: 13;">
	<div style="width: 940px; margin: 0 auto; background: url( ../pics/bg_trans.png ) repeat; border-bottom-left-radius: 5px 5px; -moz-border-radius-bottomleft: 5px 5px; border-bottom-right-radius: 5px 5px; -moz-border-radius-bottomright: 5px 5px;">
		<div id="menu_wrapper">
			<div id="menu_home" class="menu" onClick="location.href='index.php?ses=<?php echo $ses ?>'">Home</div>
			<div id="menu_depts" class="menu" onClick="location.href='depts.php?ses=<?php echo $ses ?>'">Departments</div>
			<div id="menu_ops" class="menu" onClick="location.href='ops.php?ses=<?php echo $ses ?>'">Operators</div>
			<div id="menu_icons" class="menu" onClick="location.href='icons.php?ses=<?php echo $ses ?>'">Chat Icons</div>
			<div id="menu_html" class="menu" onClick="location.href='code.php?ses=<?php echo $ses ?>'">HTML</div>
			<div id="menu_trans" class="menu" onClick="location.href='transcripts.php?ses=<?php echo $ses ?>'">Transcripts</div>
			<div id="menu_rchats" class="menu" onClick="location.href='reports_chat.php?ses=<?php echo $ses ?>'">Report: Chats</div>
			<div id="menu_rtraffic" class="menu" onClick="location.href='reports_traffic.php?ses=<?php echo $ses ?>'">Report: Traffic</div>
			<div id="menu_marketing" class="menu" onClick="location.href='marketing.php?ses=<?php echo $ses ?>'">Marketing</div>
			<div id="menu_external" class="menu" onClick="location.href='external.php?ses=<?php echo $ses ?>'">External URLs</div>
			<div id="menu_settings" class="menu" onClick="location.href='settings.php?ses=<?php echo $ses ?>'">Settings</div>
			<div style="clear: both;"></div>
		</div>
	</div>
</div>
<div style="position: absolute; top: 13px; left: 0px; width: 100%; z-index: 12;">
	<div style="width: 940px; margin: 0 auto; padding-top: 32px;">
		<div style="font-size: 10px; font-weight: bold; color: #CBD1D5; text-align: right;"><span style="font-size: 16px; text-shadow: #5A6787 -1px -1px;">PHP Live! v.<?php echo $VERSION ?></span> &nbsp; <span style="padding: 5px; background: #C5C7D5; color: #7C89A9;"><?php echo $admininfo["login"] ?> <a href="../index.php?action=logout&from=setup&ses=<?php echo $ses ?>">sign out</a></span></div>
	</div>
</div>

<div style="position: absolute; background: url( ../pics/bg_fade_bottom.png ) repeat-x; background-position: bottom; top: 0px; left: 0px; width: 100%; height: 60px; z-index: 11;"></div>
<?php
	if ( isset( $dbh ) && $dbh['con'] )
		database_mysql_close( $dbh ) ;
?>
