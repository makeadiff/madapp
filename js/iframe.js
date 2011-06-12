function iframeInit() {
	$("a,form").each(function() {
		this.target="_top";
	});
}
$(iframeInit);
