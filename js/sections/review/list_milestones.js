function init() {
	$(".milestone").click(doMilestone);
}

function doMilestone() {
	var milestone_id = this.id.toString().replace(/[\D]+/,"");
	var ele = jQuery("#milestone-"+milestone_id);
	var status = ele.prop("checked") ? '1' : '0';

	loading();
	$.ajax({
		"url": site_url + "/review/do_milestone/"+milestone_id+"/"+status,
		"method": "post",
		"dataType": "json",
		"success": function(data) {
			loaded();
			var milestone_id = data.milestone_id;
			$("#milestone-"+milestone_id).parent("li").addClass("milestone-done");
		}
	});
}