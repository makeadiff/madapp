<?php $this->load->view('layout/thickbox_header');?>
<script type="text/javascript">
$(function () {
	$("#center_id").change(getLevels);
});

function getLevels() {
	if(this.value == 0) return;
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('exam/get_levels')?>/"+this.value,
		success: function(msg){
			$('#levels_list').html(msg);
			$('#kids_list').html("");
		}
	});
}

function getKids() {
	var level_id = $("#level_id").val();
	if(level_id == 0) return;
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('exam/get_kids_in_level')?>/"+level_id,
		success: function(msg){
			$('#kids_list').html(msg);
		}
	});
}

function validate() {
	if(!document.getElementById("student_id") || !$("#student_id").val()) {
		alert("Please select the kids who took the test...");
		return false;
	}
	
	return true;
}
</script>

<h2>Add <?php echo $exam_name ?> Results</h2>

<form action="<?php echo site_url('exam/add_marks'); ?>" method="post" class="form-area" id="kids_form" onsubmit="return validate();">
<label>Date</label><input type="text" name="exam_on" value="<?php echo date('Y-m-d'); ?>" /><br />
<label>Center</label><?php echo form_dropdown('center_id', array('0'=>'Select') + $centers, '', 'id="center_id"'); ?><br />
<label>Level</label><div id="levels_list"></div><br />
<label>Kids</label><div id="kids_list"></div><br />

<label>&nbsp;</label><?php echo form_submit('action', 'Enter Marks', 'class="green button"'); ?>

<?php echo form_hidden('exam_id', $exam_id); ?>
</form>
<?php $this->load->view('layout/thickbox_footer');