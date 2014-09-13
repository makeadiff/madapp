<?php $this->load->view('layout/thickbox_header'); ?>
 <h2>Add Project</h2>
<form id="formEditor" class="mainForm clear" action="<?=site_url('project/addproject')?>" method="post"  onsubmit="return validate();" >
<fieldset class="clear">

<ul class="form city-form">
<li>
			<label for="txtName">Project Name : </label>
			<input id="name" name="name"  type="text" /> 
</li>
</ul>
<ul>
<li>
		<input id="btnSubmit" class="button green" type="submit" value="Submit" />
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