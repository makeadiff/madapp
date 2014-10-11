<?php 
$title = 'Batches in ' . $center_name;
$this->load->view('layout/header', array('title'=>$title)); ?>

<div id="head" class="clear">
<h1><?php echo $title ?></h1>

<div id="actions">
<a  id="example" class="thickbox button green primary popup" href="<?php echo site_url('batch/create/center/'.$center_id); ?>" class="add">Create New Batch in <?php echo $center_name ?></a>
</div><br class="clear" />

<div id="train-nav">
<ul>
<?php if($this->session->userdata("active_center")) { ?>
<li id="train-prev"><a href="<?php echo site_url('level/index/center/'.$this->session->userdata("active_center"))?>">&lt; Manage Levels</a></li>
<li id="train-top"><a href="<?php echo site_url('center/manage/'.$this->session->userdata("active_center"))?>">^ Manage Center</a></li>
<?php } else { ?>
<li id="train-prev"></li>
<li id="train-top"><a href="<?php echo site_url('center/manageaddcenters')?>">^ Manage Center</a></li>
<?php } ?>
</ul>
</div>
</div>


<table class="data-table" id="main">
<tr><th>Batch</th><th>Volunteers</th><?php if($this->user_auth->get_permission('classes_batch_view')) { ?><th>Mentor View</th><?php } ?>
<?php if($this->user_auth->get_permission('debug')) { ?><th>Add Class Manually</th><?php } ?><th>Mentor</th>
<th colspan="2">Action</th></tr>
<?php 
$day_list = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
$row_class = 'odd';
foreach($all_batches as $batch) {
	$batch_name =  $day_list[$batch->day] . ' ' . date('h:i A', strtotime('2000-01-01 ' . $batch->class_time));
	$row_class = ($row_class == 'odd') ? 'even' : 'odd';
?>
<tr class="<?php echo $row_class ?>">
<td><?php echo $batch_name ?></td>

<td><a href="<?php echo site_url('batch/add_volunteers/'.$batch->id) ?>" class="with-icon add">Add Volunteers to this Batch</a></td>
<?php if($this->user_auth->get_permission('classes_batch_view')) { ?><td><a href="<?php echo site_url('classes/batch_view/'.$batch->id) ?>" class="with-icon calendar">Mentor View</a></td><?php } ?>
<?php if($this->user_auth->get_permission('debug')) { ?><td><a href="<?php echo site_url('classes/add_manually/'.$batch->id.'/'.$center_id) ?>" class="popup with-icon add">Add Class Manually</a></td><?php } ?>
<td><?php echo $all_users[$batch->batch_head_id] ?></td>
<td><a href="<?php echo site_url('batch/edit/'.$batch->id); ?>" class="thickbox edit with-icon primary popup"  class="edit with-icon">Edit</a></td>
<td><a href="<?php echo site_url('batch/delete/'.$batch->id); ?>" class="confirm delete with-icon" title="Delete <?php echo addslashes($batch_name) ?>">Delete</a></td>
</tr>
<?php } ?>
</table>

<?php
$this->load->view('layout/footer'); ?>