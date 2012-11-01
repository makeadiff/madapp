<?php $this->load->view('layout/thickbox_header'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/calender.css" />
<script src="<?php echo base_url()?>js/cal.js"></script>

<?php
$edt=date('Y');
$sdt=date('Y')-20;
?>
<script>
jQuery(document).ready(function () {
	$('input#date-pick').simpleDatepicker({ startdate: <?php echo $sdt; ?>, enddate: <?php echo $edt; ?>, chosendate:new Date('2000-01-01')});
});
</script>
<script type="text/javascript">
$(function () {
	$("#corporate").change(getcorporate);
});

function getcorporate() {
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('placement/get_corporate')?>/"+this.value,
		success: function(msg){
			$('#corp').html(msg);
			//$('#kids_list').html("");
		}
	});
}

</script>

<h2>Add New Event</h2>
<script>
function validate(id)
{
	if(document.getElementById("eventname").value == '')
          {		
              alert("Event Name Missing.");
			  document.getElementById('eventname').focus();
              return false;
          }
          
          if(document.getElementById("date-pick").value == '')
          {		
              alert("Date Missing.");
			  document.getElementById('date-pick').focus();
              return false;
          }
}
</script>
<div id="message"></div>
<div style="float:left; margin-top:10px;">
<form id="formEditor" class="mainForm clear" action="<?= site_url('placement/addevent_name')?>" method="post" onsubmit="return validate();" style="width:355px;">
<fieldset class="clear">
<ul class="form city-form">
    <li>
<label for="txtName">Event Name : </label>
<input id="eventname" name="eventname" type="text" />
    </li>
    
<li>
<label for="txtName">Started On: </label>
<input name="date-pick" class="date-pick" id="date-pick" type="text">
</li>

<li>
<label for="activity_id">Placement Activity: </label>
<select name="activity_id" id="activity_id">
    <option value="">Select Activity</option>
    <?php foreach($activity->result_array() as $row) {  ?>
   <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
    <?php } ?>
</select>
</li>

<li>
<label for="corporate">Corporate Partner: </label>
<select name="corporate" id="corporate">
    <option value="0">Select Corporate</option>
     <option value="1">Yes</option>
      <option value="2">No</option>
   
</select>
</li>

<div id="corp"></div>

</ul>
<ul>
<li>
<input id="btnSubmit"  class="button green" type="submit" value="Submit"  />
<a href="<?php echo site_url('placement/manageevents') ?>" class="cancel-button">Cancel</a>
</li>
</ul>

</fieldset>
</form>
</div>

<?php $this->load->view('layout/thickbox_footer'); ?>