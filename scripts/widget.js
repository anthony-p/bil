/**
 * Created with JetBrains PhpStorm.
 * User: Lilian Codreanu
 * Date: 1/15/13
 * Time: 12:09 PM
 */

$(document).ready(function() {

    $("#width").change(function(){
        $("#frameWget").css("width",$(this).val()+"px")
    })
    $("#height").change(function(){
        $("#frameWget").css("height",$(this).val()+"px")
    })
    $("#blocks").change(function(){
        $("#frameWget").attr("src","http://"+document.domain+"/widget.php?wkey="+$("#wkey").val()+"&blocks="+$(this).val()+"&color="+$("#colorscheme").val());
    })

    $("#generate").click(function(){
        $("#coderesult").val(jQuery.trim($("#wget_frame").html()));
    });

    $("#colorscheme").change(function(){
        $("#frameWget").attr("src","http://"+document.domain+"/widget.php?wkey="+$("#wkey").val()+"&blocks="+$("#blocks").val()+"&color="+$(this).val());
    });

    $("#np_user").change(function(){
        $("#wkey").val($(this).val());
        $("#frameWget").attr("src","http://"+document.domain+"/widget.php?wkey="+$("#wkey").val()+"&blocks="+$("#blocks").val()+"&color="+$("#colorscheme").val());
    });

});
