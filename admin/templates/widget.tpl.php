<?php
/**
 * Created by Lilian Codreanu.
 * User: Lilian Codreanu
 * Date: 2/9/13
 * Time: 9:27 PM
 */

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>
<?=$members_area_header;?>

<script language=JavaScript src='/scripts/jquery/jquery-1.3.2.js'></script>
<script language=JavaScript src='/scripts/widget.js'></script>

<input type="hidden" id="servertime"/>
<? echo ($setts['default_theme'] == 'ultra') ? $menu_box_content : '';?>

<?=$members_area_header_menu;?>
<br>
<div style="width: 100%">
<div style="width: 40%; float: left">
<table cellpadding="0" cellspacing="0" width="100%" border="0">
    <tr>
   		<td valign="top">
               <div>NonProfit User: </div>
               <div>
                   <select name="np_user" id="np_user">
                       <?=$npUsers_list?>
                   </select>
               </div>
           </td>
   	</tr>
	<tr>
		<td valign="top">
            <div> Width: </div>
            <div><input type="text" name="width" id="width" value="300" /></div>
        </td>
	</tr>
	<tr>
		<td valign="top">
            <div> Height: </div>
            <div><input type="text" name="height" id="height" value="250" /></div>
        </td>
	</tr>
    <tr>
   		<td valign="top">
               <div> Number of vendors: </div>
               <div><input type="text" name="blocks" id="blocks" value="9" /></div>
           </td>
   	</tr>
	<tr>
		<td valign="top">
            <div> Color Scheme: </div>
            <div>
                <select name="colorscheme" id="colorscheme">
                    <option value="light">light</option>
                    <option value="dark">dark</option>
                </select>
            </div>
        </td>
	</tr>
    <tr>
   		<td valign="top">
               <div> <input type="button" value="Get Code" id="generate" /> </div>
           </td>
   	</tr>
</table>
</div>

    <input type="hidden" id="wkey" name="wkey" value="<?=$defaultUser?>" />
    <div id="wget_frame" style="width: 60%; float: left">
        <iframe name="frameWget" id="frameWget" frameborder="0" scrolling="0" src="http://<?php echo $_SERVER['SERVER_NAME']?>/widget.php?wkey=<?=$defaultUser?>&blocks=9" style="border: none; overflow: hidden; width:300px; height:250px" ></iframe>
    </div>
</div>

<br style="clear: both" />
<div>
    <b>Code Result:</b><br/>
    <textarea rows="4" cols="75" name="coderesult" id="coderesult" wrap="soft"></textarea>
</div>
<div>
    <h3>Attributes</h3>

    <ul>
        <li><b>width</b> - the width of the plugin in pixels. Default width: 300px.</li>
        <li><b>height</b> - the height of the plugin in pixels. The default height varies based on number of faces to display, and whether the stream is displayed. With the stream displayed, and 10 faces the default height is 556px. With no faces, and no stream the default height is 63px.</li>
        <li><b>colorscheme</b> - the color scheme for the plugin. Options: 'light', 'dark'</li>
    </ul>
</div>