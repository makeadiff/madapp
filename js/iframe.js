function iframeInit() {
	$("a,form").each(function() {
		if(!$(this).hasClass("self")) this.target="_top";
	});
}
$(iframeInit);
