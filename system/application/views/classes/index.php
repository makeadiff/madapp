<?php
$this->load->view('layout/header', array('title'=>'Classes')); ?>
<div id="head" class="clear"><h1>Classes</h1></div>

<table id="main">
<tr><th>Center</th><th>Class</th><th>Time</th><th>Volunteer</th><th>Status</th><th>Action</th></tr>
<?php foreach($all_classes as $class) {
	if(empty($all_levels[$class->level_id])) { // This one user might be handling two different classes. Different center, different level, etc.
		$all_levels[$class->level_id] = $level_model->get_level_details($all_classes[0]->level_id);
	}
	
	$level_details = $all_levels[$class->level_id];

?>
<tr>
<td><?php echo $level_details->center_name ?></td>
<td><?php echo $level_details->name ?></td>
<td><?php echo $class->class_on ?></td>
<td><?php echo (empty($all_users) ? 'You' : $all_users[$class->user_id]->name) ?></td>
<td><?php echo ucfirst($class->status) ?></td>
<td><a href="<?php echo site_url('classes/edit_class/'.$class->class_id); ?>" class="edit with-icon">Edit</a></td>
</tr>
<?php } ?>
</table>

<?php $this->load->view('layout/footer'); ?>
