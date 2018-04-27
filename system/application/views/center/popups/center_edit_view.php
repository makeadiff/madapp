<?php $this->load->view('layout/thickbox_header'); ?>
<script src="<?php echo base_url();?>js/datepicker.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/calender.css" />
<script src="<?php echo base_url()?>js/cal.js" type="text/javascript"></script>
<?php
$sdt=2011;
$edt=date('Y');
?>
<script type="text/javascript">
jQuery(document).ready(function () {
	$('#class_starts_on').simpleDatepicker({ startdate: <?php echo $sdt; ?>, enddate: <?php echo $edt; ?>, chosendate:new Date(<?php echo date('Y-m-d'); ?>)});
});
</script>

<h2>Edit Center</h2>
<?php
$details=$details->result_array();
foreach($details as $row) {
	$root_id=$row['id'];
	$name=$row['name'];
	$city_id=$row['city_id'];
	$user_id=$row['center_head_id'];
	$medium = $row['medium'];
	$preferred_gender = $row['preferred_gender'];
	$class_starts_on = $row['class_starts_on'];
}

?>
<form id="formEditor" class="mainForm clear" action="<?php echo site_url('center/update_Center')?>" method="post" style="width:500px;"  onsubmit="return validate();">
<fieldset class="clear">
<ul class="form city-form">
<li>
<label for="center">Center : </label>
<input id="center" name="center"  type="text" value="<?php echo $name; ?>" /> 
</li>

<li>
<label for="user_id">Shelter Operations Fellow:</label> 
<?php echo form_dropdown('user_id', idNameFormat($all_users), $user_id); ?>
</li>

<li>
<label for="class_starts_on">Class Starts on: </label>
<input type="text" id="class_starts_on" name="class_starts_on" value="<?php echo $class_starts_on; ?>" />
</li>

<li>
<label for="medium">Medium: </label>
<?php echo form_dropdown('medium', ['vernacular' => 'Vernacular','english' => 'English'], $medium); ?>
</li>

<li>
<label for="preferred_gender">Preferred Gender: </label>
<?php echo form_dropdown('preferred_gender', ['male' => 'Male','female' => 'Female', 'any' => 'Any'], $preferred_gender); ?>
</li>

</ul>

<ul>
<li>
<input type="hidden" value="<?php echo $root_id; ?>"  id="rootId" name="rootId" />
<input id="btnSubmit" class="button green" type="submit" value="Save" />
<a href="<?php echo site_url('center/manage/'.$root_id)?>" class="cancel-button">Cancel</a>
</li>
</ul>
</fieldset>
</form>

<script>
function validate()
{
	if(document.getElementById("center").value == '')
		{
			alert("Center Name Missing.");
			return false;
		}
}
</script>
<?php $this->load->view('layout/thickbox_footer'); ?>