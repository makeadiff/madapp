<?php 
$this->load->view('layout/header', array('title' => $action . ' Level'));

if(!isset($level)) $level = array(
	'name'			=>	'',
	'day'			=>	0,
	'class_time'	=> '16:00:00',
	'batch_head_id'	=> 0,
	);
?>

<h1><?php echo $action . ' Level' ?></h1>

<form action="" method="post">
<label for='name'>Level Name</label>
<input type="text" name="name" value="<?php echo set_value('name', $level['name']); ?>" /><br />

<label for='president_id'>President</label>
<?php echo form_dropdown('president_id', $president_ids, $level['president_id']); ?><br />

<?php 
echo form_hidden('id', $level['id']);
echo form_submit('action', $action);
?>
</form>

<?php $this->load->view('layout/footer');