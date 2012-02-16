<div id="content" class="clear">
	<div id="main" class="clear">
    	<div id="head" class="clear">
        	<h1><?php echo $title; ?></h1>
        	
        	<?php if($this->user_auth->get_permission('exam_add')) { ?><div id="actions">
			<a href="<?php echo site_url('exam/add_exam')?>" class="popup button green primary" name="Add New Exam">Add New Exam</a>
			</div><?php } ?>
</div>

<table id="tableItems" class="clear data-table" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th>Exam Name</th>
	<?php if($this->user_auth->get_permission('exam_add_event')) { ?><th>Add Results</th><?php } ?>
	<th>Level</th>
    <th>Details</th>
    <?php if($this->user_auth->get_permission('exam_delete')) { ?><th class="colActions">Actions</th><?php } ?>
</tr>
</thead>
<tbody>

<?php
$norecord_flag = 1;
$shadeClass = '';
$statusIco = '';
$statusText = '';
$i=0;
$shadeClass = 'even';

foreach($details as $row) {	
	$i++;
	$norecord_flag = 0;

	if($shadeClass == 'even') $shadeClass = 'odd';
	else $shadeClass = 'even';
?>
<tr class="<?php echo $shadeClass; ?>">
    <td><?php echo $row->name; ?></td>
    <?php if($this->user_auth->get_permission('exam_add_event')) { ?><td><a href="<?php echo site_url('exam/add_event/'.$row->id) ?>" class="popup">Add Results</a></td><?php } ?>
    <td><?php echo ucfirst($row->level); ?></td>
	<td><a href="<?php echo site_url('exam/view_exam_details/'.$row->id) ?>" class="popup primary" id="groupmanage-<?php echo $row->id; ?>" name="Details of <?php echo strtolower($row->name) ?>">View Details</a></td>
	<?php if($this->user_auth->get_permission('exam_delete')) { ?><td><a href="<?php echo site_url('exam/delete/'.$row->id) ?>" class="confirm" title="Delete '<?php echo $row->name; ?>' Exam">Delete</a></td><?php } ?>
</tr>

<?php } ?>
</tbody>
</table><br />

<?php if($norecord_flag == 1) {
	   echo "<div class='no-records'>No records found</div>";
} ?>
</div>

</div>
