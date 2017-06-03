<div id="kids_list">
<table id="tableItems" class="clear data-table" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th class="colCheck1">ID</th>
	<th class="colName left sortable">Name</th>
    <th class="colStatus">Center</th>
	<th class="colStatus">Reason</th>
   <th class="colActions">Actions</th>
</tr>
</thead>
<tbody>
<?php
$reasons_for_leaving = array(
	"duplicate"		=> "Duplicate copy of existing child",
	"repatriated"	=> "Child was repatriated with their parents",
	"disciplinary-expulsion"	=> "Child was sent away from the shelter due to discipline concerns",
	"shifted"		=> "Child shifted to another shelter",
	"cleared-12"	=> "Child cleared 12th standard and is moving to an after care city.",
	"shelter-shutdown"	=> "Shelter shut down and kids were sent away"
);

if(isset($center_name)) {
	$center_name = $center_name->result_array();
	foreach($center_name as $row){
		$center_name = $row['name'];
	}
}

$norecord_flag = 1;
$shadeFlag = 0;
$shadeClass = ''; 
$statusIco = '';
$statusText = '';
$content = $details->result_array();
foreach($content as $row) {	
	$norecord_flag = 0;

	if($shadeFlag == 0) {
  		$shadeClass = 'even';
		$shadeFlag = 1;
  	} else if($shadeFlag == 1) {
  		$shadeClass = 'odd';
		$shadeFlag = 0;
	}
?> 
<tr class="<?php echo $shadeClass; ?>" id="group">
    <td class="colCheck1"><?php echo $row['id']; ?></td>
    <td class="colName left"><?php echo $row['name']; ?></td>
    <td class="colStatus" style="text-align:left"><?php echo isset($center_name) ? $center_name : $row['center_name'] ;?></td>
    <td><?php echo isset($reasons_for_leaving[$row['reason_for_leaving']]) ? $reasons_for_leaving[$row['reason_for_leaving']] : $row['reason_for_leaving']; ?></td>
    
    <td class="colActions right"> 
    <?php if($this->user_auth->get_permission('kids_delete')) { ?><a href="<?php echo site_url('kids/undelete/'.$row['id'])?>" class="with-icon edit" 
    		name="Un-Delete student : <?= strtolower($row['name']) ?>">Un-Delete</a><?php } ?>
    </td>
</tr>

<?php  }?>
</tbody>
</table>
<?php 
if($norecord_flag == 1) { 
	if($currentPage == '0') {
		echo "<div style='background-color: #FFFF66;height:30px;text-align:center;padding-top:10px;font-weight:bold;' >- no records found -</div>";
	}
}