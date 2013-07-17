// JavaScript for PHP Report Maker 6
// (C) 2007-2012 e.World Technology Ltd.

if (!Array.prototype.forEach) {
	Array.prototype.forEach = function(fn, scope) {
		for (var i = 0, len = this.length; i < len; ++i) {
			fn.call(scope || this, this[i], i, this);
		}
	}
}

$(window).keydown(function(event){window._ctrlKey=event.ctrlKey;}).keyup(function(event){window._ctrlKey=0});

var ewrEmailDialog;
var ewrLoadingDialog, ewrDrillDownPanel, ewrDrillDownTimer = null;

// Global constants
var EWR_TABLE_CLASSNAME = "ewTable";
var EWR_GRID_CLASSNAME = "ewGrid";
var EWR_TABLE_ROW_CLASSNAME = "ewTableRow";
var EWR_TABLE_ALT_ROW_CLASSNAME = "ewTableAltRow";
var EWR_ITEM_TEMPLATE_CLASSNAME = "ewTemplate";
var EWR_ITEM_TABLE_CLASSNAME = "ewItemTable";
var EWR_UNFORMAT_YEAR = 50;

var EWR_POPUP_MINWIDTH = 200;
var EWR_POPUP_DEFAULTHEIGHT = 200;
var EWR_EMPTY_VALUE = "##empty##";
var EWR_NULL_VALUE = "##null##";

var ewrEnv = YAHOO.env;
var ewrWidget = YAHOO.widget;
var ewrLang = YAHOO.lang;
var ewrUtil = YAHOO.util;
var ewrJson = ewrLang.JSON;
var ewrDom = YAHOO.util.Dom;
var ewrEvent = YAHOO.util.Event;
var ewrGet = YAHOO.util.Get;
var ewrConnect = YAHOO.util.Connect;
var ewrDate = ewrUtil.Date; // Read http://developer.yahoo.com/yui/docs/ewUtil.Date.html
var ewrNumber = ewrUtil.Number; // Read http://developer.yahoo.com/yui/docs/YAHOO.util.Number.html
var ewrSelect = jQuery.find;

// Custom events
var ewrAddOptionEvent = new ewrUtil.CustomEvent("AddOption"); // args[0].data is the new option to be validated
var ewrNewOptionEvent = new ewrUtil.CustomEvent("NewOption"); // args[0].data is the new option to be added

// Subscribe custom event 
// Read: http://developer.yahoo.com/yui/event/#customevent
// Example:
//ewrNewOptionEvent.subscribe(function(type, args) {
//	//alert(ewrJson.stringify(args)); // View all arguments
//	var data = args[0].data; // Data
//	// your code to customize args[0].data, e.g.
//	if (args[0].id == "x_MyDateField") data[1] = ewrDate.format(ewr_StrToDate(data[1]), {format: "%d/%m/%Y"}); // Format the display value #1 as yyyy-mm-dd
//});

// Attach event by element id or name
function ewr_On(el, sType, fn, obj, overrideContext) {
	if (ewrLang.isString(el)) { // String
		if (!/^\w+(\[\])?$/.test(el)) { // Not element name => selector
			el = ewr_Select(el);
		} else {
			el = ewr_Select("[name='" + el + "'],[id='" + el + "']");
		}
	} 
	ewrEvent.on(el, sType, fn, obj, overrideContext);
}

// Forms object
var ewrForms = {};

// Charts object
var ewrDrillCharts = [];
var ewrExporting = false;
var ewrExportCharts = [];

function ewr_ExportCharts(obj, url, exportid, f) {

	if (ewrExporting)
		return;

	exportid += "_" + (new Date()).getTime();
	url += (url.split("?").length > 1 ? "&" : "?") + "exportid=" + exportid;
	var method = f ? "post" : "get";
	if (ewrExportCharts.length == 0 || ewrEnv.ua.ios > 0) // No charts, just submit the form
		ewr_Redirect(url, f, method);

	var chartcnt = 0;
	var abort = false;

	// Set FC_Rendered event
	FC_Rendered = function(id) {
		// Export the chart
		var cht = FusionCharts(id);
		cht.exportChart({exportFileName: exportid + "_" + ewrExportCharts[chartcnt]});
	}

	FC_Exported = function(oRtn) {
		ewrLoadingDialog.setBody(""); // Clear div
		ewrLoadingDialog.hide();
		if (abort)
			return;
		if (oRtn.statusCode == "1") {
			chartDispose(oRtn.DOMId);
			chartcnt++;
			if (chartcnt == ewrExportCharts.length) { // All charts exported
				ewr_Redirect(url, f, method);
				ewrExporting = false;
			} else { // Next chart
				chartExport(ewrExportCharts[chartcnt]);
			}
		} else {
			chartDispose(oRtn.DOMId);
			ewrExporting = false;
			alert(ewLanguage.Phrase("ExportChartError") + oRtn.statusMessage);
		}
	}

	// Dispose chart after use
	function chartDispose(id) {
		var cht = FusionCharts(id);
		cht.dispose();
	}

	// Export chart
	function chartExport(id) {
		var cht = FusionCharts(id);
		// Clone the original chart to div
		var chartid = "cht_" + id.substr(6);
		var divid = "div_export_" + id.substr(6);
		var divwidth = parseInt(cht.width);
		var divheight = parseInt(cht.height)+60;
		var html = "<div id=\"" + divid + "\"></div>";

		var cfg = {
			context: [obj, "tl", "bl"], // Show at "bl" (bottom left)
			draggable: true,
			width: divwidth + "px",
			height: divheight + "px",
			constraintoviewport: true,
			visible: false,
			buttons: [{ text:ewLanguage.Phrase("PopupCancel"), handler:function(){ewrExporting = false; chartDispose(this.chartId); this.cancel();} }]
		};

		ewrLoadingDialog.cfg.applyConfig(cfg);
		ewrLoadingDialog.setHeader(ewLanguage.Phrase("ExportingChart").replace("%c", chartcnt+1).replace("%t", ewrExportCharts.length)); // Exporting chart message
		ewrLoadingDialog.setBody(html);
		ewrLoadingDialog.render();
		var newCht = cht.clone({renderer: 'flash'});
		newCht.setChartAttribute("animation", "0"); // Disable animation
		newCht.render(divid);
		ewrLoadingDialog.chartId = newCht.id;
		ewrLoadingDialog.show();
	}

	// Export first chart
	ewrExporting = true;
	if (ewrLoadingDialog.cancelEvent.subscribers.length == 0)
		ewrLoadingDialog.cancelEvent.subscribe(function(type, args) { abort = true; }); 
	chartExport(ewrExportCharts[chartcnt]);

}

// popup object
var ewrPopups = {};
var ewrPopupFilterDialog, ewrPopupFilterResize, ewrPopupTimer = null;

// Drill down callback
var ewrPopupCallback = {
	cache: false,
	// Show results in div
	success: function(o) {
		if (ewrLoadingDialog)
			ewrLoadingDialog.hide();
		if (o.argument.popupname && o.responseText) {
			var db = ewrJson.parse(o.responseText); 
			ewr_CreatePopup(o.argument.popupname, db);
			ewr_SetPopupContent(o.argument.popupname, db, o.argument.useRange, o.argument.rangeFrom, o.argument.rangeTo);
			ewrPopupFilterDialog.cfg.applyConfig(o.argument.cfg);
			ewrPopupFilterDialog.render();
			ewrPopupFilterDialog.show();
		}
	},
	// Show error in div
	failure: function(o) {
		if (ewrLoadingDialog)
			ewrLoadingDialog.hide();
		if (o.responseText) {
			alert(o.responseText);
		}
	},
	// Argument
	argument: {}
}

// Init popup filter
function ewr_InitPopupFilter() {

	if (ewrPopupFilterDialog)
		return;

	// Create a dialog instance for the popup filter DIV 'ewrPopupFilterDiv'
	ewrPopupFilterDialog = new ewrWidget.Dialog("ewrPopupFilterDiv", {
		draggable: false, close: false,
		width: EWR_POPUP_MINWIDTH + "px",
		height: EWR_POPUP_DEFAULTHEIGHT + "px",
		constraintoviewport: true,
		visible: false,
		postmethod: "post"		
	});
	ewrPopupFilterDialog.render();

	// Create Resize instance, binding it to the popup filter DIV
	ewrPopupFilterResize = new ewrUtil.Resize("ewrPopupFilterDiv", {
		handles: ["br"],
		autoRatio: false,
		minWidth: EWR_POPUP_MINWIDTH,
		minHeight: EWR_POPUP_DEFAULTHEIGHT,
		status: false
	});

	// Setup startResize handler, to constrain the resize width/height
	// if the constraintoviewport configuration property is enabled.
	ewrPopupFilterResize.on("startResize", function(args) {	
		if (this.cfg.getProperty("constraintoviewport")) {
			var clientRegion = ewrDom.getClientRegion();
			var elRegion = ewrDom.getRegion(this.element);
			ewrPopupFilterResize.set("maxWidth", clientRegion.right - elRegion.left - ewrWidget.Overlay.VIEWPORT_OFFSET);
			ewrPopupFilterResize.set("maxHeight", clientRegion.bottom - elRegion.top - ewrWidget.Overlay.VIEWPORT_OFFSET);
		} else {
			ewrPopupFilterResize.set("maxWidth", null);
			ewrPopupFilterResize.set("maxHeight", null);
		}
	}, ewrPopupFilterDialog, true);

	// Setup resize handler to update the dialog's 'height' configuration property 
	// whenever the size of the popup filter DIV DIV changes.
	
	// Setting the height configuration property will result in the 
	// body of the Panel being resized to fill the new height and the iframe shim
	// and shadow being resized also if required (for IE6 and IE7 quirks mode).
	ewrPopupFilterResize.on("resize", function(args) {
		var dlgHeight = args.height;
		this.cfg.setProperty("height", dlgHeight + "px");
	}, ewrPopupFilterDialog, true);

}

