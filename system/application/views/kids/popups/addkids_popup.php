<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/g.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/l.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/bk.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/r.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/validation.css" />
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/calender.css" />
<script src="<?php echo base_url()?>js/cal.js"></script>
<?php
$edt=date('Y')-2;
$sdt=date('Y')-20;
?>
<script>
jQuery(document).ready(function () {
	$('input#date-pick').simpleDatepicker({ startdate: <?php echo $sdt; ?>, enddate: <?php echo $edt; ?> });
});
</script>

<form id="formEditor" class="mainForm clear" action="<?php echo site_url('kids/addkids')?>" method="post" enctype="multipart/form-data" style="width:500px;" onsubmit="return validate();" >
<fieldset class="clear" style="margin-top:50px;width:500px;margin-left:-30px;">
<div class="field clear" style="width:600px;">
<label for="selBulkActions">Select center:</label> 
<select id="center" name="center" > 
<option selected="selected" value="-1" >- choose action -</option> 
	<?php 
	$center = $center->result_array();
	foreach($center as $row) {
	?>
	<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option> 
	<?php } ?>
</select>
</div>



<div class="field clear" style="width:600px;"> 
	<label for="txtName">Name : </label>
	<input id="name" name="name"  type="text" /> 
			
</div>

<div  class="field clear" style="width:600px;">
	<label for="date">Dob</label>
	<input name="date-pick" class="date-pick" id="date-pick" type="text">
	<p class="error clear"></p>
</div>

<div  class="field clear" style="width:600px;">
	<label for="date">Upload photo</label>
	<input name="image"  id="image" type="file">
	<p class="error clear"></p>
</div>


<div class="field clear" style="width:600px;"> 
	<label for="txtName">Description : </label>
	<textarea rows="5" cols="40" id="description" name="description"></textarea> 
	<p class="error clear"></p>
</div>



<div class="field clear" style="width:550px;"> 
		<input style="margin-left:250px;" id="btnSubmit" class="button primary" type="submit" value="Submit" />
</div>
</fieldset>
</form>


	
<script>
function validate()
{
if(document.getElementById("center").value == '-1')
	{		
		alert("Select a Center.");
		return false;
	}
if(document.getElementById("name").value == '')
	{
		alert("Name missing");
		return false;
	}
if(document.getElementById("date-pick").value == '')
	{
		alert("Date Missing");
		return false;
	}
}
</script>