<?php $this->load->view('layout/css',array('thickbox'=>true)); ?>

<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<form id="formEditor" class="mainForm clear" action="<?=site_url('permission/addpermission')?>" method="post" style="width:500px;" onsubmit="return validate();">
<fieldset class="clear" style="margin-top:50px;width:500px;margin-left:-30px;">

<div class="field clear" style="width:600px;"> 
		<label for="txtName">Permission Name : </label>
		<input id="permission" name="permission"  type="text" /> 
</div>
<div class="field clear" style="width:550px;"> 
		<input style="margin-left:250px;" id="btnSubmit" class="button primary" type="submit" value="Submit" />
</div>
</fieldset>
</form>

<script>
function validate()
{
if(document.getElementById("permission").value == '')
	{		
		alert("Permission Name Missing.");
		return false;
	}
}
</script>