// Create a popup filter
function ewr_CreatePopup(name, db) {
	ewrPopups[name] = db;
}

// Show popup filter
function ewr_ShowPopup(anchorname, popupname, useRange, rangeFrom, rangeTo) {

	// Popup configuration
	var cfg = { context: [anchorname, "tl", "bl"],
		buttons: [ { text:ewLanguage.Phrase("PopupOK"), handler:function(){
			if (!ewr_SelectedEntry(this.form, popupname)) {
				alert(ewLanguage.Phrase("PopupNoValue"));
			} else {
				this.form.submit();
				this.hide();
			}
		}, isDefault:true },
		{ text:ewLanguage.Phrase("PopupCancel"), handler:function(){this.cancel();} } ]
	};
	if (ewrEnv.ua.ie && ewrEnv.ua.ie >= 8)
		cfg["underlay"] = "none";

	// Load popup if not loaded yet
	if (!ewrPopups[popupname]) {

		// Show loading window
		var cfg2 = {
			visible: false, draggable: false, close: false,
			width: "100px", height: "50px", zIndex: 11000,
			constraintoviewport: true,
			buttons: []
		};

		var ct = [anchorname, "tl", "bl"]; // Show at "bl" (bottom left)
		ewrLoadingDialog.cfg.applyConfig(ewrLang.merge(cfg2, {context: ct}));
		ewrLoadingDialog.setHeader("");
		ewrLoadingDialog.setBody(ewrLoadingDialog._body);
		ewrLoadingDialog.render();
		ewrLoadingDialog.show();

		if (ewrPopupTimer)
			clearTimeout(ewrPopupTimer);

		// Ajax call to load popup
		var args = {"cfg": cfg, "anchorname": anchorname, "popupname": popupname, "useRange": useRange, "rangeFrom": rangeFrom, "rangeTo": rangeTo};
		ewrPopupCallback.argument = args;
		var data = "popup=" + popupname;
		var url = window.location.href.split("?")[0].split("#")[0] + "?" + data;
		ewrPopupTimer = setTimeout(function() {ewrConnect.asyncRequest('get', url, ewrPopupCallback, null);}, 200);

	} else {

		var db = ewrPopups[popupname];
		ewr_SetPopupContent(popupname, db, useRange, rangeFrom, rangeTo);
		ewrPopupFilterDialog.cfg.applyConfig(cfg);
		ewrPopupFilterDialog.render();
		ewrPopupFilterDialog.show();

	}
}

// Hide popup filter
function ewr_HidePopup(popupname) {
	ewrPopupFilterDialog.hide();
}

// Set popup fitler content
function ewr_SetPopupContent(name, db, useRange, rangeFrom, rangeTo) {
	var cnt = 0;
	db.forEach(function(record) {
		if (record["s"] === false)
			cnt++;
	});
	var selectall = (cnt == 0);
	var showdivider = false;
	var sb = new StringBuilder();
	var checkedall = selectall ? " checked=\"checked\"" : "";
	sb.append("<form id=\"" + name + "_FilterForm\" method=\"post\">");
	sb.append("<input type=\"hidden\" name=\"popup\" value=\"" + name + "\" />");
	sb.append("<table class=\"ewPopupContainer\">");
	sb.append("<tr><td>");
	if (useRange) {
		var selected;
		sb.append("<table class=\"ewPopupRange\">");
		sb.append("<tr><td>" + ewLanguage.Phrase("PopupFrom") + "</td><td>");
		sb.append("<select name=\"rf_" + name + "\" onchange=\"ewr_SelectRange(this.form, '" + name + "');\">");
		sb.append("<option value=\"\">" + ewLanguage.Phrase("PopupSelect") + "</option>");
		db.forEach(function(record, recordnumber) {
			var key = record["k"];
			var val = record["v"];
			if (key.substring(0,2)!="@@" && key!=EWR_NULL_VALUE && key!=EWR_EMPTY_VALUE) {
				selected = (key==rangeFrom) ? " selected=\"selected\"" : "";
				sb.append("<option value=\"" + key + "\"" + selected + ">" + val + "</option>");
			}
		});
		sb.append("</select></td></tr>");
		sb.append("<tr><td>" + ewLanguage.Phrase("PopupTo") + "</td><td>");
		sb.append("<select name=\"rt_" + name + "\" onchange=\"ewr_SelectRange(this.form, '" + name + "');\">");
		sb.append("<option value=\"\">" + ewLanguage.Phrase("PopupSelect") + "</option>");
		db.forEach(function(record, recordnumber) {
			var key = record["k"];
			var val = record["v"];
			if (key.substring(0,2)!="@@" && key!=EWR_NULL_VALUE && key!=EWR_EMPTY_VALUE) {
				selected = (key==rangeTo) ? " selected=\"selected\"" : "";
				sb.append("<option value=\"" + key + "\"" + selected + ">" + val + "</option>");
			}
		});
		sb.append("</select></td></tr></table>");
	}
	sb.append("<table class=\"ewPopupTable\"><tr><td>");
	sb.append("<input type=\"checkbox\" name=\"sel_" + name + "[]\" value=\"\" onclick=\"ewr_SelectAll(this);\"" + checkedall + " />" + ewLanguage.Phrase("PopupAll") + "<br />");
	db.forEach(function(record, recordnumber) {
		var key = record["k"];
		var val = record["v"];
		var checked = record["s"] ? " checked=\"checked\"" : "";
		if (key.substring(0,2)=="@@")
			showdivider = true;
		else if (showdivider) {
			showdivider = false; sb.append("<hr class=\"ewPopupHorizRule\" />");
		}
		sb.append("<input type=\"checkbox\" name=\"sel_" + name + "[]\" value=\"" + key + "\" onclick=\"ewr_UpdateSelectAll(this);\"" + checked + " />" + val + "<br />");
	});
	sb.append("</td></tr></table>");
	sb.append("</td></tr>");
	sb.append("</table>");
	sb.append("</form>");
	ewrPopupFilterDialog.setBody(sb.toString());
}

// Check if selected
function ewr_SelectedEntry(f, name) {
	var els = f.elements["sel_" + name + "[]"];
	if (!els)
		return false;
	return (els[0]) ? ewr_Matches(":not(:first):checked", els).length > 0 : els.checked;
}

// Select range
function ewr_SelectRange(f, name) {
	var rangeFrom, rangeTo;
	if (f.elements["rf_" + name].selectedIndex > -1)
		rangeFrom = f.elements["rf_" + name].options[f.elements["rf_" + name].selectedIndex].value;
	if (f.elements["rt_" + name].selectedIndex > -1)
		rangeTo = f.elements["rt_" + name].options[f.elements["rt_" + name].selectedIndex].value;
	if (!ewrLang.isValue(rangeFrom) || !ewrLang.isValue(rangeTo) || rangeFrom === "" || rangeTo === "")
		return;
	ewr_SetRange(f, "sel_" + name + "[]", rangeFrom, rangeTo, true);
}

// Clear range
function ewr_ClearRange(elem) {
	var f = elem.form;
	var name = elem.name.substring(4, elem.name.length - 2); // remove "sel_" and "[]"
	var from = f.elements["rf_" + name];
	var to = f.elements["rt_" + name];
	if (from && to && from.selectedIndex > 0 && to.selectedIndex > 0) {
		from.selectedIndex = 0;
		to.selectedIndex = 0;
		ewr_SetRange(f, elemname, from.options[from.selectedIndex].value, to.options[to.selectedIndex].value, false);
	}
}

// Set range
function ewr_SetRange(f, elemname, rangeFrom, rangeTo, set) {
	if (!f.elements[elemname])
		return;
	var inRange = false;
	ewr_Matches(":checkbox[name='" + elemname + "']", f.elements, function(el) {
		if (el.value == rangeFrom)
			inRange = true;
		if (inRange)
			el.checked = set;
		else
			if (set) el.checked = false;
		if (el.value == rangeTo)
			inRange = false;
	});
}

// Select all
function ewr_SelectAll(elem) {
	var f = elem.form;
	if (!f.elements[elem.name])
		return;
	ewr_ClearRange(elem); // clear any range set
	ewr_Matches(":checkbox[name='" + elem.name + "']:not(:first)", f.elements, function(el) { el.checked = elem.checked; });
}

// Update select all
function ewr_UpdateSelectAll(elem) {
	var f = elem.form;
	if (!f.elements[elem.name])
		return;
	ewr_ClearRange(elem); // clear any range set
	f.elements[elem.name][0].checked = ewr_Matches(":checkbox[name='" + elem.name + "']:not(:first):not(:checked)", f.elements).length == 0;
}

// String builder
function StringBuilder() {
	this.arr = [];
	this.itemCount = 0;
	// Append
	this.append = function(strValue) {
		this.arr[this.itemCount] = strValue;
		this.itemCount++;
	}
	// Concatenate
	this.toString = function() {
		return this.arr.join("");
	}
}

