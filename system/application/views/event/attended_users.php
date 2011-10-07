<?php $this->load->view('layout/thickbox_header'); ?>
<h2>Users  Status In This Event</h2>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/calender.css" />
<script src="<?php echo base_url()?>js/cal.js"></script>
<?php
$edt=date('Y')-2;
$sdt=date('Y')-20;
?>
<script>
function update_userstatus(event_id,user_id)
{
	$.ajax({
				type: "POST",
				url: "<?= site_url('event/update_userstatus')?>"+'/'+event_id+'/'+user_id,
				success: function(data){
					$('#loading').hide();
				}
				});
}
</script>
<script>
jQuery(document).ready(function () {
	$('input#date-pick').simpleDatepicker({ startdate: <?php echo $sdt; ?>, enddate: <?php echo $edt; ?>, chosendate:new Date('2000-01-01')});
});
</script>
<div id="message"></div>
<form  class="mainForm clear" id="formEditor"  action="<?php echo site_url('event/update_user_status')?>" method="post" enctype="multipart/form-data"  >
<ul class="form city-form">
<li><label for="selBulkActions">Current Event: </label> 
<?php foreach($events as $row) { ?>
<?php $id=$row->id ;?>
<input type="text" style="background:#CCCCCC; width:260px;" disabled="disabled" value="<?=$row->name;?>" />
<?php } ?>
</li>
 <label for="txtName">Users Attendence Status :</label>
<div  style="height:100px; overflow:scroll; border:1px solid #999; padding:5px; width: 250px; overflow-x:hidden; float:left;">
<li>
       
		<?php
		if(count($attended_users) > 0){ 
		foreach($attended_users as $row){?>
         </li>
         <li>
         <label for="txtName"><?php echo $row->user_name; ?></label>
         <input type="hidden" value="<?php echo $id ?>" name="event" id="event" />
         <input type="hidden" value="<?php echo $row->user_id ?>" name="user_id" id="user_id" />
         <input type="checkbox" <?php if($row->present == 1){ ?> checked="checked" <?php } ?>  id="users" name="user" 
         onClick="javascript:update_userstatus('<?php echo $id?>','<?php echo $row->user_id?>');"  /> 
		<?php } }else{ ?>
        No Members in this event 
        <?php } ?>
			</li></div>	
            <li>
  
 </ul>
 <ul>
<li>
<?php if(count($attended_users) > 0){ ?>
<input  id="btnSubmit" class="button green" type="submit" value="+ Update Event" />
<a href="<?php echo site_url('event/index')?>" class="sec-action">Cancel</a>
<?php } ?>
</li>
</ul>
</form>


