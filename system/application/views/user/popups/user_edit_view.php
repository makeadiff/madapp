<?php $this->load->view('layout/thickbox_header'); ?>
<h2>Edit User</h2>
<?php 

$root_id	= $user->id;
$name		= $user->name;
$title		= $user->title;
$email		= $user->email;
$phone		= $user->phone;
$address	= $user->address;
$center_id	= $user->center_id;
$city_id	= $user->city_id;
$user_type	= $user->user_type;
$joined_on	= $user->joined_on;
$left_on	= $user->left_on;
$photo		= $user->photo;
$sex		= $user->sex;
$reason_for_leaving = $user->reason_for_leaving;


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
<label for="names">Name : </label>
<input id="names" name="names"  type="text" value="<?php echo stripslashes($name); ?>"/> 
</li>

<li>
<label for="group">Select Group: </label>
<select id="group" name="group[]" multiple="multiple"> 
	<?php
	foreach($all_groups as $id => $name) {
		if(($id == 1 or $id == 3) and !$this->user_auth->get_permission('permissions_index')) continue;
	?>
	<option value="<?php echo $id; ?>" <?php if(in_array($id, $user->groups)) echo 'selected="selected"'; ?>><?php echo $name; ?></option> 
	<?php } ?>
</select>
</li>

<li>
	<label for="emails">Email : </label>
	<input id="emails" name="emails"  type="text"  value="<?php echo $email; ?>"/> 
</li>
<li>
	<label for="spassword">Password : </label>
	<input id="spassword" name="spassword"  type="password"   /> 
			
</li>
<li>
	<label for="scpassword">Confirm Password : </label>
	<input id="scpassword" name="scpassword"  type="password" /> 
</li>
<li>
	<label for="phone">Phone : </label>
	<input id="phone" name="phone"  type="text" value="<?php echo $phone; ?>"  /> 
</li>

<li>
	<label for="address">Address : </label>
	<textarea id="address" name="address"  rows="5" cols="30"><?php echo $address; ?></textarea> 
</li>

<li>
	<label for="sex">Sex : </label>
	<select id="sex" name="sex">
		<option value="m" <?php if($sex == 'm') echo ' selected="selected"'; ?>>Male</option>
		<option value="f" <?php if($sex == 'f') echo ' selected="selected"'; ?>>Female</option>
	</select>
</li>
	
<?php 
$this_city_id = $this->session->userdata('city_id');
if($this->user_auth->get_permission('change_city')) { ?>
<li>
<label for="selBulkActions">Select City:</label>
<?php echo form_dropdown('city', $all_cities, $city_id); ?>
</li>
<?php } else { ?>
	<input type="hidden" name="city" value="<?php echo $this_city_id; ?>" />
<?php } ?>
<li>
	<label for="date">Photo</label>
	<?php if($photo) { ?><img src="<?php echo base_url().'uploads/users/thumbnails/'.$photo; ?>" style="float:left;" /><?php } ?>
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
	<select name="type" id="user-type-selector">
		<option value="applicant" <?php if($user_type == 'applicant') echo ' selected="selected"'; ?>>Applicant</option>
		<option value="volunteer" <?php if($user_type == 'volunteer') echo ' selected="selected"'; ?>>Volunteer</option>
		<option value="well_wisher" <?php if($user_type == 'well_wisher') echo ' selected="selected"'; ?>>Well Wisher</option>
		<option value="alumni" <?php if($user_type == 'alumni') echo ' selected="selected"'; ?>>Alumni</option>
		<option value="other" <?php if($user_type == 'other') echo ' selected="selected"'; ?>>Other</option>
		<option value="let_go" <?php if($user_type == 'let_go') echo ' selected="selected"'; ?>>Let Go</option>
	</select>
</li>
<li id="exit-interview-feedback"<?php if($user_type != 'let_go') { ?> style="display:none;"<?php } ?>>
	<label for="reason_for_leaving">Reason for Leaving: </label>
	<textarea name="reason_for_leaving" rows="5" cols="30"><?php echo $reason_for_leaving ?></textarea>
</li>
</ul>
<div class="field clear" style="width:550px;"> 
		<input type="hidden" value="<?php echo $root_id; ?>" id="rootId" name="rootId" />
		<input type="hidden" value="<?php echo $this->session->userdata('project_id'); ?>" name="project" />
		<input  id="btnSubmit" class="button green" type="submit" value="Submit" />
		<a href="<?php echo site_url('user/view_users');?>" class="cancel-button">Cancel</a>
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
				alert("Please enter an email address");
				return false;
			}
	if(document.getElementById("spassword").value && (document.getElementById("spassword").value != document.getElementById("scpassword").value))
			{
				alert("Password Missmatch.");
				return false;
			}

}
</script>
<script type="text/javascript" src="<?php echo base_url()?>js/sections/users/edit_user_view.js"></script>

<?php $this->load->view('layout/thickbox_footer'); ?>