// Select elements by selector
// Pass in a selector and an optional context (if no context is provided the root "document" is used). Runs the specified selector and returns an array of matched DOMElements.
function ewr_Select(selector, context, fn) {
	var L = ewrLang, root = L.isString(context) ? ewrDom.get(context) : context;
	var els = ewrSelect(selector, root);
	if (L.isFunction(fn)) {
		els = ewrDom.batch(els, fn);
	} else if (L.isString(fn)) {
		els = ewrDom.batch(els, new Function(fn));
	}
	return els;
}

// Filter elements by selector
// Takes in a set of DOMElements, filters them against the specified selector, and returns the results. The selector can be a full selector (e.g. "div > span.foo") and not just a fragment.
function ewr_Matches(selector, set, fn) {
	var L = ewrLang, els = ewrSelect.matches(selector, set);
	if (L.isFunction(fn)) {
		els = ewrDom.batch(els, fn);
	} else if (L.isString(fn)) {
		els = ewrDom.batch(els, new Function(fn));
	}
	return els;
}

// ewr_Page class
// Page Object
function ewr_Page(name) {
	this.Name = name;
	this.PageID = "";

	// validate function
	this.ValidateRequired = true;
}

// Form class
function ewr_Form(formId) {	
	this.ID = formId; // Same ID as the form
	this.Form = document.getElementById(formId);
	
	// Search panel
	this.InitSearchPanel = false; // Expanded by default
	this.SearchPanel = formId + "_SearchPanel";
	this.SearchButton = formId + "_SearchImage";
	
	// Toggle search panel
	this.ToggleSearchPanel = function(expand) {
		var form = this.GetForm();
		var img = ewr_GetElement(this.SearchButton, form);
		var p = ewr_GetElement(this.SearchPanel, form);
		if (!p || !img)
			return;
		if (expand === true || expand !== false && p.style.display == "none") {
			p.style.display = "";
			if (ewr_SameText(img.tagName, "IMG"))
				img.src = EWR_IMAGES_FOLDER + "collapse.gif";
		} else {
			p.style.display = "none";
			if (ewr_SameText(img.tagName, "IMG"))
				img.src = EWR_IMAGES_FOLDER + "expand.gif";
		}
	}
	
	// Change search operator
	this.SrchOprChanged = function(el) {
		var form = this.GetForm();
		var elem = ewrLang.isString(el) ? form.elements[el] : el;
		if (!elem)
			return;
		var name = "";
		if (/^so_/.test(elem.id)) {
			name = elem.id.substr(3);
			form.elements["sv_" + name].disabled = ewr_InArray(elem.options[elem.selectedIndex].value, ["IS NULL", "IS NOT NULL"]) > -1;
		} else if (/^so2_/.test(elem.id)) {
			name = elem.id.substr(4);
			form.elements["sv2_" + name].disabled = ewr_InArray(elem.options[elem.selectedIndex].value, ["IS NULL", "IS NOT NULL"]) > -1;
		}
		var isBetween = (elem.options[elem.selectedIndex].value == "BETWEEN");
		ewr_Select("span.btw0_" + name, form, function(node) {
			node.style.display = (isBetween) ? "none" : "";
		});
		ewr_Select("span.btw1_" + name, form, function(node) {
			node.style.display = (isBetween) ? "" : "none";
			ewr_Select(":input", node, function(obj) {
				obj.disabled = !isBetween;
			});
		});
	}

	// Validate
	this.ValidateRequired = true;
	this.Validate = null;

	// Disable form
	this.DisableForm = function() {
		if (!EWR_DISABLE_BUTTON_ON_SUBMIT)
			return;
		ewr_Matches(":submit, :reset", this.Form.elements, function(el) {
			el.disabled = true;
		});	
	}
	
	// Enable form
	this.EnableForm = function() {
		if (!EWR_DISABLE_BUTTON_ON_SUBMIT)
			return;
		ewr_Matches(":submit, :reset", this.Form.elements, function(el) {
			el.disabled = false;
		});
	}
	
	// Submit
	this.Submit = function(action) {
		var form = this.GetForm();
		this.DisableForm();
		if (!this.Validate || this.Validate(form)) {			
			if (action)
				form.action = action;
			ewr_Matches("input[name^=s_],input[name^=sx_],input[name^=q_]", form.elements, function(el) {
				el.disabled = true; // Do not submit these values
			});
			form.submit();			
		}
		this.EnableForm();
		return false;
	}	

	// Dynamic selection lists
	this.Lists = {};

	// AutoSuggests
	this.AutoSuggests = {};

	// Get the HTML form object
	this.GetForm = function() {
		if (!this.Form) {			
			var el = document.getElementById(this.ID);
			if (el) {
				if (ewr_SameText(el.tagName, "FORM")) { // HTML form
					this.Form = el;
				}
			}
		}
		return this.Form;
	}

	// Get Auto-Suggest unmatched item (for form submission by pressing Return)
	this.PostAutoSuggest = function() {
		for (var i in this.AutoSuggests) {
			var o = this.AutoSuggests[i];
			if (o && o.ac && o.ac.isFocused && o.ac.isFocused()) {
				o.input.blur();
				break;
			}
		}
	}

	// Update dynamic selection lists
	this.UpdateOpts = function() {
		var L = ewrLang, lists = [];
		var form = this.GetForm();
		for (var id in this.Lists) {
			var parents = this.Lists[id].ParentFields.slice(0); // Clone
			var pvalues = [];
			for (var i = 0, len = parents.length; i < len; i++)
				pvalues[pvalues.length] = ewr_GetOptValues(parents[i], form); // Save the initial values of the parent lists
			lists[lists.length] = [id, pvalues, true, false];
		}

		// Update the Ajax lists
		for (var i = 0, cnt = lists.length; i < cnt; i++)
			ewr_UpdateOpt.apply(this, lists[i]);
	}
	
	// Init form
	this.Init = function() {
		var form = this.GetForm();
		if (!form)
			return;
		// Search panel
		if (this.InitSearchPanel && !ewr_HasFormData(form))
			this.ToggleSearchPanel();
		// Dynamic selection lists
		this.UpdateOpts();
		// Search operators
		ewr_Matches("select[id^=so_],select[id^=so2_]", form.elements, function(el) {
			if (el.onchange)
				el.onchange();
		});
	}

	// Add to the global forms object
	ewrForms[this.ID] = this;
}

// Check search form data
function ewr_HasFormData(form) {
	var els = ewr_Matches("[name^=sv_][value!=''][value!='{value}'],[name^=sv2_][value!=''][value!='{value}']", form.elements);
	for (var i = 0, len = els.length; i < len; i++) {
		var el = els[i];
		if (el.type == "checkbox" || el.type == "radio") {
			if (el.checked)
				return true;
		} else if (el.type == "select-one" || el.type == "select-multiple") {
			for (var j = 0, cnt = el.options.length; j < cnt; j++) {
				if (el.options[j].selected && el.options[j].value != "" && el.options[j].value != "##all##")
					return true;
			}
		} else if (el.type == "text" || el.type == "hidden" || el.type == "textarea") {
			return true;
		}
	}
	return false;
}

// Queue
function ewr_Queue() {
	var L = ewrLang;
	this.list = []; // Array of functions
	this.args = []; // Array of arguments

	// Add a function
	this.add = function(fn, args) {
		this.list[this.list.length] = fn;
		this.args[this.args.length] = args;
	}

	// Start
	this.start = function() {
		if (L.isFunction(this.onstart))
			this.onstart();
		this.next();
	}

	// Next
	this.next = function() {
		if (this.list.length == 0) {
			if (L.isFunction(this.onend))
				this.onend();
			return;
		}
		var fn = this.list.shift();
		var args = this.args.shift();
		if (L.isFunction(fn))
			fn(args);
	}
}

