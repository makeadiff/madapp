<?php $this->load->view('layout/header'); ?>

<form id="formEditor" class="mainForm clear form-area" action="<?php echo site_url('user/update_profile')?>" method="post" onsubmit="return validate();" enctype="multipart/form-data" >
<fieldset class="clear">
<label for="user_name">Name : </label>
<input id="user_name" name="name"  type="text" value="<?php echo $user->name; ?>"/> <br />

<label for="email">Email : </label>
<input id="email" name="email"  type="text"  value="<?php echo $user->email; ?>"/><br /> 

<label for="password">Password : </label>
<input id="password" name="password"  type="password" /> <br />
			
<label for="cpassword">Confirm Password : </label>
<input id="cpassword" name="cpassword"  type="password" /><br /> 

<label for="phone">Phone : </label>
<input id="phone" name="phone"  type="text" value="<?php echo $user->phone; ?>"  /><br /> 

<label for="address">Address : </label>
<textarea id="address" name="address"  rows="5" cols="30"><?php echo $user->address; ?></textarea><br />

<label for="phone">Sex: </label>
<select id="sex" name="sex">
		<option value="m" <?php if($user->sex == 'm') echo ' selected="selected"'; ?>>Male</option>
		<option value="f" <?php if($user->sex == 'f') echo ' selected="selected"'; ?>>Female</option>
	</select><br />


<?php if($user->photo) { ?>
<label for="date">Photo</label>
<img src="<?php echo base_url().'uploads/users/thumbnails/'.$user->photo; ?>" style="float:left;" /><br />
<?php } ?>

<label for="date">Change photo</label>
<input name="image"  id="image" type="file"><br />
<p class="error clear"></p>
<br />

<?php if($this->user_auth->get_permission('user_edit_bank_details')) { ?>
<a href="<?php echo site_url('user/edit_bank_details'); ?>" class="popup">Edit Bank Details</a>
<?php } ?>

<div class="field clear" style="width:550px;"> 
		<input type="hidden" value="<?php echo $user->id; ?>"  id="rootId" name="rootId" />
		<input style="margin-left:250px;" id="btnSubmit" class="button green primary" type="submit" value="Submit" />
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