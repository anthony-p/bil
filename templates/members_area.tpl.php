<?
#################################################################
## PHP Pro Bid v6.05															##
##-------------------------------------------------------------##
## Copyright �2007 PHP Pro Software LTD. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

if ( !defined('INCLUDED') ) { die("Access Denied"); }
?>

<!--<script language="javascript" src="/scripts/jquery/tiny.editor.js" type="text/javascript"></script>-->
<!--<link href="/scripts/style/tinyeditor.css" rel="stylesheet" type="text/css">-->
<!--<script language="JavaScript" src="/scripts/jquery/tiny_mce/tiny_mce_src.js" js="text/javascript"></script>-->
<!--<script language="JavaScript" src="/scripts/jquery/tiny_mce/jquery.tinymce.js" js="text/javascript"></script>-->


<script language=JavaScript src='/scripts/jquery/jquery-1.9.1.js'></script>

<SCRIPT LANGUAGE="JavaScript">
<!--
//myPopup = '';
//
//function openPopup(url) {
//	myPopup = window.open(url,'popupWindow','width=350,height=250,status=yes');
//   if (!myPopup.opener) myPopup.opener = self;
//}
//-->
(function($) {
    $.cookie = function(key, value, options) {

        // key and at least value given, set cookie...
        if (arguments.length > 1 && (!/Object/.test(Object.prototype.toString.call(value)) || value === null || value === undefined)) {
            options = $.extend({}, options);

            if (value === null || value === undefined) {
                options.expires = -1;
            }

            if (typeof options.expires === 'number') {
                var days = options.expires, t = options.expires = new Date();
                t.setDate(t.getDate() + days);
            }

            value = String(value);

            return (document.cookie = [
                encodeURIComponent(key), '=', options.raw ? value : encodeURIComponent(value),
                options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
                options.path ? '; path=' + options.path : '',
                options.domain ? '; domain=' + options.domain : '',
                options.secure ? '; secure' : ''
            ].join(''));
        }

        // key and possibly options given, get cookie...
        options = value || {};
        var decode = options.raw ? function(s) { return s; } : decodeURIComponent;

        var pairs = document.cookie.split('; ');
        for (var i = 0, pair; pair = pairs[i] && pairs[i].split('='); i++) {
            if (decode(pair[0]) === key) return decode(pair[1] || ''); // IE saves cookies with empty string as "c; ", e.g. without "=" as opposed to EOMB, thus pair[1] may be undefined
        }
        return null;
    };
})(jQuery);

var np_userid_update=jQuery.cookie("np_userid_update");
var np_username_update=jQuery.cookie("np_username_update");
if (np_userid_update!=null && np_userid_update!="" && np_username_update!=null && np_username_update!="")
{
    $('.nonprofit').children('a').attr('href','/'+np_username_update);
    $('.nonprofit').children('a').children("strong").html(np_userid_update);

    $.cookie("np_userid_update", "test", { expires: -1, path: '/', domain: 'bringitlocal.com'});
    $.cookie("np_username_update", "test", { expires: -1, path: '/', domain: 'bringitlocal.com'});
}

</SCRIPT>
<?//=$members_area_header;?>
<?// echo ($setts['default_theme'] == 'ultra') ? $menu_box_content : '';?>
<!---->
<?//=$members_area_header_menu;?>
<!--<br>-->
<?//=$msg_store_cats_modified;?>
<!--<table cellpadding="0" cellspacing="0" width="100%" border="0">-->
<!--	<tr>-->
<!--		<td valign="top">--><?//=$members_area_stats;?><!--</td>-->
<!--		<td align="right" valign="top">--><?//=$seller_verified_status_box;?><!--</td>-->
<!--	</tr>-->
<!--</table>-->
<!---->
<!--<a href="/np/npregister.php">Create project</a>-->
<?=$msg_member_tips;?>
<?=$msg_pending_gc_transactions;?>
<?=$msg_unpaid_endauction_fees;?>
<?php if (isset ($msg_changes_saved)) echo $msg_changes_saved;?>
<?php if (isset($msg_seller_error)) echo $msg_seller_error;?>
<?php if (isset($members_area_page_content)) echo $members_area_page_content;?>
