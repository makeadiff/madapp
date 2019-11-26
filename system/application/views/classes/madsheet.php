<?php
$this->load->view('layout/header', array('title'=>'MAD Sheet'));
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/sections/classes/madsheet.css">
<script type="text/javascript" src="<?php echo base_url() ?>js/sections/classes/madsheet.js"></script>

<h3>Index</h3>
<ul>
<?php foreach($data as $center_id => $center_info) {
	if(empty($center_info)) continue;
	print "<li><a href='#center-$center_id'>$center_info[center_name]</a></li>\n";
} ?>
</ul>

<h3>Legend</h3>
<table class="legend">
<tr>
<td class='class-projected'>Projected Class</td>
<td class='class-attended'>Class Happened</td>
<td class='class-absent'>Absent</td>
<td class='class-cancelled' style='color:white;'>Cancelled</td>
</tr>
</table>

<form action="<?php echo site_url('classes/bulk_add_class')?>" method="post">
<?php
foreach($data as $center_id => $center_info) {
	if(empty($center_info)) continue;

	$all_batches = $center_info['batches'];
?>
<h3 id="center-<?php echo $center_id ?>"><a href="<?php echo site_url('center/manage/'.$center_id) ?>"><?php echo $center_info['center_name'] ?></a></h3>

<?php foreach($all_batches as $batch_id => $batch_info) {
	if(empty($batch_info['days_with_classes'])) continue;
?>
<table class="madsheet data-table info-box-table">
<tr>
<th colspan="3"><a href="<?php echo site_url('batch/index/center/' . $center_id) ?>"><?php echo $batch_info['name']; ?></a> 
	(<a href="<?php echo site_url('/user/view/' . $batch_info['batch_head']->id) ?>"><?php echo $batch_info['batch_head']->name ?></a>)</th>
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
			?><td rowspan="<?php echo count($level_info['users']); ?>" nowrap='nowrap' title="<?php echo "Level ID : " . $level_id ?>"><?php echo $level_info['grade'] . $level_info['name'] ?></td><?php 
		}
		?><td nowrap='nowrap'><a href="<?php echo site_url('/user/view/'.$teacher['id']); ?>"<?php
			if($teacher['user_type'] == 'let_go') echo ' class="let_go"';
		?>><?php if(!$teacher['name']) echo '[Volunteer Let Go]';
				else echo $teacher['name']; ?></a></td>
		<td nowrap='nowrap'><a href="<?php echo site_url('user/credithistory/'. $teacher['id']); ?>"><?php echo $teacher['credit']; ?></a></td>
		
		<?php
		$class_count= 0;
		foreach($teacher['classes'] as $classes) {
			// This to make sure that the new classes that was started after missing a lot of class works correctly. For eg. if level 1 starts in Sunday back on 12 Aug, but level 2 started only on 17 Aug(sun), this part will handle it correctly.
			while(isset($batch_info['days_with_classes'][$class_count]) and date('d M',strtotime($classes->class_on)) != $batch_info['days_with_classes'][$class_count]) { 
				//if($class_count > 5) dump($classes); :DEBUG:
				print "<td class='class-empty'>";
				if($this->user_auth->get_permission('debug')) {
					$timestamp = strtotime($classes->class_on);
					$class_on = date('Y-m-d H:i:s', strtotime($batch_info['days_with_classes'][$class_count]." ".date('Y H:i:s', $timestamp)));
				?>
				<div class="class-info info-box"><a href="<?php echo site_url("classes/add_class_manually/$level_id/$batch_id/".urlencode($class_on)."/$teacher[id]"); ?>">Create Class</a></div>
				<input type="checkbox" name="create_class[]" class="create_class_checkboxes" value="<?php echo "$level_id/$batch_id/".urlencode($class_on)."/$teacher[id]" ?>" />
				<?php 
				}
				else print "&nbsp;";
				print "</td>";
				$class_count++;
				if($class_count > 100) exit; // In case something goes terribly, terribly wrong.
			}
			
			
			print "<td class='class-{$classes->user_status}'>&nbsp;";
			if($classes->substitute_id != 0) print 'S';
			
			?><div class="class-info info-box"><ul>
			<?php if($classes->teacher['status'] != 'cancelled') { ?>
			<li><strong>Volunteer:</strong> <?php echo $all_users[$classes->teacher['user_id']]; ?></li>
			<?php if($classes->teacher['substitute_id'] != 0) { ?><li><strong>Substitute:</strong> <?php echo $all_users[$classes->teacher['substitute_id']]; ?></li><?php } ?>
			<li><strong>Status:</strong> <?php echo ucfirst($classes->teacher['status']); ?></li>
			
			<!-- <?php if($classes->lesson_id) { ?><li><strong>Lesson:</strong> <?php echo $all_lessons[$classes->lesson_id]; ?></li><?php } ?>
			<li><a href="<?php echo site_url('classes/mark_attendence/'.$classes->id) ?>">Mark Attendence</a></li>
			<?php } ?>
			<li><a href="<?php echo site_url('classes/edit_class/'.$classes->id) ?>">Edit Class</a></li> -->
			<li>Date: <?php echo date('d\<\s\u\p\>S\<\/\s\u\p\> M', strtotime($classes->class_on)); ?></li>
			<?php if($this->user_auth->get_permission('debug')) { ?>
			<li><a href="<?php echo site_url('classes/delete/'.$classes->id) ?>">Delete Class</a></li>
			<?php } ?>
			</ul></div><?php
			print "</td>";
			$level_user_count++; 
			$class_count++;
		}
		
		print '</tr>';
	}
	
	$row_count++;
} // Level end ?>
</table>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br /><br />
<?php } // Batch ?>

<hr />
<?php } // Center ?>

<?php if($this->user_auth->get_permission('debug')) { ?>
<input type="button" name="check_all" value="Check All" onclick="jQuery('.create_class_checkboxes').prop('checked', true);" />
<input type="submit" name="action" value="Bulk Create Class" />
</form>
<?php } ?>

<?php $this->load->view('layout/footer');
