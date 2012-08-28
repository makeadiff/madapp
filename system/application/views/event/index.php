<div id="content" class="clear">
<!-- Main Begins -->
<div id="main" class="clear"> 

<div id="head" class="clear">
<h1><?php echo $title; ?></h1>

<div id="actions">
<?php if($this->user_auth->get_permission('event_add')) { ?>
<a href="<?php echo site_url('event/addevent')?>" class="thickbox button green primary popup" name="Add Event">Add Events</a>
<?php } ?>
</div><br class="clear" />


</div><br />

<div id="kids_list">
<table id="tableItems" class="clear data-table" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th class="colName left sortable">Name</th>
    <th class="colStatus sortable">Starts On</th>
	<th class="colStatus">Place</th>
	<th class="colActions">Type</th>
	<th class="colActions">Manage</th>
	<th class="colActions">Action</th>
</tr>
</thead>
<tbody>

<?php 
$statusIco = '';
$statusText = '';
//$content = $details->result_array();
$count = 0;
$event_types = array(
	'process' => 'Process Training',
	'curriculum' => 'Curriculum Training',
	'teacher' => 'Teacher Training I',
	'teacher2' => 'Teacher Training II',
	'avm' => 'City Circle Time',
	'coreteam_meeting' => 'Core Team Meeting',
	'admin_meeting' => 'Admin Meeting',
	'monthly_review'=> 'Monthly Review',
);
foreach($details as $row) {	
	$count++;
	$shadeClass = 'even';
	if($count % 2) $shadeClass = 'odd';
?> 
<tr class="<?php echo $shadeClass; ?>" id="group">
    <td class="colName left"><?php echo $row->name ?></td>
    <td class="colCount"><?php echo date('dS M, Y h:i A', strtotime($row->starts_on)); ?></td> 
	<td class="colPosition"><?php echo $row->place; ?></td>
    <td class="colPosition"><?php echo $event_types[$row->type]; ?></td>
    <td class="colPosition"><a href="<?php echo site_url('event/user_event/'.$row->id)?>" class="thickbox  popup">manage</a> | <a href="<?php echo site_url('event/mark_attendence/'.$row->id)?>" class="thickbox  popup">attended</a></td>
    <td class="colActions right"> 
    <?php if($this->user_auth->get_permission('event_edit')) { ?>
    <a href="<?php echo site_url('event/event_edit/'.$row->id)?>" class="thickbox icon edit popup" name="Edit Event: <?php echo $row->name;?>">Edit</a>
	<?php } ?>
    <?php if($this->user_auth->get_permission('event_delete')) { ?>
    <a class="actionDelete icon delete confirm" href="<?php echo site_url('event/event_delete/'.$row->id); ?>">Delete</a>
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
