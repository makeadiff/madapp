<?php
$title = 'Adding Volunteers to '.$batch_name.' Batch';
$this->load->view('layout/header', array('title'=>$title)); ?>

<h1><?php echo $title ?></h1>

<form action="<?php echo site_url("batch/add_volunteers_action") ?>" method="post">

<?php foreach($levels_in_batch as $level) { ?>
<h3><?php echo $level->name ?></h3>

<select name="teachers_in_level[<?php echo $level->id ?>][]" multiple="multiple">
<?php foreach($teachers_in_center as $user) { ?>
<option value="<?php echo $user->id ?>"<?php
	// If the current user belongs to this level, mark him as selected.
	if(!empty($level_teacher[$level->id][$user->id])) print ' selected="selected"';
?>><?php echo $user->name ?></option>
<?php } ?>
</select>
<?php } ?>
<br />

<?php 
echo form_hidden('batch_id', $batch->id);
echo '<label for="action">&nbsp;</label>';echo form_submit('action', "Save");
?>
</form>

<?php $this->load->view('layout/footer'); ?>