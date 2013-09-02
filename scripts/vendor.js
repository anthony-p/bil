/**
 * Created with Lilian Codreanu.
 * User: Lilian Codreanu
 * Date: 11/4/12
 * Time: 3:56 PM
 */


$(document).ready(function() {

    var vendors = ["www.dpbolvw.net", "www.anrdoezrs.net", "www.jdoqocy.com", "doubleclick.net", "amazon.com", "linksynergy.com", "indiebound.org"];

    $.each(vendors, function(index, value){
        $("a[href*='"+value+"']").on("click", function(event){
        	var _href = $(this).attr('href');
        	if(_href.search("shop_selected.php") == -1) {
                popupAlert(_href);
        		event.preventDefault();
        		var u = "/shop_selected.php?shop_url=" + _href;
        		window.location.href=u;
        	}
           });
    })
});