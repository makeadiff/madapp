function init() {
	$("#recalculate-parameters").click(recalculate);
}

function recalculate() {
	loading();
	$.ajax({
		"url": site_url + "/parameter/review_user/"+user_id,
		"method": "post",
		"dataType": "text",
		"success": function(data) {
			loaded();
			alert("Recalculation complete. Click OK to reload page with new data.")
			location.reload(true);
		}
	});
}