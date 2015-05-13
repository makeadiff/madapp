function init() {
	var batches = $(".batch");

	for(var i=0; i<batches.length; i++) {
		var batch = $(batches[i]);
		batch.on("change", updateClassName);
		//updateClassName.apply(batches[i]);
	}
}

function updateClassName() {
	var ele = $(this);
	var user_id = this.id.replace(/\D/g,"");
	var class_name = $("#level-" + user_id);
	var batch_id = this.value;

	var select = '';
	if(batch_id == 0) {
		select = '<option value="0">None</option>';
	} else {
		var levels = batch_level_user_hirarchy[this.value];
		for(var level_id in all_levels[batch_id]) {
			select += '<option value="'+level_id+'">'+all_levels[batch_id][level_id]+'</option>';
		}
	}

	class_name.html(select)

}