// Update a dynamic selection list
// obj {HTMLElement|array[HTMLElement]|string|array[string]} target HTML element(s) or the id of the element(s) 
// parentId {array[string]|array[array]} parent field element names or data
// change {boolean} trigger onchange event
function ewr_UpdateOpt(obj, parentId, async, change) {
	var L = ewrLang, self = this, args = [];
	var exit = function() {
		if (self._queue)
			self._queue.next();
	};
	if (!obj || obj.length == 0)
		return exit();
	var f = (this.Form) ? this.Form : (this.form) ? this.form : null;
	if (!f)
		return exit();
	var frm = (this.Form) ? this : ewrForms[f.id];
	if (!frm)
		return exit();
	for (var i = 0, len = arguments.length; i < len; i++) // Copy the arguments
		args[i] = arguments[i];
	if (this.form && L.isArray(obj) && L.isString(obj[0])) { // Array of id (onchange/onclick event)
		var queue = this._queue = new ewr_Queue();
		for (var i = 0, len = obj.length; i < len; i++) {
			args[0] = obj[i];
			queue.add(function(a){ewr_UpdateOpt.apply(self, a);}, args.slice(0));
		}
		return queue.start();
	}
	if (L.isString(obj))
		obj = ewr_GetElements(obj, f);
	var ar = ewr_GetOptValues(obj);
	var oid = ewr_GetId(obj, false);
	if (!oid)
		return exit();
	var nid = oid.replace(/^([xy])(\d*)_/, "x_");
	var prefix = RegExp.$1;
	var arp = [];
	if (L.isUndefined(parentId)) { // Parent IDs not specified, use default
		parentId = frm.Lists[nid].ParentFields.slice(0); // Clone
	}
	if (L.isArray(parentId) && parentId.length > 0) {
		if (L.isArray(parentId[0])) { // Array of array => data
			arp = parentId;
		} else if (L.isString(parentId[0])) { // Array of string => Parent IDs
			for (var i = 0, len = parentId.length; i < len; i++)
				arp[arp.length] = ewr_GetOptValues(parentId[i], f);
		}
	}
	if (!ewr_IsAutoSuggest(obj)) // Do not clear Auto-Suggest
		ewr_ClearOpt(obj);

	var addOpt = function(aResults) {
		var subscribed = ewrAddOptionEvent.subscribers.length;
		for (var i = 0, cnt = aResults.length; i < cnt; i++) {
			var valid = true;
			if (subscribed) { 
				var args = {data: aResults[i], parents: arp, valid: true, id: ewr_GetId(obj), form: f};
				ewrAddOptionEvent.fire(args);
				valid = args.valid;
			}
			if (valid)
				ewr_NewOpt(obj, aResults[i], f);
		}
		if (!obj.options && obj.length) { // Radio/Checkbox list
			ewr_RenderOpt(obj, f);
			obj = ewr_GetElements(oid, f); // Update the list
		}
		ewr_SelectOpt(obj, ar);
		if (change !== false) {
			if (L.isFunction(obj.onchange)) {
				obj.onchange();
			} else if (obj.length && obj.length > 0) { // Radio/Checkbox list
				var el = obj[0];
				if (L.isFunction(el.onclick))
					el.onclick();
			}
		}
	}

	var s = ewr_Select("#s_" + ewr_GetId(obj), f)[0];
	if (!s || s.value == "")
		return exit();
	var cb = {
		success: function(oResponse) {
			var aResults = ewr_ParseResponse(oResponse.responseText);
			addOpt(aResults || []);
			if (this._queue)
				this._queue.next();
		},
		failure: function(oResponse) {
			if (this._queue)
				this._queue.next();
		},
		scope: this, argument: null
	};
	var data = s.value;
	if (ewr_IsAutoSuggest(obj) && this.Form) // Auto-Suggest (init form or auto-fill)
		data += "&v0=" + encodeURIComponent(ar[0]); // Filter by the current value
	for (var i = 0, cnt = arp.length; i < cnt; i++) // Filter by parent fields
		data += "&v" + (i+1) + "=" + encodeURIComponent(arp[i].join(","));
	ewrConnect.asyncRequest("post", EWR_LOOKUP_FILE_NAME, cb, data);
}

// Clear existing options
function ewr_ClearOpt(obj) {
	if (obj.options) { // Selection list
		var lo = 1;
		for (var i = obj.length - 1; i >= lo; i--)
			if (obj.options[i].value.substr(0,2) != "@@") // Do not clear custom filter
				obj.options[i] = null;
	} else if (obj.length) { // Radio/Checkbox list
		if (!obj[0])
			return;
		var id = ewr_GetId(obj);
		var p = ewr_GetElement("dsl_" + id, obj[0].form);
		if (!p)
			return;
		p._options = [];
		var id = ewr_GetId(obj, false);
		var els = ewr_GetElements(id, p);
		for (var i = 0; i < els.length; i++) {
			var el = els[i];
			var val = el.value;
			if (val.substr(0,2) == "@@") { // Add custom filter to array
				var label = ewrDom.getAncestorBy(el, function(lbl) {return ewr_SameText(lbl.tagName, "LABEL");});
				var txt = label ? label.innerHTML.replace(/<[^>]*>/g, '') : val;
				ewr_NewOpt(obj, [val, txt], el.form);
			}
		}
		var tbl = ewr_Select("table." + EWR_ITEM_TABLE_CLASSNAME, p)[0];
		if (tbl)
			p.removeChild(tbl);
	} else if (ewr_IsAutoSuggest(obj)) {
		var o = ewr_GetAutoSuggest(obj);
		o._options = [];
		o.input.value = "";
		obj.value = "";
	}
}

// Get the id or name of an element
// remove {boolean} remove square brackets, default: true
function ewr_GetId(el, remove) {
	var id = "";
	if (ewrLang.isString(el)) {
		id = el;
	} else {
		if (!el.options && el.length)
			el = el[0];
		id = (el) ? ((el.id || el.name) ? (el.id || el.name) : "") : "";
	}
	if (remove !== false && id.substr(id.length-2, 2) == "[]")
		id = id.substr(0, id.length-2);
	return id;
}

// Parse responseText
function ewr_ParseResponse(txt, one) {
	var aResults;
	txt = txt.replace(/^\s*|\s*$/g, ""); // Trim
	if (txt.length > 0) {
		var newLength = txt.length - EWR_RECORD_DELIMITER.length;
		if (txt.substr(newLength) == EWR_RECORD_DELIMITER)
			txt = txt.substr(0, newLength);
		aResults = [];
		var aRecords = txt.split(EWR_RECORD_DELIMITER);
		for (var n = aRecords.length - 1; n >= 0; n--) {
			var record = aRecords[n];
			var newLength = record.length - EWR_FIELD_DELIMITER.length;
			if (record.substr(newLength) == EWR_FIELD_DELIMITER)
				record = record.substr(0, newLength);
			aResults[n] = record.split(EWR_FIELD_DELIMITER);
		}

		// Check if single row or single value
		if (one && aResults.length == 1) { // Single row
			aResults = aResults[0];
			if (ewrLang.isArray(aResults) && aResults.length == 1) { // Single column
				return aResults[0]; // Return a value
			} else {
				return aResults; // Return a row
			}
		}
	}
	return aResults;
}

// Get existing selected values as an array
function ewr_GetOptValues(el, form) {
	var obj = (ewrLang.isString(el)) ? ewr_GetElements(el, form) : el;
	if (obj.options) { // Selection list
		return ewr_Matches(":selected[value!='']", obj.options, function(opt) {
			return opt.value;
		});
	} else if (ewrLang.isNumber(obj.length)) { // Radio/Checkbox list, or element not found
		return ewr_Matches(":checked[value!='{value}']", obj, function(el) {
			return el.value;
		});
	} else { // text/hidden
		return [obj.value];
	}
}

// Get element as element or radio/checkbox list as array
function ewr_GetElements(name, root) {
	var ar = ewrDom.getElementsBy(function(node){
		return ewr_SameStr(node.id || node.name, name); // Exclude template element
	}, null, root);
	if (ar.length == 1) {
		var el = ar[0];
		if (el.type && el.type != "checkbox" && el.type != "radio") 
			return el;
	}
	return ar;
}

// Create combobox option
function ewr_NewOpt(obj, ar, f) {
	var args = {data: ar, id: ewr_GetId(obj), form: f};
	ewrNewOptionEvent.fire(args);
	ar = args.data;
	var value = ar[0];
	var text = ar[1];
	for (var i = 2; i <= 4; i++) {
		if (ar[i] && ar[i] != "") {
			if (text != "")
				text += ewr_ValueSeparator(i-1, obj);
			text += ar[i];
		}
	}
	if (obj.options) { // Selection list
		obj.options[obj.length] = new Option(text, value, false, false);
	} else if (obj.length) { // Radio/Checkbox list
		var p = ewr_GetElement("dsl_" + ewr_GetId(obj), f); // Parent element
		if (p && p._options)
			p._options[p._options.length] = {val:value, lbl:text};
	} else if (ewr_IsAutoSuggest(obj)) { // Auto-Suggest
		var o = ewr_GetAutoSuggest(obj);
		o._options[o._options.length] = {val:value, lbl:text};
	}
	return text;
}

// Render the options
function ewr_RenderOpt(obj, f) {
	var id = ewr_GetId(obj); 
	var p = ewr_GetElement("dsl_" + id, f); // Parent element	
	if (!p || !p._options)
		return;
	var t = ewr_GetElement("tp_" + id, f); 	
	if (!t)
		return;
	var cols = parseInt(p.getAttribute("data-repeatcolumn"));
	if (isNaN(cols) || cols < 1)
		cols = 5;
	var tpl = t.innerHTML;		 
	var html = "";
	var ihtml;
	for (var i = 0, cnt = p._options.length; i < cnt; i++) {
		html += ewr_RepeatColumnTable(cnt, i, cols, 1);
		ihtml = tpl;
		ihtml = ihtml.replace(/\"?{value}\"?/g, "\"" + ewr_HtmlEncode(p._options[i].val) + "\""); // Replace value		
		html += "<label>" + ihtml + p._options[i].lbl + "</label>";		
		html += ewr_RepeatColumnTable(cnt, i, cols, 2);		
	} 
	p.innerHTML += html;
	p._options = [];		
}

// Render repeat column table (rowcnt is zero based row count)
function ewr_RepeatColumnTable(totcnt, rowcnt, repeatcnt, rendertype) {
	var sWrk = "";
	if (rendertype == 1) { // Render start
		if (rowcnt == 0)
			sWrk += "<table class=\"" + EWR_ITEM_TABLE_CLASSNAME + "\">";
		if (rowcnt % repeatcnt == 0)
			sWrk += "<tr>";
		sWrk += "<td>";
	} else if (rendertype == 2) { // Render end
		sWrk += "</td>";
		if (rowcnt % repeatcnt == repeatcnt - 1) {
			sWrk += "</tr>";
		} else if (rowcnt == totcnt - 1) {
			for (i = (rowcnt % repeatcnt) + 1; i < repeatcnt; i++)
				sWrk += "<td>&nbsp;</td>";
			sWrk += "</tr>";
		}
		if (rowcnt == totcnt - 1) sWrk += "</table>";
	}
	return sWrk;
}

