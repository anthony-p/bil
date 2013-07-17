function $m(theVar){
	return document.getElementById(theVar)
}
function remove(theVar){
	var theParent = theVar.parentNode;
	theParent.removeChild(theVar);
}
function addEvent(obj, evType, fn){
	if(obj.addEventListener)
	    obj.addEventListener(evType, fn, true)
	if(obj.attachEvent)
	    obj.attachEvent("on"+evType, fn)
}
function removeEvent(obj, type, fn){
	if(obj.detachEvent){
		obj.detachEvent('on'+type, fn);
	}else{
		obj.removeEventListener(type, fn, false);
	}
}

// browser detection
function isWebKit(){
	return RegExp(" AppleWebKit/").test(navigator.userAgent);
}

// send data
function ajaxUpload(form) {
	var url = "np/logo_upload/php/insert.php?do=upload";
	var div_id = "upload_area";
	var show_loading = "<img src='img/loading.gif' />";
	var html_error = "<img src='img/error.gif' />";
	var detectWebKit = isWebKit();

	// create iframe
	var sendForm = document.createElement("iframe");
	sendForm.setAttribute("id","uploadform-temp");
	sendForm.setAttribute("name","uploadform-temp");
	sendForm.setAttribute("width","0");
	sendForm.setAttribute("height","0");
	sendForm.setAttribute("border","0");
	sendForm.setAttribute("style","width: 0; height: 0; border: none;");

	// add to document
	form.parentNode.appendChild(sendForm);
	window.frames['uploadform-temp'].name="uploadform-temp";

	// add event
	var doUpload = function(){
		removeEvent($m('uploadform-temp'),"load", doUpload);
		var cross = "javascript: ";
		cross += "window.parent.$m('"+div_id+"').innerHTML = document.body.innerHTML; void(0);";
		$m(div_id).innerHTML = html_error;
		$m('uploadform-temp').src = cross;
		if(detectWebKit){
        	remove($m('uploadform-temp'));
        }else{
        	setTimeout(function(){ remove($m('uploadform-temp'))}, 250);
        }
    }
	addEvent($m('uploadform-temp'),"load", doUpload);

	// form proprietes
	form.setAttribute("target","uploadform-temp");
	form.setAttribute("action",url);
	form.setAttribute("method","post");
	form.setAttribute("enctype","multipart/form-data");
	form.setAttribute("encoding","multipart/form-data");

	// loading
	if(show_loading.length > 0){
		$m(div_id).innerHTML = show_loading;
	}
	// submit
	form.submit();
	formDisable();
  return false;
}

// send data
function ajaxSend(form){
	var url = "insert.php?do=insert";
	var div_id = "info_message";
	var show_loading = "<img src='img/loading.gif' />";
	var html_error = "<img src='img/error.gif' />";
	var detectWebKit = isWebKit();

	// create iframe
	var sendForm = document.createElement("iframe");
	sendForm.setAttribute("id","sendform-temp");
	sendForm.setAttribute("name","sendform-temp");
	sendForm.setAttribute("width","0");
	sendForm.setAttribute("height","0");
	sendForm.setAttribute("border","0");
	sendForm.setAttribute("style","width: 0; height: 0; border: none;");

	// add to document
	form.parentNode.appendChild(sendForm);
	window.frames['sendform-temp'].name="sendform-temp";

	// add event
	var doSend = function(){
		removeEvent($m('sendform-temp'),"load", doSend);
		var cross = "javascript: ";
		cross += "window.parent.$m('"+div_id+"').innerHTML = document.body.innerHTML; void(0);";
		$m(div_id).innerHTML = html_error;
		$m('sendform-temp').src = cross;
		if(detectWebKit){
        	remove($m('sendform-temp'));
        }else{
        	setTimeout(function(){ remove($m('sendform-temp'))}, 250);
        }
    }
	addEvent($m('sendform-temp'),"load", doSend);

	// form proprietes
	form.setAttribute("target","sendform-temp");
	form.setAttribute("action",url);
	form.setAttribute("method","post");
	form.setAttribute("enctype","multipart/form-data");
	form.setAttribute("encoding","multipart/form-data");

	// loading
	if(show_loading.length > 0){
		$m(div_id).innerHTML = show_loading;
	}
	// submit
	form.submit();
	formDisable();
  return false;
}

// limit textarea
function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.value = limitNum - limitField.value.length;
	}
}

// refresh captcha
function refreshCaptcha() {
	document.getElementById('captcha').src="insert.php?do=captcha&_rnd=" + Math.random();
  }

// disable form
  function formDisable() {
    var limit = document.forms[0].elements.length;
    for (i=0;i<limit;i++) {
      document.forms[0].elements[i].disabled = true;
    }
  }

// enable form
  function formEnable() {
    var limit = document.forms[0].elements.length;
    for (i=0;i<limit;i++) {
      document.forms[0].elements[i].disabled = false;
    }
  }

// when from is submited
// disable form and after 5sec
//reload captcha, reset form, then enable it
function formDone() {
	formDisable();
setTimeout ( "refreshCaptcha()", 5000 );
setTimeout ( "formEnable()", 5000 );
}
