function init() {
	$("#user-type-selector").change(function() {
		var today = new Date();
		var day_yyyy_mm_dd = (today.getYear() + 1900) + "-" + String("00" + today.getMonth()).slice(-2) + "-" + String("00" + today.getDay()).slice(-2);
		if(this.value == "let_go") {
			$("#exit-interview-feedback").show();
			if($("#left_on").val() == "0000-00-00" || $("#left_on").val() == "") 
				$("#left_on").val(day_yyyy_mm_dd);
		} else {
			$("#exit-interview-feedback").hide();
			if($("#left_on").val() == day_yyyy_mm_dd) {
				$("#left_on").val("");
			}
		}
	});
}
