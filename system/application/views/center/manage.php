<?php
$title = 'Manage ' . $center_name;
$this->load->view('layout/header',array('title'=>$title));

function showMessage($count, $message, $threshold=1) {
	if($message) $message = "($message)";
	
	if($count >= $threshold) echo '<span class="success with-icon" style="color:darkgreen;" title="'.$count.'/'.$threshold.'">Completed ' . $message . '</span>'; 
	else echo '<span class="error with-icon" title="'.$count.'/'.$threshold.'">Incomplete ' . $message . '</span>';
}

?>

<div id="head" class="clear">
<h1><?php echo $title ?></h1>
</div>

<div id="main">
<table id="tableItems" class="clear data-table info-table-box" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th class="colCheck1">Step #</th>
	<th class="colName left sortable">Task</th>
    <th class="colStatus sortable">Status</th>
</tr>
</thead>
<tbody>

<?php if($this->user_auth->get_permission('center_edit')) { ?>
<tr><td>1</td>
<td><a class="thickbox popup" href="<?php echo site_url('center/popupEdit_center/'.$center_id); ?>">Edit Center Details</a></td>
<td><?php showMessage($details['center_head_id'], ''); ?></td></tr><?php } ?>

<?php if($this->user_auth->get_permission('user_index')) { ?>
<tr><td>2</td>
<td><a href="<?php echo site_url('user/view_users'); ?>">Manage Volunteers</a></td>
<td><?php showMessage($details['total_volunteer_count'], $details['total_volunteer_count'] . " Volunteers", 30); ?></td></tr><?php } ?>

<?php if($this->user_auth->get_permission('kids_index')) { ?>
<tr><td>3</td>
<td><a href="<?php echo site_url('kids/index/'.$center_id); ?>">Manage Kids</a></td>
<td><?php showMessage($details['kids_count'], $details['kids_count'] . " Kids", 12); ?></td></tr><?php } ?>

<?php if($this->user_auth->get_permission('level_index')) { ?>
<tr><td>4</td>	
<td><a href="<?php echo site_url('level/index/center/'.$center_id); ?>">Manage Class Sections</a></td>
<td><?php showMessage($details['level_count'], $details['level_count'] . " Class Sections"); ?></td></tr><?php } ?>

<?php if($this->user_auth->get_permission('batch_index')) { // :PERMISSION_RESET: ?>
<tr><td>5</td>	
<td><a href="<?php echo site_url('classes/assign_students/'.$center_id); ?>">Assign Students to Class Sections</a></td>
<td><?php showMessage($details['assigned_student_count'], $details['assigned_student_count'] . " students assigned.", $details['kids_count']); ?></td></tr><?php } ?>

<?php if($this->user_auth->get_permission('batch_index')) { ?>
<tr><td>6</td>	
<td><a href="<?php echo site_url('batch/index/center/'.$center_id); ?>">Manage Batches</a></td>
<td><?php showMessage($details['batch_count'], $details['batch_count'] . " Batchs"); ?></td></tr><?php } ?>

<?php if($this->user_auth->get_permission('batch_index')) { // :PERMISSION_RESET: ?>
<tr><td>7</td>	
<td><a href="<?php echo site_url('batch/level_assignment/'.$center_id); ?>">Batch/Class Assignment</a></td>
<td>&nbsp;</td></tr><?php } ?>

<?php if($this->user_auth->get_permission('batch_index')) { // :PERMISSION_RESET: ?>
<tr><td>8</td>	
<td><a href="<?php echo site_url('classes/assign/'.$center_id); ?>">Assign Teachers</a></td>
<td><?php showMessage($details['teacher_count'], $details['teacher_count'] . " volunteers assigned.", ($details['batch_count'] * $details['level_count'])); ?></td></tr><?php } ?>

<?php if($this->user_auth->get_permission('batch_index')) { // :PERMISSION_RESET: ?>
<tr><td>9</td>	
<td><a href="<?php echo site_url('center/info/'.$center_id); ?>">View All Center Assignment</a></td>
<td>&nbsp;</td></tr><?php } ?>

</table>

<br /><br />
<?php if($this->user_auth->get_permission('center_delete')) { ?><a href="<?php echo site_url("center/deletecenter/".$center_id); ?>" class="confirm delete with-icon">Delete <?php echo $center_name ?> Center</a><?php } ?>
</div>

<?php
$this->load->view('layout/footer');
