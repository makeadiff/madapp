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
//print_r($center_info);
foreach($center_info['days_with_classes'] as $day) print "<th>$day</th>";

?>
<th>Classes Attended</th>

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
<td><?php foreach($all_kids[$level_info->id] as $names){?><div style="border-bottom:1px solid #063; "><?php   echo $names->name.'<br>'; ?> </div><?php }?></td>


    <td class="class">
    <?php 
	
	$sum=0;
	$totNumber=0;
	$ar="";
	$tets="";
	foreach($all_kids[$level_info->id] as $names){
		
	foreach($center_info['days_with_classes'] as $date_index => $day) { 
	
		$totNumber++;
		if(!isset($center_info['class'][$level_info->id][$date_index])) { 
			$classdateid = 0;
		} else {
			$classdateid = $center_info['class'][$level_info->id][$date_index]->id;
		}
		
		if(!isset($center_info['class'][$level_info->id][$date_index])) { 
			$status="null"; 
			$examMarks =0;
		} else {
			$examMarks=$attendance[$center_info['class'][$level_info->id][$date_index]->id];
			
			//print_r($examMarks);
		}
	
	?>
    <?php  
		//Attendance ...
		if(sizeof($examMarks) >0){foreach($examMarks as $y=>$x){
			//print_r($x);
			//echo $y;
			
		}
		}
	?></td>
 	<?php
		$level_attendence[$level_info->id][$date_index] = $examMarks;
	}
	}
	
	?>
<td nowrap='nowrap'>Classes</td>
<td>Agg</td>
</tr><td nowrap='nowrap'>Total</td>
<?php
	$row_count++;
	
} // Level end ?>
	
<td nowrap='nowrap'><?php echo $var;?></td>

<!--Get Total Rows-->
<?php
foreach($center_info['days_with_classes'] as $date_index => $day) { // All Days
	$sum = 0;
	print '<td>';
	/*foreach($all_levels[$center_id] as $level_info) { // All Levels
		if(isset($level_attendence[$level_info->id][$date_index])) $sum += $level_attendence[$level_info->id][$date_index];
	}*/
	print $sum . '</td>';
}

//$perc= ($netSum*100)/$var;
//$class_status = 'good';
//if($perc < $comppercentage) $class_status = 'low-attendance';
?>
<td nowrap='nowrap' class="class"><?php //echo round($netSum, 1);?></td>
</table>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
<hr />
<?php 
}  // Center ?>


<?php $this->load->view('layout/footer');