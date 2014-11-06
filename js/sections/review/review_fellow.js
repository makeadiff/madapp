function init() {
	$("#recalculate-parameters").click(recalculate);
}

function recalculate() {
	loading();
	alert(cycle);
	$.ajax({
		"url": site_url + "/parameter/review_user/"+user_id,
		"method": "post",
		"data": {"cycle":cycle},
		"dataType": "text",
		"success": function(data) {
			loaded();
			console.log(data)
			alert("Recalculation complete. Click OK to reload page with new data.")
			//location.reload(true);
		}
	});
}

function comment(id) {
	jQuery.ajax({
				"url":  site_url + "/review/ajax_get_comment/" + id,
				"success": function(data) {
					jQuery("#comment-area").show();
					jQuery("#comment").val(data);
					jQuery("#parameter_id").val(id);
				}
			});
	
}

function cancelComment() {
	jQuery("#comment-area").hide();
}

function saveComment() {
	var id = jQuery("#parameter_id").val();
	jQuery.ajax({
			"url":  site_url + "/review/ajax_save_comment/" + id,
			"data": {"comment": jQuery("#comment").val()},
			"type": "POST",
			"success": function(data) {
				jQuery("#comment-area").hide();
			}
		});
}

function inputData(id, name, value, ele) {
	var title = name.replace(/_/g," ");
	var input_value = prompt(title, value);
	if(input_value == undefined || input_value == null) return;
	input_value = Number(input_value);

	ele.innerHTML = input_value;
	
	jQuery.ajax({
			"url":  site_url + "review/ajax_save_value/"+id+"/"+input_value,
			"success": function(data) {
				//alert(data);
			}
		});
}
