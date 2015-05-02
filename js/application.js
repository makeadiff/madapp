//Framework Specific
function showMessage(data) {
	if(data.success) $("#success-message").html(stripSlashes(data.success)).show();
	if(data.error) $("#error-message").html(stripSlashes(data.error)).show();
}
function stripSlashes(text) {
	if(!text) return "";
	return text.replace(/\\([\'\"])/,"$1");
}


function ajaxError() {
	alert("Error communicating with server. Please try again");
}
function loading() {
	$("#loading").show();
}
function loaded() {
	$("#loading").hide();
}


function siteInit() {
	$ = jQuery.noConflict();
	$("body").on("click", "a.confirm", function(event) { //If a link has a confirm class, confrm the action
		var action = (this.title) ? this.title : "do this";
		action = action.substr(0,1).toLowerCase() + action.substr(1); //Lowercase the first char.
		
		if(!confirm("Are you sure you want to " + action + "?")) {
			event.stopPropagation();
			return false;
		}
	});

	$(".popup").click(function(event) {
		var url = this.href; 
		$("#sidebar").html("<iframe src='"+url+"' width='250' height='500'></iframe>");
		event.stopPropagation();
		window.scrollTo(0,0);
		return false;
	});
	
	$(".info-box-table td").click(showInfoBox);
	
	if(window.init && typeof window.init == "function") init(); //If there is a function called init(), call it on load
}

function showInfoBox() {
	if($(this).children(".info-box").css("display") == "none") {
		$(".info-box").hide();
		
		$(this).children(".info-box").css({
			left:Number($(this).position().left), 
			top:Number($(this).position().top) + Number($(this).height()) - 2,
		}).show();
	} else {
		$(this).children(".info-box").hide();
	}
}


$(siteInit);
