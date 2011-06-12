<?php $this->load->view('layout/thickbox_header'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/calender.css" />
<script src="<?php echo base_url()?>js/cal.js"></script>
<script type="text/javascript">
function validate() 
{
if(document.getElementById("names").value == '')
	{
		alert("Name Missing");
		document.getElementById("names").focus();
		return false;
	}
if(document.getElementById("emails").value == '')
	{
		alert("Enter Email");
		document.getElementById("emails").focus();
		return false;
	}	
if(!document.getElementById("emails").value.match(/^\w+\@\w+\.\w+/))
		{
			alert("Enter Valid Email");
			document.getElementById("emails").focus();
			return false;
		}
if(document.getElementById("spassword").value == '')
		{
			alert("Password Missing.");
			document.getElementById("spassword").focus();
			return false;
		}
if(document.getElementById("scpassword").value == '')
		{
			alert("Confirm your Password.");
			document.getElementById("scpassword").focus();
			return false;
		}
	
if(document.getElementById("spassword").value != document.getElementById("scpassword").value)
		{
			alert("Password Mismatch.");
			document.getElementById("cpassword").focus();
			return false;
		}
}
</script>

<?php
$sdt=2006;
$edt=date('Y');
?>
<script>
jQuery(document).ready(function () {
	$('input.date-pick').simpleDatepicker({ startdate: <?php echo $sdt; ?>, enddate: <?php echo $edt; ?>, chosendate:new Date('2010-01-01')});
});
</script>

<body>
<form id="formEditor" name="formEditor" class="mainForm form-area clear" action="<?=site_url('user/adduser')?>" method="post" enctype="multipart/form-data" onsubmit="return validate();">
<fieldset class="clear">

<div class="field clear" style="width:500px;"> 
<label for="txtName">Name : </label> <input id="names" name="names"  type="text" /><br />
</div>

<div class="field clear" style="width:500px;">
<label for="selBulkActions">Select Group:</label> 
<select id="group" name="group" multiple="multiple"> 
<option selected="selected" value="-1" >- Choose -</option> 
	<?php 
	$user_group = $user_group->result_array();
	foreach($user_group as $row)
	{ 
	?>
	<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option> 
	<?php } ?>
</select><br />
</div>
<div class="field clear" style="width:500px;"> 
			<label for="position">Position : </label>
			<input id="position" name="position"  type="text" /><br />
</div>

<div class="field clear" style="width:500px;"> 
			<label for="email">Email : </label>
			<input id="emails" name="emails"  type="text" /><br />
			
</div>
<div class="field clear" style="width:500px;"> 
			<label for="password">Password : </label>
			<input id="spassword" name="spassword"  type="password" /><br />	 
			
</div>
<div class="field clear" style="width:500px;"> 
	<label for="cpassword">Confirm Password : </label>
	<input id="scpassword" name="scpassword"  type="password" /><br />	 
</div>
<div class="field clear" style="width:500px;"> 
	<label for="txtName">Phone : </label>
	<input id="phone" name="phone"  type="text" /><br />	 
</div>

<div class="field clear"> 
	<label for="txtName">Address : </label>
	<textarea id="address" name="address"  rows="5" cols="30"></textarea><br />	 
</div>

<?php 
$this_city_id = $this->session->userdata('city_id');
if($this->user_auth->get_permission('change_city')) { ?>
<div class="field clear" style="width:500px;">
<label for="selBulkActions">Select city:</label> 
<select id="city" name="city" > 
<option selected="selected" value="-1" >- Choose -</option> 
	<?php 
	$details = $details->result_array();
	foreach($details as $row) {
	?>
	<option value="<?php echo $row['id']; ?>" <?php
		if($row['id'] == $this_city_id) echo 'selected';
	?>><?php echo $row['name']; ?></option> 
	<?php } ?>
</select><br />	
</div>
<?php } else { ?>
<input type="hidden" name="city" value="<?php echo $this_city_id; ?>" />
<?php } ?>

<?php 
$this_project_id = $this->session->userdata('project_id');
if($this->user_auth->get_permission('change_city')) { ?>
<div class="field clear" style="width:500px;">
<label for="selBulkActions">Select project:</label> 
<select id="project" name="project"> 
<option selected="selected" >- Choose -</option> 
	<?php 
	$project = $project->result_array();
	foreach($project as $row) { ?>
	<option value="<?php echo $row['id']; ?>" <?php
		if($row['id'] == $this_project_id) echo 'selected';
	?>><?php echo $row['name']; ?></option> 
	<?php } ?>
</select><br />	
</div>
<?php } else { ?>
<input type="hidden" name="project" value="<?php echo $this_project_id; ?>" />
<?php } ?>

<div class="field clear" style="width:500px;"> 
	<label for="txtName">Joined On : </label>
	<input id="joined_on" name="joined_on" class="date-pick" type="text" value=""  /><br />	 
</div>

<div class="field clear" style="width:500px;"> 
	<label for="txtName">Left On : </label>
	<input id="left_on" name="left_on" class="date-pick" type="text" value=""  /><br />	 
</div>

<div class="field clear"> 
<label for="type">User Type : </label>
<select name="type">
	<option value="applicant">Applicant</option>
	<option value="volunteer" selected="selected">Volunteer</option>
	<option value="well_wisher">Well Wisher</option>
	<option value="alumni">Alumni</option>
	<option value="other">Other</option>
</select><br />	
</div>

<div  class="field clear">
	<label for="image">Upload Photo</label>
	<input name="image"  id="image" type="file">
	<p class="error clear"></p>
</div>



<div class="field clear" style="width:550px;"> 
<input style="margin-left:50px; margin-top:30px;"  class="button primary"  type="submit"  value="Submit" />
<a href="<?=site_url('user/view_users');?>" class="cancel-button">Cancel</a>
</div>
</fieldset>
</form>

<?php $this->load->view('layout/thickbox_footer'); ?>
