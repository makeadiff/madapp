<?php 
$this->load->view('layout/header', array('title' => $action . ' Batch in ' . $center_name));
$day_list = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');

if(!$batch['id']) $batch = array(
	'id'			=> 0,
	'day'			=> 0,
	'class_time'	=> '16:00:00',
	'batch_head_id'	=> 0,
	'center_id'		=> $batch['center_id'],
	);
?>

<div id="head" class="clear"><h1><?php echo $action . ' Batch in ' . $center_name ?></h1></div>

<form action="<?php echo site_url('batch/'.strtolower($action).'_action'); ?>" class="form-area" method="post">
<label for="day">Day</label>
<?php echo form_dropdown('day', $day_list, $batch['day']); ?><br />

<label for='class_time'>Time</label>
<input type="text" name="class_time" value="<?php echo set_value('class_time', $batch['class_time']); ?>" /><br />

<label for='batch_head_id'>Batch Head</label>
<?php echo form_dropdown('batch_head_id', $batch_volunters, $batch['batch_head_id']); ?><br />


<?php 
echo form_hidden('id', $batch['id']);
echo form_hidden('center_id', $batch['center_id']);
echo form_hidden('project_id', 1);
echo "<label for='action'>&nbsp;</label>"; echo form_submit('action', $action);
?>
</form>

<?php $this->load->view('layout/footer');