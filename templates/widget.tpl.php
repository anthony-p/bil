<?php
/**
 * Created by Lilian Codreanu.
 * User: Lilian Codreanu
 * Date: 1/11/13
 * Time: 1:29 AM
 */

if ( !defined('INCLUDED') ) { die("Access Denied"); }

$bodyClass = "";
?>

<script language=JavaScript src='/scripts/jquery/jquery-1.3.2.js'></script>
<script language=JavaScript src='/scripts/widgetvendor.js'></script>
<script language=JavaScript src='/scripts/html5placeholder.jquery.js'></script>
<script>
  $(function(){
    $(':input[placeholder]').placeholder();
  });
</script>
<?php if(!isset($_GET["color"])): ?>
    <link href="/css/widget_dark.css" rel="stylesheet" type="text/css">
<?php else:  ?>
    <?php if($_GET["color"] == "dark"): ?>
            <?php $bodyClass = "bgblack"; ?>
            <link href="/css/widget_dark.css" rel="stylesheet" type="text/css">
        <?php else:  ?>
            <link href="/css/widget_light.css" rel="stylesheet" type="text/css">
        <?php endif; ?>

<?php endif; ?>


<body class="<?php echo $bodyClass; ?>">
<input type="hidden" id="wkey" name="wkey" value="<?php echo @$_GET["wkey"]; ?>" />
<div class="bring_frame">
<div class="header"></div>
    <div class="content">
<div class="searchBox">
    <div>Support <a href="/<?=$username?>"><?=$companyName?></a> whenever you shop online. Just start here:</div>
    <input type="text" id="searchKeyword" value="" placeholder="Search by name or keyword" />
    <input type="button" id="searchButton" value="Search" />
</div>
<div class="logos">
    <fieldset>
        <?php foreach($vendors as $vendor): ?>
            <div class="logo_box"><?=html_entity_decode($vendor["big_banner_code"])?> </div>
        <?php endforeach; ?>
        <br clear="all" />
    </fieldset>
</div>
<!--<br style="clear: both"/>-->
<div class="footer">
    <a href="http://<?php echo $_SERVER['SERVER_NAME']?>/about_us,page,content_pages" target="_blank">Keep Money Local: learn more >> </a>
</div>
        </div>
</div>
</body>