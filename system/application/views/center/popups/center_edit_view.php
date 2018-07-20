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
	$authority_id = $row['authority_id'];
	$type = $row['type'];
	$preferred_gender = $row['preferred_gender'];
	$class_starts_on = $row['class_starts_on'];
	$year_undertaking = $row['year_undertaking'];
	$shelter_authority_name = '';
	$shelter_authority_email = '';
	// $year_undertaking = $row['class_starts_on'];
	// $address = $row['address'];
}



?>
<form id="formEditor" class="mainForm clear" action="<?php echo site_url('center/update_Center')?>" method="post" style="width:500px;"  onsubmit="return validate();">
<fieldset class="clear">
<ul class="form city-form">
<li>
<label for="center">Shelter Name </label>
<input id="center" name="center"  type="text" value="<?php echo $name; ?>" />
</li>

<li>
<label for="user_id">Shelter Type</label>
<?php echo form_dropdown('shelter_type', $center_types, $type); ?>
</li>

<li>
<label for="center">Shelter Address </label>
<textarea id="center" name="address"  type="text" required></textarea>
</li>

<li>
<label for="center">Shelter Contact </label>
<input id="shelter_contact" name="shelter_contact"  type="text" value="<?php echo $name; ?>" />
</li>


<li>
<label for="user_id">Shelter Operations Fellow:</label>
<?php echo form_dropdown('user_id', idNameFormat($all_users), $user_id); ?>
</li>

<li>
	<hr class="hr-light">
</li>

<input type="hidden" name="authority_id" value="<?php echo $authority_id ?>" />

<li>
<label for="user_id">Shelter Authority Name:</label>
<input type="text" id="sa_name" name="sa_name" value="<?php echo $shelter_authority_name; ?>" />
</li>

<li>
<label for="user_id">Shelter Authority Email:</label>
<input type="text" id="sa_email" name="sa_email" value="<?php echo $shelter_authority_email; ?>" />
</li>

<li>
<label for="user_id">Shelter Authority Phone:</label>
<input type="text" id="sa_email" name="sa_email" value="<?php echo $shelter_authority_email; ?>" />
</li>

<li>
	<hr class="hr-light">
</li>

<li>
<label for="class_starts_on">Class Starts on: </label>
<input type="text" id="class_starts_on" name="class_starts_on" value="<?php echo $class_starts_on; ?>" />
</li>

<li>
Programmes in <br> Shelter: <br/>
<?php foreach ($programmes as $key => $value): ?>
		<label class="right" for="prog_<?php echo $key ?>"><input id="prog_<?php echo $key ?>" name="programmes[]" type="checkbox" value="<?php echo $key ?>"> <?php echo $value; ?>  </label> <br>
<?php endforeach; ?>
</li>

<li>
<label for="medium">Medium of Instruction: </label>
<?php echo form_dropdown('medium', ['vernacular' => 'Vernacular','english' => 'English'], $medium); ?>
</li>

<li>
<label for="preferred_gender">Year of Undertaking </label>
<input type="number" min="2006" id="year_undertaking" name="year_undertaking" value="<?php echo $year_undertaking; ?>" />
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
