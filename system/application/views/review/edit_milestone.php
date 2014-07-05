<?php $this->load->view('layout/thickbox_header');
$months = array('nothing', 'Jan', 'Feb', 'March', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');
$milestone_id = 0;
$name = '';
$status = 0;
$due_timeframe = 0;

if(!empty($milestone)) {
	$milestone_id = $milestone->id;
	$name = $milestone->name;
	$status = $milestone->status;
	$due_timeframe = $milestone->due_timeframe;
	$user_id = $milestone->user_id;
}
?>
<h2><?php echo $name ?> Milestone</h2>

<form id="formEditor" action="<?php echo site_url('review/save_milestone'); ?>" class="mainForm" method="post">
<ul class="form">
<li><label for="name">Name</label>
<input type="text" name="name" value="<?php echo $name ?>" /></li>

<li><label for="status">Status</label>
<select name="status">
	<option value="0" <?php if($status == '0') echo "selected"; ?>>Todo</option>
	<option value="0" <?php if($status == '1') echo "selected"; ?>>Done</option>
</select></li>

<li><label for="name">Due on Timeframe</label>
<select name="due_timeframe">
	<?php for($i=1; $i<=12; $i++) { ?>
	<option value="<?php echo $i ?>" <?php if($due_timeframe == $i) echo "selected"; ?>><?php echo $months[$i]; ?></option>
	<?php } ?>
</select>
</li>
</ul>
<input type="hidden" name="milestone_id" value="<?php echo $milestone_id ?>" />
<input type="hidden" name="user_id" value="<?php echo $user_id ?>" />
<input id="btnSubmit" class="button green" type="submit" value="Save"></input>
</form>

<?php $this->load->view('layout/thickbox_footer'); ?>