<?php $this->load->view('layout/thickbox_header'); ?>
<script src="<?php echo base_url();?>js/datepicker.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/calender.css" />
<script src="<?php echo base_url()?>js/cal.js" type="text/javascript"></script>
<?php
$sdt=2011;
$edt=date('Y');
?>
<script type="text/javascript">
jQuery(document).ready(function () {
	$('#class_starts_on').simpleDatepicker({ startdate: <?php echo $sdt; ?>, enddate: <?php echo $edt; ?>, chosendate:new Date(<?php echo date('Y-m-d'); ?>)});
});
</script>

<h2>Add Centers</h2>
<form id="formEditor" class="mainForm clear" action="<?php echo site_url('center/addCenter')?>" method="post" style="width:500px;" onsubmit="return validate();"  >
<fieldset class="clear">
<ul class="form city-form">
<li>
<label for="center">Center : </label>
<input id="center" name="center"  type="text" value="" /> 
</li>

<li>
<label for="user_id">Select Head:</label> 
<?php echo form_dropdown('user_id', idNameFormat($all_users)); ?>
</li>

<li>
<label for="user_id">Class Starts on: </label>
<input type="text" id="class_starts_on" name="class_starts_on" />
</li>
</ul>
<ul>
<li>
<input id="btnSubmit" class="button green" type="submit" value="Save" />
<a href="<?php echo site_url('center/manageaddcenters')?>" class="cancel-button">Cancel</a>
</li>
</ul>
</fieldset>
</form>
<script>
function validate() {
if(document.getElementById("center").value == '')
	{
		alert("Center Missing.");
		return false;
	}
}
</script>
