<?php $this->load->view('layout/thickbox_header'); ?>
<h2>Add Task</h2>
<div id="message"></div>
<form  class="mainForm clear" id="formEditor"  action="<?php echo site_url('task/insert_task')?>" method="post" enctype="multipart/form-data" onsubmit="return validate();" >
<ul class="form city-form">
	<li><label for="txtName">Name: </label>
	<input id="name" name="name"  type="text" /> 
</li>		
<li><label for="date">Credit: </label>
	<input name="credit"  id="credit" type="text">
	<p class="error clear"></p>
</li>
<li><label for="date">Type: </label>
<select id="type" name="type" > 
<option selected="selected" value="-1" >- Choose -</option> 
	<option value="1">HR</option> 
	<option value="2">PR</option> 
    <option value="3">EPH</option> 
    <option value="4">CR</option> 
    <option value="5">Finance</option> 
    <option value="6">Operations</option> 
</select>
</li>

 </ul>
 <ul>
<li>
<input  id="btnSubmit" class="button green" type="submit" value="+ Add New Task" />
<a href="<?=site_url('task/index')?>" class="sec-action">Cancel</a>
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

