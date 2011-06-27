<?php $this->load->view('layout/thickbox_header'); ?>
<h2>Edit User</h2>
<?php 
$user_details = $user->result_array();
foreach($user_details as $row) {	
	$root_id	= $row['id'];
	$name		= $row['name'];
	$title		= $row['title'];
	$email		= $row['email'];
	$phone		= $row['phone'];
	$address	= $row['address'];
	$center_id	= $row['center_id'];
	$city_id	= $row['city_id'];
	$project_id	= $row['project_id'];
	$user_type	= $row['user_type'];
	$joined_on	= $row['joined_on'];
	$left_on	= $row['left_on'];
	$photo=$row['photo'];
}

$group_name=$group_name->result_array();
foreach($group_name as $row) {
	$group_id=$row['id'];	
}
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/calender.css" />
<script src="<?php echo base_url()?>js/cal.js"></script>
<?php
$sdt=2006;
$edt=date('Y');
?>
<script>
jQuery(document).ready(function () {
	$('input.date-pick').simpleDatepicker({ startdate: <?php echo $sdt; ?>, enddate: <?php echo $edt; ?>, chosendate:new Date('2010-01-01')});
});
</script>
<form id="formEditor" class="mainForm clear" action="<?php echo site_url('user/update_user')?>" method="post" onsubmit="return validate();" style="width:500px;" enctype="multipart/form-data" >
<fieldset class="clear">
<ul class="form city-form">
<li>
<label for="txtName">Name : </label>
<input id="names" name="names"  type="text" value="<?php echo stripslashes($name); ?>"/> 
</li>

<li>
<label for="selBulkActions">Select Group:</label> 
<select id="group" name="group[]" style="width:142px; height:50px;" multiple="multiple"> 
	<?php 
	$group_details = $group_details->result_array();
	foreach($group_details as $row){ ?>
	<?php if($group_id== $row['id']){ ?>
	<option value="<?php echo $row['id']; ?>" selected="selected"><?php echo $row['name']; ?></option> 
	<?php }else{ ?>
		<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option> 
	<?php } }?>
</select>
</li>
<li>
	<label for="txtName">Position : </label>
	<input id="user_position" name="position"  type="text" value="<?php echo stripslashes($title); ?>" /> 
</li>

<li>
	<label for="txtName">Email : </label>
	<input id="emails" name="emails"  type="text"  value="<?php echo $email; ?>"/> 
</li>
<li>
	<label for="txtName">Password : </label>
	<input id="spassword" name="spassword"  type="password"   /> 
			
</li>
<li>
	<label for="txtName">Confirm Password : </label>
	<input id="scpassword" name="scpassword"  type="password" /> 
</li>
<li>
	<label for="txtName">Phone : </label>
	<input id="phone" name="phone"  type="text" value="<?php echo $phone; ?>"  /> 
</li>

<li>
	<label for="txtName">Address : </label>
	<textarea id="address" name="address"  rows="5" cols="30"><?php echo $address; ?></textarea> 
</li>
<?php 
$this_city_id = $this->session->userdata('city_id');
if($this->user_auth->get_permission('change_city')) { ?>
<li>
<label for="selBulkActions">Select city:</label> 
<select id="city" name="city"  onchange="javascript:get_center_Name(this.value);">
<option selected="selected" value="-1" >- choose action -</option> 
	<?php 
	$details = $details->result_array();
	foreach($details as $row) { ?>
	<?php if($city_id == $row['id'] ){?>
	<option value="<?php echo $row['id']; ?>" selected="selected"><?php echo $row['name']; ?></option> 
	<?php }else { ?>
	<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
	<?php }} ?>
</select>
</li>
<?php } else { ?>
	<input type="hidden" name="city" value="<?php echo $this_city_id; ?>" />
<?php } ?>

<li>
<label for="selBulkActions">Select Project:</label> 
<select id="project" name="project">
	<?php 
	$project = $project->result_array();
	foreach($project as $row)
	{
	?>
	<?php if($project_id==$row['id']) { ?>
	<option value="<?php echo $row['id']; ?>" selected="selected"><?php echo $row['name']; ?></option> 
	<?php } else { ?>
	<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
	<?php } }?>
</select>
</li>
<li>
	<label for="date">Photo</label>
	<?php if($photo) { ?><img src="<?php echo base_url().'pictures/'.$photo; ?>" width="100" style="float:left;" height="100" /><?php } ?>
</li>
<li>
	<label for="date">Change photo</label>
	<input name="image"  id="image" type="file">
	<p class="error clear"></p>
</li>

<li>
	<label for="txtName">Joined On : </label>
	<input id="joined_on" name="joined_on" class="date-pick" type="text" value="<?php echo $joined_on; ?>"  /> 
</li>
<li>
	<label for="txtName">Left On : </label>
	<input id="left_on" name="left_on" class="date-pick" type="text" value="<?php echo $left_on; ?>"  /> 
</li>
<li>
	<label for="type">User Type : </label>
	<select name="type">
		<option value="applicant" <?php if($user_type == 'applicant') echo ' selected="selected"'; ?>>Applicant</option>
		<option value="volunteer" <?php if($user_type == 'volunteer') echo ' selected="selected"'; ?>>Volunteer</option>
		<option value="well_wisher" <?php if($user_type == 'well_wisher') echo ' selected="selected"'; ?>>Well Wisher</option>
		<option value="alumni"> <?php if($user_type == 'alumni') echo ' selected="selected"'; ?>Alumni</option>
		<option value="other" <?php if($user_type == 'other') echo ' selected="selected"'; ?>>Other</option>
	</select>
</li>
</ul>
<div class="field clear" style="width:550px;"> 
		<input type="hidden" value="<?php echo $root_id; ?>"  id="rootId" name="rootId" />
		<input  id="btnSubmit" class="button green" type="submit" value="Submit" />
		<a href="<?=site_url('user/view_users');?>" class="cancel-button">Cancel</a>
</div>
</fieldset>
</form>
            
<script language="javascript">
function validate()
{
	if(document.getElementById("names").value == '')
			{
				alert("Name Missing.");
				return false;
			}
	if(document.getElementById("emails").value == '')
			{
				alert("Select Email.");
				return false;
			}
	if(document.getElementById("spassword").value == '')
			{
				alert("Enter Password.");
				return false;
			}
		
	if(document.getElementById("spassword").value != document.getElementById("scpassword").value)
			{
				alert("Password Missmatch.");
				return false;
			}
	if(document.getElementById("city").value == '-1')
			{
				alert("Select City.");
				return false;
			}
	if(document.getElementById("center").value == '-1')
			{
				alert("Select Center.");
				return false;
			}
	if(document.getElementById("project").value == '-1')
			{
				alert("Select Project.");
				return false;
			}

}
</script>

<?php $this->load->view('layout/thickbox_footer'); ?>