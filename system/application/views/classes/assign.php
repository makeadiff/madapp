<?php $this->load->view('layout/flatui/header', array('title' => $title)); ?>
<script type="text/javascript">
	var user_mapping = <?php echo json_encode($user_mapping); ?>;
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

<form action="" method="post">
<table class="table">
<tr><th>Teacher</th><th>Batch</th><th>Class Name</th><th>Subject</th></tr>
<?php foreach($all_users as $user_id => $user_name) { ?>
<tr>
<td><?php echo $user_name ?></td>
<td><select name="batch_id[<?php echo $user_id ?>]" id="batch-<?php echo $user_id ?>" class="batch">
<option value="0">None</option>
<?php foreach($all_batches as $batch_id => $batch_name) { ?>
<option value="<?php echo $batch_id ?>" <?php 
	//if(i($user_mapping, $student_id) == $batch_id) echo 'selected="selected"'; 
	?>><?php echo $batch_name ?></option>
<?php } ?>
</select></td>

<td><select name="level_id[<?php echo $user_id ?>]" id="level-<?php echo $user_id ?>">
<option value="0">None</option>
</select></td>

<td><select name="subject_id[<?php echo $user_id ?>]">
<?php foreach($all_subjects as $subject_id => $subject_name) { ?>
<option value="<?php echo $subject_id ?>" <?php 
	// if(i($user_mapping, $student_id) == $subject_id) echo 'selected="selected"'; 
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
