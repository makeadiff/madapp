function siteInit() {
	$("a.confirm").live("click", function(event) { //If a link has a confirm class, confrm the action
		var action = (this.title) ? this.title : "do this";
		action = action.substr(0,1).toLowerCase() + action.substr(1); //Lowercase the first char.
		
		if(!confirm("Are you sure you want to " + action + "?")) {
			event.stopPropagation();
			return false;
		}
	});
	
	tb_init("a.thickbox, input.thickbox");
	$(".popup").each(function() {
		var url = $(this).attr('href') + '?TB_iframe=true&height=400&width=700';
	
		$(this).attr('href', url);
	});
	
	$(".info-box-table td").click(showInfoBox);
	
	if($(".data-table").tablesorter) $(".data-table").tablesorter();
	
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
