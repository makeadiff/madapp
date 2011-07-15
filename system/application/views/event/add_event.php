<?php $this->load->view('layout/thickbox_header'); ?>
<h2>Add Event</h2>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/calender.css" />
<script src="<?php echo base_url()?>js/cal.js"></script>

<?php
$edt=date('Y')-2;
$sdt=date('Y')-20;
?>
<script>
jQuery(document).ready(function () {
	$('input#date-pick').simpleDatepicker({ startdate: <?php echo $sdt; ?>, enddate: <?php echo $edt; ?>, chosendate:new Date('2000-01-01')});
	$('input#date-pick-ends').simpleDatepicker({ startdate: <?php echo $sdt; ?>, enddate: <?php echo $edt; ?>, chosendate:new Date('2000-01-01')});
});
</script>
<div id="message"></div>
<form  class="mainForm clear" id="formEditor"  action="<?php echo site_url('event/insert_event')?>" method="post" enctype="multipart/form-data" onsubmit="return validate();" >
<ul class="form city-form">
	<li><label for="txtName">Name: </label>
	<input id="name" name="name"  type="text" /> 
	</li>		

<li><label for="date">Starts On: </label>
	<input name="date-pick" class="date-pick" id="date-pick" type="text">
	<p class="error clear"></p>
</li>
<li><label for="date">Ends On: </label>
	<input name="date-pick-ends" class="date-pick" id="date-pick-ends" type="text">
	<p class="error clear"></p>
</li>
<li><label for="date">Place: </label>
	<input name="place"  id="place" type="text">
	<p class="error clear"></p>
</li>
<li><label for="date">Type: </label>
<select id="type" name="type" > 
<option selected="selected" value="-1" >- Choose -</option> 
	<option value="1">process</option> 
	<option value="2">curriculam</option> 
    <option value="3">teacher</option> 
</select>
</li>

 </ul>
 <ul>
<li>
<input  id="btnSubmit" class="button green" type="submit" value="+ Add New Event" />
<a href="<?=site_url('event/index')?>" class="sec-action">Cancel</a>
</li>
</ul>
</form>
<script>
function validate()
{
if(document.getElementById("city").value == '-1')
	{		
		alert("Select a City");
		return false;
	}
if(document.getElementById("name").value == '')
	{
		alert("Name missing");
		return false;
	}
if(document.getElementById("date-pick").value == '')
	{
		alert("Start Date missing");
		return false;
	}
if(document.getElementById("date-pick-ends").value == '')
	{
		alert("End Date missing");
		return false;
	}
if(document.getElementById("place").value == '')
	{
		alert("Place missing");
		return false;
	}
if(document.getElementById("type").value == '-1')
	{
		alert("Select Event Type");
		return false;
	}
}
</script>

