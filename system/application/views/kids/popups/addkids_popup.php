<?php $this->load->view('layout/css',array('thickbox'=>true)); ?>

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

<form id="formEditor" class="mainForm clear form-area" action="<?php echo site_url('kids/addkids')?>" method="post" enctype="multipart/form-data" onsubmit="return validate();" >
<fieldset class="clear">
<div class="field clear">
<label for="selBulkActions">Select Center</label> 
<select id="center" name="center" > 
<option selected="selected" value="-1" >- Choose -</option> 
	<?php
	$center = $center->result_array();
	foreach($center as $row) {
	?>
	<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option> 
	<?php } ?>
</select>
</div><br />

<div class="field clear"> 
	<label for="txtName">Name</label>
	<input id="name" name="name"  type="text" /> 
			
</div><br />

<div  class="field clear">
	<label for="date">Date of Birth</label>
	<input name="date-pick" class="date-pick" id="date-pick" type="text">
	<p class="error clear"></p>
</div><br />

<div  class="field clear">
	<label for="date">Upload Photo</label>
	<input name="image"  id="image" type="file">
	<p class="error clear"></p>
</div><br />


<div class="field clear"> 
	<label for="txtName">Description</label>
	<textarea rows="5" cols="30" id="description" name="description"></textarea> 
	<p class="error clear"></p>
</div><br />



<div class="field clear" style="width:550px;">
<input style="margin-left:250px;" id="btnSubmit" class="button primary" type="submit" value="Add" />

<a href="#" class="cancel-button">Cancel</a>
</div>
</fieldset>
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

<?php $this->load->view('layout/thickbox_footer'); ?>