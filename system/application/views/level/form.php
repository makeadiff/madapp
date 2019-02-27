<?php 
$this->load->view('layout/thickbox_header');
$project_id = 1;

if(!isset($level)) $level = array(
		'id'		=> 0,
		'name'		=> '',
		'center_id'	=> 0,
		'grade'		=> 5,
		'medium'	=> 'english',
		'preferred_gender' => 'any'
	);


$labels = [
	'grade'		=> 'Grade',
	'level'		=> 'Class Sections',
	'student'	=> 'Students'
];
if($center->type == 'aftercare') {
	$project_id = 5;
	$labels['grade'] = 'SSG Name';
	$labels['level'] = 'SSG';
	$labels['student'] = 'Youth';
}
?>

<form action="" method="post" class="form-area">
<ul class="form city-form">
<li>
<label for="grade"><?php echo $labels['grade'] ?></label>
<select name="grade" style="width:100px;">
	<?php for($i=1;$i<=12;$i++) { ?>
	<option value="<?php echo $i ?>" <?php if($level['grade'] == $i) echo 'selected'; ?>><?php echo $i ?></option>
	<?php } ?>
	<option value="13" <?php if($level['grade'] == 13) echo 'selected'; ?>>Aftercare</option>
</select>

<select name="name" style="width:100px;">
	<?php foreach(range('A','Z') as $l) { ?>
	<option value="<?php echo $l ?>" <?php if($level['name'] == $l) echo 'selected'; ?>><?php echo $l ?></option>
	<?php } ?>
</select>
</li>

<li>
<label for="selBulkActions"><?php echo $labels['student'] ?>:</label>
<select id="students" name="students[]" multiple>
<?php foreach($level['kids'] as $id=>$name) { ?>
<option value="<?php echo $id; ?>" <?php if(in_array($id, $level['selected_students'])) echo 'selected'; ?>><?php echo $name; ?></option> 
<?php } ?>
</select><br />

<label>&nbsp;</label>
<input id="students-filter" class="filter-multiselect" type="text" value="" target-field="students" placeholder="Filter..." />
</li>

<li>
<label for="medium">Medium: </label>
<?php echo form_dropdown('medium', ['vernacular' => 'Vernacular','english' => 'English'], $level['medium']); ?>
</li>

<li>
<label for="preferred_gender">Preferred Gender: </label>
<?php echo form_dropdown('preferred_gender', ['male' => 'Male','female' => 'Female', 'any' => 'Any'], $level['preferred_gender']); ?>
</li>

<?php
echo form_hidden('center_id', $center_id);
echo form_hidden('project_id', $project_id);
echo form_hidden('id', $level['id']);
echo '<label for="action">&nbsp;</label>';echo form_submit('action', $action);
?>
</ul>
</form><br />
<script type="text/javascript" src="<?php echo base_url()?>js/libraries/filter-multiselect.js"></script>

<?php $this->load->view('layout/thickbox_footer');