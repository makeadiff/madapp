<?php
$this->load->view('layout/header', array('title'=>'Kids Attendance'));
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/sections/analysis/class_progress_report.css">
<script type="text/javascript" src="<?php echo base_url() ?>js/sections/classes/madsheet.js"></script>

<?php
foreach($data as $center_id => $center_info) {
	if(empty($center_info)) continue;
	//if($center_id != 147) continue; // :DEBUG: Use this to localize the issue. I would recommend keeping this commented. You'll need it a lot.
?>
<?php //print_r($center_info); ?>
<h3><?php echo $center_info['center_name'] ?></h3>
<table class="madsheet data-table info-box-table">
<tr>
<th>Level</th>
<th>Kids</th>
<?php
foreach($center_info['days_with_classes'] as $day) print "<th>$day</th>";
?>
<th>Aggr</th>
</tr>


<?php
$row_count = 0;
$var=0;
$netSum=0;
$i=0;
$comppercentage=80; //print_r($attendance);
$netvalue = array();
$level_attendence = array();

foreach($all_levels[$center_id] as $level_info) { // Level start.
?>
<tr class="<?php echo ($row_count % 2) ? 'odd' : 'even' ?>">
<td nowrap='nowrap'><?php echo $level_info->name ?></td>
<td><?php echo $all_kids[$level_info->id] ?></td>
<?php 
	$var += $all_kids[$level_info->id];
	$sum=0;
	$totNumber=0;
	$ar="";
	$tets="";
	foreach($center_info['days_with_classes'] as $date_index => $day) {
		if(!isset($center_info['class'][$level_info->id][$date_index])) { 
			$classdateid = 0;
		} else {
			$classdateid = $center_info['class'][$level_info->id][$date_index]->id;
		}
		
		if(!isset($center_info['class'][$level_info->id][$date_index])) { 
			$status="null"; 
			$attendanses =0;
		} else {
			$attendanses=$attendance[$center_info['class'][$level_info->id][$date_index]->id];
			$test=$center_info['class'][$level_info->id][$date_index]; 
			$status=$test->status;
		}
		$percentage = 0;
		if($all_kids[$level_info->id]) {
			$percentage = ($attendanses * 100) / $all_kids[$level_info->id];
		}
		
		if($status != "cancelled" and $status != 'null' and $classdateid != 0 and $status != 'projected') $totNumber++;
		
		$class_type = 'good';
		if($classdateid == 0 or $status == 'projected') $class_type = 'no-data';
		elseif($status == "cancelled" or $status == 'null') $class_type = 'cancelled';
		elseif($percentage < $comppercentage ) $class_type = 'low-attendance';
	?>

    <td class="class-<?php echo $class_type ?>"><?php
    if($class_type == 'no-data' or $class_type == 'cancelled') echo '&nbsp;';
    else { ?><a href="<?php echo site_url('classes/mark_attendence/'.$classdateid); ?>"><?php  
		//Attendance ...
		echo $attendanses;
		$sum += $attendanses;
	?></a><?php } ?></td>
 	<?php
		$level_attendence[$level_info->id][$date_index] = $attendanses;
	}
	
	?>
<td nowrap='nowrap'><?php if($totNumber) {
	$netSum += $sum / $totNumber; 
	echo round($sum/$totNumber, 2); 
}?></td>
</tr>
<?php
	$row_count++;
} // Level end ?>
<td nowrap='nowrap'>Total</td>	
<td nowrap='nowrap'><?php echo $var;?></td>

<!--Get Total Rows-->
<?php
foreach($center_info['days_with_classes'] as $date_index => $day) { // All Days
	$sum = 0;
	print '<td>';
	foreach($all_levels[$center_id] as $level_info) { // All Levels
		if(isset($level_attendence[$level_info->id][$date_index])) $sum += $level_attendence[$level_info->id][$date_index];
	}
	print $sum . '</td>';
}

if($var) $perc = ($netSum * 100)/$var;
else $perc = 0;
$class_status = 'good';
if($perc < $comppercentage) $class_status = 'low-attendance';
?>
<td nowrap='nowrap' class="class-<?php echo $class_status ?>"><?php echo round($netSum, 1);?></td>
</table>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
<hr />
<?php 
}  // Center ?>


<?php $this->load->view('layout/footer');