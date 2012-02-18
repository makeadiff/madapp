<?php
$this->load->view('layout/header', array('title'=>'Event Attendance'));
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/sections/classes/madsheet.css">

<form action="" method="post">
<label for="event_type">Event Type:</label><select id="event_type" name="event_type"> 
	<option value="">All</option>
	<option value="others" <?php if($event_type == 'other') echo 'selected="selected"'; ?>>Other</option>
	<option value="process" <?php if($event_type == 'process') echo 'selected="selected"'; ?>>Process Training</option> 
	<option value="curriculum" <?php if($event_type == 'curriculum') echo 'selected="selected"'; ?>>Curriculum Training</option> 
    <option value="teacher" <?php if($event_type == 'teacher') echo 'selected="selected"'; ?>>Teacher Training</option> 
	<option value="avm" <?php if($event_type == 'avm') echo 'selected="selected"'; ?>>AVM</option>
	<option value="coreteam_meeting" <?php if($event_type == 'coreteam_meeting') echo 'selected="selected"'; ?>>Core Team Meeting</option> 
	<option value="admin_meeting" <?php if($event_type == 'admin_meeting') echo 'selected="selected"'; ?>>Admin Meeting</option> 
</select>
<input type="submit" name="action" value="Filter" />
</form>

<table class="data-table">
<tr><th>&nbsp;</th><th>&nbsp;</th><?php foreach($events as $e) { ?><th><?php echo $e->name ?><br />
	<?php echo $event_attendance_count[$e->id]['present'] . '/'. $event_attendance_count[$e->id]['total'] ?>
</th><?php } ?></tr>
<?php foreach($users as $id => $name) { ?>
<tr><td><?php echo $name ?></td>
	<td><?php echo $user_attendance_count[$id]['present'] . '/'. $user_attendance_count[$id]['total'] ?></td>
<?php foreach($events as $e) {
	$class_type = 'no-data';
	if(isset($user_attendance[$e->id][$id])) {
		$class_type = 'absent';
		if($user_attendance[$e->id][$id]) $class_type = 'attended';
	}
?><td class="class-<?php echo $class_type ?>">&nbsp;</td><?php } ?>
</tr>
<?php } ?>

</table>


<?php $this->load->view('layout/footer');
