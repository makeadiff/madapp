<?php $this->load->view('layout/thickbox_header'); ?>
<h2>Edit Kids</h2>
<script type="text/javascript" src="<?php echo base_url()?>css/datetimepicker_css.js"></script>

<style>
.fields{
float:left;
width:300px;
padding-bottom:5px;
padding-top:5px;
}
</style>
<?php
$kids_details=$kids_details->result_array();
foreach($kids_details as $row) {
	$root_id=$row['id'];
	$name=$row['name'];
	$center_id=$row['center_id'];
	$sex = $row['sex'];
	$birthday = $row['birthday'];
	// $birthday = date("m/d/Y", strtotime($birthday));
	$description=$row['description'];
	$photo = $row['photo'];
}

?>
<form class="mainForm clear" id="formEditor" action="<?php echo site_url('kids/update_kids')?>" method="post" enctype="multipart/form-data" onsubmit="return validate();" >
<ul class="form city-form">
<li><label for="selBulkActions">Select Center</label> 
<select id="center" name="center" > 
<option selected="selected" >- Choose -</option> 
	<?php 
	$center = $center->result_array();
	foreach($center as $row)
	{ ?>
	<?php if($center_id==$row['id']) { 
	?>
	<option value="<?php echo $row['id']; ?>" selected="selected"><?php echo $row['name']; ?></option> 
	<?php }else{ ?> 
	<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option> 
	<?php } }?>
</select>
</li>
<li><label for="txtName">Name</label>
	<input id="name" name="name"  type="text"  value="<?php echo $name; ?>"/> 
			
</li>
<li><label for="date">Date of Birth</label>
	<input name="date-pick" class="date-pick" id="date-pick" type="text" value="<?php echo $birthday ; ?>">
    <img src="<?php echo base_url()?>images/calender_images/cal.gif" onclick="javascript:NewCssCal ('date-pick','yyyyMMdd','arrow')" style="cursor:pointer"/>
	<p class="error clear"></p>
</li><br />
<li><label for="sex">Sex: </label>
	<select name="sex">
	<option value="m" <?php if($sex == 'm') echo 'selected="selected"'; ?>>Male</option>
	<option value="f" <?php if($sex == 'f') echo 'selected="selected"'; ?>>Female</option>
	</select>
	<p class="error clear"></p>
</li><br />
<li><label for="date">Photo</label>
	<?php if($photo) { ?><img src="<?php echo base_url().'uploads/kids/thumbnails/'.$photo; ?>" style="float:left;" /><?php } ?>
</li><br />
<li><label for="date">Change photo</label>
	<input name="image"  id="image" type="file">
	<p class="error clear"></p>
</li><br />
<li><label for="txtName"  >Description</label>
	<textarea rows="5" cols="24" id="description" name="description"><?php echo $description; ?></textarea> 
	<p class="error clear"></p>
</li>
</ul>
<ul>
<li>
	<input type="hidden" value="<?php echo $root_id; ?>"  id="rootId" name="rootId" />
	<input id="btnSubmit" class="button green" type="submit" value="Update" />
	
	<a href="<?=site_url('kids/manageaddkids')?>" class="sec-action">Cancel</a>
</li>
</form>
<p class="info with-icon">Use date format 'YYYY-MM-DD' if you wish to enter the date manually.</p>

<script> 
function validate() {
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
}
</script>
<?php $this->load->view('layout/thickbox_footer'); ?>