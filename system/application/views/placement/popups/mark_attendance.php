<?php $this->load->view('layout/thickbox_header'); ?>

<script type="text/javascript">
$(function () {
	$("#event_id").change(getfeedback);
});

function getfeedback() {
   
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('placement/get_student')?>/"+this.value,
		success: function(msg){
			$('#feedback').html(msg);
			//$('#kids_list').html("");
		}
	});
}

</script>

<h2>Mark Attendance</h2>
<script>
function validate(id)
{
	if(document.getElementById("event_id").value == '')
          {		
              alert("Event Name Missing.");
			  document.getElementById('event_id').focus();
              return false;
          }
          
//          if(document.getElementById("date-pick").value == '')
//          {		
//              alert("Date Missing.");
//			  document.getElementById('date-pick').focus();
//              return false;
//          }
       
}
</script>
<div id="message"></div>
<div style="float:left; margin-top:10px;">
<form id="formEditor" class="mainForm clear" action="<?= site_url('placement/markattendance')?>" method="post" onsubmit="return validate();" style="width:355px;">
<fieldset class="clear">
<ul class="form city-form">
    <li>
<label for="event_id">Event Name : </label>
<select name="event_id" id="event_id">
    <option value="">Select Event</option>
    <?php foreach($event->result_array() as $row) {  ?>
   <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
    <?php } ?>
</select>
    </li>

<div id="feedback"></div>

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