// Get display value separator
function ewr_ValueSeparator(index, obj) {
	return ", ";
}

// Select combobox option
function ewr_SelectOpt(obj, value_array) {
	if (!obj || !value_array)
		return;
	if (obj.options) { // Selection List
		ewr_Matches("*", obj.options, function(opt) { // ewrDom.batch(obj.options, fn) does not work with IE
			opt.selected = (ewr_InArray(opt.value, value_array) > -1);
		});
	} else if (obj.length) { // Radio/Checkbox list
		if (obj.length == 1 && obj[0].type == "checkbox" && obj[0].value != "{value}") { // Assume boolean field // P802
			obj[0].checked = (ewr_ConvertToBool(obj[0].value) === ewr_ConvertToBool(value_array[0]));
		} else {
			ewrDom.batch(obj, function(el) {
				el.checked = (ewr_InArray(el.value, value_array) > -1);
			});
		}

//	} else if (obj.type == "hidden") {
//		var asEl = ew_GetElement("sv_" + obj.id, obj.form);
//		if (asEl && asEl.type == "text") {
//			obj.value = value_array.join(",");
//			asEl.value = value_array.join(",");
//		}

	} else if (ewr_IsAutoSuggest(obj) && value_array.length == 1) {
		var o = ewr_GetAutoSuggest(obj);
		for (var i = 0, len = o._options.length; i < len; i++) {
			if (o._options[i].val == value_array[0]) {
				obj.value = o._options[i].val;
				o.input.value = o._options[i].lbl;
				break;
			}
		}

	} else if (obj.type) {
		obj.value = value_array.join(",");
	}

}

// Auto-Suggest
function ewr_AutoSuggest(elValue, frm, forceSelection, maxEntries) {
	var nid = elValue;
	var oEmpty = {ac:{},ds:{}}; // Empty Auto-Suggest object
	var form = frm.GetForm(); 
	var elInput = ewr_GetElement("sx_" + elValue, form);
	if (!elInput)
		return oEmpty;
	var elContainer = ewr_GetElement("sc_" + elValue, form);
	var elSQL = ewr_GetElement("q_" + elValue, form);
	var elMessage = ewr_GetElement("em_" + elValue, form);	
	var elParent = frm.Lists[nid].ParentFields.slice(0); // Clone
	this.input = elInput;
	this.element = ewr_GetElement(elValue, form);
	this._options = [];

	// Create DataSource
	this.ds = new ewrUtil.XHRDataSource(EWR_LOOKUP_FILE_NAME);
	this.ds.responseType = ewrUtil.XHRDataSource.TYPE_TEXT;
	this.ds.responseSchema = {recordDelim: EWR_RECORD_DELIMITER, fieldDelim: EWR_FIELD_DELIMITER};
	this.ds.maxCacheEntries = 0; // DO NOT CHANGE!		
	this.ds.connMethodPost = true; 

	// Create AutoComplete
	this.ac = new ewrWidget.AutoComplete(elInput, elContainer, this.ds);
	this.ac._as = this;
	this.ac.useShadow = false;
	this.ac.animVert = false;
	this.ac.minQueryLength = 1;
	this.ac.maxResultsDisplayed = maxEntries;
	this.ac.typeAhead = false; // 902
	this.ac.forceSelection = forceSelection;
	this.ac.useIFrame = (ewrEnv.ua.ie > 0 && ewrEnv.ua.ie < 8);

	// Override _focus method
	this.ac._focus = function() {
		var oSelf = this;
		setTimeout(function() {
			try {
				oSelf._elTextbox.focus();
			}	catch(e) {}
		}, 500); // Increase the delay time
	};	

	// Do before expand container
	this.ac.doBeforeExpandContainer = function(oTextbox, oContainer, sQuery, aResults) {
		var pos = ewrDom.getXY(oTextbox);
		pos[1] += ewrDom.get(oTextbox).offsetHeight + 1;
		ewrDom.setXY(oContainer, pos);
		oContainer.style.width = ewrDom.get(oTextbox).offsetWidth + "px"; // Set container width
		return true;
	};

	// Format display value (Note: Override this function if link field <> display field)
	this.formatResult = function(ar) {
		return ar[0];
	};

	// Set the key to the actual value field
	this.setValue = function(v) {
		var el = this.element;
		el.value = v;
		if (el.onchange)					
			el.onchange();
	};

	// Format result
	this.ac.formatResult = function(oResultItem, sQuery) {	

		//var key = oResultItem[0];
		var lbl = this._as.formatResult(oResultItem);

		//oResultItem[0] = lbl;		
		//oResultItem.push(key); // Save the key to last

		return lbl;		
	};

	// Generate request
	this.ac.generateRequest = function(sQuery) {
		var data = elSQL.value;
		if (elParent.length > 0) {
			for (var i = 0, len = elParent.length; i < len; i++) {
				var arp = ewr_GetOptValues(elParent[i], form);
				data += "&v" + (i+1) + "=" + encodeURIComponent(arp.join(","));
			}
		}
		return "q=" + sQuery + "&" + data; 
	};

	// Item selected
	this.itemSelect = function(ar) {
		this.setValue(ar[0]);
		this.input.value = this.formatResult(ar);
	}

	// Update the key to the actual value field
	this.ac.itemSelectEvent.subscribe(function(type, e) {
		this._as.itemSelect(e[2]);
	}); 

	// Remove styles for unmatched item
	this.ac.textboxFocusEvent.subscribe(function(type, e) {
		ewrDom.removeClass(elInput, "ewUnmatched");
		ewrDom.setStyle(elMessage, "display", "none");
	});

	// Clear the actual value field
	if (forceSelection) {
		this.ac.selectionEnforceEvent.subscribe(function(type, e) {
			this._as.setValue("");
			if (e[1] == "")
				return;
			ewrDom.addClass(elInput, "ewUnmatched");
			ewrDom.setStyle(elMessage, "display", "");
		});	
	} else {
		this.ac.unmatchedItemSelectEvent.subscribe(function(type, e) {
			this._as.setValue(this._elTextbox.value);	
		});
	}
}

// Get first element only
function ewr_GetElement(name, root) {
	return ewrDom.getElementBy(function(node){
		return ewr_SameStr(node.id || node.name, name);
	}, null, root);
}

// Check if same text
function ewr_SameText(o1, o2) {
	return (String(o1).toLowerCase() == String(o2).toLowerCase());
}

// Check if same string
function ewr_SameStr(o1, o2) {
	return (String(o1) == String(o2));
}

// Check if an element is in array
function ewr_InArray(el, ar) {
	if (!ar)
		return -1;	
	for (var i = 0, len = ar.length; i < len; i++) {
		if (ewr_SameStr(ar[i], el))
			return i;
	}		
	return -1;
}

// Long form element name
function ewr_ConcatId(formid, id) {
	return formid + "$" + id + "$";
}

// Submit language form
function ewr_SubmitLanguageForm(f) {
	if (!f || !f.language || !f.language.value)
		return;
	var url = window.location.href;
	if (window.location.search) {
		var query = window.location.search;
		var param = {};			
		query.replace(/(?:\?|&)([^&=]*)=?([^&]*)/g, function ($0, $1, $2) {
			if ($1)
				param[$1] = $2;
		});
		param["language"] = encodeURIComponent(f.language.value);
		var q = "?";
		for (var i in param)
			q += i + "=" + param[i] + "&";
		q = q.substr(0, q.length-1);
		var p = url.lastIndexOf(window.location.search);
		url = url.substr(0, p) + q;			
	} else {
		url += "?language=" + encodeURIComponent(f.language.value);
	}
	window.location = url;
}

// Get Ctrl key for multiple column sort
function ewr_Sort(e, url, type) {
	var newUrl = url;
	if (type == 2 && e.ctrlKey)
		newUrl += "&ctrl=1";
	location = newUrl;
	return true;
}

// Check if hidden textbox (Auto-Suggest)
function ewr_IsAutoSuggest(el) {
	return (el && el.type && el.type == "hidden" &&
		el.form && el.id && el.id in ewrForms[el.form.id].AutoSuggests);
}

// Get AutoSuggest instance
function ewr_GetAutoSuggest(el) {
	return ewrForms[el.form.id].AutoSuggests[el.id];
}

// Set focus
function ewr_SetFocus(obj) {
	if (!obj)
		return;
	if (!obj.options && obj.length) { // Radio/Checkbox list 	
		obj = ewr_Matches("[value!='{value}']", obj)[0];
	} else if (ewr_IsAutoSuggest(obj)) { // Auto-Suggest
		obj = ewr_GetAutoSuggest(obj).input; 
		}
	if (obj.focus)
		obj.focus();
	if (obj.select)
		obj.select();
}

function ewr_OnError(input_object, error_message) {
	alert(error_message);
	ewr_SetFocus(input_object);
	return false;	
}

// Check if object has value
function ewr_HasValue(obj) {
	return ewr_GetOptValues(obj).join("") != "";
}

