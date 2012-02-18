<?php $this->load->view('layout/thickbox_header'); ?>
<style type="text/css">
label { width:200px !important; }
</style>
<script type="text/javascript">
function selectAll() {
	$(".users").attr("checked", true);
}

function validate() {
	if(document.getElementById("users").checked == '') {
		alert("Select one User");
		return false;
	}
}
</script>

<h2>Add Users To This Event</h2>

<div id="message"></div>

<form action="" method="post">
<ul class="form city-form">
<?php $row = reset($events); ?>
<li>Current Event: <strong><?php echo $row->name;?></strong></label></li>
<li>User Group: <br />
	<select name="user_group[]" id="user_group" style="width:150px; height:100px;" multiple>
	<?php
	foreach($all_groups as $id=>$gname) { ?>
	<option value="<?php echo $id; ?>"<?php 
		if(in_array($id, $selected_user_groups)) echo 'selected="selected"';
	?>><?php echo $gname; ?></option>
	<?php } ?>
	</select>
</li>
<li>Center: <br />
	<select name="center[]" id="center" style="width:150px; height:100px;" multiple>
	<?php
	foreach($all_centers as $id=>$name) { ?>
	<option value="<?php echo $id; ?>"<?php 
		if(in_array($id, $selected_centers)) echo 'selected="selected"';
	?>><?php echo $name; ?></option>
	<?php } ?>
	</select>
</li>
<li><input type="submit" value="Filter" name="action" /></li>
</ul>
</form>


<form class="form-area" action="<?php echo site_url('event/insert_userevent')?>" method="post" onsubmit="return validate();" >
<input type="hidden" value="<?php echo $row->id; ?>" name="event" id="event" />
<ul>
<li><label for="txtName"><strong>Users :</strong></label></li>
<li><a onclick="selectAll()">Select All</a></li>
