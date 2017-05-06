var global_error = false;
function init() {
	var batches = $(".batch");

	for(var i=0; i<batches.length; i++) {
		var batch = $(batches[i]);
		batch.on("change", updateClassName);
	}

	$("#new-teacher").click(function() {
		$("#new-teacher").hide();
		$("#new-teacher-area").show();
	})

	$("#email").change(validate);
	$("#phone").change(validate);
	$("#new-teacher-action").click(addTeacher);
}

function validate() {
	var id = $(this).prop("id");
	var value = $(this).val();
	global_error = false;

	if(!value) {
		global_error_field = id;
		global_error = "no-"+id;

		$("#" + id + "-info").html("Please make sure you have a valid " + id);
		return;
	} else if(id == "phone" && !value.match(/[0-9+ ]{9,15}/)) {
		global_error_field = id;
		global_error = "invalid-"+id;

		$("#" + id + "-info").html("Invalid Phone Number(use 10 digits, all numbers)");
		return;
	} else if(id == "email" && !value.match(/[\w\.\-\+]+\@\w+\.[\w\.]{2,5}/)) {
		global_error_field = id;
		global_error = "invalid-"+id;

		$("#" + id + "-info").html("Invalid Email address");
		return;
	}
	// console.log(id,value,global_error)

	// Query the database with the phone or email to see if the user already exists. 
	$.ajax({
		"url": base_url + "index.php/api/check_user_exists/?key=am3omo32hom4lnv32vO&" + id + "=" + value,
		"success": function(data) {
			if(data.user && data.user.id) {
				var user_name = data.user.name;
				// If not volunteer. Alumni, Applicant or well wisher.
				if(data.user.user_type != 'volunteer') {
					global_error_field = id;
					global_error = "duplicate-user-not-volunteer";
					$("#" + id + "-info").html("User '"+user_name+"' exist in the database - but is not marked as a volunteer(User is a '"+data.user.user_type+"'). "
						+ "Convert '"+user_name+"' to volunteer?<br />"
						+ "<input type='button' class='btn btn-primary btn-sm' onclick='makeTeacher("+data.user.id+")' value='Convert to Volunteer' /><br />").show();
				} else {
					var is_teacher = false;
					for(var group in data.user.groups) {
						if(group == 9) { // 9 is teacher.
							is_teacher = true;
							break;
						}
					}

					if(!is_teacher) {
						global_error_field = id;
						global_error = "duplicate-user-not-teacer";
						$("#" + id + "-info").html("User '"+user_name+"' already exist in the database. Convert '"+user_name+"' to teacher?<br />"
							+ "<input type='button' class='btn btn-primary btn-sm' onclick='makeTeacher("+data.user.id+")' value='Convert to Teacher' /><br />").show();
					} else { // Is a teacher.
						if(data.user.city_id != city_id) { // but of another city.
							global_error_field = id;
							global_error = "duplicate-teacher-another-city";
							$("#" + id + "-info").html("User '"+user_name+"' is already a teacher in "+data.user.city_name+" - move the teacher to this city?.<br />"
								+ "<input type='button' class='btn btn-primary btn-sm' onclick='makeTeacher("+data.user.id+")' value='Move to Current City' /><br />").show();
						} else { // Teacher in this city.
							global_error_field = id;
							global_error = "duplicate-teacher";
							$("#" + id + "-info").html("User '"+user_name+"' is already a teacher - and should be visible in the above list.<br />"
								+ "<a class='btn btn-primary btn-sm' href='#teacher-"+data.user.id+"'>Go to "+user_name+"\'s Row</a><br />").show();
						}
					}
				}
			} else {
				$("#" + id + "-info").html("");
			}
		},
		"error": function() {
			console.log("Error calling URL '" +base_url + "index.php/api/check_user_exists/?" + id + "=" + value+ "'");
		}
	});
}

function addTeacher() {
	var name = $("#name").val();
	var email= $("#email").val();
	var phone= $("#phone").val();

	var field = [];
	var error = [];
	$(".form-info").html("");

	// Validations
	if(!name) {
		field.push("name");
		error.push("Please enter the Name of the Teacher");
	}
	if(!email) {
		field.push("email");
		error.push("Please enter the Email");
	}
	if(!phone) {
		field.push("phone");
		error.push("Please enter the Phone Number");
	}

	if(global_error) {
		field.push("form");
		if(global_error == "duplicate-user-not-teacer") error.push("Given " + global_error_field + " belongs to a existing user who is not a teacher. Convert that user to a teacher rather than adding a new teacher with same data");
		else if(global_error == "duplicate-user-not-volunteer") error.push("Given " + global_error_field + " belongs to a existing user who is not a volunteer. Convert that user to a teacher rather than adding a new teacher with same data");
		else if(global_error == "duplicate-teacher") error.push("Given " + global_error_field + " belongs to a existing teacher. Assign the class to that teacher.");
	}

	if(error.length) {
		for(var i=0; i<field.length; i++)
			$("#" + field[i] + "-info").html(error[i]).show();
		return true;
	} else {
		// No error - add teacher to DB.
		$.ajax({
			"url": base_url + "index.php/api/user_add/?key=am3omo32hom4lnv32vO&name="+name+"&email="+email+"&phone="+phone+"&city_id="+city_id+"&groups=9",
			"success": showNewTeacher
		});
	}
}

function makeTeacher(user_id) {
	$.ajax({
		"url": base_url + "index.php/api/user_convert_to_teacher/?key=am3omo32hom4lnv32vO&user_id=" + user_id + "&city_id=" + city_id,
		"success": showNewTeacher
	});
}

function showNewTeacher(data) {
	if(data.success) {
		var user_id = data.user_id;

		//Copy the HTML of the last row
		var row = $(".table tr:last").html();
		var row_user_id = $(".table tr:last").prop("id").replace(/\D/g, ""); // Get the ID of the teacher of the last row of the table.
		var html = row.replace(new RegExp(row_user_id, 'g'), user_id); // Replace all teached IDs of the last row with the new user_id
		
		var tr = $("<tr />", {html: html}).css("display", "none");

		tr.attr("id", "teacher-"+user_id);
		tr.find(".name").html(data.name);
		$(".table").append(tr); // Add the new row with replace IDs to the table.
		tr.show("slow");
		$("#teacher-"+user_id+" .batch").on("change", updateClassName);

		// Reset the Add New Teacher Form
		$("#new-teacher").show();
		$("#new-teacher-area").hide();

		$("#email").val("");
		$("#name").val("");
		$("#phone").val("");
		$(".form-info").html("");

		$("#form-info").html("Added '"+data.name+"' to the above table");
		return user_id;
	} else {
		alert("Something went wrong in adding the user. Try adding the user using the HC portal");
		return false;
	}
}

function updateClassName() {
	var ele = $(this);
	var user_id = this.id.replace(/\D/g,"");
	var level_dropdown = $("#level-" + user_id);
	var batch_id = this.value;

	var select = '';
	if(batch_id == 0) {
		select = '<option value="0">None</option>';
	} else {
		var levels = batch_level_user_hirarchy[batch_id];
		for(var level_id in all_levels[batch_id]) {
			select += '<option value="'+level_id+'">'+all_levels[batch_id][level_id]+'</option>';
		}
	}

	level_dropdown.html(select);
}