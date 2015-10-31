<?php $this->load->view('layout/header',array('title'=>'Class on ' . date('dS M, Y', strtotime($class_info['class_on'])))); ?>
<div id="head" class="clear"><h1>Class on <?php echo date('dS M, Y', strtotime($class_info['class_on'])); ?></h1></div>

<form action="<?php echo site_url('classes/mark_attendence_save') ?>" method="post" class="form-area">

<?php
$participation_options = array(
		'0'	=> 'Absent',
		"1"	=> "Disruptive",
		"2"	=> "Distracted",
		"3"	=> "Attentive",
		"4"	=> "Involved",
		"5"	=> "Participative",
	);
foreach($students as $student_id => $student_name) { ?>
<label for="attendence-<?php echo $student_id; ?>"> &nbsp; <?php echo $student_name; ?></label>
<select name="attendence[<?php echo $student_id ?>]">
<?php foreach ($participation_options as $key => $value) {
	echo "<option value='$key'";
	if(isset($attendence[$student_id])) {
		if($attendence[$student_id] == $key) print " selected";
	} else {
		if($key == 3) print " selected";
	}
	echo ">$value</option>\n";
}
?>
</select><br />

<?php } ?>
<br />

<?php
echo form_hidden('class_id', $class_info['id']);
echo form_hidden('project_id', 1);
echo form_submit('action', 'Save', 'class="button green"');
?>
</form>

<?php $this->load->view('layout/footer'); ?>
