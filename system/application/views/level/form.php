<?php 
$this->load->view('layout/header', array('title' => $action . ' Level'));

if(!isset($level)) $level = array(
	'id'		=> 0,
	'name'		=> '',
	'center_id'	=> 0,
	);
?>

<h1><?php echo $action . ' Level' ?></h1>

<form action="" method="post" class="form-area">
<label for='name'>Level Name</label>
<input type="text" name="name" value="<?php echo set_value('name', $level['name']); ?>" /><br />

<label for='center_id'>Center</label>
<?php echo form_dropdown('center_id', $center_ids, $level['center_id']); ?><br />

<?php
echo form_hidden('project_id', 1);
echo form_hidden('id', $level['id']);
echo '<label for="action">&nbsp;</label>';echo form_submit('action', $action);
?>
</form><br />

<?php if($action == 'Edit') { ?>
<div class="more-links">
<ul>
<li><a href="<?php echo base_url() ?>index.php/batch/index/level/<?php echo $level['id'] ?>">Batches in <?php echo $level['name'] ?></a></li>
<li><a href="<?php echo base_url() ?>index.php/student/index/level/<?php echo $level['id'] ?>">Kids in <?php echo $level['name'] ?></a></li>
</ul>
</div>
<?php } ?>

<?php $this->load->view('layout/footer');