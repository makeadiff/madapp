<?php
$this->load->view('layout/header', array('title'=>'Event Attendance'));
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/sections/classes/madsheet.css">


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
