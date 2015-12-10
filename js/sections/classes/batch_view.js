function init() {
		$('.substitute_select').change(function(){
		if($(this).val() == -1){
			var flag = $(this).attr('id').replace(/\D/g,"");
			showCities(flag);
		}
    });

	// This part makes sure that users dont click save button when ALL classes are cancelled.
	var class_status = $(".class_status");
	var final_status = false;
	class_status.each(function() {
		if(this.value == 1) {
			final_status = true;
		}
	});

	if(!final_status) {
		$("#action").prop('disabled', true).css({background: "#aaa"});
	}
}

function showCities(flag) {
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('classes/other_city_teachers')?>"+'/'+flag,
		success: function(msg){
			$('#sidebar').html(msg);
		}
	});
}
