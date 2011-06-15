<?php $this->load->view('layout/thickbox_header'); ?>
<h2>Edit Kids</h2>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/calender.css" />
<script src="<?php echo base_url()?>js/cal.js"></script>
<?php
$edt=date('Y')-2;
$sdt=date('Y')-20;
?>
<style>
.fields{
float:left;
width:300px;
padding-bottom:5px;
padding-top:5px;
}
</style>
<script>
jQuery(document).ready(function () {
	$('input#date-pick').simpleDatepicker({ startdate: <?php echo $sdt; ?>, enddate: <?php echo $edt; ?> });
});
</script>
<?php
$kids_details=$kids_details->result_array();
foreach($kids_details as $row) {
	$root_id=$row['id'];
	$name=$row['name'];
	$center_id=$row['center_id'];
	$birthday = $row['birthday'];
	$birthday = explode("-",$birthday);
	$birthday = $birthday[2]."/".$birthday[1]."/".$birthday[0];
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
	<p class="error clear"></p>
</li><br />
<li><label for="date">Photo</label>
	<?php if($photo) { ?><img src="<?php echo base_url().'pictures/'.$photo; ?>" width="50" style="float:left;" height="50" /><?php } ?>
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