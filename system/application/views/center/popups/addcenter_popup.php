<?php $this->load->view('layout/thickbox_header'); ?>
<h2>Add Centers</h2>
<form id="formEditor" class="mainForm clear" action="<?php echo site_url('center/addCenter')?>" method="post" style="width:500px;" onsubmit="return validate();"  >
<fieldset class="clear">
<ul class="form city-form">
	<?php 
	$this_city_id = $this->session->userdata('city_id');
	if($this->user_auth->get_permission('change_city')) { ?>
<li>
		<label for="selBulkActions">Select city:</label> 
		<select id="city" name="city" > 
		<option value="0" >- choose action -</option> 
			<?php 
			$details = $details->result_array();
			foreach($details as $row) {
			?>
			<option value="<?php echo $row['id']; ?>" <?php if($this_city_id == $row['id']) print ' selected="selected"'; ?>><?php echo $row['name']; ?></option> 
			<?php } ?>
		</select>
		</li>
<?php } else { ?>
	<input type="hidden" name="city" value="<?php echo $this_city_id; ?>" />
<?php } ?>

<li>
<label for="selBulkActions">Select Head:</label> 
<select id="user_id" name="user_id"> 
<option selected="selected" value="0" >- Choose -</option> 
	<?php 
	$user_name = $user_name->result_array();
	foreach($user_name as $row)
	{
	?>
	<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option> 
	<?php } ?>
</select>
</li>
<li>
			<label for="txtName">Center Name : </label>
			<input id="center" name="center"  type="text" /> 
</li>
</ul>
<ul>
<li>
<input id="btnSubmit" class="button green" type="submit" value="Save" />
<a href="<?=site_url('center/manageaddcenters')?>" class="cancel-button">Cancel</a>
</li>
</ul>
</fieldset>
</form>
<script>
function validate()
{
if(document.getElementById("city").value == '0')
	{		
		alert("Select a City.");
		return false;
	}
if(document.getElementById("center").value == '')
	{
		alert("Center Missing.");
		return false;
	}
}
</script>
