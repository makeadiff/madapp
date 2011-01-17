<?php
$this->load->view('layout/header', array('title'=>'Your Classes')); ?>
<h1>Your Classes</h1>

<table>
<tr><th>Center</th><th>Class</th><th>Time</th><th>Volunteer</th><th>Status</th></tr>
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
<td>You</td>
<td><?php echo ucfirst($class->status) ?></td>
</tr>
<?php } ?>
</table>


<?php $this->load->view('layout/footer'); ?>