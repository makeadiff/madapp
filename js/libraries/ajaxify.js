(function($){
$.ajaxify = {};
$.ajaxify.init = function() {
	$("a.ajaxify").click($.ajaxify.handleClick);
};

$.ajaxify.handleRequest = function(ele, data) {
	if($(ele).hasClass("ajaxify-replace") && data) {
		if(data.success) {
			if(document.getElementById(ele.id + "_custom_handler")) {
				var function_name = document.getElementById(ele.id + "_custom_handler").value;
				try {
					eval(function_name + "('"+encodeURIComponent(data.success)+"')");
				} catch(E) {
					alert("Error at Eval: " + E);
				}
			} else {
				var new_node = document.createElement("span");
				new_node.innerHTML = data.success;
				ele.parentNode.replaceChild(new_node, ele);
			}
		} else {
			alert("Attempt failed");
		}
	}
}

$.ajaxify.handleClick = function(e) {
	e.stopPropagation();
	var url = this.href;
	var anchor = this;
	
	$.ajax({
		"url": url,
		"dataType": 'json',
		"success": function(data){$.ajaxify.handleRequest(anchor, data);},
		"error": function(data){alert("Call Error: "+ data);},
	});
	return false;
}

})(jQuery);
jQuery(document).ready(jQuery.ajaxify.init);


/*
var ajaxify = {
	"handleRequest": function(ele, data) {
		if($(ele).hasClass("ajaxify-replace") && data) {
			if(data.success) {
				if(document.getElementById(ele.id + "_custom_handler")) {
					var function_name = document.getElementById(ele.id + "_custom_handler").value;
					try {
						eval(function_name + "('"+encodeURIComponent(data.success)+"')");
					} catch(E) {
						alert("Error at Eval: " + E);
					}
				} else {
					var new_node = document.createElement("span");
					new_node.innerHTML = data.success;
					ele.parentNode.replaceChild(new_node, ele);
				}
			} else {
				alert("Attempt failed");
			}
		}
	},
	
	"handleClick": function(e) {
		JSL.event(e).stop();
		var url = this.href;
		var anchor = this;

		JSL.ajax(url).bind({
			"onSuccess": function(data){ajaxify.handleRequest(anchor, data);},
			"onError": function(data){alert("Call Error: "+ data);},
			"loading_indicator": "loading",
			"format":"json",
			"method":"get"
		});
	},
	
	"handleSubmit": function (e) {
		JSL.event(e).stop();
		var url = this.getAttribute("action");
		
		// Get all the data fields
		var input = this.getElementsByTagName("input");
		var textarea = this.getElementsByTagName("textarea");
		var select = this.getElementsByTagName("select");
		
		//There may be a parameter in the URL already.
		if(url.indexOf("?")+1) url += "&";
		else url += "?";
		
		var parts = [];
		//Create a URL with all the fields appended to it.
		//Nested for loops. Feel free to refactor if there is any problem in reading it.
		$(input, textarea, select).each(function(collection) {
			$(collection).each(function(ele) {
				var append = true;
				
				if(ele.tagName == "INPUT") {
					if(ele.getAttribute("type") == "checkbox") { //Add checkbox values only if its checked.
						if(!ele.checkbox) append = false;
					}
				}
				
				if(append) parts.push(ele.name + "=" + encodeURIComponent(ele.value));
			});
		});
		parts.push("response=json");
		url += parts.join("&");
		
		var method = this.getAttribute("method");
		if(!method) method = "get";
		var form = this;
		
		JSL.ajax(url).bind({
			"onSuccess": function(data){ajaxify.handleRequest(form, data);},
			"onError": function(data){alert("Call Error: "+ data);},
			"loading_indicator": "loading",
			"format":"json",
			"method":method
		});
	},
	
	"init": function() {
		$("form.ajaxify").on("submit",ajaxify.handleSubmit);
		$("a.ajaxify").on("click",ajaxify.handleClick);
	}
}

$(window).load(ajaxify.init);
*/