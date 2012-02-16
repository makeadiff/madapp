<?php $this->load->view('layout/thickbox_header'); ?>
<style type="text/css">
label { width:200px !important; }
</style>

<h2>Add Users To This Event</h2>

<div id="message"></div>
<form  class="mainForm clear" id="formEditor"  action="<?php echo site_url('event/insert_userevent')?>" method="post" enctype="multipart/form-data" onsubmit="return validate();" >

<ul class="form city-form">
<li><label for="selBulkActions">Current Event: </label> 
<?php foreach($events as $row) { ?>
<input type="hidden" value="<?php echo $row->id; ?>" name="event" id="event" />
<input type="text" style="background:#CCCCCC; width:260px;" disabled="disabled" value="<?php echo $row->name;?>" />
<?php } ?>
</li>
<li>
<label for="txtName"><strong>Users :</strong></label>

