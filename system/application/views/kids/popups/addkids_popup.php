<?php $this->load->view('layout/thickbox_header'); ?>
<h2>Add Kids</h2>
<script type="text/javascript" src="<?php echo base_url()?>css/datetimepicker_css.js"></script>

<div id="message"></div>
<form  class="mainForm clear" id="formEditor"  action="<?php echo site_url('kids/addkids')?>" method="post" enctype="multipart/form-data" onsubmit="return validate();" >
<ul class="form city-form">
<li><label for="selBulkActions">Select Center: </label> 
<select id="center" name="center" > 
<option selected="selected" value="-1" >- Choose -</option> 
	<?php foreach($center as $row) { ?>
	<option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option> 
	<?php } ?>
</select>
</li>

	<li><label for="txtName">Name: </label>
	<input id="name" name="name"  type="text" /> 
	</li>		

<li><label for="date">Date of Birth: </label>
	<input name="date-pick" class="date-pick" id="date-pick" type="text">
    <img src="<?php echo base_url()?>images/calender_images/cal.gif" onclick="javascript:NewCssCal ('date-pick','yyyyMMdd','arrow')" style="cursor:pointer"/>
	<p class="error clear"></p>
</li>
<li><label for="sex">Sex: </label>
	<select name="sex">
	<option value="m">Male</option>
	<option value="f">Female</option>
	</select>
	<p class="error clear"></p>
</li>
<li><label for="date">Upload Photo: </label>
	<input name="image"  id="image" type="file">
	<p class="error clear"></p>
</li>

<li><label for="txtName">Description: </label>
	<textarea rows="5" cols="24" id="description" name="description"></textarea> 
</li>
 </ul>
 <ul>
<li>
<input  id="btnSubmit" class="button green" type="submit" value="+ Add New Kid" />
<a href="<?=site_url('kids/manageaddkids')?>" class="sec-action">Cancel</a>
</li>
</ul>
</form>
<p class="info with-icon">Use date format 'YYYY-MM-DD' if you wish to enter the date manually.</p>

<script>
function validate()
{
if(document.getElementById("center").value == '-1')
	{		
		alert("Select a Center");
		return false;
	}
if(document.getElementById("name").value == '')
	{
		alert("Name missing");
		return false;
	}
}
</script>


<?php $this->load->view('layout/thickbox_footer'); ?>