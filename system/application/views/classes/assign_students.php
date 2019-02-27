<?php 
$this->load->view('layout/flatui/header', array('title' => $title));
$labels = [
	'student'	=> 'Students',
	'level'		=> 'Class Sections'
];
if($center->type == 'aftercare') {
	$labels['student'] = 'Youth';
	$labels['level'] = 'SSG';
}
?>
<link href="<?php echo base_url(); ?>/css/sections/classes/assign_students.css" rel="stylesheet" type="text/css" />
<style type="text/css">
	table { color: #fff; }
	select { color: #000; }
</style>
<div id="content" class="clear">
<div id="main" class="clear"> 
<div id="head" class="clear">
<h1 class="title">Assign <?php echo $labels['student'] ?> to <?php echo $labels['level'] ?></h1>

<form action="" method="post">
<center>
<input type="submit" name="action" value="Export to CSV" class="btn btn-default" />
<table class="table">
<tr><th><?php echo $labels['student'] ?></th><th class="class-section"><?php echo $labels['level'] ?></th></tr>
<?php foreach($all_students as $student) { ?>
<tr>
<!-- <td><?php echo $student->id ?></td> -->
<td class="student-name"><?php echo $student->name ?></td>

<td class="class-section"><select name="level_id[<?php echo $student->id ?>]" id="level-<?php echo $student->id ?>">
<option value="0">None</option>
<?php foreach($all_levels as $level_id => $level) { ?>
<option value="<?php echo $level_id ?>"<?php
		if(isset($student_level_mapping[$student->id]) and $student_level_mapping[$student->id] == $level_id) echo ' selected="selected"';
	?>><?php echo $level->grade . $level->name ?></option>
<?php } ?>
</select></td>
</tr>
<?php } ?>
</table>
<input type="submit" name="action" value="Save" class="btn btn-primary" />
</center>
</form>

</div>
</div>
</div>

<?php $this->load->view('layout/flatui/footer');
