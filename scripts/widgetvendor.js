
$(document).ready(function() {

    var vendors = ["www.dpbolvw.net", "www.anrdoezrs.net", "www.jdoqocy.com", "doubleclick.net", "amazon.com", "linksynergy.com", "indiebound.org", "www.kqzyfj.com"];

    $.each(vendors, function(index, value){
        $("a[href*='"+value+"']").live("click", function(event){
        	var _href = $(this).attr('href');
        	if(_href.search("shop_selected.php") == -1) {
        		event.preventDefault();
                var widget = $("#wkey").val();
        		var u = "/shop_selected.php?fromwidget="+widget+"&shop_url=" + _href;
            window.open(u);
        	}
           });
    })

    $("#searchButton").click(function(){
        if(jQuery.trim($("#searchKeyword").val()) != ""){
            $.ajax({
              url: "widget_search_gp.php?search="+$("#searchKeyword").val(),
              dataType: 'json',
              success:function(data) {
                  $(".logos fieldset").html("");
                  $.each(data, function(i, item) {
                      $(".logos fieldset").append('<div class="logo_box">'+data[i]+'</div>');
                  })
              }
            });
        }
    });

    $('#searchKeyword').keypress(function(e) {
        if(e.which == 13) {
            jQuery(this).blur();
            $("#searchButton").focus().click();
        }
    });
});