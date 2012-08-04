<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<?php $this->load->view('layout/thickbox_header'); ?>
<?php
if(!isset($level)) $level = array(
	'id'		=> 0,
	'name'		=> '',
	'center_id'	=> 0,
	);

?>


<form action="" method="post" class="form-area">
<ul class="form city-form">
<label for='name'>Level Name: </label>

<li>
<input type="text" width="400" id="level" name="name" value="<?php echo set_value('name', $level['name']); ?>" /><br />
</li>
<li>
<label for="selBulkActions">Kids:</label>

<select id="students" name="students[]" multiple>
<?php foreach($level['kids'] as $id=>$name) { ?>
<option value="<?php echo $id; ?>" <?php 
	if(in_array($id, $level['selected_students'])) echo 'selected'; 
?>><?php echo $name; ?></option> 
<?php } ?>
</select>
</li>
<li>
<label for="book_id">Book</label>
<?php echo form_dropdown('book_id', $all_books, $level['book_id']); ?><br />
</li>
<ul>
<li>
<?php
echo form_hidden('center_id', $center_id);
echo form_hidden('project_id', 1);
echo form_hidden('id', $level['id']);
echo '<label for="action">&nbsp;</label>';echo form_submit('action', $action);
?>
</li>
</ul>
</form><br />

