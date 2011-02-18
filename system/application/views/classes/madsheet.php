<?php $this->load->view('layout/header', array('title'=>'MAD Sheet')); ?>
<h1>MAD Sheet</h1>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/madsheet.css">

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
	if(empty($class_days[$center->id])) continue;

	$batches = $class_days[$center->id]['batchs'];
?>
<h3><?php echo $center->name ?></h3>

<?php foreach($batches as $id=>$batch_info) {
	if(empty($batch_info['days_with_classes'])) continue;
	
	//dump($batch_info);
?>
<table class="madsheet data-table">
<tr>
<th colspan="2"><?php echo $batch_info['name']; ?></th>
<?php
foreach($batch_info['days_with_classes'] as $day) print "<th>$day</th>";
?>
</tr>

<?php
$row_count = 0;

foreach($all_levels[$center->id] as $level) {
	$level_user_count = 0;
	foreach($batch_info[$level->id]['users'] as $user) {
?>
<tr class="<?php echo ($row_count % 2) ? 'odd' : 'even' ?>">
<?php if(!$level_user_count) { ?><td rowspan="<?php echo count($batch_info[$level->id]['users']); ?>"><?php echo $level->name ?></td><?php } ?>

<td><?php echo $all_users[$user->id]->name ?></td>

<?php
foreach($batch_info['levels'][$level->id] as $classes) {
	if($classes->user_id != $user->id) continue;
	print "<td class='class-{$classes->status}'>&nbsp;";
	if($classes->substitute_id != 0) print 'S';
	print "</td>";
}
?>

</tr>
<?php 
	$level_user_count++; 
	$row_count++;
} // User list ?>

<?php 
} // Level ?>
</table>

<?php } // Batch ?>
<?php } // Center ?>

<?php $this->load->view('layout/footer'); ?>
