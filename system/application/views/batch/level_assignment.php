<?php 
$title = 'Class-Batch Assignment for ' . $center_name;
$this->load->view('layout/flatui/header', array('title'=>$title)); ?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/sections/batch/level_assignment.css">

<div class="container-fluid">
<div class="board transparent-container">
<h1 class="title"><?php echo $title ?></h1>
<br />
<form action="<?php echo site_url('batch/level_assignment_save') ?>" method="post">
<div class="row">

<?php 
$index = 0;
$day_list = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
foreach($all_batches as $batch) { 
	$index++; ?>
    <div class="col-md-3 col-sm-12">
    	<div class="box">
        <?php 
		$batch_name =  $day_list[$batch->day] . ' ' . date('h:i A', strtotime('2000-01-01 ' . $batch->class_time));
		echo "<strong class='batch-name'>$batch_name</strong><br />";

		foreach($all_levels as $level) { ?>
			<input type="checkbox" name="batch_level_connection[<?php echo $batch->id ?>][<?php echo $level->id ?>]" id="batch-<?php echo $batch->id ?>-level-<?php echo $level->id ?>" 
				<?php
					foreach($all_batch_level_connections as $con) {
						if($batch->id == $con->batch_id and $level->id == $con->level_id) {
							print "checked='checked'";
						}
					}
				?> />
			<label for="batch-<?php echo $batch->id ?>-level-<?php echo $level->id ?>"><?php echo $level->grade . ' ' . $level->name; ?></label><br />
		<?php }
		?>
		</div>
    </div>
<?php } ?>

</div>

<input type="hidden" name="center_id" value="<?php echo $center_id ?>" />
<input type="submit" name="action" value="Save" class="btn-primary btn" />
</form>
</div>
</div>

<?php
$this->load->view('layout/flatui/footer');