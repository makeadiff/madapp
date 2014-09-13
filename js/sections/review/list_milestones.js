function init() {
	$(".milestone").click(milestoneDone);
	$(".milestone-do").click(doMilestone);
}

function milestoneDone() {
	var milestone_id = this.id.replace(/\D/g,"");
	$("#milestone-done-"+milestone_id).show();
}

function doMilestone() {
	var milestone_id = this.id.toString().replace(/[\D]+/,"");
	var ele = jQuery("#milestone-"+milestone_id);
	var done_on = $("#done_on_"+milestone_id).val();
	var status = ele.prop("checked") ? '1' : '0';

	loading();
	$.ajax({
		"url": site_url + "/review/do_milestone/"+milestone_id+"/"+status+"/"+done_on,
		"method": "post",
		"dataType": "json",
		"success": function(data) {
			loaded();
			var milestone_id = data.milestone_id;

			$("#milestone-done-"+milestone_id).hide();
		}
	});
}
