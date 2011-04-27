<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<?php 
$this->load->view('layout/header', array('title' => $action . ' Level in ' . $center_name));

if(!isset($level)) $level = array(
	'id'		=> 0,
	'name'		=> '',
	'center_id'	=> 0,
	);

?>

<h1><?php echo $action . ' Level in ' . $center_name ?></h1>

<form action="" method="post" class="form-area">
<label for='name'>Level Name</label>
<input type="text" id="level" name="name" value="<?php echo set_value('name', $level['name']); ?>" /><br />

<label for="selBulkActions">Kids:</label>
<select id="students" name="students[]" multiple>
<?php foreach($level['kids'] as $row) { ?>
<option value="<?php echo $row->id; ?>" <?php 
	if(in_array($row->id, $level['selected_students'])) echo 'selected'; 
?>><?php echo $row->name; ?></option> 
<?php } ?>
</select><br />

<label for="book_id">Book</label>
<?php echo form_dropdown('book_id', $all_books, $level['book_id']); ?><br />

<?php
echo form_hidden('center_id', $center_id);
echo form_hidden('project_id', 1);
echo form_hidden('id', $level['id']);
echo '<label for="action">&nbsp;</label>';echo form_submit('action', $action);
?>
</form><br />

<?php $this->load->view('layout/footer');