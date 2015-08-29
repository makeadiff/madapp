<?php $this->load->view('layout/flatui/header', array('title' => $title)); ?>
<script type="text/javascript">
	var batch_level_user_hirarchy = <?php echo json_encode($batch_level_user_hirarchy); ?>;
	var all_levels = <?php echo json_encode($all_levels); ?>;
</script>
<script type="text/javascript" src="<?php echo base_url() ?>js/sections/classes/assign.js"></script>

<style type="text/css">
	table { color: #fff; }
	select { color: #000; }
</style>
<div id="content" class="clear">
<div id="main" class="clear"> 
<div id="head" class="clear">
<h1 class="title"><?php echo $title; ?></h1>

<ul class="text-muted">
<li>Total Teachers in this city: <?php echo count($all_users) ?></li>
<li>Assigned Teacher for this center: <?php echo $assigned_teacher_count; ?></li>
<li>Assigned Teacher in this city: <?php echo count($all_assigned_teachers); ?></li>
<li>Total Unassigned Teacher Count: <?php echo count($all_users) - count($all_assigned_teachers); ?></li>
</ul>

<p>Users who are <strong>bolded</strong> have already been assigned to a class.</p>

<form action="" method="post">
<table class="table">
<tr><th>Teacher</th><th>Batch</th><th>Class Section</th><th>Subject</th></tr>
<?php foreach($all_users as $user_id => $user_name) { 
	$show_level = 0;
	$show_level_of_batch = 0;
?>
<tr>
<td <?php if(isset($all_assigned_teachers[$user_id])) echo 'style="font-weight:bold;" class="assigned"' ?>><?php echo $user_name->name ?></td>
<td><select name="batch_id[<?php echo $user_id ?>]" id="batch-<?php echo $user_id ?>" class="batch">
<option value="0">None</option>
<?php foreach($all_batches as $batch_id => $batch_name) { ?>
<option value="<?php echo $batch_id ?>" <?php
	if(isset($user_mapping[$user_id]))
	foreach($user_mapping[$user_id] as $batch_level_info) {
		if(i($batch_level_info, 'batch_id') == $batch_id) {
			echo 'selected="selected"';
			$show_level = $batch_level_info['level_id'];
			$show_level_of_batch = $batch_id;
		}
	}
	?>><?php echo $batch_name ?></option>
<?php } ?>
</select></td>

<td><select name="level_id[<?php echo $user_id ?>]" id="level-<?php echo $user_id ?>">
<?php if(!$show_level) { ?>
<option value="0">None</option>
<?php } else { ?>
<?php foreach($all_levels[$show_level_of_batch] as $level_id => $level_name) { ?>
<option value="<?php echo $level_id ?>" <?php
		if($show_level == $level_id) echo 'selected="selected"';
	?>><?php echo $level_name ?></option>
<?php } ?>
<?php } ?>
</select></td>

<td><select name="subject_id[<?php echo $user_id ?>]">
<?php foreach($all_subjects as $subject_id => $subject_name) { ?>
<option value="<?php echo $subject_id ?>" <?php 
	if($all_users[$user_id]->subject_id == $subject_id) echo 'selected="selected"'; 
	?>><?php echo $subject_name ?></option>
<?php } ?>
</select></td>
</tr>
<?php } ?>
</table>
<input type="submit" name="action" value="Save" class="btn btn-primary" />
</form>

</div>
</div>
</div>

<?php $this->load->view('layout/flatui/footer');
