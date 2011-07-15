<?php $this->load->view('layout/header'); ?>

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
	$photo=$row['photo'];
}

?>

<form id="formEditor" class="mainForm clear form-area" action="<?=site_url('user/update_profile')?>" method="post" onsubmit="return validate();" enctype="multipart/form-data" >
<fieldset class="clear">
<?php echo $msg; ?>
<label for="txtName">Name : </label>
<input id="user_name" name="name"  type="text" value="<?php echo $name; ?>"/> <br />

<label for="txtName">Email : </label>
<input id="email" name="email"  type="text"  value="<?php echo $email; ?>"/><br /> 

<label for="txtName">Password : </label>
<input id="password" name="password"  type="password"   /> <br />
			
<label for="txtName">Confirm Password : </label>
<input id="cpassword" name="cpassword"  type="password" /><br /> 

<label for="txtName">Phone : </label>
<input id="phone" name="phone"  type="text" value="<?php echo $phone; ?>"  /><br /> 

<label for="txtName">Address : </label>
<textarea id="address" name="address"  rows="5" cols="30"><?php echo $address; ?></textarea><br />

<label for="date">Photo</label>
<img src="<?php echo base_url().'pictures/'.$photo; ?>" width="100" style="float:left;" height="100" /><br />

<label for="date">Change photo</label>
<input name="image"  id="image" type="file"><br />
<p class="error clear"></p>

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

<?php $this->load->view('layout/footer'); ?>