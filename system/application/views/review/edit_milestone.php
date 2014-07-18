<?php $this->load->view('layout/thickbox_header');
$months = array('nothing', 'Jan', 'Feb', 'March', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');
$milestone_id = 0;
$name = '';
$status = 0;
$due_on = 0;

if(!empty($milestone)) {
	$milestone_id = $milestone->id;
	$name = $milestone->name;
	$status = $milestone->status;
	$due_on = $milestone->due_on;
	$user_id = $milestone->user_id;
}
?>
<script type="text/javascript" src="<?php echo base_url()?>css/datetimepicker_css.js"></script>
<h2><?php echo $name ?> Milestone</h2>

<form id="formEditor" action="<?php echo site_url('review/save_milestone'); ?>" class="mainForm" method="post">
<ul class="form">
<li><label for="name">Name</label>
<input type="text" name="name" value="<?php echo $name ?>" /></li>

<li><label for="status">Status</label>
<select name="status">
	<option value="0" <?php if($status == '0') echo "selected"; ?>>Todo</option>
	<option value="1" <?php if($status == '1') echo "selected"; ?>>Done</option>
</select></li>

<li><label for="name">Due on</label>
	<input name="due_on" class="date-pick" id="due_on" type="text" value="<?php echo $due_on ?>"> 
    <img src="<?php echo base_url()?>images/calender_images/cal.gif" onclick="javascript:NewCssCal ('due_on','yyyyMMdd','arrow')" style="cursor:pointer"/>	
</li>
</ul>
<input type="hidden" name="milestone_id" value="<?php echo $milestone_id ?>" />
<input type="hidden" name="user_id" value="<?php echo $user_id ?>" />
<input id="btnSubmit" class="button green" type="submit" value="Save"></input>
</form>

<?php $this->load->view('layout/thickbox_footer'); ?>