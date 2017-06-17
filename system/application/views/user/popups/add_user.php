<?php $this->load->view('layout/thickbox_header'); ?>
<h2>Add User</h2>
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
if(!document.getElementById("emails").value.match(/^[\w\.\-\+]+\@\w+\.[\w\.]{2,5}/))
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
<form id="formEditor"  style="width:550px;" name="formEditor" class="mainForm form-area clear" action="<?php echo site_url('user/adduser')?>" method="post" enctype="multipart/form-data" onSubmit="return validate();">
<fieldset class="clear">
<ul class="form city-form">
<li>
<label for="txtName">Name : </label> <input id="names" name="names"  type="text" /><br />
</li>

<li>
<label for="group">Select Group:</label> 
<select id="group" name="group[]" multiple="multiple"> 
	<?php
	foreach($all_groups as $id => $name) {
		if(($id == 1 or $id == 3) and !$this->user_auth->get_permission('permissions_index')) continue; // :HARD-CODE:. To make sure that city people can't create user with very big premissions.
	?>
	<option value="<?php echo $id; ?>"><?php echo $name; ?></option> 
	<?php } ?>
</select>
</li>
<li>
	<label for="email">Email : </label>
	<input id="emails" name="emails"  type="text" /><br />
</li>
<li>
	<label for="mad_email">MAD Email : </label>
	<input id="mad_email" name="mad_email"  type="text"  value=""/>
</li>
<li>
	<label for="password">Password : </label>
	<input id="spassword" name="spassword"  type="password" /><br />	 
</li>			
<li>
	<label for="cpassword">Confirm Password : </label>
	<input id="scpassword" name="scpassword"  type="password" /><br />	 
</li>
<li>
	<label for="txtName">Phone : </label>
	<input id="phone" name="phone"  type="text" /><br />	 
</li>

<li> 
	<label for="txtName">Address : </label>
	<textarea id="address" name="address"  rows="5" cols="30"></textarea><br />
</li>

<li>
	<label for="sex">Sex : </label>
	<select id="sex" name="sex">
		<option value="m">Male</option>
		<option value="f">Female</option>
	</select>
</li>
	

<li>
	<label for="txtName">Joined On : </label>
	<input id="joined_on" name="joined_on" class="date-pick" type="text" value=""  /><br />
</li>

<li>
	<label for="txtName">Left On : </label>
	<input id="left_on" name="left_on" class="date-pick" type="text" value=""  /><br />
</li>

<li>
<label for="type">User Type : </label>
<select name="type">
	<option value="applicant">Applicant</option>
	<option value="volunteer" selected="selected">Volunteer</option>
	<option value="well_wisher">Well Wisher</option>
	<option value="alumni">Alumni</option>
	<option value="other">Other</option>
</select>
</li>

<li>
	<label for="image">Upload Photo</label>
	<input name="image"  id="image" type="file">
	<p class="error clear"></p>
</li>
</ul>

<ul>
<li>
<input class="button green"  type="submit"  value="Submit" />
<a href="<?php echo site_url('user/view_users');?>" class="cancel-button">Cancel</a>

</li>
</ul>

</form>

<?php $this->load->view('layout/thickbox_footer'); ?>
