<?php $this->load->view('layout/header',array('title'=>'Class on ' . date('dS M, Y', strtotime($class_info['class_on'])))); ?>
<div id="head" class="clear"><h1>Class on <?php echo date('dS M, Y', strtotime($class_info['class_on'])); ?></h1></div>

<form action="<?php echo site_url('classes/mark_attendence_save') ?>" method="post">

<?php foreach($students as $student_id => $student_name) { ?>
<input type="checkbox" name="attendence[<?php echo $student_id ?>]" id="attendence-<?php echo $student_id ?>" value="1" <?php 
	if(!isset($attendence[$student_id]) or $attendence[$student_id] != '0') print "checked='checked'";
?>/> 
<label for="attendence-<?php echo $student_id; ?>"> &nbsp; <?php echo $student_name; ?></label><br />
<?php } ?>
<br />

<?php
echo form_hidden('class_id', $class_info['id']);
echo form_hidden('project_id', 1);
echo form_submit('action', 'Edit');
?>
</form>

<?php $this->load->view('layout/footer'); ?>
