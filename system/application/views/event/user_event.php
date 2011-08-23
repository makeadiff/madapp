<?php $this->load->view('layout/thickbox_header'); ?>
<h2>Add Users To This Event</h2>
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
<form  class="mainForm clear" id="formEditor"  action="<?php echo site_url('event/insert_userevent')?>" method="post" enctype="multipart/form-data" onsubmit="return validate();" >
<ul class="form city-form">
<li><label for="selBulkActions">Current Event: </label> 
<?php foreach($events as $row) { ?>
<?php $id=$row->id ;?>
<input type="text" style="background:#CCCCCC; width:260px;" disabled="disabled" value="<?=$row->name;?>" />
<?php } ?>
</li>
<li>
<label for="txtName"><strong>Users :</strong></label>
<?php 
$users=$users->result_array();
foreach($users as $row){?>
<li><label for="users-<?php echo $row['id']; ?>"><?php echo $row['name']; ?></label>
<input type="hidden" value="<?php $id?>" name="event" id="event" />
<input type="checkbox" value="<?php echo $row['id']; ?>" id="users-<?php echo $row['id']; ?>" name="users[]" />
</li>
<?php } ?>
</li>
</ul>

<ul>
<li>
<input class="button green" type="submit" value="+ Add Users Event" />
<a href="<?php echo site_url('event/index')?>" class="sec-action">Cancel</a>
</li>
</ul>
</form>
<script>
function validate()
{
if(document.getElementById("event").value == '-1')
	{		
		alert("Select a Event");
		return false;
	}
if(document.getElementById("users").checked == '')
	{
		alert("Select one User");
		return false;
	}
}
</script>

