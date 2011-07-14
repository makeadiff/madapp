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
});
</script>
<div id="message"></div>
<form  class="mainForm clear" id="formEditor"  action="<?php echo site_url('event/insert_userevent')?>" method="post" enctype="multipart/form-data" onsubmit="return validate();" >
<ul class="form city-form">
<li><label for="selBulkActions">Select Event: </label> 
<select id="event" name="event" > 
<option selected="selected" value="-1" >- Choose -</option> 
<?php foreach($events as $row) { ?>
<option value="<?=$row->id?>"><?=$row->name;?></option>
<?php } ?>
</select>
</li>
 <label for="txtName">Users :</label>
<div  style="height:100px; overflow:scroll; border:1px solid #999; padding:5px; width: 250px; overflow-x:hidden; float:left;">
<li>
       
		<?php $users=$users->result_array();
		foreach($users as $row){?>
         </li>
         <li>
         <label for="txtName"><?php echo $row['name']; ?></label>
         <input type="checkbox" value="<?php echo $row['id']; ?>" id="users" name="users[]" /> 
		<?php } ?>
			</li></div>	

 </ul>
 <ul>
<li>
<input  id="btnSubmit" class="button green" type="submit" value="+ Add Users Event" />
<a href="<?=site_url('event/index')?>" class="sec-action">Cancel</a>
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

