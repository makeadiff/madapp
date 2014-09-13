<div id="content" class="clear">
<!-- Main Begins -->
<div id="main" class="clear"> 

<div id="head" class="clear">
<h1><?php echo $title; ?></h1>

<div id="actions">
<?php if($this->user_auth->get_permission('task_add')) { ?>
<a href="<?php echo site_url('task/addtask')?>" class="thickbox button green primary popup" name="Add Event">Add Task</a>
<?php } ?>
</div><br class="clear" />
</div><br />
<?php
$all_verticals = array(
	'hr'	=> 'Human Resources',
	'pr'	=> 'Public Relations',
	'cr'	=> 'Corporate Relations',
	'finance'=>'Finance',
	'ops'	=> 'Operations',
	'eph'	=> 'English Project Head',
	'placements'=>'Placements'
);
?>

<div id="kids_list">
<table id="tableItems" class="clear data-table" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th class="colName left sortable">Name</th>
    <th class="colStatus sortable">Credit</th>
	<th class="colActions">Type</th>
	<th class="colActions">Action</th>
</tr>
</thead>
<tbody>

<?php 
$statusIco = '';
$statusText = '';
//$content = $details->result_array();
$count = 0;
foreach($details as $row) {	
	$count++;
	$shadeClass = 'even';
	if($count % 2) $shadeClass = 'odd';
?> 
<tr class="<?php echo $shadeClass; ?>" id="group">
    <td class="colName left"><?php echo $row->name ?></td>
	<td class="colPosition"><?php echo $row->credit; ?></td>
    <td class="colPosition"><?php echo $all_verticals[$row->vertical]; ?></td>
    
    <td class="colActions right"> 
    <?php if($this->user_auth->get_permission('task_edit')) { ?>
    <a href="<?php echo site_url('task/task_edit/'.$row->id)?>" class="thickbox icon edit popup" name="Edit Event: <?php echo  $row->name;?>">Edit</a>
	<?php } ?>
    <?php if($this->user_auth->get_permission('task_delete')) { ?>
    <a class="actionDelete icon delete confirm" href="<?php echo site_url('task/task_delete/'.$row->id); ?>">Delete</a>
	<?php } ?>
    </td>
</tr>

<?php  }?>
</tbody>
</table>
</div>
<?php if(!$count) {
	   echo "<div class='no-records'>- no records found -</div>";
} ?>

</div>
<br /><br />
	
</div>
