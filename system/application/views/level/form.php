<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<script language="javascript">
function bulk_action(level) {
	var agents = "";
	$('#student :checked').each(function(i, selected)
		{ 
		agents+=($(selected).val()=="")?$(selected).val():$(selected).val()+",";
		});
	$.ajax({
		type : "POST", 
		url  : "<?php echo site_url('level/update_student') ?>",
		data : 'agents='+agents+'&level='+level,
		success : function(data) 
		{
			
		}
	});
}
</script>

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

<?php
echo form_hidden('center_id', $center_id);
echo form_hidden('project_id', 1);
echo form_hidden('id', $level['id']);
$js = 'id="student" onclick="bulk_action('.$level['id'].');"';
echo '<label for="action">&nbsp;</label>';echo form_submit('action', $action, $js);
?>
</form><br />
<?php if($action == 'Edit') { ?>
<div class="more-links">
<ul>
<li><a href="<?php echo site_url('batch/index/level/'.$level['id']) ?>">Batches in <?php echo $level['name'] ?></a></li>
<li><a href="<?php echo site_url('student/index/level/'.$level['id']) ?>">Kids in <?php echo $level['name'] ?></a></li>
</ul>
</div>
<?php } ?>

<?php $this->load->view('layout/footer');