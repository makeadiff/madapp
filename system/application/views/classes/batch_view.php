<?php
$this->load->view('layout/header', array('title'=>'Batch View'));
?>

Center: <?php echo $center_name; ?><br />
Batch: <?php echo $batch_name ?><br />
Date: <?php echo $from_date; if($to_date) echo ' to ' . $to_date; ?><br />

<?php
$prev_week = change_week($from_date, -1);
$next_week = change_week($from_date, 1);

if($to_date) {
	$prev_week .= '/'. change_week($to_date, -1);
	$next_week .= '/'. change_week($to_date, 1);
}

?>
<a href="<?php echo site_url('classes/batch_view/'.$batch_id.'/'.$prev_week) ?>">Previous Week</a>
<a href="<?php echo site_url('classes/batch_view/'.$batch_id.'/'.$next_week) ?>">Next Week</a>

<table class="data-table info-box-table">
<tr><th>Level</th><th>Feedback</th><th>Students</th><th>Teacher</th><th>Substitute</th><th>Attendence</th><th>Cancelation</th></tr>

<?php
$row_count = 0;
foreach($classes as $class) {
	$teacher_count = count($class['teachers']);
	$rowspan = '';
	if($teacher_count > 1) $rowspan = "rowspan='$teacher_count'";
	
	for($teacher_index=0; $teacher_index < $teacher_count; $teacher_index++) {
		if($teacher_index == 0) {
?>
<tr class="<?php echo ($row_count % 2) ? 'odd' : 'even' ?>">
<td <?php echo $rowspan ?>><?php echo $class['level_name'] ?></td>
<td <?php echo $rowspan ?>><?php echo $class['lesson'] ?></td>
<td <?php echo $rowspan ?>><a href="<?php echo site_url('classes/mark_attendence/'.$class['id']); ?>"><?php echo $class['student_attendence'] ?></a></td>

<?php } ?>
<td><?php echo $class['teachers'][$teacher_index]['name'] ?></td>
<td><?php echo $class['teachers'][$teacher_index]['substitute'] ?></td>
<td><a href="<?php echo site_url('classes/edit_class/'.$class['id']) ?>"><?php echo ucfirst($class['teachers'][$teacher_index]['status']) ?></a></td>

<?php if($teacher_index == 0) { ?>
<td <?php echo $rowspan ?>>Cancel Class</td>
<?php } ?>
</tr>
<?php
	}
	$row_count++;
} // Level end ?>
</table>


<?php $this->load->view('layout/footer');

// Add or Subtract seven days.
function change_week($date, $add_sub) {
	return date('Y-m-d', strtotime($date) + ($add_sub * (60 * 60 * 24 * 7)));
}
