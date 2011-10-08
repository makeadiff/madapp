<?php $this->load->view('layout/thickbox_header'); ?>
<h2>Add Task</h2>
<div id="message"></div>
<form  class="mainForm clear" id="formEditor"  action="<?php echo site_url('task/insert_task')?>" method="post" enctype="multipart/form-data" onsubmit="return validate();" >
<ul class="form city-form">
	<li><label for="name">Name: </label>
	<input id="name" name="name"  type="text" /> 
</li>		
<li><label for="credit">Credit: </label>
	<input name="credit" value="1" id="credit" type="text">
	<p class="error clear"></p>
</li>
<li><label for="type">Type: </label>
<select id="type" name="type" >
	<option value="hr">HR</option> 
	<option value="pr">PR</option> 
    <option value="eph">EPH</option> 
    <option value="cr">CR</option> 
	<option value="placements">Placements</option>
	<option value="finance">Finance</option>
    <option value="ops">Operations</option> 
</select>
</li>
</ul>

<ul>
<li>
<input  id="btnSubmit" class="button green" type="submit" value="+ Add New Task" />
<a href="<?php echo site_url('task/index')?>" class="sec-action">Cancel</a>
</li>
</ul>
</form>
<script>
function validate()
{

if(document.getElementById("name").value == '')
	{
		alert("Name missing");
		return false;
	}

if(document.getElementById("credit").value == '')
	{
		alert("Credit missing");
		return false;
	}
if(document.getElementById("type").value == '-1')
	{
		alert("Select  Type");
		return false;
	}
}
</script>

