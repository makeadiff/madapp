<?php
$this->load->view('layout/header', array('title'=>'MAD Sheet'));

// See the madsheet_class_mode.php - that's the one in active use
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/madsheet.css">
<script type="text/javascript" src="<?php echo base_url() ?>js/madsheet.js"></script>

<h3>Legend</h3>
<table border="1">
<tr>
<td class='class-projected'>Projected Class</td>
<td class='class-confirmed'>Confirmed</td>
<td class='class-attended'>Class Happened</td>
<td class='class-absent'>Absent</td>
<td class='class-cancelled' style='color:white;'>Cancelled</td>
</tr>
</table>

<?php

foreach($data as $center_id => $center_info) {
	if(empty($center_info)) continue;

	$all_batches = $center_info['batches'];
?>
<h3><?php echo $center_info['center_name'] ?></h3>

<?php foreach($all_batches as $batch_id => $batch_info) {
	if(empty($batch_info['days_with_classes'])) continue;
?>
<table class="madsheet data-table info-box-table">
<tr>
<th colspan="2"><?php echo $batch_info['name']; ?></th>
<?php
foreach($batch_info['days_with_classes'] as $day) print "<th>$day</th>";
?>
</tr>

<?php
$row_count = 0;
foreach($batch_info['levels'] as $level_id => $level_info) { // Level start.
?>
<tr class="<?php echo ($row_count % 2) ? 'odd' : 'even' ?>">
<?php
	$level_user_count = 0;
	foreach($level_info['users'] as $teacher) {
		if(!$level_user_count) { 
			?><td rowspan="<?php echo count($level_info['users']); ?>"><?php echo $level_info['name'] ?></td><?php 
		}
		echo "<td>{$teacher['name']}</td>";
	
		foreach($teacher['classes'] as $classes) {
			print "<td class='class-{$classes->status}'>&nbsp;";
			if($classes->substitute_id != 0) print 'S';
			
			?><div class="class-info info-box"><ul>
			<?php if($classes->teacher['status'] != 'cancelled') { ?>
			<li><strong>Volunteer:</strong> <?php echo $all_users[$classes->teacher['user_id']]; ?></li>
			<?php if($classes->teacher['substitute_id'] != 0) { ?><li><strong>Substitute:</strong> <?php echo $all_users[$classes->teacher['substitute_id']]; ?></li><?php } ?>
			<li><strong>Status:</strong> <?php echo ucfirst($classes->teacher['status']); ?></li>
			<?php if($classes->lesson_id) { ?><li><strong>Lesson:</strong> <?php echo $all_lessons[$classes->lesson_id]; ?></li><?php } ?>
			<li><a href="<?php echo site_url('classes/mark_attendence/'.$classes->id) ?>">Mark Attendence</a></li>
			<?php } ?>
			<li><a href="<?php echo site_url('classes/edit_class/'.$classes->id) ?>">Edit Class</a></li>
			</dl>
			</div><?php
			print "</td>";
			$level_user_count++; 
		}
		
		print '</tr>';
	}
	
	$row_count++;
} // Level end ?>
</table>
<br /><br />
<?php } // Batch ?>

<hr />
<?php } // Center ?>


<?php $this->load->view('layout/footer'); ?>
