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

$mediums = array(
	'english' => 'English',
	'vernacular' => 'Vernacular'
);

$details=$details->result_array();
foreach($details as $row) {
	$jjact_registered = $row['jjact_registered'];
	$mou_signed = $row['mou_signed'];
	$root_id=$row['id'];
	$name=$row['name'];
	$city_id=$row['city_id'];
	$user_id=$row['center_head_id'];
	$medium = $row['medium'];
	$phone = $row['phone'];
	$authority_id = $row['authority_id'];
	$type = $row['type'];
	$preferred_gender = $row['preferred_gender'];
	$class_starts_on = $row['class_starts_on'];
	$year_undertaking = $row['year_undertaking'];
	$vernacular_medium = $row['vernacular_medium'];
	$shelter_authority_name = $row['ca_name'];
	$shelter_authority_email = $row['ca_email'];
	$shelter_authority_phone = $row['ca_phone'];
	$shelter_projects = explode(',',$row['project_ids']);
	$address = $row['address'];
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
<p><strong>Registered with Juvenile Justice (JJ) Act?</strong></p>
<label class="right" for="jj_act_yes">
	<input type="radio" name="jjact_registered" value="1" <?php if($jjact_registered=='1') echo "checked"; ?> id="jj_act_yes"/>Yes
</label>
<label class="right" for="jj_act_no">
	<input type="radio" name="jjact_registered" value="0" <?php if($jjact_registered=='0') echo "checked"; ?> id="jj_act_no"/>No
</label>
</li>

<p><strong>Memorandum of Understanding Signed?</strong></p>
<label class="right" for="mou_yes">
	<input type="radio" name="mou_signed" value="1" <?php if($mou_signed=='1') echo "checked"; ?> id="mou_yes"/>Yes
</label>
<label class="right" for="mou_no">
	<input type="radio" name="mou_signed" value="0" <?php if($mou_signed=='0') echo "checked"; ?> id="mou_no"/>No
</label>
</li>
<br/><br/>

<!-- ALTER TABLE `Center` ADD `jjact_registered` ENUM('1','0') NOT NULL AFTER `updated_on`; -->

<li>
<label for="center">Shelter Address </label>
<textarea id="center" name="address"  type="text" required><?php echo $address; ?></textarea>
</li>

<li>
<label for="center">Shelter Contact </label>
<input id="shelter_contact" name="shelter_contact"  type="text" value="<?php echo $phone; ?>" />
</li>


<li>
<label for="user_id">Shelter Operations Fellow</label>
<?php echo form_dropdown('user_id', idNameFormat($all_sofs), $user_id); ?>
</li>

<?php if($this->user_auth->get_permission('shelter_authority_details')) { ?>
	<!-- Shelter Authoriy Details are Only Visible to Directors -->
	<li>
		<hr class="hr-light">
		<p class="center"><strong>Shelter Authority Details</strong></p>
	</li>

	<input type="hidden" name="authority_id" value="<?php echo $authority_id ?>" />

	<li>
	<label for="user_id">Name:</label>
	<input type="text" id="sa_name" name="sa_name" value="<?php echo $shelter_authority_name; ?>" />
	</li>

	<li>
	<label for="user_id">Email:</label>
	<input type="text" id="sa_email" name="sa_email" value="<?php echo $shelter_authority_email; ?>" />
	</li>

	<li>
	<label for="user_id">Phone:</label>
	<input type="text" id="sa_email" name="sa_phone" value="<?php echo $shelter_authority_phone; ?>" />
	</li>
<?php } ?>

<li>
	<hr class="hr-light">
</li>

<li>
<label for="class_starts_on">Class Starts on </label>
<input type="text" id="class_starts_on" name="class_starts_on" value="<?php echo $class_starts_on; ?>" />
</li>

<li>
<p><strong>Programmes in Shelter </strong></p>
<?php foreach ($programmes as $key => $value):?>
	<?php
		if(in_array($key,$shelter_projects)){
			$checked = 'checked';
		}
		else{
			$checked = '';
		}
	?>
	<label class="right" for="prog_<?php echo $key ?>">
		<input id="prog_<?php echo $key ?>" name="programmes[]" type="checkbox" <?php echo $checked; ?> value="<?php echo $key ?>">
		<?php echo $value; ?>
	</label> <br>
<?php endforeach; ?>
</li>

<li>
<label for="medium"><strong>Medium of Instruction</strong></label>
<?php echo form_dropdown('medium', ['vernacular' => 'Vernacular','english' => 'English'], $medium); ?>
<input type="text" id="medium_hidden" name="medium_hidden" value="<?php echo $vernacular_medium; ?>" placeholder="If vernacular, enter Medium here."/>
</li>

<li>
<label for="preferred_gender">Year of Undertaking </label>
<input type="number" min="2006" id="year_undertaking" name="year_undertaking" value="<?php echo $year_undertaking; ?>" />
</li>

<li>
<label for="preferred_gender">Preferred Volunteer Gender </label>
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
