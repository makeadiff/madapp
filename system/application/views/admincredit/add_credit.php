<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#shown').hide();
    });
	function addnew_fields(text)
	{
		if(text=='other')
		{
		 $('#shown').show();
		}
		else
		{
		$('#shown').hide();
		}
	}
</script>
<?php $this->load->view('layout/thickbox_header'); ?>

<h2>Add Credit</h2>
<div id="message"></div>
<form  class="mainForm clear" id="formEditor"  action="<?php echo site_url('admincredit/insert_credit')?>" method="post" enctype="multipart/form-data" onsubmit="return validate();" >
<ul class="form city-form">

<li><label for="date">Users: </label>
<select id="user" name="user" > 
<option selected="selected" value="-1" >- Choose -</option> 
<?php foreach($users as $row){ ?>
	<option value="<?=$row->id?>"><?=$row->name?></option> 
	 <?php } ?>
</select>
</li>
<li><label for="date">Task: </label>
<select id="task" name="task"  onChange="javascript:addnew_fields(this.value);" >
<option selected="selected" value="-1" >- Choose -</option>  
<?php foreach($task as $row){ ?>
	<option value="<?=$row->id?>"><?=$row->name?></option> 
    <?php } ?>
    <option value="other">Other</option> 
</select>
</li>
<div id="shown">
<li><label for="date">Reason: </label>
	<input name="reason"  id="reason" type="text">
	<p class="error clear"></p>
</li>
<li><label for="date">Type: </label>
<select id="type" name="type" > 
<option selected="selected" value="-1" >- Choose -</option> 
	<option value="hr">hr</option> 
	<option value="pr">pr</option> 
    <option value="eph">eph</option> 
    <option value="cr">cr</option> 
    <option value="finance">finance</option> 
    <option value="ops">ops</option> 
</select>
</li>
<li><label for="date">Credit: </label>
	<input name="credit"  id="credit" type="text">
	<p class="error clear"></p>
</li>
</div>
 </ul>
 <ul>
<li>

<input  id="btnSubmit" class="button green" type="submit" value="+ Add Credit" />
<a href="<?=site_url('admincredit/index')?>" class="sec-action">Cancel</a>
</li>
</ul>
</form>
<script>
function validate()
{

if(document.getElementById("user").value == '-1')
	{
		alert("Select  One User");
		return false;
	}

if(document.getElementById("task").value == '-1')
	{
		alert("Select One Task");
		return false;
	}
if(document.getElementById("task").value == 'other')
{
	if(document.getElementById("reason").value == '')
	{
		alert("Enter Reason");
		return false;
	}
	if(document.getElementById("type").value == '-1')
	{
		alert("Select One Type");
		return false;
	}
	if(document.getElementById("credit").value == '')
	{
		alert("Enter Credit");
		return false;
	}
}

}
</script>

