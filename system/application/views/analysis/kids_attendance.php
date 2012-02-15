<?php
$this->load->view('layout/header', array('title'=>'Class Progress Report'));
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/sections/analysis/class_progress_report.css">
<script type="text/javascript" src="<?php echo base_url() ?>js/sections/classes/madsheet.js"></script>

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
</tr>
<?php
$row_count = 0;
$var=0;
$netSum=0;
$i=0;
$comppercentage=80;
foreach($all_levels[$center_id] as $level_info) { // Level start.
?>
<tr class="<?php echo ($row_count % 2) ? 'odd' : 'even' ?>">
<td nowrap='nowrap'><?php echo $level_info->name ?></td>
<td><?php echo $all_kids[$level_info->id] ?></td>
<?php $var+=$all_kids[$level_info->id];?>
<?php
	$last_lesson_id = 0;
	$repeat_count = 0;
	$sum=0;
	$totNumber=0;
	$ar="";
	$tets="";
	foreach($center_info['days_with_classes'] as $date_index => $day) {
	$totNumber++;
		if(!isset($center_info['class'][$level_info->id][$date_index]))  {$classdateid =0; } else {
		$classdateid = $center_info['class'][$level_info->id][$date_index]->id;
		if($classdateid != $last_lesson_id and $classdateid) {
			$last_lesson_id = $classdateid;
			$repeat_count = 0;
		} else {
			$repeat_count++;
		}
		}
		$class_type = 'good';
		if($repeat_count > 2) $class_type = 'repeated';
		if($classdateid == 0) $class_type = 'no-data';
	?>
    <?php //print_r($test); ?>
   <?php  if(!isset($center_info['class'][$level_info->id][$date_index])){ $status="null"; $attendanses =0;}else {
		$attendanses=$attendance[$center_info['class'][$level_info->id][$date_index]->id];
		$test=$center_info['class'][$level_info->id][$date_index]; 
		$status=$test->status;
		//echo $status=$attendance[$center_info['class'][$level_info->id][$date_index]->class_on]; 
	}?>
    <?php $percentage=($attendanses * 100)/$all_kids[$level_info->id];?>
    <?php if($status == "absent"){ ?>
     <td class="class-<?php echo $class_type ?>" style="background:#000000">
    <?php }else { ?>
    <?php if($percentage < $comppercentage ){ ?>
   
	<td class="class-<?php echo $class_type ?>" style="background:#FF0000;">
    <?php } else { ?>
    <td class="class-<?php echo $class_type ?>">
    <?php } } 
	//Attendance ...
		echo $attendanses;
		$sum+=$attendanses;
	?></td>
 	<?php $netvalue[$date_index]=$attendanses;
	
			ksort($netvalue);
			 
		  ?>
<?php $test=0; }$temp[$i++]=$netvalue;?>
<td nowrap='nowrap'><?php $netSum+=$sum/$totNumber; echo round($sum/$totNumber, 2);?></td>
</tr>
<?php
	$row_count++;
} // Level end ?>
<td nowrap='nowrap'>Total</td>	
<td nowrap='nowrap'><?php echo $var;?></td>

<!--Get Total Rows-->
<?php  foreach($netvalue as $y=> $row) { ?>
<td>
<?php  $sum=0; foreach($temp as $kk=> $yy) { if(isset($temp[$kk][$y])) $sum+=$temp[$kk][$y];} echo $sum.'<br>';?>
</td>
<?php } ?>



<?php $perc=($netSum*100)/$var; ?><?php if($perc < $comppercentage) {?>
<td nowrap='nowrap' style="background:#FF0000;"><?php echo $netSum;?></td>
<?php } else {?><td nowrap='nowrap'><?php echo $netSum;?></td> <?php } ?>
</table><br />
<hr />
<?php 

 }  // Center ?>


<?php $this->load->view('layout/footer');