<?php $this->load->view('layout/thickbox_header'); ?>
<h2>Edit Center</h2>
<?php
$details=$details->result_array();
foreach($details as $row) {
	$root_id=$row['id'];
	$name=$row['name'];
	$city_id=$row['city_id'];
	$user_id=$row['center_head_id'];
}

?>
<form id="formEditor" class="mainForm clear" action="<?php echo site_url('center/update_Center')?>" method="post" style="width:500px;"  onsubmit="return validate();">
<fieldset class="clear">
<ul class="form city-form">
<li>
<label for="center">Center : </label>
<input id="center" name="center"  type="text" value="<?php echo $name; ?>" /> 
</li>

<li>
<label for="user_id">Select Head:</label> 
<?php echo form_dropdown('user_id', idNameFormat($all_users), $user_id); ?>
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