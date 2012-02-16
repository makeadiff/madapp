<?php $this->load->view('layout/thickbox_header'); ?>
<style type="text/css">
input[type=text], select, textarea {
	float:right;
}
.fields{
	float:left;
	width:300px;
	padding-bottom:5px;
	padding-top:5px;
}
</style>
<script type="text/javascript">

function populat_textbox() {
	var sub_no = $('#sub_no').val();
	if(isNaN(sub_no)) {
		alert("Subject count is not a number");
		document.getElementById('sub_no').focus(); 
		return false;
	}
	
	var msg = "";
	for(var i=1; i<=sub_no; i++) {
		msg += "<li><label for='subject_"+i+"'>Subject "+i+":</label><input type='text' id='subject_"+i+"' name='subject[]' /></li>";
		msg += "<li><label for='subject_total_"+i+"'>Total Marks "+i+":</label><input type='text' id='subject_total_"+i+"' name='subject_total[]' value='20' /></li>";
	}
	$('#subject').html(msg);
}

function validate() {
	var name = $('#name').val();
	if(name == "") {
		alert("Exam name missing.");
		return false;
	}
	return true;
}
</script>
<div id="message"></div>
<h2>Add New Exam</h2>
<form name="form" class="form-area clear" onsubmit="return validate()" action="<?php echo site_url('exam/insert'); ?>" method="post">
<ul class="form city-form">
<li><label for="name">Exam Name:</label><input id="name" name="name" type="text" /></li>
<li><label for="name">Level:</label><select id="level" name="level">
	<option value="primary">Primary</option>
	<option value="level_1">Level 1</option>
	<option value="level_2">Level 2</option>
	<option value="level_3">Level 3</option>
	<option value="starters">Starters</option>
</select></li>
<li><label for="sub_no">No of Subjects:</label><input id="sub_no" name="sub_no" type="text" onkeyup="javascript:populat_textbox();" /></li>
<div id="subject"></div>
<li><input id="btnSubmit" class="button green" type="submit" value="Submit" /></li>
</ul>
</form>

<?php $this->load->view('layout/thickbox_footer'); ?>