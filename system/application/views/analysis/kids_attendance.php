<?php
$this->load->view('layout/header', array('title'=>'Class Progress Report'));
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/sections/analysis/class_progress_report.css">
<script type="text/javascript" src="<?php echo base_url() ?>js/sections/classes/madsheet.js"></script>

<h3>Legend</h3>
<table border="1">
<tr>
<td class='class-good'>Class Happening</td>
<td class='class-repeated'>Unit Repeated</td>
<td class='class-without-test'>Class Happening Without Tests</td>
</tr>
</table>

<?php
foreach($data as $center_id => $center_info) {
	if(empty($center_info)) continue;
?>
<h3><?php echo $center_info['center_name'] ?></h3>

<table class="madsheet data-table info-box-table">
<tr>
<th>Level</th>
<th>Kids</th>
<?php
foreach($center_info['days_with_classes'] as $day) print "<th>$day</th>";
?>
<th>Aggr</th>
<th>Aggr</th>
</tr>

<?php
$row_count = 0;
foreach($all_levels[$center_id] as $level_info) { // Level start.
?>
<tr class="<?php echo ($row_count % 2) ? 'odd' : 'even' ?>">
<td nowrap='nowrap'><?php echo $level_info->name ?></td>
<td><?php echo $all_kids[$level_info->id] ?></td>

<?php
	$last_lesson_id = 0;
	$repeat_count = 0;
	foreach($center_info['days_with_classes'] as $date_index => $day) {
	
		if(!isset($center_info['class'][$level_info->id][$date_index])) continue;
		$classdateid = $center_info['class'][$level_info->id][$date_index]->id;
		if($classdateid != $last_lesson_id and $classdateid) {
			$last_lesson_id = $classdateid;
			$repeat_count = 0;
		} else {
			$repeat_count++;
		}
		$class_type = 'good';
		if($repeat_count > 2) $class_type = 'repeated';
		if($classdateid == 0) $class_type = 'no-data';
	?>
	<td class="class-<?php echo $class_type ?>"><?php 
		echo $attendance[$center_info['class'][$level_info->id][$date_index]->id];
	?></td>
<?php } ?>
<td nowrap='nowrap'><?php echo "test";?></td>

</tr>

<?php
	$row_count++;
} // Level end ?>
<td nowrap='nowrap'>Total</td>
<td nowrap='nowrap'>18</td>
</table><br />

<hr />
<?php } // Center ?>


<?php $this->load->view('layout/footer');