$(function() {
	$(".teacher td").hover(showClassDetails, hideClassDetails);
}); 

function showClassDetails() {
	$(this).children(".class-info").css({
		left:Number($(this).position().left) + Number(this.width), 
		top:Number($(this).position().top) + 20,
	}).show();
}

function hideClassDetails() {
	$(".class-info").hide();
}