<?php $this->load->view('layout/header', array('title'=>'MAD Sheet')); ?>
<h1>MAD Sheet</h1>
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
foreach($all_centers as $center) {
	if(empty($data[$center->id])) continue;

	$batches = $data[$center->id]['batches'];
?>
<h3><?php echo $center->name ?></h3>

<?php foreach($batches as $id=>$batch_info) {
	if(empty($batch_info['days_with_classes'])) continue;
?>
<table class="madsheet data-table">
<tr>
<th><?php echo $batch_info['name']; ?></th>
<?php
foreach($batch_info['days_with_classes'] as $day) print "<th>$day</th>";
?>
</tr>

<?php
$row_count = 0;

foreach($all_levels[$center->id] as $level) { ?>
<tr class="<?php echo ($row_count % 2) ? 'odd' : 'even' ?>">
<td><?php echo $level->name ?></td>

<?php
foreach($batch_info['levels'][$level->id] as $classes) { 
	print "<td><table class='teacher'><tr>";
	foreach($classes->teachers as $teacher) {
		print "<td class='class-{$teacher['status']}'>&nbsp;";
		if($teacher['substitute_id'] != 0) print 'S';
		?><div class="class-info"><ul>
		<li><strong>Volunteer:</strong> <?php echo $all_users[$teacher['user_id']]; ?></li>
		<?php if($teacher['substitute_id'] != 0) { ?><li><strong>Substitute:</strong> <?php echo $all_users[$teacher['substitute_id']]; ?></li><?php } ?>
		<li><strong>Status:</strong> <?php echo ucfirst($teacher['status']); ?></li>
		<li><a href="<?php echo site_url('classes/edit_class/'.$classes->id) ?>">Edit Class</a></li>
		<li><a href="<?php echo site_url('classes/mark_attendence/'.$classes->id) ?>">Mark Attendence</a></li>
		</dl>
		</div><?php
		print "</td>";
	}
	print "</tr></table></td>";
}
?>

</tr>
<?php 
	$row_count++;
} // Level ?>
</table>

<?php } // Batch ?><br /><hr />
<?php } // Center ?>


<?php $this->load->view('layout/footer'); ?>