// Encode html
function ewr_HtmlEncode(text) {
	var str = text;
	str = str.replace(/&/g, '&amp');
	str = str.replace(/\"/g, '&quot;');
	str = str.replace(/</g, '&lt;');
	str = str.replace(/>/g, '&gt;'); 
	return str;
}

// Extended basic search clear form
function ewr_ClearForm(objForm){
	with (objForm) {
		for (var i=0; i<elements.length; i++){
			var tmpObj = eval(elements[i]);
			if (tmpObj.type == "checkbox" || tmpObj.type == "radio"){
				tmpObj.checked = false;
			} else if (tmpObj.type == "select-one"){
				tmpObj.selectedIndex = 0;
			} else if (tmpObj.type == "select-multiple") {
				for (var j=0; j<tmpObj.options.length; j++)
					tmpObj.options[j].selected = false;
			} else if (tmpObj.type == "text"){
				tmpObj.value = "";
			}
		}
	}
}

// Setup table
function ewr_SetupTable(tbl, force) {
	if (!tbl || !tbl.rows || !force && tbl.isset)
		return;
	if (ewrEnv.ua.ie < 8)
		tbl.cellSpacing = "0";
	var r, cnt = tbl.rows.length;
	for (var i = 0; i < cnt; i++) {
		r = tbl.rows[i];
		r.cells[r.cells.length-1].style.borderRight = "0"; // last column
	}
	if (cnt > 0) {
		r = tbl.rows[tbl.rows.length-1]; // last row
		cnt = r.cells.length;
		for (var i = 0; i < cnt; i++)
			r.cells[i].style.borderBottom = "0";
	}
	ewr_SetupGrid(ewrDom.getAncestorByClassName(tbl, EWR_GRID_CLASSNAME), force);
	tbl.isset = true;
}

// Setup grid
function ewr_SetupGrid(grid, force) {
	if (!grid || !force && grid.isset)
		return;
	var rowcnt = ewr_Select("table." + EWR_TABLE_CLASSNAME + ":first tr", grid).length;
	var divupper = ewr_Select("div.ewGridUpperPanel", grid)[0];
	var divmiddle = ewr_Select("div.ewGridMiddlePanel", grid)[0];
	var divlower = ewr_Select("div.ewGridLowerPanel", grid)[0];
	if (divupper && divlower) {
		if (rowcnt == 0) {
			ewrDom.addClass(divlower, "ewDisplayNone");
			ewrDom.addClass(divupper, "ewNoBorderBottom");
		} else {
			ewrDom.removeClass(divlower, "ewDisplayNone");
			ewrDom.removeClass(divupper, "ewNoBorderBottom");
		}
	} else if (divupper && !divlower) {
		if (rowcnt == 0) {
			ewrDom.addClass(divupper, "ewNoBorderBottom");
		} else {
			ewrDom.removeClass(divupper, "ewNoBorderBottom");
		}
	} else if (divlower && !divupper) {
		if (rowcnt == 0) {
			ewrDom.addClass(divlower, "ewNoBorderTop");
		} else {
			ewrDom.removeClass(divlower, "ewNoBorderTop");
		}
	}
	grid.isset = true;
}

// Init email dialog
function ewr_InitEmailDialog() {
	ewrEmailDialog = new ewrWidget.Dialog("ewrEmailDialog", { visible: false, constraintoviewport: true, hideaftersubmit: false, zIndex: 10000 });
	if (ewrEmailDialog.body) {
		ewrEmailDialog._body = ewrEmailDialog.body.innerHTML;
		ewrEmailDialog.setBody("");
	}
	ewrEmailDialog.validate = function() {
		var elm;
		var fobj = this.form;
		elm = fobj.elements["sender"];
		if (elm && !ewr_HasValue(elm))
			return ewr_OnError(elm, ewLanguage.Phrase("EnterSenderEmail"));
		if (elm && !ewr_CheckEmailList(elm.value, 1))
			return ewr_OnError(elm, ewLanguage.Phrase("EnterProperSenderEmail"));
		elm = fobj.elements["recipient"];
		if (elm && !ewr_HasValue(elm))
			return ewr_OnError(elm, ewLanguage.Phrase("EnterRecipientEmail"));
		if (elm && !ewr_CheckEmailList(elm.value, EWR_MAX_EMAIL_RECIPIENT))
			return ewr_OnError(elm, ewLanguage.Phrase("EnterProperRecipientEmail"));
		elm = fobj.elements["cc"];
		if (elm && !ewr_CheckEmailList(elm.value, EWR_MAX_EMAIL_RECIPIENT))
			return ewr_OnError(elm, ewLanguage.Phrase("EnterProperCcEmail"));
		elm = fobj.elements["bcc"];
		if (elm && !ewr_CheckEmailList(elm.value, EWR_MAX_EMAIL_RECIPIENT))
			return ewr_OnError(elm, ewLanguage.Phrase("EnterProperBccEmail"));
		elm = fobj.elements["subject"];
		if (elm && !ewr_HasValue(elm))
			return ewr_OnError(elm, ewLanguage.Phrase("EnterSubject"));
		return true;
	};
	ewrEmailDialog.render();
}

// Show dialog for email sending
// argument object members:
// lnk - email link id
// hdr - dialog header
// url - export url
// exportid - export id
// el - element
function ewr_EmailDialogShow(oArg) {

	if (ewrExporting)
		return;

	if (!ewrEmailDialog)
		return;
	if (ewrEmailDialog.cfg.getProperty("visible"))
		ewrEmailDialog.hide();

	var cfg = { context: [oArg.lnk, "tl", "bl"], postmethod: "form",
		buttons: [ { text:ewLanguage.Phrase("SendEmailBtn"), handler:ewr_EmailDialogSubmit, isDefault:true },
			{ text:ewLanguage.Phrase("CancelBtn"), handler:ewr_DefaultHandleCancel } ]
	};

	if (ewrEnv.ua.ie && ewrEnv.ua.ie >= 8)
		cfg["underlay"] = "none";
	ewrEmailDialog.cfg.applyConfig(cfg);
	ewrEmailDialog.callback.argument = oArg;
	if (ewrEmailDialog.header) ewrEmailDialog.header.style.width = "auto";
	if (ewrEmailDialog.body) ewrEmailDialog.body.style.width = "auto";
	if (ewrEmailDialog.footer) ewrEmailDialog.footer.style.width = "auto";
	ewrEmailDialog.setHeader(oArg.hdr);
	ewrEmailDialog.setBody(ewrEmailDialog._body);
	ewrEmailDialog.render();
	ewrEmailDialog.registerForm(); // make sure the form is registered (otherwise, the form is not registered in the first time)

  //alert(ewrEmailDialog.form.innerHTML);
	ewrEmailDialog.show();

}

function ewr_EmailDialogSubmit() {
	var url = this.callback.argument.url;
	var exportid = this.callback.argument.exportid;
	var exporttype = ewr_GetOptValues("contenttype", this.form);
	if (url && exportid && exporttype == "html") {
		var valid = (this.validate) ? this.validate() : true;
		if (valid) {
			this.hide();
			var el = this.callback.argument.el;
			ewr_ExportCharts(el, url, exportid, this.form);
		}
	} else {
		this.submit();
	}
}

function ewr_DefaultHandleSubmit() {
	this.submit();
}

function ewr_DefaultHandleCancel() {
	this.cancel();
	this.setBody("");
}

var ewrDrillDownCallback = {
	cache: false,
	// Show results in div
	success: function(o) {
		if (ewrLoadingDialog)
			ewrLoadingDialog.hide();
		if (ewrDrillDownPanel && o.responseText) {
			if (o.argument.cfg)
				ewrDrillDownPanel.cfg.applyConfig(o.argument.cfg);
			if (o.argument.hdr)
				ewrDrillDownPanel.setHeader(o.argument.hdr);
			var html = ewr_StripScript(o.responseText);
			var reb = /<body[^>]*>([\s\S]*?)<\/body\s*>/i;
			if (html.match(reb)) // Insert HTML in document body only (for IE)
				html = RegExp.$1;
			ewrDrillDownPanel.setBody(ewr_StripScript(html));
			if (ewrEnv.ua.mobile) { // If mobile, insert the container table only
				var container = ewr_Select("#ewContainer", ewrDrillDownPanel.body)[0];
				if (container) {
					ewrDrillDownPanel.setBody("");
					ewrDrillDownPanel.body.appendChild(container);
				}
			}
			ewrDrillDownPanel.render();
			ewr_SetupTable(ewr_Select(".ewTable", ewrDrillDownPanel.body)[0]);
			ewrDrillDownPanel.show();
			ewr_ExecScript(o.responseText, "ewrDrillDownDiv");
		}
	},
	// Show error in div
	failure: function(o) {
		if (ewrLoadingDialog)
			ewrLoadingDialog.hide();
		if (ewrDrillDownPanel && o.responseText) {
			if (o.argument.cfg)
				ewrDrillDownPanel.cfg.applyConfig(o.argument.cfg);
			if (o.argument.hdr)
				ewrDrillDownPanel.setHeader(o.argument.hdr);
			ewrDrillDownPanel.setBody(o.responseText);
			ewrDrillDownPanel.render();
			ewrDrillDownPanel.show();
		}
	},
	// Argument
	argument: {}
}

// Custom drill down event
var ewrDrillDownEvent = new ewrUtil.CustomEvent("DrillDown"); // args[0] is the arguments for the drill down panel

var ewrLoadingConfig = {
	visible: false,
	draggable: true,
	close: false,
	width: "250px",
	height: "75px",
	zIndex: 11000,
	constraintoviewport: true,
	buttons: []
};

// Init loading dialogs/panels
function ewr_InitLoadingPanels() {

	//
	// Init loading dialog
	//

	ewrLoadingDialog = new ewrWidget.Dialog("ewrLoadingDiv", ewrLoadingConfig);
	ewrLoadingDialog._body = "<div class='ewrLoading'><img src='" + EWR_IMAGES_FOLDER + "loading.gif' width='18' height='18' alt=''  style='border: 0;' /></div>";

	//
	// Init drilldown panel
	//

	var id = "ewrDrillDownDiv";

	// Create a Panel instance from the DIV standard module markup
	ewrDrillDownPanel = new ewrWidget.Panel(id, {
		visible: false,
		draggable: true,
		width: "600px",
		height: "500px",
		zIndex: 11000,
		autofillheight: "body",
		constraintoviewport: !ewrEnv.ua.mobile || ewrEnv.ua.ipad
	});
	
	// Create Resize instance and bind it to the DIV
	var resize = new ewrUtil.Resize(id, {
		handles: ["br"],
		autoRatio: false,
		minWidth: 300,
		minHeight: 100,
		status: false
	});
	
	// Setup startResize handler to constrain the resize width/height
	resize.on("startResize", function(args) {
		if (this.cfg.getProperty("constraintoviewport")) {
			var D = ewrUtil.Dom;
			var clientRegion = D.getClientRegion();
			var elRegion = D.getRegion(this.element);
			resize.set("maxWidth", clientRegion.right - elRegion.left - ewrWidget.Overlay.VIEWPORT_OFFSET);
			resize.set("maxHeight", clientRegion.bottom - elRegion.top - ewrWidget.Overlay.VIEWPORT_OFFSET);
		} else {
			resize.set("maxWidth", null);
			resize.set("maxHeight", null);
		}
	}, ewrDrillDownPanel, true);
	
	// Setup resize handler to update the panel's "height" configuration property
	resize.on("resize", function(args) {
		var panelHeight = args.height;
		this.cfg.setProperty("height", panelHeight + "px");
	}, ewrDrillDownPanel, true);
	
	// Subscribe event to remove scripts on close
	ewrDrillDownPanel.hideEvent.subscribe(function(type, args) {
		// Dispose charts
		for (var i = 0; i < ewrDrillCharts.length; i++) {
			var cht = FusionCharts(ewrDrillCharts[i]);
			cht.dispose();
		}
		this.setBody("");
		ewrDrillCharts = [];
		ewr_RemoveScript(id);
	});

}

// Show drill down
function ewr_ShowDrillDown(e, obj, url, id, hdr) {
	if (ewrDrillDownTimer)
		clearTimeout(ewrDrillDownTimer);
	if (e ? e.ctrlKey : window._ctrlKey) {
		ewr_Redirect(url.replace("?d=1&", "?d=2&")); // Change d parameter to 2
		return false;
	}
	var ct = [obj, "tl", "bl"]; // Show at "bl" (bottom left)
	if (ewrLang.isString(obj)) { // Chart
		obj = ewrDom.get(obj);
		ct = [obj, "tl", "tr"]; // Show at "tr" (top right)
	}
	if (ewrLoadingDialog) {
		ewrLoadingDialog.cfg.applyConfig(ewrLang.merge(ewrLoadingConfig, {context: ct}));
		ewrLoadingDialog.setHeader(ewLanguage.Phrase("Loading").replace("%s", (hdr) ? " " + hdr : ""));
		ewrLoadingDialog.setBody(ewrLoadingDialog._body);
		ewrLoadingDialog.render();
		ewrLoadingDialog.show();
	}
	var cfg = {width: "600px", height: "520px", autofillheight: "body", context: ct};
	var args = {"obj": obj, "id": id, "url": url, "hdr": hdr, "cfg": cfg};
	ewrDrillDownEvent.fire(args);
	var ar = args.url.split("?");
	var file = (ar[0]) ? ar[0] : "";
	var data = (ar[1]) ? ar[1] : "";
	ewrDrillDownCallback.argument = args;
	ewrDrillDownTimer = setTimeout(function() {ewrConnect.asyncRequest('post', file, ewrDrillDownCallback, data);}, 10);
}

// Execute JavaScript in HTML loaded by Ajax
function ewr_ExecScript(html, id) {
	var ar, i = 0, re = /<script([^>]*)>([\s\S]*?)<\/script\s*>/ig;
	while ((ar = re.exec(html)) != null) {
		var text = RegExp.$2;
		if (text != "" && /(\s+type\s*=\s*['"]*(text|application)\/(java|ecma)script['"]*)|^((?!\s+type\s*=).)*$/i.test(RegExp.$1))
			ewr_AddScript(text, "scr_" + id + i++);
	}
}

// Strip JavaScript in HTML loaded by Ajax
function ewr_StripScript(html) {
	var ar, re = /<script([^>]*)>([\s\S]*?)<\/script\s*>/ig;
	var str = html;
	while ((ar = re.exec(html)) != null) {
		var text = RegExp.lastMatch;
		if (/(\s+type\s*=\s*['"]*(text|application)\/(java|ecma)script['"]*)|^((?!\s+type\s*=).)*$/i.test(RegExp.$1))
			str = str.replace(text, "");
	}
	return str;
}

// Add SCRIPT tag
function ewr_AddScript(text, id) {
	var scr = document.createElement("SCRIPT");
	if (id)
		scr.id = id;
	scr.type = "text/javascript";
	scr.text = text;
	return document.body.appendChild(scr);
}

// Remove JavaScript added by Ajax
function ewr_RemoveScript(id) {
	if (!id)
		return;
	var els = ewr_Select("script[id^=scr_" + id + "_]");
	for (var i = els.length - 1; i >= 0; i--)
		els[i].parentNode.removeChild(els[i]);
}

// ewr_Language class
function ewr_Language(obj) {
	this.obj = obj;
	this.Phrase = function(id) {
		return this.obj[id.toLowerCase()];
	};
}

// Include another client script
function ewr_ClientScriptInclude(path, opts) {
	ewrGet.script(path, opts);
}

// Show message dialog
function ewr_ShowMessage(msg, cfg) {
	var div = ewr_Select("div.ewMessageDialog", document)[0];
	var html = msg || ((div) ? div.innerHTML : "");
	if (html.replace(/^\s*|\s*$/g, "") == "")
		return;
	var o = {width: "500px", fixedcenter: true, visible: false, draggable: false,
			modal: true, close: false, constraintoviewport: true, zIndex: 10000,
			text: html,
			buttons: [{text: "&nbsp;&nbsp;&nbsp;" + ewLanguage.Phrase("MessageOK") + "&nbsp;&nbsp;&nbsp;",
				handler: function(){this.hide();this.destroy();}}]};
	var dlg = new ewrWidget.SimpleDialog("ewMessageDialog", ewrLang.isObject(cfg) ? ewrLang.merge(o, cfg) : o);
	dlg.render(document.body);
	dlg.show();
}

// Validators

// Check US Date format (mm/dd/yyyy)
function ewr_CheckUSDate(object_value) {
	return ewr_CheckDateEx(object_value, "us", EWR_DATE_SEPARATOR);
}

// Check US Date format (mm/dd/yy)
function ewr_CheckShortUSDate(object_value) {
	return ewr_CheckDateEx(object_value, "usshort", EWR_DATE_SEPARATOR);
}

// Check Date format (yyyy/mm/dd)
function ewr_CheckDate(object_value) {
	return ewr_CheckDateEx(object_value, "std", EWR_DATE_SEPARATOR);
}

// Check Date format (yy/mm/dd)
function ewr_CheckShortDate(object_value) {
	return ewr_CheckDateEx(object_value, "stdshort", EWR_DATE_SEPARATOR);
}

// Check Euro Date format (dd/mm/yyyy)
function ewr_CheckEuroDate(object_value) {
	return ewr_CheckDateEx(object_value, "euro", EWR_DATE_SEPARATOR);
}

// Check Euro Date format (dd/mm/yy)
function ewr_CheckShortEuroDate(object_value) {
	return ewr_CheckDateEx(object_value, "euroshort", EWR_DATE_SEPARATOR);
}

// Check date format
// format: std/stdshort/us/usshort/euro/euroshort
function ewr_CheckDateEx(value, format, sep) {
	if (value == null || value.length == "")
		return true;
	while (value.indexOf("  ") > -1)
		value = value.replace(/  /g, " ");
	value = value.replace(/^\s*|\s*$/g, "");
	var arDT = value.split(" ");
	if (arDT.length > 0) {
		var re, sYear, sMonth, sDay;
		re = /^([0-9]{4})-([0][1-9]|[1][0-2])-([0][1-9]|[1|2][0-9]|[3][0|1])$/;
		if (ar = re.exec(arDT[0])) {
			sYear = ar[1];
			sMonth = ar[2];
			sDay = ar[3];
		} else {
			var wrksep = "\\" + sep;
			switch (format) {
				case "std":
					re = new RegExp("^(\\d{4})" + wrksep + "([0]?[1-9]|[1][0-2])" + wrksep + "([0]?[1-9]|[1|2]\\d|[3][0|1])$");
					break;
				case "stdshort":
					re = new RegExp("^(\\d{2})" + wrksep + "([0]?[1-9]|[1][0-2])" + wrksep + "([0]?[1-9]|[1|2]\\d|[3][0|1])$");
					break;
				case "us":
					re = new RegExp("^([0]?[1-9]|[1][0-2])" + wrksep + "([0]?[1-9]|[1|2]\\d|[3][0|1])" + wrksep + "(\\d{4})$");
					break;
				case "usshort":
					re = new RegExp("^([0]?[1-9]|[1][0-2])" + wrksep + "([0]?[1-9]|[1|2]\\d|[3][0|1])" + wrksep + "(\\d{2})$");
					break;
				case "euro":
					re = new RegExp("^([0]?[1-9]|[1|2]\\d|[3][0|1])" + wrksep + "([0]?[1-9]|[1][0-2])" + wrksep + "(\\d{4})$");
					break;
				case "euroshort":
					re = new RegExp("^([0]?[1-9]|[1|2]\\d|[3][0|1])" + wrksep + "([0]?[1-9]|[1][0-2])" + wrksep + "(\\d{2})$");
					break;
			}
			if (!re.test(arDT[0]))
				return false;
			var arD = arDT[0].split(sep);
			switch (format) {
				case "std":
				case "stdshort":
					sYear = ewr_UnformatYear(arD[0]);
					sMonth = arD[1];
					sDay = arD[2];
					break;
				case "us":
				case "usshort":
					sYear = ewr_UnformatYear(arD[2]);
					sMonth = arD[0];
					sDay = arD[1];
					break;
				case "euro":
				case "euroshort":
					sYear = ewr_UnformatYear(arD[2]);
					sMonth = arD[1];
					sDay = arD[0];
					break;
			}
		}
		if (!ewr_CheckDay(sYear, sMonth, sDay))
			return false;
	}
	if (arDT.length > 1 && !ewr_CheckTime(arDT[1]))
		return false;
	return true;
}

// Unformat 2 digit year to 4 digit year
function ewr_UnformatYear(yr) {
	if (yr.length == 2)
		return (yr > EWR_UNFORMAT_YEAR) ? "19" + yr : "20" + yr;
	return yr;
}

// Check day
function ewr_CheckDay(checkYear, checkMonth, checkDay) {
	var maxDay = 31;
	if (ewr_InArray(checkMonth, [4, 6, 9, 11]) > -1) {
		maxDay = 30;
	} else if (checkMonth == 2)	{
		if (checkYear % 4 > 0)
			maxDay = 28;
		else if (checkYear % 100 == 0 && checkYear % 400 > 0)
			maxDay = 28;
		else
			maxDay = 29;
	}
	return ewr_CheckRange(checkDay, 1, maxDay);
}

// Check integer
function ewr_CheckInteger(object_value) {
	if (!object_value || object_value.length == 0)
		return true;
	if (object_value.indexOf(EWR_DECIMAL_POINT) > -1)
		return false;
	return ewr_CheckNumber(object_value);
}

// Check number
function ewr_CheckNumber(object_value) {
	if (!object_value || object_value.length == 0)
		return true;
	object_value = object_value.replace(/^\s*|\s*$/g, "");
	var re = new RegExp("^[+-]?(\\d{1,3}(" + ((EWR_THOUSANDS_SEP) ? "\\" + EWR_THOUSANDS_SEP + "?" : "") + "\\d{3})*(\\" +
		EWR_DECIMAL_POINT + "\\d+)?|\\" + EWR_DECIMAL_POINT + "\\d+)$");
	return re.test(object_value);
}

// Convert to float
function ewr_StrToFloat(object_value) {
	if (EWR_THOUSANDS_SEP != "") {
		var re = new RegExp("\\" + EWR_THOUSANDS_SEP, "g");
		object_value = object_value.replace(re, "");
	}
	if (EWR_DECIMAL_POINT != "")
		object_value = object_value.replace(EWR_DECIMAL_POINT, ".");
	return parseFloat(object_value);
}

// Convert string (yyyy-mm-dd hh:mm:ss) to date object
function ewr_StrToDate(object_value) {
	var re = /^(\d{4})-([0][1-9]|[1][0-2])-([0][1-9]|[1|2]\d|[3][0|1]) (?:(0\d|1\d|2[0-3]):([0-5]\d):([0-5]\d))?$/;
	var ar = object_value.replace(re, "$1 $2 $3 $4 $5 $6").split(" ");
	return new Date(ar[0], ar[1]-1, ar[2], ar[3], ar[4], ar[5]);
}

// Check range
function ewr_CheckRange(object_value, min_value, max_value) {
	if (!object_value || object_value.length == 0)
		return true;
	var L = ewrLang;
	if (L.isNumber(min_value) || L.isNumber(max_value)) { // Number
		if (ewr_CheckNumber(object_value))
			object_value = ewr_StrToFloat(object_value);
	}
	if (!L.isNull(min_value) && object_value < min_value)
		return false;
	if (!L.isNull(max_value) && object_value > max_value)
		return false;
	return true;
}

// Check time
function ewr_CheckTime(object_value) {
	if (!object_value || object_value.length == 0)
		return true;
	object_value = object_value.replace(/^\s*|\s*$/g, "");
	var re = /^(0\d|1\d|2[0-3]):[0-5]\d:[0-5]\d$/;
	return re.test(object_value);
}

// Check phone
function ewr_CheckPhone(object_value) {
	if (!object_value || object_value.length == 0)
		return true;
	object_value = object_value.replace(/^\s*|\s*$/g, "");
	var re = /^\(\d{3}\) ?\d{3}( |-)?\d{4}|^\d{3}( |-)?\d{3}( |-)?\d{4}$/;
	return re.test(object_value);
}

// Check zip
function ewr_CheckZip(object_value) {
	if (!object_value || object_value.length == 0)
		return true;
	object_value = object_value.replace(/^\s*|\s*$/g, "");
	var re = /^\d{5}$|^\d{5}-\d{4}$/;
	return re.test(object_value);
}

// Check credit card
function ewr_CheckCreditCard(object_value) {
	if (!object_value || object_value.length == 0)
		return true;
	var creditcard_string = object_value.replace(/\D/g, "");	
	if (creditcard_string.length == 0)
		return false;
	var doubledigit = creditcard_string.length % 2 == 1 ? false : true;
	var tempdigit, checkdigit = 0;
	for (var i = 0, len = creditcard_string.length; i < len; i++) {
		tempdigit = parseInt(creditcard_string.charAt(i));		
		if (doubledigit) {
			tempdigit *= 2;
			checkdigit += (tempdigit % 10);			
			if (tempdigit / 10 >= 1.0)
				checkdigit++;			
			doubledigit = false;
		}	else {
			checkdigit += tempdigit;
			doubledigit = true;
		}
	}		
	return (checkdigit % 10 == 0);
}

// Check social security number
function ewr_CheckSSC(object_value) {
	if (!object_value || object_value.length == 0)
		return true;
	object_value = object_value.replace(/^\s*|\s*$/g, "");
	var re = /^(?!000)([0-6]\d{2}|7([0-6]\d|7[012]))([ -]?)(?!00)\d\d\3(?!0000)\d{4}$/;
	return re.test(object_value);
}

// Check emails
function ewr_CheckEmailList(object_value, email_cnt) {
	if (!object_value || object_value.length == 0)
		return true;
	var arEmails = object_value.replace(/,/g, ";").split(";");
	for (var i = 0, len = arEmails.length; i < len; i++) {
		if (email_cnt > 0 && len > email_cnt)
			return false;
		if (!ewr_CheckEmail(arEmails[i]))
			return false;
	}
	return true;
}

// Check email
function ewr_CheckEmail(object_value) {
	if (!object_value || object_value.length == 0)
		return true;
	object_value = object_value.replace(/^\s*|\s*$/g, "");
	var re = /^[\w.%+-]+@[\w.-]+\.[A-Z]{2,6}$/i;
	return re.test(object_value);
}

// Check GUID {xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx}
function ewr_CheckGUID(object_value) {
	if (!object_value || object_value.length == 0)
		return true;
	object_value = object_value.replace(/^\s*|\s*$/g, "");
	var re = /^\{\w{8}-\w{4}-\w{4}-\w{4}-\w{12}\}$/;
	var re2 = /^\w{8}-\w{4}-\w{4}-\w{4}-\w{12}$/;
	return re.test(object_value) || re2.test(object_value);
}

// Check by regular expression
function ewr_CheckByRegEx(object_value, pattern) {
	if (!object_value || object_value.length == 0)
		return true;
	return (object_value.match(pattern)) ? true : false;
}

// Redirect by HTTP GET or POST
function ewr_Redirect(url, f, method) {
	if (!method || method.toUpperCase() == "POST") { // Default
		var form = f || document.createElement("FORM");
		form.action = url.split("?")[0];
		form.method = "POST";
		if (!f) form.style.display = "none";
		var param = {};
		url.replace(/(?:\?|&)([^&=]*)=?([^&]*)/g, function ($0, $1, $2) {
			if ($1) {
				var el = document.createElement("INPUT");
				el.name = $1;
				el.type = "hidden";
				el.value = decodeURIComponent($2);
				form.appendChild(el);
			}
		});
		if (!f) document.body.appendChild(form);
		form.submit();
	} else {
		window.location = url;
	}
}