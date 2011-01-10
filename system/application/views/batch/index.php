<?php 
$title = 'Batches in ' . $center_name;
$this->load->view('layout/header', array('title'=>$title)); ?>
<h1><?php echo $title ?></h1>

<table class="data-table">
<tr><th>Batch Time</th><th colspan="2">Volunteers</th><th colspan="2">Action</th></tr>
<?php 
$day_list = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
foreach($all_batches as $batch) {
	$batch_name =  $day_list[$batch->day] . ' ' . date('h:i A', strtotime('2000-01-01 ' . $batch->class_time));
?>
<tr>
<td><?php echo $batch_name ?></td>

<td><a href="<?php echo site_url('user/index/batch/'.$batch->id) ?>">Show Volunteers</a></td>
<td><a href="<?php echo site_url('batch/add_volunteers/'.$batch->id) ?>">Add Volunteers to this Batch</a></td>

<td><a href="<?php echo site_url('batch/edit/'.$batch->id); ?>" class="edit">Edit</a></td>
<td><a href="<?php echo site_url('batch/delete/'.$batch->id); ?>" class="confirm delete" title="Delete <?php echo addslashes($batch_name) ?>">Delete</a></td>
</tr>
<?php } ?>
</table>
<a href="<?php echo site_url('batch/create/center/'.$center_id); ?>" class="add">Create New Batch in <?php echo $center_name ?></a></td>

<?php $this->load->view('layout/footer'); ?>