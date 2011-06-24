<?php $this->load->view('layout/thickbox_header'); ?>
<h2>Add Kids</h2>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/calender.css" />
<script src="<?php echo base_url()?>js/cal.js"></script>

<?php
$edt=date('Y')-2;
$sdt=date('Y')-20;
?>
<script>
jQuery(document).ready(function () {
	$('input#date-pick').simpleDatepicker({ startdate: <?php echo $sdt; ?>, enddate: <?php echo $edt; ?>, chosendate:new Date('2000-01-01')});
});
</script>
<div id="message"></div>
<form  class="mainForm clear" id="formEditor"  action="<?php echo site_url('kids/addkids')?>" method="post" enctype="multipart/form-data" onsubmit="return validate();" >
<ul class="form city-form">
<li><label for="selBulkActions">Select Center: </label> 
<select id="center" name="center" > 
<option selected="selected" value="-1" >- Choose -</option> 
	<?php
	$center = $center->result_array();
	foreach($center as $row) {
	?>
	<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option> 
	<?php } ?>
</select>
</li>

	<li><label for="txtName">Name: </label>
	<input id="name" name="name"  type="text" /> 
	</li>		

<li><label for="date">Date of Birth: </label>
	<input name="date-pick" class="date-pick" id="date-pick" type="text">
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

