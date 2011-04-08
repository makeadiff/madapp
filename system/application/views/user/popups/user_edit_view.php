<?php $this->load->view('layout/css',array('thickbox'=>true)); ?>

<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<?php
$user_details = $user->result_array();
foreach($user_details as $row) {	
	$root_id	= $row['id'];
	$name		= $row['name'];
	$title		= $row['title'];
	$email		= $row['email'];
	$phone		= $row['phone'];
	$center_id	= $row['center_id'];
	$city_id	= $row['city_id'];
	$project_id	= $row['project_id'];
	$user_type	= $row['user_type'];
	$photo=$row['photo'];
}

$group_name=$group_name->result_array();
foreach($group_name as $row) {
	$group_id=$row['id'];	
}
?>
<script type="text/javascript">
function get_center_Name(city_id)
{
alert(city_id);

}
</script>
<form id="formEditor" class="mainForm clear" action="<?php echo site_url('user/update_user')?>" method="post" onsubmit="return validate();" style="width:500px;" enctype="multipart/form-data" >
<fieldset class="clear" style="margin-top:50px;width:500px;margin-left:-30px;">

<div class="field clear" style="width:500px;"> 
<label for="txtName">Name : </label>
<input id="user_name" name="name"  type="text" value="<?php echo stripslashes($name); ?>"/> 
</div>

<div class="field clear" style="width:500px;">
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
</div>
<div class="field clear" style="width:500px;"> 
	<label for="txtName">Position : </label>
	<input id="user_position" name="position"  type="text" value="<?php echo stripslashes($title); ?>" /> 
</div>

<div class="field clear" style="width:500px;"> 
	<label for="txtName">Email : </label>
	<input id="email" name="email"  type="text"  value="<?php echo $email; ?>"/> 
</div>
<div class="field clear" style="width:500px;"> 
	<label for="txtName">Password : </label>
	<input id="password" name="password"  type="password"   /> 
			
</div>
<div class="field clear" style="width:500px;"> 
	<label for="txtName">Confirm Password : </label>
	<input id="cpassword" name="cpassword"  type="password" /> 
</div>
<div class="field clear" style="width:500px;"> 
	<label for="txtName">Phone : </label>
	<input id="phone" name="phone"  type="text" value="<?php echo $phone; ?>"  /> 
</div>

<?php 
$this_city_id = $this->session->userdata('city_id');
if($this->user_auth->get_permission('change_city')) { ?>
<div class="field clear" style="width:500px;">
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
</div>
<?php } else { ?>
	<input type="hidden" name="city" value="<?php echo $this_city_id; ?>" />
<?php } ?>

<div class="field clear" style="width:500px;">
<label for="selBulkActions">Select center:</label> 
<select id="center" name="center"> 
<option selected="selected" value="-1" >- Choose -</option> 
	<?php 
	$center = $center->result_array();
	foreach($center as $row){ ?>
	<?php if($center_id==$row['id']){ ?>
	<option value="<?php echo $row['id']; ?>" selected="selected"><?php echo $row['name']; ?></option> 
	<?php } else { ?>
	<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
	<?php }} ?>
</select>
</div>


<div class="field clear" style="width:500px;">
<label for="selBulkActions">Select project:</label> 
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
</div>

<div class="field clear" style="width:600px; margin-left:100px;">
	<label for="date">Photo</label>
	<?php if($photo) { ?><img src="<?php echo base_url().'pictures/'.$photo; ?>" width="100" style="float:left;" height="100" /><?php } ?>
</div>
 <div  class="field clear" style="width:600px; margin-left:100px;">
	<label for="date">Change photo</label>
	<input name="image"  id="image" type="file">
	<p class="error clear"></p>
</div>

<div class="field clear" style="width:500px;"> 
	<label for="type">User Type : </label>
	<select name="type">
		<option value="applicant" <?php if($user_type == 'applicant') echo ' selected="selected"'; ?>>Applicant</option>
		<option value="volunteer" <?php if($user_type == 'volunteer') echo ' selected="selected"'; ?>>Volunteer</option>
		<option value="well_wisher" <?php if($user_type == 'well_wisher') echo ' selected="selected"'; ?>>Well Wisher</option>
		<option value="alumni"> <?php if($user_type == 'alumni') echo ' selected="selected"'; ?>Alumni</option>
		<option value="other" <?php if($user_type == 'other') echo ' selected="selected"'; ?>>Other</option>
	</select>
</div>

<div class="field clear" style="width:550px;"> 
		<input type="hidden" value="<?php echo $root_id; ?>"  id="rootId" name="rootId" />
		<input style="margin-left:250px;" id="btnSubmit" class="button primary" type="submit" value="Submit" />
</div>
</fieldset>
</form>
            
<script language="javascript">
function validate()
{
	if(document.getElementById("user_name").value == '')
			{
				alert("Name Missing.");
				return false;
			}
	if(document.getElementById("email").value == '')
			{
				alert("Select Email.");
				return false;
			}
		
	if(document.getElementById("password").value != document.getElementById("cpassword").value)
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
