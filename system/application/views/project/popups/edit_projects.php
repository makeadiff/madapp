<?php $this->load->view('layout/thickbox_header'); ?>

<?php
$details=$details->result_array();
foreach($details as $row)
{
$root_id=$row['id'];
$name=$row['name'];
//$added_on=$row['added_on'];
}

?>
<form id="formEditor" class="mainForm clear" action="<?=site_url('project/update_project')?>" method="post" onsubmit="return validate();" >
<fieldset class="clear">
<ul class="form city-form">
<li>

			<label for="txtName">Name : </label>
			<input id="name" name="name"  type="text"  value="<?php echo $name; ?>"/> 
			
</li>
</ul>
<ul>
<li>
		<input type="hidden" value="<?php echo $root_id; ?>"  id="rootId" name="rootId" />
		<input  id="btnSubmit" class="button green" type="submit" value="Update" />
</li>
</ul>
</fieldset>
</form>


	<script>
function validate()
{
if(document.getElementById("name").value == '')
	{		
		alert("Project Name Missing.");
		return false;
	}
}
</script>