<?php $this->load->view('layout/thickbox_header'); ?>
<h2>Add Centers</h2>
<form id="formEditor" class="mainForm clear" action="<?php echo site_url('center/addCenter')?>" method="post" style="width:500px;" onsubmit="return validate();"  >
<fieldset class="clear">
<ul class="form city-form">
<li>
<label for="center">Center : </label>
<input id="center" name="center"  type="text" value="" /> 
</li>

<li>
<label for="user_id">Select Head:</label> 
<?php echo form_dropdown('user_id', idNameFormat($all_users)); ?>
</li>
</ul>
<ul>
<li>
<input id="btnSubmit" class="button green" type="submit" value="Save" />
<a href="<?php echo site_url('center/manageaddcenters')?>" class="cancel-button">Cancel</a>
</li>
</ul>
</fieldset>
</form>
<script>
function validate() {
if(document.getElementById("center").value == '')
	{
		alert("Center Missing.");
		return false;
	}
}
</script>
