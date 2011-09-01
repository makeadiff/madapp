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

