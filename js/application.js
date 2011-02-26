function siteInit() {
	$("a.confirm").click(function(event) { //If a link has a confirm class, confrm the action
		var action = (this.title) ? this.title : "do this";
		action = action.substr(0,1).toLowerCase() + action.substr(1); //Lowercase the first char.
		
		if(!confirm("Are you sure you want to " + action + "?")) {
			event.stopPropagation();
			return false;
		}
	});

	if(window.init && typeof window.init == "function") init(); //If there is a function called init(), call it on load
}

$(siteInit);