<?php
$title = 'Adding Volunteers to '.$batch_name.' Batch';
$this->load->view('layout/header', array('title'=>$title)); ?>

<div id="head" class="clear"><h1><?php echo $title ?></h1></div>

<form action="<?php echo site_url("batch/add_volunteers_action") ?>" method="post">

<table>
<tr>
<?php foreach($levels_in_center as $level) { ?>
<td width="200"><h3><?php echo $level->name ?></h3>

<select name="teachers_in_level[<?php echo $level->id ?>][]" multiple="multiple">
<?php foreach($all_teachers as $user) { ?>
<option value="<?php echo $user->id ?>"<?php
	// If the current user belongs to this level, mark him as selected.
	if(!empty($level_teacher[$level->id][$user->id])) print ' selected="selected"';
?>><?php echo $user->name ?></option>
<?php } ?>
</select><br /><br />

<label for="volunteer_requirement[<?php echo $level->id ?>]">Extra Volunteers Required</label>
<input type="text" size="2" name="volunteer_requirement[<?php echo $level->id ?>]" value="<?php 
	echo empty($volunteer_requirement[$level->id]) ? 0 : $volunteer_requirement[$level->id] ?>" />

</td>
<?php } ?>
</tr></table>
<br />

<?php 
echo form_hidden('batch_id', $batch->id);
echo '<label for="action">&nbsp;</label>';echo form_submit('action', "Save");
?>
</form><br />

<a href="<?php echo site_url('batch/index/center/'.$center_id) ?>">See All Batches</a>

<?php $this->load->view('layout/footer'); ?>