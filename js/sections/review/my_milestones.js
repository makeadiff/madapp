function init() {
	$(".milestone").click(doMilestone);
}

function doMilestone() {
	var milestone_id = this.id.toString().replace(/[\D]+/,"");
	var ele = jQuery("#milestone-"+milestone_id);

	if(ele.prop("checked") && !ele.prop("disabled")) {
		$.ajax({
			"url": site_url + "/review/do_milestone/"+milestone_id,
			"method": "post",
			"dataType": "json",
			"success": function(data) {
				var milestone_id = data.milestone_id;
				$("#milestone-"+milestone_id).parent("li").addClass("milestone-done");
				$("#milestone-"+milestone_id).attr("disabled", "disabled");
			}
		});
	}
}