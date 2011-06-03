<?php $this->load->view('layout/css',array('thickbox'=>true)); ?>

<form id="formEditor" class="mainForm clear" action="<?=site_url('project/addproject')?>" method="post" style="width:500px;" onsubmit="return validate();" >
<fieldset class="clear">

<div class="field clear" style="width:600px;"> 
			<label for="txtName">Project Name : </label>
			<input id="name" name="name"  type="text" /> 
</div>

<div class="field clear" style="width:550px;"> 
		<input style="margin-left:250px;" id="btnSubmit" class="button primary" type="submit" value="Submit" />
</div>
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