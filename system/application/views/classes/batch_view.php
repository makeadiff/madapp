<?php
$this->load->view('layout/header', array('title'=>'Batch View'));
?>
<link href="<?php echo base_url(); ?>/css/sections/classes/batch_view.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
$(document).ready(function(){
	$('.substitute_select').change(function(){
		if($(this).val() == -1){
			var flag = $(this).attr('id').replace(/\D/g,"");
			showCities(flag);
		}
    });
});

function showCities(flag) {
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('classes/other_city_teachers')?>"+'/'+flag,
		success: function(msg){
			$('#sidebar').html(msg);
		}
	});
}
</script>

Center: <strong><?php echo $center_name; ?></strong><br />
Batch: <?php echo $batch_name ?><br />
Date: <u><?php echo date('d<\s\u\p>S</\s\u\p> M, Y', strtotime($from_date)); if($to_date) echo ' to ' . date('dS M, Y', strtotime($to_date)); ?></u><br />

<?php
$prev_week = change_week($from_date, -1);
$next_week = change_week($from_date, 1);

if($to_date) {
	$prev_week .= '/'. change_week($to_date, -1);
	$next_week .= '/'. change_week($to_date, 1);
}

?>
<a href="<?php echo site_url('classes/batch_view/'.$batch_id.'/'.$prev_week) ?>">&lt; Previous Week</a>
<a href="<?php echo site_url('classes/batch_view/'.$batch_id.'/'.$next_week) ?>">Next Week &gt;</a>

<form action="<?php echo site_url('classes/batch_view_save'); ?>" method="post">
<table class="data-table info-box-table">
<tr><th>Level</th><th>Unit Taught</th><th>Students</th><th>Teacher</th><th>Substitute</th><th>Attendance</th><th>Cancellation</th></tr>

<?php
$row_count = 0;
$teacher_row_count = 0;
$statuses = array(
			'attended'	=> 'Attended', 
			'absent'	=> 'Absent',
		);
foreach($classes as $class) {
	$teacher_count = count($class['teachers']);
	$rowspan = '';
	if($teacher_count > 1) $rowspan = "rowspan='$teacher_count'";
	
	for($teacher_index=0; $teacher_index < $teacher_count; $teacher_index++) {
	?>
<tr class="<?php echo ($row_count % 2) ? 'odd' : 'even'; if($class['teachers'][0]['status'] == 'cancelled') echo ' cancelled';  ?>">
<?php
		if($teacher_index == 0) {
?>
<td <?php echo $rowspan ?>><a href="<?php echo site_url('classes/edit_class/'.$class['id'].'/batch') ?>"><?php echo $class['level_name'] ?></a></td>
<td <?php echo $rowspan ?>><?php echo form_dropdown('lesson_id['.$class['id'].']', $all_lessons[$class['level_id']], $class['lesson_id'], 'style="width:100px;"'); ?></td>
<td <?php echo $rowspan ?>><a href="<?php echo site_url('classes/mark_attendence/'.$class['id']); ?>"><?php echo $class['student_attendence'] ?></a></td>

<?php } ?>
<td><a href="<?php echo site_url('user/view/'.$class['teachers'][$teacher_index]['id']) ?>"><?php echo $class['teachers'][$teacher_index]['name'] ?></a></td>
<td><div id="substitute_<?php echo $teacher_row_count ?>">
<?php
if($class['teachers'][$teacher_index]['substitute_id'] and !isset($all_user_names[$class['teachers'][$teacher_index]['substitute_id']])) { // Inter city substitution...
	echo "<a href='javascript:showCities(".$teacher_row_count.");'>";
	echo $this->user_model->get_user($class['teachers'][$teacher_index]['substitute_id'])->name;
	echo "</a>";
	
} else {
	echo form_dropdown('substitute_id['.$class['id'].']['.$class['teachers'][$teacher_index]['id'].']', $all_user_names, 
							$class['teachers'][$teacher_index]['substitute_id'], 'id="other_city_'.$teacher_row_count.'" style="width:100px;" class="substitute_select"');
}
?>
</div></td>
<td><?php echo form_dropdown('status['.$class['id'].']['.$class['teachers'][$teacher_index]['id'].']', $statuses, $class['teachers'][$teacher_index]['status'], 'style="width:100px;"'); ?></td>

<?php if($teacher_index == 0) { ?><td <?php echo $rowspan ?>>
<?php if($class['teachers'][0]['status'] == 'cancelled') { ?><a class="uncancel" href="<?php echo site_url('classes/uncancel_class/'.$class['id'].'/'.$batch_id.'/'.$from_date) ?>">Undo Class Cancellation<a/>
<?php } else { ?><a href="<?php echo site_url('classes/cancel_class/'.$class['id'].'/'.$batch_id.'/'.$from_date) ?>">Cancel Class<a/><?php } ?>
</td><?php } ?>
</tr>
<?php
		$teacher_row_count++;
	}
	$row_count++;
} // Level end ?>
</table>

<input type="hidden" name="batch_id" value="<?php echo $batch_id ?>" />
<input type="hidden" name="from_date" value="<?php echo $from_date ?>" />
<input type="hidden" name="to_date" value="<?php echo $to_date ?>" />
<input type="submit" value="Save" class="button green" name="action" />
</form>

<?php $this->load->view('layout/footer');

// Add or Subtract seven days.
function change_week($date, $add_sub) {
	return date('Y-m-d', strtotime($date) + ($add_sub * (60 * 60 * 24 * 7)) + 7200); // The '+ 7200' because daylight saving had created an issue on 11th November 2011. We may have to remove it some time.
}
