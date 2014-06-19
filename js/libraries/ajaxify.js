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
			alert("Attempt failed: " + data.error);
		}
		
	} else if($(ele).hasClass("ajaxify-remove-parent") && data) {
		if(data.success) {
			ele.parentNode.parentNode.removeChild(ele.parentNode);
		} else {
			alert("Attempt failed: " + data.error);
		}
	}
}

$.ajaxify.handleClick = function(e) {
	e.stopPropagation();
	
	if($(this).hasClass("ajaxify-confirm")) {
		var action = (this.title) ? this.title : "do this";
		action = action.substr(0,1).toLowerCase() + action.substr(1); //Lowercase the first char.
		
		if(!confirm("Are you sure you want to " + action + "?")) {
			return false;
		}
	}
	
	var url = this.href;
	var anchor = this;
	$.ajax({
		"url": url,
		"dataType": 'json',
		"success": function(data){$.ajaxify.handleRequest(anchor, data);},
		"error": function(data){alert("Call Error: "+ data.error);},
	});
	return false;
}

})(jQuery);
jQuery(document).ready(jQuery.ajaxify.init);
