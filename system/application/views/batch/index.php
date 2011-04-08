<?php 
$title = 'Batches in ' . $center_name;
$this->load->view('layout/header', array('title'=>$title)); ?>

<div id="head" class="clear">
<h1><?php echo $title ?></h1>

<div id="actions">
<a id="example" class="button primary" href="<?php echo site_url('batch/create/center/'.$center_id); ?>" class="add">Create New Batch in <?php echo $center_name ?></a>
</div>
</div>


<table class="data-table" id="main">
<tr><th>Batch Time</th><th>Volunteers</th><th colspan="2">Action</th></tr>
<?php 
$day_list = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
$row_class = 'odd';
foreach($all_batches as $batch) {
	$batch_name =  $day_list[$batch->day] . ' ' . date('h:i A', strtotime('2000-01-01 ' . $batch->class_time));
	$row_class = ($row_class == 'odd') ? 'even' : 'odd';
?>
<tr class="<?php echo $row_class ?>">
<td><?php echo $batch_name ?></td>

<td><a href="<?php echo site_url('batch/add_volunteers/'.$batch->id) ?>">Add Volunteers to this Batch</a></td>

<td><a href="<?php echo site_url('batch/edit/'.$batch->id); ?>" class="edit with-icon">Edit</a></td>
<td><a href="<?php echo site_url('batch/delete/'.$batch->id); ?>" class="confirm delete with-icon" title="Delete <?php echo addslashes($batch_name) ?>">Delete</a></td>
</tr>
<?php } ?>
</table>

<?php $this->load->view('layout/footer'); ?>