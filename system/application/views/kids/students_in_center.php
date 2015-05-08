<?php $this->load->view('layout/flatui/header', array('title' => $title)); ?>
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
<tr><th>Student Name</th><th>Class Name</th></tr>
<?php foreach($all_students as $student_id => $student_name) { ?>
<tr>
<td><?php echo $student_name ?></td>
<td><select name="level_id[<?php echo $student_id ?>]">
<?php foreach($all_levels as $level_id => $level_name) { ?>
<option value="<?php echo $level_id ?>" <?php 
	if(i($student_level_mapping, $student_id) == $level_id) echo 'selected="selected"'; 
	?>><?php echo $level_name ?></option>
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
