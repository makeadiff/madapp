function init() {
	$("#user-type-selector").change(function() {
		var today = new Date();
		// var day_yyyy_mm_dd = (today.getYear() + 1900) + "-" + String("00" + today.getMonth()).slice(-2) + "-" + String("00" + today.getDay()).slice(-2);
		var day_yyyy_mm_dd = today.toISOString().slice(0,10);


		if(this.value == "let_go" || this.value == "alumni" || this.value == "left_before_induction") {
			$("#exit-interview-feedback").show();
			if($("#left_on").val() == "0000-00-00" || $("#left_on").val() == "") 
				$("#left_on").val(day_yyyy_mm_dd);
			else {
				if(confirm("Update left on date to today?")) {
					$("#left_on").val(day_yyyy_mm_dd);	
				}
			}
		
		} else {
			$("#exit-interview-feedback").hide();
			if($("#left_on").val() == day_yyyy_mm_dd) {
				$("#left_on").val("");
			}
		}
	});
}
